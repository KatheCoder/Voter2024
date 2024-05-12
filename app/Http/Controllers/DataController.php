<?php

namespace App\Http\Controllers;

use App\Models\Respondent;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DataController extends Controller
{
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


            $nationalLegend = $nationalData->map(function ($item, $index) use ($nationalPercentages,$nationalData) {
                return [
                    'key' => $item->abbreviated_national,
                    'value' => $item->national,
                    'data' => $nationalPercentages[$index] ,
                    'sum' => $nationalData[$index]
                ];
            })->toArray();

            $provincialLegend = $provincialData->map(function ($item, $index) use ($provincialPercentages,$provincialData) {
                return [
                    'key' => $item->abbreviated_provincial,
                    'value' => $item->provincial,
                    'data' => $provincialPercentages[$index] ,
                    'sum' => $provincialData[$index]
                ];
            })->toArray();

            return response()->json([
                'total' => $overallTotalRespondents,
                'nationalLegend' => $nationalLegend,
                'provincialLegend' => $provincialLegend,
                'national' => [
                    'labels' => $nationalData->pluck('abbreviated_national')->toArray(),
                    'data' => $nationalPercentages->toArray(),
                ],
                'provincial' => [
                    'labels' => $provincialData->pluck('abbreviated_provincial')->toArray(),
                    'data' => $provincialPercentages->toArray(),
                ],
            ]);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    private function applyFilters($query, $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value !== null) {
                $query->where($key, $value);
            }
        }
        return $query;
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
