<?php

use App\Http\Controllers\UserRequestController;
use App\Http\Controllers\SessionFormController;
use App\Http\Controllers\SubmitRequestController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthNewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FirstAppealController;
use App\Http\Controllers\SecondAppealController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PageHitController;


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
    return view('homePage');
    // return view('BreakDown');
});

Route::get('/page-hits', [PageHitController::class, 'index']);
Route::post('/page-hits', [PageHitController::class, 'store']);
Auth::routes();

Route::get('/test-500', function () {
    throw new Exception('Intentional test error');
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('checkSessionValues')->group(function () {

// First form
Route::get('/profile', [userRequestController::class, 'preForm'])->name('profile');
Route::post('/profile', [userRequestController::class, 'storePreForm'])->name('store-profile');
Route::get('/pending-records-list', [userRequestController::class, 'pendingList'])->name('pending-records-list');
Route::get('/pending-edit-view/{id}', [userRequestController::class, 'update'])->name('pending-edit-view');
Route::put('/submiteditform/{id}', [userRequestController::class, 'editFormSubmit'])->name('update.form');


Route::get('/submitreqform',[userRequestController::class,'newForm'])->name('submitreqform');;
Route::post('/submitreqform',[userRequestController::class,'newFormSubmit'])->name('validate.form');

Route::middleware(['throttle:captcha_refresh'])->group(function () {
    Route::get('/security-code/refresh', [CaptchaController::class, 'refresh'])->name('security.refresh');
});

Route::middleware(['throttle:captcha_audio'])->group(function () {
    Route::get('/security-code/audio/{key}', [CaptchaController::class, 'audio'])->name('security.audio');
});

});

Route::post('/getrequestData',[HomeController::class,'getRequestInfo'])->name('getRegNo');
Route::get('/header', function(){ return view('header');});

Route::get('districtDropdown/{id}',[userRequestController::class,'getDistrict']);
Route::get('talukaDropdown/{id}',[userRequestController::class,'getTaluka']);
Route::get('villageDropdown/{id}',[userRequestController::class,'getVillage']);
// Route::post('/check', [HomeController::class,'inAction'])->name('is_valid_req')->middleware('checkSessionValues');
Route::post('/check', [HomeController::class, 'inAction'])->name('is_valid_req')->middleware('checkSessionValues');

Route::get('subDeptDropdown/{id}',[userRequestController::class,'getSubDept']);
Route::get('officeDropdown/{deptId}/{officeId}',[userRequestController::class,'getOffice']);

Route::get('pioDropdown/{deptId}/{subDept}', [userRequestController::class, 'getPio']);
Route::get('faaDropdown/{deptId}/{subDept}', [userRequestController::class, 'getFaa']);

// Route::post('/sessionForm', [SessionFormController::class, 'saveSession'])->name('sessionForm.save');
Route::get('/boom', [SessionFormController::class, 'destroySession'])->name('sessionForm.destroy');

Route::view('/registerDetails','registerDetails');
Route::view('/submit-req-form.blade','submit-req-form.blade');

Route::view('/RTIMIS/login','officerLogin');
Route::view('/RTIMIS/dashboard','dashboardCommon');
Route::view('/RTIMIS/FAdashboard','dashboardFirstAppeal');
Route::view('/success-payment','success-payment');
Route::get('/application-status',[SubmitRequestController::class, 'applicationStatus'])
->name('applicant.applicationStatus')->middleware('checkSessionValues');


/*Route::get('/login', function () {
    return view('OfficerView.mobileUpdateAlert');
})->name('login');*/
Route::get('/login', [AuthNewController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthNewController::class, 'login'])->name('login');
Route::post('/logout', [AuthNewController::class, 'logout'])->name('logout');

Route::middleware('auth.check')->group(function () {
    Route::get('/RTIMIS/dashboard/{req_type?}', [SubmitRequestController::class,'index']);
    Route::get('/RTIMIS/dashboard', [SubmitRequestController::class, 'index']);
    Route::get('/RTIMIS/FAdashboard/{req_type?}', [SubmitRequestController::class,'faIndex']);
    Route::get('/RTIMIS/dashboardHeader', [SubmitRequestController::class, 'dashboardInfo']);
    Route::get('/RTIMIS/departmentStatusNR', [SubmitRequestController::class, 'departmentStatusNR'])->name('fetchDetailAppData.deptFullData');
    Route::get('/RTIMIS/departmentStatusFA', [SubmitRequestController::class, 'departmentStatusFA'])->name('fetchDetailAppData.deptFullData');
    Route::get('/RTIMIS/appStatusNR', [SubmitRequestController::class, 'appStatusNR']);
    Route::get('/RTIMIS/appStatusFA', [SubmitRequestController::class, 'appStatusFA']);
    Route::get('/RTIMIS/statusNR', [SubmitRequestController::class, 'statusNR'])->name('fetchDetailAppData.receivedAppData');
    Route::get('/RTIMIS/statusFA', [SubmitRequestController::class, 'statusFA'])->name('fetchDetailAppData.receivedAppData');
    // Route::get('/dashboardHeader', function(){ return view('dashboardHeader');});
    Route::get('/RTIMIS/submit_req/{id}', [SubmitRequestController::class, 'submitRequestDetailedView'])->name('detailedFormRoute');
    Route::get('/RTIMIS/closed_req/{id}', [SubmitRequestController::class, 'closedRequestDetailedView'])->name('closedRequest');
    Route::get('/RTIMIS/transForward/{id}', [SubmitRequestController::class, 'transfRequestDetailedView'])->name('transfRequest');
    Route::get('/RTIMIS/fa_req/{id}', [SubmitRequestController::class, 'firstAppealDetailedView'])->name('detailedFARequest');
    // Route::view('/RTIMIS/submit_req', function(){ return view('detailedForm');});
    Route::post('/RTIMIS/changeAction',[SubmitRequestController::class,'saveAction'])->name('changeAction.form');
    Route::post('/RTIMIS/dashboard/{new_FA}',[SubmitRequestController::class,'saveStatus'])->name('changeStatus.form');
    Route::post('/RTIMIS/dashboard/{in_process_FA}',[SubmitRequestController::class,'saveStatus'])->name('changeStatus.form');
    Route::post('/RTIMIS/saveFaAction',[SubmitRequestController::class,'saveFaActions'])->name('changeFAForm.form');
    // Route::post('/RTIMIS/updatePass', [AuthNewController::class, 'changePassword'])->name('changePassword');
});

Route::get('/getCommentLists',[FirstAppealController::class,'getCommentList']);

Route::post('/RTIMIS/closed_req/',[SubmitRequestController::class,'editOfficerReply'])->name('updateOfficerReplyFile');
Route::view('/response-payment','payment-response');
Route::view('/CommentLines','CommentLines');

Route::any('payment',[PaymentController::class,'initiatePayment'])->name('payment');
Route::any('payment-verify/{id}',[PaymentController::class,'paymentVerify'])->name('payment-verify');

// Route::get('/dashboard/payment-verify-new', [PaymentController::class, 'handleRedirect'])->name('redirect');
// Route::post('/dashboard/payment-verify-new', [PaymentController::class, 'handleRedirect'])->name('redirect');
Route::any('/dashboard/payment-verify-new', [PaymentController::class, 'handleRedirect'])->name('redirect');

Route::any('payment-return',[PaymentController::class,'paymentReturn'])->name('payment-return');
Route::any('payment-pending',[PaymentController::class,'paymentPending'])->name('payment-pending');

Route::get('get-message',[PaymentController::class,'sms'])->name('get-message');
Route::get('payment-return/{echallanno}',[PaymentController::class,'returnPayment'])->name('payment-return');
Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard')->middleware('checkSessionValues');

Route::controller(FirstAppealController::class)->group(function() {
    Route::any('first-appeal-detail','index')->name('first-appeal-detail')->middleware('checkSessionValues');
    Route::post('first-appeal','firstAppealDetailForm')->name('first-appeal');
    Route::get('first-appeal-boom','firstAppealDestroySession')->name('first-appeal-destroyForm');
    Route::any('first-appeal-create/{id}','firstAppealCreate')->name('first-appeal-create');
    Route::post('firstAppeal-From','firstAppealSave')->name('firstAppealSave');
    Route::get('/checkComment/{id}','firstAppealCommentDetails')->name('detailedComments');
    Route::post('/savePioComment','savePioComment')->name('savepioComment.form');
});

Route::view('maintainance','maintainance');
Route::view('contactUs','Contact-Us');
Route::view('FAQ','FAQ');
Route::view('AboutUs','AboutUs');
Route::view('Policy','Policy');

Route::post('/sessionForm', [SessionFormController::class, 'saveSessionData'])->name('sessionForm.save');
Route::view('/registerDetailsWithOtp','registerDetailsWithOtp');

Route::get('getNodalName/{id}',[SubmitRequestController::class,'getNodalName']);
Route::get('getOfficerName/{role}',[SubmitRequestController::class,'getOfficerName']);

Route::get('/officerDetail',[AuthNewController::class, 'mobileUpdateAlertOnProfile'])->name('officerDetailAlert');
// Dept-Officers Profile Update Form
Route::get('/officerDetailForm', [AuthNewController::class, 'officerDetailForm'])->name('showOfficerDetailForm');
Route::post('/updateOfficerData', [AuthNewController::class, 'updateOfficerData'])->name('updateOfficerData');
Route::post('/officerVerifyOtp', [AuthNewController::class, 'VerifyOfficerOtp'])->name('officerVerifyOtp');
Route::get('/RTIMIS/updatePass', [AuthNewController::class, 'showUpdatePassForm'])->name('changePassword');
Route::post('/changePassword', [AuthNewController::class, 'changePassword'])->name('changePassword');
Route::get('/forgot-password', [AuthNewController::class, 'forgotPass']);
// Route::get('/officerPasswordReset', [AuthNewController::class, 'resetPass']);
Route::post('/verifyResetOTP', [AuthNewController::class, 'verifyResetOtp'])->name('verifyResetOTP');

// Route::view('UpdatePass','UpdatePass');
Route::get('searchDepartment', [UserRequestController::class, 'getDepartment'])->name('department');
Route::post('searchDepartment', [UserRequestController::class, 'showDepartment'])->name('department.show');

Route::get('/detailed_req/{id}', [ApplicantController::class, 'detailedInfo'])->name('commonData');
Route::get('/generate-pdf/{id}', [PdfController::class, 'generateDetailPdf']);
Route::get('/inprocess-pdf/{id}', [PdfController::class, 'generateDetailPdfFa']);
Route::get('/sa-pdf/{id}', [PdfController::class, 'generateDetailPdfSa'])->name('sa.pdf');
Route::post('/generate-notice', [PdfController::class, 'generateNotice']);
Route::get('/search-pdf/{id}', [PdfController::class, 'searchDataPdf']);

Route::get('/detailed_req/{id}', [ApplicantController::class, 'detailedInfo'])->name('commonData');
Route::get('/fa_detailed_req/{id}', [ApplicantController::class, 'faDetailedView'])->name('FAdetailedRequest');
Route::get('/sa_detailed_req/{id}', [ApplicantController::class, 'saDetailedView'])->name('SAdetailedRequest');

Route::get('/feedback',[AuthNewController::class, 'feedbackForm'])->name('feedbackForm');
Route::post('/feedback',[AuthNewController::class, 'saveFeedback'])->name('feedback.submit');
Route::get('/reports',[ReportsController::class, 'getActions'])->name('getActions');

Route::controller(SecondAppealController::class)->group(function()
{
    Route::get('SA-form', 'requestCheck')->name('request-type-form')->middleware('checkSessionValues');
    Route::post('sa-type-handle', 'handleFormType')->name('request-form-handle');

    Route::get('SA-Online', 'index')->name('sa-form1-Form');
    Route::post('SA-Online-Form', 'handleform1')->name('sa-form1.submit');

    Route::get('online-form', 'displayOnlineForm')->name('sa-online-form');
    Route::any('save-second-appeal-on','secondAppealOnlineSave')->name('secondAppealOnlineSave');

    Route::get('offline-form', 'displayOfflineForm')->name('sa-offline-form');
    Route::any('save-second-appeal-off','secondAppealOfflineSave')->name('secondAppealOfflineSave');

    Route::get('/RTIMIS/submit_req_sa/{id}', 'saRequestDetailedView')->name('detailedSaRequest');
    Route::post('/save-sa-action','saveAction')->name('secondAppeal-Action');

    Route::get('/getNotices','getNotices');

});