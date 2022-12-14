<?php

namespace App\Http\Controllers;

use App\Repositories\BondRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class BondController extends Controller
{
    public BondRepository $bondRepository;

    public function __construct(BondRepository $bondRepository)
    {
        $this->bondRepository = $bondRepository;
    }

    public function interestDate(int $id): ?JsonResponse
    {
        try {
            $dates = $this->bondRepository->interestDate($id);
            return BaseController::responseJson(true, $dates, 'dates', 200);
        } catch (ModelNotFoundException $exception) {
            return BaseController::responseJson($exception->getMessage(), null, null, 500);
        }
    }
}
