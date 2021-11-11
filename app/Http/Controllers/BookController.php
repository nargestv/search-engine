<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::with(['author', 'publisher'])->get();

        return $books;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Cache::put('key', 'value', now()->addMinutes(10));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
    }

    public function searchString(Request $request)
    {
        try {
            $keyword = $request->get('keyword');

            $books = Cache::remember('books', now()->addMinutes(10), function () use ($keyword) {
                $data = array();
                $dataBooks = Book::with(['authors', 'publishers'])->where('title', $keyword)->get();
                $link_image = "88441_89993.jpg";

                foreach ($dataBooks as $dataBook) {
                    $data[] = array(
                        'id' => $dataBook->id,
                        'title' => $dataBook->title,
                        'content' => $dataBook->content,
                        'slug' => $dataBook->slug,
                        'image_name' => ($dataBook->image != "") ? ($link_image) : $dataBook->image_name,
                        'publishers' => $dataBook->publishers()->title,
                        'authors' =>  $dataBook->authors()->name
                    );
                }
                return $data;
            });

        if(!$books){
            $books = Book::with(['authors', 'publishers'])->where('title', $keyword)->get();
        }

        return response()->json([
            'status_code' => 200,
            'message' => 'success',
            'content' => [
                'books' => $books
            ]
        ]);
        
        } catch (\Throwable $tr) {
            return response()->json([
                'status_code' => 500,
                'message' => $tr,
            ]);
        }
    }
}
