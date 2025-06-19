@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-dark rounded-top-4 d-flex align-items-center">
                    <i class="fa fa-upload fa-lg me-2"></i>
                    <h5 class="mb-0">{{ isset($media) ? 'Edit Media' : 'Upload Media' }}</h5>
                </div>

                <div class="card-body p-5 bg-light rounded-bottom-4">
                    <form action="{{ isset($media) ? route('media.update', [$park->id, $media->id]) : route('media.store', $park->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        id="mediaForm">
                        @csrf
                        @if(isset($media)) @method('PUT') @endif
                        <input type="hidden" id="isUpdateMode" value="{{ isset($media) ? '1' : '0' }}">

                        {{-- Back Button --}}
                        <div class="mb-4">
                            <a href="{{ route('media.index', $park->id) }}" class="btn btn-secondary shadow-sm">
                                <i class="fa fa-arrow-left me-2"></i> Back
                            </a>
                        </div>

                        {{-- Show Existing Image in Edit Mode --}}
                        @if(isset($media))
                        <div class="mb-4 text-center">
                            <img src="{{ $media->s3_url }}" alt="Existing Media" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                        @endif

                        <div class="mb-4">
                            <div class="dropzone justify-content-center align-items-center border-2 border-dashed border-primary rounded-4 p-5 bg-white" id="mediaDropzone" style="cursor: pointer;">
                                <div class="dz-message text-center text-muted">
                                    <i class="fa fa-cloud-upload fa-3x text-primary mb-3"></i>
                                    <h6 class="text-secondary">Drag & Drop your files here</h6>
                                    <p class="small">or click to browse files</p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 mt-4 shadow-sm">
                            <i class="fa fa-paper-plane me-2"></i> {{ isset($media) ? 'Update Media' : 'Upload Media' }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var mediaIndexUrl = "{{ route('media.index', $park->id) }}";
</script>
<script src="{{ asset('assets/js/media-upload.js') }}"></script>
@endpush

