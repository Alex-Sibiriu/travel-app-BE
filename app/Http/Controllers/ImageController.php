<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Models\Image;
use App\Models\Travel;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImageRequest $request)
    {
        $user = $request->user();
        $travel = Travel::where('id', $request['travel_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $images = $request->file('images');

        foreach ($images as $image) {
            $imagePath = $image->store('images', 'public');

            Image::create([
                'travel_id' => $travel->id,
                'path' => 'storage/' . $imagePath,
            ]);
        }

        $updated_travel = Travel::where('id', $request['travel_id'])->with(['images', 'stops' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->first();

        return response()->json(['success' => true, 'travel' => $updated_travel]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();

        $image = Image::findOrFail($id);

        $travel = Travel::where('id', $image->travel_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $image->delete();

        $updated_travel = Travel::where('id', $image->travel_id)
            ->where('user_id', $user->id)
            ->with(['images', 'stops' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'travel' => $updated_travel,
        ]);
    }
}
