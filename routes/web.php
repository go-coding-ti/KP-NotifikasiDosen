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
Route::get('/', 'admin\HomeController@Home')->name('admin-home');
Route::get('/login','auth\AuthController@loginpage')->name('user-login');
Route::post('/login/submit','auth\AuthController@login')->name('user-login-submit');

Route::prefix('admin')->group(function () {
    //admin
    Route::get('/dashboard', 'admin\HomeController@Home')->name('admin-home');
    Route::get('/logout','auth\AuthController@logout')->name('admin-logout');
    Route::get('/', 'admin\HomeController@Home')->name('admin-home');

    //botsetting
    Route::get('/botsetting/listsetting', 'admin\BotSettingController@listsetting')->name('botsetting-index');
    Route::get('/botsetting/create', 'admin\BotSettingController@createSetting')->name('botsetting-create');
    Route::post('/botsetting/store', 'admin\BotSettingController@storeSetting')->name('botsetting-store');
    Route::get('/botsetting/listsetting/detail/{id}', 'admin\BotSettingController@showSetting')->name('botsetting-show');
    Route::post('/botsetting/listsetting/update/{id}', 'admin\BotSettingController@updateSetting')->name('botsetting-update');
    
    // Setting Role Pegawai
    Route::get('/role/setting-index', 'admin\RoleSettingController@index')->name('roleset-index');
    Route::post('/role/setting-add', 'admin\RoleSettingController@store')->name('roleset-store');


    //operasi dosen
    Route::get('/datauser/dosen/listdosen', 'admin\ValidatorController@index')->name('dosen-page');
    Route::get('/datauser/dosen/create', 'admin\ValidatorController@create')->name('dosen-createpage');
    Route::post('/datauser/dosen/store', 'admin\ValidatorController@store')->name('dosen-store');
    Route::get('/datauser/dosen/detaildosen/{id}', 'admin\ValidatorController@detailDosen')->name('dosen-detail');
    Route::post('/datauser/dosen/updatedetaildosen/{id}', 'admin\ValidatorController@update')->name('dosen-detail-update');
    Route::get('/datauser/dosen/{id}/delete', 'admin\ValidatorController@destroy')->name('dosen-delete');

    Route::post('/datauser/dosen/masa-studi/create/{id}','admin\ValidatorController@attCreate')->name('admin-masa-studi-create');
    Route::get('/datauser/dosen/masa-studi/delete/{id}','admin\ValidatorController@attDestroy')->name('admin-masa-studi-delete');

    Route::get('/datauser/dosen/import/dosen','import\ImportDosenController@importDosen')->name('admin-import-dosen');
    Route::post('/datauser/dosen/import/dosen/submit','import\ImportDosenController@storeImportDosen')->name('import-dosen');
    Route::post('/submit/import/dosen','import\ImportDosenController@storeDosenFromImport')->name('upload-import-dosen');
    Route::get('/datauser/dosen/export/dosen','export\ExportDosenController@exportDosen')->name('admin-export-dosen');
    Route::post('/datauser/dosen/export/dosen/submit','export\ExportDosenController@excel')->name('admin-export-dosen');

    Route::get('/contoh/excel', 'import\ImportDosenController@downloadExcelDosen')->name('download-contoh-import-dosen');
    Route::get('/file/karpeg/{file}', 'admin\ValidatorController@downloadKarpeg')->name('download-karpeg-dosen');
    Route::get('/file/kariskarsu/{file}', 'admin\ValidatorController@downloadKariskarsu')->name('download-kariskarsu-dosen');
    Route::get('/file/npwp/{file}', 'admin\ValidatorController@downloadNpwp')->name('download-npwp-dosen');
    Route::get('/file/ktp/{file}', 'admin\ValidatorController@downloadKtp')->name('download-ktp-dosen');
    
    //operasi pegawai

    //operasi master data
    
        //operasi fakultas
        Route::get('/masterdata/fakultas/listfakultas', 'admin\ValidatorController@indexFakultas')->name('masterdata-fakultas-index');
        Route::get('/masterdata/fakultas/create', 'admin\ValidatorController@createFakultas')->name('masterdata-fakultas-create');
        Route::post('/masterdata/fakultas/store', 'admin\ValidatorController@storeFakultas')->name('masterdata-fakultas-store');
        Route::get('/masterdata/fakultas/detail/{id}', 'admin\ValidatorController@showFakultas')->name('masterdata-fakultas-show');
        Route::post('/masterdata/fakultas/update/{id}', 'admin\ValidatorController@updateFakultas')->name('masterdata-fakultas-update');
        Route::get('/masterdata/fakultas/delete/{id}', 'admin\ValidatorController@deleteFakultas')->name('masterdata-fakultas-delete');
        //operasi jabatan fungsional
        Route::get('/masterdata/jabatanfungsional/listjabatanfungsional', 'admin\ValidatorController@indexJF')->name('masterdata-jabatanfungsional-index');
        Route::get('/masterdata/jabatanfungsional/create', 'admin\ValidatorController@createJF')->name('masterdata-jabatanfungsional-create');
        Route::post('/masterdata/jabatanfungsional/store', 'admin\ValidatorController@storeJF')->name('masterdata-jabatanfungsional-store');
        Route::get('/masterdata/jabatanfungsional/detail/{id}', 'admin\ValidatorController@showJF')->name('masterdata-jabatanfungsional-show');
        Route::post('/masterdata/jabatanfungsional/update/{id}', 'admin\ValidatorController@updateJF')->name('masterdata-jabatanfungsional-update');
        Route::get('/masterdata/jabatanfungsional/delete/{id}', 'admin\ValidatorController@deleteJF')->name('masterdata-jabatanfungsional-delete');
        //operasi kategori penelitian
        Route::get('/masterdata/kategoripenelitian/listkategoripenelitian', 'admin\ValidatorController@indexKP')->name('masterdata-kategoripenelitian-index');
        Route::get('/masterdata/kategoripenelitian/create', 'admin\ValidatorController@createKP')->name('masterdata-kategoripenelitian-create');
        Route::post('/masterdata/kategoripenelitian/store', 'admin\ValidatorController@storeKP')->name('masterdata-kategoripenelitian-store');
        Route::get('/masterdata/kategoripenelitian/detail/{id}', 'admin\ValidatorController@showKP')->name('masterdata-kategoripenelitian-show');
        Route::post('/masterdata/kategoripenelitian/update/{id}', 'admin\ValidatorController@updateKP')->name('masterdata-kategoripenelitian-update');
        Route::get('/masterdata/kategoripenelitian/delete/{id}', 'admin\ValidatorController@deleteKP')->name('masterdata-kategoripenelitian-delete');
        //operasi kategori pengabdian
        Route::get('/masterdata/kategoripengabdian/listkategoripengabdian', 'admin\ValidatorController@indexKPeng')->name('masterdata-kategoripengabdian-index');
        Route::get('/masterdata/kategoripengabdian/create', 'admin\ValidatorController@createKPeng')->name('masterdata-kategoripengabdian-create');
        Route::post('/masterdata/kategoripengabdian/store', 'admin\ValidatorController@storeKPeng')->name('masterdata-kategoripengabdian-store');
        Route::get('/masterdata/kategoripengabdian/detail/{id}', 'admin\ValidatorController@showKPeng')->name('masterdata-kategoripengabdian-show');
        Route::post('/masterdata/kategoripengabdian/update/{id}', 'admin\ValidatorController@updateKPeng')->name('masterdata-kategoripengabdian-update');
        Route::get('/masterdata/kategoripengabdian/delete/{id}', 'admin\ValidatorController@deleteKPeng')->name('masterdata-kategoripengabdian-delete');
        //operasi pangkat pns
        Route::get('/masterdata/pangkatpns/listpangkatpns', 'admin\ValidatorController@indexPP')->name('masterdata-pangkatpns-index');
        Route::get('/masterdata/pangkatpns/create', 'admin\ValidatorController@createPP')->name('masterdata-pangkatpns-create');
        Route::post('/masterdata/pangkatpns/store', 'admin\ValidatorController@storePP')->name('masterdata-pangkatpns-store');
        Route::get('/masterdata/pangkatpns/detail/{id}', 'admin\ValidatorController@showPP')->name('masterdata-pangkatpns-show');
        Route::post('/masterdata/pangkatpns/update/{id}', 'admin\ValidatorController@updatePP')->name('masterdata-pangkatpns-update');
        Route::get('/masterdata/pangkatpns/delete/{id}', 'admin\ValidatorController@deletePP')->name('masterdata-pangkatpns-delete');
        //operasi prodi
        Route::get('/masterdata/prodi/listprodi', 'admin\ValidatorController@indexProdi')->name('masterdata-prodi-index');
        Route::get('/masterdata/prodi/create', 'admin\ValidatorController@createProdi')->name('masterdata-prodi-create');
        Route::post('/masterdata/prodi/store', 'admin\ValidatorController@storeProdi')->name('masterdata-prodi-store');
        Route::get('/masterdata/prodi/detail/{id}', 'admin\ValidatorController@showProdi')->name('masterdata-prodi-show');
        Route::post('/masterdata/prodi/update/{id}', 'admin\ValidatorController@updateProdi')->name('masterdata-prodi-update');
        Route::get('/masterdata/prodi/delete/{id}', 'admin\ValidatorController@deleteProdi')->name('masterdata-prodi-delete');
        //operasi status dosen
        Route::get('/masterdata/statusdosen/liststatusdosen', 'admin\ValidatorController@indexSD')->name('masterdata-statusdosen-index');
        Route::get('/masterdata/statusdosen/create', 'admin\ValidatorController@createSD')->name('masterdata-statusdosen-create');
        Route::post('/masterdata/statusdosen/store', 'admin\ValidatorController@storeSD')->name('masterdata-statusdosen-store');
        Route::get('/masterdata/statusdosen/detail/{id}', 'admin\ValidatorController@showSD')->name('masterdata-statusdosen-show');
        Route::post('/masterdata/statusdosen/update/{id}', 'admin\ValidatorController@updateSD')->name('masterdata-statusdosen-update');
        Route::get('/masterdata/statusdosen/delete/{id}', 'admin\ValidatorController@deleteSD')->name('masterdata-statusdosen-delete');
        //operasi status keaktifan
        Route::get('/masterdata/statuskeaktifan/liststatuskeaktifan', 'admin\ValidatorController@indexSK')->name('masterdata-statuskeaktifan-index');
        Route::get('/masterdata/statuskeaktifan/create', 'admin\ValidatorController@createSK')->name('masterdata-statuskeaktifan-create');
        Route::post('/masterdata/statuskeaktifan/store', 'admin\ValidatorController@storeSK')->name('masterdata-statuskeaktifan-store');
        Route::get('/masterdata/statuskeaktifan/detail/{id}', 'admin\ValidatorController@showSK')->name('masterdata-statuskeaktifan-show');
        Route::post('/masterdata/statuskeaktifan/update/{id}', 'admin\ValidatorController@updateSK')->name('masterdata-statuskeaktifan-update');
        Route::get('/masterdata/statuskeaktifan/delete/{id}', 'admin\ValidatorController@deleteSK')->name('masterdata-statuskeaktifan-delete');
        //operasi status kepegawaian
        Route::get('/masterdata/statuskepegawaian/liststatuskepegawaian', 'admin\ValidatorController@indexSKp')->name('masterdata-statuskepegawaian-index');
        Route::get('/masterdata/statuskepegawaian/create', 'admin\ValidatorController@createSKp')->name('masterdata-statuskepegawaian-create');
        Route::post('/masterdata/statuskepegawaian/store', 'admin\ValidatorController@storeSKp')->name('masterdata-statuskepegawaian-store');
        Route::get('/masterdata/statuskepegawaian/detail/{id}', 'admin\ValidatorController@showSKp')->name('masterdata-statuskepegawaian-show');
        Route::post('/masterdata/statuskepegawaian/update/{id}', 'admin\ValidatorController@updateSKp')->name('masterdata-statuskepegawaian-update');
        Route::get('/masterdata/statuskepegawaian/delete/{id}', 'admin\ValidatorController@deleteSKp')->name('masterdata-statuskepegawaian-delete');
        //operasi tahun ajaran
        Route::get('/masterdata/tahunajaran/listtahunajaran', 'admin\ValidatorController@indexTAj')->name('masterdata-tahunajaran-index');
        Route::get('/masterdata/tahunajaran/create', 'admin\ValidatorController@createTAj')->name('masterdata-tahunajaran-create');
        Route::post('/masterdata/tahunajaran/store', 'admin\ValidatorController@storeTAj')->name('masterdata-tahunajaran-store');
        Route::get('/masterdata/tahunajaran/detail/{id}', 'admin\ValidatorController@showTAj')->name('masterdata-tahunajaran-show');
        Route::post('/masterdata/tahunajaran/update/{id}', 'admin\ValidatorController@updateTAj')->name('masterdata-tahunajaran-update');
        Route::get('/masterdata/tahunajaran/delete/{id}', 'admin\ValidatorController@deleteTAj')->name('masterdata-tahunajaran-delete');

    Route::get('/pengabdian', 'admin\PengabdianController@index')->name('pengabdian-list');
    Route::get('/kompetensi', 'admin\KompetensiController@index')->name('kompetensi-list');
    
    // Operasi Penelitian
    Route::get('/penelitian', 'admin\PenelitianController@index')->name('penelitian-list');
    route::get('/penelitian/detail/{id}', 'admin\PenelitianController@detail')->name('penelitian-detail');
    route::post('/penelitian/detail/update/{id}', 'admin\PenelitianController@update')->name('penelitian-update');
    route::get('/penelitian/delete/{id}', 'admin\penelitianController@destroy')->name('penelitian-delete');
    Route::get('/import/penelitian-show', 'import\ImportPenelitianController@show')->name('show-import-penelitian');
    Route::Post('/import/penelitian-review', 'import\ImportPenelitianController@view')->name('show-review-penelitian');
    Route::Post('/import/penelitian-save', 'import\ImportPenelitianController@save')->name('save-penelitian');

    // Report
    Route::get('/report', 'admin\ReportController@reportPage')->name('report-index');
    // Route::get('/botsetting/create', 'admin\BotSettingController@createSetting')->name('botsetting-create');
    // Route::post('/botsetting/store', 'admin\BotSettingController@storeSetting')->name('botsetting-store');
    // Route::get('/botsetting/listsetting/detail/{id}', 'admin\BotSettingController@showSetting')->name('botsetting-show');
    // Route::post('/botsetting/listsetting/update/{id}', 'admin\BotSettingController@updateSetting')->name('botsetting-update');
});

