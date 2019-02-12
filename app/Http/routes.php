<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
|
*/

Route::auth();
Route::get('/home', 'HomeController@index');

Route::resource('user', 'UserController');
Route::resource('usergroup', 'UsergroupController');
Route::resource('customer', 'CustomerController');
Route::resource('product', 'ProductController');

Route::get('/', function () {
    return view('welcome');
});

Route::get('invoice/pdf/{invoice}', 'InvoiceController@pdf')->name('invoice.pdf');
Route::get('invoice/email/{invoice}', 'InvoiceController@sendInvoiceEmail')->name('invoice.email');
Route::get('invoice/set-as-paid/{invoice}', 'InvoiceController@setAsPaid')->name('invoice.set-as-paid');
Route::get('invoice/set-as-pending/{invoice}', 'InvoiceController@setAsPending')->name('invoice.set-as-pending');

Route::resource('invoice', 'InvoiceController');
