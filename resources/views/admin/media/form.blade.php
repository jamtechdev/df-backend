@extends('layouts.app')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" rel="stylesheet">

<div class="container mt-5">
    <h3>Upload Media to S3 (Dropzone + jQuery)</h3>

    <form action="" method="POST" enctype="multipart/form-data" id="mediaForm">
        @csrf

        <!-- Dropzone area -->
        <div class="dropzone" id="mediaDropzone"></div>

        <!-- Dynamic Title & Description fields will be added here -->
        <div id="detailsArea"></div>

        <button type="submit" class="btn btn-success mt-3">Upload All to S3</button>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script>
    Dropzone.autoDiscover = false;

    const mediaDropzone = new Dropzone("#mediaDropzone", {
        url: "#", // prevent auto-upload (handled on form submit)
        autoProcessQueue: false,
        addRemoveLinks: true,
        uploadMultiple: true,
        parallelUploads: 10,
        maxFilesize: 5, // MB
        acceptedFiles: 'image/*',
        init: function() {
            this.on("addedfile", function(file) {
                let index = $('.file-detail').length;
                let detailHtml = `
                <div class="card p-3 my-3 bg-light rounded shadow-sm file-detail" data-file="${file.upload.uuid}">
                    <h5>Details for: ${file.name}</h5>
                    <input type="hidden" name="files[]" value="${file.name}">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="media[${index}][title]" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="media[${index}][description]" class="form-control" placeholder="Enter description"></textarea>
                    </div>
                </div>`;
                $('#detailsArea').append(detailHtml);
            });

            this.on("removedfile", function(file) {
                $(`.file-detail[data-file="${file.upload.uuid}"]`).remove();
            });
        }
    });
</script>
@endpush