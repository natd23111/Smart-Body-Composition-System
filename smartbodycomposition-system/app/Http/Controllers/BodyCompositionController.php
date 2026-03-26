<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BodyComposition;

class BodyCompositionController extends Controller
{
    // Display all records for logged-in user
    public function index()
    {
        $data = BodyComposition::where('user_id', auth()->id())->latest()->get();
        return response()->json($data);
    }

    // Store new record
    public function store(Request $request)
    {
        $validated = $request->validate([
            'measurement_date' => 'required|date',
            'measurement_time' => 'nullable',
            'weight_kg' => 'nullable|numeric',
            'body_fat_percent' => 'nullable|numeric',
            'muscle_mass' => 'nullable|numeric'
        ]);

        $validated['user_id'] = auth()->id();

        $record = BodyComposition::create($validated);

        return response()->json([
            'message' => 'Record saved successfully',
            'data' => $record
        ]);
    }

    // Show single record
    public function show($id)
    {
        $record = BodyComposition::findOrFail($id);

        // Security check
        if ($record->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($record);
    }

    // Update record
    public function update(Request $request, $id)
    {
        $record = BodyComposition::findOrFail($id);

        if ($record->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $record->update($request->all());

        return response()->json([
            'message' => 'Updated successfully',
            'data' => $record
        ]);
    }

    // Delete record
    public function destroy($id)
    {
        $record = BodyComposition::findOrFail($id);

        if ($record->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $record->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
