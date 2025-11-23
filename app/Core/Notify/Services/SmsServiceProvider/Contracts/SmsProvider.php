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

interface SmsProvider
{
    public function send(string $phoneNumber, string $message): void;

    public function bulk(array $phoneNumbers, string $message): void;

    public function verify(string $phoneNumber, array $tokens, int|string $identifier): void;

    public function getCredit(): ?int;

    public function latestUnseenReceived(int $count): array;

    public function latestReceived(int $count): array;
}
