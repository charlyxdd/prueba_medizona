<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Route::has('login')) {
        return redirect('/login');
    } else {
        return redirect('/dashboard');
    }
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/notes', [\App\Http\Controllers\NotesController::class, 'index'])->middleware(['auth', 'verified'])->name('notes');
Route::put('/notes/{id}', [\App\Http\Controllers\NotesController::class, 'update'])->middleware(['auth', 'verified'])->name('notes.update');
Route::post('/notes', [\App\Http\Controllers\NotesController::class, 'store'])->middleware(['auth', 'verified'])->name('notes.store');
Route::delete('/notes/{id}', [\App\Http\Controllers\NotesController::class, 'destroy'])->middleware(['auth', 'verified'])->name('notes.destroy');


require __DIR__ . '/auth.php';
