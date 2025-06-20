$(document).ready(function () {
    let editPivotId = null;
    let editProjectId = null;
    let editUserId = null;
    const allRoles = ['content_manager', 'manager']; // Fixed Roles to show always

    function showLoader() { $('#loader').show(); }
    function hideLoader() { $('#loader').hide(); }



    function fetchProjects() {
        showLoader();
        axios.get('/projects/permissions/data')
            .then(response => {
                const projects = response.data.projects; // Use all projects from backend
                const projectRoles = response.data.project_user_roles;
                const users = response.data.users;
                window.projectsData = projects; // Store projects globally for reuse
                renderUserOptions(users);
                renderProjectOptions(projects, projectRoles);
            })
            .catch(() => toastr.error('Failed to fetch data.'))
            .finally(() => hideLoader());
    }

    function renderUserOptions(users) {
        let options = '<option value="">Select User</option>';
        // Filter users to only those with roles content_manager or manager
        const filteredUsers = users.filter(user => {
            const roles = user.roles || [];
            return roles.includes('content_manager') || roles.includes('manager');
        });
        filteredUsers.forEach(user => {
            options += `<option value="${user.id}">${user.first_name} ${user.last_name}</option>`;
        });
        $('#userSelect').html(options);
    }

    function renderProjectOptions(projects, projectRoles, selectedProjectId = '') {
        console.log(projects, projectRoles);

        let uniqueProjects = [];
        projects.forEach(project => {
            if (!uniqueProjects.some(p => p.project_id === project.project_id)) {
                uniqueProjects.push({ project_id: project.id, project_name: project.name });
            }
        });
        console.log(uniqueProjects);

        let options = '<option value="">Select Project</option>';
        uniqueProjects.forEach(project => {
            const selected = (project.project_id == selectedProjectId) ? 'selected' : '';
            options += `<option value="${project.project_id}" ${selected}>${project.project_name}</option>`;
        });
        $('#projectSelect').html(options);
    }

    function renderRoleRadios(selectedRole = '') {
        let html = '';
        allRoles.forEach(role => {
            const checked = (role === selectedRole) ? 'checked' : '';
            html += `
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="role_${role}" value="${role}" ${checked} required>
                    <label class="form-check-label" for="role_${role}">${role.replace('_', ' ')}</label>
                </div>`;
        });
        $('#roleRadioGroup').html(html);
        $('.role-container').removeClass('d-none');
    }

    $(document).on('click', '.edit-btn', function () {
        editPivotId = $(this).data('id');
        editProjectId = $(this).data('project');
        editUserId = $(this).data('user');
        const roleAttached = $(this).data('role'); // this comes from your table's data-role

        console.log('editProjectId:', editProjectId, 'type:', typeof editProjectId);
        console.log('window.projectsData:', window.projectsData);

        $('#modalTitle').text('Edit User Assignment');
        renderProjectOptions(window.projectsData, null, editProjectId); // Re-render projects with selected project
        $('#projectSelect').prop('disabled', false);
        $('#userSelect').val(editUserId).prop('disabled', false);

        renderRoleRadios(roleAttached); // Always render fixed roles

        const createModalEl = document.getElementById('createProjectModal');
        const createModal = bootstrap.Modal.getInstance(createModalEl) || new bootstrap.Modal(createModalEl);
        createModal.show();
    });

    $(document).on('change', '#userSelect', function () {
        renderRoleRadios(); // When user changes, show both roles but none selected
    });

    $('#assignUsersForm').submit(function (e) {
        e.preventDefault();

        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        const project_id = $('#projectSelect').val();
        const user_id = $('#userSelect').val();
        const role = $('input[name="role"]:checked').val();

        if (!project_id || !user_id || !role) {
            toastr.warning('Please fill all fields.');
            return;
        }

        showLoader();

        axios.put(`/projects/permissions/${editPivotId}`, { project_id, user_id, role })
            .then(res => {
                toastr.success(res.data.message || 'Assignment updated!');
                const createModalEl = document.getElementById('createProjectModal');
                const createModal = bootstrap.Modal.getInstance(createModalEl);
                if (createModal) createModal.hide();
                $('#projectTable').DataTable().ajax.reload(null, false);
            })
            .catch(err => toastr.error(err.response?.data?.message || 'Update failed.'))
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
                    $('#projectTable').DataTable().ajax.reload(null, false);
                })
                .catch(err => toastr.error(err.response?.data?.message || 'Delete failed.'))
                .finally(() => hideLoader());
        }
    });






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
            .catch(() => $('#permissionsList').html('<p class="text-danger">Failed to load permissions.</p>'));
    });

    $('#permissionsForm').submit(function (e) {
        e.preventDefault();

        const userId = $('#permUserId').val();
        const roleName = $('#permRoleName').val();

        const selectedPermissions = [];
        $('#permissionsList input[type=checkbox]:checked').each(function () {
            selectedPermissions.push($(this).val());
        });

        showLoader();

        axios.post(`/roles/${roleName}/permissions/update`, {
            user_id: userId,
            permissions: selectedPermissions
        })
            .then(res => {
                toastr.success(res.data.message || 'Permissions updated successfully.');

                const permModalEl = document.getElementById('permissionsModal');
                const permModal = bootstrap.Modal.getInstance(permModalEl);
                if (permModal) permModal.hide();

                $('#projectTable').DataTable().ajax.reload(null, false);
            })
            .catch(err => toastr.error(err.response?.data?.message || 'Failed to update permissions.'))
            .finally(() => hideLoader());
    });

    fetchProjects();
});
