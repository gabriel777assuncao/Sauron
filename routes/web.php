<?php

use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Models\User;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/* @var $router Router */
$router = app('router');

Route::get('/', function () {
    if (app()->environment('local')) {

        $user = User::find(1);

        if ($user) {
            auth()->loginUsingId(1);

            return to_route('dashboard');
        } else {
            return 'User with ID 1 does not exist.';
        }
    }

    return view('welcome');
});

Route::get('/dashboard', QuestionController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/question', [QuestionController::class, 'index'])->name('questions.index');
    Route::put('/question/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::get('/question/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::delete('/question/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::post('/question/store', [QuestionController::class, 'store'])->name('questions.store');
    Route::put('/question/publish/{question}', [QuestionController::class, 'publish'])->name('questions.publish');

    Route::post('/question/{question}/like', [LikeController::class, 'like'])->name('questions.like');
    Route::post('/question/{question}/unlike', [LikeController::class, 'unlike'])->name('questions.unlike');
});

require __DIR__.'/auth.php';
