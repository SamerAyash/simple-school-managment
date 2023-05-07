<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [Controller::class,'home']);
Route::get('/optimize',[Controller::class,'optimize']);

////////////////////////////// Admin Routes //////////////////////////
Route::get('/admin/login',[AdminAuth::class,'login'])->name('admin.login');
Route::post('/admin/login',[AdminAuth::class,'dologin'])->name('admin.dologin');
Route::get('/admin/forgot/password',[AdminAuth::class,'forgetPassword'])->name('admin.forgotPassword');
Route::post('/admin/forgot/password',[AdminAuth::class,'resetPassword'])->name('admin.resetPassword');
Route::get('/admin/reset/password/{token}',[AdminAuth::class,'resetPasswordWithToken'])->name('admin.resetPasswordToken');
Route::post('/admin/update/{token}',[AdminAuth::class,'updatePassword'])->name('admin.updatePassword');
Route::group(['middleware'=>'isLogin:admin','prefix'=>'/admin/dashboard'],function (){
    /// First admin word means middleware class and second admin word means guard type
    Route::view('/', 'admin.dashboard')->name('admin.home');
    Route::resource('teacher',TeacherController::class);
    Route::resource('student',StudentController::class);
    Route::resource('course',CourseController::class);
    Route::post('/logout',[AdminAuth::class,'logout'])->name('admin.logout');
    //Route::get('/setting',[AdminAuth::class,'setting'])->name('setting');
    //Route::post('/setting/email',[AdminAuth::class,'setting_email'])->name('setting_email');
    //Route::post('/setting/password',[AdminAuth::class,'setting_password'])->name('setting_password');
});
///////////////////////////////////////////////////////////

////////////////////////////// Teacher Routes //////////////////////////
Route::get('/teacher/login',[TeacherController::class,'login'])->name('teacher.login');
Route::post('/teacher/login',[TeacherController::class,'dologin'])->name('teacher.dologin');
Route::group(['middleware'=>'isLogin:teacher','prefix'=>'/teacher/dashboard'],function (){
    /// First teacher word means middleware class and second teacher word means guard type
    Route::get('/', [TeacherController::class,'index_page'])->name('teacher.home');
    Route::get('/grade/{number}', [TeacherController::class,'grade'])->name('teacher.grade.form');
    Route::post('/grade/{number}', [TeacherController::class,'add_grade'])->name('teacher.add_grade');
    Route::post('/logout',[TeacherController::class,'logout'])->name('teacher.logout');
});
///////////////////////////////////////////////////////////


////////////////////////////// Student Routes //////////////////////////
Route::get('/student/login',[StudentController::class,'login'])->name('student.login');
Route::post('/student/login',[StudentController::class,'dologin'])->name('student.dologin');
Route::group(['middleware'=>'isLogin:student','prefix'=>'/student/dashboard'],function (){
    /// First student word means middleware class and second student word means guard type
    Route::get('/', [StudentController::class,'index_page'])->name('student.home');
    Route::post('/logout',[StudentController::class,'logout'])->name('student.logout');
});
///////////////////////////////////////////////////////////





