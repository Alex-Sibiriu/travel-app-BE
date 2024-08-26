<?php

namespace App\Http\Controllers;

use App\Functions\Helper;
use App\Http\Requests\StopRequest;
use App\Models\Stop;
use App\Models\Travel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StopController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StopRequest $request)
    {
        $user = $request->user(); // Recupera l'utente autenticato

        $travel = Travel::where('id', $request['travel_id'])->where('user_id', $user->id)->firstOrFail();

        $val_data = $request->all();
        $maxOrder = Stop::where('travel_id', $travel->id)->where('day', $request['day'])->max('order');

        $val_data['order'] = $maxOrder + 1;
        $val_data['slug'] = Helper::generateSlug($val_data['title'], Stop::class);

        $stop = new Stop();
        $stop->fill($val_data);
        $stop->save();

        $updated_travel = Travel::where('id', $request['travel_id'])
            ->with(['images', 'stops' => function ($query) {
                $query->orderBy('order', 'asc');
            }])->first();

        return response()->json(['success' => true, 'travel' => $updated_travel, 'stop' => $stop]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StopRequest $request, string $id)
    {
        $user = $request->user();

        $stop = Stop::findOrFail($id);
        $travel = Travel::where('id', $stop->travel_id)->where('user_id', $user->id)->firstOrFail();

        $val_data = $request->all();
        $order = $val_data['order'];
        $currentOrder = $stop->order;

        if ($order !== $currentOrder) {
            DB::transaction(function () use ($request, $order, $currentOrder) {
                if ($order < $currentOrder) {
                    Stop::where('travel_id', $request['travel_id'])
                        ->where('day', $request['day'])
                        ->whereBetween('order', [$order, $currentOrder - 1])
                        ->increment('order');
                } else {
                    Stop::where('travel_id', $request['travel_id'])
                        ->where('day', $request['day'])
                        ->whereBetween('order', [$currentOrder + 1, $order])
                        ->decrement('order');
                }
            });
        }

        $val_data['slug'] = Helper::generateSlug($val_data['title'], Stop::class);
        $stop->update($val_data);

        $updated_travel = Travel::with(['images', 'stops' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->findOrFail($request['travel_id']);

        $updated_day = Stop::where('travel_id', $updated_travel->id)
            ->where('day', $request['day'])
            ->orderBy('order', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'travel' => $updated_travel,
            'stop' => $stop,
            'dayStops' => $updated_day
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();

        $stop = Stop::findOrFail($id);

        $travel = Travel::where('id', $stop->travel_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $subsequent_stops = Stop::where('travel_id', $stop->travel_id)
            ->where('day', $stop->day)
            ->where('order', '>', $stop->order)
            ->get();

        foreach ($subsequent_stops as $subsequent_stop) {
            $subsequent_stop->order -= 1;
            $subsequent_stop->save();
        }

        $stop->delete();

        // Opzionale: ritorna gli Stop aggiornati per il giorno specificato
        $updated_day = Stop::where('travel_id', $stop->travel_id)
            ->where('day', $stop->day)
            ->orderBy('order', 'asc')
            ->get();

        $updated_travel = Travel::where('id', $stop->travel_id)->with(['images', 'stops' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->first();

        return response()->json([
            'success' => true,
            'updatedDayStops' => $updated_day,
            'travel' => $updated_travel,
        ]);
    }
}
