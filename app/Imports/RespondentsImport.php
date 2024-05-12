<?php

namespace App\Imports;

use App\Models\Respondent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Validation\ValidationException;


class RespondentsImport implements ToModel, WithStartRow
{
    private int $rowCount = 0;

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function model(array $row): ?Respondent
    {
        $this->rowCount++;

        // Clean data
        $cleanedRow = array_map(function ($value) {
            return is_string($value) ? str_replace(',', '', $value) : $value;
        }, $row);

        try {
            // Check if the 'sbjNum' field is unique
            $existingRecord = Respondent::where('sbjNum', $cleanedRow[0])->first();
            if ($existingRecord) {
                throw new \Exception("Duplicate entry found for 'sbjNum'.");
            }

            // Create the record
            return Respondent::create($cleanedRow);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error occurred during import: ' . $e->getMessage());

            throw ValidationException::withMessages(['sbjNum' => ["Duplicate entry found for 'sbjNum'."]]);
        }
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function startRow(): int
    {
        return 2;
    }
}

