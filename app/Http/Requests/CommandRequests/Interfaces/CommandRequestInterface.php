<?php

namespace App\Http\Requests\CommandRequests\Interfaces;

use App\Entities\Member;
use App\Repositories\MemberRepository;

interface CommandRequestInterface
{
    /**
     * Returns current command data
     *
     * @return \stdClass
     */
    public function command();

    /**
     * Returns current member
     *
     * @param MemberRepository $member
     * @return Member
     */
    public function member(MemberRepository $member);
}