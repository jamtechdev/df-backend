<?php

namespace App\DataTables;

use App\Models\MediaTranslation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MediaTranslationDataTable extends DataTable
{
    protected $mediaId;

    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('checkbox', function ($row) {
                return view('components.datatable.colunms.checkbox', ['id' => $row->id]);
            })
            ->editColumn('language_code', function ($row) {
                return $row->language_code ?? '-';
            })
            ->editColumn('status', function ($row) {
                return ucfirst($row->status ?? '-');
            })
            ->editColumn('overlay_title', function ($row) {
                return $row->overlay_title ?? '-';
            })
            ->editColumn('overlay_subtitle', function ($row) {
                return $row->overlay_subtitle ?? '-';
            })
            ->editColumn('overlay_description', function ($row) {
                return $row->overlay_description ?? '-';
            })
            ->editColumn('title', function ($row) {
                return $row->title ?? '-';
            })
            ->editColumn('subtitle', function ($row) {
                return $row->subtitle ?? '-';
            })
            ->editColumn('description', function ($row) {
                return $row->description ?? '-';
            })
            ->addColumn('action', function ($row) {
                $options = [
                    [
                        'href' => 'javascript:void(0);',
                        'class' => 'btn-primary edit-translation',
                        'icon' => 'fas fa-edit',
                        'text' => 'Edit',
                        'method' => 'GET',
                        'data-id' => $row->id, // you passed this
                    ],
                    [
                        'href' => 'javascript:void(0);', // No href, JS trigger
                        'class' => 'btn-danger btn-delete',
                        'icon' => 'fas fa-trash',
                        'text' => 'Delete',
                        'method' => 'DELETE',
                        'confirm_message' => 'Are you sure to delete this translation?',
                        'data-id' => $row->id, // pass ID
                    ],
                ];

                return view('components.datatable.colunms.action', compact('options'))->render();
            })

            ->rawColumns(['action']);
    }

    public function query(MediaTranslation $model): QueryBuilder
    {
        return $model->newQuery()->where('media_id', $this->mediaId);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('mediaTranslationTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('
                <"row"<"col-md-6 d-flex justify-content-start mb-2"f><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"lB>>
                <"row"<"col-md-12"tr>>
                <"row"<"col-md-6"i><"col-md-6"p>>
            ')
            ->orderBy(1)
            ->language(["search" => "", "lengthMenu" => "_MENU_", "searchPlaceholder" => 'Search'])
            ->buttons(
                Button::make()
                    ->className('btn btn-primary')
                    ->text('<i class="fa fa-plus"></i> New')
                    ->attr([
                        'id' => 'btnNewTranslation',
                        'media-id' => $this->mediaId
                    ])
            )
            ->parameters([
                'paging' => true,
                'lengthMenu' => [
                    [10, 15, 25, 50, -1],
                    ['10', '15', '25', '50', 'Show all']
                ],
            ]);
    }




    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('language_code')->title('Language'),
            Column::make('status')->title('Status'),
            Column::make('overlay_title')->title('Overlay Title'),
            Column::make('overlay_subtitle')->title('Overlay Subtitle'),
            Column::make('overlay_description')->title('Overlay Description'),
            Column::make('title')->title('Title'),
            Column::make('subtitle')->title('Subtitle'),
            Column::make('description')->title('Description'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'MediaTranslations_' . date('YmdHis');
    }
}
