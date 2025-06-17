$(document).ready(function () {
    window.quill = new Quill('.quill-editor', {
        theme: 'snow'
    });

    const form = $('#translationForm');

    const showLoader = () => $('#loader').show();
    const hideLoader = () => $('#loader').hide();

    const clearErrors = () => {
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
    };

    Dropzone.autoDiscover = false;

    const heroDropzone = new Dropzone("#hero-dropzone", {
        url: $('#hero-dropzone').data('upload-url'),
        maxFiles: 1,
        acceptedFiles: 'image/*',
        addRemoveLinks: true,
        dictDefaultMessage: "Drop your hero image here or click to upload.",
        autoProcessQueue: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function () {
            const dz = this;

            dz.on("success", function (file, response) {
                const heroBackgroundInput = $('#hero_background');
                heroBackgroundInput.val(JSON.stringify({ url: response.url }));
                submitFormAfterUpload();
            });

            dz.on("addedfile", function (file) {
                if (dz.files.length > 1) {
                    dz.removeFile(dz.files[0]);
                }
            });

            dz.on("removedfile", function () {
                const url = $('#hero_background').val();
                if (url) {
                    $.post('/national-parks/translations/delete-image', { url: url, _token: $('meta[name="csrf-token"]').attr('content') })
                        .done(() => $('#hero_background').val(''))
                        .fail(() => alert('Failed to delete image from server.'));
                } else {
                    $('#hero_background').val('');
                }
            });

            dz.on("error", function (file, response) {
                const message = typeof response === 'object' ? response.message : response;
                $(file.previewElement).find('.dz-error-message').text(message);
            });
        }
    });

    const submitFormAfterUpload = () => {
        clearErrors();
        showLoader();

        const quillContent = window.quill ? window.quill.root.innerHTML : '';
        $('#intro_text_first').val(quillContent);

        const formData = new FormData(form[0]);
        const url = form.attr('action');
        const method = form.find('input[name="_method"]').val() || form.attr('method') || 'POST';

        axios({
            method: method.toLowerCase(),
            url: url,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(function (response) {
                toastr.success(response.data.message || 'Saved successfully.');
                // Redirect to national-parks.translation.index after success
                const nationalParkId = $('input[name="national_park_id"]').val();
                if (nationalParkId) {
                    window.location.href = `/national-parks/translations/${nationalParkId}`;
                }
            })
            .catch(function (error) {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    $.each(errors, function (key, messages) {
                        const input = form.find(`[name="${key}"]`);
                        if (input.length) {
                            input.removeClass('is-invalid');
                            input.next('.invalid-feedback').remove();
                            input.addClass('is-invalid');
                            input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                        }
                    });
                } else {
                    toastr.error(error.response?.data?.message || 'Failed to save.');
                }
            })
            .finally(function () {
                hideLoader();
            });
    };

    const validateForm = () => {
        clearErrors();
        let isValid = true;

        const requiredFields = [
            { name: 'language_code', label: 'Language' },
            { name: 'status', label: 'Status' },
            { name: 'title', label: 'Title' },
            { name: 'hero_section[title]', label: 'Hero Title' },
            { name: 'theme_id', label: 'Theme' },
            { name: 'subtitle', label: 'Subtitle' },
            { name: 'lead_quote', label: 'Lead Quote' }
        ];

        $.each(requiredFields, function (index, field) {
            const input = form.find(`[name="${field.name}"]`);
            if (input.length === 0 || !input.val() || !input.val().trim()) {
                if (input.length) {
                    input.addClass('is-invalid');
                    input.after(`<div class="invalid-feedback">${field.label} is required.</div>`);
                } else {
                    toastr.error(`${field.label} field is missing.`);
                }
                isValid = false;
            }
        });

        const quillContent = window.quill ? window.quill.getText().trim() : '';
        if (!quillContent) {
            const hiddenTextarea = $('#intro_text_first');
            hiddenTextarea.addClass('is-invalid');
            hiddenTextarea.after('<div class="invalid-feedback">Intro Text First is required.</div>');
            isValid = false;
        }

        const parkStats = $('#parkStatsRepeater .park-stat-item');
        if (parkStats.length === 0) {
            $('#parkStatsRepeater').after('<div class="invalid-feedback d-block">At least one Park Stat is required.</div>');
            isValid = false;
        } else {
            parkStats.each(function (index) {
                const icon = $(this).find(`input[name="park_stats[${index}][icon]"]`);
                const value = $(this).find(`input[name="park_stats[${index}][value]"]`);
                const label = $(this).find(`input[name="park_stats[${index}][label]"]`);

                if (!icon.val().trim()) {
                    icon.addClass('is-invalid').after(`<div class="invalid-feedback">Icon is required.</div>`);
                    isValid = false;
                }
                if (!value.val().trim()) {
                    value.addClass('is-invalid').after(`<div class="invalid-feedback">Value is required.</div>`);
                    isValid = false;
                }
                if (!label.val().trim()) {
                    label.addClass('is-invalid').after(`<div class="invalid-feedback">Label is required.</div>`);
                    isValid = false;
                }
            });
        }

        return isValid;
    };

    form.on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        if (!validateForm()) return;

        if (heroDropzone.getQueuedFiles().length > 0) {
            heroDropzone.processQueue();
        } else {
            submitFormAfterUpload();
        }
    });

    let statIndex = $('#parkStatsRepeater .park-stat-item').length;

    $('#addStatBtn').on('click', function () {
        const html = `
            <div class="row g-3 mt-2 border rounded p-3 bg-light position-relative park-stat-item">
                <div class="col-md-2">
                    <label class="form-label">Icon (Emoji)</label>
                    <input type="text" name="park_stats[${statIndex}][icon]" class="form-control" placeholder="🦌 🌋 🐻 etc">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Value</label>
                    <input type="text" name="park_stats[${statIndex}][value]" class="form-control" placeholder="10,000+">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Label (Red Text)</label>
                    <input type="text" name="park_stats[${statIndex}][label]" class="form-control" placeholder="THERMAL FEATURES">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Description (Gray Text)</label>
                    <input type="text" name="park_stats[${statIndex}][description]" class="form-control" placeholder="World's largest concentration">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-stat">X</button>
                </div>
            </div>
        `;
        $('#parkStatsRepeater').append(html);
        statIndex++;
    });

    $('#parkStatsRepeater').on('click', '.remove-stat', function () {
        if ($('.park-stat-item').length > 1) {
            $(this).closest('.park-stat-item').remove();
        } else {
            alert('At least one stat must remain.');
        }
    });

    // ✅ Real-time error hide
    form.on('input change', 'input, select, textarea', function () {
        $(this).removeClass('is-invalid').next('.invalid-feedback').remove();
    });

});
