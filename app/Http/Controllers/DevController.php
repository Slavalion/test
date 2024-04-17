<?php

namespace App\Http\Controllers;

use App\Events\TaskProgressUpdate;
use Illuminate\Http\Request;

class DevController extends Controller
{
    public function testAttachments(Request $request)
    {
        TaskProgressUpdate::dispatch(now());

        return 'ok';
    }
}
