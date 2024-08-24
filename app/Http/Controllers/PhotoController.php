<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function store(Request $request, $eventId)
    {
        $request->validate([
            'photo_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $filePath = $request->file('photo_url')->store('photos', 's3');
        $url = Storage::disk('s3')->url($filePath);

        $photo = new Photo();
        $photo->event_id = $eventId;
        $photo->user_id = auth()->id();
        $photo->photo_url = $url;
        $photo->status = 'pending';
        $photo->save();

        return response()->json(['message' => 'Photo uploaded successfully', 'photo' => $photo]);
    }

    public function index($eventId)
    {
        $event = Event::with('photos')->findOrFail($eventId);
        return response()->json($event->photos);
    }

    public function getAllPhotos()
    {
        return Photo::with('user')->get();
    }

    public function approvePhoto(Photo $photo)
    {
        $photo->status = 'approved';
        $photo->save();

        return response()->json(['message' => 'Photo approved successfully']);
    }

    public function rejectPhoto(Photo $photo)
    {
        $photo->status = 'rejected';
        $photo->save();

        return response()->json(['message' => 'Photo rejected successfully']);
    }

    public function getPhotosByEvent($eventId)
    {
        return Photo::where('event_id', $eventId)->get();
    }

    public function destroy(Photo $photo)
    {
        $fileName = basename($photo->photo_url);
        $filePath = 'photos/' . $fileName;

        if (Storage::disk('s3')->exists($filePath)) {
            Storage::disk('s3')->delete($filePath);
        }

        $photo->delete();

        return response()->json(['message' => 'Photo deleted successfully']);
    }
}
