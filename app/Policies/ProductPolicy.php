<?php

namespace App\Policies;

use App\User;
use App\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function edit (User $user, Product $product)
    {
        return $user->id === $product->user_id;
    }

    public function update (User $user, Product $product)
    {
        return $user->id === $product->user_id;
    }

    public function destroy (User $user, Product $product)
    {
        return $user->id === $product->user_id;
    }
}
