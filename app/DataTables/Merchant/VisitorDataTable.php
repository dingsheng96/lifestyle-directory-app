<?php

namespace App\DataTables\Merchant;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\BranchVisitorHistory;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class VisitorDataTable extends DataTable
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
            ->addColumn('visitor', function ($data) {
                return $data->visitor->name;
            })
            ->addColumn('total_visit_count', function ($data) {
                return $data->visit_count;
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('updated_at', function ($data) {
                return $data->updated_at->toDateTimeString();
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\BranchVisitorHistory $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BranchVisitorHistory $model)
    {
        return $model->newQuery()
            ->with('visitor')
            ->where('branch_id', Auth::id());
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('visitor-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(count($this->getColumns()) - 2, 'desc')
            ->responsive(true)
            ->autoWidth(true)
            ->processing(false)
            ->stateSave();
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
            Column::make('visitor')->title(__('labels.visitor')),
            Column::make('total_visit_count')->title(__('labels.total_visit_count')),
            Column::make('created_at')->title(__('labels.first_visit_at')),
            Column::make('updated_at')->title(__('labels.recent_visit_at')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Review_' . date('YmdHis');
    }
}
