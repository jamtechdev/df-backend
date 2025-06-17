$(document).ready(function () {
    function showLoader() { $('#loader').show(); }
    function hideLoader() { $('#loader').hide(); }

    function fetchVisitors() {
        showLoader();
        axios.get('/users/visitors/data')
            .then(response => renderTable(response.data.visitors))
            .catch(() => toastr.error('Failed to fetch visitors.'))
            .finally(() => hideLoader());
    }

    function renderTable(visitors) {
        let tbody = '';
        visitors.forEach(visitor => {
            tbody += `<tr>
              
                <td>${visitor.first_name}</td>
                <td>${visitor.last_name}</td>
                <td>${visitor.email}</td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input toggle-block" type="checkbox" role="switch" data-id="${visitor.id}" ${visitor.is_blocked ? 'checked' : ''}>
                    </div>
                </td>
                <td>${new Date(visitor.created_at).toLocaleString()}</td>
                <td>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${visitor.id}"><i class="fa-solid fa-trash me-2"></i>Delete</button>
                </td>
            </tr>`;
        });
        $('#visitors-table tbody').html(tbody);

        // Attach event handlers
        $('.delete-btn').click(function () {
            if (confirm('Are you sure you want to delete this visitor?')) {
                const id = $(this).data('id');
                showLoader();
                axios.delete(`/users/visitors/${id}`)
                    .then(res => {
                        toastr.success(res.data.message);
                        fetchVisitors();
                    })
                    .catch(() => toastr.error('Delete failed.'))
                    .finally(() => hideLoader());
            }
        });

        $('.toggle-block').change(function () {
            const id = $(this).data('id');
            showLoader();
            axios.post(`/users/visitors/${id}/toggle-block`, {}, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(() => {
                toastr.success('Visitor block status updated');
            })
            .catch(() => toastr.error('Failed to update block status'))
            .finally(() => hideLoader());
        });
    }

    fetchVisitors();
});
