<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprunt;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalUsers = User::count();
        $totalLoans = Emprunt::count();

        // Loan statistics for the chart
        $loanStats = Emprunt::selectRaw('MONTHNAME(created_at) as month, MONTH(created_at) as month_number, COUNT(*) as count')
            ->groupBy('month', 'month_number')
            ->orderBy('month_number')
            ->pluck('count', 'month');

        return view('dashboard', [
            'totalBooks' => $totalBooks,
            'totalUsers' => $totalUsers,
            'totalLoans' => $totalLoans,
            'loanStats' => $loanStats
        ]);
    }
}
