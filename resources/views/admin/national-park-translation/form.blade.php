@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-gradient-primary text-dark fw-bold fs-5">
            {{ isset($translation) ? 'Update' : 'Add' }} {{$nationalPark->name}} Details
        </div>
        <div class="card-body p-4">
            {{-- Loader element for showing loading state --}}
            <div id="loader" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 1050; text-align: center; padding-top: 200px; font-size: 1.5rem; font-weight: bold;">
                Loading...
            </div>

            <form id="translationForm" method="POST" action="{{ isset($translation) ? route('national-parks.translation.update', [$nationalPark->id, $translation->id]) : route('national-parks.translation.store', $nationalPark->id) }}" novalidate>
                @csrf

                {{-- National Park Info --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label class="form-label">Lead Quote</label>
                        <textarea name="lead_quote" class="form-control @error('lead_quote') is-invalid @enderror" rows="3">{{ old('lead_quote', $translation->lead_quote ?? '') }}</textarea>
                        @error('lead_quote')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Language <span class="text-danger">*</span></label>
                        <select name="language_code" class="form-select @error('language_code') is-invalid @enderror">
                            <option value="en" {{ old('language_code', $translation->language_code ?? '') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="es" {{ old('language_code', $translation->language_code ?? '') == 'es' ? 'selected' : '' }}>Spanish</option>
                        </select>
                        <input type="hidden" name="national_park_id"
                            value="{{ old('national_park_id', $translation->national_park_id ?? ($nationalPark->id ?? $nationalPark->id)) }}">
                        @error('language_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Select Theme</label>
                        <select name="theme_id" class="form-select @error('theme_id') is-invalid @enderror">
                            @foreach($themes as $theme)
                            <option value="{{ $theme->id }}" {{ old('theme_id', $translation->theme_id ?? '') == $theme->id ? 'selected' : '' }}>
                                {{ $theme->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('theme_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="draft" {{ old('status', $translation->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $translation->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Content Info --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $nationalPark->name ?? '') }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $translation->subtitle ?? '') }}">
                        @error('subtitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Intro Text First</label>
                        <div class="quill-editor bg-white p-2 border rounded" style="height: 200px;">{!! old('intro_text_first', $translation->intro_text_first ?? '') !!}</div>
                        <textarea name="intro_text_first" id="intro_text_first" style="display:none;">{!! old('intro_text_first', $translation->intro_text_first ?? '') !!}</textarea>
                    </div>
                </div>

                {{-- Quotes --}}
                <div class="mb-4">
                    <label class="form-label">Closing Quote Title</label>
                    <input type="text"
                        name="closing_quote[0][title]"
                        class="form-control @error('closing_quote.0.title') is-invalid @enderror"   
                        value="{{ old('closing_quote.0.title', $translation->closing_quote[0]['title'] ?? '') }}">
                    @error('closing_quote.0.title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Closing Quote Description</label>
                    <textarea name="closing_quote[0][description]"
                        class="form-control @error('closing_quote.0.description') is-invalid @enderror"
                        rows="4">{{ old('closing_quote.0.description', $translation->closing_quote[0]['description'] ?? '') }}</textarea>
                    @error('closing_quote.0.description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Park Stats --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Park Stats (Add More)</label>
                    <div id="parkStatsRepeater">
                        @php
                        $oldStats = old('park_stats', $translation->park_stats ?? [['icon'=>'', 'value'=>'', 'label'=>'', 'description'=>'']]);
                        @endphp
                        @foreach ($oldStats as $index => $stat)
                        <div class="row g-2 mb-2 border rounded p-3 bg-light position-relative park-stat-item">
                            <div class="col-md-2">
                                <input type="text" name="park_stats[{{ $index }}][icon]" placeholder="Icon (Emoji)" value="{{ $stat['icon'] }}" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="park_stats[{{ $index }}][value]" placeholder="Value" value="{{ $stat['value'] }}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="park_stats[{{ $index }}][label]" placeholder="Label" value="{{ $stat['label'] }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="park_stats[{{ $index }}][description]" placeholder="Description" value="{{ $stat['description'] }}" class="form-control">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-stat"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addStatBtn">+ Add Stat</button>
                </div>

                {{-- Hero Section --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Hero Section (Dropzone + Axios)</label>
                    @php
                    $hero = old('hero_image_content', $translation->hero_image_content ?? ['background' => '', 'title' => '']);
                    @endphp
                    <div class="mb-3">
                        <div id="hero-dropzone" class="dropzone bg-light p-4 border border-dashed rounded text-center" data-upload-url="{{ route('national-parks.translation.uploadImage')}}">
                            <div class="dz-message text-muted fs-6">
                                <i class="fa fa-cloud-upload fa-2x mb-2"></i><br>
                                Drag & Drop or Click to Upload Hero Background
                            </div>
                        </div>
                        <input type="hidden" name="hero_background" id="hero_background" value="{{ json_encode(['url' => $hero['background']]) }}">
                        @if(!empty($hero['background']))
                        <div class="mt-3 text-center">
                            <img src="{{ $hero['background'] }}" class="img-fluid rounded shadow" style="max-height:200px;">
                        </div>
                        @endif
                    </div>
                    <input type="text" name="hero_section[title]" value="{{ $hero['title'] }}" class="form-control @error('hero_image_content.title') is-invalid @enderror" placeholder="Hero Title" required>
                    @error('hero_image_content.title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('national-parks.translation.index',$nationalPark->id) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success"> {{ isset($translation) ? 'Update' : 'Create' }} Translation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/national-park-translation-form.js') }}"></script>

@endpush