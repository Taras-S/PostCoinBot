<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserRepository;
use App\Entities\SocialAccount;
use App\Entities\User;
use App\Validators\UserValidator;
use Laravel\Socialite\Contracts\User as MessengerUser;

/**
 * Class UserRepositoryEloquent
 * @package namespace App\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Creates new user or returns existing
     *
     * @param MessengerUser $user
     * @param string $provider
     * @return mixed
     */
    public function getFromMessenger($messenger, MessengerUser $userData)
    {
        $user = User::firstOrCreate(['email' => $userData->getEmail()])->update(['name' => $userData->getName()]);
        $user->socialAccounts()->updateOrCreate([
            'messenger' => $messenger,
            'messenger_user_id' => $userData->getId()
        ]);

        return $user;
    }
}
