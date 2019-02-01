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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/api/getloot', 'Api\MonsterLootController@post');
Route::get('/api/get7dayloot', 'Api\LootController@get7DayLoot');

Route::get('/settings', 'SettingsController@settingsPage')->name('page.setting');

Route::post('/filter/post', 'Api\MonsterLootController@postFilter')->name('filter.sort');

Route::group(['prefix' => 'users', 'namespace' => 'Users'], function() {
    Route::get('{key}', 'UserController@view')->name('users.view');
    Route::get('{key}/drops/{monsters?}', 'UserController@drops')->name('users.drops');

    Route::post('generate/key', 'UserController@generateKey')->name('generate.key');
});
