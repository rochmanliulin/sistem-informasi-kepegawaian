<?php

namespace App\Http\Controllers;

use App\Models\OvertimeSalary;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PayrollExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $overtimeSalaryData = OvertimeSalary::distinct()->pluck('keterangan')->toArray();
    $remark = Payroll::distinct()->pluck('remark')->toArray();

    if ($search) {
      $payroll = Payroll::whereHas('employee', function ($query) use ($search) {
                            $query->where('nama', 'LIKE', "%$search%");
                          })
                          ->orWhere('credited_account', 'LIKE', "%$search%")
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
    // $creditedAccount
    
    if ($codeType == 1) {
      // Disable logging -> menonaktifkan log activity
			activity()->disableLogging();

      $overtimeSalaryData = OvertimeSalary::where('keterangan', $date)->get();

      foreach ($overtimeSalaryData as $data) {
        $nip = $data->nip;
        $existingPayroll = Payroll::where('nip', $nip)->where('remark', $remark)->first();
        
        if ($existingPayroll) {
          // Jika data sudah ada, lakukan pembaruan tanpa mengubah trx_id
          $existingPayroll->update([
            'transfer_type' => $transferType,
            'credited_account' => 0,
            'amount' => $data->total,
            'remark' => $remark
          ]);
        } else {
          // Jika data belum ada, buat data baru dengan trx_id baru
          $id = Payroll::max('id') + 1;
          $trxID = $codeType . $dateCode . str_pad($id, 3, '0', STR_PAD_LEFT);
          Payroll::create([
            'trx_id' => $trxID,
            'transfer_type' => $transferType,
            'credited_account' => 0,
            'amount' => $data->total,
            'nip' => $nip,
            'remark' => $remark
          ]);
        }
      }

      // Enable logging -> mengaktifkan kembali log activity
      activity()->enableLogging();

      // Log activity
			$user = Auth::user();
			activity('Payroll')
				->event('processed')
				->performedOn(new Payroll())
				->withProperties(['attributes' => ['nama' => $user->fullname]])
				->log("processed payroll {$remark}");
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
      // Log activity
      $user = Auth::user();
      activity('Payroll')
        ->event('exported')
        ->performedOn(new Payroll())
        ->withProperties(['attributes' => ['nama' => $user->fullname]])
        ->log("exported payroll {$remark}.xlsx");
      
      return Excel::download(new PayrollExport($remark), 'Payroll ' . $remark . '.xlsx');
    } catch (\Exception $e) {
      dd($e->getMessage());
      return back()->with('error', 'Gagal export file : ' . $e->getMessage());
    }
  }
}