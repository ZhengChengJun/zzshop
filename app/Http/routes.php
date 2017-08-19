<?php
//web端
Route::get('test','View\BookController@test');
Route::get('/login','View\MemberController@toLogin');
Route::get('/register','View\MemberController@toRegister');
Route::get('/category','View\BookController@toCategory');
//路由接收的值可以传给控制器
Route::get('/product/category_id/{cid}','View\BookController@toProduct');
Route::get('/product/{pid}','View\BookController@toProductContent');
//服务端
Route::group(['prefix' => 'service'], function()
{
	Route::get('validateCode/create','service\validateCodeController@create');
	Route::get('validatePhoneCode/create','service\validateCodeController@sendSMS');
	Route::post('register','service\MemberController@register');
	Route::POST('login','service\MemberController@login');
	Route::get('validateEmail','service\MemberController@validateEmail');
	Route::get('category/parent_id/{pid}','service\BookController@getCategoryByParentId');
});


