<?php

namespace App\Console\Commands;

use App\Actions\BotRequestAction;
use App\Actions\PurchaseGroupRemoveAction;
use App\Actions\PurchaseRemoveAction;
use App\Actions\Resend\ResendAddToCartAction;
use App\Actions\Resend\ResendPurchaseAction;
use App\Actions\Resend\ResendSearchAction;
use App\Actions\ReviewComplaintGroupRemoveAction;
use App\Actions\ReviewReactionGroupRemoveAction;
use App\Actions\ReviewRemoveAction;
use App\Actions\UserTopUpBalance;
use App\Enums\ActionItemStatus;
use App\Enums\UserPriceType;
use App\Models\ActionCart;
use App\Models\Purchase;
use App\Models\PurchaseGroup;
use App\Models\ReviewReactionGroup;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DevCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devtest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //  Че-то считаем (не помню уже, мб удалить за ненадобностью)

        // $purchases = Purchase::whereHas('user', function (Builder $query) {
        //     $query->whereJsonContains('preferences->use_livecargo', true);
        // })->where('delivery_status', Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP)
        //     ->where('delivery_qrcode', '!=', '')
        //     ->get();

        // $this->info($purchases->count());
        // foreach ($purchases as $purchase) {
        //     if ($purchase->livecargoDeliveries->count() > 1) {
        //         dump($purchase->livecargoDelivery->livecargo_order_id);
        //         $this->info('asdf');
        //     }
        // }

        // // Снести группу выкупов
        // $purchaseGroupRemoveAction = new PurchaseGroupRemoveAction();
        // $purchaseGroupRemoveAction->handle(914);
        // $purchaseGroupRemoveAction->handle(915);
        // $purchaseGroupRemoveAction->handle(916);
        // $purchaseGroupRemoveAction->handle(917);
        // $purchaseGroupRemoveAction->handle(918);
        // $purchaseGroupRemoveAction->handle(919);
        // $purchaseGroupRemoveAction->handle(920);
        // $purchaseGroupRemoveAction->handle(921);
        // $purchaseGroupRemoveAction->handle(922);
        // $purchaseGroupRemoveAction->handle(923);
        // $purchaseGroupRemoveAction->handle(924);
        // $purchaseGroupRemoveAction->handle(925);
        // $purchaseGroupRemoveAction->handle(926);
        // $purchaseGroupRemoveAction->handle(927);
        // $purchaseGroupRemoveAction->handle(928);
        // $purchaseGroupRemoveAction->handle(929);
        // $purchaseGroupRemoveAction->handle(930);
        // $purchaseGroupRemoveAction->handle(931);
        // $purchaseGroupRemoveAction->handle(932);

        // $purchases = Purchase::whereIn('task_id', [42631,42632,42633,42634,42635,42636,42637,42638,42639,42640,42641,42642,42643,42644,42645,42646,42647,42648,42649,42650,42651,42652,42653,42654,42655,42656,42657,42658,42659,42660,42661,42662,42663,42664,42665,42666,42667,42668,42669,42670])
        //     ->get();

        // $productsData = [];
        // foreach ($purchases as $purchase) {
        //     $productsData[] = [
        //         'task_id' => $purchase->task_id,
        //         'purchase_id' => $purchase->id,
        //         'type' => 'wb-purchase',
        //         'user_id' => $purchase->user->id,
        //         'product_id' => $purchase->product_id,
        //         'quantity' => $purchase->quantity,
        //         'gender' => $purchase->gender,
        //         'size' => $purchase->size,
        //         'keywords' => $purchase->keywords,
        //         'address' => $purchase->address,
        //         'purchase_at' => $purchase->purchase_ts,
        //         'purchase_type' => $purchase->type->value,
        //         'payment_type' => 'telegram',
        //         'payment_data' => [
        //             'chat_id' => $purchase->user->preferences['paymentChatId']
        //         ],
        //     ];

        // }
        // // dd($productsData);
        // $botRequestAction = new BotRequestAction();
        // $botRequestAction->execute($productsData);

        // Удалить отзыв с возвратом денег
        // $tasks = [
        //     0,
        // ];

        // foreach ($tasks as $task) {
        //     $purchase = Purchase::where('task_id', $task)->first();
        //     (new ReviewRemoveAction())->handle($purchase->review);
        // }

        // Снести группу реакций
        // $reviewReactionGroupRemoveAction = new ReviewReactionGroupRemoveAction();
        // $reviewReactionGroupRemoveAction->handle(21);

        // Снести группу жалоб
        // $reviewComplaintGroupRemoveAction = new ReviewComplaintGroupRemoveAction();
        // $reviewComplaintGroupRemoveAction->handle(46);

        // $walletTransactions = WalletTransaction::all();

        // foreach ($walletTransactions as $walletTransaction) {
        //     $walletTransaction->user_id = $walletTransaction->purchase->user_id;
        //     $walletTransaction->wallet_name = $walletTransaction->wallet->name;
        //     $walletTransaction->save();
        // }

        // Transaction::where('target', 'purchase')->update([
        //     'target' => PurchaseGroup::class,
        // ]);

        // $reviewReactionsGroups = ReviewReactionGroup::where('user_id', 13)->whereDate('created_at', Carbon::createFromFormat('d/m/Y', '30/11/2023'))->where('status', 1)->orderByDesc('created_at')->get();

        // foreach ($reviewReactionsGroups as $reviewReactionsGroup) {
        //     foreach ($reviewReactionsGroup->reviewReactions as $reviewReaction) {
        //         $reviewReaction->delete();
        //     }

        //     $reviewReactionsGroup->delete();
        // }

        // REMOVE ALL PURCHASES

        // $purchases = Purchase::where('status', Purchase::STATUS_PROCESSING)->where('created_at', '<', now()->toDateString())->get();

        // foreach ($purchases as $purchase) {
        //     $userTopUpBalance = new UserTopUpBalance();
        //     $userService = new UserService($purchase->user);
        //     $purchasePrice = $userService->getPrice(UserPriceType::PURCHASE);

        //     Transaction::create([
        //         'user_id' => $purchase->user?->id,
        //         'amount' => $purchasePrice,
        //         'type' => Transaction::TYPE_TOP_UP,
        //         'target' => Transaction::TARGET_RETURN_PURCHASE,
        //     ]);

        //     $userTopUpBalance->handle($purchase->user, $purchasePrice);

        //     $purchase->delete();
        // }

        // Resend some review complaints

        // $actionCarts = ActionCart::where('status', ActionItemStatus::PROCESS)
        //     ->whereNotIn('task_id', [50590,
        //         78438,
        //         80771,
        //         120501,
        //         120502, ])
        //     ->chunk(50, function (Collection $actionCarts) {
        //         $this->info($actionCarts->count());
        //         $requestData = [];

        //         foreach ($actionCarts as $actionCart) {
        //             $requestData[] = [
        //                 'id' => $actionCart->id,
        //                 'task_id' => $actionCart->task_id,
        //                 'type' => 'wb-action-add-to-cart',
        //                 'user_id' => $actionCart->user_id,
        //                 'product_id' => $actionCart->product_id,
        //                 'keywords' => $actionCart->keywords,
        //                 'view_time' => $actionCart->view_time,
        //                 'start_at' => $actionCart->start_at->toDateTimeString(),
        //             ];
        //         }

        //         $botRequestAction = new BotRequestAction();
        //         $botRequestAction->execute($requestData);
        //     });
        // dd(123);

        // Resend purchases
        // $taskIDs = [
        //     125571,
        //     125596,
        // ];
        // (new ResendPurchaseAction)->handle($taskIDs);

        // Resend search actions
        // $taskIDs = [
        //     125597,
        //     125695,
        // ];
        // (new ResendSearchAction)->handle($taskIDs);

        // Resend search actions
        // $taskIDs = [
        //     125643,
        //     125673,
        // ];
        // (new ResendAddToCartAction)->handle($taskIDs);

        // // Remove purchases
        // $purchases = Purchase::whereIn('task_id', [126997,126999,127002,127011,127020,127023,127024,127026,127032,127040,127054,131146,131154,131160,131163,131164,131165,131167,131174,131175,131489,131497])->get();
        // foreach ($purchases as $purchase) {
        //     (new PurchaseRemoveAction())->handle($purchase);
        //     // echo $purchase->task_id . PHP_EOL;
        // }
    }
}
