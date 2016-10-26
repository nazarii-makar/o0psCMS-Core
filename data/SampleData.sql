--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `name`, `abbreviation`) VALUES
(1, 'English', 'en'),
(2, 'Українська', 'ua'),
(3, 'Русский', 'ru'),
(4, 'Français', 'fr'),
(5, 'Deutsch', 'de'),
(6, 'Español', 'es'),
(7, 'Italiano', 'it'),
(8, 'Български', 'bg');

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'guest'),
(2, 'user'),
(3, 'admin');

--
-- Dumping data for table `roles_parents`
--

INSERT INTO `roles_parents` (`role_id`, `parent_id`) VALUES
(2, 1),
(3, 2);

--
-- Dumping data for table `questions`
--

INSERT INTO `question` (`id`, `question`) VALUES
(1, 'What was your childhood phone number?'),
(2, 'In what city did your mother born?'),
(3, 'In what city did your father born?'),
(4, 'In what city or town was your first job?');

--
-- Dumping data for table `questions`
--

INSERT INTO `state` (`id`, `state`) VALUES
(1, 'Disabled'),
(2, 'Enabled');

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`id`, `name`) VALUES
(1, 'all'),
(2, 'Public Resource'),
(3, 'Private Resource'),
(4, 'Admin Resource'),
(5, 'Application\\Controller\\IndexController'),
(6, 'o0psCore\\Controller\\AuthenticationController'),
(7, 'o0psCore\\Controller\\UserController'),
(8, 'o0psCore\\Controller\\AdminController');

--
-- Dumping data for table `privilege`
--

INSERT INTO `privilege` (`id`, `resource_id`, `role_id`, `name`, `permission_allow`) VALUES
(1,  5, 1, 'index', 1),
(2,  6, 1, 'login', 1),
(3,  6, 1, 'signUp', 1),
(4,  6, 1, 'forgotPassword', 1),
(5,  6, 1, 'confirmEmail', 1),
(6,  6, 1, 'confirmEmailChangePassword', 1),
(7,  6, 2, 'logout', 1),
(8,  7, 2, 'profile', 1),
(9,  7, 2, 'editProfile', 1),
(10, 7, 2, 'changeEmail', 1),
(11, 7, 2, 'changePassword', 1),
(12, 7, 2, 'changeSecurityQuestion', 1),
(13, 7, 3, 'createUser', 1),
(14, 7, 3, 'editUser', 1),
(15, 7, 3, 'deleteUser', 1),
(16, 7, 3, 'setUserState', 1),
(17, 8, 2, 'index', 1),
(18, 6, 2, 'login', 0),
(19, 6, 2, 'signUp', 0),
(20, 6, 2, 'forgotPassword', 0),
(21, 6, 2, 'confirmEmail', 0),
(22, 6, 2, 'confirmEmailChangePassword', 0);