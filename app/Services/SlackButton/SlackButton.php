<?php

namespace App\Services\SlackButton;

use Frlnc\Slack\Core\Commander;
use Illuminate\Http\Request;

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
    public function __construct(SlackAPI $api, Request $request)
    {
        if (!$request->has('code')) return false;

        $this->api = $api;

        $this->client = config('services.slack.client_id');
        $this->secret = config('services.slack.client_secret');

        return (object) $this->getAccessData($request->code);
    }

    /**
     * Make ouath.access call and return button data
     *
     * @see https://api.slack.com/docs/slack-button
     * @param $code
     * @return \Frlnc\Slack\Contracts\Http\Response
     */
    public function getAccessData($code)
    {
        return $this->api->execute('oauth.access', [
            'client_id' => $this->id,
            'client_secret' => $this->secret,
            'code' => $code,
        ]);
    }
}