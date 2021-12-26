<?php

use App\Http\Controllers\{
    AuthController,
    CommonController,
    RepController,
};
use App\Http\Controllers\Cas\{
    Cas01Controller,
    Cas02Controller,
    Cas03Controller,
    Cas04Controller,
    Cas05Controller,
    Cas06Controller,
    Cas07Controller,
    Cas08Controller,
    Cas09Controller,
    Cas10Controller,
    Cas11Controller,
    Cas12Controller,
};
use App\Http\Controllers\Com\{
    Com02Controller,
    Com03Controller,
    Com04Controller,
};
use App\Http\Controllers\Inq\{
    Inq01Controller,
    Inq02Controller,
    Inq03Controller,
    Inq04Controller,
    Inq05Controller,
    Inq06Controller,
    Inq07Controller,
    Inq08Controller,
};
use App\Http\Controllers\Mst\{
    Mst01Controller,
    Mst02Controller,
    Mst03Controller,
    Mst04Controller,
    Mst05Controller,
};
use App\Http\Controllers\Pal\{
    Pal01Controller,
    Pal02Controller,
    Pal03Controller,
    Pal04Controller,
    Pal05Controller,
    Pal06Controller,
    Pal08Controller
};
use App\Http\Controllers\Rep\Rep01Controller;
use Illuminate\Support\Facades\{
    Auth, Route
};
use Illuminate\Http\Request;

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

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/handleLogin', [AuthController::class, 'handleLogin'])->name('login.handle');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/case/CAS-030', [Cas03Controller::class, 'cas030'])->name('case.cas-030');
Route::post('/case/handleCas030', [Cas03Controller::class, 'handleCas030'])->name('case.handleCas030');
Route::get('/case/CAS-031/{managementNo?}/{officeCode?}', [Cas03Controller::class, 'cas031'])
    ->where('managementNo', '[a-zA-Z0-9]+')
    ->where('officeCode', '[a-zA-Z0-9]+')
    ->name('case.cas-031');
Route::post('/handleCas031', [Cas03Controller::class, 'handleCas031'])
    ->name('case.handleCas031');

Route::prefix('common')->middleware('auth')->as('common.')->group(function() {
    Route::get('COM-020', [Com02Controller::class, 'com020'])->name('com-020');
    Route::get('COM-020_32', [Com02Controller::class, 'com020_32'])->name('com-020_32');
    Route::get('COM-022/{diffReceiveReportId}', [Com02Controller::class, 'com022'])->where('diffReceiveReportId', '[0-9]+')->name('com022');
    Route::post('handleCom022', [Com02Controller::class, 'handleCom022'])->name('handleCom022');
    Route::get('COM-030', [Com03Controller::class, 'com030'])->name('com-030');
    Route::get('COM-040', [Com04Controller::class, 'com040'])->name('com-040');
});

Route::prefix('palette')->middleware('auth')->as('palette.')->group(function() {
    Route::get('PAL-010', [Pal01Controller::class, 'pal010'])->name('pal-010');
    Route::post('handlePal010', [Pal01Controller::class, 'handlePal010'])->name('handlePal010');
    Route::post('processPalletCase', [Pal01Controller::class, 'processPalletCase'])->name('processPalletCase');
    Route::get('PAL-020', [Pal02Controller::class, 'pal020'])->name('pal-020');
    Route::post('handlePal020', [Pal02Controller::class, 'handlePal020'])->name('handlePal020');
    Route::get('PAL-021/{palletNo}', [Pal02Controller::class, 'pal021'])
    ->where('palletNo', '[a-zA-Z0-9]+')->name('pal-021');
    Route::get('PAL-030', [Pal03Controller::class, 'pal030'])->name('pal-030');
    Route::get('PAL-040', [Pal04Controller::class, 'pal040'])->name('pal-040');
    Route::get('PAL-050', [Pal05Controller::class, 'pal050'])->name('pal-050');
    Route::post('handlePal050', [Pal05Controller::class, 'handlePal050'])->name('handlePal050');
    Route::get('PAL-051/{palTransportId}', [Pal05Controller::class, 'pal051'])
    ->where('id', '[0-9]+')->name('pal-051');
    Route::get('PAL-060', [Pal06Controller::class, 'pal060'])->name('pal-060');
    Route::get('PAL-080', [Pal08Controller::class, 'pal080'])->name('pal-080');
    Route::post('handlePal080', [Pal08Controller::class, 'handlePal080'])->name('handlePal080');
    Route::get('PAL-081/{palletId}', [Pal08Controller::class, 'pal081'])
    ->where('id', '[0-9]+')->name('pal-081');
});

