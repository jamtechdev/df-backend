Dropzone.autoDiscover = false;

const mediaDropzone = new Dropzone("#mediaDropzone", {
    url: "#", // Axios will handle upload
    autoProcessQueue: false,
    addRemoveLinks: true,
    uploadMultiple: true,
    parallelUploads: 10,
    maxFilesize: 5, // MB
    acceptedFiles: 'image/*',
    init: function () {
        const isUpdateMode = document.getElementById('isUpdateMode') ? true : false;

        this.on("addedfile", function (file) {
            if (isUpdateMode && this.files.length > 1) {
                this.removeFile(file); // Only allow 1 file in update mode
                toastr.warning('Only one image can be uploaded in update mode.');
            }
        });

        this.on("removedfile", function (file) {
            $(`.file-preview[data-file="${file.upload?.uuid}"]`).remove();
        });

        document.getElementById('mediaForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;
            const actionUrl = form.getAttribute('action');
            const formData = new FormData();
            const token = document.querySelector('input[name="_token"]').value;
            formData.append('_token', token);

            // Append files to FormData
            if (mediaDropzone.files.length === 0) {
                if (!isUpdateMode) { // Only in create mode show error
                    toastr.error('Please add at least one file.');
                    return;
                }
            }


            mediaDropzone.files.forEach((file, index) => {
                formData.append('files[]', file);
            });

            try {
                const response = await axios.post(actionUrl, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                toastr.success(response.data.message || 'Files uploaded successfully!');
                mediaDropzone.removeAllFiles();
                $('#detailsArea').empty();
                setTimeout(() => {
                    window.location.href = mediaIndexUrl;
                }, 1000);
            } catch (error) {
                toastr.error(error.response?.data?.error || 'Error uploading files.');
            }
        });
    }
});
