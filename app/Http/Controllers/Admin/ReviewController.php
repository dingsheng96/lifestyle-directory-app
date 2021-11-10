<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Admin\ReviewDataTable;

class ReviewController extends Controller
{
    public function index(Request $request, ReviewDataTable $dataTable)
    {
        return $dataTable->with(compact('request'))->render('admin.reviews.index');
    }
}
