<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Emprunt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmpruntController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


         $query = Emprunt::with(['user', 'book']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('author', 'like', '%' . $search . '%');
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $emprunts = $query->get();

        return view('emprunts.index', compact('emprunts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::all();
        $users = User::all();
        return view('emprunts.create', compact('books', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $emprunt = new Emprunt;
        $emprunt->user_id = $request->user_id;
        $emprunt->book_id = $request->book_id;
        $emprunt->date_emprunt = $request->date_emprunt;
        $emprunt->date_retour_prevu = $request->date_retour_prevu;

        $emprunt->save();

        $book = Book::find( $emprunt->book_id );
        $book->update(['availability'=> false]);

        return redirect()->route('emprunts.index')->with('success', 'Emprunt créé avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $emprunt = Emprunt::with(['user', 'book'])->findOrFail($id);
        $books = Book::all();
        $users = User::all();

        return view('emprunts.edit', compact('emprunt', 'books', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $emprunt =  Emprunt::find( $id );
        $book_id = $emprunt->book_id;
        $emprunt->delete();

        $book = Book::find( $book_id );
        $book->update(['availability'=> true]);

        return redirect()->route('emprunts.index')->with('success','Emprunt has been deleted!');
    }

    public function myEmprunts()
{
    $user = Auth::user();
    $emprunts = $user->emprunts()->get();
    return view('emprunts.my_emprunts', compact('emprunts'));
}
}
