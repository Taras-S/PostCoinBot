<?php
namespace App\Jobs;

use App\Entities\Member;
use Frlnc\Slack\Core\Commander as SlackApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;

class UpdateMemberNames implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Member
     */
    protected $members;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    /**
     * Slack API instance
     *
     * @var SlackApi
     */
    protected $api;

    /**
     * UpdateMemberNames constructor.
     *
     * @param Member $members
     */
    public function __construct(Collection $members, SlackApi $api)
    {
        $this->api = $api;
        $this->members = $members;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->members as $member) {
            $member->messenger_name = $this->getNameByID($member->messenger_id);
            $member->save();
        }
    }

    /**
     * Returns user name by his Slack ID
     *
     * @param $slackId
     * @return mixed
     * @throws \Exception
     */
    protected function getNameByID($slackId)
    {
        $response = $this->api->execute('users.info', [
            'user' => $slackId
        ]);

        if ($response['ok']) {
            return $response['usern']['name'];
        } else {
            throw new \Exception('Slack users.info dont OK');
        }
    }
}