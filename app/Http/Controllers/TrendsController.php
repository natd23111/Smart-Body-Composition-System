<?php

namespace App\Http\Controllers;

use App\Services\RecommendationEngine;
use Illuminate\Http\Request;

class TrendsController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $periodDays = (int) $request->query('period', 14);
        $periodDays = in_array($periodDays, [7, 14, 30, 90]) ? $periodDays : 14;

        $engine = new RecommendationEngine();
        $result = $engine->buildTrendAnalysis($request->user(), $periodDays);

        return response()->json($result);
    }
}
