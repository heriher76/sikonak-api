<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Events\ChatSubmitted;

class ChatsController extends Controller
{
  public function fetchMessages()
  {
      try {
          $iam = Auth::user();
          $messages = $iam->family->messages;
          //return successful response
          return response()->json(['messages' => $messages, 'message' => 'Messages Get Succesfully'], 200);
      } catch (\Exception $e) {
          //return error message
          return response()->json(['message' => 'Cannot Get Messages!'], 409);
      }
  }

  public function sendMessage(Request $request)
  {
      //validate incoming request
      $this->validate($request, [
          'message' => 'required|string'
      ]);

      $iam = Auth::user();
      $pushdata['title'] = $iam->name;
      $pushdata['message'] = $request->input('message');
      $pushdata['id'] = $iam->id;

      $registration_ids = $this->getRegistrationId();

      $fields = array(
          'registration_ids' => $registration_ids,
          'data' => $pushdata,
      );

      $pushNotif = $this->sendPushNotification($fields);
dd($pushNotif);
      try {
          $message = $iam->family->messages()->create([
            'message' => $request->input('message'),
            'name' => Auth::user()->name,
            'id_user' => $iam->id
          ]);

          //return successful response
          return response()->json(['messages' => $message, 'message' => 'Send Message Succesfully'], 200);
      } catch (\Exception $e) {printf($e);
          //return error message
          return response()->json(['message' => 'Send Message Failed!'], 409);
      }
  }

  private function getRegistrationId()
  {
      $iam = Auth::user();
      $myFamilyTokens = $iam->family->users->where('id', '!=', $iam->id)->pluck('gcmtoken');

      return $myFamilyTokens;
  }

  private function sendPushNotification($fields){
      $url = 'https://fcm.googleapis.com/fcm/send';

      $headers = array(
          'Authorization: key=' . env('GOOGLE_FIREBASE_API_KEY', null),
          'Content-Type: application/json'
      );

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

      $result = curl_exec($ch);
      if ($result === FALSE) {
          die('Curl failed: ' . curl_error($ch));
      }
      curl_close($ch);

      return $result;
  }
}
