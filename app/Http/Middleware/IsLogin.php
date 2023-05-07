<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /*if (Auth::check()) {
            if (Auth::guard('admin')->check()){
                return redirect('admin/dashboard');
            }
            else if (Auth::guard('teacher')->check()){
                return redirect('teacher/dashboard');
            }
            else if (Auth::guard('student')->check()){
                return redirect('student/dashboard');
            }
        }*/

        if (!Auth::guard($guard)->check()){
            if ($guard == 'admin'){
                return redirect()->route('admin.login');
            }
            else if ($guard == 'teacher'){
                return redirect()->route('teacher.login');
            }
            else{
                return redirect()->route('student.login');
            }
        }

        return $next($request);
    }
}
