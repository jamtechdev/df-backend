$(document).ready(function () {
    let isEditMode = false;
    let editHiddenWonderId = null;

    function showLoader() { $('#loader').show(); }
    function hideLoader() { $('#loader').hide(); }

    function clearForm() {
        $('#hiddenWonderForm')[0].reset();
        editHiddenWonderId = null;
        isEditMode = false;
        $('#hiddenWonderModalLabel').text('Add Hidden Wonder');
        $('#formErrors').addClass('d-none').empty();
        $('#hiddenWonderForm').removeClass('was-validated');
        $('#is_active').prop('checked', false);
    }

    $('#createHiddenWonderBtn').click(function () {
        clearForm();
        $('#hiddenWonderModal').modal('show');
    });

    $(document).on('click', '.edit-hidden-wonder', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        let editUrl = window.hiddenWonderRoutes.edit.replace('ID_PLACEHOLDER', id);
        showLoader();
        axios.get(editUrl)
            .then(function (response) {
                let hiddenWonder = response.data.hiddenWonder;
                isEditMode = true;
                editHiddenWonderId = hiddenWonder.id;
                $('#hiddenWonderModalLabel').text('Edit Hidden Wonder');
                $('#section_heading').val(hiddenWonder.section_heading || '');
                $('#section_title').val(hiddenWonder.section_title || '');
                $('#section_subtitle').val(hiddenWonder.section_subtitle || '');
                $('#icon').val(hiddenWonder.icon || '');
                $('#title').val(hiddenWonder.title || '');
                $('#subtitle').val(hiddenWonder.subtitle || '');
                $('#description').val(hiddenWonder.description || '');
                $('#tip_heading').val(hiddenWonder.tip_heading || '');
                $('#tip_text').val(hiddenWonder.tip_text || '');
                $('#quote').val(hiddenWonder.quote || '');
                $('#sort_order').val(hiddenWonder.sort_order || 0);
                $('#is_active').prop('checked', hiddenWonder.is_active ? true : false);
                $('#hiddenWonderModal').modal('show');
            })
            .catch(function () {
                toastr.error('Failed to fetch hidden wonder data.');
            })
            .finally(function () {
                hideLoader();
            });
    });

    $('#hiddenWonderForm').submit(function (e) {
        e.preventDefault();

        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        $('#formErrors').addClass('d-none').empty();

        let url = isEditMode
            ? window.hiddenWonderRoutes.update.replace('ID_PLACEHOLDER', editHiddenWonderId)
            : window.hiddenWonderRoutes.store;
        let method = isEditMode ? 'put' : 'post';

        let formData = {
            national_park_translation_id: $('#nationalParkTranslationId').val(),
            section_heading: $('#section_heading').val(),
            section_title: $('#section_title').val(),
            section_subtitle: $('#section_subtitle').val(),
            icon: $('#icon').val(),
            title: $('#title').val(),
            subtitle: $('#subtitle').val(),
            description: $('#description').val(),
            tip_heading: $('#tip_heading').val(),
            tip_text: $('#tip_text').val(),
            quote: $('#quote').val(),
            sort_order: $('#sort_order').val(),
            is_active: $('#is_active').is(':checked') ? 1 : 0
        };

        showLoader();
        axios({
            method: method,
            url: url,
            data: formData
        })
            .then(function (response) {
                toastr.success(response.data.message);
                $('#hiddenWonderModal').modal('hide');
                if ($.fn.DataTable.isDataTable('#hiddenWondersTable')) {
                    $('#hiddenWondersTable').DataTable().ajax.reload(null, false);
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
                } else {
                    toastr.error('Error occurred while saving.');
                }
            })
            .finally(function () {
                hideLoader();
            });
    });

    $(document).on('click', '.delete-hidden-wonder', function () {
        if (!confirm('Are you sure you want to delete this hidden wonder?')) return;
        let id = $(this).data('id');
        showLoader();
        axios.delete(window.hiddenWonderRoutes.delete + id)
            .then(function (response) {
                toastr.success(response.data.message);
                if ($.fn.DataTable.isDataTable('#hiddenWondersTable')) {
                    $('#hiddenWondersTable').DataTable().ajax.reload(null, false);
                }
            })
            .catch(function () {
                toastr.error('Failed to delete hidden wonder.');
            })
            .finally(function () {
                hideLoader();
            });
    });

    function showErrors(message) {
        $('#formErrors').removeClass('d-none').html(message);
    }
});
