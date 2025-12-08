INSERT INTO `core_org_departments` (`id`, `parent_id`, `name`, `code`, `manager_id`, `color`, `icon`, `description`, `type`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'فروش', '098', 4, 'pink-500', 'fa-sack-dollar', NULL, 3, 1, '2025-12-03 12:13:49', '2025-12-06 11:00:01', '2025-12-06 11:00:01'),
(2, NULL, 'هولدینگ', 'holding', 2, 'blue-500', 'fa-building', 'هولدینگ', 1, 1, '2025-12-06 11:01:24', '2025-12-06 11:01:24', NULL),
(3, 2, '۱۸ عیار', '18ayar', 2, 'blue-500', 'fa-building', 'برند ۱۸ عیار', 2, 1, '2025-12-06 11:09:06', '2025-12-06 11:09:06', NULL),
(4, 2, 'شعبه ستاره (شیراز)', 'setare_shiraz', 2, 'lime-500', 'fa-certificate', 'شعبه', 3, 1, '2025-12-06 11:11:45', '2025-12-06 11:11:45', NULL),
(5, 2, 'سروریان', 'soroorian', 2, 'blue-500', 'fa-building', 'برند', 2, 1, '2025-12-06 11:14:33', '2025-12-06 11:14:34', NULL),
(6, 2, 'ایران مال (تهران)', 's_iranmall', 2, 'blue-700', 'fa-user-tie', 'شعبه', 3, 1, '2025-12-06 11:18:10', '2025-12-06 11:18:10', NULL),
(7, 2, 'مالی', 'maali', 11, 'blue-500', 'fa-building', 'مالی', 2, 1, '2025-12-07 06:19:24', '2025-12-07 19:21:52', NULL),
(8, 2, 'نبات بهار', 'Nabat-bahar', 8, 'orange-500', 'fa-sun', 'فروشگاه های نبات', 3, 1, '2025-12-07 13:32:31', '2025-12-07 13:33:34', NULL),
(9, 2, 'نبات تهرانپارس', 'nabat-tehranpars', 8, 'blue-500', 'fa-building', 'فروشگاه نبات تهرانپارس', 3, 1, '2025-12-07 13:34:22', '2025-12-07 13:34:22', NULL);

INSERT INTO `core_org_user_departments` (`id`, `user_id`, `department_id`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 5, 2, 1, '2025-12-06 11:23:25', '2025-12-07 11:31:39'),
(2, 6, 2, 1, '2025-12-06 11:26:52', '2025-12-07 06:02:54'),
(3, 9, 2, 1, '2025-12-07 06:06:04', '2025-12-07 06:06:04'),
(5, 13, 7, 1, '2025-12-07 06:20:20', '2025-12-07 06:20:20'),
(6, 12, 7, 1, '2025-12-07 06:20:28', '2025-12-07 06:20:28'),
(7, 11, 7, 1, '2025-12-07 06:20:49', '2025-12-07 06:20:49'),
(8, 14, 7, 1, '2025-12-07 06:31:14', '2025-12-07 06:31:14'),
(9, 16, 6, 1, '2025-12-07 11:10:12', '2025-12-07 11:10:12'),
(10, 17, 6, 1, '2025-12-07 11:11:36', '2025-12-07 11:11:36'),
(11, 18, 6, 1, '2025-12-07 11:13:27', '2025-12-07 11:13:27');

INSERT INTO `core_file_files` (`id`, `uuid`, `entity_type`, `entity_id`, `original_name`, `file_name`, `file_path`, `file_url`, `disk`, `title`, `mime_type`, `extension`, `file_size`, `file_hash`, `file_type`, `collection`, `is_temporary`, `expires_at`, `width`, `height`, `aspect_ratio`, `duration`, `resolution`, `frame_rate`, `uploaded_by`, `folder_id`, `module_name`, `is_public`, `download_count`, `view_count`, `last_accessed_at`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'f3d195e0-21f2-4b5a-8836-6931497a203e', 'core_org_departments', 2, 'logo.png', '58fdc24d-b623-4685-94b9-427372d2653b.png', 'modules/organization/2025/12/58fdc24d-b623-4685-94b9-427372d2653b.png', 'https://c146377.parspack.net/modules/organization/2025/12/58fdc24d-b623-4685-94b9-427372d2653b.png', 's3', 'هولدینگ Image', 'image/png', 'png', 172903, 'c04c7041779618e8a3d6944847c3d0c664b5045c18b3aa5d88a1414dc48bb17e', 0, 3, 0, NULL, 500, 500, '1', NULL, NULL, NULL, 2, NULL, 'organization', 1, 0, 0, NULL, 1, '2025-12-06 11:01:24', '2025-12-06 11:01:24', NULL),
(5, '057c3e3c-b59f-4cfb-9e03-667bb9f6afd0', 'core_org_departments', 3, 'logo gold.png', '67f7a57f-0529-49f5-8887-a0fe4d92bdf9.png', 'modules/organization/2025/12/67f7a57f-0529-49f5-8887-a0fe4d92bdf9.png', 'https://c146377.parspack.net/modules/organization/2025/12/67f7a57f-0529-49f5-8887-a0fe4d92bdf9.png', 's3', '۱۸ عیار Image', 'image/png', 'png', 288347, 'a4457c6a3407795b764671dd48382a1a237d1c89b9ae17a0f8e6a2e4a96996b8', 0, 3, 0, NULL, 600, 600, '1', NULL, NULL, NULL, 2, NULL, 'organization', 1, 0, 0, NULL, 1, '2025-12-06 11:09:06', '2025-12-06 11:09:06', NULL),
(6, '297dd5c0-ff05-4eb7-8342-2daa4d15bf1c', 'core_org_departments', 5, 'logo gold.png', 'bddf6716-5529-42ca-9329-817fb0a4a094.png', 'modules/organization/2025/12/bddf6716-5529-42ca-9329-817fb0a4a094.png', 'https://c146377.parspack.net/modules/organization/2025/12/bddf6716-5529-42ca-9329-817fb0a4a094.png', 's3', 'سروریان Image', 'image/png', 'png', 288347, 'a4457c6a3407795b764671dd48382a1a237d1c89b9ae17a0f8e6a2e4a96996b8', 0, 3, 0, NULL, 600, 600, '1', NULL, NULL, NULL, 2, NULL, 'organization', 1, 0, 0, NULL, 1, '2025-12-06 11:14:34', '2025-12-06 11:14:34', NULL),
(7, '62723006-556f-4228-b71b-e7adf1dd64b6', 'core_org_departments', 7, 'IMG_20251206_121003_841.jpg', '16e6bcb7-7fec-4057-97f1-af4aa196147a.jpg', 'modules/organization/2025/12/16e6bcb7-7fec-4057-97f1-af4aa196147a.jpg', 'https://c146377.parspack.net/modules/organization/2025/12/16e6bcb7-7fec-4057-97f1-af4aa196147a.jpg', 's3', 'مالی Image', 'image/jpeg', 'jpg', 439535, '0ac2f06b974377d74e7d4df04bee88785f113fb7f67259677117b891cb8ce83f', 0, 3, 0, NULL, 1153, 1280, '0.9', NULL, NULL, NULL, 6, NULL, 'organization', 1, 0, 0, NULL, 1, '2025-12-07 19:21:52', '2025-12-07 19:21:52', NULL);
