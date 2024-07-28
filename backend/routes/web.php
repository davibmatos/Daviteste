<?php

use App\Http\Controllers\AddressesController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('registrations', RegistrationController::class);
Route::resource('addresses', AddressesController::class);

Route::get('/proxy/viacep/{cep}', function ($cep) {
    $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
    return response()->json($response->json());
});

Route::get('/csrf-cookie', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->middleware('web');

