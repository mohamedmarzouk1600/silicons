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

include 'administrators.php';


Route::get('/',function(){
    return redirect('administrators');
});

// Route::get('/form/{event}',function(){
//     return view('form');
// })->name('form');
Route::get('/form/{event}/{email}','\MaxDev\Modules\WebBaseController@getForm')->name('form');

Route::post('/form','\MaxDev\Modules\WebBaseController@form')->name('form.mail');