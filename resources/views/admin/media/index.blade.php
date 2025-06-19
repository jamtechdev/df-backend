@extends('layouts.app')
@section('title', 'Manage Media')
@section('content')
   <div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <h5 class="card-header">National Parks Media </h5>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped', 'id' => 'nationalParkMedia'], true) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
