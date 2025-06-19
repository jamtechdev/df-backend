<?php

namespace App\DataTables;

use App\Models\HiddenWonder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HiddenWonderDataTable extends DataTable
{
    protected $translationId;

    public function setTranslationId($translationId)
    {
        $this->translationId = $translationId;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('action', function (HiddenWonder $hiddenWonder) { // Fixed here: 'action'
                $buttons = [
                    [
                        'href' => 'javascript:void(0);',
                        'class' => 'btn-info btn-sm edit-hidden-wonder',
                        'icon' => 'fa-solid fa-pen-to-square',
                        'text' => 'Edit',
                        'data' => ['id' => $hiddenWonder->id],
                    ],
                    [
                        'href' => 'javascript:void(0);',
                        'class' => 'btn-danger btn-sm delete-hidden-wonder',
                        'icon' => 'fa-solid fa-trash',
                        'text' => 'Delete',
                        'data' => ['id' => $hiddenWonder->id],
                    ],
                ];

                return view('components.datatable.colunms.buttons', ['data' => $buttons])->render();
            })
            ->rawColumns(['action']); // Fixed here: 'action'
    }

    public function query(HiddenWonder $model): QueryBuilder
    {
        $query = $model->newQuery();

        if ($this->translationId) {
            $query->where('national_park_translation_id', $this->translationId);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('hidden-wonders-table')
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
                "searchPlaceholder" => 'Search Hidden Wonders'
            ])
            ->buttons(
                Button::make()
                    ->text('<i class="fa fa-plus"></i> New')
                    ->attr([
                        'id' => 'createHiddenWonderBtn',
                        'class' => 'btn btn-primary'
                    ])
            )
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

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('section_heading')->title('Section Heading'),
            Column::make('section_title')->title('Section Title'),
            Column::make('section_subtitle')->title('Section Subtitle'),
            Column::make('icon')->title('Icon'),
            Column::make('title')->title('Title'),
            Column::make('subtitle')->title('Subtitle'),
            Column::make('description')->title('Description'),
            Column::make('tip_heading')->title('Tip Heading'),
            Column::make('tip_text')->title('Tip Text'),
            Column::make('quote')->title('Quote'),
            Column::make('sort_order')->title('Sort Order'),
            Column::make('is_active')->title('Active'),
            Column::computed('action') // Fixed here: 'action'
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'HiddenWonders_' . date('YmdHis');
    }
}
