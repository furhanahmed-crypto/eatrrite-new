<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

appointment_require_post();
appointment_assert_csrf();

try {
    $input = appointment_json_input();
    $appointmentId = trim((string) ($input['appointment_id'] ?? ''));
    if ($appointmentId === '') {
        appointment_json_error('Appointment id is required.', 400);
    }

    $record = (new BookingStore())->findById($appointmentId);
    if ($record === null) {
        appointment_json_error('Appointment not found.', 404);
    }
    if (!empty($record['questionnaire_completed'])) {
        appointment_json_ok([
            'appointment_id' => $appointmentId,
            'saved' => true,
            'already_completed' => true,
            'meet_link' => (string) ($record['meet_link'] ?? ''),
        ]);
    }

    $spec = require dirname(__DIR__, 2) . '/constants/questionnaire.php';
    $answers = (new QuestionnaireValidator())->validated($input, $spec);
    $saved = (new QuestionnaireRepository())->saveOnce($appointmentId, $answers);

    appointment_json_ok([
        'appointment_id' => $appointmentId,
        'saved' => true,
        'already_completed' => $saved === 'already',
    ]);
} catch (InvalidArgumentException $e) {
    appointment_json_fail($e, 400);
} catch (Throwable $e) {
    appointment_json_fail($e, 500);
}
