<?php

namespace App\Policies;

use App\Models\ShippingCost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShippingCostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('carriers.shipping-cost');
    }

    public function create(User $user)
    {
        return $user->can('carriers.shipping-cost');
    }

    public function delete(User $user, ShippingCost $shipping_cost)
    {
        return $user->can('carriers.shipping-cost');
    }
}
