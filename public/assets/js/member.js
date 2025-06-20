$(document).ready(function () {
    let isEditMode = false;
    let editMemberId = null;
    let projectRolesMap = {}; // Project ID to Roles mapping

    function showLoader() { $('#loader').show(); }
    function hideLoader() { $('#loader').hide(); }

    function loadProjects(selectedProjectId = null) {
        let options = '<option value="">Select Project</option>';
        projects.forEach(project => {
            options += `<option value="${project.id}" ${selectedProjectId == project.id ? 'selected' : ''}>${project.name}</option>`;
        });
        $('#projectId').html(options);
    }

    function updateRolesRadioButtons(projectId) {
        $('input[name="roles"]').prop('checked', false);
        if (projectId && projectRolesMap[projectId]) {
            const role = projectRolesMap[projectId][0]; // only one role expected
            $(`input[name="roles"][value="${role}"]`).prop('checked', true);
        }
    }

    // New Member Modal Open
    $('#openCreateMemberModal').click(function () {
        isEditMode = false;
        editMemberId = null;
        projectRolesMap = {};
        clearForm();
        $('#memberModalLabel').text('Add Member');
        $('#passwordGroup').show();
        $('#password').attr('required', true);
        loadProjects();
        updateRolesRadioButtons(null);
        $('#memberModal').modal('show');
    });

    // Edit Member Modal Open
    $(document).on('click', '.edit-btn', function () {
        isEditMode = true;
        editMemberId = $(this).data('id');
        projectRolesMap = {};
        showLoader();
        axios.get(`/users/members/${editMemberId}/edit`)
            .then(res => {
                const member = res.data.member;
                const projectsWithRoles = res.data.projects_with_roles;

                fillForm(member);
                $('#memberModalLabel').text('Edit Member');
                $('#saveMemberBtn').text('Update Member');
                $('#passwordGroup').hide();
                $('#password').removeAttr('required');

                projectsWithRoles.forEach(p => {
                    projectRolesMap[p.id] = p.role ? p.role.split(',') : [];
                });

                const selectedProjectId = projectsWithRoles.length ? projectsWithRoles[0].id : null;
                loadProjects(selectedProjectId);
                updateRolesRadioButtons(selectedProjectId);

                $('#memberModal').modal('show');
            })
            .catch(() => toastr.error('Failed to load member.'))
            .finally(() => hideLoader());
    });

    $('#projectId').change(function () {
        const selectedProjectId = $(this).val();
        updateRolesRadioButtons(selectedProjectId);
    });

    // Form Submit (Create or Update)
    $('#memberForm').on('submit', function (e) {
        e.preventDefault();

        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        $('#formErrors').addClass('d-none').empty();

        const formData = {
            first_name: $('#firstName').val(),
            last_name: $('#lastName').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            project_id: $('#projectId').val(),
            roles: $('input[name="roles"]:checked').val() // radio button — only one value
        };

        if (isEditMode && !formData.password) delete formData.password;

        showLoader();

        const request = isEditMode
            ? axios.put(`/users/members/${editMemberId}`, formData)
            : axios.post('/users/members', formData);

        request.then(res => {
            $('#memberModal').modal('hide');
            $('#memberForm').removeClass('was-validated');
            clearForm();
            $('#member-table').DataTable().ajax.reload(); // Reload DataTable
            toastr.success(res.data.message);
        }).catch(err => {
            if (err.response && err.response.data) {
                const res = err.response.data;
                if (res.errors) {
                    let messages = Object.values(res.errors).flat().join('<br>');
                    showErrors(messages);
                    toastr.error('Please correct the form errors.');
                } else if (res.message) {
                    showErrors(res.message);
                    toastr.error(res.message);
                } else {
                    showErrors('An unexpected error occurred.');
                    toastr.error('Unexpected error.');
                }
            } else {
                toastr.error('Operation failed.');
            }
        }).finally(() => hideLoader());
    });

    // Delete Member
    $(document).on('click', '.delete-btn', function () {
        if (confirm('Are you sure?')) {
            const memberId = $(this).data('id');
            showLoader();
            axios.delete(`/users/members/${memberId}`)
                .then(res => {
                    $('#member-table').DataTable().ajax.reload(); // Reload DataTable
                    toastr.success(res.data.message);
                })
                .catch(() => toastr.error('Delete failed.'))
                .finally(() => hideLoader());
        }
    });

    // Helpers
    function clearForm() {
        $('#memberId').val('');
        $('#memberForm')[0].reset();
        $('#memberForm').removeClass('was-validated');
        $('#formErrors').addClass('d-none').empty();
    }

    function fillForm(data) {
        $('#memberId').val(data.id || '');
        $('#firstName').val(data.first_name || '');
        $('#lastName').val(data.last_name || '');
        $('#email').val(data.email || '');
        $('#password').val('');
        $('#projectId').val(data.project_id || '');
        // Roles radio will be updated separately
        $('#formErrors').addClass('d-none').empty();
        $('#memberForm').removeClass('was-validated');
    }

    function showErrors(message) {
        $('#formErrors').removeClass('d-none').html(message);
    }
});
