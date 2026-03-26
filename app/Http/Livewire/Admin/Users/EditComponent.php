<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EditComponent extends Component
{
    public $user;
    public $name, $email, $phone, $balance, $status, $password;

    // RBAC Properties
    public $selectedRoles = [];
    public $selectedPermissions = [];
    public $allRoles = [];
    public $allPermissions = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->balance = $user->balance;
        $this->status = $user->status;

        // Load existing roles and direct permissions
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->selectedPermissions = $user->getPermissionNames()->toArray();

        // Only load all options if the editor is authorized
        if (auth()->user()->hasRole('Super Admin')) {
            $this->allRoles = Role::all();
            $this->allPermissions = Permission::all();
        }
    }

    public function update()
    {
        $this->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $this->user->id,
            'phone'    => 'nullable|string',
            'balance'  => 'required|numeric|min:0',
            'status'   => 'required|in:active,banned,suspended', // Added suspended
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name'    => $this->name,
            'email'   => $this->email,
            'phone'   => $this->phone,
            'balance' => $this->balance,
            'status'  => $this->status,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $this->user->update($data);

        // Security Check: Only allow Super Admin to modify Roles/Permissions
        if (auth()->user()->hasRole('Super Admin')) {
            $this->user->syncRoles($this->selectedRoles);
            $this->user->syncPermissions($this->selectedPermissions);
        }

        session()->flash('success', 'User updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.users.edit-component');
    }
}