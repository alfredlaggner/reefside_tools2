<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SmsCampaignController;
use App\Http\Controllers\SmsStatusCallbackController;
use \App\Http\Controllers\ProgressController;
use App\Http\Livewire\Users\Index;
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

Route::resource('campaign', SmsCampaignController::class);

/*Route::middleware(['password.protect'])->group(function () {*/

Route::any('/', [HomeController::class, 'show']);
Route::get('create', [HomeController::class, 'create'])->name('campaign.create');

Route::get('/import', [HomeController::class, 'import']);
Route::get('/export', [HomeController::class, 'export']);
Route::any('send_bulk', [HomeController::class, 'bulkMessaging'])->name('sms.bulk');
Route::get('/progress', [ProgressController::class,'index'])->name('progress');
Route::any('verify_bulk', [HomeController::class, 'bulkVerify'])->name('sms.verify');
Route::get('/check_valid', [HomeController::class, 'validate_phone_numbers']);
Route::post('/', [HomeController::class, 'storePhoneNumber']);
Route::post('/custom', [HomeController::class, 'sendCustomMessage']);
Route::get('/file.upload', [HomeController::class, 'fileUpload'])->name('file.upload');;
Route::post('/file.upload', [HomeController::class, 'fileUploadPost'])->name('file.upload.post');;
Route::post('/file/upload/smslog', [HomeController::class, 'fileUploadSmsLog'])->name('file.upload.smslog');;

Route::post('/incoming/twilio', [LogController::class, 'handleSms']);
Route::get('cancel', [LogController::class, 'cancel_subscription']);
Route::get('/incoming/error', [HomeController::class, 'twilio_error']);
Route::any('/twilio/results', [HomeController::class, 'twilio_log_results'])->name('twilio.result');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'show'])->name('home');

Route::get('smsCampaign/{smsCampaign}/edit', [SmsCampaignController::class, 'edit'])->name('smsCampaign.edit');

Route::any('sms-status-callback', [SmsStatusCallbackController::class,'store'])->name('sms.status.callback');

/*});*/

Auth::routes();
