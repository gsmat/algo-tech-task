<?php

namespace App\Http\Controllers;

use App\Models\Bond;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class BondController extends Controller
{
    public function interestDate(int $id): ?JsonResponse
    {
        try {
            $bond = Bond::findOrFail($id);
            $calculationPeriod = (int)$bond->calculating_period_interest;
            $paymentFrequency = (int)$bond->payment_frequency_coupon;
            $issueDate = $bond->issue_date;
            switch ($calculationPeriod) {
                case 360:
                    $periodDays = (12 / $paymentFrequency) * 30;
                    break;
                case 364:
                    $periodDays = 364 / $paymentFrequency;
                    break;
                case 365:
                    $periodMonth = 12 / $paymentFrequency;
                    break;
            }
            $carbonInstance = new Carbon();
            $dates = [];
            for ($i = 0; $i < 12 / $paymentFrequency; $i++) {
                $date = $carbonInstance::createFromFormat('Y-m-d', $issueDate);
                $calculationPeriod !== 365
                    ? $date = $date->addDays($periodDays)->toDateString()
                    : $date = $date->addMonth($periodMonth)->toDateString();

                $daysOfWeek = $carbonInstance::parse($date)->dayOfWeek;

                if (in_array($daysOfWeek, [0, 6])) {
                    $date = $carbonInstance::parse($date)->startOfWeek()->addWeeks(1)->toDateString();
                }

                $dates['date'] = $date;
            }
            return response()->json([
                'message' => true,
                'dates' => $dates
            ], 200);

        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

}
