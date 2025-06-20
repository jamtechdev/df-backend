@extends('layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Manage Visitors</h5>
                    <button id="btnRefresh" class="btn btn-light btn-sm">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        {!! $dataTable->table([
                            'class' => 'table table-hover table-striped table-bordered text-center align-middle', 
                            'id' => 'visitorsTable'
                        ], true) !!}
                    </div>
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
{!! $dataTable->scripts() !!}
<script>
    $(document).ready(function() {
        $('#btnRefresh').on('click', function() {
            $('#visitorsTable').DataTable().ajax.reload(null, false);
        });
    });
</script>
@endpush
