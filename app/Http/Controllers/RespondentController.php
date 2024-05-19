<?php
namespace App\Http\Controllers;

use App\Imports\RespondentsImport;
use App\Models\Respondent;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Illuminate\Support\Facades\Log;

class RespondentController extends Controller
{
    public function import(Request $request, UploadRecordController $uploadRecordController)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,csv',
                'next_update' => 'required|date',
            ]);

            $file = $request->file('file');
            $nextUpdate = $request->input('next_update');

            Respondent::truncate();

            $import = new RespondentsImport();
            Excel::import($import, $file);
            $importedRowsCount = $import->getRowCount();

            $uploadRecordController->handleUploadRecord(auth()->id(), $importedRowsCount, $nextUpdate);

            return redirect()->route('admin')->with('success', $importedRowsCount . ' respondent(s) imported successfully.');
        } catch (Throwable $e) {
            Log::error('An error occurred while importing the file: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return redirect()->route('admin')->with('error', 'An error occurred while importing the file. Please check the logs for more details.');
        }
    }
}

