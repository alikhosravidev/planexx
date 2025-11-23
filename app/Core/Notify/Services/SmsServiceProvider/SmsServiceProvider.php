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

namespace App\Core\Notify\Services\SmsServiceProvider;

use App\Core\Notify\Services\SmsServiceProvider\Contracts\SmsProvider;
use App\Core\Notify\Services\SmsServiceProvider\DTOs\ReceivedSmsDTO;

class SmsServiceProvider
{
    public function __construct(
        private readonly SmsProvider $provider
    ) {
    }

    public function send(string $phoneNumber, string $message): self
    {
        $this->provider->send($phoneNumber, $message);

        return $this;
    }

    public function bulk(array $phoneNumbers, string $message): self
    {
        $this->provider->bulk($phoneNumbers, $message);

        return $this;
    }

    public function verify(string $phoneNumber, array $tokens, int|string $identifier): self
    {
        $this->provider->verify($phoneNumber, $tokens, $identifier);

        return $this;
    }

    public function getCredit(): int
    {
        return $this->provider->getCredit();
    }

    /**
     * @return array<ReceivedSmsDTO>
     */
    public function latestUnseenReceived(?int $count = null): array
    {
        return $this->provider
            ->latestUnseenReceived(
                $count ?? config('notification.default_unseen_count')
            )
        ;
    }

    /**
     * @return array<ReceivedSmsDTO>
     */
    public function latestReceived(?int $count = null): array
    {
        return $this->provider
            ->latestReceived(
                $count ?? config('notification.default_unseen_count')
            )
        ;
    }
}
