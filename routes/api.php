<?php

use App\Http\Controllers\Api\ActionCartController;
use App\Http\Controllers\Api\ActionSearchController;
use App\Http\Controllers\Api\LikerTaskController;
use App\Http\Controllers\Api\PaymentConroller;
use App\Http\Controllers\Api\PickUpController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ReviewComplaintController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ReviewReactionController;
use App\Http\Controllers\Api\TelegramTransactionController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\WalletTransactionController;
use App\Http\Controllers\DevController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('restrict.ip')->group(function () {
    Route::post('products/add', ProductController::class);
    Route::post('liker-tasks/update', [LikerTaskController::class, 'update']);
    Route::post('purchase-tasks/update', [PurchaseController::class, 'update']);
    Route::post('purchase-tasks/delivery-update', [PurchaseController::class, 'updateDelivery']);
    Route::post('question-tasks/update', [QuestionController::class, 'update']);
    Route::post('wallet-tasks/update', [WalletController::class, 'update']);
    Route::post('wallet-tasks/update-balance', [WalletController::class, 'updateBalance']);

    Route::post('wallet/transactions/create', [WalletTransactionController::class, 'create']);

    Route::post('telegram/transactions/create', [TelegramTransactionController::class, 'create']);

    Route::post('review/update', [ReviewController::class, 'update']);
    Route::post('review/cancel', [ReviewController::class, 'cancel']);

    Route::post('review-reactions/update', [ReviewReactionController::class, 'update']);

    Route::post('review-complaints/update', [ReviewComplaintController::class, 'update']);

    Route::post('actions/cart/update', [ActionCartController::class, 'update']);
    Route::post('actions/search/update', [ActionSearchController::class, 'update']);
});

// Payment
Route::post('webhook/tinkoff', [PaymentConroller::class, 'tinkoff']);

// Public API
Route::post('pick-ups/import', [PickUpController::class, 'import']);

// Dev
Route::post('devtest', [DevController::class, 'testAttachments']);
