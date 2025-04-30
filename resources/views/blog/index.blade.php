@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col">
            <h2>Blog Posts</h2>
        </div>
        <div class="col-auto">
            @auth
                <a href="{{ route('blog.create') }}" class="btn btn-primary">Create New Post</a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.blog') }}" class="btn btn-secondary">Admin Dashboard</a>
                @endif
            @endauth
        </div>
    </div>

    <div class="row">
        @foreach($blogs as $blog)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $blog->title }}</h5>
                        <p class="card-text">{{ Str::limit($blog->description, 150) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route('blog.show', $blog) }}" class="btn btn-primary">Read More</a>
                            <small class="text-muted">
                                {{ $blog->created_at->format('M d, Y') }}
                            </small>
                        </div>

                        <!-- Comments Section -->
                        <div class="comments-section mt-3 border-top pt-3">
                            <h6 class="mb-3">Comments</h6>
                            
                            @foreach($blog->comments as $comment)
                                <div class="comment mb-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $comment->author }}</strong>
                                            <p class="mb-1">{{ $comment->content }}</p>
                                        </div>
                                        <small class="text-muted">{{ $comment->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                </div>
                            @endforeach

                            @auth
                                <form action="{{ route('blog.comments.store', $blog) }}" method="POST" class="mt-3">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="content" placeholder="Add a comment..." required>
                                        <button type="submit" class="btn btn-outline-primary">Comment</button>
                                    </div>
                                </form>
                            @else
                                <p class="text-muted small mt-2">Please <a href="{{ route('login') }}">login</a> to leave a comment.</p>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 