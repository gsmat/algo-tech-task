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
            $periodDate = $this->getPeriodDays($calculationPeriod, $paymentFrequency);

            date_default_timezone_set('UTC');
            $dates = [];
            $date = Carbon::parse($issueDate);

            for ($i = 0; $i < 12 / $paymentFrequency; $i++) {
                if ($date->isWeekend()) {
                    $date = $date->startOfWeek()->addWeeks(1);
                }

                if ($calculationPeriod !== 365) {
                    $date = strtotime("+{$periodDate} days", strtotime($date));
                } else {
                    $date = strtotime("+{$periodDate} months", strtotime($date));
                }
                $date = Carbon::parse($date);

                $dates[] = array("date" => $date->toDateString());
            }

            return BaseController::responseJson(true, $dates, 'dates', 200);
        } catch (ModelNotFoundException $exception) {
            return BaseController::responseJson($exception->getMessage(), null, null, 500);
        }
    }

    public function getPeriodDays($calculationPeriod, $paymentFrequency)
    {
        switch ($calculationPeriod) {
            case 360:
                $periodTimes = (12 / $paymentFrequency) * 30;
                break;
            case 364:
                $periodTimes = 364 / $paymentFrequency;
                break;
            case 365:
                $periodTimes = 12 / $paymentFrequency;
                break;
        }
        return $periodTimes;
    }

}
