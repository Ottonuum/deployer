<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts
     */
    public function index()
    {
        $blogs = Blog::with('comments')->latest()->get();
        return view('blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new blog post
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * Store a newly created blog post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $blog = new Blog($validated);
        $blog->user_id = Auth::id();
        $blog->save();

        return redirect()->route('blog.show', $blog)
            ->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified blog post
     */
    public function show(Blog $blog)
    {
        return view('blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified blog post
     */
    public function edit(Blog $blog)
    {
        // Only allow editing own posts unless admin
        if (!Auth::user()->isAdmin() && $blog->user_id !== Auth::id()) {
            return redirect()->route('blog.show', $blog)
                ->with('error', 'You can only edit your own posts.');
        }
        return view('blog.edit', compact('blog'));
    }

    /**
     * Update the specified blog post
     */
    public function update(Request $request, Blog $blog)
    {
        // Only allow updating own posts unless admin
        if (!Auth::user()->isAdmin() && $blog->user_id !== Auth::id()) {
            return redirect()->route('blog.show', $blog)
                ->with('error', 'You can only update your own posts.');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $blog->update($validated);

        return redirect()->route('blog.show', $blog)
            ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog post
     */
    public function destroy(Blog $blog)
    {
        // Only allow deleting own posts unless admin
        if (!Auth::user()->isAdmin() && $blog->user_id !== Auth::id()) {
            return redirect()->route('blog.show', $blog)
                ->with('error', 'You can only delete your own posts.');
        }

        $blog->delete();

        return redirect()->route('blog.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    /**
     * Display admin dashboard for blogs and comments
     */
    public function admin()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('blog.index')
                ->with('error', 'Unauthorized access.');
        }
        $blogs = Blog::with('comments')->latest()->get();
        return view('blog.admin', compact('blogs'));
    }

    /**
     * Store a new comment for a blog post
     */
    public function storeComment(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'content' => 'required',
        ]);

        $comment = new Comment();
        $comment->blog_id = $blog->id;
        $comment->content = $validated['content'];
        $comment->author = Auth::user()->name;
        $comment->save();

        return redirect()->route('blog.show', $blog)
            ->with('success', 'Comment added successfully.');
    }
}
