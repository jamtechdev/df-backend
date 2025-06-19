<?php

namespace App\DataTables;

use App\Models\NationalParkTranslation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NationalParkTranslationDataTable extends DataTable
{
    protected $national_park_id;

    public function setNationalParkId($id)
    {
        $this->national_park_id = $id;
    }
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('national_park_name', function (NationalParkTranslation $translation) {
                return $translation->nationalPark ? $translation->nationalPark->name : '';
            })
            ->addColumn('theme_name', function (NationalParkTranslation $translation) {
                return $translation->theme ? $translation->theme->name : '';
            })
            ->addColumn('actions', function (NationalParkTranslation $translation) {
                $buttons = [
                    [
                        'href' => route('national-parks.translations.edit', ['id' => $translation->id]),
                        'class' => 'btn-info btn-sm btn-edit', // small button, margin-end for spacing
                        'icon' => 'fa-solid fa-pen-to-square',
                        'text' => 'Edit',
                        'data' => ['id' => $translation->id],
                    ],
                    [
                        'href' => url('/national-parks/content-blocks/' . $translation->id),
                        'class' => 'btn-info btn-sm mb-2',
                        'icon' => 'fa-solid fa-plus',
                        'text' => 'Content',
                        'data' => ['id' => $translation->id],
                    ],
                    [
                        'href' => route('national-parks.translations.hidden-wonders.index', ['translation' => $translation->id]),
                        'class' => 'btn-warning btn-sm',
                        'icon' => 'fa-solid fa-search',
                        'text' => 'Hidden Wonders',
                        'data' => ['id' => $translation->id],
                    ],
                    [
                        'href' => 'javascript:void(0);',
                        'class' => 'btn-danger btn-sm btn-delete',
                        'icon' => 'fa-solid fa-trash',
                        'text' => 'delete',
                        'data' => ['id' => $translation->id, 'national-park-id' => $translation->national_park_id],
                    ],
                ];

                return view('components.datatable.colunms.buttons', ['data' => $buttons])->render();
            })

            ->rawColumns(['actions'])
            ->editColumn('published_at', function (NationalParkTranslation $translation) {
                return $translation->published_at ? $translation->published_at->format('Y-m-d H:i:s') : 'N/A';
            })
            ->editColumn('created_at', function (NationalParkTranslation $translation) {
                return $translation->created_at ? $translation->created_at->format('Y-m-d H:i:s') : 'N/A';
            })
            ->editColumn('updated_at', function (NationalParkTranslation $translation) {
                return $translation->updated_at ? $translation->updated_at->format('Y-m-d H:i:s') : 'N/A';
            });
    }

    public function query(NationalParkTranslation $model): QueryBuilder
    {
        return $model->newQuery()->with(['nationalPark', 'theme']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('nationalParkTranslationsTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('
                <"row"<"col-md-6 d-flex justify-content-start"f><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"lB>>
                <"row"<"col-md-12"tr>>
                <"row"<"col-md-6"i><"col-md-6"p>>
            ')
            ->orderBy(4)
            ->language(["search" => "", "lengthMenu" => "_MENU_", "searchPlaceholder" => 'Search'])
            ->buttons([
                Button::make()
                    ->className('btn btn-primary')
                    ->text('<i class="fa fa-plus"></i> New')
                    ->action('function(e, dt, node, config) {
            window.location.href = "' . route('national-parks.translations.create', $this->national_park_id) . '";
        }')
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
            Column::make('national_park_name')->title('National Park'),
            Column::make('language_code')->title('Lang'),
            Column::make('theme_name')->title('Theme'),
            Column::make('published_at')->title('Published At'),
            Column::make('created_at')->title('Created At'),
            Column::make('updated_at')->title('Updated At'),
            Column::make('status')->title('Status'),
            Column::computed('actions')
                ->exportable(false)
                ->printable(false)
                ->width(250)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'NationalParkTranslations_' . date('YmdHis');
    }
}
