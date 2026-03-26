<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class EditComponent extends Component
{
    public $user;
    public $name;
    public $email;
    public $phone;
    public $balance;
    public $status;
    public $password; // Optional: to reset password

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->balance = $user->balance;
        $this->status = $user->status;
    }

    public function update()
    {
        $this->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $this->user->id,
            'phone'   => 'nullable|string',
            'balance' => 'required|numeric|min:0',
            'status'  => 'required|in:active,banned',
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

        session()->flash('success', 'User updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.users.edit-component');
    }
}