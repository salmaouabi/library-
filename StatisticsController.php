<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        // Pass dummy data or implement logic to fetch statistics.
        return view('statistics', [
            'loanStats' => [12, 19, 10, 5, 2], // Example data
        ]);
    }
}
