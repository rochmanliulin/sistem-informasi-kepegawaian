<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FingerprintController;
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\OvertimeSalaryController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\UsersManagementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityController;

// Jika belum terautentikasi
Route::group(['middleware' => 'guest'], function () {
	Route::get('/register', [RegisterController::class, 'create'])->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->name('login');
	Route::post('/login', [LoginController::class, 'authenticate'])->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->name('change.perform');
});

// Jika terautentikasi
Route::group(['middleware' => 'auth'], function () {
	// Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	// Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	// Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
	// Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	// Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
	// Route::get('/{page}', [PageController::class, 'index'])->name('page');
	// Route::get('/employee', [EmployeeController::class, 'index'])->name('employee');
	// Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	// Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');

	Route::get('/', function () {return redirect('/dashboard');});
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');

	// Hanya Editor dan Pegawai
	Route::group(['middleware' => 'can:isEmployeeOrEditor'], function () {
		Route::get('/user/salary', [UserController::class, 'index'])->name('user.index');
		Route::get('user/salary/{keterangan}', [UserController::class, 'download'])->name('user.download');
	});

	// Hanya Editor dan Administrator
	Route::group(['middleware' => 'can:isEditorOrAdmin'], function () {
		Route::resource('/employee', EmployeeController::class);
		Route::post('/employee/import', [EmployeeController::class, 'import'])->name('employee.import');
		Route::get('/employee-export', [EmployeeController::class, 'export'])->name('employee.export');
		Route::resource('/allowance', AllowanceController::class)->except('show');
		Route::post('/allowance/import', [AllowanceController::class, 'import'])->name('allowance.import');
		Route::get('/fingerprint', [FingerprintController::class, 'index'])->name('fingerprint.index');
		Route::post('/fingerprint/import', [FingerprintController::class, 'import'])->name('fingerprint.import');
		// Route::post('/fingerprint/process', [FingerprintController::class, 'process'])->name('fingerprint.process');
		Route::get('/fingerprint/{fingerprint}/edit', [FingerprintController::class, 'edit'])->name('fingerprint.edit');
		Route::patch('/fingerprint/{fingerprint}', [FingerprintController::class, 'update'])->name('fingerprint.update');
		Route::get('/salary/overtime', [OvertimeSalaryController::class, 'index'])->name('overtime-salary.index');
		Route::post('/salary/overtime/process', [OvertimeSalaryController::class, 'process'])->name('overtime-salary.process');
		Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
		Route::post('/payroll/process', [PayrollController::class, 'process'])->name('payroll.process');
		Route::get('/payroll-export', [PayrollController::class, 'export'])->name('payroll.export');
	});

	// Hanya Administrator
	Route::group(['middleware' => 'can:isAdmin'], function () {
		Route::resource('/users-management', UsersManagementController::class);
		Route::get('/log', [ActivityController::class, 'index'])->name('users-activity.index');
		// Route::get('/log/{log}', [EmployeeController::class, 'show'])->name('users-activity.show');
	});
});