@extends('layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <h5 class="card-header bg-primary text-white d-flex justify-content-between align-items-center">Manage Members</h5>
                <div class="card-body">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Member Modal -->
<div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="memberForm" novalidate>
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-gradient bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="memberModalLabel">
                        <i class="fa fa-user me-2"></i>Add Member
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-4">
                    <input type="hidden" id="memberId" name="memberId">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstName" name="first_name" required maxlength="255" placeholder="First Name">
                            <div class="invalid-feedback">First name is required.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastName" name="last_name" required maxlength="255" placeholder="Last Name">
                            <div class="invalid-feedback">Last name is required.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required maxlength="255" placeholder="Email">
                            <div class="invalid-feedback">Valid email is required.</div>
                        </div>

                        <div class="col-md-6" id="passwordGroup">
                            <label for="password" class="form-label">Password <span class="text-danger" id="passwordRequiredMark">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="off" maxlength="255" placeholder="Password" required>
                            <div class="invalid-feedback">Password is required.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="projectId" class="form-label">Project <span class="text-danger">*</span></label>
                            <select id="projectId" name="project_id" class="form-select" required>
                                <option value="">Select Project</option>
                            </select>
                            <div class="invalid-feedback">Please select a project.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Roles <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="roleContentManager" name="roles" value="content_manager" required>
                                    <label class="form-check-label" for="roleContentManager">Content Manager</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="roleManager" name="roles" value="manager" required>
                                    <label class="form-check-label" for="roleManager">Manager</label>
                                </div>
                            </div>
                            <div class="invalid-feedback">Please select a role.</div>
                        </div>

                    </div>

                    <div id="formErrors" class="alert alert-danger d-none mt-3"></div>
                </div>
                <div class="modal-footer bg-light border-0 rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveMemberBtn">
                        <i class="fa fa-save me-1"></i>Save
                    </button>
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
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script src="{{ asset('assets/js/member.js') }}"></script>
@endpush