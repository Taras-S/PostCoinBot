<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Entities\Team;
use App\Validators\TeamValidator;

/**
 * Class TeamRepositoryEloquent
 * @package namespace App\Repositories;
 */
class TeamRepositoryEloquent extends BaseRepository implements TeamRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Team::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Create a new team or returns existing
     *
     * @param $messenger
     * @param $id
     * @param $accessToken
     * @param $botAccessToken
     * @return mixed
     */
    public function getFromMessenger($messenger, $id, $accessToken, $botAccessToken)
    {
        $team = Team::firstOrCreate(['messenger' => $messenger, 'messenger_id' => $id]);
        $team->update([
            'access_token' => $accessToken,
            'bot_access_token' => $botAccessToken
        ]);

        return $team;
    }

    /**
     * Returns token to make API requests
     *
     * @return string
     */
    public function getTokenByMessengerId($messengerId)
    {
        try {
            return Team::where('messenger_id', $messengerId)->firstOrFail()->bot_access_token;
        } catch (ModelNotFoundException $e) {
            return '';
        }
    }
}
