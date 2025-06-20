<?php

namespace App\DataTables;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;


class ThemesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Theme> $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? Carbon::parse($row->created_at)->format('d/m/Y') : '';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="d-flex gap-1 justify-content-center">
                <button class="btn btn-sm btn-primary edit-btn"
                    data-id="' . $row->id . '"
                    data-name="' . $row->name . '"
                    data-root="' . $row->root . '"
                >Edit</button>
                <button class="btn btn-sm btn-danger delete-btn"
             data-id="' . $row->id . '">Delete</button>
                </div>';
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Theme $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'asc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('themes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('
                <"row"<"col-md-6 d-flex justify-content-start"f><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"lB>>
                <"row"<"col-md-12"tr>>
                <"row"<"col-md-6"i><"col-md-6"p>>
            ')
            ->orderBy(1)
            ->language([
                "search" => "",
                "lengthMenu" => "_MENU_",
                "searchPlaceholder" => 'Search Themes'
            ])
            ->buttons(
                Button::make()
                    ->className('btn btn-primary')
                    ->text('<i class="fa fa-plus"></i> New')
                    ->visible(true)
                    ->action('function(e, dt, node, config) {
                             $("#themeModal").modal("show");
                          }')
            )
            ->parameters([
                'paging' => true,
                'lengthMenu' => [
                    [5, 10, 25, 50, -1],
                    ['5', '10', '25', '50', 'Show all']
                ],
                'initComplete' => 'function () {
                    var selectedIds = [];

                    function logSelectedIds() {
                        console.log(selectedIds);
                    }

                    $("#check-all").on("change", function () {
                        var isChecked = $(this).prop("checked");
                        $(".row-checkbox").prop("checked", isChecked);
                        selectedIds = isChecked
                            ? $(".row-checkbox:checked").map(function () { return $(this).val(); }).get()
                            : [];
                        logSelectedIds();
                    });

                    $(document).on("change", ".row-checkbox", function () {
                        var rowId = $(this).val();
                        if ($(this).prop("checked")) {
                            selectedIds.push(rowId);
                        } else {
                            var index = selectedIds.indexOf(rowId);
                            if (index !== -1) {
                                selectedIds.splice(index, 1);
                            }
                        }
                        logSelectedIds();
                        $("#check-all").prop("checked", $(".row-checkbox:checked").length === $(".row-checkbox").length);
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
            Column::make('name')->title('Theme Name'),
            Column::make('slug')->title('Slug'),
            Column::make('created_at')->title('Created At'),
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
        return 'Themes_' . date('YmdHis');
    }
}
