<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthorizeMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		$user = Auth::guard('admin')->user();
		if ($user->role_id == null){
			return $next($request);
		}

		$userPermission = optional($user->role)->permission;
		if ($user->role){
			if (in_array($request->route()->getName(), $userPermission)){
				return $next($request);
			}
		}


		return  redirect()->route('403');

	}
}
