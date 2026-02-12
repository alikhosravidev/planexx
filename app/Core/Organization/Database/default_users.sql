INSERT INTO `core_org_users`
    (`id`, `direct_manager_id`, `job_position_id`, `full_name`, `first_name`, `last_name`, `mobile`, `user_type`, `customer_type`, `email`, `national_code`, `password`, `gender`, `is_active`, `birth_date`, `mobile_verified_at`, `email_verified_at`, `last_login_at`, `employee_code`, `employment_date`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(
    1, NULL, NULL, 'علی خسروی', 'علی', 'خسروی', '09398561672', 2, NULL, 'alikhosravi175@gmail.com', NULL, NULL, NULL, 1, NULL, NOW(), NOW(), NULL, NULL, NULL, NOW(), NOW(), NULL
),
(
    2, NULL, NULL, 'لقمان آوند', 'لقمان', 'آوند', '09129619077', 2, NULL, 'god@loghman.solutions', NULL, NULL, 1, 1, NULL, NOW(), NOW(), NULL, NULL, NULL, NOW(), NOW(), NULL
);

INSERT INTO `core_org_entity_has_roles` (`role_id`, `model_type`, `model_id`) VALUES ('1', 'core_org_users', '2'), ('1', 'core_org_users', '1');
