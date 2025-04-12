
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `image_path` varchar(300) NOT NULL,
  `role` varchar(50) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `users` (`id`, `username`, `password`, `email`, `image_path`, `role`) VALUES
(5, 'admin1', '$2y$10$yMWtwcBq9huoAIHWNwJSpOFl6QT.GFZpaWVgezB0ZpyjqS3TS8Xk6', 'admin@lost.com', '', 'admin'),
(8, 'caren', '$2y$10$StPJtlL72/Dyyw6u.tr3f.YXhTd65ei.VYs7tl7g/zJu0Li5cbgsq', 'carenmaedigo@yahoo.com', '', 'user'),
(9, 'carl', '$2y$10$KxSuZKgZg0YHJz00o179uOg81EdzyhmUCMcp6j7O2vFtK93GnTaYO', 'carldatan@gmail.com', '', 'admin'),
(10, 'jian', '$2y$10$f3tY9jX72eJFgU3KQJ0NoemRww27GlxrqLNQzbiyWI9H0YeR6bZZO', 'jianmodrigo@gmail.com', '', 'user');


ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;


