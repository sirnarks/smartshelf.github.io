<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * Allow all authenticated users to view any books.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Allow all authenticated users to view a book.
     */
    public function view(User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Only allow admin to create books.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only allow admin to update books.
     */
    public function update(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only allow admin to delete books.
     */
    public function delete(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only allow admin to restore books.
     */
    public function restore(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only allow admin to force delete books.
     */
    public function forceDelete(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }
}
