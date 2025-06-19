@extends('layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <h5 class="card-header">National Parks Media</h5>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped', 'id' => 'mediaTranslationTable'], true) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="translationModal" tabindex="-1" aria-labelledby="translationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="translationForm" novalidate>
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-gradient bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="translationModalLabel">
                        <i class="fa fa-language me-2"></i>Add Translation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-4">
                    <input type="hidden" id="translationId" name="translationId">

                    <div class="row g-3">
                        <!-- Language Dropdown -->
                        <div class="col-md-6">
                            <label for="language_code" class="form-label">Language <i class="fa fa-language"></i></label>
                            <select class="form-select" id="language_code" name="language_code" required>
                                <option value="">Select Language</option>
                                <option value="en">English</option>
                                <option value="es">Spanish</option>
                            </select>
                            <div class="invalid-feedback">Please select a language.</div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <i class="fa fa-info-circle"></i></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                            <div class="invalid-feedback">Please select status.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="overlay_title" class="form-label">Overlay Title</label>
                            <input type="text" class="form-control" id="overlay_title" name="overlay_title" maxlength="255" placeholder="Overlay Title">
                        </div>

                        <div class="col-md-6">
                            <label for="overlay_subtitle" class="form-label">Overlay Subtitle</label>
                            <input type="text" class="form-control" id="overlay_subtitle" name="overlay_subtitle" maxlength="255" placeholder="Overlay Subtitle">
                        </div>

                        <div class="col-12">
                            <label for="overlay_description" class="form-label">Overlay Description</label>
                            <textarea class="form-control" id="overlay_description" name="overlay_description" rows="2" placeholder="Overlay Description"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required maxlength="255" placeholder="Translation Title">
                            <div class="invalid-feedback">Title is required.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="subtitle" class="form-label">Subtitle</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle" maxlength="255" placeholder="Subtitle">
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Main Description"></textarea>
                        </div>
                    </div>

                    <div id="formErrors" class="alert alert-danger d-none mt-3"></div>
                </div>
                <div class="modal-footer bg-light border-0 rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveTranslationBtn">
                        <i class="fa fa-save me-1"></i>Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
<script>
    window.mediaId = {{ $mediaId}};
      
</script>
<script src="{{ asset('assets/js/media-translation.js') }}"></script>
@endpush