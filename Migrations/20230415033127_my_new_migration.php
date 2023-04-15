<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MyNewMigration extends AbstractMigration
{
    public function change(): void
    {
        $this->execute("CREATE TABLE `exercises` (
                `id` int(11) NOT NULL,
                `user` int(11) DEFAULT 0,
                `name` varchar(255) DEFAULT NULL,
                `image` varchar(255) DEFAULT NULL,
                `created_at` varchar(255) DEFAULT NULL,
                `updated_at` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;");

        $this->execute("INSERT INTO `exercises` (`id`, `user`, `name`, `image`, `created_at`, `updated_at`) VALUES
            (1, 1, 'Squats', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (2, 1, 'Jumping Jacks', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (3, 1, 'Push-Ups', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (4, 1, 'Burpees', '', '2023-04-07 19:54:21', '2023-04-09 03:36:47'),
            (5, 1, 'Front Kicks', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (6, 1, 'Lunges', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (7, 1, 'Step Ups', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (8, 1, 'Punches', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (9, 1, 'Knee Lifts', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (10, 1, 'Jump Squats', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (11, 1, 'Barstool Kicks', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (12, 1, 'Punch Routine #4', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (13, 1, 'Tire Slides', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (14, 1, 'Duck and Punch', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (15, 1, 'High Knee Jog in Place', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (16, 1, 'Arm Curls', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (17, 1, 'Arm Raises', '', '2023-04-07 19:54:21', '2023-04-09 03:37:23'),
            (18, 1, '45 Degree Lateral Raise', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (19, 1, 'Calf Raises', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (20, 1, 'Step Ups', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (21, 1, 'Mountain Climbers', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (22, 1, 'Plank', '', '2023-04-07 19:54:21', '2023-04-07 19:54:21'),
            (23, 1, 'Toe Touches', '', '2023-04-09 03:36:00', '2023-04-09 03:36:00'),
            (24, 2, 'Burpees In Place', '', '2023-04-09 03:39:45', '2023-04-09 03:39:45'),
            (25, 2, 'Arm Raises To Do For Some', '', '2023-04-09 03:41:04', '2023-04-09 03:48:28'),
            (26, 2, 'Arm Raises To Do For Me', '', '2023-04-09 03:41:11', '2023-04-09 03:41:11'),
            (27, 2, 'Arm Raises To Do For Me And You', '', '2023-04-09 03:41:40', '2023-04-09 03:41:40'),
            (28, 2, 'Arm Raises To Do For Me And', '', '2023-04-09 03:43:03', '2023-04-09 03:43:03'),
            (29, 2, 'Mountain Climbers Today', '', '2023-04-09 03:45:44', '2023-04-09 03:45:44'),
            (30, 2, 'Jumping Jacks', '', '2023-04-09 03:48:52', '2023-04-09 03:49:05'),
            (31, 2, 'Squats For Me', '', '2023-04-09 17:37:11', '2023-04-09 17:37:11'),
            (32, 4, 'KNEE TOUCHES', '', '2023-04-10 16:46:00', '2023-04-10 16:46:22'),
            (33, 4, 'JUMP HIGH ON UP', '', '2023-04-10 16:46:02', '2023-04-10 16:46:15'),
            (34, 4, 'JUMP TO THE STARTS', '', '2023-04-10 16:46:03', '2023-04-13 07:25:28'),
            (35, 4, 'METODDLOWMEDIACOM', '', '2023-04-11 09:48:25', '2023-04-13 07:26:33'),
            (37, 4, 'TESTING', '', '2023-04-12 04:34:41', '2023-04-12 04:34:41'),
            (38, 7, 'TIRE SL', '', '2023-04-12 12:14:29', '2023-04-12 12:14:29'),
            (39, 7, 'FRONT KICKS', '', '2023-04-12 12:14:35', '2023-04-12 12:14:35'),
            (40, 4, 'METODDLOWMEDIACOM', '', '2023-04-13 07:27:00', '2023-04-13 07:27:00'),
            (41, 4, 'METODDLOWMEDIACOM', '', '2023-04-13 07:27:25', '2023-04-13 07:27:25'),
            (42, 3, 'PLANK HIGH FIVES ARE GREAT', '', '2023-04-13 13:06:28', '2023-04-13 17:13:10'),
            (43, 3, 'ROUNDHOUSE KICKS', '', '2023-04-13 13:06:50', '2023-04-13 16:51:48'),
            (44, 3, 'ARM CURLS', '', '2023-04-13 17:06:57', '2023-04-13 17:06:57'),
            (45, 3, 'HIGH KNEE', '', '2023-04-13 17:08:12', '2023-04-13 17:13:15'),
            (46, 3, 'STEP UPS', '', '2023-04-13 17:12:55', '2023-04-13 17:12:55'),
            (47, 3, 'MOUNTAIN CLIMBERS', '', '2023-04-13 17:13:00', '2023-04-13 17:13:00'),
            (48, 3, '45 DEGREE LATERAL RAISE', '', '2023-04-13 17:24:53', '2023-04-13 17:24:53'),
            (49, 8, 'JOG IN PLACE', '', '2023-04-13 22:55:33', '2023-04-13 22:55:33');");

        $this->execute("CREATE TABLE `favorites` (
              `id` int(11) NOT NULL,
              `user` int(11) DEFAULT 0,
              `routine` int(11) DEFAULT 0,
              `created_at` varchar(255) DEFAULT NULL,
              `updated_at` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;");

        $this->execute("CREATE TABLE `routines` (
              `id` int(11) NOT NULL,
              `user` int(11) DEFAULT 0,
              `name` varchar(255) DEFAULT NULL,
              `sets` int(4) NOT NULL DEFAULT 8,
              `sets_time` int(4) NOT NULL DEFAULT 20,
              `break_time` int(4) NOT NULL DEFAULT 10,
              `created_at` varchar(255) DEFAULT NULL,
              `updated_at` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;");

        $this->execute("CREATE TABLE `routine_exercises` (
              `id` int(11) NOT NULL,
              `user` int(11) NOT NULL DEFAULT 0,
              `routine` int(11) NOT NULL,
              `exercise` int(11) DEFAULT NULL,
              `created_at` varchar(255) DEFAULT NULL,
              `updated_at` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;");

        $this->execute("CREATE TABLE `users` (
              `id` int(11) NOT NULL,
              `name` varchar(255) DEFAULT NULL,
              `email` varchar(255) DEFAULT NULL,
              `password` varchar(255) NOT NULL,
              `hash` varchar(255) NOT NULL,
              `dark_mode` int(1) NOT NULL DEFAULT 1,
              `sets` int(11) NOT NULL DEFAULT 8,
              `set_time` int(11) NOT NULL DEFAULT 20,
              `break_time` int(11) NOT NULL DEFAULT 10,
              `created_at` varchar(255) DEFAULT NULL,
              `updated_at` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;");

        $this->execute("ALTER TABLE `exercises` ADD PRIMARY KEY (`id`);");
        $this->execute("ALTER TABLE `favorites` ADD PRIMARY KEY (`id`);");
        $this->execute("ALTER TABLE `routines` ADD PRIMARY KEY (`id`);");
        $this->execute("ALTER TABLE `routine_exercises` ADD PRIMARY KEY (`id`), ADD KEY `routine` (`routine`), ADD KEY `user` (`user`);");
        $this->execute("ALTER TABLE `users` ADD PRIMARY KEY (`id`);");
    }
}
