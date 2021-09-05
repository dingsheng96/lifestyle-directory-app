<?php

namespace App\DataTables\Merchant;

use App\Models\User;
use App\Models\Media;
use App\Models\Career;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder;

class MediaDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {

                $options = [
                    'no_action' => $this->no_action ?: null,
                    'view' => [
                        'route' => $data->full_file_path,
                        'attribute' => 'target="_blank"'
                    ],
                    'download' => [
                        'route' => $data->full_file_path,
                        'attribute' => 'download'
                    ]
                ];

                if (!$data->is_thumbnail) {
                    $options = array_merge($options, [
                        'delete' => [
                            'route' => route('merchant.media.destroy', ['medium' => $data->id])
                        ],
                        'thumbnail' => [
                            'route' => route('merchant.media.update', ['medium' => $data->id]),
                            'form_id' => 'thumbnail_form_' . $data->id,
                            'attribute' => 'onclick="event.preventDefault(); document.getElementById(' . "'thumbnail_form_{$data->id}'" . ').submit();"'
                        ]
                    ]);
                }

                return view('merchant.components.btn_action', $options)->render();
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->addColumn('preview', function ($data) {
                return '<img src="' . $data->full_file_path . '" alt="' . $data->original_filename . '" class="table-img-preview">';
            })
            ->rawColumns(['action', 'preview']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Media $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Media $model)
    {
        return $model->whereHasMorph('mediable', [User::class], function (Builder $query) {
            $query->where('id', Auth::id())->merchant();
        })->where(function ($query) {
            $query->image()->orWhere(function ($query) {
                $query->thumbnail();
            });
        })->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('media-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
            ->responsive(true)
            ->autoWidth(true)
            ->processing(false);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex', '#'),
            Column::make('original_filename')->title(__('labels.filename')),
            Column::make('type')->title(__('labels.type')),
            Column::computed('preview')->title(__('labels.preview')),
            Column::make('created_at')->title(__('labels.created_at')),
            Column::computed('action', __('labels.action'))
                ->exportable(false)
                ->printable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Media_' . date('YmdHis');
    }
}
