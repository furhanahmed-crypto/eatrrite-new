<?php

declare(strict_types=1);

final class QuestionnaireRepository
{
    /** @param array<string, string> $answers @return 'saved'|'already' */
    public function saveOnce(string $appointmentId, array $answers): string
    {
        $pdo = app_pdo();
        $pdo->beginTransaction();
        try {
            $lock = $pdo->prepare(
                'SELECT questionnaire_completed FROM appointments WHERE id = :id LIMIT 1 FOR UPDATE'
            );
            $lock->execute(['id' => $appointmentId]);
            $row = $lock->fetch();
            if ($row === false) {
                throw new InvalidArgumentException('Appointment not found.');
            }
            if (!empty($row['questionnaire_completed'])) {
                $pdo->commit();
                return 'already';
            }

            $insert = $pdo->prepare(
                'INSERT INTO questionnaire_answers (id, appointment_id, question_key, answer)
                 VALUES (:id, :appointment_id, :question_key, :answer)'
            );
            foreach ($answers as $key => $answer) {
                $insert->execute([
                    'id' => app_id(),
                    'appointment_id' => $appointmentId,
                    'question_key' => $key,
                    'answer' => $answer,
                ]);
            }
            $pdo->prepare(
                'UPDATE appointments SET questionnaire_completed = 1 WHERE id = :id'
            )->execute(['id' => $appointmentId]);
            $pdo->commit();
            return 'saved';
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    /**
     * @param list<string> $appointmentIds
     * @return array<string, array<string, string>>
     */
    public function forAppointments(array $appointmentIds): array
    {
        $ids = array_values(array_filter($appointmentIds));
        if ($ids === []) {
            return [];
        }
        $marks = implode(',', array_fill(0, count($ids), '?'));
        $stmt = app_pdo()->prepare(
            "SELECT appointment_id, question_key, answer
             FROM questionnaire_answers
             WHERE appointment_id IN ($marks)"
        );
        $stmt->execute($ids);
        $grouped = [];
        foreach ($stmt->fetchAll() as $row) {
            $grouped[(string) $row['appointment_id']][(string) $row['question_key']] = (string) $row['answer'];
        }
        return $grouped;
    }
}
