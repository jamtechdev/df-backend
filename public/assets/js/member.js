$(document).ready(function () {
    let isEditMode = false;
    let editMemberId = null;
    let projectRolesMap = {}; // To store project id to roles mapping

    function showLoader() { $('#loader').show(); }
    function hideLoader() { $('#loader').hide(); }

    function loadProjects(selectedProjectId = null) {
        let options = '<option value="">Select Project</option>';
        projects.forEach(project => {
            options += `<option value="${project.id}" ${selectedProjectId == project.id ? 'selected' : ''}>${project.name}</option>`;
        });
        $('#projectId').html(options);
    }

    function updateRolesCheckboxes(projectId) {
        $('input[name="roles[]"]').prop('checked', false);
        if (projectId && projectRolesMap[projectId]) {
            const roles = projectRolesMap[projectId];
            roles.forEach(role => {
                $(`input[name="roles[]"][value="${role}"]`).prop('checked', true);
            });
        }
    }

    function fetchMembers() {
        showLoader();
        axios.get('/users/members/data')
            .then(response => renderTable(response.data.members))
            .catch(() => toastr.error('Failed to fetch members.'))
            .finally(() => hideLoader());
    }

    function renderTable(members) {
        let tbody = '';
        let row = 1;
        members.forEach(member => {
            const roles = member.roles.map(r => r.name).join(', ');
            tbody += `<tr>
                <td>${row++}</td>
                <td>${member.first_name}</td>
                <td>${member.last_name}</td>
                <td>${member.email}</td>
                <td>${roles}</td>
                <td>
                    <button class="btn btn-sm btn-primary edit-btn" data-id="${member.id}">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${member.id}">Delete</button>
                </td>
            </tr>`;
        });
        $('#membersTableBody').html(tbody);
    }

    $('#openCreateMemberModal').click(function () {
        isEditMode = false;
        editMemberId = null;
        projectRolesMap = {};
        $('#memberModalTitle').text('Add Member');
        $('#memberForm')[0].reset();
        $('#passwordGroup').show();
        $('#password').attr('required', true);
        loadProjects();
        updateRolesCheckboxes(null);
        $('#memberModal').modal('show');
    });

    $(document).on('click', '.edit-btn', function () {
        isEditMode = true;
        editMemberId = $(this).data('id');
        projectRolesMap = {};
        showLoader();
        axios.get(`/users/members/${editMemberId}/edit`)
            .then(res => {
                const member = res.data.member;
                const projectsWithRoles = res.data.projects_with_roles;

                $('#memberModalTitle').text('Edit Member');
                $('#saveMemberBtn').text('Update Member');
                $('#memberId').val(member.id);
                $('#firstName').val(member.first_name);
                $('#lastName').val(member.last_name);
                $('#email').val(member.email);
                $('#passwordGroup').hide();
                $('#password').removeAttr('required');

                // Build projectRolesMap from projectsWithRoles
                projectsWithRoles.forEach(p => {
                    projectRolesMap[p.id] = p.role ? p.role.split(',') : [];
                });

                // Select the first project if exists
                const selectedProjectId = projectsWithRoles.length ? projectsWithRoles[0].id : null;
                loadProjects(selectedProjectId);
                updateRolesCheckboxes(selectedProjectId);

                $('#memberModal').modal('show');
            })
            .catch(() => toastr.error('Failed to load member.'))
            .finally(() => hideLoader());
    });

    // Update roles checkboxes when project selection changes
    $('#projectId').change(function () {
        const selectedProjectId = $(this).val();
        updateRolesCheckboxes(selectedProjectId);
    });

    $(document).on('submit', '#memberForm', function (e) {
        e.preventDefault();

        console.log('Form submit triggered');
        console.log('isEditMode:', isEditMode);
        console.log('editMemberId:', editMemberId);

        const formData = {
            first_name: $('#firstName').val(),
            last_name: $('#lastName').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            project_id: $('#projectId').val(),
            roles: $('input[name="roles[]"]:checked').map(function () { return this.value; }).get()
        };

        if (isEditMode && !formData.password) delete formData.password;

        showLoader();

        const request = isEditMode
            ? axios.put(`/users/members/${editMemberId}`, formData)
            : axios.post('/users/members', formData);

        request.then(res => {
            toastr.success(res.data.message);
            $('#memberModal').modal('hide');
            fetchMembers();
        }).catch(err => {
            toastr.error(err.response?.data?.message || 'Operation failed.');
        }).finally(() => hideLoader());
    });

    $(document).on('click', '.delete-btn', function () {
        if (confirm('Are you sure?')) {
            const memberId = $(this).data('id');
            showLoader();
            axios.delete(`/users/members/${memberId}`)
                .then(res => {
                    toastr.success(res.data.message);
                    fetchMembers();
                })
                .catch(() => toastr.error('Delete failed.'))
                .finally(() => hideLoader());
        }
    });

    fetchMembers();
});
