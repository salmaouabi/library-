<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('author', 'like', '%' . $search . '%')
                ->orWhere('category', 'like', '%' . $search . '%');
        }

        $books = $query->get();

        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=> 'required|min:3',
            'category' => 'required',
            'author'=> 'required',
            'publication_date'=> 'required|date',
        ]);
        $book = Book::create([
            'title' => $request->title,
            'category' => $request->category,
            'author' => $request->author,
            'publication_date' => $request->publication_date,
            'availability' => true
        ]);

        $book->save();

        return redirect()->route('books.create')->with('success','Book has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $book = Book::find($id);
        // return view('books.details', ['book'=> $book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::find($id);
        return view('books.edit', ['book'=> $book]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'=> 'required|min:3',
            'category' => 'required',
            'author'=> 'required',
            'publication_date'=> 'required|date',
        ]);

        $book = Book::find($id);
        $book->update([
            'title' => $request->title,
            'category' => $request->category,
            'author' => $request->author,
            'publication_date' => $request->publication_date,
            'availability' => true
        ]);

        $book->save();

        return redirect()->route('books.index')->with('success','Book has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Book::find($id)->delete();
        return redirect('/books')->with('success','Book has been deleted');
    }
}
