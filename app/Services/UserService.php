<?php 

namespace App\Services;

use App\Models\User;

class UserService
{
    public function createUser(array $data)
    {
        $newUser = new User();
        $newUser->full_name = $data['full_name'];
        $newUser->is_admin = $data['is_admin'];
        $newUser->email = $data['email'];
        $newUser->password = bcrypt($data['password']);
        $newUser->save();
    }

    public function readUser(int $id)
    {
        return User::where('id', $id)
                ->with('orders')
                ->get();
    }

    public function updateUser(array $data, User $user)
    {
        $user->full_name = $data['full_name'] ?? $user->full_name;
        $user->is_admin = $data['is_admin'] ?? $user->is_admin;
        $user->email = $data['email'] ?? $user->email;
        $user->password = isset($data['password']) 
                            ? bcrypt($data['password']) 
                            : $user->password;
        $user->save();
    }

    public function deleteUser(User $user)
    {
        if ($user == auth()->user()) {
            auth()->user()->token()->revoke();
        }
        $user->delete();
    }
}