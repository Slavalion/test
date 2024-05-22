<?php

namespace App\Http\Controllers\Manager;

use App\Classes\UserSwitch;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $manager = $request->user();

        // $usersQuery = $manager->managerUsers()->with('prices');
        $usersQuery = User::query()->where('role', UserRole::CLIENT);

        if ($request->q) {
            $usersQuery->where('id', 'LIKE', '%'.$request->q.'%')
                ->orWhere('email', 'LIKE', '%'.$request->q.'%')
                ->orWhere('name', 'like', '%'.$request->q.'%');
        }

        $users = $usersQuery->paginate(50);

        $paginatorArray = $users->toArray();

        return Inertia::render('Manager/UsersPage', [
            'users' => $users->items(),
            'paginator' => $paginatorArray['links'],
        ]);
    }

    public function show(Request $request, User $user): InertiaResponse
    {
        if ($user->role != UserRole::CLIENT) {
            abort(403);
        }

        // if (! $user->managers()->where('id', $request->user()->id)->exists()) {
        //     abort(403);
        // }

        $userPrices = $user->prices->map(function ($price) {
            return [
                'id' => $price->id,
                'type' => $price->type->value,
                'value' => $price->value / 100,
            ];
        });

        $userProductsTotal = Purchase::select('product_id')
            ->selectRaw('count(product_id) as count')
            ->where('user_id', $user->id)
            ->groupBy('product_id')
            ->with('product')
            ->get()
            ->keyBy('product_id');

        $userProductsPickedUp = Purchase::select('product_id')
            ->selectRaw('count(product_id) as count')
            ->where('user_id', $user->id)
            ->where('delivery_status', Purchase::DELIVERY_STATUS_PICKED_UP)
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $userProductsPurchasesSum = Purchase::select('product_id')
            ->selectRaw('sum(price) as total_price')
            ->where('user_id', $user->id)
            // ->whereIn('delivery_status', Purchase::DELIVERY_STATUS_PICKED_UP)
            ->where('status', 'done')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $products = [];

        foreach ($userProductsTotal as $productKey => $product) {
            $products[] = [
                'product_id' => $productKey,
                'total_purchases' => $userProductsTotal[$productKey]->count,
                'total_picked_up' => $userProductsPickedUp[$productKey]->count ?? 0,
                'total_price' => ($userProductsPurchasesSum[$productKey]->total_price ?? 0) / 100,
            ];
        }

        return Inertia::render('Manager/UserPage', [
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
            'products' => $products,
        ]);
    }

    public function loginAs(Request $request, User $user): RedirectResponse
    {
        if ($user->role != UserRole::CLIENT) {
            abort(403);
        }

        // if (! $user->managers()->where('id', $request->user()->id)->exists()) {
        //     abort(403);
        // }

        UserSwitch::loginAs($user);

        return redirect()->route('purchase.list');
    }

    // Temporary deprecated
    public function setPreferences(Request $request, User $user): JsonResponse
    {
        abort(403);

        if ($user->role != UserRole::CLIENT) {
            abort(403);
        }

        // if (! $user->managers()->where('id', $request->user()->id)->exists()) {
        //     abort(403);
        // }

        $userPreferences = $user->preferences;

        $user->preferences = [
            ...$userPreferences,
            ...$request->only('use_livecargo'),
        ];

        $user->save();

        return response()->json(['ok']);
    }
}
