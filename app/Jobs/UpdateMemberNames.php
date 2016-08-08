<?php
namespace App\Jobs;
use App\Jobs\Job;
use App\Repositories\MemberRepository;
use Carbon\Carbon;
use Frlnc\Slack\Core\Commander as SlackApi;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use React\EventLoop\Factory;
use Slack\User;
use App\Entities\Member;

/**
 * Slack Event API & RTM doesnt provide full information like name about users, so we need to query it via API.
 * Because of low rate limit we use queue to prevent slowdown of application
 *
 * @package App\Jobs
 */
class UpdateMemberNames extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

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
    public function __construct(array $members, SlackApi $api)
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
     * @param $slack_id
     * @return string
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