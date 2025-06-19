@extends('layouts.app')
@section('title', 'Manage Members')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <h5 class="card-header">Manage Members</h5>
                    <div class="card-body">
                         {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
