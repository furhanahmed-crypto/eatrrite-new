<?php

declare(strict_types=1);

final class AppointmentValidator
{
    public function __construct(private array $config, private SlotService $slots)
    {
    }

    /** @return array{name:string,email:string,service:string,phone:string,date:string,time:string} */
    public function validatedBooking(array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $service = appointment_service_label(trim((string) ($input['programname'] ?? $input['service'] ?? '')));
        $phone = preg_replace('/\D+/', '', (string) ($input['mobilenumber'] ?? $input['phone'] ?? '')) ?? '';
        $date = trim((string) ($input['date'] ?? ''));
        $time = trim((string) ($input['time'] ?? ''));

        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        }
        if ($name === '' || !preg_match('/^[\p{L}\s.\'-]{2,80}$/u', $name)) {
            throw new InvalidArgumentException('Enter a valid name.');
        }
        $allowed = $this->config['services'];
        $cohortService = trim((string) ($this->config['cohort_service'] ?? ''));
        if ($cohortService !== '') {
            $allowed[] = $cohortService;
        }
        if (!in_array($service, $allowed, true)) {
            throw new InvalidArgumentException('Select a service.');
        }
        if (!preg_match('/^[6-9]\d{9}$/', $phone)) {
            throw new InvalidArgumentException('Enter a valid 10-digit mobile number.');
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Enter a valid email address.');
        }
        if ($date === '' || $time === '') {
            throw new InvalidArgumentException('Select an appointment date and time.');
        }

        $this->slots->parseDate($date);
        if (!$this->slots->isOfferedTime($date, $time)) {
            throw new InvalidArgumentException('Select a valid appointment time.');
        }

        return compact('name', 'email', 'service', 'phone', 'date', 'time');
    }
}