Route::prefix('master')->middleware('auth')->as('master.')->group(function() {
    Route::get('MST-010', [Mst01Controller::class, 'mst010'])->name('mst-010');
    Route::post('handleMst010', [Mst01Controller::class, 'handleMst010'])->name('handleMst010');
    Route::get('MST-011/{id}', [Mst01Controller::class, 'mst011'])->where('id', '[0-9]+')->name('mst-011');
    Route::post('handleMst011/{id}', [Mst01Controller::class, 'handleMst011'])->where('id', '[0-9]+')->name('handleMst011');

    Route::get('MST-020', [Mst02Controller::class, 'mst020'])->name('mst-020');
    Route::post('handleMst020', [Mst02Controller::class, 'handleMst020'])->name('handleMst020');
    Route::get('MST-021', [Mst02Controller::class, 'mst021'])->name('mst-021');
    Route::post('handleMst021', [Mst02Controller::class, 'handleMst021'])->name('handleMst021');
    Route::get('MST-022/{id}', [Mst02Controller::class, 'mst022'])->where('id', '[0-9]+')->name('mst-022');
    Route::post('handleMst022/{id}', [Mst02Controller::class, 'handleMst022'])->where('id', '[0-9]+')->name('handleMst022');

    Route::get('MST-030', [Mst03Controller::class, 'mst030'])->name('mst-030');
    Route::post('handleMst030', [Mst03Controller::class, 'handleMst030'])->name('handleMst030');
    Route::get('MST-031', [Mst03Controller::class, 'mst031'])->name('mst-031');
    Route::post('handleMst031', [Mst03Controller::class, 'handleMst031'])->name('handleMst031');
    Route::post('setUserStatusUrl', [Mst03Controller::class, 'setUserStatusUrl'])->name('setUserStatusUrl');
    Route::get('MST-032/{id}', [Mst03Controller::class, 'mst032'])->where('id', '[0-9]+')->name('mst-032');
    Route::post('handleMst032/{id}', [Mst03Controller::class, 'handleMst032'])->where('id', '[0-9]+')->name('handleMst032');
    Route::get('checkUniqueData', [Mst03Controller::class, 'checkUniqueData'])->name('checkUniqueData');

    Route::get('MST-040', [Mst04Controller::class, 'mst040'])->name('mst-040');
    Route::post('handleMst040', [Mst04Controller::class, 'handleMst040'])->name('handleMst040');
    Route::get('MST-050', [Mst05Controller::class, 'mst050'])->name('mst-050');
    Route::post('handleMst050', [Mst05Controller::class, 'handleMst050'])->name('handleMst050');
});

