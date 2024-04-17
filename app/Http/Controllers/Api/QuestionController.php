<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Task;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function update(Request $request)
    {
        $question = Question::where('task_id', $request->task_id)->first();

        if (!$question) {
            return response()->json(['bad'], 400);
        }

        $question->status = 'success';
        $question->save();

        if ($question->count == $question->progress) {
            $task = Task::find($request->task_id);
            $task->status = 'success';
            $task->save();
        }

        // TaskProgressUpdate::dispatch($questoin);

        return response()->json(['ok']);
    }
}
