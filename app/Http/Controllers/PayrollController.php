<?php

namespace App\Http\Controllers;

use App\Models\Employee;

use Carbon\Carbon;
use App\Models\Payroll;
use Illuminate\Http\Request;
use App\Exports\PayrollExport;
use App\Models\OvertimeSalary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PayrollController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $overtimeSalaryData = OvertimeSalary::select('keterangan', DB::raw("DATE_FORMAT(tgl_terbit, '%d %M %Y') as formatted_tgl_terbit"))
      ->distinct()
      ->get()
      ->map(function ($item) {
        return $item->keterangan . ' (' . $item->formatted_tgl_terbit . ')';
      })
      ->toArray();
    $remark = Payroll::distinct()->pluck('remark')->toArray();

    if ($search) {
      $payroll = Payroll::whereHas('employee', function ($query) use ($search) {
        $query->where('nama', 'LIKE', "%$search%");
      })
        ->orWhere('amount', 'LIKE', "%$search%")
        ->orWhere('nip', 'LIKE', "%$search%")
        ->orWhere('remark', 'LIKE', "%$search%")
        ->paginate(10);
    } else {
      $payroll = Payroll::paginate(10);
    }

    return view('pages.payroll.index', [
      'data' => $overtimeSalaryData,
      'payroll' => $payroll,
      'remark' => $remark,
      'search' => $search
    ])->with('page_title', 'Payroll');
  }

  public function process(Request $request)
  {
    $userId = auth()->user()->id;

    $request->validate([
      'salary_type' => 'required',
      'remark' => 'required',
      'keterangan' => 'required'
    ]);

    $codeType = $request->salary_type;
    $date = $request->keterangan;
    $dateCode = date('ymd');
    $transferType = 'BCA';
    $remark = $request->remark;

    // Memisahkan keterangan dan tgl_terbit dari $this->info
    preg_match('/^(.*)\s\((.*)\)$/', $date, $matches);
    $keterangan = $matches[1];
    $tgl_terbit = Carbon::createFromFormat('d F Y', $matches[2])->format('Y-m-d');

    if ($codeType == 1) {
      $overtimeSalaryData = OvertimeSalary::where('keterangan', $keterangan)->whereDate('tgl_terbit', $tgl_terbit)->get();

      foreach ($overtimeSalaryData as $data) {
        $nip = $data->nip;
        $existingPayroll = Payroll::where('nip', $nip)->where('remark', $remark)->first();

        if ($existingPayroll) {
          // Jika data sudah ada, lakukan pembaruan tanpa mengubah trx_id
          $existingPayroll->update([
            'transfer_type' => $transferType,
            'amount' => $data->total,
            'remark' => $remark,
            'updated_by' => $userId
          ]);
        } else {
          // Jika data belum ada, buat data baru dengan trx_id baru
          $id = Payroll::max('id') + 1;
          $trxID = $codeType . $dateCode . str_pad($id, 3, '0', STR_PAD_LEFT);
          Payroll::create([
            'trx_id' => $trxID,
            'transfer_type' => $transferType,
            'amount' => $data->total,
            'nip' => $nip,
            'remark' => $remark,
            'created_by' => $userId
          ]);
        }
      }
    }

    return redirect('/payroll')->with('success', 'Berhasil memproses Payroll');
  }

    public function export(Request $request)
    {
        $request->validate([
            'remark' => 'required'
        ]);

        $remark = $request->remark;

        try {
            return Excel::download(new PayrollExport($remark), 'Payroll ' . $remark . '.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export file : ' . $e->getMessage());
        }
    }

    public function monthlyIndex()
    {
        return view('pages.salary.monthly', [
            'page_title' => 'Gaji Bulanan'
        ]);
    }

    public function monthlyProcess(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000',
            'tanggal_terbit' => 'required|date',
            'catatan' => 'nullable|string'
        ]);

        $userId = auth()->user()->id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $tanggalTerbit = $request->tanggal_terbit;
        $catatan = $request->catatan;
        $transferType = 'BCA';

        // Get all employees
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Skip jika gaji pokok tidak ada
            if (empty($employee->gaji_pokok)) {
                continue;
            }

            $existingPayroll = Payroll::where('nip', $employee->nip)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();

            $data = [
                'transfer_type' => $transferType,
                'amount' => $employee->gaji_pokok,
                'nip' => $employee->nip,
                'remark' => 'GAJI BULANAN',
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal_terbit' => $tanggalTerbit,
                'catatan' => $catatan,
                'updated_by' => $userId
            ];

            if ($existingPayroll) {
                $existingPayroll->update($data);
            } else {
                $id = Payroll::max('id') + 1;
                $dateCode = date('ymd');
                $trxID = '2' . $dateCode . str_pad($id, 3, '0', STR_PAD_LEFT);

                $data['trx_id'] = $trxID;
                $data['created_by'] = $userId;

                Payroll::create($data);
            }
        }

        return redirect('/salary/monthly')
            ->with('success', 'Proses gaji bulanan berhasil');
    }

    public function monthlyExport(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000'
        ]);

        try {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $namaBulan = DateTime::createFromFormat('!m', $bulan)->format('F');

            return Excel::download(
                new MonthlySalaryExport($bulan, $tahun),
                'Laporan_Tunjangan_'.$namaBulan.'_'.$tahun.'.xlsx'
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export file: ' . $e->getMessage());
        }
    }
}
