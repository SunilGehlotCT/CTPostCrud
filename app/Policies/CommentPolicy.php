<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given comment can be updated by the user.
     */
    public function allow(User $user, Comment $comment): Response
    {
        return $user->id === $comment->user_id
                    ? Response::allow()
                    : Response::deny('You do not own this comment.');
    }

    // Any method here that match the controller method name will be automatically called.
}
