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

namespace App\Core\Notify\Services\SmsServiceProvider\SmsApiProviders;

use App\Core\Notify\Services\SmsServiceProvider\Contracts\SmsApiInterface;
use Cryptommer\Smsir\Classes\Smsir as BaseSmsIr;
use Cryptommer\Smsir\Exceptions\HttpException;
use GuzzleHttp\Exception\GuzzleException;

class SmsIrApi implements SmsApiInterface
{
    private BaseSmsIr $api;

    public function __construct(string $apiKey, string $senderNumber)
    {
        $this->api = new BaseSmsIr($senderNumber, $apiKey);
    }

    public function send(string $message, string $phoneNumber): void
    {
        $this->api
            ->Send()
            ->Bulk($message, [$phoneNumber])
        ;
    }

    public function bulk(string $message, array $phoneNumbers): void
    {
        $this->api
            ->Send()
            ->Bulk($message, $phoneNumbers)
        ;
    }

    public function verify(string $phoneNumber, int|string $identifier, array $tokens): void
    {
        $this->api
            ->Send()
            ->Verify($phoneNumber, (int) $identifier, $tokens)
        ;
    }

    public function getCredit(): int
    {
        return $this->api
            ->Setting()
            ->getCredit()
            ?->Data ?? 0
        ;
    }

    /**
     * @throws HttpException
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getLatestReceived(int $count): array
    {
        return $this->api
            ->Report()
            ->TodayReceived($count)
            ->Data ?? []
        ;
    }

    /**
     * @throws HttpException
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getLatestUnseenReceived(int $count): array
    {
        return $this->api
            ->Report()
            ->LatestReceived($count)
            ->Data ?? [];
    }
}
