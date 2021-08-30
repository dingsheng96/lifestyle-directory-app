<?php

namespace App\Http\View\Composers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\View\View;

class CategoryComposer
{
    /**
     * Create a new categories composer.
     *
     * @param  TopServiceRepository $top_service
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $categories = Category::with(['media'])->orderBy('name')->get();

        $view->with('categories', $categories);
    }
}
