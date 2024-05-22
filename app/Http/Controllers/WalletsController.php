<?php

namespace App\Http\Controllers;

use App\Actions\BotRequestAction;
use App\Http\Requests\WalletsConnectRequest;
use App\Models\Task;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class WalletsController extends Controller
{
    private array $walletsShortCodes = [
        'qiwi' => 'qw',
        'yoomoney' => 'ym',
    ];

    public function index(Request $request): InertiaResponse
    {
        Wallet::where('is_updating', 1)->where('is_updating_ts', '<=', Carbon::now())->update([
            'is_updating' => false,
        ]);

        $wallets = Wallet::where('user_id', $request->user()->id)->get();

        return Inertia::render('Wallets', [
            'wallets' => [
                'qiwi' => $wallets->where('type', 'qiwi'),
                'yoomoney' => $wallets->where('type', 'yoomoney'),
            ],
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        $wallet = Wallet::where('user_id', $request->user()->id)->find($request->wallet);

        if (! $wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Кошелек не найден',
            ], 422);
        }

        return response()->json($wallet);
    }

    public function connect(WalletsConnectRequest $request, BotRequestAction $botRequestAction): JsonResponse
    {
        // $shorCode = $this->walletsShortCodes[$request->code];
        $shorCode = $this->walletsShortCodes['yoomoney'];

        if (! str_contains($request->login, '@')) {
            return response()->json([
                'message' => 'Для логина необходимо использовать email адрес',
            ], 422);
        }

        if (Wallet::where('name', $request->login)->where('user_id', $request->user()->id)->first()) {
            return response()->json([
                'message' => 'Кошелек с таким логином уже привязан',
            ], 422);
        }

        $taskData = [
            'login' => $request->login,
            'password' => $request->password,
        ];

        $task = Task::create([
            'user_id' => $request->user()->id,
            'type' => 'wb-connect-'.$shorCode,
            'status' => 'process',
            'data' => json_encode($taskData),
        ]);

        $wallet = new Wallet();
        $wallet->task_id = $task->id;
        $wallet->user_id = $request->user()->id;
        $wallet->type = 'yoomoney';
        $wallet->name = $request->login;
        $wallet->save();

        try {
            $botRequestAction->execute([
                'task_id' => $task->id,
                'wallet_id' => $wallet->id,
                'type' => 'wb-connect-'.$shorCode,
                'user_id' => $request->user()->id,
                ...$taskData,
            ]);
        } catch (\Throwable $th) {
            $task->delete();
            $wallet->delete();

            return response()->json([
                'status' => 'error',
                'message' => 'Не удалось добавить кошелек',
            ], 422);
        }

        return response()->json([
            'status' => 'ok',
            'wallet_id' => $wallet->id,
        ]);
    }

    public function confirm(Request $request, BotRequestAction $botRequestAction): JsonResponse
    {
        $wallet = Wallet::find($request->id);

        if (! $wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Кошелек не найден, попробуйте заново',
            ], 422);
        }

        $shortCode = $this->walletsShortCodes[$wallet->type];

        $taskData = [
            'code' => $request->code,
        ];

        $task = Task::find($wallet->task_id);
        $task->status = 'send_request_code';
        $task->data = json_encode($taskData);
        $task->save();

        $botRequestAction->execute([
            'task_id' => $task->id,
            'type' => 'wb-connect-'.$shortCode.'-confirm',
            'user_id' => $request->user()->id,
            ...$taskData,
        ]);

        return response()->json([
            'status' => 'ok',
            'wallet_id' => $wallet->id,
        ]);
    }

    public function updateBalance(Request $request, BotRequestAction $botRequestAction): JsonResponse
    {
        $walletsData = [];

        foreach ($request->wallets as $walletItem) {
            $wallet = Wallet::where('user_id', $request->user()->id)
                ->where('id', $walletItem['id'])
                ->first();

            if (! $wallet) {
                continue;
            }

            if ($wallet->is_updating) {
                continue;
            }

            $wallet->update([
                'is_updating' => true,
                'is_updating_ts' => Carbon::now()->addMinutes(7),
            ]);

            Task::find($wallet->task_id)->update([
                'status' => 'updating-balance',
            ]);

            $walletsData[] = [
                'task_id' => $wallet->task_id,
                'wallet_id' => $wallet->id,
                'type' => 'wb-wallet-update-balance',
            ];
        }

        if (count($walletsData) == 0) {
            return response()->json([
                'status' => 'empty wallets',
            ]);
        }

        $botRequestAction->execute($walletsData);

        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function delete(Request $request, BotRequestAction $botRequestAction): JsonResponse
    {
        $wallet = Wallet::where('user_id', $request->user()->id)
            ->where('id', $request->wallet)
            ->first();

        $botResponse = $botRequestAction->execute([
            'task_id' => $wallet->task_id,
            'wallet_id' => $wallet->id,
            'type' => 'wb-wallet-delete',
        ]);

        if (! $botResponse) {
            return response()->json([
                'status' => 'error',
                'message' => 'Не удалось удалить кошелек, попробуйте позже',
            ], 422);
        }

        $wallet->delete();

        return response()->json([
            'status' => 'ok',
        ], 200);
    }
}
