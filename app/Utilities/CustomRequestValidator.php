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

namespace App\Utilities;

use App\Services\MetadataMappers\MapEntityMetadata;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CustomRequestValidator
{
    public static function registerFullNameValidation(): void
    {
        Validator::extend(
            'fullName',
            static function ($attribute, $value) {
                try {
                    $fullName = (string) $value;
                } catch (Throwable $exception) {
                    return false;
                }

                return CustomValidator::isFullNameValid($fullName);
            },
            'نام وارد شده معتبر نمی باشد'
        );
    }

    public static function registerMobileValidation(): void
    {
        Validator::extend(
            'mobile',
            static function ($attribute, $value) {
                try {
                    $mobile = StringUtility::transformMobile((string) $value);
                } catch (Throwable $exception) {
                    return false;
                }

                return CustomValidator::isMobileValid($mobile);
            },
            'شماره موبایل وارد شده معتبر نمی باشد'
        );
    }

    public static function registerTableNameValidator(): void
    {
        Validator::extend(
            'tableName',
            static function ($attribute, $value) {
                return in_array($value, MapEntityMetadata::allTableName());
            },
            'اطلاعات وارد شده صحیح نمی باشد'
        );
    }

    public static function registerEntityValidator(): void
    {
        Validator::extend(
            'entity',
            static function ($attribute, $value) {
                if (! str_contains($value, ',')) {
                    return false;
                }

                [$tableName, $id] = explode(',', $value);

                if (! is_numeric($id) || ! in_array($tableName, MapEntityMetadata::allTableName())) {
                    return false;
                }

                return null !== MapEntityMetadata::getEntity($tableName, $id);
            },
            'اطلاعات وارد شده صحیح نمی باشد'
        );
    }
}
