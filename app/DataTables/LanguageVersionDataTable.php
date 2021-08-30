<?php

namespace App\DataTables;

use App\Models\Translation;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LanguageVersionDataTable extends DataTable
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
                return view('components.action', [
                    'no_action' => $this->no_action ?: null,
                    'download' => [
                        'route' => route('locale.languages.translations.export', ['language' => $this->language->id, 'version' => $data->version]),
                    ],
                    'upload' => [
                        'route' => '#importTranslationModal',
                        'attribute' => 'data-toggle="modal" data-version="' . $data->version . '"'
                    ]
                ])->render();
            })
            ->addColumn('current_version', function ($data) {
                return '<div class="icheck-purple">
                            <input type="radio" name="current_version" id="current_version_' . $data->version . '" ' . ($this->language->current_version == $data->version ? 'checked' : null) . ' value="' . $data->version . '">
                            <label for="current_version_' . $data->version . '"></label>
                        </div>';
            })
            ->addColumn('last_updated_at', function ($data) {

                return $data->updated_at->toDateTimeString();
            })
            ->rawColumns(['action', 'current_version']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Translation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Translation $model)
    {
        return $model->select('version', DB::raw('MAX(updated_at) AS updated_at'))
            ->where('language_id', $this->language->id)
            ->groupBy('version')
            ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('language-version-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'desc')
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
            Column::make('version')->title(__('labels.version')),
            Column::make('current_version')->title(__('labels.current_version')),
            Column::make('last_updated_at')->title(__('labels.last_updated_at')),
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
        return 'Language_' . date('YmdHis');
    }
}
