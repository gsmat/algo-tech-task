<?php

namespace App\Repositories;

use App\Interfaces\BondInterface;
use App\Models\Bond;
use Carbon\Carbon;

class BondRepository implements BondInterface
{
    public Bond $bond;

    public function __construct(Bond $bond)
    {
        $this->bond = $bond;
    }

    public function calculatePeriodDays(int $calculationPeriod, int $paymentFrequency): int
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

    public function calculateDates(int $paymentFrequency, string $issueDate, int $periodDate, int $calculationPeriod): array
    {
        $date = Carbon::parse($issueDate);
        $dates = [];
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
        return $dates;
    }

    public function interestDate(int $id): string|array
    {
        try {
            $bond = $this->bond::findOrFail($id);
            $calculationPeriod = (int)$bond->calculating_period_interest;
            $paymentFrequency = (int)$bond->payment_frequency_coupon;
            $issueDate = $bond->issue_date;
            $periodDate = $this->calculatePeriodDays($calculationPeriod, $paymentFrequency);
            return $this->calculateDates($paymentFrequency, $issueDate, $periodDate, $calculationPeriod);
        } catch (\Throwable $exception) {
            return $exception->getMessage();
        }

    }
}
