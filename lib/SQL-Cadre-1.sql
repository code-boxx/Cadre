-- (A) SETTINGS
CREATE TABLE `settings` (
  `setting_name` varchar(255) NOT NULL,
  `setting_description` varchar(255) DEFAULT NULL,
  `setting_value` varchar(255) NOT NULL,
  `setting_group` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_name`),
  ADD KEY `setting_group` (`setting_group`);

INSERT INTO `settings` (`setting_name`, `setting_description`, `setting_value`, `setting_group`) VALUES
('APP_VER', 'App version', '1', 0),
('EMAIL_FROM', 'System email from', 'sys@site.com', 1),
('LEAVE_DAYS', 'Leave half or full days code', '{\"1\":\"Full Day\",\"A\":\"AM\",\"P\":\"PM\"}', 0),
('LEAVE_STATUS', 'Leave status codes', '{\"P\":\"Pending\",\"A\":\"Approved\",\"D\":\"Declined\"}', 0),
('LEAVE_TYPES', 'Types of leave', '{\"M\":\"Medical\",\"P\":\"Paid\",\"U\":\"Unpaid\"}', 0),
('PAGE_PER', 'Number of entries per page', '20', 1),
('USR_LEVELS', 'User access levels', '{\"A\":\"Admin\",\"U\":\"User\",\"S\":\"Suspended\"}', 0);

-- (B) USERS
CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_title` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_level` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `user_name` (`user_name`),
  ADD KEY `user_level` (`user_level`);

ALTER TABLE `users`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- (C) FORGOT PASSWORD
CREATE TABLE `password_reset` (
  `user_id` bigint(20) NOT NULL,
  `reset_hash` varchar(64) NOT NULL,
  `reset_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`user_id`);

-- (D) HOLIDAYS
CREATE TABLE `holidays` (
  `holiday_id` bigint(20) NOT NULL,
  `holiday_name` varchar(255) NOT NULL,
  `holiday_date` date NOT NULL,
  `holiday_half` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `holidays`
  ADD PRIMARY KEY (`holiday_id`),
  ADD KEY `holiday_date` (`holiday_date`);

ALTER TABLE `holidays`
  MODIFY `holiday_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- (E) ENTITLED LEAVE
CREATE TABLE `leave_entitled` (
  `user_id` bigint(20) NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `leave_days` decimal(4,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `leave_entitled`
  ADD PRIMARY KEY (`user_id`,`leave_type`) USING BTREE;

-- (F) LEAVE TAKEN
CREATE TABLE `leave_taken` (
  `leave_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `leave_from` date NOT NULL,
  `leave_to` date NOT NULL,
  `leave_days` decimal(4,1) NOT NULL,
  `leave_status` varchar(1) NOT NULL DEFAULT 'P'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `leave_taken`
  ADD PRIMARY KEY (`leave_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `leave_type` (`leave_type`),
  ADD KEY `leave_from` (`leave_from`),
  ADD KEY `leave_to` (`leave_to`),
  ADD KEY `leave_status` (`leave_status`);

ALTER TABLE `leave_taken`
  MODIFY `leave_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- (G) LEAVE TAKEN DAYS
CREATE TABLE `leave_taken_days` (
  `leave_id` bigint(20) NOT NULL,
  `leave_day` date NOT NULL,
  `leave_half` varchar(1) NOT NULL DEFAULT 'F'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `leave_taken_days`
  ADD PRIMARY KEY (`leave_id`,`leave_day`);