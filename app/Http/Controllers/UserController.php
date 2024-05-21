<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OvertimeSalary;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $overtimeSalary = OvertimeSalary::where('tgl_terbit', '<=', Carbon::now()->format('Y-m-d'))
                                        ->where('nip', $user->nip)
                                        ->paginate(10);

        return view('pages.user.index', [
            'overtimeSalary' => $overtimeSalary
        ])->with('page_title', 'Gaji Lembur');
    }

    public function download(Request $request)
    {
        $nip = auth()->user()->nip;
        $keterangan = $request->keterangan;
        $overtimeSalary = OvertimeSalary::where('nip', $nip)->where('keterangan', $keterangan)->get();
        $overtimeSalary->tgl_terbit = date('d-m-Y', strtotime($overtimeSalary[0]->tgl_terbit));

        if (!$overtimeSalary) {
            abort(404);
        }

        $pdf = Pdf::loadView('pages.user.pdf', [
            'data' => $overtimeSalary
        ]);

        activity('Report')
            ->event('download')
            ->withProperties(['ip' => $request->ip()])
            ->log("Download Laporan {$keterangan}.pdf by " . Auth::user()->fullname);

        return $pdf->stream('Laporan '.  $keterangan . '.pdf');
    }
}
