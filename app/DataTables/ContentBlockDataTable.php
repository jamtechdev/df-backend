<?php

namespace App\DataTables;

use App\Models\ContentBlock;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContentBlockDataTable extends DataTable
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
            ->editColumn('checkbox', function ($row) {
                return view('components.datatable.colunms.checkbox', ['id' => $row->id]);
            })
            ->addColumn('action', function (ContentBlock $contentBlock) {
                $buttons = [
                    [
                        'href' => 'javascript:void(0);',
                        'class' => 'btn-info btn-sm btn-edit',
                        'icon' => 'fa-solid fa-pen-to-square',
                        'text' => 'Edit',
                        'data' => ['id' => $contentBlock->id],
                    ],
                    [
                        'href' => 'javascript:void(0);',
                        'class' => 'btn-danger btn-sm btn-delete',
                        'icon' => 'fa-solid fa-trash',
                        'text' => 'Delete',
                        'data' => ['id' => $contentBlock->id],
                    ],
                ];

                return view('components.datatable.colunms.buttons', ['data' => $buttons])->render();
            })
            ->rawColumns(['action']);
    }

    public function query(ContentBlock $model): QueryBuilder
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
            ->setTableId('content-blocks-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('
                <"row"<"col-md-6 d-flex justify-content-start mb-2"f><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"lB>>
                <"row"<"col-md-12"tr>>
                <"row"<"col-md-6"i><"col-md-6"p>>
            ')
            ->orderBy(1)
            ->language([
                "search" => "",
                "lengthMenu" => "_MENU_",
                "searchPlaceholder" => 'Search Content Blocks'
            ])
            ->buttons(
                Button::make()
                    ->text('<i class="fa fa-plus"></i> New')
                    ->attr([
                        'id' => 'btnAdd',
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
            Column::make('section_type')->title('Section Type'),
            Column::make('heading')->title('Heading'),
            Column::make('subheading')->title('Subheading'),
            Column::make('icon')->title('Icon'),
            Column::make('title')->title('Title'),
            Column::make('description')->title('Description'),
            Column::make('sort_order')->title('Sort Order'),
            Column::make('is_active')->title('Active'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'ContentBlocks_' . date('YmdHis');
    }
}
