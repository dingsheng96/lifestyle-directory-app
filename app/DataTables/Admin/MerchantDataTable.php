<?php

namespace App\DataTables\Admin;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MerchantDataTable extends DataTable
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
                return view('admin.components.btn_action', [
                    'no_action' => $this->no_action ?: null,
                    'view' => [
                        'permission' => 'merchant.read',
                        'route' => route('admin.merchants.show', ['merchant' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'merchant.update',
                        'route' => route('admin.merchants.edit', ['merchant' => $data->id])
                    ],
                    'delete' => [
                        'permission' => 'merchant.delete',
                        'route' => route('admin.merchants.destroy', ['merchant' => $data->id])
                    ]
                ])->render();
            })
            ->addColumn('total_visitor_count', function ($data) {
                $data->load([
                    'subBranches' => function ($query) {
                        $query->withCount('visitorHistories');
                    }
                ]);

                $total_visitors = $data->visitor_histories_count;

                foreach ($data->subBranches as $branch) {
                    $total_visitors += $branch->visitor_histories_count;
                }

                return $total_visitors;
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('status', function ($data) {
                return $data->status_label;
            })
            ->editColumn('mobile_no', function ($data) {
                return $data->formatted_mobile_no;
            })
            ->rawColumns(['action', 'status', 'profile']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery()
            ->merchant()
            ->mainMerchant()
            ->withCount(['subBranches', 'visitorHistories'])
            ->approvedApplication();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('merchant-table')
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
            Column::make('name')->title(__('labels.name')),
            Column::make('email')->title(__('labels.email')),
            Column::make('mobile_no')->title(__('labels.contact_no')),
            Column::make('sub_branches_count')->title(__('labels.branches'))->searchable(false),
            Column::make('total_visitor_count')->title(__('labels.total_visitor_count'))->searchable(false),
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
        return 'Merchant_' . date('YmdHis');
    }
}
