// media-translation.js (Final Updated JS for Yajra DataTable)

$(document).ready(function () {
    const mediaId = window.mediaId || null;
    if (!mediaId) return;

    const table = window.LaravelDataTables["mediaTranslationTable"]; // Use correct Yajra instance
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // New Translation Modal Open
    $('#btnNewTranslation').on('click', function (e) {
        e.preventDefault();
        clearForm();
        $('#translationModalLabel').text('Add Translation');
        $('#translationModal').modal('show');
    });

    // Edit Translation Modal Open
    $(document).on('click', '.edit-translation', function (e) {
        
        e.preventDefault();
        const translationId = $(this).data('id');
        console.log('edit-translation',translationId);
        if (!translationId) return;

        axios.get(`/media/translations/${translationId}/edit`)
            .then(function (response) {
                if (response.data.success) {
                    fillForm(response.data.data);
                    $('#translationModalLabel').text('Edit Translation');
                    $('#translationModal').modal('show');
                } else {
                    toastr.error(response.data.message || 'Something went wrong.');
                }
            })
            .catch(function () {
                toastr.error('Failed to fetch translation data.');
            });
    });

    // Form Submit (Create or Update)
    $('#translationForm').on('submit', function (e) {
        e.preventDefault();

        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        $('#formErrors').addClass('d-none').empty();

        const translationId = $('#translationId').val();
        const formData = {
            language_code: $('#language_code').val(),
            status: $('#status').val(),
            overlay_title: $('#overlay_title').val(),
            overlay_subtitle: $('#overlay_subtitle').val(),
            overlay_description: $('#overlay_description').val(),
            title: $('#title').val(),
            subtitle: $('#subtitle').val(),
            description: $('#description').val(),
        };

        let url = translationId
            ? `/media/translations/${translationId}/update-trans`
            : `/media/translations/${mediaId}/store`;

        axios.post(url, formData, {
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
            .then(function (response) {
                if (response.data.success) {
                    $('#translationModal').modal('hide');
                    table.ajax.reload(null, false); // Reload without reset pagination
                    toastr.success(response.data.message);
                } else {
                    showErrors(response.data.message);
                    toastr.error('Validation error.');
                }
            })
            .catch(function (error) {
                if (error.response && error.response.data) {
                    const res = error.response.data;
                    if (res.errors) {
                        let messages = Object.values(res.errors).flat().join('<br>');
                        showErrors(messages);
                        toastr.error('Please correct the form errors.');
                    } else if (res.message) {
                        showErrors(res.message);
                        toastr.error(res.message);
                    } else {
                        showErrors('An unexpected error occurred.');
                        toastr.error('Unexpected error.');
                    }
                }
            });
    });

    // Delete Translation
    $('#mediaTranslationTable').on('click', '.btn-delete', function (e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to delete this translation?')) return;

        const tr = $(this).closest('tr');
        const translationId = tr.attr('id');
        if (!translationId) return;

        axios.delete(`/admin/media/translations/${translationId}/delete`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
            .then(function (response) {
                if (response.data.success) {
                    table.ajax.reload(null, false); // Reload on delete
                    toastr.success(response.data.message);
                } else {
                    toastr.error(response.data.message);
                }
            })
            .catch(function () {
                toastr.error('Failed to delete translation.');
            });
    });

    // Helpers
    function clearForm() {
        $('#translationId').val('');
        $('#translationForm')[0].reset();
        $('#translationForm').removeClass('was-validated');
        $('#formErrors').addClass('d-none').empty();
    }

    function fillForm(data) {
        $('#translationId').val(data.id || '');
        $('#language_code').val(data.language_code || '');
        $('#status').val(data.status || '');
        $('#overlay_title').val(data.overlay_title || '');
        $('#overlay_subtitle').val(data.overlay_subtitle || '');
        $('#overlay_description').val(data.overlay_description || '');
        $('#title').val(data.title || '');
        $('#subtitle').val(data.subtitle || '');
        $('#description').val(data.description || '');
        $('#formErrors').addClass('d-none').empty();
        $('#translationForm').removeClass('was-validated');
    }

    function showErrors(message) {
        $('#formErrors').removeClass('d-none').html(message);
    }
});
