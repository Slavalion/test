<?php

namespace App\Http\Controllers;

use App\Actions\BotRequestAction;
use App\Enums\BotRequestType;
use App\Exports\DeliveriesExport;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DeliveryController extends Controller
{
    private const deliveryStatuses = [
        'none' => Purchase::DELIVERY_STATUS_NONE,
        'processing' => Purchase::DELIVERY_STATUS_PROCESSING,
        'sorted' => Purchase::DELIVERY_STATUS_SORTED,
        'pending' => Purchase::DELIVERY_STATUS_PENDING,
        'on_the_way' => Purchase::DELIVERY_STATUS_ON_THE_WAY,
        'available_for_pick_up' => Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP,
        'picked_up' => Purchase::DELIVERY_STATUS_PICKED_UP,
        'canceled' => Purchase::DELIVERY_STATUS_CANCELED,
    ];

    /**
     * Summary of index
     */
    public function index(Request $request): InertiaResponse
    {
        $request->validate([
            'status' => Rule::in([...self::deliveryStatuses, 'all']),
        ]);

        $status = $request->status ?: Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP;

        $purchasesQuery = Purchase::where('user_id', $request->user()->id);

        if ($status == 'all') {
            $purchasesQuery->whereNotIn('delivery_status', [
                Purchase::DELIVERY_STATUS_NONE,
                // Purchase::DELIVERY_STATUS_PICKED_UP,
            ]);
        } else {
            $purchasesQuery->where('delivery_status', $status);
        }

        $purchasesQuery->with('product');

        $purchases = $purchasesQuery->paginate(50)->withQueryString();

        $paginatorArray = $purchases->toArray();

        return Inertia::render('Deliveries', [
            'deliveries' => $purchases->items(),
            'deliveryStatus' => $status,
            'paginator' => $paginatorArray['links'],
        ]);
    }

    public function updateData(Request $request, BotRequestAction $botRequestAction): JsonResponse
    {
        $purchase = Purchase::with('product')->where('id', $request->purchase_id)->first();
        $purchase->update(['is_updating_delivery' => true]);

        // dd($purchase);
        $botRequestAction->execute([
            'type' => BotRequestType::PURCHASE_RECHECK_COURIER_DELIVERY->value,
            'task_id' => $purchase->task_id,
            // 'purchase_id' => $request->purchase_id,
        ]);

        return response()->json(['delivery' => $purchase], 200);
    }

    public function download(Request $request): BinaryFileResponse
    {
        $request->validate([
            'status' => Rule::in([...self::deliveryStatuses, 'all']),
        ]);

        $status = $request->status ?: Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP;

        $fromDate = $request->fromDate ? Carbon::createFromDate($request->fromDate) : null;
        $withoutQRcode = $request->withoutQR ?? false;

        return (new DeliveriesExport($request->user()->id, $status, $fromDate, $withoutQRcode))->download('deliveries.xlsx');
    }
}
