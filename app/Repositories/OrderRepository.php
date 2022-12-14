<?php

namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Interfaces\OrderInterface;
use App\Models\Bond;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Throwable;

class OrderRepository implements OrderInterface
{
    public Order $order;
    public BondRepository $bond;

    public function __construct(Order $order, BondRepository $bond)
    {
        $this->order = $order;
        $this->bond = $bond;
    }

    public function orderBond(array $bondData): JsonResponse
    {
        try {
            $order = $this->order::create($bondData);
            if ($order) {
                return BaseController::responseJson(true, $order, 'order', 201);
            }
            return BaseController::responseJson(false, null, 'order', 500);
        } catch (Throwable $exception) {
            return BaseController::responseJson($exception->getMessage(), null, null, 500);
        }
    }

    public function calculatePayments(int $orderId): array
    {
        $order = $this->order::with('bond')->findOrFail($orderId);
        $bond = $order->bond;

        $dates = $this->bond->interestDate($bond->id);
        $payouts = [];
        $modifiedDates = array_values((array)$dates);
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
        return $payouts;
    }
}
