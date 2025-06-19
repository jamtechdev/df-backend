<?php

namespace App\DataTables;

use App\Models\Member;
use App\Models\User;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MemberDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param \Illuminate\Database\Eloquent\Builder<Member> $query
     */
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</button>';
            })
            ->addColumn('roles', function ($row) {
                return $row->roles->pluck('name')->implode(', ');
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model)
    {
        return $model->newQuery()
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['content_manager', 'manager']);
            })
            ->with('roles');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('member-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('
                    <"row"<"col-md-6 d-flex justify-content-start"f><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"lB>>
                    <"row"<"col-md-12"tr>>
                    <"row"<"col-md-6"i><"col-md-6"p>>
                ')
            ->orderBy(1)
            ->language(["search" => "", "lengthMenu" => "_MENU_", "searchPlaceholder" => 'Search Users'])
            ->buttons(
                Button::make()
                    ->className('btn btn-primary')
                    ->text('<i class="fa fa-plus"></i> New')
                    ->visible(true)
                    ->attr([
                        'id' => 'openCreateMemberModal',
                        'class' => 'btn btn-primary'
                    ])
            )
            ->parameters([
                'paging' => true,
                'lengthMenu' => [
                    [5, 10, 25, 50, -1],
                    ['5', '10', '25', '50', 'Show all']
                ],
                'initComplete' => 'function () {
                            $(document).on("change", ".status-switch", function () {
                                var serverId = $(this).data("user-id");
                                var isChecked = $(this).prop("checked");
                                var status = isChecked ? 1 : 0;
                                // Check if CSRF token is available
                                var csrfToken = $("meta[name=\'csrf-token\']").attr(\'content\');
                                var csrfHeader = csrfToken ? {\'X-CSRF-TOKEN\': csrfToken} : {};
                                var ajaxOptions = {
                                    headers: csrfHeader,
                                    type: "POST",
                                    url: `/master/sb-user-b-update-status/${serverId}`,
                                    data: { status: status },
                                    dataType: "json",
                                    success: function(response) {
                                        if(response.success ==true){
                                            toastr.success(response.message)
                                        }
                                        $("#servers-table").DataTable().ajax.reload();
                                    }
                                };
                                $.ajax(ajaxOptions);
                            });
                            var selectedIds = []; // Array to store selected row IDs
                            function logSelectedIds() {
                                console.log(selectedIds);
                                // You can perform further actions with the selectedIds array here
                            }
                            $("#check-all").on("change", function () {
                                var isChecked = $(this).prop("checked");
                                $(".row-checkbox").prop("checked", isChecked);
                                if (isChecked) {
                                    // Get all row IDs and add them to selectedIds
                                    selectedIds = [];
                                    $(".row-checkbox:checked").each(function() {
                                        selectedIds.push($(this).val());
                                    });
                                } else {
                                    // Clear selectedIds
                                    selectedIds = [];
                                }
                                logSelectedIds();
                            });
                            $(document).on("change", ".row-checkbox", function () {
                                var isChecked = $(this).prop("checked");
                                var rowId = $(this).val();
                                if (isChecked) {
                                    // Add row ID to selectedIds
                                    selectedIds.push(rowId);
                                } else {
                                    // Remove row ID from selectedIds
                                    var index = selectedIds.indexOf(rowId);
                                    if (index !== -1) {
                                        selectedIds.splice(index, 1);
                                    }
                                }
                                logSelectedIds();
                                var allChecked = $(".row-checkbox:checked").length === $(".row-checkbox").length;
                                $("#check-all").prop("checked", allChecked);
                            });
                        }',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('first_name')->title('First Name'),
            Column::make('last_name')->title('Last Name'),
            Column::make('email')->title('Email'),
            Column::computed('roles')->title('Roles'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Member_' . date('YmdHis');
    }
}
