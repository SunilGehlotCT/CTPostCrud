@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>My Posts</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Create Post</a>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Title</th>
                <th>Comments</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $index => $post)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->comments->count() }}</td>
                    <td>{{ $post->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-primary">View Post</a>
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-secondary">Edit Post</a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete Post</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $posts->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this post? This action cannot be undone.');
    }
</script>
@endsection
