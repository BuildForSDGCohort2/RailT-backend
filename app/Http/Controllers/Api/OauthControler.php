<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Auth;
use App\User;

class OauthControler extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $providers = [
        'github','facebook','google','twitter'
    ];

    public function redirectToProvider($driver)
    {
        if( ! $this->isProviderAllowed($driver) ) {
            return $this->sendFailedResponse("{$driver} is not currently supported");
        }

        try {

            return Socialite::driver($driver)->redirect();

        } catch (Exception $e) {
            // You should show something simple fail message
            return $this->sendFailedResponse($e->getMessage());
        }
    }

  
    public function handleProviderCallback( $driver )
    {
        try {
            $user = Socialite::driver($driver)->user();
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }

        // check for email in returned user
        return empty( $user->email )
            ? $this->sendFailedResponse("No email id returned from {$driver} provider.")
            : $this->loginOrCreateAccount($user, $driver);
    }


    protected function sendFailedResponse($msg = null)
    {
        return response()->json([
            'message' => $msg,
            'status_code' => 401
        ], 401);
    }

    protected function loginOrCreateAccount($providerUser, $driver)
    {
        // check for already has account
        $user = User::where('email', $providerUser->getEmail())->first();

        // if user already found
        if( $user ) {
            // update the avatar and provider that might have changed
            $user->update([
                'avatar' => $providerUser->avatar,
                'provider' => $driver,
                'provider_id' => $providerUser->id,
                'provider_token' => $providerUser->token
            ]);

        } else {


            if($providerUser->getEmail()){ //Check email exists or not. If exists create a new user
               $user = User::create([
                  'name' => $providerUser->getName(),
                  'email' => $providerUser->getEmail(),
                  'avatar' => $providerUser->getAvatar(),
                  'provider' => $driver,
                  'provider_id' => $providerUser->getId(),
                  'provider_token' => $providerUser->token,
                  'password' => '' // user can use reset password to create a password
            ]);

             } else {
            
                return response()->json([
                    'message' => 'Your account doesnt have email address',
                    'status_code' => 401
                ], 401);
            
            }
        }

        // login the user
        if(!Auth::attempt($user)){
            return response()->json([
                'message' => 'Invalid username/password',
                'status_code' => 401
            ], 401);
        }

        $user = $request->user();

        if ($user->role == '1'){
            $tokenData = $user->createToken('Personal Access Token', ['admin']);
        } elseif ($user->role == '2'){
            $tokenData = $user->createToken('Personal Access Token', ['tsp']);
        } elseif ($user->role == '3'){
            $tokenData = $user->createToken('Personal Access Token', ['passenger']);
        } else {
            $tokenData = $user->createToken('Personal Access Token', ['guest']);
        }

        $token = $tokenData->token;

        if ($request->remember_me){
            $token->expired_at = Carbon::now()->addWeeks();
        }

        if ($token->save()){
            return response()->json([
                'user' => $user,
                'access_token' => $tokenData->accessToken,
                'token_type' => 'Bearer',
                'token_scope' => $tokenData->token->scope[0],
                'expired_at' => Carbon::parse($tokenData->token->expired_at)->toDayDateTimeString(),
                'status_code' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Some error occurred, try again',
                'status_code' => 500
            ], 500);
        }

    }

    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }

}
