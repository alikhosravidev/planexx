<?php

declare(strict_types=1);

namespace App\Core\User\Services\OTPService;

use App\Core\User\Entities\TemporaryCode;
use App\Core\User\Entities\User;
use App\Core\User\Repositories\TemporaryCodeRepository;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use App\Core\User\Services\OTPService\Contracts\OTPGenerator;
use App\Core\User\Services\OTPService\Exceptions\OTPException;
use App\Core\User\Services\OTPService\Exceptions\TechOTPException;

class OTPService
{
    public function __construct(
        private readonly OTPGenerator $otpGenerator,
        private readonly TemporaryCodeRepository $temporaryCodeRepository,
        private readonly OTPConfig $otpConfig,
    ) {
        if (! $this->otpConfig->isEnabled) {
            throw TechOTPException::otpDisabled();
        }
    }

    public function check(Identifier $identifier, string $code): bool
    {
        /** @var TemporaryCode $tempCode */
        $tempCode = $this->temporaryCodeRepository->findActiveCode($identifier, $code);

        if ($tempCode === null) {
            return false;
        }

        // Expire code after use.
        $tempCode->expire()->save();

        return true;
    }

    /**
     * @throws OTPException
     */
    public function validateOrFail(Identifier $identifier, string $code): void
    {
        if ($this->check($identifier, $code)) {
            return;
        }

        throw OTPException::incorrect();
    }

    public function send(
        Identifier $identifier,
        ?User $user = null,
        ?string $signature = null
    ): OTPResponse {
        return new OTPResponse();
    }
}
