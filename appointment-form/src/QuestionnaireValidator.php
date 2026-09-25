<?php

declare(strict_types=1);

final class QuestionnaireValidator
{
    /** @param array<string, mixed> $spec @return array<string, string> */
    public function validated(array $input, array $spec): array
    {
        $answers = is_array($input['answers'] ?? null) ? $input['answers'] : [];
        $saved = [];
        foreach ($spec['steps'] as $step) {
            $key = (string) $step['key'];
            $saved[$key] = $this->one($answers[$key] ?? null, $step);
            if (!empty($step['follow_key'])) {
                $follow = $answers[$step['follow_key']] ?? '';
                $saved[(string) $step['follow_key']] = is_string($follow) ? trim($follow) : '';
            }
        }
        return $saved;
    }

    /** @param array<string, mixed> $step */
    private function one(mixed $value, array $step): string
    {
        $type = (string) $step['type'];
        if ($type === 'checkbox' || $type === 'checkbox-groups') {
            $list = is_array($value) ? array_values(array_filter(array_map('strval', $value))) : [];
            if (!empty($step['required']) && $list === []) {
                throw new InvalidArgumentException('Please answer: ' . $step['label']);
            }
            return json_encode($list, JSON_UNESCAPED_UNICODE);
        }
        $text = is_string($value) || is_numeric($value) ? trim((string) $value) : '';
        if (!empty($step['required']) && $text === '') {
            throw new InvalidArgumentException('Please answer: ' . $step['label']);
        }
        if ($type === 'number' && $text !== '') {
            $age = (int) $text;
            $min = (int) ($step['min'] ?? 13);
            $max = (int) ($step['max'] ?? 90);
            if ($age < $min || $age > $max) {
                throw new InvalidArgumentException('Please enter an age between ' . $min . ' and ' . $max . '.');
            }
        }
        return $text;
    }
}
