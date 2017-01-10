<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\AdminHelpers;

class AdminMiddleware
{

    /**
     * Create a new filter instance.
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $helper = new AdminHelpers();
        if(!$helper->admin_check_valid_login()){

            return redirect()->route('admin_login');
        }

        return $next($request);
    }
}
