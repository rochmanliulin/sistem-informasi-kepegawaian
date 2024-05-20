<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Exports\EmployeesExport;
use App\Imports\EmployeesImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $search = $request->search;
    
    // Jika user menggunakan search filter
    if ($search) {
      $employees = Employee::where('nama', 'LIKE', "%$search%")
                            ->orWhere('jabatan', 'LIKE', "%$search%")
                            ->orWhere('departemen', 'LIKE', "%$search%")
                            ->orWhere('tgl_masuk_kerja', 'LIKE', "%$search%")
                            ->orWhere('status', 'LIKE', "%$search%")
                            ->orderBy('nama')
                            ->paginate(10);
    } else {
      $employees = Employee::orderBy('nama')->paginate(10);
    }

    return view('pages.employee.index', [
      'employee' => $employees,
      'search' => $search
    ]);
  }

  /**
   * Fitur Import Excel
   */
  public function import(Request $request)
  {
    $request->validate([
      'file' => 'required|mimes:xlsx,xls',
    ]);

    // Exception
    try {
      // Create data
      if ($request->hasFile('file')) {
        // Cek extention
        $fileExtension = $request->file('file')->getClientOriginalExtension();

        if (!in_array($fileExtension, ['xlsx', 'xls'])) {
          return back()->with('error', 'Harap unggah file dengan ekstensi .xlsx atau .xls.');
        }

        // Disable logging -> menonaktifkan log activity
				activity()->disableLogging();

        Excel::import(new EmployeesImport, $request->file('file'));
      }

    // Enable logging -> mengaktifkan kembali log activity
    activity()->enableLogging();

    // Log activity
    $user = Auth::user();
    activity('Employee')
      ->event('imported')
      ->withProperties(['attributes' => ['nama' => $user->fullname]])
      ->log("imported employee {$request->file('file')->getClientOriginalName()}");

      return redirect('/employee')->with('success', 'Berhasil upload');
    } catch (\Exception $e) {
      return back()->with('error', 'Format file tidak sesuai!');
    }
  }
  
  /**
   * Fitur Export Excel
   */
  public function export()
  {
    // Carbon adalah library PHP untuk manipulasi tanggal dan waktu
    // now() -> Mengambil waktu saat ini
    try {
      // Log activity
      $user = Auth::user();
      activity('Employee')
        ->event('exported')
        ->withProperties(['attributes' => ['nama' => $user->fullname]])
        ->log('exported employee Data Pegawai ' . Carbon::now()->format('d-M-Y') . '.xlsx');

      return Excel::download(new EmployeesExport, 'Data Pegawai ' . Carbon::now()->format('d-M-Y') . '.xlsx');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal export file : ' . $e->getMessage());
    }
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('pages.employee.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'nip' => 'required|unique:employees',
      'nama' => 'required',
      'credited_account' => 'nullable|max:10',
      'jabatan' => 'nullable',
      'departemen' => 'nullable',
      'tgl_masuk_kerja' => 'nullable|date',
      'status' => 'required'
    ]);

    try {
      // Create data
      Employee::create($validated);

      return redirect('/employee')->with('success', 'Berhasil Ditambahkan');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal menambahkan : ' . $e->getMessage());
    }

  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $employee = Employee::where('nip', $id)->first();

    return view('pages.employee.edit', [
      'employee' => $employee
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $validated = $request->validate([
      'nama' => 'required',
      'credited_account' => 'nullable',
      'jabatan' => 'nullable',
      'departemen' => 'nullable',
      'tgl_masuk_kerja' => 'nullable',
      'status' => 'required'
    ]);

    try {
      $employee = Employee::where("nip", $id)->first();

      // Cek apakah data yang diberikan sama dengan data yang ada dalam database
      if ($employee->nama == $validated['nama'] &&
          $employee->nama == $validated['credited_account'] &&
          $employee->jabatan == $validated['jabatan'] &&
          $employee->departemen == $validated['departemen'] &&
          $employee->tgl_masuk_kerja == $validated['tgl_masuk_kerja'] &&
          $employee->status == $validated['status']) {

        return redirect('/employee')->with('error', 'Tidak Ada Perubahan');
      }

      $employee->update($validated);

      return redirect('/employee')->with('success', 'Berhasil Update');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal mengupdate : ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    try {
      $employee = Employee::where('nip', $id)->first();
      $employee->delete();

      // strtolower() -> Convert ke lower case
      // ucwords() -> Convert capital case
      return back()->with('success', 'Pegawai ' . ucwords(strtolower($employee->nama)) . ' telah dihapus!');
    } catch (\Exception $e) {
      return back()->with('error', "Gagal menghapus : {$e->getMessage()}");
    }
  }
}
