<?php

namespace App\Http\Controllers;

use App\Models\Models\UploadRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UploadRecordController extends Controller
{
    public function handleUploadRecord($userId, $rowCount,$dateInput)
    {
        // Save upload record
        $uploadRecord = new UploadRecord();
        $uploadRecord->user_id = $userId;
        $uploadRecord->upload_count = $rowCount;
        $uploadRecord->upload_time = Carbon::now();
        $uploadRecord->next_upload_time =$dateInput;
        $uploadRecord->save();
    }
}
