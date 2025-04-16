<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Critic;
use Exception;

class CriticMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($critic = Critic::where('film_id', $request->film_id)){
            if($critic->user_id == $request->user_id){
                return response()->json([
                    'message' => 'You have already submitted a critic for this film'
                ], 401);
            }
        }
        return $next($request);
    }
}
