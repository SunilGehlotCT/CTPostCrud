@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <h1>All Posts</h1>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 col-sm-6">
            <form action="{{ route('home') }}" method="GET" class="d-flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts" class="form-control me-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <div class="row">
        @foreach($posts as $post)
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card">
                <img src="{{ $post->image }}" class="card-img-top" alt="{{ $post->title }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{{ \Illuminate\Support\Str::limit($post->content, 100) }}</p>

                    <div class="d-flex justify-content-md-between align-items-center">
                        <small>Posted by <strong>{{ $post->user->name }}</strong></small>
                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-primary">View Post</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $posts->links('vendor.pagination.bootstrap-5') }}
    </div>

</div>
@endsection
