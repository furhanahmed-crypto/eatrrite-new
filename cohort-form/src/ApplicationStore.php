<?php

declare(strict_types=1);

final class CohortApplicationStore
{
    private CohortApplicationRepository $applications;

    public function __construct()
    {
        $this->applications = new CohortApplicationRepository();
    }

    public function findByPaymentId(string $paymentId): ?array
    {
        return $this->applications->findByPaymentId($paymentId);
    }

    public function countPaidForMonth(string $month): int
    {
        return $this->applications->countPaidForMonth($month);
    }

    public function save(array $application): array
    {
        return $this->applications->save($application);
    }
}