Route::prefix('case')->middleware('auth')->as('case.')->group(function() {
    Route::get('CAS-010', [Cas01Controller::class, 'cas010'])->name('cas-010');
    Route::post('handleCas010', [Cas01Controller::class, 'handleCas010'])->name('handleCas010');
    Route::get('CAS-011/{id}', [Cas01Controller::class, 'cas011'])->where('id', '[0-9]+')->name('cas-011');
    Route::post('handleCas011', [Cas01Controller::class, 'handleCas011'])->name('handleCas011');
    Route::get('CAS-012/{id}', [Cas01Controller::class, 'cas012'])->where('id', '[0-9]+')->name('cas-012');
    Route::post('handleCas012/{id}', [Cas01Controller::class, 'handleCas012'])->where('id', '[0-9]+')->name('handleCas012');

    Route::get('CAS-020', [Cas02Controller::class, 'cas020'])->name('cas-020');
    Route::post('handleCas020', [Cas02Controller::class, 'handleCas020'])->name('handleCas020');
    Route::post('downloadFileCas020', [Cas02Controller::class, 'downloadFileCas020'])->name('downloadFileCas020');
    Route::get('CAS-021/{id}', [Cas02Controller::class, 'cas021'])->where('id', '[a-zA-Z0-9-]+')->name('cas-021');

    Route::get('CAS-040', [Cas04Controller::class, 'cas040'])->name('cas-040');
    Route::get('CAS-040/{id}', [Cas04Controller::class, 'getPlanCaseById']);
    Route::post('CAS-040/{id}', [Cas04Controller::class, 'updatePlanCase']);
    Route::post('handleCas040', [Cas04Controller::class, 'handleCas040'])->name('handleCas040');
    Route::get('handleRedirectCas080', [Cas04Controller::class, 'handleRedirectCas080'])->name('redirectCas080');

    Route::get('CAS-041/{id}', [Cas04Controller::class, 'cas041'])->where('id', '[0-9]+')->name('cas-041');
    Route::post('handleCas041', [Cas04Controller::class, 'handleCas041'])->name('handleCas041');
    Route::get('CAS-042/{id}', [Cas04Controller::class, 'cas042'])->where('id', '[0-9]+')->name('cas-042');
    Route::post('handleCas042/{id}', [Cas04Controller::class, 'handleCas042'])->where('id', '[0-9]+')->name('handleCas042');

    Route::get('CAS-050', [Cas05Controller::class, 'cas050'])->name('cas-050');
    Route::get('cas050SearchByOfficeCode', [Cas05Controller::class, 'cas050SearchByOfficeCode'])->name('cas050SearchOfficeCode');
    Route::post('cas050UpdateCaseStatus', [Cas05Controller::class, 'cas050UpdateCaseStatus'])->name('cas050UpdateCaseStatus');
    Route::get('CAS-051/{officeCode}', [Cas05Controller::class, 'cas051'])->where('officeCode', '[a-zA-Z0-9]+')->name('cas-051');
    Route::post('handleCas051/{officeCode}', [Cas05Controller::class, 'handleCas051'])->where('officeCode', '[a-zA-Z0-9]+')->name('handleCas051');

    Route::get('CAS-060', [Cas06Controller::class, 'cas060'])->name('cas-060');
    Route::post('CAS-060', [Cas06Controller::class, 'addTempCase']);
    Route::post('handleCas060', [Cas06Controller::class, 'handleCas060'])->name('handleCas060');
    Route::get('CAS-061/{id}/{flag}', [Cas06Controller::class, 'cas061'])->name('cas-061');
    Route::post('cas061CancelCase', [Cas06Controller::class, 'cas061CancelCase'])->name('cas061CancelCase');
    Route::post('handleCas061/{id}/{flag}', [Cas06Controller::class, 'handleCas061'])->name('handleCas061');
    Route::get('CAS-062/{officeCode}', [Cas06Controller::class, 'cas062'])->name('cas-062');
    Route::post('handleCas062/{officeCode}', [Cas06Controller::class, 'handleCas062'])->name('handleCas062');

    Route::get('CAS-070', [Cas07Controller::class, 'cas070'])->name('cas-070');
    Route::get('CAS-071/{caseNo}', [Cas07Controller::class, 'cas071'])->name('cas-071');
    Route::post('handleCas071', [Cas07Controller::class, 'handleCas071'])->name('handle-cas-071');
    Route::post('update-case-status/{caseId}', [Cas07Controller::class, 'setCaseStatus'])->name('update-cas-status-071');
    Route::post('return-case/{caseId}', [Cas07Controller::class, 'returnCase'])->name('return-cas-071');
    Route::get('CAS-072/{caseNo}', [Cas07Controller::class, 'cas072'])->name('cas-072');
    Route::get('CAS-072/report-pdf/{caseId}', [Cas07Controller::class, 'reportPDF'])->name('cas-072-report-pdf');
    Route::get('CAS-073/{caseNo}', [Cas07Controller::class, 'cas073'])->name('cas-073');
    Route::post('handleCas073/{caseId}', [Cas07Controller::class, 'handleCas073'])->name('handle-cas-071');
    Route::get('CAS-074/{caseNo}', [Cas07Controller::class, 'cas074'])->name('cas-074');

    Route::get('CAS-074/{caseNo}', [Cas07Controller::class, 'cas074'])->name('cas-074');

    Route::get('CAS-080', [Cas08Controller::class, 'cas080'])->name('cas-080');
    Route::post('handleCas080', [Cas08Controller::class, 'handleCas080'])->name('handleCas080');
    Route::get('CAS-081/{id}', [Cas08Controller::class, 'cas081'])->where('id', '[a-zA-Z0-9-]+')->name('cas-081');

    Route::get('CAS-090', [Cas09Controller::class, 'cas090'])->name('cas-090');
    Route::get('CAS-091/{id}', [Cas09Controller::class, 'cas091'])->where('id', '[a-zA-Z0-9-]+')->name('cas-091');
    Route::post('handleCas091/{id}', [Cas09Controller::class, 'handleCas091'])->where('id', '[a-zA-Z0-9-]+')->name('handleCas091');

    Route::get('CAS-100', [Cas10Controller::class, 'cas100'])->name('cas-100');
    Route::post('CAS-100', [Cas10Controller::class, 'cas100'])->name('handleCas100');
    Route::get('CAS-101/{id}', [Cas10Controller::class, 'cas101'])->name('cas-101');
    Route::post('handleCas101/{id}', [Cas10Controller::class, 'handleCas101'])->name('handleCas101');

    Route::get('CAS-110', [Cas11Controller::class, 'cas110'])->name('cas-110');
    Route::get('CAS-120', [Cas12Controller::class, 'cas120'])->name('cas-120');
    Route::get('CAS-121/{id}', [Cas12Controller::class, 'cas121'])->name('cas-121');
    Route::post('handleCas121/{id}', [Cas12Controller::class, 'handleCas121'])->name('handleCas121');
});

