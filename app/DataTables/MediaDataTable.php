<?php

namespace App\DataTables;

use App\Models\Media;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MediaDataTable extends DataTable
{
    protected $parkId;

    // Setter method instead of constructor injection
    public function setparkId($parkId)
    {
        $this->parkId = $parkId;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                if (empty($row->id)) {
                    return '<span class="text-muted">N/A</span>';
                }

                return '<button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</button>';
            })
            ->setRowId('id');
    }

    public function query(Media $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('media-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('
                <"row"<"col-md-6 d-flex justify-content-start"f><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"lB>>
                <"row"<"col-md-12"tr>>
                <"row"<"col-md-6"i><"col-md-6"p>>
            ')
            ->orderBy(1)
            ->language(["search" => "", "lengthMenu" => "_MENU_", "searchPlaceholder" => 'Search Media'])
            ->buttons(
                Button::make()
                    ->className('btn btn-primary')
                    ->text('<i class="fa fa-plus"></i> New')
                    ->action('function(e, dt, node, config) {
                        let url = "' . route('media.create', ['id' => $this->parkId]) . '";
                        window.location.href = url;
                    }')
            )
            ->parameters([
                'paging' => true,
                'lengthMenu' => [
                    [5, 10, 25, 50, -1],
                    ['5', '10', '25', '50', 'Show all']
                ],
                'initComplete' => 'function () {}',
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('type')->title('Type'),
            Column::make('s3_bucket')->title('S3 Bucket'),
            Column::make('s3_url')->title('S3 URL'),
            Column::make('file_size')->title('File Size'),
            Column::make('mime_type')->title('MIME Type'),
            Column::make('sort_order')->title('Sort Order'),
            Column::make('is_gallery_visual')->title('Gallery Visual'),
            Column::make('uploaded_by')->title('Uploaded By'),
            Column::make('created_at')->title('Created At'),
            Column::make('updated_at')->title('Updated At'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Media_' . date('YmdHis');
    }
}
