$(document).ready(function () {
    const contentBlockModal = new bootstrap.Modal($('#contentBlockModal')[0]);
    const contentBlockForm = $('#contentBlockForm');
    const contentBlockModalLabel = $('#contentBlockModalLabel');
    const saveBtn = $('#saveBtn');

    let editingId = null;

    function resetForm() {
        contentBlockForm[0].reset();
        $('#contentBlockId').val('');
        $('#is_active').prop('checked', true);
    }

    // Handle add new button click
    $('#btnAdd').on('click', function () {
        resetForm();
        editingId = null;
        contentBlockModalLabel.text('Add Content Block');
        contentBlockModal.show();
    });

    // Handle edit button click
    $('#content-blocks-table').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        axios.get(window.location.href + '/' + id + '/edit')
            .then(function (response) {
                const cb = response.data.contentBlock || response.data;
                editingId = cb.id;
                $('#contentBlockId').val(cb.id);
                $('#title').val(cb.title);
                $('#section_type').val(cb.section_type);
                $('#icon').val(cb.icon);
                $('#heading').val(cb.heading);
                $('#subheading').val(cb.subheading);
                $('#description').val(cb.description);
                $('#sort_order').val(cb.sort_order);
                $('#is_active').prop('checked', cb.is_active);
                contentBlockModalLabel.text('Edit Content Block');
                contentBlockModal.show();
            })
            .catch(function () {
                toastr.error('Failed to fetch content block data');
            });
    });

    // Handle form submit
    contentBlockForm.on('submit', function (e) {
        e.preventDefault();
        saveBtn.prop('disabled', true);

        const data = {
            title: $('#title').val(),
            section_type: $('#section_type').val(),
            icon: $('#icon').val(),
            heading: $('#heading').val(),
            subheading: $('#subheading').val(),
            description: $('#description').val(),
            sort_order: $('#sort_order').val(),
            is_active: $('#is_active').is(':checked') ? 1 : 0,
        };

        let url = window.location.href;
        let method = 'post';

        if (editingId) {
            url += '/' + editingId + '/update';
            method = 'put';
        } else {
            url += '/store-content';
        }

        axios({
            method: method,
            url: url,
            data: data,
        })
            .then(function (response) {
                toastr.success(response.data.message);
                contentBlockModal.hide();
                $('#content-blocks-table').DataTable().ajax.reload(null, false);
            })
            .catch(function () {
                toastr.error('Failed to save content block');
            })
            .finally(function () {
                saveBtn.prop('disabled', false);
            });
    });

    // Handle delete button click
    $('#content-blocks-table').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this content block?')) return;

        axios.delete(window.location.href + '/' + id + '/delete')
            .then(function (response) {
                toastr.success(response.data.message);
                $('#content-blocks-table').DataTable().ajax.reload(null, false);
            })
            .catch(function () {
                toastr.error('Failed to delete content block');
            });
    });
});
