<?php

namespace App\Imports;

use App\Models\Respondent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class RespondentsImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private int $rowCount = 0;

    public function model(array $row): Respondent
    {
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
        ]);
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    private function cleanData($data): array|string|null
    {
        return str_replace( ',', '', $data);
    }
    public function startRow(): int
    {
        return 2;
    }

    function correctDateTime($dateTime)
    {
        # integer digits for Julian date
        $julDate = floor($dateTime);
        # The fractional digits for Julian Time
        $julTime = $dateTime - $julDate;
        # Converts to Timestamp
        $timeStamp = ($julDate > 0) ? ($julDate - 25569) * 86400 + $julTime * 86400 : $julTime * 86400;

        # php date function to convert local time
        return [
            'Date-Time' => date('Y-m-d H:i:s', $timeStamp),
            'Date' => date('Y-m-d', $timeStamp),
            'Time' => date('H:i:s', $timeStamp)
        ];
    }

}
