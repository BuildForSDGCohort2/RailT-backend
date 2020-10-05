<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Carbon\Carbon;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        //
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'mobile' => 'required|string|max:11',
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->role = 3;
        $user->password = bcrypt($request->password);

        if ($user->save()){
            return response()->json([
                'message' => 'User created successfully',
                'status_code' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Error creating new user',
                'status_code' => 500
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(Request $request){
        $request->validate([
            'password' => 'required|string',
            'email' => 'required|string|email'
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return response()->json([
                'message' => 'Invalid username/password',
                'status_code' => 401
            ], 401);
        }

        $user = $request->user();

        if ($user->role == 1){
            $tokenData = $user->createToken('Personal Access Token', ['admin']);
        } elseif($user->role == 2) {
            $tokenData = $user->createToken('Personal Access Token', ['tsp']);
        } else {
            $tokenData = $user->createToken('Personal Access Token', ['passenger']);
        }

        $token = $tokenData->token;
        $token->expires_at = Carbon::now()->addWeeks();

        $role = Role::find($user->role)->name;
        
        if ($token->save()){
            return response()->json([
                'user' => $user,
                'role' => $role,
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

    public function logout(Request $request){

        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Logout successfully',
            'status_code' => 200
        ], 200);

    }

    public function profile(){

        if(Auth::user()){
            
            return response()->json(Auth::user(), 200);

        } else {
            
            return response()->json([
                'message' => 'Not Logged In',
                'status_code' => 500
            ], 500);

        }

    }
}
