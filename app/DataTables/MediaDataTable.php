<?php

namespace App\DataTables;

use App\Models\Media;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\HtmlString;
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
            ->setRowId('id')
            ->editColumn('checkbox', function ($row) {
                return view('components.datatable.colunms.checkbox', ['id' => $row->id]);
            })
            ->editColumn('s3_url', function ($row) {
                return !empty($row->s3_url)
                    ? '<div class="text-center">
                        <img src="' . $row->s3_url . '" alt="Image" width="60" height="60" 
                             style="object-fit:cover;border-radius:5px;">
                   </div>'
                    : '<div class="text-center text-muted">No Image</div>';
            })
            ->addColumn('details', function ($row) {
                return '
                <div class="text-center">
                    <div><strong>Type:</strong> ' . ucfirst(str_replace('_', ' ', $row->type)) . '</div>
                    <div><strong>Size:</strong> ' . number_format($row->file_size / 1024, 2) . ' KB</div>
                    <div><strong>MIME:</strong> ' . $row->mime_type . '</div>
                </div>';
            })

            ->addColumn('action', function ($row) {
                $options = [
                    [
                        'href'  => route('media.translations.index', ['media' => $row->id]),
                        'text'  => 'Translations',
                        'icon'  => 'fas fa-language',
                        'class' => 'btn-primary',
                    ],
                    [
                        'href'  => route('media.edit', ['media' => $row->id]),
                        'text'  => 'Edit',
                        'icon'  => 'fas fa-edit',
                        'class' => 'btn-warning',
                    ],
                    [
                        'href'            => route('media.destroy', ['id' => $this->parkId, 'media' => $row->id]),
                        'method'          => 'DELETE',
                        'text'           => 'Delete',
                        'icon'           => 'fas fa-trash',
                        'class'          => 'btn-danger',
                        'confirm_message' => 'Are you sure to delete this media?',
                    ]
                ];

                return view('components.datatable.colunms.action', compact('options'))->render();
            })

            ->editColumn('is_gallery_visual', function ($row) {
                $checked = $row->is_gallery_visual ? 'checked' : '';
                return '<div class="text-center">
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input toggle-gallery-visual" type="checkbox" 
                                data-id="' . $row->id . '" ' . $checked . '>
                        </div>
                    </div>';
            })
            ->editColumn('status', function ($row) {
                $checked = $row->status ? 'checked' : '';
                return '<div class="text-center">
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input toggle-status" type="checkbox" 
                                data-id="' . $row->id . '" ' . $checked . '>
                        </div>
                    </div>';
            })
            ->rawColumns(['s3_url', 'is_gallery_visual', 'status', 'action', 'details']); // Required to render the HTML
    }



    public function query(Media $model): QueryBuilder
    {
        return $model->newQuery()->where('mediable_id', $this->parkId);
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
                    [10, 15, 25, 50, -1],
                    ['10', '15', '25', '50', 'Show all']
                ],
                'initComplete' => "function(settings, json) {
                // Example JS here in initComplete:
                // Tooltip init:
                $('[data-bs-toggle=\"tooltip\"]').tooltip();
                
                // Example: bind to toggle switches dynamically:
                $('.toggle-gallery-visual').off('change').on('change', function() {
                    var mediaId = $(this).data('id');
                    var isChecked = $(this).is(':checked') ? 1 : 0;

                    $.ajax({
                        url: '" . route('media.toggle-gallery-visual') . "',
                        method: 'POST',
                        data: {
                            _token: '" . csrf_token() . "',
                            media_id: mediaId,
                            is_gallery_visual: isChecked
                        },
                        success: function(response) {
                            toastr.success(response.message);
                        },
                        error: function() {
                            toastr.error('Toggle failed.');
                        }
                    });
                });

                // New toggle-status handler
                $('.toggle-status').off('change').on('change', function() {
                    var mediaId = $(this).data('id');

                    $.ajax({
                        url: '" . route('media.toggle-status-switch') . "',
                        method: 'POST',
                        data: {
                            _token: '" . csrf_token() . "',
                            media_id: mediaId
                        },
                        success: function(response) {
                            toastr.success(response.message);
                        },
                        error: function() {
                            toastr.error('Status toggle failed.');
                        }
                    });
                });
            }",
            ]);
    }


    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')
                ->addClass('bulk-checkbox')
                ->title('<input type="checkbox" id="check-all"/>')
                ->orderable(false),
            Column::make('s3_url')->title('IMAGE'),
            Column::make('sort_order')->title('Sort Order'),
            Column::make('status')->title('Status')->orderable(true),
            Column::computed('details')->title('Details')->orderable(false)->searchable(false),
            Column::make('is_gallery_visual')->title('Gallery Visual')->orderable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false),
        ];
    }




    protected function filename(): string
    {
        return 'Media_' . date('YmdHis');
    }
}
