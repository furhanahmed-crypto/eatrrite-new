<?php

declare(strict_types=1);

final class GoogleAppsScriptClient
{
    private string $url;
    private string $secret;
    private string $sheetId;
    private string $tabName;
    private string $slotTimesTab;
    private string $disabledSlotsTab;

    public function __construct(array $config)
    {
        $this->url = $config['apps_script_url'];
        $this->secret = $config['apps_script_secret'];
        $this->sheetId = $config['google_sheet_id'];
        $this->tabName = $config['google_sheet_tab'];
        $this->slotTimesTab = (string) ($config['google_slot_times_tab'] ?? 'slot-times-config');
        $this->disabledSlotsTab = (string) ($config['google_disabled_slots_tab'] ?? 'disabled-slots');
    }

    public function isConfigured(): bool
    {
        return $this->url !== '' && $this->secret !== '';
    }

    /**
     * @return list<array{date:string,time:string,name:string,service:string,phone:string,meet_link:string,booked_at:string}>
     */
    public function listAppointments(): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        $response = $this->call([
            'action' => 'list',
        ]);

        $booked = $response['booked'] ?? [];
        if (!is_array($booked)) {
            return [];
        }

        $rows = [];
        foreach ($booked as $row) {
            if (!is_array($row)) {
                continue;
            }
            $date = (string) ($row['date'] ?? '');
            $time = (string) ($row['time'] ?? '');
            if ($date === '' || $time === '') {
                continue;
            }
            $rows[] = [
                'date' => $date,
                'time' => $time,
                'name' => (string) ($row['name'] ?? ''),
                'service' => (string) ($row['service'] ?? ''),
                'phone' => (string) ($row['phone'] ?? ''),
                'meet_link' => (string) ($row['meet_link'] ?? ''),
                'booked_at' => (string) ($row['booked_at'] ?? ''),
            ];
        }

        return $rows;
    }

    /**
     * @return list<array{date:string,time:string}>
     */
    public function listBookedSlots(): array
    {
        $rows = [];
        foreach ($this->listAppointments() as $row) {
            $rows[] = [
                'date' => $row['date'],
                'time' => $row['time'],
            ];
        }

        return $rows;
    }

    /**
     * @param array{
     *   name:string,
     *   service:string,
     *   phone:string,
     *   date:string,
     *   time:string,
     *   payment_id:string,
     *   start_iso:string,
     *   end_iso:string,
     *   consultant_block_minutes?:int
     * } $booking
     * @return array{meet_link:string,booked_at:string}
     */
    public function book(array $booking): array
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('Google Apps Script is not configured. Deploy the script and add GOOGLE_APPS_SCRIPT_WEBAPP_URL to .env.');
        }

        $response = $this->call([
            'action' => 'book',
            'name' => $booking['name'],
            'service' => $booking['service'],
            'phone' => $booking['phone'],
            'date' => $booking['date'],
            'time' => $booking['time'],
            'payment_id' => $booking['payment_id'],
            'start_iso' => $booking['start_iso'],
            'end_iso' => $booking['end_iso'],
            'consultant_block_minutes' => (int) ($booking['consultant_block_minutes'] ?? 45),
        ]);

        if (empty($response['meet_link'])) {
            throw new RuntimeException('Booking saved but Google Meet link was not returned.');
        }

        return [
            'meet_link' => (string) $response['meet_link'],
            'booked_at' => (string) ($response['booked_at'] ?? ''),
        ];
    }

    /**
     * @return array{found?:bool,settings?:array<string, mixed>,windows?:list<array<string, mixed>>}
     */
    public function listSlotTimesConfig(): array
    {
        if (!$this->isConfigured()) {
            return ['found' => false];
        }

        $response = $this->call([
            'action' => 'list_slot_times',
            'tab_name' => $this->slotTimesTab,
        ]);

        $config = $response['config'] ?? $response;
        if (!is_array($config)) {
            return ['found' => false];
        }

        return $config;
    }

    /**
     * @return list<array{date:string,time:string}>
     */
    public function listDisabledSlots(): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        $response = $this->call([
            'action' => 'list_disabled_slots',
        ]);

        $rows = $response['disabled'] ?? [];
        if (!is_array($rows)) {
            return [];
        }

        $out = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $date = (string) ($row['date'] ?? '');
            $time = (string) ($row['time'] ?? '');
            if ($date === '' || $time === '') {
                continue;
            }
            $out[] = ['date' => $date, 'time' => $time];
        }

        return $out;
    }

    public function setDisabledSlot(string $date, string $time, bool $hidden): void
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('Google Sheet is not configured.');
        }

        $this->call([
            'action' => 'set_disabled_slot',
            'date' => $date,
            'time' => $time,
            'hidden' => $hidden,
        ]);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function call(array $payload): array
    {
        $payload['secret'] = $this->secret;
        $payload['sheet_id'] = $this->sheetId;
        if (empty($payload['tab_name'])) {
            $payload['tab_name'] = $this->tabName;
        }
        if (empty($payload['disabled_tab_name'])) {
            $payload['disabled_tab_name'] = $this->disabledSlotsTab;
        }

        $ch = curl_init($this->url);
        if ($ch === false) {
            throw new RuntimeException('Unable to start Google Apps Script request.');
        }

        // Apps Script accepts the POST, then 302s to a googleusercontent URL.
        // That second hop must be GET. Keeping POST on the redirect returns HTML.
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
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            throw new RuntimeException('Google Sheet request failed: ' . $error);
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('Google Sheet returned an invalid response. Open the /exec URL in a browser — it should show JSON, not a Google login page.');
        }

        if ($status >= 400 || empty($decoded['ok'])) {
            $message = (string) ($decoded['error'] ?? 'Google Sheet update failed.');
            if (($decoded['code'] ?? '') === 'slot_taken') {
                throw new InvalidArgumentException($message);
            }
            throw new RuntimeException($message);
        }

        return $decoded;
    }
}
