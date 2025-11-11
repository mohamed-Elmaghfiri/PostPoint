<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Models\Articles\Article;
use App\Models\Comments\Comment as CommentsComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Article $article)
    {
        $comments = $article->comments()->with('user')->latest()->get();
        return response()->json($comments);
    }
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $article->comments()->create([
            'user_id' => auth()->id(), // or $request->user_id if no auth yet
            'content' => $validated['content'],
        ]);

        return response()->json($comment->load('user'), 201);
    }
     public function destroy($id)
    {
        $comment = CommentsComment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
