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
     * Create a new user or returns existing
     *
     * @param $messenger
     * @param $id
     * @param $accessToken
     * @param $botAccessToken
     * @return mixed
     */
    public function getFromMessenger($messenger, $id, $accessToken, $botAccessToken)
    {
        $user = User::firstOrCreate(['messenger' => $messenger, 'messenger_id' => $id]);
        $user->update([
            'access_token' => $accessToken,
            'bot_access_token' => $botAccessToken
        ]);

        return $user;
    }
}
