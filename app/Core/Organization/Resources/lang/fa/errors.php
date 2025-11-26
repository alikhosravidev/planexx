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

return [
    // Auth
    'password_incorrect'                => 'رمز عبور وارد شده اشتباه است. اگر آن را فراموش کرده‌اید، از گزینه «فراموشی رمز عبور» استفاده کنید.',
    'concurrent_login_limit'            => 'شما به حداکثر تعداد ورود هم‌زمان (:loginLimitationCount دستگاه) رسیده‌اید. برای ورود، ابتدا از برخی دستگاه‌های دیگر خارج شوید.',
    'session_expired'                   => 'لطفاً مجدداً وارد حساب کاربری خود شوید',
    'user_not_registered'               => 'کاربری با این مشخصات یافت نشد. اگر هنوز عضو نشده‌اید، لطفاً ابتدا ثبت‌نام کنید.',
    'logout_not_possible'               => 'شما وارد حسابی نیستید که بخواهید خارج شوید.',
    'registration_data_invalid'         => 'اطلاعات وارد شده برای ثبت‌نام کامل یا معتبر نیست. لطفاً دوباره بررسی کنید.',
    'already_registered'                => 'شما قبلاً ثبت‌نام کرده‌اید. لطفاً از بخش ورود، وارد حساب کاربری خود شوید.',
    'password_confirmation_mismatch'    => 'رمز عبور جدید و تکرار آن یکسان نیستند، مجدد وارد کنید.',
    'no_valid_channel'                  => 'برای دریافت کد تأیید، لطفاً مطمئن شوید که ایمیل یا شماره موبایل معتبری در حساب شما ثبت شده باشد.',
    'password_reset_invalid_identifier' => 'برای بازیابی رمز عبور، لطفاً ایمیل یا شماره موبایل خود را وارد کنید.',
    'invalid_auth_state'                => 'اطلاعات ارسال شده برای ورود ناقص یا نامعتبر است. لطفاً فرم را با دقت تکمیل کرده و دوباره تلاش کنید.',

    // OTP
    'invalid_otp_code'       => 'کد یکبارمصرفی که وارد کردید اشتباه است. کد جدید گرفته و مجدد تلاش کنید.',
    'otp_sending_failed'     => 'در حال حاضر امکان ارسال کد وجود ندارد. لطفاً بعداً تلاش کنید یا با پشتیبانی تماس بگیرید.',
    'otp_system_disabled'    => 'سیستم OTP غیرفعال می باشد.',
    'otp_identifier_invalid' => 'کد تایید فقط به ایمیل یا شماره تماس ارسال می‌شود. لطفاً یکی از این دو را وارد کنید.',
    'credentials_invalid'    => 'نام کاربری یا پسورد شما اشتباه است، لطفا مجددا تلاش کنید.',

    // Validations
    'username_format_invalid' => 'نام کاربری معتبر نیست. (باید با حرف انگلیسی شروع شود و فقط شامل حروف، اعداد و "_" باشد)',
    'mobile_invalid'          => 'شماره موبایل وارد شده معتبر نمی باشد.',
    'email_invalid'           => 'ایمیل وارد شده معتبر نمی باشد.',
    'identifier_required'     => 'وارد کردن نام کاربری، ایمیل یا موبایل الزامی می باشد.',
    'verification_failed'     => 'فرآیند تایید حساب انجام نشد، لطفا مجدد تلاش کنید.',
    'duplicated_mobile'       => 'این شماره موبایل قبلاً استفاده شده است.',
];
