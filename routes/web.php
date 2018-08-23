<?php

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

Route::get('/mlb','MLBController@index');

Route::post('/mlb/rotogrinders/upload','MLBController@fileUploadRotoGrinders');
Route::post('/mlb/numberfire/upload','MLBController@fileUploadNumberFire');
Route::get('/mlb/numberfire/upload', function () {
    return view('mlbuploadnumberfire');
});
Route::get('/mlb/rotogrinders/upload', function () {
    return view('mlbupload');
});

Route::get('/mlb/generatelineup','MLBController@generateLineup');
