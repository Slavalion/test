<?php

namespace App\Http\Controllers\Api;

use App\Actions\SendUserNotificationAction;
use App\Enums\WalletStatus;
use App\Events\WalletConnect;
use App\Events\WalletUpdateBalance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WalletUpdateBalanceRequest;
use App\Http\Requests\Api\WalletUpdateRequest;
use App\Models\Task;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function update(WalletUpdateRequest $request)
    {
        $task = Task::find($request->task_id);

        $wallet = Wallet::where('task_id', $task->id)->first();

        if (! $wallet) {
            return response()->json(['error' => 'Wallet not found'], 422);
        }

        if ($request->status == 'request_confirmation_code') {
            $task->status = 'request_code';
        }

        if ($request->status == 'done') {
            $task->status = 'done';

            if ($request->balance) {
                $balance = str_replace([' ', '₽'], '', $request->balance);
                $balance = str_replace(',', '.', $balance);
            } else {
                $balance = 0;
            }

            $wallet->update([
                'status' => WalletStatus::READY,
                'balance' => (float) $balance,
            ]);
        }

        if ($request->status == 'ready') {
            $wallet->update([
                'status' => WalletStatus::READY,
            ]);
        }

        if ($request->status == 'limit_reached') {
            $notifyWallet = $wallet->user->preferences['notifications']['wallet.all'] ?? false;

            if ($wallet->status != WalletStatus::LIMIT_REACHED && $notifyWallet) {
                (new SendUserNotificationAction())->handle(
                    $wallet->user,
                    'По кошельку '.$wallet->name.' достигнут лимит'
                );
            }

            $wallet->update([
                'status' => WalletStatus::LIMIT_REACHED,
            ]);
        }

        if ($request->status == 'loged_out') {
            $notifyWallet = $wallet->user->preferences['notifications']['wallet.all'] ?? false;

            if ($wallet->status != WalletStatus::LOGED_OUT && $notifyWallet) {
                (new SendUserNotificationAction())->handle(
                    $wallet->user,
                    'На кошельке '.$wallet->name.' слетела авторизация'
                );
            }

            $wallet->update([
                'status' => WalletStatus::LOGED_OUT,
            ]);
        }

        if ($request->status == 'need_verif') {
            $notifyWallet = $wallet->user->preferences['notifications']['wallet.all'] ?? false;

            if ($wallet->status != WalletStatus::NEED_VERIF && $notifyWallet) {
                (new SendUserNotificationAction())->handle(
                    $wallet->user,
                    'По кошельку '.$wallet->name.' необходимо пройти идентификацию'
                );
            }

            $wallet->update([
                'status' => WalletStatus::NEED_VERIF,
            ]);
        }

        if (in_array($request->status, ['bad_pass', 'bad_code', 'bad_login'])) {
            $task->status = $request->status;
        }

        $task->save();

        WalletConnect::dispatch($wallet, $task);

        if (in_array($request->status, ['bad_pass', 'bad_code', 'bad_login'])) {
            $wallet->delete();
            $task->delete();
        }

        return response()->json(['ok']);
    }

    public function updateBalance(WalletUpdateBalanceRequest $request)
    {
        $task = Task::find($request->task_id);

        $task->data = json_encode([
            'balance' => $request->balance,
            ...json_decode($task->data, true),
        ]);

        $balance = str_replace([' ', '₽'], '', $request->balance);
        $balance = str_replace(',', '.', $balance);

        $wallet = Wallet::where('task_id', $task->id)->first();

        if (! $wallet) {
            return response()->json(['error' => 'Wallet not found'], 422);
        }

        $wallet->balance = (float) $balance;
        $wallet->is_updating = false;
        $wallet->save();

        WalletUpdateBalance::dispatch($wallet);

        return response()->json(['ok']);
    }
}
