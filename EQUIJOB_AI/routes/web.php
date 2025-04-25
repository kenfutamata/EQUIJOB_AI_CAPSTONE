<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Code\Test;

//Landing Page
Route::get('/',[LandingPageController::class, 'ViewLandingPage'])->name('landing-page');
Route::get('/EQUIJOB/Sign-in',[LandingPageController::class, 'ViewSignInPage'])->name('sign-in');

