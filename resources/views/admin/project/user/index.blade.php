@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
       <div class="col-md-12">
         <div class="d-flex justify-content-between mb-3">
            <h4>Projects & Assigned Users</h4>
        </div>
        <div class="theme-table">
            <table class="table" id="projectTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="projectTableBody"></tbody>
            </table>
        </div>
       </div>
    </div>
</div>

<!-- Assign User Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="assignUsersForm">
                <div class="modal-header">
                    <h5 id="modalTitle">Assign User to Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select id="projectSelect" class="form-select mb-2" required></select>
                    <select id="userSelect" class="form-select mb-2" required></select>
                    <div class="role-container" style="display:none;">
                        <input id="roleInput" name="role" class="form-control mb-2" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Permissions Modal -->
<div class="modal fade" id="permissionsModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <form id="permissionsForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Role Permissions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="permUserId" />
                    <input type="hidden" id="permRoleName" />

                    <div class="mb-3">
                        <strong>Role:</strong> <span id="roleNameDisplay"></span>
                    </div>

                    <div id="permissionsList" class="row gy-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Permissions</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
@endsection

@push('scripts')
<script src="{{ asset('assets/js/project.js') }}"></script>
@endpush
