<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Website;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebsitePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the website.
     */
    public function view(User $user, Website $website)
    {
        return $user->id === $website->user_id;
    }

    /**
     * Determine whether the user can update the website.
     */
    public function update(User $user, Website $website)
    {
        return $user->id === $website->user_id;
    }

    /**
     * Determine whether the user can delete the website.
     */
    public function delete(User $user, Website $website)
    {
        return $user->id === $website->user_id;
    }
}
