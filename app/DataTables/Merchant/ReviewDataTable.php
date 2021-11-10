<?php

namespace App\DataTables\Merchant;

use App\Models\User;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReviewDataTable extends DataTable
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
            ->addColumn('user', function ($data) {
                return $data->name;
            })
            ->addColumn('rating', function ($data) {
                return $data->raters->first()->pivot->scale;
            })
            ->addColumn('review', function ($data) {
                return $data->raters->first()->pivot->review;
            })
            ->addColumn('created_at', function ($data) {
                return $data->raters->first()->pivot->created_at->toDateTimeString();
            });
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
            ->member()
            ->with([
                'raters' => function ($query) {
                    $query->with(['mainBranch', 'subBranches'])
                        ->when(Auth::user()->is_main_branch, function ($query) {
                            $query->where('id', Auth::id());
                        })
                        ->when(Auth::user()->is_sub_branch, function ($query) {

                            $query->where('id', Auth::id())
                                ->whereHas('mainBranch', function ($query) {
                                    $query->where('id', Auth::user()->mainBranch->id);
                                });
                        });
                }
            ])
            ->when(Auth::user()->is_main_branch, function ($query) {
                $query->when(empty($this->request->query('branch_id')), function ($query) {
                    $query->whereHas('raters', function ($query) {
                        $query->where('id', Auth::id());
                    });
                })->when(!empty($this->request->query('branch_id')), function ($query) {
                    $query->whereHas('raters', function ($query) {
                        $query->where('id', $this->request->query('branch_id'));
                    });
                });
            })
            ->when(Auth::user()->is_sub_branch, function ($query) {

                $query->whereHas('raters', function ($query) {
                    $query->where('id', Auth::id())
                        ->whereHas('mainBranch', function ($query) {
                            $query->where('id', Auth::user()->mainBranch->id);
                        });
                });
            });
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('review-table')
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
            Column::make('user')->title(__('labels.user')),
            Column::make('rating')->title(__('labels.rating')),
            Column::make('review')->title(trans_choice('labels.review', 1)),
            Column::make('created_at')->title(__('labels.created_at')),
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
