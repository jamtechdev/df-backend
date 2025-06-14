@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Members</h4>
            <button class="btn btn-primary" id="openCreateMemberModal"><i class="fa-solid fa-plus me-2"></i>Add Member</button>
        </div>
        <div class="theme-table">
            <table class="table" id="membersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="membersTableBody"></tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<!-- Create/Edit Member Modal -->
<div class="modal fade" id="memberModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <form id="memberForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="memberModalTitle">Add Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="memberId" />
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" id="firstName" name="first_name" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" id="lastName" name="last_name" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required />
                    </div>
                    <div class="mb-3" id="passwordGroup">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required autocomplete="off" />
                    </div>
                    <div class="mb-3">
                        <label for="projectId" class="form-label">Project</label>
                        <select id="projectId" name="project_id" class="form-select" required>
                            <option value="">Select Project</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Roles</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="roleContentManager" name="roles[]" value="content_manager" />
                                <label class="form-check-label" for="roleContentManager">Content Manager</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="roleManager" name="roles[]" value="manager" />
                                <label class="form-check-label" for="roleManager">Manager</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveMemberBtn">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Loader -->
<div id="loader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:9999;">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<!-- Pass projects from PHP to JS -->
<script>
    const projects = @json($projects);
</script>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/member.js') }}"></script>
@endpush
