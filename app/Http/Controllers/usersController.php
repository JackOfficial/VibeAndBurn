<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class usersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    // 1. Security Check (Ideally moved to Middleware later)
    if (!Auth::check() || !Auth::user()->hasAnyRole(['Super Admin', 'Admin'])) {
        return view('auth.login'); 
    }

    $name = Auth::user()->name;
    
    // 2. Use paginate() instead of get()
    // 15 users per page is a good standard for admin tables
    $users = User::with('roles')->orderBy('id', 'DESC')->paginate(15);

    $usersCounter = User::count();

    return view('admin.manage.users', compact('name', 'users', 'usersCounter'));
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Safety: Prevent deleting yourself
        if (Auth::id() == $id) {
            return redirect()->back()->with('deleteUserFail', 'You cannot delete your own account!');
        }

        $user = User::find($id);

        if ($user) {
            $user->delete();
            return redirect()->back()->with('deleteUserSuccess', 'User was deleted successfully');
        }

        return redirect()->back()->with('deleteUserFail', 'User could not be deleted');
    }
}