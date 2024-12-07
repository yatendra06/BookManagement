<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display the comments for a specific book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Book $book)
    {
        $comments = $book->comments()->get();
        return view('books.show', compact('book', 'comments'));
    }
    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Book $book)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to comment.');
        }
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $book->comments()->create([
            'user_id' => auth()->id(),  // Get the authenticated user ID
            'comment' => $request->comment,
        ]);
        return redirect()->route('books.index', $book)->with('success', 'Comment added!');
    }

}
