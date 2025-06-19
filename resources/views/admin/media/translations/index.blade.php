@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1>Media Translations</h1>
    <a href="{{ route('media.index', ['id' => $mediaId]) }}" class="btn btn-secondary mb-3">Back to Media</a>

    <div class="card">
        <div class="card-body">
            {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
        </div>
    </div>
</div>
@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush