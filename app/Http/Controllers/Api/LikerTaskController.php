<?php

namespace App\Http\Controllers\Api;

use App\Events\TaskProgressUpdate;
use App\Http\Controllers\Controller;
use App\Models\LikerTask;
use App\Models\Task;
use Illuminate\Http\Request;

class LikerTaskController extends Controller
{
    public function update(Request $request)
    {
        $likerTask = LikerTask::where('task_id', $request->task_id)->first();

        if (!$likerTask) {
            return response()->json(['bad'], 400);
        }

        $likerTask->progress += $request->progress;
        $likerTask->save();

        if ($likerTask->count == $likerTask->progress) {
            $task = Task::find($request->task_id);
            $task->status = 'success';
            $task->save();
        }

        TaskProgressUpdate::dispatch($likerTask);

        return response()->json(['ok']);
    }
}
