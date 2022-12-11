<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use JsonException;
use Throwable;

class OrderController extends Controller
{
    public function orderBond(OrderRequest $request, int $id)
    {
        $validated = $request->validated();
        if ($validated) {
            $insertableData = [...$validated, 'bond_id' => $id];
            try {
                $order = Order::create($insertableData);
                if ($order) {
                    return BaseController::responseJson(true, $order, 'order', 201);
                }
                return BaseController::responseJson(false, null, 'order', 500);
            } catch (Throwable $exception) {
                return BaseController::responseJson($exception->getMessage(), null, null, 500);
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
                $nextDateOfInterest = $carbonInstance::parse(strtotime($date['date']));
                if ($key > 0) {
                    $previousDateOfInterest = $carbonInstance::parse(strtotime($modifiedDates[$key - 1]['date']));
                    $days = $nextDateOfInterest->diffInDays($previousDateOfInterest);
                } else {
                    $orderDate = $carbonInstance::parse(strtotime($order->order_date));
                    $days = $nextDateOfInterest->diffInDays($orderDate);
                }

                $amount = ($bond->nominal_price / 100 * $bond->coupon_interest) / $calculatingPeriod * $days * $order->bonds_quantity;

                $payouts[] = ["date" => $date['date'], "amount" => round($amount, 4)];
            }
            return BaseController::responseJson(true, $payouts, 'payouts', 200);
        } catch (ModelNotFoundException|JsonException $exception) {
            return BaseController::responseJson($exception->getMessage(), null, null, 500);
        }

    }
}
