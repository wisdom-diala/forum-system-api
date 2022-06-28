<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group( function () {
	Route::post('login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login']);

	Route::middleware('auth:admin,api-admin')->group( function () {
		Route::resource('categories', App\Http\Controllers\Admin\CategoriesController::class);
		Route::resource('sub_categories', App\Http\Controllers\Admin\SubCategoriesController::class);
	});
});
