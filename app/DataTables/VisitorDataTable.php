<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VisitorDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param \Illuminate\Database\Eloquent\Builder<User> $query
     */
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('blocked', function ($row) {
                $checked = $row->is_blocked ? 'checked' : '';
                return '<div class="form-check form-switch">
                            <input class="form-check-input toggle-block" type="checkbox" role="switch" data-id="' . $row->id . '" ' . $checked . '>
                        </div>';
            })
            ->addColumn('visited_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('actions', function ($row) {
                $buttons = [
                    [
                        'href' => 'javascript:void(0);',
                        'class' => 'btn-danger btn-sm delete-btn',
                        'icon' => 'fa-solid fa-trash',
                        'text' => 'Delete',
                        'data' => ['id' => $row->id],
                    ],
                ];
                return view('components.datatable.colunms.buttons', ['data' => $buttons])->render();
            })
            ->rawColumns(['blocked', 'actions'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model)
    {
        return $model->newQuery()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'reader');
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('visitors-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('
                <"row"<"col-md-6 d-flex justify-content-start mb-2 mb-2"f><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"lB>>
                <"row"<"col-md-12"tr>>
                <"row"<"col-md-6"i><"col-md-6"p>>
            ')
            ->orderBy(4)
            ->language(["search" => "", "lengthMenu" => "_MENU_", "searchPlaceholder" => 'Search Visitors'])
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
                    $(document).on("change", ".toggle-block", function () {
                        var id = $(this).data("id");
                        var isChecked = $(this).prop("checked");
                        var csrfToken = $("meta[name=\'csrf-token\']").attr("content");
                        $.ajax({
                            headers: { "X-CSRF-TOKEN": csrfToken },
                            type: "POST",
                            url: "/users/visitors/" + id + "/toggle-block",
                            dataType: "json",
                            success: function () {
                                toastr.success("Visitor block status updated");
                                table.ajax.reload(null, false);
                            },
                            error: function () {
                                toastr.error("Failed to update block status");
                            }
                        });
                    });
                    $(document).on("click", ".delete-btn", function () {
                        if (confirm("Are you sure you want to delete this visitor?")) {
                            var id = $(this).data("id");
                            var loader = $("#loader");
                            loader.show();
                            $.ajax({
                                url: "/users/visitors/" + id,
                                type: "DELETE",
                                headers: { "X-CSRF-TOKEN": $("meta[name=\'csrf-token\']").attr("content") },
                                success: function (res) {
                                    toastr.success(res.message);
                                    table.ajax.reload(null, false);
                                },
                                error: function () {
                                    toastr.error("Delete failed.");
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
            Column::make('first_name')->title('First Name'),
            Column::make('last_name')->title('Last Name'),
            Column::make('email')->title('Email'),
            Column::computed('blocked')->title('Blocked')->orderable(false)->searchable(false),
            Column::computed('visited_at')->title('Visited At'),
            Column::computed('actions')
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
        return 'Visitors_' . date('YmdHis');
    }
}
