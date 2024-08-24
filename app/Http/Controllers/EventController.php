<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index() {
        return Event::with('photos', 'user')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $event = $request->user()->events()->create($request->all());

        return response()->json($event, 201);
    }

    public function show($id) {
        $event = Event::with('photos')->findOrFail($id);
        return response()->json($event);
    }


    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $event = Event::findOrFail($id);
        $this->authorize('update', $event);
        $event->update($request->only('name', 'description'));

        return response()->json($event);
    }

    public function destroy($id) {
        $event = Event::findOrFail($id);
        $this->authorize('delete', $event);

        $event->delete();

        return response()->json(null, 204);
    }
}
