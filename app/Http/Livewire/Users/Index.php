<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;
use Okipa\LaravelTable\Table;
use App\Models\User;
class Index extends Component
{
    public $table;
    public function render()
    {
        return view('livewire.users.index', [
            'table' => table('users')->columns([
                column()->title('ID')->data('id'),
                column()->title('Name')->data('name'),
                column()->title('Email')->data('email'),
                column()->title('Actions')->component('users.actions'),
            ]),
        ]);
    }
    public function mount()
    {
        $this->table = app(Table::class);
        $this->table->setData(User::all());
    }
}
