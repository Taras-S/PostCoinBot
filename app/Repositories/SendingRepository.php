<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use App\Entities\Member;

/**
 * Interface SendingRepository
 * @package namespace App\Repositories;
 */
interface SendingRepository extends RepositoryInterface
{
    /**
     * Returns current week statistics by recipient Slack ID
     *
     * @param Member $member
     * @return mixed
     */
    public function getThisWeekStatForRecipient(Member $member);

    /**
     * Returns previous week statistics by recipient Slack ID
     *
     * @param Member $member
     * @return mixed
     */
    public function getLastWeekStatForRecipient(Member $member);

    /**
     * Returns TOP recipients for period
     *
     * @param array $period
     * @param $limit Number of rows that will be returned
     * @return mixed
     */
    public function getTopRecipients(array $period, $limit);
}
