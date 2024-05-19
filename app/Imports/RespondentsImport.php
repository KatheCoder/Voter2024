<?php

namespace App\Imports;

use App\Models\Respondent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Log;

class RespondentsImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private int $rowCount = 0;

    public function model(array $row): ?Respondent
    {
        try {
            $date = $this->correctDateTime($row[11]);
            $this->rowCount++;

            return new Respondent([
                'sbjNum' => $this->cleanData($row[0]),
                'municipality' => $this->cleanData($row[1]),
                'race' => $this->cleanData($row[2]),
                'age_groups' => $this->cleanData($row[3]),
                'gender' => $this->cleanData($row[4]),
                'likely' => $this->cleanData($row[5]),
                'decided' => $this->cleanData($row[6]),
                'voting_status' => $this->cleanData($row[7]),
                'national' => $this->cleanData($row[8]),
                'provincial' => $this->cleanData($row[9]),
                'weight' => $this->cleanData($row[10]),
                'date' => $date['Date'],
                'lost_faith' => $row[12] ?? null,
                'empty_promises' => $row[13]?? null,
                'changes' => $row[14]?? null,
                'unaddressed_by_government' => $row[15]?? null,
                'always_wins' => $row[16]?? null,
                'good_enough' => $row[17]?? null,
                'queues' => $row[18]?? null,
                'too_busy' => $row[19]?? null,
                'economy' => $row[20]?? null,
                'infrastructure' => $row[21]?? null,
                'transportation' => $row[22]?? null,
                'safety' => $row[23]?? null,
                'combating_corruption' => $row[24]?? null,
                'service_delivery' => $row[25]?? null,
                'historical_success' => $row[26]?? null,
                'brand_values' => $row[27]?? null,
                'personal_liking' => $row[28]?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Error importing row ' . ($this->rowCount + 1) . ': ' . $e->getMessage());
            return null;
        }
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    private function cleanData($data): array|string|null
    {
        return str_replace(',', '', $data);
    }

    public function startRow(): int
    {
        return 2;
    }

    function correctDateTime($dateTime)
    {
        $julDate = floor($dateTime);
        $julTime = $dateTime - $julDate;
        $timeStamp = ($julDate > 0) ? ($julDate - 25569) * 86400 + $julTime * 86400 : $julTime * 86400;

        return [
            'Date-Time' => date('Y-m-d H:i:s', $timeStamp),
            'Date' => date('Y-m-d', $timeStamp),
            'Time' => date('H:i:s', $timeStamp)
        ];
    }
}
