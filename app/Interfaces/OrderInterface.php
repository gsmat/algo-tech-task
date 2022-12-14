<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface OrderInterface
{
    public function orderBond(array $bondData): JsonResponse;

    public function calculatePayments(int $orderId): array;
}
