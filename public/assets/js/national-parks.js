$(document).ready(function () {
    let isEditMode = false;
    let editParkId = null;
    let categories = [];
    let themes = [];

    function showLoader() { $('#loader').show(); }
    function hideLoader() { $('#loader').hide(); }

    // Fetch categories and themes in one request
    function fetchCategoriesAndThemes() {
        return axios.get('/national-parks/fetch-categories-themes')
            .then(function (response) {
                categories = response.data.categories;
                themes = response.data.themes;
            })
            .catch(function (error) {
                console.error('Error fetching categories and themes:', error);
                toastr.error('Failed to fetch categories and themes.');
            });
    }

    // Populate category and theme select options
    function populateSelectOptions(selectedCategoryId = null, selectedThemeId = null) {
        let themeSelect = $('#theme_id');
        themeSelect.empty().append('<option value="">Select Theme</option>');
        themes.forEach(function (theme) {
            themeSelect.append('<option value="' + theme.id + '"' + (selectedThemeId == theme.id ? ' selected' : '') + '>' + theme.name + '</option>');
        });
    }


    // Reset form and modal for create
    function resetForm() {
        $('#nationalParkForm')[0].reset();
        editParkId = null;
        isEditMode = false;
        $('#nationalParkModalLabel').text('Add National Park');
        populateSelectOptions();
        $('#seo_title').val('');
        $('#seo_description').val('');
        $('#seo_keywords').val('');
        $('#is_featured').prop('checked', false);
    }

    // Open modal for create
    $('#btnAdd').click(function () {
        resetForm();
        $('#nationalParkModal').modal('show');
    });

    // Open modal for edit
    $(document).on('click', '.btn-edit', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        console.log(id);

        showLoader();
        axios.get('/national-parks/' + id + '/edit')
            .then(function (response) {
                let park = response.data.nationalPark;
                isEditMode = true;
                editParkId = park.id;
                $('#nationalParkModalLabel').text('Edit National Park');
                populateSelectOptions(park.category_id, park.theme_id);
                $('#name').val(park.name);
                // Removed setting slug as it will be auto-generated
                $('#seo_title').val(park.seo_title || '');
                $('#seo_description').val(park.seo_description || '');
                $('#seo_keywords').val(park.seo_keywords || '');
                $('#is_featured').prop('checked', park.is_featured ? true : false);
                $('#nationalParkModal').modal('show');
            })
            .catch(function (error) {
                console.error('Error fetching national park data:', error);
                toastr.error('Failed to fetch national park data.');
            })
            .finally(function () {
                hideLoader();
            });
    });

    // Handle form submit for create and update
    $('#nationalParkForm').submit(function (e) {
        e.preventDefault();

        let url = isEditMode ? '/national-parks/' + editParkId : '/national-parks';
        let method = isEditMode ? 'put' : 'post';

        let formData = {
            category_id: $('#category_id').val(),
            theme_id: $('#theme_id').val(),
            name: $('#name').val(),
            // slug removed as it will be auto-generated
            seo_title: $('#seo_title').val(),
            seo_description: $('#seo_description').val(),
            seo_keywords: $('#seo_keywords').val(),
            is_featured: $('#is_featured').is(':checked') ? 1 : 0
        };

        showLoader();
        axios({
            method: method,
            url: url,
            data: formData
        })
            .then(function (response) {
                toastr.success(response.data.message);
                $('#nationalParkModal').modal('hide');
            })
            .catch(function (error) {
                if (error.response && error.response.data && error.response.data.errors) {
                    let errors = error.response.data.errors;
                    let errorMessages = [];
                    for (let key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessages.push(errors[key].join(', '));
                        }
                    }
                    toastr.error('Validation errors: ' + errorMessages.join(' | '));
                } else {
                    toastr.error('An error occurred while saving the national park.');
                }
                console.error('Error saving national park:', error);
            })
            .finally(function () {
                hideLoader();
            });
    });

    // Handle delete
    $(document).on('click', '.btn-delete', function () {
        if (!confirm('Are you sure you want to delete this national park?')) {
            return;
        }
        let id = $(this).data('id');
        showLoader();
        axios.delete('/national-parks/' + id)
            .then(function (response) {
                toastr.success(response.data.message);
            })
            .catch(function (error) {
                toastr.error('Failed to delete national park.');
                console.error('Error deleting national park:', error);
            })
            .finally(function () {
                hideLoader();
            });
    });

    // Handle translations button click
    $(document).on('click', '.btn-translation', function () {
        let id = $(this).data('id');
        // Redirect to the translations index page with national park id as route param
        window.location.href = '/national-parks/translations/' + id;
    });

    // Handle is_featured toggle change
    $(document).on('change', '.is-featured-toggle', function () {
        let id = $(this).data('id');
        let isFeatured = $(this).is(':checked') ? 1 : 0;
        showLoader();
        // Fetch full park data before updating
        axios.get('/national-parks/' + id + '/edit')
            .then(function (response) {
                let park = response.data.nationalPark;
                let updateData = {
                    category_id: park.category_id,
                    theme_id: park.theme_id,
                    name: park.name,
                    seo_title: park.seo_title,
                    seo_description: park.seo_description,
                    seo_keywords: park.seo_keywords,
                    is_featured: isFeatured
                };
                return axios.put('/national-parks/' + id, updateData);
            })
            .then(function (response) {
                toastr.success('Featured status updated.');
            })
            .catch(function (error) {
                toastr.error('Failed to update featured status.');
                console.error('Error updating featured status:', error);
            })
            .finally(function () {
                hideLoader();
            });
    });

    // Initial load
    fetchCategoriesAndThemes();
});
