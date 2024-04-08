<?php

namespace App\Http\ViewComposers;

use App\Models\Type;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $types = Type::with(['tasks', 'children'])->where('parent_id', 0)->get();

        $view->with('types', $types);
    }
}
