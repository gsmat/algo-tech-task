<?php

namespace App\Interfaces;

interface BondInterface
{
    public function calculatePeriodDays(int $calculationPeriod, int $paymentFrequency): int;

    public function calculateDates(int $paymentFrequency, string $issueDate,int $periodDate, int $calculationPeriod): array;

    public function interestDate(int $id);
}
