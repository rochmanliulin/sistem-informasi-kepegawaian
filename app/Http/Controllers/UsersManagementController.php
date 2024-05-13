<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class UsersManagementController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $users = User::orderBy('last_seen', 'DESC')->paginate(10);

    return view('pages.users-management.index', [
      'users' => $users,
    ])->with('page_title', 'Manajemen User');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $nipFromUser = User::pluck('nip')->toArray();

    $employee = Employee::whereNotIn('nip', $nipFromUser)
                          ->selectRaw('nip, nama')
                          ->get();

    return view('pages.users-management.create', [
      'employee' => $employee,
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'nip' => 'required',
      'fullname' => 'required',
      'password' => 'required|min:8',
      'confirm_password' => 'required_with:password|same:password',
      'role' => 'in:admin,editor,employee',
      'email' => 'required|email:dns|unique:users,email',
      'whatsapp_number' => 'nullable|numeric|digits_between:10,13',
      'profile_image' => 'nullable|mimes:png,jpg,jpeg|max:5120'
    ]);
    
    $whatsappNumber = preg_replace('/[^\d]/', '', $request->whatsapp_number);
    
    if (!preg_match('/^62/', $whatsappNumber)) {
        // Ubah format nomor jika awalannya bukan '62'
        $whatsappNumber = '62' . preg_replace('/^\+?0/', '', $whatsappNumber);
    }

    $image = $request->file('profile_image');

    if ($image !== null) {
      $filename = date('Y-m-d') . ' ' . $image->getClientOriginalName();
      $path     = 'user_profile/' . $filename;

      Storage::disk('public')->put($path, file_get_contents($image));
      
      $validated['profile_image'] = $filename;
    }

    $validated['whatsapp_number'] = $whatsappNumber;

    User::create($validated);

    return redirect('/users-management')->with('success', 'User berhasil ditambahkan');
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $user = User::find($id);

    return view('pages.users-management.edit', [
      'user' => $user
    ])->with('page_title', 'Ubah User');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $validated = $request->validate([
      'nip' => 'required',
      'fullname' => 'required',
      'role' => 'required',
      'email' => 'required|email:dns|unique:users,email,'. $id,
      'whatsapp_number' => 'nullable',
      'profile_image' => 'nullable|mimes:png,jpg,jpeg|max:5120'
    ]);
    
    $whatsappNumber = preg_replace('/[^\d]/', '', $request->whatsapp_number);
    
    if (!preg_match('/^62/', $whatsappNumber)) {
        // Ubah format nomor jika awalannya bukan '62'
        $whatsappNumber = '62' . preg_replace('/^\+?0/', '', $whatsappNumber);
    }

    $image = $request->file('profile_image');

    if ($image !== null) {
      $filename = date('Y-m-d') . ' ' . $image->getClientOriginalName();
      $path     = 'user_profile/' . $filename;

      Storage::disk('public')->put($path, file_get_contents($image));
      
      $validated['profile_image'] = $filename;
    }

    $validated['whatsapp_number'] = $whatsappNumber;

    try {
      $user = User::where('id', $id)->first();
      
      // Cek apakah data yang diberikan sama dengan data yang ada dalam database
      if ($user->nip == $validated['nip'] &&
          $user->fullname == $validated['fullname'] &&
          $user->role == $validated['role'] &&
          $user->email == $validated['email'] &&
          $user->whatsapp_number == $validated['whatsapp_number']) {

        return redirect('/users-management')->with('error', 'Tidak Ada Perubahan');
      }

      $user->update($validated);

      return redirect('/users-management')->with('success', 'User berhasil diupdate');
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
      $user = User::where('id', $id)->first();

      $user->delete();

      // strtolower() -> Convert ke lower case
      // ucwords() -> Convert capital case
      return redirect('/users-management')->with('success', 'User ' . ucwords(strtolower($user->fullname)) . ' telah dihapus!');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal menghapus :' . $e->getMessage());
    }
  }
}
