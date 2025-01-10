<?php
session_start();



require_once ('../core/BaseController.php');
require_once '../core/Router.php';
require_once '../core/Route.php';
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/AdminController.php';
require_once '../app/config/db.php';



$router = new Router();
Route::setRouter($router);



// Define routes
// auth routes 
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'handleRegister']); 
Route::get('/login', [AuthController::class, 'showleLogin']);
Route::post('/login', [AuthController::class, 'handleLogin']);
Route::post('/logout', [AuthController::class, 'logout']);

// admin routers

Route::get('/admin/dashboard', [AdminController::class, 'index']);
Route::get('/admin/users', [AdminController::class, 'handleUsers']);
Route::post('/admin/users/removeUser', [AdminController::class, 'removeUser']);
Route::post('/admin/users/changerStatus', [AdminController::class, 'changerStatus']);
Route::get('/admin/categories', [AdminController::class, 'categories']);
Route::get('/admin/testimonials', [AdminController::class, 'testimonials']);
Route::get('/admin/projects', [AdminController::class, 'showProjects']);
Route::post('/admin/projects/removeproject', [AdminController::class, 'removePoject']);



// end admin routes 

// client Routes
// Route::get('/client/dashboard', [ClientController::class, 'index']);



// Dispatch the request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);



