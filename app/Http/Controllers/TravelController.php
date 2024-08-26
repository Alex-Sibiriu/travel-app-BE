<?php

namespace App\Http\Controllers;

use App\Functions\Helper;
use App\Http\Requests\TravelRequest;
use App\Models\Travel;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $travels = Travel::where('user_id', $user->id)->orderBy('id', 'desc')
            ->with(['images', 'stops' => function ($query) {
                $query->orderBy('order', 'asc');
            }])->get();

        return response()->json(['success' => true, 'travels' => $travels]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TravelRequest $request)
    {
        $user = $request->user();

        $val_data = $request->all();
        $val_data['user_id'] = $user->id;
        $val_data['slug'] = Helper::generateSlug($val_data['title'], Travel::class);

        $travel = new Travel();
        $travel->fill($val_data);
        $travel->save();

        return response()->json(['success' => true, 'travel' => $travel]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TravelRequest $request, string $id)
    {
        $user = $request->user();

        $travel = Travel::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $val_data = $request->all();
        $val_data['slug'] = Helper::generateSlug($val_data['title'], Travel::class, $id);

        $travel->update($val_data);

        $updated_travel = Travel::where('id', $id)
            ->with(['stops' => function ($query) {
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

        $travel = Travel::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $travel->delete();

        return response()->json(['success' => true]);
    }
}
