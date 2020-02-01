<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Get all User.
     *
     * @return Response
     */
    public function allUsers()
    {
        $iam = Auth::user();

        return response()->json(['users' =>  $iam->family->users], 200);
    }

    /**
     * Get one user.
     *
     * @return Response
     */
    public function singleUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $iam = Auth::user();

            if ($iam->family->id !== $user->family->id) {
                return response()->json(['message' => 'Cannot See Profile'], 403);
            }

            return response()->json(['user' => $user], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'User Not Found!'], 404);
        }
    }

    public function editProfile(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'name' => 'string',
            'hp' => 'string',
            'address' => 'string',
            'status' => 'string'
        ]);

        try {
            $iam = Auth::user();

            ($request->input('name')) ? $name = $request->input('name') : $name = null;
            ($request->input('hp')) ? $hp = $request->input('hp') : $hp = null;
            ($request->input('address')) ? $address = $request->input('address') : $address = null;
            ($request->input('status')) ? $status = $request->input('status') : $status = null;

            $user = User::where('id', $iam->id)->first();
            $user->update([
              'name' => $name,
              'hp' => $hp,
              'address' => $address,
              'status' => $status
            ]);

            //return successful response
            return response()->json(['user' => $user, 'message' => 'Profile Edited Succesfully'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Edit Profile Failed!'], 409);
        }
    }

    public function editPhoto(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            //
        ]);

        try {
            $iam = Auth::user();

            ($request->input('status')) ? $status = $request->input('status') : $status = null;

            $user = User::where('id', $iam->id)->first();

            ($request->file('photo') != null) ? $namaPhoto = Str::random(32).'.'.$request->file('photo')->getClientOriginalExtension() : $namaPhoto = null;

            if (isset($user->photo)) {
                unlink(base_path().'/public/photo-profile/'.$user->photo);
            }

            $user->update([
              'photo' => $namaPhoto,
            ]);

            ($request->file('photo') != null) ? $request->file('photo')->move(base_path().('/public/photo-profile'), $namaPhoto) : null;

            //return successful response
            return response()->json(['user' => $user, 'message' => 'Photo Edited Succesfully'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Edit Photo Failed!'], 409);
        }
    }

    public function editPassword(Request $request)
    {
        $this->validate($request, [
            'old_password'     => 'required',
            'new_password'     => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $data = $request->all();

        if(app('hash')->check($data['old_password'], Auth::user()->password)){
            try {
                $iam = Auth::user();

                $iam->password = app('hash')->make($data['new_password']);
                $iam->save();

                //return successful response
                return response()->json(['message' => 'Password Edited Succesfully'], 200);
            } catch (\Exception $e) {
                //return error message
                return response()->json(['message' => 'Edit Password Failed!'], 409);
            }
        }else{
            //return error message
            return response()->json(['message' => 'You Have Entered Wrong Password!'], 409);
        }
    }

    public function storeGCMToken(Request $request)
    {
      //validate incoming request
      $this->validate($request, [
          'gcmtoken' => 'string'
      ]);

      try {
          $iam = Auth::user();

          $iam->update([
            'gcmtoken' => $request->input('gcmtoken')
          ]);

          //return successful response
          return response()->json(['GCM Token' => $request->input('gcmtoken'), 'message' => 'GCM Token Stored Succesfully'], 200);
      } catch (\Exception $e) {
          //return error message
          return response()->json(['message' => 'Store GCM Token Failed!'], 409);
      }
    }

    public function updateStatus(Request $request, $id)
    {dd(Carbon::now()->toDateTimeString());
      //validate incoming request
      $this->validate($request, [
          'status' => 'boolean'
      ]);

      return $request->all();
    }

    public function updateTimer(Request $request, $id)
    {
      //validate incoming request
      $this->validate($request, [
          'until' => 'date_format:H:i:s'
      ]);

      return $request->all();
    }
}
