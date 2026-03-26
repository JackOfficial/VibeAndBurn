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
    return view('admin.users.index');
}

public function show(User $user)
{
    return view('admin.users.show', compact('user'));
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