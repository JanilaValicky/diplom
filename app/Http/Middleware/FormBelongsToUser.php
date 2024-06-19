<?php

namespace App\Http\Middleware;

use App\Models\Form;
use Closure;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FormBelongsToUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_id = $request->user->id;
        $hashid = new Hashids();
        $form_id = $hashid->decode($request->route('id'));
        if (!Form::where('id', $form_id)->where('user_id', $user_id)->exists()) {
            return new Response('Not found.', 404);
        }

        return $next($request);
    }
}
