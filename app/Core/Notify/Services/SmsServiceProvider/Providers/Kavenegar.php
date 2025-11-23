<?php

declare(strict_types = 1);

/*
 * This file is part of the LSP API and Panels projects
 *
 * Copyright (c) >= 2023 LSP
 *
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * Please follow OOP, SOLID and linux philosophy in development and becarefull about anti-patterns
 *
 * @CTO Mehrdad Dadkhah <dadkhah.ir@gmail.com>
 */

namespace App\Core\Notify\Services\SmsServiceProvider\Providers;

use App\Core\Notify\Services\SmsServiceProvider\Contracts\SmsApiInterface;
use App\Core\Notify\Services\SmsServiceProvider\Contracts\SmsDataNormalizer;
use App\Core\Notify\Services\SmsServiceProvider\Contracts\SmsProvider;
use App\Core\Notify\Services\SmsServiceProvider\Contracts\TokenNormalizer;
use App\Core\Notify\Services\SmsServiceProvider\DTOs\ReceivedSmsDTO;

class Kavenegar implements SmsProvider
{
    public function __construct(
        private readonly SmsApiInterface $api,
        private readonly TokenNormalizer $tokenNormalizer,
        private readonly SmsDataNormalizer $smsDataNormalizer,
    ) {
    }

    public function send(string $phoneNumber, string $message): void
    {
        $this->api->send($message, $phoneNumber);
    }

    public function bulk(array $phoneNumbers, string $message): void
    {
        $this->api->bulk($message, $phoneNumbers);
    }

    public function verify(string $phoneNumber, array $tokens, int|string $identifier): void
    {
        $this->api->verify(
            $phoneNumber,
            $identifier,
            $this->tokenNormalizer->normalize($tokens)
        );
    }

    public function getCredit(): ?int
    {
        return $this->api->getCredit();
    }

    /**
     * @return array<ReceivedSmsDTO>
     */
    public function latestUnseenReceived(int $count): array
    {
        return $this->smsDataNormalizer->normalize(
            $this->api->getLatestUnseenReceived($count),
        );
    }

    /**
     * @return array<ReceivedSmsDTO>
     */
    public function latestReceived(int $count): array
    {
        return $this->smsDataNormalizer->normalize(
            $this->api->getLatestReceived($count),
        );
    }
}
