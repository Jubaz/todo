<?php


// unauthorized routes

// user routes
Route::post('login', 'Api\UserController@login');
Route::post('register', 'Api\UserController@register');

// list => category  routes
Route::get('list', 'Api\CategoryController@showAll');
Route::get('list/{categoryId}/details', 'Api\CategoryController@showById');



// item routes
Route::get('item', 'Api\ItemController@showAll');
Route::get('list/{categoryId}', 'Api\ItemController@showAll');

// authorised route
Route::group(['middleware' => 'auth:api'], function () {

    // user routes
    Route::get('user/details', 'Api\UserController@detailsByToken');

    // list routes
    Route::post('list/create', 'Api\CategoryController@store');
    Route::post('list/{id}/edit', 'Api\CategoryController@updateById');
    Route::delete('list/{id}/delete', 'Api\CategoryController@deleteById');

    // item routes
    Route::post('list/{categoryId}/item', 'Api\ItemController@store');
    Route::post('list/{categoryId}/item/{itemID}/edit', 'Api\ItemController@updateById');
    Route::delete('list/{categoryId}/item/{itemID}/delete', 'Api\ItemController@deleteById');

});
