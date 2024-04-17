<?php

namespace App\Http\Controllers;

use App\Models\LikerTask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class LikerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Liker', [
            'phpVersion' => PHP_VERSION,
            'tasks' => LikerTask::where('user_id', $request->user()->id)->orderByDesc('created_at')->get()
        ]);
    }

    public function do(Request $request)
    {
        $task = new Task();
        $task->type = 'wb-liker';
        $task->status = 'process';
        $task->data = json_encode([
            'link' => $request->link,
            'count' => $request->count,
            'period' => $request->period,
        ]);
        $task->save();

        $likerTask = new LikerTask();
        $likerTask->user_id = $request->user()->id;
        $likerTask->task_id = $task->id;
        $likerTask->link = $request->link;
        $likerTask->count = (int) $request->count;
        $likerTask->save();

        $data = [
            'task_id' => $task->id,
            'type' => 'wb-liker',
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
            'keywords' => $request->keywords,
            ...json_decode($task->data, true)
        ];

        $response = Http::withBody(json_encode($data), 'application/json')->post('http://95.217.108.45', $data);

        return Inertia::render('Liker', [
            'phpVersion' => PHP_VERSION,
            'result' => $response->body(),
            'tasks' => LikerTask::where('user_id', $request->user()->id)->orderByDesc('created_at')->get()
        ]);
    }
}
