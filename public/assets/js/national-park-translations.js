$(document).ready(function () {
    
    // Handle delete
    $('#nationalParkTranslationsTable').on('click', '.btn-delete', function () {
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
                toastr.success(response.message);
                table.ajax.reload(null, false);
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
