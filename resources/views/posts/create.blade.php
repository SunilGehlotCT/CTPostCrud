@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="box-title">Create Post</h4>

    <hr>

    <form method="post" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row ">
            <div class="col-md-6 mx-auto">
                <div class="form-group ">
                    <label class="control-label">Title*</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group ">
                    <label class="control-label">Content*</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="6">{{ old('content') }}</textarea>
                    @error('content')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label class="control-label">Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" />
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success btn-sm">Create Post</button>
                <a href="{{ route('posts.index') }}" class="btn  btn-sm">Cancel</a>
            </div>                        
        </div>
    </form>

</div>
@endsection
