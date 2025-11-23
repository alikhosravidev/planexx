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

namespace App\Core\Notify\Services\SmsServiceProvider\SmsApiProviders;

use App\Core\Notify\Services\SmsServiceProvider\Contracts\SmsApiInterface;
use Kavenegar\KavenegarApi as BaseKavenegarApi;

class KavenegarApi implements SmsApiInterface
{
    private BaseKavenegarApi $api;

    public function __construct(
        string $apiKey,
        private readonly string $senderNumber,
    ) {
        $this->api = new BaseKavenegarApi($apiKey);
    }

    public function send(string $message, string $phoneNumber): void
    {
        $this->api->Send($this->senderNumber, $phoneNumber, $message);
    }

    public function bulk(string $message, array $phoneNumbers): void
    {
        $this->api->SendArray($this->senderNumber, $phoneNumbers, $message);
    }

    public function verify(string $phoneNumber, int|string $identifier, array $tokens): void
    {
        $this->api->VerifyLookup(
            $phoneNumber,
            $tokens[0] ?? null,
            $tokens[1] ?? null,
            $tokens[2] ?? null,
            $identifier,
        );
    }

    public function getCredit(): int
    {
        return $this->api
            ->AccountInfo()
            ?->remaincredit ?? 0
        ;
    }

    public function getLatestUnseenReceived(int $count): array
    {
        $data = $this->api
            ->Receive(
                linenumber: $this->senderNumber,
                isread: 0
            ) ?? []
        ;

        return \array_slice($data, 0, $count);
    }

    public function getLatestReceived(int $count): array
    {
        $data = $this->api
            ->Receive(
                linenumber: $this->senderNumber,
                isread: 1
            ) ?? []
        ;

        return \array_slice($data, 0, $count);
    }
}
