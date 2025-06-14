@extends('layouts.app')

@section('content')
<div class="mt-4">
    <div class="card border-0">
        <div class="card-header tbl-nme-head d-flex justify-content-between align-items-center">
            <h4>Visitors</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped-custom table-responsive" id="visitors-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Blocked</th>
                        <th>Visited At</th>
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
<script src="{{ asset('assets/js/visitors.js') }}"></script>
@endpush
