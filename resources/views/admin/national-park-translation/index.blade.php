@extends('layouts.app')

@section('content')

<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <h5 class="card-header bg-primary text-white d-flex justify-content-between align-items-center">National Parks translations</h5>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped', 'id' => 'nPTranslationTable'], true) !!}
                </div>
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
{!! $dataTable->scripts() !!}

@endpush
