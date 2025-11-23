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

namespace App\Core\Notify\Services\SmsServiceProvider\Contracts;

interface SmsApiInterface
{
    public function send(string $message, string $phoneNumber): void;

    public function bulk(string $message, array $phoneNumbers): void;

    public function verify(string $phoneNumber, int|string $identifier, array $tokens): void;

    public function getCredit(): int;

    public function getLatestReceived(int $count): array;

    public function getLatestUnseenReceived(int $count): array;
}
