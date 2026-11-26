<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('ezraStudio.home');
});

Route::domain('admin.ezramal.com')->group(function () {
    Route::get('/', function () {
        //ver esto aun, es una idea aproximada
        return view('filament.admin.pages.dashboard');
    });
    //Login con Google para admin.
    Route::get('/login/google', function () {
        //ver esto aun, es una idea aproximada
        return view('filament.admin.pages.login');
    });
    
    //Logout para admin.
    Route::get('/logout', function () {
        //ver esto aun, es una idea aproximada
        return view('filament.admin.pages.logout');
    });
    
    


});



