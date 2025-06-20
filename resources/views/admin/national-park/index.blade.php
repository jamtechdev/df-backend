@extends('layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <h5 class="card-header bg-primary text-white d-flex justify-content-between align-items-center">National Parks</h5>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped', 'id' => 'nationalParksTable'], true) !!}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Create/Edit Modal -->
<!-- Create/Edit Modal -->
<div class="modal fade" id="nationalParkModal" tabindex="-1" aria-labelledby="nationalParkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered"> <!-- Increased width to XL -->
        <form id="nationalParkForm" novalidate>
            <div class="modal-content rounded-4 shadow-lg border-0">
                <!-- Modal Header -->
                <div class="modal-header bg-gradient bg-primary text-white rounded-top-4 py-3">
                    <h5 class="modal-title d-flex align-items-center" id="nationalParkModalLabel">
                        <i class="fa fa-tree me-2"></i> Add / Edit National Park
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="modalCloseBtn"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body bg-light p-4">
                    <input type="hidden" id="nationalParkId" name="nationalParkId" value="">

                    <div class="row g-4">
                        <!-- Theme -->
                        <div class="col-md-12">
                            <label for="theme_id" class="form-label fw-semibold">Theme <span class="text-danger">*</span></label>
                            <select class="form-select" id="theme_id" name="theme_id" required>
                                <option value="">Select Theme</option>
                            </select>
                            <div class="invalid-feedback">Please select a theme.</div>
                        </div>

                        <!-- Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="255" placeholder="Enter National Park Name">
                            <div class="invalid-feedback">Name is required.</div>
                        </div>

                        <!-- SEO Title -->
                        <div class="col-md-6">
                            <label for="seo_title" class="form-label fw-semibold">SEO Title</label>
                            <input type="text" class="form-control" id="seo_title" name="seo_title" maxlength="255" placeholder="Enter SEO Title">
                        </div>

                        <!-- SEO Description -->
                        <div class="col-md-6">
                            <label for="seo_description" class="form-label fw-semibold">SEO Description</label>
                            <textarea name="seo_description" class="form-control" id="seo_description" rows="4" placeholder="Enter SEO Description"></textarea>
                        </div>

                        <!-- SEO Keywords -->
                        <div class="col-md-6">
                            <label for="seo_keywords" class="form-label fw-semibold">SEO Keywords (comma separated)</label>
                            <textarea name="seo_keywords" class="form-control" id="seo_keywords" rows="4" placeholder="Enter SEO Keywords"></textarea>
                        </div>
                    </div>

                    <!-- Error Display -->
                    <div id="formErrors" class="alert alert-danger d-none mt-4"></div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-white border-0 rounded-bottom-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="cancelBtn">
                        <i class="fa fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <i class="fa fa-save me-1"></i> Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Loader -->
<div id="loader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:9999;">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

@endsection




@push('scripts')
{!! $dataTable->scripts() !!}
<script src="{{ asset('assets/js/national-parks.js') }}"></script>
@endpush