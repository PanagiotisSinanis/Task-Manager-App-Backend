<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AuthManager extends Controller
{
   

    function LoginPost(LoginRequest $request)
    {
       // $request->validate([
          //  'email' => 'required',
           // 'password' => 'required',
        //]);

        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials)){
            return redirect()->intended(route("home"));
            //return redirect()->intended('/home'); if i dont have a name
        }
        return redirect(route("login"))->with("error","Invalid Email or Password");
    }

    function register()
    {
        return view('auth.register');
    }

    function registerPost(RegisterUserRequest  $request){
        //$request->validate([
          //  'fullname'=>'required',
           // 'email'=>'required|email|unique:users',
           // 'password'=>'required|min:6'
        //]);
        $user = new User();
        $user->name=$request->fullname;
        $user->email=$request->email;
        $user->password = Hash::make($request->password); 
        if($user->save()){
            return redirect(route("login"))->with("success","Registration Successful");
        }
        return redirect(route("register"))->with("error","Registration Failed");
    }

  public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Σβήνουμε παλιά tokens αν θες
        $user->tokens()->delete();

        // Δημιουργούμε νέο token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
             'roles' => $user->getRoleNames(), 
        ]);
    }

public function apiLogout(Request $request)
{
    $user = $request->user();

    if ($user) {
        $user->currentAccessToken()->delete();
    }

    return response()->json(['message' => 'Logout successful']);
}
public function apiRegister(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
    ]);

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'token' => $token,
            'user' => $user,
        ], 201);

    } catch (\Exception $e) {
        Log::error('Register error: ' . $e->getMessage());
        return response()->json(['message' => 'Registration failed'], 500);
    }
}



}
