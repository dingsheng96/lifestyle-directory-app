<?php

namespace App\DataTables\Admin;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BranchDataTable extends DataTable
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

                if ($this->view_only) {
                    return view('admin.components.btn_action', [
                        'no_action' => $this->no_action ?: null,
                        'view' => [
                            'permission' => 'merchant.read',
                            'route' => route('admin.merchants.branches.show', ['merchant' => $this->merchant->id, 'branch' => $data->id])
                        ]
                    ])->render();
                }

                return view('admin.components.btn_action', [
                    'no_action' => $this->no_action ?: null,
                    'view' => [
                        'permission' => 'merchant.read',
                        'route' => route('admin.merchants.branches.show', ['merchant' => $this->merchant->id, 'branch' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'merchant.update',
                        'route' => route('admin.merchants.branches.edit', ['merchant' => $this->merchant->id, 'branch' => $data->id])
                    ],
                    'delete' => [
                        'permission' => 'merchant.delete',
                        'route' => route('admin.merchants.branches.destroy', ['merchant' => $this->merchant->id, 'branch' => $data->id])
                    ]
                ])->render();
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('status', function ($data) {
                return $data->status_label;
            })
            ->rawColumns(['action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->subMerchant()
            ->whereHas('mainBranch', function ($query) {
                $query->where('id', $this->merchant->id);
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
            ->setTableId('merchant-branch-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
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
            Column::make('name')->title(__('labels.name')),
            Column::make('email')->title(__('labels.email')),
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
        return 'MerchantBranch_' . date('YmdHis');
    }
}
