-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 21, 2022 at 09:44 AM
-- Server version: 8.0.28-0ubuntu0.20.04.3
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `county`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adm_id` int NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `level` varchar(20) NOT NULL,
  `floc` varchar(500) DEFAULT 'uploads/passport.jpg',
  `createdby` int DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adm_id`, `fullname`, `phone`, `designation`, `username`, `email`, `password`, `level`, `floc`, `createdby`) VALUES
(1, 'Admin Admin', '0727044818', 'System Administrator', 'Admin0', 'denkytheka@gmail.com', 'd0204b396d379b6bef266085a0bd43c9', 'SuperAdmin', 'uploads/PrinceMike.jpg', 1),
(2, 'Daniel Brown', '0705577337', 'Director', 'dbrown', 'mnwuzor@gmail.com', 'e8e66141b4315295ce50773e62216f4c', '2', 'uploads/passport.jpg', 1),
(3, 'McAriel Enyichi', '07036757279', 'Project Manager', 'admin', 'info@netdataflow.com', '042325fe091720991a2daafe615128a1', 'Admin', 'uploads/passport.jpg', 1),
(6, ' ', '0727044818', '1', 'dmk2', 'afyasend2017@gmail.com', 'bcb04c48e8e2e20a0e28da71d9fa868c', 'SuperAdmin', 'uploads/4018_File_male avatar.png', 1),
(7, ' Dennis Kitheka', '0727044818', '6', 'dmk', 'dennis.kitheka@projtrac.com', 'e74537940734b28bf6578d99c5bea978', 'Officer', 'uploads/7716_File_male avatar.png', 1),
(8, ' ', '0720006633', '8', 'chematel2013', 'chematel2013@softcimes.co.ke', 'b6255fe4eb53d73521cc05cb6965c138', 'MOfficer', 'uploads/2200_File_index.jpg', 1),
(9, ' ', '0722114471', '1', 'Admin1', 'nick@afrique.com', '5004aaa8d13d77c4742d1157f7a70aa6     e8e66141b4315295ce50773e62216f4c', 'MOfficer', 'uploads/8234_File_king J.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `counties`
--

CREATE TABLE `counties` (
  `id` int NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(250) NOT NULL,
  `size` float DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `counties`
--

INSERT INTO `counties` (`id`, `code`, `name`, `size`, `status`) VALUES
(1, '01', 'Mombasa', 250000, 1),
(2, '02', 'Kwale', NULL, 1),
(3, '03', 'Kilifi', NULL, 1),
(4, '04', 'Tana River', NULL, 1),
(5, '05', 'Lamu', NULL, 1),
(6, '06', 'Taita-Taveta', NULL, 1),
(7, '07', 'Garissa', NULL, 1),
(8, '08', 'Wajir', NULL, 1),
(9, '09', 'Mandera', NULL, 1),
(10, '10', 'Marsabit', NULL, 1),
(11, '11', 'Isiolo', NULL, 1),
(12, '12', 'Meru', NULL, 1),
(13, '13', 'Tharaka-Nithi', NULL, 1),
(14, '14', 'Embu', NULL, 1),
(15, '15', 'Kitui', NULL, 1),
(16, '16', 'Machakos', NULL, 1),
(17, '17', 'Makueni', NULL, 1),
(18, '18', 'Nyandarua', NULL, 1),
(19, '19', 'Nyeri', NULL, 1),
(20, '20', 'Kirinyanga', NULL, 1),
(21, '21', 'Murang\'a', NULL, 1),
(22, '22', 'Kiambu', NULL, 1),
(23, '23', 'Turkana', NULL, 1),
(24, '24', 'West Pokot', NULL, 1),
(25, '25', 'Samburu', NULL, 1),
(26, '26', 'Trans-Nzoia', NULL, 1),
(27, '27', 'Uasin Gishu', NULL, 1),
(28, '28', 'Elgeyo-Marakwet', NULL, 1),
(29, '29', 'Nandi', NULL, 1),
(30, '30', 'Baringo', NULL, 1),
(31, '31', 'Laikipia', NULL, 1),
(32, '32', 'Nakuru', NULL, 1),
(33, '33', 'Narok', NULL, 1),
(34, '34', 'Kajiado', NULL, 1),
(35, '35', 'Kericho', NULL, 1),
(36, '36', 'Bomet', NULL, 1),
(37, '37', 'Kakamega', NULL, 1),
(38, '38', 'Vihiga', NULL, 1),
(39, '39', 'Bungoma', NULL, 1),
(40, '40', 'Busia', NULL, 1),
(41, '41', 'Siaya', NULL, 1),
(42, '42', 'Kisumu', NULL, 1),
(43, '43', 'Homa Bay', NULL, 1),
(44, '44', 'Migori', NULL, 1),
(45, '45', 'Kisii', NULL, 1),
(46, '46', 'Nyamira', NULL, 1),
(47, '47', 'Nairobi', NULL, 1),
(49, '49', 'Roanna Kane', 124444, 1);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint NOT NULL,
  `country` varchar(255) NOT NULL DEFAULT '',
  `iso_code` varchar(5) DEFAULT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `value` int NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country`, `iso_code`, `country_code`, `value`, `status`) VALUES
(1, 'Afghanistan', 'AF', '93', 1, 1),
(2, 'Albania', 'AL', '355', 1, 1),
(3, 'Algeria', 'DZ', '213', 1, 1),
(4, 'Andorra', 'AD', '376', 1, 1),
(5, 'Angola', 'AO', '244', 1, 1),
(6, 'Anguilla', 'AI', '1-264', 1, 1),
(7, 'Antigua & Barbuda', 'AG', '1-268', 1, 1),
(8, 'Argentina', 'AR', '54', 1, 1),
(9, 'Armenia', 'AM', '374', 1, 1),
(10, 'Austria', 'AT', '43', 1, 1),
(11, 'Azerbaijan', 'AZ', '994', 1, 1),
(12, 'Bahamas', 'BS', '1-242', 1, 1),
(13, 'Bahrain', 'BH', '973', 1, 1),
(14, 'Bangladesh', 'BD', '880', 1, 1),
(15, 'Barbados', 'BB', '1-246', 1, 1),
(16, 'Belarus', 'BY', '375', 1, 1),
(17, 'Belgium', 'BE', '32', 1, 1),
(18, 'Belize', 'BZ', '501', 1, 1),
(19, 'Benin', 'BJ', '229', 1, 1),
(20, 'Bermuda', 'BM', '1-441', 1, 1),
(21, 'Bhutan', 'BT', '975', 1, 1),
(22, 'Bolivia', 'BO', '591', 1, 1),
(23, 'Bosnia and Herzegovina', 'BA', '387', 1, 1),
(24, 'Botswana', 'BW', '267', 1, 1),
(25, 'Brazil', 'BR', '55', 1, 1),
(26, 'Brunei', 'BN', '673', 1, 1),
(27, 'Bulgaria', 'BG', '359', 1, 1),
(28, 'Burkina Faso', 'BF', '226', 1, 1),
(29, 'Burundi', 'BI', '257', 1, 1),
(30, 'Cambodia', 'KH', '855', 1, 1),
(31, 'Cameroon', 'CM', '237', 1, 1),
(32, 'Canada', 'CA', '1', 1, 1),
(33, 'Cape Verde', 'CV', '238', 1, 1),
(34, 'Cayman Islands', 'KY', '1-345', 1, 1),
(35, 'Central African Republic', 'CF', '236', 1, 1),
(36, 'Chad', 'TD', '235', 1, 1),
(37, 'Chile', 'CL', '56', 1, 1),
(38, 'China', 'CN', '86', 1, 1),
(39, 'Colombia', 'CO', '57', 1, 1),
(40, 'Comoros', 'KM', '269', 1, 1),
(41, 'Congo', 'CG', '242', 1, 1),
(42, 'Congo (DRC)', 'CD', '243', 1, 1),
(43, 'Cook Islands', 'CK', '682', 1, 1),
(44, 'Costa Rica', 'CR', '506', 1, 1),
(45, 'Cote d\'Ivoire', 'CI', '225', 1, 1),
(46, 'Croatia (Hrvatska)', 'HR', '385', 1, 1),
(47, 'Cuba', 'CU', '53', 1, 1),
(48, 'Cyprus', 'CY', '357', 1, 1),
(49, 'Czech Republic', 'CZ', '420', 1, 1),
(50, 'Denmark', 'DK', '45', 1, 1),
(51, 'Djibouti', 'DJ', '253', 1, 1),
(52, 'Dominica', 'DM', '1-767', 1, 1),
(53, 'Dominican Republic', 'DO', '1809', 1, 1),
(54, 'East Timor', 'TL', '670', 1, 1),
(55, 'Ecuador', 'EC', '593', 1, 1),
(56, 'Egypt', 'EG', '20', 1, 1),
(57, 'El Salvador', 'SV', '20', 1, 1),
(58, 'Equatorial Guinea', 'GQ', '240', 1, 1),
(59, 'Eritrea', 'ER', '291', 1, 1),
(60, 'Estonia', 'EE', '372', 1, 1),
(61, 'Ethiopia', 'ET', '251', 1, 1),
(62, 'Falkland Islands', 'FK', '500', 1, 1),
(63, 'Faroe Islands', 'FO', '298', 1, 1),
(64, 'Fiji Islands', 'FJ', '679', 1, 1),
(65, 'Finland', 'FI', '358', 1, 1),
(66, 'France', 'FR', '33', 1, 1),
(67, 'French Guiana', NULL, NULL, 1, 1),
(68, 'French Polynesia', 'PF', '689', 1, 1),
(69, 'Gabon', 'GA', '241', 1, 1),
(70, 'Gambia', 'GM', '220', 1, 1),
(71, 'Georgia', 'GE', '995', 1, 1),
(72, 'Germany', 'DE', '49', 1, 1),
(73, 'Ghana', 'GH', '233', 1, 1),
(74, 'Gibraltar', 'GI', '350', 1, 1),
(75, 'Greece', 'GR', '30', 1, 1),
(76, 'Greenland', 'GL', '299', 1, 1),
(77, 'Grenada', 'GD', '+1473', 1, 1),
(78, 'Guadeloupe', NULL, NULL, 1, 1),
(79, 'Guam', 'GU', '1671', 1, 1),
(80, 'Guatemala', 'GT', '502', 1, 1),
(81, 'Guinea', 'GN', '224', 1, 1),
(82, 'Guinea-Bissau', 'GW', '245', 1, 1),
(83, 'Guyana', 'GY', '592', 1, 1),
(84, 'Haiti', 'HT', '509', 1, 1),
(85, 'Honduras', 'HN', '504', 1, 1),
(86, 'Hong Kong SAR', 'HK', '852', 1, 1),
(87, 'Hungary', 'HU', '36', 1, 1),
(88, 'Iceland', 'IS', '354', 1, 1),
(89, 'India', 'IN', '91', 1, 1),
(90, 'Indonesia', 'ID', '62', 1, 1),
(91, 'Iran', 'IR', '98', 1, 1),
(92, 'Iraq', 'IQ', '964', 1, 1),
(93, 'Ireland', 'IE', '353', 1, 1),
(94, 'Israel', 'IL', '972', 1, 1),
(95, 'Italy', 'IT', '39', 1, 1),
(96, 'Jamaica', 'JM', '1876', 1, 1),
(97, 'Japan', 'JP', '81', 1, 1),
(98, 'Jordan', 'JO', '962', 1, 1),
(99, 'Kazakhstan', 'KZ', '7', 1, 1),
(100, 'Kenya', 'KE', '254', 1, 1),
(101, 'Kiribati', 'KI', '686', 1, 1),
(102, 'South Korea', 'KR', '82', 1, 1),
(103, 'Kuwait', 'KW', '965', 1, 1),
(104, 'Kyrgyzstan', 'KG', '996', 1, 1),
(105, 'Laos', 'LA', '856', 1, 1),
(106, 'Latvia', 'LV', '371', 1, 1),
(107, 'Lebanon', 'LB', '961', 1, 1),
(108, 'Lesotho', 'LS', '266', 1, 1),
(109, 'Liberia', 'LR', '231', 1, 1),
(110, 'Libya', 'LY', '218', 1, 1),
(111, 'Liechtenstein', 'LI', '423', 1, 1),
(112, 'Lithuania', 'LT', '370', 1, 1),
(113, 'Luxembourg', 'LU', '352', 1, 1),
(114, 'Macao SAR', 'MO', '853', 1, 1),
(115, 'Macedonia', 'MK', '389', 1, 1),
(116, 'Madagascar', 'MG', '261', 1, 1),
(117, 'Malawi', 'MW', '265', 1, 1),
(118, 'Malaysia', 'MY', '60', 1, 1),
(119, 'Maldives', 'MV', '960', 1, 1),
(120, 'Mali', 'ML', '223', 1, 1),
(121, 'Malta', 'MT', '356', 1, 1),
(122, 'Martinique', NULL, NULL, 1, 1),
(123, 'Mauritania', 'MR', '222', 1, 1),
(124, 'Mauritius', 'MU', '230', 1, 1),
(125, 'Mayotte', 'YT', '262', 1, 1),
(126, 'Mexico', 'MX', '52', 1, 1),
(127, 'Micronesia', 'FM', '691', 1, 1),
(128, 'Moldova', 'MD', '373', 1, 1),
(129, 'Monaco', 'MC', '377', 1, 1),
(130, 'Mongolia', 'MN', '976', 1, 1),
(131, 'Montserrat', 'MS', '1-664', 1, 1),
(132, 'Morocco', 'MA', '212', 1, 1),
(133, 'Mozambique', 'MZ', '258', 1, 1),
(134, 'Myanmar', 'MM', '95', 1, 1),
(135, 'Namibia', 'NA', '264', 1, 1),
(136, 'Nauru', 'NR', '674', 1, 1),
(137, 'Nepal', 'NP', '977', 1, 1),
(138, 'Netherlands', 'NL', '31', 1, 1),
(139, 'Netherlands Antilles', 'AN', '599', 1, 1),
(140, 'New Caledonia', 'NC', '687', 1, 1),
(141, 'New Zealand', 'NZ', '64', 1, 1),
(142, 'Nicaragua', 'NI', '505', 1, 1),
(143, 'Niger', 'NE', '227', 1, 1),
(144, 'Nigeria', 'NG', '234', 1, 1),
(145, 'Niue', 'NU', '683', 1, 1),
(146, 'Norfolk Island', NULL, NULL, 1, 1),
(147, 'North Korea', 'NK', '850', 1, 1),
(148, 'Norway', 'NO', '850', 1, 1),
(149, 'Oman', 'OM', NULL, 1, 1),
(150, 'Pakistan', 'PK', '92', 1, 1),
(151, 'Panama', 'PA', '507', 1, 1),
(152, 'Papua New Guinea', 'PG', '675', 1, 1),
(153, 'Paraguay', 'PY', '595', 1, 1),
(154, 'Peru', 'PE', '51', 1, 1),
(155, 'Philippines', 'PH', '63', 1, 1),
(156, 'Pitcairn Islands', 'PN', '64', 1, 1),
(157, 'Poland', 'PL', '48', 1, 1),
(158, 'Portugal', 'PT', '351', 1, 1),
(159, 'Puerto Rico', 'PR', '1787', 1, 1),
(160, 'Qatar', 'QA', '974', 1, 1),
(161, 'Reunion', 'RE', '262', 1, 1),
(162, 'Romania', 'RO', '40', 1, 1),
(163, 'Russia', 'RU', '7', 1, 1),
(164, 'Rwanda', 'RW', '250', 1, 1),
(165, 'Samoa', 'WS', '685', 1, 1),
(166, 'San Marino', 'SM', '378', 1, 1),
(167, 'Sao Tome and Principe', 'ST', '239', 1, 1),
(168, 'Saudi Arabia', 'SA', '966', 1, 1),
(169, 'Senegal', 'SN', '221', 1, 1),
(170, 'Serbia and Montenegro', 'RS', '381', 1, 1),
(171, 'Seychelles', 'SC', '248', 1, 1),
(172, 'Sierra Leone', 'SL', '232', 1, 1),
(173, 'Singapore', 'SG', '65', 1, 1),
(174, 'Slovakia', 'SK', '421', 1, 1),
(175, 'Slovenia', 'SI', '386', 1, 1),
(176, 'Solomon Islands', 'SB', '677', 1, 1),
(177, 'Somalia', 'SO', '252', 1, 1),
(178, 'South Africa', 'ZA', '27', 1, 1),
(179, 'Spain', 'ES', '34', 1, 1),
(180, 'Sri Lanka', 'LK', '94', 1, 1),
(181, 'St. Helena', 'SH', '290', 1, 1),
(182, 'St. Kitts and Nevis', 'KN', '1869', 1, 1),
(183, 'St. Lucia', 'LC', '1758', 1, 1),
(184, 'St. Pierre and Miquelon', 'PM', '508', 1, 1),
(185, 'St. Vincent & Grenadines', 'VC', '1784', 1, 1),
(186, 'Sudan', 'SD', '249', 1, 1),
(187, 'Suriname', 'SR', '597', 1, 1),
(188, 'Swaziland', 'SZ', '268', 1, 1),
(189, 'Sweden', 'SE', '46', 1, 1),
(190, 'Switzerland', 'CH', '41', 1, 1),
(191, 'Syria', 'SY', '963', 1, 1),
(192, 'Taiwan', 'TW', '886', 1, 1),
(193, 'Tajikistan', 'TJ', '992', 1, 1),
(194, 'Tanzania', 'TZ', '255', 1, 1),
(195, 'Thailand', 'TH', '66', 1, 1),
(196, 'Togo', 'TG', '228', 1, 1),
(197, 'Tokelau', 'TK', '690', 1, 1),
(198, 'Tonga', 'TO', '676', 1, 1),
(199, 'Trinidad and Tobago', 'TT', '1868', 1, 1),
(200, 'Tunisia', 'TN', '216', 1, 1),
(201, 'Turkey', 'TR', '90', 1, 1),
(202, 'Turkmenistan', 'TM', '993', 1, 1),
(203, 'Turks and Caicos Islands', 'TC', '1649', 1, 1),
(204, 'Tuvalu', 'TV', '688', 1, 1),
(205, 'Uganda', 'UG', '256', 1, 1),
(206, 'Ukraine', 'UA', '380', 1, 1),
(207, 'United Arab Emirates', 'AE', '971', 1, 1),
(208, 'United Kingdom', 'GB', '44', 1, 1),
(209, 'Uruguay', 'UY', '598', 1, 1),
(210, 'USA', 'US', '1', 2, 1),
(211, 'Uzbekistan', 'UZ', '998', 1, 1),
(212, 'Vanuatu', 'VU', '678', 1, 1),
(213, 'Venezuela', 'VE', '58', 1, 1),
(214, 'Vietnam', 'VN', '84', 1, 1),
(215, 'US Virgin Islands', 'VI', '1340', 1, 1),
(216, 'British Virgin Islands', 'VG', '1284', 1, 1),
(217, 'Wallis and Futuna', 'WF', '681', 1, 1),
(218, 'Yemen', 'YE', '967', 1, 1),
(219, 'Yugoslavia', NULL, NULL, 1, 1),
(220, 'Zambia', 'ZM', '260', 1, 1),
(221, 'Zimbabwe', 'ZW', '263', 1, 1),
(222, 'Australia', 'AU', '61', 1, 1),
(223, 'American Samoa', 'AS', '1684', 1, 1),
(224, 'Antarctica', 'AQ', '672', 1, 1),
(225, 'Aruba', 'AW', '297', 1, 1),
(226, 'British Indian Ocean Territory', 'IO', '246', 1, 1),
(227, 'Christmas Island', 'CX', '61', 1, 1),
(228, 'Cocos Islands', 'CC', '61', 1, 1),
(229, 'Antarctica', 'ATA', '672', 1, 1),
(230, 'Curacao', 'CW', '599', 1, 1),
(231, 'Guernsey', 'GG', '441481', 1, 1),
(232, 'Isle of Man', 'IM', '441624', 1, 1),
(233, 'Jersey', 'JE', '441534', 1, 1),
(234, 'Kosovo', 'XK', '383', 1, 1),
(235, 'Marshall Islands', 'MH', '692', 1, 1),
(236, 'Montenegro', 'ME', '382', 1, 1),
(237, 'Saint Barthelemy', 'BL', '590', 1, 1),
(238, 'Saint Martin', 'MF', '590', 1, 1),
(239, 'Sint Maarten', 'SX', '1721', 1, 1),
(240, 'South Sudan', 'SS', '211', 1, 1),
(241, 'Svalbard and Jan Mayen', 'SJ', '47', 1, 1),
(242, 'Vatican', 'VA', '379', 1, 1),
(243, 'Western Sahara', 'EH', '212', 1, 1),
(244, 'Eaque repellendus S', 'quia ', '58', 79, 1);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `fid` int NOT NULL,
  `filename` varchar(300) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `fcategory` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `catid` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `ftype` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `projid` int NOT NULL,
  `msid` int NOT NULL,
  `tkid` int NOT NULL,
  `task` varchar(300) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `floc` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `user_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `udate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int NOT NULL,
  `image` int DEFAULT NULL,
  `image2` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `sysid` int NOT NULL,
  `clientname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `county` int DEFAULT NULL,
  `location` float DEFAULT NULL,
  `country` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `floc` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`sysid`, `clientname`, `address`, `city`, `state`, `county`, `location`, `country`, `phone`, `email`, `floc`) VALUES
(1, 'ProjTrac Systems Limited', '13th Floor, Landmark Building, Argwings Kodhek Road', 'Nairobi', '', 47, NULL, 'Kenya', '0727044818', 'info@projtrac.co.ke', 'images/projtrac_logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `tble_feedback_categories`
--

CREATE TABLE `tble_feedback_categories` (
  `catid` int NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tble_feedback_categories`
--

INSERT INTO `tble_feedback_categories` (`catid`, `category`) VALUES
(1, 'Timeline '),
(2, 'Quality'),
(3, 'Personnel'),
(4, 'Enviromental'),
(5, 'Health'),
(6, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_annual_dev_plan`
--

CREATE TABLE `tbl_annual_dev_plan` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `financial_year` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `approved_by` varchar(100) DEFAULT NULL,
  `date_approved` date DEFAULT NULL,
  `unapproved_by` varchar(100) DEFAULT NULL,
  `date_unapproved` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_annual_dev_plan`
--

INSERT INTO `tbl_annual_dev_plan` (`id`, `projid`, `financial_year`, `status`, `created_by`, `date_created`, `approved_by`, `date_approved`, `unapproved_by`, `date_unapproved`) VALUES
(1, 1, 4, 1, 'admin0', '2022-01-16', 'admin0', '2022-01-18', NULL, NULL),
(2, 5, 4, 1, 'Admin0', '2022-01-16', 'Admin0', '2022-01-16', NULL, NULL),
(5, 6, 4, 1, 'Admin0', '2022-01-16', 'Admin0', '2022-01-16', NULL, NULL),
(6, 7, 4, 1, 'Admin0', '2022-01-16', 'Admin0', '2022-01-16', NULL, NULL),
(14, 2, 4, 1, 'admin0', '2022-01-16', 'Admin0', '2022-01-16', NULL, NULL),
(15, 8, 4, 1, 'Admin0', '2022-01-16', 'Admin0', '2022-01-16', NULL, NULL),
(37, 9, 4, 1, 'admin0', '2022-01-18', 'admin0', '2022-01-18', NULL, NULL),
(38, 10, 4, 1, 'Admin0', '2022-01-19', 'Admin0', '2022-01-19', NULL, NULL),
(39, 4, 4, 1, 'Admin0', '2022-01-19', 'Admin0', '2022-01-19', NULL, NULL),
(40, 3, 4, 1, 'Admin0', '2022-01-19', 'Admin0', '2022-01-19', NULL, NULL),
(41, 11, 4, 1, 'admin0', '2022-01-24', 'admin0', '2022-01-25', NULL, NULL),
(44, 18, 4, 1, 'admin0', '2022-02-01', '1', '2022-03-14', NULL, '2022-03-10'),
(45, 21, 4, 1, 'admin0', '2022-02-02', 'admin0', '2022-02-02', NULL, NULL),
(46, 20, 4, 1, 'admin0', '2022-02-02', 'Admin0', '2022-02-13', NULL, NULL),
(47, 22, 4, 1, 'admin0', '2022-02-02', 'admin0', '2022-02-02', NULL, NULL),
(55, 23, 4, 1, 'admin0', '2022-02-08', 'Admin0', '2022-02-13', NULL, NULL),
(58, 12, 4, 1, 'admin0', '2022-02-10', 'admin0', '2022-02-21', NULL, NULL),
(60, 24, 4, 1, 'Admin0', '2022-02-13', 'admin0', '2022-02-13', NULL, NULL),
(61, 19, 4, 0, 'Admin0', '2022-02-16', 'admin0', '2022-03-10', NULL, '2022-03-10'),
(63, 15, 4, 1, 'admin0', '2022-02-16', 'admin0', '2022-02-22', NULL, NULL),
(64, 14, 4, 1, 'admin0', '2022-02-16', '1', '2022-03-23', NULL, '2022-03-10'),
(66, 25, 4, 1, 'admin0', '2022-02-17', 'admin0', '2022-02-17', NULL, NULL),
(67, 27, 4, 1, 'admin0', '2022-02-25', 'admin0', '2022-02-25', NULL, NULL),
(68, 29, 4, 1, 'admin0', '2022-03-15', '1', '2022-03-15', NULL, NULL),
(71, 34, 4, 0, 'Admin0', '2022-03-20', NULL, NULL, NULL, NULL),
(72, 35, 4, 1, 'Admin0', '2022-03-20', '1', '2022-03-20', NULL, NULL),
(73, 37, 4, 0, 'Admin0', '2022-03-23', NULL, NULL, NULL, NULL),
(74, 16, 4, 0, 'Admin0', '2022-03-23', NULL, NULL, NULL, NULL),
(75, 38, 4, 1, 'Admin0', '2022-03-23', '1', '2022-03-23', NULL, NULL),
(76, 28, 4, 0, 'Admin0', '2022-03-23', NULL, NULL, NULL, NULL),
(77, 39, 4, 1, 'Admin0', '2022-03-24', '1', '2022-03-24', NULL, NULL),
(78, 40, 4, 1, 'Admin0', '2022-03-25', '1', '2022-03-27', NULL, NULL),
(79, 41, 4, 0, 'Admin0', '2022-03-25', NULL, NULL, NULL, NULL),
(80, 44, 4, 1, 'admin0', '2022-03-29', '1', '2022-03-29', NULL, NULL),
(81, 48, 4, 1, 'admin0', '2022-03-31', '1', '2022-03-31', NULL, NULL),
(82, 47, 4, 0, 'admin0', '2022-03-31', NULL, NULL, NULL, NULL),
(83, 46, 4, 0, 'admin0', '2022-03-31', NULL, NULL, NULL, NULL),
(84, 13, 5, 0, 'admin0', '2022-04-02', NULL, NULL, NULL, NULL),
(85, 54, 4, 1, 'admin0', '2022-04-02', '1', '2022-04-03', NULL, NULL),
(86, 50, 4, 1, 'admin0', '2022-04-02', '1', '2022-04-03', NULL, NULL),
(87, 51, 4, 1, 'admin0', '2022-04-02', '1', '2022-04-03', NULL, NULL),
(88, 52, 4, 0, 'admin0', '2022-04-02', NULL, NULL, NULL, NULL),
(89, 53, 4, 0, 'admin0', '2022-04-02', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_assumption`
--

CREATE TABLE `tbl_assumption` (
  `asid` int NOT NULL,
  `cidpid` int DEFAULT NULL,
  `cadpid` int DEFAULT NULL,
  `projid` int NOT NULL,
  `assumption` text NOT NULL,
  `progress` int DEFAULT '0',
  `status` varchar(100) NOT NULL,
  `sdate` date NOT NULL,
  `edate` date NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_big_four_agenda`
--

CREATE TABLE `tbl_big_four_agenda` (
  `id` int NOT NULL,
  `agenda` varchar(255) NOT NULL,
  `description` text,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_big_four_agenda`
--

INSERT INTO `tbl_big_four_agenda` (`id`, `agenda`, `description`, `status`) VALUES
(1, 'Enhancing Manufacturing', 'Enhancing Manufacturing', 1),
(2, 'Food Security and Nutrition', 'Food Security and Nutrition', 1),
(3, 'Universal Health Coverage', 'Universal Health Coverage', 1),
(4, 'Affordable Housing', 'Affordable Housing', 1),
(5, 'Others', 'Others outside the Big Four', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_budget_lines`
--

CREATE TABLE `tbl_budget_lines` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_budget_lines`
--

INSERT INTO `tbl_budget_lines` (`id`, `name`, `description`, `status`) VALUES
(2, 'Administration/Operational Cost', 'Budget for project office administration', 1),
(3, 'Monitoring & Evaluation Cost', 'Project M&E Operations cost', 1),
(4, 'Non Expendable Equipment Cost', 'Non Expendable Equipment Cost', 1),
(5, 'Local Travel Cost', 'Project local travel expenses', 1),
(6, 'Testtest', 'Testing', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_capr_report_conclusion`
--

CREATE TABLE `tbl_capr_report_conclusion` (
  `id` int NOT NULL,
  `stid` int NOT NULL,
  `year` longtext NOT NULL,
  `section_comments` longtext NOT NULL,
  `challenges` longtext NOT NULL,
  `conclusion` longtext NOT NULL,
  `appendices` longtext NOT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_capr_report_conclusion`
--

INSERT INTO `tbl_capr_report_conclusion` (`id`, `stid`, `year`, `section_comments`, `challenges`, `conclusion`, `appendices`, `created_by`, `created_at`) VALUES
(1, 9, '4', '<p><span style=\"background-color:transparent; color:rgb(0, 0, 0); font-family:times new roman; font-size:12pt\">The sub-sector/department to provide major implementation challenges that they faced during the period under review and recommendations on how to address them.</span></p>', '<p><span style=\"background-color:transparent; color:rgb(0, 0, 0); font-family:times new roman; font-size:12pt\">The sub-sector/department to provide major implementation challenges that they faced during the period under review and recommendations on how to address them.</span></p>', '<p><span style=\"background-color:transparent; color:rgb(0, 0, 0); font-family:times new roman; font-size:12pt\">The sub-sector/department to provide major implementation challenges that they faced during the period under review and recommendations on how to address them.</span></p>', '<p><span style=\"background-color:transparent; color:rgb(0, 0, 0); font-family:times new roman; font-size:12pt\">The sub-sector/department to provide major implementation challenges that they faced during the period under review and recommendations on how to address them.</span></p>', 1, '2022-03-31 08:39:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_capr_report_remarks`
--

CREATE TABLE `tbl_capr_report_remarks` (
  `id` int NOT NULL,
  `indid` int NOT NULL,
  `year` int NOT NULL,
  `remarks` text NOT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_capr_report_remarks`
--

INSERT INTO `tbl_capr_report_remarks` (`id`, `indid`, `year`, `remarks`, `created_by`, `created_at`) VALUES
(1, 1, 2021, 'test', 1, '2022-03-29 00:00:00'),
(2, 1, 2021, 'testing', 1, '2022-03-29 00:00:00'),
(3, 1, 4, 'testing', 1, '2022-03-29 00:00:00'),
(4, 2, 4, 'testing 5', 1, '2022-03-29 00:00:00'),
(5, 12, 4, 'Testing the space ', 1, '2022-03-29 00:00:00'),
(6, 3, 4, 'testing', 1, '2022-03-31 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_certificates`
--

CREATE TABLE `tbl_certificates` (
  `id` int NOT NULL,
  `certificateno` varchar(100) NOT NULL,
  `projid` int NOT NULL,
  `category` int NOT NULL,
  `itemid` int NOT NULL,
  `prefix` varchar(10) NOT NULL,
  `year` int NOT NULL,
  `previousnumber` int NOT NULL,
  `certficatedate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_certificates`
--

INSERT INTO `tbl_certificates` (`id`, `certificateno`, `projid`, `category`, `itemid`, `prefix`, `year`, `previousnumber`, `certficatedate`) VALUES
(1, 'MCK/MST/22-0005000', 38, 2, 43, 'MST', 22, 5000, '2022-04-04'),
(2, 'MCK/MST/22-0005001', 38, 2, 42, 'MST', 22, 5001, '2022-04-05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_checklist_test`
--

CREATE TABLE `tbl_checklist_test` (
  `id` int NOT NULL,
  `checklistid` int NOT NULL,
  `score` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comments`
--

CREATE TABLE `tbl_comments` (
  `comid` int NOT NULL,
  `Comments` text NOT NULL,
  `projno` int NOT NULL,
  `projname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_community`
--

CREATE TABLE `tbl_community` (
  `communityid` int NOT NULL,
  `community` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_company_settings`
--

CREATE TABLE `tbl_company_settings` (
  `id` int NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `type` int NOT NULL DEFAULT '2',
  `code` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '0',
  `postal_address` varchar(255) DEFAULT NULL,
  `country` int NOT NULL,
  `telephone_no` varchar(15) DEFAULT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `county` int NOT NULL,
  `city` varchar(255) NOT NULL,
  `plot_no` varchar(255) DEFAULT NULL,
  `directory_location` varchar(255) DEFAULT NULL,
  `domain_address` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `main_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `coordinates_path` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_company_settings`
--

INSERT INTO `tbl_company_settings` (`id`, `company_name`, `type`, `code`, `postal_address`, `country`, `telephone_no`, `mobile_no`, `email_address`, `county`, `city`, `plot_no`, `directory_location`, `domain_address`, `ip_address`, `main_url`, `coordinates_path`, `logo`, `latitude`, `longitude`) VALUES
(1, 'County Government of Uasin Gishu', 2, '0', 'P.O. Box 40-30100, Eldoret', 254, '053 2061330', '0727044818', 'info@uasingishu.go.ke', 27, 'Eldoret', NULL, NULL, 'http://www.uasingishu.go.ke/', NULL, 'http://34.74.197.215/county/', NULL, NULL, -1.292066, 36.821946);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contractor`
--

CREATE TABLE `tbl_contractor` (
  `contrid` int NOT NULL,
  `contractor_name` varchar(255) NOT NULL,
  `businesstype` int NOT NULL,
  `pinno` varchar(50) NOT NULL,
  `busregno` varchar(100) NOT NULL,
  `dateregistered` date NOT NULL,
  `pinstatus` int NOT NULL,
  `vatregistered` int NOT NULL DEFAULT '1',
  `contact` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `county` int DEFAULT NULL,
  `country` int DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `comments` text,
  `date_created` date NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `user_name` varchar(255) NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_contractor`
--

INSERT INTO `tbl_contractor` (`contrid`, `contractor_name`, `businesstype`, `pinno`, `busregno`, `dateregistered`, `pinstatus`, `vatregistered`, `contact`, `address`, `city`, `county`, `country`, `phone`, `email`, `comments`, `date_created`, `active`, `deleted`, `user_name`, `updated_by`, `date_updated`) VALUES
(1, 'Dittman Company', 1, 'A06226250Q', '234444', '2000-09-16', 1, 1, 'P.O Box 2233 Eldoret', 'Uganda Road', 'Eldoret', 27, NULL, '0733467811', 'kkipe15@gmail.com', '<p>Construction Company</p>', '2022-01-16', '1', '0', 'Admin0', NULL, NULL),
(2, 'African Water Company', 1, 'P0516226250P', '445566', '2010-02-16', 1, 1, 'P.O Box 444-0100', 'Riverside', 'Nairobi', 47, NULL, '0733467811', 'pkorir59@gmail.com', '', '2022-01-16', '1', '0', 'Admin0', NULL, NULL),
(3, 'Fatah Construction & Civil Works Limited', 1, 'P07227894R', '11098', '2005-02-16', 1, 1, 'P.O Box 889-00200', 'Karen', 'Nairobi', 47, NULL, '0720942928', 'kiplish@gmail.com', '', '2022-01-16', '1', '0', 'Admin0', NULL, NULL),
(4, 'Civicon Engineering Co', 1, 'Q922925T', '12367', '1998-07-16', 1, 1, 'P.O Box3456-00200 Nairobi', 'Upper Hill', 'Nairobi', 47, NULL, '0720941929', 'kiplish@gmail.com', '', '2022-01-16', '1', '0', 'Admin0', NULL, NULL),
(5, 'Projtrac Systems Limited', 1, 'A6226250P', '779966', '2018-01-05', 1, 1, 'P.O Box 1234-00200', 'Nairobi', 'Nairobi', 47, NULL, '+254 733 467 811', 'kiplish@gmail.com', '<p>IT Company</p>', '2022-03-05', '1', '0', 'Admin0', 'admin0', '2022-03-31 23:03:35'),
(6, 'Destiny Mcintyre', 1, '726', 'qiloton@mailinator.com', '1975-10-03', 1, 2, 'zuxeq@mailinator.com', 'Rerum perspiciatis ', 'zipewakaky@mailinator.com', 40, NULL, '+1 (522) 267-8201', 'nosazedo@mailinator.com', '<p>testing</p>', '2022-03-05', '1', '0', 'admin0', '', '2022-03-05 13:18:35'),
(7, 'Molly Dillon', 1, '746', 'fobycu@mailinator.com', '2013-02-01', 2, 2, 'mequditut@mailinator.com', 'Dolorum qui incididu', 'fymeru@mailinator.com', 25, NULL, '+1 (284) 905-4381', 'gacy@mailinator.com', '<p>poping</p>', '2022-03-05', '1', '0', 'admin0', 'admin0', '2022-03-31 23:02:01'),
(8, 'Afrique', 1, 'A6226250X', '99995533', '2014-01-01', 1, 1, 'XXXXXXXXXXXXXXXX', '78900000', 'Nbi', 47, NULL, '7891234', 'kkipe15@gmail.com', '<p>cccccccccccccccc</p>', '2022-03-20', '1', '0', 'Admin0', NULL, NULL),
(9, 'Veda Mueller', 1, '307', 'morenib@mailinator.com', '1997-04-08', 1, 2, 'fizubanyh@mailinator.com', 'Expedita enim eaque ', 'juvemaq@mailinator.com', 28, NULL, '+1 (966) 806-5488', 'vuvezixip@mailinator.com', '<p>test</p>', '2022-04-03', '1', '1', 'admin0', '1', NULL),
(10, 'Colin Vaughan', 2, '677', 'ryvaro@mailinator.com', '2006-07-05', 1, 1, 'cufi@mailinator.com', 'Sit nisi commodo fu', 'dyca@mailinator.com', 41, NULL, '+1 (924) 218-9519', 'jahepem@mailinator.com', '<p>testing 233</p>', '2022-04-16', '1', '0', '1', '1', '2022-04-16 10:47:59'),
(11, 'Brett Maynard', 1, '909', 'pywada@mailinator.com', '2004-02-22', 1, 2, 'wilajoke@mailinator.com', 'Voluptate sint facil', 'kifaw@mailinator.com', 16, NULL, '+1 (687) 823-8632', 'tedoti@mailinator.com', '<p>testing</p>', '2022-04-16', '1', '1', '1', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contractorbusinesstype`
--

CREATE TABLE `tbl_contractorbusinesstype` (
  `id` int NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_contractorbusinesstype`
--

INSERT INTO `tbl_contractorbusinesstype` (`id`, `type`, `description`, `status`) VALUES
(1, 'Private', 'Private Business', 1),
(2, 'Public', 'Public Company', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contractordirectors`
--

CREATE TABLE `tbl_contractordirectors` (
  `id` int NOT NULL,
  `contrid` int NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `pinpassport` varchar(100) NOT NULL,
  `nationality` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `date_created` date NOT NULL,
  `changed_by` int DEFAULT NULL,
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_contractordirectors`
--

INSERT INTO `tbl_contractordirectors` (`id`, `contrid`, `fullname`, `pinpassport`, `nationality`, `created_by`, `date_created`, `changed_by`, `date_changed`) VALUES
(1, 1, 'Kimani John', '22334567', '2', 'Admin0', '2022-01-16', NULL, NULL),
(2, 1, 'Kalya Sylvester', '23803895', '2', 'Admin0', '2022-01-16', NULL, NULL),
(3, 2, 'Eng. Kanoti P', '134567', '2', 'Admin0', '2022-01-16', NULL, NULL),
(4, 2, 'Dr. Patasi O', '2004567', '2', 'Admin0', '2022-01-16', NULL, NULL),
(5, 3, 'Ahmed Mohamed', '22347890', '2', 'Admin0', '2022-01-16', NULL, NULL),
(6, 3, 'Eng. Iger Biwott', '1002456', '1', 'Admin0', '2022-01-16', NULL, NULL),
(7, 3, 'Reuben Ojwang', '20008907', '1', 'Admin0', '2022-01-16', NULL, NULL),
(8, 4, 'Eng. Mwangi G', '12347890', '2', 'Admin0', '2022-01-16', NULL, NULL),
(9, 4, 'Eng. Aggrey Sigei', '1903765', '1', 'Admin0', '2022-01-16', NULL, NULL),
(13, 6, 'miho@mailinator.com', '128', '4', 'admin0', '2022-03-05', NULL, NULL),
(34, 8, 'Daudi', '213456789', '2', 'Admin0', '2022-03-20', NULL, NULL),
(45, 7, 'nojigyzex@mailinator.com', '810', '1', 'admin0', '2022-03-31', NULL, NULL),
(46, 5, 'Kitheka Dennis', '23883894', '2', 'admin0', '2022-03-31', NULL, NULL),
(47, 5, 'Nicholas Kosgey', '22803894', '2', 'admin0', '2022-03-31', NULL, NULL),
(48, 5, 'Peter Korir', '23803894', '2', 'admin0', '2022-03-31', NULL, NULL),
(49, 9, 'hico@mailinator.com', '39', '2', 'admin0', '2022-04-03', NULL, NULL),
(51, 11, 'givori@mailinator.com', '232', '2', '1', '2022-04-16', NULL, NULL),
(54, 10, 'xatihifod@mailinator.com', '1000', '1', '1', '2022-04-16', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contractordocuments`
--

CREATE TABLE `tbl_contractordocuments` (
  `id` int NOT NULL,
  `contrid` int NOT NULL,
  `floc` varchar(255) NOT NULL,
  `file_format` varchar(255) DEFAULT NULL,
  `attachment_purpose` varchar(255) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_contractordocuments`
--

INSERT INTO `tbl_contractordocuments` (`id`, `contrid`, `floc`, `file_format`, `attachment_purpose`, `created_by`, `date_created`, `changed_by`, `date_changed`) VALUES
(1, 2, 'uploads/contractordirectors/2_1371_File_UG_CIMES ToRs-converted.docx', 'docx', 'Tend', 'Admin0', '2022-01-16 00:00:00', NULL, NULL),
(2, 4, 'uploads/contractordirectors/4_4618_File_UG_CIMES ToRs.pdf', 'pdf', 'Doc 10', 'Admin0', '2022-01-16 00:00:00', NULL, NULL),
(4, 14, 'uploads/contractordirectors/14_7223_File_Boundaries_ When to Say Yes, How to Say No to Take Control of Your Life ( PDFDrive ).pdf', 'pdf', 'testRatione ad non velit', 'admin0', '2022-03-05 00:00:00', NULL, NULL),
(5, 34, 'uploads/contractordirectors/34_4462_File_Output Name.doc', 'doc', 'Outname', 'Admin0', '2022-03-20 00:00:00', NULL, NULL),
(7, 7, 'uploads/contractordirectors/7_8147_File_Output.pdf', 'pdf', 'test', 'admin0', '2022-03-31 00:00:00', NULL, NULL),
(8, 49, 'uploads/contractordirectors/49_6635_File_Output.pdf', 'pdf', 'Placeat dolorem min', 'admin0', '2022-04-03 00:00:00', NULL, NULL),
(10, 11, 'uploads/contractordirectors/11_7334_File_IMG_46.jpg', 'jpg', 'Numquam tempora tota', '1', '2022-04-16 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contractornationality`
--

CREATE TABLE `tbl_contractornationality` (
  `id` int NOT NULL,
  `nationality` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` int NOT NULL DEFAULT '1',
  `created_by` int DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_contractornationality`
--

INSERT INTO `tbl_contractornationality` (`id`, `nationality`, `description`, `active`, `created_by`, `date_created`) VALUES
(1, 'Visitor', NULL, 1, NULL, '2018-11-22 06:59:00'),
(2, 'Citizen', NULL, 1, NULL, '2018-11-22 06:59:00'),
(3, 'Local Company', NULL, 1, NULL, '2018-11-22 06:59:00'),
(4, 'Foreign Company', NULL, 1, NULL, '2018-11-22 06:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contractorpinstatus`
--

CREATE TABLE `tbl_contractorpinstatus` (
  `id` int NOT NULL,
  `pin_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_contractorpinstatus`
--

INSERT INTO `tbl_contractorpinstatus` (`id`, `pin_status`) VALUES
(1, 'Active'),
(2, 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contractorvat`
--

CREATE TABLE `tbl_contractorvat` (
  `id` int NOT NULL,
  `vat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_contractorvat`
--

INSERT INTO `tbl_contractorvat` (`id`, `vat`) VALUES
(1, 'Yes'),
(2, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cooperates_types`
--

CREATE TABLE `tbl_cooperates_types` (
  `id` int NOT NULL,
  `type` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_cooperates_types`
--

INSERT INTO `tbl_cooperates_types` (`id`, `type`, `description`, `active`) VALUES
(1, 'International Organization', 'An Organization with presence in several countries', 0),
(2, 'National Government', 'National Government as the organization', 0),
(3, 'County Government', 'County Government as the organization', 1),
(4, 'Government Parastatal', 'Government Parastatal', 0),
(5, 'Non Governmental Organization (NGO)', 'Non Governmental Organization', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_countyadmindesignation`
--

CREATE TABLE `tbl_countyadmindesignation` (
  `id` int NOT NULL,
  `designation` varchar(100) NOT NULL,
  `reporting` int NOT NULL,
  `level` int NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_countyadmindesignation`
--

INSERT INTO `tbl_countyadmindesignation` (`id`, `designation`, `reporting`, `level`, `active`) VALUES
(1, 'Assistant  Chief Conservator', 0, 1, 1),
(2, 'Conservator', 1, 2, 1),
(3, 'Forest Station Manager', 2, 3, 1),
(4, 'Senior Forester', 3, 3, 1),
(5, 'Forester', 4, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_currency`
--

CREATE TABLE `tbl_currency` (
  `id` int NOT NULL,
  `code` varchar(100) NOT NULL,
  `currency` varchar(100) NOT NULL,
  `sympol` varchar(20) DEFAULT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_currency`
--

INSERT INTO `tbl_currency` (`id`, `code`, `currency`, `sympol`, `active`) VALUES
(1, 'KES', 'Kenya Shilling', 'Ksh', '1'),
(2, 'USD', 'US Dollar', '$', '1'),
(3, 'EUR', 'Euro', '€', '1'),
(4, 'GBP', 'Pound Sterling', '£', '1'),
(5, 'CNY', 'Yuan', '¥', '1'),
(6, 'JPY', 'Yen', '¥', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_datacollectionfreq`
--

CREATE TABLE `tbl_datacollectionfreq` (
  `fqid` int NOT NULL,
  `frequency` varchar(250) NOT NULL,
  `days` varchar(100) NOT NULL,
  `level` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_by` varchar(100) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_datacollectionfreq`
--

INSERT INTO `tbl_datacollectionfreq` (`fqid`, `frequency`, `days`, `level`, `status`, `created_by`, `date_created`, `modified_by`, `date_modified`) VALUES
(1, 'Daily', '1 days', 1, 0, NULL, NULL, '10', '2022-02-17 12:06:27'),
(2, 'Weekly', '7 days', 2, 0, NULL, NULL, '10', '2022-02-17 12:06:35'),
(3, 'Monthly', '1 month', 3, 1, NULL, NULL, NULL, '2018-08-01 15:23:30'),
(4, 'Quarterly', '3 months', 4, 1, NULL, NULL, NULL, '2018-08-01 15:23:30'),
(5, 'Semi-Annually', '6 months', 5, 0, NULL, NULL, '10', '2022-02-17 12:05:45'),
(6, 'Yearly', '1 year', 6, 0, NULL, NULL, '10', '2022-02-17 12:05:56'),
(7, 'Bi-Annual', '2 Years', 7, 0, NULL, NULL, '10', '2022-02-17 12:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_datacollection_settings`
--

CREATE TABLE `tbl_datacollection_settings` (
  `id` int NOT NULL,
  `fname` varchar(255) NOT NULL,
  `fvalue` int NOT NULL,
  `type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_datacollection_settings`
--

INSERT INTO `tbl_datacollection_settings` (`id`, `fname`, `fvalue`, `type`) VALUES
(1, 'impact', 15, 1),
(2, 'outcome', 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_datagatheringmethods`
--

CREATE TABLE `tbl_datagatheringmethods` (
  `id` int NOT NULL,
  `methods` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `added_by` varchar(100) DEFAULT NULL,
  `date_added` date NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_datagatheringmethods`
--

INSERT INTO `tbl_datagatheringmethods` (`id`, `methods`, `description`, `added_by`, `date_added`, `changed_by`, `date_changed`) VALUES
(2, 'Recorded observation ', NULL, NULL, '0000-00-00', NULL, NULL),
(5, 'Structured interviews ', NULL, NULL, '0000-00-00', NULL, NULL),
(7, 'Survey ', NULL, NULL, '0000-00-00', NULL, NULL),
(8, 'Systematic review of relevant official statistics. ', NULL, NULL, '0000-00-00', NULL, NULL),
(9, 'Electronic Survey', NULL, 'Admin0', '0000-00-00', NULL, NULL),
(10, 'Questionnaire', NULL, 'Admin0', '0000-00-00', NULL, NULL),
(11, 'Focus Group Discussion', NULL, 'Admin0', '0000-00-00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_data_source`
--

CREATE TABLE `tbl_data_source` (
  `id` int NOT NULL,
  `source` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_data_source`
--

INSERT INTO `tbl_data_source` (`id`, `source`, `description`, `created_by`, `date_created`, `changed_by`, `date_changed`) VALUES
(1, 'Exist Documents', 'Exist Documents', 'Admin0', '2019-12-13', NULL, NULL),
(2, 'Interviews', 'Primary Data', 'Admin0', '2019-12-13', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_departments_allocation`
--

CREATE TABLE `tbl_departments_allocation` (
  `id` int NOT NULL,
  `fundid` int NOT NULL,
  `department` int NOT NULL,
  `allocation` double NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `financialyear` int NOT NULL,
  `createdby` varchar(100) NOT NULL,
  `datecreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_disaggregation_type`
--

CREATE TABLE `tbl_disaggregation_type` (
  `id` int NOT NULL,
  `category` varchar(255) NOT NULL,
  `type` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_disaggregation_type`
--

INSERT INTO `tbl_disaggregation_type` (`id`, `category`, `type`) VALUES
(1, 'Geographical Location', 0),
(2, 'Farming Scale', 1),
(3, 'Gender', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_donation_type`
--

CREATE TABLE `tbl_donation_type` (
  `id` int NOT NULL,
  `type` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_donation_type`
--

INSERT INTO `tbl_donation_type` (`id`, `type`, `description`, `status`) VALUES
(1, 'Annual Recurring Donation', 'Donation offered Each Year', 1),
(2, 'One Off Donation', 'Donation offered once in ransom ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_donors`
--

CREATE TABLE `tbl_donors` (
  `dnid` int NOT NULL,
  `donorname` varchar(255) NOT NULL,
  `type` int DEFAULT NULL,
  `category` int NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `title` int DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` int DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comments` text,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `date_created` date NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `statusdate` date DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `user_name` varchar(255) NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_email_settings`
--

CREATE TABLE `tbl_email_settings` (
  `id` int NOT NULL,
  `smtpAutoTLS` varchar(100) NOT NULL,
  `SMTPAuth` varchar(20) NOT NULL,
  `port` int NOT NULL,
  `host` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `SMTPSecure` varchar(10) NOT NULL,
  `createdby` int NOT NULL,
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_email_settings`
--

INSERT INTO `tbl_email_settings` (`id`, `smtpAutoTLS`, `SMTPAuth`, `port`, `host`, `username`, `password`, `SMTPSecure`, `createdby`, `dateCreated`) VALUES
(1, 'true', 'true', 587, 'smtp.ionos.es', 'info@odesatv.es', 'Test@2021#', 'tls', 1, '2022-03-03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_email_templates`
--

CREATE TABLE `tbl_email_templates` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` int NOT NULL,
  `createdBy` int NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_email_templates`
--

INSERT INTO `tbl_email_templates` (`id`, `title`, `content`, `type`, `createdBy`, `active`, `dateCreated`) VALUES
(1, 'Email Template', 'Hola [FIRST_NAME], <br>Usted ha sido registrado como Delaer en [SITE_NAME].Pinche aqui [SITE_URL] para acceder a su cuenta y use los datos siguientes para acceder.<br><br>Email: [EMAIL]<br>Passcode: [PASSWORD]', 1, 2, 1, '2022-01-14'),
(2, 'Welcome Template', 'Hello [FIRST_NAME], \r\n<br>\r\nYour bike service number  [SERVICE_NUMBER] was successfully done by [DEALER] on date [DATE].\r\n<br>\r\n<br>\r\n<br>', 0, 1, 1, '2021-09-15'),
(3, 'Event Received Template', 'Hello [FIRST_NAME], <br><br>We would like to thank you for purchasing ATV bike from our dealer [DEALER]. Welcome to the ODES family!', 0, 1, 1, '2021-09-08'),
(4, 'Others: Test Template', 'Hello [FIRST_NAME], \r\n<br>\r\nYour bike service number  [SERVICE_NUMBER] was successfully done by [DEALER] on date [DATE].\r\n<br>\r\n<br>\r\n<br>\r\n<br>', 0, 1, 0, '2022-01-22'),
(5, 'Test Template', 'Testing [SITE_URL] [SITE_NAME] [FIRST_NAME] [LAST_NAME] [ADDRESS] [CITY] [MOBILE_NUMBER] [EMAIL] [PASSWORD] <br><br><br><br><br><br><br><br><br><br>', 0, 1, 1, '2022-01-22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employees_leave_categories`
--

CREATE TABLE `tbl_employees_leave_categories` (
  `id` int NOT NULL,
  `leavename` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `days` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `addedby` int NOT NULL,
  `date_added` date NOT NULL,
  `modifiedby` int DEFAULT NULL,
  `date_modified` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_employees_leave_categories`
--

INSERT INTO `tbl_employees_leave_categories` (`id`, `leavename`, `description`, `days`, `status`, `addedby`, `date_added`, `modifiedby`, `date_modified`) VALUES
(1, 'Annual Leave', 'Currently Annual leave for staff shall be earned at the following rates:\r\n30 working days\r\nLeave year shall be from 1st January to 31st December.', 30, 1, 1, '2019-07-02', NULL, NULL),
(2, 'Maternity Leave', '(a) Female employees shall be entitled to ninety (90) calendar days maternity leave with full pay. In this regard no female employee shall forfeit her annual leave entitlement on account of having taken maternity leave.\r\n\r\n(b) Maternity leave may be taken approximately one month before the expected date of confinement, and application for maternity leave should be submitted to the Director General through the Head of Department at least one month before the date of commencement of leave.', 90, 1, 1, '2019-07-02', NULL, NULL),
(3, 'Paternity Leave', 'Male employees shall be entitled to ten (10) working days paternity leave with full pay during maternity confinement of their legal spouse. In this regard, it is clarified that in case of a male employee with more than one (1) spouse he will be entitled to paternity leave only in respect of the wife registered with NHIF contributor’s card held by the employer and such leave shall be taken not more than once in a year.', 10, 1, 1, '2019-07-02', NULL, NULL),
(4, 'Special Leave', 'The Employer may at her discretion, grant special leave for any purpose not covered by the categories of leave set out in this agreement. In granting such leave, Employer shall take into account the frequency of such absences on a member’s Organization work.', 0, 1, 1, '2019-07-02', NULL, NULL),
(5, 'Leave on Compassionate Grounds', 'An employee desiring to take compassionate leave shall with prior arrangement with the Head of Department and approval by Director General be granted upto a maximum of ten (10) working days in case of emergencies/dire needs concerning a member of a nuclear family.', 10, 1, 1, '2019-07-02', NULL, NULL),
(6, 'Study Leave', 'i) Study leave shall only be granted by the Organization on the basis of the needs of the Organization and the interests of staff development.\r\nii) Study leave may be of any duration as the Organization may determine and subject to such conditions as the Organization may lay down.\r\niii) The Organization may with reasons accept or reject an application for study leave.', 0, 1, 1, '2019-07-02', NULL, NULL),
(7, 'Sick Leave', '(a) A member of staff who is prevented by illness from carrying out his/her duties is required to furnish a medical certificate signed by a qualified Medical Practitioner or use such other mode of communication to this effect within two consecutive working days of absence.\r\n(b) A member of staff may be granted sick leave at the following rates in a calendar year subject to the following maximum:\r\n\r\nOn Full Pay\r\nAbove 5 years of Service – 6 months\r\nBelow 5 years of Service – 3 months\r\nContract Staff – 2 months\r\n\r\nOn Half Pay\r\nAbove 5 years of Service – 6 months\r\nBelow 5 years of Service – 3 months\r\nContract Staff – 2 months\r\n\r\n(c) On expiry of the maximum days provided above, the Organization will appoint a Medical Board to examine the employee and after considering the Medical Boards’ report have the right to terminate the services of the employee on medical grounds. Termination of appointment on the basis of a Medical Board Report shall not be made until 9 months after the receipt by the employer of such medical report. The employee shall remain on half pay pending determination of the case by the Organization.', 0, 1, 1, '2019-07-02', NULL, NULL),
(8, 'Sabbatical Leave', 'i) The Organization considers that in the interests of both the Employer and members of staff it is desirable that members of staff should be released from their normal duties at intervals during their career to undertake further study.\r\n\r\nii) Sabbatical leave will be granted to members of staff on permanent terms only after completion of six years continuous service with the Organization from the date of appointment or since return from sabbatical or study leave. The Director General will determine when such leave may be taken.\r\n\r\niii) Sabbatical leave shall be granted at the rate of nine (9) months after six (6) years of continuous service. However, should need arise the employer may extend upon justifiable request.\r\n\r\niv) Sabbatical leave will be granted on full pay and normally in extension of a long vacation.\r\n\r\nv) Applications for sabbatical leave shall set out in detail the course of study proposed, the duration of leave requested and the financial assistance sought. Applications shall be sent to the Director General with copies to the Head of Department concerned.', 0, 1, 1, '2019-07-02', NULL, NULL),
(9, 'Leave of Absence', 'Upon request, employees shall be granted unpaid leave of absence when on secondment to public institutions or for personal reasons, on the recommendation of the Head of Department and approval of the Organization.', 0, 1, 1, '2019-07-02', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee_leave`
--

CREATE TABLE `tbl_employee_leave` (
  `id` int NOT NULL,
  `employee` varchar(100) NOT NULL,
  `leavecategory` int NOT NULL,
  `days` int NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `caretaker` int DEFAULT NULL,
  `comments` text,
  `added_by` varchar(100) NOT NULL,
  `date_added` date NOT NULL,
  `modifiedby` varchar(100) DEFAULT NULL,
  `date_modified` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_employee_leave`
--

INSERT INTO `tbl_employee_leave` (`id`, `employee`, `leavecategory`, `days`, `startdate`, `enddate`, `caretaker`, `comments`, `added_by`, `date_added`, `modifiedby`, `date_modified`) VALUES
(1, '6', 2, 25, '2014-11-15', '2022-04-26', NULL, 'Nulla non reiciendis', 'admin0', '2022-03-22', NULL, NULL),
(2, '6', 2, 25, '2014-11-15', '2022-04-26', NULL, 'Nulla non reiciendis', 'admin0', '2022-03-22', NULL, NULL),
(3, '6', 2, 25, '2014-11-15', '2022-04-26', NULL, 'Nulla non reiciendis', 'admin0', '2022-03-22', NULL, NULL),
(4, '6', 2, 25, '2014-11-15', '2022-04-26', NULL, 'Nulla non reiciendis', 'admin0', '2022-03-22', NULL, NULL),
(5, '7', 1, 10, '2022-03-31', '2022-04-05', NULL, 'Comments', 'admin0', '2022-03-22', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee_leave_bal`
--

CREATE TABLE `tbl_employee_leave_bal` (
  `id` int NOT NULL,
  `category` int NOT NULL,
  `staff` int NOT NULL,
  `year` int NOT NULL,
  `balforward` int NOT NULL DEFAULT '0',
  `days` int NOT NULL DEFAULT '0',
  `totaldays` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_escalations`
--

CREATE TABLE `tbl_escalations` (
  `id` int NOT NULL,
  `category` varchar(100) NOT NULL,
  `owner` int NOT NULL,
  `projid` int NOT NULL,
  `itemid` int NOT NULL,
  `comments` text NOT NULL,
  `escalated_by` varchar(100) NOT NULL,
  `date_escalated` date NOT NULL,
  `date_on_hold` date DEFAULT NULL,
  `date_cancelled` date DEFAULT NULL,
  `date_continue` date DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_escalations`
--

INSERT INTO `tbl_escalations` (`id`, `category`, `owner`, `projid`, `itemid`, `comments`, `escalated_by`, `date_escalated`, `date_on_hold`, `date_cancelled`, `date_continue`, `status`) VALUES
(1, 'issue', 37, 8, 1, 'Testing project issue escalation!', '1', '2022-03-02', '2022-03-04', NULL, '2022-03-05', 5),
(2, 'issue', 1, 8, 6, 'test', '1', '2022-03-23', NULL, NULL, '2022-03-24', 6),
(3, 'issue', 1, 8, 11, 'Deal', '1', '2022-03-23', NULL, NULL, NULL, 6),
(4, 'issue', 1, 38, 12, 'BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB', '1', '2022-03-24', '2022-03-24', NULL, NULL, 6),
(5, 'issue', 36, 38, 13, 'fffffffffffffffffffff', '1', '2022-03-26', NULL, NULL, NULL, 1),
(6, 'issue', 36, 38, 13, 'fffffffffffffffffffff', '1', '2022-03-26', NULL, NULL, NULL, 1),
(7, 'issue', 1, 55, 17, 'testing', '1', '2022-04-04', '2022-04-04', NULL, '2022-04-04', 6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_estage`
--

CREATE TABLE `tbl_estage` (
  `esid` int NOT NULL,
  `stages` varchar(255) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_estage`
--

INSERT INTO `tbl_estage` (`esid`, `stages`, `active`) VALUES
(1, 'Mid-term Evaluation', '1'),
(2, 'Formative Evaluation', '1'),
(3, 'Post Evaluation', '1'),
(4, 'Final Evaluation', '0'),
(5, 'Summative Evaluation', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_evaluation`
--

CREATE TABLE `tbl_evaluation` (
  `eid` int NOT NULL,
  `projid` int NOT NULL,
  `projcode` varchar(255) NOT NULL,
  `projname` varchar(255) NOT NULL,
  `projcommunity` varchar(255) NOT NULL,
  `projlga` varchar(255) NOT NULL,
  `projstate` varchar(255) NOT NULL,
  `background` text NOT NULL,
  `purpose` text NOT NULL,
  `objectives` text NOT NULL,
  `scope` text NOT NULL,
  `main_questions` text NOT NULL COMMENT 'Outcomes: what has been achieved as a results of the outputs',
  `approach_methods` text NOT NULL,
  `findings` text,
  `conclusions` text,
  `recommendations` text,
  `attachments` varchar(255) DEFAULT NULL,
  `team` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `eva_stage` varchar(250) NOT NULL,
  `edate` date NOT NULL,
  `dateentered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_files`
--

CREATE TABLE `tbl_files` (
  `fid` int NOT NULL,
  `projid` int DEFAULT NULL,
  `projstage` int NOT NULL,
  `monitoringid` int DEFAULT NULL,
  `general_inspection_id` int DEFAULT NULL,
  `form_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `opid` int DEFAULT NULL,
  `filename` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `ftype` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `floc` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `fcategory` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '0',
  `reason` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `uploaded_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `date_uploaded` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `tbl_files`
--

INSERT INTO `tbl_files` (`fid`, `projid`, `projstage`, `monitoringid`, `general_inspection_id`, `form_id`, `opid`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES
(1, 1, 1, NULL, NULL, NULL, NULL, 'New Text Document.txt', 'txt', 'uploads/main-project/1_1_New Text Document.txt', 'Project Planning', 'test', 'admin0', '2022-01-16'),
(2, NULL, 1, NULL, NULL, NULL, NULL, '1-UG_CIMES ToRs.pdf', 'pdf', 'uploads/financiers/1-UG_CIMES ToRs.pdf', 'Financiers', 'Doc1', 'Admin0', '2022-01-16'),
(3, NULL, 2, NULL, NULL, NULL, NULL, '2-UG_CIMES ToRs.pdf', 'pdf', 'uploads/financiers/2-UG_CIMES ToRs.pdf', 'Financiers', 'Doc2', 'Admin0', '2022-01-16'),
(4, NULL, 3, NULL, NULL, NULL, NULL, '3-UG_CIMES ToRs.pdf', 'pdf', 'uploads/financiers/3-UG_CIMES ToRs.pdf', 'Funding', 'Doc7', 'Admin0', '2022-01-16'),
(5, 2, 1, NULL, NULL, NULL, NULL, 'New Text Document.txt', 'txt', 'uploads/main-project/2_1_New Text Document.txt', 'Project Planning', 'TEST', 'admin0', '2022-01-16'),
(6, 3, 1, NULL, NULL, NULL, NULL, 'PROJECT LIST.xlsx', 'xlsx', 'uploads/main-project/3_1_PROJECT LIST.xlsx', 'Project Planning', 'Project details and implementation plan', 'admin0', '2022-01-16'),
(7, 4, 1, NULL, NULL, NULL, NULL, 'New Text Document.txt', 'txt', 'uploads/main-project/4_1_New Text Document.txt', 'Project Planning', 'Detailed methodology provided by the Contractor', 'admin0', '2022-01-16'),
(8, 5, 1, NULL, NULL, NULL, NULL, 'UG_CIMES ToRs.pdf', 'pdf', 'uploads/main-project/5_1_UG_CIMES ToRs.pdf', 'Project Planning', 'Project Doc1', 'Admin0', '2022-01-16'),
(9, 9, 1, NULL, NULL, NULL, NULL, 'New Text Document.txt', 'txt', 'uploads/main-project/9_1_New Text Document.txt', 'Project Planning', 'PROJECT IMPLEMENTATION PLAN ', 'admin0', '2022-01-18'),
(10, 8, 10, 2, NULL, 'GWDTL', NULL, '8-GWDTL-63-New Text Document.txt', 'txt', 'uploads/monitoring/other-files/8-GWDTL-63-New Text Document.txt', 'GWDTL', 'TEST', '1', '2022-01-21'),
(11, 11, 1, NULL, NULL, NULL, NULL, 'New Text Document.txt', 'txt', 'uploads/main-project/11_1_New Text Document.txt', 'Project Planning', 'test', 'admin0', '2022-01-24'),
(12, 15, 1, NULL, NULL, NULL, NULL, 'New Text Document.txt', 'txt', 'uploads/main-project/15_1_New Text Document.txt', 'Project Planning', 'test', 'admin0', '2022-01-28'),
(13, 16, 1, NULL, NULL, NULL, NULL, 'PROJECT LIST.xlsx', 'xlsx', 'uploads/main-project/16_1_PROJECT LIST.xlsx', 'Project Planning', 'TEST', 'admin0', '2022-01-28'),
(14, 18, 1, NULL, NULL, NULL, NULL, '272779_2S.jpg', 'jpg', 'uploads/main-project/18_1_272779_2S.jpg', 'Project Planning', 'test', 'admin0', '2022-02-01'),
(15, 20, 1, NULL, NULL, NULL, NULL, '272779_2S.jpg', 'jpg', 'uploads/main-project/20_1_272779_2S.jpg', 'Project Planning', 'test', 'admin0', '2022-02-02'),
(16, 23, 1, NULL, NULL, NULL, NULL, 'New Text Document.txt', 'txt', 'uploads/main-project/23_1_New Text Document.txt', 'Project Planning', 'TESTING TESTING ', 'admin0', '2022-02-08'),
(17, 25, 1, NULL, NULL, NULL, NULL, 'New Text Document.txt', 'txt', 'uploads/main-project/25_1_New Text Document.txt', 'Project Planning', 'PROJECT DETAILS DOCUMENT', 'admin0', '2022-02-17'),
(18, NULL, 4, NULL, NULL, NULL, NULL, '4-evaluation-pdf-report.pdf', 'pdf', 'uploads/financiers/4-evaluation-pdf-report.pdf', 'Financiers', 'Test', 'admin0', '2022-02-22'),
(19, 27, 1, NULL, NULL, NULL, NULL, 'IMS-STOCK.xlsx', 'xlsx', 'uploads/main-project/27_1_IMS-STOCK.xlsx', 'Project Planning', 'PROJECT detailed plan', 'admin0', '2022-02-25'),
(20, 28, 1, NULL, NULL, NULL, NULL, 'PROJECT LIST.xlsx', 'xlsx', 'uploads/main-project/28_1_PROJECT LIST.xlsx', 'Project Planning', 'deatiled plan', 'admin0', '2022-02-25'),
(21, 8, 1, NULL, 8, NULL, NULL, 'tree-736885__480.jpg', 'jpg', 'uploads/inspection/8_1_tree-736885__480.jpg', 'Project Inspection', 'testing', '1', '2022-03-01'),
(22, 8, 10, NULL, 11, NULL, NULL, 'tree-736885__480.jpg', 'jpg', 'uploads/inspection/16461242338_10_tree-736885__480.jpg', 'Project Inspection', 'Testing', '1', '2022-03-01'),
(23, 8, 10, NULL, 12, NULL, NULL, 'tree-736885__480.jpg', 'jpg', 'uploads/inspection/16461242728_10_tree-736885__480.jpg', 'Project Inspection', 'Testing', '1', '2022-03-01'),
(24, 8, 10, NULL, 13, NULL, NULL, 'tree-736885__480.jpg', 'jpg', 'uploads/inspection/16461243258_10_tree-736885__480.jpg', 'Project Inspection', 'testing', '1', '2022-03-01'),
(25, 8, 10, NULL, 15, NULL, NULL, 'tree-736885__480.jpg', 'jpg', 'uploads/inspection/16461251918_10_tree-736885__480.jpg', 'Project Inspection', 'testing', '1', '2022-03-01'),
(26, 8, 10, NULL, 15, NULL, NULL, 'istockphoto-1093110112-612x612.jpg', 'jpg', 'uploads/inspection/16461251918_10_istockphoto-1093110112-612x612.jpg', 'Project Inspection', 'testing', '1', '2022-03-01'),
(27, 8, 10, NULL, 15, NULL, NULL, 'rainbow-love-heart-background-red-wood-60045149.jpg', 'jpg', 'uploads/inspection/16461251918_10_rainbow-love-heart-background-red-wood-60045149.jpg', 'Project Inspection', 'testing pop', '1', '2022-03-01'),
(28, 8, 10, NULL, 16, NULL, NULL, '272779_2S.jpg', 'jpg', 'uploads/inspection/16461335588_10_272779_2S.jpg', 'Project Inspection', 'test', '1', '2022-03-01'),
(29, NULL, 7, NULL, NULL, NULL, NULL, '7-POSTER.pdf', 'pdf', 'uploads/financiers/7-POSTER.pdf', 'Funding', 'Docy', 'Admin0', '2022-03-05'),
(30, 29, 1, NULL, NULL, NULL, NULL, 'Programme Based Budget for the Year Ending 30th June', 'rogramme Based Budget for the Year Ending 30th June', 'uploads/main-project/29_1_Programme Based Budget for the Year Ending 30th June', 'Project Planning', 'PROJECT DETAILS', 'admin0', '2022-03-15'),
(31, 8, 10, NULL, 18, NULL, NULL, 'groot.jpeg', 'jpeg', 'uploads/inspection/16475836868_10_groot.jpeg', 'Project Inspection', 'Description', '1', '2022-03-18'),
(32, 34, 1, NULL, NULL, NULL, NULL, 'invoice draft.pdf', 'pdf', 'uploads/main-project/34_1_invoice draft.pdf', 'Project Planning', 'DocTest', 'Admin0', '2022-03-20'),
(33, 35, 1, NULL, NULL, NULL, NULL, 'invoice draft.pdf', 'pdf', 'uploads/main-project/35_1_invoice draft.pdf', 'Project Planning', 'Doci', 'Admin0', '2022-03-20'),
(34, NULL, 8, NULL, NULL, NULL, NULL, '8-Evaluation-Plan-template.docx', 'docx', 'uploads/financiers/8-Evaluation-Plan-template.docx', 'Funding', 'Doc345', 'Admin0', '2022-03-20'),
(35, NULL, 9, NULL, NULL, NULL, NULL, '9-PROJECT CHARTER.docx', 'docx', 'uploads/financiers/9-PROJECT CHARTER.docx', 'Funding', 'doccccc', 'Admin0', '2022-03-22'),
(36, 37, 1, NULL, NULL, NULL, NULL, 'Inception Report (1).doc', 'doc', 'uploads/main-project/37_1_Inception Report (1).doc', 'Project Planning', 'DBK', 'Admin0', '2022-03-23'),
(37, 8, 10, 7, NULL, 'SJLLP', NULL, '8-SJLLP-63-dd164d51-county-government-uasin-gishu-progra (2).pdf', 'pdf', 'uploads/monitoring/other-files/8-SJLLP-63-dd164d51-county-government-uasin-gishu-progra (2).pdf', 'SJLLP', 'doc5', '1', '2022-03-23'),
(38, 8, 10, 8, NULL, 'SJLYY', NULL, '8-SJLYY-63-List_of_KOI_details_EN_2021.pdf', 'pdf', 'uploads/monitoring/other-files/8-SJLYY-63-List_of_KOI_details_EN_2021.pdf', 'SJLYY', 'Doc78', '1', '2022-03-23'),
(39, 8, 10, NULL, 19, NULL, NULL, 'Dashboard-TI.png', 'png', 'uploads/inspection/16480220918_10_Dashboard-TI.png', 'Project Inspection', 'DASH', '1', '2022-03-23'),
(40, 8, 10, NULL, 20, NULL, NULL, 'B30.png', 'png', 'uploads/inspection/16480227528_10_B30.png', 'Project Inspection', 'inspection', '1', '2022-03-23'),
(41, 39, 1, NULL, NULL, NULL, NULL, 'WORK PROGRAMME.pdf', 'pdf', 'uploads/main-project/39_1_WORK PROGRAMME.pdf', 'Project Planning', 'programme', 'Admin0', '2022-03-24'),
(42, 38, 10, 13, NULL, 'SPFET', NULL, '38-SPFET-134-INCEPTION REPORT.doc', 'doc', 'uploads/monitoring/other-files/38-SPFET-134-INCEPTION REPORT.doc', 'SPFET', 'xxxxxxxxxxxxxxxxxxxxxx', '1', '2022-03-24'),
(46, 43, 1, NULL, NULL, NULL, NULL, 'ToR.doc', 'doc', 'uploads/main-project/43_1_ToR.doc', 'Project Planning', 'tor', 'Admin0', '2022-03-28'),
(47, 45, 1, NULL, NULL, NULL, NULL, 'INVOICE_PROJTRAC - Google Docs.pdf', 'pdf', 'uploads/main-project/45_1_INVOICE_PROJTRAC - Google Docs.pdf', 'Project Planning', 'proj', 'Admin0', '2022-03-30'),
(44, 41, 1, NULL, NULL, NULL, NULL, 'System Requirements.pdf', 'pdf', 'uploads/main-project/41_1_System Requirements.pdf', 'Project Planning', 'Req', 'Admin0', '2022-03-25'),
(45, 42, 1, NULL, NULL, NULL, NULL, 'System Requirements.pdf', 'pdf', 'uploads/main-project/42_1_System Requirements.pdf', 'Project Planning', 'SR', 'Admin0', '2022-03-26'),
(48, 49, 1, NULL, NULL, NULL, NULL, 'Access Levels.doc', 'doc', 'uploads/main-project/49_1_Access Levels.doc', 'Project Planning', 'gg', '1', '2022-04-01'),
(49, NULL, 14, NULL, NULL, NULL, NULL, '14-FPKhloyXsAAOdKh.jpeg', 'jpeg', 'uploads/financiers/14-FPKhloyXsAAOdKh.jpeg', 'Funding', 'Sed nulla quis quibu', 'admin0', '2022-04-04'),
(50, 55, 10, 20, NULL, 'URNLG', NULL, '55-URNLG-170-WorkFlow.doc', 'doc', 'uploads/monitoring/other-files/55-URNLG-170-WorkFlow.doc', 'URNLG', 'List', '1', '2022-04-04'),
(51, 45, 10, 21, NULL, 'UROXO', NULL, '45-UROXO-151-PROGRAMME.doc', 'doc', 'uploads/monitoring/other-files/45-UROXO-151-PROGRAMME.doc', 'UROXO', 'List', '1', '2022-04-04'),
(52, 49, 10, 22, NULL, 'URWJF', NULL, '49-URWJF-164-Access Levels.doc', 'doc', 'uploads/monitoring/other-files/49-URWJF-164-Access Levels.doc', 'URWJF', 'doc', '1', '2022-04-04'),
(53, 49, 10, NULL, 23, NULL, NULL, 'pic.webp', 'webp', 'uploads/inspection/164908382849_10_pic.webp', 'Project Inspection', 'pic1', '1', '2022-04-04'),
(54, NULL, 8, NULL, NULL, NULL, NULL, '8-FPfy4VVXMAgKLjl.jpeg', 'jpeg', 'uploads/financiers/8-FPfy4VVXMAgKLjl.jpeg', 'Financiers', 'Vel laboriosam ipsa', '34', '2022-04-05'),
(55, NULL, 9, NULL, NULL, NULL, NULL, '9-FP_0INfWYAIEcUg.jpeg', 'jpeg', 'uploads/financiers/9-FP_0INfWYAIEcUg.jpeg', 'Financiers', 'Incidunt error omni', '1', '2022-04-17'),
(56, NULL, 10, NULL, NULL, NULL, NULL, '10-FP_0INfWYAIEcUg.jpeg', 'jpeg', 'uploads/financiers/10-FP_0INfWYAIEcUg.jpeg', 'Financiers', 'Incidunt error omni', '1', '2022-04-17'),
(57, NULL, 10, NULL, NULL, NULL, NULL, '10-WhatsApp Image 2022-03-26 at 3.11.09 PM.jpeg', 'jpeg', 'uploads/financiers/10-WhatsApp Image 2022-03-26 at 3.11.09 PM.jpeg', 'Financiers', '33', '1', '2022-04-17'),
(58, NULL, 10, NULL, NULL, NULL, NULL, '10-273567384_7082070515198629_1805019500230902247_n.jpg', 'jpg', 'uploads/financiers/10-273567384_7082070515198629_1805019500230902247_n.jpg', 'Financiers', '77', '1', '2022-04-17'),
(59, NULL, 15, NULL, NULL, NULL, NULL, '15-WhatsApp Image 2022-03-26 at 3.12.45 PM.jpeg', 'jpeg', 'uploads/financiers/15-WhatsApp Image 2022-03-26 at 3.12.45 PM.jpeg', 'Funding', 'Debitis quibusdam ei', '1', '2022-04-17'),
(60, NULL, 17, NULL, NULL, NULL, NULL, '17-273661006_4815991395184905_3329807975205578301_n.jpg', 'jpg', 'uploads/financiers/17-273661006_4815991395184905_3329807975205578301_n.jpg', 'Funding', 'Tenetur est sint i', '1', '2022-04-17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_filetypes`
--

CREATE TABLE `tbl_filetypes` (
  `ftid` int NOT NULL,
  `filetype` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_filetypes`
--

INSERT INTO `tbl_filetypes` (`ftid`, `filetype`) VALUES
(1, 'JPG'),
(2, 'JPEG'),
(3, 'BMP'),
(4, 'GIF'),
(5, 'PNG'),
(6, 'PDF'),
(7, 'MS Word'),
(8, 'MS Excel'),
(9, 'MS PowerPoint'),
(10, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_financiers`
--

CREATE TABLE `tbl_financiers` (
  `id` int NOT NULL,
  `financier` varchar(255) NOT NULL,
  `type` int NOT NULL,
  `contact` varchar(255) NOT NULL,
  `title` int NOT NULL,
  `designation` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` int NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comments` text,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `active` int NOT NULL DEFAULT '1',
  `statusdate` date DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_financiers`
--

INSERT INTO `tbl_financiers` (`id`, `financier`, `type`, `contact`, `title`, `designation`, `address`, `city`, `state`, `country`, `phone`, `email`, `comments`, `startdate`, `enddate`, `active`, `statusdate`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(1, 'Exchequer', 2, 'Keino P', 2, 'Director', '223344 Eldoret', 'Eldoret', 'Rift Valley', 100, '0720941928', 'kkipe15@gmail.com', '<p>Share</p>', NULL, NULL, 1, NULL, 'Admin0', '2022-01-16', 'Admin0', NULL),
(2, 'County Government of Uasin Gishu', 1, 'Kibet Leonard', 4, 'Manager', '5566 Eldoret', 'Eldoret', 'Rift Valley', 100, '0733467811', 'kkipe15@gmail.com', '<p>Revenue collection</p>', NULL, NULL, 1, NULL, 'Admin0', '2022-01-16', NULL, NULL),
(3, 'World Bank', 3, 'Pattni', 2, 'Economist', 'P.O Box 6734-00200 Nairobi', 'Nairobi', 'Nairobi', 100, '733467811', 'pkorir59@gmail.com', '<p>xxxxxxxxxxxxxxxxxx</p>', NULL, NULL, 1, NULL, 'Admin0', '2022-01-16', NULL, NULL),
(4, 'ABC', 1, 'ABC Person', 4, 'Manager', '1234', 'Nairobi', 'Nairobi', 72, '020383838838', 'abc@gmail.com', 'test', NULL, NULL, 1, NULL, 'admin0', '2022-02-22', NULL, NULL),
(5, 'African Development Bank', 3, 'Hopsman Staunch', 2, 'Chief Partnership Manager', 'P.O Box 8900 Nairobi', 'Nairobi', 'Nairobi', 100, '254 111222333', 'hs123@gmail.com', '<p>African Development Bank</p>', NULL, NULL, 1, NULL, '1', '2022-03-05', NULL, NULL),
(6, 'Kenya Red Cross Society', 4, 'Remmy Oundo', 2, 'Regional Coordinator', '9000 Nbi', 'Eldoret', 'Eldoret', 100, '0733467811', 'pkorir59@gmail.com', '<p>Testing</p>', NULL, NULL, 1, NULL, '1', '2022-03-20', NULL, NULL),
(7, 'USAid', 4, 'Kimathi Dick', 4, 'Partnership Manager', '1234 Nairobi', 'Nairobi', 'Nairobi', 100, '2349002', 'p.usaid@gmail.com', '<p>Courtesy of the US government</p>', NULL, NULL, 1, NULL, '1', '2022-03-22', NULL, NULL),
(8, 'Excepturi ullam vita', 1, 'Minus quod libero as', 5, 'Ipsam eos duis ipsum', 'Quis officia omnis n', 'Dolor cillum quod pa', 'Ex libero aut totam ', 26, '+1 (843) 202-1759', 'baxavanic@mailinator.com', '<p>testing</p>', NULL, NULL, 1, NULL, '34', '2022-04-05', NULL, NULL),
(9, 'Velit facilis obcaec', 4, 'Quo id eligendi omn', 4, 'Ratione voluptate ob', 'Dolor quisquam disti', 'Cillum et minima inc', 'Dolor ipsum adipisic', 209, '+1 (582) 934-9373', 'legoraren@mailinator.com', '<p>testing</p>', NULL, NULL, 1, NULL, '1', '2022-04-17', NULL, NULL),
(10, 'Velit facilis obcaec', 4, 'Quo id eligendi omn', 4, 'Ratione voluptate ob', 'Dolor quisquam disti', 'Cillum et minima inc', 'Dolor ipsum adipisic', 209, '+1 (582) 934-9373', 'legoraren@mailinator.com', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p>&nbsp;</p>', NULL, NULL, 1, NULL, '1', '2022-04-17', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_financier_status_comments`
--

CREATE TABLE `tbl_financier_status_comments` (
  `id` int NOT NULL,
  `fnid` int NOT NULL,
  `comments` varchar(255) NOT NULL,
  `statusdate` date NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_financier_type`
--

CREATE TABLE `tbl_financier_type` (
  `id` int NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_financier_type`
--

INSERT INTO `tbl_financier_type` (`id`, `type`, `description`) VALUES
(1, 'Self', 'Self Financing'),
(2, 'GoK', 'GoK Equitable Share'),
(3, 'Granter', 'Conditional granter'),
(4, 'Donor', 'Donor');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_fiscal_year`
--

CREATE TABLE `tbl_fiscal_year` (
  `id` int NOT NULL,
  `year` varchar(100) NOT NULL,
  `yr` int NOT NULL,
  `sdate` datetime NOT NULL,
  `edate` datetime NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_fiscal_year`
--

INSERT INTO `tbl_fiscal_year` (`id`, `year`, `yr`, `sdate`, `edate`, `status`) VALUES
(1, '2018/2019', 2018, '2018-07-01 00:00:00', '2019-06-30 00:00:00', 1),
(2, '2019/2020', 2019, '2019-07-01 00:00:00', '2020-06-30 00:00:00', 1),
(3, '2020/2021', 2020, '2020-07-01 00:00:00', '2021-06-30 00:00:00', 1),
(4, '2021/2022', 2021, '2021-07-01 00:00:00', '2022-06-30 00:00:00', 1),
(5, '2022/2023', 2022, '2022-07-01 00:00:00', '2023-06-30 00:00:00', 1),
(6, '2023/2024', 2023, '2023-07-01 00:00:00', '2024-06-30 00:00:00', 1),
(7, '2024/2025', 2024, '2024-07-01 00:00:00', '2025-06-30 00:00:00', 1),
(8, '2025/2026', 2025, '2025-07-01 00:00:00', '2026-06-30 00:00:00', 1),
(9, '2026/2027', 2026, '2026-07-01 00:00:00', '2027-06-30 00:00:00', 1),
(10, '2027/2028', 2027, '2027-07-01 00:00:00', '2028-06-30 00:00:00', 1),
(11, '2028/2029', 2028, '2028-07-01 00:00:00', '2029-06-30 00:00:00', 1),
(12, '2029/2030', 2029, '2029-07-01 00:00:00', '2030-06-30 00:00:00', 1),
(13, '2030/2031', 2030, '2030-07-01 00:00:00', '2031-06-30 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_funder`
--

CREATE TABLE `tbl_funder` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` int NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_funding`
--

CREATE TABLE `tbl_funding` (
  `fid` int NOT NULL,
  `source` int NOT NULL,
  `amtrec` decimal(19,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(255) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `financial_year` int NOT NULL,
  `purpose` text NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `receiver` varchar(300) NOT NULL,
  `remarks` text NOT NULL,
  `date` date NOT NULL,
  `enteredby` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_funding_type`
--

CREATE TABLE `tbl_funding_type` (
  `id` int NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `category` int NOT NULL DEFAULT '1',
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_funding_type`
--

INSERT INTO `tbl_funding_type` (`id`, `type`, `description`, `category`, `status`) VALUES
(1, 'County Revenue Fund', 'Funds collected within the county as revenue + Equitable share ', 1, 1),
(2, 'Equitable Share', 'Funds from the exchequer', 2, 0),
(3, 'Conditional Grant', 'Conditional Grant from Development Partners', 3, 1),
(4, 'Donation', 'Donation', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_funds`
--

CREATE TABLE `tbl_funds` (
  `id` int NOT NULL,
  `funder` int NOT NULL,
  `fund_code` varchar(100) NOT NULL,
  `financial_year` int NOT NULL,
  `amount` double NOT NULL,
  `currency` int NOT NULL,
  `exchange_rate` float NOT NULL,
  `date_funds_released` date NOT NULL,
  `funds_purpose` varchar(255) DEFAULT NULL,
  `grant_life_span` int DEFAULT NULL,
  `grant_installments` int DEFAULT NULL,
  `grant_installment_date` varchar(100) DEFAULT NULL,
  `recorded_by` varchar(100) NOT NULL,
  `date_recorded` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_funds`
--

INSERT INTO `tbl_funds` (`id`, `funder`, `fund_code`, `financial_year`, `amount`, `currency`, `exchange_rate`, `date_funds_released`, `funds_purpose`, `grant_life_span`, `grant_installments`, `grant_installment_date`, `recorded_by`, `date_recorded`, `updated_by`, `date_updated`) VALUES
(1, 2, '99988', 4, 8500000000, 1, 1, '2021-07-01', '', 0, 0, '0', 'Admin0', '2022-01-16', NULL, NULL),
(2, 1, '5566', 4, 10000000000, 1, 1, '2021-07-14', '', 0, 0, '0', 'Admin0', '2022-01-16', NULL, NULL),
(3, 3, '123', 4, 5000000000, 1, 100, '2021-07-01', '2', 0, 0, '0', 'Admin0', '2022-01-16', NULL, NULL),
(4, 1, '1234', 4, 50000000000, 1, 1, '2022-02-25', '', 0, 0, '0', 'admin0', '2022-02-22', NULL, NULL),
(5, 2, '2345', 5, 45000000000, 1, 1, '2022-08-31', 'Projects funding', 0, 0, '0', 'admin0', '2022-02-22', NULL, NULL),
(6, 1, '98700', 4, 50000000000, 1, 1, '2022-01-01', 'Funding programmes', 0, 0, '0', 'Admin0', '2022-03-02', NULL, NULL),
(7, 5, '9800', 5, 20000000, 2, 100, '2022-01-03', '5', 0, 0, '0', 'Admin0', '2022-03-05', NULL, NULL),
(8, 6, '094567', 5, 1000000000, 1, 1, '2022-07-01', '9', 0, 0, '0', 'Admin0', '2022-03-20', NULL, NULL),
(9, 7, '8900', 5, 30000000, 2, 110, '2022-01-03', '11', 0, 0, '0', 'Admin0', '2022-03-22', NULL, NULL),
(10, 2, '666', 4, 60000000, 1, 1, '2022-03-10', 'test', 0, 0, '0', 'admin0', '2022-03-30', NULL, NULL),
(11, 7, '678000', 4, 5000000000, 1, 1, '2021-06-30', '62', 0, 0, '0', 'Admin0', '2022-03-30', NULL, NULL),
(12, 6, '239000', 4, 2000000000, 1, 1, '2021-01-07', '62', 0, 0, '0', 'Admin0', '2022-03-30', NULL, NULL),
(13, 7, '6780098', 5, 3000000000, 1, 1, '2022-07-01', '62', 0, 0, '0', 'Admin0', '2022-03-30', NULL, NULL),
(14, 1, 'Repudiandae soluta a', 4, 10000, 2, 22, '1970-01-01', 'Necessitatibus perfe', 0, 0, '0', 'admin0', '2022-04-04', NULL, NULL),
(15, 10, 'Saepe dicta exercita', 4, 2222, 4, 18, '2022-04-18', '7', 0, 0, '0', '1', '2022-04-17', NULL, NULL),
(17, 10, 'Voluptas vero cupida', 4, 77, 4, 77, '2022-04-16', '59', 0, 0, '0', '1', '2022-04-17', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_funds_request`
--

CREATE TABLE `tbl_funds_request` (
  `fid` int NOT NULL,
  `projcategory` int NOT NULL,
  `projactivityid` int NOT NULL,
  `source` int DEFAULT NULL,
  `amtrec` decimal(19,2) DEFAULT '0.00',
  `type` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `amtreq` decimal(19,2) DEFAULT '0.00',
  `fundreqstatus` varchar(100) NOT NULL DEFAULT 'Not Paid',
  `reqref` varchar(300) DEFAULT NULL,
  `reqid` int DEFAULT NULL,
  `purpose` text,
  `amtdis` decimal(19,2) DEFAULT '0.00',
  `receiver` varchar(300) DEFAULT NULL,
  `remarks` text,
  `date` date DEFAULT NULL,
  `enteredby` varchar(255) DEFAULT NULL,
  `total` decimal(19,2) DEFAULT NULL,
  `projid` int DEFAULT NULL,
  `projcode` varchar(200) DEFAULT NULL,
  `projname` varchar(300) DEFAULT NULL,
  `dateentered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_general_inspection`
--

CREATE TABLE `tbl_general_inspection` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `location` int NOT NULL,
  `subject` int NOT NULL,
  `observations` text NOT NULL,
  `created_at` date NOT NULL,
  `created_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_general_inspection`
--

INSERT INTO `tbl_general_inspection` (`id`, `projid`, `location`, `subject`, `observations`, `created_at`, `created_by`) VALUES
(1, 8, 361, 0, '<p>test</p>', '2022-03-01', 1),
(2, 8, 361, 0, '<p>test</p>', '2022-03-01', 1),
(3, 8, 361, 0, '<p>test</p>', '2022-03-01', 1),
(4, 8, 361, 0, '<p>test</p>', '2022-03-01', 1),
(5, 8, 361, 0, '<p>testing</p>', '2022-03-01', 1),
(6, 8, 361, 0, '<p>testing</p>', '2022-03-01', 1),
(7, 8, 361, 0, '<p>test</p>', '2022-03-01', 1),
(8, 8, 361, 0, '<p>testing</p>', '2022-03-01', 1),
(9, 8, 361, 0, '<p>testing</p>', '2022-03-01', 1),
(10, 8, 361, 0, '<p>testing</p>', '2022-03-01', 1),
(11, 8, 361, 0, '<p>testing</p>', '2022-03-01', 1),
(12, 8, 361, 0, '<p>testing</p>', '2022-03-01', 1),
(13, 8, 361, 0, '<p>Inspect</p>', '2022-03-01', 1),
(14, 8, 361, 0, '<p>testing&nbsp;</p>', '2022-03-01', 1),
(15, 8, 361, 0, '<p>testing&nbsp;</p>', '2022-03-01', 1),
(16, 8, 361, 0, '<p>tersertgwskujys</p>', '2022-03-01', 1),
(17, 8, 361, 1, '<p><span style=\"color:rgb(88, 102, 126); font-family:poppins,arial,sans-serif; font-size:13.2px\">onnie Rashid Lynn (born March 13, 1972), known by his stage name Common (formerly Common Sense), is an American rapper and actor. He debuted in 1992 with the album Can I Borrow a Dollar?, and gained critical acclaim with his 1994 album Resurrection. He maintained an underground following into&nbsp;</span></p>', '2022-03-18', 1),
(18, 8, 361, 1, '<p><span style=\"color:rgb(88, 102, 126); font-family:poppins,arial,sans-serif; font-size:13.2px\">onnie Rashid Lynn (born March 13, 1972), known by his stage name Common (formerly Common Sense), is an American rapper and actor. He debuted in 1992 with the album Can I Borrow a Dollar?, and gained critical acclaim with his 1994 album Resurrection. He maintained an underground following into&nbsp;</span></p>', '2022-03-18', 1),
(19, 8, 361, 1, '<p>Despite issue of financing, the work is progressing well as required</p>', '2022-03-23', 1),
(20, 8, 361, 2, '<p>All the specifications have been&nbsp;complied with</p>', '2022-03-23', 1),
(21, 44, 352, 2, '<p>TESTST</p>', '2022-04-01', 1),
(22, 55, 409, 1, '<p>The project is progressing as planned</p>', '2022-04-04', 1),
(23, 49, 357, 2, '<p>Poor quality</p>', '2022-04-04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_global_setting`
--

CREATE TABLE `tbl_global_setting` (
  `id` int NOT NULL,
  `category` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_images`
--

CREATE TABLE `tbl_images` (
  `imageid` int NOT NULL,
  `projid` int NOT NULL,
  `projname` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_imagesfive`
--

CREATE TABLE `tbl_imagesfive` (
  `imagefiveid` int NOT NULL,
  `projid` int NOT NULL,
  `projname` varchar(255) NOT NULL,
  `namefive` varchar(100) DEFAULT NULL,
  `pathfive` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_imagestable_copy`
--

CREATE TABLE `tbl_imagestable_copy` (
  `imageid` int NOT NULL,
  `projid` int NOT NULL,
  `projname` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `nameone` varchar(100) DEFAULT NULL,
  `pathone` varchar(100) DEFAULT NULL,
  `nametwo` varchar(100) DEFAULT NULL,
  `pathtwo` varchar(100) DEFAULT NULL,
  `namethree` varchar(100) DEFAULT NULL,
  `paththree` varchar(100) DEFAULT NULL,
  `namefour` varchar(100) DEFAULT NULL,
  `pathfour` varchar(100) DEFAULT NULL,
  `namefive` varchar(100) DEFAULT NULL,
  `pathfive` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_images_copy`
--

CREATE TABLE `tbl_images_copy` (
  `imageid` int NOT NULL,
  `projid` int NOT NULL,
  `projname` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `nameone` varchar(100) DEFAULT NULL,
  `pathone` varchar(100) DEFAULT NULL,
  `nametwo` varchar(100) DEFAULT NULL,
  `pathtwo` varchar(100) DEFAULT NULL,
  `namethree` varchar(100) DEFAULT NULL,
  `paththree` varchar(100) DEFAULT NULL,
  `namefour` varchar(100) DEFAULT NULL,
  `pathfour` varchar(100) DEFAULT NULL,
  `namefive` varchar(100) DEFAULT NULL,
  `pathfive` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_impacts`
--

CREATE TABLE `tbl_impacts` (
  `impid` int NOT NULL,
  `deptid` int NOT NULL,
  `code` varchar(20) NOT NULL,
  `impact` varchar(255) NOT NULL,
  `indicator` int NOT NULL,
  `date_created` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_independent_programs_quarterly_targets`
--

CREATE TABLE `tbl_independent_programs_quarterly_targets` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `opid` int NOT NULL,
  `indid` int NOT NULL,
  `year` int NOT NULL,
  `Q1` float NOT NULL,
  `Q2` float NOT NULL,
  `Q3` float NOT NULL,
  `Q4` float NOT NULL,
  `created_by` int NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_independent_programs_quarterly_targets`
--

INSERT INTO `tbl_independent_programs_quarterly_targets` (`id`, `progid`, `opid`, `indid`, `year`, `Q1`, `Q2`, `Q3`, `Q4`, `created_by`, `date_created`) VALUES
(5, 1, 6, 17, 2021, 55, 55, 50, 50, 1, '2022-04-02'),
(6, 62, 73, 151, 2021, 1000, 1000, 1000, 1000, 1, '2022-04-04'),
(7, 63, 75, 144, 2021, 20, 10, 10, 10, 1, '2022-04-04'),
(8, 65, 82, 163, 2021, 2500, 2500, 2500, 2500, 1, '2022-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator`
--

CREATE TABLE `tbl_indicator` (
  `indid` int NOT NULL,
  `indicator_code` varchar(20) NOT NULL,
  `indicator_name` varchar(255) NOT NULL,
  `indicator_description` text,
  `indicator_type` int NOT NULL DEFAULT '2',
  `indicator_category` varchar(255) NOT NULL,
  `indicator_disaggregation` int NOT NULL DEFAULT '0',
  `indicator_calculation_method` int DEFAULT NULL,
  `indicator_unit` int NOT NULL,
  `indicator_direction` int DEFAULT NULL,
  `indicator_aggregation` int DEFAULT NULL,
  `indicator_sector` int DEFAULT NULL,
  `indicator_dept` int DEFAULT NULL,
  `indicator_data_source` int DEFAULT NULL,
  `indicator_baseline_level` int NOT NULL DEFAULT '2',
  `indicator_mapping_type` int NOT NULL DEFAULT '0',
  `baseline` int NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `user_name` varchar(100) NOT NULL,
  `date_entered` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_indicator`
--

INSERT INTO `tbl_indicator` (`indid`, `indicator_code`, `indicator_name`, `indicator_description`, `indicator_type`, `indicator_category`, `indicator_disaggregation`, `indicator_calculation_method`, `indicator_unit`, `indicator_direction`, `indicator_aggregation`, `indicator_sector`, `indicator_dept`, `indicator_data_source`, `indicator_baseline_level`, `indicator_mapping_type`, `baseline`, `active`, `user_name`, `date_entered`, `updated_by`, `date_updated`) VALUES
(1, '001', 'dams constructed', '<p>Dams constructed</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, 2, 0, 1, '1', 'Admin0', '2021-12-28', '1', '2022-03-19'),
(2, '002', 'Number of boreholes drilled and equiped', '<p>Boreholes drilled</p>', 2, 'Output', 0, NULL, 21, NULL, NULL, 9, 23, NULL, 2, 0, 1, '1', 'Admin0', '2021-12-28', 'Admin0', '2022-01-16'),
(3, 'WP001', 'Volume of water produced', '', 2, 'Output', 0, NULL, 13, NULL, NULL, 9, 23, NULL, 2, 0, 1, '1', 'admin0', '2021-12-28', '1', '2022-03-15'),
(4, '003', 'sewerage network constructed ', '<p>Sewerage Network laid</p>', 2, 'Output', 0, NULL, 16, NULL, NULL, 9, 23, NULL, 2, 0, 1, '1', 'Admin0', '2021-12-28', '1', '2022-03-15'),
(5, '004', 'Number of water connections', '<p>Connection of water to people</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'Admin0', '2021-12-28', '1', '2022-03-15'),
(7, 'OUT1', 'Water produced in cubic meters', '<p>testing</p>', 2, 'Outcome', 0, 1, 13, 1, 0, 1, 2, NULL, 2, 0, 0, '1', 'Admin0', '2021-12-28', '1', '2022-04-16'),
(8, 'OUT2', 'Sewerage treatment Capacity  (M3 /Day)', '', 2, 'Outcome', 0, 1, 36, 1, NULL, 1, 18, 0, 2, 0, 0, '1', 'Admin0', '2021-12-28', NULL, NULL),
(9, 'IMP001', '% of Households with access to Water', '', 2, 'Impact', 0, 2, 18, 1, NULL, 2, 8, NULL, 2, 0, 0, '1', 'Admin0', '2021-12-28', NULL, NULL),
(10, 'IMP002', '% of Households with Access to Sewerage and sanitation.', '', 2, 'Impact', 0, 2, 19, 1, NULL, 1, 18, NULL, 2, 0, 0, '1', 'Admin0', '2021-12-28', NULL, NULL),
(11, 'OUT005', 'Treatment plants constructed', '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione quisquam quae placeat qui. A facilis placeat voluptatem molestias totam ipsum, mollitia dolorem obcaecati animi, accusantium eligendi beatae tempore corrupti debitis!&nbsp;Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione quisquam quae placeat qui. A facilis placeat voluptatem molestias totam ipsum, mollitia dolorem obcaecati animi, accusantium eligendi beatae tempore corrupti debitis!&nbsp;Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione quisquam quae placeat qui. A facilis placeat voluptatem molestias totam ipsum, mollitia dolorem obcaecati animi, accusantium eligendi beatae tempore corrupti debitis!</p>', 2, 'Output', 0, NULL, 2, NULL, NULL, 11, 31, NULL, 2, 0, 0, '1', 'Admin0', '2021-12-28', '1', '2022-04-16'),
(12, 'OUT006', 'Water pans developed', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, 2, 0, 1, '1', 'Admin0', '2021-12-28', '1', '2022-03-15'),
(13, 'Out007', 'trunk sewers constructed', '', 2, 'Output', 0, NULL, 16, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'Admin0', '2021-12-28', '1', '2022-03-15'),
(14, 'OUT010', 'reticulation sewers constructed', '', 2, 'Output', 0, NULL, 16, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'Admin0', '2021-12-28', '1', '2022-03-15'),
(15, 'OUT011', 'Km of water transmission pipeline', '', 2, 'Output', 0, NULL, 46, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'Admin0', '2021-12-28', 'Admin0', '2022-01-16'),
(16, 'OUT5', 'Households with access to sewerage and sanitation', '<p>SEWERAGE AND SANITATION</p>', 2, 'Outcome', 0, 1, 19, 1, 0, 1, 2, NULL, 2, 0, 0, '1', 'Admin0', '2022-01-05', '1', '2022-03-12'),
(17, 'RWO001', 'Bridges Constructed ', '<p>Number of bridges built</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, 2, 0, 1, '1', 'admin0', '2022-01-15', '1', '2022-03-15'),
(18, 'RD001', 'Road constructed to bitumen standards', '<p>Number of Kilometers</p>', 2, 'Output', 0, NULL, 16, NULL, NULL, 15, 19, NULL, 2, 0, 1, '1', 'admin0', '2022-01-18', '1', '2022-03-15'),
(19, 'RWO003', 'Box Culverts installed ', '<p>test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, 2, 0, 0, '1', 'admin0', '2022-01-15', '1', '2022-03-15'),
(20, 'RWO004', 'drainage systems rehabilitated ', '<p>test</p>', 2, 'Output', 0, NULL, 16, NULL, NULL, 15, 19, NULL, 2, 0, 0, '1', 'admin0', '2022-01-15', '1', '2022-03-15'),
(21, 'TDO001', 'Boda Boda Shades constructed', '<p>test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, 2, 0, 0, '1', 'admin0', '2022-01-16', '1', '2022-03-23'),
(23, 'AG342', 'Number of cereal stores constructed', '<p>Operational stores</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 1, 2, NULL, 2, 0, 0, '1', 'Admin0', '2022-01-16', '1', '2022-03-17'),
(25, 'OUT40', 'Farmers accessing storage facilities', '', 2, 'Outcome', 0, 1, 19, 1, 0, 1, 2, NULL, 2, 0, 0, '1', 'Admin0', '2022-01-18', '1', '2022-03-12'),
(26, 'TD003', 'Parking Bay', '<p>test</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 15, 20, NULL, 2, 0, 0, '1', 'admin0', '2022-01-24', NULL, NULL),
(28, 'EPOU001', 'Fully functional Monitoring and Evaluation System', '<p>test</p>', 2, 'Output', 0, NULL, 50, NULL, NULL, 10, 18, NULL, 2, 0, 0, '1', 'admin0', '2022-01-28', NULL, NULL),
(31, 'HS0001', 'dispensaries constructed', '<p>Number of new dispensaries coinstructed by the county government</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-25'),
(32, 'HD0002', 'hospitals constructed and fully equipped ', '<p>Number of new hospitals constrcuted</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(33, 'HD0003', 'Maternity Hospitals constructed and fully equipped ', '<p>test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(34, 'HD ', 'Trauma and Emergency Centers', '<p>test</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(35, 'HD005', 'Incinerators installed ', '<p>test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(36, 'HD0006', 'Telemedicine Equipment installed ', '<p>test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(37, '09000020', 'Oxygen Plants', '<p>yes</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(38, 'HDOO9023', 'Centers rehabilitated  ', '<p>Test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(39, 'DHP009', 'CT Scan Machines installed ', '<p>TEST</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(40, '009809', 'Ambulances acquired ', '<p>test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(42, 'RDW007', 'Kilometers of road graded', '<p>Test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(43, 'RWD002', 'road gravelled ', '<p>test</p>', 2, 'Output', 0, NULL, 16, NULL, NULL, 15, 19, NULL, 2, 0, 1, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(44, 'RWD006', ' road opened ', '', 2, 'Output', 0, NULL, 16, NULL, NULL, 15, 19, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(45, 'RWD007', 'Ring Culverts installed ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(46, 'ED001', 'street lights installed ', '<p>Test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(47, 'RWD09000', 'Fire Stations constructed and equipped ', '<p>Test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, 2, 0, 0, '1', 'admin0', '2022-02-10', '1', '2022-03-15'),
(48, 'Fugiat quis ab numq', 'Tanisha Moore', '<p>testing</p>', 2, 'Impact', 0, 1, 22, 2, 0, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-11', 'admin0', '2022-02-11'),
(49, '0967072', 'Subcounties benefiting from spraying and issuance protective gears ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-11', '1', '2022-03-15'),
(50, 'HD00W01', 'Number of Subcounties benefiting ', '<p>test</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-11', NULL, NULL),
(51, 'Labore ex inventore ', 'Rigel Simpson', '<p>testing</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, 2, 0, 0, '1', 'admin0', '2022-02-11', NULL, NULL),
(52, 'HD00O51', 'Sub-counties benefiting ', '<p>test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-11', '1', '2022-03-15'),
(53, 'HDSO0034', 'health facilities benefiting ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-12', '1', '2022-03-15'),
(54, '8001', 'Number of litres of pesticides purchased', '<p>pesticides purchased</p>', 2, 'Output', 0, NULL, 52, NULL, NULL, 1, 2, NULL, 2, 0, 0, '1', 'Admin0', '2022-02-12', NULL, NULL),
(55, 'HOU002', 'health facilities automated ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-12', '1', '2022-03-15'),
(56, 'HD0P02', 'health facilities offering specialized health care services ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-12', '1', '2022-03-15'),
(57, 'H009DO', 'Health research centers', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-12', '1', '2022-03-15'),
(58, '8002', 'No. of seedlings distributed', '<p>No. of seedlings distributed</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 2, NULL, 2, 0, 0, '1', 'Admin0', '2022-02-12', NULL, NULL),
(59, '8003', 'No. of drip irrigation kits purchased', '<p>No. of drip irrigation kits purchased</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 2, NULL, 2, 0, 0, '1', 'Admin0', '2022-02-12', NULL, NULL),
(60, 'HOO78S', 'Utility Vehicles acquired ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-12', '1', '2022-03-15'),
(61, 'HDOU0034', 'Number of health facilities ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 12, 24, NULL, 2, 0, 0, '1', 'admin0', '2022-02-12', NULL, NULL),
(62, '8004', 'No. of direct project beneficiaries', '<p>No. of direct project beneficiaries</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 2, NULL, 2, 0, 0, '1', 'Admin0', '2022-02-12', NULL, NULL),
(63, '8005', 'No. of machinery acquired', '<p>No. of machinery acquired</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 2, NULL, 2, 0, 0, '1', 'Admin0', '2022-02-12', NULL, NULL),
(64, '8006', 'equipment acquired', '<p>No. of equipment acquired</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 1, 2, NULL, 2, 0, 0, '1', 'Admin0', '2022-02-12', '1', '2022-03-15'),
(65, '0950002', 'acres of land acquired ', '<p>test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 13, 28, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', '1', '2022-03-15'),
(66, 'LI0093', 'Number of issued Title deeds ', '<p>test</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 13, 28, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(67, '09006001', 'Parcels of Land Surveyed ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 13, 28, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', '1', '2022-03-15'),
(68, '0980002', 'Office blocks Constructed ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 13, 28, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', '1', '2022-03-15'),
(69, '09888202', 'housing units renovated ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 13, 28, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', '1', '2022-03-15'),
(70, '0969171', 'public utilities fenced', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 13, 28, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', '1', '2022-03-15'),
(71, '0802u29', 'non-residential buildings renovated ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 13, 28, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', '1', '2022-03-15'),
(72, 'o000928', 'Number of community water projects developed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(73, '00809', 'Number of Water Supplies Maintained ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(74, '00939q0', 'Number of Dams Rehabilitated ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(75, 'WD0089', 'Number of new water intakes constructed ', '<p>TEST</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(76, 'WDO0023', 'Number of Water tanks purchased and distributed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(77, 'WDOU0056', 'Number of Water Machinery bought ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(78, 'E00001', 'Number of Skip Containers purchased ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(79, 'e000o021', 'Number of skip loader trucks purchased ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(80, 'E0003201', 'Number of Solid Waste Transportation Trucks Purchased ', '<p>TEST</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(81, 'E0032', 'Number of Solid Waste Compactors bought', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(82, 'E00004', 'Number of dumpsites fenced ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(83, 'EWD0005', 'Number of Dumpsite Offices constructed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(84, 'WD00015', 'Number of water bodies/ Catchments conserved and protected ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(85, 'EDW0002', 'Number of tree seedlings purchased and planted ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(86, 'TE0035', 'Number of tourism sites developed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 9, 23, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(87, '090004', 'Number of beneficiaries of TVET Scholarship', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 5, 7, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(88, 'TV0072', 'Number of TVET Administration blocks constructed  ', '<p>TEST</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 5, 7, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(89, 'ou0882828', 'Number of TVET Classrooms constructed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 5, 7, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(90, '09018910', 'Number of TVET Hostels Constructed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 5, 7, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(91, 'OU0092', 'Number of VTCs supplied with tools and equipment', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 5, 7, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(92, 'YAO00928', 'Number of Stadia Constructed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 5, 7, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(93, '097hh02', 'Number of high altitude training camps constructed and equipped ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 5, 7, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(94, 'YDI00827', 'Number of subcounty playfields upgraded', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 5, 7, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(95, 'WOOO019', 'Number of Ward Play fields upgraded', '<p>TEST</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 5, 7, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(96, '09hao0r', 'Number of Wards Supplied with Sports equipment ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 5, 7, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(97, '09ou625', 'Number of cooperative societies funded by Enterprise Development Funds ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 16, 25, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(98, 'OPIIW026', 'Number of cooperative societies facilitated with training on enterpreneurship skills', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 16, 25, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(99, 'EOU009', 'Number of Coperatives Automated ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 16, 25, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', '1', '2022-03-15'),
(100, 'ou02782', 'Number of Cooperatives utility vehicles acquired ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 16, 25, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(101, 'ouowu029', 'Number of Cooperative members Education/Training programs conducted ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 16, 25, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(102, 'H0Y279', 'Number of committee member education programs conducted ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 16, 25, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(103, '028790782', 'Number of staff education/training programs conducted ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 16, 25, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(104, '03901', 'Number of cooperative seminars and workshops conducted ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 16, 25, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(105, 'hoh092', 'Number of cooperative offices refurbished ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 16, 25, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(106, '09hggh', 'Number of cooperative Management Softwares developed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 16, 25, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(107, 'LDOU002', 'Animals inseminated ', '<p>TEST</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 1, 3, NULL, 2, 0, 1, '1', 'admin0', '2022-02-14', '1', '2022-03-20'),
(108, 'TEST', 'Number of animals vaccinated ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 3, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(109, 'LDOU009', 'Number of dips supplied with accaricides', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 3, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(110, 'ldou003', 'Number of new dips constructed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 3, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(111, 'LDOUHAO', 'Number of dips rehabilitated ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 3, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(112, '09ueuh03', 'Number of Category A,B and C Slaughterhouse Constructed', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 3, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(113, 'OUL007', 'Number of cooperatives benefiting from the value addition machinery', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 3, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(114, 'AOU0037', 'Number of Agricultural Machinery acquired for improved productivity ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 2, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(115, '09OUTHW', 'Number of fingerlings distributed ', '<p>Test</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 3, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(116, 'LVH009', 'Number of 20kg bags of Fish feed distributed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 1, 3, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(117, 'pp008', 'Number of physical development plans prepared ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 13, 29, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(118, 'P00YW9', 'physical plans digitized ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 13, 29, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', '1', '2022-03-17'),
(119, 'TI001', 'Number of new markets constructed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 11, 31, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(120, '00MO65', 'Number of Markets rehabilitated ', '<p>Test</p>', 2, 'Output', 0, NULL, 14, NULL, NULL, 11, 31, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(121, 'T00OUT6', 'Number of new stalls constructed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 11, 31, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(122, 'TI0078', 'Number of Market Stalls Rehabilitated ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 13, 29, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(123, 't00701', 'Amount of loan allocated in Kshs', '<p>adult, youth</p>', 2, 'Output', 1, NULL, 54, NULL, NULL, 11, 31, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(124, 'TOU99287', 'Number of beneficiaries trained on SMES', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 11, 31, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(125, 'TD0070', 'Number of business incubation centers constructed and equipped ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 11, 31, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(126, 'NW009HW', 'Number of weighbridge testing units developed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 11, 31, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(127, 'T0Y2JJ0', 'Number of SME databases developed ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 11, 31, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(128, 'Puw-83', 'Number of trade/investment exhibitions conducted ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 11, 31, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(129, 'Toyt0828', 'Number of online marketing softwares for traders acquired ', '', 2, 'Output', 0, NULL, 14, NULL, NULL, 11, 31, NULL, 2, 0, 0, '1', 'admin0', '2022-02-14', NULL, NULL),
(130, '785544', 'Number of animals vaccinated', '<p>gggggggggggggggggg</p>', 2, 'Output', 0, NULL, 55, NULL, NULL, 1, 3, NULL, 2, 0, 0, '1', 'Admin0', '2022-02-26', NULL, NULL),
(131, 'OU009/OU', 'Level of access to transport infrastructure ', '<p>Leveln of access to transport infrastructure</p>', 2, 'Outcome', 0, 2, 17, 1, NULL, 15, 19, 0, 2, 0, 0, '1', 'admin0', '2022-02-27', NULL, NULL),
(132, '2354', 'valuation rolls', '<p>ttttttttttt</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 13, 28, NULL, 1, 0, 1, '1', '1', '2022-03-05', NULL, NULL),
(133, '7777', 'Farm Machineries Purchased', '<p>Machineries</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 1, 2, NULL, 2, 0, 0, '1', '1', '2022-03-05', NULL, NULL),
(134, 'IT001', 'Softwares Installed ', '<p>Softwares</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 11, 30, NULL, 1, 0, 0, '1', '1', '2022-03-05', NULL, NULL),
(135, 'LND001', 'Title deeds issued', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 13, 28, NULL, 2, 0, 1, '1', '1', '2022-03-08', NULL, NULL),
(136, '4321', 'Children vaccinated against Covid 19', '<p>Testing</p>', 2, 'Outcome', 1, 1, 19, 1, NULL, 12, 24, 0, 2, 0, 0, '1', '1', '2022-03-15', NULL, NULL),
(137, '0907', 'Title deeds issued ', '<p>test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 13, 28, NULL, 1, 0, 0, '1', '1', '2022-03-19', NULL, NULL),
(138, '7891', 'registered land beneficiaries', '<p>landowners</p>', 2, 'Outcome', 0, 1, 19, 1, 0, 13, 28, NULL, 2, 0, 0, '1', '1', '2022-03-21', '1', '2022-03-23'),
(139, 'AT006', 'softwares deployed', '<p>Softwares deployed</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 11, 30, NULL, 1, 0, 0, '1', '1', '2022-03-21', NULL, NULL),
(140, '67111', ' Children benefiting from school feeding program', '<p>Children benefiting from school feeding program</p>', 2, 'Outcome', 1, 1, 19, 1, NULL, 5, 6, 0, 2, 0, 0, '1', '1', '2022-03-21', NULL, NULL),
(141, '090909', 'Firewalls installed ', '<p>test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 11, 30, NULL, 2, 0, 0, '1', '1', '2022-03-22', NULL, NULL),
(143, '000T00', 'enrollment of ECDE pupils', '<p>change in ECDE pupils enrollment=New&nbsp;ECDE Enrollment-Initial ECDE Enrollment</p><p>Total number number ECDE pupils=Initial ECDE Enrollment</p><p>&nbsp;</p>', 2, 'Outcome', 1, 2, 17, 1, NULL, 5, 6, 0, 2, 0, 0, '1', '1', '2022-03-22', NULL, NULL),
(144, 'EC56789', 'classrooms constructed', '<p>ECDE Classrooms</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 5, 6, NULL, 2, 0, 1, '1', '1', '2022-03-24', NULL, NULL),
(145, '890032', 'ECDE pupils enrolled', '<p>ECDE pupils</p>', 2, 'Outcome', 1, 1, 19, 1, NULL, 5, 6, 0, 2, 0, 0, '1', '1', '2022-03-24', NULL, NULL),
(146, '500687', 'time taken to access main road networks', '<p>test</p>', 2, 'Outcome', 0, 1, 60, 2, NULL, 15, 19, 0, 2, 0, 0, '1', '1', '2022-03-24', NULL, NULL),
(147, '56787654', 'softwares deployed at the county', '<p>xxxxxxxxxxxxxxxxxx</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 11, 30, NULL, 1, 0, 1, '1', '1', '2022-03-25', NULL, NULL),
(149, '0078', 'HIV Infection', '<p>People tested positive</p><p>Total number of people tested</p>', 2, 'Outcome', 1, 2, 61, 2, NULL, 12, 24, 0, 2, 0, 0, '1', '1', '2022-03-29', NULL, NULL),
(150, '00510', 'Adults using condoms ', '', 2, 'Outcome', 1, 1, 19, 1, NULL, 12, 24, 0, 2, 0, 0, '1', '1', '2022-03-30', NULL, NULL),
(151, 'HS09', 'Condoms distributed', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, 2, 0, 1, '1', '1', '2022-03-30', NULL, NULL),
(152, 'ou0001', 'level of access to clean water ', '', 2, 'Outcome', 0, 2, 17, 1, NULL, 9, 23, 0, 2, 0, 0, '1', '1', '2022-03-30', NULL, NULL),
(153, '89002', 'People accessing the center through the road', '', 2, 'Outcome', 1, 3, 17, 1, NULL, 15, 19, 0, 2, 0, 0, '1', '1', '2022-03-31', NULL, NULL),
(154, 'ou0028', 'Tippers purchased ', '<p>test</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, 1, 0, 1, '1', '1', '2022-04-02', NULL, NULL),
(155, '0ou668', 'Dozzers Purchased ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, 1, 0, 1, '1', '1', '2022-04-02', NULL, NULL),
(156, '0090', 'Excavators purchased ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, 1, 0, 1, '1', '1', '2022-04-02', NULL, NULL),
(158, 'ou782', 'Drilling rigs purchased ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, 1, 0, 1, '1', '1', '2022-04-02', NULL, NULL),
(159, 'ueow009', 'Water Master Purchased', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, 1, 0, 1, '1', '1', '2022-04-02', NULL, NULL),
(160, 'ind-203', 'twenty', '<p>testing&nbsp;</p>', 2, 'Output', 1, NULL, 7, NULL, NULL, 1, 3, NULL, 1, 0, 0, '1', '1', '2022-04-03', NULL, NULL),
(161, 'Non proident lorem ', 'Myles Alexander', '<p>testing the test</p>', 2, 'Output', 0, NULL, 17, NULL, NULL, 10, 18, NULL, 1, 0, 0, '1', '1', '2022-04-03', NULL, NULL),
(162, 'Exercitationem delec', 'Baxter Rocha', '<p>testng&nbsp;</p>', 2, 'Output', 0, NULL, 15, NULL, NULL, 1, 3, NULL, 1, 0, 0, '1', '1', '2022-04-03', NULL, NULL),
(163, '9003', 'Chicken distributed ', '', 2, 'Output', 1, NULL, 19, NULL, NULL, 1, 2, NULL, 2, 0, 1, '1', '1', '2022-04-04', NULL, NULL),
(164, '056', 'women rearing chicken', '<p>women rearing chicken</p>', 2, 'Outcome', 1, 1, 19, 1, NULL, 1, 2, 0, 2, 0, 0, '1', '1', '2022-04-04', NULL, NULL),
(165, 'Dolorem magni sint e', 'Deanna Navarro', '<p>testing</p>', 2, 'Output', 0, NULL, 13, NULL, NULL, 13, 29, NULL, 1, 0, 0, '1', '1', '2022-04-04', NULL, NULL),
(166, 'Consequatur ea est', 'Kiayada Foster', '<p>testing</p>', 2, 'Output', 0, NULL, 13, NULL, NULL, 1, 2, NULL, 1, 0, 0, '1', '1', '2022-04-16', NULL, NULL),
(167, 'Laborum nihil nostru', 'Rinah Mcneil', '<p>testing</p>', 2, 'Output', 0, NULL, 62, NULL, NULL, 13, 29, NULL, 1, 0, 0, '1', '1', '2022-04-16', NULL, NULL),
(168, 'Quod fugiat enim inc', 'Julian Davenport', '<p>testing</p>', 2, 'Output', 0, NULL, 63, NULL, NULL, 10, 17, NULL, 2, 0, 0, '1', '1', '2022-04-16', NULL, NULL),
(169, 'Ut nostrud laboris p', 'Oscar Howell', '<p>testing</p>', 2, 'Output', 0, NULL, 13, NULL, NULL, 16, 25, NULL, 1, 0, 0, '1', '1', '2022-04-16', NULL, NULL),
(170, 'Sit tempor quia sus', 'Megan Mercer', '<div style=\"color: rgb(238, 255, 255); background-color: rgb(38, 50, 56); font-family: fonts-powerline, &quot;Fira Code&quot;, &quot;Menlo for Powerline&quot;, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace; font-size: 12px; line-height: 16px; white-space: pre;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit facere eveniet repellendus quis, ipsam vitae exercitationem, iste deleniti quo excepturi quasi provident. Alias earum eius porro, fugiat enim nostrum facere?</div>\r\n\r\n<div style=\"color: rgb(238, 255, 255); background-color: rgb(38, 50, 56); font-family: fonts-powerline, &quot;Fira Code&quot;, &quot;Menlo for Powerline&quot;, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace; font-size: 12px; line-height: 16px; white-space: pre;\">\r\n<div style=\"color: rgb(238, 255, 255); background-color: rgb(38, 50, 56); font-family: fonts-powerline, &quot;Fira Code&quot;, &quot;Menlo for Powerline&quot;, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace; font-size: 12px; line-height: 16px; white-space: pre;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit facere eveniet repellendus quis, ipsam vitae exercitationem, iste deleniti quo excepturi quasi provident. Alias earum eius porro, fugiat enim nostrum facere?</div>\r\n\r\n<div style=\"color: rgb(238, 255, 255); background-color: rgb(38, 50, 56); font-family: fonts-powerline, &quot;Fira Code&quot;, &quot;Menlo for Powerline&quot;, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace; font-size: 12px; line-height: 16px; white-space: pre;\">\r\n<div style=\"color: rgb(238, 255, 255); background-color: rgb(38, 50, 56); font-family: fonts-powerline, &quot;Fira Code&quot;, &quot;Menlo for Powerline&quot;, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace; font-size: 12px; line-height: 16px; white-space: pre;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit facere eveniet repellendus quis, ipsam vitae exercitationem, iste deleniti quo excepturi quasi provident. Alias earum eius porro, fugiat enim nostrum facere?</div>\r\n\r\n<div style=\"color: rgb(238, 255, 255); background-color: rgb(38, 50, 56); font-family: fonts-powerline, &quot;Fira Code&quot;, &quot;Menlo for Powerline&quot;, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace; font-size: 12px; line-height: 16px; white-space: pre;\">\r\n<div style=\"color: rgb(238, 255, 255); background-color: rgb(38, 50, 56); font-family: fonts-powerline, &quot;Fira Code&quot;, &quot;Menlo for Powerline&quot;, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace; font-size: 12px; line-height: 16px; white-space: pre;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit facere eveniet repellendus quis, ipsam vitae exercitationem, iste deleniti quo excepturi quasi provident. Alias earum eius porro, fugiat enim nostrum facere?</div>\r\n</div>\r\n</div>\r\n</div>\r\n', 2, 'Outcome', 0, 1, 63, 2, NULL, 5, 6, 0, 2, 0, 0, '1', '1', '2022-04-16', NULL, NULL),
(171, 'Deserunt ea ut maxim', 'Robin French', '<div style=\"color: rgb(238, 255, 255); background-color: rgb(38, 50, 56); font-family: fonts-powerline, &quot;Fira Code&quot;, &quot;Menlo for Powerline&quot;, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace; font-size: 12px; line-height: 16px; white-space: pre;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit facere eveniet repellendus quis, ipsam vitae exercitationem, iste deleniti quo excepturi quasi provident. Alias earum eius porro, fugiat enim nostrum facere?</div>\r\n', 2, 'Outcome', 0, 1, 59, 2, NULL, 15, 19, 0, 2, 0, 0, '1', '1', '2022-04-16', NULL, NULL),
(172, 'Voluptas aut eaque c', 'Brenden Travis', '<div style=\"color: rgb(238, 255, 255); background-color: rgb(38, 50, 56); font-family: fonts-powerline, &quot;Fira Code&quot;, &quot;Menlo for Powerline&quot;, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace, &quot;Droid Sans Mono&quot;, &quot;monospace&quot;, monospace; font-size: 12px; line-height: 16px; white-space: pre;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit facere eveniet repellendus quis, ipsam vitae exercitationem, iste deleniti quo excepturi quasi provident. Alias earum eius porro, fugiat enim nostrum facere?</div>\r\n', 2, 'Outcome', 0, 1, 14, 1, NULL, 14, 26, 0, 2, 0, 0, '1', '1', '2022-04-16', NULL, NULL),
(173, 'Doloremque nisi natu', 'Cameran Allison', '<p>Testing the test</p>', 2, 'Output', 0, NULL, 7, NULL, NULL, 10, 17, NULL, 1, 3, 0, '1', '1', '2022-04-16', '1', '2022-04-16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_details`
--

CREATE TABLE `tbl_indicator_baseline_details` (
  `id` int NOT NULL,
  `indid` int NOT NULL,
  `baseyear` int NOT NULL,
  `level1` int NOT NULL,
  `level2` int NOT NULL,
  `level3` int NOT NULL,
  `disaggregationid` int DEFAULT NULL,
  `variableid` int NOT NULL,
  `basevalue` int NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_survey_answers`
--

CREATE TABLE `tbl_indicator_baseline_survey_answers` (
  `id` int NOT NULL,
  `submissionid` int NOT NULL,
  `fieldid` int NOT NULL,
  `answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_survey_conclusion`
--

CREATE TABLE `tbl_indicator_baseline_survey_conclusion` (
  `id` int NOT NULL,
  `indid` int NOT NULL,
  `formid` int NOT NULL,
  `conclusion` text NOT NULL,
  `recommendation` text,
  `user` varchar(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_survey_details`
--

CREATE TABLE `tbl_indicator_baseline_survey_details` (
  `id` int NOT NULL,
  `formid` int NOT NULL,
  `level3` int NOT NULL,
  `location_disaggregation` int DEFAULT NULL,
  `enumerators` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `added_by` varchar(100) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_indicator_baseline_survey_details`
--

INSERT INTO `tbl_indicator_baseline_survey_details` (`id`, `formid`, `level3`, `location_disaggregation`, `enumerators`, `added_by`, `date_added`) VALUES
(2, 2, 350, NULL, '17', 'admin0', '2022-02-16'),
(3, 3, 342, NULL, 'ptojtractest@gmail.com', 'admin0', '2022-02-28'),
(4, 3, 344, NULL, 'ptojtractest@gmail.com', 'admin0', '2022-02-28'),
(5, 4, 361, NULL, '16', 'admin0', '2022-03-01'),
(6, 4, 361, NULL, '16', 'admin0', '2022-03-01'),
(99, 5, 342, NULL, '44', '1', '2022-03-17'),
(100, 5, 344, NULL, '45', '1', '2022-03-17'),
(101, 6, 389, NULL, '76', '1', '2022-03-23'),
(102, 7, 389, NULL, '76', '1', '2022-03-24'),
(105, 9, 389, NULL, '75', '1', '2022-03-29'),
(106, 8, 389, NULL, '75', '1', '2022-03-29'),
(107, 8, 390, NULL, '75', '1', '2022-03-29'),
(108, 10, 352, NULL, '34', '1', '2022-03-30'),
(109, 11, 404, NULL, 'kkipe15@gmail.com', '1', '2022-03-31'),
(110, 12, 409, NULL, '47', '1', '2022-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_survey_forms`
--

CREATE TABLE `tbl_indicator_baseline_survey_forms` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `indid` int NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `responsible` int DEFAULT NULL,
  `enumerator_type` int NOT NULL DEFAULT '1',
  `status` int NOT NULL DEFAULT '0',
  `sample_size` int DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `date_deployed` date DEFAULT NULL,
  `closed_by` varchar(100) DEFAULT NULL,
  `date_closed` date DEFAULT NULL,
  `form_type` int DEFAULT NULL,
  `type` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_indicator_baseline_survey_forms`
--

INSERT INTO `tbl_indicator_baseline_survey_forms` (`id`, `projid`, `indid`, `form_name`, `responsible`, `enumerator_type`, `status`, `sample_size`, `startdate`, `enddate`, `created_by`, `date_created`, `date_deployed`, `closed_by`, `date_closed`, `form_type`, `type`) VALUES
(2, 24, 7, 'Baseline', NULL, 1, 2, 5, '2022-02-14', '2022-02-17', 'admin0', '2022-02-13', '2022-02-16', NULL, NULL, 9, 2),
(4, 8, 25, 'Endline', NULL, 1, 3, 10, '2022-03-01', '2022-03-02', 'admin0', '2022-03-01', '2022-03-01', NULL, NULL, 11, 2),
(5, 27, 131, 'Baseline', NULL, 1, 4, 10, '2022-03-17', '2022-03-18', '1', '2022-03-15', '2022-03-17', NULL, NULL, NULL, NULL),
(8, 38, 146, 'Baseline', NULL, 1, 4, 10, '2022-03-24', '2022-03-30', '1', '2022-03-24', '2022-03-29', NULL, NULL, NULL, NULL),
(9, 35, 138, 'Baseline', NULL, 1, 4, 5, '2022-03-29', '2022-03-30', '1', '2022-03-29', '2022-03-29', NULL, NULL, NULL, NULL),
(10, 44, 152, 'Baseline', NULL, 1, 4, 25, '2022-03-30', '2022-03-30', '1', '2022-03-30', '2022-03-30', NULL, NULL, NULL, NULL),
(11, 22, 153, 'Baseline', NULL, 2, 4, 5, '2022-03-31', '2022-04-01', '1', '2022-03-31', '2022-03-31', NULL, NULL, NULL, NULL),
(12, 55, 164, 'Baseline', NULL, 1, 4, 5, '2022-04-04', '2022-04-04', '1', '2022-04-04', '2022-04-04', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_survey_form_question_fields`
--

CREATE TABLE `tbl_indicator_baseline_survey_form_question_fields` (
  `id` int NOT NULL,
  `formid` int NOT NULL,
  `sectionid` int NOT NULL,
  `fieldtype` varchar(255) NOT NULL,
  `access` varchar(255) DEFAULT NULL,
  `requirevalidoption` varchar(255) DEFAULT NULL,
  `fieldrole` varchar(255) DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `subtype` varchar(255) DEFAULT NULL,
  `style` varchar(255) DEFAULT NULL,
  `fieldname` varchar(255) NOT NULL,
  `classname` varchar(255) DEFAULT NULL,
  `placeholder` varchar(255) DEFAULT NULL,
  `fielddesc` varchar(255) DEFAULT NULL,
  `fieldrequired` varchar(255) NOT NULL,
  `toggle` varchar(255) DEFAULT NULL,
  `inline` varchar(255) DEFAULT NULL,
  `other` varchar(255) DEFAULT NULL,
  `fieldmaxlength` varchar(255) DEFAULT NULL,
  `fieldmin` varchar(255) DEFAULT NULL,
  `fieldmax` varchar(255) DEFAULT NULL,
  `step` varchar(255) DEFAULT NULL,
  `fieldvalue` varchar(255) NOT NULL,
  `multiple` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_survey_form_question_field_values`
--

CREATE TABLE `tbl_indicator_baseline_survey_form_question_field_values` (
  `id` int NOT NULL,
  `fieldid` int NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_survey_form_sections`
--

CREATE TABLE `tbl_indicator_baseline_survey_form_sections` (
  `id` int NOT NULL,
  `formid` int NOT NULL,
  `section` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_survey_submission`
--

CREATE TABLE `tbl_indicator_baseline_survey_submission` (
  `id` int NOT NULL,
  `indid` int NOT NULL,
  `formid` int NOT NULL,
  `level3id` int NOT NULL,
  `submission_code` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `submission_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_values`
--

CREATE TABLE `tbl_indicator_baseline_values` (
  `id` int NOT NULL,
  `key_unique` int DEFAULT NULL,
  `form_id` int NOT NULL,
  `level3` int NOT NULL,
  `location` int DEFAULT NULL,
  `measurement_variable` int NOT NULL,
  `disaggregations` int DEFAULT NULL,
  `value` varchar(255) NOT NULL,
  `respondent` int NOT NULL,
  `form_type` int NOT NULL,
  `type` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_years`
--

CREATE TABLE `tbl_indicator_baseline_years` (
  `id` int NOT NULL,
  `indid` int NOT NULL,
  `projid` int DEFAULT NULL,
  `year` int NOT NULL,
  `datecreated` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_indicator_baseline_years`
--

INSERT INTO `tbl_indicator_baseline_years` (`id`, `indid`, `projid`, `year`, `datecreated`) VALUES
(2, 2, NULL, 3, '2022-01-06'),
(3, 1, NULL, 5, '2022-03-19'),
(4, 17, NULL, 5, '2022-03-19'),
(5, 135, NULL, 5, '2022-03-19'),
(7, 3, NULL, 4, '2022-03-19'),
(10, 107, NULL, 1, '2022-03-20'),
(12, 4, NULL, 5, '2022-03-20'),
(13, 132, NULL, 4, '2022-03-22'),
(14, 43, NULL, 1, '2022-03-23'),
(15, 18, NULL, 1, '2022-03-23'),
(16, 144, NULL, 3, '2022-03-24'),
(17, 12, NULL, 1, '2022-03-25'),
(18, 147, NULL, 3, '2022-03-25'),
(19, 151, NULL, 4, '2022-03-30'),
(20, 154, NULL, 2, '2022-04-02'),
(21, 156, NULL, 3, '2022-04-02'),
(22, 158, NULL, 2, '2022-04-02'),
(23, 159, NULL, 2, '2022-04-02'),
(24, 155, NULL, 2, '2022-04-02'),
(25, 163, NULL, 4, '2022-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_beneficiaries`
--

CREATE TABLE `tbl_indicator_beneficiaries` (
  `id` int NOT NULL,
  `indicatorid` int NOT NULL,
  `beneficiary` varchar(255) DEFAULT NULL,
  `type` int NOT NULL,
  `dissagragated_category` varchar(255) DEFAULT NULL,
  `dissagragated` int DEFAULT NULL,
  `added_by` varchar(100) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_beneficiary_dissegragation`
--

CREATE TABLE `tbl_indicator_beneficiary_dissegragation` (
  `id` int NOT NULL,
  `indicatorid` int NOT NULL,
  `ben_id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_calculation_method`
--

CREATE TABLE `tbl_indicator_calculation_method` (
  `id` int NOT NULL,
  `method` varchar(255) NOT NULL,
  `description` text,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_indicator_calculation_method`
--

INSERT INTO `tbl_indicator_calculation_method` (`id`, `method`, `description`, `active`) VALUES
(1, 'Aggregated', 'Aggregation', 1),
(2, 'Percentage', 'Percentage', 1),
(3, 'Average', 'Average', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_categories`
--

CREATE TABLE `tbl_indicator_categories` (
  `catid` int NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `indicator_type` int NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_indicator_categories`
--

INSERT INTO `tbl_indicator_categories` (`catid`, `category`, `description`, `indicator_type`, `active`) VALUES
(1, 'Output', 'Output Indicator', 2, 1),
(5, 'Outcome', 'Outcome Indicator', 2, 1),
(6, 'Impact', 'Impact Indicator', 2, 1),
(7, 'KPI', 'Key Performance Indicator', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_disaggregations`
--

CREATE TABLE `tbl_indicator_disaggregations` (
  `id` int NOT NULL,
  `indicatorid` int NOT NULL,
  `disaggregation_type` int NOT NULL,
  `level3` int DEFAULT NULL,
  `disaggregation` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_indicator_disaggregations`
--

INSERT INTO `tbl_indicator_disaggregations` (`id`, `indicatorid`, `disaggregation_type`, `level3`, `disaggregation`) VALUES
(1, 136, 6, NULL, 'Male'),
(2, 136, 6, NULL, 'Female'),
(3, 140, 6, NULL, 'Male'),
(4, 140, 6, NULL, 'Female'),
(5, 143, 6, NULL, 'Boys'),
(6, 143, 6, NULL, 'Girls'),
(7, 145, 6, NULL, 'Boys'),
(8, 145, 6, NULL, 'Girls'),
(12, 149, 6, NULL, 'M'),
(11, 149, 6, NULL, 'F'),
(13, 150, 6, NULL, 'M'),
(14, 150, 6, NULL, 'F'),
(15, 153, 6, NULL, 'M'),
(16, 153, 6, NULL, 'F'),
(17, 164, 6, NULL, 'Married'),
(18, 164, 6, NULL, 'Single'),
(19, 164, 6, NULL, ' Widow'),
(20, 164, 6, NULL, 'divorcee'),
(21, 164, 6, NULL, 'separated');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_disaggregation_types`
--

CREATE TABLE `tbl_indicator_disaggregation_types` (
  `id` int NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_indicator_disaggregation_types`
--

INSERT INTO `tbl_indicator_disaggregation_types` (`id`, `category`, `description`, `type`) VALUES
(1, 'Geographical', '													', 0),
(8, 'Type of Chicken', '													', 1),
(6, 'Gender', 'Test						', 1),
(7, 'Scale', 'DEscription', 1),
(9, 'Marital Status', '													', 1),
(10, 'Marital Status', '													', 1),
(11, 'Marital Status', '													', 1),
(12, 'Marital Status', '													', 1),
(13, 'Marital Status', '													yyyy', 1),
(14, 'Marital Status', '													yyyy', 1),
(15, 'Sonya Sandoval', 'Similique sunt autem', 1),
(16, 'Sonya Sandoval fff', '													ttt', 0),
(17, 'Sonya Sandoval ffff', '													ttttt', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_level3_disaggregations`
--

CREATE TABLE `tbl_indicator_level3_disaggregations` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `form_id` int NOT NULL,
  `indicatorid` int NOT NULL,
  `level3` int NOT NULL,
  `disaggregations` varchar(255) NOT NULL,
  `responsible` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_measurement_variables`
--

CREATE TABLE `tbl_indicator_measurement_variables` (
  `id` int NOT NULL,
  `indicatorid` int NOT NULL,
  `measurement_variable` varchar(255) NOT NULL,
  `category` int NOT NULL,
  `outcome_ben_type` int DEFAULT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_indicator_measurement_variables`
--

INSERT INTO `tbl_indicator_measurement_variables` (`id`, `indicatorid`, `measurement_variable`, `category`, `outcome_ben_type`, `type`) VALUES
(35, 7, 'Volume (M3)', 2, NULL, 'n'),
(2, 8, ' (M3 /Day)', 2, NULL, 'n'),
(3, 9, 'Current number of households with access to water-Initial number of households with access to water', 1, NULL, 'n'),
(4, 9, 'Total number of households with access to water', 1, NULL, 'd'),
(5, 10, 'Current number of Households with Access to Sewerage and sanitation-Initial number of Households with Access to Sewerage and sanitation.', 1, NULL, 'n'),
(6, 10, 'Total number of Households with Access to Sewerage and sanitation', 1, NULL, 'd'),
(15, 16, 'households with access to sewerage and sanitation', 2, NULL, 'n'),
(14, 25, 'Number of farmers accessing storage facilities', 2, NULL, 'n'),
(10, 131, 'Number of households accessing transport infrastructure ', 2, NULL, 'n'),
(11, 131, 'Total Population ', 2, NULL, 'd'),
(16, 136, 'Number of children', 2, NULL, 'n'),
(21, 138, 'number of registered land beneficiaries', 2, NULL, 'n'),
(18, 140, 'Children benefiting from school feeding program', 2, NULL, 'n'),
(19, 143, 'change in ECDE pupils enrollment', 2, NULL, 'n'),
(20, 143, 'Total number number ECDE pupils', 2, NULL, 'd'),
(22, 145, 'ECDE pupils', 2, NULL, 'n'),
(23, 146, 'Time taken to access main road network', 2, NULL, 'n'),
(25, 149, 'People tested positive', 2, NULL, 'n'),
(26, 149, 'Total number of people tested', 2, NULL, 'd'),
(27, 150, 'Adults using condoms ', 2, NULL, 'n'),
(28, 152, 'Number of households accessing clean water', 2, NULL, 'n'),
(29, 152, 'Total Population ', 2, NULL, 'd'),
(30, 153, 'Number of people passing through the road to the center', 2, NULL, 'n'),
(31, 164, 'women rearing chicken', 2, NULL, 'n'),
(32, 170, 'Summation of the total students', 2, NULL, 'n'),
(33, 171, 'sum', 2, NULL, 'n'),
(34, 172, 'ddd', 2, NULL, 'n');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_measurement_variables_disaggregation_type`
--

CREATE TABLE `tbl_indicator_measurement_variables_disaggregation_type` (
  `id` int NOT NULL,
  `indicatorid` int NOT NULL,
  `disaggregation_type` int NOT NULL,
  `parent` int DEFAULT NULL,
  `type` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_indicator_measurement_variables_disaggregation_type`
--

INSERT INTO `tbl_indicator_measurement_variables_disaggregation_type` (`id`, `indicatorid`, `disaggregation_type`, `parent`, `type`) VALUES
(1, 136, 6, 0, 2),
(2, 140, 6, 0, 2),
(3, 143, 6, 0, 2),
(4, 145, 6, 0, 2),
(6, 149, 6, 0, 2),
(7, 150, 6, 0, 2),
(8, 153, 6, 0, 2),
(9, 164, 6, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_output_baseline_values`
--

CREATE TABLE `tbl_indicator_output_baseline_values` (
  `id` int NOT NULL,
  `indid` int NOT NULL,
  `key_unique` varchar(255) NOT NULL DEFAULT '0',
  `level3` int NOT NULL,
  `location` int DEFAULT NULL,
  `disaggregations` varchar(255) DEFAULT NULL,
  `value` float NOT NULL,
  `respondent` int DEFAULT NULL,
  `datecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_indicator_output_baseline_values`
--

INSERT INTO `tbl_indicator_output_baseline_values` (`id`, `indid`, `key_unique`, `level3`, `location`, `disaggregations`, `value`, `respondent`, `datecreated`) VALUES
(2, 2, '0', 311, 0, '0', 15, NULL, '2022-01-06 11:57:00'),
(4, 2, '0', 312, 0, '0', 25, NULL, '2022-01-06 11:57:24'),
(5, 2, '0', 313, 0, '0', 10, NULL, '2022-01-06 11:57:56'),
(6, 2, '0', 314, 0, '0', 30, NULL, '2022-01-06 11:58:08'),
(7, 2, '0', 315, 0, '0', 35, NULL, '2022-01-06 11:58:17'),
(8, 2, '0', 272, 0, '0', 40, NULL, '2022-01-06 11:58:29'),
(9, 2, '0', 273, 0, '0', 45, NULL, '2022-01-06 11:58:40'),
(10, 2, '0', 274, 0, '0', 50, NULL, '2022-01-06 11:58:50'),
(11, 2, '0', 242, 0, '0', 55, NULL, '2022-01-06 11:59:17'),
(12, 2, '0', 241, 0, '0', 60, NULL, '2022-01-06 11:59:27'),
(13, 2, '0', 243, 0, '0', 65, NULL, '2022-01-06 11:59:39'),
(14, 2, '0', 245, 0, '0', 70, NULL, '2022-01-06 11:59:51'),
(15, 2, '0', 244, 0, '0', 75, NULL, '2022-01-06 12:00:00'),
(16, 2, '0', 290, 0, '0', 80, NULL, '2022-01-06 12:00:11'),
(17, 2, '0', 292, 0, '0', 85, NULL, '2022-01-06 12:00:21'),
(18, 2, '0', 288, 0, '0', 90, NULL, '2022-01-06 12:00:32'),
(19, 2, '0', 289, 0, '0', 95, NULL, '2022-01-06 12:00:45'),
(20, 2, '0', 291, 0, '0', 100, NULL, '2022-01-06 12:00:56'),
(21, 2, '0', 252, 0, '0', 105, NULL, '2022-01-06 12:01:06'),
(22, 2, '0', 255, 0, '0', 110, NULL, '2022-01-06 12:01:15'),
(23, 2, '0', 253, 0, '0', 115, NULL, '2022-01-06 12:01:24'),
(24, 2, '0', 254, 0, '0', 120, NULL, '2022-01-06 12:01:32'),
(25, 2, '0', 256, 0, '0', 125, NULL, '2022-01-06 12:01:42'),
(26, 2, '0', 246, 0, '0', 130, NULL, '2022-01-06 12:01:55'),
(27, 2, '0', 247, 0, '0', 135, NULL, '2022-01-06 12:02:08'),
(28, 2, '0', 248, 0, '0', 135, NULL, '2022-01-06 12:02:20'),
(29, 2, '0', 251, 0, '0', 140, NULL, '2022-01-06 12:02:31'),
(30, 2, '0', 250, 0, '0', 145, NULL, '2022-01-06 12:02:41'),
(31, 2, '0', 249, 0, '0', 150, NULL, '2022-01-06 12:02:52'),
(32, 2, '0', 300, 0, '0', 155, NULL, '2022-01-06 12:03:00'),
(33, 2, '0', 301, 0, '0', 160, NULL, '2022-01-06 12:03:09'),
(34, 2, '0', 299, 0, '0', 165, NULL, '2022-01-06 12:03:17'),
(35, 2, '0', 302, 0, '0', 170, NULL, '2022-01-06 12:03:27'),
(36, 2, '0', 86, 0, '0', 175, NULL, '2022-01-06 12:03:37'),
(37, 2, '0', 283, 0, '0', 180, NULL, '2022-01-06 12:03:50'),
(38, 2, '0', 286, 0, '0', 185, NULL, '2022-01-06 12:03:58'),
(39, 2, '0', 287, 0, '0', 190, NULL, '2022-01-06 12:04:11'),
(40, 2, '0', 284, 0, '0', 195, NULL, '2022-01-06 12:04:22'),
(41, 2, '0', 285, 0, '0', 200, NULL, '2022-01-06 12:04:32'),
(42, 2, '0', 265, 0, '0', 205, NULL, '2022-01-06 12:04:46'),
(43, 2, '0', 263, 0, '0', 210, NULL, '2022-01-06 12:04:55'),
(44, 2, '0', 262, 0, '0', 215, NULL, '2022-01-06 12:05:07'),
(45, 2, '0', 266, 0, '0', 220, NULL, '2022-01-06 12:05:20'),
(46, 2, '0', 264, 0, '0', 225, NULL, '2022-01-06 12:05:28'),
(47, 2, '0', 85, 0, '0', 230, NULL, '2022-01-06 12:05:39'),
(48, 2, '0', 257, 0, '0', 235, NULL, '2022-01-06 12:05:49'),
(49, 2, '0', 258, 0, '0', 240, NULL, '2022-01-06 12:06:00'),
(50, 2, '0', 260, 0, '0', 245, NULL, '2022-01-06 12:06:08'),
(51, 2, '0', 259, 0, '0', 250, NULL, '2022-01-06 12:06:16'),
(52, 2, '0', 261, 0, '0', 255, NULL, '2022-01-06 12:06:27'),
(53, 2, '0', 276, 0, '0', 260, NULL, '2022-01-06 12:06:37'),
(54, 2, '0', 277, 0, '0', 265, NULL, '2022-01-06 12:06:50'),
(55, 2, '0', 275, 0, '0', 270, NULL, '2022-01-06 12:07:02'),
(56, 2, '0', 279, 0, '0', 275, NULL, '2022-01-06 12:07:09'),
(57, 2, '0', 278, 0, '0', 280, NULL, '2022-01-06 12:07:20'),
(58, 2, '0', 280, 0, '0', 285, NULL, '2022-01-06 12:07:34'),
(59, 2, '0', 282, 0, '0', 290, NULL, '2022-01-06 12:07:43'),
(60, 2, '0', 281, 0, '0', 295, NULL, '2022-01-06 12:07:52'),
(61, 2, '0', 296, 0, '0', 300, NULL, '2022-01-06 12:08:01'),
(62, 2, '0', 297, 0, '0', 305, NULL, '2022-01-06 12:08:09'),
(63, 2, '0', 295, 0, '0', 310, NULL, '2022-01-06 12:08:16'),
(64, 2, '0', 294, 0, '0', 315, NULL, '2022-01-06 12:08:28'),
(65, 2, '0', 293, 0, '0', 320, NULL, '2022-01-06 12:08:35'),
(66, 2, '0', 185, 0, '0', 325, NULL, '2022-01-06 12:08:46'),
(67, 2, '0', 176, 0, '0', 330, NULL, '2022-01-06 12:08:53'),
(68, 2, '0', 179, 0, '0', 335, NULL, '2022-01-06 12:09:06'),
(69, 2, '0', 183, 0, '0', 340, NULL, '2022-01-06 12:09:12'),
(70, 2, '0', 181, 0, '0', 345, NULL, '2022-01-06 12:09:21'),
(71, 2, '0', 194, 0, '0', 350, NULL, '2022-01-06 12:09:28'),
(72, 2, '0', 196, 0, '0', 355, NULL, '2022-01-06 12:09:37'),
(73, 2, '0', 192, 0, '0', 360, NULL, '2022-01-06 12:09:49'),
(74, 2, '0', 191, 0, '0', 365, NULL, '2022-01-06 12:10:03'),
(75, 2, '0', 189, 0, '0', 370, NULL, '2022-01-06 12:10:11'),
(76, 2, '0', 197, 0, '0', 375, NULL, '2022-01-06 12:10:20'),
(77, 2, '0', 134, 0, '0', 375, NULL, '2022-01-06 12:10:37'),
(78, 2, '0', 138, 0, '0', 375, NULL, '2022-01-06 12:10:48'),
(79, 2, '0', 135, 0, '0', 380, NULL, '2022-01-06 12:11:00'),
(80, 2, '0', 151, 0, '0', 380, NULL, '2022-01-06 12:11:11'),
(81, 2, '0', 159, 0, '0', 1, NULL, '2022-01-06 12:11:28'),
(82, 2, '0', 157, 0, '0', 2, NULL, '2022-01-06 12:11:37'),
(83, 2, '0', 161, 0, '0', 3, NULL, '2022-01-06 12:11:44'),
(84, 2, '0', 155, 0, '0', 4, NULL, '2022-01-06 12:11:53'),
(85, 2, '0', 146, 0, '0', 5, NULL, '2022-01-06 12:12:03'),
(86, 2, '0', 143, 0, '0', 6, NULL, '2022-01-06 12:12:12'),
(87, 2, '0', 148, 0, '0', 7, NULL, '2022-01-06 12:12:20'),
(88, 2, '0', 141, 0, '0', 8, NULL, '2022-01-06 12:12:30'),
(89, 2, '0', 173, 0, '0', 9, NULL, '2022-01-06 12:12:37'),
(90, 2, '0', 168, 0, '0', 10, NULL, '2022-01-06 12:12:47'),
(91, 2, '0', 170, 0, '0', 9, NULL, '2022-01-06 12:12:58'),
(92, 2, '0', 165, 0, '0', 11, NULL, '2022-01-06 12:13:06'),
(93, 2, '0', 166, 0, '0', 8, NULL, '2022-01-06 12:13:17'),
(94, 2, '0', 203, 0, '0', 12, NULL, '2022-01-06 12:13:32'),
(95, 2, '0', 205, 0, '0', 7, NULL, '2022-01-06 12:13:38'),
(96, 2, '0', 204, 0, '0', 13, NULL, '2022-01-06 12:13:45'),
(97, 2, '0', 131, 0, '0', 6, NULL, '2022-01-06 12:13:54'),
(98, 2, '0', 132, 0, '0', 14, NULL, '2022-01-06 12:14:11'),
(99, 2, '0', 130, 0, '0', 5, NULL, '2022-01-06 12:14:20'),
(100, 2, '0', 133, 0, '0', 15, NULL, '2022-01-06 12:14:28'),
(101, 2, '0', 72, 0, '0', 4, NULL, '2022-01-06 12:14:35'),
(102, 2, '0', 75, 0, '0', 16, NULL, '2022-01-06 12:14:45'),
(103, 2, '0', 136, 0, '0', 3, NULL, '2022-01-06 12:14:51'),
(104, 2, '0', 137, 0, '0', 17, NULL, '2022-01-06 12:14:59'),
(105, 2, '0', 139, 0, '0', 2, NULL, '2022-01-06 12:15:08'),
(106, 2, '0', 140, 0, '0', 2, NULL, '2022-01-06 12:15:18'),
(107, 2, '0', 207, 0, '0', 18, NULL, '2022-01-06 12:15:26'),
(108, 2, '0', 206, 0, '0', 1, NULL, '2022-01-06 12:15:32'),
(109, 2, '0', 208, 0, '0', 19, NULL, '2022-01-06 12:15:40'),
(110, 2, '0', 209, 0, '0', 0, NULL, '2022-01-06 12:15:49'),
(111, 2, '0', 210, 0, '0', 20, NULL, '2022-01-06 12:15:59'),
(112, 2, '0', 213, 0, '0', 20, NULL, '2022-01-06 12:16:09'),
(113, 2, '0', 212, 0, '0', 19, NULL, '2022-01-06 12:16:16'),
(114, 2, '0', 215, 0, '0', 18, NULL, '2022-01-06 12:16:24'),
(115, 2, '0', 211, 0, '0', 17, NULL, '2022-01-06 12:16:31'),
(116, 2, '0', 214, 0, '0', 16, NULL, '2022-01-06 12:16:38'),
(117, 2, '0', 198, 0, '0', 15, NULL, '2022-01-06 12:16:46'),
(118, 2, '0', 199, 0, '0', 14, NULL, '2022-01-06 12:16:53'),
(119, 2, '0', 200, 0, '0', 15, NULL, '2022-01-06 12:17:04'),
(120, 2, '0', 201, 0, '0', 14, NULL, '2022-01-06 12:17:13'),
(121, 2, '0', 202, 0, '0', 13, NULL, '2022-01-06 12:17:24'),
(122, 2, '0', 184, 0, '0', 12, NULL, '2022-01-06 12:17:32'),
(123, 2, '0', 188, 0, '0', 11, NULL, '2022-01-06 12:17:39'),
(124, 2, '0', 187, 0, '0', 10, NULL, '2022-01-06 12:17:47'),
(125, 2, '0', 193, 0, '0', 9, NULL, '2022-01-06 12:17:54'),
(126, 2, '0', 190, 0, '0', 8, NULL, '2022-01-06 12:18:06'),
(127, 2, '0', 216, 0, '0', 7, NULL, '2022-01-06 12:18:13'),
(128, 2, '0', 217, 0, '0', 7, NULL, '2022-01-06 12:18:20'),
(129, 2, '0', 218, 0, '0', 6, NULL, '2022-01-06 12:18:33'),
(130, 2, '0', 219, 0, '0', 5, NULL, '2022-01-06 12:18:40'),
(131, 2, '0', 220, 0, '0', 4, NULL, '2022-01-06 12:18:56'),
(132, 2, '0', 226, 0, '0', 3, NULL, '2022-01-06 12:19:15'),
(133, 2, '0', 230, 0, '0', 2, NULL, '2022-01-06 12:19:25'),
(135, 2, '0', 227, 0, '0', 1, NULL, '2022-01-06 12:19:45'),
(136, 2, '0', 228, 0, '0', 0, NULL, '2022-01-06 12:19:58'),
(137, 2, '0', 229, 0, '0', 1, NULL, '2022-01-06 12:20:14'),
(138, 2, '0', 167, 0, '0', 99, NULL, '2022-01-06 12:20:24'),
(139, 2, '0', 169, 0, '0', 98, NULL, '2022-01-06 12:20:30'),
(140, 2, '0', 171, 0, '0', 97, NULL, '2022-01-06 12:20:37'),
(141, 2, '0', 172, 0, '0', 96, NULL, '2022-01-06 12:20:44'),
(142, 2, '0', 174, 0, '0', 96, NULL, '2022-01-06 12:21:05'),
(143, 2, '0', 156, 0, '0', 85, NULL, '2022-01-06 12:21:16'),
(144, 2, '0', 74, 0, '0', 94, NULL, '2022-01-06 12:21:25'),
(145, 2, '0', 149, 0, '0', 93, NULL, '2022-01-06 12:21:38'),
(146, 2, '0', 150, 0, '0', 92, NULL, '2022-01-06 12:21:51'),
(147, 2, '0', 152, 0, '0', 91, NULL, '2022-01-06 12:22:03'),
(148, 2, '0', 154, 0, '0', 89, NULL, '2022-01-06 12:22:16'),
(149, 2, '0', 153, 0, '0', 88, NULL, '2022-01-06 12:22:23'),
(150, 2, '0', 73, 0, '0', 87, NULL, '2022-01-06 12:22:31'),
(151, 2, '0', 142, 0, '0', 86, NULL, '2022-01-06 12:22:46'),
(152, 2, '0', 144, 0, '0', 85, NULL, '2022-01-06 12:22:56'),
(153, 2, '0', 147, 0, '0', 83, NULL, '2022-01-06 12:23:11'),
(154, 2, '0', 145, 0, '0', 82, NULL, '2022-01-06 12:23:28'),
(155, 2, '0', 225, 0, '0', 81, NULL, '2022-01-06 12:23:41'),
(156, 2, '0', 222, 0, '0', 80, NULL, '2022-01-06 12:23:53'),
(157, 2, '0', 223, 0, '0', 79, NULL, '2022-01-06 12:24:15'),
(158, 2, '0', 224, 0, '0', 78, NULL, '2022-01-06 12:24:27'),
(160, 2, '0', 221, 0, '0', 77, NULL, '2022-01-06 12:24:49'),
(161, 2, '0', 238, 0, '0', 76, NULL, '2022-01-06 12:25:00'),
(162, 2, '0', 237, 0, '0', 74, NULL, '2022-01-06 12:25:17'),
(163, 2, '0', 236, 0, '0', 73, NULL, '2022-01-06 12:25:24'),
(164, 2, '0', 240, 0, '0', 72, NULL, '2022-01-06 12:25:31'),
(165, 2, '0', 239, 0, '0', 71, NULL, '2022-01-06 12:25:39'),
(166, 2, '0', 158, 0, '0', 69, NULL, '2022-01-06 12:26:01'),
(167, 2, '0', 162, 0, '0', 68, NULL, '2022-01-06 12:26:12'),
(168, 2, '0', 160, 0, '0', 67, NULL, '2022-01-06 12:26:22'),
(169, 2, '0', 163, 0, '0', 66, NULL, '2022-01-06 12:26:33'),
(170, 2, '0', 164, 0, '0', 66, NULL, '2022-01-06 12:26:49'),
(171, 2, '0', 175, 0, '0', 65, NULL, '2022-01-06 12:27:00'),
(172, 2, '0', 180, 0, '0', 64, NULL, '2022-01-06 12:27:28'),
(173, 2, '0', 178, 0, '0', 63, NULL, '2022-01-06 12:27:35'),
(174, 2, '0', 177, 0, '0', 63, NULL, '2022-01-06 12:27:50'),
(175, 2, '0', 182, 0, '0', 62, NULL, '2022-01-06 12:27:57'),
(176, 2, '0', 231, 0, '0', 61, NULL, '2022-01-06 12:28:18'),
(177, 2, '0', 233, 0, '0', 59, NULL, '2022-01-06 12:28:29'),
(178, 2, '0', 232, 0, '0', 58, NULL, '2022-01-06 12:28:41'),
(179, 2, '0', 235, 0, '0', 57, NULL, '2022-01-06 12:28:58'),
(180, 2, '0', 234, 0, '0', 56, NULL, '2022-01-06 12:29:06'),
(181, 2, '0', 127, 0, '0', 55, NULL, '2022-01-06 12:29:26'),
(182, 2, '0', 126, 0, '0', 54, NULL, '2022-01-06 12:29:36'),
(183, 2, '0', 129, 0, '0', 53, NULL, '2022-01-06 12:29:44'),
(184, 2, '0', 128, 0, '0', 52, NULL, '2022-01-06 12:29:58'),
(185, 2, '0', 125, 0, '0', 51, NULL, '2022-01-06 12:30:05'),
(186, 1, '0', 269, 0, '0', 5, NULL, '2022-01-06 13:12:31'),
(187, 1, '0', 268, 0, '0', 15, NULL, '2022-01-06 18:02:36'),
(188, 1, '0', 242, 0, '0', 12, NULL, '2022-01-06 18:03:54'),
(189, 1, '0', 241, 0, '0', 18, NULL, '2022-01-07 10:20:09'),
(190, 1, '0', 350, 0, '0', 10, NULL, '2022-01-19 06:59:37'),
(191, 1, '0', 351, 0, '0', 20, NULL, '2022-01-19 07:00:03'),
(192, 54, '0', 350, 0, '0', 0, NULL, '2022-02-12 12:00:08'),
(193, 54, '0', 351, 0, '0', 0, NULL, '2022-02-12 12:00:25'),
(194, 54, '0', 356, 0, '0', 0, NULL, '2022-02-12 12:00:38'),
(195, 1, '0', 356, 0, '0', 0, NULL, '2022-02-15 06:40:36'),
(196, 1, '0', 355, 0, '0', 2, NULL, '2022-02-15 06:40:59'),
(197, 1, '0', 354, 0, '0', 4, NULL, '2022-02-15 06:41:22'),
(198, 1, '0', 353, 0, '0', 4, NULL, '2022-02-15 06:42:17'),
(199, 1, '0', 352, 0, '0', 5, NULL, '2022-02-15 06:42:41'),
(201, 1, '0', 390, 0, '0', 5, NULL, '2022-02-15 21:01:59'),
(202, 130, '0', 350, 0, '0', 0, NULL, '2022-02-26 08:41:02'),
(203, 130, '0', 351, 0, '0', 0, NULL, '2022-02-26 08:42:12'),
(204, 130, '0', 356, 0, '0', 0, NULL, '2022-02-26 08:42:28'),
(205, 130, '0', 355, 0, '0', 0, NULL, '2022-02-26 08:42:41'),
(206, 130, '0', 354, 0, '0', 0, NULL, '2022-02-26 08:42:55'),
(207, 130, '0', 353, 0, '0', 0, NULL, '2022-02-26 08:43:06'),
(208, 130, '0', 352, 0, '0', 0, NULL, '2022-02-26 08:43:23'),
(209, 130, '0', 384, 0, '0', 0, NULL, '2022-02-26 08:43:33'),
(210, 130, '0', 385, 0, '0', 0, NULL, '2022-02-26 08:43:43'),
(211, 130, '0', 390, 0, '0', 0, NULL, '2022-02-26 08:43:54'),
(212, 130, '0', 389, 0, '0', 0, NULL, '2022-02-26 08:44:04'),
(213, 130, '0', 391, 0, '0', 0, NULL, '2022-02-26 08:44:14'),
(214, 130, '0', 386, 0, '0', 0, NULL, '2022-02-26 08:44:26'),
(215, 130, '0', 388, 0, '0', 0, NULL, '2022-02-26 08:44:38'),
(216, 130, '0', 387, 0, '0', 0, NULL, '2022-02-26 08:44:49'),
(217, 130, '0', 411, 0, '0', 0, NULL, '2022-02-26 08:45:01'),
(218, 130, '0', 412, 0, '0', 0, NULL, '2022-02-26 08:45:12'),
(219, 130, '0', 413, 0, '0', 0, NULL, '2022-02-26 08:45:21'),
(220, 130, '0', 383, 0, '0', 0, NULL, '2022-02-26 08:45:33'),
(221, 130, '0', 348, 0, '0', 0, NULL, '2022-02-26 08:45:44'),
(222, 130, '0', 382, 0, '0', 0, NULL, '2022-02-26 08:45:59'),
(223, 130, '0', 349, 0, '0', 0, NULL, '2022-02-26 08:46:08'),
(224, 130, '0', 368, 0, '0', 0, NULL, '2022-02-26 08:46:22'),
(225, 130, '0', 369, 0, '0', 0, NULL, '2022-02-26 08:46:33'),
(226, 130, '0', 400, 0, '0', 0, NULL, '2022-02-26 08:46:42'),
(227, 130, '0', 392, 0, '0', 0, NULL, '2022-02-26 08:47:06'),
(228, 130, '0', 394, 0, '0', 0, NULL, '2022-02-26 08:47:15'),
(229, 130, '0', 393, 0, '0', 0, NULL, '2022-02-26 08:47:27'),
(230, 130, '0', 395, 0, '0', 0, NULL, '2022-02-26 08:47:44'),
(231, 130, '0', 398, 0, '0', 0, NULL, '2022-02-26 08:47:54'),
(232, 130, '0', 397, 0, '0', 0, NULL, '2022-02-26 08:52:00'),
(233, 130, '0', 396, 0, '0', 0, NULL, '2022-02-26 08:53:24'),
(234, 130, '0', 399, 0, '0', 0, NULL, '2022-02-26 08:53:37'),
(235, 130, '0', 401, 0, '0', 0, NULL, '2022-02-26 08:53:55'),
(236, 130, '0', 402, 0, '0', 0, NULL, '2022-02-26 08:54:08'),
(237, 130, '0', 346, 0, '0', 0, NULL, '2022-02-26 08:54:26'),
(238, 130, '0', 347, 0, '0', 0, NULL, '2022-02-26 08:54:40'),
(239, 130, '0', 345, 0, '0', 0, NULL, '2022-02-26 08:54:56'),
(240, 130, '0', 343, 0, '0', 0, NULL, '2022-02-26 08:55:10'),
(241, 130, '0', 344, 0, '0', 0, NULL, '2022-02-26 08:55:22'),
(242, 130, '0', 342, 0, '0', 0, NULL, '2022-02-26 08:55:56'),
(243, 130, '0', 403, 0, '0', 0, NULL, '2022-02-26 08:56:11'),
(244, 130, '0', 405, 0, '0', 0, NULL, '2022-02-26 09:13:52'),
(245, 130, '0', 404, 0, '0', 0, NULL, '2022-02-26 09:14:02'),
(246, 130, '0', 341, 0, '0', 0, NULL, '2022-02-26 09:14:16'),
(247, 130, '0', 340, 0, '0', 0, NULL, '2022-02-26 09:14:27'),
(248, 130, '0', 339, 0, '0', 0, NULL, '2022-02-26 09:15:17'),
(249, 130, '0', 358, 0, '0', 0, NULL, '2022-02-26 09:15:29'),
(250, 130, '0', 357, 0, '0', 0, NULL, '2022-02-26 09:15:41'),
(251, 130, '0', 409, 0, '0', 0, NULL, '2022-02-26 09:15:52'),
(252, 130, '0', 367, 0, '0', 0, NULL, '2022-02-26 09:16:53'),
(253, 1, '0', 384, 0, '0', 0, NULL, '2022-02-28 17:21:20'),
(254, 1, '0', 385, 0, '0', 1, NULL, '2022-02-28 17:22:33'),
(255, 1, '0', 383, 0, '0', 0, NULL, '2022-02-28 17:24:52'),
(256, 1, '0', 389, 0, '0', 0, NULL, '2022-02-28 17:25:14'),
(257, 1, '0', 391, 0, '0', 0, NULL, '2022-02-28 17:25:47'),
(258, 1, '0', 386, 0, '0', 0, NULL, '2022-02-28 17:26:18'),
(259, 1, '0', 388, 0, '0', 0, NULL, '2022-02-28 17:26:39'),
(260, 1, '0', 387, 0, '0', 0, NULL, '2022-02-28 17:27:02'),
(261, 1, '0', 398, 0, '0', 0, NULL, '2022-02-28 17:28:08'),
(262, 1, '0', 348, 0, '0', 0, NULL, '2022-02-28 17:31:28'),
(263, 1, '0', 411, 0, '0', 0, NULL, '2022-02-28 17:32:31'),
(264, 1, '0', 382, 0, '0', 0, NULL, '2022-02-28 17:33:01'),
(265, 1, '0', 349, 0, '0', 2, NULL, '2022-02-28 17:33:57'),
(266, 1, '0', 412, 0, '0', 1, NULL, '2022-02-28 17:34:21'),
(267, 1, '0', 413, 0, '0', 0, NULL, '2022-02-28 17:36:11'),
(268, 1, '0', 368, 0, '0', 0, NULL, '2022-02-28 17:38:56'),
(269, 1, '0', 369, 0, '0', 0, NULL, '2022-02-28 17:41:31'),
(270, 1, '0', 400, 0, '0', 0, NULL, '2022-02-28 17:42:04'),
(271, 1, '0', 392, 0, '0', 0, NULL, '2022-02-28 17:44:58'),
(272, 1, '0', 394, 0, '0', 0, NULL, '2022-02-28 17:45:45'),
(273, 1, '0', 393, 0, '0', 0, NULL, '2022-02-28 17:46:22'),
(274, 1, '0', 395, 0, '0', 0, NULL, '2022-02-28 17:47:20'),
(275, 1, '0', 397, 0, '0', 0, NULL, '2022-02-28 17:47:44'),
(276, 1, '0', 372, 0, '0', 0, NULL, '2022-02-28 17:48:19'),
(277, 1, '0', 373, 0, '0', 0, NULL, '2022-02-28 17:49:04'),
(278, 1, '0', 396, 0, '0', 0, NULL, '2022-03-19 06:26:53'),
(279, 1, '0', 399, 0, '0', 0, NULL, '2022-03-19 06:27:05'),
(280, 1, '0', 401, 0, '0', 0, NULL, '2022-03-19 06:27:17'),
(281, 1, '0', 402, 0, '0', 0, NULL, '2022-03-19 06:27:56'),
(282, 1, '0', 346, 0, '0', 0, NULL, '2022-03-19 06:28:05'),
(283, 1, '0', 347, 0, '0', 0, NULL, '2022-03-19 06:29:54'),
(284, 1, '0', 345, 0, '0', 0, NULL, '2022-03-19 06:30:07'),
(285, 1, '0', 343, 0, '0', 0, NULL, '2022-03-19 06:30:17'),
(286, 1, '0', 344, 0, '0', 0, NULL, '2022-03-19 06:30:27'),
(287, 1, '0', 342, 0, '0', 0, NULL, '2022-03-19 06:30:43'),
(288, 1, '0', 403, 0, '0', 0, NULL, '2022-03-19 06:30:56'),
(289, 1, '0', 405, 0, '0', 0, NULL, '2022-03-19 06:31:07'),
(290, 1, '0', 404, 0, '0', 0, NULL, '2022-03-19 06:31:20'),
(291, 1, '0', 341, 0, '0', 0, NULL, '2022-03-19 06:31:30'),
(292, 1, '0', 340, 0, '0', 0, NULL, '2022-03-19 06:31:47'),
(293, 1, '0', 339, 0, '0', 0, NULL, '2022-03-19 06:31:59'),
(294, 1, '0', 357, 0, '0', 0, NULL, '2022-03-19 06:32:10'),
(295, 1, '0', 358, 0, '0', 0, NULL, '2022-03-19 06:32:40'),
(296, 1, '0', 409, 0, '0', 0, NULL, '2022-03-19 06:33:01'),
(297, 1, '0', 367, 0, '0', 0, NULL, '2022-03-19 06:33:14'),
(298, 1, '0', 366, 0, '0', 0, NULL, '2022-03-19 06:34:08'),
(299, 1, '0', 410, 0, '0', 0, NULL, '2022-03-19 06:34:34'),
(300, 1, '0', 364, 0, '0', 0, NULL, '2022-03-19 06:35:34'),
(301, 1, '0', 365, 0, '0', 0, NULL, '2022-03-19 06:36:08'),
(302, 1, '0', 363, 0, '0', 0, NULL, '2022-03-19 06:36:42'),
(303, 1, '0', 408, 0, '0', 0, NULL, '2022-03-19 06:36:54'),
(304, 1, '0', 407, 0, '0', 0, NULL, '2022-03-19 06:39:48'),
(305, 1, '0', 362, 0, '0', 0, NULL, '2022-03-19 06:40:17'),
(306, 1, '0', 360, 0, '0', 0, NULL, '2022-03-19 06:40:29'),
(307, 1, '0', 361, 0, '0', 0, NULL, '2022-03-19 06:40:40'),
(308, 1, '0', 380, 0, '0', 0, NULL, '2022-03-19 06:40:58'),
(309, 1, '0', 376, 0, '0', 0, NULL, '2022-03-19 06:41:29'),
(310, 1, '0', 375, 0, '0', 0, NULL, '2022-03-19 06:41:54'),
(311, 1, '0', 379, 0, '0', 0, NULL, '2022-03-19 06:42:16'),
(312, 1, '0', 378, 0, '0', 0, NULL, '2022-03-19 06:42:27'),
(313, 1, '0', 377, 0, '0', 0, NULL, '2022-03-19 06:42:41'),
(314, 1, '0', 371, 0, '0', 0, NULL, '2022-03-19 06:43:10'),
(315, 1, '0', 370, 0, '0', 0, NULL, '2022-03-19 06:43:21'),
(316, 1, '0', 374, 0, '0', 0, NULL, '2022-03-19 06:43:33'),
(317, 17, '0', 350, 0, '0', 0, NULL, '2022-03-19 06:47:26'),
(318, 17, '0', 351, 0, '0', 0, NULL, '2022-03-19 06:47:37'),
(319, 17, '0', 356, 0, '0', 0, NULL, '2022-03-19 06:47:47'),
(320, 17, '0', 355, 0, '0', 0, NULL, '2022-03-19 06:47:59'),
(321, 17, '0', 354, 0, '0', 0, NULL, '2022-03-19 06:48:12'),
(322, 17, '0', 353, 0, '0', 0, NULL, '2022-03-19 06:48:23'),
(323, 17, '0', 352, 0, '0', 0, NULL, '2022-03-19 06:49:12'),
(324, 17, '0', 384, 0, '0', 0, NULL, '2022-03-19 06:49:24'),
(325, 17, '0', 385, 0, '0', 0, NULL, '2022-03-19 06:49:42'),
(326, 17, '0', 390, 0, '0', 0, NULL, '2022-03-19 06:49:55'),
(327, 17, '0', 389, 0, '0', 0, NULL, '2022-03-19 06:50:05'),
(328, 17, '0', 391, 0, '0', 0, NULL, '2022-03-19 06:50:15'),
(329, 17, '0', 386, 0, '0', 0, NULL, '2022-03-19 06:50:27'),
(330, 17, '0', 388, 0, '0', 0, NULL, '2022-03-19 06:51:03'),
(331, 17, '0', 387, 0, '0', 0, NULL, '2022-03-19 06:51:15'),
(332, 17, '0', 411, 0, '0', 0, NULL, '2022-03-19 06:51:26'),
(333, 17, '0', 412, 0, '0', 0, NULL, '2022-03-19 06:51:39'),
(334, 17, '0', 413, 0, '0', 0, NULL, '2022-03-19 06:51:49'),
(335, 17, '0', 383, 0, '0', 0, NULL, '2022-03-19 06:51:59'),
(336, 17, '0', 348, 0, '0', 0, NULL, '2022-03-19 06:52:11'),
(337, 17, '0', 382, 0, '0', 0, NULL, '2022-03-19 06:52:22'),
(338, 17, '0', 349, 0, '0', 0, NULL, '2022-03-19 06:52:35'),
(339, 17, '0', 368, 0, '0', 0, NULL, '2022-03-19 06:52:49'),
(340, 17, '0', 369, 0, '0', 0, NULL, '2022-03-19 06:53:01'),
(341, 17, '0', 400, 0, '0', 0, NULL, '2022-03-19 06:53:21'),
(342, 17, '0', 392, 0, '0', 0, NULL, '2022-03-19 06:53:33'),
(343, 17, '0', 394, 0, '0', 0, NULL, '2022-03-19 06:53:47'),
(344, 17, '0', 393, 0, '0', 0, NULL, '2022-03-19 06:53:58'),
(345, 17, '0', 395, 0, '0', 0, NULL, '2022-03-19 06:54:17'),
(346, 17, '0', 398, 0, '0', 0, NULL, '2022-03-19 06:54:35'),
(347, 17, '0', 397, 0, '0', 0, NULL, '2022-03-19 06:54:47'),
(348, 17, '0', 396, 0, '0', 0, NULL, '2022-03-19 06:54:56'),
(349, 17, '0', 399, 0, '0', 0, NULL, '2022-03-19 06:55:14'),
(350, 17, '0', 401, 0, '0', 0, NULL, '2022-03-19 06:55:24'),
(351, 17, '0', 402, 0, '0', 0, NULL, '2022-03-19 06:55:36'),
(352, 17, '0', 346, 0, '0', 0, NULL, '2022-03-19 06:55:52'),
(353, 17, '0', 347, 0, '0', 0, NULL, '2022-03-19 06:56:03'),
(354, 17, '0', 345, 0, '0', 0, NULL, '2022-03-19 06:56:15'),
(355, 17, '0', 343, 0, '0', 0, NULL, '2022-03-19 06:56:26'),
(356, 17, '0', 344, 0, '0', 0, NULL, '2022-03-19 06:56:39'),
(357, 17, '0', 342, 0, '0', 0, NULL, '2022-03-19 06:56:51'),
(358, 17, '0', 403, 0, '0', 0, NULL, '2022-03-19 06:57:03'),
(359, 17, '0', 405, 0, '0', 0, NULL, '2022-03-19 06:58:50'),
(360, 17, '0', 404, 0, '0', 0, NULL, '2022-03-19 06:59:06'),
(361, 17, '0', 341, 0, '0', 0, NULL, '2022-03-19 06:59:16'),
(362, 17, '0', 340, 0, '0', 0, NULL, '2022-03-19 06:59:27'),
(363, 17, '0', 339, 0, '0', 0, NULL, '2022-03-19 06:59:39'),
(364, 17, '0', 357, 0, '0', 0, NULL, '2022-03-19 06:59:49'),
(365, 17, '0', 358, 0, '0', 0, NULL, '2022-03-19 07:00:00'),
(366, 17, '0', 409, 0, '0', 0, NULL, '2022-03-19 07:00:12'),
(367, 17, '0', 367, 0, '0', 0, NULL, '2022-03-19 07:00:22'),
(368, 17, '0', 366, 0, '0', 0, NULL, '2022-03-19 07:00:32'),
(369, 17, '0', 410, 0, '0', 0, NULL, '2022-03-19 07:00:53'),
(370, 17, '0', 364, 0, '0', 0, NULL, '2022-03-19 07:01:24'),
(371, 17, '0', 365, 0, '0', 0, NULL, '2022-03-19 07:02:16'),
(372, 17, '0', 363, 0, '0', 0, NULL, '2022-03-19 07:02:34'),
(373, 17, '0', 408, 0, '0', 0, NULL, '2022-03-19 07:02:45'),
(374, 17, '0', 407, 0, '0', 0, NULL, '2022-03-19 07:04:56'),
(375, 17, '0', 362, 0, '0', 0, NULL, '2022-03-19 07:05:13'),
(376, 17, '0', 360, 0, '0', 0, NULL, '2022-03-19 07:05:23'),
(377, 17, '0', 361, 0, '0', 0, NULL, '2022-03-19 07:05:33'),
(378, 17, '0', 380, 0, '0', 0, NULL, '2022-03-19 07:05:44'),
(379, 17, '0', 376, 0, '0', 0, NULL, '2022-03-19 07:05:55'),
(380, 17, '0', 375, 0, '0', 0, NULL, '2022-03-19 07:06:06'),
(381, 17, '0', 379, 0, '0', 0, NULL, '2022-03-19 07:06:19'),
(382, 17, '0', 378, 0, '0', 0, NULL, '2022-03-19 07:06:33'),
(383, 17, '0', 377, 0, '0', 0, NULL, '2022-03-19 07:07:20'),
(384, 17, '0', 371, 0, '0', 0, NULL, '2022-03-19 07:07:31'),
(385, 17, '0', 370, 0, '0', 0, NULL, '2022-03-19 07:07:42'),
(386, 17, '0', 374, 0, '0', 0, NULL, '2022-03-19 07:08:03'),
(387, 17, '0', 373, 0, '0', 0, NULL, '2022-03-19 07:08:19'),
(388, 17, '0', 372, 0, '0', 0, NULL, '2022-03-19 07:08:30'),
(389, 135, '0', 350, 0, '0', 0, NULL, '2022-03-19 08:58:31'),
(390, 135, '0', 351, 0, '0', 0, NULL, '2022-03-19 08:58:42'),
(391, 135, '0', 356, 0, '0', 0, NULL, '2022-03-19 08:58:53'),
(392, 135, '0', 355, 0, '0', 0, NULL, '2022-03-19 08:59:07'),
(393, 135, '0', 354, 0, '0', 0, NULL, '2022-03-19 09:04:20'),
(394, 135, '0', 353, 0, '0', 0, NULL, '2022-03-19 09:04:32'),
(395, 135, '0', 352, 0, '0', 0, NULL, '2022-03-19 09:04:57'),
(396, 135, '0', 384, 0, '0', 0, NULL, '2022-03-19 09:05:09'),
(397, 135, '0', 385, 0, '0', 0, NULL, '2022-03-19 09:05:22'),
(398, 135, '0', 390, 0, '0', 0, NULL, '2022-03-19 09:05:37'),
(399, 135, '0', 389, 0, '0', 0, NULL, '2022-03-19 09:05:51'),
(400, 135, '0', 391, 0, '0', 0, NULL, '2022-03-19 09:06:02'),
(401, 135, '0', 386, 0, '0', 0, NULL, '2022-03-19 09:06:16'),
(402, 135, '0', 388, 0, '0', 0, NULL, '2022-03-19 09:06:32'),
(403, 135, '0', 387, 0, '0', 0, NULL, '2022-03-19 09:06:45'),
(404, 135, '0', 411, 0, '0', 0, NULL, '2022-03-19 09:06:56'),
(405, 135, '0', 412, 0, '0', 0, NULL, '2022-03-19 09:07:08'),
(406, 135, '0', 413, 0, '0', 0, NULL, '2022-03-19 09:07:22'),
(407, 135, '0', 383, 0, '0', 0, NULL, '2022-03-19 09:07:33'),
(408, 135, '0', 348, 0, '0', 0, NULL, '2022-03-19 09:07:44'),
(409, 135, '0', 382, 0, '0', 0, NULL, '2022-03-19 09:08:33'),
(410, 135, '0', 349, 0, '0', 0, NULL, '2022-03-19 09:09:07'),
(411, 135, '0', 368, 0, '0', 0, NULL, '2022-03-19 09:09:22'),
(412, 135, '0', 369, 0, '0', 0, NULL, '2022-03-19 09:09:31'),
(413, 135, '0', 400, 0, '0', 0, NULL, '2022-03-19 09:09:42'),
(414, 135, '0', 392, 0, '0', 0, NULL, '2022-03-19 09:11:11'),
(415, 135, '0', 394, 0, '0', 0, NULL, '2022-03-19 09:11:23'),
(416, 135, '0', 393, 0, '0', 0, NULL, '2022-03-19 09:12:56'),
(417, 135, '0', 395, 0, '0', 0, NULL, '2022-03-19 09:13:34'),
(418, 135, '0', 398, 0, '0', 0, NULL, '2022-03-19 09:13:49'),
(419, 135, '0', 397, 0, '0', 0, NULL, '2022-03-19 09:14:35'),
(420, 135, '0', 396, 0, '0', 0, NULL, '2022-03-19 09:15:23'),
(421, 135, '0', 399, 0, '0', 0, NULL, '2022-03-19 09:16:16'),
(422, 135, '0', 401, 0, '0', 0, NULL, '2022-03-19 09:19:36'),
(423, 135, '0', 402, 0, '0', 0, NULL, '2022-03-19 09:21:55'),
(424, 135, '0', 346, 0, '0', 0, NULL, '2022-03-19 09:22:08'),
(425, 135, '0', 347, 0, '0', 0, NULL, '2022-03-19 09:22:20'),
(426, 135, '0', 345, 0, '0', 0, NULL, '2022-03-19 09:39:34'),
(427, 135, '0', 343, 0, '0', 0, NULL, '2022-03-19 09:39:46'),
(428, 135, '0', 344, 0, '0', 0, NULL, '2022-03-19 09:40:06'),
(429, 135, '0', 342, 0, '0', 0, NULL, '2022-03-19 09:40:19'),
(430, 135, '0', 403, 0, '0', 0, NULL, '2022-03-19 09:40:51'),
(431, 135, '0', 405, 0, '0', 0, NULL, '2022-03-19 09:41:19'),
(432, 135, '0', 404, 0, '0', 0, NULL, '2022-03-19 09:41:30'),
(433, 135, '0', 341, 0, '0', 0, NULL, '2022-03-19 09:41:55'),
(434, 135, '0', 340, 0, '0', 0, NULL, '2022-03-19 09:42:37'),
(435, 135, '0', 339, 0, '0', 0, NULL, '2022-03-19 09:42:52'),
(436, 135, '0', 357, 0, '0', 0, NULL, '2022-03-19 09:43:05'),
(437, 135, '0', 358, 0, '0', 0, NULL, '2022-03-19 09:43:24'),
(438, 135, '0', 409, 0, '0', 0, NULL, '2022-03-19 09:43:36'),
(439, 135, '0', 367, 0, '0', 0, NULL, '2022-03-19 09:45:46'),
(440, 135, '0', 366, 0, '0', 0, NULL, '2022-03-19 09:47:14'),
(441, 135, '0', 410, 0, '0', 0, NULL, '2022-03-19 09:47:29'),
(442, 135, '0', 364, 0, '0', 0, NULL, '2022-03-19 09:47:45'),
(443, 135, '0', 365, 0, '0', 0, NULL, '2022-03-19 09:47:59'),
(444, 135, '0', 363, 0, '0', 0, NULL, '2022-03-19 09:48:19'),
(445, 135, '0', 408, 0, '0', 0, NULL, '2022-03-19 09:48:33'),
(446, 135, '0', 407, 0, '0', 0, NULL, '2022-03-19 09:48:46'),
(447, 135, '0', 362, 0, '0', 0, NULL, '2022-03-19 09:48:58'),
(448, 135, '0', 360, 0, '0', 0, NULL, '2022-03-19 09:49:10'),
(449, 135, '0', 361, 0, '0', 0, NULL, '2022-03-19 09:49:22'),
(450, 135, '0', 380, 0, '0', 0, NULL, '2022-03-19 09:49:39'),
(451, 135, '0', 376, 0, '0', 0, NULL, '2022-03-19 09:50:30'),
(452, 135, '0', 375, 0, '0', 0, NULL, '2022-03-19 09:50:50'),
(453, 135, '0', 379, 0, '0', 0, NULL, '2022-03-19 09:51:01'),
(454, 135, '0', 378, 0, '0', 0, NULL, '2022-03-19 09:51:17'),
(455, 135, '0', 377, 0, '0', 0, NULL, '2022-03-19 09:51:31'),
(456, 135, '0', 371, 0, '0', 0, NULL, '2022-03-19 09:51:41'),
(457, 135, '0', 370, 0, '0', 0, NULL, '2022-03-19 09:52:24'),
(458, 135, '0', 374, 0, '0', 0, NULL, '2022-03-19 09:52:56'),
(459, 135, '0', 373, 0, '0', 0, NULL, '2022-03-19 09:53:15'),
(460, 135, '0', 372, 0, '0', 0, NULL, '2022-03-19 09:53:28'),
(461, 3, '0', 350, 0, '0', 10, NULL, '2022-03-19 10:11:30'),
(462, 3, '0', 351, 0, '0', 20, NULL, '2022-03-19 10:11:40'),
(463, 3, '0', 354, 0, '0', 3, NULL, '2022-03-19 10:11:50'),
(464, 3, '0', 372, 0, '0', 22, NULL, '2022-03-19 10:12:12'),
(465, 3, '0', 373, 0, '0', 2, NULL, '2022-03-19 10:12:20'),
(466, 3, '0', 374, 0, '0', 22, NULL, '2022-03-19 10:12:31'),
(467, 3, '0', 371, 0, '0', 2, NULL, '2022-03-19 10:12:42'),
(468, 3, '0', 370, 0, '0', 222, NULL, '2022-03-19 10:12:49'),
(469, 3, '0', 377, 0, '0', 22, NULL, '2022-03-19 10:12:56'),
(470, 3, '0', 378, 0, '0', 22, NULL, '2022-03-19 10:13:07'),
(471, 3, '0', 379, 0, '0', 22, NULL, '2022-03-19 10:13:14'),
(472, 3, '0', 375, 0, '0', 22, NULL, '2022-03-19 10:13:22'),
(473, 3, '0', 376, 0, '0', 22, NULL, '2022-03-19 10:13:27'),
(474, 3, '0', 380, 0, '0', 44, NULL, '2022-03-19 10:13:36'),
(475, 3, '0', 408, 0, '0', 10, NULL, '2022-03-19 10:13:46'),
(476, 3, '0', 363, 0, '0', 33, NULL, '2022-03-19 10:13:54'),
(477, 3, '0', 364, 0, '0', 33, NULL, '2022-03-19 10:14:01'),
(478, 3, '0', 365, 0, '0', 33, NULL, '2022-03-19 10:14:09'),
(479, 3, '0', 407, 0, '0', 33, NULL, '2022-03-19 10:14:17'),
(480, 3, '0', 362, 0, '0', 3, NULL, '2022-03-19 10:14:30'),
(481, 3, '0', 360, 0, '0', 3, NULL, '2022-03-19 10:14:37'),
(482, 3, '0', 361, 0, '0', 3, NULL, '2022-03-19 10:20:35'),
(483, 3, '0', 358, 0, '0', 44, NULL, '2022-03-19 10:20:48'),
(485, 3, '0', 409, 0, '0', 2, NULL, '2022-03-19 10:21:24'),
(486, 3, '0', 367, 0, '0', 55, NULL, '2022-03-19 10:21:41'),
(487, 3, '0', 366, 0, '0', 55, NULL, '2022-03-19 10:22:26'),
(488, 3, '0', 410, 0, '0', 44, NULL, '2022-03-19 10:27:29'),
(489, 3, '0', 357, 0, '0', 7, NULL, '2022-03-19 10:28:21'),
(490, 3, '0', 339, 0, '0', 6, NULL, '2022-03-19 10:33:13'),
(491, 3, '0', 345, 0, '0', 55, NULL, '2022-03-19 10:39:56'),
(492, 3, '0', 356, 0, '0', 11, NULL, '2022-03-19 10:41:12'),
(493, 3, '0', 355, 0, '0', 11, NULL, '2022-03-19 10:41:27'),
(494, 3, '0', 353, 0, '0', 10, NULL, '2022-03-19 10:41:48'),
(495, 3, '0', 352, 0, '0', 11, NULL, '2022-03-19 10:41:58'),
(496, 3, '0', 384, 0, '0', 11, NULL, '2022-03-19 10:42:06'),
(497, 3, '0', 385, 0, '0', 11, NULL, '2022-03-19 10:42:22'),
(498, 3, '0', 390, 0, '0', 11, NULL, '2022-03-19 10:42:32'),
(499, 3, '0', 389, 0, '0', 11, NULL, '2022-03-19 10:42:43'),
(500, 3, '0', 368, 0, '0', 11, NULL, '2022-03-19 10:42:54'),
(501, 3, '0', 369, 0, '0', 1, NULL, '2022-03-19 10:43:05'),
(502, 3, '0', 400, 0, '0', 1, NULL, '2022-03-19 10:43:19'),
(503, 3, '0', 392, 0, '0', 2, NULL, '2022-03-19 10:43:31'),
(504, 3, '0', 394, 0, '0', 2, NULL, '2022-03-19 10:43:39'),
(505, 3, '0', 393, 0, '0', 2, NULL, '2022-03-19 10:44:08'),
(506, 3, '0', 395, 0, '0', 22, NULL, '2022-03-19 10:44:20'),
(507, 3, '0', 398, 0, '0', 22, NULL, '2022-03-19 10:44:41'),
(508, 3, '0', 397, 0, '0', 2, NULL, '2022-03-19 10:44:48'),
(509, 3, '0', 396, 0, '0', 2, NULL, '2022-03-19 10:45:11'),
(510, 3, '0', 399, 0, '0', 22, NULL, '2022-03-19 10:45:23'),
(511, 3, '0', 402, 0, '0', 3, NULL, '2022-03-19 10:46:11'),
(512, 3, '0', 391, 0, '0', 22, NULL, '2022-03-19 11:47:27'),
(513, 3, '0', 386, 0, '0', 22, NULL, '2022-03-19 11:47:37'),
(514, 3, '0', 388, 0, '0', 22, NULL, '2022-03-19 11:47:46'),
(515, 3, '0', 387, 0, '0', 22, NULL, '2022-03-19 11:47:53'),
(516, 3, '0', 411, 0, '0', 22, NULL, '2022-03-19 11:48:03'),
(517, 3, '0', 412, 0, '0', 22, NULL, '2022-03-19 11:48:10'),
(518, 3, '0', 413, 0, '0', 22, NULL, '2022-03-19 11:48:19'),
(519, 3, '0', 383, 0, '0', 22, NULL, '2022-03-19 11:48:26'),
(520, 3, '0', 348, 0, '0', 22, NULL, '2022-03-19 11:48:41'),
(521, 3, '0', 382, 0, '0', 22, NULL, '2022-03-19 11:48:50'),
(522, 3, '0', 349, 0, '0', 11, NULL, '2022-03-19 11:51:36'),
(523, 3, '0', 401, 0, '0', 1, NULL, '2022-03-19 11:51:46'),
(524, 3, '0', 346, 0, '0', 10, NULL, '2022-03-19 11:51:57'),
(525, 3, '0', 347, 0, '0', 1, NULL, '2022-03-19 11:52:05'),
(526, 3, '0', 343, 0, '0', 22, NULL, '2022-03-19 11:52:34'),
(527, 3, '0', 344, 0, '0', 1, NULL, '2022-03-19 11:52:45'),
(528, 3, '0', 342, 0, '0', 1, NULL, '2022-03-19 11:53:24'),
(529, 3, '0', 403, 0, '0', 11, NULL, '2022-03-19 11:53:55'),
(530, 3, '0', 405, 0, '0', 1, NULL, '2022-03-19 11:54:19'),
(531, 3, '0', 404, 0, '0', 1, NULL, '2022-03-19 11:54:32'),
(532, 3, '0', 341, 0, '0', 1, NULL, '2022-03-19 11:54:47'),
(533, 3, '0', 340, 0, '0', 1, NULL, '2022-03-19 11:55:05'),
(534, 107, '0', 351, 0, '0', 150, NULL, '2022-03-20 16:48:49'),
(535, 107, '0', 355, 0, '0', 190, NULL, '2022-03-20 16:49:19'),
(536, 107, '0', 350, 0, '0', 67, NULL, '2022-03-20 17:12:37'),
(537, 107, '0', 356, 0, '0', 90, NULL, '2022-03-20 17:12:59'),
(538, 107, '0', 354, 0, '0', 200, NULL, '2022-03-20 17:13:30'),
(539, 107, '0', 353, 0, '0', 120, NULL, '2022-03-20 17:14:11'),
(540, 107, '0', 352, 0, '0', 809, NULL, '2022-03-20 17:15:31'),
(541, 107, '0', 372, 0, '0', 60, NULL, '2022-03-20 17:16:14'),
(542, 107, '0', 3, 0, '0', 0, NULL, '2022-03-20 17:27:31'),
(543, 107, '0', 384, 0, '0', 60, NULL, '2022-03-20 17:31:04'),
(544, 107, '0', 385, 0, '0', 78, NULL, '2022-03-20 17:36:18'),
(545, 107, '0', 390, 0, '0', 90, NULL, '2022-03-20 17:36:45'),
(546, 107, '0', 389, 0, '0', 40, NULL, '2022-03-20 17:37:22'),
(547, 107, '0', 391, 0, '0', 5, NULL, '2022-03-20 17:39:33'),
(548, 107, '0', 386, 0, '0', 56, NULL, '2022-03-20 17:41:38'),
(549, 107, '0', 388, 0, '0', 40, NULL, '2022-03-20 17:43:46'),
(551, 107, '0', 387, 0, '0', 56, NULL, '2022-03-20 17:44:13'),
(552, 107, '0', 393, 0, '0', 6, NULL, '2022-03-20 17:50:36'),
(553, 107, '0', 394, 0, '0', 90, NULL, '2022-03-20 17:51:08'),
(554, 107, '0', 392, 0, '0', 90, NULL, '2022-03-20 17:51:25'),
(555, 107, '0', 400, 0, '0', 87, NULL, '2022-03-20 17:51:40'),
(556, 107, '0', 368, 0, '0', 87, NULL, '2022-03-20 17:56:13'),
(557, 107, '0', 383, 0, '0', 0, NULL, '2022-03-20 17:56:35'),
(558, 107, '0', 348, 0, '0', 98, NULL, '2022-03-20 17:57:12'),
(559, 107, '0', 349, 0, '0', 78, NULL, '2022-03-20 18:02:48'),
(560, 107, '0', 382, 0, '0', 67, NULL, '2022-03-20 18:03:01'),
(561, 107, '0', 369, 0, '0', 20, NULL, '2022-03-20 18:03:20'),
(562, 107, '0', 395, 0, '0', 45, NULL, '2022-03-20 18:03:35'),
(563, 107, '0', 398, 0, '0', 86, NULL, '2022-03-20 18:03:48'),
(565, 107, '0', 397, 0, '0', 90, NULL, '2022-03-20 18:04:11'),
(566, 4, '0', 350, 0, '0', 1, NULL, '2022-03-20 18:05:18'),
(567, 107, '0', 396, 0, '0', 65, NULL, '2022-03-20 18:07:04'),
(568, 107, '0', 373, 0, '0', 67, NULL, '2022-03-20 18:13:38'),
(569, 107, '0', 374, 0, '0', 78, NULL, '2022-03-20 18:14:11'),
(570, 107, '0', 377, 0, '0', 34, NULL, '2022-03-20 18:14:37'),
(571, 107, '0', 371, 0, '0', 54, NULL, '2022-03-20 18:16:00'),
(572, 107, '0', 370, 0, '0', 65, NULL, '2022-03-20 18:25:21'),
(573, 107, '0', 378, 0, '0', 87, NULL, '2022-03-20 18:26:08'),
(574, 107, '0', 379, 0, '0', 67, NULL, '2022-03-20 18:26:24'),
(576, 107, '0', 410, 0, '0', 129, NULL, '2022-03-20 18:26:45'),
(577, 107, '0', 367, 0, '0', 67, NULL, '2022-03-20 18:26:59'),
(578, 107, '0', 411, 0, '0', 67, NULL, '2022-03-20 18:30:55'),
(579, 107, '0', 412, 0, '0', 56, NULL, '2022-03-20 18:31:15'),
(580, 107, '0', 413, 0, '0', 86, NULL, '2022-03-20 18:31:36'),
(581, 107, '0', 399, 0, '0', 89, NULL, '2022-03-20 18:31:53'),
(582, 107, '0', 401, 0, '0', 90, NULL, '2022-03-20 18:32:10'),
(583, 107, '0', 402, 0, '0', 56, NULL, '2022-03-20 18:32:38'),
(584, 107, '0', 346, 0, '0', 167, NULL, '2022-03-20 18:34:39'),
(585, 107, '0', 347, 0, '0', 50, NULL, '2022-03-20 18:34:52'),
(586, 107, '0', 345, 0, '0', 45, NULL, '2022-03-20 18:37:01'),
(588, 107, '0', 343, 0, '0', 78, NULL, '2022-03-20 18:37:33'),
(589, 107, '0', 344, 0, '0', 46, NULL, '2022-03-20 18:37:48'),
(590, 107, '0', 342, 0, '0', 86, NULL, '2022-03-20 18:37:58'),
(591, 107, '0', 403, 0, '0', 60, NULL, '2022-03-20 18:38:13'),
(592, 107, '0', 404, 0, '0', 98, NULL, '2022-03-20 18:39:32'),
(593, 107, '0', 341, 0, '0', 71, NULL, '2022-03-20 18:40:11'),
(594, 107, '0', 340, 0, '0', 98, NULL, '2022-03-20 18:40:32'),
(595, 107, '0', 405, 0, '0', 97, NULL, '2022-03-20 18:40:46'),
(596, 107, '0', 339, 0, '0', 36, NULL, '2022-03-20 18:41:08'),
(597, 107, '0', 357, 0, '0', 190, NULL, '2022-03-20 18:41:20'),
(598, 107, '0', 364, 0, '0', 50, NULL, '2022-03-20 18:42:35'),
(599, 107, '0', 365, 0, '0', 28, NULL, '2022-03-20 18:42:47'),
(600, 107, '0', 363, 0, '0', 178, NULL, '2022-03-20 18:43:01'),
(601, 107, '0', 366, 0, '0', 180, NULL, '2022-03-20 18:43:14'),
(602, 107, '0', 358, 0, '0', 68, NULL, '2022-03-20 18:43:42'),
(603, 107, '0', 409, 0, '0', 128, NULL, '2022-03-20 18:44:27'),
(604, 107, '0', 408, 0, '0', 90, NULL, '2022-03-20 18:50:28'),
(605, 107, '0', 407, 0, '0', 78, NULL, '2022-03-20 18:50:55'),
(606, 107, '0', 362, 0, '0', 67, NULL, '2022-03-20 18:51:15'),
(608, 107, '0', 360, 0, '0', 136, NULL, '2022-03-20 18:51:28'),
(609, 107, '0', 361, 0, '0', 78, NULL, '2022-03-20 18:51:58'),
(610, 107, '0', 380, 0, '0', 120, NULL, '2022-03-20 18:52:13'),
(611, 107, '0', 376, 0, '0', 160, NULL, '2022-03-20 18:52:37'),
(612, 107, '0', 375, 0, '0', 60, NULL, '2022-03-20 18:53:12'),
(613, 4, '0', 351, 0, '0', 1, NULL, '2022-03-20 20:02:08'),
(614, 4, '0', 356, 0, '0', 1, NULL, '2022-03-20 20:02:17'),
(615, 4, '0', 355, 0, '0', 1, NULL, '2022-03-20 20:02:23'),
(616, 4, '0', 354, 0, '0', 1, NULL, '2022-03-20 20:02:33'),
(617, 4, '0', 353, 0, '0', 1, NULL, '2022-03-20 20:02:41'),
(618, 4, '0', 352, 0, '0', 1, NULL, '2022-03-20 20:02:51'),
(619, 4, '0', 3, 0, '0', 1, NULL, '2022-03-20 20:02:59'),
(620, 4, '0', 384, 0, '0', 1, NULL, '2022-03-20 20:03:07'),
(621, 4, '0', 385, 0, '0', 1, NULL, '2022-03-20 20:03:16'),
(622, 4, '0', 390, 0, '0', 1, NULL, '2022-03-20 20:03:24'),
(623, 4, '0', 389, 0, '0', 1, NULL, '2022-03-20 20:03:39'),
(624, 4, '0', 391, 0, '0', 1, NULL, '2022-03-20 20:03:46'),
(625, 4, '0', 386, 0, '0', 1, NULL, '2022-03-20 20:03:56'),
(626, 4, '0', 388, 0, '0', 1, NULL, '2022-03-20 20:04:07'),
(627, 4, '0', 387, 0, '0', 1, NULL, '2022-03-20 20:04:16'),
(628, 4, '0', 411, 0, '0', 1, NULL, '2022-03-20 20:04:24'),
(629, 4, '0', 412, 0, '0', 1, NULL, '2022-03-20 20:04:33'),
(630, 4, '0', 413, 0, '0', 11, NULL, '2022-03-20 20:04:42'),
(631, 4, '0', 383, 0, '0', 1, NULL, '2022-03-20 20:04:50'),
(632, 4, '0', 348, 0, '0', 1, NULL, '2022-03-20 20:04:59'),
(633, 4, '0', 382, 0, '0', 1, NULL, '2022-03-20 20:05:07'),
(634, 4, '0', 349, 0, '0', 1, NULL, '2022-03-20 20:05:14'),
(635, 4, '0', 368, 0, '0', 1, NULL, '2022-03-20 20:05:25'),
(636, 4, '0', 369, 0, '0', 1, NULL, '2022-03-20 20:05:33'),
(637, 4, '0', 400, 0, '0', 1, NULL, '2022-03-20 20:05:40'),
(638, 4, '0', 392, 0, '0', 1, NULL, '2022-03-20 20:05:49'),
(639, 4, '0', 394, 0, '0', 1, NULL, '2022-03-20 20:05:55'),
(640, 4, '0', 393, 0, '0', 1, NULL, '2022-03-20 20:06:03'),
(641, 4, '0', 395, 0, '0', 1, NULL, '2022-03-20 20:06:11'),
(642, 4, '0', 398, 0, '0', 1, NULL, '2022-03-20 20:06:17'),
(643, 4, '0', 397, 0, '0', 1, NULL, '2022-03-20 20:06:25'),
(644, 4, '0', 396, 0, '0', 1, NULL, '2022-03-20 20:06:33'),
(645, 4, '0', 399, 0, '0', 1, NULL, '2022-03-20 20:06:39'),
(646, 4, '0', 401, 0, '0', 1, NULL, '2022-03-20 20:06:46'),
(647, 4, '0', 402, 0, '0', 1, NULL, '2022-03-20 20:06:56'),
(648, 4, '0', 346, 0, '0', 1, NULL, '2022-03-20 20:07:03'),
(649, 4, '0', 347, 0, '0', 1, NULL, '2022-03-20 20:07:10'),
(650, 4, '0', 345, 0, '0', 1, NULL, '2022-03-20 20:07:21'),
(651, 4, '0', 343, 0, '0', 1, NULL, '2022-03-20 20:07:27'),
(652, 4, '0', 344, 0, '0', 1, NULL, '2022-03-20 20:07:34'),
(653, 4, '0', 342, 0, '0', 1, NULL, '2022-03-20 20:07:40'),
(654, 4, '0', 403, 0, '0', 1, NULL, '2022-03-20 20:07:52'),
(655, 4, '0', 405, 0, '0', 1, NULL, '2022-03-20 20:08:00'),
(656, 4, '0', 404, 0, '0', 1, NULL, '2022-03-20 20:08:06'),
(657, 4, '0', 341, 0, '0', 1, NULL, '2022-03-20 20:08:15'),
(658, 4, '0', 340, 0, '0', 1, NULL, '2022-03-20 20:08:23'),
(659, 4, '0', 339, 0, '0', 1, NULL, '2022-03-20 20:08:30'),
(660, 4, '0', 357, 0, '0', 1, NULL, '2022-03-20 20:08:38'),
(662, 4, '0', 358, 0, '0', 1, NULL, '2022-03-20 20:08:44'),
(663, 4, '0', 409, 0, '0', 1, NULL, '2022-03-20 20:08:55'),
(664, 4, '0', 367, 0, '0', 1, NULL, '2022-03-20 20:09:01'),
(665, 4, '0', 366, 0, '0', 1, NULL, '2022-03-20 20:09:08'),
(666, 4, '0', 410, 0, '0', 1, NULL, '2022-03-20 20:09:16'),
(667, 4, '0', 372, 0, '0', 1, NULL, '2022-03-20 20:09:28'),
(668, 4, '0', 373, 0, '0', 1, NULL, '2022-03-20 20:09:36'),
(669, 4, '0', 374, 0, '0', 1, NULL, '2022-03-20 20:09:45'),
(670, 4, '0', 370, 0, '0', 1, NULL, '2022-03-20 20:09:52'),
(671, 4, '0', 371, 0, '0', 1, NULL, '2022-03-20 20:09:59'),
(672, 4, '0', 377, 0, '0', 1, NULL, '2022-03-20 20:10:06'),
(673, 4, '0', 378, 0, '0', 1, NULL, '2022-03-20 20:10:13'),
(674, 4, '0', 379, 0, '0', 1, NULL, '2022-03-20 20:10:20'),
(675, 4, '0', 380, 0, '0', 1, NULL, '2022-03-20 20:10:29'),
(676, 4, '0', 376, 0, '0', 1, NULL, '2022-03-20 20:10:36'),
(677, 4, '0', 375, 0, '0', 1, NULL, '2022-03-20 20:10:43'),
(678, 4, '0', 360, 0, '0', 1, NULL, '2022-03-20 20:10:51'),
(679, 4, '0', 361, 0, '0', 1, NULL, '2022-03-20 20:11:00'),
(680, 4, '0', 362, 0, '0', 1, NULL, '2022-03-20 20:11:06'),
(681, 4, '0', 407, 0, '0', 1, NULL, '2022-03-20 20:11:13'),
(682, 4, '0', 363, 0, '0', 1, NULL, '2022-03-20 20:11:23'),
(683, 4, '0', 364, 0, '0', 1, NULL, '2022-03-20 20:11:30'),
(684, 4, '0', 365, 0, '0', 1, NULL, '2022-03-20 20:11:38'),
(685, 4, '0', 408, 0, '0', 1, NULL, '2022-03-20 20:11:44'),
(689, 132, '0', 3, 0, '0', 1, NULL, '2022-03-21 22:45:38'),
(690, 12, '0', 350, 0, '0', 9, NULL, '2022-03-22 13:33:44'),
(691, 12, '0', 351, 0, '0', 6, NULL, '2022-03-22 13:33:56'),
(692, 12, '0', 356, 0, '0', 8, NULL, '2022-03-22 13:34:24'),
(693, 6, '0', 350, 0, '0', 0, NULL, '2022-03-22 13:40:30'),
(694, 18, '0', 350, 0, '0', 10, NULL, '2022-03-23 07:21:08'),
(695, 18, '0', 351, 0, '0', 5, NULL, '2022-03-23 07:21:21'),
(696, 18, '0', 356, 0, '0', 0, NULL, '2022-03-23 07:21:33'),
(697, 18, '0', 355, 0, '0', 9, NULL, '2022-03-23 07:21:50'),
(698, 18, '0', 354, 0, '0', 7, NULL, '2022-03-23 07:21:59'),
(699, 18, '0', 353, 0, '0', 8, NULL, '2022-03-23 07:22:11'),
(700, 18, '0', 352, 0, '0', 2, NULL, '2022-03-23 07:22:22'),
(701, 18, '0', 382, 0, '0', 3, NULL, '2022-03-23 07:22:38'),
(702, 18, '0', 368, 0, '0', 4, NULL, '2022-03-23 07:22:49'),
(703, 18, '0', 369, 0, '0', 0, NULL, '2022-03-23 07:22:59'),
(704, 18, '0', 400, 0, '0', 4, NULL, '2022-03-23 07:23:08'),
(705, 18, '0', 349, 0, '0', 5, NULL, '2022-03-23 07:23:16'),
(706, 18, '0', 392, 0, '0', 3, NULL, '2022-03-23 07:23:28'),
(707, 18, '0', 348, 0, '0', 4, NULL, '2022-03-23 07:23:36'),
(708, 18, '0', 383, 0, '0', 6, NULL, '2022-03-23 07:23:47'),
(709, 18, '0', 413, 0, '0', 4, NULL, '2022-03-23 07:23:59'),
(710, 18, '0', 387, 0, '0', 6, NULL, '2022-03-23 07:24:08'),
(711, 18, '0', 388, 0, '0', 0, NULL, '2022-03-23 07:24:18'),
(712, 18, '0', 411, 0, '0', 0, NULL, '2022-03-23 07:24:27'),
(713, 18, '0', 412, 0, '0', 0, NULL, '2022-03-23 07:24:36'),
(714, 18, '0', 386, 0, '0', 0, NULL, '2022-03-23 07:24:45'),
(715, 18, '0', 384, 0, '0', 0, NULL, '2022-03-23 07:24:58'),
(716, 18, '0', 385, 0, '0', 9, NULL, '2022-03-23 07:25:08'),
(717, 18, '0', 390, 0, '0', 0, NULL, '2022-03-23 07:25:18'),
(718, 18, '0', 389, 0, '0', 0, NULL, '2022-03-23 07:25:25'),
(719, 18, '0', 391, 0, '0', 3, NULL, '2022-03-23 07:25:36'),
(720, 18, '0', 394, 0, '0', 0, NULL, '2022-03-23 07:25:52'),
(721, 18, '0', 393, 0, '0', 0, NULL, '2022-03-23 07:26:00'),
(722, 18, '0', 395, 0, '0', 0, NULL, '2022-03-23 07:26:17'),
(723, 18, '0', 398, 0, '0', 8, NULL, '2022-03-23 07:26:30'),
(724, 18, '0', 397, 0, '0', 9, NULL, '2022-03-23 07:26:40'),
(726, 18, '0', 396, 0, '0', 0, NULL, '2022-03-23 07:27:01'),
(727, 18, '0', 399, 0, '0', 0, NULL, '2022-03-23 07:27:13'),
(728, 42, '0', 350, 0, '0', 4, NULL, '2022-03-23 07:27:21'),
(729, 18, '0', 401, 0, '0', 0, NULL, '2022-03-23 07:27:27'),
(730, 18, '0', 402, 0, '0', 0, NULL, '2022-03-23 07:27:35'),
(731, 42, '0', 351, 0, '0', 2, NULL, '2022-03-23 07:27:35'),
(732, 18, '0', 347, 0, '0', 0, NULL, '2022-03-23 07:27:46'),
(733, 42, '0', 356, 0, '0', 2, NULL, '2022-03-23 07:27:51'),
(734, 18, '0', 346, 0, '0', 1, NULL, '2022-03-23 07:27:56'),
(735, 42, '0', 355, 0, '0', 2, NULL, '2022-03-23 07:28:03'),
(736, 18, '0', 345, 0, '0', 7, NULL, '2022-03-23 07:28:27'),
(737, 18, '0', 343, 0, '0', 8, NULL, '2022-03-23 07:28:41'),
(738, 18, '0', 344, 0, '0', 20, NULL, '2022-03-23 07:29:10'),
(739, 42, '0', 354, 0, '0', 0, NULL, '2022-03-23 07:29:10'),
(740, 42, '0', 353, 0, '0', 0, NULL, '2022-03-23 07:29:21'),
(741, 18, '0', 342, 0, '0', 10, NULL, '2022-03-23 07:29:24'),
(742, 18, '0', 403, 0, '0', 7, NULL, '2022-03-23 07:29:39'),
(743, 18, '0', 405, 0, '0', 7, NULL, '2022-03-23 07:29:55'),
(744, 18, '0', 404, 0, '0', 4, NULL, '2022-03-23 07:30:04'),
(745, 18, '0', 341, 0, '0', 8, NULL, '2022-03-23 07:30:20'),
(746, 18, '0', 340, 0, '0', 6, NULL, '2022-03-23 07:30:30'),
(747, 18, '0', 339, 0, '0', 9, NULL, '2022-03-23 07:30:39'),
(748, 42, '0', 352, 0, '0', 2, NULL, '2022-03-23 07:30:56'),
(750, 18, '0', 357, 0, '0', 2, NULL, '2022-03-23 07:31:32'),
(751, 42, '0', 384, 0, '0', 2, NULL, '2022-03-23 07:31:37'),
(753, 18, '0', 358, 0, '0', 7, NULL, '2022-03-23 07:31:40'),
(754, 42, '0', 385, 0, '0', 8, NULL, '2022-03-23 07:31:50'),
(823, 18, '0', 409, 0, '0', 1, NULL, '2022-03-23 07:50:13'),
(756, 18, '0', 367, 0, '0', 8, NULL, '2022-03-23 07:32:03'),
(757, 42, '0', 390, 0, '0', 0, NULL, '2022-03-23 07:32:13'),
(758, 18, '0', 366, 0, '0', 8, NULL, '2022-03-23 07:32:14'),
(759, 18, '0', 410, 0, '0', 5, NULL, '2022-03-23 07:32:24'),
(760, 18, '0', 364, 0, '0', 4, NULL, '2022-03-23 07:32:36'),
(761, 18, '0', 363, 0, '0', 9, NULL, '2022-03-23 07:32:48'),
(762, 18, '0', 365, 0, '0', 9, NULL, '2022-03-23 07:32:56'),
(763, 18, '0', 408, 0, '0', 8, NULL, '2022-03-23 07:33:04'),
(764, 18, '0', 407, 0, '0', 6, NULL, '2022-03-23 07:33:13'),
(765, 18, '0', 362, 0, '0', 8, NULL, '2022-03-23 07:33:32'),
(766, 18, '0', 380, 0, '0', 0, NULL, '2022-03-23 07:33:43'),
(767, 18, '0', 361, 0, '0', 0, NULL, '2022-03-23 07:33:51'),
(768, 42, '0', 389, 0, '0', 2, NULL, '2022-03-23 07:33:56'),
(769, 18, '0', 360, 0, '0', 7, NULL, '2022-03-23 07:34:02'),
(770, 42, '0', 391, 0, '0', 5, NULL, '2022-03-23 07:34:10'),
(807, 18, '0', 372, 0, '0', 2, NULL, '2022-03-23 07:47:26'),
(772, 18, '0', 373, 0, '0', 0, NULL, '2022-03-23 07:34:25'),
(773, 42, '0', 386, 0, '0', 5, NULL, '2022-03-23 07:34:25'),
(774, 18, '0', 374, 0, '0', 9, NULL, '2022-03-23 07:34:34'),
(775, 18, '0', 371, 0, '0', 7, NULL, '2022-03-23 07:34:43'),
(776, 18, '0', 370, 0, '0', 9, NULL, '2022-03-23 07:34:51'),
(777, 18, '0', 378, 0, '0', 3, NULL, '2022-03-23 07:35:00'),
(778, 18, '0', 377, 0, '0', 0, NULL, '2022-03-23 07:35:07'),
(779, 18, '0', 379, 0, '0', 0, NULL, '2022-03-23 07:35:16'),
(780, 18, '0', 375, 0, '0', 0, NULL, '2022-03-23 07:35:25'),
(781, 18, '0', 376, 0, '0', 0, NULL, '2022-03-23 07:35:46'),
(782, 42, '0', 388, 0, '0', 3, NULL, '2022-03-23 07:36:35'),
(783, 42, '0', 387, 0, '0', 4, NULL, '2022-03-23 07:36:44'),
(784, 42, '0', 411, 0, '0', 3, NULL, '2022-03-23 07:36:55'),
(785, 42, '0', 412, 0, '0', 5, NULL, '2022-03-23 07:37:06'),
(786, 42, '0', 413, 0, '0', 3, NULL, '2022-03-23 07:37:24'),
(787, 42, '0', 383, 0, '0', 2, NULL, '2022-03-23 07:37:37'),
(788, 42, '0', 348, 0, '0', 3, NULL, '2022-03-23 07:37:51'),
(789, 43, '0', 372, 0, '0', 9, NULL, '2022-03-23 07:43:37'),
(790, 43, '0', 374, 0, '0', 6, NULL, '2022-03-23 07:43:46'),
(791, 43, '0', 378, 0, '0', 0, NULL, '2022-03-23 07:43:58'),
(792, 43, '0', 377, 0, '0', 0, NULL, '2022-03-23 07:44:05'),
(793, 43, '0', 371, 0, '0', 0, NULL, '2022-03-23 07:44:13'),
(794, 43, '0', 370, 0, '0', 0, NULL, '2022-03-23 07:44:22'),
(795, 43, '0', 373, 0, '0', 0, NULL, '2022-03-23 07:44:31'),
(796, 43, '0', 379, 0, '0', 0, NULL, '2022-03-23 07:44:45'),
(797, 43, '0', 345, 0, '0', 0, NULL, '2022-03-23 07:45:17'),
(798, 43, '0', 343, 0, '0', 0, NULL, '2022-03-23 07:45:29'),
(799, 43, '0', 344, 0, '0', 0, NULL, '2022-03-23 07:45:37'),
(800, 43, '0', 342, 0, '0', 0, NULL, '2022-03-23 07:45:48'),
(801, 43, '0', 403, 0, '0', 0, NULL, '2022-03-23 07:46:24'),
(802, 43, '0', 405, 0, '0', 0, NULL, '2022-03-23 07:46:37'),
(803, 43, '0', 404, 0, '0', 0, NULL, '2022-03-23 07:46:46'),
(804, 43, '0', 341, 0, '0', 0, NULL, '2022-03-23 07:46:55'),
(805, 43, '0', 340, 0, '0', 0, NULL, '2022-03-23 07:47:05'),
(806, 43, '0', 347, 0, '0', 7, NULL, '2022-03-23 07:47:13'),
(808, 43, '0', 339, 0, '0', 1, NULL, '2022-03-23 07:47:26'),
(809, 43, '0', 357, 0, '0', 2, NULL, '2022-03-23 07:47:38'),
(810, 43, '0', 358, 0, '0', 1, NULL, '2022-03-23 07:47:46'),
(811, 43, '0', 409, 0, '0', 9, NULL, '2022-03-23 07:48:08'),
(812, 43, '0', 366, 0, '0', 6, NULL, '2022-03-23 07:48:16'),
(813, 43, '0', 367, 0, '0', 5, NULL, '2022-03-23 07:48:26'),
(814, 43, '0', 410, 0, '0', 9, NULL, '2022-03-23 07:48:34'),
(815, 43, '0', 364, 0, '0', 8, NULL, '2022-03-23 07:48:50'),
(816, 43, '0', 365, 0, '0', 3, NULL, '2022-03-23 07:48:59'),
(817, 43, '0', 363, 0, '0', 6, NULL, '2022-03-23 07:49:09'),
(818, 43, '0', 408, 0, '0', 7, NULL, '2022-03-23 07:49:20'),
(819, 43, '0', 407, 0, '0', 2, NULL, '2022-03-23 07:49:33'),
(820, 43, '0', 362, 0, '0', 7, NULL, '2022-03-23 07:49:45'),
(821, 43, '0', 360, 0, '0', 0, NULL, '2022-03-23 07:49:59'),
(822, 43, '0', 380, 0, '0', 8, NULL, '2022-03-23 07:50:08'),
(824, 43, '0', 361, 0, '0', 9, NULL, '2022-03-23 07:50:18'),
(825, 43, '0', 376, 0, '0', 0, NULL, '2022-03-23 07:50:27'),
(826, 43, '0', 375, 0, '0', 0, NULL, '2022-03-23 07:50:38'),
(827, 43, '0', 350, 0, '0', 0, NULL, '2022-03-23 07:50:53'),
(828, 43, '0', 351, 0, '0', 0, NULL, '2022-03-23 07:51:06'),
(829, 43, '0', 356, 0, '0', 0, NULL, '2022-03-23 07:51:23'),
(831, 43, '0', 355, 0, '0', 9, NULL, '2022-03-23 07:51:42'),
(832, 43, '0', 412, 0, '0', 0, NULL, '2022-03-23 07:51:52'),
(833, 43, '0', 348, 0, '0', 0, NULL, '2022-03-23 07:52:00'),
(834, 43, '0', 383, 0, '0', 0, NULL, '2022-03-23 07:52:08'),
(835, 43, '0', 382, 0, '0', 0, NULL, '2022-03-23 07:52:16'),
(836, 43, '0', 349, 0, '0', 1, NULL, '2022-03-23 07:52:25'),
(837, 43, '0', 368, 0, '0', 1, NULL, '2022-03-23 07:52:33'),
(838, 43, '0', 411, 0, '0', 1, NULL, '2022-03-23 07:52:42'),
(839, 43, '0', 413, 0, '0', 1, NULL, '2022-03-23 07:52:51'),
(840, 43, '0', 369, 0, '0', 1, NULL, '2022-03-23 07:53:02'),
(841, 43, '0', 393, 0, '0', 12, NULL, '2022-03-23 07:53:14'),
(842, 43, '0', 398, 0, '0', 8, NULL, '2022-03-23 07:53:23'),
(843, 43, '0', 396, 0, '0', 0, NULL, '2022-03-23 07:53:31'),
(844, 43, '0', 397, 0, '0', 9, NULL, '2022-03-23 07:53:39'),
(845, 43, '0', 399, 0, '0', 9, NULL, '2022-03-23 07:53:47'),
(846, 43, '0', 395, 0, '0', 9, NULL, '2022-03-23 07:53:56'),
(847, 43, '0', 394, 0, '0', 8, NULL, '2022-03-23 07:54:06'),
(848, 43, '0', 401, 0, '0', 0, NULL, '2022-03-23 07:54:13'),
(849, 43, '0', 402, 0, '0', 0, NULL, '2022-03-23 07:54:22'),
(850, 43, '0', 346, 0, '0', 0, NULL, '2022-03-23 07:54:34'),
(851, 43, '0', 354, 0, '0', 8, NULL, '2022-03-23 07:54:50'),
(852, 43, '0', 353, 0, '0', 8, NULL, '2022-03-23 07:54:58'),
(853, 43, '0', 352, 0, '0', 9, NULL, '2022-03-23 07:55:06'),
(854, 43, '0', 384, 0, '0', 0, NULL, '2022-03-23 07:55:16'),
(855, 43, '0', 385, 0, '0', 3, NULL, '2022-03-23 07:55:24'),
(856, 43, '0', 390, 0, '0', 0, NULL, '2022-03-23 07:55:33'),
(857, 43, '0', 389, 0, '0', 0, NULL, '2022-03-23 07:55:56'),
(858, 43, '0', 391, 0, '0', 0, NULL, '2022-03-23 07:56:06'),
(859, 43, '0', 386, 0, '0', 0, NULL, '2022-03-23 07:56:14'),
(860, 43, '0', 388, 0, '0', 0, NULL, '2022-03-23 07:56:23'),
(861, 43, '0', 387, 0, '0', 0, NULL, '2022-03-23 07:56:31'),
(862, 43, '0', 400, 0, '0', 9, NULL, '2022-03-23 07:56:47'),
(863, 43, '0', 392, 0, '0', 0, NULL, '2022-03-23 07:56:55'),
(864, 144, '0', 350, 0, '0', 10, NULL, '2022-03-24 03:29:30'),
(865, 144, '0', 351, 0, '0', 10, NULL, '2022-03-24 03:29:39');
INSERT INTO `tbl_indicator_output_baseline_values` (`id`, `indid`, `key_unique`, `level3`, `location`, `disaggregations`, `value`, `respondent`, `datecreated`) VALUES
(866, 144, '0', 356, 0, '0', 10, NULL, '2022-03-24 03:29:48'),
(867, 144, '0', 355, 0, '0', 10, NULL, '2022-03-24 03:29:56'),
(868, 144, '0', 354, 0, '0', 10, NULL, '2022-03-24 03:30:04'),
(869, 144, '0', 353, 0, '0', 10, NULL, '2022-03-24 03:30:13'),
(870, 144, '0', 352, 0, '0', 10, NULL, '2022-03-24 03:30:21'),
(871, 144, '0', 384, 0, '0', 10, NULL, '2022-03-24 03:30:32'),
(872, 144, '0', 385, 0, '0', 10, NULL, '2022-03-24 03:30:40'),
(873, 144, '0', 390, 0, '0', 10, NULL, '2022-03-24 03:30:48'),
(874, 144, '0', 389, 0, '0', 10, NULL, '2022-03-24 03:30:56'),
(875, 144, '0', 391, 0, '0', 10, NULL, '2022-03-24 03:31:04'),
(876, 144, '0', 386, 0, '0', 10, NULL, '2022-03-24 03:31:12'),
(877, 144, '0', 388, 0, '0', 10, NULL, '2022-03-24 03:31:19'),
(878, 144, '0', 387, 0, '0', 10, NULL, '2022-03-24 03:31:29'),
(879, 144, '0', 411, 0, '0', 10, NULL, '2022-03-24 03:31:42'),
(880, 144, '0', 412, 0, '0', 10, NULL, '2022-03-24 03:31:56'),
(881, 144, '0', 413, 0, '0', 10, NULL, '2022-03-24 03:32:06'),
(882, 144, '0', 383, 0, '0', 10, NULL, '2022-03-24 03:32:19'),
(883, 144, '0', 348, 0, '0', 10, NULL, '2022-03-24 03:32:27'),
(884, 144, '0', 382, 0, '0', 10, NULL, '2022-03-24 03:32:35'),
(885, 144, '0', 349, 0, '0', 10, NULL, '2022-03-24 03:32:44'),
(886, 144, '0', 368, 0, '0', 10, NULL, '2022-03-24 03:32:55'),
(887, 144, '0', 369, 0, '0', 10, NULL, '2022-03-24 03:33:04'),
(888, 144, '0', 400, 0, '0', 10, NULL, '2022-03-24 03:33:12'),
(889, 144, '0', 392, 0, '0', 10, NULL, '2022-03-24 03:33:21'),
(890, 144, '0', 394, 0, '0', 10, NULL, '2022-03-24 03:33:30'),
(891, 144, '0', 393, 0, '0', 10, NULL, '2022-03-24 03:33:40'),
(892, 144, '0', 395, 0, '0', 10, NULL, '2022-03-24 03:33:49'),
(893, 144, '0', 398, 0, '0', 10, NULL, '2022-03-24 03:33:57'),
(894, 144, '0', 397, 0, '0', 10, NULL, '2022-03-24 03:34:13'),
(895, 144, '0', 396, 0, '0', 10, NULL, '2022-03-24 03:34:23'),
(896, 144, '0', 399, 0, '0', 10, NULL, '2022-03-24 03:34:33'),
(897, 144, '0', 401, 0, '0', 10, NULL, '2022-03-24 03:34:42'),
(899, 144, '0', 402, 0, '0', 10, NULL, '2022-03-24 03:35:03'),
(900, 144, '0', 346, 0, '0', 10, NULL, '2022-03-24 03:37:06'),
(901, 144, '0', 347, 0, '0', 10, NULL, '2022-03-24 03:37:36'),
(902, 144, '0', 345, 0, '0', 10, NULL, '2022-03-24 03:38:09'),
(903, 144, '0', 343, 0, '0', 10, NULL, '2022-03-24 03:38:18'),
(904, 144, '0', 344, 0, '0', 10, NULL, '2022-03-24 03:38:27'),
(905, 144, '0', 342, 0, '0', 10, NULL, '2022-03-24 03:38:39'),
(906, 144, '0', 403, 0, '0', 10, NULL, '2022-03-24 03:38:49'),
(907, 144, '0', 405, 0, '0', 10, NULL, '2022-03-24 03:38:57'),
(908, 144, '0', 404, 0, '0', 10, NULL, '2022-03-24 03:39:05'),
(909, 144, '0', 341, 0, '0', 10, NULL, '2022-03-24 03:39:14'),
(910, 144, '0', 340, 0, '0', 10, NULL, '2022-03-24 03:39:24'),
(911, 144, '0', 339, 0, '0', 10, NULL, '2022-03-24 03:39:33'),
(912, 144, '0', 357, 0, '0', 10, NULL, '2022-03-24 03:39:41'),
(913, 144, '0', 358, 0, '0', 10, NULL, '2022-03-24 03:39:54'),
(914, 144, '0', 409, 0, '0', 10, NULL, '2022-03-24 03:40:03'),
(915, 144, '0', 367, 0, '0', 10, NULL, '2022-03-24 03:40:14'),
(916, 144, '0', 366, 0, '0', 10, NULL, '2022-03-24 03:40:22'),
(917, 144, '0', 410, 0, '0', 10, NULL, '2022-03-24 03:40:31'),
(918, 144, '0', 364, 0, '0', 10, NULL, '2022-03-24 03:40:40'),
(919, 144, '0', 365, 0, '0', 10, NULL, '2022-03-24 03:40:48'),
(920, 144, '0', 363, 0, '0', 10, NULL, '2022-03-24 03:40:58'),
(921, 144, '0', 408, 0, '0', 10, NULL, '2022-03-24 03:41:10'),
(922, 144, '0', 407, 0, '0', 10, NULL, '2022-03-24 03:41:18'),
(923, 144, '0', 362, 0, '0', 10, NULL, '2022-03-24 03:41:26'),
(924, 144, '0', 360, 0, '0', 10, NULL, '2022-03-24 03:41:35'),
(925, 144, '0', 361, 0, '0', 10, NULL, '2022-03-24 03:41:42'),
(926, 144, '0', 380, 0, '0', 10, NULL, '2022-03-24 03:41:57'),
(927, 144, '0', 376, 0, '0', 10, NULL, '2022-03-24 03:42:08'),
(928, 144, '0', 375, 0, '0', 10, NULL, '2022-03-24 03:42:18'),
(929, 144, '0', 379, 0, '0', 10, NULL, '2022-03-24 03:42:26'),
(930, 144, '0', 378, 0, '0', 10, NULL, '2022-03-24 03:42:35'),
(931, 144, '0', 377, 0, '0', 10, NULL, '2022-03-24 03:42:46'),
(932, 144, '0', 371, 0, '0', 10, NULL, '2022-03-24 03:42:55'),
(933, 144, '0', 370, 0, '0', 10, NULL, '2022-03-24 03:43:03'),
(934, 144, '0', 374, 0, '0', 10, NULL, '2022-03-24 03:43:11'),
(935, 144, '0', 373, 0, '0', 10, NULL, '2022-03-24 03:43:19'),
(936, 144, '0', 372, 0, '0', 10, NULL, '2022-03-24 03:43:27'),
(937, 12, '0', 355, 0, '0', 0, NULL, '2022-03-25 09:37:29'),
(938, 12, '0', 354, 0, '0', 0, NULL, '2022-03-25 09:37:44'),
(939, 12, '0', 353, 0, '0', 0, NULL, '2022-03-25 09:38:09'),
(940, 12, '0', 352, 0, '0', 0, NULL, '2022-03-25 09:39:21'),
(941, 12, '0', 384, 0, '0', 0, NULL, '2022-03-25 09:39:33'),
(942, 12, '0', 385, 0, '0', 0, NULL, '2022-03-25 09:39:50'),
(943, 12, '0', 411, 0, '0', 2, NULL, '2022-03-25 09:40:03'),
(944, 12, '0', 412, 0, '0', 4, NULL, '2022-03-25 09:40:14'),
(945, 12, '0', 413, 0, '0', 0, NULL, '2022-03-25 09:40:26'),
(946, 12, '0', 383, 0, '0', 0, NULL, '2022-03-25 09:40:37'),
(947, 12, '0', 387, 0, '0', 0, NULL, '2022-03-25 09:41:00'),
(948, 12, '0', 348, 0, '0', 0, NULL, '2022-03-25 09:41:18'),
(950, 12, '0', 382, 0, '0', 5, NULL, '2022-03-25 09:41:32'),
(951, 12, '0', 349, 0, '0', 0, NULL, '2022-03-25 09:43:36'),
(952, 12, '0', 368, 0, '0', 0, NULL, '2022-03-25 09:43:46'),
(953, 12, '0', 388, 0, '0', 0, NULL, '2022-03-25 09:44:10'),
(954, 12, '0', 369, 0, '0', 1, NULL, '2022-03-25 09:44:22'),
(955, 12, '0', 401, 0, '0', 8, NULL, '2022-03-25 09:44:35'),
(956, 12, '0', 402, 0, '0', 3, NULL, '2022-03-25 09:44:44'),
(957, 12, '0', 345, 0, '0', 0, NULL, '2022-03-25 09:45:01'),
(958, 12, '0', 346, 0, '0', 1, NULL, '2022-03-25 09:45:14'),
(959, 12, '0', 347, 0, '0', 0, NULL, '2022-03-25 09:45:23'),
(960, 12, '0', 399, 0, '0', 2, NULL, '2022-03-25 09:45:32'),
(961, 12, '0', 396, 0, '0', 2, NULL, '2022-03-25 09:45:45'),
(962, 12, '0', 397, 0, '0', 1, NULL, '2022-03-25 09:45:55'),
(963, 12, '0', 398, 0, '0', 2, NULL, '2022-03-25 09:46:03'),
(964, 12, '0', 403, 0, '0', 0, NULL, '2022-03-25 09:46:14'),
(965, 12, '0', 405, 0, '0', 0, NULL, '2022-03-25 09:46:24'),
(966, 12, '0', 404, 0, '0', 0, NULL, '2022-03-25 09:46:34'),
(967, 12, '0', 341, 0, '0', 0, NULL, '2022-03-25 09:46:43'),
(968, 12, '0', 340, 0, '0', 0, NULL, '2022-03-25 09:46:52'),
(969, 12, '0', 339, 0, '0', 0, NULL, '2022-03-25 09:47:01'),
(970, 12, '0', 342, 0, '0', 0, NULL, '2022-03-25 09:47:15'),
(971, 12, '0', 343, 0, '0', 0, NULL, '2022-03-25 09:47:25'),
(972, 12, '0', 344, 0, '0', 0, NULL, '2022-03-25 09:47:34'),
(973, 12, '0', 357, 0, '0', 0, NULL, '2022-03-25 09:47:45'),
(974, 12, '0', 367, 0, '0', 0, NULL, '2022-03-25 09:47:56'),
(975, 12, '0', 366, 0, '0', 0, NULL, '2022-03-25 09:48:05'),
(976, 12, '0', 409, 0, '0', 0, NULL, '2022-03-25 09:48:15'),
(977, 12, '0', 365, 0, '0', 0, NULL, '2022-03-25 09:48:27'),
(978, 12, '0', 358, 0, '0', 8, NULL, '2022-03-25 09:48:39'),
(979, 12, '0', 410, 0, '0', 2, NULL, '2022-03-25 09:48:53'),
(980, 12, '0', 364, 0, '0', 1, NULL, '2022-03-25 09:49:07'),
(981, 12, '0', 363, 0, '0', 7, NULL, '2022-03-25 09:49:21'),
(982, 12, '0', 408, 0, '0', 5, NULL, '2022-03-25 09:49:34'),
(983, 12, '0', 362, 0, '0', 1, NULL, '2022-03-25 09:49:46'),
(984, 12, '0', 361, 0, '0', 0, NULL, '2022-03-25 09:50:01'),
(985, 12, '0', 360, 0, '0', 1, NULL, '2022-03-25 09:50:11'),
(986, 12, '0', 407, 0, '0', 9, NULL, '2022-03-25 09:50:24'),
(987, 12, '0', 380, 0, '0', 0, NULL, '2022-03-25 09:50:36'),
(988, 12, '0', 376, 0, '0', 5, NULL, '2022-03-25 09:50:55'),
(989, 12, '0', 375, 0, '0', 4, NULL, '2022-03-25 09:51:10'),
(990, 12, '0', 379, 0, '0', 0, NULL, '2022-03-25 09:51:28'),
(991, 12, '0', 377, 0, '0', 0, NULL, '2022-03-25 09:51:38'),
(992, 12, '0', 378, 0, '0', 0, NULL, '2022-03-25 09:51:50'),
(993, 12, '0', 371, 0, '0', 0, NULL, '2022-03-25 09:52:00'),
(994, 12, '0', 370, 0, '0', 0, NULL, '2022-03-25 09:52:35'),
(995, 12, '0', 374, 0, '0', 0, NULL, '2022-03-25 09:52:46'),
(996, 12, '0', 373, 0, '0', 0, NULL, '2022-03-25 09:52:58'),
(997, 12, '0', 372, 0, '0', 0, NULL, '2022-03-25 09:53:14'),
(998, 12, '0', 400, 0, '0', 2, NULL, '2022-03-25 09:53:36'),
(999, 12, '0', 392, 0, '0', 2, NULL, '2022-03-25 09:53:48'),
(1000, 12, '0', 394, 0, '0', 2, NULL, '2022-03-25 09:53:59'),
(1001, 12, '0', 393, 0, '0', 2, NULL, '2022-03-25 09:54:19'),
(1002, 12, '0', 395, 0, '0', 5, NULL, '2022-03-25 09:54:30'),
(1003, 12, '0', 390, 0, '0', 1, NULL, '2022-03-25 09:55:10'),
(1004, 12, '0', 389, 0, '0', 0, NULL, '2022-03-25 09:55:24'),
(1005, 12, '0', 391, 0, '0', 0, NULL, '2022-03-25 09:55:38'),
(1006, 12, '0', 386, 0, '0', 0, NULL, '2022-03-25 09:55:52'),
(1007, 5, '0', 350, 0, '0', 1, NULL, '2022-03-25 11:38:42'),
(1008, 5, '0', 351, 0, '0', 3, NULL, '2022-03-25 11:39:00'),
(1009, 5, '0', 356, 0, '0', 1, NULL, '2022-03-25 11:39:42'),
(1010, 147, '0', 3, 0, '0', 4, NULL, '2022-03-25 13:05:46'),
(1011, 151, '0', 350, 0, '0', 1000, NULL, '2022-03-30 07:01:24'),
(1012, 151, '0', 351, 0, '0', 1000, NULL, '2022-03-30 07:01:37'),
(1013, 151, '0', 356, 0, '0', 1000, NULL, '2022-03-30 07:01:47'),
(1014, 151, '0', 355, 0, '0', 1000, NULL, '2022-03-30 07:01:57'),
(1015, 151, '0', 354, 0, '0', 1000, NULL, '2022-03-30 07:02:08'),
(1016, 151, '0', 353, 0, '0', 1000, NULL, '2022-03-30 07:02:18'),
(1017, 151, '0', 352, 0, '0', 1000, NULL, '2022-03-30 07:02:26'),
(1019, 151, '0', 384, 0, '0', 1000, NULL, '2022-03-30 07:02:34'),
(1021, 151, '0', 385, 0, '0', 1000, NULL, '2022-03-30 07:02:45'),
(1022, 151, '0', 390, 0, '0', 1000, NULL, '2022-03-30 07:02:55'),
(1023, 151, '0', 389, 0, '0', 1000, NULL, '2022-03-30 07:03:05'),
(1024, 151, '0', 391, 0, '0', 1000, NULL, '2022-03-30 07:03:13'),
(1025, 151, '0', 386, 0, '0', 1000, NULL, '2022-03-30 07:03:30'),
(1026, 151, '0', 388, 0, '0', 1000, NULL, '2022-03-30 07:04:12'),
(1027, 151, '0', 387, 0, '0', 1000, NULL, '2022-03-30 07:04:35'),
(1028, 151, '0', 411, 0, '0', 1000, NULL, '2022-03-30 07:05:14'),
(1029, 151, '0', 412, 0, '0', 1000, NULL, '2022-03-30 07:05:21'),
(1030, 151, '0', 413, 0, '0', 1000, NULL, '2022-03-30 07:05:28'),
(1031, 151, '0', 383, 0, '0', 1000, NULL, '2022-03-30 07:05:49'),
(1032, 151, '0', 348, 0, '0', 1000, NULL, '2022-03-30 07:05:59'),
(1033, 151, '0', 382, 0, '0', 1000, NULL, '2022-03-30 07:06:15'),
(1034, 151, '0', 349, 0, '0', 1000, NULL, '2022-03-30 07:06:56'),
(1035, 151, '0', 368, 0, '0', 1000, NULL, '2022-03-30 07:07:06'),
(1036, 151, '0', 369, 0, '0', 1000, NULL, '2022-03-30 07:08:07'),
(1037, 151, '0', 400, 0, '0', 1000, NULL, '2022-03-30 07:08:14'),
(1038, 151, '0', 392, 0, '0', 1000, NULL, '2022-03-30 07:08:22'),
(1039, 151, '0', 394, 0, '0', 1000, NULL, '2022-03-30 07:08:31'),
(1040, 151, '0', 393, 0, '0', 1000, NULL, '2022-03-30 07:08:41'),
(1041, 151, '0', 395, 0, '0', 1000, NULL, '2022-03-30 07:08:47'),
(1042, 151, '0', 398, 0, '0', 1000, NULL, '2022-03-30 07:08:54'),
(1043, 151, '0', 397, 0, '0', 1000, NULL, '2022-03-30 07:09:02'),
(1044, 151, '0', 396, 0, '0', 1000, NULL, '2022-03-30 07:09:16'),
(1045, 151, '0', 399, 0, '0', 1000, NULL, '2022-03-30 07:09:23'),
(1046, 151, '0', 401, 0, '0', 1000, NULL, '2022-03-30 07:09:31'),
(1047, 151, '0', 402, 0, '0', 1000, NULL, '2022-03-30 07:09:39'),
(1048, 151, '0', 346, 0, '0', 1000, NULL, '2022-03-30 07:09:47'),
(1049, 151, '0', 347, 0, '0', 1000, NULL, '2022-03-30 07:09:56'),
(1050, 151, '0', 345, 0, '0', 1000, NULL, '2022-03-30 07:10:04'),
(1051, 151, '0', 343, 0, '0', 1000, NULL, '2022-03-30 07:10:11'),
(1052, 151, '0', 344, 0, '0', 1000, NULL, '2022-03-30 07:10:18'),
(1053, 151, '0', 342, 0, '0', 1000, NULL, '2022-03-30 07:10:28'),
(1054, 151, '0', 403, 0, '0', 1000, NULL, '2022-03-30 07:10:36'),
(1055, 151, '0', 405, 0, '0', 1000, NULL, '2022-03-30 07:10:43'),
(1056, 151, '0', 404, 0, '0', 1000, NULL, '2022-03-30 07:10:52'),
(1057, 151, '0', 341, 0, '0', 1000, NULL, '2022-03-30 07:10:59'),
(1058, 151, '0', 340, 0, '0', 1000, NULL, '2022-03-30 07:11:06'),
(1059, 151, '0', 339, 0, '0', 1000, NULL, '2022-03-30 07:11:14'),
(1060, 151, '0', 357, 0, '0', 1000, NULL, '2022-03-30 07:11:22'),
(1061, 151, '0', 358, 0, '0', 1000, NULL, '2022-03-30 07:11:30'),
(1062, 151, '0', 409, 0, '0', 1000, NULL, '2022-03-30 07:11:37'),
(1064, 151, '0', 367, 0, '0', 1000, NULL, '2022-03-30 07:11:55'),
(1065, 151, '0', 366, 0, '0', 1000, NULL, '2022-03-30 07:12:17'),
(1067, 151, '0', 410, 0, '0', 1000, NULL, '2022-03-30 07:12:35'),
(1068, 151, '0', 364, 0, '0', 1000, NULL, '2022-03-30 07:12:47'),
(1069, 151, '0', 365, 0, '0', 1000, NULL, '2022-03-30 07:12:58'),
(1070, 151, '0', 363, 0, '0', 1000, NULL, '2022-03-30 07:13:08'),
(1071, 151, '0', 408, 0, '0', 1000, NULL, '2022-03-30 07:15:17'),
(1072, 151, '0', 407, 0, '0', 1000, NULL, '2022-03-30 07:15:25'),
(1073, 151, '0', 362, 0, '0', 1000, NULL, '2022-03-30 07:15:34'),
(1074, 151, '0', 360, 0, '0', 1000, NULL, '2022-03-30 07:15:41'),
(1075, 151, '0', 361, 0, '0', 1000, NULL, '2022-03-30 07:15:49'),
(1076, 151, '0', 380, 0, '0', 1000, NULL, '2022-03-30 07:15:57'),
(1077, 151, '0', 376, 0, '0', 1000, NULL, '2022-03-30 07:16:04'),
(1078, 151, '0', 375, 0, '0', 1000, NULL, '2022-03-30 07:16:23'),
(1079, 151, '0', 379, 0, '0', 1000, NULL, '2022-03-30 07:16:38'),
(1080, 151, '0', 378, 0, '0', 1000, NULL, '2022-03-30 07:16:52'),
(1081, 151, '0', 377, 0, '0', 1000, NULL, '2022-03-30 07:17:03'),
(1082, 151, '0', 371, 0, '0', 1000, NULL, '2022-03-30 07:17:14'),
(1083, 151, '0', 370, 0, '0', 1000, NULL, '2022-03-30 07:17:25'),
(1085, 151, '0', 374, 0, '0', 1000, NULL, '2022-03-30 07:17:33'),
(1086, 151, '0', 373, 0, '0', 1000, NULL, '2022-03-30 07:17:46'),
(1087, 151, '0', 372, 0, '0', 1000, NULL, '2022-03-30 07:17:54'),
(1088, 154, '0', 3, 0, '0', 4, NULL, '2022-04-02 11:49:21'),
(1089, 156, '0', 3, 0, '0', 3, NULL, '2022-04-02 11:50:38'),
(1090, 158, '0', 3, 0, '0', 1, NULL, '2022-04-02 11:52:02'),
(1091, 159, '0', 3, 0, '0', 0, NULL, '2022-04-02 11:54:34'),
(1092, 155, '0', 3, 0, '0', 0, NULL, '2022-04-02 11:56:08'),
(1093, 163, '', 350, 0, 'root', 1000, NULL, '2022-04-04 09:14:21'),
(1094, 163, '', 351, 0, 'root', 1000, NULL, '2022-04-04 09:21:04'),
(1095, 163, '', 356, 0, 'root', 1000, NULL, '2022-04-04 09:21:19'),
(1096, 163, '', 355, 0, 'root', 1000, NULL, '2022-04-04 09:21:28'),
(1097, 163, '', 354, 0, 'root', 1000, NULL, '2022-04-04 09:23:05'),
(1098, 163, '', 353, 0, 'root', 1000, NULL, '2022-04-04 09:23:15'),
(1099, 163, '', 352, 0, 'root', 1000, NULL, '2022-04-04 09:23:26'),
(1100, 163, '', 384, 0, 'root', 1000, NULL, '2022-04-04 09:23:52'),
(1101, 163, '', 385, 0, 'root', 1000, NULL, '2022-04-04 09:24:02'),
(1102, 163, '', 390, 0, 'root', 1000, NULL, '2022-04-04 09:24:11'),
(1103, 163, '', 389, 0, 'root', 1000, NULL, '2022-04-04 09:24:19'),
(1104, 163, '', 391, 0, 'root', 1000, NULL, '2022-04-04 09:24:30'),
(1105, 163, '', 386, 0, 'root', 1000, NULL, '2022-04-04 09:24:39'),
(1106, 163, '', 388, 0, 'root', 1000, NULL, '2022-04-04 09:24:49'),
(1107, 163, '', 387, 0, 'root', 1000, NULL, '2022-04-04 09:24:58'),
(1108, 163, '', 411, 0, 'root', 1000, NULL, '2022-04-04 09:25:11'),
(1109, 163, '', 412, 0, 'root', 1000, NULL, '2022-04-04 09:25:20'),
(1110, 163, '', 413, 0, 'root', 1000, NULL, '2022-04-04 09:25:32'),
(1111, 163, '', 383, 0, 'root', 1000, NULL, '2022-04-04 09:25:40'),
(1112, 163, '', 348, 0, 'root', 1000, NULL, '2022-04-04 09:25:49'),
(1113, 163, '', 382, 0, 'root', 1000, NULL, '2022-04-04 09:25:59'),
(1114, 163, '', 349, 0, 'root', 1000, NULL, '2022-04-04 09:26:07'),
(1115, 163, '', 368, 0, 'root', 1000, NULL, '2022-04-04 09:26:19'),
(1116, 163, '', 369, 0, 'root', 1000, NULL, '2022-04-04 09:26:41'),
(1117, 163, '', 400, 0, 'root', 1000, NULL, '2022-04-04 09:26:58'),
(1118, 163, '', 392, 0, 'root', 1000, NULL, '2022-04-04 09:27:07'),
(1119, 163, '', 394, 0, 'root', 1000, NULL, '2022-04-04 09:27:17'),
(1120, 163, '', 393, 0, 'root', 1000, NULL, '2022-04-04 09:27:34'),
(1121, 163, '', 395, 0, 'root', 1000, NULL, '2022-04-04 09:27:44'),
(1122, 163, '', 398, 0, 'root', 1000, NULL, '2022-04-04 09:27:54'),
(1124, 163, '', 397, 0, 'root', 1000, NULL, '2022-04-04 09:28:12'),
(1125, 163, '', 396, 0, 'root', 1000, NULL, '2022-04-04 09:28:29'),
(1126, 163, '', 399, 0, 'root', 1000, NULL, '2022-04-04 09:28:48'),
(1127, 163, '', 401, 0, 'root', 1000, NULL, '2022-04-04 09:29:05'),
(1128, 163, '', 402, 0, 'root', 1000, NULL, '2022-04-04 09:29:15'),
(1129, 163, '', 346, 0, 'root', 1000, NULL, '2022-04-04 09:29:34'),
(1130, 163, '', 347, 0, 'root', 1000, NULL, '2022-04-04 09:29:44'),
(1131, 163, '', 345, 0, 'root', 1000, NULL, '2022-04-04 09:29:54'),
(1132, 163, '', 343, 0, 'root', 1000, NULL, '2022-04-04 09:30:03'),
(1133, 163, '', 344, 0, 'root', 1000, NULL, '2022-04-04 09:30:13'),
(1134, 163, '', 342, 0, 'root', 1000, NULL, '2022-04-04 09:30:27'),
(1135, 163, '', 403, 0, 'root', 1000, NULL, '2022-04-04 09:30:36'),
(1136, 163, '', 405, 0, 'root', 1000, NULL, '2022-04-04 09:30:45'),
(1137, 163, '', 404, 0, 'root', 1000, NULL, '2022-04-04 09:31:00'),
(1138, 163, '', 341, 0, 'root', 1000, NULL, '2022-04-04 09:31:10'),
(1139, 163, '', 340, 0, 'root', 1000, NULL, '2022-04-04 09:31:22'),
(1140, 163, '', 339, 0, 'root', 1000, NULL, '2022-04-04 09:31:32'),
(1141, 163, '', 357, 0, 'root', 1000, NULL, '2022-04-04 09:31:41'),
(1142, 163, '', 358, 0, 'root', 1000, NULL, '2022-04-04 09:31:51'),
(1143, 163, '', 409, 0, 'root', 1000, NULL, '2022-04-04 09:32:00'),
(1144, 163, '', 367, 0, 'root', 1000, NULL, '2022-04-04 09:32:15'),
(1145, 163, '', 366, 0, 'root', 1000, NULL, '2022-04-04 09:32:24'),
(1146, 163, '', 410, 0, 'root', 1000, NULL, '2022-04-04 09:32:48'),
(1147, 163, '', 364, 0, 'root', 1000, NULL, '2022-04-04 09:33:11'),
(1148, 163, '', 365, 0, 'root', 1000, NULL, '2022-04-04 09:33:30'),
(1149, 163, '', 363, 0, 'root', 1000, NULL, '2022-04-04 09:33:41'),
(1150, 163, '', 408, 0, 'root', 1000, NULL, '2022-04-04 09:33:56'),
(1151, 163, '', 407, 0, 'root', 1000, NULL, '2022-04-04 09:34:06'),
(1152, 163, '', 362, 0, 'root', 1000, NULL, '2022-04-04 09:34:23'),
(1153, 163, '', 360, 0, 'root', 1000, NULL, '2022-04-04 09:34:33'),
(1154, 163, '', 361, 0, 'root', 1000, NULL, '2022-04-04 09:34:43'),
(1155, 163, '', 380, 0, 'root', 1000, NULL, '2022-04-04 09:34:57'),
(1156, 163, '', 376, 0, 'root', 1000, NULL, '2022-04-04 09:35:10'),
(1157, 163, '', 375, 0, 'root', 1000, NULL, '2022-04-04 09:35:20'),
(1158, 163, '', 379, 0, 'root', 1000, NULL, '2022-04-04 09:35:29'),
(1159, 163, '', 378, 0, 'root', 1000, NULL, '2022-04-04 09:35:38'),
(1160, 163, '', 377, 0, 'root', 1000, NULL, '2022-04-04 09:35:54'),
(1161, 163, '', 371, 0, 'root', 1000, NULL, '2022-04-04 09:36:03'),
(1162, 163, '', 370, 0, 'root', 1000, NULL, '2022-04-04 09:36:12'),
(1163, 163, '', 374, 0, 'root', 1000, NULL, '2022-04-04 09:36:22'),
(1164, 163, '', 373, 0, 'root', 1000, NULL, '2022-04-04 09:36:30'),
(1165, 163, '', 372, 0, 'root', 1000, NULL, '2022-04-04 09:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_strategic_plan_targets`
--

CREATE TABLE `tbl_indicator_strategic_plan_targets` (
  `strategicplanTargetId` int NOT NULL,
  `indicatorid` int NOT NULL,
  `fscyear` int NOT NULL,
  `indicator_target` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inner_menu`
--

CREATE TABLE `tbl_inner_menu` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent` int NOT NULL,
  `icons` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `role` int DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_inner_menu`
--

INSERT INTO `tbl_inner_menu` (`id`, `name`, `parent`, `icons`, `url`, `role`, `status`) VALUES
(1, 'Details ', 0, '   ', 'myprojectdash', 1, 1),
(2, 'Milestones', 0, '  ', 'myprojectmilestones', 1, 1),
(3, 'Tasks', 0, ' ', 'myprojecttask', 1, 1),
(4, 'Project Issues', 0, ' ', 'projectissueslist', 1, 1),
(5, 'Team Members', 0, ' ', 'myprojmembers', 1, 1),
(6, 'Team Discussions', 0, ' ', 'myprojectmsgs', 1, 1),
(7, 'Files ', 0, ' ', 'myprojectfiles', 1, 1),
(8, 'Progress Report ', 0, ' ', 'projreports', 1, 1),
(9, 'Issue Log', 4, 'projectissueslist', 'projectissueslist', 0, 1),
(10, 'Ralph Cervantes', 2, 'Voluptas quidem magn', 'Voluptates officia m', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inspection_assignment`
--

CREATE TABLE `tbl_inspection_assignment` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `level3` int NOT NULL,
  `level4` int NOT NULL,
  `officer` int NOT NULL,
  `inspection_date` date NOT NULL,
  `comments` text NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `update_reason` text,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inspection_checklist`
--

CREATE TABLE `tbl_inspection_checklist` (
  `id` int NOT NULL,
  `department` int NOT NULL,
  `output` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` set('0','1') DEFAULT '1',
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_inspection_checklist`
--

INSERT INTO `tbl_inspection_checklist` (`id`, `department`, `output`, `name`, `active`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(1, 15, 20, 'Tunnel Type', '1', '3', '2022-02-24', NULL, NULL),
(2, 9, 2, 'Boreholes', '1', '3', '2022-02-24', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inspection_checklist_questions`
--

CREATE TABLE `tbl_inspection_checklist_questions` (
  `id` int NOT NULL,
  `checklistname` int NOT NULL,
  `topic` int NOT NULL,
  `question` text NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_inspection_checklist_questions`
--

INSERT INTO `tbl_inspection_checklist_questions` (`id`, `checklistname`, `topic`, `question`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(1, 1, 1, 'Testing one', '3', '2022-02-24', NULL, NULL),
(2, 1, 1, 'Testing two', '3', '2022-02-24', NULL, NULL),
(3, 2, 2, 'Testing AB', '3', '2022-02-24', NULL, NULL),
(4, 2, 2, 'Testing CD', '3', '2022-02-24', NULL, NULL),
(5, 2, 1, 'Testing YZ', '3', '2022-02-24', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inspection_checklist_topics`
--

CREATE TABLE `tbl_inspection_checklist_topics` (
  `id` int NOT NULL,
  `topic` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_inspection_checklist_topics`
--

INSERT INTO `tbl_inspection_checklist_topics` (`id`, `topic`, `description`, `active`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(1, 'Test', 'Testing', 1, '3', '2022-02-24', NULL, NULL),
(2, 'Test 2', 'Testing', 1, '3', '2022-02-24', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_key_results_area`
--

CREATE TABLE `tbl_key_results_area` (
  `id` int NOT NULL,
  `spid` int NOT NULL,
  `kra` varchar(255) NOT NULL,
  `description` text,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_key_results_area`
--

INSERT INTO `tbl_key_results_area` (`id`, `spid`, `kra`, `description`, `created_by`, `date_created`) VALUES
(1, 1, 'Food Security', NULL, 'admin0', '2021-12-27'),
(2, 1, 'Poverty Reduction ', NULL, 'admin0', '2021-12-27'),
(3, 1, 'Affordable healthcare ', NULL, 'admin0', '2021-12-27'),
(4, 1, 'Access to clean water and sustainable environment ', NULL, 'admin0', '2021-12-27'),
(5, 1, 'Access to Quality and Affordable Education', NULL, 'admin0', '2021-12-27'),
(11, 1, 'Land administration', NULL, 'admin0', '2022-02-15'),
(12, 2, 'KRA1', NULL, '1', '2022-03-19'),
(13, 2, 'KRA2', NULL, '1', '2022-03-19'),
(14, 1, 'kra 5', NULL, '1', '2022-03-19'),
(15, 1, 'Tester KRA', NULL, '1', '2022-03-20'),
(16, 1, 'KRA5', NULL, '1', '2022-03-23'),
(17, 1, 'Food Security and Nutrition', NULL, '1', '2022-03-23'),
(19, 1, 'Infrastructure Development', NULL, '1', '2022-03-23'),
(20, 1, 'ttte', NULL, '1', '2022-03-23'),
(21, 1, 'ttte', NULL, '1', '2022-03-23'),
(22, 1, 'Test K', NULL, '1', '2022-03-23'),
(25, 1, 'Bottom-up', NULL, '1', '2022-04-02'),
(31, 4, 'Anim veniam cillum ', NULL, '1', '2022-04-19'),
(32, 4, 'Voluptatibus in aliq', NULL, '1', '2022-04-19'),
(33, 4, 'Sint dolorem reprehe', NULL, '1', '2022-04-19'),
(34, 4, 'Ipsum sequi dolorem', NULL, '1', '2022-04-19'),
(35, 3, 'Voluptatem quisquam ', NULL, '1', '2022-04-19'),
(36, 3, 'Molestiae dolor sed ', NULL, '1', '2022-04-19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kpi`
--

CREATE TABLE `tbl_kpi` (
  `id` int NOT NULL,
  `kpi` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `value` decimal(10,0) DEFAULT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kpi_aggregation_type`
--

CREATE TABLE `tbl_kpi_aggregation_type` (
  `id` int NOT NULL,
  `aggregation_type` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_kpi_aggregation_type`
--

INSERT INTO `tbl_kpi_aggregation_type` (`id`, `aggregation_type`, `description`, `active`) VALUES
(1, 'Sum Total', 'Sum Total', 1),
(2, 'Average', 'Average', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_level`
--

CREATE TABLE `tbl_level` (
  `level_id` int NOT NULL,
  `levelname` varchar(100) NOT NULL,
  `level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_level`
--

INSERT INTO `tbl_level` (`level_id`, `levelname`, `level`) VALUES
(3, 'Administrator', 'Admin'),
(4, 'Super Admin', 'SuperAdmin'),
(5, 'Managing Director', 'MD'),
(6, 'General Manager', 'GM'),
(7, 'M&E Manager', 'MEManager'),
(8, 'Monitoring Office', 'MOfficer'),
(9, 'Evaluation Office', 'EOfficer'),
(10, 'Operator', 'Operator'),
(11, 'Guest', 'Guest');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_lga`
--

CREATE TABLE `tbl_lga` (
  `lgaid` bigint NOT NULL,
  `stateid` int DEFAULT '0',
  `lga` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_location`
--

CREATE TABLE `tbl_location` (
  `id` int NOT NULL,
  `parent` int NOT NULL,
  `name` varchar(250) NOT NULL,
  `long` float DEFAULT NULL,
  `alt` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login`
--

CREATE TABLE `tbl_login` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_login`
--

INSERT INTO `tbl_login` (`id`, `name`, `username`, `password`) VALUES
(4, 'guest4', 'PrinceTom', '043da1cf96e519691ee2d9f9e581291e7f5917c2bb15a5eabbb08930437a381bef4d3aef3976e41fa1a8660b5657493129874ac280bf3800cd2790f3cdc232e5'),
(5, 'Guest1', 'myguest', '5ac2ac5ba94dbce933c6719ca250bf1752d938f43367af6618ab9e9e30b57df701dcda95287c5af56d8374abf292813efa07e1f287b9e4877ff17969b6735fe1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_main_funding`
--

CREATE TABLE `tbl_main_funding` (
  `fdid` int NOT NULL,
  `fund_code` varchar(100) NOT NULL,
  `financialyear` int NOT NULL,
  `amount` double NOT NULL,
  `date_authorized` date NOT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_map_markers`
--

CREATE TABLE `tbl_map_markers` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `lat` float(10,7) NOT NULL,
  `lng` float(10,7) NOT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_map_markers_road`
--

CREATE TABLE `tbl_map_markers_road` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `latstart` float(10,6) NOT NULL,
  `latend` float(10,6) NOT NULL,
  `lngstart` float(10,6) NOT NULL,
  `lngend` float(10,6) NOT NULL,
  `latpoint` float DEFAULT NULL,
  `lngpoint` float DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_map_markers_waypoint`
--

CREATE TABLE `tbl_map_markers_waypoint` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `lat` float(10,7) NOT NULL,
  `lng` float(10,7) NOT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_map_type`
--

CREATE TABLE `tbl_map_type` (
  `id` int NOT NULL,
  `typeid` int NOT NULL,
  `type` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_map_type`
--

INSERT INTO `tbl_map_type` (`id`, `typeid`, `type`, `description`, `status`) VALUES
(1, 1, 'One-Point', 'Project site is on a specific point e.g A borehole', 1),
(2, 2, 'Way-Points', 'A project goes through a number points (A to D through B and C) e.g road project', 1),
(3, 3, 'Area', 'project covers an area e.g forest', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_markers`
--

CREATE TABLE `tbl_markers` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `opid` int NOT NULL,
  `mapid` int NOT NULL,
  `state` int NOT NULL,
  `location` int DEFAULT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `comment` text NOT NULL,
  `mapped_date` date NOT NULL,
  `mapped_by` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_markers`
--

INSERT INTO `tbl_markers` (`id`, `projid`, `opid`, `mapid`, `state`, `location`, `lat`, `lng`, `comment`, `mapped_date`, `mapped_by`) VALUES
(1, 27, 105, 1, 342, 0, 0.728974, 35.3522, 'Tarmack Road', '2022-02-25', 10),
(2, 27, 105, 1, 342, 0, 0.728685, 35.356, 'Tarmack Road', '2022-02-25', 10),
(3, 27, 105, 1, 342, 0, 0.729049, 35.3582, 'Tarmack Road', '2022-02-25', 10),
(4, 27, 105, 1, 342, 0, 0.729071, 35.3598, 'Tarmack Road', '2022-02-25', 10),
(5, 27, 105, 1, 342, 0, 0.729114, 35.361, 'Tarmack Road', '2022-02-25', 10),
(6, 27, 105, 1, 342, 0, 0.729403, 35.3653, 'Tarmack Road', '2022-02-25', 10),
(7, 27, 105, 1, 342, 0, 0.72965, 35.3722, 'Tarmack Road', '2022-02-25', 10),
(8, 27, 105, 2, 344, 0, 0.776798, 35.3368, '', '2022-02-25', 10),
(9, 27, 105, 2, 344, 0, 0.778697, 35.3364, '', '2022-02-25', 10),
(10, 27, 105, 2, 344, 0, 0.778815, 35.3368, '', '2022-02-25', 10),
(11, 27, 105, 2, 344, 0, 0.778632, 35.3371, '', '2022-02-25', 10),
(12, 27, 105, 2, 344, 0, 0.77889, 35.3377, '', '2022-02-25', 10),
(13, 27, 105, 2, 344, 0, 0.778933, 35.3386, '', '2022-02-25', 10),
(14, 27, 105, 2, 344, 0, 0.77948, 35.3393, '', '2022-02-25', 10),
(15, 27, 105, 2, 344, 0, 0.779877, 35.3403, '', '2022-02-25', 10),
(16, 27, 105, 2, 344, 0, 0.779984, 35.3409, '', '2022-02-25', 10),
(17, 27, 105, 2, 344, 0, 0.779769, 35.3418, '', '2022-02-25', 10),
(18, 27, 105, 2, 344, 0, 0.779576, 35.3421, '', '2022-02-25', 10),
(19, 27, 105, 2, 344, 0, 0.7789, 35.3422, '', '2022-02-25', 10),
(20, 27, 105, 2, 344, 0, 0.778364, 35.3419, '', '2022-02-25', 10),
(21, 27, 105, 2, 344, 0, 0.777452, 35.3418, '', '2022-02-25', 10),
(22, 27, 105, 2, 344, 0, 0.776841, 35.3415, '', '2022-02-25', 10),
(23, 27, 105, 2, 344, 0, 0.775907, 35.3415, '', '2022-02-25', 10),
(24, 27, 105, 2, 344, 0, 0.775425, 35.3421, '', '2022-02-25', 10),
(25, 35, 131, 4, 389, 0, 0.786242, 35.436, 'Cheplaskei primary school title', '2022-03-20', 10),
(26, 35, 131, 4, 389, 0, 0.786285, 35.4369, 'Cheplaskei primary school title', '2022-03-20', 10),
(27, 35, 131, 4, 389, 0, 0.78622, 35.4373, 'Cheplaskei primary school title', '2022-03-20', 10),
(28, 35, 131, 4, 389, 0, 0.786102, 35.4377, 'Cheplaskei primary school title', '2022-03-20', 10),
(29, 35, 131, 4, 389, 0, 0.786016, 35.4379, 'Cheplaskei primary school title', '2022-03-20', 10),
(30, 35, 131, 4, 389, 0, 0.785619, 35.4378, 'Cheplaskei primary school title', '2022-03-20', 10),
(31, 35, 131, 4, 389, 0, 0.78504, 35.4375, 'Cheplaskei primary school title', '2022-03-20', 10),
(32, 35, 131, 4, 389, 0, 0.784407, 35.4372, 'Cheplaskei primary school title', '2022-03-20', 10),
(33, 35, 131, 4, 389, 0, 0.784053, 35.437, 'Cheplaskei primary school title', '2022-03-20', 10),
(34, 35, 131, 4, 389, 0, 0.783935, 35.4367, 'Cheplaskei primary school title', '2022-03-20', 10),
(35, 35, 131, 4, 389, 0, 0.784032, 35.4363, 'Cheplaskei primary school title', '2022-03-20', 10),
(36, 35, 131, 4, 389, 0, 0.784128, 35.4362, 'Cheplaskei primary school title', '2022-03-20', 10),
(37, 35, 131, 4, 389, 0, 0.784439, 35.436, 'Cheplaskei primary school title', '2022-03-20', 10),
(38, 35, 131, 4, 389, 0, 0.784804, 35.4358, 'Cheplaskei primary school title', '2022-03-20', 10),
(39, 35, 131, 4, 389, 0, 0.785501, 35.4358, 'Cheplaskei primary school title', '2022-03-20', 10),
(40, 35, 131, 4, 389, 0, 0.785888, 35.4359, 'Cheplaskei primary school title', '2022-03-20', 10),
(41, 35, 131, 4, 389, 0, 0.786199, 35.4361, 'Cheplaskei primary school title', '2022-03-20', 10),
(42, 38, 134, 5, 389, 0, 0.483641, 35.2579, 'Test', '2022-03-23', 10),
(43, 38, 134, 5, 389, 0, 0.485068, 35.2571, 'Test', '2022-03-23', 10),
(44, 38, 134, 5, 389, 0, 0.484811, 35.2576, 'Test', '2022-03-23', 10),
(45, 38, 134, 5, 389, 0, 0.484499, 35.2582, 'Test', '2022-03-23', 10),
(46, 38, 134, 5, 389, 0, 0.484199, 35.2595, 'Test', '2022-03-23', 10),
(47, 38, 134, 5, 389, 0, 0.48406, 35.2605, 'Test', '2022-03-23', 10),
(48, 38, 134, 6, 390, 0, 0.48642, 35.2583, 'This are coordinates for a proposed road from point A to Point B', '2022-03-23', 10),
(49, 38, 134, 6, 390, 0, 0.488533, 35.2592, 'This are coordinates for a proposed road from point A to Point B', '2022-03-23', 10),
(50, 39, 135, 13, 356, 0, 0.521087, 35.3026, '', '2022-03-25', 10),
(51, 40, 136, 97, 373, 0, 0.534584, 35.0814, 'Kaplemur Water pan', '2022-03-29', 10),
(52, 43, 142, 19, 385, 0, 0.522394, 35.2559, '', '2022-03-29', 10),
(53, 43, 142, 20, 384, 0, 0.642702, 35.0573, '', '2022-03-29', 10),
(54, 43, 142, 20, 384, 0, 0.392479, 35.2175, '', '2022-03-29', 10),
(55, 25, 101, 15, 397, 0, -1.2841, 36.8155, 'Officia sapiente in ', '2022-03-29', 1),
(56, 25, 101, 15, 397, 0, -1.2841, 36.8155, 'Officia sapiente in ', '2022-03-29', 1),
(57, 25, 101, 15, 397, 0, -1.2841, 36.8155, 'Officia sapiente in ', '2022-03-29', 1),
(58, 25, 101, 15, 397, 0, -1.2841, 36.8155, 'Officia sapiente in ', '2022-03-29', 1),
(59, 29, 122, 16, 409, 0, -1.2841, 36.8155, 'testing ', '2022-03-29', 1),
(60, 44, 143, 98, 352, 0, 0.488611, 35.4443, '', '2022-03-30', 10),
(61, 2, 55, 14, 370, 0, -1.2841, 36.8155, 'Testing', '2022-03-30', 1),
(62, 29, 122, 17, 409, 0, -1.2841, 36.8155, 'testing', '2022-03-30', 1),
(63, 29, 122, 18, 409, 0, -1.2841, 36.8155, 'testing', '2022-03-30', 1),
(64, 43, 142, 21, 385, 0, -1.2841, 36.8155, 'testing', '2022-03-30', 1),
(65, 43, 142, 22, 384, 0, -1.2841, 36.8155, 'testing', '2022-03-30', 1),
(66, 48, 163, 102, 404, 0, 1.08383, 35.3542, '', '2022-03-31', 10),
(67, 49, 164, 104, 358, 0, 0.851973, 35.1889, '', '2022-04-01', 10),
(68, 49, 164, 103, 357, 0, 0.823202, 35.1217, '', '2022-04-01', 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mbrtitle`
--

CREATE TABLE `tbl_mbrtitle` (
  `id` int NOT NULL,
  `title` varchar(10) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_mbrtitle`
--

INSERT INTO `tbl_mbrtitle` (`id`, `title`, `description`) VALUES
(1, 'Prof', 'Professor'),
(2, 'Dr', 'Doctor'),
(3, 'Eng', 'Engineer'),
(4, 'Mr', 'Mister'),
(5, 'Mrs', 'Misis'),
(6, 'Ms', 'Miss');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_measurement_units`
--

CREATE TABLE `tbl_measurement_units` (
  `id` int NOT NULL,
  `unit` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_measurement_units`
--

INSERT INTO `tbl_measurement_units` (`id`, `unit`, `description`, `active`) VALUES
(2, 'Tonnes', '		Weight Measure', 1),
(7, 'Kilograms', '		Weight measure											', 1),
(13, 'Cubic Meters (M3)', 'Volume measure', 1),
(14, 'Meters', '		Length measure', 1),
(15, 'Litres', '	Volume measure												', 1),
(16, 'Kilometers', '				Length measure', 1),
(17, 'Percentage (%) ', 'Proportion										', 1),
(19, 'Number', '			Counts', 1),
(54, 'Kenya Shillings ', '		Amount											', 1),
(58, 'Ratio', 'Proportion', 1),
(59, 'Hours', '													', 1),
(60, 'Minutes', '													', 1),
(61, 'Rate', 'Proportion												', 1),
(62, 'ffffffff', '													ttt', 1),
(63, 'Ipsum lorem ut quis', 'Quia repudiandae eu ', 1),
(64, ' testing  the test ', 'test tshh', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_meetings`
--

CREATE TABLE `tbl_meetings` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_messages`
--

CREATE TABLE `tbl_messages` (
  `mgid` int NOT NULL,
  `parent` int NOT NULL DEFAULT '0',
  `projid` int NOT NULL,
  `milestone` int NOT NULL,
  `msubject` varchar(300) NOT NULL,
  `message` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `username` varchar(100) NOT NULL,
  `fullname` varchar(300) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `floc` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_milestone`
--

CREATE TABLE `tbl_milestone` (
  `msid` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `milestone` varchar(300) NOT NULL,
  `parent` int DEFAULT NULL,
  `milestonebudget` double DEFAULT '0',
  `progress` float DEFAULT '0',
  `inspectionstatus` int NOT NULL DEFAULT '0',
  `status` int NOT NULL,
  `paymentrequired` int NOT NULL DEFAULT '0',
  `paymentstatus` int NOT NULL DEFAULT '0',
  `changedstatus` varchar(100) DEFAULT NULL,
  `sdate` date NOT NULL,
  `edate` date NOT NULL,
  `datecompleted` date DEFAULT NULL,
  `user_name` varchar(200) NOT NULL,
  `date_entered` date NOT NULL,
  `changedby` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `datechanged` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_milestone`
--

INSERT INTO `tbl_milestone` (`msid`, `projid`, `outputid`, `milestone`, `parent`, `milestonebudget`, `progress`, `inspectionstatus`, `status`, `paymentrequired`, `paymentstatus`, `changedstatus`, `sdate`, `edate`, `datecompleted`, `user_name`, `date_entered`, `changedby`, `datechanged`) VALUES
(1, 8, 63, 'Foundation', NULL, 0, 50, 0, 5, 0, 0, '4', '2021-07-06', '2021-07-21', NULL, '1', '2022-01-18', '1', '2022-03-24 10:19:32'),
(2, 8, 63, 'Walling', 1, 0, 50, 0, 5, 0, 0, '4', '2021-08-05', '2021-10-15', NULL, '1', '2022-01-18', '1', '2022-03-24 10:19:32'),
(3, 8, 63, 'Roofing', 2, 0, 40, 0, 5, 0, 0, '4', '2021-11-01', '2021-11-30', NULL, '1', '2022-01-18', '1', '2022-03-24 10:19:32'),
(7, 9, 70, 'Mobilization to site ', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-01-07', '2021-01-15', NULL, '1', '2022-01-18', NULL, NULL),
(8, 9, 70, 'Mobilization to site ', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-01-07', '2021-01-15', NULL, '1', '2022-01-18', NULL, NULL),
(11, 9, 69, 'Mobilization to site', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-03-10', '2021-03-20', NULL, '1', '2022-01-18', NULL, NULL),
(12, 9, 70, 'Road Construction Works ', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-04-15', '2021-08-12', NULL, '1', '2022-01-18', NULL, NULL),
(13, 9, 72, 'Mobilization to site', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-06-16', '2021-08-20', NULL, '1', '2022-01-18', NULL, NULL),
(14, 9, 72, 'Excavation and Construction Works ', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-08-19', '2022-02-01', NULL, '1', '2022-01-18', NULL, NULL),
(15, 7, 61, 'Foundation', NULL, 0, 0, 0, 1, 0, 0, NULL, '2022-02-01', '2022-02-14', NULL, '1', '2022-01-19', NULL, NULL),
(16, 7, 61, 'Walling', 15, 0, 0, 0, 1, 0, 0, NULL, '2022-03-01', '2022-04-27', NULL, '1', '2022-01-19', NULL, NULL),
(17, 7, 61, 'Roofing', 16, 0, 0, 0, 1, 0, 0, NULL, '2022-02-20', '2022-03-13', NULL, '1', '2022-01-19', NULL, NULL),
(18, 5, 59, 'Milestone 1', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-07-02', '2021-07-15', NULL, '1', '2022-01-19', NULL, NULL),
(19, 5, 59, 'M2', 18, 0, 0, 0, 1, 0, 0, NULL, '2021-07-16', '2021-07-21', NULL, '1', '2022-01-19', NULL, NULL),
(20, 10, 73, 'M1', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-07-02', '2021-10-09', NULL, '1', '2022-01-29', NULL, NULL),
(21, 10, 73, 'M2', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-07-02', '2021-10-09', NULL, '1', '2022-01-29', '1', '2022-01-31 00:00:00'),
(23, 21, 95, 'Mobilization to site', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-07-01', '2021-07-15', NULL, '1', '2022-02-02', NULL, NULL),
(24, 21, 95, 'Road Construction Works', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-07-15', '2021-07-22', NULL, '1', '2022-02-02', '1', '2022-02-02 00:00:00'),
(25, 21, 95, 'Eathworks ', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-08-01', '2021-08-13', NULL, '1', '2022-02-02', NULL, NULL),
(26, 21, 95, 'Drainage ', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-09-01', '2021-10-15', NULL, '1', '2022-02-02', NULL, NULL),
(27, 21, 95, 'Sign Off', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-11-10', '2021-11-17', NULL, '1', '2022-02-02', NULL, NULL),
(28, 21, 95, 'Roadworks ', 26, 0, 0, 0, 1, 0, 0, NULL, '2021-10-16', '2021-11-17', NULL, '1', '2022-02-02', NULL, NULL),
(29, 22, 96, 'Mobilization to site', NULL, 0, 0, 0, 11, 0, 0, NULL, '2021-07-01', '2021-07-15', NULL, '1', '2022-02-02', NULL, NULL),
(30, 22, 96, 'Bridge Works ', NULL, 0, 0, 0, 11, 0, 0, NULL, '2021-07-15', '2021-10-25', NULL, '1', '2022-02-02', NULL, NULL),
(31, 22, 96, 'Sign Off', 30, 0, 0, 0, 11, 0, 0, NULL, '2021-10-26', '2021-11-25', NULL, '1', '2022-02-02', NULL, NULL),
(32, 24, 100, 'ML1', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-08-08', '2022-01-01', NULL, '1', '2022-02-13', NULL, NULL),
(33, 10, 73, 'test', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-08-20', '2021-09-16', NULL, '1', '2022-02-23', NULL, NULL),
(34, 10, 73, 'test', NULL, 0, 0, 0, 1, 0, 0, NULL, '2021-08-20', '2021-09-16', NULL, '1', '2022-02-23', NULL, NULL),
(35, 10, 73, 'Milestone', NULL, 0, 0, 0, 1, 1, 0, NULL, '2021-07-01', '2021-07-23', NULL, '1', '2022-02-23', NULL, NULL),
(37, 10, 73, 'testing for 23', NULL, 0, 0, 0, 1, 1, 0, NULL, '2021-07-15', '2021-08-05', NULL, '1', '2022-02-23', NULL, NULL),
(38, 27, 105, 'Mobilization to site', NULL, 0, 0, 0, 1, 1, 0, NULL, '2021-07-08', '2021-08-07', NULL, '1', '2022-02-25', NULL, NULL),
(39, 27, 105, 'Road Construction Works', 38, 0, 0, 0, 1, 1, 0, NULL, '2021-08-26', '2021-09-25', NULL, '1', '2022-02-25', NULL, NULL),
(40, 27, 105, 'Demobilization and Sign off', 39, 0, 0, 0, 1, 1, 0, NULL, '2021-11-25', '2021-12-03', NULL, '1', '2022-02-25', NULL, NULL),
(41, 35, 131, 'Milestone 1', NULL, 0, 0, 0, 11, 1, 0, NULL, '2021-08-01', '2021-09-10', NULL, '1', '2022-03-20', NULL, NULL),
(42, 38, 134, 'Mobilization to site', NULL, 0, 100, 0, 5, 1, 0, NULL, '2021-07-02', '2021-07-10', NULL, '1', '2022-03-23', NULL, NULL),
(43, 38, 134, 'Road construction works', 42, 0, 100, 0, 5, 1, 1, NULL, '2021-07-15', '2021-07-28', NULL, '1', '2022-03-23', NULL, NULL),
(44, 38, 134, 'Drainage', 43, 0, 6.25, 0, 11, 1, 0, '11', '2021-08-05', '2021-08-15', NULL, '1', '2022-03-23', '1', '2022-03-24 16:25:05'),
(45, 39, 135, 'Foundation', NULL, 0, 0, 0, 11, 1, 0, NULL, '2021-07-02', '2021-07-15', NULL, '1', '2022-03-25', NULL, NULL),
(47, 39, 135, 'Walling', 45, 0, 0, 0, 11, 1, 0, NULL, '2021-08-05', '2021-09-09', NULL, '1', '2022-03-25', NULL, NULL),
(48, 39, 135, 'Roofing', 47, 0, 0, 0, 11, 1, 0, NULL, '2021-11-03', '2021-11-20', NULL, '1', '2022-03-25', NULL, NULL),
(49, 40, 136, 'Milestone2', NULL, 0, 0, 0, 1, 1, 0, NULL, '2021-07-31', '2021-09-09', NULL, '1', '2022-03-29', NULL, NULL),
(50, 40, 136, 'Milestone2', NULL, 0, 0, 0, 1, 1, 0, NULL, '2021-07-31', '2021-09-09', NULL, '1', '2022-03-29', NULL, NULL),
(51, 40, 136, 'Milestone 3', NULL, 0, 0, 0, 1, 1, 0, NULL, '2022-01-01', '2022-03-31', NULL, '1', '2022-03-29', NULL, NULL),
(52, 44, 143, 'MILestone 1', NULL, 0, 100, 0, 5, 1, 0, NULL, '2021-08-10', '2022-04-07', NULL, '1', '2022-03-30', NULL, NULL),
(53, 44, 143, 'mile2', 52, 0, 0, 0, 3, 1, 0, NULL, '2022-04-07', '2022-06-16', NULL, '1', '2022-03-30', NULL, NULL),
(54, 45, 151, 'Procurement', NULL, 0, 100, 0, 5, 0, 0, NULL, '2021-07-01', '2021-07-10', NULL, '1', '2022-03-30', NULL, NULL),
(55, 45, 151, 'Distribution', 54, 0, 25, 0, 11, 0, 0, NULL, '2021-07-10', '2021-08-30', NULL, '1', '2022-03-30', NULL, NULL),
(56, 49, 164, 'Foundation', NULL, 0, 23.33, 0, 11, 1, 0, NULL, '2021-07-01', '2021-07-15', NULL, '1', '2022-04-01', NULL, NULL),
(57, 54, 169, 'Delivery and Inspection ', NULL, 0, 100, 0, 5, 1, 0, NULL, '2022-03-24', '2022-04-04', NULL, '1', '2022-04-03', NULL, NULL),
(58, 55, 170, 'Training done', NULL, 0, 25, 0, 11, 0, 0, '11', '2021-07-02', '2021-08-01', NULL, '1', '2022-04-04', '1', '2022-04-04 17:27:05'),
(59, 55, 170, 'Distribution of chicken done', 58, 0, 45, 0, 11, 1, 0, '11', '2021-08-02', '2021-09-28', NULL, '1', '2022-04-04', '1', '2022-04-04 17:27:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_milestone_certificate`
--

CREATE TABLE `tbl_milestone_certificate` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `msid` int NOT NULL,
  `certificate_no` varchar(255) NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_milestone_certificate`
--

INSERT INTO `tbl_milestone_certificate` (`id`, `projid`, `msid`, `certificate_no`, `status`, `created_at`) VALUES
(1, 44, 52, 'UG-2022-13516', 0, '2022-03-31'),
(2, 44, 52, 'UG-2022-38261', 0, '2022-04-01'),
(3, 44, 52, 'UG-2022-36675', 0, '2022-04-01'),
(4, 44, 52, 'UG-2022-14117', 0, '2022-04-01'),
(5, 44, 52, 'UG-2022-26280', 0, '2022-04-01'),
(6, 44, 52, 'UG-2022-14762', 0, '2022-04-01'),
(7, 44, 52, 'UG-2022-18320', 0, '2022-04-03'),
(8, 44, 52, 'UG-2022-33627', 0, '2022-04-03'),
(9, 44, 52, 'UG-2022-39566', 0, '2022-04-03'),
(10, 44, 52, 'UG-2022-4892', 0, '2022-04-03'),
(11, 38, 42, 'UG-2022-22096', 0, '2022-04-04'),
(12, 38, 43, 'UG-2022-2949', 0, '2022-04-04'),
(13, 44, 52, 'UG-2022-22691', 0, '2022-04-04'),
(14, 38, 42, 'UG-2022-33738', 0, '2022-04-04'),
(15, 38, 43, 'UG-2022-11092', 0, '2022-04-04'),
(16, 44, 52, 'UG-2022-34172', 0, '2022-04-04'),
(17, 38, 42, 'UG-2022-36973', 0, '2022-04-04'),
(18, 38, 43, 'UG-2022-7238', 0, '2022-04-04'),
(19, 44, 52, 'UG-2022-3465', 0, '2022-04-04'),
(20, 38, 42, 'UG-2022-11946', 0, '2022-04-04'),
(21, 38, 43, 'UG-2022-30839', 0, '2022-04-04'),
(22, 44, 52, 'UG-2022-1146', 0, '2022-04-04'),
(23, 54, 57, 'UG-2022-39206', 0, '2022-04-04'),
(24, 38, 42, 'UG-2022-19960', 0, '2022-04-04'),
(25, 38, 43, 'UG-2022-32899', 0, '2022-04-04'),
(26, 44, 52, 'UG-2022-14525', 0, '2022-04-04'),
(27, 54, 57, 'UG-2022-24301', 0, '2022-04-04'),
(28, 38, 42, 'UG-2022-2326', 0, '2022-04-04'),
(29, 38, 43, 'UG-2022-11792', 0, '2022-04-04'),
(30, 44, 52, 'UG-2022-31202', 0, '2022-04-04'),
(31, 54, 57, 'UG-2022-25549', 0, '2022-04-04'),
(32, 38, 42, 'UG-2022-15434', 0, '2022-04-04'),
(33, 38, 43, 'UG-2022-16746', 0, '2022-04-04'),
(34, 44, 52, 'UG-2022-31223', 0, '2022-04-04'),
(35, 38, 42, 'UG-2022-1815', 0, '2022-04-04'),
(36, 38, 43, 'UG-2022-10365', 0, '2022-04-04'),
(37, 44, 52, 'UG-2022-9698', 0, '2022-04-04'),
(38, 38, 42, 'UG-2022-12401', 0, '2022-04-05'),
(39, 38, 43, 'UG-2022-7493', 0, '2022-04-05'),
(40, 44, 52, 'UG-2022-1844', 0, '2022-04-05'),
(41, 38, 42, 'UG-2022-10373', 0, '2022-04-05'),
(42, 38, 43, 'UG-2022-26842', 0, '2022-04-05'),
(43, 44, 52, 'UG-2022-21812', 0, '2022-04-05'),
(44, 38, 42, 'UG-2022-19075', 0, '2022-04-05'),
(45, 38, 43, 'UG-2022-17602', 0, '2022-04-05'),
(46, 44, 52, 'UG-2022-26321', 0, '2022-04-05'),
(47, 54, 57, 'UG-2022-24278', 0, '2022-04-05'),
(48, 38, 42, 'UG-2022-35977', 0, '2022-04-05'),
(49, 38, 43, 'UG-2022-38671', 0, '2022-04-05'),
(50, 44, 52, 'UG-2022-16600', 0, '2022-04-05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_monevarevrep`
--

CREATE TABLE `tbl_monevarevrep` (
  `monevarevrep_id` int NOT NULL,
  `monitoring` text NOT NULL,
  `evaluation` text NOT NULL,
  `evaluationreview` text NOT NULL,
  `reportoptions` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_monevarevrepoptions`
--

CREATE TABLE `tbl_monevarevrepoptions` (
  `monevarevoptns_id` int NOT NULL,
  `options` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_monevarevrepoptions`
--

INSERT INTO `tbl_monevarevrepoptions` (`monevarevoptns_id`, `options`) VALUES
(1, 'Purpose'),
(2, 'Responsibility'),
(3, 'Use of findings'),
(4, 'Focus'),
(5, 'Deliverables'),
(6, 'Dissemination'),
(7, 'Quality assurance and support');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_monitoring`
--

CREATE TABLE `tbl_monitoring` (
  `mid` int NOT NULL,
  `projid` int NOT NULL,
  `formid` varchar(100) DEFAULT NULL,
  `projlatitude` varchar(50) DEFAULT NULL,
  `projlongitude` varchar(50) DEFAULT NULL,
  `projgeopositionerror` varchar(255) NOT NULL,
  `projlocation` int DEFAULT NULL,
  `level3` int NOT NULL,
  `location` int DEFAULT NULL,
  `rankingscore` int DEFAULT NULL,
  `adate` date DEFAULT NULL,
  `dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `datechanged` date DEFAULT NULL,
  `changedby` int DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_monitoring`
--

INSERT INTO `tbl_monitoring` (`mid`, `projid`, `formid`, `projlatitude`, `projlongitude`, `projgeopositionerror`, `projlocation`, `level3`, `location`, `rankingscore`, `adate`, `datechanged`, `changedby`, `user_name`) VALUES
(1, 8, 'GWBZI', NULL, NULL, 'User denied the request for Geolocation.', NULL, 361, NULL, NULL, '2022-01-21', NULL, NULL, '1'),
(2, 8, 'GWDTL', NULL, NULL, 'User denied the request for Geolocation.', NULL, 361, NULL, NULL, '2022-01-21', NULL, NULL, '1'),
(3, 8, 'GYRFK', NULL, NULL, 'User denied the request for Geolocation.', NULL, 361, NULL, NULL, '2022-01-22', NULL, NULL, '1'),
(4, 8, 'MWMSY', NULL, NULL, '', NULL, 361, NULL, NULL, '2022-02-22', NULL, NULL, '1'),
(5, 8, 'NOOQY', NULL, NULL, '', NULL, 361, NULL, NULL, '2022-02-26', NULL, NULL, '1'),
(6, 8, 'NOSOD', '12345677', '23536788', '', NULL, 361, NULL, 1, '2022-02-26', NULL, NULL, '1'),
(7, 8, 'SJLLP', '12345677', '23536788', '', NULL, 361, NULL, 2, '2022-03-23', NULL, NULL, '1'),
(8, 8, 'SJLYY', '12345677', '23536788', '', NULL, 361, NULL, NULL, '2022-03-23', NULL, NULL, '1'),
(9, 8, 'SJNPI', '12345677', '23536788', '', NULL, 361, NULL, NULL, '2022-03-23', NULL, NULL, '1'),
(10, 8, 'SJNPI', '12345677', '23536788', '', NULL, 361, NULL, NULL, '2022-03-23', NULL, NULL, '1'),
(11, 8, 'SJOWI', '12345677', '23536788', '', NULL, 361, NULL, NULL, '2022-03-23', NULL, NULL, '1'),
(12, 8, 'SJOWI', '12345677', '23536788', '', NULL, 361, NULL, NULL, '2022-03-23', NULL, NULL, '1'),
(13, 38, 'SPFET', '12345677', '23536788', '', NULL, 389, NULL, 2, '2022-03-24', NULL, NULL, '1'),
(14, 38, 'SPITP', '12345677', '23536788', '', NULL, 389, NULL, 2, '2022-03-24', NULL, NULL, '1'),
(15, 38, 'SPITP', '12345677', '23536788', '', NULL, 389, NULL, 2, '2022-03-24', NULL, NULL, '1'),
(16, 44, 'TZCWK', '12345677', '23536788', '', NULL, 352, NULL, NULL, '2022-03-31', NULL, NULL, '1'),
(17, 44, 'TZFDM', '12345677', '23536788', '', NULL, 352, NULL, NULL, '2022-03-31', NULL, NULL, '1'),
(18, 44, 'TZGBW', '12345677', '23536788', '', NULL, 352, NULL, NULL, '2022-03-31', NULL, NULL, '1'),
(19, 54, 'UQSVJ', '12345677', '23536788', '', NULL, 3, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(20, 55, 'URNLG', '12345677', '23536788', '', NULL, 409, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(21, 45, 'UROXO', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(22, 49, 'URWJF', '12345677', '23536788', '', NULL, 357, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(23, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(24, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(25, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(26, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(27, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(28, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(29, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(30, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(31, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(32, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(33, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(34, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(35, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(36, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(37, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(38, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(39, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(40, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(41, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(42, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(43, 45, 'USWDA', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(44, 45, 'USYVB', '12345677', '23536788', '', NULL, 346, NULL, NULL, '2022-04-04', NULL, NULL, '1'),
(45, 49, 'XFLIL', '12345677', '23536788', '', NULL, 357, NULL, NULL, '2022-04-18', NULL, NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_monitoringoutput`
--

CREATE TABLE `tbl_monitoringoutput` (
  `moid` int NOT NULL,
  `monitoringid` int NOT NULL,
  `formid` varchar(100) DEFAULT NULL,
  `opid` int NOT NULL,
  `projid` int DEFAULT NULL,
  `level3` int NOT NULL,
  `level4` int DEFAULT NULL,
  `key_unique` int NOT NULL,
  `actualoutput` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_monitoringoutput`
--

INSERT INTO `tbl_monitoringoutput` (`moid`, `monitoringid`, `formid`, `opid`, `projid`, `level3`, `level4`, `key_unique`, `actualoutput`, `created_by`, `date_created`) VALUES
(1, 1, 'GWBZI', 63, 8, 361, NULL, 1, '0', '1', '2022-01-21'),
(2, 2, 'GWDTL', 63, 8, 361, NULL, 1, '0', '1', '2022-01-21'),
(3, 3, 'GYRFK', 63, 8, 361, NULL, 1, '10', '1', '2022-01-22'),
(4, 4, 'MWMSY', 63, 8, 361, NULL, 1, '1', '1', '2022-02-22'),
(5, 5, 'NOOQY', 63, 8, 361, NULL, 1, '1', '1', '2022-02-26'),
(6, 6, 'NOSOD', 63, 8, 361, NULL, 1, '1', '1', '2022-02-26'),
(7, 7, 'SJLLP', 63, 8, 361, NULL, 1, '0', '1', '2022-03-23'),
(8, 8, 'SJLYY', 63, 8, 361, NULL, 1, '0', '1', '2022-03-23'),
(9, 9, 'SJNPI', 63, 8, 361, NULL, 1, '0', '1', '2022-03-23'),
(10, 10, 'SJNPI', 63, 8, 361, NULL, 1, '0', '1', '2022-03-23'),
(11, 11, 'SJOWI', 63, 8, 361, NULL, 1, '0', '1', '2022-03-23'),
(12, 12, 'SJOWI', 63, 8, 361, NULL, 1, '0', '1', '2022-03-23'),
(13, 13, 'SPFET', 134, 38, 389, NULL, 1, '1', '1', '2022-03-24'),
(14, 14, 'SPITP', 134, 38, 389, NULL, 1, '5', '1', '2022-03-24'),
(15, 15, 'SPITP', 134, 38, 389, NULL, 1, '5', '1', '2022-03-24'),
(16, 16, 'TZCWK', 143, 44, 352, NULL, 1, '0', '1', '2022-03-31'),
(17, 17, 'TZFDM', 143, 44, 352, NULL, 1, '0', '1', '2022-03-31'),
(18, 18, 'TZGBW', 143, 44, 352, NULL, 1, '0', '1', '2022-03-31'),
(19, 19, 'UQSVJ', 169, 54, 3, NULL, 1, '6', '1', '2022-04-04'),
(20, 20, 'URNLG', 170, 55, 409, NULL, 1, '0', '1', '2022-04-04'),
(21, 21, 'UROXO', 151, 45, 346, NULL, 1, '700', '1', '2022-04-04'),
(22, 22, 'URWJF', 164, 49, 357, NULL, 1, '0', '1', '2022-04-04'),
(23, 23, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(24, 24, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(25, 25, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(26, 26, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(27, 27, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(28, 28, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(29, 29, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(30, 30, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(31, 31, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(32, 32, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(33, 33, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(34, 34, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(35, 35, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(36, 36, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(37, 37, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(38, 38, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(39, 39, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(40, 40, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(41, 41, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(42, 42, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(43, 43, 'USWDA', 151, 45, 346, NULL, 1, '99', '1', '2022-04-04'),
(44, 44, 'USYVB', 151, 45, 346, NULL, 1, '39', '1', '2022-04-04'),
(45, 45, 'XFLIL', 164, 49, 357, NULL, 1, '19', '1', '2022-04-18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_monitoring_links`
--

CREATE TABLE `tbl_monitoring_links` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `monitoringid` int NOT NULL,
  `formid` varchar(255) NOT NULL,
  `opid` int NOT NULL,
  `url` varchar(255) NOT NULL,
  `urlpurpose` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_monitoring_links`
--

INSERT INTO `tbl_monitoring_links` (`id`, `projid`, `monitoringid`, `formid`, `opid`, `url`, `urlpurpose`) VALUES
(1, 45, 28, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(2, 45, 29, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(3, 45, 30, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(4, 45, 31, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(5, 45, 32, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(6, 45, 33, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(7, 45, 34, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(8, 45, 35, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(9, 45, 36, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(10, 45, 37, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(11, 45, 38, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(12, 45, 39, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(13, 45, 40, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(14, 45, 41, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(15, 45, 42, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(16, 45, 43, 'USWDA', 151, 'https://www.tupituzypu.us', 'Neque neque odio ea '),
(17, 45, 44, 'USYVB', 151, 'https://www.degerefamu.biz', 'Dolore illo ut possi'),
(18, 49, 45, 'XFLIL', 164, 'https://www.symizi.ws', 'Quae nulla est totam');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_monitoring_observations`
--

CREATE TABLE `tbl_monitoring_observations` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `monitoringid` int DEFAULT NULL,
  `formid` varchar(20) NOT NULL,
  `opid` int DEFAULT '0',
  `observation` text NOT NULL,
  `created_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_monitoring_observations`
--

INSERT INTO `tbl_monitoring_observations` (`id`, `projid`, `monitoringid`, `formid`, `opid`, `observation`, `created_by`, `date_created`) VALUES
(1, 8, 1, 'GWBZI', 63, 'TEST', '1', '2022-01-21 00:00:00'),
(2, 8, 2, 'GWDTL', 63, 'TEST', '1', '2022-01-21 00:00:00'),
(3, 8, 3, 'GYRFK', 63, 'test', '1', '2022-01-22 00:00:00'),
(4, 8, 4, 'MWMSY', 63, 'The progress is very slow', '1', '2022-02-22 00:00:00'),
(5, 8, 5, 'NOOQY', 63, 'Testing', '1', '2022-02-26 00:00:00'),
(6, 8, 6, 'NOSOD', 63, '', '1', '2022-02-26 00:00:00'),
(7, 8, NULL, '1234', 0, 'Est in totam excepte', '0', '2022-03-16 06:33:21'),
(8, 8, NULL, '1234', 0, 'Est in totam excepte', '0', '2022-03-16 06:35:45'),
(9, 8, NULL, '1234', 0, 'Est in totam excepte', '0', '2022-03-16 06:46:52'),
(10, 8, NULL, '1234', 0, 'Est in totam excepte', '0', '2022-03-16 06:48:09'),
(11, 8, NULL, '1234', 0, 'Est in totam excepte', '0', '2022-03-16 06:49:00'),
(12, 8, NULL, '1234', 0, 'Dolor aut do optio', '0', '2022-03-16 06:49:29'),
(13, 8, NULL, '1234', 0, 'Dolor aut do optio', '0', '2022-03-16 06:50:29'),
(14, 8, NULL, '1234', 0, 'Dolor aut do optio', '0', '2022-03-16 06:51:31'),
(15, 8, NULL, '1234', 0, 'Dolor aut do optio', '0', '2022-03-16 06:52:22'),
(16, 8, NULL, '1234', 0, 'Dolor aut do optio', '0', '2022-03-16 06:56:50'),
(17, 8, 7, 'SJLLP', 63, '', '1', '2022-03-23 00:00:00'),
(18, 8, 8, 'SJLYY', 63, '', '1', '2022-03-23 00:00:00'),
(19, 8, 9, 'SJNPI', 63, '', '1', '2022-03-23 00:00:00'),
(20, 8, 10, 'SJNPI', 63, '', '1', '2022-03-23 00:00:00'),
(21, 8, 11, 'SJOWI', 63, '', '1', '2022-03-23 00:00:00'),
(22, 8, 12, 'SJOWI', 63, '', '1', '2022-03-23 00:00:00'),
(23, 38, 13, 'SPFET', 134, 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', '1', '2022-03-24 00:00:00'),
(24, 38, 14, 'SPITP', 134, 'TEST', '1', '2022-03-24 00:00:00'),
(25, 38, 15, 'SPITP', 134, 'TEST', '1', '2022-03-24 00:00:00'),
(26, 44, 16, 'TZCWK', 143, 'test', '1', '2022-03-31 00:00:00'),
(27, 44, 17, 'TZFDM', 143, 'test', '1', '2022-03-31 00:00:00'),
(28, 44, 18, 'TZGBW', 143, 'test', '1', '2022-03-31 00:00:00'),
(29, 54, 19, 'UQSVJ', 169, '', '1', '2022-04-04 00:00:00'),
(30, 55, 20, 'URNLG', 170, '', '1', '2022-04-04 00:00:00'),
(31, 45, 21, 'UROXO', 151, '', '1', '2022-04-04 00:00:00'),
(32, 49, 22, 'URWJF', 164, 'Testing', '1', '2022-04-04 00:00:00'),
(33, 45, 23, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(34, 45, 24, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(35, 45, 25, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(36, 45, 26, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(37, 45, 27, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(38, 45, 28, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(39, 45, 29, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(40, 45, 30, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(41, 45, 31, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(42, 45, 32, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(43, 45, 33, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(44, 45, 34, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(45, 45, 35, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(46, 45, 36, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(47, 45, 37, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(48, 45, 38, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(49, 45, 39, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(50, 45, 40, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(51, 45, 41, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(52, 45, 42, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(53, 45, 43, 'USWDA', 151, 'Quia non qui facilis', '1', '2022-04-04 00:00:00'),
(54, 45, 44, 'USYVB', 151, 'Ut alias officia vol', '1', '2022-04-04 00:00:00'),
(55, 49, 45, 'XFLIL', 164, 'Dolore quod dolor ip', '1', '2022-04-17 21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_msgcomments`
--

CREATE TABLE `tbl_msgcomments` (
  `mcid` int NOT NULL,
  `mgid` int NOT NULL,
  `projid` int NOT NULL,
  `msubject` varchar(300) NOT NULL,
  `message` text NOT NULL,
  `username` varchar(100) NOT NULL,
  `fullname` varchar(300) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `floc` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_myprogfunding`
--

CREATE TABLE `tbl_myprogfunding` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `sourcecategory` varchar(100) NOT NULL,
  `sourceid` int DEFAULT '0',
  `grantid` int DEFAULT NULL,
  `amountfunding` double NOT NULL,
  `currency` int DEFAULT NULL,
  `rate` int DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_myprogfunding`
--

INSERT INTO `tbl_myprogfunding` (`id`, `progid`, `sourcecategory`, `sourceid`, `grantid`, `amountfunding`, `currency`, `rate`, `created_by`, `date_created`) VALUES
(3, 3, '2', 0, NULL, 900000000, NULL, NULL, '3', '2022-01-15 22:46:00'),
(4, 4, '2', 0, NULL, 900000000, NULL, NULL, '3', '2022-01-15 22:47:29'),
(7, 2, '3', 0, NULL, 11000000000, NULL, NULL, 'admin0', '2022-01-16 11:55:03'),
(11, 6, '2', 0, NULL, 50000000, NULL, NULL, 'Admin0', '2022-01-16 15:44:23'),
(12, 1, '1', 0, NULL, 50000000, NULL, NULL, 'admin0', '2022-01-16 21:45:21'),
(16, 7, '2', 0, NULL, 290000000, NULL, NULL, 'admin0', '2022-01-24 21:39:49'),
(17, 8, '2', 0, NULL, 15000000, NULL, NULL, '3', '2022-01-28 22:03:37'),
(19, 9, '2', 0, NULL, 50000000, NULL, NULL, 'admin0', '2022-02-10 22:41:27'),
(20, 10, '3', 0, NULL, 80000000, NULL, NULL, '3', '2022-02-10 23:03:48'),
(21, 11, '2', 0, NULL, 42000000, NULL, NULL, '3', '2022-02-12 16:19:51'),
(22, 12, '2', 0, NULL, 1120000000, NULL, NULL, '3', '2022-02-12 16:50:04'),
(23, 13, '3', 0, NULL, 5000000, NULL, NULL, '3', '2022-02-13 06:00:53'),
(24, 5, '1', 0, NULL, 1000000000, NULL, NULL, 'admin0', '2022-02-14 12:47:44'),
(25, 14, '1', 0, NULL, 130000000, NULL, NULL, '3', '2022-02-15 12:10:57'),
(26, 15, '2', 0, NULL, 6000000, NULL, NULL, '3', '2022-02-15 12:48:22'),
(27, 16, '1', 0, NULL, 100000000, NULL, NULL, '3', '2022-02-15 13:14:03'),
(28, 17, '2', 0, NULL, 10000000, NULL, NULL, '3', '2022-02-15 19:37:27'),
(29, 18, '4', 0, NULL, 100000000, NULL, NULL, '3', '2022-02-15 19:54:04'),
(30, 20, '1', 0, NULL, 1000, NULL, NULL, '3', '2022-02-15 23:54:27'),
(31, 21, '1', 0, NULL, 1000, NULL, NULL, '3', '2022-02-15 23:54:45'),
(32, 22, '1', 0, NULL, 1000, NULL, NULL, '3', '2022-02-15 23:55:51'),
(33, 23, '1', 0, NULL, 1000, NULL, NULL, '3', '2022-02-15 23:56:23'),
(34, 24, '1', 0, NULL, 1000, NULL, NULL, '3', '2022-02-15 23:57:26'),
(35, 25, '1', 0, NULL, 1000, NULL, NULL, '3', '2022-02-15 23:57:44'),
(36, 26, '1', 0, NULL, 100, NULL, NULL, '3', '2022-02-17 13:01:08'),
(37, 27, '1', 0, NULL, 100, NULL, NULL, '3', '2022-02-17 13:02:49'),
(38, 28, '1', 0, NULL, 100, NULL, NULL, '3', '2022-02-17 13:04:30'),
(39, 29, '2', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:13:10'),
(40, 30, '2', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:13:57'),
(41, 31, '2', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:14:34'),
(42, 32, '2', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:15:30'),
(43, 33, '2', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:15:50'),
(44, 34, '2', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:16:31'),
(45, 35, '2', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:17:14'),
(46, 36, '1', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:19:20'),
(48, 38, '1', 0, NULL, 200000, NULL, NULL, '3', '2022-02-17 13:23:33'),
(49, 39, '1', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:30:57'),
(50, 40, '1', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:31:34'),
(51, 41, '1', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:32:00'),
(52, 42, '1', 0, NULL, 1000, NULL, NULL, '3', '2022-02-17 13:32:20'),
(53, 43, '3', 0, NULL, 20000000, NULL, NULL, '3', '2022-02-17 13:52:17'),
(55, 37, '2', 0, NULL, 100000, NULL, NULL, 'admin0', '2022-02-17 17:04:09'),
(56, 44, '2', 0, NULL, 8000000, NULL, NULL, '3', '2022-02-17 19:51:42'),
(57, 45, '3', 0, NULL, 7807220, NULL, NULL, '3', '2022-02-17 20:04:22'),
(58, 46, '3', 0, NULL, 9000000, NULL, NULL, '3', '2022-02-17 20:08:21'),
(59, 47, '1', 0, NULL, 3000000, NULL, NULL, '3', '2022-02-24 12:28:20'),
(61, 48, '2', 0, NULL, 300000000, NULL, NULL, 'admin0', '2022-03-15 14:19:18'),
(62, 49, '1', 0, NULL, 500000000, NULL, NULL, '3', '2022-03-19 15:01:45'),
(63, 49, '2', 0, NULL, 500000000, NULL, NULL, '3', '2022-03-19 15:01:45'),
(64, 50, '2', 0, NULL, 20000000, NULL, NULL, '3', '2022-03-20 15:24:30'),
(65, 51, '2', 0, NULL, 100000000, NULL, NULL, '3', '2022-03-20 16:49:05'),
(66, 52, '2', 0, NULL, 50000000, NULL, NULL, '3', '2022-03-20 23:17:06'),
(67, 53, '1', 0, NULL, 20000000, NULL, NULL, '3', '2022-03-23 06:38:40'),
(68, 53, '2', 0, NULL, 30000000, NULL, NULL, '3', '2022-03-23 06:38:40'),
(69, 54, '1', 0, NULL, 800000000, NULL, NULL, '3', '2022-03-23 11:27:05'),
(70, 54, '2', 0, NULL, 1200000000, NULL, NULL, '3', '2022-03-23 11:27:05'),
(71, 54, '4', 0, NULL, 300000000, NULL, NULL, '3', '2022-03-23 11:27:05'),
(72, 54, '3', 0, NULL, 400000000, NULL, NULL, '3', '2022-03-23 11:27:05'),
(73, 55, '2', 0, NULL, 100000000, NULL, NULL, '3', '2022-03-24 06:58:08'),
(74, 56, '2', 0, NULL, 150000000, NULL, NULL, '3', '2022-03-25 15:02:41'),
(75, 56, '4', 0, NULL, 150000000, NULL, NULL, '3', '2022-03-25 15:02:41'),
(76, 57, '1', 0, NULL, 50000, NULL, NULL, '3', '2022-03-25 16:11:09'),
(77, 58, '2', 0, NULL, 50000000, NULL, NULL, '3', '2022-03-25 16:19:37'),
(78, 59, '1', 0, NULL, 100000000, NULL, NULL, '3', '2022-03-26 15:25:10'),
(79, 60, '1', 0, NULL, 50000000, NULL, NULL, '3', '2022-03-28 08:47:08'),
(80, 61, '1', 0, NULL, 600000000, NULL, NULL, '3', '2022-03-29 12:24:18'),
(81, 62, '4', 0, NULL, 100000000, NULL, NULL, '3', '2022-03-30 10:20:55'),
(82, 63, '1', 0, NULL, 100000000, NULL, NULL, '3', '2022-04-01 20:47:53'),
(83, 64, '1', 0, NULL, 116000000, NULL, NULL, '3', '2022-04-02 15:04:19'),
(84, 65, '1', 0, NULL, 80000000, NULL, NULL, '3', '2022-04-04 14:25:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_myprogfunding_history`
--

CREATE TABLE `tbl_myprogfunding_history` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `sourcecategory` varchar(100) NOT NULL,
  `sourceid` int NOT NULL DEFAULT '0',
  `grantid` int NOT NULL DEFAULT '0',
  `amountfunding` double NOT NULL,
  `currency` int NOT NULL DEFAULT '0',
  `rate` int NOT NULL DEFAULT '0',
  `created_by` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_myprogfunding_history`
--

INSERT INTO `tbl_myprogfunding_history` (`id`, `progid`, `sourcecategory`, `sourceid`, `grantid`, `amountfunding`, `currency`, `rate`, `created_by`, `date_created`) VALUES
(1, 2, '3', 0, 0, 8200000000, 0, 0, 'admin0', '2022-01-15 22:34:03'),
(2, 2, '3', 0, 0, 8200000000, 0, 0, 'admin0', '2022-01-16 10:58:56'),
(3, 2, '3', 0, 0, 11000000000, 0, 0, 'admin0', '2022-01-16 11:52:58'),
(4, 1, '1', 0, 0, 1050000000, 0, 0, 'admin0', '2022-01-15 22:09:53'),
(5, 6, '2', 0, 0, 50000000, 0, 0, 'Admin0', '2022-01-16 15:41:54'),
(6, 1, '1', 0, 0, 1050000000, 0, 0, 'admin0', '2022-01-16 12:30:10'),
(7, 7, '2', 0, 0, 290000000, 0, 0, 'admin0', '2022-01-24 20:26:29'),
(8, 7, '2', 0, 0, 290000000, 0, 0, 'admin0', '2022-01-24 20:38:32'),
(9, 7, '2', 0, 0, 290000000, 0, 0, 'admin0', '2022-01-24 20:40:51'),
(10, 9, '2', 0, 0, 50000000, 0, 0, 'admin0', '2022-02-10 18:37:43'),
(11, 5, '1', 0, 0, 1000000000, 0, 0, 'admin0', '2022-01-16 15:03:19'),
(12, 37, '2', 0, 0, 100000, 0, 0, 'admin0', '2022-02-17 13:21:12'),
(13, 37, '2', 0, 0, 100000, 0, 0, 'admin0', '2022-02-17 17:03:33'),
(14, 48, '2', 0, 0, 4000000000, 0, 0, 'admin0', '2022-03-15 14:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_myprojfunding`
--

CREATE TABLE `tbl_myprojfunding` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `projid` int NOT NULL,
  `sourcecategory` int NOT NULL,
  `financier` int NOT NULL,
  `amountfunding` double NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_myprojfunding`
--

INSERT INTO `tbl_myprojfunding` (`id`, `progid`, `projid`, `sourcecategory`, `financier`, `amountfunding`, `created_by`, `date_created`) VALUES
(1, 6, 8, 2, 1, 10000000, 'Admin0', '2022-01-16 00:00:00'),
(2, 1, 2, 1, 2, 50000000, 'Admin0', '2022-01-16 00:00:00'),
(3, 6, 7, 2, 1, 15000000, 'Admin0', '2022-01-16 00:00:00'),
(4, 5, 6, 1, 2, 12000000, 'Admin0', '2022-01-16 00:00:00'),
(5, 5, 5, 1, 2, 100000000, 'Admin0', '2022-01-16 00:00:00'),
(6, 2, 9, 3, 3, 64000000, 'admin0', '2022-01-18 00:00:00'),
(7, 2, 1, 3, 3, 5000000, 'admin0', '2022-01-18 00:00:00'),
(8, 2, 4, 3, 3, 17000000, 'Admin0', '2022-01-19 00:00:00'),
(9, 5, 10, 1, 2, 2000000, 'Admin0', '2022-01-19 00:00:00'),
(10, 2, 3, 3, 3, 49000000, 'Admin0', '2022-01-19 00:00:00'),
(11, 7, 11, 2, 1, 3000000, 'admin0', '2022-01-25 00:00:00'),
(13, 2, 21, 3, 3, 17000000, 'admin0', '2022-02-02 00:00:00'),
(14, 2, 22, 3, 3, 2000000, 'admin0', '2022-02-02 00:00:00'),
(16, 2, 23, 3, 3, 7000000, 'Admin0', '2022-02-13 00:00:00'),
(17, 13, 24, 3, 3, 5000000, 'admin0', '2022-02-13 00:00:00'),
(18, 2, 25, 3, 3, 4000000, 'admin0', '2022-02-17 00:00:00'),
(19, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(20, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(21, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(22, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(23, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(24, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(25, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(26, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(27, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(28, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(29, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(30, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(31, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(32, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(33, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(34, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(35, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(36, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(37, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(38, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(39, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(40, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(41, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(42, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(43, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(44, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(45, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(46, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(47, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(48, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(49, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(50, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(51, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(52, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(53, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(54, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(55, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(56, 2, 12, 3, 3, 100, 'admin0', '2022-02-21 00:00:00'),
(57, 2, 15, 3, 3, 14000000, 'admin0', '2022-02-22 00:00:00'),
(58, 2, 15, 3, 3, 14000000, 'admin0', '2022-02-22 00:00:00'),
(59, 2, 15, 3, 3, 14000000, 'admin0', '2022-02-22 00:00:00'),
(60, 2, 15, 3, 3, 14000000, 'admin0', '2022-02-22 00:00:00'),
(61, 2, 15, 3, 3, 14000000, 'admin0', '2022-02-22 00:00:00'),
(62, 2, 15, 3, 3, 14000000, 'admin0', '2022-02-22 00:00:00'),
(63, 2, 27, 3, 3, 5500000, 'admin0', '2022-02-25 00:00:00'),
(178, 2, 18, 3, 3, 300, 'admin0', '2022-03-14 00:00:00'),
(179, 2, 18, 3, 3, 300, 'admin0', '2022-03-14 00:00:00'),
(180, 2, 18, 3, 3, 300, 'admin0', '2022-03-14 00:00:00'),
(181, 2, 18, 3, 3, 300, 'admin0', '2022-03-14 00:00:00'),
(182, 2, 18, 3, 3, 300, 'admin0', '2022-03-14 00:00:00'),
(183, 2, 18, 3, 3, 300, 'admin0', '2022-03-14 00:00:00'),
(184, 2, 18, 3, 3, 300, 'admin0', '2022-03-14 00:00:00'),
(185, 2, 29, 3, 3, 24000000, 'admin0', '2022-03-15 00:00:00'),
(186, 2, 29, 3, 3, 24000000, 'admin0', '2022-03-15 00:00:00'),
(187, 2, 29, 3, 3, 24000000, 'admin0', '2022-03-15 00:00:00'),
(188, 2, 29, 3, 3, 24000000, 'admin0', '2022-03-15 00:00:00'),
(189, 2, 29, 3, 3, 24000000, 'admin0', '2022-03-15 00:00:00'),
(190, 2, 29, 3, 3, 24000000, 'admin0', '2022-03-15 00:00:00'),
(191, 2, 29, 3, 3, 24000000, 'admin0', '2022-03-15 00:00:00'),
(192, 2, 29, 3, 3, 24000000, 'admin0', '2022-03-15 00:00:00'),
(193, 2, 29, 3, 3, 24000000, 'admin0', '2022-03-15 00:00:00'),
(194, 2, 29, 3, 3, 24000000, 'admin0', '2022-03-15 00:00:00'),
(195, 2, 29, 3, 3, 24000000, 'admin0', '2022-03-15 00:00:00'),
(196, 51, 35, 2, 1, 90000000, 'Admin0', '2022-03-20 00:00:00'),
(201, 54, 38, 3, 3, 6000000, 'Admin0', '2022-03-23 00:00:00'),
(203, 55, 39, 2, 1, 10000000, 'Admin0', '2022-03-24 00:00:00'),
(224, 56, 40, 2, 1, 10000000, 'admin0', '2022-03-27 00:00:00'),
(225, 60, 43, 1, 2, 50000000, '1', '2022-03-28 00:00:00'),
(226, 2, 13, 3, 3, 25000000, '1', '2022-03-28 00:00:00'),
(227, 47, 26, 1, 2, 20000000, '1', '2022-03-28 00:00:00'),
(228, 61, 44, 1, 2, 30000000, 'admin0', '2022-03-29 00:00:00'),
(230, 62, 45, 4, 6, 5000000, '1', '2022-03-30 00:00:00'),
(231, 62, 45, 4, 7, 5000000, '1', '2022-03-30 00:00:00'),
(232, 8, 16, 2, 1, 15000000, '1', '2022-03-30 00:00:00'),
(233, 61, 48, 1, 2, 24000000, 'admin0', '2022-03-31 00:00:00'),
(234, 63, 49, 1, 2, 50000000, '1', '2022-04-01 00:00:00'),
(235, 64, 54, 1, 2, 50000000, 'admin0', '2022-04-03 00:00:00'),
(236, 64, 50, 1, 2, 20000000, 'admin0', '2022-04-03 00:00:00'),
(237, 64, 51, 1, 2, 6000000, 'admin0', '2022-04-03 00:00:00'),
(238, 65, 55, 1, 2, 1000000, '1', '2022-04-04 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_myprojfunding_history`
--

CREATE TABLE `tbl_myprojfunding_history` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `projid` int NOT NULL,
  `sourcecategory` int NOT NULL,
  `financier` int NOT NULL,
  `amountfunding` double NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_myprojpartner`
--

CREATE TABLE `tbl_myprojpartner` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `lead_implementer` int NOT NULL,
  `implementing_partner` varchar(10) NOT NULL,
  `collaborative_partner` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_myprojpartner`
--

INSERT INTO `tbl_myprojpartner` (`id`, `projid`, `lead_implementer`, `implementing_partner`, `collaborative_partner`) VALUES
(1, 1, 0, '0', '0'),
(2, 2, 0, '0', '0'),
(3, 3, 0, '0', '0'),
(4, 4, 0, '0', '0'),
(5, 5, 0, '0', '0'),
(6, 6, 0, '0', '0'),
(7, 7, 0, '0', '0'),
(8, 8, 0, '0', '0'),
(9, 9, 0, '0', '0'),
(10, 10, 0, '0', '0'),
(11, 11, 0, '0', '0'),
(12, 12, 0, '0', '0'),
(13, 13, 0, '0', '0'),
(14, 14, 0, '0', '0'),
(15, 15, 0, '0', '0'),
(16, 16, 0, '0', '0'),
(17, 18, 0, '0', '0'),
(19, 20, 0, '0', '0'),
(20, 21, 0, '0', '0'),
(21, 22, 0, '0', '0'),
(22, 23, 0, '0', '0'),
(23, 24, 0, '0', '0'),
(24, 25, 0, '0', '0'),
(25, 26, 0, '0', '0'),
(26, 27, 0, '0', '0'),
(28, 29, 0, '0', '0'),
(29, 30, 0, '0', '0'),
(30, 33, 1, '2,3', NULL),
(31, 34, 0, '3,5', NULL),
(32, 35, 0, '0', NULL),
(33, 36, 0, '3', NULL),
(35, 38, 0, '3', NULL),
(36, 39, 2, '0', NULL),
(37, 40, 0, '6', NULL),
(38, 41, 2, '0', NULL),
(39, 42, 2, '3', NULL),
(40, 43, 2, '0', NULL),
(41, 44, 2, '0', NULL),
(42, 45, 2, '7', NULL),
(43, 46, 2, '5', NULL),
(44, 47, 2, '3', NULL),
(45, 48, 2, '0', NULL),
(46, 49, 2, '0', NULL),
(47, 50, 2, '0', NULL),
(48, 51, 2, '0', NULL),
(49, 52, 2, '0', NULL),
(50, 53, 2, '0', NULL),
(51, 54, 2, '0', NULL),
(52, 55, 2, '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `user` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `opened` enum('0','1') NOT NULL DEFAULT '0',
  `status` int NOT NULL,
  `date` datetime NOT NULL,
  `origin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`id`, `projid`, `user`, `subject`, `message`, `opened`, `status`, `date`, `origin`) VALUES
(1, 8, 'Admin0', 'Project 11', 'Project with project code. CP9000 is Behind Schedule', '0', 11, '2022-01-19 06:15:23', 'Project Monitoring'),
(2, 8, 'Admin0', 'Project In Progress', 'Project with project code. CP9000 is In Progress', '0', 4, '2022-03-23 10:48:18', 'Project Monitoring'),
(3, 35, 'Admin0', 'Project 11', 'Project with project code. 004321 is Behind Schedule', '0', 11, '2022-03-24 14:36:38', 'Project Monitoring'),
(4, 38, 'Admin0', 'Project 11', 'Project with project code. 2021/RW002 is Behind Schedule', '0', 11, '2022-03-24 14:36:38', 'Project Monitoring');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_objective_strategy`
--

CREATE TABLE `tbl_objective_strategy` (
  `id` int NOT NULL,
  `objid` int NOT NULL,
  `strategy` varchar(255) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_objective_strategy`
--

INSERT INTO `tbl_objective_strategy` (`id`, `objid`, `strategy`, `created_by`, `date_created`) VALUES
(2, 2, 'Strategy 1', 'admin0', '2022-01-15'),
(3, 3, 'Strategy 1', 'admin0', '2022-01-15'),
(4, 4, 'Strategy 1', 'admin0', '2022-01-15'),
(5, 5, 'Strategy 1', 'admin0', '2022-01-15'),
(8, 1, 'Transport Infrastructure Services', 'admin0', '2022-01-15'),
(9, 1, 'Acquisition of Roads Equipment and Putting up of a Plant', 'admin0', '2022-01-15'),
(10, 1, 'Roads Infrastructure Services', 'admin0', '2022-01-15'),
(11, 1, 'Automation of systems ', 'admin0', '2022-01-28'),
(12, 6, 'ECDE Education', 'admin0', '2022-02-10'),
(13, 6, 'Social Development Services ', 'admin0', '2022-02-10'),
(14, 7, 'ST1', 'Admin0', '2022-02-12'),
(15, 8, 'ST', 'Admin0', '2022-02-12'),
(16, 9, 'st', 'Admin0', '2022-02-12'),
(21, 14, 'provide an integrated spatial framework for sustainable socio-economic development of Uasin Gishu County through research, policy, land use planning and development control', 'admin0', '2022-02-15'),
(22, 15, 'provide an integrated spatial framework for sustainable socio-economic development of Uasin Gishu County through research, policy, land use planning and development control', 'admin0', '2022-02-15'),
(23, 16, 'Efficiently and effectively administer land and housing matters to the satisfaction of all', 'admin0', '2022-02-15'),
(24, 17, 'Efficiently and effectively administer land and housing matters to the satisfaction of all', 'admin0', '2022-02-15'),
(25, 18, 'Efficiently and effectively administer land and housing matters to the satisfaction of all', 'admin0', '2022-02-15'),
(26, 14, 'Efficiently and effectively administer land and housing matters to the satisfaction of all', 'admin0', '2022-02-15'),
(27, 19, 'To improve food security and livelihoods in Uasin Gishu through commercial agriculture for sustainable development', 'admin0', '2022-02-15'),
(28, 20, 'To improve food security and livelihoods in Uasin Gishu through commercial agriculture for sustainable development', 'admin0', '2022-02-15'),
(29, 21, 'To improve food security and livelihoods in Uasin Gishu through commercial agriculture for sustainable development', 'admin0', '2022-02-15'),
(30, 22, 'Consequatur Modi mi', '1', '2022-03-19'),
(31, 23, 'Strategy 1', '1', '2022-03-19'),
(32, 24, 'ST', '1', '2022-03-19'),
(33, 1, 'test', '1', '2022-03-19'),
(34, 23, 'S4', '1', '2022-03-19'),
(35, 25, 'Tester Strategy', '1', '2022-03-20'),
(36, 26, 'Strategy 5', '1', '2022-03-23'),
(37, 27, 'To provide efficient, affordable and reliable infrastructure for sustainable economic growth and development through construction, modernization, rehabilitation and effective management of all infrastructure facilities', '1', '2022-03-23'),
(39, 29, 'Strategy 1', '1', '2022-03-23'),
(40, 30, 'Strategy 5', '1', '2022-03-25'),
(41, 31, 'strategy1', '1', '2022-04-02'),
(42, 32, 'strategy1', '1', '2022-04-02'),
(43, 33, 'stt1', '1', '2022-04-02'),
(44, 31, 'sttttttttree', '1', '2022-04-02'),
(45, 34, 'STrtategy1', '1', '2022-04-02'),
(46, 2, 'strategy', '1', '2022-04-02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_other_funding`
--

CREATE TABLE `tbl_other_funding` (
  `fdid` int NOT NULL,
  `fund_code` varchar(100) NOT NULL,
  `funder` varchar(250) NOT NULL,
  `funding_type` int NOT NULL,
  `financialyear` int NOT NULL,
  `amount` double NOT NULL,
  `date_authorized` date NOT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_outcomes`
--

CREATE TABLE `tbl_outcomes` (
  `ocid` int NOT NULL,
  `deptid` int NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `outcome` varchar(255) NOT NULL,
  `indicator` int NOT NULL,
  `date_created` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_outputs`
--

CREATE TABLE `tbl_outputs` (
  `opid` int NOT NULL,
  `deptid` int NOT NULL,
  `code` varchar(20) NOT NULL,
  `output` varchar(255) NOT NULL,
  `indicator` int NOT NULL,
  `date_created` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_output_disaggregation`
--

CREATE TABLE `tbl_output_disaggregation` (
  `id` int NOT NULL,
  `projid` int DEFAULT NULL,
  `outputid` int NOT NULL,
  `outputstate` int NOT NULL,
  `total_target` double NOT NULL,
  `locations` varchar(255) DEFAULT NULL,
  `responsible` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_output_disaggregation`
--

INSERT INTO `tbl_output_disaggregation` (`id`, `projid`, `outputid`, `outputstate`, `total_target`, `locations`, `responsible`) VALUES
(234, 1, 64, 367, 2, NULL, NULL),
(233, 1, 64, 366, 2, NULL, NULL),
(3, NULL, 2, 348, 2, NULL, NULL),
(4, NULL, 2, 349, 2, NULL, NULL),
(5, NULL, 2, 382, 1, NULL, NULL),
(6, NULL, 2, 384, 1, NULL, NULL),
(7, NULL, 3, 348, 2, NULL, NULL),
(8, NULL, 3, 349, 2, NULL, NULL),
(9, NULL, 4, 348, 2, NULL, NULL),
(10, NULL, 3, 382, 1, NULL, NULL),
(11, NULL, 4, 349, 2, NULL, NULL),
(12, NULL, 3, 384, 1, NULL, NULL),
(13, NULL, 5, 348, 2, NULL, NULL),
(14, NULL, 4, 382, 1, NULL, NULL),
(15, NULL, 5, 349, 2, NULL, NULL),
(16, NULL, 4, 384, 1, NULL, NULL),
(17, NULL, 5, 382, 1, NULL, NULL),
(18, NULL, 5, 384, 1, NULL, NULL),
(19, NULL, 6, 348, 2, NULL, NULL),
(20, NULL, 6, 349, 2, NULL, NULL),
(21, NULL, 6, 382, 1, NULL, NULL),
(22, NULL, 6, 384, 1, NULL, NULL),
(23, NULL, 7, 348, 2, NULL, NULL),
(24, NULL, 7, 349, 2, NULL, NULL),
(25, NULL, 8, 348, 2, NULL, NULL),
(26, NULL, 7, 382, 1, NULL, NULL),
(27, NULL, 9, 348, 2, NULL, NULL),
(28, NULL, 8, 349, 2, NULL, NULL),
(29, NULL, 7, 384, 1, NULL, NULL),
(30, NULL, 10, 348, 2, NULL, NULL),
(31, NULL, 9, 349, 2, NULL, NULL),
(32, NULL, 8, 382, 1, NULL, NULL),
(33, NULL, 10, 349, 2, NULL, NULL),
(34, NULL, 9, 382, 1, NULL, NULL),
(35, NULL, 8, 384, 1, NULL, NULL),
(36, NULL, 10, 382, 1, NULL, NULL),
(37, NULL, 9, 384, 1, NULL, NULL),
(38, NULL, 10, 384, 1, NULL, NULL),
(39, NULL, 11, 348, 2, NULL, NULL),
(40, NULL, 11, 349, 2, NULL, NULL),
(41, NULL, 11, 382, 1, NULL, NULL),
(42, NULL, 11, 384, 1, NULL, NULL),
(43, NULL, 12, 348, 2, NULL, NULL),
(44, NULL, 13, 348, 2, NULL, NULL),
(45, NULL, 12, 349, 2, NULL, NULL),
(46, NULL, 13, 349, 2, NULL, NULL),
(47, NULL, 14, 348, 2, NULL, NULL),
(48, NULL, 12, 382, 1, NULL, NULL),
(49, NULL, 13, 382, 1, NULL, NULL),
(50, NULL, 15, 348, 2, NULL, NULL),
(51, NULL, 14, 349, 2, NULL, NULL),
(52, NULL, 12, 384, 1, NULL, NULL),
(53, NULL, 13, 384, 1, NULL, NULL),
(54, NULL, 15, 349, 2, NULL, NULL),
(55, NULL, 14, 382, 1, NULL, NULL),
(56, NULL, 15, 382, 1, NULL, NULL),
(57, NULL, 14, 384, 1, NULL, NULL),
(58, NULL, 15, 384, 1, NULL, NULL),
(59, NULL, 16, 348, 2, NULL, NULL),
(60, NULL, 16, 349, 2, NULL, NULL),
(61, NULL, 17, 348, 2, NULL, NULL),
(62, NULL, 16, 382, 1, NULL, NULL),
(63, NULL, 18, 348, 2, NULL, NULL),
(64, NULL, 17, 349, 2, NULL, NULL),
(65, NULL, 16, 384, 1, NULL, NULL),
(66, NULL, 19, 348, 2, NULL, NULL),
(67, NULL, 18, 349, 2, NULL, NULL),
(68, NULL, 17, 382, 1, NULL, NULL),
(69, NULL, 19, 349, 2, NULL, NULL),
(70, NULL, 18, 382, 1, NULL, NULL),
(71, NULL, 17, 384, 1, NULL, NULL),
(72, NULL, 19, 382, 1, NULL, NULL),
(73, NULL, 18, 384, 1, NULL, NULL),
(74, NULL, 19, 384, 1, NULL, NULL),
(75, NULL, 20, 348, 2, NULL, NULL),
(76, NULL, 20, 349, 2, NULL, NULL),
(77, NULL, 20, 382, 1, NULL, NULL),
(78, NULL, 20, 384, 1, NULL, NULL),
(79, NULL, 21, 348, 2, NULL, NULL),
(80, NULL, 22, 348, 2, NULL, NULL),
(81, NULL, 21, 349, 2, NULL, NULL),
(82, NULL, 22, 349, 2, NULL, NULL),
(83, NULL, 21, 382, 1, NULL, NULL),
(84, NULL, 22, 382, 1, NULL, NULL),
(85, NULL, 21, 384, 1, NULL, NULL),
(86, NULL, 22, 384, 1, NULL, NULL),
(87, NULL, 23, 348, 2, NULL, NULL),
(88, NULL, 24, 348, 2, NULL, NULL),
(89, NULL, 23, 349, 2, NULL, NULL),
(90, NULL, 25, 348, 2, NULL, NULL),
(91, NULL, 24, 349, 2, NULL, NULL),
(92, NULL, 23, 382, 1, NULL, NULL),
(93, NULL, 25, 349, 2, NULL, NULL),
(94, NULL, 26, 348, 2, NULL, NULL),
(95, NULL, 27, 348, 2, NULL, NULL),
(96, NULL, 24, 382, 1, NULL, NULL),
(97, NULL, 23, 384, 1, NULL, NULL),
(98, NULL, 25, 382, 1, NULL, NULL),
(99, NULL, 28, 348, 2, NULL, NULL),
(100, NULL, 26, 349, 2, NULL, NULL),
(101, NULL, 27, 349, 2, NULL, NULL),
(102, NULL, 24, 384, 1, NULL, NULL),
(103, NULL, 25, 384, 1, NULL, NULL),
(104, NULL, 28, 349, 2, NULL, NULL),
(105, NULL, 26, 382, 1, NULL, NULL),
(106, NULL, 27, 382, 1, NULL, NULL),
(107, NULL, 28, 382, 1, NULL, NULL),
(108, NULL, 26, 384, 1, NULL, NULL),
(109, NULL, 27, 384, 1, NULL, NULL),
(110, NULL, 28, 384, 1, NULL, NULL),
(111, NULL, 29, 348, 2, NULL, NULL),
(112, NULL, 29, 349, 2, NULL, NULL),
(113, NULL, 29, 382, 1, NULL, NULL),
(114, NULL, 29, 384, 1, NULL, NULL),
(115, NULL, 30, 348, 2, NULL, NULL),
(116, NULL, 31, 348, 2, NULL, NULL),
(117, NULL, 30, 349, 2, NULL, NULL),
(118, NULL, 31, 349, 2, NULL, NULL),
(119, NULL, 30, 382, 1, NULL, NULL),
(120, NULL, 31, 382, 1, NULL, NULL),
(121, NULL, 30, 384, 1, NULL, NULL),
(122, NULL, 31, 384, 1, NULL, NULL),
(123, NULL, 32, 348, 2, NULL, NULL),
(124, NULL, 32, 349, 2, NULL, NULL),
(125, NULL, 33, 348, 2, NULL, NULL),
(126, NULL, 32, 382, 1, NULL, NULL),
(127, NULL, 33, 349, 2, NULL, NULL),
(128, NULL, 34, 348, 2, NULL, NULL),
(129, NULL, 32, 384, 1, NULL, NULL),
(130, NULL, 33, 382, 1, NULL, NULL),
(131, NULL, 34, 349, 2, NULL, NULL),
(132, NULL, 33, 384, 1, NULL, NULL),
(133, NULL, 35, 348, 2, NULL, NULL),
(134, NULL, 34, 382, 1, NULL, NULL),
(135, NULL, 36, 348, 2, NULL, NULL),
(136, NULL, 35, 349, 2, NULL, NULL),
(137, NULL, 37, 348, 2, NULL, NULL),
(138, NULL, 34, 384, 1, NULL, NULL),
(139, NULL, 36, 349, 2, NULL, NULL),
(140, NULL, 35, 382, 1, NULL, NULL),
(141, NULL, 37, 349, 2, NULL, NULL),
(142, NULL, 36, 382, 1, NULL, NULL),
(143, NULL, 35, 384, 1, NULL, NULL),
(144, NULL, 37, 382, 1, NULL, NULL),
(145, NULL, 36, 384, 1, NULL, NULL),
(146, NULL, 37, 384, 1, NULL, NULL),
(147, NULL, 38, 348, 2, NULL, NULL),
(148, NULL, 38, 349, 2, NULL, NULL),
(149, NULL, 38, 382, 1, NULL, NULL),
(150, NULL, 38, 384, 1, NULL, NULL),
(151, NULL, 39, 348, 2, NULL, NULL),
(152, NULL, 40, 348, 2, NULL, NULL),
(153, NULL, 39, 349, 2, NULL, NULL),
(154, NULL, 40, 349, 2, NULL, NULL),
(155, NULL, 39, 382, 1, NULL, NULL),
(156, NULL, 40, 382, 1, NULL, NULL),
(157, NULL, 39, 384, 1, NULL, NULL),
(158, NULL, 40, 384, 1, NULL, NULL),
(159, NULL, 41, 348, 2, NULL, NULL),
(160, NULL, 41, 349, 2, NULL, NULL),
(161, NULL, 41, 382, 1, NULL, NULL),
(162, NULL, 41, 384, 1, NULL, NULL),
(163, NULL, 42, 348, 2, NULL, NULL),
(164, NULL, 43, 348, 2, NULL, NULL),
(165, NULL, 42, 349, 2, NULL, NULL),
(166, NULL, 43, 349, 2, NULL, NULL),
(167, NULL, 42, 382, 1, NULL, NULL),
(168, NULL, 43, 382, 1, NULL, NULL),
(169, NULL, 42, 384, 1, NULL, NULL),
(170, NULL, 43, 384, 1, NULL, NULL),
(171, NULL, 44, 348, 2, NULL, NULL),
(172, NULL, 45, 348, 2, NULL, NULL),
(173, NULL, 44, 349, 2, NULL, NULL),
(174, NULL, 46, 348, 2, NULL, NULL),
(175, NULL, 45, 349, 2, NULL, NULL),
(176, NULL, 44, 382, 1, NULL, NULL),
(177, NULL, 46, 349, 2, NULL, NULL),
(178, NULL, 45, 382, 1, NULL, NULL),
(179, NULL, 44, 384, 1, NULL, NULL),
(180, NULL, 46, 382, 1, NULL, NULL),
(181, NULL, 45, 384, 1, NULL, NULL),
(182, NULL, 46, 384, 1, NULL, NULL),
(183, NULL, 47, 348, 2, NULL, NULL),
(184, NULL, 47, 349, 2, NULL, NULL),
(185, NULL, 47, 382, 1, NULL, NULL),
(186, NULL, 48, 348, 2, NULL, NULL),
(187, NULL, 47, 384, 1, NULL, NULL),
(188, NULL, 49, 348, 2, NULL, NULL),
(189, NULL, 48, 349, 2, NULL, NULL),
(190, NULL, 50, 348, 2, NULL, NULL),
(191, NULL, 49, 349, 2, NULL, NULL),
(192, NULL, 48, 382, 1, NULL, NULL),
(193, NULL, 51, 348, 2, NULL, NULL),
(194, NULL, 52, 348, 2, NULL, NULL),
(195, NULL, 50, 349, 2, NULL, NULL),
(196, NULL, 49, 382, 1, NULL, NULL),
(197, NULL, 48, 384, 1, NULL, NULL),
(198, NULL, 51, 349, 2, NULL, NULL),
(199, NULL, 52, 349, 2, NULL, NULL),
(200, NULL, 50, 382, 1, NULL, NULL),
(201, NULL, 49, 384, 1, NULL, NULL),
(202, NULL, 51, 382, 1, NULL, NULL),
(203, NULL, 52, 382, 1, NULL, NULL),
(204, NULL, 50, 384, 1, NULL, NULL),
(205, NULL, 51, 384, 1, NULL, NULL),
(206, NULL, 52, 384, 1, NULL, NULL),
(207, NULL, 53, 348, 2, NULL, NULL),
(208, NULL, 54, 348, 2, NULL, NULL),
(209, NULL, 53, 349, 2, NULL, NULL),
(210, NULL, 54, 349, 2, NULL, NULL),
(211, NULL, 53, 382, 1, NULL, NULL),
(212, NULL, 54, 382, 1, NULL, NULL),
(213, NULL, 53, 384, 1, NULL, NULL),
(214, NULL, 54, 384, 1, NULL, NULL),
(215, 2, 55, 370, 1, NULL, NULL),
(216, 3, 56, 365, 1, NULL, NULL),
(217, 4, 57, 402, 1, NULL, NULL),
(225, NULL, 58, 346, 3.5, NULL, NULL),
(224, NULL, 58, 369, 1.5, NULL, NULL),
(226, 5, 59, 411, 12, NULL, NULL),
(227, 5, 59, 412, 12, NULL, NULL),
(228, 5, 59, 413, 12, NULL, NULL),
(229, 6, 60, 361, 3, NULL, NULL),
(230, 7, 61, 383, 1, NULL, NULL),
(235, NULL, 65, 366, 5, NULL, NULL),
(232, 8, 63, 361, 1, NULL, '8'),
(236, NULL, 65, 367, 9, NULL, NULL),
(237, NULL, 66, 354, 5, NULL, NULL),
(240, NULL, 67, 354, 15, NULL, NULL),
(241, NULL, 68, 354, 1, NULL, NULL),
(242, 9, 69, 354, 2, NULL, NULL),
(243, 9, 70, 354, 10, NULL, NULL),
(244, NULL, 71, 354, 10, NULL, NULL),
(245, 9, 72, 354, 1, NULL, NULL),
(246, 10, 73, 401, 1, NULL, NULL),
(247, NULL, 74, 350, 10, NULL, NULL),
(249, NULL, 75, 346, 1, NULL, NULL),
(250, 11, 76, 346, 1, NULL, NULL),
(251, NULL, 77, 368, 20, NULL, NULL),
(252, 15, 86, 364, 1, NULL, NULL),
(253, NULL, 92, 396, 1, NULL, NULL),
(254, NULL, 92, 398, 1, NULL, NULL),
(255, 19, 93, 341, 2, NULL, NULL),
(256, 20, 94, 396, 9, NULL, NULL),
(257, 20, 94, 398, 4, NULL, NULL),
(258, 21, 95, 340, 4.2, NULL, NULL),
(259, 21, 95, 341, 5.8, NULL, NULL),
(260, 22, 96, 404, 4, NULL, '10'),
(261, 23, 97, 383, 1, NULL, NULL),
(262, NULL, 98, 346, 10, NULL, NULL),
(263, NULL, 99, 346, 1, NULL, NULL),
(264, NULL, 99, 401, 1, NULL, NULL),
(265, NULL, 99, 402, 1, NULL, NULL),
(266, 24, 100, 350, 1, NULL, '13'),
(267, 25, 101, 397, 6, NULL, NULL),
(268, NULL, 102, 351, 1, NULL, NULL),
(269, NULL, 102, 382, 1, NULL, NULL),
(270, NULL, 102, 390, 1, NULL, NULL),
(271, NULL, 103, 360, 1, NULL, NULL),
(272, 26, 104, 374, 2, NULL, NULL),
(273, 27, 105, 342, 3, NULL, '2'),
(274, 27, 105, 344, 2, NULL, '2'),
(285, 30, 125, 352, 1, NULL, NULL),
(276, NULL, 107, 380, 50, NULL, NULL),
(278, NULL, 119, 347, 70, NULL, NULL),
(279, NULL, 120, 363, 2, NULL, NULL),
(280, NULL, 121, 389, 2, NULL, NULL),
(282, 29, 122, 409, 3, NULL, NULL),
(283, 29, 123, 409, 2, NULL, NULL),
(286, NULL, 126, 357, 2, NULL, NULL),
(289, NULL, 127, 363, 3, NULL, NULL),
(290, NULL, 128, 384, 30, NULL, NULL),
(292, 33, 129, 373, 2, NULL, NULL),
(293, 34, 130, 409, 1, NULL, NULL),
(294, 35, 131, 389, 40, NULL, '24'),
(295, 36, 132, 410, 10, NULL, NULL),
(296, 37, 133, 371, 1, NULL, NULL),
(297, 38, 134, 389, 15, NULL, '8'),
(298, 38, 134, 390, 5, NULL, '2'),
(299, 39, 135, 356, 4, NULL, '34'),
(300, 40, 136, 373, 1, NULL, NULL),
(301, NULL, 137, 350, 1, NULL, NULL),
(302, 41, 138, 3, 1, NULL, NULL),
(303, 42, 139, 360, 10, NULL, NULL),
(304, NULL, 140, 409, 10, NULL, NULL),
(308, 43, 142, 385, 5, NULL, NULL),
(307, 43, 142, 384, 5, NULL, NULL),
(309, 44, 143, 352, 1, NULL, '15'),
(310, NULL, 144, 410, 1000, NULL, NULL),
(311, NULL, 145, 410, 1000, NULL, NULL),
(312, NULL, 146, 410, 1000, NULL, NULL),
(313, NULL, 147, 410, 1000, NULL, NULL),
(314, NULL, 148, 410, 1000, NULL, NULL),
(316, NULL, 149, 410, 1000, NULL, NULL),
(317, NULL, 150, 346, 1000, NULL, NULL),
(318, NULL, 150, 347, 1000, NULL, NULL),
(326, 45, 151, 346, 1000, NULL, '1'),
(325, 45, 151, 347, 1000, NULL, '1'),
(330, NULL, 152, 377, 9, NULL, NULL),
(331, NULL, 153, 377, 1, NULL, NULL),
(332, NULL, 154, 377, 1, NULL, NULL),
(333, NULL, 155, 350, 1, NULL, NULL),
(334, NULL, 156, 365, 1, NULL, NULL),
(335, NULL, 157, 365, 1, NULL, NULL),
(336, NULL, 158, 384, 1, NULL, NULL),
(337, NULL, 159, 384, 1, NULL, NULL),
(338, NULL, 160, 411, 6, NULL, NULL),
(339, NULL, 160, 412, 4, NULL, NULL),
(340, 46, 161, 386, 1, NULL, NULL),
(341, 47, 162, 366, 1, NULL, NULL),
(342, 48, 163, 404, 1, NULL, NULL),
(343, 49, 164, 357, 25, NULL, '1'),
(344, 49, 164, 358, 25, NULL, '1'),
(345, 50, 165, 3, 2, NULL, NULL),
(346, 51, 166, 3, 1, NULL, NULL),
(347, 52, 167, 3, 3, NULL, NULL),
(348, 53, 168, 3, 3, NULL, NULL),
(349, 54, 169, 3, 6, NULL, '1'),
(350, 55, 170, 409, 3000, NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_output_risks`
--

CREATE TABLE `tbl_output_risks` (
  `id` int NOT NULL,
  `projid` int DEFAULT NULL,
  `rskid` int NOT NULL,
  `outputid` int NOT NULL,
  `type` int NOT NULL,
  `assumption` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_partners`
--

CREATE TABLE `tbl_partners` (
  `ptnid` int NOT NULL,
  `partnertype` int NOT NULL DEFAULT '1',
  `partnercompany` varchar(250) DEFAULT NULL,
  `partnername` varchar(250) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `designation` varchar(250) NOT NULL,
  `address` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `state` varchar(250) DEFAULT NULL,
  `county` int DEFAULT NULL,
  `country` int NOT NULL,
  `phone` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `comments` text,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `created_by` int NOT NULL,
  `date_created` datetime NOT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_password_resets`
--

CREATE TABLE `tbl_password_resets` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_password_resets`
--

INSERT INTO `tbl_password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(1, 'biwottech@gmail.com', 'oreqXQrr4ecNiUmD8dGjIoZsGQz59OKaVbTs5z1WfvC3sShpHD2V3E0ofCy2sMMM', '2022-04-05 20:55:55'),
(2, 'biwottech@gmail.com', '1fGiT1EwRXJuDyQzLLKflOnFH0YqRgmvqBghtcLVTiMkyepmsQsVYujOY3H7L2rN', '2022-04-05 20:59:08'),
(3, 'biwottech@gmail.com', 'vAYLxXRe0ykQ8wwwDMZSg5wV23p0eQVMLbLf6FQXYuH4UqxVVOtKdk2M9nl7wpIK', '2022-04-05 21:00:30'),
(4, 'biwottech@gmail.com', 'wGyBktVQ0x5AmdrZdWr4bZxwrXqZGA6GKHAvTfeM3Lj2rXlwb7mhWJidlz7km3LB', '2022-04-05 21:01:55'),
(5, 'biwottech@gmail.com', 'vYgQNDyLwxHbhQEdLdiwt5H46QHTluI0a9TKjfWlz7Xh97hK8RTjHXQSLaiMGNMB', '2022-04-05 21:02:22'),
(6, 'biwottech@gmail.com', 'YthC8wtSGxBVaGGdkF3fGYz3PiUnhEMWwGNOEO0DAbOIUZ5zPlW0wtaSyNTN72et', '2022-04-05 21:03:11'),
(7, 'denkytheka@gmail.com', 'IDA9MZ3zXLnolHnco3nbHO0y7nKR8wDdoIC8tdlSK6evsNvPLw7LMlcnsEXfyCyY', '2022-04-06 03:01:15'),
(8, 'biwottech@gmail.com', 'dxhrC9rKDeYJW27MrYGMd1n2Qb0D5o3Zjq0PQ6f8ix9cbDRwzgPVHKiJ5uv4BpIf', '2022-04-06 13:49:21'),
(9, 'biwottech@gmail.com', 'KjOUuRc5maiVBAhjZyuqWlvB80T5UB48NBlubOXUmaPA9YkdqorIWwk8Q653xn1g', '2022-04-06 13:50:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payments_disbursed`
--

CREATE TABLE `tbl_payments_disbursed` (
  `id` int NOT NULL,
  `reqid` int NOT NULL,
  `requestid` varchar(20) NOT NULL,
  `refid` varchar(20) NOT NULL,
  `projid` int DEFAULT NULL,
  `itemid` int DEFAULT NULL,
  `amountpaid` double NOT NULL,
  `paymentmode` varchar(20) NOT NULL,
  `fundsource` int DEFAULT NULL,
  `comments` text,
  `floc` varchar(255) DEFAULT NULL,
  `paidby` varchar(20) DEFAULT NULL,
  `recipient` int NOT NULL,
  `datepaid` date DEFAULT NULL,
  `recordedby` varchar(100) NOT NULL,
  `daterecorded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_payments_disbursed`
--

INSERT INTO `tbl_payments_disbursed` (`id`, `reqid`, `requestid`, `refid`, `projid`, `itemid`, `amountpaid`, `paymentmode`, `fundsource`, `comments`, `floc`, `paidby`, `recipient`, `datepaid`, `recordedby`, `daterecorded`) VALUES
(1, 1, 'MST/22/79843', '34657', 38, NULL, 650000, '4', NULL, 'Test', NULL, '1', 10, '2022-04-04', '1', '2022-04-04'),
(2, 2, 'MST/22/52542', '2738', 38, NULL, 1500000, '4', NULL, 'Testing', NULL, '1', 2, '2022-04-05', '1', '2022-04-05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payments_request`
--

CREATE TABLE `tbl_payments_request` (
  `id` int NOT NULL,
  `requestid` varchar(20) NOT NULL,
  `projid` int NOT NULL,
  `itemid` int NOT NULL,
  `itemcategory` int NOT NULL,
  `amountrequested` double NOT NULL,
  `requestedby` varchar(100) NOT NULL,
  `daterequested` date NOT NULL,
  `approvalby` varchar(100) DEFAULT NULL,
  `approvaldate` date DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_payments_request`
--

INSERT INTO `tbl_payments_request` (`id`, `requestid`, `projid`, `itemid`, `itemcategory`, `amountrequested`, `requestedby`, `daterequested`, `approvalby`, `approvaldate`, `status`) VALUES
(1, 'MST/22/79843', 38, 43, 2, 650000, '1', '2022-04-04', 'admin0', '2022-04-04', 4),
(2, 'MST/22/52542', 38, 42, 2, 1500000, '1', '2022-04-04', 'admin0', '2022-04-05', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_request_comments`
--

CREATE TABLE `tbl_payment_request_comments` (
  `id` int NOT NULL,
  `reqid` int NOT NULL,
  `comments` text NOT NULL,
  `user` varchar(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_payment_request_comments`
--

INSERT INTO `tbl_payment_request_comments` (`id`, `reqid`, `comments`, `user`, `date`) VALUES
(1, 1, 'Test', '1', '2022-04-04'),
(2, 2, 'test', '1', '2022-04-04'),
(3, 1, '<p>Testing</p>\r\n', 'admin0', '2022-04-04'),
(4, 2, '<p>Testing</p>\r\n', 'admin0', '2022-04-05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_status`
--

CREATE TABLE `tbl_payment_status` (
  `id` int NOT NULL,
  `status` varchar(20) NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_payment_status`
--

INSERT INTO `tbl_payment_status` (`id`, `status`, `active`) VALUES
(1, 'Pending Approval', 1),
(2, 'Pending Payment', 1),
(3, 'Request Rejected', 1),
(4, 'Fully Paid', 1),
(5, 'Partly Paid', 1),
(6, 'Payment Overdue', 1),
(7, 'Approval Overdue', 1),
(8, 'Accusamus repudianda', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pmdesignation`
--

CREATE TABLE `tbl_pmdesignation` (
  `moid` int NOT NULL,
  `designation` varchar(255) NOT NULL,
  `Reporting` int NOT NULL,
  `level` int DEFAULT NULL,
  `position` int NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pmdesignation`
--

INSERT INTO `tbl_pmdesignation` (`moid`, `designation`, `Reporting`, `level`, `position`, `active`) VALUES
(1, 'Super User', 0, 0, 1, 1),
(2, 'Governor', 0, 0, 2, 1),
(3, 'Deputy Governor', 0, 0, 3, 1),
(4, 'County Secretary', 2, 0, 4, 1),
(5, 'CEC', 2, 1, 5, 1),
(6, 'CO', 2, 1, 6, 1),
(7, 'Director', 6, 2, 7, 1),
(8, 'Deputy Director', 6, 2, 8, 1),
(9, 'Assistant Director', 7, 2, 9, 1),
(10, 'Manager', 7, 2, 10, 1),
(11, 'Assistant Manager', 7, 2, 11, 1),
(12, 'Principal Officer', 7, 2, 12, 1),
(13, 'Officer', 7, 2, 13, 1),
(14, 'Assistant Officer', 7, 2, 14, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_priorities`
--

CREATE TABLE `tbl_priorities` (
  `id` int NOT NULL,
  `priority` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `weight` int NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_priorities`
--

INSERT INTO `tbl_priorities` (`id`, `priority`, `description`, `weight`, `status`) VALUES
(1, 'High', 'High Priority', 3, 1),
(2, 'Medium', 'Medium Priority', 2, 1),
(3, 'Low', 'Low Priority', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_procurementmethod`
--

CREATE TABLE `tbl_procurementmethod` (
  `id` int NOT NULL,
  `method` varchar(100) NOT NULL,
  `description` text,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_procurementmethod`
--

INSERT INTO `tbl_procurementmethod` (`id`, `method`, `description`, `status`) VALUES
(1, 'Open Tender', 'This method is prioritized by law (Sec. 91 of the Act). All other methods need to be justified by special circumstances.Where tenders are open to anybody who may wish to to apply', 1),
(2, 'Restricted Tendering', 'An accounting officer of a procuring entity may use restricted tendering only if any of the following conditions are satisfied', 1),
(3, 'Two-stage tendering', 'Two-stage tendering is a procedure typically used to achieve an early appointment of a contractor to a lump-sum contract. A procuring entity may engage in procurement by means of two-stage tendering when, due to complexity and inadequate knowledge ', 1),
(4, 'Design competition', 'In this type of procurement, part of the services are already a part of the tender, because the overall shape and the details of the contract are offered by the contractual partner and not by the procuring entity', 1),
(6, 'Request for Quotations', 'Used for Consultancy Services. There are elaborate provisions for requests for proposals in Sections 115 to 130 of the  Public Procurement and Asset Disposal Act. They are used if the services to be procured are advisory or otherwise of a predominately in', 1),
(7, 'Direct procurement', 'A procuring entity may use direct procurement as allowed as long as the purpose is not to avoid competition.\', 1),\r\n(7, \'Request for quotations\', \'A procuring entity may use a request for quotations from the register of suppliers for a procurement only if the estimated value of the goods, works or non-consultancy services being procured is not more than 1 Million KES', 1),
(8, 'Electronic reverse auction', 'This is a type of auction in which the roles of buyer and seller are reversed. In an ordinary auction (also known as a forward auction), buyers compete to obtain a good or service by offering increasingly higher prices.', 1),
(9, 'Low Value Procurement', 'A procuring entity may use a low-value procurement procedure if the entity is procuring low value items which are not procured on a regular or frequent basis and are not covered in framework agreement', 1),
(10, 'Force Account', 'The work is done and billed by a public entity with labour and equipment owned by the public. The Law in Art. 109 is very clear about the conditions', 1),
(11, 'Request for proposals', 'Used for Consultancy Services. There are elaborate provisions for requests for proposals in Sections 115 to 130 of the  Public Procurement and Asset Disposal Act', 1),
(12, 'Framework Agreeement', 'The law states â€“ among other provisions â€“ in Sec. 114 (1)', 1),
(13, 'Classified Procurement', 'For reasons of national security some procurements are of a classified nature. Which means that the list of items is confidential and must not be disclosed. Procurement can be managed with dual lists of goods and servicese, of which one is confidential', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_progdetails`
--

CREATE TABLE `tbl_progdetails` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `year` int NOT NULL,
  `output` varchar(255) NOT NULL,
  `indicator` int NOT NULL,
  `target` int NOT NULL,
  `budget` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_progdetails`
--

INSERT INTO `tbl_progdetails` (`id`, `progid`, `year`, `output`, `indicator`, `target`, `budget`) VALUES
(1, 2, 2020, 'Tarmacked Road', 18, 16, 7250000000),
(2, 2, 2021, 'Tarmacked Road', 18, 60, 7550000000),
(3, 2, 2022, 'Tarmacked Road', 18, 60, 8200000000),
(4, 2, 2021, 'Culvert', 19, 89, 30000000),
(5, 2, 2021, 'Bridge', 17, 30, 1500000000),
(6, 1, 2021, 'Bridge', 17, 30, 1050000000),
(7, 6, 2021, 'Cereal stores constructed', 23, 5, 50000000),
(8, 7, 2021, 'Parking Bay', 21, 30, 59000000),
(9, 7, 2022, 'Parking Bay', 21, 45, 78000000),
(13, 14, 2021, 'Titles Issued', 66, 200, 30000000),
(12, 5, 2021, 'Boreholes drilled and equiped', 2, 50, 200000000),
(14, 14, 2021, 'Acres of land acquired ', 65, 20, 100000000),
(15, 15, 2021, 'Parcel of Land Surveyed', 67, 100, 6000000),
(16, 16, 2021, 'Operational Office block', 68, 16, 30000000),
(17, 16, 2021, 'Housing Unit renovated ', 69, 100, 259000),
(18, 16, 2021, 'Public utility fenced ', 70, 10, 60000000),
(19, 16, 2021, 'Non-residential building renovated ', 71, 1, 200000),
(20, 18, 2022, 'Improved dairy breed for increased milk production ', 107, 15000, 15000000),
(21, 18, 2022, 'Notifiable desease controlled ', 108, 200000, 16000000),
(22, 18, 2022, 'Incident of Vector-borne desease reduced ', 109, 78000, 28000000),
(23, 18, 2022, 'Tick controlled ', 110, 78000, 6000000),
(24, 18, 2022, 'Tick controlled', 111, 80008, 7000000),
(25, 20, 2022, 'Output 1', 23, 1000, 1000),
(26, 21, 2022, 'Output 1', 23, 1000, 1000),
(27, 22, 2022, 'Output 1', 23, 1000, 1000),
(28, 23, 2022, 'Output 1', 23, 1000, 1000),
(29, 24, 2022, 'Output 1', 23, 1000, 1000),
(30, 25, 2022, 'Output 1', 23, 1000, 1000),
(31, 26, 2021, 'Output', 2, 200, 100),
(32, 27, 2021, 'Output', 2, 200, 100),
(33, 35, 2021, 'OOutput 1', 2, 200, 1000),
(34, 36, 2021, 'Output', 2, 100, 1000),
(43, 37, 2022, 'Output 12', 2, 1000, 100000),
(36, 38, 2021, 'Output', 2, 1000, 200000),
(37, 39, 2022, 'Ouptut 1', 2, 1000, 1000),
(38, 40, 2022, 'Ouptut 1', 2, 1000, 1000),
(39, 41, 2022, 'Ouptut 1', 2, 1000, 1000),
(40, 42, 2022, 'Ouptut 1', 2, 1000, 1000),
(41, 43, 2021, 'Borehole drilled and equiped ', 2, 3, 20000000),
(44, 44, 2021, 'TEST', 2, 20, 8000000),
(45, 45, 2022, 'TEST', 2, 6, 7808000),
(46, 47, 2021, 'Number of boreholes drilled and equiped ABC', 2, 5, 3000000),
(48, 48, 2022, 'OUTPUTTEST', 2, 30, 300000000),
(49, 49, 2024, 'Dams Constructed', 1, 50, 100000000),
(50, 49, 2025, 'Dams Constructed', 1, 50, 100000000),
(51, 49, 2026, 'Dams Constructed', 1, 50, 100000000),
(52, 49, 2027, 'Dams Constructed', 1, 50, 100000000),
(53, 49, 2028, 'Dams Constructed', 1, 50, 100000000),
(54, 49, 2024, 'Boreholes drilled ', 2, 100, 100000000),
(55, 49, 2025, 'Boreholes drilled ', 2, 50, 100000000),
(56, 49, 2026, 'Boreholes drilled ', 2, 50, 100000000),
(57, 49, 2027, 'Boreholes drilled ', 2, 50, 100000000),
(58, 49, 2028, 'Boreholes drilled ', 2, 50, 100000000),
(59, 50, 2022, 'Bridges constructed', 17, 1, 20000000),
(60, 51, 2021, 'Title deeds issued', 135, 100, 100000000),
(61, 52, 2021, 'Boreholes drilled', 2, 10, 50000000),
(62, 53, 2021, 'Dams constructed', 1, 5, 50000000),
(63, 54, 2021, 'New Bridges Constructed ', 17, 40, 800000000),
(64, 54, 2021, 'New road constructed to bitumen standards', 18, 100, 1200000000),
(65, 54, 2021, 'Gravelled road', 43, 200, 700000000),
(66, 55, 2021, 'ECDE classrooms constructed', 144, 100, 100000000),
(67, 56, 2021, 'Water Pans developed ', 12, 15, 300000000),
(68, 57, 2022, 'test', 132, 20, 1000000),
(69, 58, 2022, 'Softwares deployed', 147, 4, 50000000),
(70, 59, 2021, 'Classrooms Constructed', 144, 50, 100000000),
(71, 60, 2022, 'Boreholes drilled and equiped', 2, 20, 50000000),
(72, 61, 2021, 'Dams', 1, 5, 600000000),
(73, 62, 2021, 'Condoms distributed', 151, 10000, 50000000),
(74, 62, 2022, 'Condoms distributed', 151, 10000, 50000000),
(75, 63, 2021, 'Classrooms Constructed', 144, 50, 50000000),
(76, 63, 2022, 'Classrooms Constructed', 144, 50, 50000000),
(77, 64, 2021, 'Dozzers Purchased ', 155, 6, 50000000),
(78, 64, 2021, 'Tippers Purchased', 154, 3, 30000000),
(79, 64, 2021, 'Water Master Purchased ', 159, 3, 10000000),
(80, 64, 2021, 'Driling Rigs Purchased ', 158, 3, 20000000),
(81, 64, 2021, 'Excavators Purchased ', 156, 1, 6000000),
(82, 65, 2021, 'Chickens distributed', 163, 10000, 20000000),
(83, 65, 2022, 'Chickens distributed', 163, 10000, 20000000),
(84, 65, 2023, 'Chickens distributed', 163, 10000, 20000000),
(85, 65, 2024, 'Chickens distributed', 163, 10000, 20000000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_progdetails_history`
--

CREATE TABLE `tbl_progdetails_history` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `year` int NOT NULL,
  `output` varchar(255) NOT NULL,
  `indicator` int NOT NULL,
  `target` int NOT NULL,
  `budget` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_progdetails_history`
--

INSERT INTO `tbl_progdetails_history` (`id`, `progid`, `year`, `output`, `indicator`, `target`, `budget`) VALUES
(1, 2, 2021, 'Tarmacked Road', 18, 16, 8200000000),
(2, 2, 2021, 'Tarmacked Road', 18, 60, 8200000000),
(3, 2, 2021, 'Tarmacked Road', 18, 60, 8200000000),
(4, 2, 2021, 'Culvert', 19, 89, 30000000),
(5, 2, 2021, 'Bridge', 17, 30, 1500000000),
(6, 1, 2021, 'Bridge', 17, 30, 1050000000),
(7, 6, 2021, 'Cereal stores constructed', 23, 5, 50000000),
(8, 7, 2021, 'Parking Bay', 21, 30, 59000000),
(9, 7, 2022, 'Parking Bay', 21, 45, 78000000),
(10, 7, 2021, 'parking Bay', 26, 30, 40000000),
(11, 7, 2022, 'parking Bay', 26, 20, 25000000),
(12, 5, 2021, 'Boreholes drilled and equiped', 2, 50, 200000000),
(27, 48, 2022, 'TEST', 2, 5, 4000000000),
(26, 37, 2022, 'Output 12', 2, 1000, 100000),
(25, 37, 2022, 'Output 12', 2, 1000, 100000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programs`
--

CREATE TABLE `tbl_programs` (
  `progid` int NOT NULL,
  `progname` varchar(100) NOT NULL,
  `problem_statement` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `description` text NOT NULL,
  `strategic_plan` int DEFAULT NULL,
  `strategic_obj` int DEFAULT NULL,
  `progstrategy` int DEFAULT NULL,
  `projsector` int NOT NULL,
  `projdept` int NOT NULL,
  `budget` double DEFAULT NULL,
  `syear` int NOT NULL,
  `years` int NOT NULL,
  `program_type` int NOT NULL COMMENT '1 is for strategic plan programs while 0 is for independent  programs',
  `createdby` varchar(100) NOT NULL,
  `datecreated` date NOT NULL,
  `modifiedby` varchar(100) DEFAULT NULL,
  `datemodified` date DEFAULT NULL,
  `deleted` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_programs`
--

INSERT INTO `tbl_programs` (`progid`, `progname`, `problem_statement`, `description`, `strategic_plan`, `strategic_obj`, `progstrategy`, `projsector`, `projdept`, `budget`, `syear`, `years`, `program_type`, `createdby`, `datecreated`, `modifiedby`, `datemodified`, `deleted`) VALUES
(1, 'Transport Infrastructure ', 'Lack of boda boda shades ', '<p>Construction of boda boda shades to provide shelter to Boda boda transporters and clients</p>', 1, 1, NULL, 15, 20, NULL, 2021, 1, 0, '3', '2022-01-15', 'admin0', '2022-01-16', 0),
(2, 'Road Infrastructure', 'Poor road infrastructure ', '', 1, 1, NULL, 15, 19, NULL, 2021, 1, 0, '3', '2022-01-15', 'admin0', '2022-01-16', 0),
(5, 'Water and Sanitation Deveopment ', 'Inadequate water supply', '<p>Water shortage will be addressed</p>', 1, 5, NULL, 9, 23, NULL, 2021, 1, 0, '3', '2022-01-16', 'admin0', '2022-02-14', 0),
(6, 'Cereals Program', 'Preservation of produce', '', 1, 2, NULL, 1, 2, NULL, 2021, 1, 0, '3', '2022-01-16', 'Admin0', '2022-01-16', 0),
(7, 'Parking Bays ', 'insufficient Parking Bays ', '<p>test</p>', 1, 1, NULL, 15, 20, NULL, 2021, 2, 0, '3', '2022-01-24', 'admin0', '2022-01-24', 0),
(8, 'Automation of Systems', 'Lack of tracking sytems', '<p>test</p>', 1, 1, NULL, 10, 18, NULL, 2021, 1, 0, '3', '2022-01-28', NULL, NULL, 0),
(9, 'Curative and Rehabilitative services', 'test', '<p>test</p>', 1, 4, NULL, 12, 24, NULL, 2021, 1, 0, '3', '2022-02-10', 'admin0', '2022-02-10', 0),
(11, 'Preventive, Promotive and , RMNCAH Services', 'Preventive, Promotive and , RMNCAH Services', '', 1, 4, NULL, 12, 24, NULL, 2021, 1, 0, '3', '2022-02-12', NULL, NULL, 0),
(12, 'General Administration and Support Services', 'General Administration and Support Services', '', 1, 4, NULL, 12, 24, NULL, 2021, 1, 0, '3', '2022-02-12', NULL, NULL, 0),
(13, 'Programme Tester', 'xxxxxxxxxxxxxxxxxxxxxxxxxxx', '<p>xxxxxxxxxxxxxxxxxxxxxxxx</p>', 1, 1, NULL, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-13', NULL, NULL, 0),
(14, 'Land Managenent and administration ', 'Land admin', '', 1, 16, NULL, 13, 28, NULL, 2021, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(15, 'Survey Services ', 'test', '', 1, 17, NULL, 13, 28, NULL, 2021, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(16, 'Housing Services', 'Mushrooming informal settlements which is threat to security', '', 1, 18, NULL, 13, 28, NULL, 2021, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(17, 'Fisheries Production', 'test', '', 1, 20, NULL, 1, 3, NULL, 2022, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(18, 'Veterinary services ', 'rampant deseases', '', 1, 21, NULL, 1, 3, NULL, 2022, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(19, 'test', 'test', '<p>test</p>', 0, 0, 0, 1, 2, NULL, 2022, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(20, 'test', 'test', '<p>test</p>', 0, 0, 0, 1, 2, NULL, 2022, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(21, 'test', 'test', '<p>test</p>', 0, 0, 0, 1, 2, NULL, 2022, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(22, 'test', 'test', '<p>test</p>', 0, 0, 0, 1, 2, NULL, 2022, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(23, 'test', 'test', '<p>test</p>', 0, 0, 0, 1, 2, NULL, 2022, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(24, 'test', 'test', '<p>test</p>', 0, 0, 0, 1, 2, NULL, 2022, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(25, 'test', 'test', '<p>test</p>', 0, 0, 0, 1, 2, NULL, 2022, 1, 0, '3', '2022-02-15', NULL, NULL, 0),
(26, 'Eleanor Hudson', 'Aute non ex nisi rep', '<p>test</p>', 0, 0, 0, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(27, 'Eleanor Hudson', 'Aute non ex nisi rep', '<p>test</p>', 0, 0, 0, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(28, 'Meghan Mcgee', 'Provident provident', '<p>test`</p>', NULL, NULL, 9, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(29, 'Hanae Montoya', 'Qui dolore et quae i', '<p>Test</p>', 1, NULL, 2, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(30, 'Hanae Montoya', 'Qui dolore et quae i', '<p>Test</p>', 1, 2, 2, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(31, 'Hanae Montoya', 'Qui dolore et quae i', '<p>Test</p>', 1, 2, 2, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(32, 'Hanae Montoya', 'Qui dolore et quae i', '<p>Test</p>', 1, 2, 2, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(33, 'Hanae Montoya', 'Qui dolore et quae i', '<p>Test</p>', 1, 2, 2, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(34, 'Hanae Montoya', 'Qui dolore et quae i', '<p>Test</p>', 1, 2, 2, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(35, 'Hanae Montoya', 'Qui dolore et quae i', '<p>Test</p>', 1, 2, 2, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(36, 'Testing program', 'Voluptatem et debiti', '<p>Testing</p>', 1, 16, 23, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(37, 'Testing 2 Indipendent ', 'Ipsum hic enim poss', '<p>testing</p>', 0, 0, 0, 9, 23, NULL, 2022, 1, 0, '3', '2022-02-17', 'admin0', '2022-02-17', 0),
(38, 'Indipendent strategic plan ', 'Tempore autem non l', '<p>testing&nbsp;</p>', 1, 18, 25, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(39, 'Strategic plan program testing ', 'Officia dolor consec', '<p>testing&nbsp;</p>', 1, 1, 8, 9, 23, NULL, 2022, 1, 1, '3', '2022-02-17', NULL, NULL, 0),
(40, 'Strategic plan program testing ', 'Officia dolor consec', '<p>testing&nbsp;</p>', 1, 1, 8, 9, 23, NULL, 2022, 1, 1, '3', '2022-02-17', NULL, NULL, 0),
(41, 'Strategic plan program testing ', 'Officia dolor consec', '<p>testing&nbsp;</p>', 1, 1, 8, 9, 23, NULL, 2022, 1, 1, '3', '2022-02-17', NULL, NULL, 0),
(42, 'Strategic plan program testing ', 'Officia dolor consec', '<p>testing&nbsp;</p>', 1, 1, 8, 9, 23, NULL, 2022, 1, 1, '3', '2022-02-17', NULL, NULL, 0),
(43, 'COVID 19 Protocol', 'mitigation/control measures ', '', 0, 0, 0, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(44, 'TESTATESTA', 'TEST', '<p>tEST</p>', 0, 0, 0, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(45, 'NAKURU TEST', 'TEST', '', 1, 1, 10, 9, 23, NULL, 2022, 1, 1, '3', '2022-02-17', NULL, NULL, 0),
(46, 'PRGRAM IND', 'TEST', '', 1, 5, 5, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-17', NULL, NULL, 0),
(47, 'Madaline Beach', 'Ea dolorum eos facer', '<p>testing</p>', 0, 0, 0, 9, 23, NULL, 2021, 1, 0, '3', '2022-02-24', NULL, NULL, 0),
(48, 'TEST/15/03', 'TEST', '', 1, 2, 2, 9, 23, NULL, 2022, 1, 1, '3', '2022-03-15', 'admin0', '2022-03-15', 0),
(49, 'PROG1', 'Testing', '<p>Testing</p>', 2, 23, 31, 9, 23, NULL, 2024, 5, 1, '3', '2022-03-19', NULL, NULL, 0),
(50, 'Tester Prog1', 'Tester', '<p>TesterTester</p>', 1, 25, 35, 15, 19, NULL, 2022, 1, 1, '3', '2022-03-20', NULL, NULL, 0),
(51, 'Testa', 'Tester', '<p>Tester</p>', 1, 25, 35, 13, 28, NULL, 2021, 1, 1, '3', '2022-03-20', NULL, NULL, 0),
(52, 'IndepTest', 'xxxxxxxxxxxxxxxxxxxxxxxxxx', '<p>xxxxxxxxxxxxxxxxxxxxxxxx</p>', 0, 0, 0, 9, 23, NULL, 2022, 1, 0, '3', '2022-03-20', NULL, NULL, 0),
(53, 'Prog5', 'Problem', '<p>Testing</p>', 1, 26, 36, 9, 23, NULL, 2022, 1, 1, '3', '2022-03-23', NULL, NULL, 0),
(54, 'Road and transport infrastructure development ', 'Poor road connectivity ', '<p>test</p>', 1, 27, 37, 15, 19, NULL, 2021, 1, 1, '3', '2022-03-23', NULL, NULL, 0),
(55, 'ECDE Education', 'No facilities for increasing number ECDE pupils', '<p>ECDE classrooms</p>', 1, 6, 12, 5, 6, NULL, 2022, 1, 1, '3', '2022-03-24', NULL, NULL, 0),
(56, 'Water Programmes', 'test', '', 1, 5, 5, 9, 23, NULL, 2021, 1, 1, '3', '2022-03-25', NULL, NULL, 0),
(57, 'tESTER', 'TEST', '<p>testing</p>', 1, 2, 2, 13, 28, NULL, 2022, 1, 1, '3', '2022-03-25', NULL, NULL, 0),
(58, 'Prog8', 'PROBLEM', '', 1, 30, 40, 11, 30, NULL, 2022, 1, 1, '3', '2022-03-25', NULL, NULL, 0),
(59, 'IndepProgram1', 'problem', '<p>Description</p>', 0, 0, 0, 5, 6, NULL, 2021, 1, 0, '3', '2022-03-26', NULL, NULL, 0),
(60, 'Pro1', 'Problem', '<p>Description</p>', 0, 0, 0, 9, 23, NULL, 2022, 1, 0, '3', '2022-03-28', NULL, NULL, 0),
(61, 'PROGTEST', 'test', '', 1, 4, 4, 9, 23, NULL, 2021, 1, 1, '3', '2022-03-29', NULL, NULL, 0),
(62, 'Preventive ', 'Testing', '', 0, 0, 0, 12, 24, NULL, 2021, 2, 0, '3', '2022-03-30', NULL, NULL, 0),
(63, 'Early Childhood Education Programme', 'Congestion', '<p>ECDE Program</p>', 0, 0, 0, 5, 6, NULL, 2021, 2, 0, '3', '2022-04-01', NULL, NULL, 0),
(64, 'Water Equipment and Machinery', 'test', '', 1, 5, 5, 9, 23, NULL, 2021, 1, 1, '3', '2022-04-02', NULL, NULL, 0),
(65, 'Inua Mama na Kuku', 'Inua Mama na Kuku', '', 0, 0, 0, 1, 2, NULL, 2021, 4, 0, '3', '2022-04-04', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programs_based_budget`
--

CREATE TABLE `tbl_programs_based_budget` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `opid` int NOT NULL,
  `indid` int NOT NULL,
  `finyear` int NOT NULL,
  `budget` double NOT NULL,
  `target` float NOT NULL,
  `created_by` int NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_programs_based_budget`
--

INSERT INTO `tbl_programs_based_budget` (`id`, `progid`, `opid`, `indid`, `finyear`, `budget`, `target`, `created_by`, `date_created`) VALUES
(25, 2, 2, 18, 2021, 85000000000, 250, 1, '2022-02-15'),
(26, 2, 4, 19, 2021, 50000000, 10, 1, '2022-02-15'),
(27, 2, 5, 17, 2021, 4500000000, 5, 1, '2022-02-15'),
(28, 2, 3, 18, 2022, 50000000, 100, 1, '2022-02-16'),
(31, 50, 59, 17, 2022, 15000000, 1, 1, '2022-03-20'),
(32, 51, 60, 135, 2021, 9000000, 40, 1, '2022-03-20'),
(33, 53, 62, 1, 2021, 50000000, 5, 1, '2022-03-23'),
(34, 54, 63, 17, 2021, 300000000, 20, 1, '2022-03-23'),
(35, 54, 64, 18, 2021, 200000000, 100, 1, '2022-03-23'),
(36, 54, 65, 43, 2021, 200000000, 100, 1, '2022-03-23'),
(37, 55, 66, 144, 2021, 10000000, 4, 1, '2022-03-24'),
(39, 5, 12, 2, 2021, 100000000, 2, 1, '2022-03-26'),
(40, 6, 7, 23, 2021, 2222222, 2, 1, '2022-03-26'),
(42, 5, 12, 2, 2021, 150000000, 40, 1, '2022-03-26'),
(44, 5, 12, 2, 2021, 150000000, 40, 1, '2022-03-26'),
(46, 1, 6, 17, 2021, 100000000, 1, 1, '2022-03-26'),
(47, 56, 67, 12, 2021, 250000000, 10, 1, '2022-03-26'),
(48, 7, 8, 21, 2021, 150000000, 40, 1, '2022-03-26'),
(50, 61, 72, 1, 2021, 550000000, 4, 1, '2022-03-29'),
(56, 64, 77, 155, 2021, 50000000, 6, 1, '2022-04-03'),
(57, 64, 78, 154, 2021, 20000000, 3, 1, '2022-04-03'),
(58, 64, 79, 159, 2021, 10000000, 3, 1, '2022-04-03'),
(59, 64, 80, 158, 2021, 20000000, 3, 1, '2022-04-03'),
(60, 64, 81, 156, 2021, 6000000, 1, 1, '2022-04-03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programs_quarterly_targets`
--

CREATE TABLE `tbl_programs_quarterly_targets` (
  `id` int NOT NULL,
  `pbbid` int NOT NULL,
  `progid` int NOT NULL,
  `opid` int NOT NULL,
  `indid` int NOT NULL,
  `year` int NOT NULL,
  `Q1` float NOT NULL,
  `Q2` float NOT NULL,
  `Q3` float NOT NULL,
  `Q4` float NOT NULL,
  `created_by` int NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_programs_quarterly_targets`
--

INSERT INTO `tbl_programs_quarterly_targets` (`id`, `pbbid`, `progid`, `opid`, `indid`, `year`, `Q1`, `Q2`, `Q3`, `Q4`, `created_by`, `date_created`) VALUES
(1, 25, 2, 2, 18, 2021, 65, 65, 60, 60, 1, '2022-02-15'),
(2, 26, 2, 4, 19, 2021, 3, 3, 2, 2, 1, '2022-02-15'),
(3, 27, 2, 5, 17, 2021, 2, 1, 1, 1, 1, '2022-02-15'),
(4, 31, 50, 59, 17, 2022, 1, 1, 1, 1, 1, '2022-03-20'),
(5, 34, 54, 63, 17, 2021, 5, 5, 5, 5, 1, '2022-03-24'),
(6, 35, 54, 64, 18, 2021, 25, 25, 25, 25, 1, '2022-03-24'),
(7, 36, 54, 65, 43, 2021, 25, 25, 25, 25, 1, '2022-03-24'),
(11, 10, 56, 67, 12, 2021, 3, 2, 2, 3, 1, '2022-03-26'),
(12, 50, 61, 72, 1, 2021, 1, 1, 1, 1, 1, '2022-03-30'),
(13, 56, 64, 77, 155, 2021, 0, 0, 0, 6, 1, '2022-04-03'),
(14, 57, 64, 78, 154, 2021, 0, 3, 0, 0, 1, '2022-04-03'),
(15, 58, 64, 79, 159, 2021, 0, 0, 3, 0, 1, '2022-04-03'),
(16, 59, 64, 80, 158, 2021, 0, 0, 0, 3, 1, '2022-04-03'),
(17, 60, 64, 81, 156, 2021, 0, 0, 0, 1, 1, '2022-04-03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projcountyadmin`
--

CREATE TABLE `tbl_projcountyadmin` (
  `ptid` int NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `designation` int NOT NULL,
  `level1` int DEFAULT NULL,
  `level2` int NOT NULL DEFAULT '0',
  `level3` int NOT NULL DEFAULT '0',
  `floc` varchar(300) DEFAULT 'uploads/passport.jpg',
  `filename` varchar(300) DEFAULT NULL,
  `ftype` varchar(300) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `email` varchar(300) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `disabled` enum('0','1') NOT NULL DEFAULT '0',
  `createdby` int NOT NULL,
  `datecreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projdetails`
--

CREATE TABLE `tbl_projdetails` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `outputid` int NOT NULL,
  `projid` int NOT NULL,
  `year` int NOT NULL,
  `target` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projectrisks`
--

CREATE TABLE `tbl_projectrisks` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int DEFAULT NULL,
  `rskid` int NOT NULL,
  `opid` int DEFAULT NULL,
  `type` int NOT NULL,
  `assumption` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projectrisks`
--

INSERT INTO `tbl_projectrisks` (`id`, `projid`, `outputid`, `rskid`, `opid`, `type`, `assumption`) VALUES
(2, 8, 63, 24, NULL, 3, 'Timely disbursement of funds'),
(5, 8, NULL, 26, NULL, 2, 'Favourable climate'),
(7, 24, 100, 24, NULL, 3, 'funding'),
(8, 24, NULL, 31, NULL, 2, 'utilization'),
(9, 27, 105, 24, NULL, 3, 'Adequate funds will be released in time '),
(10, 27, 105, 26, NULL, 3, 'Rainy season will not affect the work progress'),
(11, 27, 105, 24, NULL, 3, 'Adequate funds will be released in time'),
(12, 27, 105, 26, NULL, 3, 'Rainy season will not affect the work progress'),
(13, 27, 105, 26, NULL, 3, 'Test'),
(14, 27, 105, 26, NULL, 3, 'There will be no interruption by the heavy rains '),
(15, 27, 105, 24, NULL, 3, 'Adequate funds will be released in time'),
(19, 27, NULL, 29, NULL, 2, 'test'),
(20, 35, 131, 28, NULL, 3, 'Compliance'),
(21, 35, NULL, 31, NULL, 2, 'Utilization'),
(22, 35, 131, 30, NULL, 3, 'Compliance'),
(23, 35, NULL, 31, NULL, 2, 'Beneficiaries will come out to apply for registration'),
(24, 35, 131, 28, NULL, 3, 'Compliance'),
(25, 35, NULL, 31, NULL, 2, 'Utilization'),
(26, 38, 134, 24, NULL, 3, 'Timely disbursement of funds'),
(27, 38, NULL, 31, NULL, 2, 'residents utilizing the road'),
(28, 39, 135, 24, NULL, 3, 'Funding'),
(29, 39, NULL, 31, NULL, 2, 'utilization'),
(34, 22, 96, 26, NULL, 3, 'Eum sint neque velit'),
(38, 44, 143, 24, NULL, 3, 'Funds will be availed in time '),
(39, 44, NULL, 31, NULL, 2, 'The project will coverage of targeted '),
(40, 22, 96, 28, NULL, 3, 'test'),
(43, 45, 151, 24, NULL, 3, 'test'),
(44, 45, NULL, 31, NULL, 2, 'testing'),
(45, 54, 169, 24, NULL, 3, 'Funds will be availed in time '),
(46, 49, 164, 24, NULL, 3, 'Timely disbursement of funds'),
(47, 49, NULL, 31, NULL, 2, 'Parents will send their children to school'),
(48, 22, NULL, 24, NULL, 2, 'Test'),
(49, 55, 170, 24, NULL, 3, 'Timely disbursement of funds'),
(50, 55, NULL, 29, NULL, 2, 'Women employing skills on poultry management');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projects`
--

CREATE TABLE `tbl_projects` (
  `projid` int NOT NULL,
  `progid` int DEFAULT NULL,
  `projcode` varchar(255) NOT NULL,
  `projname` varchar(300) NOT NULL,
  `projdesc` text,
  `projstatement` text,
  `projsolution` text,
  `projcase` text,
  `projfocusarea` varchar(255) DEFAULT NULL,
  `projmapping` int NOT NULL DEFAULT '1',
  `projinspection` int NOT NULL DEFAULT '1',
  `projevaluation` int NOT NULL DEFAULT '0',
  `projcategory` enum('1','2') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1' COMMENT '1 is In House while 2 is Contractor',
  `projbigfouragenda` int DEFAULT NULL,
  `projfscyear` int DEFAULT NULL,
  `projfinancier` int DEFAULT NULL,
  `projduration` int NOT NULL,
  `projstatus` int NOT NULL DEFAULT '0',
  `projplanstatus` int NOT NULL DEFAULT '0',
  `projchangedstatus` varchar(100) DEFAULT NULL,
  `projsectorname` varchar(255) DEFAULT NULL,
  `projappdate` date DEFAULT NULL,
  `projtender` int DEFAULT NULL,
  `projcontractor` int DEFAULT NULL,
  `projcommunity` varchar(255) NOT NULL,
  `projlga` varchar(255) NOT NULL,
  `projstate` varchar(255) NOT NULL,
  `projlocation` varchar(250) DEFAULT NULL,
  `projcounty` int DEFAULT NULL,
  `projbudget` double NOT NULL,
  `projcost` decimal(19,2) DEFAULT '0.00',
  `projtype` varchar(255) DEFAULT NULL,
  `projwaypoints` int DEFAULT NULL,
  `mapped` int NOT NULL DEFAULT '0',
  `projstartdate` date DEFAULT NULL,
  `projenddate` date DEFAULT NULL,
  `projmonitoringdate` date DEFAULT NULL,
  `outcome` text,
  `outcome_indicator` int DEFAULT NULL,
  `mne_responsible` int DEFAULT NULL,
  `mne_report_users` varchar(255) DEFAULT NULL,
  `projstatuschangereason` text,
  `projstatusrestorereason` text,
  `projstage` int NOT NULL DEFAULT '1',
  `projmestage` int NOT NULL DEFAULT '1',
  `projevaluate` int NOT NULL DEFAULT '0',
  `dateentered` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `projdatecompleted` date DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `date_deleted` datetime DEFAULT NULL,
  `deleted_by` varchar(250) DEFAULT NULL,
  `approved_by` varchar(100) DEFAULT NULL,
  `approved_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projects`
--

INSERT INTO `tbl_projects` (`projid`, `progid`, `projcode`, `projname`, `projdesc`, `projstatement`, `projsolution`, `projcase`, `projfocusarea`, `projmapping`, `projinspection`, `projevaluation`, `projcategory`, `projbigfouragenda`, `projfscyear`, `projfinancier`, `projduration`, `projstatus`, `projplanstatus`, `projchangedstatus`, `projsectorname`, `projappdate`, `projtender`, `projcontractor`, `projcommunity`, `projlga`, `projstate`, `projlocation`, `projcounty`, `projbudget`, `projcost`, `projtype`, `projwaypoints`, `mapped`, `projstartdate`, `projenddate`, `projmonitoringdate`, `outcome`, `outcome_indicator`, `mne_responsible`, `mne_report_users`, `projstatuschangereason`, `projstatusrestorereason`, `projstage`, `projmestage`, `projevaluate`, `projdatecompleted`, `user_name`, `date_created`, `updated_by`, `date_updated`, `deleted`, `date_deleted`, `deleted_by`, `approved_by`, `approved_date`) VALUES
(1, 2, '2021', 'Kuinet- Kapsuswa Road ', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 278, 0, 1, NULL, NULL, NULL, NULL, NULL, '303', '323', '366,367', NULL, NULL, 0, '5000000.00', 'New', NULL, 0, '2021-07-01', '2021-07-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-01-16', 'admin0', '2022-01-16', '0', NULL, NULL, 'admin0', '2022-01-18'),
(2, 1, '2022/01/BW002', 'Labuywet Bridge ', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 150, 0, 1, NULL, NULL, NULL, NULL, NULL, '305', '332', '370', NULL, NULL, 0, '50000000.00', 'New', NULL, 0, '2021-07-01', '2021-11-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-01-16', NULL, NULL, '0', NULL, NULL, 'Admin0', '2022-01-16'),
(3, 2, '2022/01/BW009', 'Construction of Kiborokwo- Kapngetuny Bridge', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 120, 0, 1, NULL, NULL, NULL, NULL, NULL, '303', '325', '365', NULL, NULL, 0, '49000000.00', 'New', NULL, 0, '2021-07-01', '2021-10-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-01-16', NULL, NULL, '0', NULL, NULL, 'Admin0', '2022-01-19'),
(4, 2, '2022/01/BW010', 'Construction of Kongnyalil (B) Bridge', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 130, 0, 1, NULL, NULL, NULL, NULL, NULL, '304', '334', '402', NULL, NULL, 0, '17000000.00', 'New', NULL, 0, '2021-07-01', '2021-11-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-01-16', NULL, NULL, '0', NULL, NULL, 'Admin0', '2022-01-19'),
(5, 5, '678900', 'Drilling of boreholes and equipping them', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 364, 0, 1, NULL, NULL, NULL, NULL, NULL, '307', '316', '411,412,413', NULL, NULL, 0, '100000000.00', 'New', NULL, 0, '2021-07-01', '2022-06-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 1, 0, NULL, '1', '2022-01-16', NULL, NULL, '0', NULL, NULL, 'Admin0', '2022-01-16'),
(6, 5, '67744', 'Mafuta borehole water project ziwa ward soy sub County', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 300, 0, 1, NULL, NULL, NULL, NULL, NULL, '303', '327', '361', NULL, NULL, 0, '12000000.00', 'New', NULL, 0, '2021-07-01', '2022-04-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 1, 0, NULL, '1', '2022-01-16', NULL, NULL, '0', NULL, NULL, 'Admin0', '2022-01-16'),
(7, 6, 'CP90', 'Construction of cereal stores-Tuiyo co-operative society', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 300, 0, 1, NULL, NULL, NULL, 3, 3, '307', '312', '383', NULL, NULL, 0, '15000000.00', 'New', NULL, 0, '2021-07-01', '2022-04-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 1, 0, NULL, '1', '2022-01-16', NULL, NULL, '0', NULL, NULL, 'Admin0', '2022-01-16'),
(8, 6, 'CP9000', 'Construction of store-Mafuta cooperative society', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', 2, 4, NULL, 300, 5, 1, '4', NULL, NULL, 1, 1, '303', '327', '361', NULL, NULL, 0, '10000000.00', 'New', NULL, 0, '2021-07-01', '2022-04-26', '2021-07-13', 'Increased accessibility of storage facilities by farmers', 25, 12, '2,3,4,5,6,15', NULL, '<p>Test</p>', 10, 1, 0, NULL, '1', '2022-01-16', 'Admin0', '2022-01-16', '0', '2022-03-24 10:19:32', NULL, 'Admin0', '2022-01-16'),
(9, 2, '2021/12/RW030', 'Chepkonginy- Kiburer Road ', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 300, 0, 1, NULL, NULL, NULL, NULL, NULL, '306', '310', '354', NULL, NULL, 0, '64000000.00', 'New', NULL, 0, '2021-07-01', '2022-04-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 1, 0, NULL, '1', '2022-01-18', NULL, NULL, '0', NULL, NULL, 'admin0', '2022-01-18'),
(10, 5, '8888888888', 'Construction of Water treatment plant', NULL, NULL, NULL, NULL, NULL, 1, 0, 0, '2', NULL, 4, NULL, 200, 0, 1, NULL, NULL, NULL, NULL, NULL, '304', '334', '401', NULL, NULL, 0, '2000000.00', 'New', NULL, 0, '2021-07-01', '2022-01-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 1, 0, NULL, '1', '2022-01-19', NULL, NULL, '0', NULL, NULL, 'Admin0', '2022-01-19'),
(11, 7, '2021/12/TD007', 'Kipkaren Parking Bay', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 170, 0, 1, NULL, NULL, NULL, NULL, NULL, '304', '335', '346', NULL, NULL, 0, '3000000.00', 'New', NULL, 0, '2021-07-01', '2021-12-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-01-24', NULL, NULL, '0', NULL, NULL, 'admin0', '2022-01-25'),
(12, 2, 'Quibusdam dolore inv', 'Tallulah Barker', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 100, 0, 1, NULL, NULL, NULL, NULL, NULL, '307,304,303', '313', '384', NULL, NULL, 0, '100.00', 'New', NULL, 0, '2021-07-01', '2021-10-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-01-28', NULL, NULL, '0', NULL, NULL, 'admin0', '2022-02-21'),
(13, 2, 'Consequat Pariatur', 'Warren Fisher', NULL, NULL, NULL, NULL, NULL, 1, 0, 1, '1', NULL, 4, NULL, 100, 0, 1, NULL, NULL, NULL, NULL, NULL, '304,303,305', '325,326', '363,407', NULL, NULL, 0, '100.00', 'New', NULL, 0, '2021-07-01', '2021-10-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-01-28', NULL, NULL, '0', NULL, NULL, '1', '2022-03-28'),
(14, 2, 'Voluptatem aut quasi', 'Giacomo Dillard', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 300, 0, 0, NULL, NULL, NULL, NULL, NULL, '304,303', '325', '363,364', NULL, NULL, 0, '0.00', 'New', NULL, 0, '2021-07-01', '2022-04-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-01-28', 'admin0', '2022-01-28', '0', NULL, NULL, '0', NULL),
(15, 2, '009/2021', 'Cheukta Bridge ', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 120, 0, 1, NULL, NULL, NULL, NULL, NULL, '303', '325', '364', NULL, NULL, 0, '14000000.00', 'New', NULL, 0, '2021-07-01', '2021-10-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 1, 0, NULL, '1', '2022-01-28', NULL, NULL, '0', NULL, NULL, 'admin0', '2022-02-22'),
(16, 8, '20', 'Standardized CIMES', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, '2', NULL, 4, NULL, 120, 0, 1, NULL, NULL, NULL, NULL, NULL, '305', '328', '380', NULL, NULL, 0, '15000000.00', 'New', NULL, 0, '2021-07-01', '2021-10-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 0, NULL, '1', '2022-01-28', NULL, NULL, '0', NULL, NULL, '1', '2022-03-30'),
(18, 2, '2021/10/rwc056', 'Kapkures- Matunda Road', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 130, 0, 1, NULL, NULL, NULL, NULL, NULL, '303', '322', '357,358', NULL, NULL, 0, '0.00', 'New', NULL, 0, '2021-07-01', '2021-11-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-02-01', NULL, NULL, '0', NULL, NULL, '1', '2022-03-14'),
(20, 2, '2020/005/RCP09', 'Koisagat -Kesses Road', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 178, 0, 0, NULL, NULL, NULL, NULL, NULL, '308', '319', '396,398', NULL, NULL, 0, '0.00', 'New', NULL, 0, '2021-07-01', '2021-12-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-02-02', NULL, NULL, '0', NULL, NULL, '0', NULL),
(21, 2, '090810', 'Kamaua -Elgeyo Border Road', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '1', NULL, 4, NULL, 140, 0, 1, NULL, NULL, NULL, NULL, NULL, '304', '338', '340,341', NULL, NULL, 0, '17000000.00', 'New', NULL, 0, '2021-07-01', '2021-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 1, 0, NULL, '1', '2022-02-02', NULL, NULL, '0', NULL, NULL, 'admin0', '2022-02-02'),
(22, 2, '2020/01/RW012', 'Sergoit Center Culverts ', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 130, 11, 1, NULL, NULL, NULL, 6, 2, '304', '337', '404', NULL, NULL, 0, '2000000.00', 'New', NULL, 1, '2021-07-01', '2021-11-07', NULL, 'Improved drainage at the center', 153, 15, '18', NULL, NULL, 10, 1, 0, NULL, '1', '2022-02-02', NULL, NULL, '0', NULL, NULL, 'admin0', '2022-02-02'),
(23, 2, '2021/01/RCW008', 'Chepkatet Bridge ', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 90, 0, 1, NULL, NULL, NULL, NULL, NULL, '307', '312', '383', NULL, NULL, 0, '7000000.00', 'New', NULL, 0, '2021-07-01', '2021-09-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-02-08', NULL, NULL, '0', NULL, NULL, 'Admin0', '2022-02-13'),
(24, 13, '7000', 'Dam', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 200, 0, 1, NULL, NULL, NULL, 2, 2, '306', '309', '350', NULL, NULL, 0, '5000000.00', 'New', NULL, 0, '2021-07-01', '2022-01-16', NULL, 'Increased water production', 7, 8, '2,3,4,5', NULL, NULL, 9, 1, 0, NULL, '1', '2022-02-13', NULL, NULL, '0', NULL, NULL, 'admin0', '2022-02-13'),
(25, 2, '2022/09/RW009', 'Chuiyat Road ', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 120, 0, 1, NULL, NULL, NULL, NULL, NULL, '308', '319', '397', NULL, NULL, 0, '4000000.00', 'New', NULL, 0, '2021-07-01', '2021-10-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-02-17', NULL, NULL, '0', NULL, NULL, 'admin0', '2022-02-17'),
(26, 47, 'Ratione ut a volupta', 'Hiroko Howell', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 63, 0, 1, NULL, NULL, NULL, NULL, NULL, '305', '333', '374', NULL, NULL, 0, '40000.00', 'New', NULL, 0, '2021-07-01', '2021-09-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 1, 0, NULL, '1', '2022-02-24', NULL, NULL, '0', NULL, NULL, '1', '2022-03-28'),
(27, 2, 'P02902', 'Moiben- Torochmoi Road', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 120, 0, 1, NULL, NULL, NULL, 5, 4, '304', '336', '342,344', NULL, NULL, 0, '5500000.00', 'New', NULL, 1, '2021-07-01', '2021-10-28', NULL, 'Increased level of access to road transport network', 131, 2, '1,2,3,4', NULL, NULL, 9, 1, 0, NULL, '1', '2022-02-25', NULL, NULL, '0', NULL, NULL, 'admin0', '2022-02-25'),
(29, 2, '090', 'Kipsomba Road ', NULL, NULL, NULL, NULL, NULL, 1, 0, 1, '2', NULL, 4, NULL, 120, 0, 1, NULL, NULL, NULL, NULL, NULL, '303', '321', '409', NULL, NULL, 0, '0.00', 'New', NULL, 0, '2021-07-01', '2021-10-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-03-15', NULL, NULL, '0', NULL, NULL, '1', '2022-03-15'),
(30, 49, '3450945', 'PROG1Proj1', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 7, NULL, 360, 0, 0, NULL, NULL, NULL, NULL, NULL, '306', '310', '352', NULL, NULL, 0, '0.00', 'New', NULL, 0, '2024-07-01', '2025-06-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-03-19', NULL, NULL, '0', NULL, NULL, NULL, NULL),
(31, 49, 'Cillum consequuntur ', 'Cairo Vaughn', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '1', NULL, 7, NULL, 400, 0, 0, NULL, NULL, NULL, NULL, NULL, '304,307,308', '313', '384', NULL, NULL, 0, '0.00', 'New', NULL, 0, '2024-07-01', '2025-08-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-03-20', NULL, NULL, '0', NULL, NULL, NULL, NULL),
(33, 49, 'Ipsam dolore ab reru', 'Ocean Pruitt', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 10, NULL, 500, 0, 0, NULL, NULL, NULL, NULL, NULL, '306,308,304,305', '333', '373', NULL, NULL, 0, '0.00', 'New', NULL, 0, '2027-07-01', '2028-11-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-03-20', 'admin0', '2022-03-20', '0', NULL, NULL, NULL, NULL),
(34, 50, '775511', 'Tester Project2', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 5, NULL, 200, 0, 0, NULL, NULL, NULL, NULL, NULL, '303', '321', '409', NULL, NULL, 0, '0.00', 'New', NULL, 0, '2022-07-01', '2023-01-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-03-20', NULL, NULL, '0', NULL, NULL, NULL, NULL),
(35, 51, '004321', 'Issuance of Title Deed', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '1', NULL, 4, NULL, 300, 11, 1, NULL, NULL, NULL, NULL, NULL, '307', '314', '389', NULL, NULL, 0, '0.00', 'New', NULL, 1, '2021-07-01', '2022-04-26', '2021-11-01', 'Increased land ownership', 138, 10, '4,18', NULL, NULL, 10, 1, 0, NULL, '1', '2022-03-20', NULL, NULL, '0', NULL, NULL, '1', '2022-03-20'),
(36, 52, '7777777777', 'Drilling of boreholes', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 5, NULL, 200, 0, 0, NULL, NULL, NULL, NULL, NULL, '303', '324', '410', NULL, NULL, 0, '0.00', 'New', NULL, 0, '2022-07-01', '2023-01-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-03-20', NULL, NULL, '0', NULL, NULL, NULL, NULL),
(38, 54, '2021/RW002', 'Kisumu ndogo- Kona Mbaya Road ', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2', NULL, 4, NULL, 130, 11, 1, '4', NULL, NULL, 7, 3, '307', '314', '389,390', NULL, NULL, 0, '0.00', 'New', NULL, 1, '2022-07-01', '2022-11-07', '2021-08-02', 'Improved road network and connectivity', 146, 10, '5', NULL, '<p>Funding availed</p>', 10, 1, 0, NULL, '1', '2022-03-23', NULL, NULL, '0', '2022-03-24 16:31:13', '1', '1', '2022-03-23'),
(39, 55, 'ECD0245899', 'Construction of ECDE Classrooms', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 300, 11, 1, NULL, NULL, NULL, 8, 3, '306', '311', '357,358', NULL, NULL, 0, '0.00', 'New', NULL, 1, '2022-07-01', '2023-04-26', NULL, 'Increased ECDE pupils enrollment rate', 145, 2, '4', NULL, NULL, 10, 1, 0, NULL, '1', '2022-03-24', NULL, NULL, '0', NULL, NULL, '1', '2022-03-24'),
(40, 56, '7272727', 'kaplemur water pan', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 360, 0, 1, NULL, NULL, NULL, NULL, NULL, '305', '333', '373', NULL, NULL, 0, '10000000.00', 'New', NULL, 1, '2022-07-01', '2023-06-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 1, 0, NULL, '1', '2022-03-25', NULL, NULL, '0', NULL, NULL, '1', '2022-03-27'),
(41, 58, '6666666666666', 'M&E Software  installed', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 90, 0, 0, NULL, NULL, NULL, NULL, NULL, '1', '2', '3', NULL, NULL, 0, '0.00', 'New', NULL, 0, '2022-07-01', '2022-09-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-03-25', NULL, NULL, '0', NULL, NULL, NULL, NULL),
(42, 59, '222222222', 'Construction of classrooms at location A', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 300, 0, 0, NULL, NULL, NULL, NULL, NULL, '303', '327', '360', NULL, NULL, 0, '0.00', 'New', NULL, 0, '2021-07-01', '2022-04-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-03-26', NULL, NULL, '0', NULL, NULL, NULL, NULL),
(43, 60, 'WTO00900', 'Drilling of boreholes at Kapseret', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 5, NULL, 280, 0, 1, NULL, NULL, NULL, NULL, NULL, '307', '313', '384,385', NULL, NULL, 0, '50000000.00', 'New', NULL, 0, '2022-07-01', '2023-04-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, NULL, '1', '2022-03-28', NULL, NULL, '0', NULL, NULL, '1', '2022-03-28'),
(44, 61, '009', 'kapteldet dam ', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 360, 4, 1, NULL, NULL, NULL, 9, 5, '306', '310', '352', NULL, NULL, 0, '30000000.00', 'New', NULL, 1, '2022-07-01', '2023-06-25', NULL, 'Increased access to clean and safe water', 152, 16, '2,3,4,5', NULL, NULL, 10, 1, 0, NULL, '1', '2022-03-29', NULL, NULL, '0', NULL, NULL, '1', '2022-03-29'),
(45, 62, '900g', 'Distribution of condoms', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, '1', NULL, 4, NULL, 60, 11, 1, NULL, NULL, NULL, NULL, NULL, '304', '335', '346,347', NULL, NULL, 0, '10000000.00', 'New', NULL, 0, '2021-07-01', '2021-08-29', NULL, 'Reduced HIV infection rate', 149, 10, '4', NULL, NULL, 10, 1, 0, NULL, '1', '2022-03-30', NULL, NULL, '0', NULL, NULL, '1', '2022-03-30'),
(46, 61, '000676', 'Kabongo Dam', NULL, NULL, NULL, NULL, NULL, 1, 0, 0, '2', NULL, 4, NULL, 364, 0, 0, NULL, NULL, NULL, NULL, NULL, '307', '315', '386', NULL, NULL, 0, '15000000.00', 'New', NULL, 0, '2021-07-01', '2022-06-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-03-31', NULL, NULL, '0', NULL, NULL, NULL, NULL),
(47, 61, '6w0w77', 'Merewet Dam', NULL, NULL, NULL, NULL, NULL, 1, 0, 0, '1', NULL, 4, NULL, 362, 0, 0, NULL, NULL, NULL, NULL, NULL, '303', '323', '366', NULL, NULL, 0, '10000000.00', 'New', NULL, 0, '2021-07-01', '2022-06-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-03-31', NULL, NULL, '0', NULL, NULL, NULL, NULL),
(48, 61, 'h092h', 'Chebororwa Dam', NULL, NULL, NULL, NULL, NULL, 1, 0, 0, '2', NULL, 4, NULL, 364, 0, 1, NULL, NULL, NULL, NULL, NULL, '304', '337', '404', NULL, NULL, 0, '24000000.00', 'New', NULL, 1, '2021-07-01', '2022-06-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 0, NULL, '1', '2022-03-31', NULL, NULL, '0', NULL, NULL, '1', '2022-03-31'),
(49, 63, '8888EA', 'Construction of classrooms', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2', NULL, 4, NULL, 700, 11, 1, NULL, NULL, NULL, 10, 4, '303', '322', '357,358', NULL, NULL, 0, '50000000.00', 'New', NULL, 1, '2021-07-01', '2023-05-31', NULL, 'Increased enrollment of ECDE pupils', 145, 16, '4', NULL, NULL, 10, 1, 0, NULL, '1', '2022-04-01', NULL, NULL, '0', NULL, NULL, '1', '2022-04-01'),
(50, 64, '2018/12001', 'Purchase of tippers', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, '1', NULL, 4, NULL, 360, 0, 1, NULL, NULL, NULL, NULL, NULL, '1', '2', '3', NULL, NULL, 0, '20000000.00', 'New', NULL, 0, '2021-07-01', '2022-06-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 1, 0, NULL, '1', '2022-04-02', NULL, NULL, '0', NULL, NULL, '1', '2022-04-03'),
(51, 64, '92jnn', 'Purchase of Excavators ', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, '1', NULL, 4, NULL, 361, 0, 1, NULL, NULL, NULL, NULL, NULL, '1', '2', '3', NULL, NULL, 0, '6000000.00', 'New', NULL, 0, '2021-07-01', '2022-06-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 0, NULL, '1', '2022-04-02', NULL, NULL, '0', NULL, NULL, '1', '2022-04-03'),
(52, 64, 'OU0028', 'Purchase of drilling rigs ', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, '1', NULL, 4, NULL, 360, 0, 0, NULL, NULL, NULL, NULL, NULL, '1', '2', '3', NULL, NULL, 0, '20000000.00', 'New', NULL, 0, '2021-07-01', '2022-06-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-04-02', NULL, NULL, '0', NULL, NULL, NULL, NULL),
(53, 64, 'ou88ut', 'Purchase of Water Master', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, '1', NULL, 4, NULL, 360, 0, 0, NULL, NULL, NULL, NULL, NULL, '1', '2', '3', NULL, NULL, 0, '10000000.00', 'New', NULL, 0, '2021-07-01', '2022-06-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, '1', '2022-04-02', NULL, NULL, '0', NULL, NULL, NULL, NULL),
(54, 64, 'OUW89', 'Purchase of Dozzers', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, '2', NULL, 4, NULL, 350, 5, 1, NULL, NULL, NULL, 11, 5, '1', '2', '3', NULL, NULL, 0, '50000000.00', 'New', NULL, 0, '2021-07-01', '2022-06-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 1, 0, NULL, '1', '2022-04-02', NULL, NULL, '0', NULL, NULL, '1', '2022-04-03'),
(55, 65, '02346AG', 'Distribution of chicken to women', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, '1', NULL, 4, NULL, 90, 11, 1, '11', NULL, NULL, NULL, NULL, '303', '321', '409', NULL, NULL, 0, '1000000.00', 'New', NULL, 0, '2021-07-01', '2021-09-28', NULL, 'Enhanced empowerment of women by giving them chicken', 164, 4, '4', NULL, '<p>Test</p>', 10, 1, 0, NULL, '1', '2022-04-04', NULL, NULL, '0', '2022-04-04 17:27:05', '1', '1', '2022-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projectstages`
--

CREATE TABLE `tbl_projectstages` (
  `psid` int NOT NULL,
  `projid` int DEFAULT NULL,
  `stages` varchar(255) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projects_evaluation`
--

CREATE TABLE `tbl_projects_evaluation` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `evaluation_type` int NOT NULL,
  `itemid` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `added_by` varchar(100) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projects_evaluation`
--

INSERT INTO `tbl_projects_evaluation` (`id`, `projid`, `evaluation_type`, `itemid`, `status`, `added_by`, `date_added`) VALUES
(1, 8, 3, 1, 0, 'admin0', '2022-03-03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projects_location_targets`
--

CREATE TABLE `tbl_projects_location_targets` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `level3` int NOT NULL,
  `locationdisid` int NOT NULL,
  `target` int NOT NULL,
  `responsible` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projects_performance_report_remarks`
--

CREATE TABLE `tbl_projects_performance_report_remarks` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `remarks` text NOT NULL,
  `created_by` int NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_projects_performance_report_remarks`
--

INSERT INTO `tbl_projects_performance_report_remarks` (`id`, `projid`, `remarks`, `created_by`, `date_created`) VALUES
(1, 8, 'Testing project remarks', 1, '2022-01-24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_approved_yearly_budget`
--

CREATE TABLE `tbl_project_approved_yearly_budget` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `year` int NOT NULL,
  `amount` double NOT NULL,
  `created_by` int NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_approved_yearly_budget`
--

INSERT INTO `tbl_project_approved_yearly_budget` (`id`, `projid`, `year`, `amount`, `created_by`, `date_created`) VALUES
(1, 12, 2021, 6000000, 1, '2022-02-21'),
(2, 12, 2021, 6000000, 1, '2022-02-21'),
(3, 12, 2021, 6000000, 1, '2022-02-21'),
(4, 12, 2021, 6000000, 1, '2022-02-21'),
(5, 12, 2021, 6000000, 1, '2022-02-21'),
(6, 12, 2021, 6000000, 1, '2022-02-21'),
(7, 12, 2021, 6000000, 1, '2022-02-21'),
(8, 12, 2021, 6000000, 1, '2022-02-21'),
(9, 12, 2021, 6000000, 1, '2022-02-21'),
(10, 12, 2021, 6000000, 1, '2022-02-21'),
(11, 12, 2021, 6000000, 1, '2022-02-21'),
(12, 12, 2021, 6000000, 1, '2022-02-21'),
(13, 12, 2021, 6000000, 1, '2022-02-21'),
(14, 12, 2021, 6000000, 1, '2022-02-21'),
(15, 12, 2021, 6000000, 1, '2022-02-21'),
(16, 12, 2021, 6000000, 1, '2022-02-21'),
(17, 12, 2021, 6000000, 1, '2022-02-21'),
(18, 12, 2021, 6000000, 1, '2022-02-21'),
(19, 12, 2021, 6000000, 1, '2022-02-21'),
(20, 12, 2021, 6000000, 1, '2022-02-21'),
(21, 15, 2021, 15000000, 1, '2022-02-22'),
(22, 15, 2021, 15000000, 1, '2022-02-22'),
(23, 15, 2021, 15000000, 1, '2022-02-22'),
(24, 27, 2021, 5500000, 1, '2022-02-25'),
(25, 27, 2021, 5500000, 1, '2022-02-25'),
(26, 27, 2021, 5500000, 1, '2022-02-25'),
(27, 27, 2021, 5500000, 1, '2022-02-25'),
(28, 27, 2021, 5500000, 1, '2022-02-25'),
(29, 27, 2021, 5500000, 1, '2022-02-25'),
(30, 27, 2021, 5500000, 1, '2022-02-25'),
(31, 27, 2021, 5500000, 1, '2022-02-25'),
(32, 27, 2021, 5500000, 1, '2022-02-25'),
(33, 27, 2021, 5500000, 1, '2022-02-25'),
(34, 27, 2021, 5500000, 1, '2022-02-25'),
(35, 27, 2021, 5500000, 1, '2022-02-25'),
(36, 27, 2021, 5500000, 1, '2022-02-25'),
(37, 27, 2021, 5500000, 1, '2022-02-25'),
(38, 27, 2021, 5500000, 1, '2022-02-25'),
(39, 27, 2021, 5500000, 1, '2022-02-25'),
(40, 27, 2021, 5500000, 1, '2022-02-25'),
(41, 27, 2021, 5500000, 1, '2022-02-25'),
(42, 27, 2021, 5500000, 1, '2022-02-25'),
(43, 27, 2021, 5500000, 1, '2022-02-25'),
(44, 27, 2021, 5500000, 1, '2022-02-25'),
(45, 27, 2021, 5500000, 1, '2022-02-25'),
(46, 27, 2021, 5500000, 1, '2022-02-25'),
(47, 27, 2021, 5500000, 1, '2022-02-25'),
(48, 27, 2021, 5500000, 1, '2022-02-25'),
(49, 27, 2021, 5500000, 1, '2022-02-25'),
(50, 27, 2021, 5500000, 1, '2022-02-25'),
(51, 27, 2021, 5500000, 1, '2022-02-25'),
(52, 27, 2021, 5500000, 1, '2022-02-25'),
(53, 27, 2021, 5500000, 1, '2022-02-25'),
(54, 27, 2021, 5500000, 1, '2022-02-25'),
(55, 27, 2021, 5500000, 1, '2022-02-25'),
(56, 27, 2021, 5500000, 1, '2022-02-25'),
(57, 27, 2021, 5500000, 1, '2022-02-25'),
(58, 27, 2021, 5500000, 1, '2022-02-25'),
(59, 27, 2021, 5500000, 1, '2022-02-25'),
(60, 27, 2021, 5500000, 1, '2022-02-25'),
(61, 27, 2021, 5500000, 1, '2022-02-25'),
(62, 27, 2021, 5500000, 1, '2022-02-25'),
(63, 27, 2021, 5500000, 1, '2022-02-25'),
(64, 27, 2021, 5500000, 1, '2022-02-25'),
(65, 27, 2021, 5500000, 1, '2022-02-25'),
(67, 27, 2021, 5500000, 1, '2022-02-25'),
(68, 27, 2021, 5500000, 1, '2022-02-25'),
(69, 27, 2021, 5500000, 1, '2022-02-25'),
(70, 27, 2021, 5500000, 1, '2022-02-25'),
(71, 27, 2021, 5500000, 1, '2022-02-25'),
(72, 27, 2021, 5500000, 1, '2022-02-25'),
(73, 27, 2021, 5500000, 1, '2022-02-25'),
(74, 27, 2021, 5500000, 1, '2022-02-25'),
(75, 27, 2021, 5500000, 1, '2022-02-25'),
(76, 27, 2021, 5500000, 1, '2022-02-25'),
(77, 27, 2021, 5500000, 1, '2022-02-25'),
(78, 27, 2021, 5500000, 1, '2022-02-25'),
(80, 19, 2021, 3000, 1, '2022-03-10'),
(81, 18, 2021, 300, 1, '2022-03-14'),
(82, 18, 2021, 300, 1, '2022-03-14'),
(83, 18, 2021, 300, 1, '2022-03-14'),
(84, 29, 2021, 24000000, 1, '2022-03-15'),
(85, 29, 2021, 24000000, 1, '2022-03-15'),
(86, 29, 2021, 24000000, 1, '2022-03-15'),
(87, 29, 2021, 24000000, 1, '2022-03-15'),
(88, 29, 2021, 24000000, 1, '2022-03-15'),
(89, 29, 2021, 24000000, 1, '2022-03-15'),
(90, 29, 2021, 24000000, 1, '2022-03-15'),
(91, 29, 2021, 24000000, 1, '2022-03-15'),
(92, 29, 2021, 24000000, 1, '2022-03-15'),
(93, 29, 2021, 24000000, 1, '2022-03-15'),
(94, 29, 2021, 24000000, 1, '2022-03-15'),
(95, 35, 2021, 9000000, 1, '2022-03-20'),
(100, 38, 2021, 6000000, 1, '2022-03-23'),
(101, 38, 2021, 6000000, 1, '2022-03-23'),
(102, 39, 2021, 10000000, 1, '2022-03-24'),
(103, 39, 2021, 10000000, 1, '2022-03-24'),
(104, 39, 2021, 10000000, 1, '2022-03-24'),
(105, 39, 2021, 10000000, 1, '2022-03-24'),
(106, 39, 2021, 10000000, 1, '2022-03-24'),
(107, 39, 2021, 10000000, 1, '2022-03-24'),
(108, 39, 2021, 10000000, 1, '2022-03-24'),
(109, 39, 2021, 10000000, 1, '2022-03-24'),
(110, 39, 2021, 10000000, 1, '2022-03-24'),
(111, 39, 2021, 10000000, 1, '2022-03-24'),
(112, 39, 2021, 10000000, 1, '2022-03-24'),
(113, 39, 2021, 10000000, 1, '2022-03-24'),
(114, 39, 2021, 10000000, 1, '2022-03-24'),
(115, 39, 2021, 10000000, 1, '2022-03-24'),
(116, 39, 2021, 10000000, 1, '2022-03-24'),
(117, 39, 2021, 10000000, 1, '2022-03-24'),
(118, 39, 2021, 10000000, 1, '2022-03-24'),
(119, 40, 2021, 100000000, 1, '2022-03-27'),
(120, 40, 2021, 10000000, 1, '2022-03-27'),
(121, 40, 2021, 10000000, 1, '2022-03-27'),
(122, 40, 2021, 10000000, 1, '2022-03-27'),
(123, 43, 2021, 50000000, 1, '2022-03-28'),
(124, 13, 2021, 100, 1, '2022-03-28'),
(125, 26, 2021, 40000, 1, '2022-03-28'),
(126, 44, 2021, 30000000, 1, '2022-03-29'),
(128, 45, 2021, 10000000, 1, '2022-03-30'),
(129, 16, 2021, 15000000, 1, '2022-03-30'),
(130, 48, 2021, 24000000, 1, '2022-03-31'),
(131, 49, 2021, 50000000, 1, '2022-04-01'),
(132, 54, 2021, 50000000, 1, '2022-04-03'),
(133, 50, 2021, 20000000, 1, '2022-04-03'),
(134, 51, 2021, 6000000, 1, '2022-04-03'),
(135, 55, 2021, 1000000, 1, '2022-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_beneficiaries`
--

CREATE TABLE `tbl_project_beneficiaries` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `beneficiary` varchar(255) NOT NULL,
  `total_value` decimal(10,0) NOT NULL,
  `type` int NOT NULL,
  `disaggregated` int NOT NULL,
  `added_by` varchar(100) NOT NULL,
  `date_added` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_beneficiary_disaggregation`
--

CREATE TABLE `tbl_project_beneficiary_disaggregation` (
  `id` int NOT NULL,
  `ben_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_changed_parameters`
--

CREATE TABLE `tbl_project_changed_parameters` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `issueid` int NOT NULL,
  `itype` varchar(100) NOT NULL,
  `category` int NOT NULL,
  `parameter` varchar(100) NOT NULL,
  `parameter_value` int NOT NULL,
  `previous_value` varchar(100) NOT NULL,
  `added_by` varchar(100) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_changed_parameters`
--

INSERT INTO `tbl_project_changed_parameters` (`id`, `projid`, `issueid`, `itype`, `category`, `parameter`, `parameter_value`, `previous_value`, `added_by`, `date_added`) VALUES
(1, 8, 1, 'time', 3, '2', 365, '2021-07-15', 'admin0', '2022-03-04'),
(2, 8, 1, 'time', 3, '4', 365, '2021-07-21', 'admin0', '2022-03-04'),
(3, 8, 1, 'time', 3, '6', 365, '2021-08-31', 'admin0', '2022-03-04'),
(4, 8, 1, 'time', 3, '8', 365, '2021-09-15', 'admin0', '2022-03-04'),
(5, 8, 1, 'time', 3, '9', 365, '2021-10-15', 'admin0', '2022-03-04'),
(6, 8, 1, 'time', 3, '10', 365, '2023-06-15', 'admin0', '2022-03-04'),
(7, 8, 1, 'time', 3, '13', 365, '2021-11-30', 'admin0', '2022-03-04'),
(8, 8, 11, 'time', 3, '2', 60, '2022-07-15', 'admin0', '2022-03-24'),
(9, 8, 11, 'time', 3, '4', 60, '2022-07-21', 'admin0', '2022-03-24'),
(10, 8, 11, 'time', 3, '6', 60, '2022-08-31', 'admin0', '2022-03-24'),
(11, 8, 11, 'time', 3, '8', 60, '2022-09-15', 'admin0', '2022-03-24'),
(12, 8, 11, 'time', 3, '9', 60, '2022-10-15', 'admin0', '2022-03-24'),
(13, 8, 11, 'time', 3, '10', 60, '2024-06-14', 'admin0', '2022-03-24'),
(14, 8, 11, 'time', 3, '13', 60, '2022-11-30', 'admin0', '2022-03-24'),
(15, 8, 11, 'time', 3, '2', 30, '2022-09-13', '1', '2022-03-24'),
(16, 8, 11, 'time', 3, '4', 30, '2022-09-19', '1', '2022-03-24'),
(17, 8, 11, 'time', 3, '6', 30, '2022-10-30', '1', '2022-03-24'),
(18, 8, 11, 'time', 3, '8', 30, '2022-11-14', '1', '2022-03-24'),
(19, 8, 11, 'time', 3, '9', 30, '2022-12-14', '1', '2022-03-24'),
(20, 8, 11, 'time', 3, '10', 30, '2024-08-13', '1', '2022-03-24'),
(21, 8, 11, 'time', 3, '13', 30, '2023-01-29', '1', '2022-03-24'),
(22, 55, 17, 'time', 3, '66', 10, '2021-08-01', '1', '2022-04-04'),
(23, 55, 17, 'time', 3, '67', 10, '2021-08-16', '1', '2022-04-04'),
(24, 55, 17, 'time', 3, '68', 10, '2021-09-28', '1', '2022-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_cost_funders_share`
--

CREATE TABLE `tbl_project_cost_funders_share` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `type` int NOT NULL,
  `plan_id` int NOT NULL,
  `funder` int NOT NULL,
  `amount` double NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `update_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_cost_funders_share`
--

INSERT INTO `tbl_project_cost_funders_share` (`id`, `projid`, `outputid`, `type`, `plan_id`, `funder`, `amount`, `created_by`, `date_created`, `update_by`, `date_updated`) VALUES
(1, 9, 69, 2, 1, 3, 90000, 'admin0', '2022-01-18', NULL, NULL),
(2, 9, 69, 2, 2, 3, 100000, 'admin0', '2022-01-18', NULL, NULL),
(5, 8, 63, 1, 5, 1, 450000, 'Admin0', '2022-01-18', NULL, NULL),
(6, 8, 63, 1, 6, 1, 750000, 'Admin0', '2022-01-18', NULL, NULL),
(8, 8, 63, 1, 8, 1, 1500000, 'Admin0', '2022-01-18', NULL, NULL),
(9, 8, 63, 1, 9, 1, 1000000, 'Admin0', '2022-01-18', NULL, NULL),
(10, 8, 63, 1, 10, 1, 2600000, 'Admin0', '2022-01-18', NULL, NULL),
(11, 8, 63, 1, 11, 1, 300000, 'Admin0', '2022-01-18', NULL, NULL),
(12, 8, 63, 1, 12, 1, 200000, 'Admin0', '2022-01-18', NULL, NULL),
(14, 8, 63, 2, 14, 1, 800000, 'Admin0', '2022-01-18', NULL, NULL),
(15, 8, 63, 2, 15, 1, 1200000, 'Admin0', '2022-01-18', NULL, NULL),
(16, 8, 63, 3, 16, 1, 50000, 'Admin0', '2022-01-18', NULL, NULL),
(17, 8, 63, 3, 17, 1, 900000, 'Admin0', '2022-01-18', NULL, NULL),
(18, 8, 63, 3, 18, 1, 100000, 'Admin0', '2022-01-18', NULL, NULL),
(19, 8, 63, 3, 19, 1, 150000, 'Admin0', '2022-01-18', NULL, NULL),
(22, 5, 59, 1, 22, 2, 300000, 'Admin0', '2022-01-19', NULL, NULL),
(24, 5, 59, 1, 24, 2, 10000000, 'Admin0', '2022-01-19', NULL, NULL),
(25, 5, 59, 2, 25, 2, 40000, 'Admin0', '2022-01-19', NULL, NULL),
(26, 5, 59, 2, 26, 2, 80000, 'Admin0', '2022-01-19', NULL, NULL),
(27, 5, 59, 3, 27, 2, 30000, 'Admin0', '2022-01-19', NULL, NULL),
(28, 5, 59, 3, 28, 2, 80000, 'Admin0', '2022-01-19', NULL, NULL),
(29, 5, 59, 3, 29, 2, 70000000, 'Admin0', '2022-01-19', NULL, NULL),
(30, 5, 59, 3, 30, 2, 19470000, 'Admin0', '2022-01-19', NULL, NULL),
(31, 5, 59, 3, 31, 2, 19470000, 'Admin0', '2022-01-19', NULL, NULL),
(85, 22, 96, 1, 85, 3, 418, 'admin0', '2022-02-09', NULL, NULL),
(86, 22, 96, 1, 86, 3, 504, 'admin0', '2022-02-09', NULL, NULL),
(87, 22, 96, 1, 87, 3, 3740, 'admin0', '2022-02-09', NULL, NULL),
(88, 22, 96, 2, 88, 3, 1995338, 'admin0', '2022-02-09', NULL, NULL),
(89, 22, 96, 2, 89, 3, 1995338, 'admin0', '2022-02-09', NULL, NULL),
(96, 24, 100, 3, 96, 3, 1127000, 'admin0', '2022-02-13', NULL, NULL),
(97, 24, 100, 3, 97, 3, 364000, 'admin0', '2022-02-13', NULL, NULL),
(98, 24, 100, 3, 98, 3, 360000, 'admin0', '2022-02-13', NULL, NULL),
(99, 24, 100, 3, 99, 3, 450000, 'admin0', '2022-02-13', NULL, NULL),
(100, 24, 100, 2, 100, 3, 200000, 'admin0', '2022-02-13', NULL, NULL),
(101, 24, 100, 1, 101, 3, 500000, 'admin0', '2022-02-13', NULL, NULL),
(102, 24, 100, 1, 102, 3, 1999000, 'admin0', '2022-02-13', NULL, NULL),
(115, 7, 61, 3, 115, 1, 14993074, 'admin0', '2022-02-25', NULL, NULL),
(116, 7, 61, 2, 116, 1, 989, 'admin0', '2022-02-25', NULL, NULL),
(117, 7, 61, 1, 117, 1, 1554, 'admin0', '2022-02-25', NULL, NULL),
(118, 7, 61, 1, 118, 1, 1156, 'admin0', '2022-02-25', NULL, NULL),
(119, 7, 61, 1, 119, 1, 819, 'admin0', '2022-02-25', NULL, NULL),
(120, 7, 61, 1, 120, 1, 2408, 'admin0', '2022-02-25', NULL, NULL),
(121, 27, 105, 1, 121, 3, 450000, 'admin0', '2022-02-25', NULL, NULL),
(122, 27, 105, 1, 122, 3, 1800000, 'admin0', '2022-02-25', NULL, NULL),
(123, 27, 105, 1, 123, 3, 1250000, 'admin0', '2022-02-25', NULL, NULL),
(124, 27, 105, 2, 124, 3, 120000, 'admin0', '2022-02-25', NULL, NULL),
(125, 27, 105, 2, 125, 3, 200000, 'admin0', '2022-02-25', NULL, NULL),
(126, 27, 105, 3, 126, 3, 600000, 'admin0', '2022-02-25', NULL, NULL),
(127, 27, 105, 3, 127, 3, 150000, 'admin0', '2022-02-25', NULL, NULL),
(128, 27, 105, 1, 128, 3, 210000, 'admin0', '2022-02-25', NULL, NULL),
(129, 27, 105, 3, 129, 3, 360000, 'admin0', '2022-02-25', NULL, NULL),
(130, 27, 105, 3, 130, 3, 360000, 'admin0', '2022-02-25', NULL, NULL),
(133, 35, 131, 1, 133, 1, 9000000, 'Admin0', '2022-03-20', NULL, NULL),
(134, 35, 131, 2, 134, 1, 500000, 'Admin0', '2022-03-20', NULL, NULL),
(135, 35, 131, 3, 135, 1, 500000, 'Admin0', '2022-03-20', NULL, NULL),
(136, 38, 134, 1, 136, 3, 500000, 'Admin0', '2022-03-24', NULL, NULL),
(137, 38, 134, 1, 137, 3, 1000000, 'Admin0', '2022-03-24', NULL, NULL),
(138, 38, 134, 1, 138, 3, 650000, 'Admin0', '2022-03-24', NULL, NULL),
(139, 38, 134, 1, 139, 3, 3000000, 'Admin0', '2022-03-24', NULL, NULL),
(141, 38, 134, 2, 141, 3, 100000, 'Admin0', '2022-03-24', NULL, NULL),
(142, 38, 134, 2, 142, 3, 100000, 'Admin0', '2022-03-24', NULL, NULL),
(143, 38, 134, 3, 143, 3, 50000, 'Admin0', '2022-03-24', NULL, NULL),
(145, 38, 134, 3, 145, 3, 200000, 'Admin0', '2022-03-24', NULL, NULL),
(146, 39, 135, 1, 146, 1, 1000000, 'Admin0', '2022-03-25', NULL, NULL),
(147, 39, 135, 1, 147, 1, 6000000, 'Admin0', '2022-03-25', NULL, NULL),
(148, 39, 135, 3, 148, 1, 250000, 'Admin0', '2022-03-25', NULL, NULL),
(149, 39, 135, 3, 149, 1, 400000, 'Admin0', '2022-03-25', NULL, NULL),
(150, 39, 135, 3, 150, 1, 100000, 'Admin0', '2022-03-25', NULL, NULL),
(151, 39, 135, 2, 151, 1, 250000, 'Admin0', '2022-03-25', NULL, NULL),
(152, 39, 135, 1, 152, 1, 2000000, 'Admin0', '2022-03-25', NULL, NULL),
(153, 40, 136, 1, 153, 1, 1500000, 'admin0', '2022-03-29', NULL, NULL),
(154, 40, 136, 1, 154, 1, 4500000, 'admin0', '2022-03-29', NULL, NULL),
(155, 40, 136, 2, 155, 1, 100000, 'admin0', '2022-03-29', NULL, NULL),
(156, 40, 136, 2, 156, 1, 100000, 'admin0', '2022-03-29', NULL, NULL),
(157, 40, 136, 2, 157, 1, 100000, 'admin0', '2022-03-29', NULL, NULL),
(158, 44, 143, 1, 158, 2, 15000000, 'admin0', '2022-03-30', NULL, NULL),
(159, 44, 143, 1, 159, 2, 6000000, 'admin0', '2022-03-30', NULL, NULL),
(160, 44, 143, 2, 160, 2, 500000, 'admin0', '2022-03-30', NULL, NULL),
(161, 44, 143, 3, 161, 2, 1000000, 'admin0', '2022-03-30', NULL, NULL),
(162, 44, 143, 3, 162, 2, 4000000, 'admin0', '2022-03-30', NULL, NULL),
(163, 44, 143, 3, 163, 2, 2000000, 'admin0', '2022-03-30', NULL, NULL),
(164, 44, 143, 2, 164, 2, 1500000, 'admin0', '2022-03-30', NULL, NULL),
(207, 45, 151, 1, 193, 6, 500000, 'Admin0', '2022-03-30', NULL, NULL),
(208, 45, 151, 1, 194, 7, 1200000, 'Admin0', '2022-03-30', NULL, NULL),
(209, 45, 151, 1, 195, 6, 3000000, 'Admin0', '2022-03-30', NULL, NULL),
(210, 45, 151, 1, 195, 7, 3000000, 'Admin0', '2022-03-30', NULL, NULL),
(211, 45, 151, 2, 196, 6, 1500000, 'Admin0', '2022-03-30', NULL, NULL),
(212, 45, 151, 2, 196, 7, 500000, 'Admin0', '2022-03-30', NULL, NULL),
(213, 45, 151, 2, 197, 7, 300000, 'Admin0', '2022-03-30', NULL, NULL),
(214, 49, 164, 1, 198, 2, 42000000, 'Admin0', '2022-04-01', NULL, NULL),
(215, 49, 164, 2, 199, 2, 8000000, 'Admin0', '2022-04-01', NULL, NULL),
(216, 54, 169, 1, 200, 2, 48000000, 'admin0', '2022-04-03', NULL, NULL),
(217, 54, 169, 1, 201, 2, 1800000, 'admin0', '2022-04-03', NULL, NULL),
(218, 54, 169, 2, 202, 2, 200000, 'admin0', '2022-04-03', NULL, NULL),
(219, 55, 170, 1, 203, 2, 300000, 'Admin0', '2022-04-04', NULL, NULL),
(220, 55, 170, 1, 204, 2, 300000, 'Admin0', '2022-04-04', NULL, NULL),
(221, 55, 170, 1, 205, 2, 300000, 'Admin0', '2022-04-04', NULL, NULL),
(222, 55, 170, 2, 206, 2, 100000, 'Admin0', '2022-04-04', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_details`
--

CREATE TABLE `tbl_project_details` (
  `id` int NOT NULL,
  `unique_key` varchar(255) NOT NULL,
  `progid` int DEFAULT NULL,
  `projid` int DEFAULT NULL,
  `outputid` int DEFAULT NULL,
  `indicator` int NOT NULL,
  `year` int NOT NULL,
  `duration` int NOT NULL,
  `budget` int NOT NULL,
  `mapping_type` int DEFAULT NULL,
  `total_target` bigint NOT NULL,
  `workplan_interval` int DEFAULT NULL COMMENT 'Rename to monitoring frequency'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_details`
--

INSERT INTO `tbl_project_details` (`id`, `unique_key`, `progid`, `projid`, `outputid`, `indicator`, `year`, `duration`, `budget`, `mapping_type`, `total_target`, `workplan_interval`) VALUES
(64, '0', 2, 1, 10, 19, 4, 277, 5000000, 1, 4, NULL),
(2, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(3, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(4, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(5, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(6, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(7, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(8, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(9, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(10, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(11, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(12, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(13, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(14, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(15, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(16, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(17, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(18, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(19, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(20, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(21, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(22, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(23, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(24, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(25, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(26, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(27, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(28, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(29, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(30, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(31, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(32, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(33, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(34, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(35, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(36, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(37, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(38, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(39, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(40, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(41, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(42, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(43, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(44, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(45, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(46, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(47, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(48, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(49, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(50, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(51, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(52, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(53, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(54, '0', 2, NULL, 2, 18, 4, 119, 200000000, 2, 6, NULL),
(55, '0', 1, 2, 1, 17, 4, 149, 50000000, 1, 1, NULL),
(56, '0', 2, 3, 11, 17, 4, 119, 49000000, 1, 1, NULL),
(57, '0', 2, 4, 11, 17, 4, 129, 17000000, 1, 1, NULL),
(58, '0', 2, NULL, 9, 18, 4, 200, 30000, NULL, 5, NULL),
(59, '0', 5, 5, 12, 2, 4, 363, 100000000, 1, 36, NULL),
(60, '0', 5, 6, 12, 2, 4, 298, 12000000, 1, 3, NULL),
(61, '0', 6, 7, 14, 23, 4, 270, 15000000, 1, 1, NULL),
(63, '0', 6, 8, 14, 23, 4, 295, 10000000, 1, 1, 5),
(65, '0', 2, NULL, 9, 18, 4, 277, 45000000, 2, 14, NULL),
(66, '0', 2, NULL, 10, 19, 4, 299, 6000000, 2, 5, NULL),
(67, '0', 2, NULL, 9, 18, 4, 297, 15000000, 2, 15, NULL),
(68, '0', 2, NULL, 11, 17, 4, 299, 20000000, 1, 1, NULL),
(69, '0', 2, 9, 10, 19, 4, 299, 4000000, 1, 2, NULL),
(70, '0', 2, 9, 9, 18, 4, 299, 30000000, 2, 10, NULL),
(73, '0', 5, 10, 12, 2, 4, 100, 2000000, 1, 1, NULL),
(72, '0', 2, 9, 11, 17, 4, 299, 30000000, 1, 1, NULL),
(74, '0', 1, NULL, 15, 21, 4, 364, 10000, 2, 10, NULL),
(75, '0', 7, NULL, 19, 26, 4, 367, 3000000, 1, 1, NULL),
(76, '0', 7, 11, 21, 26, 4, 169, 3000000, 1, 1, NULL),
(77, '0', 2, NULL, 9, 18, 4, 20, 200, 1, 20, NULL),
(78, '0', 2, NULL, 9, 18, 4, 11, 100, NULL, 10, NULL),
(79, '0', 2, NULL, 10, 19, 4, 100, 200, 1, 83, NULL),
(80, '0', 2, NULL, 10, 19, 4, 100, 200, 1, 83, NULL),
(81, '0', 2, NULL, 10, 19, 4, 100, 200, 1, 83, NULL),
(82, '0', 2, NULL, 10, 19, 4, 100, 200, 1, 83, NULL),
(83, '0', 2, 12, 10, 19, 4, 30, 100, 1, 30, NULL),
(84, '0', 2, 13, 10, 19, 4, 100, 100, 1, 20, NULL),
(147, '0', 62, NULL, 73, 151, 4, 59, 5000000, NULL, 1000, NULL),
(86, '0', 2, 15, 11, 17, 4, 119, 14000000, 1, 1, 4),
(87, '0', 8, 16, 22, 28, 4, 119, 15000000, NULL, 1, NULL),
(88, '0', 1, NULL, 15, 21, 4, 127, 5000000, 1, 1, NULL),
(89, '0', 1, NULL, 15, 21, 4, 298, 50000000, 1, 10, NULL),
(90, '0', 2, NULL, 9, 18, 4, 129, 12000000, 2, 12, NULL),
(119, '0', 16, NULL, 17, 69, 4, 128, 189000, 1, 70, NULL),
(92, '0', 1, NULL, 15, 21, 4, 340, 120, 1, 2, NULL),
(128, '0', 49, NULL, 54, 2, 7, 300, 200, 2, 30, NULL),
(95, '0', 2, 21, 9, 18, 4, 139, 17000000, 2, 10, NULL),
(96, '0', 2, 22, 10, 19, 4, 129, 2000000, 1, 4, NULL),
(97, '0', 2, 23, 11, 17, 4, 89, 7000000, 1, 1, NULL),
(98, '0', 1, NULL, 15, 21, 4, 200, 5000000, 1, 10, NULL),
(99, '0', 10, NULL, 28, 40, 4, 364, 5000000, 1, 3, NULL),
(100, '0', 13, 24, 34, 1, 4, 199, 5000000, NULL, 1, 5),
(101, '0', 2, 25, 1, 18, 4, 119, 4000000, 1, 6, NULL),
(102, '0', 43, NULL, 41, 2, 4, 298, 10000000, 1, 3, NULL),
(103, '0', 2, NULL, 1, 18, 4, 1, 7888, 2, 1, NULL),
(104, '0', 47, 26, 46, 2, 4, 9, 40000, 1, 2, NULL),
(105, '0', 2, 27, 1, 18, 4, 119, 5500000, 2, 5, NULL),
(125, '0', 49, 30, 49, 1, 7, 359, 10000000, 1, 1, NULL),
(107, '0', 21, NULL, 26, 23, 5, 364, 1000, 1, 50, NULL),
(127, '0', 49, NULL, 49, 1, 10, 500, 4000, 2, 3, NULL),
(126, '0', 49, NULL, 49, 1, 10, 100, 2222, 2, 2, NULL),
(146, '0', 62, NULL, 73, 151, 4, 59, 5000000, NULL, 1000, NULL),
(145, '0', 62, NULL, 73, 151, 4, 30, 5000000, NULL, 1000, NULL),
(144, '0', 62, NULL, 73, 151, 4, 30, 5000000, NULL, 1000, NULL),
(120, '0', 1, NULL, 6, 17, 4, 199, 200000, NULL, 2, NULL),
(121, '0', 1, NULL, 6, 17, 4, 58, 666, NULL, 2, NULL),
(122, '0', 2, 29, 1, 18, 4, 119, 4000000, 1, 3, NULL),
(123, '0', 2, 29, 5, 17, 4, 119, 13000000, 1, 2, NULL),
(129, '0', 49, 33, 54, 2, 10, 500, 200, 1, 2, NULL),
(130, '0', 50, 34, 59, 17, 5, 199, 20000000, 1, 1, NULL),
(131, '0', 51, 35, 60, 135, 4, 100, 10000000, 3, 40, NULL),
(132, '0', 52, 36, 61, 2, 5, 199, 50000000, 1, 10, NULL),
(135, '0', 55, 39, 66, 144, 4, 299, 10000000, 1, 4, NULL),
(134, '0', 54, 38, 64, 18, 4, 129, 5600000, 2, 20, 4),
(136, '0', 56, 40, 67, 12, 4, 359, 10000000, 1, 1, NULL),
(137, '0', 1, NULL, 6, 17, 4, 359, 8000000, 1, 1, NULL),
(138, '0', 58, 41, 69, 147, 4, 89, 15000000, 1, 1, NULL),
(139, '0', 59, 42, 70, 144, 4, 299, 10000000, 1, 10, NULL),
(140, '0', 1, NULL, 6, 17, 4, 299, 1000000000, 1, 10, NULL),
(142, '0', 60, 43, 71, 2, 5, 279, 50000000, 1, 10, NULL),
(143, '0', 61, 44, 72, 1, 4, 359, 30000000, 1, 1, 4),
(152, '0', 49, NULL, 49, 1, 7, 400, 3000, NULL, 9, NULL),
(151, '0', 62, 45, 73, 151, 4, 60, 10000000, NULL, 2000, 4),
(153, '0', 49, NULL, 54, 2, 7, 400, 1200, NULL, 1, NULL),
(154, '0', 49, NULL, 54, 2, 7, 400, 1200, NULL, 1, NULL),
(155, '1648650419prkrll', 49, NULL, 49, 1, 7, 200, 2000, 2, 1, NULL),
(156, '1648651077PkwKmu', 49, NULL, 54, 2, 11, 13, 100, NULL, 1, NULL),
(157, '1648651077PkwKmu', 49, NULL, 49, 1, 11, 63, 100, NULL, 1, NULL),
(158, '1648651413rXHtmD', 49, NULL, 49, 1, 9, 400, 300, NULL, 1, NULL),
(159, '1648651413rXHtmD', 49, NULL, 54, 2, 9, 300, 3000, NULL, 1, NULL),
(160, '1648705235Wg41cU', 54, NULL, 64, 18, 4, 699, 20000000, 2, 10, NULL),
(161, '1648708127lfVPz0', 61, 46, 72, 1, 4, 363, 15000000, 1, 1, NULL),
(162, '1648708540mBawQa', 61, 47, 72, 1, 4, 361, 10000000, 1, 1, NULL),
(163, '1648708931zSWo1D', 61, 48, 72, 1, 4, 363, 24000000, 1, 1, NULL),
(164, '16488353295MWLYh', 63, 49, 75, 144, 4, 700, 50000000, 1, 50, 4),
(165, '1648901461C2W2CS', 64, 50, 78, 154, 4, 359, 20000000, NULL, 2, NULL),
(166, '1648901718Uk43OB', 64, 51, 81, 156, 4, 360, 6000000, NULL, 1, NULL),
(167, '164890260691earp', 64, 52, 80, 158, 4, 360, 20000000, NULL, 3, NULL),
(168, '1648902823BAePRc', 64, 53, 79, 159, 4, 359, 10000000, NULL, 3, NULL),
(169, '1648903042BpopRN', 64, 54, 77, 155, 4, 350, 50000000, NULL, 6, 4),
(170, '1649071571sF7luN', 65, 55, 82, 163, 4, 90, 1000000, NULL, 3000, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_details_history`
--

CREATE TABLE `tbl_project_details_history` (
  `id` int NOT NULL,
  `pdid` int NOT NULL,
  `progid` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `indicator` int NOT NULL,
  `year` int NOT NULL,
  `duration` int NOT NULL,
  `budget` double NOT NULL,
  `mapping_type` int NOT NULL DEFAULT '0',
  `total_target` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_details_history`
--

INSERT INTO `tbl_project_details_history` (`id`, `pdid`, `progid`, `projid`, `outputid`, `indicator`, `year`, `duration`, `budget`, `mapping_type`, `total_target`) VALUES
(1, 63, 6, 8, 14, 23, 4, 295, 10000000, 1, 1),
(2, 55, 1, 2, 1, 17, 4, 149, 50000000, 1, 1),
(3, 61, 6, 7, 14, 23, 4, 270, 15000000, 1, 1),
(4, 60, 5, 6, 12, 2, 4, 298, 12000000, 1, 3),
(5, 59, 5, 5, 12, 2, 4, 363, 100000000, 1, 36),
(6, 69, 2, 9, 10, 19, 4, 299, 4000000, 1, 2),
(7, 70, 2, 9, 9, 18, 4, 299, 30000000, 2, 10),
(8, 72, 2, 9, 11, 17, 4, 299, 30000000, 1, 1),
(9, 64, 2, 1, 10, 19, 4, 277, 5000000, 1, 4),
(10, 57, 2, 4, 11, 17, 4, 129, 17000000, 1, 1),
(11, 73, 5, 10, 12, 2, 4, 100, 2000000, 1, 1),
(12, 56, 2, 3, 11, 17, 4, 119, 49000000, 1, 1),
(13, 76, 7, 11, 21, 26, 4, 169, 3000000, 1, 1),
(15, 95, 2, 21, 9, 18, 4, 139, 17000000, 2, 10),
(16, 96, 2, 22, 10, 19, 4, 129, 2000000, 1, 4),
(18, 97, 2, 23, 11, 17, 4, 89, 7000000, 1, 1),
(19, 100, 13, 24, 34, 1, 4, 199, 5000000, 0, 1),
(20, 101, 2, 25, 1, 18, 4, 119, 4000000, 1, 6),
(21, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(22, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(23, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(24, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(25, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(26, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(27, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(28, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(29, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(30, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(31, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(32, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(33, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(34, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(35, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(36, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(37, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(38, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(39, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(40, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(41, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(42, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(43, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(44, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(45, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(46, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(47, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(48, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(49, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(50, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(51, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(52, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(53, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(54, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(55, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(56, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(57, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(58, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(59, 83, 2, 12, 10, 19, 4, 30, 100, 1, 30),
(60, 86, 2, 15, 11, 17, 4, 119, 14000000, 1, 1),
(61, 86, 2, 15, 11, 17, 4, 119, 14000000, 1, 1),
(62, 86, 2, 15, 11, 17, 4, 119, 14000000, 1, 1),
(63, 86, 2, 15, 11, 17, 4, 119, 14000000, 1, 1),
(64, 86, 2, 15, 11, 17, 4, 119, 14000000, 1, 1),
(65, 86, 2, 15, 11, 17, 4, 119, 14000000, 1, 1),
(66, 105, 2, 27, 1, 18, 4, 119, 6000000, 2, 5),
(67, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(68, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(69, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(70, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(71, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(72, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(73, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(74, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(75, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(76, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(77, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(78, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(79, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(80, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(81, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(82, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(83, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(84, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(85, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(86, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(87, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(88, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(89, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(90, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(91, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(92, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(93, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(94, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(95, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(96, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(97, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(98, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(99, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(100, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(101, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(102, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(103, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(104, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(105, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(106, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(107, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(108, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(109, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(110, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(111, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(112, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(113, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(114, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(115, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(116, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(117, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(118, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(119, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(120, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(121, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(122, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(123, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(124, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(125, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(126, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(127, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(128, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(129, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(130, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(131, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(132, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(133, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(134, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(135, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(136, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(137, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(138, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(139, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(140, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(141, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(142, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(143, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(144, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(145, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(146, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(147, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(148, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(149, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(152, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(153, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(154, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(155, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(156, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(157, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(158, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(159, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(160, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(161, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(162, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(163, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(164, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(165, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(166, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(167, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(168, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(169, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(170, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(171, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(172, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(173, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(174, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(175, 105, 2, 27, 1, 18, 4, 119, 5500000, 2, 5),
(184, 136, 56, 40, 67, 12, 4, 359, 10000000, 1, 1),
(185, 136, 56, 40, 67, 12, 4, 359, 10000000, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_direct_cost_plan`
--

CREATE TABLE `tbl_project_direct_cost_plan` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `plan_id` int DEFAULT NULL,
  `tasks` int DEFAULT NULL,
  `personnel` int DEFAULT NULL,
  `other_plan_id` int DEFAULT NULL,
  `description` text,
  `unit` varchar(100) DEFAULT NULL,
  `unit_cost` double DEFAULT NULL,
  `units_no` int DEFAULT NULL,
  `comments` text,
  `cost_type` int NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `update_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_direct_cost_plan`
--

INSERT INTO `tbl_project_direct_cost_plan` (`id`, `projid`, `outputid`, `plan_id`, `tasks`, `personnel`, `other_plan_id`, `description`, `unit`, `unit_cost`, `units_no`, `comments`, `cost_type`, `created_by`, `date_created`, `update_by`, `date_updated`) VALUES
(1, 9, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'test', 2, 'admin0', '2022-01-18', NULL, NULL),
(2, 9, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'test', 2, 'admin0', '2022-01-18', NULL, NULL),
(5, 8, 63, NULL, 2, NULL, NULL, 'Labour', 'Days', 50000, 9, '', 1, 'Admin0', '2022-01-18', NULL, NULL),
(6, 8, 63, NULL, 4, NULL, NULL, 'Labour', 'Days', 50000, 15, '', 1, 'Admin0', '2022-01-18', NULL, NULL),
(8, 8, 63, NULL, 8, NULL, NULL, 'Materials', 'Units', 100000, 15, '', 1, 'Admin0', '2022-01-18', NULL, NULL),
(9, 8, 63, NULL, 9, NULL, NULL, 'Materials', 'Units', 50000, 20, '', 1, 'Admin0', '2022-01-18', NULL, NULL),
(10, 8, 63, NULL, 6, NULL, NULL, 'Materials', 'Units', 100000, 26, '', 1, 'Admin0', '2022-01-18', NULL, NULL),
(11, 8, 63, NULL, 10, NULL, NULL, 'Timber', 'Foot', 3000, 100, '', 1, 'Admin0', '2022-01-18', NULL, NULL),
(12, 8, 63, NULL, 13, NULL, NULL, 'Iron Sheets', 'Number', 200, 1000, '', 1, 'Admin0', '2022-01-18', NULL, NULL),
(14, 8, 63, NULL, NULL, 15, NULL, NULL, 'Days', 20000, 40, '', 2, 'Admin0', '2022-01-18', NULL, NULL),
(15, 8, 63, NULL, NULL, 16, NULL, NULL, 'Days', 30000, 40, '', 2, 'Admin0', '2022-01-18', NULL, NULL),
(16, 8, 63, NULL, NULL, NULL, 2, 'Stationery', 'Number', 1000, 50, '', 3, 'Admin0', '2022-01-18', NULL, NULL),
(17, 8, 63, NULL, NULL, NULL, 3, 'SDA', 'Days', 30000, 30, '', 3, 'Admin0', '2022-01-18', NULL, NULL),
(18, 8, 63, NULL, NULL, NULL, 4, 'Hired tools', 'Days', 10000, 10, '', 3, 'Admin0', '2022-01-18', NULL, NULL),
(19, 8, 63, NULL, NULL, NULL, 5, 'Hiring of vehicles', 'Days', 10000, 15, '', 3, 'Admin0', '2022-01-18', NULL, NULL),
(22, 5, 59, NULL, 18, NULL, NULL, 'Labour', 'Workers', 1000, 300, '', 1, 'Admin0', '2022-01-19', NULL, NULL),
(24, 5, 59, NULL, 18, NULL, NULL, 'Item', 'Days', 1000000, 10, '', 1, 'Admin0', '2022-01-19', NULL, NULL),
(25, 5, 59, NULL, NULL, 6, NULL, NULL, 'Days', 20000, 2, '', 2, 'Admin0', '2022-01-19', NULL, NULL),
(26, 5, 59, NULL, NULL, 17, NULL, NULL, 'Days', 20000, 4, '', 2, 'Admin0', '2022-01-19', NULL, NULL),
(27, 5, 59, NULL, NULL, NULL, 2, 'Advertisement', 'Days', 10000, 3, '', 3, 'Admin0', '2022-01-19', NULL, NULL),
(28, 5, 59, NULL, NULL, NULL, 3, 'care Hire ', 'Days', 20000, 4, '', 3, 'Admin0', '2022-01-19', NULL, NULL),
(29, 5, 59, NULL, NULL, NULL, 4, 'NA', 'UNITS', 2000000, 35, '', 3, 'Admin0', '2022-01-19', NULL, NULL),
(30, 5, 59, NULL, NULL, NULL, 5, 'Transport', 'UNITS', 19470000, 1, '', 3, 'Admin0', '2022-01-19', NULL, NULL),
(31, 5, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 3, 'Admin0', '2022-01-19', NULL, NULL),
(85, 22, 96, NULL, 42, NULL, NULL, 'Labore quod laudanti', 'Quia eiusmod numquam', 22, 19, '418', 1, 'admin0', '2022-02-09', NULL, NULL),
(86, 22, 96, NULL, 43, NULL, NULL, 'Non nisi et maxime a', 'Reprehenderit facili', 56, 9, '504', 1, 'admin0', '2022-02-09', NULL, NULL),
(87, 22, 96, NULL, 44, NULL, NULL, 'Adipisci minim volup', 'Error sed at illo in', 55, 68, '3,7403,740', 1, 'admin0', '2022-02-09', NULL, NULL),
(88, 22, 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1,995,338', 2, 'admin0', '2022-02-09', NULL, NULL),
(89, 22, 96, NULL, NULL, 10, NULL, NULL, 'Vel dolore aliqua A', 1995338, 1, '1,995,338', 2, 'admin0', '2022-02-09', NULL, NULL),
(96, 24, 100, NULL, NULL, NULL, 5, 'Modi aut irure dolor', 'Illum voluptatem v', 4900, 230, '', 3, 'admin0', '2022-02-13', NULL, NULL),
(97, 24, 100, NULL, NULL, NULL, 4, 'Ducimus vel tenetur', 'Tempore molestiae e', 2800, 130, '', 3, 'admin0', '2022-02-13', NULL, NULL),
(98, 24, 100, NULL, NULL, NULL, 3, 'Fuga Cum atque aute', 'Duis qui autem fugia', 3600, 100, '', 3, 'admin0', '2022-02-13', NULL, NULL),
(99, 24, 100, NULL, NULL, NULL, 2, 'Cillum dolor eiusmod', 'Rem voluptatem quia ', 4500, 100, '', 3, 'admin0', '2022-02-13', NULL, NULL),
(100, 24, 100, NULL, NULL, 6, NULL, NULL, 'Quo irure earum volu', 2000, 100, '', 2, 'admin0', '2022-02-13', NULL, NULL),
(101, 24, 100, NULL, 45, NULL, NULL, 'Quae accusantium sus', 'Dignissimos quos cup', 100, 5000, '', 1, 'admin0', '2022-02-13', NULL, NULL),
(102, 24, 100, NULL, 45, NULL, NULL, 'Aut magni debitis no', 'Nostrud quos assumen', 1999, 1000, '', 1, 'admin0', '2022-02-13', NULL, NULL),
(115, 7, 61, NULL, NULL, NULL, 2, 'Description', '1', 14993074, 1, '', 3, 'admin0', '2022-02-25', NULL, NULL),
(116, 7, 61, NULL, NULL, 16, NULL, NULL, 'Cillum ullam minim n', 43, 23, '', 2, 'admin0', '2022-02-25', NULL, NULL),
(117, 7, 61, NULL, 16, NULL, NULL, 'Sint et ullam conse', 'Sed veritatis iste e', 21, 74, '1,554', 1, 'admin0', '2022-02-25', NULL, NULL),
(118, 7, 61, NULL, 15, NULL, NULL, 'Est minima quo dolo', 'Temporibus voluptate', 34, 34, '1,156', 1, 'admin0', '2022-02-25', NULL, NULL),
(119, 7, 61, NULL, 17, NULL, NULL, 'Non officiis aliquid', 'Autem blanditiis des', 91, 9, '819', 1, 'admin0', '2022-02-25', NULL, NULL),
(120, 7, 61, NULL, 14, NULL, NULL, 'Eveniet ut amet es', 'Et non soluta iusto ', 56, 43, '2,408', 1, 'admin0', '2022-02-25', NULL, NULL),
(121, 27, 105, NULL, 46, NULL, NULL, 'Labour', 'Day', 30000, 15, 'test', 1, 'admin0', '2022-02-25', NULL, NULL),
(122, 27, 105, NULL, 47, NULL, NULL, 'Machinery/equipment Hire ', 'Day', 90000, 20, 'test', 1, 'admin0', '2022-02-25', NULL, NULL),
(123, 27, 105, NULL, 47, NULL, NULL, 'Labour', 'Day', 50000, 25, 'Test', 1, 'admin0', '2022-02-25', NULL, NULL),
(124, 27, 105, NULL, NULL, 14, NULL, NULL, 'Day', 6000, 20, 'test', 2, 'admin0', '2022-02-25', NULL, NULL),
(125, 27, 105, NULL, NULL, 2, NULL, NULL, 'Day', 10000, 20, '', 2, 'admin0', '2022-02-25', NULL, NULL),
(126, 27, 105, NULL, NULL, NULL, 2, 'Admin', 'Day', 30000, 20, '', 3, 'admin0', '2022-02-25', NULL, NULL),
(127, 27, 105, NULL, NULL, NULL, 3, 'Travel/ lunches/ allowances', 'Days', 30000, 5, '', 3, 'admin0', '2022-02-25', NULL, NULL),
(128, 27, 105, NULL, 48, NULL, NULL, 'Labour', 'Day', 30000, 7, '', 1, 'admin0', '2022-02-25', NULL, NULL),
(129, 27, 105, NULL, NULL, NULL, 5, 'Travel ', 'Day', 30000, 12, '', 3, 'admin0', '2022-02-25', NULL, NULL),
(130, 27, 105, NULL, NULL, NULL, 4, 'Non Expendable equipment ', 'Day', 30000, 12, '', 3, 'admin0', '2022-02-25', NULL, NULL),
(133, 35, 131, NULL, 49, NULL, NULL, 'Labour', 'Days', 300, 30000, '', 1, 'Admin0', '2022-03-20', NULL, NULL),
(134, 35, 131, NULL, NULL, 25, NULL, NULL, 'days', 500, 1000, '', 2, 'Admin0', '2022-03-20', NULL, NULL),
(135, 35, 131, NULL, NULL, NULL, 2, 'materials', 'number', 500, 1000, '', 3, 'Admin0', '2022-03-20', NULL, NULL),
(136, 38, 134, NULL, 50, NULL, NULL, 'Hiring of equipment', 'Days', 50000, 10, 'test', 1, 'Admin0', '2022-03-24', NULL, NULL),
(137, 38, 134, NULL, 51, NULL, NULL, 'Labour', 'Days', 100000, 10, 'test', 1, 'Admin0', '2022-03-24', NULL, NULL),
(138, 38, 134, NULL, 52, NULL, NULL, 'Casual Labourers', 'Number', 50000, 13, 'test', 1, 'Admin0', '2022-03-24', NULL, NULL),
(139, 38, 134, NULL, 53, NULL, NULL, 'Casual Labourers', 'No.', 100000, 30, 'tset', 1, 'Admin0', '2022-03-24', NULL, NULL),
(141, 38, 134, NULL, NULL, 2, NULL, NULL, 'Days', 10000, 10, 'test', 2, 'Admin0', '2022-03-24', NULL, NULL),
(142, 38, 134, NULL, NULL, 8, NULL, NULL, 'Days', 10000, 10, 'Test', 2, 'Admin0', '2022-03-24', NULL, NULL),
(143, 38, 134, NULL, NULL, NULL, 2, 'Stationery', 'bales', 500, 100, 'test', 3, 'Admin0', '2022-03-24', NULL, NULL),
(145, 38, 134, NULL, NULL, NULL, 3, 'Lunch Allowances', 'Days', 20000, 10, 'Test', 3, 'Admin0', '2022-03-24', NULL, NULL),
(146, 39, 135, NULL, 54, NULL, NULL, 'Labour', 'days', 50000, 20, '', 1, 'Admin0', '2022-03-25', NULL, NULL),
(147, 39, 135, NULL, 55, NULL, NULL, 'Labour', 'days', 100000, 60, '', 1, 'Admin0', '2022-03-25', NULL, NULL),
(148, 39, 135, NULL, NULL, NULL, 2, 'stationery', 'units', 25000, 10, '', 3, 'Admin0', '2022-03-25', NULL, NULL),
(149, 39, 135, NULL, NULL, NULL, 3, 'Allow', 'days', 20000, 20, '', 3, 'Admin0', '2022-03-25', NULL, NULL),
(150, 39, 135, NULL, NULL, NULL, 4, 'Equipment', 'days', 10000, 10, '', 3, 'Admin0', '2022-03-25', NULL, NULL),
(151, 39, 135, NULL, NULL, 34, NULL, NULL, 'days', 25000, 10, '', 2, 'Admin0', '2022-03-25', NULL, NULL),
(152, 39, 135, NULL, 56, NULL, NULL, 'materials', 'units', 20000, 100, '', 1, 'Admin0', '2022-03-25', NULL, NULL),
(153, 40, 136, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 1, 'admin0', '2022-03-29', NULL, NULL),
(154, 40, 136, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 1, 'admin0', '2022-03-29', NULL, NULL),
(155, 40, 136, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 2, 'admin0', '2022-03-29', NULL, NULL),
(156, 40, 136, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 2, 'admin0', '2022-03-29', NULL, NULL),
(157, 40, 136, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 2, 'admin0', '2022-03-29', NULL, NULL),
(158, 44, 143, NULL, 59, NULL, NULL, 'Labour', 'Day', 150000, 100, '', 1, 'admin0', '2022-03-30', NULL, NULL),
(159, 44, 143, NULL, 60, NULL, NULL, 'Casuals', 'day', 200000, 30, '', 1, 'admin0', '2022-03-30', NULL, NULL),
(160, 44, 143, NULL, NULL, 15, NULL, NULL, 'dsa', 10000, 50, '', 2, 'admin0', '2022-03-30', NULL, NULL),
(161, 44, 143, NULL, NULL, NULL, 2, 'Supervision', 'days', 20000, 50, '', 3, 'admin0', '2022-03-30', NULL, NULL),
(162, 44, 143, NULL, NULL, NULL, 3, 'DSA', 'Days', 200000, 20, '', 3, 'admin0', '2022-03-30', NULL, NULL),
(163, 44, 143, NULL, NULL, NULL, 5, 'Travel', 'Days', 200000, 10, '', 3, 'admin0', '2022-03-30', NULL, NULL),
(164, 44, 143, NULL, NULL, 29, NULL, NULL, 'dsa', 15000, 100, '', 2, 'admin0', '2022-03-30', NULL, NULL),
(193, 45, 151, NULL, 61, NULL, NULL, 'Condoms', 'Packets', 100, 5000, '', 1, 'Admin0', '2022-03-30', NULL, NULL),
(194, 45, 151, NULL, 62, NULL, NULL, 'Transport', 'Days', 20000, 60, '', 1, 'Admin0', '2022-03-30', NULL, NULL),
(195, 45, 151, NULL, 62, NULL, NULL, 'Fix Dispensers', 'Number', 3000, 2000, '', 1, 'Admin0', '2022-03-30', NULL, NULL),
(196, 45, 151, NULL, NULL, 7, NULL, NULL, 'Days', 50000, 40, '', 2, 'Admin0', '2022-03-30', NULL, NULL),
(197, 45, 151, NULL, NULL, 19, NULL, NULL, 'Days', 10000, 30, '', 2, 'Admin0', '2022-03-30', NULL, NULL),
(198, 49, 164, NULL, 63, NULL, NULL, 'Labour', 'Days', 60000, 700, '', 1, 'Admin0', '2022-04-01', NULL, NULL),
(199, 49, 164, NULL, NULL, 29, NULL, NULL, 'Days', 10000, 800, '', 2, 'Admin0', '2022-04-01', NULL, NULL),
(200, 54, 169, NULL, 64, NULL, NULL, 'Purchase ', 'Dozzer', 8000000, 6, '', 1, 'admin0', '2022-04-03', NULL, NULL),
(201, 54, 169, NULL, 65, NULL, NULL, 'Inspection ', 'Dozzer', 300000, 6, '', 1, 'admin0', '2022-04-03', NULL, NULL),
(202, 54, 169, NULL, NULL, 6, NULL, NULL, 'Day', 10000, 20, '', 2, 'admin0', '2022-04-03', NULL, NULL),
(203, 55, 170, NULL, 66, NULL, NULL, 'Payment', 'Days', 3000, 100, '', 1, 'Admin0', '2022-04-04', NULL, NULL),
(204, 55, 170, NULL, 67, NULL, NULL, 'Hiring', 'Days', 10000, 30, '', 1, 'Admin0', '2022-04-04', NULL, NULL),
(205, 55, 170, NULL, 68, NULL, NULL, 'Transport', 'Days', 10000, 30, '', 1, 'Admin0', '2022-04-04', NULL, NULL),
(206, 55, 170, NULL, NULL, 4, NULL, NULL, 'Days', 10000, 10, '', 2, 'Admin0', '2022-04-04', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_evaluation_answers`
--

CREATE TABLE `tbl_project_evaluation_answers` (
  `id` int NOT NULL,
  `submissionid` int NOT NULL,
  `questionid` int NOT NULL,
  `answer` varchar(255) NOT NULL,
  `disaggregation` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_evaluation_answers`
--

INSERT INTO `tbl_project_evaluation_answers` (`id`, `submissionid`, `questionid`, `answer`, `disaggregation`) VALUES
(1, 1, 6, '0', NULL),
(2, 1, 7, '1', NULL),
(3, 1, 8, '1', NULL),
(4, 2, 6, '1', NULL),
(5, 2, 7, '0', NULL),
(6, 2, 8, '1', NULL),
(7, 3, 6, '0', NULL),
(8, 3, 7, '0', NULL),
(9, 3, 8, '0', NULL),
(10, 4, 6, '1', NULL),
(11, 4, 7, '1', NULL),
(12, 4, 8, '1', NULL),
(13, 5, 6, '1', NULL),
(14, 5, 7, '1', NULL),
(15, 5, 8, '1', NULL),
(16, 6, 6, '1', NULL),
(17, 6, 7, '1', NULL),
(18, 6, 8, '0', NULL),
(19, 7, 6, '1', NULL),
(20, 7, 7, '1', NULL),
(21, 7, 8, '1', NULL),
(22, 8, 6, '0', NULL),
(23, 8, 7, '1', NULL),
(24, 8, 8, '1', NULL),
(25, 9, 6, '1', NULL),
(26, 9, 7, '1', NULL),
(27, 9, 8, '1', NULL),
(28, 10, 6, '0', NULL),
(29, 10, 7, '0', NULL),
(30, 10, 8, '0', NULL),
(82, 62, 12, '1', NULL),
(83, 63, 12, '1', NULL),
(84, 64, 12, '1', NULL),
(85, 65, 12, '0', NULL),
(86, 66, 12, '0', NULL),
(87, 67, 13, '1', NULL),
(88, 68, 13, '1', NULL),
(89, 69, 13, '1', NULL),
(90, 70, 13, '0', NULL),
(91, 71, 13, '1', NULL),
(92, 72, 13, '0', NULL),
(93, 73, 13, '1', NULL),
(94, 74, 13, '0', NULL),
(95, 75, 13, '1', NULL),
(96, 76, 13, '1', NULL),
(97, 77, 14, '1', NULL),
(98, 78, 14, '1', NULL),
(99, 79, 14, '1', NULL),
(100, 80, 14, '1', NULL),
(101, 81, 14, '1', NULL),
(102, 82, 14, '1', NULL),
(103, 83, 14, '1', NULL),
(104, 84, 14, '1', NULL),
(105, 85, 14, '1', NULL),
(106, 86, 14, '1', NULL),
(107, 87, 14, '0', NULL),
(108, 88, 14, '0', NULL),
(109, 89, 14, '0', NULL),
(110, 90, 14, '0', NULL),
(111, 91, 14, '0', NULL),
(112, 92, 14, '0', NULL),
(113, 93, 14, '0', NULL),
(114, 94, 14, '0', NULL),
(115, 95, 14, '0', NULL),
(116, 96, 14, '0', NULL),
(117, 97, 14, '0', NULL),
(118, 98, 14, '0', NULL),
(119, 99, 14, '0', NULL),
(120, 100, 14, '0', NULL),
(121, 101, 14, '0', NULL),
(127, 108, 15, '1', 15),
(128, 109, 15, '1', 16),
(129, 110, 15, '1', 15),
(130, 111, 15, '1', 15),
(131, 112, 15, '0', 16),
(132, 113, 17, '1', 17),
(133, 114, 17, '1', 19),
(134, 115, 17, '0', 20),
(135, 116, 17, '1', 21),
(136, 117, 17, '0', 18);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_evaluation_conclusion`
--

CREATE TABLE `tbl_project_evaluation_conclusion` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `formid` int NOT NULL,
  `conclusion` text NOT NULL,
  `recommendation` text,
  `user` varchar(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_evaluation_forms`
--

CREATE TABLE `tbl_project_evaluation_forms` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `evaluation_id` int NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `responsible` int NOT NULL,
  `correspondents` text,
  `limit_type` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `responses_number` int DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `date_deployed` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_evaluation_form_questions`
--

CREATE TABLE `tbl_project_evaluation_form_questions` (
  `id` int NOT NULL,
  `formid` int NOT NULL,
  `sectionid` int NOT NULL,
  `question` varchar(255) NOT NULL,
  `field_type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_evaluation_form_question_fileds`
--

CREATE TABLE `tbl_project_evaluation_form_question_fileds` (
  `id` int NOT NULL,
  `formid` int NOT NULL,
  `sectionid` int NOT NULL,
  `fieldtype` varchar(255) NOT NULL,
  `access` varchar(255) DEFAULT NULL,
  `requirevalidoption` varchar(255) DEFAULT NULL,
  `fieldrole` varchar(255) DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `subtype` varchar(255) DEFAULT NULL,
  `style` varchar(255) DEFAULT NULL,
  `fieldname` varchar(255) NOT NULL,
  `classname` varchar(255) DEFAULT NULL,
  `placeholder` varchar(255) DEFAULT NULL,
  `fielddesc` varchar(255) DEFAULT NULL,
  `fieldrequired` varchar(255) NOT NULL,
  `toggle` varchar(255) DEFAULT NULL,
  `inline` varchar(255) DEFAULT NULL,
  `other` varchar(255) DEFAULT NULL,
  `fieldmaxlength` varchar(255) DEFAULT NULL,
  `fieldmin` varchar(255) DEFAULT NULL,
  `fieldmax` varchar(255) DEFAULT NULL,
  `step` varchar(255) DEFAULT NULL,
  `fieldvalue` varchar(255) NOT NULL,
  `multiple` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_evaluation_form_question_filed_values`
--

CREATE TABLE `tbl_project_evaluation_form_question_filed_values` (
  `id` int NOT NULL,
  `sectionid` int NOT NULL,
  `fieldid` int NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_evaluation_form_sections`
--

CREATE TABLE `tbl_project_evaluation_form_sections` (
  `id` int NOT NULL,
  `formid` int NOT NULL,
  `section` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_evaluation_submission`
--

CREATE TABLE `tbl_project_evaluation_submission` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `formid` int NOT NULL,
  `submission_code` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `level3` int NOT NULL,
  `location_disaggregation` int DEFAULT NULL,
  `submission_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_evaluation_submission`
--

INSERT INTO `tbl_project_evaluation_submission` (`id`, `projid`, `formid`, `submission_code`, `email`, `level3`, `location_disaggregation`, `submission_date`) VALUES
(1, 27, 5, '5793', 'fred@gmail.com', 342, NULL, '2022-03-17'),
(2, 27, 5, '5126', 'fred@gmail.com', 342, NULL, '2022-03-17'),
(3, 27, 5, '5145', 'fred@gmail.com', 342, NULL, '2022-03-17'),
(4, 27, 5, '5782', 'fred@gmail.com', 342, NULL, '2022-03-17'),
(5, 27, 5, '5431', 'fred@gmail.com', 342, NULL, '2022-03-17'),
(6, 27, 5, '5958', 'fred@gmail.com', 344, NULL, '2022-03-17'),
(7, 27, 5, '5370', 'fred@gmail.com', 344, NULL, '2022-03-17'),
(8, 27, 5, '5146', 'fred@gmail.com', 344, NULL, '2022-03-17'),
(9, 27, 5, '5150', 'fred@gmail.com', 344, NULL, '2022-03-17'),
(10, 27, 5, '5162', 'fred@gmail.com', 344, NULL, '2022-03-17'),
(62, 35, 9, '9784', 'denkytheka@gmail.com', 389, NULL, '2022-03-29'),
(63, 35, 9, '9097', 'denkytheka@gmail.com', 389, NULL, '2022-03-29'),
(64, 35, 9, '9196', 'denkytheka@gmail.com', 389, NULL, '2022-03-29'),
(65, 35, 9, '9924', 'denkytheka@gmail.com', 389, NULL, '2022-03-29'),
(66, 35, 9, '9281', 'denkytheka@gmail.com', 389, NULL, '2022-03-29'),
(67, 38, 8, '8321', 'denkytheka@gmail.com', 389, NULL, '2022-03-29'),
(68, 38, 8, '8250', 'denkytheka@gmail.com', 390, NULL, '2022-03-29'),
(69, 38, 8, '8619', 'denkytheka@gmail.com', 389, NULL, '2022-03-29'),
(70, 38, 8, '8809', 'denkytheka@gmail.com', 390, NULL, '2022-03-29'),
(71, 38, 8, '8769', 'denkytheka@gmail.com', 389, NULL, '2022-03-29'),
(72, 38, 8, '8280', 'denkytheka@gmail.com', 390, NULL, '2022-03-29'),
(73, 38, 8, '8204', 'denkytheka@gmail.com', 389, NULL, '2022-03-29'),
(74, 38, 8, '8452', 'denkytheka@gmail.com', 390, NULL, '2022-03-29'),
(75, 38, 8, '8204', 'denkytheka@gmail.com', 389, NULL, '2022-03-29'),
(76, 38, 8, '8876', 'denkytheka@gmail.com', 390, NULL, '2022-03-29'),
(77, 44, 10, '10561', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(78, 44, 10, '10560', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(79, 44, 10, '10123', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(80, 44, 10, '10301', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(81, 44, 10, '10465', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(82, 44, 10, '10398', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(83, 44, 10, '10513', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(84, 44, 10, '10908', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(85, 44, 10, '10230', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(86, 44, 10, '10561', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(87, 44, 10, '10375', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(88, 44, 10, '10108', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(89, 44, 10, '10128', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(90, 44, 10, '10972', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(91, 44, 10, '10251', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(92, 44, 10, '10317', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(93, 44, 10, '10758', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(94, 44, 10, '10570', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(95, 44, 10, '10264', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(96, 44, 10, '10875', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(97, 44, 10, '10894', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(98, 44, 10, '10237', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(99, 44, 10, '10432', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(100, 44, 10, '10154', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(101, 44, 10, '10546', 'kiplish@gmail.com', 352, NULL, '2022-03-30'),
(108, 22, 11, '11486', 'kkipe15@gmail.com', 404, NULL, '2022-04-01'),
(109, 22, 11, '11491', 'kkipe15@gmail.com', 404, NULL, '2022-04-01'),
(110, 22, 11, '11028', 'kkipe15@gmail.com', 404, NULL, '2022-04-01'),
(111, 22, 11, '11430', 'kkipe15@gmail.com', 404, NULL, '2022-04-01'),
(112, 22, 11, '11724', 'kkipe15@gmail.com', 404, NULL, '2022-04-01'),
(113, 55, 12, '12590', 'kiplish@gmail.com', 409, NULL, '2022-04-04'),
(114, 55, 12, '12623', 'kiplish@gmail.com', 409, NULL, '2022-04-04'),
(115, 55, 12, '12210', 'kiplish@gmail.com', 409, NULL, '2022-04-04'),
(116, 55, 12, '12732', 'kiplish@gmail.com', 409, NULL, '2022-04-04'),
(117, 55, 12, '12920', 'kiplish@gmail.com', 409, NULL, '2022-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_evaluation_types`
--

CREATE TABLE `tbl_project_evaluation_types` (
  `id` int NOT NULL,
  `type` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `sequence` int NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_evaluation_types`
--

INSERT INTO `tbl_project_evaluation_types` (`id`, `type`, `description`, `sequence`, `active`) VALUES
(1, 'Process Evaluation', 'Checks project implementation process', 4, 1),
(2, 'Assessment', 'This checks if a problem or issue has been resolved!!', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_expected_impact_details`
--

CREATE TABLE `tbl_project_expected_impact_details` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `impact` varchar(255) NOT NULL,
  `indid` int NOT NULL,
  `target` decimal(10,0) DEFAULT NULL,
  `data_source` varchar(100) NOT NULL,
  `data_collection_method` int DEFAULT NULL,
  `evaluation_frequency` int NOT NULL,
  `next_evaluation_date` date DEFAULT NULL,
  `responsible` varchar(100) DEFAULT NULL,
  `report_user` varchar(100) DEFAULT NULL,
  `reporting_timeline` varchar(255) DEFAULT NULL,
  `added_by` varchar(100) NOT NULL,
  `date_added` date NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_expected_outcome_details`
--

CREATE TABLE `tbl_project_expected_outcome_details` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `target` decimal(10,0) DEFAULT NULL,
  `data_source` varchar(100) NOT NULL,
  `data_collection_method` int DEFAULT NULL,
  `evaluation_frequency` int NOT NULL,
  `next_evaluation_date` date DEFAULT NULL,
  `responsible` varchar(100) DEFAULT NULL,
  `report_user` varchar(100) DEFAULT NULL,
  `reporting_timeline` varchar(255) DEFAULT NULL,
  `added_by` varchar(100) NOT NULL,
  `date_added` date NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_expected_outcome_details`
--

INSERT INTO `tbl_project_expected_outcome_details` (`id`, `projid`, `target`, `data_source`, `data_collection_method`, `evaluation_frequency`, `next_evaluation_date`, `responsible`, `report_user`, `reporting_timeline`, `added_by`, `date_added`, `changed_by`, `date_changed`) VALUES
(1, 8, NULL, '1', NULL, 1, NULL, NULL, NULL, NULL, 'Admin0', '2022-01-18', '33', '2022-02-05'),
(2, 24, NULL, '1', NULL, 1, NULL, NULL, NULL, NULL, 'Admin0', '2022-02-13', NULL, NULL),
(3, 27, NULL, '1', NULL, 3, NULL, NULL, NULL, NULL, 'admin0', '2022-02-28', '33', '2022-03-18'),
(6, 35, NULL, '1', NULL, 1, NULL, NULL, NULL, NULL, '1', '2022-03-24', NULL, NULL),
(7, 38, NULL, '1', NULL, 1, NULL, NULL, NULL, NULL, '1', '2022-03-24', NULL, NULL),
(8, 39, NULL, '2', NULL, 1, NULL, NULL, NULL, NULL, '1', '2022-03-26', NULL, NULL),
(9, 44, NULL, '1', NULL, 1, NULL, NULL, NULL, NULL, '1', '2022-03-30', NULL, NULL),
(10, 22, NULL, '1', NULL, 1, NULL, NULL, NULL, NULL, '1', '2022-03-31', '33', '2022-04-04'),
(11, 45, NULL, '2', NULL, 1, NULL, NULL, NULL, NULL, '1', '2022-04-04', NULL, NULL),
(12, 49, NULL, '2', NULL, 1, NULL, NULL, NULL, NULL, '1', '2022-04-04', NULL, NULL),
(13, 55, NULL, '1', NULL, 1, NULL, NULL, NULL, NULL, '1', '2022-04-04', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_expenditure_timeline`
--

CREATE TABLE `tbl_project_expenditure_timeline` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `type` int NOT NULL,
  `plan_id` int NOT NULL,
  `expenditure_description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `cost` double DEFAULT NULL,
  `disbursement_date` date NOT NULL,
  `responsible` int DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `update_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_expenditure_timeline`
--

INSERT INTO `tbl_project_expenditure_timeline` (`id`, `projid`, `outputid`, `type`, `plan_id`, `expenditure_description`, `cost`, `disbursement_date`, `responsible`, `created_by`, `date_created`, `update_by`, `date_updated`) VALUES
(1, 9, 69, 2, 1, NULL, NULL, '2021-09-15', NULL, 'admin0', '2022-01-18', NULL, NULL),
(2, 9, 69, 2, 2, NULL, NULL, '2021-09-15', NULL, 'admin0', '2022-01-18', NULL, NULL),
(4, 8, 63, 2, 14, NULL, NULL, '2021-08-13', NULL, 'Admin0', '2022-01-18', NULL, NULL),
(5, 8, 63, 2, 15, NULL, NULL, '2021-11-10', NULL, 'Admin0', '2022-01-18', NULL, NULL),
(6, 8, 63, 3, 16, NULL, NULL, '2021-12-03', 16, 'Admin0', '2022-01-18', NULL, NULL),
(7, 8, 63, 3, 17, NULL, NULL, '2022-01-01', 15, 'Admin0', '2022-01-18', NULL, NULL),
(8, 8, 63, 3, 18, NULL, NULL, '2021-07-02', 16, 'Admin0', '2022-01-18', NULL, NULL),
(9, 8, 63, 3, 19, NULL, NULL, '2022-01-01', 16, 'Admin0', '2022-01-18', NULL, NULL),
(10, 5, 59, 2, 25, NULL, NULL, '2022-01-19', NULL, 'Admin0', '2022-01-19', NULL, NULL),
(11, 5, 59, 2, 26, NULL, NULL, '2022-01-19', NULL, 'Admin0', '2022-01-19', NULL, NULL),
(12, 5, 59, 3, 27, NULL, NULL, '2022-01-19', 6, 'Admin0', '2022-01-19', NULL, NULL),
(13, 5, 59, 3, 28, NULL, NULL, '2022-01-19', 17, 'Admin0', '2022-01-19', NULL, NULL),
(14, 5, 59, 3, 29, NULL, NULL, '2022-01-19', 14, 'Admin0', '2022-01-19', NULL, NULL),
(15, 5, 59, 3, 30, NULL, NULL, '2022-01-19', 14, 'Admin0', '2022-01-19', NULL, NULL),
(16, 5, 59, 3, 31, NULL, NULL, '2022-01-19', 14, 'Admin0', '2022-01-19', NULL, NULL),
(40, 22, 96, 2, 88, NULL, NULL, '2021-07-22', NULL, 'admin0', '2022-02-09', NULL, NULL),
(41, 22, 96, 2, 89, NULL, NULL, '2021-07-22', NULL, 'admin0', '2022-02-09', NULL, NULL),
(47, 24, 100, 3, 96, NULL, NULL, '2022-01-06', 6, 'admin0', '2022-02-13', NULL, NULL),
(48, 24, 100, 3, 97, NULL, NULL, '2022-01-04', 14, 'admin0', '2022-02-13', NULL, NULL),
(49, 24, 100, 3, 98, NULL, NULL, '2022-01-05', 17, 'admin0', '2022-02-13', NULL, NULL),
(50, 24, 100, 3, 99, NULL, NULL, '2022-01-06', 14, 'admin0', '2022-02-13', NULL, NULL),
(51, 24, 100, 2, 100, NULL, NULL, '2022-01-11', NULL, 'admin0', '2022-02-13', NULL, NULL),
(56, 7, 61, 3, 115, NULL, NULL, '2022-02-24', 16, 'admin0', '2022-02-25', NULL, NULL),
(57, 7, 61, 2, 116, NULL, NULL, '2022-02-24', NULL, 'admin0', '2022-02-25', NULL, NULL),
(58, 27, 105, 2, 124, NULL, NULL, '2021-10-20', NULL, 'admin0', '2022-02-25', NULL, NULL),
(59, 27, 105, 2, 125, NULL, NULL, '2021-10-20', NULL, 'admin0', '2022-02-25', NULL, NULL),
(60, 27, 105, 3, 126, NULL, NULL, '2021-09-16', 2, 'admin0', '2022-02-25', NULL, NULL),
(61, 27, 105, 3, 127, NULL, NULL, '2021-09-30', 14, 'admin0', '2022-02-25', NULL, NULL),
(62, 27, 105, 3, 129, NULL, NULL, '2021-10-15', 14, 'admin0', '2022-02-25', NULL, NULL),
(63, 27, 105, 3, 130, NULL, NULL, '2021-09-30', 2, 'admin0', '2022-02-25', NULL, NULL),
(64, 35, 131, 2, 134, NULL, NULL, '2021-08-20', NULL, 'Admin0', '2022-03-20', NULL, NULL),
(65, 35, 131, 3, 135, NULL, NULL, '2021-08-21', 25, 'Admin0', '2022-03-20', NULL, NULL),
(67, 38, 134, 2, 141, NULL, NULL, '2022-07-01', NULL, 'Admin0', '2022-03-24', NULL, NULL),
(68, 38, 134, 2, 142, NULL, NULL, '2022-07-01', NULL, 'Admin0', '2022-03-24', NULL, NULL),
(69, 38, 134, 3, 143, NULL, NULL, '2022-07-01', 2, 'Admin0', '2022-03-24', NULL, NULL),
(71, 38, 134, 3, 145, NULL, NULL, '2022-07-01', 9, 'Admin0', '2022-03-24', NULL, NULL),
(72, 39, 135, 3, 148, NULL, NULL, '2022-07-01', 34, 'Admin0', '2022-03-25', NULL, NULL),
(73, 39, 135, 3, 149, NULL, NULL, '2022-07-02', 34, 'Admin0', '2022-03-25', NULL, NULL),
(74, 39, 135, 3, 150, NULL, NULL, '2022-07-01', 34, 'Admin0', '2022-03-25', NULL, NULL),
(75, 39, 135, 2, 151, NULL, NULL, '2022-07-01', NULL, 'Admin0', '2022-03-25', NULL, NULL),
(76, 40, 136, 2, 155, NULL, NULL, '2022-09-30', NULL, 'admin0', '2022-03-29', NULL, NULL),
(77, 40, 136, 2, 156, NULL, NULL, '2022-09-30', NULL, 'admin0', '2022-03-29', NULL, NULL),
(78, 40, 136, 2, 157, NULL, NULL, '2022-10-20', NULL, 'admin0', '2022-03-29', NULL, NULL),
(79, 44, 143, 2, 160, NULL, NULL, '2022-09-30', NULL, 'admin0', '2022-03-30', NULL, NULL),
(80, 44, 143, 3, 161, NULL, NULL, '2022-09-30', 29, 'admin0', '2022-03-30', NULL, NULL),
(81, 44, 143, 3, 162, NULL, NULL, '2022-10-29', 29, 'admin0', '2022-03-30', NULL, NULL),
(82, 44, 143, 3, 163, NULL, NULL, '2022-08-31', 29, 'admin0', '2022-03-30', NULL, NULL),
(83, 44, 143, 2, 164, NULL, NULL, '2022-11-30', NULL, 'admin0', '2022-03-30', NULL, NULL),
(96, 45, 151, 2, 196, NULL, NULL, '2021-07-01', NULL, 'Admin0', '2022-03-30', NULL, NULL),
(97, 45, 151, 2, 197, NULL, NULL, '2021-07-01', NULL, 'Admin0', '2022-03-30', NULL, NULL),
(98, 49, 164, 2, 199, NULL, NULL, '2021-07-02', NULL, 'Admin0', '2022-04-01', NULL, NULL),
(99, 54, 169, 2, 202, NULL, NULL, '2022-04-04', NULL, 'admin0', '2022-04-03', NULL, NULL),
(100, 55, 170, 2, 206, NULL, NULL, '2021-07-02', NULL, 'Admin0', '2022-04-04', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_form_markers`
--

CREATE TABLE `tbl_project_form_markers` (
  `id` int NOT NULL,
  `form_id` int NOT NULL,
  `level3` int NOT NULL,
  `location` int DEFAULT NULL,
  `respondent` int NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `createddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_history_results_level_disaggregation`
--

CREATE TABLE `tbl_project_history_results_level_disaggregation` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `ben_id` int DEFAULT NULL,
  `projoutputid` int DEFAULT NULL,
  `opstate` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `value` decimal(10,0) NOT NULL,
  `type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_history_results_level_disaggregation`
--

INSERT INTO `tbl_project_history_results_level_disaggregation` (`id`, `projid`, `ben_id`, `projoutputid`, `opstate`, `name`, `value`, `type`) VALUES
(1, 2, NULL, 55, 370, '', '1', 3),
(2, 9, NULL, 69, 354, '', '2', 3),
(3, 9, NULL, 70, 354, '', '10', 3),
(4, 9, NULL, 72, 354, '', '1', 3),
(5, 1, NULL, 1, 366, '', '2', 3),
(6, 1, NULL, 1, 367, '', '3', 3),
(7, 4, NULL, 57, 402, '', '1', 3),
(8, 3, NULL, 56, 365, '', '1', 3),
(9, 11, NULL, 76, 346, '', '1', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_implementation_method`
--

CREATE TABLE `tbl_project_implementation_method` (
  `id` int NOT NULL,
  `method` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_implementation_method`
--

INSERT INTO `tbl_project_implementation_method` (`id`, `method`, `description`, `status`) VALUES
(1, 'In House', 'Project implemented by the organization without out-sourcing', 1),
(2, 'Contractor/Consultant', 'Project implemented by the organization using contractor or consultant ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_inspection_checklist`
--

CREATE TABLE `tbl_project_inspection_checklist` (
  `ckid` int NOT NULL,
  `projid` int DEFAULT NULL,
  `msid` int NOT NULL,
  `taskid` int NOT NULL,
  `questionid` int NOT NULL,
  `score` int DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `assigned_by` varchar(50) DEFAULT NULL,
  `date_assigned` date DEFAULT NULL,
  `assignee_comments` int DEFAULT NULL,
  `inspector` int DEFAULT NULL,
  `date_inspected` date DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_inspection_checklist`
--

INSERT INTO `tbl_project_inspection_checklist` (`ckid`, `projid`, `msid`, `taskid`, `questionid`, `score`, `status`, `created_by`, `date_created`, `assigned_by`, `date_assigned`, `assignee_comments`, `inspector`, `date_inspected`, `updated_by`, `date_updated`) VALUES
(1, 5, 18, 18, 3, 0, 0, '55', '2022-02-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 5, 18, 18, 4, 0, 0, '55', '2022-02-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 5, 18, 18, 5, 0, 0, '55', '2022-02-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_inspection_checklist_comments`
--

CREATE TABLE `tbl_project_inspection_checklist_comments` (
  `id` int NOT NULL,
  `comments` varchar(255) NOT NULL,
  `assignmentdays` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_inspection_checklist_score`
--

CREATE TABLE `tbl_project_inspection_checklist_score` (
  `ckid` int NOT NULL,
  `assignment_id` int NOT NULL,
  `questionid` int NOT NULL,
  `taskid` int NOT NULL,
  `score` int DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `comments` int DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_inspection_gis_location`
--

CREATE TABLE `tbl_project_inspection_gis_location` (
  `id` int NOT NULL,
  `assignment_id` int NOT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `gisfailuremsg` varchar(100) DEFAULT NULL,
  `user` varchar(50) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_inspection_noncompliance_comments`
--

CREATE TABLE `tbl_project_inspection_noncompliance_comments` (
  `id` int NOT NULL,
  `assignment_id` int NOT NULL,
  `task_id` int NOT NULL,
  `question_id` int NOT NULL,
  `comments` text NOT NULL,
  `attachment_path` varchar(255) NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_inspection_status`
--

CREATE TABLE `tbl_project_inspection_status` (
  `id` int NOT NULL,
  `status` varchar(100) NOT NULL,
  `days` int DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_inspection_status`
--

INSERT INTO `tbl_project_inspection_status` (`id`, `status`, `days`, `description`) VALUES
(1, 'Pending Assignment', 5, NULL),
(2, 'Assignment Overdue', NULL, NULL),
(3, 'Pending Inspection', 10, NULL),
(4, 'Inspection Overdue', NULL, NULL),
(5, 'Fully Complied', NULL, NULL),
(6, 'Partial Complied', 30, NULL),
(7, 'Inspected', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_mapping`
--

CREATE TABLE `tbl_project_mapping` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `ptid` varchar(255) NOT NULL,
  `stid` int NOT NULL,
  `responsible` int NOT NULL,
  `mapping_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_mapping`
--

INSERT INTO `tbl_project_mapping` (`id`, `projid`, `outputid`, `location`, `ptid`, `stid`, `responsible`, `mapping_date`) VALUES
(1, 27, 105, NULL, '2,14', 342, 2, '2022-02-26'),
(2, 27, 105, NULL, '2,14', 344, 14, '2022-02-26'),
(14, 2, 55, NULL, '3', 370, 3, '2022-03-21'),
(4, 35, 131, NULL, '25', 389, 25, '2022-03-21'),
(5, 38, 134, NULL, '8,9', 389, 8, '2022-03-24'),
(6, 38, 134, NULL, '10,11', 390, 10, '2022-03-24'),
(13, 39, 135, NULL, '35', 356, 35, '2022-03-25'),
(15, 25, 101, NULL, '3', 397, 3, '2022-03-25'),
(16, 29, 122, NULL, '29', 409, 29, '2022-03-28'),
(17, 29, 122, NULL, '29', 409, 29, '2022-03-28'),
(18, 29, 122, NULL, '29', 409, 29, '2022-03-28'),
(19, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(20, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(21, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(22, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(23, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(24, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(25, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(26, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(27, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(28, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(29, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(30, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(31, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(32, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(33, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(34, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(35, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(36, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(37, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(38, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(39, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(40, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(41, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(42, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(43, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(44, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(45, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(46, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(47, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(48, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(49, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(50, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(51, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(52, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(53, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(54, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(55, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(56, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(57, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(58, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(59, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(60, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(61, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(62, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(63, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(64, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(65, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(66, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(67, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(68, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(69, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(70, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(71, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(72, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(73, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(74, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(75, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(76, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(77, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(78, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(79, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(80, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(81, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(82, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(83, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(84, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(85, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(86, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(87, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(88, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(89, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(90, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(91, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(92, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(93, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(94, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(95, 43, 142, NULL, '14', 385, 14, '2022-03-30'),
(96, 43, 142, NULL, '17', 384, 17, '2022-03-30'),
(97, 40, 136, NULL, '19,17,15', 373, 19, '2022-03-30'),
(98, 44, 143, NULL, '15,29', 352, 15, '2022-03-30'),
(99, 26, 104, NULL, '11', 374, 11, '2022-03-31'),
(100, 26, 104, NULL, '11', 374, 11, '2022-03-31'),
(101, 26, 104, NULL, '11', 374, 11, '2022-03-31'),
(102, 48, 163, NULL, '3', 404, 3, '2022-04-01'),
(103, 49, 164, NULL, '29', 357, 29, '2022-04-02'),
(104, 49, 164, NULL, '30', 358, 30, '2022-04-02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_monitoring_checklist`
--

CREATE TABLE `tbl_project_monitoring_checklist` (
  `ckid` int NOT NULL,
  `projid` int DEFAULT NULL,
  `taskid` int NOT NULL,
  `name` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `score` int DEFAULT '0',
  `created_by` varchar(100) DEFAULT NULL,
  `date_created` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_monitoring_checklist`
--

INSERT INTO `tbl_project_monitoring_checklist` (`ckid`, `projid`, `taskid`, `name`, `score`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(1, 8, 2, 'Vegetation and topsoil removed and deposit 100 metres away and later spread and level on site where directed', 0, '55', '2022-01-18', NULL, NULL),
(3, 8, 4, 'Approved murram filling to make up levels, well rammed and consolidated in 150mm thick layers', 0, '4', '2022-01-18', NULL, NULL),
(4, 8, 4, '300mm thick approved hardcore filling spread, levelled, well rammed and consolidated in 150mm thick layers to receive concrete surfacec bed', 0, '4', '2022-01-18', NULL, NULL),
(5, 8, 6, 'Masonry walls done', 0, '55', '2022-01-18', NULL, NULL),
(6, 8, 6, 'Masonry walls done', 0, '55', '2022-01-18', NULL, NULL),
(7, 8, 8, 'Ring beams done', 0, '55', '2022-01-18', NULL, NULL),
(8, 8, 9, 'Reinforcements for lintels done', 0, '55', '2022-01-18', NULL, NULL),
(9, 8, 10, 'Timber trusses hoisting and fixing in position done', 0, '55', '2022-01-18', NULL, NULL),
(10, 8, 10, 'Timber trusses hoisting and fixing in position done', 0, '55', '2022-01-18', NULL, NULL),
(11, 8, 13, 'Fixing of iron sheets done', 0, '55', '2022-01-18', NULL, NULL),
(12, 24, 45, 'Item1', 0, '55', '2022-02-13', NULL, NULL),
(13, 24, 45, 'Item2', 0, '55', '2022-02-13', NULL, NULL),
(14, 24, 45, 'Item3', 0, '55', '2022-02-13', NULL, NULL),
(15, 24, 45, 'Item3', 0, '55', '2022-02-13', NULL, NULL),
(16, 24, 45, 'Item4', 0, '55', '2022-02-13', NULL, NULL),
(33, 27, 48, 'CHECKLIST1', 0, '48', '2022-02-28', NULL, NULL),
(34, 27, 48, 'CHECKLUIST2', 0, '48', '2022-02-28', NULL, NULL),
(35, 27, 48, 'CHECKLIST4', 0, '48', '2022-02-28', NULL, NULL),
(36, 27, 48, 'CHECKLIST6', 0, '48', '2022-02-28', NULL, NULL),
(37, 27, 48, 'CHECKLISTZ', 0, '48', '2022-02-28', NULL, NULL),
(38, 27, 48, 'CHECKLIST9', 0, '48', '2022-02-28', NULL, NULL),
(39, 27, 48, 'TEST', 0, '48', '2022-02-28', NULL, NULL),
(40, 27, 46, 'test', 0, '46', '2022-02-28', NULL, NULL),
(41, 27, 46, 'TEST', 0, '46', '2022-02-28', NULL, NULL),
(47, 27, 47, 'TEST', 0, '47', '2022-02-28', NULL, NULL),
(48, 27, 47, 'CHECKLIST1', 0, '47', '2022-02-28', NULL, NULL),
(49, 27, 47, 'CHECKLIST 3', 0, '47', '2022-02-28', NULL, NULL),
(50, 27, 47, 'CHECKLIST 4', 0, '47', '2022-02-28', NULL, NULL),
(51, 27, 47, 'CHECKLIST 5', 0, '47', '2022-02-28', NULL, NULL),
(54, 35, 49, 'Titles issued', 0, '49', '2022-03-23', NULL, NULL),
(55, 38, 50, 'Excavators on the site', 0, '55', '2022-03-24', NULL, NULL),
(58, 38, 50, 'List of casuals recruited', 0, '55', '2022-03-24', NULL, NULL),
(59, 38, 51, 'Foundation done', 0, '55', '2022-03-24', NULL, NULL),
(60, 38, 51, 'Walling done', 0, '55', '2022-03-24', NULL, NULL),
(61, 38, 51, 'Roofing done', 0, '55', '2022-03-24', NULL, NULL),
(62, 38, 51, 'Finishing done', 0, '55', '2022-03-24', NULL, NULL),
(63, 38, 51, 'Doors and windows fixed', 0, '55', '2022-03-24', NULL, NULL),
(65, 38, 52, 'Map of the road', 0, '52', '2022-03-24', NULL, NULL),
(66, 38, 53, 'Excavation of the ground', 0, '55', '2022-03-24', NULL, NULL),
(67, 38, 53, 'Culverts at the site', 0, '55', '2022-03-24', NULL, NULL),
(68, 38, 53, 'Fixing of the culverts', 0, '55', '2022-03-24', NULL, NULL),
(69, 38, 53, 'Backfilling', 0, '55', '2022-03-24', NULL, NULL),
(70, 39, 54, 'Excavation of the ground done', 0, '55', '2022-03-26', NULL, NULL),
(71, 39, 54, 'Excavation of the ground done', 0, '55', '2022-03-26', NULL, NULL),
(72, 39, 54, 'Excavation of the ground done', 0, '55', '2022-03-26', NULL, NULL),
(73, 39, 54, 'Laying of foundation stones done', 0, '55', '2022-03-26', NULL, NULL),
(74, 39, 54, 'Laying of foundation stones done', 0, '55', '2022-03-26', NULL, NULL),
(75, 39, 54, 'Laying of foundation stones done', 0, '55', '2022-03-26', NULL, NULL),
(76, 39, 55, 'Walling done', 0, '55', '2022-03-26', NULL, NULL),
(77, 39, 55, 'Walling done', 0, '55', '2022-03-26', NULL, NULL),
(78, 39, 56, 'Roofing done', 0, '55', '2022-03-26', NULL, NULL),
(79, 44, 59, 'checklist1', 0, '55', '2022-03-30', NULL, NULL),
(80, 44, 59, 'checklist2', 0, '55', '2022-03-30', NULL, NULL),
(81, 44, 59, 'checklist3', 0, '55', '2022-03-30', NULL, NULL),
(82, 44, 59, 'checklist4', 0, '55', '2022-03-30', NULL, NULL),
(83, 44, 59, 'checklist5', 0, '55', '2022-03-30', NULL, NULL),
(84, 44, 60, 'checklist1', 0, '55', '2022-03-30', NULL, NULL),
(85, 44, 60, 'checklist2', 0, '55', '2022-03-30', NULL, NULL),
(86, 44, 60, 'checklist3', 0, '55', '2022-03-30', NULL, NULL),
(87, 44, 60, 'checklist4', 0, '55', '2022-03-30', NULL, NULL),
(88, 44, 60, 'checklist4', 0, '55', '2022-03-30', NULL, NULL),
(89, 45, 61, 'Report on supplied condoms', 0, '55', '2022-04-04', NULL, NULL),
(90, 45, 62, 'List of distributed condoms', 0, '55', '2022-04-04', NULL, NULL),
(91, 49, 63, 'Vegetation and topsoil removed and deposit 100 metres away and later spread and level on site where directed', 0, '55', '2022-04-04', NULL, NULL),
(92, 49, 63, 'Approved murram filling to make up levels, well rammed and consolidated in 150mm thick layers', 0, '55', '2022-04-04', NULL, NULL),
(93, 49, 63, '300mm thick approved hardcore filling spread, levelled, well rammed and consolidated in 150mm thick layers to receive concrete surface bed', 0, '55', '2022-04-04', NULL, NULL),
(98, 54, 65, 'has been Inspected and conforms to specifications', 0, '55', '2022-04-04', NULL, NULL),
(99, 54, 64, 'delivered ', 0, '64', '2022-04-04', NULL, NULL),
(100, 54, 64, 'checklist2', 0, '64', '2022-04-04', NULL, NULL),
(101, 54, 64, 'delivered ', 0, '64', '2022-04-04', NULL, NULL),
(102, 54, 64, 'checklist2', 0, '64', '2022-04-04', NULL, NULL),
(103, 54, 64, 'testing', 0, '64', '2022-04-04', NULL, NULL),
(104, 55, 66, 'Training Manual', 0, '55', '2022-04-04', NULL, NULL),
(105, 55, 66, 'Attendance List', 0, '55', '2022-04-04', NULL, NULL),
(106, 55, 66, 'Training Manual', 0, '55', '2022-04-04', NULL, NULL),
(107, 55, 66, 'Attendance List', 0, '55', '2022-04-04', NULL, NULL),
(108, 55, 67, 'Signed contract form', 0, '55', '2022-04-04', NULL, NULL),
(109, 55, 68, 'List of women who have received chicken', 0, '55', '2022-04-04', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_monitoring_checklist_noncompliance_comments`
--

CREATE TABLE `tbl_project_monitoring_checklist_noncompliance_comments` (
  `id` int NOT NULL,
  `ckid` int NOT NULL,
  `comments` varchar(255) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_monitoring_checklist_score`
--

CREATE TABLE `tbl_project_monitoring_checklist_score` (
  `id` int NOT NULL,
  `taskid` int NOT NULL,
  `checklistid` int NOT NULL,
  `formid` varchar(100) NOT NULL,
  `score` float NOT NULL DEFAULT '0',
  `level3` int NOT NULL,
  `level4` int DEFAULT NULL,
  `responsible` int NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_monitoring_checklist_score`
--

INSERT INTO `tbl_project_monitoring_checklist_score` (`id`, `taskid`, `checklistid`, `formid`, `score`, `level3`, `level4`, `responsible`, `date`) VALUES
(1, 2, 1, 'GRMNN', 3, 361, NULL, 8, '2022-01-20 00:00:00'),
(2, 4, 3, 'GRMNN', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(3, 4, 4, 'GRMNN', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(4, 6, 5, 'GRMNN', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(5, 6, 6, 'GRMNN', 1, 361, NULL, 8, '2022-01-20 00:00:00'),
(6, 8, 7, 'GRMNN', 1, 361, NULL, 8, '2022-01-20 00:00:00'),
(7, 9, 8, 'GRMNN', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(8, 10, 9, 'GRMNN', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(9, 10, 10, 'GRMNN', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(10, 13, 11, 'GRMNN', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(11, 2, 1, 'GRMST', 3, 361, NULL, 8, '2022-01-20 00:00:00'),
(12, 4, 3, 'GRMST', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(13, 4, 4, 'GRMST', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(14, 6, 5, 'GRMST', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(15, 6, 6, 'GRMST', 1, 361, NULL, 8, '2022-01-20 00:00:00'),
(16, 8, 7, 'GRMST', 1, 361, NULL, 8, '2022-01-20 00:00:00'),
(17, 9, 8, 'GRMST', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(18, 10, 9, 'GRMST', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(19, 10, 10, 'GRMST', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(20, 13, 11, 'GRMST', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(21, 2, 1, 'GRNHZ', 3, 361, NULL, 8, '2022-01-20 00:00:00'),
(22, 4, 3, 'GRNHZ', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(23, 4, 4, 'GRNHZ', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(24, 6, 5, 'GRNHZ', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(25, 6, 6, 'GRNHZ', 1, 361, NULL, 8, '2022-01-20 00:00:00'),
(26, 8, 7, 'GRNHZ', 1, 361, NULL, 8, '2022-01-20 00:00:00'),
(27, 9, 8, 'GRNHZ', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(28, 10, 9, 'GRNHZ', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(29, 10, 10, 'GRNHZ', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(30, 13, 11, 'GRNHZ', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(31, 2, 1, 'GRNOG', 3, 361, NULL, 8, '2022-01-20 00:00:00'),
(32, 4, 3, 'GRNOG', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(33, 4, 4, 'GRNOG', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(34, 6, 5, 'GRNOG', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(35, 6, 6, 'GRNOG', 1, 361, NULL, 8, '2022-01-20 00:00:00'),
(36, 8, 7, 'GRNOG', 1, 361, NULL, 8, '2022-01-20 00:00:00'),
(37, 9, 8, 'GRNOG', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(38, 10, 9, 'GRNOG', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(39, 10, 10, 'GRNOG', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(40, 13, 11, 'GRNOG', 0, 361, NULL, 8, '2022-01-20 00:00:00'),
(41, 2, 1, 'GROOZ', 3, 361, NULL, 8, '2022-01-20 00:00:00'),
(42, 4, 3, 'GROOZ', 2, 361, NULL, 8, '2022-01-20 00:00:00'),
(43, 4, 4, 'GROOZ', 3, 361, NULL, 8, '2022-01-20 00:00:00'),
(44, 2, 1, 'GWBZI', 3, 361, NULL, 8, '2022-01-21 00:00:00'),
(45, 4, 3, 'GWBZI', 2, 361, NULL, 8, '2022-01-21 00:00:00'),
(46, 4, 4, 'GWBZI', 3, 361, NULL, 8, '2022-01-21 00:00:00'),
(47, 6, 5, 'GWBZI', 2, 361, NULL, 8, '2022-01-21 00:00:00'),
(48, 6, 6, 'GWBZI', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(49, 8, 7, 'GWBZI', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(50, 9, 8, 'GWBZI', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(51, 10, 9, 'GWBZI', 0, 361, NULL, 8, '2022-01-21 00:00:00'),
(52, 10, 10, 'GWBZI', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(53, 13, 11, 'GWBZI', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(54, 2, 1, 'GWDTL', 3, 361, NULL, 8, '2022-01-21 00:00:00'),
(55, 4, 3, 'GWDTL', 2, 361, NULL, 8, '2022-01-21 00:00:00'),
(56, 4, 4, 'GWDTL', 3, 361, NULL, 8, '2022-01-21 00:00:00'),
(57, 6, 5, 'GWDTL', 2, 361, NULL, 8, '2022-01-21 00:00:00'),
(58, 6, 6, 'GWDTL', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(59, 8, 7, 'GWDTL', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(60, 9, 8, 'GWDTL', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(61, 10, 9, 'GWDTL', 0, 361, NULL, 8, '2022-01-21 00:00:00'),
(62, 10, 10, 'GWDTL', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(63, 13, 11, 'GWDTL', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(64, 2, 1, 'GYRFK', 3, 361, NULL, 8, '2022-01-21 00:00:00'),
(65, 4, 3, 'GYRFK', 2, 361, NULL, 8, '2022-01-21 00:00:00'),
(66, 4, 4, 'GYRFK', 3, 361, NULL, 8, '2022-01-21 00:00:00'),
(67, 6, 5, 'GYRFK', 2, 361, NULL, 8, '2022-01-21 00:00:00'),
(68, 6, 6, 'GYRFK', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(69, 8, 7, 'GYRFK', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(70, 9, 8, 'GYRFK', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(71, 10, 9, 'GYRFK', 0, 361, NULL, 8, '2022-01-21 00:00:00'),
(72, 10, 10, 'GYRFK', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(73, 13, 11, 'GYRFK', 1, 361, NULL, 8, '2022-01-21 00:00:00'),
(74, 10, 9, 'LSXAE', 2, 361, NULL, 8, '2022-02-16 00:00:00'),
(75, 10, 10, 'LSXAE', 2, 361, NULL, 8, '2022-02-16 00:00:00'),
(76, 2, 1, 'MWMSY', 3, 361, NULL, 8, '2022-02-22 00:00:00'),
(77, 4, 3, 'MWMSY', 3, 361, NULL, 8, '2022-02-22 00:00:00'),
(78, 4, 4, 'MWMSY', 4, 361, NULL, 8, '2022-02-22 00:00:00'),
(79, 6, 5, 'MWMSY', 3, 361, NULL, 8, '2022-02-22 00:00:00'),
(80, 6, 6, 'MWMSY', 3, 361, NULL, 8, '2022-02-22 00:00:00'),
(81, 8, 7, 'MWMSY', 2, 361, NULL, 8, '2022-02-22 00:00:00'),
(82, 9, 8, 'MWMSY', 1, 361, NULL, 8, '2022-02-22 00:00:00'),
(83, 10, 9, 'MWMSY', 3, 361, NULL, 8, '2022-02-22 00:00:00'),
(84, 10, 10, 'MWMSY', 3, 361, NULL, 8, '2022-02-22 00:00:00'),
(85, 13, 11, 'MWMSY', 2, 361, NULL, 8, '2022-02-22 00:00:00'),
(86, 2, 1, 'NJZAS', 3, 361, NULL, 8, '2022-02-24 00:00:00'),
(87, 4, 3, 'NJZAS', 3, 361, NULL, 8, '2022-02-24 00:00:00'),
(88, 4, 4, 'NJZAS', 4, 361, NULL, 8, '2022-02-24 00:00:00'),
(89, 6, 5, 'NJZAS', 3, 361, NULL, 8, '2022-02-24 00:00:00'),
(90, 6, 6, 'NJZAS', 3, 361, NULL, 8, '2022-02-24 00:00:00'),
(91, 8, 7, 'NJZAS', 2, 361, NULL, 8, '2022-02-24 00:00:00'),
(92, 9, 8, 'NJZAS', 2, 361, NULL, 8, '2022-02-24 00:00:00'),
(93, 13, 11, 'NJZAS', 2, 361, NULL, 8, '2022-02-24 00:00:00'),
(94, 2, 1, 'NOOQY', 3, 361, NULL, 8, '2022-02-25 00:00:00'),
(95, 4, 3, 'NOOQY', 3, 361, NULL, 8, '2022-02-25 00:00:00'),
(96, 4, 4, 'NOOQY', 4, 361, NULL, 8, '2022-02-25 00:00:00'),
(97, 6, 5, 'NOOQY', 3, 361, NULL, 8, '2022-02-25 00:00:00'),
(98, 6, 6, 'NOOQY', 3, 361, NULL, 8, '2022-02-25 00:00:00'),
(99, 8, 7, 'NOOQY', 2, 361, NULL, 8, '2022-02-25 00:00:00'),
(100, 9, 8, 'NOOQY', 2, 361, NULL, 8, '2022-02-25 00:00:00'),
(101, 13, 11, 'NOOQY', 2, 361, NULL, 8, '2022-02-25 00:00:00'),
(102, 2, 1, 'NOSOD', 3, 361, NULL, 8, '2022-02-25 00:00:00'),
(103, 4, 3, 'NOSOD', 3, 361, NULL, 8, '2022-02-25 00:00:00'),
(104, 4, 4, 'NOSOD', 4, 361, NULL, 8, '2022-02-25 00:00:00'),
(105, 6, 5, 'NOSOD', 3, 361, NULL, 8, '2022-02-25 00:00:00'),
(106, 6, 6, 'NOSOD', 3, 361, NULL, 8, '2022-02-25 00:00:00'),
(107, 8, 7, 'NOSOD', 2, 361, NULL, 8, '2022-02-25 00:00:00'),
(108, 9, 8, 'NOSOD', 2, 361, NULL, 8, '2022-02-25 00:00:00'),
(109, 13, 11, 'NOSOD', 2, 361, NULL, 8, '2022-02-25 00:00:00'),
(110, 2, 1, 'SJLLP', 3, 361, NULL, 8, '2022-03-23 00:00:00'),
(111, 4, 3, 'SJLLP', 3, 361, NULL, 8, '2022-03-23 00:00:00'),
(112, 4, 4, 'SJLLP', 4, 361, NULL, 8, '2022-03-23 00:00:00'),
(113, 6, 5, 'SJLLP', 3, 361, NULL, 8, '2022-03-23 00:00:00'),
(114, 6, 6, 'SJLLP', 3, 361, NULL, 8, '2022-03-23 00:00:00'),
(115, 8, 7, 'SJLLP', 2, 361, NULL, 8, '2022-03-23 00:00:00'),
(116, 9, 8, 'SJLLP', 2, 361, NULL, 8, '2022-03-23 00:00:00'),
(117, 13, 11, 'SJLLP', 2, 361, NULL, 8, '2022-03-23 00:00:00'),
(118, 2, 1, 'SJLYY', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(119, 4, 3, 'SJLYY', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(120, 4, 4, 'SJLYY', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(121, 6, 5, 'SJLYY', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(122, 6, 6, 'SJLYY', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(123, 8, 7, 'SJLYY', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(124, 9, 8, 'SJLYY', 4, 361, NULL, 8, '2022-03-23 00:00:00'),
(125, 13, 11, 'SJLYY', 4, 361, NULL, 8, '2022-03-23 00:00:00'),
(126, 2, 1, 'SJNPI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(127, 4, 3, 'SJNPI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(128, 4, 4, 'SJNPI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(129, 6, 5, 'SJNPI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(130, 6, 6, 'SJNPI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(131, 8, 7, 'SJNPI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(132, 9, 8, 'SJNPI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(133, 13, 11, 'SJNPI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(134, 2, 1, 'SJOWI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(135, 4, 3, 'SJOWI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(136, 4, 4, 'SJOWI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(137, 6, 5, 'SJOWI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(138, 6, 6, 'SJOWI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(139, 8, 7, 'SJOWI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(140, 9, 8, 'SJOWI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(141, 13, 11, 'SJOWI', 5, 361, NULL, 8, '2022-03-23 00:00:00'),
(142, 50, 55, 'SPFET', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(143, 50, 58, 'SPFET', 10, 389, NULL, 8, '2022-03-24 00:00:00'),
(144, 51, 59, 'SPFET', 4, 389, NULL, 8, '2022-03-24 00:00:00'),
(145, 51, 60, 'SPFET', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(146, 51, 61, 'SPFET', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(147, 51, 62, 'SPFET', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(148, 51, 63, 'SPFET', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(149, 52, 65, 'SPFET', 10, 389, NULL, 8, '2022-03-24 00:00:00'),
(150, 53, 66, 'SPFET', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(151, 53, 67, 'SPFET', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(152, 53, 68, 'SPFET', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(153, 53, 69, 'SPFET', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(154, 50, 55, 'SPITP', 10, 389, NULL, 8, '2022-03-24 00:00:00'),
(155, 50, 58, 'SPITP', 10, 389, NULL, 8, '2022-03-24 00:00:00'),
(156, 51, 59, 'SPITP', 10, 389, NULL, 8, '2022-03-24 00:00:00'),
(157, 51, 60, 'SPITP', 10, 389, NULL, 8, '2022-03-24 00:00:00'),
(158, 51, 61, 'SPITP', 10, 389, NULL, 8, '2022-03-24 00:00:00'),
(159, 51, 62, 'SPITP', 10, 389, NULL, 8, '2022-03-24 00:00:00'),
(160, 51, 63, 'SPITP', 10, 389, NULL, 8, '2022-03-24 00:00:00'),
(161, 53, 66, 'SPITP', 5, 389, NULL, 8, '2022-03-24 00:00:00'),
(162, 53, 67, 'SPITP', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(163, 53, 68, 'SPITP', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(164, 53, 69, 'SPITP', 0, 389, NULL, 8, '2022-03-24 00:00:00'),
(165, 59, 79, 'TZCWK', 0, 352, NULL, 15, '2022-03-31 00:00:00'),
(166, 59, 80, 'TZCWK', 0, 352, NULL, 15, '2022-03-31 00:00:00'),
(167, 59, 81, 'TZCWK', 0, 352, NULL, 15, '2022-03-31 00:00:00'),
(168, 59, 82, 'TZCWK', 0, 352, NULL, 15, '2022-03-31 00:00:00'),
(169, 59, 83, 'TZCWK', 0, 352, NULL, 15, '2022-03-31 00:00:00'),
(170, 59, 79, 'TZFDM', 3, 352, NULL, 15, '2022-03-31 00:00:00'),
(171, 59, 80, 'TZFDM', 5, 352, NULL, 15, '2022-03-31 00:00:00'),
(172, 59, 81, 'TZFDM', 4, 352, NULL, 15, '2022-03-31 00:00:00'),
(173, 59, 82, 'TZFDM', 2, 352, NULL, 15, '2022-03-31 00:00:00'),
(174, 59, 83, 'TZFDM', 10, 352, NULL, 15, '2022-03-31 00:00:00'),
(175, 59, 79, 'TZGBW', 10, 352, NULL, 15, '2022-03-31 00:00:00'),
(176, 59, 80, 'TZGBW', 10, 352, NULL, 15, '2022-03-31 00:00:00'),
(177, 59, 81, 'TZGBW', 10, 352, NULL, 15, '2022-03-31 00:00:00'),
(178, 59, 82, 'TZGBW', 10, 352, NULL, 15, '2022-03-31 00:00:00'),
(179, 59, 83, 'TZGBW', 10, 352, NULL, 15, '2022-03-31 00:00:00'),
(180, 64, 99, 'UQSVJ', 10, 3, NULL, 15, '2022-04-04 00:00:00'),
(181, 64, 100, 'UQSVJ', 10, 3, NULL, 15, '2022-04-04 00:00:00'),
(182, 64, 101, 'UQSVJ', 10, 3, NULL, 15, '2022-04-04 00:00:00'),
(183, 64, 102, 'UQSVJ', 10, 3, NULL, 15, '2022-04-04 00:00:00'),
(184, 64, 103, 'UQSVJ', 10, 3, NULL, 15, '2022-04-04 00:00:00'),
(185, 65, 98, 'UQSVJ', 10, 3, NULL, 15, '2022-04-04 00:00:00'),
(186, 66, 104, 'URNLG', 5, 409, NULL, 15, '2022-04-04 00:00:00'),
(187, 66, 105, 'URNLG', 0, 409, NULL, 15, '2022-04-04 00:00:00'),
(188, 66, 106, 'URNLG', 5, 409, NULL, 15, '2022-04-04 00:00:00'),
(189, 66, 107, 'URNLG', 0, 409, NULL, 15, '2022-04-04 00:00:00'),
(190, 67, 108, 'URNLG', 9, 409, NULL, 15, '2022-04-04 00:00:00'),
(191, 68, 109, 'URNLG', 0, 409, NULL, 15, '2022-04-04 00:00:00'),
(192, 61, 89, 'UROXO', 10, 346, NULL, 15, '2022-04-04 00:00:00'),
(193, 62, 90, 'UROXO', 5, 346, NULL, 15, '2022-04-04 00:00:00'),
(194, 63, 91, 'URWJF', 4, 357, NULL, 15, '2022-04-04 00:00:00'),
(195, 63, 92, 'URWJF', 5, 357, NULL, 15, '2022-04-04 00:00:00'),
(196, 63, 93, 'URWJF', 5, 357, NULL, 15, '2022-04-04 00:00:00'),
(197, 62, 90, 'USWDA', 5, 346, NULL, 15, '2022-04-04 00:00:00'),
(198, 62, 90, 'USYVB', 5, 346, NULL, 15, '2022-04-04 00:00:00'),
(199, 63, 91, 'XFLIL', 5, 357, NULL, 1, '2022-04-18 00:00:00'),
(200, 63, 92, 'XFLIL', 5, 357, NULL, 1, '2022-04-18 00:00:00'),
(201, 63, 93, 'XFLIL', 6, 357, NULL, 1, '2022-04-18 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_other_cost_plan`
--

CREATE TABLE `tbl_project_other_cost_plan` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` int NOT NULL,
  `unit_cost` double NOT NULL,
  `units_no` int NOT NULL,
  `comments` text,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `update_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_outcome_evaluation_questions`
--

CREATE TABLE `tbl_project_outcome_evaluation_questions` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `question` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_outcome_evaluation_questions`
--

INSERT INTO `tbl_project_outcome_evaluation_questions` (`id`, `projid`, `question`) VALUES
(6, 27, 'Tester 1'),
(7, 27, 'Tester 2'),
(8, 27, 'Tester 3'),
(12, 35, 'Are you a registered land owner?'),
(13, 38, 'Has this project reduced the time taken to access the main road?'),
(14, 44, 'Do you have access to clean and safe water?'),
(16, 22, 'Are you accessing the Center through the road'),
(17, 55, 'Are you rearing chicken?');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_outputs`
--

CREATE TABLE `tbl_project_outputs` (
  `opid` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `indicator` int NOT NULL,
  `data_source` varchar(100) NOT NULL,
  `data_collection_method` int NOT NULL,
  `monitoring_frequency` int NOT NULL,
  `responsible` varchar(100) DEFAULT NULL,
  `report_user` varchar(255) NOT NULL,
  `reporting_timeline` varchar(255) NOT NULL,
  `next_monitoring_date` date DEFAULT NULL,
  `date_created` date NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_outputs_mne_details`
--

CREATE TABLE `tbl_project_outputs_mne_details` (
  `opid` int NOT NULL,
  `projid` int DEFAULT NULL,
  `outputid` varchar(255) NOT NULL,
  `indicator` int NOT NULL,
  `data_source` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `data_collection_method` int DEFAULT NULL,
  `monitoring_frequency` int NOT NULL,
  `responsible` varchar(100) DEFAULT NULL,
  `report_user` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `reporting_timeline` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `next_monitoring_date` date DEFAULT NULL,
  `date_created` date NOT NULL,
  `created_by` int NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_outputs_mne_details`
--

INSERT INTO `tbl_project_outputs_mne_details` (`opid`, `projid`, `outputid`, `indicator`, `data_source`, `data_collection_method`, `monitoring_frequency`, `responsible`, `report_user`, `reporting_timeline`, `next_monitoring_date`, `date_created`, `created_by`, `changed_by`, `date_changed`) VALUES
(1, 8, '63', 23, NULL, NULL, 2, NULL, NULL, '3', '2021-07-13', '2022-01-18', 1, '1', '2022-01-18 00:00:00'),
(2, 24, '100', 1, NULL, NULL, 3, NULL, NULL, '4', '2022-02-25', '2022-02-13', 1, '1', '2022-02-13 00:00:00'),
(3, 27, '105', 18, NULL, NULL, 3, NULL, NULL, '', NULL, '2022-02-28', 1, NULL, NULL),
(4, 27, '105', 18, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2022-02-28', 1, NULL, NULL),
(5, 27, '105', 18, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2022-02-28', 1, NULL, NULL),
(6, 27, '105', 18, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2022-02-28', 1, NULL, NULL),
(7, 35, '131', 135, NULL, NULL, 4, NULL, NULL, NULL, '2021-11-01', '2022-03-23', 1, NULL, NULL),
(8, 35, '131', 135, NULL, NULL, 4, NULL, NULL, NULL, '2021-11-01', '2022-03-24', 1, NULL, NULL),
(9, 35, '131', 135, NULL, NULL, 4, NULL, NULL, NULL, '2021-11-01', '2022-03-24', 1, NULL, NULL),
(10, 38, '134', 18, NULL, NULL, 3, NULL, NULL, NULL, '2021-08-02', '2022-03-24', 1, NULL, NULL),
(11, 39, '135', 144, NULL, NULL, 3, NULL, NULL, NULL, '2021-08-02', '2022-03-26', 1, NULL, NULL),
(12, 44, '143', 1, NULL, NULL, 4, NULL, NULL, NULL, '2021-11-10', '2022-03-30', 1, '1', '2022-03-30 00:00:00'),
(13, 22, '96', 19, NULL, NULL, 3, NULL, NULL, NULL, '2021-08-01', '2022-03-30', 1, NULL, NULL),
(14, 22, '96', 19, NULL, NULL, 3, NULL, NULL, NULL, '2021-08-01', '2022-03-31', 1, NULL, NULL),
(16, 45, '151', 151, NULL, NULL, 3, NULL, NULL, NULL, '2021-08-01', '2022-04-04', 1, NULL, NULL),
(17, 54, '169', 155, NULL, NULL, 3, NULL, NULL, NULL, '2022-04-24', '2022-04-04', 1, NULL, NULL),
(18, 49, '164', 144, NULL, NULL, 3, NULL, NULL, NULL, '2022-04-05', '2022-04-04', 1, NULL, NULL),
(19, 55, '170', 163, NULL, NULL, 3, NULL, NULL, NULL, '2022-04-05', '2022-04-04', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_output_details`
--

CREATE TABLE `tbl_project_output_details` (
  `id` int NOT NULL,
  `projoutputid` int NOT NULL,
  `progid` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int DEFAULT NULL,
  `indicator` int NOT NULL,
  `year` int NOT NULL,
  `target` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_output_details`
--

INSERT INTO `tbl_project_output_details` (`id`, `projoutputid`, `progid`, `projid`, `outputid`, `indicator`, `year`, `target`) VALUES
(22, 64, 2, 1, NULL, 19, 2021, 4),
(11, 55, 1, 2, NULL, 17, 2021, 1),
(26, 56, 2, 3, NULL, 17, 2021, 1),
(24, 57, 2, 4, NULL, 17, 2021, 1),
(14, 59, 5, 5, NULL, 2, 2021, 36),
(13, 60, 5, 6, NULL, 2, 2021, 3),
(12, 61, 6, 7, NULL, 23, 2021, 1),
(10, 63, 6, 8, NULL, 23, 2021, 1),
(21, 72, 2, 9, NULL, 17, 2021, 1),
(20, 70, 2, 9, NULL, 18, 2021, 10),
(19, 69, 2, 9, NULL, 19, 2021, 2),
(25, 73, 5, 10, NULL, 2, 2021, 1),
(28, 76, 7, 11, NULL, 26, 2021, 1),
(94, 104, 47, 26, NULL, 2, 2021, 2),
(30, 84, 2, 13, NULL, 19, 2021, 20),
(223, 111, 2, 14, NULL, 19, 2021, 10),
(93, 86, 2, 15, NULL, 17, 2021, 1),
(34, 87, 8, 16, NULL, 28, 2021, 1),
(248, 118, 2, 18, NULL, 18, 2021, 10),
(229, 112, 2, 14, NULL, 19, 2021, 10),
(265, 141, 2, 20, NULL, 18, 2021, 13),
(40, 95, 2, 21, NULL, 18, 2021, 10),
(42, 96, 2, 22, NULL, 19, 2021, 4),
(46, 97, 2, 23, NULL, 17, 2021, 1),
(47, 100, 13, 24, NULL, 1, 2021, 1),
(49, 101, 2, 25, NULL, 18, 2021, 6),
(87, 83, 2, 12, NULL, 19, 2021, 1),
(205, 105, 2, 27, NULL, 18, 2021, 4),
(251, 125, 49, 30, NULL, 1, 2024, 1),
(228, 112, 2, 14, NULL, 19, 2021, 10),
(227, 112, 2, 14, NULL, 19, 2021, 10),
(226, 111, 2, 14, NULL, 19, 2021, 10),
(225, 111, 2, 14, NULL, 19, 2021, 10),
(224, 111, 2, 14, NULL, 19, 2021, 10),
(263, 138, 58, 41, NULL, 147, 2022, 1),
(262, 136, 56, 40, NULL, 12, 2021, 1),
(260, 134, 54, 38, NULL, 18, 2021, 20),
(230, 112, 2, 14, NULL, 19, 2021, 10),
(231, 113, 2, 14, NULL, 19, 2021, 10),
(232, 113, 2, 14, NULL, 19, 2021, 10),
(233, 113, 2, 14, NULL, 19, 2021, 10),
(234, 113, 2, 14, NULL, 19, 2021, 10),
(235, 114, 2, 14, NULL, 19, 2021, 10),
(236, 114, 2, 14, NULL, 19, 2021, 10),
(237, 114, 2, 14, NULL, 19, 2021, 10),
(238, 114, 2, 14, NULL, 19, 2021, 10),
(261, 135, 55, 39, NULL, 144, 2021, 4),
(258, 132, 52, 36, NULL, 2, 2022, 10),
(257, 131, 51, 35, NULL, 135, 2021, 40),
(256, 130, 50, 34, NULL, 17, 2022, 1),
(255, 129, 49, 33, NULL, 2, 2028, 1),
(254, 129, 49, 33, NULL, 2, 2027, 1),
(249, 122, 2, 29, NULL, 18, 2021, 3),
(250, 123, 2, 29, NULL, 17, 2021, 2),
(264, 139, 59, 42, NULL, 144, 2021, 10),
(266, 142, 60, 43, NULL, 2, 2022, 10),
(267, 143, 61, 44, NULL, 1, 2022, 1),
(268, 151, 62, 45, NULL, 151, 2021, 2000),
(269, 161, 61, 46, NULL, 1, 2021, 1),
(270, 162, 61, 47, NULL, 1, 2021, 1),
(271, 163, 61, 48, NULL, 1, 2021, 1),
(272, 164, 63, 49, NULL, 144, 2021, 25),
(273, 164, 63, 49, NULL, 144, 2022, 25),
(274, 165, 64, 50, NULL, 154, 2021, 2),
(275, 166, 64, 51, NULL, 156, 2021, 1),
(276, 167, 64, 52, NULL, 158, 2021, 3),
(277, 168, 64, 53, NULL, 159, 2021, 3),
(278, 169, 64, 54, NULL, 155, 2021, 6),
(279, 170, 65, 55, NULL, 163, 2021, 3000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_output_details_history`
--

CREATE TABLE `tbl_project_output_details_history` (
  `odid` int NOT NULL,
  `projoutputid` int NOT NULL,
  `progid` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int DEFAULT NULL,
  `indicator` int NOT NULL,
  `year` int NOT NULL,
  `target` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_output_details_history`
--

INSERT INTO `tbl_project_output_details_history` (`odid`, `projoutputid`, `progid`, `projid`, `outputid`, `indicator`, `year`, `target`) VALUES
(1, 63, 6, 8, NULL, 23, 2021, 1),
(2, 55, 1, 2, NULL, 17, 2021, 1),
(3, 61, 6, 7, NULL, 23, 2021, 1),
(4, 60, 5, 6, NULL, 2, 2021, 3),
(5, 59, 5, 5, NULL, 2, 2021, 36),
(6, 69, 2, 9, NULL, 19, 2021, 2),
(7, 70, 2, 9, NULL, 18, 2021, 10),
(8, 72, 2, 9, NULL, 17, 2021, 1),
(9, 64, 2, 1, NULL, 19, 2021, 4),
(10, 57, 2, 4, NULL, 17, 2021, 1),
(11, 73, 5, 10, NULL, 2, 2021, 1),
(12, 56, 2, 3, NULL, 17, 2021, 1),
(13, 76, 7, 11, NULL, 26, 2021, 1),
(15, 95, 2, 21, NULL, 18, 2021, 10),
(16, 96, 2, 22, NULL, 19, 2021, 4),
(18, 97, 2, 23, NULL, 17, 2021, 1),
(19, 100, 13, 24, NULL, 1, 2021, 1),
(20, 101, 2, 25, NULL, 18, 2021, 6),
(21, 83, 2, 12, NULL, 19, 2021, 30),
(22, 83, 2, 12, NULL, 19, 2021, 1),
(23, 83, 2, 12, NULL, 19, 2021, 1),
(24, 83, 2, 12, NULL, 19, 2021, 1),
(25, 83, 2, 12, NULL, 19, 2021, 1),
(26, 83, 2, 12, NULL, 19, 2021, 1),
(27, 83, 2, 12, NULL, 19, 2021, 1),
(28, 83, 2, 12, NULL, 19, 2021, 1),
(29, 83, 2, 12, NULL, 19, 2021, 1),
(30, 83, 2, 12, NULL, 19, 2021, 1),
(31, 83, 2, 12, NULL, 19, 2021, 1),
(32, 83, 2, 12, NULL, 19, 2021, 1),
(33, 83, 2, 12, NULL, 19, 2021, 1),
(34, 83, 2, 12, NULL, 19, 2021, 1),
(35, 83, 2, 12, NULL, 19, 2021, 1),
(36, 83, 2, 12, NULL, 19, 2021, 1),
(37, 83, 2, 12, NULL, 19, 2021, 1),
(38, 83, 2, 12, NULL, 19, 2021, 1),
(39, 83, 2, 12, NULL, 19, 2021, 1),
(40, 83, 2, 12, NULL, 19, 2021, 1),
(41, 83, 2, 12, NULL, 19, 2021, 1),
(42, 83, 2, 12, NULL, 19, 2021, 1),
(43, 83, 2, 12, NULL, 19, 2021, 1),
(44, 83, 2, 12, NULL, 19, 2021, 1),
(45, 83, 2, 12, NULL, 19, 2021, 1),
(46, 83, 2, 12, NULL, 19, 2021, 1),
(47, 83, 2, 12, NULL, 19, 2021, 1),
(48, 83, 2, 12, NULL, 19, 2021, 1),
(49, 83, 2, 12, NULL, 19, 2021, 1),
(50, 83, 2, 12, NULL, 19, 2021, 1),
(51, 83, 2, 12, NULL, 19, 2021, 1),
(52, 83, 2, 12, NULL, 19, 2021, 1),
(53, 83, 2, 12, NULL, 19, 2021, 1),
(54, 83, 2, 12, NULL, 19, 2021, 1),
(55, 83, 2, 12, NULL, 19, 2021, 1),
(56, 83, 2, 12, NULL, 19, 2021, 1),
(57, 83, 2, 12, NULL, 19, 2021, 1),
(58, 83, 2, 12, NULL, 19, 2021, 1),
(59, 83, 2, 12, NULL, 19, 2021, 1),
(60, 83, 2, 12, NULL, 19, 2021, 1),
(61, 83, 2, 12, NULL, 19, 2021, 1),
(62, 86, 2, 15, NULL, 17, 2021, 1),
(63, 86, 2, 15, NULL, 17, 2021, 1),
(64, 86, 2, 15, NULL, 17, 2021, 1),
(65, 86, 2, 15, NULL, 17, 2021, 1),
(66, 86, 2, 15, NULL, 17, 2021, 1),
(67, 86, 2, 15, NULL, 17, 2021, 1),
(68, 105, 2, 27, NULL, 18, 2021, 5),
(69, 105, 2, 27, NULL, 18, 2021, 4),
(70, 105, 2, 27, NULL, 18, 2021, 4),
(71, 105, 2, 27, NULL, 18, 2021, 4),
(72, 105, 2, 27, NULL, 18, 2021, 4),
(73, 105, 2, 27, NULL, 18, 2021, 4),
(74, 105, 2, 27, NULL, 18, 2021, 4),
(75, 105, 2, 27, NULL, 18, 2021, 4),
(76, 105, 2, 27, NULL, 18, 2021, 4),
(77, 105, 2, 27, NULL, 18, 2021, 4),
(78, 105, 2, 27, NULL, 18, 2021, 4),
(79, 105, 2, 27, NULL, 18, 2021, 4),
(80, 105, 2, 27, NULL, 18, 2021, 4),
(81, 105, 2, 27, NULL, 18, 2021, 4),
(82, 105, 2, 27, NULL, 18, 2021, 4),
(83, 105, 2, 27, NULL, 18, 2021, 4),
(84, 105, 2, 27, NULL, 18, 2021, 4),
(85, 105, 2, 27, NULL, 18, 2021, 4),
(86, 105, 2, 27, NULL, 18, 2021, 4),
(87, 105, 2, 27, NULL, 18, 2021, 4),
(88, 105, 2, 27, NULL, 18, 2021, 4),
(89, 105, 2, 27, NULL, 18, 2021, 4),
(90, 105, 2, 27, NULL, 18, 2021, 4),
(91, 105, 2, 27, NULL, 18, 2021, 4),
(92, 105, 2, 27, NULL, 18, 2021, 4),
(93, 105, 2, 27, NULL, 18, 2021, 4),
(94, 105, 2, 27, NULL, 18, 2021, 4),
(95, 105, 2, 27, NULL, 18, 2021, 4),
(96, 105, 2, 27, NULL, 18, 2021, 4),
(97, 105, 2, 27, NULL, 18, 2021, 4),
(98, 105, 2, 27, NULL, 18, 2021, 4),
(99, 105, 2, 27, NULL, 18, 2021, 4),
(100, 105, 2, 27, NULL, 18, 2021, 4),
(101, 105, 2, 27, NULL, 18, 2021, 4),
(102, 105, 2, 27, NULL, 18, 2021, 4),
(103, 105, 2, 27, NULL, 18, 2021, 4),
(104, 105, 2, 27, NULL, 18, 2021, 4),
(105, 105, 2, 27, NULL, 18, 2021, 4),
(106, 105, 2, 27, NULL, 18, 2021, 4),
(107, 105, 2, 27, NULL, 18, 2021, 4),
(108, 105, 2, 27, NULL, 18, 2021, 4),
(109, 105, 2, 27, NULL, 18, 2021, 4),
(110, 105, 2, 27, NULL, 18, 2021, 4),
(111, 105, 2, 27, NULL, 18, 2021, 4),
(112, 105, 2, 27, NULL, 18, 2021, 4),
(113, 105, 2, 27, NULL, 18, 2021, 4),
(114, 105, 2, 27, NULL, 18, 2021, 4),
(115, 105, 2, 27, NULL, 18, 2021, 4),
(116, 105, 2, 27, NULL, 18, 2021, 4),
(117, 105, 2, 27, NULL, 18, 2021, 4),
(118, 105, 2, 27, NULL, 18, 2021, 4),
(119, 105, 2, 27, NULL, 18, 2021, 4),
(120, 105, 2, 27, NULL, 18, 2021, 4),
(121, 105, 2, 27, NULL, 18, 2021, 4),
(122, 105, 2, 27, NULL, 18, 2021, 4),
(123, 105, 2, 27, NULL, 18, 2021, 4),
(124, 105, 2, 27, NULL, 18, 2021, 4),
(125, 105, 2, 27, NULL, 18, 2021, 4),
(126, 105, 2, 27, NULL, 18, 2021, 4),
(127, 105, 2, 27, NULL, 18, 2021, 4),
(128, 105, 2, 27, NULL, 18, 2021, 4),
(129, 105, 2, 27, NULL, 18, 2021, 4),
(130, 105, 2, 27, NULL, 18, 2021, 4),
(131, 105, 2, 27, NULL, 18, 2021, 4),
(132, 105, 2, 27, NULL, 18, 2021, 4),
(133, 105, 2, 27, NULL, 18, 2021, 4),
(134, 105, 2, 27, NULL, 18, 2021, 4),
(135, 105, 2, 27, NULL, 18, 2021, 4),
(136, 105, 2, 27, NULL, 18, 2021, 4),
(137, 105, 2, 27, NULL, 18, 2021, 4),
(138, 105, 2, 27, NULL, 18, 2021, 4),
(139, 105, 2, 27, NULL, 18, 2021, 4),
(140, 105, 2, 27, NULL, 18, 2021, 4),
(141, 105, 2, 27, NULL, 18, 2021, 4),
(142, 105, 2, 27, NULL, 18, 2021, 4),
(143, 105, 2, 27, NULL, 18, 2021, 4),
(144, 105, 2, 27, NULL, 18, 2021, 4),
(145, 105, 2, 27, NULL, 18, 2021, 4),
(146, 105, 2, 27, NULL, 18, 2021, 4),
(147, 105, 2, 27, NULL, 18, 2021, 4),
(148, 105, 2, 27, NULL, 18, 2021, 4),
(149, 105, 2, 27, NULL, 18, 2021, 4),
(150, 105, 2, 27, NULL, 18, 2021, 4),
(153, 105, 2, 27, NULL, 18, 2021, 4),
(154, 105, 2, 27, NULL, 18, 2021, 4),
(155, 105, 2, 27, NULL, 18, 2021, 4),
(156, 105, 2, 27, NULL, 18, 2021, 4),
(157, 105, 2, 27, NULL, 18, 2021, 4),
(158, 105, 2, 27, NULL, 18, 2021, 4),
(159, 105, 2, 27, NULL, 18, 2021, 4),
(160, 105, 2, 27, NULL, 18, 2021, 4),
(161, 105, 2, 27, NULL, 18, 2021, 4),
(162, 105, 2, 27, NULL, 18, 2021, 4),
(163, 105, 2, 27, NULL, 18, 2021, 4),
(164, 105, 2, 27, NULL, 18, 2021, 4),
(165, 105, 2, 27, NULL, 18, 2021, 4),
(166, 105, 2, 27, NULL, 18, 2021, 4),
(167, 105, 2, 27, NULL, 18, 2021, 4),
(168, 105, 2, 27, NULL, 18, 2021, 4),
(169, 105, 2, 27, NULL, 18, 2021, 4),
(170, 105, 2, 27, NULL, 18, 2021, 4),
(171, 105, 2, 27, NULL, 18, 2021, 4),
(172, 105, 2, 27, NULL, 18, 2021, 4),
(173, 105, 2, 27, NULL, 18, 2021, 4),
(174, 105, 2, 27, NULL, 18, 2021, 4),
(175, 105, 2, 27, NULL, 18, 2021, 4),
(176, 105, 2, 27, NULL, 18, 2021, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_output_diss_resp`
--

CREATE TABLE `tbl_project_output_diss_resp` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `opstate` int NOT NULL,
  `oplocation` varchar(255) NOT NULL,
  `responsible` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_photos`
--

CREATE TABLE `tbl_project_photos` (
  `fid` int NOT NULL,
  `projid` int DEFAULT NULL,
  `monitoringid` int NOT NULL DEFAULT '0',
  `form_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `opid` int DEFAULT NULL,
  `projstage` int NOT NULL,
  `fcategory` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `filename` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `ftype` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `floc` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `origin` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `uploaded_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `date_uploaded` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `tbl_project_photos`
--

INSERT INTO `tbl_project_photos` (`fid`, `projid`, `monitoringid`, `form_id`, `opid`, `projstage`, `fcategory`, `filename`, `ftype`, `description`, `floc`, `origin`, `uploaded_by`, `date_uploaded`) VALUES
(1, 8, 1, '1', 1, 1, '1', 'test/images/image-gallery/1.jpg', 'jpg', 'Monitoring Project Images Test', 'test/images/image-gallery/1.jpg', 'monitoring', '1', '2022-02-10'),
(2, 8, 1, '1', 1, 1, '1', 'test/images/image-gallery/1.jpg', 'jpg', 'Monitoring Project Images Test', 'test/images/image-gallery/1.jpg', 'monitoring', '1', '2022-02-10'),
(3, 8, 1, '1', 1, 1, '1', 'test/images/image-gallery/1.jpg', 'jpg', 'Monitoring Project Images Test', 'test/images/image-gallery/1.jpg', 'monitoring', '1', '2022-02-10'),
(4, 8, 1, '1', 1, 1, '1', 'test/images/image-gallery/1.jpg', 'jpg', 'Monitoring Project Images Test', 'test/images/image-gallery/1.jpg', 'monitoring', '1', '2022-02-10'),
(5, 8, 1, '1', 1, 1, '1', 'test/images/image-gallery/1.jpg', 'jpg', 'Monitoring Project Images Test', 'test/images/image-gallery/1.jpg', NULL, '1', '2022-02-10'),
(6, 8, 1, '1', 1, 1, '1', 'test/images/image-gallery/1.jpg', 'jpg', 'Monitoring Project Images Test', 'test/images/image-gallery/1.jpg', NULL, '1', '2022-02-10'),
(7, 8, 0, '6', NULL, 1, 'Project Planning', 'main-qimg-d47013e7d57b30295a26c9b7c33f8650.jpeg', 'jpeg', 'Sit natus eveniet ', 'uploads/6_1_main-qimg-d47013e7d57b30295a26c9b7c33f8650.jpeg', 'Public', '0', '16-03-2022'),
(8, 45, 38, 'USWDA', 151, 10, 'USWDA', '45-USWDA-151-1649101866FPKhloyXsAAOdKh.jpeg', 'jpeg', 'Ducimus ullam sint ', 'uploads/monitoring/photos/45-USWDA-151-1649101866FPKhloyXsAAOdKh.jpeg', NULL, '1', '2022-04-04'),
(9, 45, 39, 'USWDA', 151, 10, 'Monitoring', '45-Monitoring-151-1649101954FPKhloyXsAAOdKh.jpeg', 'jpeg', 'Ducimus ullam sint ', 'uploads/monitoring/photos/45-Monitoring-151-1649101954FPKhloyXsAAOdKh.jpeg', NULL, '1', '2022-04-04'),
(10, 45, 40, 'USWDA', 151, 10, 'Monitoring', '164910203245-Monitoring-151-FPKhloyXsAAOdKh.jpeg', 'jpeg', 'Ducimus ullam sint ', 'uploads/monitoring/photos/164910203245-Monitoring-151-FPKhloyXsAAOdKh.jpeg', NULL, '1', '2022-04-04'),
(11, 45, 41, 'USWDA', 151, 10, 'Monitoring', '164910219745-Monitoring-151-FPKhloyXsAAOdKh.jpeg', 'jpeg', 'Ducimus ullam sint ', 'uploads/monitoring/photos/164910219745-Monitoring-151-FPKhloyXsAAOdKh.jpeg', NULL, '1', '2022-04-04'),
(12, 45, 42, 'USWDA', 151, 10, 'Monitoring', '164910234245-Monitoring-151-FPKhloyXsAAOdKh.jpeg', 'jpeg', 'Ducimus ullam sint ', 'uploads/monitoring/photos/164910234245-Monitoring-151-FPKhloyXsAAOdKh.jpeg', NULL, '1', '2022-04-04'),
(13, 45, 42, 'USWDA', 151, 10, 'Monitoring', '164910234345-Monitoring-151-FPKhloyXsAAOdKh.jpeg', 'jpeg', 'Sit a minus praesen', 'uploads/monitoring/photos/164910234345-Monitoring-151-FPKhloyXsAAOdKh.jpeg', NULL, '1', '2022-04-04'),
(14, 45, 43, 'USWDA', 151, 10, 'Monitoring', '164910242245-Monitoring-151-FPKhloyXsAAOdKh.jpeg', 'jpeg', 'Ducimus ullam sint ', 'uploads/monitoring/photos/164910242245-Monitoring-151-FPKhloyXsAAOdKh.jpeg', NULL, '1', '2022-04-04'),
(15, 45, 44, 'USYVB', 151, 10, 'Monitoring', '164910261345-Monitoring-151-FPKhloyXsAAOdKh.jpeg', 'jpeg', 'Laboriosam officia ', 'uploads/monitoring/photos/164910261345-Monitoring-151-FPKhloyXsAAOdKh.jpeg', NULL, '1', '2022-04-04'),
(16, 45, 44, 'USYVB', 151, 10, 'Monitoring', '164910261345-Monitoring-151-273661006_4815991395184905_3329807975205578301_n.jpg', 'jpg', 'Impedit nemo eaque ', 'uploads/monitoring/photos/164910261345-Monitoring-151-273661006_4815991395184905_3329807975205578301_n.jpg', NULL, '1', '2022-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_results_level_disaggregation`
--

CREATE TABLE `tbl_project_results_level_disaggregation` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `ben_id` int DEFAULT NULL,
  `projoutputid` int DEFAULT NULL,
  `opstate` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `value` decimal(10,0) NOT NULL,
  `responsible` int DEFAULT NULL,
  `type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_results_level_disaggregation`
--

INSERT INTO `tbl_project_results_level_disaggregation` (`id`, `projid`, `ben_id`, `projoutputid`, `opstate`, `name`, `value`, `responsible`, `type`) VALUES
(10, 55, NULL, 170, 409, '', '3000', NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_riskscore`
--

CREATE TABLE `tbl_project_riskscore` (
  `scid` int NOT NULL,
  `projid` int NOT NULL,
  `issueid` int NOT NULL,
  `score` int NOT NULL,
  `mitigation` int NOT NULL,
  `notes` text NOT NULL,
  `date_analysed` date NOT NULL,
  `added_by` varchar(100) NOT NULL,
  `committee` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_riskscore`
--

INSERT INTO `tbl_project_riskscore` (`scid`, `projid`, `issueid`, `score`, `mitigation`, `notes`, `date_analysed`, `added_by`, `committee`) VALUES
(1, 8, 1, 4, 19, 'Testing', '2022-03-01', '', NULL),
(2, 8, 2, 5, 19, '', '2022-03-02', '', NULL),
(4, 8, 11, 5, 20, '', '2022-03-23', '', NULL),
(5, 8, 6, 5, 19, 'test', '2022-03-23', '', NULL),
(6, 8, 9, 5, 20, '', '2022-03-23', '', NULL),
(7, 38, 12, 5, 20, 'hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh', '2022-03-24', '', NULL),
(8, 38, 13, 4, 20, 'xxxxxxxxxxxxxxxxxx', '2022-03-26', '', NULL),
(9, 55, 17, 5, 20, 'testing', '2022-04-04', '', NULL),
(10, 49, 19, 5, 20, 'test', '2022-04-04', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_stages`
--

CREATE TABLE `tbl_project_stages` (
  `id` int NOT NULL,
  `stage` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `days` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_stages`
--

INSERT INTO `tbl_project_stages` (`id`, `stage`, `description`, `days`) VALUES
(1, 'Plan', 'Planned project awaiting approval', 365),
(2, 'Assign Team', 'Add Project Team Members', 30),
(3, 'Mapping', 'Project Mapping by assigning location coordinates', 30),
(4, 'Add Activities', 'Add Project Milestones and Tasks', 10),
(5, 'Add Financial Plan', 'Add Project Financial Plan', 15),
(6, 'Procurement', 'Add Procurement details', 60),
(7, 'Implementation', 'Project implementation stage', 0),
(8, 'Evaluation', 'Project evaluation', 0),
(9, 'Impact', 'Project impact', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_team_roles`
--

CREATE TABLE `tbl_project_team_roles` (
  `id` int NOT NULL,
  `role` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `rank` int NOT NULL,
  `active` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_team_roles`
--

INSERT INTO `tbl_project_team_roles` (`id`, `role`, `description`, `rank`, `active`) VALUES
(1, 'Project Manager', 'Project Team Leader', 1, 1),
(2, 'Team Leader', 'Project Deputy Team Leader', 2, 1),
(3, 'Assistant Team Leader', 'Project Alias Officer', 3, 1),
(4, 'Team Member', 'Project Monitoring and Evaluation Officer', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_tender_details`
--

CREATE TABLE `tbl_project_tender_details` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `costlineid` int NOT NULL,
  `tasks` int NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `unit` varchar(255) NOT NULL,
  `unit_cost` double NOT NULL,
  `units_no` int NOT NULL,
  `sdate` date DEFAULT NULL,
  `edate` date DEFAULT NULL,
  `comments` text,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `update_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_tender_details`
--

INSERT INTO `tbl_project_tender_details` (`id`, `projid`, `outputid`, `costlineid`, `tasks`, `description`, `unit`, `unit_cost`, `units_no`, `sdate`, `edate`, `comments`, `created_by`, `date_created`, `update_by`, `date_updated`) VALUES
(1, 8, 63, 5, 2, 'Labour', 'Days', 50000, 9, NULL, NULL, NULL, 'Admin0', '2022-01-18', NULL, NULL),
(2, 8, 63, 6, 4, 'Labour', 'Days', 50000, 15, NULL, NULL, NULL, 'Admin0', '2022-01-18', NULL, NULL),
(3, 8, 63, 10, 6, 'Materials', 'Units', 100000, 26, NULL, NULL, NULL, 'Admin0', '2022-01-18', NULL, NULL),
(4, 8, 63, 8, 8, 'Materials', 'Units', 100000, 15, NULL, NULL, NULL, 'Admin0', '2022-01-18', NULL, NULL),
(5, 8, 63, 9, 9, 'Materials', 'Units', 50000, 20, NULL, NULL, NULL, 'Admin0', '2022-01-18', NULL, NULL),
(6, 8, 63, 11, 10, 'Timber', 'Foot', 3000, 100, NULL, NULL, NULL, 'Admin0', '2022-01-18', NULL, NULL),
(7, 8, 63, 12, 13, 'Iron Sheets', 'Number', 200, 1000, NULL, NULL, NULL, 'Admin0', '2022-01-18', NULL, NULL),
(8, 24, 100, 101, 45, 'Quae accusantium sus', 'Dignissimos quos cup', 100, 5000, NULL, NULL, NULL, 'Admin0', '2022-02-13', NULL, NULL),
(9, 24, 100, 102, 45, 'Aut magni debitis no', 'Nostrud quos assumen', 1999, 1000, NULL, NULL, NULL, 'Admin0', '2022-02-13', NULL, NULL),
(10, 24, 100, 101, 45, 'Quae accusantium sus', 'Dignissimos quos cup', 100, 5000, NULL, NULL, NULL, 'Admin0', '2022-02-13', NULL, NULL),
(11, 24, 100, 102, 45, 'Aut magni debitis no', 'Nostrud quos assumen', 1999, 1000, NULL, NULL, NULL, 'Admin0', '2022-02-13', NULL, NULL),
(12, 7, 61, 103, 14, 'test', 'T', 1000, 80, NULL, NULL, NULL, 'admin0', '2022-02-18', NULL, NULL),
(13, 7, 61, 104, 15, 'ab', 'T', 2000, 500, NULL, NULL, NULL, 'admin0', '2022-02-18', NULL, NULL),
(14, 7, 61, 105, 16, 'sg', 'Y', 80000, 100, NULL, NULL, NULL, 'admin0', '2022-02-18', NULL, NULL),
(15, 7, 61, 106, 17, 'wy', 'Y', 5000, 1000, NULL, NULL, NULL, 'admin0', '2022-02-18', NULL, NULL),
(22, 27, 105, 121, 46, 'Labour', 'Day', 30000, 15, NULL, NULL, NULL, 'admin0', '2022-02-25', NULL, NULL),
(23, 27, 105, 122, 47, 'Machinery/equipment Hire ', 'Day', 90000, 20, NULL, NULL, NULL, 'admin0', '2022-02-25', NULL, NULL),
(24, 27, 105, 123, 47, 'Labour', 'Day', 50000, 25, NULL, NULL, NULL, 'admin0', '2022-02-25', NULL, NULL),
(25, 27, 105, 122, 47, 'Machinery/equipment Hire ', 'Day', 90000, 20, NULL, NULL, NULL, 'admin0', '2022-02-25', NULL, NULL),
(26, 27, 105, 123, 47, 'Labour', 'Day', 50000, 25, NULL, NULL, NULL, 'admin0', '2022-02-25', NULL, NULL),
(27, 27, 105, 128, 48, 'Labour', 'Day', 30000, 7, NULL, NULL, NULL, 'admin0', '2022-02-25', NULL, NULL),
(28, 38, 134, 136, 50, 'Hiring of equipment', 'Days', 50000, 10, NULL, NULL, NULL, 'Admin0', '2022-03-24', NULL, NULL),
(29, 38, 134, 137, 51, 'Labour', 'Days', 100000, 10, NULL, NULL, NULL, 'Admin0', '2022-03-24', NULL, NULL),
(30, 38, 134, 138, 52, 'Casual Labourers', 'Number', 50000, 13, NULL, NULL, NULL, 'Admin0', '2022-03-24', NULL, NULL),
(31, 38, 134, 139, 53, 'Casual Labourers', 'No.', 100000, 30, NULL, NULL, NULL, 'Admin0', '2022-03-24', NULL, NULL),
(32, 39, 135, 146, 54, 'Labour', 'days', 50000, 20, NULL, NULL, NULL, 'Admin0', '2022-03-26', NULL, NULL),
(33, 39, 135, 147, 55, 'Labour', 'days', 100000, 60, NULL, NULL, NULL, 'Admin0', '2022-03-26', NULL, NULL),
(34, 39, 135, 152, 56, 'materials', 'units', 20000, 100, NULL, NULL, NULL, 'Admin0', '2022-03-26', NULL, NULL),
(35, 44, 143, 158, 59, 'Labour', 'Day', 140800, 100, NULL, NULL, NULL, 'admin0', '2022-03-30', NULL, NULL),
(36, 44, 143, 159, 60, 'Casuals', 'day', 198000, 30, NULL, NULL, NULL, 'admin0', '2022-03-30', NULL, NULL),
(37, 49, 164, 198, 63, 'Labour', 'Days', 60000, 700, NULL, NULL, NULL, 'Admin0', '2022-04-01', NULL, NULL),
(38, 54, 169, 200, 64, 'Purchase ', 'Dozzer', 7900000, 6, NULL, NULL, NULL, 'admin0', '2022-04-03', NULL, NULL),
(39, 54, 169, 201, 65, 'Inspection ', 'Dozzer', 300000, 6, NULL, NULL, NULL, 'admin0', '2022-04-03', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_workflow_stage`
--

CREATE TABLE `tbl_project_workflow_stage` (
  `id` int NOT NULL,
  `stage` varchar(255) NOT NULL,
  `parent` int DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_workflow_stage`
--

INSERT INTO `tbl_project_workflow_stage` (`id`, `stage`, `parent`, `description`, `active`) VALUES
(1, 'Planned ', 0, NULL, 1),
(2, 'Team ', 1, NULL, 1),
(3, 'Mapping', 2, NULL, 1),
(4, 'Activities', 3, NULL, 1),
(5, 'Financial Plan', 4, NULL, 1),
(6, 'Procurement', 5, NULL, 1),
(9, 'Baseline Survey', 8, NULL, 1),
(7, 'Work Plan', 6, NULL, 1),
(8, 'M&E Plan', 7, NULL, 1),
(10, 'Implementation', 9, NULL, 1),
(11, 'Evaluation', 10, NULL, 1),
(13, 'Inspection Checklist', 6, NULL, 0),
(15, 'Quarterly Targets', 6, 'Add quarterly targets ready for implementation', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_workflow_stage_old`
--

CREATE TABLE `tbl_project_workflow_stage_old` (
  `id` int NOT NULL,
  `stage` varchar(255) NOT NULL,
  `parent` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_workflow_stage_old`
--

INSERT INTO `tbl_project_workflow_stage_old` (`id`, `stage`, `parent`) VALUES
(1, 'Planned ', 0),
(2, 'Team ', 1),
(3, 'Mapping', 2),
(4, 'Activities', 3),
(5, 'Checklist', 4),
(6, 'Financial Plan', 5),
(7, 'Procurement', 6),
(8, 'Baseline Survey', 7),
(9, 'Work Plan', 8),
(10, 'MnE Plan', 9),
(11, 'Implementation  ', 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_workflow_stage_timelines`
--

CREATE TABLE `tbl_project_workflow_stage_timelines` (
  `id` int NOT NULL,
  `workflow` int NOT NULL,
  `category` varchar(100) NOT NULL,
  `stage` int NOT NULL,
  `status` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `time` int NOT NULL,
  `escalate_after` int NOT NULL,
  `units` varchar(100) NOT NULL,
  `escalate_to` int NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_workflow_stage_timelines`
--

INSERT INTO `tbl_project_workflow_stage_timelines` (`id`, `workflow`, `category`, `stage`, `status`, `description`, `time`, `escalate_after`, `units`, `escalate_to`, `active`) VALUES
(1, 10, 'issue', 1, 'Open', 'project team leader assign issue owner', 5, 2, 'days', 0, 1),
(2, 10, 'issue', 2, 'Analysis', 'issue owner start working on the assigned issue', 5, 2, 'days', 0, 1),
(3, 10, 'issue', 3, 'Analyzed', 'Issue analyzed and report ready', 2, 1, 'days', 0, 1),
(4, 10, 'issue', 4, 'Escalated', 'Issue escalated to project committee for further action', 30, 5, 'days', 0, 1),
(5, 10, 'issue', 5, 'On Hold', 'project committee discussed escalated issue and decide to put the project on hold', 30, 2, 'days', 0, 1),
(6, 10, 'issue', 6, 'Continue', 'Project committee decides to let the project continue', 5, 2, 'days', 0, 1),
(7, 10, 'issue', 7, 'Closed', 'Issue closed by the project team leader', 5, 2, 'days', 0, 1),
(8, 9, 'Baseline', 1, 'Pending', 'Pending Form Details', 30, 5, 'days', 0, 0),
(9, 9, 'Baseline', 2, 'Pending Deployment', 'Pending Deployment', 10, 5, 'days', 0, 0),
(11, 11, 'Endline', 1, 'Pending', 'Pending Form Details', 30, 5, 'days', 0, 0),
(12, 7, 'mne', 1, 'Pending', 'Pending', 30, 2, 'days', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projfunding`
--

CREATE TABLE `tbl_projfunding` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `projid` int NOT NULL,
  `sourcecategory` int NOT NULL,
  `amountfunding` double NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projfunding`
--

INSERT INTO `tbl_projfunding` (`id`, `progid`, `projid`, `sourcecategory`, `amountfunding`, `created_by`, `date_created`) VALUES
(2, 1, 2, 1, 50000000, 'admin0', '2022-01-16 00:00:00'),
(3, 2, 3, 3, 49000000, 'admin0', '2022-01-16 00:00:00'),
(4, 2, 4, 3, 17000000, 'admin0', '2022-01-16 00:00:00'),
(5, 5, 5, 1, 100000000, 'Admin0', '2022-01-16 00:00:00'),
(6, 5, 6, 1, 12000000, 'Admin0', '2022-01-16 00:00:00'),
(7, 6, 7, 2, 15000000, 'Admin0', '2022-01-16 00:00:00'),
(9, 6, 8, 2, 10000000, 'Admin0', '2022-01-16 00:00:00'),
(10, 2, 1, 3, 50000000, 'admin0', '2022-01-16 00:00:00'),
(11, 2, 9, 3, 64000000, 'admin0', '2022-01-18 00:00:00'),
(12, 5, 10, 1, 2000000, 'Admin0', '2022-01-19 00:00:00'),
(13, 7, 11, 2, 3000000, 'admin0', '2022-01-24 00:00:00'),
(14, 2, 12, 3, 100, 'admin0', '2022-01-28 00:00:00'),
(15, 2, 13, 3, 100, 'admin0', '2022-01-28 00:00:00'),
(17, 2, 14, 3, 300, 'admin0', '2022-01-28 00:00:00'),
(18, 2, 15, 3, 14000000, 'admin0', '2022-01-28 00:00:00'),
(19, 8, 16, 2, 15000000, 'admin0', '2022-01-28 00:00:00'),
(20, 2, 18, 3, 15000000, 'admin0', '2022-02-01 00:00:00'),
(22, 2, 20, 3, 13000000, 'admin0', '2022-02-02 00:00:00'),
(23, 2, 21, 3, 17000000, 'admin0', '2022-02-02 00:00:00'),
(24, 2, 22, 3, 2000000, 'admin0', '2022-02-02 00:00:00'),
(25, 2, 23, 3, 7000000, 'admin0', '2022-02-08 00:00:00'),
(26, 13, 24, 3, 5000000, 'Admin0', '2022-02-13 00:00:00'),
(27, 2, 25, 3, 4000000, 'admin0', '2022-02-17 00:00:00'),
(28, 47, 26, 1, 40000, 'admin0', '2022-02-24 00:00:00'),
(29, 2, 27, 3, 6000000, 'admin0', '2022-02-25 00:00:00'),
(31, 2, 29, 3, 17000000, 'admin0', '2022-03-15 00:00:00'),
(32, 49, 30, 2, 10000000, 'Admin0', '2022-03-19 00:00:00'),
(34, 49, 33, 1, 200, 'admin0', '2022-03-20 00:00:00'),
(35, 50, 34, 2, 20000000, 'Admin0', '2022-03-20 00:00:00'),
(36, 51, 35, 2, 10000000, 'Admin0', '2022-03-20 00:00:00'),
(37, 52, 36, 2, 50000000, 'Admin0', '2022-03-20 00:00:00'),
(39, 54, 38, 1, 1600000, 'Admin0', '2022-03-23 00:00:00'),
(40, 54, 38, 3, 4000000, 'Admin0', '2022-03-23 00:00:00'),
(41, 55, 39, 2, 10000000, 'Admin0', '2022-03-24 00:00:00'),
(42, 56, 40, 2, 10000000, 'Admin0', '2022-03-25 00:00:00'),
(43, 58, 41, 2, 15000000, 'Admin0', '2022-03-25 00:00:00'),
(44, 59, 42, 1, 10000000, 'Admin0', '2022-03-26 00:00:00'),
(45, 60, 43, 1, 50000000, 'Admin0', '2022-03-28 00:00:00'),
(46, 61, 44, 1, 30000000, 'admin0', '2022-03-29 00:00:00'),
(47, 62, 45, 4, 10000000, 'Admin0', '2022-03-30 00:00:00'),
(48, 61, 46, 1, 15000000, '1', '2022-03-31 00:00:00'),
(49, 61, 47, 1, 10000000, '1', '2022-03-31 00:00:00'),
(50, 61, 48, 1, 24000000, '1', '2022-03-31 00:00:00'),
(51, 63, 49, 1, 50000000, '1', '2022-04-01 00:00:00'),
(52, 64, 50, 1, 20000000, '1', '2022-04-02 00:00:00'),
(53, 64, 51, 1, 6000000, '1', '2022-04-02 00:00:00'),
(54, 64, 52, 1, 20000000, '1', '2022-04-02 00:00:00'),
(55, 64, 53, 1, 10000000, '1', '2022-04-02 00:00:00'),
(56, 64, 54, 1, 50000000, '1', '2022-04-02 00:00:00'),
(57, 65, 55, 1, 1000000, '1', '2022-04-04 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projfunding_history`
--

CREATE TABLE `tbl_projfunding_history` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `projid` int NOT NULL,
  `sourcecategory` int NOT NULL,
  `amountfunding` double NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projissues`
--

CREATE TABLE `tbl_projissues` (
  `id` int NOT NULL,
  `monitoringid` int NOT NULL DEFAULT '0',
  `formid` varchar(100) NOT NULL,
  `projid` int NOT NULL,
  `origin` varchar(100) NOT NULL,
  `opid` int NOT NULL DEFAULT '0',
  `taskid` int DEFAULT NULL,
  `issue_type` int DEFAULT NULL,
  `risk_category` int NOT NULL,
  `observation` text NOT NULL,
  `recommendation` varchar(255) DEFAULT NULL,
  `owner` int DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `priority` int DEFAULT NULL,
  `assessment` int NOT NULL DEFAULT '0',
  `created_by` varchar(100) NOT NULL,
  `date_created` varchar(255) NOT NULL,
  `assigned_by` varchar(100) DEFAULT NULL,
  `date_assigned` date DEFAULT NULL,
  `escalated_by` varchar(100) DEFAULT NULL,
  `date_escalated` date DEFAULT NULL,
  `closed_by` varchar(100) DEFAULT NULL,
  `date_closed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projissues`
--

INSERT INTO `tbl_projissues` (`id`, `monitoringid`, `formid`, `projid`, `origin`, `opid`, `taskid`, `issue_type`, `risk_category`, `observation`, `recommendation`, `owner`, `status`, `priority`, `assessment`, `created_by`, `date_created`, `assigned_by`, `date_assigned`, `escalated_by`, `date_escalated`, `closed_by`, `date_closed`) VALUES
(1, 4, 'MWMSY', 8, 'monitoring', 63, NULL, NULL, 24, 'Inadequate manpower, Go slow by the casuals at site citing non payment of their dues ', NULL, 46, 7, 1, 1, '1', '2022-02-22 00:00:00', '1', '2022-03-01', '1', '2022-03-02', '1', '2022-03-06 03:03:52'),
(2, 6, 'NOSOD', 8, 'monitoring', 63, NULL, NULL, 24, 'test', NULL, 46, 7, 1, 0, '1', '2022-02-26 00:00:00', '1', '2022-03-02', NULL, NULL, '1', '2022-03-05 12:03:05'),
(3, 0, '1234', 8, 'Public', 0, NULL, NULL, 1, 'Mollit facere molest', NULL, NULL, 1, NULL, 0, '0', '16-03-2022', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 0, '1234', 8, 'Public', 0, NULL, NULL, 1, 'Mollit facere molest', NULL, NULL, 1, NULL, 0, '0', '16-03-2022', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 0, '1234', 8, 'Public', 0, NULL, NULL, 1, 'Mollit facere molest', NULL, NULL, 1, NULL, 0, '0', '16-03-2022', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 7, 'SJLLP', 8, 'monitoring', 63, NULL, NULL, 24, 'Delayed funding', NULL, 46, 6, 1, 0, '1', '2022-03-23', '1', '2022-03-23', '1', '2022-03-23', NULL, NULL),
(7, 8, 'SJLYY', 8, 'monitoring', 63, NULL, NULL, 24, 'Reduced funding', NULL, 47, 2, 1, 0, '1', '2022-03-23', '1', '2022-03-24', NULL, NULL, NULL, NULL),
(8, 9, 'SJNPI', 8, 'monitoring', 63, NULL, NULL, 24, 'Complaint by the contractor about delayed funding', NULL, NULL, 1, NULL, 0, '1', '2022-03-23', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 10, 'SJNPI', 8, 'monitoring', 63, NULL, NULL, 24, 'Complaint by the contractor about delayed funding', NULL, 46, 3, 1, 0, '1', '2022-03-23', '1', '2022-03-23', NULL, NULL, NULL, NULL),
(10, 11, 'SJOWI', 8, 'monitoring', 63, NULL, NULL, 24, 'Delayed payment to suppliers', NULL, 47, 2, 1, 0, '1', '2022-03-23', '1', '2022-03-23', NULL, NULL, NULL, NULL),
(11, 12, 'SJOWI', 8, 'monitoring', 63, NULL, NULL, 24, 'Delayed payment to suppliers', NULL, 46, 6, 1, 1, '1', '2022-03-23', '1', '2022-03-23', '1', '2022-03-23', NULL, NULL),
(12, 13, 'SPFET', 38, 'monitoring', 134, NULL, NULL, 24, 'The contractor is complaining about delay in payment', NULL, 40, 6, 1, 0, '1', '2022-03-24', '1', '2022-03-24', '1', '2022-03-24', NULL, NULL),
(13, 14, 'SPITP', 38, 'monitoring', 134, NULL, NULL, 24, 'TEST', NULL, 41, 4, 1, 0, '1', '2022-03-24', '1', '2022-03-26', '1', '2022-03-26', NULL, NULL),
(14, 15, 'SPITP', 38, 'monitoring', 134, NULL, NULL, 24, 'TEST', NULL, 33, 2, 1, 0, '1', '2022-03-24', '1', '2022-03-25', NULL, NULL, NULL, NULL),
(15, 17, 'TZFDM', 44, 'monitoring', 143, NULL, NULL, 24, 'test', NULL, NULL, 1, NULL, 0, '1', '2022-03-31', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 18, 'TZGBW', 44, 'monitoring', 143, NULL, NULL, 24, 'delay', NULL, NULL, 1, NULL, 0, '1', '2022-03-31', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 20, 'URNLG', 55, 'monitoring', 170, NULL, NULL, 24, 'Delay in disbursement of fund', NULL, 46, 7, 1, 0, '1', '2022-04-04', '1', '2022-04-04', '1', '2022-04-04', '1', '2022-04-04 17:04:47'),
(18, 21, 'UROXO', 45, 'monitoring', 151, NULL, NULL, 24, 'Delay in payment', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 22, 'URWJF', 49, 'monitoring', 164, NULL, NULL, 24, 'Delay', NULL, 80, 7, 1, 0, '1', '2022-04-04', '1', '2022-04-04', NULL, NULL, '1', '2022-04-04 17:04:42'),
(20, 23, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(21, 24, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 25, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(23, 26, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(24, 27, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(25, 28, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(26, 29, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(27, 30, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(28, 31, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(29, 32, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(30, 33, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(31, 34, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(32, 35, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(33, 36, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(34, 37, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(35, 38, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(36, 39, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(37, 40, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(38, 41, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(39, 42, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(40, 43, 'USWDA', 45, 'monitoring', 151, NULL, NULL, 24, 'Sunt veniam eos a', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(41, 44, 'USYVB', 45, 'monitoring', 151, NULL, NULL, 24, 'Quia sed et aliqua ', NULL, NULL, 1, NULL, 0, '1', '2022-04-04', NULL, NULL, NULL, NULL, NULL, NULL),
(42, 45, 'XFLIL', 49, 'monitoring', 164, NULL, NULL, 24, 'Sint et impedit eiu', NULL, NULL, 1, NULL, 0, '1', '2022-04-18', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projissues_discussions`
--

CREATE TABLE `tbl_projissues_discussions` (
  `id` int NOT NULL,
  `parent` int NOT NULL,
  `projid` int NOT NULL,
  `issueid` int NOT NULL,
  `owner` varchar(100) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `floc` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projissues_discussions`
--

INSERT INTO `tbl_projissues_discussions` (`id`, `parent`, `projid`, `issueid`, `owner`, `comment`, `floc`, `status`, `date_created`) VALUES
(1, 0, 8, 1, '1', 'Inadequate and delayed exchequer funding: Inadequate manpower, Go slow by the casuals at site citing non payment of their dues ', NULL, 2, '2022-03-01 22:41:37'),
(2, 1, 8, 1, '1', 'Testing', NULL, 1, '2022-03-01 22:46:04'),
(3, 1, 8, 1, '1', 'Its working', NULL, 1, '2022-03-01 22:46:27'),
(4, 1, 8, 1, '1', 'That\'s nice', NULL, 1, '2022-03-01 22:46:40'),
(5, 1, 8, 1, '1', 'Greate!', NULL, 1, '2022-03-01 22:56:46'),
(6, 0, 8, 2, '1', 'Inadequate and delayed exchequer funding: test', NULL, 2, '2022-03-02 11:24:56'),
(7, 6, 8, 2, '1', 'The PM to raise it with Finance', NULL, 1, '2022-03-02 11:25:57'),
(8, 6, 8, 2, '1', 'Finance indicated that they are given two days to follow it up', NULL, 1, '2022-03-02 11:26:51'),
(9, 6, 8, 2, '1', 'Okay', NULL, 1, '2022-03-02 11:27:28'),
(10, 0, 8, 10, '1', 'Inadequate and delayed exchequer funding: Delayed payment to suppliers', NULL, 2, '2022-03-23 11:34:07'),
(11, 0, 8, 11, '1', 'Inadequate and delayed exchequer funding: Delayed payment to suppliers', NULL, 2, '2022-03-23 11:43:07'),
(12, 11, 8, 11, '1', 'Hey Guys, we have an issue regarding delay funding', NULL, 1, '2022-03-23 11:43:55'),
(13, 11, 8, 11, '1', 'Hey Guys, we have an issue regarding delay funding', NULL, 1, '2022-03-23 11:44:11'),
(14, 11, 8, 11, '1', 'I have confirmed with the department that there is funds already disbursed', NULL, 1, '2022-03-23 11:46:45'),
(15, 0, 8, 6, '1', 'Inadequate and delayed exchequer funding: Delayed funding', NULL, 2, '2022-03-23 21:02:50'),
(16, 15, 8, 6, '1', 'what do we do about the project?', NULL, 1, '2022-03-23 21:03:36'),
(17, 15, 8, 6, '1', '', NULL, 1, '2022-03-23 21:04:11'),
(18, 0, 8, 9, '1', 'Inadequate and delayed exchequer funding: Complaint by the contractor about delayed funding', NULL, 2, '2022-03-23 21:30:21'),
(19, 18, 8, 9, '1', 'i have sorted the issue', NULL, 1, '2022-03-23 21:31:07'),
(20, 0, 38, 12, '1', 'Inadequate and delayed exchequer funding: The contractor is complaining about delay in payment', NULL, 2, '2022-03-24 15:11:21'),
(21, 20, 38, 12, '1', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', NULL, 1, '2022-03-24 15:11:43'),
(22, 20, 38, 12, '1', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', NULL, 1, '2022-03-24 15:11:56'),
(23, 20, 38, 12, '1', 'fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff', NULL, 1, '2022-03-24 15:12:19'),
(24, 0, 38, 14, '1', 'Inadequate and delayed exchequer funding: TEST', NULL, 1, '2022-03-25 15:16:44'),
(25, 24, 38, 14, '1', 'Test', NULL, 1, '2022-03-25 15:17:04'),
(26, 24, 38, 14, '1', 'Test', NULL, 1, '2022-03-25 15:17:23'),
(27, 24, 38, 14, '1', 'Test', NULL, 1, '2022-03-25 16:03:42'),
(28, 0, 38, 13, '1', 'Inadequate and delayed exchequer funding: TEST', NULL, 2, '2022-03-26 12:52:02'),
(29, 28, 38, 13, '1', 'Testing', NULL, 1, '2022-03-26 12:52:57'),
(30, 28, 38, 13, '1', 'Testing', NULL, 1, '2022-03-26 12:53:37'),
(31, 28, 38, 13, '1', 'Testing', NULL, 1, '2022-03-26 12:53:50'),
(32, 28, 38, 13, '1', 'Testing', NULL, 1, '2022-03-26 12:54:01'),
(33, 28, 38, 13, '1', 'Testing', NULL, 1, '2022-03-26 12:54:12'),
(34, 0, 55, 17, '1', 'Financial: Delay in disbursement of fund', NULL, 2, '2022-04-04 16:47:57'),
(35, 34, 55, 17, '1', 'check whether the funds have been disbursed', NULL, 1, '2022-04-04 16:48:49'),
(36, 34, 55, 17, '1', 'check whether the funds have been disbursed', NULL, 1, '2022-04-04 16:49:04'),
(37, 0, 49, 19, '1', 'Financial: Delay', NULL, 2, '2022-04-04 17:53:56'),
(38, 37, 49, 19, '1', 'check whether the funds have been disbursed', NULL, 1, '2022-04-04 17:54:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projissue_comments`
--

CREATE TABLE `tbl_projissue_comments` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `rskid` int NOT NULL,
  `stage` int NOT NULL,
  `comments` text NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projissue_comments`
--

INSERT INTO `tbl_projissue_comments` (`id`, `projid`, `rskid`, `stage`, `comments`, `created_by`, `date_created`) VALUES
(1, 8, 1, 4, '<p>Testing</p>', 'admin0', '2022-03-03'),
(3, 8, 1, 5, '<p>Testing</p>', 'admin0', '2022-03-04'),
(4, 8, 1, 4, '<p>Testing</p>', 'admin0', '2022-03-04'),
(5, 8, 2, 5, 'Testing', '1', '2022-03-05'),
(6, 8, 1, 5, 'Testing', '1', '2022-03-06'),
(7, 8, 1, 7, 'Testing again issue close', '1', '2022-03-06'),
(8, 8, 1, 7, 'Testing more', '1', '2022-03-06'),
(9, 8, 11, 2, '', '1', '2022-03-23'),
(10, 8, 10, 2, '', '1', '2022-03-23'),
(11, 8, 6, 2, 'test', '1', '2022-03-23'),
(12, 8, 6, 4, '<p>test</p>', '1', '2022-03-23'),
(13, 8, 9, 2, '', '1', '2022-03-23'),
(22, 8, 11, 4, '<p>Testing</p>', '1', '2022-03-24'),
(23, 8, 11, 5, '<p>Testing</p>', 'admin0', '2022-03-24'),
(24, 8, 11, 5, 'The committee has decided to <strong>Restore</strong> the project to its previous status (On Track).<br> The Project parameters as been affected as follows: There is no change in both the project budget and the timeline.', '1', '2022-03-24'),
(25, 8, 7, 2, 'TEST', '1', '2022-03-24'),
(26, 8, 6, 5, 'The committee has decided to <strong>Restore</strong> the project to its previous status (On Track).<br> The Project parameters as been affected as follows: There is no change in both the project budget and the timeline.', '1', '2022-03-24'),
(27, 38, 12, 2, 'deal', '1', '2022-03-24'),
(28, 38, 12, 4, '<p>yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy</p>', '1', '2022-03-24'),
(29, 38, 12, 5, 'The committee has decided to <strong>Restore</strong> the project to its previous status (On Track).<br> The Project parameters as been affected as follows: There is no change in both the project budget and the timeline.', '1', '2022-03-24'),
(30, 38, 14, 2, 'Testing', '1', '2022-03-25'),
(31, 38, 13, 2, 'xxxxxxxxxxxxxxxxxxxx', '1', '2022-03-26'),
(32, 55, 17, 2, 'testing', '1', '2022-04-04'),
(33, 55, 17, 4, '<p>Testing</p>', '1', '2022-04-04'),
(34, 55, 17, 5, 'The committee has decided to <strong>Restore</strong> the project to its previous status (Behind Schedule).<br> The Project parameters as been affected as follows: There is no change in both the project budget and the timeline.', '1', '2022-04-04'),
(35, 55, 17, 7, 'The issue has been resolved', '1', '2022-04-04'),
(36, 49, 19, 2, 'Testing', '1', '2022-04-04'),
(37, 49, 19, 7, 'Resolved', '1', '2022-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projissue_severity`
--

CREATE TABLE `tbl_projissue_severity` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `score` int NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projissue_severity`
--

INSERT INTO `tbl_projissue_severity` (`id`, `name`, `score`, `status`) VALUES
(1, 'Negligible', 1, 1),
(2, 'Minor', 2, 1),
(3, 'Moderate', 3, 1),
(4, 'Significant', 4, 1),
(5, 'Severe', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projmembers`
--

CREATE TABLE `tbl_projmembers` (
  `pmid` int NOT NULL,
  `ptid` int NOT NULL,
  `projid` int NOT NULL,
  `projidplus` varchar(255) DEFAULT NULL,
  `role` int NOT NULL,
  `ptleave` int NOT NULL DEFAULT '0',
  `reassignee` int DEFAULT NULL,
  `datereassigned` date DEFAULT NULL,
  `dateentered` date NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `dateupdated` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projmembers`
--

INSERT INTO `tbl_projmembers` (`pmid`, `ptid`, `projid`, `projidplus`, `role`, `ptleave`, `reassignee`, `datereassigned`, `dateentered`, `user_name`, `dateupdated`, `updatedby`) VALUES
(28, 3, 2, NULL, 1, 0, NULL, NULL, '2022-01-17', 'admin0', NULL, NULL),
(29, 17, 2, NULL, 2, 0, NULL, NULL, '2022-01-17', 'admin0', NULL, NULL),
(30, 11, 2, NULL, 1, 0, NULL, NULL, '2022-01-17', 'admin0', NULL, NULL),
(31, 16, 7, NULL, 4, 0, NULL, NULL, '2022-01-18', 'Admin0', NULL, NULL),
(32, 13, 7, NULL, 4, 0, NULL, NULL, '2022-01-18', 'Admin0', NULL, NULL),
(33, 15, 8, NULL, 4, 0, NULL, NULL, '2022-01-18', 'Admin0', NULL, NULL),
(34, 16, 8, NULL, 4, 0, NULL, NULL, '2022-01-18', 'Admin0', NULL, NULL),
(35, 6, 5, NULL, 4, 0, NULL, NULL, '2022-01-18', 'Admin0', NULL, NULL),
(36, 17, 5, NULL, 4, 0, NULL, NULL, '2022-01-18', 'Admin0', NULL, NULL),
(37, 14, 5, NULL, 4, 0, NULL, NULL, '2022-01-18', 'Admin0', NULL, NULL),
(38, 14, 6, NULL, 2, 0, NULL, NULL, '2022-01-18', 'Admin0', NULL, NULL),
(39, 17, 6, NULL, 3, 0, NULL, NULL, '2022-01-18', 'Admin0', NULL, NULL),
(40, 20, 6, NULL, 4, 0, NULL, NULL, '2022-01-18', 'Admin0', NULL, NULL),
(41, 2, 9, NULL, 1, 0, NULL, NULL, '2022-01-18', 'admin0', NULL, NULL),
(42, 17, 9, NULL, 2, 0, NULL, NULL, '2022-01-18', 'admin0', NULL, NULL),
(43, 2, 1, NULL, 1, 0, NULL, NULL, '2022-01-18', 'admin0', NULL, NULL),
(44, 13, 3, NULL, 1, 0, NULL, NULL, '2022-01-19', 'Admin0', NULL, NULL),
(45, 5, 3, NULL, 2, 0, NULL, NULL, '2022-01-19', 'Admin0', NULL, NULL),
(46, 7, 3, NULL, 4, 0, NULL, NULL, '2022-01-19', 'Admin0', NULL, NULL),
(47, 8, 11, NULL, 1, 0, NULL, NULL, '2022-01-25', 'admin0', NULL, NULL),
(48, 10, 11, NULL, 2, 0, NULL, NULL, '2022-01-25', 'admin0', NULL, NULL),
(49, 11, 21, NULL, 1, 0, NULL, NULL, '2022-02-02', 'admin0', NULL, NULL),
(50, 13, 21, NULL, 2, 0, NULL, NULL, '2022-02-02', 'admin0', NULL, NULL),
(51, 19, 21, NULL, 4, 0, NULL, NULL, '2022-02-02', 'admin0', NULL, NULL),
(52, 10, 22, NULL, 1, 0, NULL, NULL, '2022-02-02', 'admin0', NULL, NULL),
(53, 9, 22, NULL, 2, 0, NULL, NULL, '2022-02-02', 'admin0', NULL, NULL),
(54, 2, 4, NULL, 1, 0, NULL, NULL, '2022-02-13', 'Admin0', NULL, NULL),
(55, 5, 4, NULL, 2, 0, NULL, NULL, '2022-02-13', 'Admin0', NULL, NULL),
(56, 8, 4, NULL, 3, 0, NULL, NULL, '2022-02-13', 'Admin0', NULL, NULL),
(57, 9, 4, NULL, 4, 0, NULL, NULL, '2022-02-13', 'Admin0', NULL, NULL),
(58, 5, 23, NULL, 2, 0, NULL, NULL, '2022-02-13', 'Admin0', NULL, NULL),
(59, 8, 23, NULL, 3, 0, NULL, NULL, '2022-02-13', 'Admin0', NULL, NULL),
(60, 9, 23, NULL, 4, 0, NULL, NULL, '2022-02-13', 'Admin0', NULL, NULL),
(61, 6, 24, NULL, 2, 0, NULL, NULL, '2022-02-13', 'Admin0', NULL, NULL),
(62, 14, 24, NULL, 3, 0, NULL, NULL, '2022-02-13', 'Admin0', NULL, NULL),
(63, 17, 24, NULL, 4, 0, NULL, NULL, '2022-02-13', 'Admin0', NULL, NULL),
(64, 3, 25, NULL, 1, 0, NULL, NULL, '2022-02-17', 'admin0', NULL, NULL),
(65, 3, 12, NULL, 1, 0, NULL, NULL, '2022-02-21', 'admin0', NULL, NULL),
(66, 2, 27, NULL, 1, 0, NULL, NULL, '2022-02-25', 'admin0', NULL, NULL),
(67, 14, 27, NULL, 4, 0, NULL, NULL, '2022-02-25', 'admin0', NULL, NULL),
(68, 2, 18, NULL, 1, 0, NULL, NULL, '2022-03-20', 'Admin0', NULL, NULL),
(69, 5, 18, NULL, 2, 0, NULL, NULL, '2022-03-20', 'Admin0', NULL, NULL),
(70, 8, 18, NULL, 4, 0, NULL, NULL, '2022-03-20', 'Admin0', NULL, NULL),
(71, 11, 18, NULL, 4, 0, NULL, NULL, '2022-03-20', 'Admin0', NULL, NULL),
(72, 24, 35, NULL, 1, 0, NULL, NULL, '2022-03-20', 'Admin0', NULL, NULL),
(73, 25, 35, NULL, 2, 0, NULL, NULL, '2022-03-20', 'Admin0', NULL, NULL),
(74, 2, 38, NULL, 1, 0, NULL, NULL, '2022-03-23', 'Admin0', NULL, NULL),
(75, 8, 38, NULL, 2, 0, NULL, NULL, '2022-03-23', 'Admin0', NULL, NULL),
(76, 9, 38, NULL, 3, 0, NULL, NULL, '2022-03-23', 'Admin0', NULL, NULL),
(77, 10, 38, NULL, 4, 0, NULL, NULL, '2022-03-23', 'Admin0', NULL, NULL),
(78, 11, 38, NULL, 4, 0, NULL, NULL, '2022-03-23', 'Admin0', NULL, NULL),
(135, 34, 39, NULL, 1, 0, NULL, NULL, '2022-03-24', 'admin0', NULL, NULL),
(136, 35, 39, NULL, 2, 0, NULL, NULL, '2022-03-24', 'admin0', NULL, NULL),
(137, 29, 29, NULL, 2, 0, NULL, NULL, '2022-03-24', 'admin0', NULL, NULL),
(138, 34, 20, NULL, 1, 0, NULL, NULL, '2022-03-24', 'admin0', NULL, NULL),
(142, 3, 43, NULL, 1, 0, NULL, NULL, '2022-03-29', 'Admin0', NULL, NULL),
(143, 14, 43, NULL, 4, 0, NULL, NULL, '2022-03-29', 'Admin0', NULL, NULL),
(144, 17, 43, NULL, 4, 0, NULL, NULL, '2022-03-29', 'Admin0', NULL, NULL),
(145, 19, 40, NULL, 1, 0, NULL, NULL, '2022-03-29', 'admin0', NULL, NULL),
(146, 17, 40, NULL, 2, 0, NULL, NULL, '2022-03-29', 'admin0', NULL, NULL),
(147, 15, 40, NULL, 3, 0, NULL, NULL, '2022-03-29', 'admin0', NULL, NULL),
(148, 15, 44, NULL, 2, 0, NULL, NULL, '2022-03-29', 'admin0', NULL, NULL),
(149, 29, 44, NULL, 2, 0, NULL, NULL, '2022-03-29', 'admin0', NULL, NULL),
(150, 7, 45, NULL, 1, 0, NULL, NULL, '2022-03-30', 'Admin0', NULL, NULL),
(151, 19, 45, NULL, 2, 0, NULL, NULL, '2022-03-30', 'Admin0', NULL, NULL),
(152, 11, 26, NULL, 1, 0, NULL, NULL, '2022-03-30', 'admin0', NULL, NULL),
(153, 3, 48, NULL, 1, 0, NULL, NULL, '2022-03-31', 'admin0', NULL, NULL),
(154, 29, 49, NULL, 1, 0, NULL, NULL, '2022-04-01', 'Admin0', NULL, NULL),
(155, 30, 49, NULL, 2, 0, NULL, NULL, '2022-04-01', 'Admin0', NULL, NULL),
(156, 37, 49, NULL, 4, 0, NULL, NULL, '2022-04-01', 'Admin0', NULL, NULL),
(157, 6, 54, NULL, 1, 0, NULL, NULL, '2022-04-03', 'admin0', NULL, NULL),
(158, 34, 13, NULL, 1, 0, NULL, NULL, '2022-04-04', 'admin0', NULL, NULL),
(159, 4, 55, NULL, 1, 0, NULL, NULL, '2022-04-04', 'Admin0', NULL, NULL),
(160, 13, 55, NULL, 2, 0, NULL, NULL, '2022-04-04', 'Admin0', NULL, NULL),
(161, 15, 55, NULL, 4, 0, NULL, NULL, '2022-04-04', 'Admin0', NULL, NULL),
(162, 18, 55, NULL, 4, 0, NULL, NULL, '2022-04-04', 'Admin0', NULL, NULL),
(163, 36, 55, NULL, 4, 0, NULL, NULL, '2022-04-04', 'Admin0', NULL, NULL),
(164, 49, 16, NULL, 1, 0, NULL, NULL, '2022-04-06', '1', NULL, NULL),
(165, 48, 50, NULL, 1, 0, NULL, NULL, '2022-04-06', '1', NULL, NULL),
(168, 49, 51, NULL, 2, 0, NULL, NULL, '2022-04-06', '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projmemoffices`
--

CREATE TABLE `tbl_projmemoffices` (
  `moid` int NOT NULL,
  `office` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projmemoffices`
--

INSERT INTO `tbl_projmemoffices` (`moid`, `office`) VALUES
(1, 'Parent'),
(2, 'Monitoring & Evaluation'),
(3, 'Finance'),
(4, 'ICT'),
(5, 'Security'),
(6, 'Procurement'),
(7, 'Mechanical & Electronics Engineering'),
(8, 'Another Office 2'),
(9, 'Another Office 3');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projrisk_categories`
--

CREATE TABLE `tbl_projrisk_categories` (
  `rskid` int NOT NULL,
  `opid` int DEFAULT NULL,
  `department` int DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projrisk_categories`
--

INSERT INTO `tbl_projrisk_categories` (`rskid`, `opid`, `department`, `category`, `description`, `type`, `created_by`, `date_created`, `changed_by`, `date_changed`) VALUES
(24, NULL, NULL, 'Financial', NULL, '1,2,3', 'Admin0', '2020-11-14', '1', '2022-04-04'),
(26, NULL, NULL, 'Environmental', NULL, '1,2,3', 'Admin0', '2020-11-14', '1', '2022-04-04'),
(27, NULL, NULL, 'Human Resources', NULL, '1,2,3', 'Admin0', '2020-11-14', '1', '2022-04-04'),
(28, NULL, NULL, 'Legal', NULL, '1,2,3', 'Admin0', '2020-11-14', '1', '2022-04-04'),
(29, NULL, NULL, 'Sustainability', NULL, '1,2', 'Admin0', '2020-11-14', '1', '2022-04-04'),
(31, NULL, NULL, 'Utilization by beneficiaries', NULL, '1,2', 'Admin0', '2020-11-14', '1', '2022-04-04'),
(48, NULL, NULL, 'Technical', NULL, '2', '1', '2022-04-04', '1', '2022-04-16'),
(49, NULL, NULL, 'Operational', NULL, '1,2,3', '1', '2022-04-04', NULL, NULL),
(50, NULL, NULL, 'Category 123', NULL, '3', '1', '2022-04-16', '1', '2022-04-16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projrisk_response`
--

CREATE TABLE `tbl_projrisk_response` (
  `id` int NOT NULL,
  `cat` int NOT NULL,
  `response` varchar(100) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projrisk_response`
--

INSERT INTO `tbl_projrisk_response` (`id`, `cat`, `response`, `description`) VALUES
(2, 2, 'Sensitize the political class on the importance of conserving and sustainably managing forests ', NULL),
(3, 2, 'Develop partnerships with County Governments', NULL),
(4, 2, 'Sharing information with the public on forest operations', NULL),
(5, 3, 'Adapt climate resilience strategies and technologies', NULL),
(6, 4, 'Work closely with other security agencies to enhance security of public forests and corporate assets', NULL),
(7, 5, 'Intensify surveillance and protection of forests', NULL),
(8, 5, 'Involvement and participation of local communities in forest management', NULL),
(9, 7, 'Implement strategies that will enhance transparency and accountability in organizational operations ', NULL),
(10, 8, 'Anticipate changes in these factors and provide for them in the programme budget. ', NULL),
(11, 9, 'Embrace and adapt appropriate technologies in organizational operations', NULL),
(12, 10, 'Lobby the Government    and diversify funding sources.', NULL),
(13, 11, 'Monitor, identify and take appropriate actions', NULL),
(14, 12, 'Develop a disaster response plan ', NULL),
(15, 13, 'Monitor, identify and take appropriate actions', NULL),
(16, 14, 'Lobby for harmonization of the relevant legal, policies  and mandates', NULL),
(17, 21, 'The strategic plan is ï€‚exible and can easily be reviewed and aligned tothe new Government structur', NULL),
(18, 24, 'Review policies', NULL),
(19, 24, 'Pursue alternative financing streams', NULL),
(20, 24, 'Sustain existing Partnerships', NULL),
(21, 25, 'Entrenchment of corporate governance mechanisms, ', NULL),
(22, 25, 'Continuous staff training and implementatio n of the ethics and anticorruption measures', NULL),
(23, 25, 'Continuous staff training and implementation of the ethics and anticorruption measures', NULL),
(24, 26, 'Climate change adaptation and mitigation measures', NULL),
(25, 26, 'Cross Counties water sharing models', NULL),
(26, 27, 'Develop a career progression guideline to retain competent staff', NULL),
(27, 27, 'Introduce various incentives to motivate and retain staff', NULL),
(28, 28, 'compliance audits', NULL),
(29, 29, 'implementation of departmental policies and procedures', NULL),
(30, 30, 'Enforce the provisions of the service charter.', NULL),
(31, 31, 'Continuously carry out, progress review, resource mobilisation and financing mechanisms', NULL),
(32, 2, 'test 2', NULL),
(33, 2, 'test 3', NULL),
(34, 2, 'test 4', NULL),
(35, 4, 'test ', NULL),
(36, 4, 'test ', NULL),
(37, 4, 'test ', NULL),
(38, 2, 'test 122', NULL),
(39, 2, 'test 637748', NULL),
(40, 2, 'test 122', NULL),
(41, 2, 'test 637748', NULL),
(42, 3, 'Climate change methods  edited', NULL),
(43, 13, 'Recusandae Officia ', NULL),
(44, 13, 'Quos ipsa enim ulla', NULL),
(45, 13, 'Enim laboriosam ips', NULL),
(46, 4, 'test', NULL),
(48, 4, '', NULL),
(49, 4, '', NULL),
(50, 10, 'follow up', NULL),
(51, 43, 'mittest', NULL),
(52, 26, 'test', NULL),
(53, 48, 'Deserunt ut nemo dol', NULL),
(54, 48, 'Laudantium laudanti', NULL),
(55, 48, 'Omnis mollit natus c', NULL),
(56, 48, 'Explicabo Repellend', NULL),
(57, 48, 'Velit accusantium d', NULL),
(58, 48, 'Libero accusamus in ', NULL),
(59, 48, 'Aut omnis sed suscip', NULL),
(60, 48, 'Nulla similique minu', NULL),
(61, 48, 'Vero dolore consequa', NULL),
(62, 48, 'Nulla amet saepe er', NULL),
(63, 48, 'Non hic expedita ius', NULL),
(64, 48, 'Corporis consectetur', NULL),
(65, 26, 'Dolores natus iusto ', NULL),
(66, 26, 'Aliquid qui aliquam ', NULL),
(67, 26, 'Esse reprehenderit e', NULL),
(68, 26, 'Ea cupiditate sequi ', NULL),
(69, 26, 'Fuga Autem in deser', NULL),
(70, 26, 'Consequatur est a n', NULL),
(71, 26, 'Repellendus Repudia', NULL),
(72, 26, 'Minima sed voluptati', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projstage_responsible`
--

CREATE TABLE `tbl_projstage_responsible` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `stageid` int NOT NULL,
  `userid` int NOT NULL,
  `startdate` datetime NOT NULL,
  `lastdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projstatuschangereason`
--

CREATE TABLE `tbl_projstatuschangereason` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `status` varchar(50) NOT NULL,
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `reason` text NOT NULL,
  `originalcost` double NOT NULL,
  `originalenddate` date NOT NULL,
  `originaltarget` int NOT NULL,
  `entered_by` varchar(100) NOT NULL,
  `date_entered` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projstatuschangereason`
--

INSERT INTO `tbl_projstatuschangereason` (`id`, `projid`, `status`, `type`, `reason`, `originalcost`, `originalenddate`, `originaltarget`, `entered_by`, `date_entered`) VALUES
(1, 8, '11', '1', 'Inadequate and delayed exchequer funding', 25485000, '2023-06-15', 45, 'admin0', '2022-03-03 02:33:10'),
(2, 8, '4', '1', 'Inadequate and delayed exchequer funding', 25485000, '2024-06-14', 45, 'Admin0', '2022-03-23 21:21:57'),
(3, 8, '4', '1', 'Inadequate and delayed exchequer funding', 25485000, '2024-08-13', 45, 'admin0', '2022-03-24 05:06:31'),
(4, 8, '4', '1', 'Inadequate and delayed exchequer funding', 25485000, '2024-08-13', 45, 'admin0', '2022-03-24 05:13:51'),
(5, 8, '4', '1', 'Inadequate and delayed exchequer funding', 25485000, '2024-08-13', 45, 'admin0', '2022-03-24 05:23:51'),
(6, 8, '4', '1', 'Inadequate and delayed exchequer funding', 25485000, '2024-08-13', 45, '1', '2022-03-24 05:57:29'),
(7, 8, '4', '1', 'Inadequate and delayed exchequer funding', 25485000, '2024-08-13', 45, '1', '2022-03-24 06:00:17'),
(8, 8, '4', '1', 'Inadequate and delayed exchequer funding', 25485000, '2024-08-13', 45, '1', '2022-03-24 06:00:47'),
(9, 8, '4', '1', 'Inadequate and delayed exchequer funding', 25485000, '2024-08-13', 45, '1', '2022-03-24 06:02:11'),
(10, 38, '4', '1', 'Inadequate and delayed exchequer funding', 10415000, '2021-08-15', 45, '1', '2022-03-24 16:25:05'),
(11, 55, '11', '1', 'Financial', 5610000, '2021-09-28', 45, '1', '2022-04-04 17:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projstatusfiles`
--

CREATE TABLE `tbl_projstatusfiles` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `reasonid` int NOT NULL,
  `projstatus` varchar(255) NOT NULL,
  `floc` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projtable`
--

CREATE TABLE `tbl_projtable` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `projcode` varchar(255) NOT NULL,
  `projname` varchar(255) NOT NULL,
  `projdesc` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `nameone` varchar(100) DEFAULT NULL,
  `pathone` varchar(100) DEFAULT NULL,
  `nametwo` varchar(100) DEFAULT NULL,
  `pathtwo` varchar(100) DEFAULT NULL,
  `namethree` varchar(100) DEFAULT NULL,
  `paththree` varchar(100) DEFAULT NULL,
  `namefour` varchar(100) DEFAULT NULL,
  `pathfour` varchar(100) DEFAULT NULL,
  `namefive` varchar(100) DEFAULT NULL,
  `pathfive` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projteam2`
--

CREATE TABLE `tbl_projteam2` (
  `ptid` int NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `designation` int NOT NULL,
  `ministry` int DEFAULT NULL,
  `department` int DEFAULT NULL,
  `levelA` int NOT NULL,
  `levelB` int NOT NULL,
  `levelC` int NOT NULL,
  `floc` varchar(300) DEFAULT 'uploads/passport.jpg',
  `filename` varchar(300) DEFAULT NULL,
  `ftype` varchar(300) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `email` varchar(300) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `availability` int NOT NULL DEFAULT '1',
  `disabled` enum('0','1') NOT NULL DEFAULT '0',
  `createdby` int NOT NULL,
  `datecreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projteam2`
--

INSERT INTO `tbl_projteam2` (`ptid`, `fullname`, `firstname`, `middlename`, `lastname`, `title`, `designation`, `ministry`, `department`, `levelA`, `levelB`, `levelC`, `floc`, `filename`, `ftype`, `level`, `email`, `phone`, `availability`, `disabled`, `createdby`, `datecreated`) VALUES
(1, 'Super Admin', 'Super', '', 'Admin', 'Mr', 7, 9, 0, 0, 0, 0, 'uploads/staff/3705_File_logo.jpg', 'logo.jpg', 'jpg', '0', 'denkytheka1@gmail.com', '0727044818', 1, '0', 1, '2022-01-16'),
(47, 'Chaney Baird', 'Chaney', 'Hammett Dyer', 'Baird', 'Dr', 4, 0, 0, 0, 0, 0, 'uploads/staff/9358_File_273661006_4815991395184905_3329807975205578301_n.jpg', '273661006_4815991395184905_3329807975205578301_n.jpg', 'jpg', '0', 'kyhilyd@mailinator.com', '+1 (861) 851-5219', 1, '0', 1, '2022-04-06'),
(48, 'Abigail Obrien', 'Abigail', 'Belle Gill', 'Obrien', 'Dr', 2, 14, 26, 0, 0, 0, 'uploads/staff/9813_File_FPfy4VVXMAgKLjl.jpeg', '9813_File_FPfy4VVXMAgKLjl.jpeg', 'jpeg', NULL, 'wezad@mailinator.com', '+1 (172) 971-3201', 1, '0', 1, '2022-04-06'),
(49, 'James Bennett', 'James', 'Charissa Gilbert', 'Bennett', 'Mr', 8, 11, 30, 0, 0, 0, 'uploads/staff/5352_File_FPfy4VVXMAgKLjl.jpeg', '5352_File_FPfy4VVXMAgKLjl.jpeg', 'jpeg', NULL, 'mited@mailinator.com', '+1 (817) 417-6499', 1, '0', 1, '2022-04-06'),
(50, 'Dane Farley', 'Dane', 'Vielka Nixon', 'Farley', 'Dr', 1, 0, 0, 0, 0, 0, 'uploads/staff/8585_File_logo.jpg', '8585_File_logo.jpg', 'jpg', NULL, 'biwottech@gmail.com', '+1 (207) 641-7838', 1, '0', 4, '2022-04-21'),
(51, 'Madeson Oneal', 'Madeson', 'Michelle Preston', 'Oneal', 'Ms', 4, 0, 0, 0, 0, 0, 'uploads/staff/2594_File_logo.jpg', '2594_File_logo.jpg', 'jpg', NULL, 'koechvantos@gmail.com4', '+1 (659) 343-6982', 1, '0', 4, '2022-04-21'),
(52, 'Selma Craig', 'Selma', 'Pearl Hines', 'Craig', 'Mrs', 6, 9, 0, 0, 0, 0, 'uploads/staff/4707_File_logo.jpg', '4707_File_logo.jpg', 'jpg', NULL, 'koechvantos@gmail.com', '+1 (804) 271-3625', 1, '0', 4, '2022-04-21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projtypelist`
--

CREATE TABLE `tbl_projtypelist` (
  `projtypeid` int NOT NULL,
  `projtypelist` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projtypelist`
--

INSERT INTO `tbl_projtypelist` (`projtypeid`, `projtypelist`) VALUES
(1, 'New'),
(2, 'Existing');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_public_feedback`
--

CREATE TABLE `tbl_public_feedback` (
  `id` int NOT NULL,
  `issues_id` int NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_public_feedback`
--

INSERT INTO `tbl_public_feedback` (`id`, `issues_id`, `fullname`, `phone`, `email`, `type`) VALUES
(1, 7, 'James Herman', '+1 (338) 301-3567', 'jonyvu@mailinator.com', 2),
(2, 8, 'James Herman', '+1 (338) 301-3567', 'jonyvu@mailinator.com', 2),
(3, 9, 'James Herman', '+1 (338) 301-3567', 'jonyvu@mailinator.com', 2),
(4, 10, 'James Herman', '+1 (338) 301-3567', 'jonyvu@mailinator.com', 2),
(5, 11, 'James Herman', '+1 (338) 301-3567', 'jonyvu@mailinator.com', 2),
(6, 12, 'Dieter Nunez', '+1 (106) 123-5939', 'simej@mailinator.com', 2),
(7, 13, 'Dieter Nunez', '+1 (106) 123-5939', 'simej@mailinator.com', 2),
(8, 14, 'Dieter Nunez', '+1 (106) 123-5939', 'simej@mailinator.com', 2),
(9, 15, 'Dieter Nunez', '+1 (106) 123-5939', 'simej@mailinator.com', 2),
(10, 16, 'Dieter Nunez', '+1 (106) 123-5939', 'simej@mailinator.com', 2),
(11, 3, 'Lee Holman', '+1 (356) 935-1055', 'qofymuzope@mailinator.com', 1),
(12, 4, 'Lee Holman', '+1 (356) 935-1055', 'qofymuzope@mailinator.com', 1),
(13, 5, 'Lee Holman', '+1 (356) 935-1055', 'qofymuzope@mailinator.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_qapr_report_conclusion`
--

CREATE TABLE `tbl_qapr_report_conclusion` (
  `id` int NOT NULL,
  `stid` int NOT NULL,
  `year` int NOT NULL,
  `quarter` varchar(255) NOT NULL,
  `section_comments` longtext NOT NULL,
  `challenges` longtext NOT NULL,
  `conclusion` longtext NOT NULL,
  `appendices` longtext NOT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_qapr_report_conclusion`
--

INSERT INTO `tbl_qapr_report_conclusion` (`id`, `stid`, `year`, `quarter`, `section_comments`, `challenges`, `conclusion`, `appendices`, `created_by`, `created_at`) VALUES
(1, 9, 4, '2', '<p><span style=\"background-color:transparent; color:rgb(0, 0, 0); font-family:times new roman; font-size:12pt\">In this section, the department should provide a brief overview including its mandate and overall goals as stipulated in the CIDP.</span></p>', '<p><span style=\"background-color:transparent; color:rgb(0, 0, 0); font-family:times new roman; font-size:12pt\">In this section, the department should provide a brief overview including its mandate and overall goals as stipulated in the CIDP.</span></p>', '<p><span style=\"background-color:transparent; color:rgb(0, 0, 0); font-family:times new roman; font-size:12pt\">In this section, the department should provide a brief overview including its mandate and overall goals as stipulated in the CIDP.</span></p>', '<p><span style=\"background-color:transparent; color:rgb(0, 0, 0); font-family:times new roman; font-size:12pt\">In this section, the department should provide a brief overview including its mandate and overall goals as stipulated in the CIDP.</span></p>', 1, '2022-03-31 15:44:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_qapr_report_remarks`
--

CREATE TABLE `tbl_qapr_report_remarks` (
  `id` int NOT NULL,
  `indid` int NOT NULL,
  `year` int NOT NULL,
  `quarter` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_qapr_report_remarks`
--

INSERT INTO `tbl_qapr_report_remarks` (`id`, `indid`, `year`, `quarter`, `remarks`, `created_by`, `created_at`) VALUES
(1, 3, 4, '2', 'remarks', 1, '2022-03-31 00:00:00'),
(2, 4, 4, '2', 'In this section, the department should provide a brief overview including its mandate and overall goals as stipulated in the CIDP.', 1, '2022-03-31 00:00:00'),
(3, 2, 4, '2', 'In this section, the department should provide a brief overview including its mandate and overall goals as stipulated in the CIDP.', 1, '2022-03-31 00:00:00'),
(4, 1, 4, '2', 'In this section, the department should provide a brief overview including its mandate and overall goals as stipulated in the CIDP.', 1, '2022-03-31 00:00:00'),
(5, 12, 4, '2', 'In this section, the department should provide a brief overview including its mandate and overall goals as stipulated in the CIDP.', 1, '2022-03-31 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `id` int NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_role_escalation`
--

CREATE TABLE `tbl_role_escalation` (
  `id` int NOT NULL,
  `module` varchar(100) NOT NULL,
  `level` int NOT NULL,
  `designation` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_role_escalation`
--

INSERT INTO `tbl_role_escalation` (`id`, `module`, `level`, `designation`) VALUES
(1, 'issues', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sectors`
--

CREATE TABLE `tbl_sectors` (
  `stid` int NOT NULL,
  `parent` int DEFAULT NULL,
  `sector` varchar(300) NOT NULL,
  `shortname` varchar(100) DEFAULT NULL,
  `role_id` int NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sectors`
--

INSERT INTO `tbl_sectors` (`stid`, `parent`, `sector`, `shortname`, `role_id`, `deleted`, `changed_by`, `date_changed`) VALUES
(1, 0, 'Agriculture, Livestock Development and Fisheries', NULL, 2, '0', NULL, NULL),
(2, 1, 'Agriculture', NULL, 2, '0', NULL, NULL),
(3, 1, 'Livestock Development and Fisheries ', NULL, 2, '0', NULL, NULL),
(4, 1, 'Fisheries', NULL, 2, '1', NULL, NULL),
(5, 0, 'Education, Culture, Youth Affairs, Sports and Social Services ', NULL, 2, '0', NULL, NULL),
(6, 5, 'Education, Culture, and Social Services ', NULL, 2, '0', NULL, NULL),
(7, 5, 'Youth Affairs, Gender and Sports Development  ', NULL, 2, '0', NULL, NULL),
(8, 5, 'Youth Affairs', NULL, 2, '1', NULL, NULL),
(9, 0, 'Water, Environment, Natural Resources, Tourism and Wildlife Management ', NULL, 2, '0', NULL, NULL),
(10, 0, 'Finance and Economic Planning', NULL, 2, '0', NULL, NULL),
(11, 0, 'Information and Communication Technology, Trade and Industrialization ', NULL, 2, '0', NULL, NULL),
(12, 0, 'Health Services ', NULL, 2, '0', NULL, NULL),
(13, 0, 'Land, Housing, Physical Planning & Urban Development', NULL, 2, '0', NULL, NULL),
(14, 0, 'Devolution, Administration, and Public Service Management', NULL, 2, '0', NULL, NULL),
(15, 0, 'Roads, Transport, Energy and Public Works', NULL, 2, '0', NULL, NULL),
(16, 0, 'Cooperatives and Enterprise Development ', NULL, 2, '0', NULL, NULL),
(17, 10, 'Finance ', NULL, 2, '0', NULL, NULL),
(18, 10, 'Economic Planning', NULL, 1, '0', NULL, NULL),
(19, 15, 'Roads, Transport, Energy and Public Works ', NULL, 2, '0', NULL, NULL),
(20, 15, 'Transport', NULL, 2, '1', NULL, NULL),
(21, 15, 'Energy ', NULL, 2, '1', NULL, NULL),
(22, 15, 'Public Works', NULL, 2, '1', NULL, NULL),
(23, 9, 'Water and Environment and Natural Resources, Tourism and Wildlife Management  ', NULL, 2, '0', NULL, NULL),
(24, 12, 'Health Services ', NULL, 2, '0', NULL, NULL),
(25, 16, 'Cooperatives and Enterprise Development ', NULL, 2, '0', NULL, NULL),
(26, 14, 'Devolution and Public Administration ', NULL, 2, '0', NULL, NULL),
(27, 14, 'Public Service Management ', NULL, 2, '0', NULL, NULL),
(28, 13, 'Lands and Housing ', NULL, 2, '0', NULL, NULL),
(29, 13, 'Physical Planning and Urban Development ', NULL, 2, '0', NULL, NULL),
(30, 11, 'ICT and E-Government', NULL, 2, '0', NULL, NULL),
(31, 11, 'Trade, Investment and Industrialization', NULL, 2, '0', NULL, NULL),
(32, 0, 'SPORTS AND CULTURE', NULL, 2, '1', NULL, NULL),
(33, 32, 'SPORTS', NULL, 2, '0', NULL, NULL),
(34, 32, 'Culture ', NULL, 2, '0', NULL, NULL),
(35, 13, 'Municipality of Eldoret', NULL, 2, '0', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings_menu`
--

CREATE TABLE `tbl_settings_menu` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent` int NOT NULL,
  `icons` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `role` int NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sidebar_menu`
--

CREATE TABLE `tbl_sidebar_menu` (
  `id` int NOT NULL,
  `parent` int DEFAULT '0',
  `Name` varchar(255) NOT NULL,
  `icons` text NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `view_group` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `add_group` varchar(255) NOT NULL DEFAULT '0',
  `edit_group` varchar(255) NOT NULL DEFAULT '0',
  `delete_group` varchar(255) NOT NULL DEFAULT '0',
  `view_designation` varchar(255) NOT NULL DEFAULT '0',
  `add_designation` varchar(255) NOT NULL DEFAULT '0',
  `edit_designation` varchar(255) NOT NULL DEFAULT '0',
  `delete_designation` varchar(255) NOT NULL DEFAULT '0',
  `view_path` text NOT NULL,
  `add_path` text NOT NULL,
  `edit_path` text NOT NULL,
  `delete_path` text NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sidebar_menu`
--

INSERT INTO `tbl_sidebar_menu` (`id`, `parent`, `Name`, `icons`, `url`, `view_group`, `add_group`, `edit_group`, `delete_group`, `view_designation`, `add_designation`, `edit_designation`, `delete_designation`, `view_path`, `add_path`, `edit_path`, `delete_path`, `status`) VALUES
(1, 0, 'Dashboards', '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', NULL, '1,2,3,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(2, 0, 'Plans', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', NULL, '1,2,3,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(3, 0, 'Programs', '<i class=\"fa fa-object-group\" style=\"color:white\"></i>', NULL, '1,2,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(4, 0, 'Projects Data', '<i class=\"fa fa-briefcase\" style=\"color:white\"></i>', NULL, '1,2,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(5, 0, 'Implementation', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', NULL, '1,2,3,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(6, 0, 'Evaluation', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', NULL, '1,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(7, 0, 'Payment', '<i class=\"fa fa-money\" style=\"color:white\"></i>', NULL, '1,2,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(8, 0, 'Partners', '<i class=\"fa fa-slideshare\" style=\"color:white\"></i>', NULL, '1,2,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(9, 0, 'Issues & Risks', '<i class=\"fa fa-exclamation-circle\" style=\"color:white\"></i>', NULL, '1,2,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(10, 0, 'Indicators', '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', NULL, '1,2,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(11, 0, 'Contractors', '<i class=\"fa fa-shopping-bag\" style=\"color:white\"></i>', NULL, '1,2,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(12, 0, 'Personnel', '<i class=\"fa fa-users\" style=\"color:white\"></i>', NULL, '1,2,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(13, 0, 'Files', '<i class=\"fa fa-folder-open-o\" style=\"color:white\"></i>', NULL, '1,2,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(14, 0, 'Reports', '<i class=\"fa fa-file-text-o\" aria-hidden=\"true\" style=\"color:white\"></i>', NULL, '1,2,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(15, 0, 'Settings', '<i class=\"fa fa-cog fa-spin\" style=\"font-size:16px; color:red;\"></i>', NULL, '1,4', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1),
(16, 1, 'General Dashboard', '•&nbsp;', 'dashboard', '1,2,3,4', '0', '0', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(17, 1, 'Financial Dashboard', '•&nbsp;', 'view-financial-dashboard', '1,2,3,4', '0', '0', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(18, 1, 'Indicators GIS Dashboard', '•&nbsp;', 'view-indicator-map-dashboard', '1,2,3,4', '0', '0', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(19, 1, 'Projects GIS Dashboard', '•&nbsp;', 'view-project-map-dashboard', '1,2,3,4', '0', '0', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(20, 2, 'Plan', '•&nbsp;', 'view-strategic-plans', '1,2,3,4', '1,4', '1,4', '1,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(21, 2, 'Annual Plans (ADPs)', '•&nbsp;', 'view-adps', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(22, 2, 'Program Based Budgets', '•&nbsp;', 'programs-adp', '1,4', '1,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(23, 3, 'Independent Programs', '•&nbsp;', 'all-programs', '1,2,4', '', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(24, 3, 'Add Independent Program', '•&nbsp;', 'add-program', '2,4', '2,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(25, 4, 'Add Project Team', '•&nbsp;', 'add-project-team', '2,4', '2,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(26, 4, 'Add Mapping Details', '•&nbsp;', 'project-mapping', '2,4', '1,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(27, 4, 'Add Project Activities', '•&nbsp;', 'add-project-activities', '2,4', '2,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(28, 4, 'Add Financial Plan', '•&nbsp;', 'add-project-financial-plan', '2,4', '2,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(29, 4, 'Add Procurement Details', '•&nbsp;', 'add-project-procurement-details', '2,4', '2,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(30, 4, 'Add Project Quarterly Targets', '•&nbsp;', 'view-workplan', '2,4', '2,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(31, 4, 'Add Project M&E Plan', '•&nbsp;', 'view-mne-plan', '1,4', '2,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(32, 5, 'All Projects', '•&nbsp;', 'projects', '1,2,3,4', '0', '0', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(33, 5, 'My Projects', '•&nbsp;', 'myprojects', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(34, 5, 'Inspection', '•&nbsp;', 'general-inspection', '2,4', '2,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(35, 5, 'Monitoring', '•&nbsp;', 'projects-monitoring', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '1,7', '0', '0', '0', 1),
(36, 6, 'Projects Evaluation', '•&nbsp;', 'view-project-survey', '1,4', '1,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(37, 6, 'Concluded Evaluations', '•&nbsp;', 'project-concluded-evaluations', '1,2,3,4', '0', '0', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(38, 7, 'Payments Request', '•&nbsp;', 'project-milestones-payment', '2,4', '2,4', '2,4', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '1,6', '1,6', '1,6', '0', '0', '0', '0', 1),
(39, 7, 'Payments Approval', '•&nbsp;', 'view-financial-requests', '1,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(40, 7, 'Payment Disbursement', '•&nbsp;', 'view-project-payments', '1,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(41, 8, 'Financial Partners', '•&nbsp;', 'view-financiers', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '1,7', '0', '0', '0', 1),
(42, 9, 'Projects Issues/Risks', '•&nbsp;', 'view-projects-risk-response', '1,4', '1,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(43, 9, 'Escalated Issues', '•&nbsp;', 'projects-escalated-issues', '2,4', '2,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(44, 9, 'Risk Categories', '•&nbsp;', 'view-risk-categories', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(45, 9, 'Risk Mitigations', '•&nbsp;', 'view-risk-mitigation', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(46, 10, 'Indicators', '•&nbsp;', 'view-indicators', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(47, 10, 'Measurement Units', '•&nbsp;', 'view-measurement-units', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(48, 11, 'Manage Contractors', '•&nbsp;', 'view-contractors', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(49, 12, 'View Personnel', '•&nbsp;', 'view-members', '1,2,4', '2,4', '2,4', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(50, 12, 'Add Personnel', '•&nbsp;', 'add-member', '2,4', '2,4', '2,4', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(51, 13, 'View Project Files', '•&nbsp;', 'view-project-files', '1,2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(52, 14, 'C-APR', '•&nbsp;', 'view-objective-performance?plan=<?=$plan?>&orig=2', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(53, 14, 'Quarterly Progress Report', '•&nbsp;', 'output-indicators-quarterly-progress-report.php?plan=<?=$plan?>&orig=2', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(54, 14, 'Projects Performance Report', '•&nbsp;', 'project-indicators-tracking-table', '1,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(55, 14, 'Projects Implementation Report', '•&nbsp;', 'projects-implementation-report', '1,2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 0),
(56, 14, 'Indicators Performance', '•&nbsp;', 'output-indicators-tracking', '1,2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 0),
(57, 14, 'Financial Report', '•&nbsp;', 'projfundingreport', '1,2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 0),
(58, 14, 'Funders Report', '•&nbsp;', 'projects-financiers-report', '1,2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 0),
(59, 15, 'Global Configuration', '<i class=\"fa fa-cogs\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'organization-details', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(60, 15, 'Add/View Sectors', '<i class=\"fa fa-puzzle-piece\" aria-hidden=\"true\" style=\"color:white\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'sectors', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(61, 15, 'Add/View Locations', '<i class=\"fa fa-map-marker\" aria-hidden=\"true\" style=\"color:white\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'locations', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(64, 16, 'Dashboard Projects', '•&nbsp;', 'view-dashboard-projects', '1,2,3,4', '0', '0', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(65, 49, 'View Personnel Info', '•&nbsp;', 'view-member-info', '1,2,4', '2,4', '2,4', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(66, 48, 'Manage Contractors Info', '•&nbsp;', 'view-contractor-info', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(67, 48, 'Add Contractors ', '•&nbsp;', 'add-contractor', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(68, 46, 'Add Indicators', '•&nbsp;', 'add-output-indicator', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(69, 46, 'Edit Indicators', '•&nbsp;', 'edit-output-indicator', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(70, 46, 'Edit Indicators', '•&nbsp;', 'add-outcome-indicator', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(71, 46, 'Edit Indicators', '•&nbsp;', 'edit-outcome-indicator', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(72, 46, 'Indicator Baseline', '•&nbsp;', 'indicator-existing-baseline-data', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(73, 44, 'Risk Categories', '•&nbsp;', 'add-risk-category', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(74, 45, 'Risk Mitigations', '•&nbsp;', 'add-risk-mitigation', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(75, 45, 'Risk Mitigations', '•&nbsp;', 'edit-risk-mitigation', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(76, 36, 'Projects Evaluation', '•&nbsp;', 'evaluation-secondary-data-source', '1,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(77, 41, 'Financial Partners', '•&nbsp;', 'view-funding', '1,2,4', '1,4', 'a1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(78, 41, 'Financial Partners', '•&nbsp;', 'view-financier-info', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(79, 41, 'Financial Partners', '•&nbsp;', 'view-financier-funds', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(80, 41, 'Financial Partners', '•&nbsp;', 'view-financier-status', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(81, 41, 'Financier Projects', '•&nbsp;', 'view-financier-projects', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(82, 41, 'Financier Projects', '•&nbsp;', 'add-financier', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(83, 41, 'Financier Projects', '•&nbsp;', 'edit-financier', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(84, 41, 'Financier Projects', '•&nbsp;', 'add-development-funds', '1,2,4', '1,4', '1,4', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(85, 32, 'All Projects', '•&nbsp;', 'view-project-maps', '1,2,3,4', '0', '0', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(86, 35, 'Monitoring', '•&nbsp;', 'add-monitoring-data', '1,4', '1,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(87, 20, 'Plan', '•&nbsp;', 'add-strategic-plan', '1,2,3,4', '1,4', '1,4', '1,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(88, 20, 'Plan', '•&nbsp;', 'edit-strategic-plan', '1,2,3,4', '1,4', '1,4', '1,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(89, 20, 'Plan', '•&nbsp;', 'view-strategic-plan-framework', '1,2,3,4', '1,4', '1,4', '1,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(90, 20, 'Plan', '•&nbsp;', 'view-strategic-plan-objectives', '1,2,3,4', '1,4', '1,4', '1,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(91, 20, 'Plan', '•&nbsp;', 'view-kra', '1,2,3,4', '1,4', '1,4', '1,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(92, 20, 'Plan', '•&nbsp;', 'view-strategic-workplan-budget', '1,2,3,4', '1,4', '1,4', '1,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(93, 20, 'Plan', '•&nbsp;', 'view-program', '1,2,3,4', '1,4', '1,4', '1,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(94, 20, 'Plan', '•&nbsp;', 'strategic-plan-projects', '1,2,3,4', '1,4', '1,4', '1,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(95, 20, 'Plan', '•&nbsp;', 'view-objective-performance', '1,2,3,4', '1,4', '1,4', '1,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(96, 38, 'Payments Request', '•&nbsp;', 'project-inhouse-payment', '2,4', '2,4', '2,4', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(97, 38, 'Payments Request', '•&nbsp;', 'certificateofcompletion', '1,2,4', '2,4', '2,4', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '1,7', '1,7', '1,7', '0', '0', '0', '0', 1),
(98, 34, 'Inspection', '•&nbsp;', 'project-inspection-report', '2,4', '2,4', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(99, 33, 'My Projects', '•&nbsp;', 'view-project-gallery', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(100, 33, 'My Projects', '•&nbsp;', 'myprojectdash', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(101, 33, 'My Projects', '•&nbsp;', 'myprojectmilestones', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(102, 33, 'My Projects', '•&nbsp;', 'myprojectworkplan', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(103, 33, 'My Projects', '•&nbsp;', 'myprojectfinancialplan', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(104, 33, 'My Projects', '•&nbsp;', 'myproject-key-stakeholders', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(105, 33, 'My Projects', '•&nbsp;', 'projectissueslist', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(106, 33, 'My Projects', '•&nbsp;', 'myprojectfiles', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(107, 33, 'My Projects', '•&nbsp;', 'projreports', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(108, 33, 'My Projects', '•&nbsp;', 'projectissuesanalysis', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1),
(109, 33, 'My Projects', '•&nbsp;', 'project-issue-discussion', '2,4', '0', '0', '0', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', '0', '0', '0', '0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_state`
--

CREATE TABLE `tbl_state` (
  `id` int NOT NULL,
  `parent` int DEFAULT NULL,
  `state` varchar(100) NOT NULL,
  `location` int NOT NULL DEFAULT '0',
  `active` int NOT NULL DEFAULT '1',
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_state`
--

INSERT INTO `tbl_state` (`id`, `parent`, `state`, `location`, `active`, `changed_by`, `date_changed`) VALUES
(1, NULL, 'Headquarters', 0, 1, NULL, NULL),
(2, 1, 'Headquarters', 0, 1, NULL, NULL),
(3, 2, 'Headquarters', 0, 1, NULL, NULL),
(303, NULL, 'Soy', 0, 1, NULL, NULL),
(304, NULL, 'Moiben', 0, 1, NULL, NULL),
(305, NULL, 'Turbo', 0, 1, NULL, NULL),
(306, NULL, 'Ainabkoi', 0, 1, NULL, NULL),
(307, NULL, 'Kapseret', 0, 1, NULL, NULL),
(308, NULL, 'Kesses', 0, 1, NULL, NULL),
(309, 306, 'Ainabkoi/ Olare', 0, 1, NULL, NULL),
(310, 306, 'Kaptagat', 0, 1, NULL, NULL),
(311, 306, 'Kapsoya', 0, 1, NULL, NULL),
(312, 307, 'Simat/ Kapseret', 0, 1, NULL, NULL),
(313, 307, 'Kipkenyo', 0, 1, NULL, NULL),
(314, 307, 'Langas', 0, 1, NULL, NULL),
(315, 307, 'Megun ', 0, 1, NULL, NULL),
(316, 307, 'Ngeria', 0, 1, NULL, NULL),
(317, 308, 'Cheptiret/ Kipchamo', 0, 1, NULL, NULL),
(318, 308, 'Racecourse', 0, 1, NULL, NULL),
(319, 308, 'Tulwet/ Chuiyat', 0, 1, NULL, NULL),
(320, 308, 'Tarakwa', 0, 1, NULL, NULL),
(321, 303, 'Kipsomba', 0, 1, NULL, NULL),
(322, 303, 'Kapkures', 0, 1, NULL, NULL),
(323, 303, 'Kuinet/ Kapsuswa', 0, 1, NULL, NULL),
(324, 303, 'Moi\'s Bridge ', 0, 1, NULL, NULL),
(325, 303, 'Segero/ Barsombe', 0, 1, NULL, NULL),
(326, 303, 'Soy', 0, 1, NULL, NULL),
(327, 303, 'Ziwa', 0, 1, NULL, NULL),
(328, 305, 'Huruma', 0, 1, NULL, NULL),
(329, 305, 'Kamagut', 0, 1, NULL, NULL),
(330, 305, 'Kapsaos', 0, 1, NULL, NULL),
(331, 305, 'Kiplombe', 0, 1, NULL, NULL),
(332, 305, 'Ngenyilel', 0, 1, NULL, NULL),
(333, 305, 'Tapsagoi', 0, 1, NULL, NULL),
(334, 304, 'Karuna/ Meibeki', 0, 1, NULL, NULL),
(335, 304, 'Kimumu', 0, 1, NULL, NULL),
(336, 304, 'Moiben', 0, 1, NULL, NULL),
(337, 304, 'Sergoit', 0, 1, NULL, NULL),
(338, 304, 'Tembelio', 0, 1, NULL, NULL),
(339, 338, 'Kimoning', 1, 1, NULL, NULL),
(340, 338, 'Kamaua', 1, 1, NULL, NULL),
(341, 338, 'Elgeyo Border', 1, 1, NULL, NULL),
(342, 336, 'Torochmoi', 1, 1, NULL, NULL),
(343, 336, 'Merewet', 1, 1, 'admin0', '2022-01-15 14:43:17'),
(344, 336, 'Toloita', 1, 1, NULL, NULL),
(345, 336, 'Kapsubere', 1, 1, NULL, NULL),
(346, 335, 'Kimumu', 1, 1, NULL, NULL),
(347, 335, 'Sigot', 1, 1, NULL, NULL),
(348, 312, 'Kapseret', 1, 1, NULL, NULL),
(349, 312, 'Simat', 1, 1, NULL, NULL),
(350, 309, 'Ainabkoi', 1, 1, NULL, NULL),
(351, 309, 'Olare', 1, 1, NULL, NULL),
(352, 310, 'Tendwo', 1, 1, NULL, NULL),
(353, 310, 'Kileges', 1, 1, NULL, NULL),
(354, 310, 'Kapsundei', 1, 1, NULL, NULL),
(355, 310, 'Chesongor', 1, 1, NULL, NULL),
(356, 311, 'Kapsoya', 1, 1, NULL, NULL),
(357, 322, 'Kapkures', 1, 1, NULL, NULL),
(358, 322, 'Matunda', 1, 1, NULL, NULL),
(360, 327, 'Legebet', 1, 1, NULL, NULL),
(361, 327, 'Sirikwa', 1, 1, NULL, NULL),
(362, 327, 'Kerotet', 1, 1, NULL, NULL),
(363, 325, 'Segero', 1, 1, NULL, NULL),
(364, 325, 'Barsombe', 1, 1, NULL, NULL),
(365, 325, 'Kiborokwa', 1, 1, NULL, NULL),
(366, 323, 'Kuinet', 1, 1, NULL, NULL),
(367, 323, 'Kapsuswa', 1, 1, NULL, NULL),
(368, 317, 'Cheptiret', 1, 1, NULL, NULL),
(369, 317, 'Kipchamo', 1, 1, NULL, NULL),
(370, 332, 'Osorongai', 1, 1, NULL, NULL),
(371, 332, 'Chepsaita', 1, 1, NULL, NULL),
(372, 333, 'Tapsagoi', 1, 1, NULL, NULL),
(373, 333, 'Kaptebee', 1, 1, NULL, NULL),
(374, 333, 'Kapkong', 1, 1, NULL, NULL),
(375, 329, 'Sosiani', 1, 1, NULL, NULL),
(376, 329, 'Leseru', 1, 1, NULL, NULL),
(377, 331, 'Kiplombe', 1, 1, NULL, NULL),
(378, 331, 'Kilimani', 1, 1, NULL, NULL),
(379, 330, 'Kapsaos', 1, 1, NULL, NULL),
(380, 328, 'Huruma', 1, 1, NULL, NULL),
(382, 312, 'Lemook', 1, 1, NULL, NULL),
(383, 312, 'Chepkatet', 1, 1, NULL, NULL),
(384, 313, 'Kipkaren Estate', 1, 1, NULL, NULL),
(385, 313, 'Pioneer', 1, 1, NULL, NULL),
(386, 315, 'Megun', 1, 1, NULL, NULL),
(387, 315, 'Songoliet', 1, 1, NULL, NULL),
(388, 315, 'Ndubeneti', 1, 1, NULL, NULL),
(389, 314, 'Langas', 1, 1, NULL, NULL),
(390, 314, 'Kasarani', 1, 1, NULL, NULL),
(391, 314, 'Yamumbi', 1, 1, NULL, NULL),
(392, 320, 'Chagaia', 1, 1, NULL, NULL),
(393, 320, 'Lenguise', 1, 1, NULL, NULL),
(394, 320, 'Kipkurere', 1, 1, NULL, NULL),
(395, 320, 'Tarakwa', 1, 1, NULL, NULL),
(396, 319, 'Kesses', 1, 1, NULL, NULL),
(397, 319, ' Tulwet', 1, 1, NULL, NULL),
(398, 319, ' Koisagat ', 1, 1, NULL, NULL),
(399, 319, 'Lingwai ', 1, 1, NULL, NULL),
(400, 318, 'Racecourse', 1, 1, NULL, NULL),
(401, 334, 'Karuna', 1, 1, NULL, NULL),
(402, 334, 'Meibeki', 1, 1, NULL, NULL),
(403, 337, 'Chepkoilel', 1, 1, NULL, NULL),
(404, 337, 'Sergoit ', 1, 1, NULL, NULL),
(405, 337, 'Kelji ', 1, 1, NULL, NULL),
(407, 326, 'Soy ', 1, 1, NULL, NULL),
(408, 326, 'Kongasis ', 1, 1, NULL, NULL),
(409, 321, 'Kipsomba', 1, 1, NULL, NULL),
(410, 324, 'Moi\'s Bridge', 1, 1, NULL, NULL),
(411, 316, 'Kabongo', 1, 1, NULL, NULL),
(412, 316, 'Kaplelach', 1, 1, NULL, NULL),
(413, 316, 'Oletepes', 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_status`
--

CREATE TABLE `tbl_status` (
  `statusid` int NOT NULL,
  `statusname` varchar(255) NOT NULL,
  `level` int NOT NULL DEFAULT '1',
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_status`
--

INSERT INTO `tbl_status` (`statusid`, `statusname`, `level`, `active`) VALUES
(1, 'Awaiting-Implementation', 1, 1),
(2, 'Cancelled', 1, 1),
(3, 'Pending', 1, 1),
(4, 'On Track', 1, 1),
(5, 'Completed', 1, 1),
(6, 'On Hold', 1, 1),
(7, 'Unapproved', 2, 0),
(8, 'Awaiting Approval', 2, 1),
(9, 'Overdue', 1, 0),
(10, 'Restored', 2, 1),
(11, 'Behind Schedule', 1, 1),
(13, 'Planned', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_strategicplan`
--

CREATE TABLE `tbl_strategicplan` (
  `id` int NOT NULL,
  `plan` varchar(100) NOT NULL,
  `vision` varchar(255) DEFAULT NULL,
  `mission` varchar(255) NOT NULL,
  `years` int NOT NULL,
  `starting_year` int NOT NULL,
  `current_plan` int NOT NULL DEFAULT '0',
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_strategicplan`
--

INSERT INTO `tbl_strategicplan` (`id`, `plan`, `vision`, `mission`, `years`, `starting_year`, `current_plan`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(1, 'Uasingishu County CIDP 2018-2022', 'A prosperous county in Kenya and beyond', 'To serve and improve people’s livelihood through good leadership, innovative technology and efficient infrastructure.', 5, 2018, 1, '1', '2022-01-15', NULL, NULL),
(2, 'CIDP 2023/2028', 'Test', 'Test', 5, 2024, 1, '1', '2022-03-19', NULL, NULL),
(3, 'Dignissimos ipsa di', 'Expedita eligendi si', 'Voluptas in amet qu', 1, 2040, 0, '1', '2022-04-02', '1', '2022-04-19'),
(4, 'Aut est officia cons', 'Enim reprehenderit m', 'Aut debitis est quo', 1, 2038, 0, '1', '2022-04-19', '1', '2022-04-19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_strategic_objective_targets_threshold`
--

CREATE TABLE `tbl_strategic_objective_targets_threshold` (
  `id` int NOT NULL,
  `objid` int NOT NULL,
  `year` int NOT NULL,
  `threshold` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_strategic_plan_objectives`
--

CREATE TABLE `tbl_strategic_plan_objectives` (
  `id` int NOT NULL,
  `kraid` int NOT NULL,
  `objective` varchar(255) NOT NULL,
  `description` text,
  `outcome` varchar(255) DEFAULT NULL,
  `kpi` int NOT NULL,
  `baseline` decimal(10,0) DEFAULT NULL,
  `target` decimal(10,0) DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_strategic_plan_objectives`
--

INSERT INTO `tbl_strategic_plan_objectives` (`id`, `kraid`, `objective`, `description`, `outcome`, `kpi`, `baseline`, `target`, `created_by`, `date_created`) VALUES
(1, 10, 'To establish a sustainable, secure, compliant and reliable infrastructure in Uasin Gishu County', '<p>To increase the adequacy of the road network and improve accessibility within the County<br />\r\nand especially in Eldoret town Business Hub</p>', NULL, 90, NULL, NULL, 'admin0', '2022-01-15'),
(2, 1, 'To attain food security and improve nutritional status of residents of Uasin Gishu County', '<p>To attain food security and improve nutritional status of residents of Uasin Gishu County</p>', NULL, 12, NULL, NULL, 'admin0', '2022-01-15'),
(3, 2, 'To reduce poverty and increase incomes amongst residents of Uasin Gishu County', '<p>Test</p>', NULL, 12, NULL, NULL, 'admin0', '2022-01-15'),
(4, 3, 'To improve health and well-being of residents of Uasin Gishu County', '<p>test</p>', NULL, 12, NULL, NULL, 'admin0', '2022-01-15'),
(5, 4, 'To improve access to clean and portable water, and attain sustainable environment through protection, restoration, conservation and management of the environment', '<p>test</p>', NULL, 12, NULL, NULL, 'admin0', '2022-01-15'),
(6, 5, 'To provide quality education that is accessible, affordable and responsive to societal needs', '<p>Test</p>', NULL, 12, NULL, NULL, 'admin0', '2022-01-15'),
(7, 1, 'To increase agricultural productivity and production', '<p>To increase agricultural productivity and production</p>', NULL, 12, NULL, NULL, 'Admin0', '2022-02-12'),
(8, 1, 'To increase Agricultural mechanization', '<p>To increase Agricultural mechanization&nbsp;</p>', NULL, 12, NULL, NULL, 'Admin0', '2022-02-12'),
(9, 1, 'To increase agricultural productivity and production', '<p>To increase agricultural productivity and production</p>', NULL, 12, NULL, NULL, 'Admin0', '2022-02-12'),
(14, 11, 'To provide a framework to guide and control physical development & urban development and management', '', NULL, 12, NULL, NULL, 'admin0', '2022-02-15'),
(15, 11, 'To provide a framework for coordinated urban development and management', '<p>provide an integrated spatial framework for sustainable socio-economic development of Uasin Gishu County through research, policy, land use planning and development control</p>', NULL, 12, NULL, NULL, 'admin0', '2022-02-15'),
(16, 11, 'To digitize and updated land records for ease in transacting lands activities', '', NULL, 12, NULL, NULL, 'admin0', '2022-02-15'),
(17, 11, 'To provide quality and accurate land surveys and mapping services to facilitate development', '', NULL, 12, NULL, NULL, 'admin0', '2022-02-15'),
(18, 11, 'To provide affordable housing and adequate office space', '', NULL, 12, NULL, NULL, 'admin0', '2022-02-15'),
(19, 1, 'To improve food security and livelihoods in Uasin Gishu through commercial agriculture for sustainable development', '', NULL, 12, NULL, NULL, 'admin0', '2022-02-15'),
(20, 1, 'Increase fish productivity', '', NULL, 12, NULL, NULL, 'admin0', '2022-02-15'),
(21, 1, 'Increase Animal Productivity ', '', NULL, 12, NULL, NULL, 'admin0', '2022-02-15'),
(22, 1, 'Qui itaque et nesciu', '<p>test</p>', NULL, 12, NULL, NULL, '1', '2022-03-19'),
(23, 12, 'SO1', '<p>Testing</p>', NULL, 12, NULL, NULL, '1', '2022-03-19'),
(24, 13, 'SOb1', '<p>Testing</p>', NULL, 12, NULL, NULL, '1', '2022-03-19'),
(25, 15, 'Tester SOb1', '<p>tttttttttttttttttttttttttt</p>', NULL, 12, NULL, NULL, '1', '2022-03-20'),
(26, 16, 'Strategic Ob5', '<p>Testing</p>', NULL, 12, NULL, NULL, '1', '2022-03-23'),
(27, 19, 'To establish sustainable, secure, compliant, and reliable infrastructure ', '<p>infrastructure&nbsp;</p>', NULL, 12, NULL, NULL, '1', '2022-03-23'),
(29, 17, 'To attain food security and improve nutritional status ', '<p>test</p>', NULL, 12, NULL, NULL, '1', '2022-03-23'),
(30, 14, 'strategic Objective 8', '<p>strategic Objective 8</p>', NULL, 12, NULL, NULL, '1', '2022-03-25'),
(31, 23, 'Ovjective1', '<p>test</p>', NULL, 12, NULL, NULL, '1', '2022-04-02'),
(32, 23, 'Ovjective1', '<p>test</p>', NULL, 12, NULL, NULL, '1', '2022-04-02'),
(33, 24, 'obbbb', '', NULL, 12, NULL, NULL, '1', '2022-04-02'),
(34, 25, 'Youth and women empowerment', '', NULL, 12, NULL, NULL, '1', '2022-04-02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_strategic_plan_objective_targets`
--

CREATE TABLE `tbl_strategic_plan_objective_targets` (
  `id` int NOT NULL,
  `objid` int NOT NULL,
  `year` int NOT NULL,
  `target` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_strategic_plan_op_indicator_budget`
--

CREATE TABLE `tbl_strategic_plan_op_indicator_budget` (
  `id` int NOT NULL,
  `spid` int NOT NULL,
  `indid` int NOT NULL,
  `budget` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_strategic_plan_op_indicator_budget`
--

INSERT INTO `tbl_strategic_plan_op_indicator_budget` (`id`, `spid`, `indid`, `budget`) VALUES
(107, 1, 107, 200000000),
(108, 1, 144, 0),
(109, 1, 1, 0),
(110, 1, 2, 850000000),
(111, 1, 3, 0),
(112, 1, 4, 0),
(113, 1, 12, 800000000),
(114, 1, 147, 0),
(115, 1, 132, 0),
(116, 1, 135, 0),
(117, 1, 17, 0),
(118, 1, 18, 0),
(119, 1, 43, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_strategic_plan_op_indicator_targets`
--

CREATE TABLE `tbl_strategic_plan_op_indicator_targets` (
  `id` int NOT NULL,
  `strategic_plan_id` int NOT NULL,
  `op_indicator_id` int NOT NULL,
  `year` int NOT NULL,
  `year_target` float NOT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_strategic_plan_op_indicator_targets`
--

INSERT INTO `tbl_strategic_plan_op_indicator_targets` (`id`, `strategic_plan_id`, `op_indicator_id`, `year`, `year_target`, `created_by`, `date_created`) VALUES
(621, 1, 107, 2018, 1900, 'Admin0', '2022-03-25 13:22:49'),
(622, 1, 107, 2019, 2000, 'Admin0', '2022-03-25 13:22:49'),
(623, 1, 107, 2020, 1400, 'Admin0', '2022-03-25 13:22:49'),
(624, 1, 107, 2021, 2000, 'Admin0', '2022-03-25 13:22:49'),
(625, 1, 107, 2022, 4000, 'Admin0', '2022-03-25 13:22:49'),
(626, 1, 144, 2018, 0, 'Admin0', '2022-03-25 13:22:49'),
(627, 1, 144, 2019, 0, 'Admin0', '2022-03-25 13:22:49'),
(628, 1, 144, 2020, 0, 'Admin0', '2022-03-25 13:22:49'),
(629, 1, 144, 2021, 0, 'Admin0', '2022-03-25 13:22:49'),
(630, 1, 144, 2022, 0, 'Admin0', '2022-03-25 13:22:49'),
(631, 1, 1, 2018, 0, 'Admin0', '2022-03-25 13:22:49'),
(632, 1, 1, 2019, 0, 'Admin0', '2022-03-25 13:22:49'),
(633, 1, 1, 2020, 0, 'Admin0', '2022-03-25 13:22:49'),
(634, 1, 1, 2021, 0, 'Admin0', '2022-03-25 13:22:49'),
(635, 1, 1, 2022, 0, 'Admin0', '2022-03-25 13:22:49'),
(636, 1, 2, 2018, 100, 'Admin0', '2022-03-25 13:22:49'),
(637, 1, 2, 2019, 150, 'Admin0', '2022-03-25 13:22:49'),
(638, 1, 2, 2020, 200, 'Admin0', '2022-03-25 13:22:49'),
(639, 1, 2, 2021, 1550, 'Admin0', '2022-03-25 13:22:49'),
(640, 1, 2, 2022, 300, 'Admin0', '2022-03-25 13:22:49'),
(641, 1, 3, 2018, 0, 'Admin0', '2022-03-25 13:22:49'),
(642, 1, 3, 2019, 0, 'Admin0', '2022-03-25 13:22:49'),
(643, 1, 3, 2020, 0, 'Admin0', '2022-03-25 13:22:49'),
(644, 1, 3, 2021, 0, 'Admin0', '2022-03-25 13:22:49'),
(645, 1, 3, 2022, 0, 'Admin0', '2022-03-25 13:22:49'),
(646, 1, 4, 2018, 0, 'Admin0', '2022-03-25 13:22:49'),
(647, 1, 4, 2019, 0, 'Admin0', '2022-03-25 13:22:49'),
(648, 1, 4, 2020, 0, 'Admin0', '2022-03-25 13:22:49'),
(649, 1, 4, 2021, 0, 'Admin0', '2022-03-25 13:22:49'),
(650, 1, 4, 2022, 0, 'Admin0', '2022-03-25 13:22:49'),
(651, 1, 12, 2018, 20, 'Admin0', '2022-03-25 13:22:49'),
(652, 1, 12, 2019, 20, 'Admin0', '2022-03-25 13:22:49'),
(653, 1, 12, 2020, 20, 'Admin0', '2022-03-25 13:22:49'),
(654, 1, 12, 2021, 20, 'Admin0', '2022-03-25 13:22:49'),
(655, 1, 12, 2022, 20, 'Admin0', '2022-03-25 13:22:49'),
(656, 1, 147, 2018, 4, 'Admin0', '2022-03-25 13:22:49'),
(657, 1, 147, 2019, 4, 'Admin0', '2022-03-25 13:22:49'),
(658, 1, 147, 2020, 4, 'Admin0', '2022-03-25 13:22:49'),
(659, 1, 147, 2021, 4, 'Admin0', '2022-03-25 13:22:49'),
(660, 1, 147, 2022, 4, 'Admin0', '2022-03-25 13:22:49'),
(661, 1, 132, 2018, 0, 'Admin0', '2022-03-25 13:22:49'),
(662, 1, 132, 2019, 0, 'Admin0', '2022-03-25 13:22:49'),
(663, 1, 132, 2020, 0, 'Admin0', '2022-03-25 13:22:49'),
(664, 1, 132, 2021, 0, 'Admin0', '2022-03-25 13:22:49'),
(665, 1, 132, 2022, 0, 'Admin0', '2022-03-25 13:22:49'),
(666, 1, 135, 2018, 0, 'Admin0', '2022-03-25 13:22:49'),
(667, 1, 135, 2019, 0, 'Admin0', '2022-03-25 13:22:49'),
(668, 1, 135, 2020, 0, 'Admin0', '2022-03-25 13:22:49'),
(669, 1, 135, 2021, 0, 'Admin0', '2022-03-25 13:22:49'),
(670, 1, 135, 2022, 0, 'Admin0', '2022-03-25 13:22:49'),
(671, 1, 17, 2018, 0, 'Admin0', '2022-03-25 13:22:49'),
(672, 1, 17, 2019, 0, 'Admin0', '2022-03-25 13:22:49'),
(673, 1, 17, 2020, 0, 'Admin0', '2022-03-25 13:22:49'),
(674, 1, 17, 2021, 0, 'Admin0', '2022-03-25 13:22:49'),
(675, 1, 17, 2022, 0, 'Admin0', '2022-03-25 13:22:49'),
(676, 1, 18, 2018, 0, 'Admin0', '2022-03-25 13:22:49'),
(677, 1, 18, 2019, 0, 'Admin0', '2022-03-25 13:22:49'),
(678, 1, 18, 2020, 0, 'Admin0', '2022-03-25 13:22:49'),
(679, 1, 18, 2021, 0, 'Admin0', '2022-03-25 13:22:49'),
(680, 1, 18, 2022, 0, 'Admin0', '2022-03-25 13:22:49'),
(681, 1, 43, 2018, 0, 'Admin0', '2022-03-25 13:22:49'),
(682, 1, 43, 2019, 0, 'Admin0', '2022-03-25 13:22:49'),
(683, 1, 43, 2020, 0, 'Admin0', '2022-03-25 13:22:49'),
(684, 1, 43, 2021, 0, 'Admin0', '2022-03-25 13:22:49'),
(685, 1, 43, 2022, 0, 'Admin0', '2022-03-25 13:22:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_survey_conclusion`
--

CREATE TABLE `tbl_survey_conclusion` (
  `id` int NOT NULL,
  `formkey` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `projid` int NOT NULL,
  `indid` int NOT NULL,
  `survey_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `level3` int DEFAULT NULL,
  `disaggregation` int DEFAULT NULL,
  `variable_category` int NOT NULL,
  `numerator` float NOT NULL,
  `denominator` float DEFAULT NULL,
  `comments` text,
  `created_by` int NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_survey_conclusion`
--

INSERT INTO `tbl_survey_conclusion` (`id`, `formkey`, `projid`, `indid`, `survey_type`, `level3`, `disaggregation`, `variable_category`, `numerator`, `denominator`, `comments`, `created_by`, `date_created`) VALUES
(1, '873921', 27, 131, 'Baseline', NULL, NULL, 2, 20, 30, '<p>Testing</p>', 1, '2022-03-20'),
(5, '812942', 35, 138, 'Baseline', NULL, NULL, 1, 3, NULL, '<p>Testing</p>', 1, '2022-03-29'),
(13, '146503', 44, 152, 'Baseline', 352, NULL, 2, 10, 25, '<p>The average level of access is 40%. The water pan needs expansion to increase coverage</p>', 1, '2022-03-30'),
(46, '743580', 39, 145, 'Baseline', 357, 7, 1, 200, NULL, '<p>Testing</p>', 1, '2022-03-31'),
(47, '743580', 39, 145, 'Baseline', 357, 8, 1, 100, NULL, '<p>Testing</p>', 1, '2022-03-31'),
(48, '743580', 39, 145, 'Baseline', 358, 7, 1, 250, NULL, '<p>Testing</p>', 1, '2022-03-31'),
(49, '743580', 39, 145, 'Baseline', 358, 8, 1, 50, NULL, '<p>Testing</p>', 1, '2022-03-31'),
(51, '438027', 22, 153, 'Baseline', 404, 15, 3, 3, NULL, '<p>The evaluation was conducted successfully</p>', 1, '2022-04-01'),
(52, '438027', 22, 153, 'Baseline', 404, 16, 3, 1, NULL, '<p>The evaluation was conducted successfully</p>', 1, '2022-04-01'),
(53, '296147', 45, 149, 'Baseline', 346, 12, 2, 50, 2000, '', 1, '2022-04-04'),
(54, '296147', 45, 149, 'Baseline', 346, 12, 2, 60, 3000, '', 1, '2022-04-04'),
(55, '296147', 45, 149, 'Baseline', 347, 12, 2, 20, 3000, '', 1, '2022-04-04'),
(56, '296147', 45, 149, 'Baseline', 347, 12, 2, 20, 3000, '', 1, '2022-04-04'),
(57, '913728', 49, 145, 'Baseline', 357, 7, 1, 500, NULL, '', 1, '2022-04-04'),
(58, '913728', 49, 145, 'Baseline', 357, 8, 1, 600, NULL, '', 1, '2022-04-04'),
(59, '913728', 49, 145, 'Baseline', 358, 7, 1, 700, NULL, '', 1, '2022-04-04'),
(60, '913728', 49, 145, 'Baseline', 358, 8, 1, 600, NULL, '', 1, '2022-04-04'),
(61, '782593', 55, 164, 'Baseline', 409, 17, 1, 1, NULL, '<p>The question was administered to five respondents. one woman was......................................................................................................</p>', 1, '2022-04-04'),
(62, '782593', 55, 164, 'Baseline', 409, 18, 1, 0, NULL, '<p>The question was administered to five respondents. one woman was......................................................................................................</p>', 1, '2022-04-04'),
(63, '782593', 55, 164, 'Baseline', 409, 19, 1, 1, NULL, '<p>The question was administered to five respondents. one woman was......................................................................................................</p>', 1, '2022-04-04'),
(64, '782593', 55, 164, 'Baseline', 409, 20, 1, 0, NULL, '<p>The question was administered to five respondents. one woman was......................................................................................................</p>', 1, '2022-04-04'),
(65, '782593', 55, 164, 'Baseline', 409, 21, 1, 1, NULL, '<p>The question was administered to five respondents. one woman was......................................................................................................</p>', 1, '2022-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_system_modules`
--

CREATE TABLE `tbl_system_modules` (
  `id` int NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_system_status`
--

CREATE TABLE `tbl_system_status` (
  `id` int NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_system_status`
--

INSERT INTO `tbl_system_status` (`id`, `status`) VALUES
(0, 'Disable'),
(1, 'Enable');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_task`
--

CREATE TABLE `tbl_task` (
  `tkid` int NOT NULL,
  `msid` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `parenttask` int DEFAULT NULL,
  `task` varchar(300) NOT NULL,
  `taskbudget` double NOT NULL DEFAULT '0',
  `progress` decimal(10,2) NOT NULL DEFAULT '0.00',
  `inspectionscore` float NOT NULL DEFAULT '0',
  `inspectionstatus` int NOT NULL DEFAULT '0',
  `description` text,
  `status` int NOT NULL,
  `paymentstatus` int NOT NULL DEFAULT '0',
  `changedstatus` varchar(100) DEFAULT NULL,
  `monitored` enum('0','1') NOT NULL DEFAULT '0',
  `sdate` date DEFAULT NULL,
  `edate` date DEFAULT NULL,
  `responsible` int NOT NULL,
  `datecompleted` date DEFAULT NULL,
  `user_name` varchar(200) NOT NULL,
  `date_entered` date NOT NULL,
  `changedby` varchar(100) DEFAULT NULL,
  `datechanged` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_task`
--

INSERT INTO `tbl_task` (`tkid`, `msid`, `projid`, `outputid`, `parenttask`, `task`, `taskbudget`, `progress`, `inspectionscore`, `inspectionstatus`, `description`, `status`, `paymentstatus`, `changedstatus`, `monitored`, `sdate`, `edate`, `responsible`, `datecompleted`, `user_name`, `date_entered`, `changedby`, `datechanged`) VALUES
(1, 7, 9, 70, NULL, 'Plant Facilities ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-01-07', '2021-01-14', 2, NULL, '1', '2022-01-18', NULL, NULL),
(2, 1, 8, 63, NULL, 'Excavations and Earthworks ', 0, '50.00', 0, 0, NULL, 4, 0, '4', '1', '2021-07-06', '2022-10-13', 15, NULL, '1', '2022-01-18', '1', '2022-03-24 10:19:32'),
(4, 1, 8, 63, 2, 'Filling', 0, '50.00', 0, 0, NULL, 4, 0, '4', '1', '2021-07-16', '2022-10-19', 16, NULL, '1', '2022-01-18', '1', '2022-03-24 10:19:32'),
(5, 7, 9, 70, NULL, 'Resident Engineer\'s office ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-01-07', '2021-01-14', 2, NULL, '1', '2022-01-18', NULL, NULL),
(6, 2, 8, 63, NULL, 'Masonry walls bedded and jointed in cement and sand', 0, '50.00', 0, 0, NULL, 4, 0, '4', '1', '2021-08-05', '2022-11-29', 15, NULL, '1', '2022-01-18', '1', '2022-03-24 10:19:32'),
(8, 2, 8, 63, 7, 'Ring Beam', 0, '50.00', 0, 0, NULL, 4, 0, '4', '1', '2021-09-01', '2022-12-14', 16, NULL, '1', '2022-01-18', '1', '2022-03-24 10:19:32'),
(9, 2, 8, 63, 8, 'Reinforcements/Sawn Formwork for Lintels', 0, '50.00', 0, 0, NULL, 4, 0, '4', '1', '2021-09-18', '2023-01-13', 16, NULL, '1', '2022-01-18', '1', '2022-03-24 10:19:32'),
(10, 3, 8, 63, NULL, 'Timber trusses hoisting and fixing in position', 0, '30.00', 0, 0, NULL, 4, 0, '4', '1', '2022-11-01', '2024-09-12', 16, NULL, '1', '2022-01-18', '1', '2022-03-24 10:19:32'),
(13, 3, 8, 63, NULL, 'Fixing of iron sheets', 0, '50.00', 0, 0, NULL, 4, 0, '4', '1', '2021-11-16', '2023-02-28', 16, NULL, '1', '2022-01-18', '1', '2022-03-24 10:19:32'),
(14, 15, 7, 61, NULL, 'Excavations and Earthworks ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2022-02-01', '2022-02-14', 16, NULL, '1', '2022-01-19', NULL, NULL),
(15, 16, 7, 61, NULL, 'Masonry walls bedded and jointed in cement and sand', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2022-03-01', '2022-04-27', 16, NULL, '1', '2022-01-19', NULL, NULL),
(16, 17, 7, 61, NULL, 'Timber trusses hoisting and fixing in position', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2022-03-10', '2022-03-13', 13, NULL, '1', '2022-01-19', NULL, NULL),
(17, 17, 7, 61, 16, 'Fixing of iron sheets', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2022-02-20', '2022-03-04', 13, NULL, '1', '2022-01-19', NULL, NULL),
(18, 18, 5, 59, NULL, 'T1', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-08', '2021-07-10', 6, NULL, '1', '2022-01-19', NULL, NULL),
(19, 23, 21, 95, NULL, 'Plant Facilities', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-01', '2021-07-07', 11, NULL, '1', '2022-02-02', NULL, NULL),
(20, 23, 21, 95, NULL, 'Plant Facilities', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-01', '2021-07-07', 11, NULL, '1', '2022-02-02', NULL, NULL),
(21, 23, 21, 95, NULL, 'Contractor\'s Site ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-01', '2021-07-06', 13, NULL, '1', '2022-02-02', NULL, NULL),
(22, 23, 21, 95, NULL, 'Resident Engineer\'s office', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-05', '2021-07-10', 13, NULL, '1', '2022-02-02', NULL, NULL),
(23, 23, 21, 95, NULL, 'Purchase of Equipment ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-08', '2021-07-14', 11, NULL, '1', '2022-02-02', NULL, NULL),
(24, 24, 21, 95, NULL, 'Survey Works and Setting Out ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-15', '2021-07-21', 13, NULL, '1', '2022-02-02', NULL, NULL),
(25, 24, 21, 95, NULL, 'Site Clearance ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-15', '2021-07-22', 13, NULL, '1', '2022-02-02', NULL, NULL),
(26, 24, 21, 95, NULL, 'Construction of temporary access road', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-15', '2021-07-22', 13, NULL, '1', '2022-02-02', NULL, NULL),
(27, 25, 21, 95, NULL, 'Excavation', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-08-01', '2021-08-07', 13, NULL, '1', '2022-02-02', NULL, NULL),
(28, 25, 21, 95, 27, 'Embankment fill', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-08-09', '2021-08-13', 11, NULL, '1', '2022-02-02', NULL, NULL),
(29, 26, 21, 95, NULL, 'Cross drainage ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-09-01', '2021-09-09', 11, NULL, '1', '2022-02-02', NULL, NULL),
(30, 26, 21, 95, 29, 'Slope Protection', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-09-10', '2021-10-15', 11, NULL, '1', '2022-02-02', NULL, NULL),
(31, 25, 21, 95, NULL, 'Top Soil Removal, Collection and Dumping ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-08-01', '2021-08-10', 11, NULL, '1', '2022-02-02', NULL, NULL),
(32, 26, 21, 95, NULL, 'Construction of box culverts ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-09-01', '2021-10-10', 13, NULL, '1', '2022-02-02', NULL, NULL),
(33, 26, 21, 95, NULL, 'Installation of Ring Culverts', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-09-09', '2021-10-15', 19, NULL, '1', '2022-02-02', NULL, NULL),
(34, 26, 21, 95, NULL, 'Installation of Ring Culverts', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-09-09', '2021-10-15', 19, NULL, '1', '2022-02-02', NULL, NULL),
(35, 28, 21, 95, NULL, 'Construction of improved Sub-grade ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-10-16', '2021-10-30', 11, NULL, '1', '2022-02-02', NULL, NULL),
(36, 28, 21, 95, NULL, 'Sub-base', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-10-21', '2021-10-30', 11, NULL, '1', '2022-02-02', NULL, NULL),
(37, 28, 21, 95, NULL, 'Base Course', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-10-30', '2021-11-05', 13, NULL, '1', '2022-02-02', NULL, NULL),
(38, 28, 21, 95, 37, 'Surfacing ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-11-06', '2021-11-11', 11, NULL, '1', '2022-02-02', NULL, NULL),
(39, 28, 21, 95, NULL, 'Road Furniture ', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-10-16', '2021-11-14', 11, NULL, '1', '2022-02-02', NULL, NULL),
(40, 27, 21, 95, NULL, 'Demobilization', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-11-11', '2021-11-15', 13, NULL, '1', '2022-02-02', NULL, NULL),
(41, 27, 21, 95, 40, 'Final Inspection and Hand over', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-11-16', '2021-11-17', 19, NULL, '1', '2022-02-02', NULL, NULL),
(42, 29, 22, 96, NULL, 'Site Clearance and mobililization of facilities ', 0, '0.00', 0, 0, NULL, 11, 0, NULL, '0', '2021-07-01', '2021-07-15', 9, NULL, '1', '2022-02-02', NULL, NULL),
(43, 30, 22, 96, NULL, 'Bridgeworks ', 0, '0.00', 0, 0, NULL, 11, 0, NULL, '0', '2021-07-15', '2021-10-25', 9, NULL, '1', '2022-02-02', NULL, NULL),
(44, 31, 22, 96, NULL, 'Final Inspection and Hand over', 0, '0.00', 0, 0, NULL, 11, 0, NULL, '0', '2021-11-10', '2021-11-25', 10, NULL, '1', '2022-02-02', NULL, NULL),
(45, 32, 24, 100, NULL, 'TSK1', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-08-08', '2022-01-01', 6, NULL, '1', '2022-02-13', NULL, NULL),
(46, 38, 27, 105, NULL, 'TASK 1', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-08', '2021-08-07', 2, NULL, '1', '2022-02-25', NULL, NULL),
(47, 39, 27, 105, NULL, 'Task11', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-08-26', '2021-09-25', 14, NULL, '1', '2022-02-25', NULL, NULL),
(48, 40, 27, 105, NULL, 'TASK 1', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-11-25', '2021-12-03', 14, NULL, '1', '2022-02-25', NULL, NULL),
(49, 41, 35, 131, NULL, 'Task 1', 0, '0.00', 0, 0, NULL, 11, 0, NULL, '0', '2021-08-04', '2021-08-20', 25, NULL, '1', '2022-03-20', NULL, NULL),
(50, 42, 38, 134, NULL, 'Plant facilities', 0, '100.00', 0, 0, NULL, 5, 0, NULL, '1', '2021-07-02', '2021-07-05', 8, NULL, '1', '2022-03-23', NULL, NULL),
(51, 42, 38, 134, NULL, 'Resident engineer,s office done', 0, '100.00', 0, 0, NULL, 5, 0, NULL, '1', '2021-07-05', '2021-07-10', 9, NULL, '1', '2022-03-23', NULL, NULL),
(52, 43, 38, 134, NULL, 'survey works and setting out', 0, '100.00', 0, 0, NULL, 5, 0, NULL, '1', '2021-07-15', '2021-07-28', 11, NULL, '1', '2022-03-23', NULL, NULL),
(53, 44, 38, 134, NULL, 'construction of box culverts', 0, '6.25', 0, 0, NULL, 11, 0, '0', '1', '2021-08-05', '2021-08-15', 10, NULL, '1', '2022-03-23', NULL, NULL),
(54, 45, 39, 135, NULL, 'T1', 0, '0.00', 0, 0, NULL, 11, 0, NULL, '0', '2021-07-02', '2021-07-15', 34, NULL, '1', '2022-03-25', NULL, NULL),
(55, 47, 39, 135, NULL, 'T2', 0, '0.00', 0, 0, NULL, 11, 0, NULL, '0', '2021-08-05', '2021-09-09', 35, NULL, '1', '2022-03-25', NULL, NULL),
(56, 48, 39, 135, NULL, 'T3', 0, '0.00', 0, 0, NULL, 11, 0, NULL, '0', '2021-11-03', '2021-11-20', 35, NULL, '1', '2022-03-25', NULL, NULL),
(57, 49, 40, 136, NULL, 'task1', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-31', '2021-09-09', 19, NULL, '1', '2022-03-29', NULL, NULL),
(58, 50, 40, 136, NULL, 'tasktask', 0, '0.00', 0, 0, NULL, 1, 0, NULL, '0', '2021-07-31', '2021-09-09', 17, NULL, '1', '2022-03-29', NULL, NULL),
(59, 52, 44, 143, NULL, 'construction works', 0, '100.00', 0, 0, NULL, 5, 0, NULL, '1', '2021-08-10', '2022-04-07', 15, NULL, '1', '2022-03-30', NULL, NULL),
(60, 53, 44, 143, NULL, 'equipping', 0, '0.00', 0, 0, NULL, 3, 0, NULL, '0', '2022-04-07', '2022-06-16', 15, NULL, '1', '2022-03-30', NULL, NULL),
(61, 54, 45, 151, NULL, 'Supply', 0, '100.00', 0, 0, NULL, 5, 0, NULL, '1', '2021-07-01', '2021-07-10', 7, NULL, '1', '2022-03-30', NULL, NULL),
(62, 55, 45, 151, NULL, 'Transportation of condoms to various identified dispensing points', 0, '25.00', 0, 0, NULL, 11, 0, NULL, '1', '2021-07-11', '2021-08-29', 19, NULL, '1', '2022-03-30', NULL, NULL),
(63, 56, 49, 164, NULL, 'Excavations and Earthworks ', 0, '23.33', 0, 0, NULL, 11, 0, NULL, '1', '2021-07-01', '2021-07-15', 29, NULL, '1', '2022-04-01', NULL, NULL),
(64, 57, 54, 169, NULL, 'delivery of dozzer', 0, '100.00', 0, 0, NULL, 5, 0, NULL, '1', '2022-03-24', '2022-03-31', 6, NULL, '1', '2022-04-03', NULL, NULL),
(65, 57, 54, 169, NULL, 'Inspection', 0, '100.00', 0, 0, NULL, 5, 0, NULL, '1', '2022-04-01', '2022-04-04', 6, NULL, '1', '2022-04-03', NULL, NULL),
(66, 58, 55, 170, NULL, 'Train farmers on poultry management', 0, '25.00', 0, 0, NULL, 11, 0, '4', '1', '2021-07-02', '2021-08-11', 13, NULL, '1', '2022-04-04', '1', '2022-04-04 17:27:05'),
(67, 59, 55, 170, NULL, 'Hiring of transport', 0, '90.00', 0, 0, NULL, 11, 0, '4', '1', '2021-08-02', '2021-08-26', 15, NULL, '1', '2022-04-04', '1', '2022-04-04 17:27:05'),
(68, 59, 55, 170, 67, 'Actual Distribution', 0, '0.00', 0, 0, NULL, 11, 0, '11', '1', '2021-08-17', '2021-10-08', 18, NULL, '1', '2022-04-04', '1', '2022-04-04 17:27:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_taskstatus`
--

CREATE TABLE `tbl_taskstatus` (
  `tsid` int NOT NULL,
  `taskstatus` varchar(300) NOT NULL,
  `taskstatusname` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_taskstatus`
--

INSERT INTO `tbl_taskstatus` (`tsid`, `taskstatus`, `taskstatusname`) VALUES
(1, 'Task On Track', 'On Track'),
(2, 'Pending Task', 'Pending'),
(3, 'Completed Task', 'Completed'),
(4, 'Overdue Task', 'Overdue'),
(5, 'Task On Hold', 'On Hold'),
(6, 'Task Cancelled', 'Cancelled'),
(7, 'Task Behind Schedule', 'Behind Schedule');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_task_inspection_status`
--

CREATE TABLE `tbl_task_inspection_status` (
  `id` int NOT NULL,
  `taskid` int NOT NULL,
  `level3` int NOT NULL,
  `level4` int NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_task_progress`
--

CREATE TABLE `tbl_task_progress` (
  `id` int NOT NULL,
  `monitoringid` int NOT NULL,
  `formid` varchar(100) DEFAULT NULL,
  `opid` int NOT NULL,
  `opdetailsid` int NOT NULL,
  `tkid` int NOT NULL,
  `progress` decimal(10,2) NOT NULL,
  `level3` int NOT NULL,
  `level4` int DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_task_progress`
--

INSERT INTO `tbl_task_progress` (`id`, `monitoringid`, `formid`, `opid`, `opdetailsid`, `tkid`, `progress`, `level3`, `level4`, `date`) VALUES
(1, 1, 'GWBZI', 63, 63, 2, '30.00', 361, NULL, '2022-01-21'),
(2, 1, 'GWBZI', 63, 63, 4, '25.00', 361, NULL, '2022-01-21'),
(3, 1, 'GWBZI', 63, 63, 6, '15.00', 361, NULL, '2022-01-21'),
(4, 1, 'GWBZI', 63, 63, 8, '10.00', 361, NULL, '2022-01-21'),
(5, 1, 'GWBZI', 63, 63, 9, '10.00', 361, NULL, '2022-01-21'),
(6, 1, 'GWBZI', 63, 63, 10, '5.00', 361, NULL, '2022-01-21'),
(7, 1, 'GWBZI', 63, 63, 13, '10.00', 361, NULL, '2022-01-21'),
(8, 2, 'GWDTL', 63, 63, 2, '30.00', 361, NULL, '2022-01-21'),
(9, 2, 'GWDTL', 63, 63, 4, '25.00', 361, NULL, '2022-01-21'),
(10, 2, 'GWDTL', 63, 63, 6, '15.00', 361, NULL, '2022-01-21'),
(11, 2, 'GWDTL', 63, 63, 8, '10.00', 361, NULL, '2022-01-21'),
(12, 2, 'GWDTL', 63, 63, 9, '10.00', 361, NULL, '2022-01-21'),
(13, 2, 'GWDTL', 63, 63, 10, '5.00', 361, NULL, '2022-01-21'),
(14, 2, 'GWDTL', 63, 63, 13, '10.00', 361, NULL, '2022-01-21'),
(15, 3, 'GYRFK', 63, 63, 2, '30.00', 361, NULL, '2022-01-22'),
(16, 3, 'GYRFK', 63, 63, 4, '25.00', 361, NULL, '2022-01-22'),
(17, 3, 'GYRFK', 63, 63, 6, '15.00', 361, NULL, '2022-01-22'),
(18, 3, 'GYRFK', 63, 63, 8, '10.00', 361, NULL, '2022-01-22'),
(19, 3, 'GYRFK', 63, 63, 9, '10.00', 361, NULL, '2022-01-22'),
(20, 3, 'GYRFK', 63, 63, 10, '5.00', 361, NULL, '2022-01-22'),
(21, 3, 'GYRFK', 63, 63, 13, '10.00', 361, NULL, '2022-01-22'),
(22, 4, 'MWMSY', 63, 63, 2, '30.00', 361, NULL, '2022-02-22'),
(23, 4, 'MWMSY', 63, 63, 4, '35.00', 361, NULL, '2022-02-22'),
(24, 4, 'MWMSY', 63, 63, 6, '30.00', 361, NULL, '2022-02-22'),
(25, 4, 'MWMSY', 63, 63, 8, '20.00', 361, NULL, '2022-02-22'),
(26, 4, 'MWMSY', 63, 63, 9, '10.00', 361, NULL, '2022-02-22'),
(27, 4, 'MWMSY', 63, 63, 10, '30.00', 361, NULL, '2022-02-22'),
(28, 4, 'MWMSY', 63, 63, 13, '20.00', 361, NULL, '2022-02-22'),
(29, 5, 'NOOQY', 63, 63, 2, '30.00', 361, NULL, '2022-02-26'),
(30, 5, 'NOOQY', 63, 63, 4, '35.00', 361, NULL, '2022-02-26'),
(31, 5, 'NOOQY', 63, 63, 6, '30.00', 361, NULL, '2022-02-26'),
(32, 5, 'NOOQY', 63, 63, 8, '20.00', 361, NULL, '2022-02-26'),
(33, 5, 'NOOQY', 63, 63, 9, '20.00', 361, NULL, '2022-02-26'),
(34, 5, 'NOOQY', 63, 63, 13, '20.00', 361, NULL, '2022-02-26'),
(35, 6, 'NOSOD', 63, 63, 2, '30.00', 361, NULL, '2022-02-26'),
(36, 6, 'NOSOD', 63, 63, 4, '35.00', 361, NULL, '2022-02-26'),
(37, 6, 'NOSOD', 63, 63, 6, '30.00', 361, NULL, '2022-02-26'),
(38, 6, 'NOSOD', 63, 63, 8, '20.00', 361, NULL, '2022-02-26'),
(39, 6, 'NOSOD', 63, 63, 9, '20.00', 361, NULL, '2022-02-26'),
(40, 6, 'NOSOD', 63, 63, 13, '20.00', 361, NULL, '2022-02-26'),
(41, 7, 'SJLLP', 63, 63, 2, '30.00', 361, NULL, '2022-03-23'),
(42, 7, 'SJLLP', 63, 63, 4, '35.00', 361, NULL, '2022-03-23'),
(43, 7, 'SJLLP', 63, 63, 6, '30.00', 361, NULL, '2022-03-23'),
(44, 7, 'SJLLP', 63, 63, 8, '20.00', 361, NULL, '2022-03-23'),
(45, 7, 'SJLLP', 63, 63, 9, '20.00', 361, NULL, '2022-03-23'),
(46, 7, 'SJLLP', 63, 63, 13, '20.00', 361, NULL, '2022-03-23'),
(47, 8, 'SJLYY', 63, 63, 2, '50.00', 361, NULL, '2022-03-23'),
(48, 8, 'SJLYY', 63, 63, 4, '50.00', 361, NULL, '2022-03-23'),
(49, 8, 'SJLYY', 63, 63, 6, '50.00', 361, NULL, '2022-03-23'),
(50, 8, 'SJLYY', 63, 63, 8, '50.00', 361, NULL, '2022-03-23'),
(51, 8, 'SJLYY', 63, 63, 9, '40.00', 361, NULL, '2022-03-23'),
(52, 8, 'SJLYY', 63, 63, 13, '40.00', 361, NULL, '2022-03-23'),
(53, 9, 'SJNPI', 63, 63, 2, '50.00', 361, NULL, '2022-03-23'),
(54, 9, 'SJNPI', 63, 63, 4, '50.00', 361, NULL, '2022-03-23'),
(55, 9, 'SJNPI', 63, 63, 6, '50.00', 361, NULL, '2022-03-23'),
(56, 9, 'SJNPI', 63, 63, 8, '50.00', 361, NULL, '2022-03-23'),
(57, 9, 'SJNPI', 63, 63, 9, '50.00', 361, NULL, '2022-03-23'),
(58, 9, 'SJNPI', 63, 63, 13, '50.00', 361, NULL, '2022-03-23'),
(59, 10, 'SJNPI', 63, 63, 2, '50.00', 361, NULL, '2022-03-23'),
(60, 10, 'SJNPI', 63, 63, 4, '50.00', 361, NULL, '2022-03-23'),
(61, 10, 'SJNPI', 63, 63, 6, '50.00', 361, NULL, '2022-03-23'),
(62, 10, 'SJNPI', 63, 63, 8, '50.00', 361, NULL, '2022-03-23'),
(63, 10, 'SJNPI', 63, 63, 9, '50.00', 361, NULL, '2022-03-23'),
(64, 10, 'SJNPI', 63, 63, 13, '50.00', 361, NULL, '2022-03-23'),
(65, 11, 'SJOWI', 63, 63, 2, '50.00', 361, NULL, '2022-03-23'),
(66, 11, 'SJOWI', 63, 63, 4, '50.00', 361, NULL, '2022-03-23'),
(67, 11, 'SJOWI', 63, 63, 6, '50.00', 361, NULL, '2022-03-23'),
(68, 11, 'SJOWI', 63, 63, 8, '50.00', 361, NULL, '2022-03-23'),
(69, 11, 'SJOWI', 63, 63, 9, '50.00', 361, NULL, '2022-03-23'),
(70, 11, 'SJOWI', 63, 63, 13, '50.00', 361, NULL, '2022-03-23'),
(71, 12, 'SJOWI', 63, 63, 2, '50.00', 361, NULL, '2022-03-23'),
(72, 12, 'SJOWI', 63, 63, 4, '50.00', 361, NULL, '2022-03-23'),
(73, 12, 'SJOWI', 63, 63, 6, '50.00', 361, NULL, '2022-03-23'),
(74, 12, 'SJOWI', 63, 63, 8, '50.00', 361, NULL, '2022-03-23'),
(75, 12, 'SJOWI', 63, 63, 9, '50.00', 361, NULL, '2022-03-23'),
(76, 12, 'SJOWI', 63, 63, 13, '50.00', 361, NULL, '2022-03-23'),
(77, 13, 'SPFET', 134, 134, 50, '50.00', 389, NULL, '2022-03-24'),
(78, 13, 'SPFET', 134, 134, 51, '8.00', 389, NULL, '2022-03-24'),
(79, 13, 'SPFET', 134, 134, 52, '100.00', 389, NULL, '2022-03-24'),
(80, 13, 'SPFET', 134, 134, 53, '0.00', 389, NULL, '2022-03-24'),
(81, 14, 'SPITP', 134, 134, 50, '100.00', 389, NULL, '2022-03-24'),
(82, 14, 'SPITP', 134, 134, 51, '100.00', 389, NULL, '2022-03-24'),
(83, 14, 'SPITP', 134, 134, 53, '12.50', 389, NULL, '2022-03-24'),
(84, 15, 'SPITP', 134, 134, 50, '100.00', 389, NULL, '2022-03-24'),
(85, 15, 'SPITP', 134, 134, 51, '100.00', 389, NULL, '2022-03-24'),
(86, 15, 'SPITP', 134, 134, 53, '12.50', 389, NULL, '2022-03-24'),
(87, 16, 'TZCWK', 143, 143, 59, '0.00', 352, NULL, '2022-03-31'),
(88, 17, 'TZFDM', 143, 143, 59, '48.00', 352, NULL, '2022-03-31'),
(89, 18, 'TZGBW', 143, 143, 59, '100.00', 352, NULL, '2022-03-31'),
(90, 19, 'UQSVJ', 169, 169, 64, '100.00', 3, NULL, '2022-04-04'),
(91, 19, 'UQSVJ', 169, 169, 65, '100.00', 3, NULL, '2022-04-04'),
(92, 20, 'URNLG', 170, 170, 66, '25.00', 409, NULL, '2022-04-04'),
(93, 20, 'URNLG', 170, 170, 67, '90.00', 409, NULL, '2022-04-04'),
(94, 20, 'URNLG', 170, 170, 68, '0.00', 409, NULL, '2022-04-04'),
(95, 21, 'UROXO', 151, 151, 61, '100.00', 346, NULL, '2022-04-04'),
(96, 21, 'UROXO', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(97, 22, 'URWJF', 164, 164, 63, '46.67', 357, NULL, '2022-04-04'),
(98, 23, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(99, 24, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(100, 25, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(101, 26, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(102, 27, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(103, 28, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(104, 29, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(105, 30, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(106, 31, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(107, 32, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(108, 33, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(109, 34, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(110, 35, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(111, 36, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(112, 37, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(113, 38, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(114, 39, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(115, 40, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(116, 41, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(117, 42, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(118, 43, 'USWDA', 151, 151, 62, '50.00', 346, NULL, '2022-04-04'),
(119, 44, 'USYVB', 151, 151, 62, '50.00', 346, NULL, '2022-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_task_status`
--

CREATE TABLE `tbl_task_status` (
  `statusid` int NOT NULL,
  `statusname` varchar(255) NOT NULL,
  `level` int NOT NULL DEFAULT '1',
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_task_status`
--

INSERT INTO `tbl_task_status` (`statusid`, `statusname`, `level`, `active`) VALUES
(1, 'Approved Task', 1, 1),
(2, 'Cancelled Task', 1, 1),
(3, 'Pending Task', 1, 1),
(4, 'Task On Track', 1, 1),
(5, 'Completed Task', 1, 1),
(6, 'On Hold Task', 1, 1),
(7, 'Unapproved', 2, 1),
(8, 'Awaiting Approval', 2, 1),
(9, 'Overdue Task', 1, 0),
(10, 'Restored', 2, 1),
(11, 'Task Behind Schedule', 1, 1),
(13, 'Planned', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tenderdetails`
--

CREATE TABLE `tbl_tenderdetails` (
  `td_id` int NOT NULL,
  `projid` int DEFAULT NULL,
  `contractrefno` varchar(100) NOT NULL,
  `tenderno` varchar(100) NOT NULL,
  `tendertitle` text NOT NULL,
  `tendertype` int NOT NULL,
  `tendercat` int NOT NULL,
  `tenderamount` double DEFAULT NULL,
  `procurementmethod` int NOT NULL,
  `evaluationdate` date NOT NULL,
  `awarddate` date NOT NULL,
  `notificationdate` date NOT NULL,
  `signaturedate` date NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `financialscore` varchar(10) NOT NULL,
  `technicalscore` varchar(10) NOT NULL,
  `contractor` int DEFAULT NULL,
  `comments` text,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_tenderdetails`
--

INSERT INTO `tbl_tenderdetails` (`td_id`, `projid`, `contractrefno`, `tenderno`, `tendertitle`, `tendertype`, `tendercat`, `tenderamount`, `procurementmethod`, `evaluationdate`, `awarddate`, `notificationdate`, `signaturedate`, `startdate`, `enddate`, `financialscore`, `technicalscore`, `contractor`, `comments`, `created_by`, `date_created`, `changed_by`, `date_changed`) VALUES
(1, 8, '678900', '09000', 'Tender for construction of store-Mafuta Cooperative Society', 1, 1, 6800000, 1, '2021-06-21', '2021-06-27', '2021-06-30', '2021-06-30', '2021-07-01', '2022-04-01', '10', '80', 1, '', 'Admin0', '2022-01-18', NULL, NULL),
(2, 24, 'TRN9800', 'TNDR9006900', 'Procurement', 1, 1, 2499000, 1, '2021-06-15', '2021-06-25', '2021-06-28', '2021-06-29', '2021-07-01', '2022-03-30', '12', '78', 2, '', 'Admin0', '2022-02-13', NULL, NULL),
(3, 7, '123647', '8337299', 'Tender Test', 1, 1, 14080000, 1, '2021-12-01', '2021-12-15', '2022-01-03', '2022-01-06', '2022-01-10', '2022-06-30', '95', '90', 3, 'Test', 'admin0', '2022-02-18', NULL, NULL),
(5, 27, 'T0KYN003', '099', 'Tarmacking of Moiben Torochmoi Roa ', 1, 2, 3710000, 1, '2021-04-14', '2021-05-14', '2021-06-01', '2021-06-04', '2021-07-01', '2021-10-28', '26', '68', 4, 'The tender was done above board', 'admin0', '2022-02-25', NULL, NULL),
(6, 22, 'rrrrrrrrrrrrrrrrrrr', '56788', 'test', 1, 1, NULL, 1, '2021-06-30', '2021-07-30', '2021-08-20', '2021-09-20', '2021-10-20', '2021-10-23', '10', '75', 2, 'ggggggggggggg', 'Admin0', '2022-03-20', NULL, NULL),
(7, 38, '456789', '45678', 'Construction [of kisumu ndogo', 1, 1, 5150000, 1, '2021-07-01', '2021-07-10', '2021-08-01', '2021-08-31', '2021-08-02', '2022-07-29', '10', '75', 3, 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'Admin0', '2022-03-24', NULL, NULL),
(8, 39, '5555555555', '7865', 'Procurement for construction of ECDE classrooms', 1, 1, 9000000, 1, '2021-06-01', '2021-06-09', '2021-06-20', '2021-07-01', '2021-07-02', '2022-07-01', '15', '75', 3, 'testing', 'Admin0', '2022-03-26', NULL, NULL),
(9, 44, '63636', 'OO', 'KAPTELDET DAM', 1, 2, 20020000, 3, '2021-05-28', '2021-07-30', '2021-07-28', '2021-07-29', '2021-08-10', '2022-06-20', '20', '70', 5, '', 'admin0', '2022-03-30', NULL, NULL),
(10, 49, '77777777777', '88888888888888888', 'Tender for construction of classrooms', 1, 1, 42000000, 1, '2021-06-18', '2021-06-19', '2021-06-25', '2021-06-26', '2021-07-01', '2023-07-01', '15', '75', 4, '', 'Admin0', '2022-04-01', NULL, NULL),
(11, 54, '83ho', 'uwuwu', 'Supply of Dozzers', 2, 1, 49200000, 1, '2021-12-09', '2022-01-05', '2022-01-20', '2022-02-15', '2022-02-20', '2022-04-01', '10', '70', 5, '', 'admin0', '2022-04-03', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tenderdocuments`
--

CREATE TABLE `tbl_tenderdocuments` (
  `id` int NOT NULL,
  `td_id` int NOT NULL,
  `projid` int NOT NULL,
  `fname` varchar(255) NOT NULL,
  `floc` varchar(255) NOT NULL,
  `file_format` varchar(10) NOT NULL,
  `attachment_purpose` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `date_created` date NOT NULL,
  `changed_by` varchar(255) DEFAULT NULL,
  `date_changed` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tender_category`
--

CREATE TABLE `tbl_tender_category` (
  `id` int NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_tender_category`
--

INSERT INTO `tbl_tender_category` (`id`, `category`, `description`, `status`) VALUES
(1, 'Open Tender', 'Open to any potential tenderer', 1),
(2, 'AGPO', 'Reserved for some special groups', 1),
(3, 'Tester', 'Testing again', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tender_type`
--

CREATE TABLE `tbl_tender_type` (
  `id` int NOT NULL,
  `type` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_tender_type`
--

INSERT INTO `tbl_tender_type` (`id`, `type`, `description`, `status`) VALUES
(1, 'Works/Services', NULL, 1),
(2, 'Goods', NULL, 1),
(3, 'Tester', 'testing 123', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_terminologies`
--

CREATE TABLE `tbl_terminologies` (
  `id` int NOT NULL,
  `category` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `label_plural` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_terminologies`
--

INSERT INTO `tbl_terminologies` (`id`, `category`, `name`, `label`, `label_plural`) VALUES
(1, 1, 'ministry', 'Department', 'Departments'),
(2, 1, 'department', 'Section', 'Sections'),
(3, 2, 'level1', 'Sub-County', 'Sub-Counties'),
(4, 2, 'level2', 'Ward', 'Wards'),
(5, 2, 'level3', 'Location', 'Locations'),
(6, 3, 'Plan', 'CIDP', 'CIPDs');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_title`
--

CREATE TABLE `tbl_title` (
  `Title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_title`
--

INSERT INTO `tbl_title` (`Title`) VALUES
('Dr'),
('Eld'),
('Engr.'),
('Miss.'),
('Mr.'),
('Mrs.'),
('Prof');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_titles`
--

CREATE TABLE `tbl_titles` (
  `id` int NOT NULL,
  `title` varchar(50) NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_titles`
--

INSERT INTO `tbl_titles` (`id`, `title`, `status`) VALUES
(1, 'Prof', 1),
(2, 'Dr', 1),
(3, 'Engr', 1),
(4, 'Mr', 1),
(5, 'Mrs', 1),
(6, 'Miss', 1),
(7, 'Eld', 1),
(8, 't', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `userid` int NOT NULL,
  `pt_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `type` int NOT NULL,
  `level` varchar(20) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`userid`, `pt_id`, `username`, `password`, `type`, `level`, `lastlogin`) VALUES
(1, 1, 'john', 'd0204b396d379b6bef266085a0bd43c9', 1, '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_workflow`
--

CREATE TABLE `tbl_workflow` (
  `id` int NOT NULL,
  `workflow` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_workflow_stages`
--

CREATE TABLE `tbl_workflow_stages` (
  `id` int NOT NULL,
  `workflowid` int NOT NULL,
  `stage` varchar(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_workplan_targets`
--

CREATE TABLE `tbl_workplan_targets` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `indid` int NOT NULL,
  `year` int NOT NULL,
  `target` bigint DEFAULT NULL,
  `Q1` double NOT NULL,
  `Q2` double NOT NULL,
  `Q3` double NOT NULL,
  `Q4` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_workplan_targets`
--

INSERT INTO `tbl_workplan_targets` (`id`, `projid`, `outputid`, `indid`, `year`, `target`, `Q1`, `Q2`, `Q3`, `Q4`) VALUES
(1, 8, 63, 0, 2021, 1, 0, 0, 0, 0),
(2, 24, 100, 0, 2021, 1, 0, 0, 0, 0),
(3, 27, 105, 0, 2021, 2, 0, 0, 0, 0),
(4, 27, 105, 0, 2021, 2, 0, 0, 0, 0),
(5, 35, 131, 0, 2021, 40, 0, 0, 0, 0),
(6, 22, 96, 0, 2021, 3, 0, 0, 0, 0),
(7, 22, 96, 0, 2021, 1, 0, 0, 0, 0),
(8, 39, 135, 0, 2021, 2, 0, 0, 0, 0),
(9, 39, 135, 0, 2021, 2, 0, 0, 0, 0),
(13, 44, 143, 1, 2021, NULL, 0, 0, 0, 1),
(14, 38, 134, 18, 2021, NULL, 1, 5, 4, 2),
(15, 15, 86, 17, 2021, NULL, 2, 1, 1, 1),
(17, 45, 151, 151, 2021, NULL, 1000, 1000, 1000, 1000),
(18, 49, 164, 144, 2021, NULL, 20, 10, 10, 10),
(19, 55, 170, 163, 2021, NULL, 2500, 2500, 2500, 2500);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int NOT NULL,
  `pt_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `first_login` int NOT NULL DEFAULT '1',
  `password` varchar(200) NOT NULL,
  `type` int NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `pt_id`, `username`, `email`, `first_login`, `password`, `type`) VALUES
(1, 1, 'rejysobaf', 'biwottech@gmail.com', 0, '$2y$10$SiQJHm6bXzG9hSwHq9E25O9ANuTZmiAD98W6mx.71C5/BvTIl07a2', 1),
(112, 47, 'jasomyqyny', 'kyhilyd@mailinator.com', 1, '$2y$10$4c23u0YpOr0BW81jqMGmP.6plkjz33VL.UgzQHJEDx6N2w8C84BkO', 1),
(113, 48, 'qifujoj', 'wezad@mailinator.com', 0, '$2y$10$xQ..5qHvwUkd0kGejnSWuuW11TsDqtqT2d6qjKPcqC0alLW3LIbAu', 1),
(114, 49, 'fiqoqoxi', 'mited@mailinator.com', 0, '$2y$10$GAYwmVNmZxSqpQ2QjXxOqewFde3nbxM.z7iGqjBZ39acWNRce7yJC', 1),
(115, 50, 'wylojeq', NULL, 1, '237d172102814424299979536cd01903', 1),
(116, 51, 'leruty', 'koechvanos@gmail.com', 1, '89bf2a8c46506eb0ac04c77aa3429672', 1),
(117, 52, 'quxycejeh', 'koechvantos@gmail.com', 0, '$2y$10$bdZcYJ.qzcJqMau7z6xfw.L.M0xg.5NvNnARrpT9EVd6Ng2ObOY8O', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adm_id`),
  ADD KEY `adm_id` (`adm_id`);

--
-- Indexes for table `counties`
--
ALTER TABLE `counties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `iso_code` (`iso_code`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `tble_feedback_categories`
--
ALTER TABLE `tble_feedback_categories`
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `tbl_annual_dev_plan`
--
ALTER TABLE `tbl_annual_dev_plan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projid` (`projid`);

--
-- Indexes for table `tbl_assumption`
--
ALTER TABLE `tbl_assumption`
  ADD PRIMARY KEY (`asid`);

--
-- Indexes for table `tbl_big_four_agenda`
--
ALTER TABLE `tbl_big_four_agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_budget_lines`
--
ALTER TABLE `tbl_budget_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_capr_report_conclusion`
--
ALTER TABLE `tbl_capr_report_conclusion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_capr_report_remarks`
--
ALTER TABLE `tbl_capr_report_remarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_certificates`
--
ALTER TABLE `tbl_certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_checklist_test`
--
ALTER TABLE `tbl_checklist_test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  ADD PRIMARY KEY (`comid`);

--
-- Indexes for table `tbl_community`
--
ALTER TABLE `tbl_community`
  ADD PRIMARY KEY (`communityid`);

--
-- Indexes for table `tbl_company_settings`
--
ALTER TABLE `tbl_company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contractor`
--
ALTER TABLE `tbl_contractor`
  ADD PRIMARY KEY (`contrid`);

--
-- Indexes for table `tbl_contractorbusinesstype`
--
ALTER TABLE `tbl_contractorbusinesstype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contractordirectors`
--
ALTER TABLE `tbl_contractordirectors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contractordocuments`
--
ALTER TABLE `tbl_contractordocuments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contractornationality`
--
ALTER TABLE `tbl_contractornationality`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contractorpinstatus`
--
ALTER TABLE `tbl_contractorpinstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contractorvat`
--
ALTER TABLE `tbl_contractorvat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_cooperates_types`
--
ALTER TABLE `tbl_cooperates_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_countyadmindesignation`
--
ALTER TABLE `tbl_countyadmindesignation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_currency`
--
ALTER TABLE `tbl_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_datacollectionfreq`
--
ALTER TABLE `tbl_datacollectionfreq`
  ADD PRIMARY KEY (`fqid`);

--
-- Indexes for table `tbl_datacollection_settings`
--
ALTER TABLE `tbl_datacollection_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `tbl_datagatheringmethods`
--
ALTER TABLE `tbl_datagatheringmethods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_data_source`
--
ALTER TABLE `tbl_data_source`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_departments_allocation`
--
ALTER TABLE `tbl_departments_allocation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_disaggregation_type`
--
ALTER TABLE `tbl_disaggregation_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_donation_type`
--
ALTER TABLE `tbl_donation_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_donors`
--
ALTER TABLE `tbl_donors`
  ADD PRIMARY KEY (`dnid`);

--
-- Indexes for table `tbl_email_settings`
--
ALTER TABLE `tbl_email_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_email_templates`
--
ALTER TABLE `tbl_email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_employees_leave_categories`
--
ALTER TABLE `tbl_employees_leave_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_employee_leave`
--
ALTER TABLE `tbl_employee_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_employee_leave_bal`
--
ALTER TABLE `tbl_employee_leave_bal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_escalations`
--
ALTER TABLE `tbl_escalations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_estage`
--
ALTER TABLE `tbl_estage`
  ADD PRIMARY KEY (`esid`);

--
-- Indexes for table `tbl_evaluation`
--
ALTER TABLE `tbl_evaluation`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `tbl_files`
--
ALTER TABLE `tbl_files`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `tbl_filetypes`
--
ALTER TABLE `tbl_filetypes`
  ADD PRIMARY KEY (`ftid`);

--
-- Indexes for table `tbl_financiers`
--
ALTER TABLE `tbl_financiers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_financier_status_comments`
--
ALTER TABLE `tbl_financier_status_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_financier_type`
--
ALTER TABLE `tbl_financier_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_fiscal_year`
--
ALTER TABLE `tbl_fiscal_year`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year` (`year`);

--
-- Indexes for table `tbl_funder`
--
ALTER TABLE `tbl_funder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_funding`
--
ALTER TABLE `tbl_funding`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `tbl_funding_type`
--
ALTER TABLE `tbl_funding_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category` (`category`);

--
-- Indexes for table `tbl_funds`
--
ALTER TABLE `tbl_funds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `donationcode` (`fund_code`);

--
-- Indexes for table `tbl_funds_request`
--
ALTER TABLE `tbl_funds_request`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `tbl_general_inspection`
--
ALTER TABLE `tbl_general_inspection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_global_setting`
--
ALTER TABLE `tbl_global_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD PRIMARY KEY (`imageid`);

--
-- Indexes for table `tbl_imagesfive`
--
ALTER TABLE `tbl_imagesfive`
  ADD PRIMARY KEY (`imagefiveid`);

--
-- Indexes for table `tbl_imagestable_copy`
--
ALTER TABLE `tbl_imagestable_copy`
  ADD PRIMARY KEY (`imageid`);

--
-- Indexes for table `tbl_images_copy`
--
ALTER TABLE `tbl_images_copy`
  ADD PRIMARY KEY (`imageid`);

--
-- Indexes for table `tbl_impacts`
--
ALTER TABLE `tbl_impacts`
  ADD PRIMARY KEY (`impid`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `tbl_independent_programs_quarterly_targets`
--
ALTER TABLE `tbl_independent_programs_quarterly_targets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator`
--
ALTER TABLE `tbl_indicator`
  ADD PRIMARY KEY (`indid`);

--
-- Indexes for table `tbl_indicator_baseline_details`
--
ALTER TABLE `tbl_indicator_baseline_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_baseline_survey_answers`
--
ALTER TABLE `tbl_indicator_baseline_survey_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_baseline_survey_conclusion`
--
ALTER TABLE `tbl_indicator_baseline_survey_conclusion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_baseline_survey_details`
--
ALTER TABLE `tbl_indicator_baseline_survey_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_baseline_survey_forms`
--
ALTER TABLE `tbl_indicator_baseline_survey_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_baseline_survey_form_question_fields`
--
ALTER TABLE `tbl_indicator_baseline_survey_form_question_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_baseline_survey_form_question_field_values`
--
ALTER TABLE `tbl_indicator_baseline_survey_form_question_field_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_baseline_survey_form_sections`
--
ALTER TABLE `tbl_indicator_baseline_survey_form_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_baseline_survey_submission`
--
ALTER TABLE `tbl_indicator_baseline_survey_submission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_baseline_values`
--
ALTER TABLE `tbl_indicator_baseline_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_baseline_years`
--
ALTER TABLE `tbl_indicator_baseline_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_beneficiaries`
--
ALTER TABLE `tbl_indicator_beneficiaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_beneficiary_dissegragation`
--
ALTER TABLE `tbl_indicator_beneficiary_dissegragation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_calculation_method`
--
ALTER TABLE `tbl_indicator_calculation_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_categories`
--
ALTER TABLE `tbl_indicator_categories`
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `tbl_indicator_disaggregations`
--
ALTER TABLE `tbl_indicator_disaggregations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_disaggregation_types`
--
ALTER TABLE `tbl_indicator_disaggregation_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_level3_disaggregations`
--
ALTER TABLE `tbl_indicator_level3_disaggregations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_measurement_variables`
--
ALTER TABLE `tbl_indicator_measurement_variables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_measurement_variables_disaggregation_type`
--
ALTER TABLE `tbl_indicator_measurement_variables_disaggregation_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_output_baseline_values`
--
ALTER TABLE `tbl_indicator_output_baseline_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_indicator_strategic_plan_targets`
--
ALTER TABLE `tbl_indicator_strategic_plan_targets`
  ADD PRIMARY KEY (`strategicplanTargetId`);

--
-- Indexes for table `tbl_inner_menu`
--
ALTER TABLE `tbl_inner_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_inspection_assignment`
--
ALTER TABLE `tbl_inspection_assignment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_inspection_checklist`
--
ALTER TABLE `tbl_inspection_checklist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_inspection_checklist_questions`
--
ALTER TABLE `tbl_inspection_checklist_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_inspection_checklist_topics`
--
ALTER TABLE `tbl_inspection_checklist_topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_key_results_area`
--
ALTER TABLE `tbl_key_results_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_kpi`
--
ALTER TABLE `tbl_kpi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_kpi_aggregation_type`
--
ALTER TABLE `tbl_kpi_aggregation_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_level`
--
ALTER TABLE `tbl_level`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `tbl_lga`
--
ALTER TABLE `tbl_lga`
  ADD PRIMARY KEY (`lgaid`),
  ADD UNIQUE KEY `lgaid` (`lgaid`),
  ADD KEY `LGA_PrimaryKey_ndx` (`lgaid`);

--
-- Indexes for table `tbl_location`
--
ALTER TABLE `tbl_location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_main_funding`
--
ALTER TABLE `tbl_main_funding`
  ADD PRIMARY KEY (`fdid`);

--
-- Indexes for table `tbl_map_markers`
--
ALTER TABLE `tbl_map_markers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projid` (`projid`);

--
-- Indexes for table `tbl_map_markers_road`
--
ALTER TABLE `tbl_map_markers_road`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projid` (`projid`);

--
-- Indexes for table `tbl_map_markers_waypoint`
--
ALTER TABLE `tbl_map_markers_waypoint`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projid` (`projid`);

--
-- Indexes for table `tbl_map_type`
--
ALTER TABLE `tbl_map_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_markers`
--
ALTER TABLE `tbl_markers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_mbrtitle`
--
ALTER TABLE `tbl_mbrtitle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_measurement_units`
--
ALTER TABLE `tbl_measurement_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_meetings`
--
ALTER TABLE `tbl_meetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  ADD PRIMARY KEY (`mgid`);

--
-- Indexes for table `tbl_milestone`
--
ALTER TABLE `tbl_milestone`
  ADD PRIMARY KEY (`msid`);

--
-- Indexes for table `tbl_milestone_certificate`
--
ALTER TABLE `tbl_milestone_certificate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_monevarevrep`
--
ALTER TABLE `tbl_monevarevrep`
  ADD PRIMARY KEY (`monevarevrep_id`);

--
-- Indexes for table `tbl_monevarevrepoptions`
--
ALTER TABLE `tbl_monevarevrepoptions`
  ADD PRIMARY KEY (`monevarevoptns_id`);

--
-- Indexes for table `tbl_monitoring`
--
ALTER TABLE `tbl_monitoring`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `tbl_monitoringoutput`
--
ALTER TABLE `tbl_monitoringoutput`
  ADD PRIMARY KEY (`moid`);

--
-- Indexes for table `tbl_monitoring_links`
--
ALTER TABLE `tbl_monitoring_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_monitoring_observations`
--
ALTER TABLE `tbl_monitoring_observations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_msgcomments`
--
ALTER TABLE `tbl_msgcomments`
  ADD PRIMARY KEY (`mcid`);

--
-- Indexes for table `tbl_myprogfunding`
--
ALTER TABLE `tbl_myprogfunding`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_myprogfunding_history`
--
ALTER TABLE `tbl_myprogfunding_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_myprojfunding`
--
ALTER TABLE `tbl_myprojfunding`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_myprojfunding_history`
--
ALTER TABLE `tbl_myprojfunding_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_myprojpartner`
--
ALTER TABLE `tbl_myprojpartner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_objective_strategy`
--
ALTER TABLE `tbl_objective_strategy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_other_funding`
--
ALTER TABLE `tbl_other_funding`
  ADD PRIMARY KEY (`fdid`);

--
-- Indexes for table `tbl_outcomes`
--
ALTER TABLE `tbl_outcomes`
  ADD PRIMARY KEY (`ocid`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `tbl_outputs`
--
ALTER TABLE `tbl_outputs`
  ADD PRIMARY KEY (`opid`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `tbl_output_disaggregation`
--
ALTER TABLE `tbl_output_disaggregation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_output_risks`
--
ALTER TABLE `tbl_output_risks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_partners`
--
ALTER TABLE `tbl_partners`
  ADD PRIMARY KEY (`ptnid`);

--
-- Indexes for table `tbl_password_resets`
--
ALTER TABLE `tbl_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_payments_disbursed`
--
ALTER TABLE `tbl_payments_disbursed`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `requestid` (`requestid`),
  ADD UNIQUE KEY `refid` (`refid`);

--
-- Indexes for table `tbl_payments_request`
--
ALTER TABLE `tbl_payments_request`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `requestid` (`requestid`);

--
-- Indexes for table `tbl_payment_request_comments`
--
ALTER TABLE `tbl_payment_request_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_payment_status`
--
ALTER TABLE `tbl_payment_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pmdesignation`
--
ALTER TABLE `tbl_pmdesignation`
  ADD PRIMARY KEY (`moid`);

--
-- Indexes for table `tbl_priorities`
--
ALTER TABLE `tbl_priorities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_procurementmethod`
--
ALTER TABLE `tbl_procurementmethod`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_progdetails`
--
ALTER TABLE `tbl_progdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_progdetails_history`
--
ALTER TABLE `tbl_progdetails_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_programs`
--
ALTER TABLE `tbl_programs`
  ADD PRIMARY KEY (`progid`);

--
-- Indexes for table `tbl_programs_based_budget`
--
ALTER TABLE `tbl_programs_based_budget`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_programs_quarterly_targets`
--
ALTER TABLE `tbl_programs_quarterly_targets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projcountyadmin`
--
ALTER TABLE `tbl_projcountyadmin`
  ADD PRIMARY KEY (`ptid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Indexes for table `tbl_projdetails`
--
ALTER TABLE `tbl_projdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projectrisks`
--
ALTER TABLE `tbl_projectrisks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projects`
--
ALTER TABLE `tbl_projects`
  ADD PRIMARY KEY (`projid`,`projname`),
  ADD UNIQUE KEY `projname` (`projname`),
  ADD UNIQUE KEY `projcode` (`projcode`);

--
-- Indexes for table `tbl_projectstages`
--
ALTER TABLE `tbl_projectstages`
  ADD PRIMARY KEY (`psid`);

--
-- Indexes for table `tbl_projects_evaluation`
--
ALTER TABLE `tbl_projects_evaluation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projects_location_targets`
--
ALTER TABLE `tbl_projects_location_targets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projects_performance_report_remarks`
--
ALTER TABLE `tbl_projects_performance_report_remarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_approved_yearly_budget`
--
ALTER TABLE `tbl_project_approved_yearly_budget`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_beneficiaries`
--
ALTER TABLE `tbl_project_beneficiaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_beneficiary_disaggregation`
--
ALTER TABLE `tbl_project_beneficiary_disaggregation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_changed_parameters`
--
ALTER TABLE `tbl_project_changed_parameters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_cost_funders_share`
--
ALTER TABLE `tbl_project_cost_funders_share`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_details`
--
ALTER TABLE `tbl_project_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_details_history`
--
ALTER TABLE `tbl_project_details_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_direct_cost_plan`
--
ALTER TABLE `tbl_project_direct_cost_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_evaluation_answers`
--
ALTER TABLE `tbl_project_evaluation_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_evaluation_conclusion`
--
ALTER TABLE `tbl_project_evaluation_conclusion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_evaluation_forms`
--
ALTER TABLE `tbl_project_evaluation_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_evaluation_form_questions`
--
ALTER TABLE `tbl_project_evaluation_form_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_evaluation_form_question_fileds`
--
ALTER TABLE `tbl_project_evaluation_form_question_fileds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_evaluation_form_question_filed_values`
--
ALTER TABLE `tbl_project_evaluation_form_question_filed_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_evaluation_form_sections`
--
ALTER TABLE `tbl_project_evaluation_form_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_evaluation_submission`
--
ALTER TABLE `tbl_project_evaluation_submission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_evaluation_types`
--
ALTER TABLE `tbl_project_evaluation_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_expected_impact_details`
--
ALTER TABLE `tbl_project_expected_impact_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_expected_outcome_details`
--
ALTER TABLE `tbl_project_expected_outcome_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_expenditure_timeline`
--
ALTER TABLE `tbl_project_expenditure_timeline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_form_markers`
--
ALTER TABLE `tbl_project_form_markers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_history_results_level_disaggregation`
--
ALTER TABLE `tbl_project_history_results_level_disaggregation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_implementation_method`
--
ALTER TABLE `tbl_project_implementation_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_inspection_checklist`
--
ALTER TABLE `tbl_project_inspection_checklist`
  ADD PRIMARY KEY (`ckid`);

--
-- Indexes for table `tbl_project_inspection_checklist_comments`
--
ALTER TABLE `tbl_project_inspection_checklist_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_inspection_checklist_score`
--
ALTER TABLE `tbl_project_inspection_checklist_score`
  ADD PRIMARY KEY (`ckid`);

--
-- Indexes for table `tbl_project_inspection_gis_location`
--
ALTER TABLE `tbl_project_inspection_gis_location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_inspection_noncompliance_comments`
--
ALTER TABLE `tbl_project_inspection_noncompliance_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_inspection_status`
--
ALTER TABLE `tbl_project_inspection_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_mapping`
--
ALTER TABLE `tbl_project_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_monitoring_checklist`
--
ALTER TABLE `tbl_project_monitoring_checklist`
  ADD PRIMARY KEY (`ckid`);

--
-- Indexes for table `tbl_project_monitoring_checklist_noncompliance_comments`
--
ALTER TABLE `tbl_project_monitoring_checklist_noncompliance_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_monitoring_checklist_score`
--
ALTER TABLE `tbl_project_monitoring_checklist_score`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_other_cost_plan`
--
ALTER TABLE `tbl_project_other_cost_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_outcome_evaluation_questions`
--
ALTER TABLE `tbl_project_outcome_evaluation_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_outputs`
--
ALTER TABLE `tbl_project_outputs`
  ADD PRIMARY KEY (`opid`);

--
-- Indexes for table `tbl_project_outputs_mne_details`
--
ALTER TABLE `tbl_project_outputs_mne_details`
  ADD PRIMARY KEY (`opid`);

--
-- Indexes for table `tbl_project_output_details`
--
ALTER TABLE `tbl_project_output_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_output_details_history`
--
ALTER TABLE `tbl_project_output_details_history`
  ADD PRIMARY KEY (`odid`);

--
-- Indexes for table `tbl_project_output_diss_resp`
--
ALTER TABLE `tbl_project_output_diss_resp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_photos`
--
ALTER TABLE `tbl_project_photos`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `tbl_project_results_level_disaggregation`
--
ALTER TABLE `tbl_project_results_level_disaggregation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_riskscore`
--
ALTER TABLE `tbl_project_riskscore`
  ADD PRIMARY KEY (`scid`),
  ADD UNIQUE KEY `issueid` (`issueid`);

--
-- Indexes for table `tbl_project_stages`
--
ALTER TABLE `tbl_project_stages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_team_roles`
--
ALTER TABLE `tbl_project_team_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_tender_details`
--
ALTER TABLE `tbl_project_tender_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_workflow_stage`
--
ALTER TABLE `tbl_project_workflow_stage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_workflow_stage_old`
--
ALTER TABLE `tbl_project_workflow_stage_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_workflow_stage_timelines`
--
ALTER TABLE `tbl_project_workflow_stage_timelines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projfunding`
--
ALTER TABLE `tbl_projfunding`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projfunding_history`
--
ALTER TABLE `tbl_projfunding_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projissues`
--
ALTER TABLE `tbl_projissues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projissues_discussions`
--
ALTER TABLE `tbl_projissues_discussions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projissue_comments`
--
ALTER TABLE `tbl_projissue_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projissue_severity`
--
ALTER TABLE `tbl_projissue_severity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projmembers`
--
ALTER TABLE `tbl_projmembers`
  ADD PRIMARY KEY (`pmid`);

--
-- Indexes for table `tbl_projmemoffices`
--
ALTER TABLE `tbl_projmemoffices`
  ADD PRIMARY KEY (`moid`);

--
-- Indexes for table `tbl_projrisk_categories`
--
ALTER TABLE `tbl_projrisk_categories`
  ADD PRIMARY KEY (`rskid`);

--
-- Indexes for table `tbl_projrisk_response`
--
ALTER TABLE `tbl_projrisk_response`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projstage_responsible`
--
ALTER TABLE `tbl_projstage_responsible`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projstatuschangereason`
--
ALTER TABLE `tbl_projstatuschangereason`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projstatusfiles`
--
ALTER TABLE `tbl_projstatusfiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projtable`
--
ALTER TABLE `tbl_projtable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_projteam2`
--
ALTER TABLE `tbl_projteam2`
  ADD PRIMARY KEY (`ptid`);

--
-- Indexes for table `tbl_projtypelist`
--
ALTER TABLE `tbl_projtypelist`
  ADD PRIMARY KEY (`projtypeid`);

--
-- Indexes for table `tbl_public_feedback`
--
ALTER TABLE `tbl_public_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_qapr_report_conclusion`
--
ALTER TABLE `tbl_qapr_report_conclusion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_qapr_report_remarks`
--
ALTER TABLE `tbl_qapr_report_remarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_role_escalation`
--
ALTER TABLE `tbl_role_escalation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sectors`
--
ALTER TABLE `tbl_sectors`
  ADD PRIMARY KEY (`stid`);

--
-- Indexes for table `tbl_settings_menu`
--
ALTER TABLE `tbl_settings_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sidebar_menu`
--
ALTER TABLE `tbl_sidebar_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_state`
--
ALTER TABLE `tbl_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_status`
--
ALTER TABLE `tbl_status`
  ADD PRIMARY KEY (`statusid`);

--
-- Indexes for table `tbl_strategicplan`
--
ALTER TABLE `tbl_strategicplan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_strategic_objective_targets_threshold`
--
ALTER TABLE `tbl_strategic_objective_targets_threshold`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_strategic_plan_objectives`
--
ALTER TABLE `tbl_strategic_plan_objectives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_strategic_plan_objective_targets`
--
ALTER TABLE `tbl_strategic_plan_objective_targets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_strategic_plan_op_indicator_budget`
--
ALTER TABLE `tbl_strategic_plan_op_indicator_budget`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_strategic_plan_op_indicator_targets`
--
ALTER TABLE `tbl_strategic_plan_op_indicator_targets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_survey_conclusion`
--
ALTER TABLE `tbl_survey_conclusion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_system_modules`
--
ALTER TABLE `tbl_system_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_system_status`
--
ALTER TABLE `tbl_system_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_task`
--
ALTER TABLE `tbl_task`
  ADD PRIMARY KEY (`tkid`);

--
-- Indexes for table `tbl_taskstatus`
--
ALTER TABLE `tbl_taskstatus`
  ADD PRIMARY KEY (`tsid`);

--
-- Indexes for table `tbl_task_inspection_status`
--
ALTER TABLE `tbl_task_inspection_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_task_progress`
--
ALTER TABLE `tbl_task_progress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_task_status`
--
ALTER TABLE `tbl_task_status`
  ADD PRIMARY KEY (`statusid`);

--
-- Indexes for table `tbl_tenderdetails`
--
ALTER TABLE `tbl_tenderdetails`
  ADD PRIMARY KEY (`td_id`);

--
-- Indexes for table `tbl_tenderdocuments`
--
ALTER TABLE `tbl_tenderdocuments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tender_category`
--
ALTER TABLE `tbl_tender_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tender_type`
--
ALTER TABLE `tbl_tender_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_terminologies`
--
ALTER TABLE `tbl_terminologies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_title`
--
ALTER TABLE `tbl_title`
  ADD PRIMARY KEY (`Title`);

--
-- Indexes for table `tbl_titles`
--
ALTER TABLE `tbl_titles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `adm_id` (`userid`);

--
-- Indexes for table `tbl_workflow`
--
ALTER TABLE `tbl_workflow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_workflow_stages`
--
ALTER TABLE `tbl_workflow_stages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_workplan_targets`
--
ALTER TABLE `tbl_workplan_targets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `adm_id` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adm_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `counties`
--
ALTER TABLE `counties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `fid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `sysid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tble_feedback_categories`
--
ALTER TABLE `tble_feedback_categories`
  MODIFY `catid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_annual_dev_plan`
--
ALTER TABLE `tbl_annual_dev_plan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `tbl_assumption`
--
ALTER TABLE `tbl_assumption`
  MODIFY `asid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_big_four_agenda`
--
ALTER TABLE `tbl_big_four_agenda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_budget_lines`
--
ALTER TABLE `tbl_budget_lines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_capr_report_conclusion`
--
ALTER TABLE `tbl_capr_report_conclusion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_capr_report_remarks`
--
ALTER TABLE `tbl_capr_report_remarks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_certificates`
--
ALTER TABLE `tbl_certificates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_checklist_test`
--
ALTER TABLE `tbl_checklist_test`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  MODIFY `comid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_community`
--
ALTER TABLE `tbl_community`
  MODIFY `communityid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_company_settings`
--
ALTER TABLE `tbl_company_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_contractor`
--
ALTER TABLE `tbl_contractor`
  MODIFY `contrid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_contractorbusinesstype`
--
ALTER TABLE `tbl_contractorbusinesstype`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_contractordirectors`
--
ALTER TABLE `tbl_contractordirectors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tbl_contractordocuments`
--
ALTER TABLE `tbl_contractordocuments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_contractornationality`
--
ALTER TABLE `tbl_contractornationality`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_contractorpinstatus`
--
ALTER TABLE `tbl_contractorpinstatus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_contractorvat`
--
ALTER TABLE `tbl_contractorvat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_cooperates_types`
--
ALTER TABLE `tbl_cooperates_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_countyadmindesignation`
--
ALTER TABLE `tbl_countyadmindesignation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_currency`
--
ALTER TABLE `tbl_currency`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_datacollectionfreq`
--
ALTER TABLE `tbl_datacollectionfreq`
  MODIFY `fqid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_datacollection_settings`
--
ALTER TABLE `tbl_datacollection_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_datagatheringmethods`
--
ALTER TABLE `tbl_datagatheringmethods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_data_source`
--
ALTER TABLE `tbl_data_source`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_departments_allocation`
--
ALTER TABLE `tbl_departments_allocation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_disaggregation_type`
--
ALTER TABLE `tbl_disaggregation_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_donation_type`
--
ALTER TABLE `tbl_donation_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_donors`
--
ALTER TABLE `tbl_donors`
  MODIFY `dnid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_email_settings`
--
ALTER TABLE `tbl_email_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_email_templates`
--
ALTER TABLE `tbl_email_templates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_employees_leave_categories`
--
ALTER TABLE `tbl_employees_leave_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_employee_leave`
--
ALTER TABLE `tbl_employee_leave`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_employee_leave_bal`
--
ALTER TABLE `tbl_employee_leave_bal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_escalations`
--
ALTER TABLE `tbl_escalations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_estage`
--
ALTER TABLE `tbl_estage`
  MODIFY `esid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_evaluation`
--
ALTER TABLE `tbl_evaluation`
  MODIFY `eid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_files`
--
ALTER TABLE `tbl_files`
  MODIFY `fid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `tbl_filetypes`
--
ALTER TABLE `tbl_filetypes`
  MODIFY `ftid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_financiers`
--
ALTER TABLE `tbl_financiers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_financier_status_comments`
--
ALTER TABLE `tbl_financier_status_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_financier_type`
--
ALTER TABLE `tbl_financier_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_fiscal_year`
--
ALTER TABLE `tbl_fiscal_year`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_funder`
--
ALTER TABLE `tbl_funder`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_funding`
--
ALTER TABLE `tbl_funding`
  MODIFY `fid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_funding_type`
--
ALTER TABLE `tbl_funding_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_funds`
--
ALTER TABLE `tbl_funds`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_funds_request`
--
ALTER TABLE `tbl_funds_request`
  MODIFY `fid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_general_inspection`
--
ALTER TABLE `tbl_general_inspection`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_global_setting`
--
ALTER TABLE `tbl_global_setting`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_images`
--
ALTER TABLE `tbl_images`
  MODIFY `imageid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_imagesfive`
--
ALTER TABLE `tbl_imagesfive`
  MODIFY `imagefiveid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_imagestable_copy`
--
ALTER TABLE `tbl_imagestable_copy`
  MODIFY `imageid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_images_copy`
--
ALTER TABLE `tbl_images_copy`
  MODIFY `imageid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_impacts`
--
ALTER TABLE `tbl_impacts`
  MODIFY `impid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_independent_programs_quarterly_targets`
--
ALTER TABLE `tbl_independent_programs_quarterly_targets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_indicator`
--
ALTER TABLE `tbl_indicator`
  MODIFY `indid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_details`
--
ALTER TABLE `tbl_indicator_baseline_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_survey_answers`
--
ALTER TABLE `tbl_indicator_baseline_survey_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_survey_conclusion`
--
ALTER TABLE `tbl_indicator_baseline_survey_conclusion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_survey_details`
--
ALTER TABLE `tbl_indicator_baseline_survey_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_survey_forms`
--
ALTER TABLE `tbl_indicator_baseline_survey_forms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_survey_form_question_fields`
--
ALTER TABLE `tbl_indicator_baseline_survey_form_question_fields`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_survey_form_question_field_values`
--
ALTER TABLE `tbl_indicator_baseline_survey_form_question_field_values`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_survey_form_sections`
--
ALTER TABLE `tbl_indicator_baseline_survey_form_sections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_survey_submission`
--
ALTER TABLE `tbl_indicator_baseline_survey_submission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_values`
--
ALTER TABLE `tbl_indicator_baseline_values`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_years`
--
ALTER TABLE `tbl_indicator_baseline_years`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_indicator_beneficiaries`
--
ALTER TABLE `tbl_indicator_beneficiaries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_beneficiary_dissegragation`
--
ALTER TABLE `tbl_indicator_beneficiary_dissegragation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_calculation_method`
--
ALTER TABLE `tbl_indicator_calculation_method`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_indicator_categories`
--
ALTER TABLE `tbl_indicator_categories`
  MODIFY `catid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_indicator_disaggregations`
--
ALTER TABLE `tbl_indicator_disaggregations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_indicator_disaggregation_types`
--
ALTER TABLE `tbl_indicator_disaggregation_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_indicator_level3_disaggregations`
--
ALTER TABLE `tbl_indicator_level3_disaggregations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_measurement_variables`
--
ALTER TABLE `tbl_indicator_measurement_variables`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_indicator_measurement_variables_disaggregation_type`
--
ALTER TABLE `tbl_indicator_measurement_variables_disaggregation_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_indicator_output_baseline_values`
--
ALTER TABLE `tbl_indicator_output_baseline_values`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1166;

--
-- AUTO_INCREMENT for table `tbl_indicator_strategic_plan_targets`
--
ALTER TABLE `tbl_indicator_strategic_plan_targets`
  MODIFY `strategicplanTargetId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_inner_menu`
--
ALTER TABLE `tbl_inner_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_inspection_assignment`
--
ALTER TABLE `tbl_inspection_assignment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_inspection_checklist`
--
ALTER TABLE `tbl_inspection_checklist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_inspection_checklist_questions`
--
ALTER TABLE `tbl_inspection_checklist_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_inspection_checklist_topics`
--
ALTER TABLE `tbl_inspection_checklist_topics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_key_results_area`
--
ALTER TABLE `tbl_key_results_area`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_kpi`
--
ALTER TABLE `tbl_kpi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_kpi_aggregation_type`
--
ALTER TABLE `tbl_kpi_aggregation_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_level`
--
ALTER TABLE `tbl_level`
  MODIFY `level_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_lga`
--
ALTER TABLE `tbl_lga`
  MODIFY `lgaid` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_location`
--
ALTER TABLE `tbl_location`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_login`
--
ALTER TABLE `tbl_login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_main_funding`
--
ALTER TABLE `tbl_main_funding`
  MODIFY `fdid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_map_markers`
--
ALTER TABLE `tbl_map_markers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_map_markers_road`
--
ALTER TABLE `tbl_map_markers_road`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_map_markers_waypoint`
--
ALTER TABLE `tbl_map_markers_waypoint`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_map_type`
--
ALTER TABLE `tbl_map_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_markers`
--
ALTER TABLE `tbl_markers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `tbl_mbrtitle`
--
ALTER TABLE `tbl_mbrtitle`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_measurement_units`
--
ALTER TABLE `tbl_measurement_units`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tbl_meetings`
--
ALTER TABLE `tbl_meetings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  MODIFY `mgid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_milestone`
--
ALTER TABLE `tbl_milestone`
  MODIFY `msid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `tbl_milestone_certificate`
--
ALTER TABLE `tbl_milestone_certificate`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tbl_monevarevrep`
--
ALTER TABLE `tbl_monevarevrep`
  MODIFY `monevarevrep_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_monevarevrepoptions`
--
ALTER TABLE `tbl_monevarevrepoptions`
  MODIFY `monevarevoptns_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_monitoring`
--
ALTER TABLE `tbl_monitoring`
  MODIFY `mid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tbl_monitoringoutput`
--
ALTER TABLE `tbl_monitoringoutput`
  MODIFY `moid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tbl_monitoring_links`
--
ALTER TABLE `tbl_monitoring_links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_monitoring_observations`
--
ALTER TABLE `tbl_monitoring_observations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tbl_msgcomments`
--
ALTER TABLE `tbl_msgcomments`
  MODIFY `mcid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_myprogfunding`
--
ALTER TABLE `tbl_myprogfunding`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `tbl_myprogfunding_history`
--
ALTER TABLE `tbl_myprogfunding_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_myprojfunding`
--
ALTER TABLE `tbl_myprojfunding`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;

--
-- AUTO_INCREMENT for table `tbl_myprojfunding_history`
--
ALTER TABLE `tbl_myprojfunding_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_myprojpartner`
--
ALTER TABLE `tbl_myprojpartner`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_objective_strategy`
--
ALTER TABLE `tbl_objective_strategy`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `tbl_other_funding`
--
ALTER TABLE `tbl_other_funding`
  MODIFY `fdid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_outcomes`
--
ALTER TABLE `tbl_outcomes`
  MODIFY `ocid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_outputs`
--
ALTER TABLE `tbl_outputs`
  MODIFY `opid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_output_disaggregation`
--
ALTER TABLE `tbl_output_disaggregation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=351;

--
-- AUTO_INCREMENT for table `tbl_output_risks`
--
ALTER TABLE `tbl_output_risks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_partners`
--
ALTER TABLE `tbl_partners`
  MODIFY `ptnid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_password_resets`
--
ALTER TABLE `tbl_password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_payments_disbursed`
--
ALTER TABLE `tbl_payments_disbursed`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_payments_request`
--
ALTER TABLE `tbl_payments_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_payment_request_comments`
--
ALTER TABLE `tbl_payment_request_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_payment_status`
--
ALTER TABLE `tbl_payment_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_pmdesignation`
--
ALTER TABLE `tbl_pmdesignation`
  MODIFY `moid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=445;

--
-- AUTO_INCREMENT for table `tbl_priorities`
--
ALTER TABLE `tbl_priorities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_procurementmethod`
--
ALTER TABLE `tbl_procurementmethod`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_progdetails`
--
ALTER TABLE `tbl_progdetails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `tbl_progdetails_history`
--
ALTER TABLE `tbl_progdetails_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_programs`
--
ALTER TABLE `tbl_programs`
  MODIFY `progid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `tbl_programs_based_budget`
--
ALTER TABLE `tbl_programs_based_budget`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `tbl_programs_quarterly_targets`
--
ALTER TABLE `tbl_programs_quarterly_targets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_projcountyadmin`
--
ALTER TABLE `tbl_projcountyadmin`
  MODIFY `ptid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projdetails`
--
ALTER TABLE `tbl_projdetails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projectrisks`
--
ALTER TABLE `tbl_projectrisks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tbl_projects`
--
ALTER TABLE `tbl_projects`
  MODIFY `projid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tbl_projectstages`
--
ALTER TABLE `tbl_projectstages`
  MODIFY `psid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projects_evaluation`
--
ALTER TABLE `tbl_projects_evaluation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_projects_location_targets`
--
ALTER TABLE `tbl_projects_location_targets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `tbl_projects_performance_report_remarks`
--
ALTER TABLE `tbl_projects_performance_report_remarks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_project_approved_yearly_budget`
--
ALTER TABLE `tbl_project_approved_yearly_budget`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `tbl_project_beneficiaries`
--
ALTER TABLE `tbl_project_beneficiaries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_beneficiary_disaggregation`
--
ALTER TABLE `tbl_project_beneficiary_disaggregation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_changed_parameters`
--
ALTER TABLE `tbl_project_changed_parameters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_project_cost_funders_share`
--
ALTER TABLE `tbl_project_cost_funders_share`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `tbl_project_details`
--
ALTER TABLE `tbl_project_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `tbl_project_details_history`
--
ALTER TABLE `tbl_project_details_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `tbl_project_direct_cost_plan`
--
ALTER TABLE `tbl_project_direct_cost_plan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_answers`
--
ALTER TABLE `tbl_project_evaluation_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_conclusion`
--
ALTER TABLE `tbl_project_evaluation_conclusion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_forms`
--
ALTER TABLE `tbl_project_evaluation_forms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_form_questions`
--
ALTER TABLE `tbl_project_evaluation_form_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_form_question_fileds`
--
ALTER TABLE `tbl_project_evaluation_form_question_fileds`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_form_question_filed_values`
--
ALTER TABLE `tbl_project_evaluation_form_question_filed_values`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_form_sections`
--
ALTER TABLE `tbl_project_evaluation_form_sections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_submission`
--
ALTER TABLE `tbl_project_evaluation_submission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_types`
--
ALTER TABLE `tbl_project_evaluation_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_project_expected_impact_details`
--
ALTER TABLE `tbl_project_expected_impact_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_expected_outcome_details`
--
ALTER TABLE `tbl_project_expected_outcome_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_project_expenditure_timeline`
--
ALTER TABLE `tbl_project_expenditure_timeline`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `tbl_project_form_markers`
--
ALTER TABLE `tbl_project_form_markers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_project_history_results_level_disaggregation`
--
ALTER TABLE `tbl_project_history_results_level_disaggregation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_project_implementation_method`
--
ALTER TABLE `tbl_project_implementation_method`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_project_inspection_checklist`
--
ALTER TABLE `tbl_project_inspection_checklist`
  MODIFY `ckid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_project_inspection_checklist_comments`
--
ALTER TABLE `tbl_project_inspection_checklist_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_inspection_checklist_score`
--
ALTER TABLE `tbl_project_inspection_checklist_score`
  MODIFY `ckid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_inspection_gis_location`
--
ALTER TABLE `tbl_project_inspection_gis_location`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_inspection_noncompliance_comments`
--
ALTER TABLE `tbl_project_inspection_noncompliance_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_inspection_status`
--
ALTER TABLE `tbl_project_inspection_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_project_mapping`
--
ALTER TABLE `tbl_project_mapping`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `tbl_project_monitoring_checklist`
--
ALTER TABLE `tbl_project_monitoring_checklist`
  MODIFY `ckid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `tbl_project_monitoring_checklist_noncompliance_comments`
--
ALTER TABLE `tbl_project_monitoring_checklist_noncompliance_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_monitoring_checklist_score`
--
ALTER TABLE `tbl_project_monitoring_checklist_score`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `tbl_project_other_cost_plan`
--
ALTER TABLE `tbl_project_other_cost_plan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_outcome_evaluation_questions`
--
ALTER TABLE `tbl_project_outcome_evaluation_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_project_outputs`
--
ALTER TABLE `tbl_project_outputs`
  MODIFY `opid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_outputs_mne_details`
--
ALTER TABLE `tbl_project_outputs_mne_details`
  MODIFY `opid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_project_output_details`
--
ALTER TABLE `tbl_project_output_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;

--
-- AUTO_INCREMENT for table `tbl_project_output_details_history`
--
ALTER TABLE `tbl_project_output_details_history`
  MODIFY `odid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `tbl_project_output_diss_resp`
--
ALTER TABLE `tbl_project_output_diss_resp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_photos`
--
ALTER TABLE `tbl_project_photos`
  MODIFY `fid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_project_results_level_disaggregation`
--
ALTER TABLE `tbl_project_results_level_disaggregation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_project_riskscore`
--
ALTER TABLE `tbl_project_riskscore`
  MODIFY `scid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_project_stages`
--
ALTER TABLE `tbl_project_stages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_project_team_roles`
--
ALTER TABLE `tbl_project_team_roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_project_tender_details`
--
ALTER TABLE `tbl_project_tender_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tbl_project_workflow_stage`
--
ALTER TABLE `tbl_project_workflow_stage`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_project_workflow_stage_old`
--
ALTER TABLE `tbl_project_workflow_stage_old`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_project_workflow_stage_timelines`
--
ALTER TABLE `tbl_project_workflow_stage_timelines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_projfunding`
--
ALTER TABLE `tbl_projfunding`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `tbl_projfunding_history`
--
ALTER TABLE `tbl_projfunding_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projissues`
--
ALTER TABLE `tbl_projissues`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tbl_projissues_discussions`
--
ALTER TABLE `tbl_projissues_discussions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_projissue_comments`
--
ALTER TABLE `tbl_projissue_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_projissue_severity`
--
ALTER TABLE `tbl_projissue_severity`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_projmembers`
--
ALTER TABLE `tbl_projmembers`
  MODIFY `pmid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `tbl_projmemoffices`
--
ALTER TABLE `tbl_projmemoffices`
  MODIFY `moid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_projrisk_categories`
--
ALTER TABLE `tbl_projrisk_categories`
  MODIFY `rskid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `tbl_projrisk_response`
--
ALTER TABLE `tbl_projrisk_response`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `tbl_projstage_responsible`
--
ALTER TABLE `tbl_projstage_responsible`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projstatuschangereason`
--
ALTER TABLE `tbl_projstatuschangereason`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_projstatusfiles`
--
ALTER TABLE `tbl_projstatusfiles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projtable`
--
ALTER TABLE `tbl_projtable`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projteam2`
--
ALTER TABLE `tbl_projteam2`
  MODIFY `ptid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `tbl_projtypelist`
--
ALTER TABLE `tbl_projtypelist`
  MODIFY `projtypeid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_public_feedback`
--
ALTER TABLE `tbl_public_feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_qapr_report_conclusion`
--
ALTER TABLE `tbl_qapr_report_conclusion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_qapr_report_remarks`
--
ALTER TABLE `tbl_qapr_report_remarks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_role_escalation`
--
ALTER TABLE `tbl_role_escalation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_sectors`
--
ALTER TABLE `tbl_sectors`
  MODIFY `stid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_settings_menu`
--
ALTER TABLE `tbl_settings_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sidebar_menu`
--
ALTER TABLE `tbl_sidebar_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `tbl_state`
--
ALTER TABLE `tbl_state`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=415;

--
-- AUTO_INCREMENT for table `tbl_status`
--
ALTER TABLE `tbl_status`
  MODIFY `statusid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_strategicplan`
--
ALTER TABLE `tbl_strategicplan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_strategic_objective_targets_threshold`
--
ALTER TABLE `tbl_strategic_objective_targets_threshold`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_strategic_plan_objectives`
--
ALTER TABLE `tbl_strategic_plan_objectives`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tbl_strategic_plan_objective_targets`
--
ALTER TABLE `tbl_strategic_plan_objective_targets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_strategic_plan_op_indicator_budget`
--
ALTER TABLE `tbl_strategic_plan_op_indicator_budget`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `tbl_strategic_plan_op_indicator_targets`
--
ALTER TABLE `tbl_strategic_plan_op_indicator_targets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=686;

--
-- AUTO_INCREMENT for table `tbl_survey_conclusion`
--
ALTER TABLE `tbl_survey_conclusion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `tbl_system_modules`
--
ALTER TABLE `tbl_system_modules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_task`
--
ALTER TABLE `tbl_task`
  MODIFY `tkid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `tbl_taskstatus`
--
ALTER TABLE `tbl_taskstatus`
  MODIFY `tsid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_task_inspection_status`
--
ALTER TABLE `tbl_task_inspection_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_task_progress`
--
ALTER TABLE `tbl_task_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `tbl_task_status`
--
ALTER TABLE `tbl_task_status`
  MODIFY `statusid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_tenderdetails`
--
ALTER TABLE `tbl_tenderdetails`
  MODIFY `td_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_tenderdocuments`
--
ALTER TABLE `tbl_tenderdocuments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_tender_category`
--
ALTER TABLE `tbl_tender_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_tender_type`
--
ALTER TABLE `tbl_tender_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_terminologies`
--
ALTER TABLE `tbl_terminologies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_titles`
--
ALTER TABLE `tbl_titles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_workflow`
--
ALTER TABLE `tbl_workflow`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_workflow_stages`
--
ALTER TABLE `tbl_workflow_stages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_workplan_targets`
--
ALTER TABLE `tbl_workplan_targets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
