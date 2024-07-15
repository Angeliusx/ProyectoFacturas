<?php

namespace App\Http\Livewire\Pages\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ViewUser extends Component
{
    use WithPagination;

    public $verName, $delete_user;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.pages.admin.view-user', [
            'users' => User::paginate(15),
        ]);
    }


    public function mount()
    {
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->route('dashboard');
        }
    }

    public function deleteConfirmation($id)
    {
        $this->delete_user = $id;
        $user = User::where('ID', $this->delete_user)->first();
        $this->verName = $user->name;
        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    public function deleteUser()
    {
        $user = User::where('ID', $this->delete_user)->first();
        $user->delete();

        session()->flash('success', $user->name . ' ha sido eliminado');

        $this->dispatchBrowserEvent('close-modal');

        $this->delete_user = '';
    }

    public function cancel()
    {
        $this->delete_user = '';
    }

    public function demoteUser($id)
    {
        $user = User::find($id);
        $user->detachRole('admin');
        $user->attachRole('user');
        session()->flash('success', $user->name . ' ha sido degradado a Usuario');
    }

    public function promoteUser($id)
    {
        $user = User::find($id);
        $user->detachRole('user');
        $user->attachRole('admin');
        session()->flash('success', $user->name . ' ha sido ascendido a Admin');
    }

    public function toggleUserRole($id)
    {
        $user = User::find($id);
        $roles = ['Viewer', 'User', 'Admin'];

        $currentRole = $user->roles->first()->display_name;
        $currentIndex = array_search($currentRole, $roles);

        $nextIndex = ($currentIndex + 1) % count($roles);
        $nextRole = $roles[$nextIndex];

        $user->detachRole($currentRole);
        $user->attachRole($nextRole);

        $messages = [
            'Viewer' => 'Ser Viewer',
            'User' => 'Poner como Usuario',
            'Admin' => 'Ascender a Admin'
        ];

        session()->flash('success', $user->name . ' ha sido cambiado a ' . $nextRole . '. ');
    }


    public function cancelAccion($id)
    {
        $user = User::find($id);
        session()->flash('error', $user->name . ' no te puedes modificar');
    }
    
}