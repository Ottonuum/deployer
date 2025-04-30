@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="card-title">{{ $blog->title }}</h1>
                    <p class="text-muted">
                        Posted on {{ $blog->created_at->format('F d, Y') }}
                    </p>
                    <div class="card-text">
                        {!! nl2br(e($blog->description)) !!}
                    </div>
                    
                    @auth
                        @if(Auth::user()->isAdmin() || Auth::id() === $blog->user_id)
                            <div class="mt-4">
                                <a href="{{ route('blog.edit', $blog) }}" class="btn btn-primary">Edit</a>
                                <form action="{{ route('blog.destroy', $blog) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 