<?php

use App\Http\Controllers\Forms\UploadExcelData;
use App\Http\Controllers\API\RowsAPI;

Route::get('/', function () {
    return view('app');
});

Route::group(['prefix' => '/forms', 'as' => 'forms.'], function() {
    Route::get('upload_excel_data', [UploadExcelData::class, 'showForm'])->name('upload_excel_data');
    Route::post('upload_excel_data', [UploadExcelData::class, 'upload'])->name('upload_excel_data');
});

Route::group(['prefix' => '/api', 'as' => 'api.'], function() {
    Route::get('/', [RowsAPI::class, 'showForm']);
});
