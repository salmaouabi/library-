<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprunt;
use App\Models\Book;

class AdminController extends Controller
{
    public function index()
    {
        // Admin sees all borrowings and books
        return view('dashboard.admin', [
            'borrowings' => Emprunt::all(),
            'books' => Book::all(),
        ]);
    }
}
