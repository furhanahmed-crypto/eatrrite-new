<?php

declare(strict_types=1);

final class BookingStore
{
    private BookingRepository $repo;

    public function __construct()
    {
        $this->repo = new BookingRepository();
    }

    public function findById(string $id): ?array
    {
        return $this->repo->findById($id);
    }

    public function findByPaymentId(string $paymentId): ?array
    {
        return $this->repo->findByPaymentId($paymentId);
    }

    public function claimForFinalize(string $paymentId): string
    {
        return $this->repo->claimForFinalize($paymentId);
    }

    public function saveVerified(array $row): void
    {
        $this->repo->saveVerified($row);
    }

    public function update(string $paymentId, callable $callback): array
    {
        return $this->repo->update($paymentId, $callback);
    }

    /** @return list<array{date:string,time:string}> */
    public function occupiedSlots(): array
    {
        return $this->repo->occupiedSlots();
    }

    /** @return list<array<string, mixed>> */
    public function allCompleted(): array
    {
        return $this->repo->allCompleted();
    }

    /** @return array<string, mixed> */
    public function deleteBySlot(string $date, string $time, string $phone, string $name): array
    {
        return $this->repo->deleteBySlot($date, $time, $phone, $name);
    }
}
