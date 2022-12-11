<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Bond;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function orderBond(OrderRequest $request, int $id)
    {
        $validated = $request->validated();
        if ($validated) {
            $insertableData = array_merge($validated, ['bond_id' => $id]);
            try {
                $order = Order::create($insertableData);
                return response()->json([
                    'message' => true,
                    'order' => $order
                ], 201);
            } catch (\Throwable $exception) {
                return response()->json([
                    'message' => $exception->getMessage()
                ], 500);
            }
        }
    }

    public function interestPayments($orderId): ?JsonResponse
    {
        try {
            $order = Order::with('bond')->findOrFail($orderId);
            $bond = $order->bond;

            $dates = (new BondController)->interestDate($bond->id);
            $modifiedDates = json_decode($dates->getContent(), true, 512, JSON_THROW_ON_ERROR)['dates'];
            $payouts = [];
            $modifiedDates = array_values((array)$modifiedDates);
            $calculatingPeriod = (int)$bond->calculating_period_interest;
            $carbonInstance = new Carbon();
            foreach ($modifiedDates as $key => $date) {
                $nextDateOfInterest = $carbonInstance::parse(strtotime($date));
                if ($key > 0) {
                    $previousDateOfInterest = $carbonInstance::parse(strtotime($modifiedDates[$key - 1]));
                    $days = $nextDateOfInterest->diffInDays($previousDateOfInterest);
                } else {
                    $orderDate = $carbonInstance::parse(strtotime($order->order_date));
                    $days = $nextDateOfInterest->diffInDays($orderDate);
                }

                $amount = ($bond->nominal_price / 100 * $bond->coupon_interest) / $calculatingPeriod * $days * $order->bonds_quantity;

                $payouts[] = ["date" => $date, "amount" => round($amount, 4)];
            }
            return response()->json([
                'payouts' => $payouts
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        } catch (\JsonException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

    }
}
