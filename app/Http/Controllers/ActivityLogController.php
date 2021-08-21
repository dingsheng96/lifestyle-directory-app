<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\DataTables\ActivityLogDataTable;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ActivityLogDataTable $dataTable)
    {
        return $dataTable->render('activity_log.index');
    }
}
