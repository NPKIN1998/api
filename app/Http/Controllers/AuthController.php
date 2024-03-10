<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(Request $request)
    {
       // Log::alert($request->all());
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
            'confirm_password' => 'required'
        ]);
        //check password
        if($request->input('password') != $request->input('confirm_password')) {
            return response()->json(['error', 'password do not match!']);
        }
        //unique email
        $user = User::where('phone', $request->input('phone'))->first();
        if($user) {
            return response()->json(['error','Phone has already been used']);
        }

        //create user
        $user = new User([
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        if ($user->save()) {
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            return response()->json([
                'message' => 'success',
                'accessToken' => $token,
            ], 200);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }


    /**
     * Login user and create token
     *
     * @param  [string] phone
     * @param  [string] password
     * @param  [boolean] remember_me
     */

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = request(['phone', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Incorrect password or phone'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        Log::alert($request->user());
        return response()->json($request->user());
    }

    public function userTeams(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->user();

            // Fetch users based on the upline ID
            $users = User::where('upline', $user->id)->get();

            // Additional details for each user
            $userDetails = [];
            foreach ($users as $u) {
                $userDetails[] = [
                    'id' => $u->id,
                    'name' => $u->name,
                    'phone' => $u->phone,
                    'totalInvestments' => $u->trades, // Assuming you have a method for calculating investments in the User model
                    'totalCashouts' => $u->cashout, // Assuming you have a method for calculating cashouts in the User model
                    'referralLink' => $u->id, // Assuming you have a referralLink attribute in the User model
                ];
            }
            Log::alert($userDetails);

            return response()->json(['users' => $userDetails]);
        } catch (\Exception $e) {
            Log::alert($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);

    }

}
