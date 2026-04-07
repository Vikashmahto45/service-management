CREATE TABLE IF NOT EXISTS `team_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Seed some initial team members (Urban Company Leadership)
INSERT INTO `team_members` (`name`, `designation`, `image`) VALUES
('Abhiraj Singh Bhal', 'Co-founder & CEO', 'https://urbancompany.com/assets/images/about-us/abhiraj.jpg'),
('Varun Khaitan', 'Co-founder & COO', 'https://urbancompany.com/assets/images/about-us/varun.jpg'),
('Raghav Chandra', 'Co-founder & CPTO', 'https://urbancompany.com/assets/images/about-us/raghav.jpg');
