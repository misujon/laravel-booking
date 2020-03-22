<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function(){
    return view('front.home');
})->name('home');

Route::get('/profile', function(){
    return view('front.profile');
})->name('profile')->middleware('auth');

Route::post('/profile', 'frontend\ProfileController@updateUser')->name('update_profile')->middleware('auth');
Route::post('/profile_picture', 'frontend\ProfileController@profilePicture')->name('profile_picture')->middleware('auth');
Route::get('/bookings', 'frontend\ProfileController@bookingsUser')->name('bookings_user')->middleware('auth');

Route::get('/services', function(){
    return view('front.services');
})->name('all_services');

Route::group(['prefix'=>'admin'], function (){
    Route::get('/', 'backend\Auth\LoginController@showLoginForm')->name('admin_login');
    Route::post('/login', 'backend\Auth\LoginController@login')->name('admin_login_post');
    Route::get('/logout', 'backend\Auth\LoginController@logout')->name('admin_logout');

    Route::get('/dashboard', 'backend\UsersController@dashboard')->name('dashboard');

    Route::get('/profile', function (){
        return view('admin.profile');
    })->name('profile');

    Route::post('/profile', 'backend\UsersController@updateUser')->name('update_admin_profile');
    Route::post('/profile_picture', 'backend\UsersController@profilePicture')->name('admin_profile_picture');

    Route::post('/report', 'backend\UsersController@generate_report')->name('report_generate');

    Route::get('/auser/{id}', 'backend\UsersController@showByID');
    Route::post('/auser_status', 'backend\UsersController@userStatusUpdate');
    Route::post('/auser_update', 'backend\UsersController@auser_update')->name('auser_update');
    Route::post('/auser_add', 'backend\UsersController@auser_add')->name('auser_add');
    Route::get('/auser_delete/{id}', 'backend\UsersController@auser_delete');
    Route::get('/auser_restore/{id}', 'backend\UsersController@auser_restore');
    Route::resource('/users', 'backend\UsersController');
    Route::get('/users_trash', 'backend\UsersController@usersTrash');

    Route::get('/staff/{id}', 'backend\StaffController@showByID');
    Route::post('/staff_status', 'backend\StaffController@staffStatusUpdate');
    Route::post('/staff_update', 'backend\StaffController@staff_update')->name('staff_update');
    Route::post('/staff_add', 'backend\StaffController@staff_add')->name('staff_add');
    Route::get('/staff_delete/{id}', 'backend\StaffController@staff_delete');
    Route::get('/staff_restore/{id}', 'backend\StaffController@staff_restore');
    Route::resource('/staff', 'backend\StaffController');
    Route::get('/staff_trash', 'backend\StaffController@usersTrash');

    Route::resource('/computer', 'backend\ComputerController');
    Route::post('/cservice_add', 'backend\ComputerController@cservice_add')->name('cservice_add');
    Route::post('/cservice_status', 'backend\ComputerController@cserviceStatusUpdate');
    Route::get('/cservice/{id}', 'backend\ComputerController@showByID');
    Route::post('/cservice_update', 'backend\ComputerController@cservice_update')->name('cservice_update');
    Route::get('/cservice_delete/{id}', 'backend\ComputerController@cservice_delete');
    Route::get('/cservice_trash', 'backend\ComputerController@cserviceTrash');
    Route::get('/cservice_restore/{id}', 'backend\ComputerController@cservice_restore');

    Route::resource('/booking', 'backend\ServiceBookingController');
    Route::post('/booking_status', 'backend\ServiceBookingController@bookingStatusUpdate');
    Route::get('/booking_delete/{id}', 'backend\ServiceBookingController@booking_delete');
    Route::get('/booking_trash', 'backend\ServiceBookingController@bookingTrash');
    Route::get('/book_restore/{id}', 'backend\ServiceBookingController@book_restore');
});



Auth::routes(['verify'=>true]);