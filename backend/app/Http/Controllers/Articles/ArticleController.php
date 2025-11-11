<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Models\Articles\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $articles = Article::with(['category', 'images', 'comments', 'user'])->latest()->get();
        return response()->json($articles);
    }

    /**
     * Show the form for creating a new resource.
     */
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,pending,published',
         
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $article = Article::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'user_id' => auth()->id(), // replace with user id if no auth yet
            'category_id' => $validated['category_id'],
        ]);

            if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('articles', 'public');
                $article->images()->create(['path' => $path]);
            }
            }

            return response()->json([
            'message' => 'Article created successfully!',
            'article' => $article->load(['category', 'images', 'comments'])
        ], 201);
        
        }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            $article = Article::with(['category', 'images', 'comments', 'user'])->findOrFail($id);
        return response()->json($article);
    }

    /**
     * Show the form for editing the specified resource.
     */
  

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'status' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $article->update($validated);

        // Replace images if new ones uploaded
        if ($request->hasFile('images')) {
            foreach ($article->images as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
            foreach ($request->file('images') as $file) {
                $path = $file->store('articles', 'public');
                $article->images()->create(['path' => $path]);
            }
        }

        return response()->json([
            'message' => 'Article updated successfully!',
            'article' => $article->load(['category', 'images', 'comments'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);
         foreach ($article->images as $img) {
            Storage::disk('public')->delete($img->path);
            $img->delete();
        }

        $article->delete();

        return response()->json(['message' => 'Article deleted successfully']);
    }

}

        //
    