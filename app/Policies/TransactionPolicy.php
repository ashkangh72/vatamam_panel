<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('transactions.index');
    }

    public function view(User $user, Transaction $transaction)
    {
        return $user->can('transactions.view');
    }

    public function delete(User $user, Transaction $transaction)
    {
        return $user->can('transactions.delete');
    }
}
