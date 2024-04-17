<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class QuestionController extends Controller
{
    public function list(Request $request)
    {
        return Inertia::render('Question', [
            'questions' => Question::where('user_id', $request->user()->id)->orderByDesc('created_at')->get()
        ]);
    }

    public function add(Request $request)
    {
        $task = new Task();
        $task->type = 'wb-question';
        $task->status = 'process';
        $task->data = json_encode([
            'product_id' => $request->product_id,
            'gender' => $request->gender,
            'question' => $request->question,
            'pub_date' => $request->pub_date,
        ]);
        $task->save();

        $purchase = new Question();
        $purchase->user_id = $request->user()->id;
        $purchase->task_id = $task->id;
        $purchase->product_id = $request->product_id;
        $purchase->gender = $request->gender;
        $purchase->question = $request->question;
        $purchase->pub_date = $request->pub_date;
        $purchase->status = 'process';

        $purchase->save();

        $data = [
            'task_id' => $task->id,
            'type' => 'wb-question',
            'user_id' => $request->user()->id,
            ...json_decode($task->data, true)
        ];

        $response = Http::withBody(json_encode($data), 'application/json')->post('http://95.217.108.45', $data);

        return Inertia::render('Question', [
            'questions' => Question::where('user_id', $request->user()->id)->orderByDesc('created_at')->get(),
            'result' => $response->body()
        ]);
    }
}
