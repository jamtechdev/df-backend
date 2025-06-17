@extends('layouts.app')

@section('content')

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>National Park Translations</h4>
                <button id="btnAddTranslation" class="btn btn-primary" data-id="{{ $national_park_id }}"><i class="fa-solid fa-plus me-2"></i>Add New Translation</button>
            </div>
            <div class="theme-table">
                <table class="table" id="nationalParkTranslationsTable">
                    <thead>
                        <tr>
                      
                            <th>National Park </th>
                            <th>Lang</th>
                            <th>Theme </th>
                            <th>Published At</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>
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
    window.globalNationalParkId = {{$national_park_id ?? 'null'}};
</script>
<script src="{{ asset('assets/js/national-park-translations.js') }}"></script>

@endpush