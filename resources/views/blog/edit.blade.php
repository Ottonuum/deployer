<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Blog Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            font-family: inherit;
        }
        textarea {
            min-height: 300px;
            resize: vertical;
        }
        .btn-container {
            margin-top: 30px;
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }
        .btn-secondary {
            background-color: #ccc;
            color: #333;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .error-message {
            color: #dc3545;
            margin-top: 5px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Blog Post</h1>

        @if ($errors->any())
            <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #721c24;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('blog.update', $blog) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $blog->title) }}" required>
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" required>{{ old('content', $blog->content) }}</textarea>
                @error('content')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="is_published">
                    <input type="checkbox" id="is_published" name="is_published" value="1" {{ $blog->is_published ? 'checked' : '' }}>
                    Published
                </label>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Update Post</button>
                <a href="{{ route('blog.show', $blog) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <div style="margin-top: 50px; border-top: 1px solid #ddd; padding-top: 20px;">
            <h2>Delete this blog post</h2>
            <p>Once you delete this blog post, there is no going back. Please be certain.</p>
            <form action="{{ route('blog.destroy', $blog) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this blog post?')">Delete Blog Post</button>
            </form>
        </div>
    </div>
</body>
</html> 