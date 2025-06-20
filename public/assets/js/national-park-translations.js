$(document).ready(function () {
    // Initialize DataTable
    var table = $('#nPTranslationTable').DataTable(); // <-- this is the table you render in Blade

    // Handle delete
    $(document).on('click', '.btn-delete', function () {
        if (!confirm('Are you sure you want to delete this translation?')) {
            return;
        }
        var id = $(this).data('id');
        
        $.ajax({
            url: '/national-parks/translations/' + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#loader').show();
            },
            success: function (response) {
                toastr.success(response.message || 'Translation deleted successfully.');
                // Reload the DataTable after delete
                table.ajax.reload(null, false); // false = do not reset pagination
            },
            error: function () {
                toastr.error('Failed to delete translation.');
            },
            complete: function () {
                $('#loader').hide();
            }
        });
    });
});
