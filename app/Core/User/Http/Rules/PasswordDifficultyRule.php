<?php

declare(strict_types=1);

namespace App\Core\User\Http\Rules;

use App\Core\User\Services\Auth\DTOs\PasswordConfig;
use Illuminate\Contracts\Validation\InvokableRule;

final class PasswordDifficultyRule implements InvokableRule
{
    public function __construct(
        private readonly PasswordConfig $passwordConfig,
    ) {
    }

    public function __invoke($attribute, $value, $fail): void
    {
        $regex = $this->passwordConfig->validationRegex;

        if (! (preg_match('/' . $regex . '/', $value) > 0)) {
            $fail($this->passwordFailMessage());
        }
    }

    private function passwordFailMessage(): string
    {
        return match ($this->passwordConfig->validationRegex) {
            '^.{6,}'                                     => 'پسورد حداقل باید حاوی ۶ حرف باشد.',
            '^.{8,}'                                     => 'پسورد حداقل باید حاوی ۸ حرف باشد.',
            '^(?=.*[a-z|A-Z])(?=.*[0-9]).{8,}'           => 'پسورد باید حاوی حروف و اعداد و حداقل ۸ حرف داشته باشد.',
            '^(?=.*[a-z|A-Z])(?=.*[0-9])(?=.*[\W]).{8,}' => 'پسورد باید حاوی حروف و اعداد و کاراکترهای خاص و حداقل ۸ حرف داشته باشد.',
            default                                      => 'پسورد وارد شده ضعیف می باشد.'
        };
    }
}
