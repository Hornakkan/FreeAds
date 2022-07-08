@extends('layouts.app')

@section('content')    
    <div class="ad-create">
        <div id="hideMe" class="error-msg">
            {{ session('picture_error') }}
        </div>
        <form action="{{ route('ads.index') }}" method="post" enctype="multipart/form-data">
            @csrf
                <input type="hidden" id="author_id" name="author_id" value="{{ Auth::user()->id }}">
            <p>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="your title here" required>
            </p>
            <p>
                <label for="description">Description</label>
                <textarea name="description" id="description" placeholder="your description here" required></textarea>
            </p>
            <p>
                <label for="category">Category</label>
                <select name="category" id="category">
                    <option value="" selected disabled hidden>Choose a category</option>
                    <option value="sell">Sell</option>
                    <option value="buy">Buy</option>
                    <option value="found">Found</option>
                    <option value="lost">Lost</option>
                </select>
            </p>
            <p>
                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="your location here" required>
            </p>
            <p>
                <label for="price">Price</label>
                <input type="number" id="price" name="price" placeholder="your price here" min=0 step=0.01 required>
            </p>
            <div class="pic-mngt">
                <label class="pic-label" for="pictures">Picture (one or more)</label>
                <p><input type="file" id="pictures" name="pictures[]" multiple required></p>
                <p class='pic-format'>Format supported: jpg, jpeg, png</p>
                <p class='pic-size'>Max size: 2Mo</p>
</div>
            <button class="btn btn-warning" type="submit" id="create" name="create">Place this ad</button>
        </form>
        
    </div>
    <p class="back-lnk"><a href="{{ route('ads.index') }}"><i class="bi bi-box-arrow-in-left"></i> Back</a></p>    
@endsection