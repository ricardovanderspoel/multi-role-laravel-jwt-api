<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;

use App\Http\Middleware\HasCompany;
use App\Http\Middleware\IsCompanyAdmin;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/** Auth Prefix */
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('signup', [AuthController::class, 'signup'])->name('api.auth.signup');
    /** Auth Prefix + Auth Middleware */
    Route::middleware('auth:api')->group(function () {
        Route::get('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    });
});

/** Auth Middleware */
Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthController::class, 'user'])->name('api.auth.user');
    /** Auth Middleware + Companies Prefix */
    Route::prefix('companies')->group(function () {
        Route::post('/add', [CompanyController::class, 'add'])->name('api.companies.add');
    });
    /** Auth Middleware + HasCompany Middleware */
    Route::post('companies/active', [CompanyController::class, 'active'])->name('api.companies.active');
    Route::middleware([HasCompany::class])->group(function () {

        /** Auth Middleware + HasCompany Middleware + Companies Prefix */
        Route::prefix('companies')->group(function () {
            Route::get('/', [CompanyController::class, 'list'])->name('api.companies.list');
            Route::post('/join', [CompanyController::class, 'join'])->name('api.companies.join');
            Route::post('/leave', [CompanyController::class, 'leave'])->name('api.companies.leave');
            /** Auth Middleware + HasCompany Middleware + Is Company Owner Middleware */
            Route::middleware([IsCompanyAdmin::class])->group(function () {
                Route::post('/{id}/update', [CompanyController::class, 'update'])->name('api.companies.update');
                Route::get('/{id}/delete', [CompanyController::class, 'delete'])->name('api.companies.delete');
            });
        });
        Route::prefix('invoices')->group(function () {
            Route::get('/', [InvoiceController::class, 'list'])->name('api.invoices.list');
            Route::post('/create', [InvoiceController::class, 'create'])->name('api.invoices.create');
        });

        Route::prefix('customers')->group(function () {
            Route::get('/', [CustomerController::class, 'list'])->name('api.customers.list');
            Route::post('/add', [CustomerController::class, 'create'])->name('api.customers.add');
            /** Auth Middleware + HasCompany Middleware + Is Company Owner Middleware */
            Route::middleware([IsCompanyAdmin::class])->group(function () {
                Route::post('/{id}/update', [CustomerController::class, 'update'])->name('api.customers.update');
            });
        });
    });
});
