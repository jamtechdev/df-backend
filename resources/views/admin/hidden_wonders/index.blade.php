@extends('layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <h5 class="card-header bg-primary text-white d-flex justify-content-between align-items-center">Manage Hidden Wonder </h5>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped', 'id' => 'hiddenWondersTable'], true) !!}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Create/Edit Modal -->
<div class="modal fade" id="hiddenWonderModal" tabindex="-1" aria-labelledby="hiddenWonderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="hiddenWonderForm" novalidate>
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-gradient bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="hiddenWonderModalLabel">
                        <i class="fas fa-eye me-2"></i> Add/Edit Hidden Wonder
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="modalCloseBtn"></button>
                </div>
                <div class="modal-body bg-light p-4">
                    <input type="hidden" id="hiddenWonderId" name="hiddenWonderId" value="">
                    <input type="hidden" id="nationalParkTranslationId" name="national_park_translation_id" value="{{$national_park_translation_id}}">

                    <!-- Section Info -->
                    <h6 class="text-primary"><i class="fas fa-layer-group"></i> Section Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="section_heading" class="form-label"><i class="fas fa-heading"></i> Section Heading</label>
                            <input type="text" class="form-control" id="section_heading" name="section_heading" maxlength="255" placeholder="Section Heading">
                        </div>
                        <div class="col-md-6">
                            <label for="section_title" class="form-label"><i class="fas fa-heading"></i> Section Title</label>
                            <input type="text" class="form-control" id="section_title" name="section_title" maxlength="255" placeholder="Section Title">
                        </div>
                        <div class="col-12">
                            <label for="section_subtitle" class="form-label"><i class="fas fa-subscript"></i> Section Subtitle</label>
                            <input type="text" class="form-control" id="section_subtitle" name="section_subtitle" maxlength="255" placeholder="Section Subtitle">
                        </div>
                    </div>

                    <!-- Main Content -->
                    <h6 class="text-primary mt-4"><i class="fas fa-info-circle"></i> Main Content</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="icon" class="form-label"><i class="fas fa-icons"></i> Icon</label>
                            <input type="text" class="form-control" id="icon" name="icon" maxlength="255" placeholder="e.g., fas fa-mountain">
                        </div>
                        <div class="col-md-6">
                            <label for="sort_order" class="form-label"><i class="fas fa-sort"></i> Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" step="1" placeholder="Sort Order">
                        </div>
                        <div class="col-md-6">
                            <label for="title" class="form-label"><i class="fas fa-font"></i> Title</label>
                            <input type="text" class="form-control" id="title" name="title" maxlength="255" placeholder="Title">
                        </div>
                        <div class="col-md-6">
                            <label for="subtitle" class="form-label"><i class="fas fa-subscript"></i> Subtitle</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle" maxlength="255" placeholder="Subtitle">
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="description" class="form-control" id="description" rows="3" placeholder="Description"></textarea>
                        </div>
                    </div>

                    <!-- Tip Section -->
                    <h6 class="text-primary mt-4"><i class="fas fa-lightbulb"></i> Tip Section</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tip_heading" class="form-label"><i class="fas fa-heading"></i> Tip Heading</label>
                            <input type="text" class="form-control" id="tip_heading" name="tip_heading" maxlength="255" placeholder="Tip Heading">
                        </div>
                        <div class="col-md-6">
                            <label for="tip_text" class="form-label"><i class="fas fa-align-left"></i> Tip Text</label>
                            <textarea name="tip_text" class="form-control" id="tip_text" rows="2" placeholder="Tip Text"></textarea>
                        </div>
                    </div>

                    <!-- Quote -->
                    <h6 class="text-primary mt-4"><i class="fas fa-quote-right"></i> Quote</h6>
                    <div class="mb-3">
                        <label for="quote" class="form-label"><i class="fas fa-quote-left"></i> Quote</label>
                        <textarea name="quote" class="form-control" id="quote" rows="2" placeholder="Quote"></textarea>
                    </div>

                    <!-- Active Checkbox -->
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1">
                        <label class="form-check-label" for="is_active"><i class="fas fa-toggle-on"></i> Active</label>
                    </div>

                    <div id="formErrors" class="alert alert-danger d-none mt-3"></div>
                </div>
                <div class="modal-footer bg-light border-0 rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="cancelBtn">
                        <i class="fas fa-times-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <i class="fas fa-save"></i> Save
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
<script>
    window.hiddenWonderRoutes = {
        store: "{{ route('national-parks.translations.hidden-wonders.store') }}",
        update: "{{ route('national-parks.translations.hidden-wonders.update', ['hiddenWonder' => 'ID_PLACEHOLDER']) }}",
        edit: "{{ route('national-parks.translations.hidden-wonders.edit', ['hiddenWonder' => 'ID_PLACEHOLDER']) }}", // ✅ Fixed here
        delete: "{{ url('national-parks/translations/hidden-wonders') }}/"
    };
</script>
{!! $dataTable->scripts() !!}
<script src="{{ asset('assets/js/hidden-wonders.js') }}"></script>
@endpush
