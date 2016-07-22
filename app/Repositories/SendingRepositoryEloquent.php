<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\SendingRepository;
use App\Entities\Sending;
use App\Validators\SendingValidator;

/**
 * Class SendingRepositoryEloquent
 * @package namespace App\Repositories;
 * @implements SendingRepositry
 */
class SendingRepositoryEloquent extends BaseRepository implements SendingRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Sending::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Returns current week statistics by recipient Slack ID
     *
     * @param $recipientSlackId
     * @return mixed
     */
    public function getThisWeekStatForRecipient($id)
    {
        return Sending::where('recipient_id', $recipientSlackId)->thisWeek()->count();
    }

    /**
     * Returns previous week statistics by recipient Slack ID
     *
     * @param $recipientSlackId
     * @return mixed
     */
    public function getLastWeekStatForRecipient($id)
    {
        return Sending::where('to_messenger_id', $recipientSlackId)->lastWeek()->count();
    }

    /**
     * Returns TOP recipients for period
     *
     * @param array $period
     * @param $limit Number of rows that will be returned
     * @return mixed
     */
    public function getTopRecipients(array $period, $limit)
    {
        return Sending::select(['recipient_id', DB::raw('SUM(amount)')])
            ->whereBetween('created_at', $period)
            ->groupBy('recipient_id')
            ->orderBy(DB::raw('SUM(amount)'), 'DESC')
            ->limit($limit)
            ->get();
    }
}
