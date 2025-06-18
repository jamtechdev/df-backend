$(document).ready(function () {
    let nationalParks = [];

    function showLoader() {
        $('#loader').show();
    }

    function hideLoader() {
        $('#loader').hide();
    }



    // Fetch and display national park translations data
    function fetchTranslations() {
        showLoader();
        let url = '/national-parks/translations/fetch-data';

        // Get national_park_id from URL path params (not query params)
        let pathParts = window.location.pathname.split('/');
        let nationalParkId = null;
        if (pathParts.length >= 3) {
            nationalParkId = pathParts[pathParts.length - 1];
            if (nationalParkId && !isNaN(nationalParkId)) {
                url = '/national-parks/translations/fetch-data/' + nationalParkId;
            }
        }

        axios.get(url)
            .then(function (response) {
                let translations = response.data.translations;
                let tbody = $('#nationalParkTranslationsTable tbody');
                tbody.empty();

                if (translations.length === 0) {
                    tbody.append('<tr><td colspan="14" class="text-center text-danger">Data Not Found</td></tr>');
                    return;
                }

                translations.forEach(function (t) {
                    let row = '<tr>' +
                        '<td>' + (t.title || 'N/A') + '</td>' +
                        '<td>' + (t.language_code || 'N/A') + '</td>' +
                        '<td>' + (t.theme?.name || 'N/A') + '</td>' +
                        '<td>' + (formatDate(t.published_at) || 'N/A') + '</td>' +
                        '<td>' + (formatDate(t.created_at) || 'N/A') + '</td>' +
                        '<td>' + (formatDate(t.updated_at) || 'N/A') + '</td>' +
                        '<td>' + (t.status) + '</td>' +
                        '<td>' +
                        '<a href="/national-parks/translations/' + t.id + '/edit" class="btn btn-sm btn-info me-2">Edit</a>' +
                        '<a href="/national-parks/content-blocks/' + t.id + '" class="btn btn-sm btn-info me-2">+ Content</a>' +
                        '<a href="/national-parks/translations/media/' + t.id + '" class="btn btn-sm btn-info me-2">+ Media</a>' +
                        '<button class="btn btn-sm btn-danger btn-delete" data-id="' + t.id + '" data-national-park-id="' + t.national_park_id + '">Delete</button>' +
                        '</td>' +
                        '</tr>';
                    tbody.append(row);
                });
            })
            .catch(function (error) {
                console.error('Error fetching translations:', error);
                toastr.error('Failed to fetch translations.');
            })
            .finally(function () {
                hideLoader();
            });
    }

    // Handle delete
    $(document).on('click', '.btn-delete', function () {
        if (!confirm('Are you sure you want to delete this translation?')) {
            return;
        }
        let id = $(this).data('id');
        showLoader();
        let nationalParkId = $(this).data('national-park-id');
        axios.delete('/national-parks/translations/' + id)
            .then(function (response) {
                toastr.success(response.data.message);
                fetchTranslations();
            })
            .catch(function (error) {
                toastr.error('Failed to delete translation.');
                console.error('Error deleting translation:', error);
            })
            .finally(function () {
                hideLoader();
            });
    });

    // Open create page
    $('#btnAddTranslation').click(function () {
        let nationalParkId = $(this).data('id');
        if (nationalParkId) {
            window.location.href = '/national-parks/translations/' + nationalParkId + '/create';
        } else {
            window.location.href = '/national-parks/translations/create';
        }
    });

    // Initial fetch
    fetchTranslations();
});
