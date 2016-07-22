<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface SendingRepository
 * @package namespace App\Repositories;
 */
interface SendingRepository extends RepositoryInterface
{
    /**
     * Returns current week statistics by recipient Slack ID
     *
     * @param $recipientSlackId
     * @return mixed
     */
    public function getThisWeekStatForRecipient($recipientSlackId);

    /**
     * Returns previous week statistics by recipient Slack ID
     *
     * @param $recipientSlackId
     * @return mixed
     */
    public function getLastWeekStatForRecipient($recipientSlackId);

    /**
     * Returns TOP recipients for period
     *
     * @param array $period
     * @param $limit Number of rows that will be returned
     * @return mixed
     */
    public function getTopRecipients(array $period, $limit);
}
