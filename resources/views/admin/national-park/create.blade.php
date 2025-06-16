@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New National Park</h1>
    <form id="createNationalParkForm">
        <div class="form-group">
            <label for="category_id">Category<span class="text-danger">*</span></label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name ?? $category->id }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="theme_id">Theme<span class="text-danger">*</span></label>
            <select class="form-control" id="theme_id" name="theme_id" required>
                <option value="">Select Theme</option>
                @foreach($themes as $theme)
                <option value="{{ $theme->id }}">{{ $theme->name ?? $theme->id }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name">Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" required maxlength="255">
        </div>
        <div class="form-group">
            <label for="slug">Slug<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="slug" name="slug" required maxlength="255">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Create</button>
        <a href="{{ route('national-parks.index') }}" class="btn btn-secondary mt-2">Cancel</a>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/national_parks.js') }}"></script>
@endsection