Route::prefix('recycle')->middleware('auth')->as('recycle.')->group(function() {
    Route::get('REP-010', [Rep01Controller::class, 'rep010'])->name('rep-010');
    Route::post('handleRep010', [Rep01Controller::class, 'handleRep010'])->name('handleRep010');
});

Route::prefix('inquiry')->middleware('auth')->as('inquiry.')->group(function() {
    Route::get('INQ-010', [Inq01Controller::class, 'inq010'])->name('inq-010');
    Route::get('INQ-020', [Inq02Controller::class, 'inq020'])->name('inq-020');
    Route::get('INQ-030', [Inq03Controller::class, 'inq030'])->name('inq-030');
    Route::get('INQ-040', [Inq04Controller::class, 'inq040'])->name('inq-040');
    Route::get('INQ-050', [Inq05Controller::class, 'inq050'])->name('inq-050');
    Route::get('INQ-060', [Inq06Controller::class, 'inq060'])->name('inq-060');
    Route::get('INQ-070', [Inq07Controller::class, 'inq070'])->name('inq-070');
    Route::get('INQ-080', [Inq08Controller::class, 'inq080'])->name('inq-080');
});

Route::prefix('public')->middleware('auth')->as('public.')->group(function() {
    Route::get('getNameOffice', [CommonController::class, 'getOfficeName'])->name('getNameOffice');
    Route::get('resetSearch', [CommonController::class, 'resetSearch'])->name('resetSearch');
    Route::get('test', [CommonController::class, 'index'])->name('test');
    Route::get('test1', [CommonController::class, 'signature'])->name('signature');
});

Route::fallback(function(Request $request) {
    if (Auth::guest()) {
        $pathRoute = $request->path();
        $pathRouteExplode = explode('/', $pathRoute);
        if ($pathRouteExplode[1] === 'CAS-031') {
            return redirect()->route('case.cas-031');
        }
        return redirect()->route('login');
    }
    return view('errors.404');
});
