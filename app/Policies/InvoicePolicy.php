<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;

class InvoicePolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view invoices');
    }

    public function view(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo('view invoices');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create invoices');
    }

    public function update(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo('edit invoices');
    }

    public function delete(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo('delete invoices');
    }
}