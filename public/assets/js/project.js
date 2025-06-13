$(document).ready(function () {
    let editPivotId = null; // Pivot table 'id'
    let editProjectId = null;
    let editUserId = null;

    function showLoader() { $('#loader').show(); }
    function hideLoader() { $('#loader').hide(); }

    function fetchProjects() {
        showLoader();
        axios.get('/projects/permissions/data')
            .then(response => {
                const projects = response.data.project_user_roles;
                const users = response.data.users;

                renderTable(projects);
                renderUserOptions(users);
                renderProjectOptions(projects);
            })
            .catch(() => {
                toastr.error('Failed to fetch data.');
            })
            .finally(() => {
                hideLoader();
            });
    }

    function renderTable(projects) {
        let tbody = '';
        let row = 1;
        projects.forEach(data => {
            // Convert role to readable form:
            const roleReadable = formatRole(data.role);

            tbody += `<tr>
            <td>${row++}</td>
            <td>${data.project_name}</td>
            <td>${data.user_name}</td>
            <td>${data.email}</td>
            <td>${roleReadable}</td> <!-- Changed this line -->
            <td>
                <button class="btn btn-sm btn-primary edit-btn" 
                    data-project="${data.project_id}" 
                    data-id="${data.id}"   
                    data-user="${data.user_id}" 
                    data-role="${data.role}">
                    Edit
                </button>
                <button class="btn btn-sm btn-outline-success me-1 permission-btn" 
                    data-project="${data.project_id}" 
                    data-user="${data.user_id}" 
                    data-role="${data.role}" 
                    title="Permissions">
                    <i class="fas fa-user-shield"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-btn" 
                    data-project="${data.project_id}" 
                    data-user="${data.user_id}">
                    Delete
                </button>
            </td>
        </tr>`;
        });
        $('#projectTableBody').html(tbody);
    }

    // helper function to format role
    function formatRole(role) {
        if (!role) return '';
        return role.replace(/_/g, ' ')
            .replace(/\b\w/g, l => l.toUpperCase()); // capitalize first letters
    }


    function renderUserOptions(users) {
        let options = '<option value="">Select User</option>';
        users.forEach(user => {
            options += `<option value="${user.id}">${user.first_name} ${user.last_name}</option>`;
        });
        $('#userSelect').html(options);
    }

    function renderProjectOptions(projects) {
        let uniqueProjects = [];
        projects.forEach(project => {
            if (!uniqueProjects.some(p => p.project_id === project.project_id)) {
                uniqueProjects.push({ project_id: project.project_id, project_name: project.project_name });
            }
        });

        let options = '<option value="">Select Project</option>';
        uniqueProjects.forEach(project => {
            options += `<option value="${project.project_id}">${project.project_name}</option>`;
        });
        $('#projectSelect').html(options);
    }

    $(document).on('click', '.edit-btn', function () {
        editPivotId = $(this).data('id'); // Pivot ID here
        editProjectId = $(this).data('project');
        editUserId = $(this).data('user');
        const role = $(this).data('role');

        $('#modalTitle').text('Edit User Assignment');
        $('#projectSelect').val(editProjectId).prop('disabled', false);
        $('#userSelect').val(editUserId).prop('disabled', false);
        $('#roleInput').val(role).closest('.role-container').show();
        $('#createProjectModal').modal('show');
    });

    $(document).on('change', '#userSelect', function () {
        const userId = $(this).val();
        if (userId) {
            axios.get(`/user/${userId}/roles`)
                .then(res => {
                    const roles = res.data.roles.join(', ');
                    $('#roleInput').val(roles);
                    $('.role-container').show();
                })
                .catch(() => toastr.error('Failed to fetch user role.'));
        } else {
            $('#roleInput').val('');
            $('.role-container').hide();
        }
    });

    $('#assignUsersForm').submit(function (e) {
        e.preventDefault();
        const project_id = $('#projectSelect').val();
        const user_id = $('#userSelect').val();
        const role = $('#roleInput').val();

        if (!project_id || !user_id || !role) {
            toastr.warning('Please fill all fields.');
            return;
        }

        showLoader();

        // PUT Request for Edit only
        axios.put(`/projects/permissions/${editPivotId}`, {
            project_id,
            user_id,
            role
        })
            .then(res => {
                toastr.success(res.data.message || 'Assignment updated!');
                $('#createProjectModal').modal('hide');
                fetchProjects();
            })
            .catch(err => {
                toastr.error(err.response?.data?.message || 'Update failed.');
            })
            .finally(() => hideLoader());
    });

    $(document).on('click', '.delete-btn', function () {
        if (confirm('Are you sure?')) {
            const project_id = $(this).data('project');
            const user_id = $(this).data('user');

            showLoader();
            axios.delete(`/projects/permissions/${project_id}/${user_id}`)
                .then(res => {
                    toastr.success(res.data.message || 'User removed!');
                    fetchProjects();
                })
                .catch(err => {
                    toastr.error(err.response?.data?.message || 'Delete failed.');
                })
                .finally(() => hideLoader());
        }
    });

    // === PERMISSIONS MODAL ===
    $(document).on('click', '.permission-btn', function () {
        const userId = $(this).data('user');
        const roleName = $(this).data('role');

        $('#permUserId').val(userId);
        $('#permRoleName').val(roleName);
        $('#permissionsList').html('<p>Loading permissions...</p>');

        const permModalEl = document.getElementById('permissionsModal');
        const permModal = bootstrap.Modal.getInstance(permModalEl) || new bootstrap.Modal(permModalEl);
        permModal.show();

        axios.get(`/roles/${roleName}/permissions`)
            .then(response => {
                const allPermissions = response.data.all_permissions;
                const assignedPermissions = response.data.role_permissions;

                let html = '';
                allPermissions.forEach(perm => {
                    const checked = assignedPermissions.includes(perm.name) ? 'checked' : '';
                    html += `
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="${perm.name}" id="perm_${perm.id}" ${checked}>
                                <label class="form-check-label" for="perm_${perm.id}">${perm.name}</label>
                            </div>
                        </div>`;
                });

                $('#permissionsList').html(html);
            })
            .catch(() => {
                $('#permissionsList').html('<p class="text-danger">Failed to load permissions.</p>');
            });
    });

    $('#permissionsForm').submit(function (e) {
        e.preventDefault();

        const userId = $('#permUserId').val();
        const roleName = $('#permRoleName').val();

        const selectedPermissions = [];
        $('#permissionsList input[type=checkbox]:checked').each(function () {
            selectedPermissions.push($(this).val());
        });

        axios.post(`/roles/${roleName}/permissions/update`, {
            user_id: userId,
            permissions: selectedPermissions
        })
            .then(res => {
                toastr.success(res.data.message || 'Permissions updated successfully.');
                $('#permissionsModal').modal('hide');
            })
            .catch(err => {
                toastr.error(err.response?.data?.message || 'Failed to update permissions.');
            });
    });

    fetchProjects();
});
