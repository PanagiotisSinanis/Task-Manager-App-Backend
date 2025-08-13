<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
   public function dashboard()
{
    return response()->json(['message' => 'Welcome to the admin dashboard']);
}
public function index()
    {
        $users = User::all(['id', 'name', 'email']); // Παίρνουμε τα πεδία που χρειάζεσαι
        return response()->json(['users' => $users]);
    }
}
