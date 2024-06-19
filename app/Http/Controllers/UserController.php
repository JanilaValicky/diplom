<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/')->with('success', 'User deleted successfully');
    }
}
