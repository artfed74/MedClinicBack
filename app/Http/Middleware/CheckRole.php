<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = Auth::user();

        // Разделяем строку ролей по символу '|' и получаем массив
        $rolesArray = explode('|', $roles);

        // Проверяем, есть ли у пользователя хотя бы одна из указанных ролей
        if (!$user || !in_array($user->role, $rolesArray)) {
            return response()->json([
                "error" => [
                    "code" => 403,
                    "message" => "Недоступно для вас",
                ]
            ], 403);
        }

        return $next($request);
    }
}