Route::prefix('user')->group(function () {
    Route::get('/changepass', 'user\HomeController@changepass')->name('user-changepass');
    Route::post('/store/changepass', 'user\HomeController@storechangepass')->name('user-store-changepass');
    Route::get('/dashboard', 'user\HomeController@Home')->name('user-home');
    Route::get('/logout','auth\AuthController@logout')->name('user-logout');
    Route::get('/datadiridosen','user\HomeController@dataDosen')->name('user-data');
    Route::post('/datadiridosen/update/{id}', 'user\HomeController@updatedataDosen')->name('user-data-update');
    Route::get('/masa-studi/index','user\MasaStudiController@index')->name('masa-studi-index');
    Route::post('/masa-studi/create','user\MasaStudiController@create')->name('masa-studi-create');
    Route::get('/masa-studi/delete/{id}','user\MasaStudiController@destroy')->name('masa-studi-delete');
    Route::get('/file/karpeg/{file}', 'user\HomeController@downloadKarpeg')->name('user-download-karpeg-dosen');
    Route::get('/file/kariskarsu/{file}', 'user\HomeController@downloadKariskarsu')->name('user-download-kariskarsu-dosen');
    Route::get('/file/npwp/{file}', 'user\HomeController@downloadNpwp')->name('user-download-npwp-dosen');
    Route::get('/file/ktp/{file}', 'user\HomeController@downloadKtp')->name('user-download-ktp-dosen');
    Route::get('/penelitian', 'user\HomeController@penelitian')->name('user-penelitian');
    Route::get('/penelitian-detail/{id}', 'user\HomeController@penelitianDetail')->name('user-penelitian-detail');
    Route::post('/penelitian-update/{id}', 'user\HomeController@penelitianUpdate')->name('user-penelitian-update');
    Route::get('/penelitian-delete/{id}', 'user\HomeController@penelitianDestroy')->name('user-penelitian-delete');
});
