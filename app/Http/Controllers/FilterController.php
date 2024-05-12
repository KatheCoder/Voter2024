<?php

namespace App\Http\Controllers;

use App\Models\Respondent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public const AGE_GROUP = [
        '18 to 24',
        '25 to 29',
        '30 to 35',
        '36 to 49',
        '50 to 64 years',
        '65 and above'
    ];

    public function fetchFilters(): JsonResponse
    {

        $genders = Respondent::distinct()->pluck('gender')->filter()->sortBy('gender');

        /*
         * until a proper solution is found to grab data from db and put into proper asc order
         */
        $ageGroups = self::AGE_GROUP;
        $races = Respondent::distinct()->pluck('race')->filter()->sortBy('race');
        $municipality = Respondent::distinct()->pluck('municipality')->filter()->sortBy('municipality');

        return response()->json([
            'genders' => $genders,
            'age_groups' => $ageGroups,
            'races' => $races,
            'municipality' => $municipality,
        ]);
    }
}
