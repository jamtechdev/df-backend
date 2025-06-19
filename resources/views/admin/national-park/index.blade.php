@extends('layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <h5 class="card-header">National Parks</h5>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped', 'id' => 'nationalParksTable'], true) !!}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Create/Edit Modal -->
<div class="modal fade" id="nationalParkModal" tabindex="-1" aria-labelledby="nationalParkModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <form id="nationalParkForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nationalParkModalLabel">Add/Edit National Park</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modalCloseBtn"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="nationalParkId" name="nationalParkId" value="">

                    <div class="mb-3">
                        <label for="theme_id" class="form-label">Theme<span class="text-danger">*</span></label>
                        <select class="form-select" id="theme_id" name="theme_id" required>
                            <option value="">Select Theme</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="255">
                    </div>
                    <!-- Removed slug input as slug will be auto-generated -->
                    <div class="mb-3">
                        <label for="seo_title" class="form-label">SEO Title</label>
                        <input type="text" class="form-control" id="seo_title" name="seo_title" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="seo_description" class="form-label">SEO Description</label>
                        <textarea name="seo_description" class="form-control" id="seo_description" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="seo_keywords" class="form-label">SEO Keywords (comma seprated)</label>
                        <textarea name="seo_keywords" class="form-control" id="seo_keywords" rows="5"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
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
<script src="{{ asset('assets/js/national-parks.js') }}"></script>
{!! $dataTable->scripts() !!}
@endpush