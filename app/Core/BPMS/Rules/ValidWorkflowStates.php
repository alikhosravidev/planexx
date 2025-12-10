<?php

declare(strict_types=1);

namespace App\Core\BPMS\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

// TODO: refactor this validation rule and add this validation rules for workflow service
class ValidWorkflowStates implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!is_array($value) || empty($value)) {
            // خود فیلد توسط rules اصلی برای required/array/min:1 کنترل می‌شود
            return;
        }

        $hasStart = false;
        $hasFinal = false;

        $names = [];
        $slugs = [];

        foreach ($value as $state) {
            if (!is_array($state)) {
                continue;
            }

            $position = $state['position'] ?? null;

            if (!is_string($position)) {
                continue;
            }

            if ($position === 'start') {
                $hasStart = true;
            }

            if (str_starts_with($position, 'final')) {
                $hasFinal = true;
            }

            $name = $state['name'] ?? null;

            if (is_string($name)) {
                $name = trim($name);

                if ($name !== '') {
                    if (in_array($name, $names, true)) {
                        $fail('نام مراحل نباید تکراری باشد');

                        return;
                    }

                    $names[] = $name;
                }
            }

            $slug = $state['slug'] ?? null;

            if (is_string($slug)) {
                $slug = trim($slug);

                if ($slug !== '') {
                    if (in_array($slug, $slugs, true)) {
                        $fail('شناسه (slug) مراحل نباید تکراری باشد');

                        return;
                    }

                    $slugs[] = $slug;
                }
            }
        }

        if (!$hasStart) {
            $fail('لطفاً یک مرحله به عنوان "نقطه شروع" تعریف کنید');
        }

        if (!$hasFinal) {
            $fail('لطفاً حداقل یک مرحله "پایانی" تعریف کنید');
        }
    }
}
