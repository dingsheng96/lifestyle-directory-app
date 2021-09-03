<?php

namespace App\DataTables\Merchant;

use App\Models\User;
use App\Models\Career;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CareerDataTable extends DataTable
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
                return view('merchant.components.btn_action', [
                    'no_action' => $this->no_action ?: null,
                    'view' => [
                        'permission' => 'career.read',
                        'route' => route('merchant.careers.show', ['career' => $data->id]),
                    ],
                    'update' => [
                        'permission' => 'career.update',
                        'route' => route('merchant.careers.edit', ['career' => $data->id]),
                    ],
                    'delete' => [
                        'permission' => 'career.delete',
                        'route' => route('merchant.careers.destroy', ['career' => $data->id])
                    ]
                ])->render();
            })
            ->addColumn('branch', function ($data) {
                return $data->branch->name;
            })
            ->addColumn('location', function ($data) {
                return $data->branch->address->location_city_state;
            })
            ->editColumn('status', function ($data) {
                return $data->status_label;
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->rawColumns(['action', 'status'])
            ->filterColumn('branch', function ($query, $keyword) {
                return $query->whereHas('branch', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('location', function ($query, $keyword) {
                return $query->whereHas('branch', function ($query) use ($keyword) {
                    $query->searchByAddress($keyword);
                });
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Career $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Career $model)
    {
        $merchant = Auth::guard(User::USER_TYPE_MERCHANT)
            ->user()->load(['mainBranch', 'subBranches']);

        $branches = collect([$merchant])
            ->merge([$merchant->mainBranch])
            ->merge($merchant->subBranches)
            ->pluck('id')->unique();


        return $model->with(['branch.address'])
            ->whereIn('branch_id', $branches)
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
            ->setTableId('career-table')
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
            Column::make('position')->title(__('labels.job_title')),
            Column::make('branch')->title(trans_choice('labels.branch', 1)),
            Column::make('location')->title(__('labels.location')),
            Column::make('status')->title(__('labels.status')),
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
        return 'Career_' . date('YmdHis');
    }
}
