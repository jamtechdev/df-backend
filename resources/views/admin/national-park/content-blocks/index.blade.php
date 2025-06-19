@extends('layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <h5 class="card-header">Manage Content Blocks</h5>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="contentBlockModal" tabindex="-1" aria-labelledby="contentBlockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0">
            <form id="contentBlockForm">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="contentBlockModalLabel">
                        <i class="fa fa-pencil-alt me-2"></i> Add Content Block
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">

                    <input type="hidden" id="contentBlockId" name="contentBlockId" value="">
                    <input type="hidden" id="national_park_translation_id" name="national_park_translation_id" value="{{ $np_translation_id }}">

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label for="title" class="form-label">
                                <i class="fa fa-pen-nib me-1 text-primary"></i> Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" required>
                        </div>
                        <div class="col-md-6">
                            <label for="section_type" class="form-label">
                                <i class="fa fa-layer-group me-1 text-primary"></i> Section Type
                            </label>
                            <select class="form-select" id="section_type" name="section_type" required>
                                <option value="">Select Section Type</option>
                                <option value="key_feature">Key Feature</option>
                                <option value="explore">Explore</option>
                                <option value="other">Other</option>
                                <option value="journey">Journey</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="icon" class="form-label">
                                <i class="fa fa-icons me-1 text-primary"></i> Icon Class
                            </label>
                            <input type="text" class="form-control" id="icon" name="icon" placeholder="e.g., fa fa-tree">
                        </div>

                        <div class="col-md-12">
                            <label for="heading" class="form-label">
                                <i class="fa fa-heading me-1 text-primary"></i> Heading
                            </label>
                            <input type="text" class="form-control" id="heading" name="heading" placeholder="Enter Heading">
                        </div>

                        <div class="col-md-12">
                            <label for="subheading" class="form-label">
                                <i class="fa fa-subscript me-1 text-primary"></i> Subheading
                            </label>
                            <input type="text" class="form-control" id="subheading" name="subheading" placeholder="Enter Subheading">
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">
                                <i class="fa fa-align-left me-1 text-primary"></i> Description
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="sort_order" class="form-label">
                                <i class="fa fa-sort-numeric-down me-1 text-primary"></i> Sort Order
                            </label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="0" min="0">
                        </div>

                        <div class="col-md-6 d-flex align-items-center mt-4">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success" id="saveBtn">
                        <i class="fa fa-save me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    window.npTranslationId = @json($np_translation_id);
</script>
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}

<script src="{{ asset('assets/js/content-blocks.js') }}"></script>
@endpush