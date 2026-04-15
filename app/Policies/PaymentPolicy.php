<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{
    /**
     * Check if organizer owns the event for this payment
     */
    private function isOrganizerOfEvent(User $user, Payment $payment): bool
    {
<<<<<<< HEAD
        return (int) $payment->booking->event->organiser_id === (int) $user->id;
=======
        return $payment->booking->event->organiser_id === $user->id;
>>>>>>> 28eb5084204bf389f4d4e74976466e14f04bbbcf
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
<<<<<<< HEAD
        return $user->isOrganiser() || $user->isAdmin();
=======
        return $user->hasRole('Organiser') || $user->hasRole('Admin');
>>>>>>> 28eb5084204bf389f4d4e74976466e14f04bbbcf
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payment $payment): bool
    {
<<<<<<< HEAD
        return $this->isOrganizerOfEvent($user, $payment) || $user->isAdmin();
=======
        return $this->isOrganizerOfEvent($user, $payment) || $user->hasRole('Admin');
>>>>>>> 28eb5084204bf389f4d4e74976466e14f04bbbcf
    }

    /**
     * Determine whether the user can approve the payment.
     */
    public function approvePayment(User $user, Payment $payment): bool
    {
        return $this->isOrganizerOfEvent($user, $payment) && $payment->status === 'pending';
    }

    /**
     * Determine whether the user can reject the payment.
     */
    public function rejectPayment(User $user, Payment $payment): bool
    {
        return $this->isOrganizerOfEvent($user, $payment) && $payment->status === 'pending';
    }

    /**
     * Determine whether the user can update payment status.
     */
    public function updatePayment(User $user, Payment $payment): bool
    {
        return $this->isOrganizerOfEvent($user, $payment);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment): bool
    {
        return $this->isOrganizerOfEvent($user, $payment);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $payment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Payment $payment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Payment $payment): bool
    {
        return false;
    }
}
