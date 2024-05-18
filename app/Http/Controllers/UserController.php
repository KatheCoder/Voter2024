<?php

namespace App\Http\Controllers;

use App\Models\Models\UploadRecord;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): Factory|View|Application
    {
        $timeStampRecord = UploadRecord::latest('next_upload_time')->first();

        $time_stamp = $timeStampRecord?->updated_at->format('Y-m-d') ?? null;
        $time_stamp_next = $timeStampRecord?->next_upload_time ?? null;

        return view('app', [
            'time_stamp' => $time_stamp,
            'time_stamp_next' => $time_stamp_next
        ]);
    }

}
