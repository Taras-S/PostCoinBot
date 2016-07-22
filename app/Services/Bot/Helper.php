<?php

namespace App\Services\Bot;

class Helper
{
    protected $messengerPrefixes = [
        'slack' => 'slack_'
    ];

    /**
     * Returns Member ID with messenger prefix
     *
     * @param string $messenger Name of messenger, request come from
     * @param string $id user id in messenger
     * @return string
     */
    public function memberId($messenger, $id)
    {
        return $this->messengerPrefixes[$messenger] . $id;
    }
}