<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Career;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

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

        return view('merchant.dashboard', compact('total_listing_careers'));
    }
}
