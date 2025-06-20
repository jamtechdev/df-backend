@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">{{ isset($hiddenWonder) ? 'Edit Hidden Wonder' : 'Create Hidden Wonder' }}</h1>

    <form id="hiddenWonderForm" method="POST" action="{{ isset($hiddenWonder) ? route('admin.hidden-wonders.update', $hiddenWonder->id) : route('admin.hidden-wonders.store') }}">
        @csrf
        @if(isset($hiddenWonder))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="national_park_translation_id" class="form-label">National Park Translation ID</label>
            <input type="number" class="form-control" id="national_park_translation_id" name="national_park_translation_id" value="{{ old('national_park_translation_id', $hiddenWonder->national_park_translation_id ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="section_heading" class="form-label">Section Heading</label>
            <input type="text" class="form-control" id="section_heading" name="section_heading" value="{{ old('section_heading', $hiddenWonder->section_heading ?? '') }}" maxlength="255">
        </div>

        <div class="mb-3">
            <label for="section_title" class="form-label">Section Title</label>
            <input type="text" class="form-control" id="section_title" name="section_title" value="{{ old('section_title', $hiddenWonder->section_title ?? '') }}" maxlength="255">
        </div>

        <div class="mb-3">
            <label for="section_subtitle" class="form-label">Section Subtitle</label>
            <input type="text" class="form-control" id="section_subtitle" name="section_subtitle" value="{{ old('section_subtitle', $hiddenWonder->section_subtitle ?? '') }}" maxlength="255">
        </div>

        <div class="mb-3">
            <label for="icon" class="form-label">Icon</label>
            <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', $hiddenWonder->icon ?? '') }}" maxlength="255">
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $hiddenWonder->title ?? '') }}" maxlength="255" required>
        </div>

        <div class="mb-3">
            <label for="subtitle" class="form-label">Subtitle</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $hiddenWonder->subtitle ?? '') }}" maxlength="255">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $hiddenWonder->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="tip_heading" class="form-label">Tip Heading</label>
            <input type="text" class="form-control" id="tip_heading" name="tip_heading" value="{{ old('tip_heading', $hiddenWonder->tip_heading ?? '') }}" maxlength="255">
        </div>

        <div class="mb-3">
            <label for="tip_text" class="form-label">Tip Text</label>
            <textarea class="form-control" id="tip_text" name="tip_text" rows="3">{{ old('tip_text', $hiddenWonder->tip_text ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="quote" class="form-label">Quote</label>
            <textarea class="form-control" id="quote" name="quote" rows="3">{{ old('quote', $hiddenWonder->quote ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="sort_order" class="form-label">Sort Order</label>
            <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $hiddenWonder->sort_order ?? '') }}">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $hiddenWonder->is_active ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($hiddenWonder) ? 'Update' : 'Create' }}</button>
        <a href="{{ route('admin.hidden-wonders.index') }}" class="btn btn-info">Cancel</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('hiddenWonderForm').addEventListener('submit', function(event) {
    // Basic client-side validation example
    var title = document.getElementById('title').value.trim();
    var nationalParkTranslationId = document.getElementById('national_park_translation_id').value.trim();

    if (!nationalParkTranslationId || isNaN(nationalParkTranslationId) || nationalParkTranslationId <= 0) {
        alert('Please enter a valid National Park Translation ID.');
        event.preventDefault();
        return false;
    }

    if (!title) {
        alert('Title is required.');
        event.preventDefault();
        return false;
    }

    // Additional client-side validation can be added here
});
</script>
@endsection
