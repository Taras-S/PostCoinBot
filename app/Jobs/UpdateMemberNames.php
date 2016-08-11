<?php
namespace App\Jobs;

use App\Entities\Member;
use Frlnc\Slack\Core\Commander as SlackApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use App\Exceptions\SlackApiException;
use Illuminate\Support\Facades\Log;

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
    public function __construct(SlackApi $api, Member ...$members)
    {
        $this->api = $api;
        $this->members = $members;
    }
    /**
     * Execute the job.
     *
     * @param SlackApi $api
     * @return void
     */
    public function handle()
    {
        Log::info('handle');
        foreach($this->members as $member) {
            $member->messenger_name = $this->getNameByID($member->messenger_id);
            $member->save();
        }
    }

    /**
     * Returns user name by his Slack ID
     *
     * @param $slackId
     * @param SlackApi
     * @return mixed
     * @throws \Exception
     */
    protected function getNameByID($slackId)
    {
        $response = $this->api->execute('users.info', [
            'user' => $slackId
        ])->getBody();

        if ($response['ok']) {
            return $response['user']['name'];
        } else {
            throw new SlackApiException('Slack users.info method returned error: ' . $response['error']);
        }
    }
}