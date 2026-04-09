<?php

namespace App\Policies;

use App\Models\ParkingTransaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ParkingTransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ParkingTransaction $parkingTransaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'officer';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ParkingTransaction $parkingTransaction): bool
    {
        return $user->role === 'officer' && $parkingTransaction->status === 'parked';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ParkingTransaction $parkingTransaction): bool
    {
        return $user->role === 'admin' || $parkingTransaction->entry_officer_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ParkingTransaction $parkingTransaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ParkingTransaction $parkingTransaction): bool
    {
        return $user->role === 'admin' || $parkingTransaction->entry_officer_id === $user->id;
    }

    public function checkout(User $user, ParkingTransaction $parkingTransaction): bool
    {
        return $user->role === 'officer' && $parkingTransaction->entry_officer_id === $user->id && $parkingTransaction->status === 'parked';
    }

    public function print(User $user, ParkingTransaction $parkingTransaction): bool
    {
        return $parkingTransaction->status === 'completed' && $user->role === 'officer';
    }
}
