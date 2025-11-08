<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $cacheKey = 'events_page_' . $request->get('page', 1);
        $events = cache()->remember($cacheKey, 60, function () use ($request) {
            $query = Event::query();
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            }

            if( $request->has('date') ) {
                $date = $request->get('date');
                $query->whereDate('date', $date);
            }

            if ( $request->has('location') ) {
                $location = $request->get('location');
                $query->where('location', 'like', '%' . $location . '%');
            }

            return $query->with('tickets')->paginate(10);
        });

        return response()->json($events, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event = Event::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'date' => $request->input('date'),
            'location' => $request->input('location'),
            'created_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        $event = Event::with('tickets')->find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json([
            'message' => 'Event details',
            'event' => $event
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        if($request->user()->id !== $event->organizer_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'date' => 'sometimes|required|date',
            'location' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event->update($request->only(['title', 'description', 'date', 'location']));

        return response()->json([
            'message' => 'Event updated successfully',
            'event' => $event
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        //

        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        if($request->user()->id !== $event->organizer_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully'], 200);
    }
}
