<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Task;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $product = new Product();
        $product->title = $request->title;
        $product->image = $request->image;
        $product->remote_id = $request->remote_id;
        $product->save();

        $task = Task::find($request->task_id);
        $task->status = 'success';
        $task->save();

        return 'ok';
    }
}
