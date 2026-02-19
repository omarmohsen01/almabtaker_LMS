<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckNationalId
{
    /**
     * Redirect authenticated users who haven't filled in their national_id.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && empty($user->national_id)) {
            $allowedPaths = [
                'panel/national-id',
                'panel/national-id/store',
                'logout',
            ];

            $currentPath = $request->path();

            foreach ($allowedPaths as $path) {
                if ($currentPath === $path || str_starts_with($currentPath, $path)) {
                    return $next($request);
                }
            }

            return redirect('/panel/national-id');
        }

        return $next($request);
    }
}
