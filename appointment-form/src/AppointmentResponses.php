<?php

declare(strict_types=1);

final class AppointmentResponses
{
    /** @param array<string, mixed> $record */
    public static function verified(array $record): array
    {
        $completed = ($record['status'] ?? '') === 'completed' && !empty($record['meet_link']);
        $response = [
            'appointment_id' => (string) ($record['id'] ?? ''),
            'payment_id' => (string) ($record['payment_id'] ?? ''),
            'order_id' => (string) ($record['order_id'] ?? ''),
            'name' => (string) ($record['name'] ?? ''),
            'email' => (string) ($record['email'] ?? ''),
            'service' => (string) ($record['service'] ?? ''),
            'display_date' => (string) ($record['display_date'] ?? ''),
            'display_time' => (string) ($record['display_time'] ?? ''),
            'status' => (string) ($record['status'] ?? ''),
            'meet_link_ready' => $completed,
        ];
        if ($completed) {
            $response['meet_link'] = (string) $record['meet_link'];
            $response['emails_sent'] = (bool) ($record['emails_sent'] ?? false);
        }
        return $response;
    }

    /** @param array<string, mixed> $record */
    public static function finalized(array $record): array
    {
        $response = [
            'status' => 'completed',
            'meet_link' => (string) ($record['meet_link'] ?? ''),
            'booked_at' => (string) ($record['booked_at'] ?? ''),
            'emails_sent' => (bool) ($record['emails_sent'] ?? false),
        ];
        if (!empty($record['email_error'])) {
            $response['email_error'] = (string) $record['email_error'];
        }
        return $response;
    }
}
