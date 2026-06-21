<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../bootstrap.php';

define('APPNAME', 'Passion Coffee Shop');

session_start();

// Khởi tạo router
$router = new \Bramus\Router\Router();

// --- KHAI BÁO CÁC ROUTE ---

// Đăng nhập

$router->post('/logout', '\App\Controllers\Auth\LoginController@destroy');
$router->get('/register', '\App\Controllers\Auth\RegisterController@create');
$router->post('/register', '\\App\Controllers\Auth\RegisterController@store');
$router->get('/login', '\App\Controllers\Auth\LoginController@create');
$router->post('/login', '\App\Controllers\Auth\LoginController@store');


// Trang chủ
$router->get('/', '\App\Controllers\HomeController@index');

// Tìm kiếm sản phẩm
$router->get('/search', '\App\Controllers\HomeController@search');

// Xem sản phẩm theo loại
$router->get('/category/{id}', '\App\Controllers\HomeController@category');

// Sản phẩm
$router->get('/san-pham', '\App\Controllers\ProductController@index');
$router->get('/san-pham/{id}', '\App\Controllers\ProductController@show');

// Cửa hàng
$router->get('/cua-hang', '\App\Controllers\StoreController@locator');

// Giới thiệu / Về Passion
$router->get('/ve-passion', '\App\Controllers\HomeController@about');

// Liên hệ
$router->get('/lien-he', '\App\Controllers\HomeController@contact'); 

// Giỏ hàng
$router->get('/cart', '\App\Controllers\CartController@index');
$router->get('/cart/add', '\App\Controllers\CartController@add');
$router->get('/cart/add-ajax', '\App\Controllers\CartController@addAjax');
$router->post('/cart/update', '\App\Controllers\CartController@update');
$router->get('/cart/remove', '\App\Controllers\CartController@remove');
$router->get('/cart/clear', '\App\Controllers\CartController@clear');
$router->get('/cart/count', '\App\Controllers\CartController@count');

// Đơn hàng
$router->post('/dat-hang', '\App\Controllers\OrderController@store');

// Admin đăng nhập
$router->get('/admin/login', '\App\Controllers\Auth\LoginController@index');
$router->post('/admin/login', '\App\Controllers\Auth\LoginController@authenticate');
$router->get('/admin/register', '\App\Controllers\Auth\AdminRegisterController@create');
$router->post('/admin/register', '\App\Controllers\Auth\AdminRegisterController@store');

// Admin Dashboard
$router->get('/admin', '\App\Controllers\AdminController@index');
$router->get('/admin/logout', '\App\Controllers\AdminController@logout');
$router->get('/admin/orders', '\App\Controllers\AdminController@orders');
$router->get('/admin/order-detail', '\App\Controllers\AdminController@orderDetail');
$router->post('/admin/update-order-status', '\App\Controllers\AdminController@updateOrderStatus');
$router->get('/admin/statistics', '\App\Controllers\AdminController@statistics');

// --- ĐOẠN BẠN CẦN DI CHUYỂN LÊN ĐÂY ---
// Route hiển thị form
$router->get('/admin/products/create', '\App\Controllers\ProductController@create');
// Route xử lý khi bấm nút Submit (POST)
$router->post('/admin/products/store', '\App\Controllers\ProductController@store');
// Route xử lý cập nhật (POST)
$router->post('/admin/products/update', '\App\Controllers\ProductController@update');

// Route xử lý xóa (POST)
$router->post('/admin/products/delete', '\App\Controllers\ProductController@delete');
// --- CHẠY ROUTER (Luôn phải ở cuối cùng) ---
// Quản lý cửa hàng
$router->get('/admin/stores', '\App\Controllers\StoreController@index');
$router->post('/admin/stores/save', '\App\Controllers\StoreController@save'); // Dùng chung cho Thêm & Sửa
$router->post('/admin/stores/delete', '\App\Controllers\StoreController@delete');
$router->run();