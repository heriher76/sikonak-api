<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
  public function index()
  {
      try {
          $iam = Auth::user();
          $events = $iam->events;
          //return successful response
          return response()->json(['events' => $events, 'message' => 'Event Get Succesfully'], 200);
      } catch (\Exception $e) {
          //return error message
          return response()->json(['message' => 'Cannot Get Event!'], 409);
      }
  }
  public function store(Request $request)
  {
      //validate incoming request
      $this->validate($request, [
          'name' => 'required|string',
          'date' => 'required',
          'description' => 'required|string',
          'reminder' => 'required|integer'
      ]);

      try {
          $user = Auth::user();

          if ($user->id_family != null) {
            $event = new Event;
            $event->name = $request->input('name');
            $event->date = $request->input('date');
            $event->description = $request->input('description');
            $event->reminder = $request->input('reminder');
            $event->id_family = $user->id_family;
            $event->id_user = $user->id;

            $event->save();

            //return successful response
            return response()->json(['event' => $event, 'message' => 'Event Created'], 201);
          }else{
            return response()->json(['message' => 'Error, You Dont Have Family!'], 409);
          }
      } catch (\Exception $e) {
          //return error message
          return response()->json(['message' => 'Create Event Failed!'], 409);
      }
  }

  public function update(Request $request, $id)
  {
      //validate incoming request
      $this->validate($request, [
          'name' => 'required|string',
          'date' => 'required',
          'description' => 'required|string',
          'reminder' => 'required|integer'
      ]);

      try {
          $event = Event::where('id', $id)->first();
          $event->update([
            'name' => $request->input('name'),
            'date' => $request->input('date'),
            'description' => $request->input('description'),
            'reminder' => $request->input('reminder')
          ]);

          //return successful response
          return response()->json(['event' => $event, 'message' => 'Event Updated'], 200);
      } catch (\Exception $e) {
          //return error message
          return response()->json(['message' => 'Update Event Failed!'], 409);
      }
  }

  public function destroy($id)
  {
      try {
          Event::destroy($id);
          //return error message
          return response()->json(['message' => 'Event Deleted!'], 200);
      } catch (\Exception $e) {
          //return error message
          return response()->json(['message' => 'Delete Event Failed!'], 409);
      }
  }
}
