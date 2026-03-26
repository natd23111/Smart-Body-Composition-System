<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BodyComposition;

class AdminController extends Controller
{
    // Dashboard summary
    public function dashboard()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_records' => BodyComposition::count()
        ]);
    }

    // View all users
    public function users()
    {
        return response()->json(User::all());
    }

    // Delete user
    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();

        return response()->json([
            'message' => 'User deleted'
        ]);
    }

    // View all records
    public function records()
    {
        return response()->json(BodyComposition::with('user')->get());
    }
}
