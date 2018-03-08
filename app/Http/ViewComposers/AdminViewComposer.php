<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\UserRepository;

class AdminViewComposer
{
    public function __construct()
    {
    }

    public function compose(View $view)
    {
        $view->with('hello', 'Hello there');
    }
}
