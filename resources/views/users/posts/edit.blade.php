@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        
        <div class="mb-3">
            <label for="category" class="form-label d-block fw-bold">
                Category <span class="text-muted fw-normal">(up to 3)</span>
            </label>

            <!-- This command iterates over each category in $all_categories and generates a checkbox input for each category.  -->

            <!-- Each checkbox is associated with a category's name and ID, allowing users to select multiple categories. -->

            @foreach($all_categories as $category)
                <div class="form-check form-check-inline">
                    {{-- 
                        @if(in_array($category->id, $selected_categories)): This checks if the current category's ID ($category->id) is present in the $selected_categories array.   
                        
                        If the category ID is found in $selected_categories, it means the category is selected, and the checkbox will be checked.

                        @else: This part is executed if the category's ID is not found in $selected_categories.

                        In this case, the checkbox will not be checked by default.
                    --}}
                    @if(in_array($category->id, $selected_categories))
                        {{--
                            The `in_array()` function is a PHP function used to check if a value exists in an array. It returns `true` if the value is found in the array, and `false` otherwise.    
                        --}}
                        <input type="checkbox" name="category[]" id="{{ $category->name }}" value="{{ $category->id }}" class="form-check-input" checked>
                    @else
                        <input type="checkbox" name="category[]" id="{{ $category->name }}" value="{{ $category->id }}" class="form-check-input">
                    @endif

                    <label for="{{ $category->name }}" class="form-check-label">{{ $category->name }}</label>
                </div>
            @endforeach

            <!-- Error -->
            @error('category')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control" placeholder="What's on your mind?">{{ old('description', $post->description) }}</textarea>

             <!-- Error -->
             @error('description')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <label for="image" class="form-label fw-bold">Image</label>

                <img src="{{ $post->image }}" alt="{{ $post->id }}" class="img-thumbnail w-100">

                <input type="file" name="image" id="image" class="form-control mt-1" aria-describedby="image-info">

                <div id="image-info" class="form-text">
                    <p class="mb-0">The acceptable formats are jpeg, jpg, png, and gif only.</p>
                    <p class="mt-0">Max file size is 1048kb.</p>
                </div>

                <!-- Error -->
                @error('image')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-warning px-5">
            Save
        </button>
    </form>
@endsection