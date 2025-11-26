<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\OTPService;

use App\Core\Notify\Services\SmsServiceProvider\SmsServiceProvider;
use App\Core\Organization\Entities\TemporaryCode;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Repositories\TemporaryCodeRepository;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use App\Core\Organization\Services\OTPService\Contracts\OTPGenerator;
use App\Core\Organization\Services\OTPService\Exceptions\OTPException;
use App\Core\Organization\Services\OTPService\Exceptions\TechOTPException;

class OTPService
{
    public function __construct(
        private readonly OTPGenerator $otpGenerator,
        private readonly TemporaryCodeRepository $temporaryCodeRepository,
        private readonly OTPConfig $otpConfig,
        private readonly SmsServiceProvider $smsServiceProvider,
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
        $tempCode = $this->temporaryCodeRepository
            ->updateOrCreateFor(
                identifier: $identifier,
                newCode: $this->otpGenerator->generate(),
                expiresAt: now()->addMinutes($this->otpConfig->expiresInMinutes),
                userId: $user?->id
            );
        $this->smsServiceProvider->send($identifier->value, $tempCode->code);

        return new OTPResponse($identifier->value, 'sms');
    }
}
