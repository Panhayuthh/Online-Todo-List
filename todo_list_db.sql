SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todo_list_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int NOT NULL,
  `todo_list_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `due_date` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `priority` varchar(50) NOT NULL,
  `createAt` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `todo_list_id`, `name`, `description`, `due_date`, `status`, `priority`, `createAt`) VALUES
(3, 1, 'sleep', '', '2024-10-23 00:00:00', 'Completed', 'High', '2024-10-20 05:10:14'),
(4, 1, 'exam', '', '2024-10-24 00:00:00', 'Completed', 'Low', '2024-10-20 05:11:25'),
(6, 2, 'Morning Run', 'Run 5km in the park', '2024-10-23 00:00:00', 'In Progress', 'medium', '2024-10-21 08:19:21'),
(7, 3, 'Finish Coding', 'Complete module implementation', '2024-10-25 00:00:00', 'In Progress', 'high', '2024-10-21 08:19:21'),
(8, 4, 'Read Novel', 'Read 3 chapters of the new novel', '2024-10-24 00:00:00', 'In Progress', 'low', '2024-10-21 08:19:21'),
(11, 2, 'Gym Workout', 'Attend the evening fitness class', '2024-10-31 00:00:00', 'in progress', 'high', '2024-10-21 10:08:35'),
(12, 3, 'Project Meeting', 'Prepare for the team meeting', '2024-10-28 00:00:00', 'Not Started', 'High', '2024-10-21 10:08:35'),
(13, 4, 'Update Resume', 'Revise and update CV for job applications', '2024-10-29 00:00:00', 'in progress', 'medium', '2024-10-21 10:08:35'),
(15, 6, 'Write Blog Post', 'Draft the new blog article', '2024-10-27 00:00:00', 'in progress', 'medium', '2024-10-21 10:08:36'),
(17, 1, 'Buy Groceries', 'Purchase groceries for the week', '2024-10-28 00:00:00', 'Completed', 'Low', '2024-10-21 10:09:16'),
(19, 2, 'Plan Weekend Trip', 'Organize logistics for the weekend getaway', '2024-11-01 00:00:00', 'In Progress', 'high', '2024-10-21 10:09:16'),
(20, 2, 'Start New Book', 'Begin reading the latest bestseller', '2024-10-29 00:00:00', 'In Progress', 'low', '2024-10-21 10:09:16'),
(21, 3, 'Fix Bugs', 'Address bugs found during testing', '2024-10-27 00:00:00', 'In Progress', 'high', '2024-10-21 10:09:16'),
(22, 3, 'Write Documentation', 'Document the new module for users', '2024-10-31 00:00:00', 'In Progress', 'medium', '2024-10-21 10:09:16'),
(23, 4, 'Attend Webinar', 'Participate in the online webinar', '2024-11-02 00:00:00', 'In Progress', 'medium', '2024-10-21 10:09:16'),
(24, 4, 'Update Resume', 'Revise and update resume for job applications', '2024-11-05 00:00:00', 'In Progress', 'high', '2024-10-21 10:09:16'),
(28, 2, 'buy banana', 'eat banana', '2024-10-24 00:00:00', 'Not Started', 'Low', '2024-10-23 02:45:39');
-- --------------------------------------------------------

--
-- Table structure for table `to_do_list`
--

CREATE TABLE `to_do_list` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `to_do_list`
--

INSERT INTO `to_do_list` (`id`, `user_id`, `title`) VALUES
(1, 3, 'Personal'),
(2, 3, 'Grocery Shopping'),
(3, 3, 'Workout Plan'),
(4, 3, 'Side Quest'),
(6, 3, 'House Cleaning'),
(8, 3, 'Gym');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `profile_picture`) VALUES
(3, 'yuth', 'panhayuth@gmail.com', '$2y$10$jA.m5wxD63/ipEXN6yGdN.Eui0qmLla9w7m6t/RF8ROxHXdxhnDG.', '122-1224045_monkey-with-space-helmet-png-download-monkey-with.png');

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todo_list_id` (`todo_list_id`);

--
-- Indexes for table `to_do_list`
--
ALTER TABLE `to_do_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `to_do_list`
--
ALTER TABLE `to_do_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`todo_list_id`) REFERENCES `to_do_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `to_do_list`
--
ALTER TABLE `to_do_list`
  ADD CONSTRAINT `to_do_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
