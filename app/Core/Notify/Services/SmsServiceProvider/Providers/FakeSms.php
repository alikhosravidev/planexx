<?php

declare(strict_types=1);

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

use App\Core\Notify\Services\SmsServiceProvider\Contracts\SmsProvider;
use App\Core\Notify\Services\SmsServiceProvider\DTOs\ReceivedSmsDTO;
use Psr\Log\LoggerInterface;

class FakeSms implements SmsProvider
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function send(string $phoneNumber, string $message): void
    {
        $this->logger->channel('fake_sms')->info($message, compact('phoneNumber'));
    }

    public function bulk(array $phoneNumbers, string $message): void
    {
        foreach ($phoneNumbers as $phoneNumber) {
            $this->send($phoneNumber, $message);
        }
    }

    public function verify(string $phoneNumber, array $tokens, int|string $identifier): void
    {
        $this->logger->channel('fake_sms')->info(
            'Template message:',
            compact('phoneNumber', 'tokens', 'identifier')
        );
    }

    public function getCredit(): int
    {
        return random_int(100000, 9999999);
    }

    /**
     * @return array<ReceivedSmsDTO>
     */
    public function latestUnseenReceived(int $count): array
    {
        return [];
    }

    /**
     * @return array<ReceivedSmsDTO>
     */
    public function latestReceived(int $count): array
    {
        return [];
    }
}
