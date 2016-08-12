<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TeamRepository
 * @package namespace App\Repositories;
 */
interface TeamRepository extends RepositoryInterface
{
    public function getFromMessenger($messenger, $id, $accessToken, $botAccessToken);
    public function getTokenByMessengerId($messengerId);
}
