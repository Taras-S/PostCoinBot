<?php

namespace App\Services\SlackButton;

use Frlnc\Slack\Core\Commander as SlackApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Oauth implementation to auth via Slack Button
 *
 * @see https://api.slack.com/docs/slack-button
 * @package App\Services\SlackButton
 */
class SlackButton
{
    /**
     * Client ID
     *
     * @var
     */
    protected $id;

    /**
     * Client secret
     *
     * @var
     */
    protected $secret;

    /**
     * Oauth code
     */
    protected $code;

    /**
     * @var Commander
     */
    protected $api;

    /**
     * SlackButton constructor.
     *
     * @param Commander $api
     * @param Request $request
     * @return boolean|\stdClass
     */
    public function __construct(SlackApi $api, Request $request)
    {
        $this->api = $api;
        $this->code = $request->input('code');
        $this->id = config('services.slack.client_id');
        $this->secret = config('services.slack.client_secret');
    }

    /**
     * Make ouath.access call and return button data
     *
     * @see https://api.slack.com/docs/slack-button
     * @param $code
     * @return \Frlnc\Slack\Contracts\Http\Response
     */
    public function getAccessData()
    {
         return $this->api->execute('oauth.access', [
            'client_id' => $this->id,
            'client_secret' => $this->secret,
            'code' => $this->code
        ])->getBody();
    }

    /**
     * Redirects to Slack oauth
     *
     * @param array $scope
     * @return Redirect
     */
    public function redirectWithScope(array $scope)
    {
        $scope = implode(',', $scope);
        return Redirect('https://slack.com/oauth/authorize?scope='.$scope.'&client_id='.$this->id);
    }
}