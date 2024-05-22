<?php

namespace App\Http\Controllers\Admin;

use App\Actions\UserTopUpBalance;
use App\Classes\UserSwitch;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Enums\UserPriceType;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPrice;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $usersQuery = User::with('prices');

        if ($request->q) {
            $usersQuery->where('id', 'LIKE', '%'.$request->q.'%')
                ->orWhere('email', 'LIKE', '%'.$request->q.'%')
                ->orWhere('name', 'like', '%'.$request->q.'%');
        }

        // $mappedUsers = $users->map(function ($user) {
        //     $userService = new UserService($user);
        //     $user->user_prices = $userService->getPrices();

        //     return $user;
        // });

        $users = $usersQuery->paginate(50);

        $paginatorArray = $users->toArray();

        return Inertia::render('Admin/Users', [
            'users' => $users->items(),
            'paginator' => $paginatorArray['links'],
        ]);
    }

    public function show(Request $request, User $user): InertiaResponse
    {
        // $userService = new UserService($user);
        // $userService->getPrices();

        $userPrices = $user->prices->map(function ($price) {
            return [
                'id' => $price->id,
                'type' => $price->type->value,
                'value' => $price->value / 100,
            ];
        });

        return Inertia::render('Admin/User', [
            'user' => $user,
            'prices' => $userPrices,
            'stat' => [
                'return_purchases' => [
                    'items' => Transaction::where('user_id', $user->id)->where('target', 'return_purchase')->count(),
                    'total' => Transaction::where('user_id', $user->id)->where('target', 'return_purchase')->sum('amount') / 100,
                ],
                'purchase_groups' => [
                    'items' => Transaction::where('user_id', $user->id)->where('target', 'App\\Models\\PurchaseGroup')->count(),
                    'total' => Transaction::where('user_id', $user->id)->where('target', 'App\\Models\\PurchaseGroup')->sum('amount') / 100,
                ],
                'pick_ups' => [
                    'items' => Transaction::where('user_id', $user->id)->where('target', 'livecargo')->count(),
                    'total' => Transaction::where('user_id', $user->id)->where('target', 'livecargo')->sum('amount') / 100,
                ],

                'purchases' => [
                    'items' => Purchase::where('user_id', $user->id)->count(),
                ],
                'reviews' => [
                    'items' => Review::where('user_id', $user->id)->count(),
                ],
            ],
        ]);
    }

    public function topUpBalance(Request $request, User $user, UserTopUpBalance $userTopUpBalance): JsonResponse
    {
        if ($request->user()->id == 15) {
            return response()->json([
                'message' => 'Недостаточно прав для операции',
            ], 422);
        }

        $request->validate([
            'amount' => ['required', 'numeric'],
        ]);

        $amount = $request->amount * 100;

        Transaction::create([
            'user_id' => $user?->id,
            'amount' => $amount > 0 ? $amount : -1 * $amount,
            'target' => TransactionTarget::BALANCE_MANUALLY,
            'type' => $request->amount > 0 ? TransactionType::TOP_UP : TransactionType::WRITE_OFF,
        ]);

        $userTopUpBalance->handle($user, $amount);

        return response()->json();
    }

    public function loginAs(User $user): RedirectResponse
    {
        UserSwitch::loginAs($user);

        return redirect()->route('purchase.list');
    }

    public function switchLogout(): RedirectResponse
    {
        UserSwitch::logout();

        return redirect()->route('purchase.list');
    }

    public function setPreferences(Request $request): JsonResponse
    {
        if ($request->user()->id == 15) {
            return response()->json([
                'message' => 'Недостаточно прав для операции',
            ], 422);
        }

        $user = User::findOrFail($request->user_id);

        $userPreferences = $user->preferences;

        $user->preferences = [
            ...$userPreferences,
            ...$request->only('use_livecargo'),
        ];

        $user->save();

        return response()->json(['ok']);
    }

    public function setPrices(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['required'],
            'prices' => ['required', 'array'],
        ]);

        $user = User::findOrFail($request->user_id);

        foreach ($request->prices as $type => $value) {
            if ($value <= 0) {
                continue;
            }

            $priceType = UserPriceType::tryFrom($type);

            if (! $priceType) {
                continue;
            }

            $price = $value * 100;

            UserPrice::updateOrCreate([
                'user_id' => $user->id,
                'type' => $priceType,
            ], [
                'value' => $price,
            ]);
        }

        return response()->json(['ok']);
    }
}
