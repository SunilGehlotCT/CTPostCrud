@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-4">
            <img src="{{ $post->image }}" class="img-fluid " alt="{{ $post->title }}" />
        </div>
        <div class="col-md-8">
            <h1>{{ $post->title }}</h1>
            <p><small>Posted by {{ $post->user->name }}</small></p>
            <p>{!! nl2br(e($post->content)) !!}</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-4">
            <h4>Add a Comment</h4>
            <form action="{{ route('comments.store', $post->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="10" >{{ old('content') }}</textarea>
                    @error('content')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
            </form>
        </div>
        <div class="col-md-8">
            <h4>Comments ({{$post->comments()->count()}})</h4>
            @empty(!$comments)

                @foreach($comments as $comment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <p>{{ $comment->content }} <br /></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="">
                                    <small class="text-muted">By {{ $comment->user->name }} at {{ $comment->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                                <!-- Only show delete button if it's logged in user -->
                                @if(auth()->id() == $comment->user_id)
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display: inline-block;"  onsubmit="return confirmDelete();">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm ">Delete</button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-center">
                    {{ $comments->links('vendor.pagination.bootstrap-5') }}
                </div>

            @endempty
        </div>
    </div>

</div>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this post? This action cannot be undone.');
    }
</script>
@endsection
