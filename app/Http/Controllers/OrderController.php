<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use JsonException;

class OrderController extends Controller
{
    public OrderRepository $orderRepository;


    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function orderBond(OrderRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        $insertableData = [...$validated, 'bond_id' => $id];
        return $this->orderRepository->orderBond($insertableData);
    }

    public function interestPayments($orderId): ?JsonResponse
    {
        try {
            $payouts = $this->orderRepository->calculatePayments($orderId);
            return BaseController::responseJson(true, $payouts, 'payouts', 200);
        } catch (ModelNotFoundException|JsonException $exception) {
            return BaseController::responseJson($exception->getMessage(), null, null, 500);
        }

    }
}
