<?php

namespace App\Services\User;

use App\User;

class UserService
{
    /**
     * Find a user by email
     *
     * @param string $email
     * @return User instance
     */
    public function findUserByEmail($email)
    {
        return User::whereEmail($email)->first();
    }

    public function getUserByID($id)
    {
        return User::where('id',$id)->first();
    }

}
