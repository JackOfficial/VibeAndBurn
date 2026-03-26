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