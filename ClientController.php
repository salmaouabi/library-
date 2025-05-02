<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class ClientController extends Controller
{
    public function index()
    {
        // Client sees options to add or delete books
        return view('dashboard.client', [
            'books' => Book::all(),
        ]);
    }
}
