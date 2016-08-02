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
     * Messenger name to messenger DB prefix map
     *
     * @var array
     */
    protected $messengerPrefixes = [
        'slack' => 'slack_'
    ];

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
     * Return members without name
     *
     * @return mixed
     */
    public function getWithoutName()
    {
        return Member::withoutName()-get();
    }

    /**
     * Returns current member model by messenger ID. If model not found, new model will be created.
     *
     * @param string $messengerId Messenger ID with messenger name prefix
     * @param string $messengerName Username in messenger
     * @return Member
     */
    public function getFromMessenger($messenger, $id, array $update = [])
    {
        $member = Member::firstOrNew(['messenger_id' => $id, 'messenger_name' => $messenger]);
        $member->update(array_filter($update));

        $member->save();
        return $member;
    }
}
