<?php

namespace App\Http\Controllers;

use App\Imports\RespondentsImport;
use App\Models\Respondent;
use Illuminate\Http\Request;
use \Maatwebsite\Excel\Facades\Excel;
use Throwable;

class RespondentController extends Controller
{
    public function import(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $import = new RespondentsImport();
                Excel::import($import, $file);
                $importedRowsCount = $import->getRowCount();
                return redirect()->route('admin')->with('success', $importedRowsCount . ' respondent(s) imported successfully.');
            } else {
                return redirect()->route('admin')->with('error', 'No file uploaded.');
            }
        } catch (Throwable $e) {
            return  redirect()->route('admin')->with('error', 'An error occurred while importing the file.');
        }
    }

}
