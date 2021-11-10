<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Merchant\ReviewDataTable;

class ReviewController extends Controller
{
    public function index(Request $request, ReviewDataTable $dataTable)
    {
        return $dataTable->with(compact('request'))->render('merchant.reviews.index');
    }
}
