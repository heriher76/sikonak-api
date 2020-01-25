<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected function respondWithToken($token)
    {
        return response()->json([
          'error' => false,
          'token_type' => 'Bearer',
          'access_token' => $token,
          'expires_in' => $this->guard()->factory()->getTTL() * 60,
          'user' => JWTAuth::user(),
          'message' => 'Login Berhasil !'
        ]);
    }
    private function guard()
  	{
    		// return auth()->guard('api');
    		return Auth::guard('api');
  	}
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'hp' => 'string',
            // 'address' => 'string',
            'status' => 'string'
        ]);

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->hp = $request->input('hp');
            // $user->address = $request->input('address');
            $user->status = $request->input('status');

            $user->assignRole('parent');

            //test
            $user->id_family = 4;

            // ($request->file('photo') != null) ? $namaPhoto = Str::random(32).'.'.$request->file('photo')->getClientOriginalExtension() : $namaPhoto = null;
            //
            // $user->photo = $namaPhoto;
            //
            // ($request->file('photo') != null) ? $request->file('photo')->move(base_path().('/public/photo-profile'), $namaPhoto) : null;
            $user->save();

            $credentials = $request->only(['email', 'password']);

            if (! $token = Auth::attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            //return successful response
            return response()->json(['user' => $user, 'token' => 'Bearer '.$token, 'message' => 'Berhasil Register!'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Registrasi Gagal!'], 409);
        }
    }

    public function registerChild(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            // 'photo' => 'mimes:jpg,jpeg,png'
        ]);

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->id_family = Auth::user()->family->id;

            $user->assignRole('child');

            // ($request->file('photo') != null) ? $namaPhoto = Str::random(32).'.'.$request->file('photo')->getClientOriginalExtension() : $namaPhoto = null;
            //
            // $user->photo = $namaPhoto;
            //
            // ($request->file('photo') != null) ? $request->file('photo')->move(base_path().('/public/photo-profile'), $namaPhoto) : null;

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'Register Anak Berhasil!'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Register Anak Gagal!'], 409);
        }
    }
}
