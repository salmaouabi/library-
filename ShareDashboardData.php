<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Models\Book;

class ShareDashboardData
{
    public function handle($request, Closure $next)
    {
        $books = Book::all(); // Fetch all books
        View::share('books', $books); // Share the books data with all views

        return $next($request);
    }
}
