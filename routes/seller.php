<?php

use App\Http\Controllers\MultiImageController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Seller\SellerDashboardGuideController;
use App\Http\Controllers\Seller\SellerDashboardNotificationController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerProductBannerController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerProductReviewController;
use App\Http\Controllers\Seller\SellerShopReviewController;
use Illuminate\Support\Facades\Route;

// Seller Authentication
Route::controller(SellerAuthController::class)
     ->prefix('/seller')
     ->name('seller.')
     ->middleware('guest')
     ->group(function () {
         Route::get('/register', 'register')->name('register');
         Route::get('/login', 'login')->name('login');
     });

Route::middleware(['seller', 'user.role:seller'])
     ->prefix('seller')
     ->name('seller.')
     ->group(function () {
         // Dashboard Section
         Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

         // Dashboard Notification
         Route::controller(SellerDashboardNotificationController::class)
              ->prefix('/notifications')
              ->name('notifications.')
              ->group(function () {
                  Route::patch('/{notification_id}/read', 'reatNotification')->name('read');
                  Route::patch('/read-all', 'markAllAsRead')->name('read-all');
              });

         // Seller Products Section
         Route::controller(SellerProductController::class)
              ->prefix('/products')
              ->name('products.')
              ->group(function () {
                  Route::get('/', 'index')->name('index');
                  Route::get('/{product}/details', 'show')->name('show');
                  Route::get('/create', 'create')->name('create');
                  Route::post('/', 'store')->name('store');
                  Route::get('/{product}/edit', 'edit')->name('edit');
                  Route::post('/{product}', 'update')->name('update');
                  Route::delete('/{product}', 'destroy')->name('destroy');
                  Route::get('/trash', 'trash')->name('trash');
                  Route::post('/trash/{trash_product_id}/restore', 'restore')->name('trash.restore');
                  Route::delete('/trash/{trash_product_id}/force-delete', 'forceDelete')->name('trash.force.delete');
                  Route::delete('/trash/permanently-delete', 'permanentlyDelete')->name('trash.permanently.delete');
              });

         Route::delete('products/{product_id}/images/{image_id}', [MultiImageController::class, 'destroy'])->name('image.destroy');

         // Seller Product Banners Section
         Route::controller(SellerProductBannerController::class)
              ->prefix('/product-banners')
              ->name('product-banners.')
              ->group(function () {
                  Route::get('/', 'index')->name('index');
                  Route::get('/create', 'create')->name('create');
                  Route::post('/', 'store')->name('store');
                  Route::get('/{seller_product_banner}/edit', 'edit')->name('edit');
                  Route::post('/{seller_product_banner}', 'update')->name('update');
                  Route::delete('/{seller_product_banner}', 'destroy')->name('destroy');
                  Route::get('/trash', 'trash')->name('trash');
                  Route::post('/trash/{trash_seller_product_banner_id}/restore', 'restore')->name('trash.restore');
                  Route::delete('/trash/{trash_seller_product_banner_id}/force-delete', 'forceDelete')->name('trash.force.delete');
                  Route::delete('/trash/permanently-delete', 'permanentlyDelete')->name('trash.permanently.delete');
                  Route::patch('/{seller_product_banner}/show', 'handleShow')->name('show');
                  Route::patch('/{seller_product_banner}/hide', 'handleHide')->name('hide');
              });

         // Seller Orders
         Route::controller(SellerOrderController::class)
              ->prefix('/orders')
              ->name('orders.')
              ->group(function () {
                  Route::get('/', 'index')->name('index');
                  Route::get('/{order}/details', 'show')->name('show');
                  Route::patch('/{order}', 'update')->name('update');
              });

         // Seller Product Reviews Section
         Route::controller(SellerProductReviewController::class)
              ->prefix('/product-reviews')
              ->name('product-reviews.')
              ->group(function () {
                  Route::get('/', 'index')->name('index');
                  Route::get('/{product_review}/details', 'show')->name('show');
              });

         // Seller Shop Reviews Section
         Route::controller(SellerShopReviewController::class)
              ->prefix('/shop-reviews')
              ->name('shop-reviews.')
              ->group(function () {
                  Route::get('/', 'index')->name('index');
                  Route::get('/{shop_review}/details', 'show')->name('show');
              });

         // Seller Dashboard Guide Section
         Route::get('/guide', [SellerDashboardGuideController::class, 'index'])->name('dashboard.guide');
     });
