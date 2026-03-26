<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap'; // Force Bootstrap style

    // Reset pagination when searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Add this method to toggle the ban status
public function toggleBan($id)
{
    $user = User::find($id);
    
    // Safety check: Don't let an admin ban themselves
    if (auth()->id() == $id) {
        session()->flash('error', 'You cannot ban your own account.');
        return;
    }

    $user->status = ($user->status === 'banned') ? 'active' : 'banned';
    $user->save();

    $message = $user->status === 'banned' ? 'User has been banned.' : 'User has been unbanned.';
    session()->flash('success', $message);
}

// For the Edit button, you usually redirect to a separate page 
// or open a modal. Here is the redirect approach:
public function editUser($id)
{
    return redirect()->route('admin.users.edit', $id);
}

    public function deleteUser($id)
    {
        if (Auth::id() == $id) {
            session()->flash('error', 'You cannot delete yourself!');
            return;
        }

        User::find($id)->delete();
        session()->flash('success', 'User deleted successfully.');
    }

    public function render()
    {
        // Security Check
        if (!Auth::check() || !Auth::user()->hasAnyRole(['Super Admin', 'Admin'])) {
            abort(403);
        }

        $users = User::with('roles')
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return view('livewire.admin.user-management', [
            'users' => $users,
            'usersCounter' => User::count()
        ]);
    }
}