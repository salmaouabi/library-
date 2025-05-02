<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Models\Book;
use App\Models\User;
use App\Models\Emprunt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

// Share the $books variable with all views
View::composer('*', function ($view) {
    $view->with('books', \App\Models\Book::all());
});

Route::get('/', function () {
    return view("users.login");
});

Route::get('/books', function() {
    return Book::all();
});

Route::get('/books/store', function() {
    return Book::create([
        'title' => "Clean Code",
        "author" => "John Doe",
        "publication_date" => '2000-08-25',
        "availability" => true,
        "category" => "Programming"
    ]);
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/connect', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::resource("books", BookController::class)->middleware('auth');
Route::resource("users", UserController::class)->middleware('auth');
Route::resource("emprunts", EmpruntController::class)->middleware('auth');
Route::get('/my_emprunts', [EmpruntController::class, 'myEmprunts']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

Route::resource('loans', LoanController::class);

Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/gestionnaire/dashboard', [ClientController::class, 'index'])->name('gestionnaire.dashboard');
    Route::get('/etudiant/dashboard', function () {
        return view('dashboard.etudiant', [
            'borrowings' => \App\Models\Emprunt::where('user_id', auth()->id())->get(),
            'books' => \App\Models\Book::all(),
        ]);
    })->name('etudiant.dashboard');

    Route::get('/user/dashboard', function () {
        $role = auth()->user()->role;

        if ($role === 'admin') {
            return view('dashboard.admin', [
                'users' => \App\Models\User::all(),
                'books' => \App\Models\Book::all(),
                'borrowings' => \App\Models\Emprunt::all(),
            ]);
        } elseif ($role === 'gestionnaire') {
            return view('dashboard.gestionnaire', [
                'books' => \App\Models\Book::all(),
                'borrowings' => \App\Models\Emprunt::all(),
            ]);
        } elseif ($role === 'etudiant') {
            return view('dashboard.etudiant', [
                'books' => \App\Models\Book::all(),
                'borrowings' => \App\Models\Emprunt::where('user_id', auth()->id())->get(),
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    })->name('user.dashboard');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::get("/logout", function(Request $request) {
    Auth::logout();
 
    $request->session()->invalidate();
 
    $request->session()->regenerateToken();
 
    return redirect('/');

});