<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;

class BookPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Book $book)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Book $book)
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Book $book)
    {
        return $user->role === 'admin';
    }
}
