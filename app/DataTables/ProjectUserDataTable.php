<?php

namespace App\DataTables;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class ProjectUserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param mixed $query
     */
    public function dataTable($query)
{
    return datatables()
        ->of($query)
        ->addColumn('actions', function ($row) {
            $editButton = '<button class="btn btn-sm btn-primary edit-btn me-1" 
                                data-id="' . $row->id . '" 
                                data-project="' . $row->project_id . '" 
                                data-user="' . $row->user_id . '" 
                                data-role="' . $row->role . '">
                                Edit
                           </button>';

            $permissionButton = '<button class="btn btn-sm btn-outline-success permission-btn me-1" 
                                      data-project="' . $row->project_id . '" 
                                      data-user="' . $row->user_id . '" 
                                      data-role="' . $row->role . '" 
                                      title="Permissions">
                                      <i class="fas fa-user-shield"></i>
                                 </button>';

            $deleteButton = '<button class="btn btn-sm btn-danger delete-btn" 
                                   data-project="' . $row->project_id . '" 
                                   data-user="' . $row->user_id . '">
                                   <i class="fa-solid fa-trash me-1"></i> Delete
                             </button>';

            return $editButton . $permissionButton . $deleteButton;
        })
        ->rawColumns(['actions'])
        ->setRowId('id');
}


    /**
     * Get the query source of dataTable.
     */
    public function query()
    {
        return DB::table('project_user')
            ->join('projects', 'projects.id', '=', 'project_user.project_id')
            ->join('users', 'users.id', '=', 'project_user.user_id')
            ->select(
                'project_user.id',
                'projects.id as project_id',
                'projects.name as project_name',
                'users.id as user_id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"),
                'users.email',
                'project_user.role'
            );
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('projectTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('
                <"row"<"col-md-6 d-flex justify-content-start mb-2"f><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"lB>>
                <"row"<"col-md-12"tr>>
                <"row"<"col-md-6"i><"col-md-6"p>>
            ')
            ->orderBy(1)
            ->language(["search" => "", "lengthMenu" => "_MENU_", "searchPlaceholder" => 'Search'])
            ->parameters([
                'paging' => true,
                'lengthMenu' => [
                    [5, 10, 25, 50, -1],
                    ['5', '10', '25', '50', 'Show all']
                ],
                'processing' => true,
                'serverSide' => true,
                'initComplete' => 'function () {
                    var table = this.api();
                    $(document).on("click", ".delete-btn", function () {
                        if (confirm("Are you sure you want to remove this user from the project?")) {
                            var projectId = $(this).data("project-id");
                            var userId = $(this).data("user-id");
                            var loader = $("#loader");
                            loader.show();
                            $.ajax({
                                url: "/admin/project-user/" + projectId + "/" + userId,
                                type: "DELETE",
                                headers: { "X-CSRF-TOKEN": $("meta[name=\'csrf-token\']").attr("content") },
                                success: function (res) {
                                    toastr.success(res.message);
                                    table.ajax.reload(null, false);
                                },
                                error: function () {
                                    toastr.error("Remove failed.");
                                },
                                complete: function () {
                                    loader.hide();
                                }
                            });
                        }
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
            Column::make('id')->title('#'),
            Column::make('project_name')->title('Project'),
            Column::make('user_name')->title('User'),
            Column::make('email')->title('Email'),
            Column::make('role')->title('Role'),
            Column::computed('actions')
                ->exportable(false)
                ->printable(false)
                ->width(300)
                ->addClass('text-center'),
        ];
    }
}
