<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use Laravel\Socialite\Contracts\User as MessengerUser;

/**
 * Interface UserRepository
 * @package namespace App\Repositories;
 */
interface UserRepository extends RepositoryInterface
{
    public function getFromMessenger($messenger, $id, $accessToken, $botAccessToken);
}
