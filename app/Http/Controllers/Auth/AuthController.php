<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\TeamRepository;
use App\Services\SlackButton\SlackButton;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Entities\Team;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var SlackButton
     */
    protected $provider;

    /**
     * Create a new authentication controller instance.
     *
     * @param SlackButton $provider
     * @return void
     */
    public function __construct(SlackButton $provider)
    {
        $this->provider = $provider;
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return $this->provider->redirectWithScope([
            'incoming-webhook',
            'commands',
            'bot',
            'reactions:read',
            'users:read',
            'chat:write:bot'
        ]);
    }

    /**
     * Obtain team information from Slack
     *
     * @return Response
     */
    public function handleProviderCallback(TeamRepository $teams)
    {
        $data = $this->provider->getAccessData();
        $team = $teams->getFromMessenger('slack', $data['team_id'], $data['access_token'], $data['bot']['bot_access_token']);
        Auth::login($team, true);
        return redirect()->route('dashboard');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Team
     */
    protected function create(array $data)
    {
        return Team::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
