@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="box-title">{{ !empty($post)?'Update':'Create' }} Post</h4>

    <hr>

    <form method="post" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row mb-3">
            <div class="col-md-6 ">
                <div class="form-group ">
                    <label class="control-label">Title*</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}">
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group ">
                    <label class="control-label">Content*</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" name="content"  rows="6">{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group ">
                    <label class="control-label">Image</label>
                    <input type="file" name="image"  class="form-control @error('image') is-invalid @enderror" />
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div> 
            <div class="col-md-6">
                @if( !empty($post->image) )
                    <img src="{{ asset('uploads/posts/thumb/'.$post->image) }}" class="img-fluid" alt="{{ $post->title }}">
                @endif
            </div>
        </div>

        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Update Post</button>
        <a href="{{ route('posts.index') }}" class="btn ">Cancel</a>
    </form>

</div>
@endsection
