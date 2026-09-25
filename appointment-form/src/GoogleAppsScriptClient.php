<?php

declare(strict_types=1);

/**
 * Apps Script is Meet-only. Bookings live in MySQL.
 */
final class GoogleAppsScriptClient
{
    private string $url;
    private string $secret;

    public function __construct(array $config)
    {
        $this->url = (string) ($config['apps_script_url'] ?? '');
        $this->secret = (string) ($config['apps_script_secret'] ?? '');
    }

    public function isConfigured(): bool
    {
        return $this->url !== '' && $this->secret !== '';
    }

    /**
     * @param array{name:string,service:string,phone:string,payment_id:string,start_iso:string,end_iso:string} $booking
     * @return array{meet_link:string,booked_at:string}
     */
    public function createMeet(array $booking): array
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('Google Apps Script is not configured for Meet links.');
        }

        $response = $this->call([
            'action' => 'create_meet',
            'name' => $booking['name'],
            'service' => $booking['service'],
            'phone' => $booking['phone'],
            'payment_id' => $booking['payment_id'],
            'start_iso' => $booking['start_iso'],
            'end_iso' => $booking['end_iso'],
        ]);

        if (empty($response['meet_link'])) {
            throw new RuntimeException('Google Meet link was not returned.');
        }

        return [
            'meet_link' => (string) $response['meet_link'],
            'booked_at' => (string) ($response['booked_at'] ?? date('Y-m-d H:i:s')),
        ];
    }

    /** @deprecated Use createMeet(). Kept for older finalize callers. */
    public function book(array $booking): array
    {
        return $this->createMeet($booking);
    }

    /** @param array<string, mixed> $payload */
    private function call(array $payload): array
    {
        $payload['secret'] = $this->secret;
        $ch = curl_init($this->url);
        if ($ch === false) {
            throw new RuntimeException('Unable to start Google Apps Script request.');
        }

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => ['Content-Type: text/plain;charset=utf-8'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 120,
        ]);

        $raw = curl_exec($ch);
        $error = curl_error($ch);
        unset($ch);

        if ($raw === false) {
            throw new RuntimeException('Meet request failed: ' . $error);
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded) || empty($decoded['ok'])) {
            throw new RuntimeException((string) ($decoded['error'] ?? 'Meet request failed.'));
        }

        return $decoded;
    }
}
