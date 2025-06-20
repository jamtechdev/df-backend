@extends('layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <h5 class="card-header bg-primary text-white d-flex justify-content-between align-items-center">Project Users</h5>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped', 'id' => 'nationalParksTable'], true) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign User Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered"> <!-- XL Modal for Big Size -->
        <form id="assignUsersForm" novalidate>
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="modalTitle">Assign User to Project</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-4">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <select id="projectSelect" class="form-select" required>
                                <option value="">Select Project</option>
                            </select>
                            <div class="invalid-feedback">Please select a project.</div>
                        </div>

                        <div class="col-12 mb-2">
                            <select id="userSelect" class="form-select" required>
                                <option value="">Select User</option>
                            </select>
                            <div class="invalid-feedback">Please select a user.</div>
                        </div>

                        <!-- Radio Buttons for Role (Dynamic) -->
                        <div class="col-12 role-container d-none mt-3">
                            <label class="form-label">Select Role:</label>
                            <div id="roleRadioGroup" class="d-flex flex-wrap gap-3"></div> <!-- Radio buttons render here -->
                            <div class="invalid-feedback">Role is required.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 rounded-bottom-4">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i>Save
                    </button>
                </div>
            </div>
        </form>
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
                    <button type="button" class="btn btn-info" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Permissions</button>
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
{!! $dataTable->scripts() !!}
<script src="{{ asset('assets/js/project.js') }}"></script>


@endpush