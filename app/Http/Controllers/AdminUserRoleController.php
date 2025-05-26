<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminUserRoleController extends Controller
{
    // Вернуть JSON список пользователей с ролями
    public function list()
    {
        $users = User::select('id', 'email', 'role')->get();
        return response()->json($users);
    }

    // Обновить роли пользователей
    public function update(Request $request)
    {
        $data = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'in:Пользователь,Администратор',
        ]);

        $currentUserId = Auth::id();

        foreach ($data['roles'] as $userId => $role) {
            // Не даю менять роль самому себе
            if ((int)$userId === $currentUserId) {
                continue;
            }
            $user = User::find($userId);
            if ($user) {
                $user->role = $role;
                $user->save();
            }
        }

        return response()->json(['message' => 'Роли обновлены']);
    }
}
