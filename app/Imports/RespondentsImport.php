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
}
