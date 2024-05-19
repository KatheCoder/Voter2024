<?php

namespace App\Http\Controllers;

use App\Models\Respondent;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{

    const  PARTY_COLORS = [
        'ANC' => '#ffcb03',
        'DA' => '#00bfff',
        'EFF' => '#FF0000',
        'AJ' => '#4cbb17',
        'MK' => '#000000',
        'IFP' => '#4cbb17',
        'ACTION SA' => '#32CD32',
        'ASC' => '#a94c4c',
    ];
    const  FULL_PARTY_COLORS = [
        'AFRICAN NATIONAL CONGRESS' => '#ffcb03',
        'DEMOCRATIC ALLIANCE' => '#00bfff',
        'ECONOMIC FREEDOM FIGHTERS' => '#FF0000',
        'AJ' => '#4cbb17',
        'UMKHONTO WESIZWE' => '#000000',
        'INKATHA FREEDOM PARTY' => '#4cbb17',
        'ACTION SA' => '#32CD32',
        'ASC' => '#a94c4c',
    ];


    public function getPieFilteredData(Request $request): JsonResponse
    {

        try {
            $parameters = $request->query->all();
            $overallTotalRespondents = Respondent::count();

            $query = $this->applyFilters(Respondent::query(), $parameters);
            $filteredTotalRespondents = $query->count();
            if ($filteredTotalRespondents === 0) {
                return response()->json(['message' => 'No results.']);
            }
            $totalRespondents = $query->count();

            $responses = ['likely', 'decided', 'voting_status'];

            $colors = [
                'I will vote' => '#2bff00',
                'I have voted before in national elections (not municipal)' => '#2bff00',
                'I will not vote' => '#ff0000',
                'I am a first-time voter in national elections (not municipal)' => '#ff0000',
                'I’m not sure' => '#FF7200FF',
                'Yes' => '#2bff00',
                'No will decide closer to the time' => '#ff0000',
                '' => '#FF7200FF',
            ];
            $pieChartData = [];

            foreach ($responses as $response) {
                $responseCounts = $query->pluck($response)->countBy();
                $formattedData = $this->formatPieChartData($responseCounts);

                foreach ($formattedData as &$data) {
                    $data['color'] = $colors[$data['response']] ?? null;
                }
                $pieChartData[$response] = $formattedData;

            }

            return response()->json([
                'overall' => $overallTotalRespondents,
                'total_respondents_per' => $totalRespondents,
                'data' => $pieChartData,
            ]);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error.', $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.', $e->getMessage()], 500);
        }
    }

    public function getOverallCount(): JsonResponse
    {
        return response()->json([
            'overall' => Respondent::count(),
        ]);

    }

    public function getFilteredData(Request $request): JsonResponse
    {
        try {
            $parameters = $request->query->all();
            $overallTotalRespondents = Respondent::count();

            $filters = [
                'gender' => $parameters['gender'] ?? null,
                'age_groups' => $parameters['age_groups'] ?? null,
                'race' => $parameters['race'] ?? null,
                'municipality' => $parameters['municipality'] ?? null,
            ];

            $query = Respondent::query();
            $provQuery = Respondent::query();
            $query = $this->applyFilters($query, $filters);
            $provQuery = $this->applyFilters($provQuery, $filters);
            $filteredTotalRespondents = $query->count();
            if ($filteredTotalRespondents === 0) {
                return response()->json(['message' => 'No results.']);
            }
            $nationalData = $this->getDataForLevel($query, 'national');

            $provincialData = $this->getDataForLevel($provQuery, 'provincial');

            $nationalPercentages = $this->calculatePercentages($nationalData);
            $provincialPercentages = $this->calculatePercentages($provincialData);


            $nationalLegend = $nationalData->map(function ($item, $index) use ($nationalPercentages, $nationalData) {
                $color = self::PARTY_COLORS[$item->abbreviated_national] ?? '#A9A9A9'; // Default to black if color not found

                return [
                    'abbr_name' => $item->abbreviated_national,
                    'color' => $color,
                    'name' => $item->national,
                    'value' => $nationalPercentages[$index],
                    'weight' => number_format($nationalData[$index]['total_weight'], 2, '.', ','),
                ];
            })->toArray();

            $provincialLegend = $provincialData->map(function ($item, $index) use ($provincialPercentages, $provincialData) {
                $color = self::PARTY_COLORS[$item->abbreviated_provincial] ?? '#A9A9A9'; // Default to black if color not found

                return [
                    'abbr_name' => $item->abbreviated_provincial,
                    'color' => $color,
                    'name' => $item->provincial,
                    'value' => $provincialPercentages[$index],
                    'weight' => number_format($provincialData[$index]['total_weight'], 2, '.', ','),

                ];
            })->toArray();
            usort($provincialLegend, function ($a, $b) {
                return $b['value'] <=> $a['value'];
            });
            usort($nationalLegend, function ($a, $b) {
                return $b['value'] <=> $a['value'];
            });

            return response()->json([
                'total' => $overallTotalRespondents,
                'national' => $nationalLegend,
                'provincial' => $provincialLegend,
            ]);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    public function lineChartData(Request $request): JsonResponse
    {
        try {
            $parameters = $request->all();

            $filters = [
                'gender' => $parameters['gender'] ?? null,
                'age_groups' => $parameters['age_groups'] ?? null,
                'race' => $parameters['race'] ?? null,
                'municipality' => $parameters['municipality'] ?? null,
            ];
            $type = $request->type;
            if (!in_array($type, ['national', 'provincial'])) {
                throw new \InvalidArgumentException('Invalid type specified.');
            }

            $inputData = $this->applyFilters(Respondent::query(), $filters)->get();

            $processedData = $this->processData($inputData, $type);

            $topParties = $this->getTopParties();

            $otherPartyData = $this->calculateOtherPartyData($inputData, $processedData, $topParties, $type);

            $chartData = $this->formatChartData($processedData, $topParties, $otherPartyData, $type, $inputData);
            return response()->json([
                'dates' => $processedData->keys(),
                'results' => $chartData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function meanDataTable(Request $request): JsonResponse
    {
        $parameters = $request->all();

        $filters = [
            'gender' => $parameters['gender'] ?? null,
            'age_groups' => $parameters['age_groups'] ?? null,
            'race' => $parameters['race'] ?? null,
            'municipality' => $parameters['municipality'] ?? null,
        ];

        // Define the categories to be averaged
        $categories = [
            'lost_faith',
            'empty_promises',
            'changes',
            'unaddressed_by_government',
            'always_wins',
            'good_enough',
            'queues',
            'too_busy',
        ];

        $meanRatings = $this->calculateMeanRatings($filters, $categories);

        return response()->json(['meanRatings' => $meanRatings]);
    }

    public function meanData(Request $request)
    {
        $parameters = $request->all();

        $filters = [
            'gender' => $parameters['gender'] ?? null,
            'age_groups' => $parameters['age_groups'] ?? null,
            'race' => $parameters['race'] ?? null,
            'municipality' => $parameters['municipality'] ?? null,
        ];

        // Define the categories to be averaged
        $categories = [
            'economy',
            'infrastructure',
            'transportation',
            'safety',
            'combating_corruption',
            'service_delivery',
            'historical_success',
            'brand_values',
            'personal_liking',
        ];

        $meanRatings = $this->calculateMeanRatings($filters, $categories);

        return response()->json(['meanRatings' => $meanRatings]);
    }

    private function calculateMeanRatings(array $filters, array $categories): array
    {
        $topParties = $this->getTopParties();

        $inputData = $this->applyFilters(Respondent::select('national')->distinct(), $filters)->get();

        $meanRatings = [];
        $othersRatings = [];

        foreach ($categories as $category) {
            foreach ($inputData as $respondent) {
                $party = $respondent->national;
                $abbreviatedParty = $respondent->abbreviated_national;

                if (!empty($party)) {
                    $meanQuery = Respondent::where('national', $party);
                    $meanQuery = $this->applyFilters($meanQuery, $filters);
                    $meanRating = $meanQuery->selectRaw("AVG($category) as mean_rating")->pluck('mean_rating')->first();

                    if ($meanRating !== null) {
                        if (in_array($party, $topParties)) {
                            $meanRatings[$abbreviatedParty][$category] = round($meanRating, 2);
                        } else {
                            $othersRatings[$category][] = $meanRating;
                        }
                    }
                }
            }
        }

        if (!empty($othersRatings)) {
            $combinedOthersRatings = [];
            foreach ($categories as $category) {
                $combinedOthersRatings[$category] = isset($othersRatings[$category])
                    ? round(array_sum($othersRatings[$category]) / count($othersRatings[$category]), 2)
                    : null;
            }
            $meanRatings['Others'] = $combinedOthersRatings;
        }

        return $meanRatings;
    }

    private function getTopParties(): array
    {
        return [
            'AFRICAN NATIONAL CONGRESS',
            'DEMOCRATIC ALLIANCE',
            'ECONOMIC FREEDOM FIGHTERS',
            'INKATHA FREEDOM PARTY',
            'UMKHONTO WESIZWE'
        ];
    }

    private function applyFilters($query, array $filters)
    {
        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                $query->where($column, $value);
            }
        }
        return $query;
    }

    private function processData($inputData, string $type)
    {
        return $inputData->groupBy('date')
            ->map(function ($group) use ($type) {
                $totalCountPerDate = $group->count(); // Counting IDs instead of summing weights
                return $group->groupBy($type)->map(function ($groupByType) use ($totalCountPerDate) {
                    $totalCountForParty = $groupByType->count(); // Counting IDs instead of summing weights
                    return round($totalCountForParty / $totalCountPerDate * 100, 2); // Calculate percentage to 2 decimal places
                });
            });
    }

    private function calculateOtherPartyData($inputData, $processedData, $topParties, string $type): array
    {
        $otherPartyData = [];
        foreach ($processedData as $date => $groupedByDate) {
            $otherPartiesCount = $inputData
                ->where('date', $date)
                ->reject(fn($respondent) => in_array($respondent->$type, $topParties))
                ->count('id'); // Count the IDs

            // Calculate the percentage for the "Other" party for the current date
            $totalCountPerDate = $inputData->where('date', $date)->count('id');
            $otherPartyData[$date] = $totalCountPerDate > 0 ? round($otherPartiesCount / $totalCountPerDate * 100, 2) : 0;
        }
        return $otherPartyData;
    }

    private function formatChartData($processedData, $topParties, $otherPartyData, string $type, $inputData): array
    {
        $chartData = [];

        // Calculate sample counts for each party
        foreach ($topParties as $party) {
            $partyData = [];
            $sampleCounts = [];

            foreach ($processedData->keys() as $date) {
                $partyData[] = $processedData[$date]->get($party, 0);
                $sampleCounts[] = $inputData
                    ->where('date', $date)
                    ->where($type, $party)
                    ->count();
            }

            // Add party data to chart data
            $chartData[] = [
                'name' => $party,
                'data' => $partyData,
                'sampleCounts' => $sampleCounts,
                'color' => self::FULL_PARTY_COLORS[$party] ?? '#000000',  // Default color if not specified
            ];
        }

        // Calculate sample counts for the "Other" party
        $otherSampleCounts = [];

        foreach ($processedData->keys() as $date) {
            $otherSampleCounts[] = $inputData
                ->where('date', $date)
                ->reject(function ($respondent) use ($topParties, $type) {
                    return in_array($respondent->$type, $topParties);
                })
                ->count();
        }

        // Add "Other" party data to chart data
        $chartData[] = [
            'name' => 'Other',
            'data' => array_values($otherPartyData), // Convert associative array to indexed array
            'sampleCounts' => $otherSampleCounts,
            'color' => '#a94c4c',  // Color for the combined "other" party
        ];

        return $chartData;
    }

    private function convertToObjects($array): array
    {
        $objects = [];
        foreach ($array as $party => $weight) {
            $objects[] = ['party' => $party, 'weight' => $weight];
        }
        return $objects;
    }

    private function getDataForLevel($query, $level)
    {
        return $query
            ->groupBy($level)
            ->selectRaw("$level, SUM(weight) as total_weight")
            ->get();
    }

    private function calculatePercentages($data)
    {
        $totalSum = $data->sum('total_weight');
        return $data->map(function ($item) use ($totalSum) {
            return round(($item->total_weight / $totalSum) * 100, 1);
        });
    }

    private function formatPieChartData($responseCounts): array
    {
        $pieChartData = [];

        foreach ($responseCounts as $response => $count) {
            $pieChartData[] = [
                'response' => $response,
                'count' => $count,
            ];
        }

        return $pieChartData;
    }

}
