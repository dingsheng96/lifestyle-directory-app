<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Career;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\Merchant\ReviewDataTable;
use App\DataTables\Merchant\VisitorDataTable;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user()
            ->loadCount(['visitorHistories'])
            ->load([
                'ratings',
                'visitorHistories' => function ($query) {
                    $query->wherePivot('created_at', '>=', today()->startOfDay())
                        ->wherePivot('created_at', '<=', today()->endOfDay());
                }
            ]);

        // Widgets
        $total_listing_careers = Career::publish()
            ->when($user->is_main_branch, function ($query) use ($user) {

                $user->load(['subBranches'])->loadCount('subBranches');

                $query->where('branch_id', $user->id)
                    ->when($user->sub_branches_count > 0, function ($query) use ($user) {
                        $query->orWhere('branch_id', $user->subBranches->pluck('id'));
                    });
            })
            ->when($user->is_sub_branch, function ($query) use ($user) {
                $query->where('branch_id', $user->id);
            })
            ->count();

        $today_visitor_count = $user->visitorHistories->count();

        $total_visitor_count = $user->visitor_histories_count;

        $average_ratings = $user->rating;

        return view('merchant.dashboard', compact('total_listing_careers', 'today_visitor_count', 'total_visitor_count', 'average_ratings'));
    }

    public function reviewIndex(ReviewDataTable $dataTable)
    {
        return $dataTable->render('merchant.dashboard.review');
    }

    public function visitorHistoryIndex(VisitorDataTable $dataTable)
    {
        return $dataTable->render('merchant.dashboard.visitor');
    }
}
