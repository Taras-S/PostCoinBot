<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\MemberRepository;
use App\Entities\Member;
use App\Validators\MemberValidator;

/**
 * Class MemberRepositoryEloquent
 * @package namespace App\Repositories;
 * @implements MemberRepositry
 */
class MemberRepositoryEloquent extends BaseRepository implements MemberRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Member::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Returns current member model
     *
     * @param string $messengerId Messenger ID with messenger name prefix
     * @param string $messengerName Username in messenger
     * @return Member
     */
    public function getFromMessenger($messengerId, $messengerName)
    {
        $member = Member::firstOrNew(['messenger_id' => $messengerId]);
        if ($member->username != $messengerName) $member->username = $messengerName;
        $member->save();

        return $member;
    }
}
