<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\User;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
  public function getLocation($id)
  {
      try {
          $iam = User::where('id', $id)->first();
          $location = $iam->location;
          //return successful response
          return response()->json(['location' => $location, 'message' => 'Location Get Succesfully'], 200);
      } catch (\Exception $e) {
          //return error message
          return response()->json(['message' => 'Cannot Get Location!'], 409);
      }
  }

  public function update(Request $request)
  {
      //validate incoming request
      $this->validate($request, [
          'lat' => 'required|string',
          'long' => 'required|string'
      ]);

      try {
          $iam = Auth::user();
          if($iam->location == null) {
            Location::create([
              'lat' => $request->input('lat'),
              'long' => $request->input('long'),
              'id_user' => $iam->id
            ]);
          }else{
            $iam->location->update([
              'lat' => $request->input('lat'),
              'long' => $request->input('long')
            ]);
          }

          //return successful response
          return response()->json(['location' => $iam->location, 'message' => 'Location Updated'], 200);
      } catch (\Exception $e) {
          //return error message
          return response()->json(['message' => 'Update Location Failed!'], 409);
      }
  }
}
