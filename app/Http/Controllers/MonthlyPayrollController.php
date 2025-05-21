<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\MonthlyPayrollExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyPayrollController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        // Get distinct allowance data for dropdown
        $allowanceData = Allowance::select('keterangan', DB::raw("DATE_FORMAT(created_at, '%d %M %Y') as formatted_date"))
            ->distinct()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return $item->keterangan . ' (' . $item->formatted_date . ')';
            })
            ->toArray();

        // Get all remarks for export dropdown
        $remarks = Allowance::select('keterangan')
            ->distinct()
            ->orderBy('keterangan', 'asc')
            ->pluck('keterangan')
            ->toArray();

        // Get allowance data with employee relation
        $allowances = Allowance::with('employee')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('employee', function ($q) use ($search) {
                    $q->where('nama', 'LIKE', "%$search%")
                      ->orWhere('nip', 'LIKE', "%$search%");
                })
                ->orWhere('amount', 'LIKE', "%$search%")
                ->orWhere('keterangan', 'LIKE', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalAmount = $allowances->sum('amount');

        return view('pages.payroll.index_monthly', [
            'totalAmount' => $totalAmount,
            'data' => $allowanceData,
            'remark' => $remarks,
            'allowances' => $allowances,
            'search' => $search,
            'page_title' => 'Payroll Bulanan',
        ]);
    }

    public function process(Request $request)
    {
        $userId = auth()->id();

        $request->validate([
            'keterangan' => 'required',
            'remark' => 'required'
        ]);

        // Parse format: "Tunjangan (15 March 2025)"
        preg_match('/^(.*)\s\((.*)\)$/', $request->keterangan, $matches);

        if (!isset($matches[1], $matches[2])) {
            return redirect()->back()->withErrors(['Format keterangan tidak valid.']);
        }

        $keterangan = trim($matches[1]);
        try {
            $createdDate = Carbon::createFromFormat('d F Y', trim($matches[2]))->format('Y-m-d');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Format tanggal tidak valid.']);
        }

        $transferType = 'BCA';
        $remark = $request->remark;
        $dateCode = date('ymd');

        $allowanceData = Allowance::with('employee')
            ->where('keterangan', 'like', '%'.$keterangan.'%')
            ->whereDate('created_at', $createdDate)
            ->get();

        if($allowanceData->isEmpty()) {
            \Log::error('No allowance data found for export', [
                'keterangan' => $keterangan,
                'created_date' => $createdDate
            ]);
        }

        $totalAllowances = 0;
        if ($allowanceComponents) {
            $totalAllowances = $allowanceComponents->gaji
                + $allowanceComponents->kos
                + $allowanceComponents->masuk_pagi
                + $allowanceComponents->prestasi
                + $allowanceComponents->komunikasi
                + $allowanceComponents->jabatan
                + $allowanceComponents->lain_lain
                + $allowanceComponents->uang_makan
                + $allowanceComponents->kasbon
                + $allowanceComponents->premi_hadir
                + $allowanceComponents->premi_lembur
                + $allowanceComponents->doa;
        }

        foreach ($allowanceData as $data) {
            $nip = $data->nip;
            $employee = $data->employee;

            $existingPayroll = Payroll::where('nip', $nip)
                ->where('keterangan', $keterangan)
                ->whereDate('created_at', $createdDate)
                ->first();

            $payrollData = [
                'transfer_type' => $transferType,
                'amount' => $data->amount,
                'nip' => $nip,
                'remark' => $remark,
                'keterangan' => $keterangan,
                'created_by' => $userId,
                'updated_by' => $userId
            ];

            if ($employee) {
                $payrollData['credited_account'] = $employee->credited_account;
            }

            if ($existingPayroll) {
                $existingPayroll->update($payrollData);
            } else {
                $id = Payroll::max('id') + 1;
                $trxID = '3' . $dateCode . str_pad($id, 3, '0', STR_PAD_LEFT);
                $payrollData['trx_id'] = $trxID;

                Payroll::create($payrollData);
            }
        }

        return redirect('/monthly-payroll')->with('success', 'Berhasil memproses Payroll Bulanan');
    }

    public function export(Request $request)
    {
        $request->validate([
            'keterangan' => 'required'
        ]);

        $fullKeterangan = $request->keterangan;

        // Handle both formats: "Tunjangan" or "Tunjangan (tanggal)"
        if (preg_match('/^(.*)\s\((.*)\)$/', $fullKeterangan, $matches)) {
            // Format dengan tanggal: "Tunjangan (15 March 2025)"
            $keterangan = trim($matches[1]);
            try {
                $createdDate = Carbon::createFromFormat('d F Y', trim($matches[2]))->format('Y-m-d');
            } catch (\Exception $e) {
                return back()->with('error', 'Format tanggal tidak valid. Harus berupa "d F Y" contoh: 15 March 2025');
            }
        } else {
            // Format tanpa tanggal: "Tunjangan"
            $keterangan = trim($fullKeterangan);
            $createdDate = null;
        }

        try {
            $filename = 'Payroll Bulanan - ' . $keterangan;
            if ($createdDate) {
                $filename .= ' - ' . Carbon::parse($createdDate)->format('d M Y');
            }

            return Excel::download(
                new MonthlyPayrollExport($keterangan, $createdDate),
                $filename . '.xlsx'
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export file: ' . $e->getMessage());
        }
    }
}
