<?php

namespace App\DataTables;

use App\Models\NationalPark;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NationalParkDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('theme_name', function (NationalPark $park) {
                return $park->theme ? $park->theme->name : '';
            })
            ->addColumn('is_featured_checkbox', function (NationalPark $park) {
                $checked = $park->is_featured ? 'checked' : '';
                return '<div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input is-featured-toggle" data-id="' . $park->id . '" ' . $checked . '>
                        </div>';
            })
            ->addColumn('actions', function (NationalPark $park) {
                $buttons = [
                    [
                        'href' => 'javascript:void(0);',
                        'class' => 'btn-info btn-edit ml-1',
                        'icon' => 'fa-solid fa-pen-to-square',
                        'text' => '',
                        'data' => ['id' => $park->id], // All data attributes here
                    ],
                    [
                        'href' => 'javascript:void(0);',
                        'class' => 'btn-secondary btn-translation',
                        'icon' => 'fa-solid fa-language',
                        'text' => '',
                        'data' => ['id' => $park->id],
                    ],
                    [
                        'href' => url('/media/' . $park->id),
                        'class' => 'btn-warning',
                        'icon' => 'fa-solid fa-image',
                        'text' => '',
                        'data' => ['id' => $park->id],
                    ],
                    [
                        'href' => 'javascript:void(0);',
                        'class' => 'btn-danger btn-delete',
                        'icon' => 'fa-solid fa-trash',
                        'text' => '',
                        'data' => ['id' => $park->id],
                    ],
                ];

                return view('components.datatable.colunms.buttons', ['data' => $buttons])->render();
            })

            ->rawColumns(['is_featured_checkbox', 'actions'])
            ->editColumn('created_at', function (NationalPark $park) {
                return $park->created_at ? $park->created_at->format('Y-m-d H:i:s') : 'N/A';
            })
            ->editColumn('updated_at', function (NationalPark $park) {
                return $park->updated_at ? $park->updated_at->format('Y-m-d H:i:s') : 'N/A';
            });
    }

    public function query(NationalPark $model): QueryBuilder
    {
        return $model->newQuery()->with('theme');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('nationalParksTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('
                <"row"<"col-md-6 d-flex justify-content-start"f><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"lB>>
                <"row"<"col-md-12"tr>>
                <"row"<"col-md-6"i><"col-md-6"p>>
            ')
            ->orderBy(1)
            ->language(["search" => "", "lengthMenu" => "_MENU_", "searchPlaceholder" => 'Search'])
            ->buttons([
                Button::make()
                    ->text('<i class="fa fa-plus"></i> New')
                    ->attr([
                        'id' => 'btnAdd',
                        'class' => 'btn btn-primary'
                    ])
            ])

            ->parameters([
                'paging' => true,
                'lengthMenu' => [
                    [10, 15, 25, 50, -1],
                    ['10', '15', '25', '50', 'Show all']
                ],
                'initComplete' => "function(settings, json) {
                    $('[data-bs-toggle=\"tooltip\"]').tooltip();
                }",
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('theme_name')->title('Default Theme'),
            Column::make('name')->title('Name'),
            Column::make('slug')->title('Slug'),
            Column::make('created_at')->title('Created At'),
            Column::make('updated_at')->title('Updated At'),
            Column::computed('is_featured_checkbox')
                ->title('Featured')
                ->orderable(false)
                ->searchable(false),
            Column::computed('actions')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'NationalParks_' . date('YmdHis');
    }
}
