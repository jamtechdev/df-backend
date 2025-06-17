$(document).ready(function () {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

    const contentBlocksTableBody = $('#contentBlocksTable tbody');
    const contentBlockModal = new bootstrap.Modal($('#contentBlockModal')[0]);
    const contentBlockForm = $('#contentBlockForm');
    const contentBlockModalLabel = $('#contentBlockModalLabel');
    const saveBtn = $('#saveBtn');
    
    let editingId = null;
    const npTranslationId = window.npTranslationId; // Now correctly coming from Blade
    const contentBlocksFetchDataUrl = window.contentBlocksFetchDataUrl;
    const contentBlocksBaseUrl = window.contentBlocksBaseUrl;

    // Initialize Quill Editor
    const quill = new Quill('#descriptionEditor', {
        theme: 'snow'
    });

    function fetchContentBlocks(translationId = null) {
        let url = contentBlocksFetchDataUrl;
        if (translationId) {
            url += '?translation_id=' + encodeURIComponent(translationId);
        }
        axios.get(url)
            .then(function (response) {
                const contentBlocks = response.data;
                contentBlocksTableBody.empty();

                if (contentBlocks.length === 0) {
                    contentBlocksTableBody.append('<tr><td colspan="10" class="text-center">No data found</td></tr>');
                    return;
                }

                $.each(contentBlocks, function (i, cb) {
                    const tr = $('<tr>');
                    tr.html(
                        '<td>' + cb.section_type + '</td>' +
                        '<td>' + (cb.heading || '') + '</td>' +
                        '<td>' + (cb.subheading || '') + '</td>' +
                        '<td>' + (cb.icon || '') + '</td>' +
                        '<td>' + cb.title + '</td>' +
                        '<td>' + (cb.description || '') + '</td>' +
                        '<td>' + cb.sort_order + '</td>' +
                        '<td>' + (cb.is_active ? 'Yes' : 'No') + '</td>' +
                        '<td>' +
                        '<button class="btn btn-sm btn-primary btn-edit" data-id="' + cb.id + '">Edit</button> ' +
                        '<button class="btn btn-sm btn-danger btn-delete" data-id="' + cb.id + '">Delete</button>' +
                        '</td>'
                    );
                    contentBlocksTableBody.append(tr);
                });

                attachEventListeners();
            })
            .catch(function () {
                toastr.error('Failed to fetch content blocks');
            });
    }

    function attachEventListeners() {
        $('.btn-edit').off('click').on('click', function () {
            const id = $(this).data('id');
            editContentBlock(id);
        });

        $('.btn-delete').off('click').on('click', function () {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this content block?')) {
                deleteContentBlock(id);
            }
        });
    }

    function editContentBlock(id) {
        axios.get(contentBlocksFetchDataUrl)
            .then(function (response) {
                const contentBlocks = response.data;
                const contentBlock = contentBlocks.find(cb => cb.id == id);
                if (!contentBlock) {
                    toastr.error('Content block not found');
                    return;
                }
                editingId = id;
                contentBlockModalLabel.text('Edit Content Block');
                contentBlockForm.find('#national_park_translation_id').val(contentBlock.national_park_translation_id);
                contentBlockForm.find('#section_type').val(contentBlock.section_type);
                contentBlockForm.find('#heading').val(contentBlock.heading || '');
                contentBlockForm.find('#subheading').val(contentBlock.subheading || '');
                contentBlockForm.find('#icon').val(contentBlock.icon || '');
                contentBlockForm.find('#title').val(contentBlock.title);
                quill.root.innerHTML = contentBlock.description || '';
                contentBlockForm.find('#sort_order').val(contentBlock.sort_order);
                contentBlockForm.find('#is_active').prop('checked', contentBlock.is_active);
                contentBlockModal.show();
            })
            .catch(function () {
                toastr.error('Failed to fetch content block data');
            });
    }

    $('#btnAdd').on('click', function () {
        editingId = null;
        contentBlockModalLabel.text('Add Content Block');
        contentBlockForm[0].reset();
        quill.root.innerHTML = '';
        contentBlockForm.find('#is_active').prop('checked', true);
        contentBlockModal.show();
    });

    contentBlockForm.on('submit', function (e) {
        e.preventDefault();
        saveBtn.prop('disabled', true);

        const data = {
            national_park_translation_id: contentBlockForm.find('#national_park_translation_id').val(),
            section_type: contentBlockForm.find('#section_type').val(),
            heading: contentBlockForm.find('#heading').val(),
            subheading: contentBlockForm.find('#subheading').val(),
            icon: contentBlockForm.find('#icon').val(),
            title: contentBlockForm.find('#title').val(),
            description: quill.root.innerHTML,
            sort_order: contentBlockForm.find('#sort_order').val(),
            is_active: contentBlockForm.find('#is_active').prop('checked'),
        };

        let request;
        if (editingId) {
            request = axios.put(contentBlocksBaseUrl + '/' + editingId + '/update', data);
        } else {
            request = axios.post(contentBlocksBaseUrl + '/store', data);
        }

        request.then(function (response) {
            toastr.success(response.data.message || 'Content block saved successfully.');
            contentBlockModal.hide();
            fetchContentBlocks(npTranslationId);
        })
        .catch(function (error) {
            toastr.error('Failed to save content block');
            console.error(error);
        })
        .finally(function () {
            saveBtn.prop('disabled', false);
        });
    });

    function deleteContentBlock(id) {
        // Fix delete URL to match route: /national-parks/content-blocks/{np_translation_id}/{content_block}/delete
        axios.delete('/national-parks/content-blocks/' + npTranslationId + '/' + id + '/delete')
            .then(function (response) {
                toastr.success(response.data.message || 'Content block deleted successfully.');
                fetchContentBlocks(npTranslationId);
            })
            .catch(function () {
                toastr.error('Failed to delete content block');
            });
    }

    fetchContentBlocks(npTranslationId);
});
