@extends('layouts.app')
@section('title', 'Manage Members')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <h5 class="card-header bg-primary text-white d-flex justify-content-between align-items-center">Manage Themes</h5>
                    <div class="card-body">
                        {{ $dataTable->table() }}

                        <!-- Modal -->
                        <div class="modal fade" id="themeModal" tabindex="-1" aria-labelledby="themeModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form id="themeForm">
                                    @csrf

                                    <input type="hidden" id="updateThemeId" name="id">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="themeModalLabel">Add New Theme</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="themeName" class="form-label">Theme Name</label>
                                                <input type="text" class="form-control" id="themeName" name="name"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="root" class="form-label">Root</label>
                                                <input type="text" class="form-control" id="root" name="root">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Save Theme</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).ready(function() {
            // For open modal and reset form
            $('#themeModal').on('hidden.bs.modal', function() {
                $('#themeForm')[0].reset();
                $('#themeForm').attr('data-mode', 'create');
                $('#updateThemeId').val('');
            });

            // Open modal for editing
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const root = $(this).data('root');

                $('#themeName').val(name);
                $('#root').val(root);
                $('#updateThemeId').val(id);
                $('#themeForm').attr('data-mode', 'edit');

                $('#themeModal').modal('show');
            });

            // Handle create or update
            $('#themeForm').on('submit', function(e) {
                e.preventDefault();

                let mode = $(this).attr('data-mode');
                let id = $('#updateThemeId').val();
                let url = mode === 'edit' ? `/themes/${id}` : "{{ route('themes.store') }}";
                let data = $(this).serialize();

                // Add _method=PUT for update
                if (mode === 'edit') {
                    data += '&_method=post';
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        $('#themeModal').modal('hide');
                        $('#themes-table').DataTable().ajax.reload();
                        alert(response.message);
                    },
                    error: function() {
                        alert('Operation failed!');
                    }
                });
            });


            // Delete theme
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this theme?')) {
                    $.ajax({
                        url: `/themes/${id}`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            $('#themes-table').DataTable().ajax.reload();
                            alert(response.message);
                        },
                        error: function() {
                            alert('Failed to delete theme!');
                        }
                    });
                }
            });
        });
    </script>
@endpush
