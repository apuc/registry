<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'PlotRegistryController@index')->name('plot-registry.index');
Route::post('/check', 'PlotRegistryController@check')->name('plot-registry.check');
