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

namespace App\Core\Notify\Services\SmsServiceProvider\Utilities;

use App\Core\Notify\Services\SmsServiceProvider\Contracts\SmsProvider;
use App\Core\Notify\Services\SmsServiceProvider\Providers\FakeSms;
use App\Core\Notify\Services\SmsServiceProvider\Providers\Kavenegar;
use App\Core\Notify\Services\SmsServiceProvider\Providers\SmsIr;
use App\Core\Notify\Services\SmsServiceProvider\SmsApiProviders\KavenegarApi;
use App\Core\Notify\Services\SmsServiceProvider\SmsApiProviders\SmsIrApi;
use App\Core\Notify\Services\SmsServiceProvider\SmsDataNormalizers\KavenegarDataNormalizer;
use App\Core\Notify\Services\SmsServiceProvider\SmsDataNormalizers\SmsIrDataNormalizer;
use App\Core\Notify\Services\SmsServiceProvider\TokenNormalizers\KavenegarTokenNormalizer;
use App\Core\Notify\Services\SmsServiceProvider\TokenNormalizers\SmsIrTokenNormalizer;

class ResolveSmsProvider
{
    public static function resolve(): SmsProvider
    {
        $provider = config('services.sms.provider');

        return match ($provider) {
            'sms.ir'    => self::resolveSmsIr(),
            'kavenegar' => self::resolveKavenegar(),
            'fake_sms'  => resolve(FakeSms::class),
            default     => throw new \Exception('Strategy not found for this SMS provider.'),
        };
    }

    private static function resolveSmsIr(): SmsIr
    {
        return new SmsIr(
            new SmsIrApi(
                config('services.sms.api_key', ''),
                config('services.sms.sender', '')
            ),
            new SmsIrTokenNormalizer(
                config('services.sms.variable_number_limit.sms_ir')
            ),
            new SmsIrDataNormalizer(),
        );
    }

    private static function resolveKavenegar(): Kavenegar
    {
        return new Kavenegar(
            new KavenegarApi(
                config('services.sms.api_key', ''),
                config('services.sms.sender', '')
            ),
            new KavenegarTokenNormalizer(
                config('services.sms.variable_number_limit.kavenegar')
            ),
            new KavenegarDataNormalizer(),
        );
    }
}
