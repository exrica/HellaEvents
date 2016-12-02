-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2015 at 07:10 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `events2`
--

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `countryid` char(3) NOT NULL,
  `countryname` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`countryid`, `countryname`) VALUES
('USA', 'United States');

-- --------------------------------------------------------

--
-- Table structure for table `eventrsvp`
--

CREATE TABLE IF NOT EXISTS `eventrsvp` (
  `eventid` int(8) unsigned zerofill NOT NULL,
  `userid` int(8) unsigned zerofill NOT NULL,
  `response` enum('YES','MAYBE','NO') NOT NULL,
  `guests` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
`eventID` int(8) unsigned zerofill NOT NULL,
  `title` varchar(255) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `location` int(8) unsigned zerofill NOT NULL,
  `description` mediumtext,
  `published` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `event_discussion`
--

CREATE TABLE IF NOT EXISTS `event_discussion` (
`postid` int(10) unsigned zerofill NOT NULL,
  `eventid` int(8) unsigned zerofill NOT NULL,
  `userid` int(8) unsigned zerofill NOT NULL,
  `timestamp` datetime NOT NULL,
  `comment` mediumtext NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
`locationid` int(8) unsigned zerofill NOT NULL,
  `locationname` varchar(255) NOT NULL,
  `streetaddr1` varchar(255) NOT NULL,
  `aptunit` varchar(255) DEFAULT NULL,
  `city` varchar(64) NOT NULL,
  `state` char(2) NOT NULL,
  `country` char(3) NOT NULL,
  `zip` char(5) NOT NULL,
  `phone` char(14) NOT NULL,
  `phoneext` char(10) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `comment` text NOT NULL,
  `allownewevents` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`locationid`, `locationname`, `streetaddr1`, `aptunit`, `city`, `state`, `country`, `zip`, `phone`, `phoneext`, `website`, `comment`, `allownewevents`) VALUES
(00000001, 'Sample Location', '100 Main St', '', 'Sample City', 'FL', 'USA', '12345', '555-555-1212', '', '', 'Sample location only.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `profile_data`
--

CREATE TABLE IF NOT EXISTS `profile_data` (
  `profileID` int(8) unsigned zerofill NOT NULL,
  `fname` varchar(32) DEFAULT NULL,
  `lname` varchar(32) DEFAULT NULL,
  `displayname` varchar(64) NOT NULL,
  `birthday` date DEFAULT NULL,
  `bio` text,
  `gender` enum('F','M','O') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profile_data`
--

INSERT INTO `profile_data` (`profileID`, `fname`, `lname`, `displayname`, `birthday`, `bio`, `gender`) VALUES
(00000010, 'Super', 'User', 'suser', '2015-04-01', 'This is the default super user account.', 'O');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `roleid` int(4) unsigned zerofill NOT NULL,
  `rolename` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleid`, `rolename`) VALUES
(5000, 'Admin'),
(0000, 'Banned'),
(2500, 'Event Coordinator'),
(1000, 'Member'),
(9000, 'Super User');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE IF NOT EXISTS `states` (
  `stateid` char(2) NOT NULL,
  `statename` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`stateid`, `statename`) VALUES
('AK', 'Alaska'),
('AL', 'Alabama'),
('AR', 'Arkansas'),
('AZ', 'Arizona'),
('CA', 'California'),
('CO', 'Colorado'),
('CT', 'Connecticut'),
('DC', 'D. C.'),
('DE', 'Delaware'),
('FL', 'Florida'),
('GA', 'Georgia'),
('HI', 'Hawaii'),
('IA', 'Iowa'),
('ID', 'Idaho'),
('IL', 'Illinois'),
('IN', 'Indiana'),
('KS', 'Kansas'),
('KY', 'Kentucky'),
('LA', 'Louisiana'),
('MA', 'Massachusetts'),
('MD', 'Maryland'),
('ME', 'Maine'),
('MI', 'Michigan'),
('MN', 'Minnesota'),
('MO', 'Missouri'),
('MS', 'Mississippi'),
('MT', 'Montana'),
('NC', 'North Carolina'),
('ND', 'North Dakota'),
('NE', 'Nebraska'),
('NH', 'New Hampshire'),
('NJ', 'New Jersey'),
('NM', 'New Mexico'),
('NV', 'Nevada'),
('NY', 'New York'),
('OH', 'Ohio'),
('OK', 'Oklahoma'),
('OR', 'Oregon'),
('PA', 'Pennsylvania'),
('RI', 'Rhode Island'),
('SC', 'South Carolina'),
('SD', 'South Dakota'),
('TN', 'Tennessee'),
('TX', 'Texas'),
('UT', 'Utah'),
('VA', 'Virginia'),
('VT', 'Vermont'),
('WA', 'Washington'),
('WI', 'Wisconsin'),
('WV', 'West Virginia'),
('WY', 'Wyoming');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`userID` int(8) unsigned zerofill NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` char(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `registerdate` datetime NOT NULL,
  `userrank` int(4) unsigned zerofill NOT NULL DEFAULT '1000'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `email`, `registerdate`, `userrank`) VALUES
(00000010, 'suser', '8734e64d9a528310ae287a1586a9fb7f3fcadf2ed718b7ad3e761390f82c3295', 'admin@localhost', '2015-04-18 14:54:31', 9000);

--
-- Triggers `users`
--
DELIMITER //
CREATE TRIGGER `ins_user_profile_data_tg` AFTER INSERT ON `users`
 FOR EACH ROW INSERT INTO `profile_data`(`profileID`, `fname`, `lname`, `displayname`, `birthday`, `bio`, `gender`) VALUES (NEW.userid,null,null,NEW.username,null,null,null)
//
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `country`
--
ALTER TABLE `country`
 ADD PRIMARY KEY (`countryid`);

--
-- Indexes for table `eventrsvp`
--
ALTER TABLE `eventrsvp`
 ADD PRIMARY KEY (`eventid`,`userid`), ADD KEY `eventrsvp_users_userid_fk` (`userid`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
 ADD PRIMARY KEY (`eventID`), ADD KEY `title` (`title`,`start`), ADD KEY `location` (`location`);

--
-- Indexes for table `event_discussion`
--
ALTER TABLE `event_discussion`
 ADD PRIMARY KEY (`postid`), ADD KEY `eventid` (`eventid`,`userid`), ADD KEY `userid` (`userid`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
 ADD PRIMARY KEY (`locationid`), ADD KEY `state` (`state`), ADD KEY `country` (`country`);

--
-- Indexes for table `profile_data`
--
ALTER TABLE `profile_data`
 ADD PRIMARY KEY (`profileID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
 ADD PRIMARY KEY (`roleid`), ADD KEY `rolename` (`rolename`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
 ADD PRIMARY KEY (`stateid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`userID`), ADD UNIQUE KEY `username` (`username`), ADD KEY `userrank` (`userrank`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
MODIFY `eventID` int(8) unsigned zerofill NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `event_discussion`
--
ALTER TABLE `event_discussion`
MODIFY `postid` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
MODIFY `locationid` int(8) unsigned zerofill NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `userID` int(8) unsigned zerofill NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `eventrsvp`
--
ALTER TABLE `eventrsvp`
ADD CONSTRAINT `eventrsvp_events _eventid_fk` FOREIGN KEY (`eventid`) REFERENCES `events` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `eventrsvp_users_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
ADD CONSTRAINT `events_location_locationid_fk` FOREIGN KEY (`location`) REFERENCES `locations` (`locationid`) ON UPDATE CASCADE;

--
-- Constraints for table `event_discussion`
--
ALTER TABLE `event_discussion`
ADD CONSTRAINT `event_discussion_ibfk_1` FOREIGN KEY (`eventid`) REFERENCES `events` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `event_discussion_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
ADD CONSTRAINT `location_country_countryid_fk` FOREIGN KEY (`country`) REFERENCES `country` (`countryid`) ON UPDATE CASCADE,
ADD CONSTRAINT `location_state_stateid_fk` FOREIGN KEY (`state`) REFERENCES `states` (`stateid`) ON UPDATE CASCADE;

--
-- Constraints for table `profile_data`
--
ALTER TABLE `profile_data`
ADD CONSTRAINT `profile_data_users_userid_fk` FOREIGN KEY (`profileID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `users_userrank_role_roleid_fk` FOREIGN KEY (`userrank`) REFERENCES `roles` (`roleid`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
