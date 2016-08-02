<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface MemberRepository
 * @package namespace App\Repositories;
 */
interface MemberRepository extends RepositoryInterface
{
   public function getFromMessenger($messenger, $id, array $name = []);
   public function getWithoutName();
}
