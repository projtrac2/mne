-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 16, 2023 at 08:12 AM
-- Server version: 8.0.34-0ubuntu0.20.04.1
-- PHP Version: 7.4.3-4ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projtrac-mne`
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

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
-- Table structure for table `tbl_adp_projects_budget`
--

CREATE TABLE `tbl_adp_projects_budget` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `progid` int NOT NULL,
  `year` int NOT NULL,
  `amount` double NOT NULL,
  `created_by` int NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_adp_projects_budget`
--

INSERT INTO `tbl_adp_projects_budget` (`id`, `projid`, `progid`, `year`, `amount`, `created_by`, `date_created`) VALUES
(1, 53, 4, 6, 40000000, 118, '2023-09-28'),
(2, 88, 14, 6, 21000000, 118, '2023-09-28'),
(3, 54, 4, 6, 10000000, 118, '2023-09-28'),
(4, 56, 4, 6, 60000000, 118, '2023-09-28'),
(5, 55, 4, 6, 5000000, 118, '2023-09-28'),
(6, 51, 4, 6, 50000000, 118, '2023-09-28');

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
(25, 15, 6, 1, '118', '2023-04-03', '1', '2023-04-03', NULL, NULL),
(27, 52, 6, 1, '118', '2023-04-03', '1', '2023-09-02', NULL, NULL),
(28, 53, 6, 0, '118', '2023-04-03', NULL, NULL, NULL, NULL),
(29, 54, 6, 0, '118', '2023-04-03', NULL, NULL, NULL, NULL),
(30, 55, 6, 0, '118', '2023-04-03', NULL, NULL, NULL, NULL),
(36, 51, 6, 0, '1', '2023-04-15', NULL, NULL, NULL, NULL),
(37, 56, 6, 0, '118', '2023-08-07', NULL, NULL, NULL, NULL),
(38, 88, 6, 0, '118', '2023-08-12', NULL, NULL, NULL, NULL);

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
  `grp` int NOT NULL COMMENT 'Budgetlines groups',
  `description` varchar(255) NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_budget_lines`
--

INSERT INTO `tbl_budget_lines` (`id`, `name`, `grp`, `description`, `status`) VALUES
(2, 'Administration/Operational Cost', 1, 'Budget for project office administration', 1),
(3, 'Monitoring & Evaluation Cost', 1, 'Project M&E Operations cost', 0),
(4, 'Non Expendable Equipment Cost', 2, 'Non Expendable Equipment Cost', 0),
(5, 'Local Travel Cost', 1, 'Project local travel expenses', 1),
(8, 'test budget line ', 3, 'description', 0);

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
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `county` int DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `plot_no` varchar(255) DEFAULT NULL,
  `directory_location` varchar(255) DEFAULT NULL,
  `domain_address` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `main_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `coordinates_path` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `created_by` int NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_company_settings`
--

INSERT INTO `tbl_company_settings` (`id`, `company_name`, `type`, `code`, `postal_address`, `country`, `telephone_no`, `mobile_no`, `email_address`, `county`, `city`, `plot_no`, `directory_location`, `domain_address`, `ip_address`, `main_url`, `coordinates_path`, `logo`, `latitude`, `longitude`, `created_by`, `date_created`) VALUES
(1, 'County Government of Uasin Gishu', 2, '0', 'P.O. Box 40-30100, Eldoret', 100, '053 2061330', '0727044818', 'info@uasingishu.go.ke', 27, 'Eldoret', '12345-6', NULL, 'www.uasingishu.go.ke/', '34.74.197.215', 'http://34.74.197.215/mne/', NULL, 'images/projtrac_logo.png', -1.292066, 36.821946, 118, '2022-09-28');

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
  `first_login` int NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg',
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

INSERT INTO `tbl_contractor` (`contrid`, `contractor_name`, `businesstype`, `pinno`, `busregno`, `dateregistered`, `pinstatus`, `vatregistered`, `contact`, `address`, `city`, `county`, `country`, `phone`, `email`, `first_login`, `password`, `avatar`, `comments`, `date_created`, `active`, `deleted`, `user_name`, `updated_by`, `date_updated`) VALUES
(1, 'Dittman Company', 1, 'A06226250Q', '234444', '2000-09-16', 1, 1, 'P.O Box 2233 Eldoret', 'Uganda Road', 'Eldoret', 27, NULL, '0733467811', 'kkipe15@gmail.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', '<p>Construction Company</p>', '2022-01-16', '1', '0', 'Admin0', NULL, NULL),
(2, 'African Water Company', 1, 'P0516226250P', '445566', '2010-02-16', 1, 1, 'P.O Box 444-0100', 'Riverside', 'Nairobi', 47, NULL, '0733467811', 'pkorir59@gmail.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', '', '2022-01-16', '1', '0', 'Admin0', NULL, NULL),
(3, 'Fatah Construction & Civil Works Limited', 1, 'P07227894R', '11098', '2005-02-16', 1, 1, 'P.O Box 889-00200', 'Karen', 'Nairobi', 47, NULL, '0720942928', 'kiplish@gmail.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', '', '2022-01-16', '1', '0', 'Admin0', NULL, NULL),
(4, 'Civicon Engineering Co', 1, 'Q922925T', '12367', '1998-07-16', 1, 1, 'P.O Box3456-00200 Nairobi', 'Upper Hill', 'Nairobi', 47, NULL, '0720941929', 'kiplish@gmail.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', '', '2022-01-16', '1', '0', 'Admin0', NULL, NULL),
(5, 'Projtrac Systems Limited', 1, 'A6226250P', '779966', '2018-01-05', 1, 1, 'P.O Box 1234-00200', 'Nairobi', 'Nairobi', 47, NULL, '+254 733 467 811', 'kiplish@gmail.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', '<p>IT Company</p>', '2022-03-05', '1', '0', 'Admin0', 'admin0', '2022-03-31 23:03:35'),
(6, 'Destiny Mcintyre', 1, '726', 'qiloton@mailinator.com', '1975-10-03', 1, 2, 'zuxeq@mailinator.com', 'Rerum perspiciatis ', 'zipewakaky@mailinator.com', 40, NULL, '+1 (522) 267-8201', 'nosazedo@mailinator.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', '<p>testing</p>', '2022-03-05', '1', '0', 'admin0', '', '2022-03-05 13:18:35'),
(7, 'Molly Dillon', 1, '746', 'fobycu@mailinator.com', '2013-02-01', 2, 2, 'mequditut@mailinator.com', 'Dolorum qui incididu', 'fymeru@mailinator.com', 25, NULL, '+1 (284) 905-4381', 'gacy@mailinator.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', '<p>poping</p>', '2022-03-05', '1', '0', 'admin0', 'admin0', '2022-03-31 23:02:01'),
(8, 'Afrique', 1, 'A6226250X', '99995533', '2014-01-01', 1, 1, 'XXXXXXXXXXXXXXXX', '78900000', 'Nbi', 47, NULL, '7891234', 'kkipe15@gmail.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', '<p>cccccccccccccccc</p>', '2022-03-20', '1', '0', 'Admin0', NULL, NULL),
(9, 'Veda Mueller', 1, '307', 'morenib@mailinator.com', '1997-04-08', 1, 2, 'fizubanyh@mailinator.com', 'Expedita enim eaque ', 'juvemaq@mailinator.com', 28, NULL, '+1 (966) 806-5488', 'vuvezixip@mailinator.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', '<p>test</p>', '2022-04-03', '1', '1', 'admin0', '1', NULL),
(10, 'Colin Vaughan', 2, '677', 'ryvaro@mailinator.com', '2006-07-05', 1, 1, 'cufi@mailinator.com', 'Sit nisi commodo fu', 'dyca@mailinator.com', 41, NULL, '+1 (924) 218-9519', 'jahepem@mailinator.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', '<p>testing 233</p>', '2022-04-16', '1', '1', '1', '1', NULL),
(11, 'Brett Maynard', 1, '909', 'pywada@mailinator.com', '2004-02-22', 1, 2, 'wilajoke@mailinator.com', 'Voluptate sint facil', 'kifaw@mailinator.com', 16, NULL, '+1 (687) 823-8632', 'tedoti@mailinator.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', '<p>testing</p>', '2022-04-16', '1', '1', '1', '1', NULL);

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
-- Table structure for table `tbl_contractor_password_resets`
--

CREATE TABLE `tbl_contractor_password_resets` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contractor_payment_requests`
--

CREATE TABLE `tbl_contractor_payment_requests` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `contractor_id` int NOT NULL,
  `request_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `project_plan` int NOT NULL DEFAULT '0',
  `item_id` int NOT NULL,
  `requested_amount` double NOT NULL,
  `cod` int DEFAULT NULL,
  `cod_action_date` date DEFAULT NULL,
  `cof` int DEFAULT NULL,
  `cof_action_date` date DEFAULT NULL,
  `status` int NOT NULL,
  `stage` int NOT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_contractor_payment_requests`
--

INSERT INTO `tbl_contractor_payment_requests` (`id`, `projid`, `contractor_id`, `request_id`, `project_plan`, `item_id`, `requested_amount`, `cod`, `cod_action_date`, `cof`, `cof_action_date`, `status`, `stage`, `created_by`, `created_at`) VALUES
(1, 101, 1, 'UWAYZ101', 2, 0, 4000000, NULL, NULL, 1, '2023-10-11', 3, 5, NULL, '2023-10-07'),
(2, 69, 1, 'UWGLF69', 2, 0, 30720000, NULL, NULL, 1, '2023-10-10', 3, 5, NULL, '2023-10-07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contractor_payment_request_comments`
--

CREATE TABLE `tbl_contractor_payment_request_comments` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `stage` int NOT NULL,
  `status` int NOT NULL,
  `comments` text NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_contractor_payment_request_comments`
--

INSERT INTO `tbl_contractor_payment_request_comments` (`id`, `request_id`, `stage`, `status`, `comments`, `created_by`, `created_at`) VALUES
(1, 1, 2, 1, 'Approve request', 1, '2023-10-10'),
(2, 2, 2, 1, 'Test the tests ', 1, '2023-10-10'),
(3, 1, 2, 1, 'Test name', 1, '2023-10-10'),
(4, 2, 2, 1, 'Approve by department', 1, '2023-10-10'),
(5, 2, 3, 1, 'Comments along with them', 1, '2023-10-10'),
(6, 2, 4, 1, 'Co department comments', 1, '2023-10-10'),
(7, 2, 5, 1, 'Co finance approve ', 1, '2023-10-10'),
(8, 1, 3, 1, 'Testing the test', 1, '2023-10-11'),
(9, 1, 4, 1, 'Testing finance department', 1, '2023-10-11'),
(10, 1, 5, 1, 'Finance deopartmenyt', 1, '2023-10-11'),
(11, 1, 6, 3, 'test the name of the name ', 1, '2023-10-11'),
(12, 1, 6, 3, 'test the costline id', 1, '2023-10-11'),
(13, 1, 6, 3, 'test the quarter ', 1, '2023-10-11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contractor_payment_request_details`
--

CREATE TABLE `tbl_contractor_payment_request_details` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL,
  `site_id` int NOT NULL,
  `task_id` int NOT NULL,
  `subtask_id` int NOT NULL,
  `tender_item_id` int NOT NULL,
  `request_id` varchar(255) NOT NULL,
  `unit_cost` int NOT NULL,
  `units_no` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_contractor_payment_request_details`
--

INSERT INTO `tbl_contractor_payment_request_details` (`id`, `projid`, `output_id`, `site_id`, `task_id`, `subtask_id`, `tender_item_id`, `request_id`, `unit_cost`, `units_no`) VALUES
(1, 101, 59, 144, 112, 348, 149, 'UWAYZ101', 2000, 1000),
(2, 101, 59, 144, 113, 349, 150, 'UWAYZ101', 2000, 1000),
(3, 69, 26, 65, 53, 0, 53, 'UWGLF69', 2000000, 10),
(4, 69, 26, 65, 54, 0, 54, 'UWGLF69', 1000000, 1),
(5, 69, 25, 65, 51, 0, 55, 'UWGLF69', 1000000, 2),
(6, 69, 25, 65, 51, 0, 56, 'UWGLF69', 1000000, 3),
(7, 69, 25, 65, 52, 0, 57, 'UWGLF69', 1000, 1000),
(8, 69, 25, 65, 52, 0, 58, 'UWGLF69', 1000, 1000),
(9, 69, 25, 67, 51, 0, 65, 'UWGLF69', 5000, 10),
(10, 69, 25, 67, 51, 0, 66, 'UWGLF69', 5000, 10),
(11, 69, 25, 67, 52, 0, 67, 'UWGLF69', 500, 1000),
(12, 69, 25, 67, 52, 0, 68, 'UWGLF69', 500, 1000),
(13, 69, 25, 66, 51, 0, 89, 'UWGLF69', 800, 1000),
(14, 69, 25, 66, 51, 0, 90, 'UWGLF69', 800, 1000),
(15, 69, 25, 66, 52, 0, 91, 'UWGLF69', 1000, 10),
(16, 69, 25, 66, 52, 0, 92, 'UWGLF69', 1000, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contract_guarantees`
--

CREATE TABLE `tbl_contract_guarantees` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `guarantee` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `duration` int NOT NULL,
  `notification` int NOT NULL,
  `date_created` date NOT NULL,
  `created_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_contract_guarantees`
--

INSERT INTO `tbl_contract_guarantees` (`id`, `projid`, `guarantee`, `start_date`, `duration`, `notification`, `date_created`, `created_by`) VALUES
(1, 2, 'Performance Security', '2023-10-01', 90, 15, '2023-09-21', 118),
(2, 2, 'Performance Bond', '2023-10-01', 30, 7, '2023-09-21', 118),
(3, 2, 'WIBA insurance', '2023-10-01', 365, 30, '2023-09-21', 118),
(4, 2, 'Advanced Guarantee', '2023-10-01', 60, 15, '2023-09-21', 118),
(5, 2, 'Defect Liability', '2023-10-01', 365, 30, '2023-09-21', 118),
(6, 2, 'Tester one', '2023-10-02', 500, 30, '2023-09-21', 118),
(7, 2, 'Test ABC', '2023-09-01', 45, 15, '2023-09-23', 118);

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
(2, 'Weekly', '7 days', 2, 1, NULL, NULL, '10', '2022-02-17 12:06:35'),
(3, 'Monthly', '1 month', 3, 1, NULL, NULL, NULL, '2018-08-01 15:23:30'),
(4, 'Quarterly', '3 months', 4, 1, NULL, NULL, NULL, '2018-08-01 15:23:30'),
(5, 'Semi-Annually', '6 months', 5, 1, NULL, NULL, '10', '2022-02-17 12:05:45'),
(6, 'Yearly', '1 year', 6, 1, NULL, NULL, '10', '2022-02-17 12:05:56'),
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
-- Table structure for table `tbl_designation_permissions`
--

CREATE TABLE `tbl_designation_permissions` (
  `id` int NOT NULL,
  `designation_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_designation_permissions`
--

INSERT INTO `tbl_designation_permissions` (`id`, `designation_id`, `permission_id`, `created_by`, `created_at`) VALUES
(19, 3, 2, 1, '2023-03-29'),
(20, 4, 2, 1, '2023-03-29'),
(21, 5, 1, 1, '2023-03-29'),
(22, 5, 2, 1, '2023-03-29'),
(23, 5, 3, 1, '2023-03-29'),
(24, 5, 4, 1, '2023-03-29'),
(25, 6, 1, 1, '2023-03-29'),
(26, 6, 2, 1, '2023-03-29'),
(27, 6, 3, 1, '2023-03-29'),
(28, 6, 4, 1, '2023-03-29'),
(29, 6, 5, 1, '2023-03-29'),
(30, 6, 6, 1, '2023-03-29'),
(31, 6, 7, 1, '2023-03-29'),
(32, 6, 8, 1, '2023-03-29'),
(33, 6, 9, 1, '2023-03-29'),
(47, 8, 1, 1, '2023-03-29'),
(48, 8, 2, 1, '2023-03-29'),
(49, 8, 3, 1, '2023-03-29'),
(50, 8, 4, 1, '2023-03-29'),
(51, 8, 5, 1, '2023-03-29'),
(52, 8, 6, 1, '2023-03-29'),
(53, 8, 7, 1, '2023-03-29'),
(54, 8, 8, 1, '2023-03-29'),
(55, 8, 9, 1, '2023-03-29'),
(56, 9, 1, 1, '2023-03-29'),
(57, 9, 2, 1, '2023-03-29'),
(58, 9, 3, 1, '2023-03-29'),
(59, 9, 4, 1, '2023-03-29'),
(60, 9, 5, 1, '2023-03-29'),
(61, 9, 6, 1, '2023-03-29'),
(62, 9, 7, 1, '2023-03-29'),
(63, 9, 8, 1, '2023-03-29'),
(64, 9, 9, 1, '2023-03-29'),
(65, 10, 2, 1, '2023-03-29'),
(66, 10, 4, 1, '2023-03-29'),
(67, 10, 5, 1, '2023-03-29'),
(68, 10, 7, 1, '2023-03-29'),
(69, 10, 8, 1, '2023-03-29'),
(70, 10, 9, 1, '2023-03-29'),
(71, 11, 1, 1, '2023-03-29'),
(72, 11, 2, 1, '2023-03-29'),
(73, 11, 3, 1, '2023-03-29'),
(74, 11, 4, 1, '2023-03-29'),
(75, 11, 5, 1, '2023-03-29'),
(76, 11, 7, 1, '2023-03-29'),
(77, 11, 8, 1, '2023-03-29'),
(78, 11, 9, 1, '2023-03-29'),
(79, 14, 1, 1, '2023-03-29'),
(80, 14, 2, 1, '2023-03-29'),
(81, 14, 3, 1, '2023-03-29'),
(82, 14, 5, 1, '2023-03-29'),
(83, 14, 6, 1, '2023-03-29'),
(84, 13, 1, 1, '2023-03-29'),
(85, 13, 2, 1, '2023-03-29'),
(86, 13, 3, 1, '2023-03-29'),
(87, 13, 5, 1, '2023-03-29'),
(88, 13, 6, 1, '2023-03-29'),
(89, 12, 1, 1, '2023-03-29'),
(90, 12, 2, 1, '2023-03-29'),
(91, 12, 3, 1, '2023-03-29'),
(101, 2, 2, 1, '2023-04-01'),
(127, 1, 1, 1, '2023-04-20'),
(128, 1, 2, 1, '2023-04-20'),
(129, 1, 3, 1, '2023-04-20'),
(130, 1, 4, 1, '2023-04-20'),
(131, 1, 5, 1, '2023-04-20'),
(132, 1, 6, 1, '2023-04-20'),
(133, 1, 7, 1, '2023-04-20'),
(134, 1, 8, 1, '2023-04-20'),
(135, 1, 9, 1, '2023-04-20'),
(136, 1, 10, 1, '2023-04-20'),
(137, 1, 11, 1, '2023-04-20'),
(138, 1, 12, 1, '2023-04-20'),
(139, 1, 13, 1, '2023-04-20'),
(140, 1, 14, 1, '2023-04-20'),
(141, 1, 15, 1, '2023-04-20'),
(142, 1, 16, 1, '2023-04-20'),
(154, 7, 1, 1, '2023-05-30'),
(155, 7, 2, 1, '2023-05-30'),
(156, 7, 3, 1, '2023-05-30'),
(157, 7, 4, 1, '2023-05-30'),
(158, 7, 5, 1, '2023-05-30'),
(159, 7, 6, 1, '2023-05-30'),
(160, 7, 7, 1, '2023-05-30'),
(161, 7, 8, 1, '2023-05-30'),
(162, 7, 9, 1, '2023-05-30'),
(163, 7, 10, 1, '2023-05-30'),
(164, 7, 11, 1, '2023-05-30'),
(165, 7, 12, 1, '2023-05-30'),
(166, 7, 13, 1, '2023-05-30'),
(167, 7, 14, 1, '2023-05-30'),
(168, 7, 15, 1, '2023-05-30'),
(169, 7, 16, 1, '2023-05-30');

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
  `stage` int DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` int NOT NULL,
  `createdBy` int NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_email_templates`
--

INSERT INTO `tbl_email_templates` (`id`, `title`, `stage`, `content`, `type`, `createdBy`, `active`, `dateCreated`) VALUES
(1, 'User Welcome', 1, 'Hola [FIRST_NAME], <br>welcome to [SITE_NAME]. Please use the below details to log in.<br><br>Email: [EMAIL]<br>Passcode: [PASSWORD]', 1, 1, 1, '2022-09-24'),
(2, 'Welcome Template', NULL, 'Hello [FIRST_NAME], \r\n<br>\r\nTest test  [SERVICE_NUMBER] was successfully done by [FIRST_NAME] [LAST_NAME] on date [DATE].\r\n<br>\r\n<br>\r\n<br>', 0, 1, 1, '2022-09-24'),
(3, 'Event Received Template', NULL, 'Hello [FIRST_NAME], <br><br>We would like to notify you that you have been assign do project mapping as per the below details  <br><br> Project name: [PROJECT_NAME]<br>Project Location: [PROJECT_LOCATION]<br>Project Output Name: [PROJECT_OUTPUT].', 0, 1, 1, '2022-09-24'),
(4, 'Others: Test Template', NULL, 'Hello [FIRST_NAME], \r\n<br>\r\nOther tests  [PROJECT_NUMBER] was successfully done by [USERNAME] on date [DATE].\r\n<br>\r\n<br>\r\n<br>', 0, 1, 0, '2022-09-24'),
(5, 'Test Template', 11, 'Testing [SITE_URL] [SITE_NAME] [FIRST_NAME] [LAST_NAME] [ADDRESS] [CITY] [MOBILE_NUMBER] [EMAIL] [PASSWORD] <br><br><br><br><br><br><br><br><br><br>', 0, 1, 1, '2022-09-24');

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
  `status` int NOT NULL DEFAULT '0' COMMENT '0 is pending; 1 is rejected; 2 is approved',
  `created_by` int NOT NULL,
  `created_on` date NOT NULL,
  `authorized_by` int DEFAULT NULL,
  `authorized_on` date DEFAULT NULL,
  `modifiedby` varchar(100) DEFAULT NULL,
  `date_modified` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(7, 'issue', 1, 55, 17, 'testing', '1', '2022-04-04', '2022-04-04', NULL, '2022-04-04', 6),
(8, 'issue', 120, 7, 2, 'testing', '120', '2022-07-26', '2022-07-27', NULL, '2022-08-01', 6),
(9, 'issue', 130, 7, 3, 'TEST', '120', '2022-07-26', NULL, NULL, NULL, 1),
(10, 'issue', 130, 7, 4, 'T', '120', '2022-07-26', NULL, NULL, NULL, 1),
(11, 'issue', 131, 24, 7, 'Deal', '121', '2022-08-29', NULL, NULL, '2022-08-29', 1),
(12, 'issue', 131, 24, 8, 'Kindly deal...', '121', '2022-08-29', '2022-08-29', NULL, '2022-08-31', 6),
(14, 'issue', 131, 24, 9, 'Testing', '118', '2022-09-02', '2022-09-17', NULL, '2022-09-17', 6),
(17, 'issue', 131, 24, 14, 'Testing', '121', '2022-09-16', '2022-09-16', NULL, '2022-09-17', 6),
(18, 'issue', 131, 24, 13, 'Testing', '118', '2022-09-20', NULL, NULL, '2022-09-20', 1),
(19, 'issue', 131, 24, 10, 'Test', '121', '2022-09-20', '2022-09-10', NULL, NULL, 1),
(20, 'issue', 131, 70, 20, 'Kindly advise', '121', '2022-10-07', NULL, NULL, NULL, 1);

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
  `opid` int DEFAULT NULL,
  `design_id` int DEFAULT NULL,
  `site_id` int DEFAULT NULL,
  `state_id` int NOT NULL DEFAULT '0',
  `task_id` int DEFAULT NULL,
  `parameter_id` int DEFAULT NULL,
  `specification_id` int DEFAULT NULL,
  `form_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `inspection_id` int NOT NULL DEFAULT '0',
  `projstage` int NOT NULL,
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

INSERT INTO `tbl_files` (`fid`, `projid`, `opid`, `design_id`, `site_id`, `state_id`, `task_id`, `parameter_id`, `specification_id`, `form_id`, `inspection_id`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES
(1, 1, 1, 1, 0, 377, NULL, NULL, NULL, '2023-03-17', 0, 7, '17-03-2023-1-monitoring checklist-1679057226-D-ruto sign.jpg', 'jpg', 'uploads/monitoring/photos/17-03-2023-1-monitoring checklist-1679057226-D-ruto sign.jpg', 'monitoring checklist', 'TEST', '128', '2023-03-17'),
(2, 1, 1, 1, 0, 377, 2, NULL, NULL, '2023-03-17', 0, 1, '17-03-2023-1-monitoring checklist-1679057408-DRUTO STMP.jpg', 'jpg', 'uploads/monitoring/photos/17-03-2023-1-monitoring checklist-1679057408-DRUTO STMP.jpg', 'monitoring checklist', '', '118', '2023-03-17'),
(3, 3, 5, 4, 6, 0, 7, 12, 9, '2023-03-21', 2, 10, '21-03-2023-3-Inspection-1679387181-Toilet.png', 'png', 'uploads/inspection/photos/21-03-2023-3-Inspection-1679387181-Toilet.png', 'Inspection', 'test', '118', '2023-03-21'),
(4, 3, 5, 4, 6, 0, 7, NULL, NULL, '2023-03-21', 0, 1, '21-03-2023-3-monitoring checklist-1679387634-prow2.png', 'png', 'uploads/monitoring/photos/21-03-2023-3-monitoring checklist-1679387634-prow2.png', 'monitoring checklist', 'pr', '118', '2023-03-21'),
(5, 4, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 'evans-koech-3----cv.pdf', 'pdf', '../../uploads/main-project/1679481318_4_1_evans-koech-3----cv.pdf', 'Project Planning', '666', '1', '2023-03-22'),
(6, 70, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 'IMG-20230329-WA0007 (1).jpg', 'jpg', '../../uploads/main-project/1685450440_70_1_IMG-20230329-WA0007 (1).jpg', 'Project Planning', 'testing attachment at creation of project', '124', '2023-05-30'),
(7, 77, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 'DRUTO STMP.jpg', 'jpg', '../../uploads/main-project/1690990976_77_1_DRUTO STMP.jpg', 'Project Planning', 'testing upload', '118', '2023-08-02'),
(8, 78, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 'FINAL OMOSH.jpg', 'jpg', '../../uploads/main-project/1691061796_78_1_FINAL OMOSH.jpg', 'Project Planning', 'creatrio', '118', '2023-08-03'),
(9, 81, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 'Tracer_Study_Kenya_Web.pdf', 'pdf', '../../uploads/main-project/1691418975_81_1_Tracer_Study_Kenya_Web.pdf', 'Project Planning', 'Test', '118', '2023-08-07'),
(10, 81, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, '1689746334223-Tender document for provision of tracking system for Corporation Motor vehicle services.pdf', 'pdf', '../../uploads/main-project/1691418975_81_1_1689746334223-Tender document for provision of tracking system for Corporation Motor vehicle services.pdf', 'Project Planning', 'Testing', '118', '2023-08-07'),
(11, 81, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 'Tracer_Study_Kenya_Web.pdf', 'pdf', '../../uploads/main-project/1691418982_81_1_Tracer_Study_Kenya_Web.pdf', 'Project Planning', 'Test', '118', '2023-08-07'),
(12, 81, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, '1689746334223-Tender document for provision of tracking system for Corporation Motor vehicle services.pdf', 'pdf', '../../uploads/main-project/1691418982_81_1_1689746334223-Tender document for provision of tracking system for Corporation Motor vehicle services.pdf', 'Project Planning', 'Testing', '118', '2023-08-07'),
(13, 94, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 'IMG_0046.JPG', 'JPG', '../../uploads/main-project/1692478642_94_1_IMG_0046.JPG', 'Project Planning', 'Testing if it works ', '1', '2023-08-19'),
(14, 96, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 4, '1693987323-0-4-CONSTRUCTION-OF-WATER-WORKS-AT-CHEPKOILEL-WATER-PROJECT.pdf', 'pdf', 'uploads/procurement/1693987323-0-4-CONSTRUCTION-OF-WATER-WORKS-AT-CHEPKOILEL-WATER-PROJECT.pdf', '0', 'TESTUPLOAD AT PROCUREMENT', '118', '2023-09-06'),
(15, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 19, '19-ROUTERS, SWITCHES AND HUBS.pptx', 'pptx', 'uploads/financiers/19-ROUTERS, SWITCHES AND HUBS.pptx', 'Funding', 'Vel modi nobis sed r', '118', '2023-09-21'),
(16, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 20, '20-ROUTERS, SWITCHES AND HUBS.pptx', 'pptx', 'uploads/financiers/20-ROUTERS, SWITCHES AND HUBS.pptx', 'Funding', 'Ut nostrud odit comm', '118', '2023-09-21'),
(17, 2, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 4, '1695308275-0-4-hotel-booking-website.png', 'png', 'uploads/procurement/1695308275-0-4-hotel-booking-website.png', '0', 'Tester', '118', '2023-09-21'),
(18, 2, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 4, '1695308581-0-4-Modern-Architecture-Studio-Website-Design.png', 'png', 'uploads/procurement/1695308581-0-4-Modern-Architecture-Studio-Website-Design.png', '0', 'ABC', '118', '2023-09-21'),
(19, 78, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-09-25', 0, 1, '25-09-2023-78-monitoring checklist-1695648852-Successful-tender-award-letter-template.pdf', 'pdf', 'uploads/monitoring/other-files/25-09-2023-78-monitoring checklist-1695648852-Successful-tender-award-letter-template.pdf', 'monitoring checklist', 'Tester ABC', '118', '2023-09-25'),
(20, 105, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 'IMG-20230924-WA0004.jpg', 'jpg', '../../uploads/main-project/1695890580_105_1_IMG-20230924-WA0004.jpg', 'Project Planning', 'test', '118', '2023-09-28'),
(21, 105, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 'IMG-20230924-WA0004.jpg', 'jpg', '../../uploads/main-project/1695890586_105_1_IMG-20230924-WA0004.jpg', 'Project Planning', 'test', '118', '2023-09-28'),
(22, 105, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 'IMG-20230924-WA0004.jpg', 'jpg', '../../uploads/main-project/1695890588_105_1_IMG-20230924-WA0004.jpg', 'Project Planning', 'test', '118', '2023-09-28'),
(23, 109, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 4, '1695983637-0-4-WORK PROGRAMME (3).pdf', 'pdf', 'uploads/procurement/1695983637-0-4-WORK PROGRAMME (3).pdf', '0', 'File testing at procurement', '118', '2023-09-29'),
(24, 111, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, '10 Bags_Kensil_March 2023 (1) (2).pdf', 'pdf', '../../uploads/main-project/1696340491_111_1_10 Bags_Kensil_March 2023 (1) (2).pdf', 'Project Planning', 'testing at project creation', '118', '2023-10-03'),
(25, 101, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, '378948292_6634977183251109_8639244104715301120_n.jpg', 'jpg', 'uploads/payments/1697077944_101_1_378948292_6634977183251109_8639244104715301120_n.jpg', 'Project Inspection', 'Sed est eos quas vol', '1', '2023-10-12'),
(26, 101, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, '378948292_6634977183251109_8639244104715301120_n.jpg', 'jpg', 'uploads/payments/1697078011_101_1_378948292_6634977183251109_8639244104715301120_n.jpg', 'Project Inspection', 'Sed est eos quas vol', '1', '2023-10-12'),
(27, 84, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, '361078041_6077519649024240_5762839329424038319_n.jpg', 'jpg', 'uploads/payments/1697083419_84_1_361078041_6077519649024240_5762839329424038319_n.jpg', 'Project Observations', 'test', '1', '2023-10-12'),
(28, 84, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, '378948292_6634977183251109_8639244104715301120_n.jpg', 'jpg', 'uploads/payments/1697083419_84_1_378948292_6634977183251109_8639244104715301120_n.jpg', 'Project Observations', 'test', '1', '2023-10-12'),
(29, 84, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, '378948292_6634977183251109_8639244104715301120_n.jpg', 'jpg', 'uploads/payments/1697083468_84_1_378948292_6634977183251109_8639244104715301120_n.jpg', 'Project Observations', 'testing the test', '1', '2023-10-12');

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
(1, 'Exchequer', 2, 'Keino P', 2, 'Director', '223344 Eldoret', 'Eldoret', 'Rift Valley', 100, '0720941928', 'kkipe15@gmail.com', '<p>Share the way that she is&nbsp;</p>', NULL, NULL, 1, '2022-04-27', 'Admin0', '2022-04-26', '1', '2022-04-26 00:00:00'),
(2, 'County Government of Uasin Gishu', 1, 'Kibet Leonard', 4, 'Manager', '5566 Eldoret', 'Eldoret', 'Rift Valley', 100, '0733467811', 'kkipe15@gmail.com', '<p>Revenue collection</p>', NULL, NULL, 1, NULL, 'Admin0', '2022-01-16', NULL, NULL),
(3, 'World Bank', 3, 'Pattni', 2, 'Economist', 'P.O Box 6734-00200 Nairobi', 'Nairobi', 'Nairobi', 100, '733467811', 'pkorir59@gmail.com', '<p>xxxxxxxxxxxxxxxxxx</p>', NULL, NULL, 1, NULL, 'Admin0', '2022-01-16', NULL, NULL),
(4, 'ABC', 1, 'ABC Person', 4, 'Manager', '1234', 'Nairobi', 'Nairobi', 72, '020383838838', 'abc@gmail.com', 'test', NULL, NULL, 0, '2022-11-18', 'admin0', '2022-02-22', '118', '2022-11-19 00:00:00'),
(5, 'African Development Bank', 3, 'Hopsman Staunch', 2, 'Chief Partnership Manager', 'P.O Box 8900 Nairobi', 'Nairobi', 'Nairobi', 100, '254 111222333', 'hs123@gmail.com', '<p>African Development Bank</p>', NULL, NULL, 1, NULL, '1', '2022-03-05', NULL, NULL),
(6, 'Kenya Red Cross Society', 4, 'Remmy Oundo', 2, 'Regional Coordinator', '9000 Nbi', 'Eldoret', 'Eldoret', 100, '0733467811', 'pkorir59@gmail.com', '<p>Testing</p>', NULL, NULL, 1, NULL, '1', '2022-03-20', NULL, NULL),
(7, 'USAid', 4, 'Kimathi Dick', 4, 'Partnership Manager', '1234 Nairobi', 'Nairobi', 'Nairobi', 100, '2349002', 'p.usaid@gmail.com', '<p>Courtesy of the US government</p>', NULL, NULL, 1, NULL, '1', '2022-03-22', NULL, NULL),
(8, 'UKAid', 4, 'Lucas Wood', 4, 'Partnership Coordinator', 'London', 'London', 'London', 208, '+1 (843) 202-1759', 'baxavanic@mailinator.com', '<p>testing</p>', NULL, NULL, 1, NULL, '34', '2022-10-27', '120', NULL),
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

--
-- Dumping data for table `tbl_financier_status_comments`
--

INSERT INTO `tbl_financier_status_comments` (`id`, `fnid`, `comments`, `statusdate`, `created_by`, `date_created`) VALUES
(1, 1, 'Share this as it is ', '2022-04-27', '1', '2022-04-26'),
(2, 1, 'Share the way it is', '2022-04-27', '1', '2022-04-26'),
(3, 4, 'test', '2022-11-18', '118', '2022-11-19');

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
(5, 2, '2345', 5, 100000000000, 1, 1, '2022-08-31', 'Projects funding', 0, 0, '0', 'admin0', '2022-02-22', NULL, NULL),
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
(17, 10, 'Voluptas vero cupida', 4, 77, 4, 77, '2022-04-16', '59', 0, 0, '0', '1', '2022-04-17', NULL, NULL),
(18, 1, 'Id ipsa proident u', 5, 600, 1, 11, '2022-04-25', 'testing', 0, 0, '0', '1', '2022-04-26', NULL, NULL),
(19, 3, 'Optio velit dolore', 7, 2000000, 6, 0, '1970-01-01', 'Lorem quo ea ut unde', 0, 0, '0', '118', '2023-09-21', NULL, NULL),
(20, 5, 'Sequi asperiores ven', 7, 2000, 1, 0, '2023-09-09', 'Culpa officia volup', 0, 0, '0', '118', '2023-09-21', NULL, NULL);

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
  `inspectionid` varchar(20) NOT NULL,
  `task_id` int NOT NULL DEFAULT '0',
  `location` int NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `observations` text NOT NULL,
  `origin` int NOT NULL COMMENT '1:general, 2:inspection',
  `created_at` date NOT NULL,
  `created_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(10, 3, 19, 21, 2022, 25, 25, 25, 25, 121, '2023-03-18'),
(11, 3, 21, 12, 2022, 50, 50, 50, 50, 121, '2023-03-18'),
(36, 1, 3, 2, 2022, 64, 23, 7, 74, 1, '2023-04-19'),
(37, 1, 3, 2, 2023, 7, 66, 60, 850, 1, '2023-04-19'),
(38, 1, 5, 8, 2022, 8, 78, 55, 75, 1, '2023-04-19'),
(39, 1, 5, 8, 2023, 74, 78, 6, 660, 1, '2023-04-19'),
(40, 2, 9, 7, 2022, 1, 1, 1, 1, 118, '2023-05-31'),
(41, 2, 9, 7, 2023, 1, 1, 1, 1, 118, '2023-05-31'),
(42, 2, 11, 9, 2022, 1, 1, 1, 1, 118, '2023-05-31'),
(43, 2, 11, 9, 2023, 1, 1, 1, 1, 118, '2023-05-31'),
(44, 2, 13, 10, 2022, 5, 5, 5, 5, 118, '2023-05-31'),
(45, 2, 13, 10, 2023, 5, 5, 5, 5, 118, '2023-05-31'),
(46, 2, 15, 12, 2022, 20, 20, 20, 20, 118, '2023-05-31'),
(47, 2, 15, 12, 2023, 20, 20, 20, 20, 118, '2023-05-31'),
(48, 2, 17, 18, 2022, 20, 20, 20, 20, 118, '2023-05-31'),
(49, 2, 17, 18, 2023, 20, 20, 20, 20, 118, '2023-05-31'),
(50, 10, 64, 4, 2022, 13, 13, 12, 12, 118, '2023-06-10'),
(51, 11, 68, 25, 2023, 65, 65, 60, 60, 118, '2023-08-03'),
(52, 11, 68, 25, 2024, 65, 65, 60, 60, 118, '2023-08-03'),
(53, 12, 69, 26, 2023, 0, 0, 0, 1, 118, '2023-08-09'),
(54, 12, 69, 26, 2024, 0, 0, 0, 1, 118, '2023-08-09'),
(55, 12, 71, 27, 2023, 0, 0, 0, 1, 118, '2023-08-09'),
(56, 12, 71, 27, 2024, 0, 0, 0, 1, 118, '2023-08-09'),
(57, 12, 73, 28, 2023, 0, 0, 0, 1, 118, '2023-08-09'),
(58, 12, 73, 28, 2024, 0, 0, 0, 1, 118, '2023-08-09'),
(59, 9, 62, 1, 2023, 30, 30, 30, 30, 118, '2023-09-23'),
(60, 9, 63, 8, 2023, 2, 3, 3, 3, 118, '2023-09-23'),
(65, 18, 87, 34, 2023, 25, 25, 25, 25, 118, '2023-10-10'),
(66, 18, 87, 34, 2024, 25, 25, 25, 25, 118, '2023-10-10');

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
  `indicator_beneficiary` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `indicator_baseline_level` int NOT NULL DEFAULT '2',
  `indicator_mapping_type` int NOT NULL DEFAULT '0' COMMENT '1 is One-Point; 2 is Way-Point; 3 is Area',
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

INSERT INTO `tbl_indicator` (`indid`, `indicator_code`, `indicator_name`, `indicator_description`, `indicator_type`, `indicator_category`, `indicator_disaggregation`, `indicator_calculation_method`, `indicator_unit`, `indicator_direction`, `indicator_aggregation`, `indicator_sector`, `indicator_dept`, `indicator_data_source`, `indicator_beneficiary`, `indicator_baseline_level`, `indicator_mapping_type`, `baseline`, `active`, `user_name`, `date_entered`, `updated_by`, `date_updated`) VALUES
(1, 'RD0001', 'Road Tarmacked ', '', 2, 'Output', 0, NULL, 16, NULL, NULL, 15, 19, NULL, NULL, 2, 2, 1, '1', '128', '2023-03-14', NULL, NULL),
(2, 'RD0002', 'Road graded', '', 2, 'Output', 0, NULL, 16, NULL, NULL, 15, 19, NULL, NULL, 2, 2, 1, '1', '118', '2023-03-14', NULL, NULL),
(3, 'RD0003', 'Road Gravelled ', '', 2, 'Output', 0, NULL, 16, NULL, NULL, 15, 19, NULL, NULL, 2, 2, 1, '1', '118', '2023-03-14', NULL, NULL),
(4, 'WTR001', 'Boreholes drilled', '<p>Count the number of boreholes drilled and operationalised</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, NULL, 2, 1, 1, '1', '118', '2023-03-14', '118', '2023-03-14'),
(5, 'RD0004', 'Road Maintained ', '', 2, 'Output', 0, NULL, 16, NULL, NULL, 15, 19, NULL, NULL, 2, 2, 0, '1', '118', '2023-03-14', NULL, NULL),
(6, 'RD0006', 'drainage system rehabilitated (unblocked and strengthened)', '', 2, 'Output', 0, NULL, 16, NULL, NULL, 15, 19, NULL, NULL, 2, 2, 0, '1', '118', '2023-03-14', NULL, NULL),
(7, 'WTR002', 'Dams constructed', '<p>Count the number of dams completed and operational</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, NULL, 2, 1, 1, '1', '118', '2023-03-14', NULL, NULL),
(8, 'RD0007', 'Bridges Constructed', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, NULL, 2, 1, 1, '1', '118', '2023-03-14', NULL, NULL),
(9, 'WTR003', 'Treatment Plant Constructed', '<p>Count the number of treatment plants completed and operational</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, NULL, 2, 1, 1, '1', '118', '2023-03-14', NULL, NULL),
(10, 'WTR005', 'Water Storage Tanks Constructed', '<p>Count the number of storage tanks completed and functional</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, NULL, 2, 1, 1, '1', '118', '2023-03-14', '118', '2023-03-14'),
(11, 'RD0009', 'Asphalt construction plant constructed ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, NULL, 2, 2, 0, '1', '118', '2023-03-14', NULL, NULL),
(12, 'WTR006', 'Water Pipeline Laid', '<p>Measure the number of km laid</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, NULL, 2, 2, 1, '1', '118', '2023-03-14', NULL, NULL),
(13, 'RD00010', 'Boda Boda Shades Constructed ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, NULL, 2, 1, 0, '1', '118', '2023-03-14', NULL, NULL),
(14, 'RD00011', 'bus bays rehabilitated/constructed  ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, NULL, 2, 1, 0, '1', '118', '2023-03-14', NULL, NULL),
(15, 'RD00012', 'Traffic signal infrastructure established', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, NULL, 2, 1, 0, '1', '118', '2023-03-14', NULL, NULL),
(16, 'RD00013', 'new street light lamps installed ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, NULL, 2, 1, 0, '1', '118', '2023-03-14', NULL, NULL),
(17, 'RD00015', 'Government Offices rehabilitated and maintained ', '', 2, 'Output', 0, NULL, 19, NULL, NULL, 15, 19, NULL, NULL, 2, 1, 0, '1', '118', '2023-03-14', NULL, NULL),
(18, 'WTR010', 'Sewerline laid', '<p>Test</p>', 2, 'Output', 0, NULL, 16, NULL, NULL, 9, 23, NULL, NULL, 1, 2, 1, '1', '118', '2023-03-15', NULL, NULL),
(19, 'IMP001', 'Number of positive tests of water-borne diseases', '<p>Positive tests of water-borne diseases</p>', 2, 'Impact', 1, 3, 19, 2, NULL, 9, 23, 0, 'Households accessing water from the Storage Tank', 2, 0, 0, '1', '118', '2023-03-15', NULL, NULL),
(20, 'OUT001', 'Volume of water', '<p>Volume of water in cubic meters</p>', 2, 'Outcome', 0, 1, 13, 1, NULL, 9, 23, 0, 'Households living around the project', 2, 0, 0, '1', '118', '2023-03-15', NULL, NULL),
(21, 'WTR011', 'Water Kiosks Constructed', '<p>Count the number of kiosks completed and functional</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, NULL, 1, 1, 1, '1', '118', '2023-03-17', '118', '2023-03-17'),
(22, 'IMP002', 'Percentage of households accessing clean water', '', 2, 'Impact', 0, 2, 19, 1, NULL, 9, 23, 0, 'Targeted Population/Households', 2, 0, 0, '1', '118', '2023-03-18', NULL, NULL),
(23, 'IMP004', 'Average income (Ksh)', '<p>Average income (Ksh)</p>', 2, 'Impact', 0, 3, 54, 1, NULL, 9, 23, 0, 'Tageted population benefitting from the project', 2, 0, 0, '1', '118', '2023-03-21', NULL, NULL),
(24, 'OUT004', 'Number of people employed', '', 2, 'Outcome', 1, 1, 19, 1, NULL, 9, 23, 0, 'Targeted Population employed as a result of the project', 2, 0, 0, '1', '118', '2023-03-21', NULL, NULL),
(25, 'AG1234', 'farmers trained on new farming methods', '<p>Testing</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 1, 2, NULL, NULL, 1, 0, 1, '1', '118', '2023-08-03', NULL, NULL),
(26, '0006', 'Solid Waste Incinerator', '<p>Testing</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, NULL, 1, 1, 1, '1', '118', '2023-08-09', NULL, NULL),
(27, '009', 'Anaerobic Reactor', '<p>Testing</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, NULL, 1, 1, 1, '1', '118', '2023-08-09', NULL, NULL),
(28, '010', 'Vertical Flow Wetlands Constructed					', '<p>Testing</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, NULL, 1, 1, 1, '1', '118', '2023-08-09', NULL, NULL),
(29, 'ICN671', 'Immunization Centers', '<p>measure the number of immunization centers consutructed</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 12, 24, NULL, NULL, 1, 1, 0, '1', '118', '2023-08-13', NULL, NULL),
(30, 'BINS271', 'Bins Distributed', '<p>Indicate the number of dustbin distributed</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, NULL, 1, 1, 1, '1', '118', '2023-08-14', '118', '2023-08-14'),
(31, 'RD002', 'Level of access to road infrastructure', '<p>test</p>', 2, 'Outcome', 0, 2, 77, 1, NULL, 15, 19, 0, 'Road Users', 2, 0, 0, '1', '118', '2023-09-30', NULL, NULL),
(32, '009/R/989', 'Economically Empowered Society ', '<p>economically empowered society as a result of good road infrastructure</p>', 2, 'Impact', 0, 2, 77, 1, NULL, 15, 19, 0, 'Entire Populace', 2, 0, 0, '1', '118', '2023-09-30', NULL, NULL),
(33, 'R001', 'Research Studies Conducted', '<p>Research</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 9, 23, NULL, NULL, 1, 0, 0, '1', '118', '2023-10-07', NULL, NULL),
(34, 'E002', 'Classrooms Constructed', '<p>Classrooms Completed and ready for occupation</p>', 2, 'Output', 0, NULL, 19, NULL, NULL, 5, 6, NULL, NULL, 2, 1, 1, '1', '118', '2023-10-10', NULL, NULL);

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
(3, 2, 355, NULL, 'kkipe15@gmail.com', '118', '2023-03-18'),
(6, 5, 321, NULL, 'denkytheka@gmail.com', '118', '2023-10-03'),
(7, 5, 331, NULL, 'projtracsystemsltd@gmail.com', '118', '2023-10-03'),
(12, 8, 321, NULL, 'denkytheka@gmail.com', '118', '2023-10-04'),
(13, 8, 331, NULL, 'projtracsystemsltd@gmail.com', '118', '2023-10-04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_indicator_baseline_survey_forms`
--

CREATE TABLE `tbl_indicator_baseline_survey_forms` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `resultstype` int NOT NULL,
  `resultstypeid` int NOT NULL,
  `indid` int NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `responsible` int DEFAULT NULL,
  `respondent_description` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
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

INSERT INTO `tbl_indicator_baseline_survey_forms` (`id`, `projid`, `resultstype`, `resultstypeid`, `indid`, `form_name`, `responsible`, `respondent_description`, `enumerator_type`, `status`, `sample_size`, `startdate`, `enddate`, `created_by`, `date_created`, `date_deployed`, `closed_by`, `date_closed`, `form_type`, `type`) VALUES
(2, 7, 1, 2, 22, 'Baseline', NULL, 'Targeted Households accessing clean water', 2, 3, 3, '2023-03-18', '2023-03-18', '118', '2023-03-18', '2023-03-18', NULL, NULL, NULL, NULL),
(5, 109, 2, 26, 31, 'Baseline', NULL, 'Test', 2, 3, 5, '2023-10-03', '2023-10-07', '118', '2023-10-03', '2023-10-03', NULL, NULL, NULL, NULL),
(8, 109, 1, 11, 32, 'Baseline', NULL, 'Tester Impact', 2, 3, 3, '2023-10-04', '2023-10-07', '118', '2023-10-04', '2023-10-04', NULL, NULL, NULL, NULL),
(10, 3, 2, 1, 20, 'Baseline', NULL, 'Tester No.1', 2, 1, 2, '2023-10-04', '2023-10-07', '118', '2023-10-04', NULL, NULL, NULL, NULL, NULL),
(11, 2, 2, 6, 24, 'Baseline', NULL, 'ABC Test', 1, 1, 5, '2023-10-04', '2023-10-07', '118', '2023-10-04', NULL, NULL, NULL, NULL, NULL);

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
(1, 7, NULL, 4, '2023-03-14'),
(2, 9, NULL, 2, '2023-03-14'),
(3, 12, NULL, 2, '2023-03-14'),
(4, 10, NULL, 5, '2023-03-14'),
(5, 1, NULL, 2, '2023-03-14'),
(6, 2, NULL, 2, '2023-03-14'),
(7, 8, NULL, 2, '2023-03-14'),
(8, 4, NULL, 5, '2023-03-15'),
(9, 18, NULL, 4, '2023-03-15'),
(10, 21, NULL, 5, '2023-03-17'),
(11, 25, NULL, 3, '2023-08-03'),
(12, 26, NULL, 5, '2023-08-09'),
(13, 27, NULL, 5, '2023-08-09'),
(14, 28, NULL, 5, '2023-08-09'),
(15, 30, NULL, 1, '2023-08-14'),
(16, 3, NULL, 1, '2023-10-03'),
(17, 34, NULL, 5, '2023-10-10');

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
(1, 19, 1, NULL, 'Cholera'),
(2, 19, 1, NULL, ' Diarrhea'),
(3, 19, 1, NULL, ' Typhoid'),
(4, 19, 1, NULL, ' Dysentery'),
(5, 24, 2, NULL, 'Male'),
(6, 24, 2, NULL, ' Female');

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
(1, 'Type of waterborne disease', 'Type of waterborne diseases											', 1),
(2, 'Gender', 'Gender of the beneficiary						', 1);

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
(1, 19, 'Positive tests of water-borne diseases', 2, NULL, 'n'),
(2, 20, 'Volume of water in cubic meters', 2, NULL, 'n'),
(3, 22, 'Number of households accessing clean water', 2, NULL, 'n'),
(4, 22, 'Total number of households surveyed', 2, NULL, 'd'),
(5, 23, 'Average income per month (Ksh)', 2, NULL, 'n'),
(6, 24, 'Number of targeted population employed', 2, NULL, 'n'),
(7, 31, 'Number of people accessing good road infrastructure', 2, NULL, 'n'),
(8, 31, 'Total number of respondents ', 2, NULL, 'd'),
(9, 32, 'Number of economically empowered people in the society', 2, NULL, 'n'),
(10, 32, 'population ', 2, NULL, 'd');

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
(1, 19, 1, 0, 2),
(2, 24, 2, 0, 2);

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
(4, 7, '0', 350, 0, '0', 1, NULL, '2023-03-14 10:09:30'),
(5, 7, '0', 351, 0, '0', 1, NULL, '2023-03-14 10:09:38'),
(6, 7, '0', 356, 0, '0', 1, NULL, '2023-03-14 10:09:45'),
(7, 7, '0', 355, 0, '0', 1, NULL, '2023-03-14 10:09:50'),
(8, 7, '0', 354, 0, '0', 1, NULL, '2023-03-14 10:09:56'),
(9, 7, '0', 353, 0, '0', 1, NULL, '2023-03-14 10:10:04'),
(10, 7, '0', 352, 0, '0', 1, NULL, '2023-03-14 10:10:09'),
(11, 7, '0', 384, 0, '0', 1, NULL, '2023-03-14 10:10:16'),
(12, 7, '0', 385, 0, '0', 1, NULL, '2023-03-14 10:10:28'),
(13, 7, '0', 390, 0, '0', 1, NULL, '2023-03-14 10:10:35'),
(14, 7, '0', 389, 0, '0', 1, NULL, '2023-03-14 10:10:41'),
(15, 7, '0', 391, 0, '0', 1, NULL, '2023-03-14 10:10:46'),
(17, 7, '0', 386, 0, '0', 1, NULL, '2023-03-14 10:10:52'),
(18, 7, '0', 388, 0, '0', 1, NULL, '2023-03-14 10:11:03'),
(19, 7, '0', 387, 0, '0', 1, NULL, '2023-03-14 10:11:14'),
(20, 7, '0', 412, 0, '0', 1, NULL, '2023-03-14 10:11:20'),
(21, 7, '0', 411, 0, '0', 1, NULL, '2023-03-14 10:11:25'),
(22, 7, '0', 413, 0, '0', 1, NULL, '2023-03-14 10:11:31'),
(23, 7, '0', 383, 0, '0', 1, NULL, '2023-03-14 10:11:36'),
(24, 7, '0', 348, 0, '0', 1, NULL, '2023-03-14 10:11:41'),
(25, 7, '0', 382, 0, '0', 1, NULL, '2023-03-14 10:11:46'),
(26, 7, '0', 349, 0, '0', 1, NULL, '2023-03-14 10:11:51'),
(27, 7, '0', 368, 0, '0', 1, NULL, '2023-03-14 10:11:58'),
(28, 7, '0', 369, 0, '0', 1, NULL, '2023-03-14 10:12:07'),
(29, 7, '0', 400, 0, '0', 1, NULL, '2023-03-14 10:12:12'),
(30, 7, '0', 392, 0, '0', 1, NULL, '2023-03-14 10:12:17'),
(31, 7, '0', 394, 0, '0', 1, NULL, '2023-03-14 10:12:24'),
(33, 7, '0', 393, 0, '0', 1, NULL, '2023-03-14 10:12:29'),
(34, 7, '0', 395, 0, '0', 1, NULL, '2023-03-14 10:12:38'),
(35, 7, '0', 398, 0, '0', 1, NULL, '2023-03-14 10:12:44'),
(36, 7, '0', 397, 0, '0', 1, NULL, '2023-03-14 10:12:50'),
(37, 7, '0', 396, 0, '0', 1, NULL, '2023-03-14 10:12:59'),
(39, 7, '0', 399, 0, '0', 1, NULL, '2023-03-14 10:13:09'),
(40, 7, '0', 401, 0, '0', 1, NULL, '2023-03-14 10:13:17'),
(41, 7, '0', 402, 0, '0', 1, NULL, '2023-03-14 10:13:23'),
(42, 7, '0', 346, 0, '0', 1, NULL, '2023-03-14 10:13:29'),
(43, 7, '0', 347, 0, '0', 1, NULL, '2023-03-14 10:13:35'),
(44, 7, '0', 345, 0, '0', 1, NULL, '2023-03-14 10:13:43'),
(46, 7, '0', 343, 0, '0', 1, NULL, '2023-03-14 10:13:48'),
(47, 7, '0', 344, 0, '0', 1, NULL, '2023-03-14 10:13:59'),
(49, 7, '0', 342, 0, '0', 1, NULL, '2023-03-14 10:14:05'),
(50, 7, '0', 403, 0, '0', 0, NULL, '2023-03-14 10:14:31'),
(51, 7, '0', 405, 0, '0', 0, NULL, '2023-03-14 10:14:36'),
(53, 7, '0', 404, 0, '0', 0, NULL, '2023-03-14 10:14:43'),
(55, 7, '0', 341, 0, '0', 0, NULL, '2023-03-14 10:14:50'),
(57, 7, '0', 340, 0, '0', 0, NULL, '2023-03-14 10:15:01'),
(58, 7, '0', 339, 0, '0', 0, NULL, '2023-03-14 10:15:09'),
(59, 7, '0', 357, 0, '0', 0, NULL, '2023-03-14 10:15:17'),
(60, 7, '0', 358, 0, '0', 0, NULL, '2023-03-14 10:15:25'),
(61, 7, '0', 409, 0, '0', 0, NULL, '2023-03-14 10:15:31'),
(62, 7, '0', 367, 0, '0', 0, NULL, '2023-03-14 10:15:36'),
(63, 7, '0', 366, 0, '0', 0, NULL, '2023-03-14 10:15:44'),
(64, 7, '0', 410, 0, '0', 0, NULL, '2023-03-14 10:15:49'),
(65, 7, '0', 364, 0, '0', 0, NULL, '2023-03-14 10:15:55'),
(66, 7, '0', 365, 0, '0', 0, NULL, '2023-03-14 10:16:00'),
(67, 7, '0', 363, 0, '0', 0, NULL, '2023-03-14 10:16:06'),
(68, 7, '0', 408, 0, '0', 0, NULL, '2023-03-14 10:16:12'),
(69, 7, '0', 407, 0, '0', 0, NULL, '2023-03-14 10:16:18'),
(70, 7, '0', 362, 0, '0', 0, NULL, '2023-03-14 10:16:23'),
(71, 7, '0', 360, 0, '0', 0, NULL, '2023-03-14 10:16:30'),
(72, 7, '0', 361, 0, '0', 0, NULL, '2023-03-14 10:16:35'),
(73, 7, '0', 380, 0, '0', 0, NULL, '2023-03-14 10:16:41'),
(74, 7, '0', 376, 0, '0', 0, NULL, '2023-03-14 10:16:48'),
(75, 7, '0', 375, 0, '0', 0, NULL, '2023-03-14 10:16:53'),
(76, 7, '0', 379, 0, '0', 0, NULL, '2023-03-14 10:16:58'),
(77, 7, '0', 378, 0, '0', 0, NULL, '2023-03-14 10:17:03'),
(78, 7, '0', 377, 0, '0', 0, NULL, '2023-03-14 10:17:08'),
(79, 7, '0', 371, 0, '0', 0, NULL, '2023-03-14 10:17:18'),
(80, 7, '0', 370, 0, '0', 0, NULL, '2023-03-14 10:17:24'),
(81, 7, '0', 374, 0, '0', 0, NULL, '2023-03-14 10:17:29'),
(82, 7, '0', 373, 0, '0', 0, NULL, '2023-03-14 10:17:34'),
(83, 7, '0', 372, 0, '0', 0, NULL, '2023-03-14 10:17:39'),
(84, 9, '0', 350, 0, '0', 0, NULL, '2023-03-14 10:18:31'),
(85, 9, '0', 351, 0, '0', 0, NULL, '2023-03-14 10:18:37'),
(86, 9, '0', 356, 0, '0', 0, NULL, '2023-03-14 10:18:42'),
(87, 9, '0', 355, 0, '0', 0, NULL, '2023-03-14 10:18:48'),
(88, 9, '0', 354, 0, '0', 0, NULL, '2023-03-14 10:18:55'),
(89, 9, '0', 353, 0, '0', 0, NULL, '2023-03-14 10:19:01'),
(90, 9, '0', 352, 0, '0', 0, NULL, '2023-03-14 10:19:06'),
(91, 9, '0', 384, 0, '0', 0, NULL, '2023-03-14 10:19:11'),
(92, 9, '0', 385, 0, '0', 0, NULL, '2023-03-14 10:19:18'),
(93, 9, '0', 390, 0, '0', 0, NULL, '2023-03-14 10:19:24'),
(94, 9, '0', 389, 0, '0', 0, NULL, '2023-03-14 10:19:30'),
(95, 9, '0', 391, 0, '0', 0, NULL, '2023-03-14 10:19:36'),
(96, 9, '0', 386, 0, '0', 0, NULL, '2023-03-14 10:19:44'),
(97, 9, '0', 388, 0, '0', 0, NULL, '2023-03-14 10:19:52'),
(98, 9, '0', 387, 0, '0', 0, NULL, '2023-03-14 10:20:01'),
(99, 9, '0', 411, 0, '0', 0, NULL, '2023-03-14 10:20:10'),
(100, 9, '0', 412, 0, '0', 0, NULL, '2023-03-14 10:20:20'),
(101, 9, '0', 413, 0, '0', 0, NULL, '2023-03-14 10:20:26'),
(102, 9, '0', 383, 0, '0', 0, NULL, '2023-03-14 10:20:34'),
(103, 9, '0', 348, 0, '0', 0, NULL, '2023-03-14 10:20:43'),
(104, 9, '0', 382, 0, '0', 0, NULL, '2023-03-14 10:20:48'),
(105, 9, '0', 349, 0, '0', 0, NULL, '2023-03-14 10:20:55'),
(106, 9, '0', 368, 0, '0', 0, NULL, '2023-03-14 10:21:01'),
(107, 9, '0', 369, 0, '0', 0, NULL, '2023-03-14 10:21:07'),
(108, 9, '0', 400, 0, '0', 0, NULL, '2023-03-14 10:21:14'),
(109, 9, '0', 392, 0, '0', 0, NULL, '2023-03-14 10:21:19'),
(110, 9, '0', 394, 0, '0', 0, NULL, '2023-03-14 10:21:25'),
(111, 9, '0', 393, 0, '0', 0, NULL, '2023-03-14 10:21:33'),
(112, 9, '0', 395, 0, '0', 0, NULL, '2023-03-14 10:21:38'),
(113, 9, '0', 398, 0, '0', 0, NULL, '2023-03-14 10:21:43'),
(114, 9, '0', 397, 0, '0', 0, NULL, '2023-03-14 10:21:51'),
(116, 9, '0', 396, 0, '0', 0, NULL, '2023-03-14 10:21:56'),
(117, 9, '0', 399, 0, '0', 0, NULL, '2023-03-14 10:22:04'),
(118, 9, '0', 401, 0, '0', 0, NULL, '2023-03-14 10:22:09'),
(120, 9, '0', 402, 0, '0', 0, NULL, '2023-03-14 10:22:15'),
(121, 9, '0', 346, 0, '0', 0, NULL, '2023-03-14 10:22:23'),
(122, 9, '0', 347, 0, '0', 0, NULL, '2023-03-14 10:22:29'),
(123, 9, '0', 345, 0, '0', 0, NULL, '2023-03-14 10:22:35'),
(124, 9, '0', 343, 0, '0', 0, NULL, '2023-03-14 10:22:40'),
(125, 9, '0', 344, 0, '0', 0, NULL, '2023-03-14 10:22:45'),
(126, 9, '0', 342, 0, '0', 0, NULL, '2023-03-14 10:22:50'),
(127, 9, '0', 403, 0, '0', 0, NULL, '2023-03-14 11:39:40'),
(128, 9, '0', 405, 0, '0', 0, NULL, '2023-03-14 11:39:46'),
(129, 9, '0', 404, 0, '0', 0, NULL, '2023-03-14 11:39:52'),
(130, 9, '0', 341, 0, '0', 0, NULL, '2023-03-14 11:39:58'),
(131, 9, '0', 340, 0, '0', 0, NULL, '2023-03-14 11:40:04'),
(132, 9, '0', 339, 0, '0', 0, NULL, '2023-03-14 11:40:11'),
(133, 9, '0', 357, 0, '0', 0, NULL, '2023-03-14 11:40:17'),
(134, 9, '0', 358, 0, '0', 0, NULL, '2023-03-14 11:40:22'),
(135, 9, '0', 409, 0, '0', 0, NULL, '2023-03-14 11:40:28'),
(136, 9, '0', 367, 0, '0', 0, NULL, '2023-03-14 11:40:35'),
(137, 9, '0', 366, 0, '0', 0, NULL, '2023-03-14 11:40:40'),
(138, 9, '0', 410, 0, '0', 0, NULL, '2023-03-14 11:40:45'),
(139, 9, '0', 364, 0, '0', 0, NULL, '2023-03-14 11:40:49'),
(140, 9, '0', 365, 0, '0', 0, NULL, '2023-03-14 11:40:53'),
(141, 9, '0', 363, 0, '0', 0, NULL, '2023-03-14 11:40:57'),
(142, 9, '0', 407, 0, '0', 0, NULL, '2023-03-14 11:41:02'),
(143, 9, '0', 408, 0, '0', 0, NULL, '2023-03-14 11:41:07'),
(144, 9, '0', 362, 0, '0', 0, NULL, '2023-03-14 11:41:11'),
(145, 9, '0', 360, 0, '0', 0, NULL, '2023-03-14 11:41:16'),
(146, 9, '0', 361, 0, '0', 0, NULL, '2023-03-14 11:41:21'),
(147, 9, '0', 380, 0, '0', 0, NULL, '2023-03-14 11:41:26'),
(148, 9, '0', 376, 0, '0', 0, NULL, '2023-03-14 11:41:30'),
(149, 9, '0', 375, 0, '0', 0, NULL, '2023-03-14 11:41:34'),
(150, 9, '0', 379, 0, '0', 0, NULL, '2023-03-14 11:41:38'),
(151, 9, '0', 378, 0, '0', 0, NULL, '2023-03-14 11:41:43'),
(152, 9, '0', 377, 0, '0', 0, NULL, '2023-03-14 11:41:47'),
(153, 9, '0', 371, 0, '0', 0, NULL, '2023-03-14 11:41:52'),
(154, 9, '0', 370, 0, '0', 0, NULL, '2023-03-14 11:42:00'),
(155, 9, '0', 374, 0, '0', 0, NULL, '2023-03-14 11:42:04'),
(156, 9, '0', 373, 0, '0', 0, NULL, '2023-03-14 11:42:09'),
(157, 9, '0', 372, 0, '0', 0, NULL, '2023-03-14 11:42:13'),
(158, 12, '0', 350, 0, '0', 0, NULL, '2023-03-14 11:43:13'),
(159, 12, '0', 351, 0, '0', 0, NULL, '2023-03-14 11:43:23'),
(160, 12, '0', 356, 0, '0', 0, NULL, '2023-03-14 11:43:30'),
(161, 12, '0', 355, 0, '0', 0, NULL, '2023-03-14 11:43:36'),
(162, 12, '0', 354, 0, '0', 0, NULL, '2023-03-14 11:43:42'),
(163, 12, '0', 353, 0, '0', 0, NULL, '2023-03-14 11:43:47'),
(164, 12, '0', 352, 0, '0', 0, NULL, '2023-03-14 11:43:51'),
(165, 12, '0', 384, 0, '0', 0, NULL, '2023-03-14 11:43:59'),
(166, 12, '0', 385, 0, '0', 0, NULL, '2023-03-14 11:44:04'),
(167, 12, '0', 390, 0, '0', 0, NULL, '2023-03-14 11:44:10'),
(168, 12, '0', 389, 0, '0', 0, NULL, '2023-03-14 11:44:15'),
(169, 12, '0', 391, 0, '0', 0, NULL, '2023-03-14 11:44:25'),
(170, 12, '0', 386, 0, '0', 0, NULL, '2023-03-14 11:44:44'),
(172, 12, '0', 388, 0, '0', 0, NULL, '2023-03-14 11:44:52'),
(173, 12, '0', 387, 0, '0', 0, NULL, '2023-03-14 11:44:59'),
(175, 12, '0', 412, 0, '0', 0, NULL, '2023-03-14 11:45:03'),
(176, 12, '0', 411, 0, '0', 0, NULL, '2023-03-14 11:45:10'),
(178, 12, '0', 413, 0, '0', 0, NULL, '2023-03-14 11:45:15'),
(179, 12, '0', 383, 0, '0', 0, NULL, '2023-03-14 11:45:24'),
(180, 12, '0', 348, 0, '0', 0, NULL, '2023-03-14 11:45:28'),
(181, 12, '0', 382, 0, '0', 0, NULL, '2023-03-14 11:45:34'),
(182, 12, '0', 349, 0, '0', 0, NULL, '2023-03-14 11:45:39'),
(183, 12, '0', 368, 0, '0', 0, NULL, '2023-03-14 11:45:45'),
(185, 12, '0', 369, 0, '0', 0, NULL, '2023-03-14 11:45:50'),
(186, 12, '0', 400, 0, '0', 0, NULL, '2023-03-14 11:45:58'),
(187, 12, '0', 392, 0, '0', 0, NULL, '2023-03-14 11:46:03'),
(188, 12, '0', 394, 0, '0', 0, NULL, '2023-03-14 11:46:09'),
(189, 12, '0', 393, 0, '0', 0, NULL, '2023-03-14 11:46:13'),
(190, 12, '0', 395, 0, '0', 0, NULL, '2023-03-14 11:46:17'),
(191, 12, '0', 398, 0, '0', 0, NULL, '2023-03-14 11:46:21'),
(193, 12, '0', 397, 0, '0', 0, NULL, '2023-03-14 11:46:26'),
(195, 12, '0', 396, 0, '0', 0, NULL, '2023-03-14 11:46:34'),
(196, 12, '0', 399, 0, '0', 0, NULL, '2023-03-14 11:46:41'),
(197, 12, '0', 401, 0, '0', 0, NULL, '2023-03-14 11:46:46'),
(198, 12, '0', 402, 0, '0', 0, NULL, '2023-03-14 11:46:50'),
(200, 12, '0', 346, 0, '0', 0, NULL, '2023-03-14 11:46:54'),
(202, 12, '0', 347, 0, '0', 0, NULL, '2023-03-14 11:47:00'),
(203, 12, '0', 345, 0, '0', 0, NULL, '2023-03-14 11:47:07'),
(205, 12, '0', 343, 0, '0', 0, NULL, '2023-03-14 11:47:12'),
(207, 12, '0', 344, 0, '0', 0, NULL, '2023-03-14 11:47:19'),
(209, 12, '0', 342, 0, '0', 0, NULL, '2023-03-14 11:47:26'),
(210, 12, '0', 403, 0, '0', 0, NULL, '2023-03-14 11:47:32'),
(211, 12, '0', 405, 0, '0', 0, NULL, '2023-03-14 11:47:38'),
(212, 12, '0', 404, 0, '0', 0, NULL, '2023-03-14 11:47:42'),
(213, 12, '0', 341, 0, '0', 0, NULL, '2023-03-14 11:48:08'),
(214, 12, '0', 340, 0, '0', 0, NULL, '2023-03-14 11:48:13'),
(216, 12, '0', 339, 0, '0', 0, NULL, '2023-03-14 11:48:18'),
(218, 12, '0', 357, 0, '0', 0, NULL, '2023-03-14 11:48:25'),
(219, 12, '0', 358, 0, '0', 0, NULL, '2023-03-14 11:48:33'),
(220, 12, '0', 409, 0, '0', 0, NULL, '2023-03-14 11:48:37'),
(221, 12, '0', 367, 0, '0', 0, NULL, '2023-03-14 11:48:44'),
(223, 12, '0', 366, 0, '0', 0, NULL, '2023-03-14 11:49:04'),
(224, 12, '0', 410, 0, '0', 0, NULL, '2023-03-14 11:49:10'),
(225, 12, '0', 364, 0, '0', 0, NULL, '2023-03-14 11:49:16'),
(226, 12, '0', 365, 0, '0', 0, NULL, '2023-03-14 11:49:22'),
(227, 12, '0', 363, 0, '0', 0, NULL, '2023-03-14 11:49:27'),
(228, 12, '0', 408, 0, '0', 0, NULL, '2023-03-14 11:49:33'),
(230, 12, '0', 407, 0, '0', 0, NULL, '2023-03-14 11:49:39'),
(231, 12, '0', 362, 0, '0', 0, NULL, '2023-03-14 11:50:07'),
(232, 12, '0', 360, 0, '0', 0, NULL, '2023-03-14 11:50:14'),
(233, 12, '0', 361, 0, '0', 0, NULL, '2023-03-14 11:50:28'),
(235, 12, '0', 380, 0, '0', 0, NULL, '2023-03-14 11:50:36'),
(236, 12, '0', 376, 0, '0', 0, NULL, '2023-03-14 11:50:45'),
(238, 12, '0', 375, 0, '0', 0, NULL, '2023-03-14 11:50:50'),
(239, 12, '0', 379, 0, '0', 0, NULL, '2023-03-14 11:50:59'),
(240, 12, '0', 378, 0, '0', 0, NULL, '2023-03-14 11:51:05'),
(242, 12, '0', 377, 0, '0', 0, NULL, '2023-03-14 11:51:10'),
(244, 12, '0', 371, 0, '0', 0, NULL, '2023-03-14 11:51:18'),
(245, 12, '0', 370, 0, '0', 0, NULL, '2023-03-14 11:51:26'),
(246, 12, '0', 374, 0, '0', 0, NULL, '2023-03-14 11:51:33'),
(248, 12, '0', 373, 0, '0', 0, NULL, '2023-03-14 11:51:39'),
(249, 12, '0', 372, 0, '0', 0, NULL, '2023-03-14 11:51:51'),
(250, 10, '0', 350, 0, '0', 0, NULL, '2023-03-14 11:53:41'),
(251, 10, '0', 351, 0, '0', 0, NULL, '2023-03-14 11:53:48'),
(252, 10, '0', 356, 0, '0', 0, NULL, '2023-03-14 11:53:53'),
(254, 10, '0', 355, 0, '0', 0, NULL, '2023-03-14 11:53:57'),
(256, 10, '0', 354, 0, '0', 0, NULL, '2023-03-14 11:54:05'),
(258, 10, '0', 352, 0, '0', 0, NULL, '2023-03-14 11:54:11'),
(260, 10, '0', 353, 0, '0', 0, NULL, '2023-03-14 11:54:17'),
(261, 10, '0', 384, 0, '0', 0, NULL, '2023-03-14 11:54:23'),
(262, 10, '0', 385, 0, '0', 0, NULL, '2023-03-14 11:54:27'),
(263, 10, '0', 390, 0, '0', 0, NULL, '2023-03-14 11:54:32'),
(264, 10, '0', 389, 0, '0', 0, NULL, '2023-03-14 11:54:36'),
(265, 10, '0', 391, 0, '0', 0, NULL, '2023-03-14 11:54:40'),
(266, 10, '0', 386, 0, '0', 0, NULL, '2023-03-14 11:54:44'),
(267, 10, '0', 388, 0, '0', 0, NULL, '2023-03-14 11:54:49'),
(268, 10, '0', 387, 0, '0', 0, NULL, '2023-03-14 11:54:53'),
(269, 10, '0', 411, 0, '0', 0, NULL, '2023-03-14 11:54:57'),
(270, 10, '0', 412, 0, '0', 0, NULL, '2023-03-14 11:55:00'),
(271, 10, '0', 413, 0, '0', 0, NULL, '2023-03-14 11:55:04'),
(273, 10, '0', 383, 0, '0', 0, NULL, '2023-03-14 11:55:08'),
(274, 10, '0', 348, 0, '0', 0, NULL, '2023-03-14 11:55:15'),
(275, 10, '0', 382, 0, '0', 0, NULL, '2023-03-14 11:55:19'),
(276, 10, '0', 349, 0, '0', 0, NULL, '2023-03-14 11:55:22'),
(277, 10, '0', 368, 0, '0', 0, NULL, '2023-03-14 11:55:26'),
(278, 10, '0', 369, 0, '0', 0, NULL, '2023-03-14 11:55:30'),
(279, 10, '0', 400, 0, '0', 0, NULL, '2023-03-14 11:55:33'),
(280, 10, '0', 392, 0, '0', 0, NULL, '2023-03-14 11:55:37'),
(281, 1, '0', 409, 0, '0', 20, NULL, '2023-03-14 11:55:40'),
(282, 10, '0', 394, 0, '0', 0, NULL, '2023-03-14 11:55:41'),
(283, 10, '0', 393, 0, '0', 0, NULL, '2023-03-14 11:55:45'),
(284, 10, '0', 395, 0, '0', 0, NULL, '2023-03-14 11:55:49'),
(285, 10, '0', 398, 0, '0', 0, NULL, '2023-03-14 11:55:52'),
(286, 1, '0', 357, 0, '0', 40, NULL, '2023-03-14 11:55:53'),
(287, 10, '0', 397, 0, '0', 0, NULL, '2023-03-14 11:55:56'),
(288, 10, '0', 396, 0, '0', 0, NULL, '2023-03-14 11:55:59'),
(289, 10, '0', 399, 0, '0', 0, NULL, '2023-03-14 11:56:03'),
(290, 1, '0', 401, 0, '0', 50, NULL, '2023-03-14 11:56:04'),
(291, 10, '0', 401, 0, '0', 0, NULL, '2023-03-14 11:56:07'),
(292, 10, '0', 402, 0, '0', 0, NULL, '2023-03-14 11:56:10'),
(293, 10, '0', 346, 0, '0', 0, NULL, '2023-03-14 11:56:14'),
(294, 1, '0', 360, 0, '0', 47, NULL, '2023-03-14 11:56:14'),
(296, 10, '0', 347, 0, '0', 0, NULL, '2023-03-14 11:56:19'),
(297, 10, '0', 345, 0, '0', 0, NULL, '2023-03-14 11:56:25'),
(298, 10, '0', 343, 0, '0', 0, NULL, '2023-03-14 11:56:28'),
(299, 10, '0', 344, 0, '0', 0, NULL, '2023-03-14 11:56:31'),
(300, 10, '0', 342, 0, '0', 0, NULL, '2023-03-14 11:56:35'),
(301, 10, '0', 403, 0, '0', 0, NULL, '2023-03-14 11:56:39'),
(302, 10, '0', 404, 0, '0', 0, NULL, '2023-03-14 11:56:43'),
(303, 10, '0', 405, 0, '0', 0, NULL, '2023-03-14 11:56:47'),
(304, 10, '0', 341, 0, '0', 0, NULL, '2023-03-14 11:56:52'),
(305, 10, '0', 340, 0, '0', 0, NULL, '2023-03-14 11:56:56'),
(306, 10, '0', 339, 0, '0', 0, NULL, '2023-03-14 11:57:00'),
(308, 10, '0', 357, 0, '0', 0, NULL, '2023-03-14 11:57:05'),
(309, 10, '0', 409, 0, '0', 0, NULL, '2023-03-14 11:57:11'),
(310, 10, '0', 358, 0, '0', 0, NULL, '2023-03-14 11:57:15'),
(311, 10, '0', 367, 0, '0', 0, NULL, '2023-03-14 11:57:20'),
(312, 10, '0', 366, 0, '0', 0, NULL, '2023-03-14 11:57:23'),
(313, 10, '0', 410, 0, '0', 0, NULL, '2023-03-14 11:57:29'),
(314, 10, '0', 364, 0, '0', 0, NULL, '2023-03-14 11:57:34'),
(315, 10, '0', 365, 0, '0', 0, NULL, '2023-03-14 11:57:37'),
(316, 10, '0', 363, 0, '0', 0, NULL, '2023-03-14 11:57:41'),
(317, 10, '0', 408, 0, '0', 0, NULL, '2023-03-14 11:57:46'),
(318, 10, '0', 407, 0, '0', 0, NULL, '2023-03-14 11:57:51'),
(319, 10, '0', 362, 0, '0', 0, NULL, '2023-03-14 11:57:54'),
(320, 10, '0', 360, 0, '0', 0, NULL, '2023-03-14 11:57:59'),
(321, 10, '0', 361, 0, '0', 0, NULL, '2023-03-14 11:58:03'),
(322, 10, '0', 380, 0, '0', 0, NULL, '2023-03-14 11:58:06'),
(323, 10, '0', 376, 0, '0', 0, NULL, '2023-03-14 11:58:11'),
(324, 10, '0', 375, 0, '0', 0, NULL, '2023-03-14 11:58:15'),
(325, 10, '0', 379, 0, '0', 0, NULL, '2023-03-14 11:58:18'),
(326, 10, '0', 378, 0, '0', 0, NULL, '2023-03-14 11:58:23'),
(327, 10, '0', 377, 0, '0', 0, NULL, '2023-03-14 11:58:27'),
(328, 10, '0', 371, 0, '0', 0, NULL, '2023-03-14 11:58:31'),
(329, 10, '0', 370, 0, '0', 0, NULL, '2023-03-14 11:58:35'),
(330, 10, '0', 374, 0, '0', 0, NULL, '2023-03-14 11:58:40'),
(331, 10, '0', 373, 0, '0', 0, NULL, '2023-03-14 11:58:44'),
(332, 10, '0', 372, 0, '0', 0, NULL, '2023-03-14 11:58:48'),
(333, 1, '0', 361, 0, '0', 40, NULL, '2023-03-14 12:00:06'),
(334, 1, '0', 362, 0, '0', 29, NULL, '2023-03-14 12:00:25'),
(335, 1, '0', 402, 0, '0', 39, NULL, '2023-03-14 12:00:35'),
(336, 1, '0', 346, 0, '0', 49, NULL, '2023-03-14 12:00:45'),
(337, 1, '0', 407, 0, '0', 50, NULL, '2023-03-14 12:00:54'),
(338, 1, '0', 347, 0, '0', 40, NULL, '2023-03-14 12:01:03'),
(339, 1, '0', 408, 0, '0', 29, NULL, '2023-03-14 12:01:14'),
(340, 1, '0', 342, 0, '0', 20, NULL, '2023-03-14 12:01:25'),
(341, 1, '0', 366, 0, '0', 5, NULL, '2023-03-14 12:01:40'),
(342, 1, '0', 358, 0, '0', 20, NULL, '2023-03-14 12:01:51'),
(343, 1, '0', 367, 0, '0', 1, NULL, '2023-03-14 12:02:00'),
(344, 1, '0', 410, 0, '0', 3, NULL, '2023-03-14 12:02:09'),
(345, 1, '0', 363, 0, '0', 3, NULL, '2023-03-14 12:02:18'),
(346, 1, '0', 364, 0, '0', 3, NULL, '2023-03-14 12:02:27'),
(347, 1, '0', 365, 0, '0', 2, NULL, '2023-03-14 12:02:40'),
(348, 1, '0', 392, 0, '0', 2, NULL, '2023-03-14 12:02:58'),
(349, 1, '0', 393, 0, '0', 4, NULL, '2023-03-14 12:03:05'),
(350, 1, '0', 396, 0, '0', 0, NULL, '2023-03-14 12:03:13'),
(351, 1, '0', 395, 0, '0', 0, NULL, '2023-03-14 12:03:26'),
(352, 1, '0', 394, 0, '0', 0, NULL, '2023-03-14 12:03:35'),
(353, 1, '0', 397, 0, '0', 0, NULL, '2023-03-14 12:03:43'),
(354, 1, '0', 399, 0, '0', 0, NULL, '2023-03-14 12:03:51'),
(356, 1, '0', 398, 0, '0', 0, NULL, '2023-03-14 12:04:07'),
(357, 1, '0', 400, 0, '0', 0, NULL, '2023-03-14 12:04:16'),
(358, 1, '0', 339, 0, '0', 0, NULL, '2023-03-14 12:04:32'),
(359, 1, '0', 340, 0, '0', 0, NULL, '2023-03-14 12:04:43'),
(360, 1, '0', 341, 0, '0', 0, NULL, '2023-03-14 12:04:50'),
(361, 1, '0', 380, 0, '0', 1, NULL, '2023-03-14 12:04:58'),
(362, 1, '0', 404, 0, '0', 1, NULL, '2023-03-14 12:05:05'),
(363, 1, '0', 405, 0, '0', 1, NULL, '2023-03-14 12:05:12'),
(364, 1, '0', 375, 0, '0', 0, NULL, '2023-03-14 12:05:19'),
(365, 1, '0', 379, 0, '0', 0, NULL, '2023-03-14 12:05:30'),
(366, 1, '0', 376, 0, '0', 1, NULL, '2023-03-14 12:05:37'),
(367, 1, '0', 377, 0, '0', 1, NULL, '2023-03-14 12:05:47'),
(368, 1, '0', 378, 0, '0', 1, NULL, '2023-03-14 12:05:53'),
(369, 1, '0', 370, 0, '0', 1, NULL, '2023-03-14 12:06:01'),
(370, 1, '0', 372, 0, '0', 0, NULL, '2023-03-14 12:06:11'),
(371, 1, '0', 351, 0, '0', 0, NULL, '2023-03-14 12:06:18'),
(372, 1, '0', 373, 0, '0', 0, NULL, '2023-03-14 12:06:27'),
(373, 1, '0', 374, 0, '0', 0, NULL, '2023-03-14 12:17:35'),
(374, 1, '0', 352, 0, '0', 0, NULL, '2023-03-14 12:18:01'),
(375, 1, '0', 350, 0, '0', 0, NULL, '2023-03-14 12:18:14'),
(376, 1, '0', 353, 0, '0', 0, NULL, '2023-03-14 12:18:23'),
(377, 1, '0', 354, 0, '0', 0, NULL, '2023-03-14 12:18:31'),
(378, 1, '0', 355, 0, '0', 0, NULL, '2023-03-14 12:21:16'),
(379, 1, '0', 356, 0, '0', 0, NULL, '2023-03-14 12:21:30'),
(380, 1, '0', 348, 0, '0', 0, NULL, '2023-03-14 12:21:38'),
(381, 1, '0', 349, 0, '0', 0, NULL, '2023-03-14 12:21:45'),
(382, 1, '0', 382, 0, '0', 0, NULL, '2023-03-14 12:21:53'),
(383, 1, '0', 383, 0, '0', 0, NULL, '2023-03-14 12:22:02'),
(384, 1, '0', 384, 0, '0', 0, NULL, '2023-03-14 12:22:10'),
(385, 1, '0', 389, 0, '0', 0, NULL, '2023-03-14 12:22:16'),
(386, 1, '0', 385, 0, '0', 0, NULL, '2023-03-14 12:22:23'),
(387, 1, '0', 411, 0, '0', 0, NULL, '2023-03-14 12:22:32'),
(388, 1, '0', 388, 0, '0', 0, NULL, '2023-03-14 12:22:39'),
(389, 1, '0', 386, 0, '0', 0, NULL, '2023-03-14 12:22:46'),
(390, 1, '0', 390, 0, '0', 0, NULL, '2023-03-14 12:29:41'),
(391, 1, '0', 391, 0, '0', 0, NULL, '2023-03-14 12:29:51'),
(392, 1, '0', 413, 0, '0', 0, NULL, '2023-03-14 12:30:02'),
(393, 1, '0', 412, 0, '0', 0, NULL, '2023-03-14 12:30:11'),
(394, 1, '0', 387, 0, '0', 0, NULL, '2023-03-14 12:30:23'),
(395, 1, '0', 368, 0, '0', 0, NULL, '2023-03-14 12:30:34'),
(396, 1, '0', 369, 0, '0', 0, NULL, '2023-03-14 12:30:40'),
(397, 1, '0', 371, 0, '0', 0, NULL, '2023-03-14 12:30:55'),
(398, 1, '0', 343, 0, '0', 0, NULL, '2023-03-14 12:31:05'),
(399, 1, '0', 344, 0, '0', 0, NULL, '2023-03-14 12:31:12'),
(400, 1, '0', 345, 0, '0', 0, NULL, '2023-03-14 12:31:19'),
(401, 1, '0', 403, 0, '0', 0, NULL, '2023-03-14 12:31:27'),
(402, 2, '0', 350, 0, '0', 0, NULL, '2023-03-14 12:34:34'),
(403, 2, '0', 351, 0, '0', 0, NULL, '2023-03-14 12:34:42'),
(404, 2, '0', 356, 0, '0', 0, NULL, '2023-03-14 12:34:53'),
(405, 2, '0', 355, 0, '0', 0, NULL, '2023-03-14 12:35:01'),
(406, 2, '0', 354, 0, '0', 0, NULL, '2023-03-14 12:35:08'),
(407, 2, '0', 353, 0, '0', 0, NULL, '2023-03-14 12:35:17'),
(408, 2, '0', 352, 0, '0', 0, NULL, '2023-03-14 12:35:25'),
(409, 2, '0', 384, 0, '0', 0, NULL, '2023-03-14 12:35:33'),
(410, 2, '0', 385, 0, '0', 0, NULL, '2023-03-14 12:35:58'),
(411, 2, '0', 390, 0, '0', 0, NULL, '2023-03-14 12:36:06'),
(412, 2, '0', 389, 0, '0', 0, NULL, '2023-03-14 12:36:12'),
(413, 2, '0', 386, 0, '0', 0, NULL, '2023-03-14 12:36:21'),
(414, 2, '0', 391, 0, '0', 0, NULL, '2023-03-14 12:37:04'),
(415, 2, '0', 388, 0, '0', 0, NULL, '2023-03-14 12:37:13'),
(416, 2, '0', 374, 0, '0', 0, NULL, '2023-03-14 12:37:25'),
(417, 2, '0', 373, 0, '0', 0, NULL, '2023-03-14 12:39:22'),
(418, 2, '0', 371, 0, '0', 0, NULL, '2023-03-14 12:39:29'),
(419, 2, '0', 372, 0, '0', 0, NULL, '2023-03-14 12:39:37'),
(420, 2, '0', 370, 0, '0', 0, NULL, '2023-03-14 12:39:44'),
(421, 2, '0', 378, 0, '0', 0, NULL, '2023-03-14 12:39:56'),
(422, 2, '0', 377, 0, '0', 0, NULL, '2023-03-14 12:40:03'),
(423, 2, '0', 379, 0, '0', 0, NULL, '2023-03-14 12:40:10'),
(424, 2, '0', 387, 0, '0', 0, NULL, '2023-03-14 12:40:24'),
(425, 2, '0', 411, 0, '0', 0, NULL, '2023-03-14 12:40:32'),
(426, 2, '0', 412, 0, '0', 0, NULL, '2023-03-14 12:41:29'),
(427, 2, '0', 413, 0, '0', 0, NULL, '2023-03-14 12:41:36'),
(428, 2, '0', 383, 0, '0', 0, NULL, '2023-03-14 12:41:43'),
(429, 2, '0', 348, 0, '0', 0, NULL, '2023-03-14 12:41:50'),
(430, 2, '0', 401, 0, '0', 1, NULL, '2023-03-14 12:42:02'),
(431, 2, '0', 402, 0, '0', 0, NULL, '2023-03-14 12:42:10'),
(432, 2, '0', 398, 0, '0', 0, NULL, '2023-03-14 12:42:20'),
(433, 2, '0', 396, 0, '0', 1, NULL, '2023-03-14 12:42:27'),
(434, 2, '0', 397, 0, '0', 0, NULL, '2023-03-14 12:42:34'),
(435, 2, '0', 399, 0, '0', 0, NULL, '2023-03-14 12:42:40'),
(436, 2, '0', 346, 0, '0', 0, NULL, '2023-03-14 12:42:47'),
(437, 2, '0', 347, 0, '0', 0, NULL, '2023-03-14 12:42:55'),
(438, 2, '0', 345, 0, '0', 0, NULL, '2023-03-14 12:43:02'),
(439, 2, '0', 410, 0, '0', 0, NULL, '2023-03-14 12:43:23'),
(440, 2, '0', 364, 0, '0', 0, NULL, '2023-03-14 12:43:32'),
(441, 2, '0', 365, 0, '0', 0, NULL, '2023-03-14 12:44:10'),
(442, 2, '0', 367, 0, '0', 0, NULL, '2023-03-14 12:44:19'),
(443, 2, '0', 366, 0, '0', 0, NULL, '2023-03-14 12:44:28'),
(444, 2, '0', 409, 0, '0', 0, NULL, '2023-03-14 12:51:30'),
(445, 2, '0', 363, 0, '0', 0, NULL, '2023-03-14 12:51:37'),
(446, 2, '0', 408, 0, '0', 0, NULL, '2023-03-14 12:51:44'),
(447, 2, '0', 407, 0, '0', 0, NULL, '2023-03-14 12:51:51'),
(448, 2, '0', 362, 0, '0', 0, NULL, '2023-03-14 12:51:59'),
(449, 2, '0', 360, 0, '0', 0, NULL, '2023-03-14 12:52:06'),
(450, 2, '0', 361, 0, '0', 0, NULL, '2023-03-14 12:52:13'),
(451, 2, '0', 380, 0, '0', 0, NULL, '2023-03-14 12:52:20'),
(452, 2, '0', 376, 0, '0', 0, NULL, '2023-03-14 12:52:27'),
(453, 2, '0', 375, 0, '0', 0, NULL, '2023-03-14 12:52:37'),
(454, 2, '0', 343, 0, '0', 0, NULL, '2023-03-14 12:52:54'),
(455, 2, '0', 344, 0, '0', 0, NULL, '2023-03-14 12:53:03'),
(456, 2, '0', 403, 0, '0', 0, NULL, '2023-03-14 12:53:12'),
(457, 2, '0', 342, 0, '0', 0, NULL, '2023-03-14 12:53:40'),
(458, 2, '0', 405, 0, '0', 0, NULL, '2023-03-14 12:54:54'),
(459, 2, '0', 404, 0, '0', 0, NULL, '2023-03-14 12:55:05'),
(460, 2, '0', 341, 0, '0', 1, NULL, '2023-03-14 12:55:13'),
(461, 2, '0', 340, 0, '0', 0, NULL, '2023-03-14 12:55:28'),
(462, 2, '0', 339, 0, '0', 0, NULL, '2023-03-14 12:55:41'),
(463, 2, '0', 357, 0, '0', 1, NULL, '2023-03-14 12:55:51'),
(464, 2, '0', 358, 0, '0', 0, NULL, '2023-03-14 12:56:00'),
(465, 8, '0', 350, 0, '0', 0, NULL, '2023-03-14 12:59:08'),
(466, 8, '0', 351, 0, '0', 0, NULL, '2023-03-14 12:59:15'),
(467, 8, '0', 356, 0, '0', 0, NULL, '2023-03-14 12:59:22'),
(468, 8, '0', 355, 0, '0', 0, NULL, '2023-03-14 12:59:30'),
(469, 8, '0', 353, 0, '0', 0, NULL, '2023-03-14 12:59:40'),
(470, 8, '0', 391, 0, '0', 0, NULL, '2023-03-14 12:59:47'),
(471, 8, '0', 352, 0, '0', 0, NULL, '2023-03-14 13:08:19'),
(472, 8, '0', 384, 0, '0', 0, NULL, '2023-03-14 13:08:29'),
(473, 8, '0', 385, 0, '0', 0, NULL, '2023-03-14 13:08:35'),
(474, 8, '0', 390, 0, '0', 0, NULL, '2023-03-14 13:08:42'),
(475, 8, '0', 389, 0, '0', 0, NULL, '2023-03-14 13:08:49'),
(476, 8, '0', 386, 0, '0', 0, NULL, '2023-03-14 13:08:56'),
(477, 8, '0', 388, 0, '0', 0, NULL, '2023-03-14 13:09:03'),
(478, 8, '0', 387, 0, '0', 0, NULL, '2023-03-14 13:09:12'),
(479, 8, '0', 411, 0, '0', 0, NULL, '2023-03-14 13:09:19'),
(480, 8, '0', 412, 0, '0', 0, NULL, '2023-03-14 13:09:25'),
(481, 8, '0', 413, 0, '0', 0, NULL, '2023-03-14 13:15:36'),
(482, 8, '0', 383, 0, '0', 0, NULL, '2023-03-14 13:15:42'),
(483, 8, '0', 348, 0, '0', 0, NULL, '2023-03-14 13:15:49'),
(484, 8, '0', 382, 0, '0', 0, NULL, '2023-03-14 13:15:55'),
(485, 8, '0', 349, 0, '0', 0, NULL, '2023-03-14 13:16:01'),
(486, 8, '0', 368, 0, '0', 0, NULL, '2023-03-14 13:16:11'),
(487, 8, '0', 392, 0, '0', 1, NULL, '2023-03-14 13:16:17'),
(488, 8, '0', 394, 0, '0', 1, NULL, '2023-03-14 13:16:24'),
(489, 8, '0', 369, 0, '0', 0, NULL, '2023-03-14 13:16:31'),
(490, 8, '0', 395, 0, '0', 0, NULL, '2023-03-14 13:16:38'),
(491, 8, '0', 400, 0, '0', 0, NULL, '2023-03-14 13:18:38'),
(492, 8, '0', 393, 0, '0', 0, NULL, '2023-03-14 13:21:09'),
(493, 8, '0', 398, 0, '0', 0, NULL, '2023-03-14 13:21:16'),
(494, 8, '0', 397, 0, '0', 0, NULL, '2023-03-14 13:21:24'),
(495, 8, '0', 396, 0, '0', 0, NULL, '2023-03-14 13:21:34'),
(496, 8, '0', 399, 0, '0', 0, NULL, '2023-03-14 13:21:42'),
(497, 8, '0', 401, 0, '0', 0, NULL, '2023-03-14 13:21:49'),
(498, 8, '0', 402, 0, '0', 0, NULL, '2023-03-14 13:21:56'),
(499, 8, '0', 346, 0, '0', 0, NULL, '2023-03-14 13:22:04'),
(500, 8, '0', 347, 0, '0', 0, NULL, '2023-03-14 13:22:16'),
(501, 8, '0', 403, 0, '0', 0, NULL, '2023-03-14 13:22:28'),
(502, 8, '0', 345, 0, '0', 0, NULL, '2023-03-14 13:22:39'),
(503, 8, '0', 343, 0, '0', 0, NULL, '2023-03-14 13:22:45'),
(504, 8, '0', 342, 0, '0', 0, NULL, '2023-03-14 13:22:53'),
(505, 8, '0', 344, 0, '0', 0, NULL, '2023-03-14 13:23:00'),
(506, 8, '0', 404, 0, '0', 0, NULL, '2023-03-14 13:23:08'),
(507, 8, '0', 405, 0, '0', 0, NULL, '2023-03-14 13:23:14'),
(508, 8, '0', 340, 0, '0', 0, NULL, '2023-03-14 13:23:21'),
(509, 8, '0', 341, 0, '0', 0, NULL, '2023-03-14 13:23:45'),
(510, 8, '0', 357, 0, '0', 0, NULL, '2023-03-14 13:24:26'),
(511, 8, '0', 409, 0, '0', 0, NULL, '2023-03-14 13:24:34'),
(512, 8, '0', 410, 0, '0', 0, NULL, '2023-03-14 13:24:41'),
(513, 8, '0', 358, 0, '0', 0, NULL, '2023-03-14 13:24:48'),
(514, 8, '0', 367, 0, '0', 0, NULL, '2023-03-14 13:24:56'),
(515, 8, '0', 366, 0, '0', 0, NULL, '2023-03-14 13:25:03'),
(516, 8, '0', 364, 0, '0', 0, NULL, '2023-03-14 13:25:10'),
(517, 8, '0', 365, 0, '0', 0, NULL, '2023-03-14 13:25:22'),
(518, 8, '0', 408, 0, '0', 0, NULL, '2023-03-14 13:25:29'),
(519, 8, '0', 363, 0, '0', 0, NULL, '2023-03-14 13:25:35'),
(520, 8, '0', 407, 0, '0', 0, NULL, '2023-03-14 13:25:42'),
(521, 8, '0', 362, 0, '0', 0, NULL, '2023-03-14 13:25:48'),
(522, 8, '0', 360, 0, '0', 1, NULL, '2023-03-14 13:25:55'),
(523, 8, '0', 361, 0, '0', 0, NULL, '2023-03-14 13:26:05'),
(524, 8, '0', 376, 0, '0', 0, NULL, '2023-03-14 13:26:12'),
(525, 8, '0', 380, 0, '0', 0, NULL, '2023-03-14 13:26:19'),
(526, 8, '0', 379, 0, '0', 0, NULL, '2023-03-14 13:26:26'),
(527, 8, '0', 375, 0, '0', 0, NULL, '2023-03-14 13:26:32'),
(528, 8, '0', 378, 0, '0', 0, NULL, '2023-03-14 13:26:39'),
(529, 8, '0', 377, 0, '0', 0, NULL, '2023-03-14 13:26:46'),
(530, 8, '0', 371, 0, '0', 0, NULL, '2023-03-14 13:26:57'),
(531, 8, '0', 370, 0, '0', 0, NULL, '2023-03-14 13:27:04'),
(532, 8, '0', 372, 0, '0', 0, NULL, '2023-03-14 13:27:10'),
(533, 8, '0', 373, 0, '0', 1, NULL, '2023-03-14 13:27:17'),
(534, 8, '0', 374, 0, '0', 1, NULL, '2023-03-14 13:27:24'),
(535, 8, '0', 339, 0, '0', 1, NULL, '2023-03-14 13:27:35'),
(536, 8, '0', 354, 0, '0', 1, NULL, '2023-03-14 13:27:45'),
(537, 5, '0', 350, 0, '0', 0, NULL, '2023-03-14 13:52:00'),
(538, 5, '0', 351, 0, '0', 0, NULL, '2023-03-14 13:52:12'),
(539, 5, '0', 356, 0, '0', 0, NULL, '2023-03-14 13:52:18'),
(540, 5, '0', 355, 0, '0', 0, NULL, '2023-03-14 13:52:25'),
(541, 5, '0', 354, 0, '0', 0, NULL, '2023-03-14 13:52:32'),
(542, 5, '0', 353, 0, '0', 0, NULL, '2023-03-14 13:52:40'),
(543, 5, '0', 352, 0, '0', 0, NULL, '2023-03-14 13:52:48'),
(544, 5, '0', 384, 0, '0', 0, NULL, '2023-03-14 13:52:56'),
(545, 5, '0', 385, 0, '0', 0, NULL, '2023-03-14 13:53:05'),
(546, 5, '0', 389, 0, '0', 0, NULL, '2023-03-14 13:53:12'),
(547, 5, '0', 390, 0, '0', 0, NULL, '2023-03-14 13:54:14'),
(548, 5, '0', 391, 0, '0', 0, NULL, '2023-03-14 13:54:22'),
(549, 5, '0', 386, 0, '0', 0, NULL, '2023-03-14 13:54:30'),
(550, 5, '0', 388, 0, '0', 0, NULL, '2023-03-14 13:54:37'),
(551, 5, '0', 387, 0, '0', 0, NULL, '2023-03-14 13:54:47'),
(552, 5, '0', 411, 0, '0', 0, NULL, '2023-03-14 13:54:54'),
(553, 5, '0', 412, 0, '0', 0, NULL, '2023-03-14 13:55:01'),
(554, 5, '0', 413, 0, '0', 0, NULL, '2023-03-14 13:55:08'),
(555, 5, '0', 383, 0, '0', 1, NULL, '2023-03-14 13:55:16'),
(556, 5, '0', 348, 0, '0', 0, NULL, '2023-03-14 13:55:23'),
(557, 5, '0', 382, 0, '0', 0, NULL, '2023-03-14 13:55:29'),
(558, 5, '0', 378, 0, '0', 0, NULL, '2023-03-14 14:07:51'),
(559, 5, '0', 377, 0, '0', 0, NULL, '2023-03-14 14:07:58'),
(560, 5, '0', 371, 0, '0', 0, NULL, '2023-03-14 14:08:09'),
(561, 5, '0', 370, 0, '0', 1, NULL, '2023-03-14 14:08:15'),
(562, 5, '0', 374, 0, '0', 1, NULL, '2023-03-14 14:08:22'),
(563, 5, '0', 373, 0, '0', 1, NULL, '2023-03-14 14:08:29'),
(564, 5, '0', 372, 0, '0', 1, NULL, '2023-03-14 14:08:35'),
(565, 5, '0', 379, 0, '0', 0, NULL, '2023-03-14 14:08:44'),
(566, 5, '0', 380, 0, '0', 0, NULL, '2023-03-14 14:08:53'),
(567, 5, '0', 376, 0, '0', 0, NULL, '2023-03-14 14:09:00'),
(568, 5, '0', 375, 0, '0', 0, NULL, '2023-03-14 14:09:08'),
(569, 5, '0', 363, 0, '0', 0, NULL, '2023-03-14 14:10:14'),
(570, 5, '0', 407, 0, '0', 0, NULL, '2023-03-14 14:10:22'),
(571, 5, '0', 362, 0, '0', 0, NULL, '2023-03-14 14:10:38'),
(572, 5, '0', 360, 0, '0', 9, NULL, '2023-03-14 14:10:45'),
(573, 5, '0', 361, 0, '0', 0, NULL, '2023-03-14 14:10:53'),
(574, 5, '0', 408, 0, '0', 0, NULL, '2023-03-14 14:11:01'),
(575, 5, '0', 349, 0, '0', 0, NULL, '2023-03-14 14:11:15'),
(576, 5, '0', 368, 0, '0', 0, NULL, '2023-03-14 14:11:23'),
(577, 5, '0', 369, 0, '0', 0, NULL, '2023-03-14 14:11:30'),
(578, 5, '0', 400, 0, '0', 0, NULL, '2023-03-14 14:11:39'),
(579, 5, '0', 392, 0, '0', 0, NULL, '2023-03-14 14:11:47'),
(580, 5, '0', 394, 0, '0', 0, NULL, '2023-03-14 14:11:56'),
(581, 5, '0', 393, 0, '0', 0, NULL, '2023-03-14 14:12:04'),
(582, 5, '0', 395, 0, '0', 0, NULL, '2023-03-14 14:12:10'),
(583, 5, '0', 398, 0, '0', 0, NULL, '2023-03-14 14:12:17'),
(584, 5, '0', 397, 0, '0', 0, NULL, '2023-03-14 14:12:24'),
(585, 5, '0', 396, 0, '0', 0, NULL, '2023-03-14 14:12:31'),
(586, 5, '0', 399, 0, '0', 0, NULL, '2023-03-14 14:12:37'),
(587, 5, '0', 409, 0, '0', 0, NULL, '2023-03-14 14:12:49'),
(588, 5, '0', 366, 0, '0', 0, NULL, '2023-03-14 14:12:56'),
(589, 5, '0', 367, 0, '0', 0, NULL, '2023-03-14 14:13:04'),
(590, 5, '0', 357, 0, '0', 0, NULL, '2023-03-14 14:13:11'),
(591, 5, '0', 364, 0, '0', 0, NULL, '2023-03-14 14:13:55'),
(592, 5, '0', 410, 0, '0', 0, NULL, '2023-03-14 14:14:02'),
(593, 5, '0', 365, 0, '0', 0, NULL, '2023-03-14 14:14:13'),
(609, 5, '0', 358, 0, '0', 10, NULL, '2023-03-14 14:18:02'),
(595, 5, '0', 401, 0, '0', 0, NULL, '2023-03-14 14:15:44'),
(596, 5, '0', 402, 0, '0', 0, NULL, '2023-03-14 14:15:51'),
(597, 5, '0', 346, 0, '0', 0, NULL, '2023-03-14 14:15:59'),
(598, 5, '0', 347, 0, '0', 0, NULL, '2023-03-14 14:16:05'),
(599, 5, '0', 345, 0, '0', 0, NULL, '2023-03-14 14:16:15'),
(600, 5, '0', 343, 0, '0', 0, NULL, '2023-03-14 14:16:23'),
(601, 5, '0', 344, 0, '0', 0, NULL, '2023-03-14 14:16:35'),
(602, 5, '0', 403, 0, '0', 0, NULL, '2023-03-14 14:16:41'),
(603, 5, '0', 342, 0, '0', 0, NULL, '2023-03-14 14:16:48'),
(604, 5, '0', 405, 0, '0', 0, NULL, '2023-03-14 14:16:55'),
(605, 5, '0', 404, 0, '0', 0, NULL, '2023-03-14 14:17:01'),
(606, 5, '0', 341, 0, '0', 0, NULL, '2023-03-14 14:17:08'),
(607, 5, '0', 340, 0, '0', 0, NULL, '2023-03-14 14:17:15'),
(608, 5, '0', 339, 0, '0', 0, NULL, '2023-03-14 14:17:23'),
(610, 4, '0', 350, 0, '0', 0, NULL, '2023-03-15 11:43:59'),
(613, 4, '0', 351, 0, '0', 0, NULL, '2023-03-15 11:44:11'),
(614, 4, '0', 356, 0, '0', 0, NULL, '2023-03-15 11:44:17'),
(615, 4, '0', 355, 0, '0', 0, NULL, '2023-03-15 11:44:24'),
(616, 4, '0', 354, 0, '0', 0, NULL, '2023-03-15 11:44:31'),
(618, 4, '0', 353, 0, '0', 0, NULL, '2023-03-15 11:44:36'),
(619, 4, '0', 352, 0, '0', 0, NULL, '2023-03-15 11:44:45'),
(620, 4, '0', 384, 0, '0', 0, NULL, '2023-03-15 11:44:49'),
(622, 4, '0', 385, 0, '0', 0, NULL, '2023-03-15 11:44:56'),
(624, 4, '0', 390, 0, '0', 0, NULL, '2023-03-15 11:45:28'),
(625, 4, '0', 389, 0, '0', 0, NULL, '2023-03-15 11:45:37'),
(626, 4, '0', 391, 0, '0', 0, NULL, '2023-03-15 11:45:43'),
(627, 4, '0', 386, 0, '0', 0, NULL, '2023-03-15 11:45:47'),
(628, 4, '0', 388, 0, '0', 0, NULL, '2023-03-15 11:45:53'),
(629, 4, '0', 411, 0, '0', 0, NULL, '2023-03-15 11:45:58'),
(630, 4, '0', 387, 0, '0', 0, NULL, '2023-03-15 11:46:05'),
(631, 4, '0', 412, 0, '0', 0, NULL, '2023-03-15 11:46:11'),
(632, 4, '0', 413, 0, '0', 0, NULL, '2023-03-15 11:46:16'),
(633, 4, '0', 383, 0, '0', 0, NULL, '2023-03-15 11:46:21'),
(634, 4, '0', 348, 0, '0', 0, NULL, '2023-03-15 11:46:27'),
(635, 4, '0', 382, 0, '0', 0, NULL, '2023-03-15 11:46:32'),
(636, 4, '0', 349, 0, '0', 0, NULL, '2023-03-15 11:46:37'),
(637, 4, '0', 368, 0, '0', 0, NULL, '2023-03-15 11:46:44'),
(638, 4, '0', 369, 0, '0', 0, NULL, '2023-03-15 11:46:49'),
(639, 4, '0', 400, 0, '0', 0, NULL, '2023-03-15 11:46:53'),
(640, 4, '0', 392, 0, '0', 0, NULL, '2023-03-15 11:46:57'),
(641, 4, '0', 394, 0, '0', 0, NULL, '2023-03-15 11:47:02'),
(642, 4, '0', 393, 0, '0', 0, NULL, '2023-03-15 11:47:06'),
(644, 4, '0', 395, 0, '0', 0, NULL, '2023-03-15 11:47:11'),
(645, 4, '0', 398, 0, '0', 0, NULL, '2023-03-15 11:47:19'),
(646, 4, '0', 397, 0, '0', 0, NULL, '2023-03-15 11:47:23'),
(647, 4, '0', 396, 0, '0', 0, NULL, '2023-03-15 11:47:29'),
(648, 4, '0', 399, 0, '0', 0, NULL, '2023-03-15 11:47:36'),
(649, 4, '0', 401, 0, '0', 0, NULL, '2023-03-15 11:47:42'),
(650, 4, '0', 402, 0, '0', 0, NULL, '2023-03-15 11:47:47'),
(651, 4, '0', 346, 0, '0', 0, NULL, '2023-03-15 11:47:52'),
(652, 4, '0', 347, 0, '0', 0, NULL, '2023-03-15 11:47:57'),
(653, 4, '0', 345, 0, '0', 0, NULL, '2023-03-15 11:48:02'),
(654, 4, '0', 343, 0, '0', 0, NULL, '2023-03-15 11:48:08'),
(656, 4, '0', 344, 0, '0', 0, NULL, '2023-03-15 11:48:13'),
(657, 4, '0', 342, 0, '0', 0, NULL, '2023-03-15 11:48:22'),
(658, 4, '0', 403, 0, '0', 0, NULL, '2023-03-15 11:48:28'),
(659, 4, '0', 405, 0, '0', 0, NULL, '2023-03-15 11:48:33'),
(660, 4, '0', 404, 0, '0', 0, NULL, '2023-03-15 11:48:39'),
(661, 4, '0', 341, 0, '0', 0, NULL, '2023-03-15 11:48:44'),
(662, 4, '0', 340, 0, '0', 0, NULL, '2023-03-15 11:48:48'),
(663, 4, '0', 339, 0, '0', 0, NULL, '2023-03-15 11:48:52'),
(664, 4, '0', 357, 0, '0', 0, NULL, '2023-03-15 11:48:58'),
(665, 4, '0', 358, 0, '0', 0, NULL, '2023-03-15 11:49:02'),
(666, 4, '0', 409, 0, '0', 0, NULL, '2023-03-15 11:49:07'),
(667, 4, '0', 367, 0, '0', 0, NULL, '2023-03-15 11:49:14'),
(668, 4, '0', 366, 0, '0', 0, NULL, '2023-03-15 11:49:18'),
(669, 4, '0', 410, 0, '0', 0, NULL, '2023-03-15 11:49:24'),
(670, 4, '0', 364, 0, '0', 0, NULL, '2023-03-15 11:49:30'),
(671, 4, '0', 365, 0, '0', 0, NULL, '2023-03-15 11:49:35'),
(673, 4, '0', 363, 0, '0', 0, NULL, '2023-03-15 11:49:39'),
(674, 4, '0', 408, 0, '0', 0, NULL, '2023-03-15 11:49:47'),
(676, 4, '0', 407, 0, '0', 0, NULL, '2023-03-15 11:49:52'),
(677, 4, '0', 362, 0, '0', 0, NULL, '2023-03-15 11:49:58'),
(678, 4, '0', 360, 0, '0', 0, NULL, '2023-03-15 11:50:11'),
(679, 4, '0', 361, 0, '0', 0, NULL, '2023-03-15 11:50:30'),
(680, 4, '0', 380, 0, '0', 0, NULL, '2023-03-15 11:50:36'),
(681, 4, '0', 376, 0, '0', 0, NULL, '2023-03-15 11:50:41'),
(682, 4, '0', 375, 0, '0', 0, NULL, '2023-03-15 11:50:45'),
(683, 4, '0', 379, 0, '0', 0, NULL, '2023-03-15 11:50:50'),
(684, 4, '0', 378, 0, '0', 0, NULL, '2023-03-15 11:50:55'),
(685, 4, '0', 377, 0, '0', 0, NULL, '2023-03-15 11:51:02'),
(686, 4, '0', 371, 0, '0', 0, NULL, '2023-03-15 11:51:07'),
(687, 4, '0', 370, 0, '0', 0, NULL, '2023-03-15 11:51:11'),
(688, 4, '0', 374, 0, '0', 0, NULL, '2023-03-15 11:51:15'),
(690, 4, '0', 373, 0, '0', 0, NULL, '2023-03-15 11:51:25'),
(691, 4, '0', 372, 0, '0', 0, NULL, '2023-03-15 11:51:31'),
(692, 18, '0', 3, 0, '0', 20, NULL, '2023-03-15 12:10:15'),
(693, 21, '0', 3, 0, '0', 10, NULL, '2023-03-17 08:55:52'),
(700, 25, '0', 3, 0, '0', 500, NULL, '2023-08-03 12:25:18'),
(699, 3, '0', 350, 0, '0', 5, NULL, '2023-08-03 12:24:29'),
(701, 26, '0', 3, 0, '0', 10, NULL, '2023-08-09 13:03:11'),
(702, 27, '0', 3, 0, '0', 0, NULL, '2023-08-09 13:18:38'),
(703, 28, '0', 3, 0, '0', 0, NULL, '2023-08-09 13:19:10'),
(704, 30, '0', 3, 0, '0', 0, NULL, '2023-08-14 14:34:15'),
(705, 3, '0', 334, 0, '0', 30, NULL, '2023-10-03 12:20:07'),
(706, 3, '0', 309, 0, '0', 20, NULL, '2023-10-03 12:21:26'),
(707, 3, '0', 333, 0, '0', 10, NULL, '2023-10-03 12:22:26'),
(708, 3, '0', 332, 0, '0', 5, NULL, '2023-10-03 12:23:22'),
(713, 34, '0', 309, 0, '0', 500, NULL, '2023-10-10 08:11:24'),
(712, 34, '0', 311, 0, '0', 500, NULL, '2023-10-10 08:11:15'),
(711, 34, '0', 310, 0, '0', 500, NULL, '2023-10-10 08:11:04'),
(714, 34, '0', 313, 0, '0', 500, NULL, '2023-10-10 08:11:34'),
(715, 34, '0', 314, 0, '0', 500, NULL, '2023-10-10 08:11:40'),
(716, 34, '0', 315, 0, '0', 500, NULL, '2023-10-10 08:11:47'),
(717, 34, '0', 316, 0, '0', 500, NULL, '2023-10-10 08:11:56'),
(718, 34, '0', 317, 0, '0', 500, NULL, '2023-10-10 08:12:04'),
(719, 34, '0', 312, 0, '0', 500, NULL, '2023-10-10 08:12:11'),
(720, 34, '0', 318, 0, '0', 500, NULL, '2023-10-10 08:12:20'),
(721, 34, '0', 320, 0, '0', 500, NULL, '2023-10-10 08:12:28'),
(722, 34, '0', 319, 0, '0', 500, NULL, '2023-10-10 08:12:34'),
(723, 34, '0', 334, 0, '0', 500, NULL, '2023-10-10 08:12:43'),
(724, 34, '0', 335, 0, '0', 500, NULL, '2023-10-10 08:12:48'),
(725, 34, '0', 336, 0, '0', 500, NULL, '2023-10-10 08:12:55'),
(726, 34, '0', 337, 0, '0', 500, NULL, '2023-10-10 08:19:33'),
(727, 34, '0', 338, 0, '0', 500, NULL, '2023-10-10 08:19:41'),
(728, 34, '0', 322, 0, '0', 500, NULL, '2023-10-10 08:19:47'),
(729, 34, '0', 321, 0, '0', 500, NULL, '2023-10-10 08:19:53'),
(730, 34, '0', 323, 0, '0', 500, NULL, '2023-10-10 08:19:58'),
(732, 34, '0', 324, 0, '0', 500, NULL, '2023-10-10 08:20:04'),
(733, 34, '0', 325, 0, '0', 500, NULL, '2023-10-10 08:20:17'),
(734, 34, '0', 326, 0, '0', 500, NULL, '2023-10-10 08:20:23'),
(735, 34, '0', 328, 0, '0', 500, NULL, '2023-10-10 08:20:28'),
(736, 34, '0', 327, 0, '0', 500, NULL, '2023-10-10 08:20:34'),
(737, 34, '0', 329, 0, '0', 500, NULL, '2023-10-10 08:20:40'),
(738, 34, '0', 330, 0, '0', 500, NULL, '2023-10-10 08:20:46'),
(739, 34, '0', 331, 0, '0', 500, NULL, '2023-10-10 08:20:54'),
(740, 34, '0', 332, 0, '0', 500, NULL, '2023-10-10 08:21:01'),
(741, 34, '0', 333, 0, '0', 500, NULL, '2023-10-10 08:21:07');

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inspection_checklist_questions`
--

CREATE TABLE `tbl_inspection_checklist_questions` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `question` text NOT NULL,
  `answer` int NOT NULL DEFAULT '0',
  `comment` text,
  `created_by` varchar(100) NOT NULL,
  `updated_by` int NOT NULL DEFAULT '0',
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_inspection_checklist_questions`
--

INSERT INTO `tbl_inspection_checklist_questions` (`id`, `projid`, `question`, `answer`, `comment`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(17, 101, 'Enim corrupti iste ', 2, '8888888', '118', 118, '2023-10-11', '2023-10-11'),
(18, 101, 'Voluptatem Quidem q', 2, 'Comment the commments ', '118', 1, '2023-10-11', '2023-10-11'),
(19, 101, 'Non quam voluptatum ', 2, 'ttttt', '118', 118, '2023-10-11', '2023-10-11'),
(20, 101, 'Exercitation fugit ', 2, 'Testing the probability', '118', 1, '2023-10-11', '2023-10-11');

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
-- Table structure for table `tbl_inspection_monitoring_data_origin`
--

CREATE TABLE `tbl_inspection_monitoring_data_origin` (
  `id` int NOT NULL,
  `origin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_inspection_monitoring_data_origin`
--

INSERT INTO `tbl_inspection_monitoring_data_origin` (`id`, `origin`) VALUES
(1, 'General Inspection'),
(2, 'Specification Inspection'),
(3, 'Activities Monitoring'),
(4, 'Output Monitoring');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inspection_observations`
--

CREATE TABLE `tbl_inspection_observations` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL DEFAULT '0',
  `design_id` int NOT NULL,
  `site_id` int NOT NULL,
  `state_id` int NOT NULL,
  `task_id` int NOT NULL,
  `parameter_id` int NOT NULL,
  `specification_id` int NOT NULL,
  `inspection_id` int NOT NULL DEFAULT '0',
  `formid` varchar(20) NOT NULL,
  `observation` text NOT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_inspection_observations`
--

INSERT INTO `tbl_inspection_observations` (`id`, `projid`, `output_id`, `design_id`, `site_id`, `state_id`, `task_id`, `parameter_id`, `specification_id`, `inspection_id`, `formid`, `observation`, `created_by`, `created_at`) VALUES
(1, 3, 5, 4, 6, 0, 7, 12, 8, 1, '2023-03-21', 'Work done is upto the standards', 118, '2023-03-21'),
(2, 3, 5, 4, 6, 0, 7, 12, 9, 2, '2023-03-21', 'Not well treated', 118, '2023-03-21'),
(3, 3, 5, 4, 6, 0, 7, 12, 10, 3, '2023-03-21', 'ongoing well', 118, '2023-03-21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_issue_status`
--

CREATE TABLE `tbl_issue_status` (
  `id` int NOT NULL,
  `status` varchar(100) NOT NULL,
  `days` int DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_issue_status`
--

INSERT INTO `tbl_issue_status` (`id`, `status`, `days`, `description`) VALUES
(1, 'Pending', NULL, NULL),
(2, 'Analysis', NULL, NULL),
(3, 'Analyzed', NULL, NULL),
(4, 'Escalated', NULL, NULL),
(5, 'Continue', NULL, NULL),
(6, 'Hold', NULL, NULL),
(7, 'Closed', NULL, NULL);

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
(7, 1, 'Food Security', NULL, '118', '2023-03-14'),
(8, 1, 'Poverty/Income ', NULL, '118', '2023-03-14'),
(9, 1, 'Literacy', NULL, '118', '2023-03-14'),
(10, 1, 'Health ', NULL, '118', '2023-03-14'),
(11, 1, 'Infrastructure ', NULL, '118', '2023-03-14'),
(12, 1, 'Water and Environment ', NULL, '118', '2023-03-14'),
(13, 2, 'Omnis dolor autem ni', NULL, '1', '2023-04-10'),
(14, 3, 'Health', NULL, '118', '2023-08-12'),
(15, 3, 'Water and Environment', NULL, '118', '2023-08-12'),
(16, 1, 'higyi', NULL, '118', '2023-09-22');

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
  `state` int NOT NULL,
  `site_id` int DEFAULT '0',
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `distance_mapped` float NOT NULL DEFAULT '0',
  `mapped_date` date NOT NULL,
  `mapped_by` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_markers`
--

INSERT INTO `tbl_markers` (`id`, `projid`, `opid`, `state`, `site_id`, `lat`, `lng`, `distance_mapped`, `mapped_date`, `mapped_by`) VALUES
(1, 3, 5, 346, 6, -1.28538, 36.9558, 0, '2023-09-25', 1),
(3, 3, 5, 384, 7, -1.28539, 36.9558, 0, '2023-09-25', 1),
(20, 109, 66, 0, 0, 0.777588, 35.155, 0, '2023-10-03', 118),
(19, 109, 66, 0, 0, 0.780564, 35.1619, 0, '2023-10-03', 118),
(18, 109, 66, 0, 0, 0.78197, 35.1654, 0, '2023-10-03', 118),
(17, 109, 66, 0, 0, 0.781289, 35.1654, 0, '2023-10-03', 118),
(16, 109, 66, 0, 0, 0.778614, 35.1655, 0, '2023-10-03', 118),
(15, 109, 66, 0, 0, 0.775927, 35.1656, 0, '2023-10-03', 118),
(14, 109, 66, 0, 0, 0.774656, 35.1656, 0, '2023-10-03', 118),
(21, 109, 66, 0, 0, 0.775227, 35.1492, 0, '2023-10-03', 118),
(22, 109, 66, 0, 0, 0.774254, 35.1493, 0, '2023-10-03', 118),
(23, 109, 66, 0, 0, 0.773993, 35.1504, 0, '2023-10-03', 118),
(24, 109, 66, 0, 0, 0.771643, 35.1495, 0, '2023-10-03', 118),
(25, 109, 66, 0, 0, 0.768039, 35.1473, 0, '2023-10-03', 118),
(26, 109, 66, 0, 0, 0.768897, 35.1345, 0, '2023-10-03', 118),
(27, 109, 66, 0, 0, 0.768897, 35.1121, 0, '2023-10-03', 118),
(28, 109, 66, 0, 0, 0.747355, 35.1119, 0, '2023-10-03', 118),
(29, 109, 67, 321, 153, -1.28008, 36.9721, 0, '2023-10-03', 118),
(30, 109, 67, 321, 154, -1.27272, 36.8334, 0, '2023-10-03', 118),
(31, 109, 67, 331, 155, -1.27272, 36.8334, 0, '2023-10-03', 118),
(32, 109, 67, 331, 156, -1.27272, 36.8334, 0, '2023-10-03', 118);

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
(2, 'Tonnes', '		Weight Measure in Tonnes', 1),
(7, 'Kg', '		Weight measure										in Kilograms', 1),
(13, 'm3', 'Volume measure in Cubic Meters (m3)', 1),
(14, 'Meters', '		Length measure in Meters', 1),
(15, 'Ltr', 'Volume measure in Litres											', 1),
(16, 'Km', '				Length measure in Kilometers', 1),
(19, 'Number', 'Count in Number', 1),
(54, 'Ksh ', 'Money in Kenya Shillings 										', 1),
(59, 'Hr', 'Time measure in Hours												', 1),
(60, 'Min', 'Time Measure										 in Minutes', 1),
(72, 'M2', 'Area in Square Meters', 1),
(73, 'Dolore harum at ut a', 'Iusto sunt non volu', 1),
(74, 'Accusantium exceptur', 'Cupidatat non consec', 1),
(75, 'Day', 'Number of days', 1),
(76, 'pax', 'Number Persons', 1),
(77, '%', 'percentage 													', 1);

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
-- Table structure for table `tbl_member_subtasks`
--

CREATE TABLE `tbl_member_subtasks` (
  `id` int NOT NULL,
  `member_id` int NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL,
  `task_id` int NOT NULL,
  `subtask_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_member_subtasks`
--

INSERT INTO `tbl_member_subtasks` (`id`, `member_id`, `projid`, `output_id`, `task_id`, `subtask_id`) VALUES
(12, 124, 101, 59, 112, 348),
(13, 119, 101, 59, 113, 349),
(14, 130, 101, 59, 113, 349),
(15, 144, 101, 59, 112, 348),
(16, 144, 101, 59, 113, 349),
(17, 121, 109, 67, 120, 352),
(18, 121, 109, 67, 120, 353),
(19, 121, 109, 67, 121, 355),
(20, 121, 109, 67, 123, 360),
(21, 121, 109, 67, 123, 361),
(22, 121, 109, 67, 123, 362),
(23, 121, 109, 67, 123, 363),
(24, 121, 109, 67, 123, 364),
(25, 121, 109, 67, 123, 365),
(40, 128, 109, 67, 121, 354),
(41, 128, 109, 67, 121, 355),
(42, 128, 109, 67, 121, 356),
(43, 128, 109, 67, 123, 360),
(44, 128, 109, 67, 123, 361),
(45, 128, 109, 67, 123, 362),
(46, 128, 109, 67, 123, 363),
(47, 128, 109, 67, 123, 364),
(48, 128, 109, 67, 123, 365),
(49, 132, 109, 66, 116, 366),
(50, 132, 109, 66, 116, 367),
(51, 132, 109, 66, 116, 368),
(52, 132, 109, 66, 116, 369),
(53, 132, 109, 66, 117, 370),
(54, 132, 109, 66, 117, 371),
(55, 132, 109, 66, 117, 372),
(56, 132, 109, 66, 117, 373),
(57, 132, 109, 66, 117, 374),
(58, 132, 109, 66, 118, 375),
(59, 132, 109, 66, 118, 376),
(60, 132, 109, 66, 118, 377),
(61, 132, 109, 66, 118, 378),
(62, 132, 109, 66, 119, 379),
(63, 132, 109, 67, 120, 352),
(64, 132, 109, 67, 120, 353),
(65, 132, 109, 67, 121, 354),
(66, 132, 109, 67, 121, 355),
(67, 132, 109, 67, 121, 356),
(68, 132, 109, 67, 122, 357),
(69, 132, 109, 67, 122, 358),
(70, 132, 109, 67, 122, 359),
(71, 132, 109, 67, 123, 360),
(72, 132, 109, 67, 123, 361),
(73, 132, 109, 67, 123, 362),
(74, 132, 109, 67, 123, 363),
(75, 132, 109, 67, 123, 364),
(76, 132, 109, 67, 123, 365);

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
  `design_id` int DEFAULT NULL,
  `milestone` varchar(300) NOT NULL,
  `parent` int DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `milestonebudget` double DEFAULT '0',
  `progress` float DEFAULT '0',
  `inspectionstatus` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `paymentrequired` int NOT NULL DEFAULT '0',
  `paymentstatus` int NOT NULL DEFAULT '0',
  `payment_plan_id` int NOT NULL DEFAULT '0',
  `changedstatus` varchar(100) DEFAULT NULL,
  `datecompleted` date DEFAULT NULL,
  `user_name` varchar(200) NOT NULL,
  `date_entered` date NOT NULL,
  `changedby` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `datechanged` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_milestone`
--

INSERT INTO `tbl_milestone` (`msid`, `projid`, `outputid`, `design_id`, `milestone`, `parent`, `location`, `milestonebudget`, `progress`, `inspectionstatus`, `status`, `paymentrequired`, `paymentstatus`, `payment_plan_id`, `changedstatus`, `datecompleted`, `user_name`, `date_entered`, `changedby`, `datechanged`) VALUES
(1, 1, 1, 1, 'Excavation', NULL, '377', 0, 0, 0, 0, 0, 0, 5, NULL, NULL, '118', '2023-03-15', NULL, NULL),
(2, 1, 1, 1, 'Construction works', NULL, '377', 0, 0, 0, 0, 0, 0, 12, NULL, NULL, '118', '2023-03-15', NULL, NULL),
(3, 1, 2, 2, 'Excavation', NULL, '377', 0, 0, 0, 0, 0, 0, 12, NULL, NULL, '118', '2023-03-15', NULL, NULL),
(4, 1, 2, 2, 'Construction works', NULL, '377', 0, 0, 0, 0, 0, 0, 13, NULL, NULL, '118', '2023-03-15', NULL, NULL),
(5, 1, 3, 3, 'Excavation', NULL, '1,2', 0, 0, 0, 0, 0, 0, 13, NULL, NULL, '118', '2023-03-15', NULL, NULL),
(6, 1, 3, 3, 'Construction works', NULL, '1,2', 0, 0, 0, 0, 0, 0, 13, NULL, NULL, '118', '2023-03-15', NULL, NULL),
(7, 3, 5, 4, 'Earthworks', NULL, '6,7', 0, 0, 0, 0, 0, 0, 1, NULL, NULL, '118', '2023-03-15', NULL, NULL),
(8, 3, 5, 4, 'Masonary', NULL, '6,7', 0, 0, 0, 0, 0, 0, 2, NULL, NULL, '118', '2023-03-15', NULL, NULL),
(15, 2, 4, 9, 'Stage 1: Hydrological Study and Survey to confirm opportunity of borehole', NULL, '3,4,5', 0, 0, 0, 0, 0, 0, 3, NULL, NULL, '118', '2023-03-15', NULL, NULL),
(16, 2, 4, 9, 'Stage 2: Borehole Drilling', NULL, '3,4,5', 0, 0, 0, 0, 0, 0, 4, NULL, NULL, '118', '2023-03-15', NULL, NULL),
(17, 7, 10, 10, 'Excavation', NULL, '355', 0, 0, 0, 0, 0, 0, 14, NULL, NULL, '118', '2023-03-18', NULL, NULL),
(18, 7, 10, 10, 'Pipes Work, Meters & Fittings', NULL, '355', 0, 0, 0, 0, 0, 0, 15, NULL, NULL, '118', '2023-03-18', NULL, NULL),
(19, 6, 8, 11, 'Earthworks', NULL, '11', 0, 0, 0, 0, 0, 0, 16, NULL, NULL, '118', '2023-03-18', NULL, NULL),
(20, 6, 8, 11, 'Concrete Works', NULL, '11', 0, 0, 0, 0, 0, 0, 17, NULL, NULL, '118', '2023-03-18', NULL, NULL),
(21, 6, 9, 12, 'Earthworks', NULL, '374', 0, 0, 0, 0, 0, 0, 18, NULL, NULL, '118', '2023-03-18', NULL, NULL),
(22, 6, 9, 12, 'Pipes Work, Meters & Fittings', NULL, '374', 0, 0, 0, 0, 0, 0, 19, NULL, NULL, '118', '2023-03-18', NULL, NULL),
(23, 5, 6, 13, 'Earthworks', NULL, '10', 0, 0, 0, 0, 0, 0, 20, NULL, NULL, '118', '2023-03-18', NULL, NULL),
(24, 5, 6, 13, 'Concrete Works', NULL, '10', 0, 0, 0, 0, 0, 0, 21, NULL, NULL, '118', '2023-03-18', NULL, NULL),
(25, 5, 7, 14, 'Substructure', NULL, '10', 0, 0, 0, 0, 0, 0, 22, NULL, NULL, '118', '2023-03-18', NULL, NULL),
(26, 5, 7, 14, 'Superstructure', NULL, '10', 0, 0, 0, 0, 0, 0, 23, NULL, NULL, '118', '2023-03-18', NULL, NULL),
(27, 15, 11, 15, 'Test', NULL, '29', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-04-11', NULL, NULL),
(28, 62, 22, 16, 'Praesentium in commo', NULL, '407', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-04-23', NULL, NULL),
(29, 59, 23, 17, 'Design m 1', NULL, '3', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-04-25', NULL, NULL),
(31, 58, 21, 18, 'Milestone 1', NULL, '3', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-05-03', '1', '2023-05-03 00:00:00'),
(32, 58, 21, 18, 'Milestone 2', NULL, '3', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-05-03', NULL, NULL),
(33, 66, 24, 19, 'Milestone 1', NULL, '339', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-05-05', NULL, NULL),
(34, 69, 25, 20, 'Task 1', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-05-15', '1', '2023-05-15 00:00:00'),
(35, 69, 26, 21, 'Task 1', NULL, '65,67', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-05-15', NULL, NULL),
(36, 69, 25, 20, 'Task 2', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-05-15', NULL, NULL),
(37, 70, 27, 22, 'Pillar Works for elevated Sections', NULL, '377,378', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '124', '2023-05-30', '124', '2023-05-30 00:00:00'),
(38, 70, 27, 22, 'Surfacing ', NULL, '377,378', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '124', '2023-05-30', NULL, NULL),
(39, 70, 27, 22, 'Excavation', NULL, '377,378', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '124', '2023-05-30', NULL, NULL),
(40, 70, 27, 22, 'Construction works', NULL, '377,378', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '124', '2023-05-30', NULL, NULL),
(41, 70, 27, 22, 'Drainage', NULL, '377,378', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '124', '2023-05-30', NULL, NULL),
(42, 70, 28, 23, 'Excavation', NULL, '69', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '124', '2023-05-31', NULL, NULL),
(43, 70, 28, 23, 'Construction works', NULL, '69', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '124', '2023-05-31', NULL, NULL),
(44, 70, 28, 23, 'Finishing', NULL, '69', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '124', '2023-05-31', NULL, NULL),
(45, 71, 29, 24, 'Test task', NULL, '70', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-06-10', NULL, NULL),
(47, 75, 32, NULL, 'Test Task', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-08-02', '1', '2023-08-02 00:00:00'),
(48, 74, 31, NULL, 'Drilling', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-08-02', '118', '2023-09-14 00:00:00'),
(51, 77, 35, NULL, 'Task A', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-03', NULL, NULL),
(52, 77, 35, NULL, 'Task B', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-03', NULL, NULL),
(53, 78, 37, NULL, 'Excavation', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-03', NULL, NULL),
(54, 78, 37, NULL, 'Construction works', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-03', '1', '2023-08-04 00:00:00'),
(55, 78, 37, NULL, 'Drainage', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-03', NULL, NULL),
(56, 78, 37, NULL, 'Demobilization', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-03', NULL, NULL),
(57, 78, 37, NULL, 'Road Works', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-03', NULL, NULL),
(59, 78, 38, NULL, 'Site Clearance', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-04', NULL, NULL),
(60, 78, 38, NULL, 'Bridgeworks', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-04', NULL, NULL),
(61, 78, 38, NULL, 'Gabion works', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-04', NULL, NULL),
(62, 78, 38, NULL, 'Reinforcement', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-04', NULL, NULL),
(63, 78, 38, NULL, 'Footpath', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-04', NULL, NULL),
(65, 79, 39, NULL, 'Mobilization to site', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-07', '118', '2023-08-07 00:00:00'),
(66, 79, 39, NULL, 'Road Construction works', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-07', '118', '2023-08-07 00:00:00'),
(67, 79, 39, NULL, 'Road Works', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-07', NULL, NULL),
(68, 79, 40, NULL, 'Site Clearance', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-07', NULL, NULL),
(69, 79, 40, NULL, 'Planking and Strutting ', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-07', NULL, NULL),
(70, 79, 40, NULL, 'Gabions', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-07', NULL, NULL),
(71, 79, 40, NULL, 'Reinforcement', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-07', NULL, NULL),
(72, 81, 41, NULL, 'Task A', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-07', NULL, NULL),
(73, 81, 41, NULL, 'Task B', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-07', NULL, NULL),
(74, 81, 41, NULL, 'Task C', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-07', NULL, NULL),
(75, 81, 41, NULL, 'Planning', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-07', NULL, NULL),
(76, 82, 42, NULL, 'Site Clearance ', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-08', NULL, NULL),
(77, 82, 42, NULL, 'Excavation', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-08', NULL, NULL),
(78, 82, 42, NULL, 'Construction Works', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-08', NULL, NULL),
(79, 85, 43, NULL, 'Earthworks', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-09', NULL, NULL),
(80, 85, 43, NULL, ' SUBSTRUCTURE: CONCRETE / REINFORCEMENT/ FORMWORKS ', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-09', NULL, NULL),
(81, 89, 47, NULL, 'Excavation', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-12', NULL, NULL),
(82, 89, 47, NULL, 'Welding', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-12', NULL, NULL),
(83, 89, 47, NULL, 'Painting', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-12', NULL, NULL),
(84, 91, 48, NULL, 'Manufacturing of bins', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-14', NULL, NULL),
(85, 91, 48, NULL, 'Allocate transport system to distribute the bins', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-14', NULL, NULL),
(86, 93, 49, NULL, 'Task A', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-18', NULL, NULL),
(87, 93, 49, NULL, 'Task B', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-18', NULL, NULL),
(88, 94, 50, NULL, 'Task 1', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-08-19', '1', '2023-08-19 00:00:00'),
(89, 94, 50, NULL, 'Task 2', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '1', '2023-08-19', '1', '2023-08-19 00:00:00'),
(93, 95, 51, NULL, 'Aperiam quo sit qui', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-21', NULL, NULL),
(94, 95, 51, NULL, 'Vero et quo esse opt', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-21', NULL, NULL),
(95, 95, 51, NULL, 'Ipsam voluptatem eiu', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-21', NULL, NULL),
(96, 95, 51, NULL, 'Distinctio Soluta o', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-21', NULL, NULL),
(97, 95, 52, NULL, 'Rerum laborum qui el', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-21', NULL, NULL),
(98, 95, 52, NULL, 'Exercitation praesen', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-21', NULL, NULL),
(99, 95, 52, NULL, 'Veniam cupiditate a', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-21', NULL, NULL),
(100, 96, 53, NULL, 'Mobilization to site ', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-24', NULL, NULL),
(101, 96, 53, NULL, 'Construction works', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-24', NULL, NULL),
(103, 96, 54, NULL, 'Site Clearance', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-24', NULL, NULL),
(104, 96, 54, NULL, 'Bridgeworks', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-08-24', NULL, NULL),
(105, 98, 57, NULL, 'Task A', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-04', NULL, NULL),
(106, 98, 57, NULL, 'Task B', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-04', NULL, NULL),
(107, 98, 57, NULL, 'Task C', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-04', NULL, NULL),
(108, 97, 56, NULL, 'Drilling', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-04', NULL, NULL),
(109, 100, 58, NULL, 'Excavation', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-06', NULL, NULL),
(110, 100, 58, NULL, 'Filling', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-06', NULL, NULL),
(111, 100, 58, NULL, 'Pipework', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-06', '118', '2023-09-06 00:00:00'),
(112, 101, 59, NULL, 'Site Clearance ', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-14', NULL, NULL),
(113, 101, 59, NULL, 'Earthworks', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-14', NULL, NULL),
(114, 84, 55, NULL, 'Earthworks', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-14', NULL, NULL),
(115, 84, 55, NULL, 'PIPEWORK - PIPES and FITTINGS', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-14', NULL, NULL),
(116, 109, 66, NULL, 'Preliminaries', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-29', NULL, NULL),
(117, 109, 66, NULL, 'Excavation', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-29', NULL, NULL),
(118, 109, 66, NULL, 'Construction works', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-29', NULL, NULL),
(119, 109, 66, NULL, 'Final Inspection and Handover', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-29', NULL, NULL),
(120, 109, 67, NULL, 'Site Clearance', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-29', NULL, NULL),
(121, 109, 67, NULL, 'Excavation', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-29', NULL, NULL),
(122, 109, 67, NULL, 'Planking and Strutting ', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-29', NULL, NULL),
(123, 109, 67, NULL, 'Gabion works', NULL, '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '118', '2023-09-29', NULL, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_milestone_outputs`
--

CREATE TABLE `tbl_milestone_outputs` (
  `milestone_output_id` int NOT NULL,
  `projid` int NOT NULL,
  `milestone_id` int NOT NULL,
  `output_id` int NOT NULL,
  `target` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_milestone_output_subtasks`
--

CREATE TABLE `tbl_milestone_output_subtasks` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL,
  `milestone_id` int NOT NULL,
  `task_id` int NOT NULL,
  `subtask_id` int NOT NULL,
  `complete` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_milestone_output_subtasks`
--

INSERT INTO `tbl_milestone_output_subtasks` (`id`, `projid`, `output_id`, `milestone_id`, `task_id`, `subtask_id`, `complete`) VALUES
(1, 71, 29, 45, 45, 81, 1),
(11, 78, 38, 63, 60, 156, 1),
(12, 78, 38, 63, 60, 157, 1),
(13, 78, 38, 63, 60, 158, 1),
(14, 78, 38, 63, 60, 159, 1),
(15, 78, 38, 63, 60, 160, 1),
(16, 78, 38, 63, 60, 161, 1),
(17, 78, 37, 57, 55, 145, 1),
(18, 78, 37, 57, 55, 146, 1),
(19, 78, 37, 57, 56, 152, 1),
(20, 78, 37, 57, 56, 153, 1),
(21, 78, 37, 57, 57, 147, 1),
(22, 78, 37, 57, 57, 148, 1),
(23, 78, 37, 57, 57, 149, 1),
(24, 78, 37, 57, 57, 150, 1),
(25, 78, 37, 57, 57, 151, 1),
(36, 78, 38, 10, 60, 156, 1),
(37, 78, 38, 10, 60, 157, 1),
(38, 78, 38, 10, 60, 158, 1),
(39, 78, 38, 10, 60, 159, 1),
(40, 78, 38, 10, 60, 160, 1),
(41, 78, 38, 10, 60, 161, 1),
(42, 78, 38, 10, 61, 162, 1),
(43, 78, 38, 10, 62, 165, 1),
(44, 78, 38, 10, 63, 166, 1),
(45, 78, 38, 10, 63, 167, 1),
(58, 78, 37, 10, 54, 139, 1),
(59, 78, 37, 10, 54, 140, 1),
(60, 78, 37, 10, 54, 141, 1),
(61, 78, 37, 10, 55, 145, 1),
(62, 78, 37, 10, 55, 146, 1),
(63, 78, 37, 10, 56, 152, 1),
(64, 78, 37, 10, 56, 153, 1),
(65, 78, 37, 10, 57, 147, 1),
(66, 78, 37, 10, 57, 148, 1),
(67, 78, 37, 10, 57, 149, 1),
(68, 78, 37, 10, 57, 150, 1),
(69, 78, 37, 10, 57, 151, 1),
(82, 82, 42, 13, 76, 259, 1),
(83, 82, 42, 13, 77, 260, 1),
(84, 82, 42, 13, 78, 261, 1),
(87, 85, 43, 14, 79, 262, 1),
(101, 85, 43, 15, 79, 262, 1),
(102, 85, 43, 15, 80, 263, 1),
(103, 89, 47, 18, 81, 264, 1),
(104, 89, 47, 18, 82, 265, 1),
(105, 89, 47, 18, 83, 266, 1),
(114, 79, 39, 23, 65, 239, 1),
(115, 79, 39, 23, 65, 240, 1),
(116, 79, 39, 23, 65, 241, 1),
(117, 79, 39, 23, 65, 242, 1),
(121, 91, 48, 25, 84, 267, 1),
(122, 91, 48, 25, 84, 268, 1),
(123, 91, 48, 25, 85, 269, 1),
(124, 91, 48, 25, 85, 270, 1),
(125, 91, 48, 24, 84, 267, 1),
(126, 91, 48, 24, 84, 268, 1),
(127, 91, 48, 24, 85, 269, 1),
(128, 91, 48, 24, 85, 270, 1),
(129, 62, 22, 26, 28, 26, 1),
(130, 62, 22, 26, 28, 25, 1),
(131, 94, 50, 27, 88, 287, 1),
(132, 94, 50, 27, 88, 288, 1),
(133, 94, 50, 27, 88, 289, 1),
(134, 94, 50, 27, 88, 290, 1),
(135, 94, 50, 27, 88, 291, 1),
(136, 94, 50, 27, 88, 292, 1),
(137, 94, 50, 27, 88, 293, 1),
(138, 94, 50, 27, 88, 294, 1),
(139, 94, 50, 27, 88, 295, 1),
(140, 94, 50, 27, 88, 296, 1),
(141, 94, 50, 27, 88, 299, 1),
(142, 94, 50, 27, 88, 300, 1),
(143, 94, 50, 27, 88, 301, 1),
(144, 94, 50, 27, 88, 297, 1),
(145, 94, 50, 27, 88, 298, 1),
(146, 94, 50, 28, 88, 287, 1),
(147, 94, 50, 28, 88, 288, 1),
(148, 94, 50, 28, 88, 289, 1),
(149, 94, 50, 28, 88, 290, 1),
(150, 94, 50, 28, 88, 291, 1),
(151, 94, 50, 28, 88, 292, 1),
(152, 94, 50, 28, 88, 293, 1),
(153, 94, 50, 28, 88, 294, 1),
(154, 94, 50, 28, 88, 295, 1),
(155, 94, 50, 28, 88, 296, 1),
(156, 94, 50, 28, 88, 299, 1),
(157, 94, 50, 28, 88, 300, 1),
(158, 94, 50, 28, 88, 301, 1),
(159, 94, 50, 28, 88, 297, 1),
(160, 94, 50, 28, 88, 298, 1),
(161, 94, 50, 28, 89, 282, 1),
(162, 94, 50, 28, 89, 283, 1),
(163, 94, 50, 28, 89, 284, 1),
(164, 94, 50, 28, 89, 285, 1),
(165, 94, 50, 28, 89, 286, 1),
(166, 94, 50, 28, 89, 281, 1),
(167, 94, 50, 28, 89, 280, 1),
(168, 59, 23, 31, 29, 28, 1),
(174, 95, 52, 30, 97, 311, 1),
(175, 95, 52, 30, 97, 312, 1),
(176, 95, 52, 30, 98, 313, 1),
(177, 95, 52, 30, 98, 314, 1),
(182, 95, 51, 30, 93, 302, 1),
(183, 95, 51, 30, 93, 303, 1),
(184, 95, 51, 30, 93, 304, 1),
(185, 95, 51, 30, 96, 310, 1),
(189, 95, 52, 33, 97, 312, 1),
(190, 95, 52, 33, 98, 313, 1),
(191, 95, 52, 33, 99, 316, 1),
(192, 95, 51, 33, 94, 305, 1),
(193, 95, 51, 33, 94, 306, 1),
(194, 95, 51, 33, 96, 309, 1),
(195, 96, 53, 34, 101, 323, 1),
(196, 96, 53, 36, 100, 321, 1),
(197, 96, 53, 36, 100, 322, 1),
(198, 96, 53, 35, 101, 323, 1),
(199, 96, 54, 34, 103, 317, 1),
(200, 96, 54, 34, 104, 318, 1),
(201, 96, 54, 34, 104, 319, 1),
(202, 96, 54, 34, 104, 320, 1),
(224, 97, 56, 39, 108, 337, 1),
(225, 97, 56, 39, 108, 340, 1),
(226, 97, 56, 39, 108, 341, 1),
(232, 100, 58, 40, 109, 342, 1),
(233, 100, 58, 40, 109, 343, 1),
(234, 100, 58, 40, 110, 344, 1),
(235, 100, 58, 40, 111, 345, 1),
(236, 100, 58, 40, 111, 346, 1),
(239, 74, 31, 1, 48, 347, 1),
(240, 74, 31, 1, 48, 89, 1),
(241, 101, 59, 41, 112, 348, 1),
(242, 101, 59, 41, 113, 349, 1),
(245, 84, 55, 42, 114, 350, 2),
(246, 84, 55, 42, 115, 351, 1),
(247, 109, 66, 43, 116, 366, 1),
(248, 109, 66, 43, 116, 367, 1),
(249, 109, 66, 43, 116, 368, 1),
(250, 109, 66, 43, 116, 369, 1),
(251, 109, 66, 43, 117, 370, 1),
(252, 109, 66, 43, 117, 371, 1),
(253, 109, 66, 43, 117, 372, 1),
(254, 109, 66, 43, 117, 373, 1),
(255, 109, 66, 43, 117, 374, 1),
(256, 109, 66, 43, 118, 375, 1),
(257, 109, 66, 43, 118, 376, 1),
(258, 109, 66, 43, 118, 377, 1),
(259, 109, 66, 43, 118, 378, 1),
(260, 109, 67, 43, 120, 352, 1),
(261, 109, 67, 43, 120, 353, 1),
(262, 109, 67, 43, 121, 354, 1),
(263, 109, 67, 43, 121, 355, 1),
(264, 109, 67, 43, 121, 356, 1),
(265, 109, 67, 43, 122, 357, 1),
(266, 109, 67, 43, 122, 358, 1),
(267, 109, 67, 43, 122, 359, 1),
(268, 109, 67, 43, 123, 360, 1),
(269, 109, 67, 43, 123, 361, 1),
(270, 109, 67, 43, 123, 362, 1),
(271, 109, 67, 43, 123, 363, 1),
(272, 109, 67, 43, 123, 364, 1),
(273, 109, 67, 43, 123, 365, 1),
(274, 109, 66, 44, 117, 370, 1),
(275, 109, 66, 44, 117, 371, 1),
(276, 109, 66, 44, 117, 372, 1),
(277, 109, 66, 44, 117, 373, 1),
(278, 109, 66, 44, 117, 374, 1),
(279, 109, 66, 44, 118, 375, 1),
(280, 109, 66, 44, 118, 376, 1),
(281, 109, 66, 44, 118, 377, 1),
(282, 109, 66, 44, 118, 378, 1),
(283, 109, 66, 44, 119, 379, 1),
(284, 109, 67, 44, 120, 352, 1),
(285, 109, 67, 44, 120, 353, 1),
(286, 109, 67, 44, 121, 354, 1),
(287, 109, 67, 44, 121, 355, 1),
(288, 109, 67, 44, 121, 356, 1),
(289, 109, 67, 44, 122, 357, 1),
(290, 109, 67, 44, 122, 358, 1),
(291, 109, 67, 44, 122, 359, 1),
(292, 109, 67, 44, 123, 360, 1),
(293, 109, 67, 44, 123, 361, 1),
(294, 109, 67, 44, 123, 362, 1),
(295, 109, 67, 44, 123, 363, 1),
(296, 109, 67, 44, 123, 364, 1),
(297, 109, 67, 44, 123, 365, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_milestone_output_tasks`
--

CREATE TABLE `tbl_milestone_output_tasks` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL,
  `milestone_id` int NOT NULL,
  `task_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `output_id` int NOT NULL,
  `milestone_id` int NOT NULL,
  `mne_code` varchar(255) NOT NULL,
  `formid` varchar(100) DEFAULT NULL,
  `projlatitude` varchar(50) DEFAULT NULL,
  `projlongitude` varchar(50) DEFAULT NULL,
  `projgeopositionerror` varchar(255) NOT NULL,
  `projlocation` int DEFAULT NULL,
  `level3` int NOT NULL,
  `progress` double NOT NULL,
  `location` int DEFAULT NULL,
  `rankingscore` int DEFAULT NULL,
  `adate` date DEFAULT NULL,
  `dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `datechanged` date DEFAULT NULL,
  `changedby` int DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_monitoringoutput`
--

CREATE TABLE `tbl_monitoringoutput` (
  `moid` int NOT NULL,
  `projid` int DEFAULT NULL,
  `output_id` int NOT NULL,
  `milestone_id` int NOT NULL,
  `site_id` int DEFAULT NULL,
  `state_id` int NOT NULL,
  `form_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `achieved` float NOT NULL,
  `record_type` int NOT NULL DEFAULT '1',
  `created_by` varchar(255) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_monitoring_observations`
--

CREATE TABLE `tbl_monitoring_observations` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL DEFAULT '0',
  `milestone_id` int NOT NULL,
  `state_id` int NOT NULL DEFAULT '0',
  `site_id` int NOT NULL,
  `task_id` int NOT NULL,
  `subtask_id` int NOT NULL,
  `formid` varchar(20) NOT NULL,
  `observation` text NOT NULL,
  `observation_type` int NOT NULL DEFAULT '1',
  `created_by` int DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_monitoring_observations`
--

INSERT INTO `tbl_monitoring_observations` (`id`, `projid`, `output_id`, `milestone_id`, `state_id`, `site_id`, `task_id`, `subtask_id`, `formid`, `observation`, `observation_type`, `created_by`, `created_at`) VALUES
(1, 96, 53, 34, 0, 0, 101, 323, '2023-09-08', 'Comments ', 1, 1, '2023-09-08'),
(2, 96, 54, 34, 0, 136, 103, 317, '2023-09-08', 'comments', 1, 1, '2023-09-08'),
(3, 96, 54, 34, 0, 136, 103, 317, '2023-09-08', 'Exercitation et cupi', 1, 1, '2023-09-08'),
(4, 96, 54, 34, 0, 136, 103, 317, '2023-09-08', 'Exercitation et cupi', 1, 1, '2023-09-08'),
(5, 96, 54, 34, 0, 136, 103, 317, '2023-09-08', 'Exercitation et cupi', 1, 1, '2023-09-08'),
(6, 96, 54, 34, 0, 136, 103, 317, '2023-09-08', 'Exercitation et cupi', 1, 1, '2023-09-08'),
(7, 96, 53, 34, 0, 0, 101, 323, '2023-09-08', 'Atque eum dolorem in', 1, 1, '2023-09-08'),
(8, 96, 53, 34, 0, 0, 101, 323, '2023-09-10', 'Comments', 1, 1, '2023-09-10'),
(9, 84, 55, 42, 0, 115, 115, 351, '2023-09-14', 'The activity is progressing well', 1, 118, '2023-09-14'),
(10, 84, 55, 42, 0, 115, 115, 351, '2023-09-14', 'The activity is progressing well', 1, 118, '2023-09-14'),
(11, 84, 55, 42, 0, 115, 115, 351, '2023-09-14', 'The activity is progressing well', 1, 118, '2023-09-14'),
(12, 97, 56, 39, 0, 138, 108, 337, '2023-09-15', 'remarks', 1, 1, '2023-09-15'),
(13, 84, 55, 42, 0, 115, 114, 350, '2023-09-15', 'Fairing on well', 1, 118, '2023-09-15'),
(14, 84, 55, 42, 0, 115, 114, 350, '2023-09-15', 'Fairing on well', 1, 118, '2023-09-15'),
(15, 84, 55, 42, 0, 115, 114, 350, '2023-09-18', 'Remarks', 1, 1, '2023-09-18'),
(16, 84, 55, 42, 0, 115, 114, 350, '2023-09-18', 'remarks', 1, 1, '2023-09-18'),
(17, 84, 55, 42, 0, 115, 114, 350, '2023-09-18', '222', 1, 1, '2023-09-18'),
(18, 84, 55, 42, 0, 115, 114, 350, '2023-09-18', '44', 1, 1, '2023-09-18'),
(19, 84, 55, 42, 0, 115, 114, 350, '2023-09-18', '22', 1, 1, '2023-09-18'),
(20, 84, 55, 0, 0, 115, 0, 0, '2023-09-18', '222', 2, 1, '2023-09-18'),
(21, 84, 55, 0, 0, 115, 0, 0, '2023-09-18', '222', 2, 1, '2023-09-18'),
(22, 84, 55, 0, 0, 115, 0, 0, '2023-09-18', 'Remarks ', 2, 1, '2023-09-18'),
(23, 78, 37, 8, 357, 0, 0, 0, '2023-09-18', 'Testing', 2, 1, '2023-09-18'),
(24, 78, 37, 7, 409, 0, 0, 0, '2023-09-18', 'Remarks', 2, 1, '2023-09-18'),
(25, 78, 37, 8, 409, 0, 0, 0, '2023-09-18', 'Remarks', 2, 1, '2023-09-18'),
(26, 78, 37, 8, 357, 0, 0, 0, '2023-09-18', 'Remarks', 2, 1, '2023-09-18'),
(27, 84, 0, 0, 0, 0, 0, 0, '2023-10-12', 'Testing observations ', 5, 1, '2023-10-12'),
(28, 84, 0, 0, 0, 0, 0, 0, '2023-10-12', 'Testing', 5, 1, '2023-10-12');

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
(1, 1, '1', 0, NULL, 4640000000, NULL, NULL, '3', '2023-03-15 07:53:14'),
(2, 2, '1', 0, NULL, 6320000000, NULL, NULL, '3', '2023-03-15 12:18:25'),
(3, 3, '1', 0, NULL, 4000000000, NULL, NULL, '3', '2023-03-18 06:48:25'),
(4, 4, '1', 0, NULL, 3500000000, NULL, NULL, '3', '2023-03-21 09:25:08');

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
(1, 1, 1, 1, 2, 1020000000, '118', '2023-03-15 00:00:00'),
(2, 2, 2, 1, 2, 20000000, '121', '2023-03-15 00:00:00'),
(3, 2, 3, 1, 2, 41000000, '121', '2023-03-15 00:00:00'),
(4, 2, 5, 1, 2, 1000000000, '118', '2023-03-18 00:00:00'),
(5, 2, 6, 1, 2, 1100000000, '118', '2023-03-18 00:00:00'),
(6, 2, 7, 1, 2, 500000000, '118', '2023-03-18 00:00:00'),
(7, 4, 15, 1, 2, 240000000, '118', '2023-04-03 00:00:00'),
(8, 2, 58, 2, 2, 51, '1', '2023-04-18 00:00:00'),
(9, 2, 58, 1, 1, 23, '1', '2023-04-18 00:00:00'),
(10, 2, 58, 2, 2, 51, '1', '2023-04-18 00:00:00'),
(11, 2, 58, 1, 1, 23, '1', '2023-04-18 00:00:00'),
(12, 2, 59, 4, 4, 19, '1', '2023-04-18 00:00:00'),
(13, 2, 59, 2, 2, 5, '1', '2023-04-18 00:00:00'),
(14, 2, 59, 1, 1, 49, '1', '2023-04-18 00:00:00'),
(15, 3, 62, 4, 4, 12, '1', '2023-04-18 00:00:00'),
(16, 1, 66, 1, 1, 119998, '1', '2023-05-05 00:00:00'),
(17, 2, 69, 1, 1, 2000000, '1', '2023-05-11 00:00:00'),
(18, 2, 69, 2, 2, 40000000, '1', '2023-05-11 00:00:00'),
(19, 1, 70, 1, 1, 70000000, '124', '2023-05-30 00:00:00'),
(20, 3, 71, 2, 2, 50000000, '118', '2023-05-30 00:00:00'),
(21, 3, 71, 4, 4, 20000000, '118', '2023-05-30 00:00:00'),
(22, 10, 74, 1, 1, 50000000, '118', '2023-06-12 00:00:00'),
(23, 9, 75, 2, 2, 100000, '1', '2023-08-02 00:00:00'),
(24, 1, 77, 1, 1, 500000100, '118', '2023-08-02 00:00:00'),
(25, 1, 78, 1, 1, 300000000, '118', '2023-08-03 00:00:00'),
(26, 11, 81, 1, 1, 30000000, '118', '2023-08-07 00:00:00'),
(27, 2, 82, 1, 1, 50000000, '118', '2023-08-08 00:00:00'),
(28, 12, 85, 1, 1, 10000000, '118', '2023-08-09 00:00:00'),
(29, 15, 89, 1, 1, 9999995, '118', '2023-08-12 00:00:00'),
(30, 1, 79, 1, 1, 66000000, '118', '2023-08-14 00:00:00'),
(31, 16, 91, 1, 1, 2000000, '118', '2023-08-14 00:00:00'),
(32, 11, 93, 1, 1, 1000000, '118', '2023-08-18 00:00:00'),
(33, 1, 94, 2, 2, 3000000, '1', '2023-08-19 00:00:00'),
(34, 1, 95, 1, 1, 56000000, '118', '2023-08-21 00:00:00'),
(35, 2, 72, 2, 2, 999999, '1', '2023-08-24 00:00:00'),
(36, 2, 72, 2, 2, 999999, '1', '2023-08-24 00:00:00'),
(37, 2, 72, 1, 1, 999999, '1', '2023-08-24 00:00:00'),
(38, 2, 72, 1, 1, 9999998, '1', '2023-08-24 00:00:00'),
(39, 1, 96, 1, 1, 603000000, '118', '2023-08-24 00:00:00'),
(40, 4, 52, 1, 2, 15000000, '118', '2023-09-02 00:00:00'),
(41, 2, 97, 1, 1, 60000000, '118', '2023-09-03 00:00:00'),
(42, 11, 98, 2, 2, 5000000, '118', '2023-09-04 00:00:00'),
(43, 2, 100, 1, 1, 32000000, '118', '2023-09-06 00:00:00'),
(44, 12, 101, 1, 1, 5000000, '118', '2023-09-14 00:00:00'),
(45, 12, 84, 1, 1, 1000000, '118', '2023-09-14 00:00:00'),
(46, 12, 83, 1, 1, 2000000, '118', '2023-09-14 00:00:00'),
(47, 1, 109, 1, 1, 50000000, '118', '2023-09-28 00:00:00');

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
  `name` text NOT NULL,
  `role` int NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_myprojpartner`
--

INSERT INTO `tbl_myprojpartner` (`id`, `projid`, `name`, `role`, `description`, `created_by`, `created_at`) VALUES
(1, 58, 'Caldwell Lambert', 3, 'Earum dolorem sint a', '1', '2023-04-18'),
(2, 58, 'Lawrence Pennington', 3, 'Quam quis est dolor', '1', '2023-04-18'),
(3, 58, 'Inez Richards', 4, 'Incididunt temporibu', '1', '2023-04-18'),
(4, 59, 'Flynn Powell', 2, 'Voluptas sunt dolor ', '1', '2023-04-18'),
(5, 59, 'Willow Lawrence', 2, 'Fugiat voluptatibus', '1', '2023-04-18'),
(6, 62, 'Brennan Patrick', 5, 'Quis magna iste natu', '1', '2023-04-18'),
(7, 62, 'Levi Johnston', 5, 'Quod eu necessitatib', '1', '2023-04-18'),
(8, 62, 'Lucy Charles', 5, 'Id dolorem eius qui ', '1', '2023-04-18'),
(9, 66, 'Aim Global', 1, 'Maxime laudantium nesciunt modi pariatur. Numquam magnam tempore quisquam dolor rem quia dolores. Quae, repellendus magnam ex aliquid tenetur voluptate! Voluptatem quibusdam ratione velit vero placeat asperiores, adipisci minima atque dolor itaque voluptas ipsum, corrupti beatae nisi. Unde totam pariatur omnis neque accusamus, excepturi commodi officiis placeat mollitia amet?\r\n', '1', '2023-05-05'),
(10, 66, 'NRG', 3, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores, quos unde, quam odit eveniet sit labore beatae dolores architecto maxime eos aliquam praesentium magnam dignissimos nam eligendi quod autem fuga. Saepe vero culpa repudiandae non distinctio magnam delectus quia tempora veniam? Vitae mollitia, autem quaerat voluptatibus sapiente expedita nesciunt quos. Repellat sint sed eveniet commodi nihil tempora molestiae eum ducimus error ratione ipsam mollitia dolore esse suscipit, fuga consectetur, architecto laudantium cupiditate rerum? Enim obcaecati quidem magni quas numquam, exercitationem nemo sit, ipsum error doloremque explicabo possimus amet? Atque dicta alias inventore eveniet doloribus minima assumenda nam? Voluptas expedita laborum vitae debitis commodi perferendis doloribus animi, iste, incidunt harum cum quos vel necessitatibus. Officiis soluta inventore nisi asperiores quas? Debitis ducimus harum dolor adipisci dignissimos ipsum expedita quaerat consequuntur accusantium, tempora eos! Consectetur omnis reiciendis saepe provident voluptatibus enim neque nihil in quo suscipit, sint, consequuntur asperiores commodi libero, beatae magnam deleniti tenetur officiis eos aliquam explicabo vel quaerat. Quaerat vel ex atque nam obcaecati et, rerum, nesciunt dicta cupiditate quisquam corrupti neque esse ipsam consequuntur aut quam iure blanditiis quo vitae! Repellendus nihil, harum dolorum veniam quaerat a asperiores porro ipsa quae maxime fugit nobis aliquid nesciunt iusto error iste? Expedita, facilis maxime esse, at explicabo veritatis minus veniam eos vitae dolore accusantium mollitia ipsum possimus sunt sint unde molestias eum numquam. Ea cumque, rem, expedita error sequi quo assumenda est quod sit recusandae voluptates aliquid suscipit ducimus repellat libero asperiores sed necessitatibus officia aperiam velit quam debitis corporis similique. ', '1', '2023-05-05'),
(11, 69, 'Partner 1', 1, 'Description of partner 1', '1', '2023-05-11'),
(12, 71, 'Tester Partner One', 1, 'Doing ABC ', '118', '2023-05-30'),
(13, 75, 'Evans ', 2, 'Describe', '1', '2023-08-02'),
(14, 81, 'Tester Partner One', 1, 'Trainers', '118', '2023-08-07'),
(15, 89, 'Samsons', 1, 'lead in this project', '118', '2023-08-12'),
(16, 94, 'Evans ', 2, 'Descrobe ', '1', '2023-08-19'),
(17, 98, 'National Government', 3, 'Chief to assist in awareness and mobilization within wards', '118', '2023-09-04'),
(18, 109, 'Muthokinju Paints ', 2, 'Supply of paints/painting works ', '118', '2023-09-28');

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
(1, 1, 'Strategy 1', '118', '2023-03-14'),
(2, 2, 'Strategy 1', '118', '2023-03-14'),
(3, 3, 'Strategy 1', '118', '2023-03-14'),
(9, 9, 'Strategy 1', '118', '2023-03-14'),
(10, 10, 'Strategy 1', '118', '2023-03-14'),
(11, 6, 'Investments in efficient and reliable roads infrastructure', '118', '2023-03-18'),
(12, 6, 'Routine road maintenance programmes', '118', '2023-03-18'),
(13, 6, 'Construction of road inlets, outlets and by-passes', '118', '2023-03-18'),
(14, 6, 'Construction of bridges/box culverts', '118', '2023-03-18'),
(15, 6, 'Investment in reliable traffic signal infrastructure', '118', '2023-03-18'),
(16, 6, 'Expansion and relocation of bus bays and Lorry parks', '118', '2023-03-18'),
(17, 6, 'Installation and maintenance of street lights', '118', '2023-03-18'),
(18, 6, 'Construction of boda boda shades;', '118', '2023-03-18'),
(19, 11, 'Hospitals to promote importance', '118', '2023-08-12'),
(20, 11, 'Advertise the importance of immunization', '118', '2023-08-12'),
(21, 11, 'Construction of immunization centers', '118', '2023-08-12'),
(22, 3, 'construction of immunization centers', '118', '2023-08-12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_other_budget_lines_timelines`
--

CREATE TABLE `tbl_other_budget_lines_timelines` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `budget_line_id` int NOT NULL,
  `year` int NOT NULL,
  `budget` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `unique_key` varchar(255) NOT NULL,
  `projid` int DEFAULT NULL,
  `outputid` int NOT NULL,
  `outputstate` int NOT NULL,
  `output_site` varchar(255) NOT NULL DEFAULT '0',
  `sequence` int NOT NULL DEFAULT '0',
  `total_target` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_output_disaggregation`
--

INSERT INTO `tbl_output_disaggregation` (`id`, `unique_key`, `projid`, `outputid`, `outputstate`, `output_site`, `sequence`, `total_target`) VALUES
(1, 'FSi8I0kqDQ', 1, 1, 377, '0', 1, 45),
(2, 'FSi8I0kqDQ', 1, 2, 377, '0', 1, 6),
(3, 'FSi8I0kqDQ', 1, 3, 0, '1', 0, 1),
(4, 'FSi8I0kqDQ', 1, 3, 0, '2', 0, 1),
(5, 'hwtZpr18Ub', 2, 4, 0, '3', 0, 3),
(6, 'hwtZpr18Ub', 2, 4, 0, '4', 0, 3),
(7, 'hwtZpr18Ub', 2, 4, 0, '5', 0, 3),
(8, 'xjUbtFQxUg', 3, 5, 0, '6', 0, 2),
(9, 'xjUbtFQxUg', 3, 5, 0, '7', 0, 2),
(10, '41yEFQxnFt', 5, 6, 0, '10', 0, 1),
(11, '41yEFQxnFt', 5, 7, 0, '10', 0, 1),
(12, 'YBftO5sE3P', 6, 8, 0, '11', 0, 1),
(13, 'YBftO5sE3P', 6, 9, 374, '0', 0, 20),
(14, 'GAJK7tK7aS', 7, 10, 355, '0', 0, 20),
(15, 'VWlkgNWOAN', 15, 11, 0, '29', 0, 1),
(16, 'VWlkgNWOAN', 15, 11, 0, '25', 0, 1),
(17, 'VWlkgNWOAN', 15, 11, 0, '26', 0, 1),
(18, 'VWlkgNWOAN', 15, 11, 0, '27', 0, 1),
(19, 'VWlkgNWOAN', 15, 11, 0, '28', 0, 1),
(20, 'VWlkgNWOAN', 15, 12, 350, '0', 1, 2),
(21, 'VWlkgNWOAN', 15, 12, 351, '0', 3, 2),
(22, 'VWlkgNWOAN', 15, 12, 353, '0', 2, 3),
(23, 'AHW9vtpuiK', 53, 13, 0, '52', 0, 1),
(24, 'AHW9vtpuiK', 53, 13, 0, '54', 0, 1),
(25, 'AHW9vtpuiK', 53, 13, 0, '53', 0, 1),
(26, 'AHW9vtpuiK', 53, 13, 0, '55', 0, 1),
(27, 'rrNWy45hU3', 52, 14, 348, '0', 1, 2),
(28, 'rrNWy45hU3', 52, 14, 384, '0', 2, 3),
(29, 'B30xbZg1Yh', 54, 15, 357, '0', 1, 30),
(30, 'xNAXavKqT3', 55, 16, 400, '0', 1, 10),
(31, 'z3O1kH2OMX', 16, 17, 409, '0', 1, 2),
(32, 'z3O1kH2OMX', 16, 17, 357, '0', 2, 3),
(33, 'z3O1kH2OMX', 16, 18, 0, '19', 0, 1),
(34, 'z3O1kH2OMX', 16, 18, 0, '21', 0, 1),
(35, 'z3O1kH2OMX', 16, 18, 0, '22', 0, 1),
(36, '621WEzaUTf', 51, 19, 373, '0', 1, 3),
(37, 'Eoi140KNVE', 56, 20, 344, '0', 1, 10),
(38, 'KgcMXZwbVe', 58, 21, 3, '0', 0, 10),
(39, 'ULIRQxPl1Z', 62, 22, 407, '0', 1, 1),
(40, 'rwYx0yXrPk', 59, 23, 3, '0', 1, 2),
(41, 'cVZqhbcC3u', 66, 24, 339, '0', 1, 12),
(42, '5vAmYsQc3r', 69, 25, 0, '65', 0, 2),
(43, '5vAmYsQc3r', 69, 25, 0, '66', 0, 12),
(44, '5vAmYsQc3r', 69, 25, 0, '67', 0, 10),
(45, '5vAmYsQc3r', 69, 26, 0, '65', 0, 2),
(46, '5vAmYsQc3r', 69, 26, 0, '67', 0, 2),
(47, 'LOEb1fECU7', 70, 27, 377, '0', 1, 20),
(48, 'LOEb1fECU7', 70, 27, 378, '0', 2, 9),
(49, 'LOEb1fECU7', 70, 28, 0, '69', 0, 1),
(50, 'oxH7TzGQFE', 71, 29, 0, '70', 0, 30),
(51, 'S6jORghkYv', 72, 30, 0, '71', 0, 1),
(52, 'S6jORghkYv', 72, 30, 0, '72', 0, 1),
(53, 'grs4zZ4fbX', 74, 31, 0, '73', 0, 1),
(54, 'grs4zZ4fbX', 74, 31, 0, '74', 0, 1),
(55, 'grs4zZ4fbX', 74, 31, 0, '75', 0, 1),
(56, 'grs4zZ4fbX', 74, 31, 0, '76', 0, 1),
(78, 's55pZXfG9W', 75, 32, 341, '0', 1, 5),
(77, 's55pZXfG9W', 75, 32, 339, '0', 0, 5),
(79, '9u90JNqRjm', 76, 33, 396, '0', 0, 4),
(80, '9u90JNqRjm', 76, 33, 397, '0', 1, 3),
(81, '9u90JNqRjm', 76, 33, 399, '0', 2, 3),
(82, '9u90JNqRjm', 76, 34, 0, '83', 0, 2),
(83, '9u90JNqRjm', 76, 34, 0, '88', 0, 2),
(84, 'NgolZqjA8v', 77, 35, 373, '0', 1, 10),
(85, 'NgolZqjA8v', 77, 36, 0, '89', 0, 1),
(86, 'NgolZqjA8v', 77, 36, 0, '90', 0, 1),
(87, 'NgolZqjA8v', 77, 36, 0, '91', 0, 1),
(88, 'tIWZztgaTX', 78, 37, 409, '0', 1, 13),
(89, 'tIWZztgaTX', 78, 37, 357, '0', 2, 7),
(90, 'tIWZztgaTX', 78, 38, 0, '92', 0, 1),
(91, 'tIWZztgaTX', 78, 38, 0, '93', 0, 1),
(92, 'tIWZztgaTX', 78, 38, 0, '95', 0, 1),
(93, 'tIWZztgaTX', 78, 38, 0, '96', 0, 1),
(94, 'tIWZztgaTX', 78, 38, 0, '98', 0, 1),
(95, 'oImnDqM0QV', 79, 39, 348, '0', 1, 30),
(96, 'oImnDqM0QV', 79, 40, 0, '102', 0, 1),
(97, 'oImnDqM0QV', 79, 40, 0, '103', 0, 1),
(98, 'oImnDqM0QV', 79, 40, 0, '104', 0, 1),
(99, 'TLVvNyyn5J', 81, 41, 3, '0', 0, 300),
(100, 'kbPDExHFLh', 82, 42, 0, '111', 0, 1),
(101, 'kbPDExHFLh', 82, 42, 0, '112', 0, 1),
(102, 'kbPDExHFLh', 82, 42, 0, '113', 0, 1),
(103, 'bJtLMRcyXQ', 85, 43, 0, '116', 0, 1),
(104, 'TAEOEOUmz5', 86, 44, 0, '117', 0, 1),
(105, 'zTG4DUvCqB', 87, 45, 0, '118', 0, 1),
(106, '3WjADBEccC', 88, 46, 0, '119', 0, 1),
(107, 'y1lfFIxza0', 89, 47, 0, '120', 0, 1),
(108, 'cuepEVj5V9', 91, 48, 0, '122', 0, 20),
(109, 'AXcBjiOMW4', 93, 49, 409, '0', 0, 25),
(110, 'AXcBjiOMW4', 93, 49, 357, '0', 0, 25),
(111, 'AXcBjiOMW4', 93, 49, 358, '0', 0, 25),
(112, 'AXcBjiOMW4', 93, 49, 366, '0', 0, 25),
(113, 'Sve871xHqm', 94, 50, 321, '0', 0, 1),
(114, 'Sve871xHqm', 94, 50, 322, '0', 1, 1),
(115, 'hOSj3SufS9', 95, 51, 335, '0', 1, 15),
(116, 'hOSj3SufS9', 95, 51, 338, '0', 2, 10),
(117, 'hOSj3SufS9', 95, 52, 0, '130', 0, 1),
(118, 'hOSj3SufS9', 95, 52, 0, '131', 0, 1),
(119, 'hOSj3SufS9', 95, 52, 0, '132', 0, 1),
(120, 'hOSj3SufS9', 95, 52, 0, '133', 0, 1),
(121, 'HO03OC5cCZ', 96, 53, 321, '0', 1, 10),
(127, 'HO03OC5cCZ', 96, 54, 0, '137', 0, 1),
(126, 'HO03OC5cCZ', 96, 54, 0, '136', 0, 1),
(141, 'WWpkgR1rTc', 84, 55, 0, '115', 0, 1),
(129, 'u6lowGwJ0U', 97, 56, 0, '138', 0, 2),
(130, 'u6lowGwJ0U', 97, 56, 0, '139', 0, 2),
(131, 'u6lowGwJ0U', 97, 56, 0, '140', 0, 2),
(132, 'F0MG3frYuQ', 98, 57, 326, '0', 0, 25),
(133, 'F0MG3frYuQ', 98, 57, 327, '0', 0, 25),
(134, 'F0MG3frYuQ', 98, 57, 336, '0', 0, 25),
(135, 'F0MG3frYuQ', 98, 57, 337, '0', 0, 25),
(136, 'K1ISaJJSer', 100, 58, 0, '141', 0, 1),
(137, 'K1ISaJJSer', 100, 58, 0, '142', 0, 1),
(138, 'K1ISaJJSer', 100, 58, 0, '143', 0, 1),
(140, 'vkPmOPwFqX', 101, 59, 0, '144', 0, 1),
(145, '7q0NgFM0MC', 83, 60, 0, '135', 0, 1),
(144, '7q0NgFM0MC', 83, 60, 0, '134', 0, 1),
(146, 'L2qcQMGofg', 80, 61, 319, '0', 1, 10),
(155, 'L2qcQMGofg', 80, 62, 0, '107', 0, 1),
(154, 'L2qcQMGofg', 80, 62, 0, '106', 0, 1),
(153, 'L2qcQMGofg', 80, 62, 0, '105', 0, 1),
(156, 'qh2HEcaEts', 105, 63, 331, '0', 5, 10),
(157, 'qh2HEcaEts', 105, 64, 0, '146', 0, 1),
(158, 'qh2HEcaEts', 105, 64, 0, '147', 0, 1),
(159, 'qh2HEcaEts', 105, 64, 0, '148', 0, 1),
(167, 'P9w0dm4qqR', 109, 65, 321, '0', 1, 1),
(166, 'P9w0dm4qqR', 109, 65, 331, '0', 2, 4),
(168, 'P9w0dm4qqR', 109, 66, 321, '0', 1, 4),
(169, 'P9w0dm4qqR', 109, 66, 331, '0', 2, 6),
(170, 'P9w0dm4qqR', 109, 67, 0, '153', 0, 1),
(171, 'P9w0dm4qqR', 109, 67, 0, '154', 0, 1),
(172, 'P9w0dm4qqR', 109, 67, 0, '155', 0, 1),
(173, 'P9w0dm4qqR', 109, 67, 0, '156', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_output_disaggregation_values`
--

CREATE TABLE `tbl_output_disaggregation_values` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL,
  `dissaggregation` text NOT NULL,
  `dissaggregation_target` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_output_disaggregation_values`
--

INSERT INTO `tbl_output_disaggregation_values` (`id`, `projid`, `output_id`, `dissaggregation`, `dissaggregation_target`) VALUES
(11, 75, 32, 'test of the test ', 8),
(12, 76, 33, '1murram', 7),
(13, 76, 33, 'tarmac', 3),
(14, 76, 34, '1murram', 2),
(15, 76, 34, '1murram vvv', 2),
(16, 81, 41, 'ABC 1', 200),
(17, 81, 41, 'ABC 2', 100),
(18, 86, 44, 'hall', 1),
(19, 91, 48, 'diwa sub location', 4),
(20, 91, 48, 'miwa sub location', 4),
(21, 91, 48, 'siwa sub location', 4),
(22, 91, 48, 'niwa sub location', 4),
(23, 91, 48, 'piwa sub location', 4),
(30, 80, 62, 'Dissagggregation 1', 1),
(31, 80, 62, 'Dissagggregation 2', 1),
(32, 80, 62, 'Dissagggregation 3', 1);

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
-- Table structure for table `tbl_pages`
--

CREATE TABLE `tbl_pages` (
  `id` int NOT NULL,
  `parent` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `workflow_stage` int NOT NULL DEFAULT '0',
  `allow_read` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_pages`
--

INSERT INTO `tbl_pages` (`id`, `parent`, `name`, `url`, `icon`, `priority`, `workflow_stage`, `allow_read`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 0, 'Dashboard', 'Gen-dash', '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', 1, 0, 0, 1, 1, NULL, '1', NULL),
(2, 0, 'Plans', 'pans', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 2, 0, 0, 1, 1, NULL, '1', NULL),
(3, 0, 'Master Data', 'master-data', '<i class=\"fa fa-database\" style=\"color:white\"></i>', 3, 0, 0, 1, 1, 118, '1', '09-08-2023'),
(4, 0, 'Project Management ', 'page-management', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 4, 0, 0, 1, 1, NULL, '1', NULL),
(5, 0, 'M&E ', 'mne', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 5, 0, 0, 1, 1, NULL, '1', NULL),
(6, 0, 'Payment ', 'payment', '<i class=\"fa fa-money\" style=\"color:white\"></i>', 7, 0, 0, 1, 1, NULL, '1', NULL),
(7, 0, 'Partners ', 'partners', '<i class=\"fa fa-slideshare\" style=\"color:white\"></i>', 8, 0, 0, 1, 1, NULL, '1', NULL),
(8, 0, 'Contractors', 'contractors', ' <i class=\"fa fa-puzzle-piece\" style=\"color:white\"></i>', 9, 0, 0, 1, 1, NULL, '1', NULL),
(9, 0, 'Personnel', 'personne', ' <i class=\"fa fa-users\" style=\"color:white\"></i>', 10, 0, 0, 1, 1, NULL, '1', NULL),
(10, 0, 'Files ', 'file', '<i class=\"fa fa-folder-open-o\" style=\"color:white\"></i>', 12, 0, 0, 1, 1, NULL, '1', NULL),
(11, 0, 'Reports', 'reports', ' <i class=\"fa fa-file-text-o\" aria-hidden=\"true\" style=\"color:white\"></i>', 13, 0, 0, 1, 1, NULL, '1', NULL),
(12, 0, 'Indicators ', 'indicator', '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', 6, 0, 0, 1, 1, NULL, '1', NULL),
(13, 0, 'Quality Standards', 'quality-standards', ' <i class=\"fa fa-certificate\" style=\"color:white\"></i>', 11, 0, 0, 1, 1, 1, '1', '23-05-2023'),
(14, 0, 'Settings ', 'settings', '<i class=\"fa fa-cog fa-spin\" style=\"font-size:16px; color:red;\"></i>', 14, 0, 0, 1, 1, NULL, '1', NULL),
(15, 1, 'Main Dashboard', 'dashboard', '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', 1, 0, 0, 1, 1, 118, '1', '18-09-2023'),
(16, 1, 'Financial Dashboard', 'view-financial-dashboard', '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', 3, 0, 0, 1, 1, 118, '1', '18-09-2023'),
(17, 1, 'Projects Dashboard', 'projects', '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', 4, 0, 0, 1, 1, 118, '1', '18-09-2023'),
(18, 1, 'GIS Dashboard', 'view-indicator-map-dashboard', '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', 2, 0, 0, 1, 1, 118, '1', '18-09-2023'),
(19, 2, 'CIDPs', 'view-strategic-plans', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 0, 1, 1, 1, '1', '02-10-2023'),
(20, 2, 'Annual Plans (ADPs)', 'view-adps', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 2, 0, 0, 1, 1, 1, '1', '14-04-2023'),
(21, 2, 'Program Based Budgets ', 'programs-adp', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 3, 0, 0, 1, 1, NULL, '1', NULL),
(22, 2, ' Independent Programs', 'all-programs', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 4, 0, 0, 1, 1, 1, '1', '14-04-2023'),
(23, 3, 'Add Activities', 'add-project-activities', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 0, 1, 0, 1, 1, 118, '1', '09-08-2023'),
(24, 3, ' Add Financial Plan', 'add-project-financial-plan', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 2, 3, 0, 1, 1, 1, '1', '04-08-2023'),
(25, 3, 'Add Procurement Details', 'add-project-procurement-details', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 3, 4, 0, 1, 1, 1, '1', '08-08-2023'),
(26, 3, 'Add M&E Plan', 'view-mne-plan', '<i class=\"fa fa-map\" style=\"color:white\"></i>', 5, 6, 0, 1, 1, 1, '1', '29-09-2023'),
(27, 27, 'Add Monitoring Checklist', 'add-project-monitoring-checklist', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 20, 5, 0, 0, 1, 1, '1', '28-08-2023'),
(28, 3, 'Add Mapping Details ', 'project-mapping', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 7, 8, 0, 1, 1, 1, '1', '30-09-2023'),
(29, 3, 'Add Team', 'add-project-team', '<i class=\"fa fa-users\" style=\"color:white\"></i>', 4, 5, 0, 1, 1, 1, '1', '29-09-2023'),
(30, 3, 'Add Program of Works', 'add-program-of-works', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 8, 7, 0, 1, 1, 1, '1', '28-08-2023'),
(31, 4, 'My Projects', 'myprojects', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, 1, '1', '29-03-2023'),
(32, 5, 'Inspection & Acceptance', 'general-project-progress', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 5, 10, 0, 1, 1, 1, '1', '11-10-2023'),
(33, 4, 'Issue Log', 'projects-issues', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, NULL, '1', NULL),
(34, 4, 'Escalated Issues', 'projects-escalated-issues', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, NULL, '1', NULL),
(35, 4, 'Risk Categories', 'view-risk-categories', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, NULL, '1', NULL),
(36, 4, 'Risk Mitigations', 'view-risk-mitigation', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, NULL, '1', NULL),
(37, 4, 'Projects Team', 'view-project-team', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, NULL, '1', NULL),
(38, 5, 'Activities Monitoring', 'project-output-monitoring-checklist', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 1, 9, 0, 1, 1, 1, '1', '31-08-2023'),
(39, 5, 'Output Monitoring', 'projects-monitoring', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 2, 10, 0, 1, 1, 1, '1', '22-09-2023'),
(40, 5, 'Outcome Evaluation', 'view-project-survey', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 3, 0, 0, 1, 1, NULL, '1', NULL),
(41, 5, 'Impact Evaluation', 'view-project-impact-evaluation', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 4, 0, 0, 1, 1, NULL, '1', NULL),
(42, 11, 'M&E Reports', 'mne-reports', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 5, 0, 0, 1, 1, 118, '1', '18-09-2023'),
(43, 6, 'Payment Requests', 'contractor-payment-requests', '<i class=\"fa fa-money\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, NULL, '1', NULL),
(44, 6, 'Payment Approval', 'contractor-payment-approvals', '<i class=\"fa fa-money\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, NULL, '1', NULL),
(45, 6, 'Payment Disbursement', 'contractor-payment-disbursements', '<i class=\"fa fa-money\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, 1, '1', '29-03-2023'),
(46, 7, 'Partners', 'view-financiers', '<i class=\"fa fa-slideshare\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, NULL, '1', NULL),
(47, 8, 'Manage Contractors', 'view-contractors', '<i class=\"fa fa-puzzle-piece\" style=\"color:white\"></i>', 0, 0, 1, 1, 1, 1, '1', '08-04-2023'),
(48, 9, 'View Personnel', 'view-members', '<i class=\"fa fa-users\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, NULL, '1', NULL),
(49, 9, 'Add Personnel', 'add-member', '<i class=\"fa fa-users\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, NULL, '1', NULL),
(50, 9, 'Leave Requests', 'leave-requests', '<i class=\"fa fa-users\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, 1, '1', '29-03-2023'),
(51, 10, 'View All Files', 'view-all-files', '<i class=\"fa fa-folder-open-o\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, NULL, '1', NULL),
(52, 11, 'C-APR Report', 'view-objective-performance', '<i class=\"fa fa-file-text-o\" aria-hidden=\"true\" style=\"color:white\"></i>', 1, 0, 0, 1, 1, 118, '1', '18-09-2023'),
(53, 11, 'Quartely Progress Report', 'output-indicators-quarterly-progress-report', '<i class=\"fa fa-file-text-o\" aria-hidden=\"true\" style=\"color:white\"></i>', 2, 0, 0, 1, 1, 1, '1', '09-05-2023'),
(54, 11, 'Projects Performance Report', 'project-indicators-tracking-table', '<i class=\"fa fa-file-text-o\" aria-hidden=\"true\" style=\"color:white\"></i>', 3, 0, 0, 1, 1, 1, '1', '09-05-2023'),
(55, 12, 'Indicators', 'view-indicators', '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, 1, '1', '03-04-2023'),
(56, 12, 'Measurement Units', 'view-measurement-units', ' <i class=\"fa fa-microchip\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, 1, '1', '29-03-2023'),
(57, 13, 'View Standards', 'view-standards-categories', ' <i class=\"fa fa-certificate\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, 1, '1', '23-05-2023'),
(58, 14, 'Organization Details', 'organization-details', '<i class=\"fa fa-cog fa-spin\" style=\"font-size:16px; color:red;\"></i>', 1, 0, 0, 1, 1, 1, '1', '08-04-2023'),
(59, 14, 'Add/View Sections', 'view-sectors', '<i class=\"fa fa-cog fa-spin\" style=\"font-size:16px; color:red;\"></i>', 2, 0, 0, 1, 1, 1, '1', '29-03-2023'),
(60, 14, 'Add/View Locations', 'locations', '<i class=\"fa fa-cog fa-spin\" style=\"font-size:16px; color:red;\"></i>', 3, 0, 0, 1, 1, NULL, '1', NULL),
(61, 15, 'Dashboard Projects', 'view-dashboard-projects', '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, 1, '1', '09-04-2023'),
(62, 19, 'Strategic Plan Framework', 'view-strategic-plan-framework', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, 1, '1', '09-04-2023'),
(63, 63, 'Email Configurations', 'email-configuration', '<i class=\"fa fa-cog fa-spin\" style=\"font-size:16px; color:red;\"></i>', 1, 0, 0, 1, 1, 118, '1', '19-08-2023'),
(64, 55, 'Output Indicator', 'add-output-indicator', '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, NULL, '1', NULL),
(65, 55, 'Outcome Indicator', ' add-outcome-indicator', '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', 2, 0, 1, 1, 1, NULL, '1', NULL),
(66, 55, 'Impact Indicators', 'add-impact-indicator', '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', 3, 0, 1, 1, 1, 1, '1', '01-05-2023'),
(67, 55, 'Edit Output Indicator', 'edit-output-indicator', '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', 4, 0, 1, 1, 1, NULL, '1', NULL),
(68, 55, 'Edit Outcome', 'edit-outcome-indicator', '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', 5, 0, 1, 1, 1, NULL, '1', NULL),
(69, 55, 'Edit Impact Indicator', 'edit-impact-indicator', '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', 6, 0, 1, 1, 1, NULL, '1', NULL),
(70, 55, 'Output Indicator Baseline', 'indicator-existing-baseline-data', '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', 7, 0, 1, 1, 1, NULL, '1', NULL),
(71, 51, 'Project Files', 'view-project-files', '<i class=\"fa fa-folder-open-o\" style=\"color:white\"></i>', 2, 0, 1, 0, 1, NULL, '1', NULL),
(72, 48, 'Personnel Projects', 'view-member-projects', ' <i class=\"fa fa-users\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(73, 48, 'Personnel Info', 'view-member-info', ' <i class=\"fa fa-users\" style=\"color:white\"></i>', 3, 0, 1, 0, 1, NULL, '1', NULL),
(74, 47, 'Add Contractor', 'add-contractor', ' <i class=\"fa fa-puzzle-piece\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, NULL, '1', NULL),
(75, 47, 'Contractor Info', 'view-contractor-info', ' <i class=\"fa fa-puzzle-piece\" style=\"color:white\"></i>', 3, 0, 1, 1, 1, NULL, '1', NULL),
(76, 46, 'Funding', 'view-funding', '<i class=\"fa fa-slideshare\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, NULL, '1', NULL),
(77, 45, 'In-house Payments', 'inhouse-payment-disbursements', '<i class=\"fa fa-money\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(78, 44, 'Inhouse Payment Approvals', 'inhouse-payment-approvals', '<i class=\"fa fa-money\" style=\"color:white\"></i>', 2, 0, 1, 0, 1, NULL, '1', NULL),
(79, 43, 'Inhouse Payment Requests', 'inhouse-payment-requests', '<i class=\"fa fa-money\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(80, 42, 'MNE Report', 'project-mne-report', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(81, 41, 'Impact Secondary', 'impact-evaluation-secondary-data-source', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(82, 40, 'Outcome Secondary Evaluation', 'evaluation-secondary-data-source', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, NULL, '1', NULL),
(83, 38, 'Activities Monitoring', 'project-monitoring', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 1, 9, 0, 1, 1, 1, '1', '31-08-2023'),
(84, 38, 'Project Activities Monitoring Details', 'myprojectdash', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 0, 0, 1, 118, '1', '20-09-2023'),
(85, 31, 'Contractor Details', 'view-project-contractor-info', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(86, 38, 'Progress', 'myprojectmilestones', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 0, 0, 1, 118, '1', '21-09-2023'),
(87, 31, 'Financial Plan', 'myprojectfinancialplan', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(88, 38, 'Key Stakeholders', 'myproject-key-stakeholders', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 0, 0, 1, 118, '1', '21-09-2023'),
(89, 38, 'Issues', 'my-project-issues', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 0, 0, 1, 118, '1', '21-09-2023'),
(90, 38, 'My Project Files', 'myprojectfiles', '<i class=\"fa fa-book\" aria-hidden=\"true\" style=\"color:white\"></i>', 1, 0, 0, 0, 1, 118, '1', '21-09-2023'),
(91, 32, 'Quality Assurance', 'project-inspection', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(92, 32, 'Quality Assurance', 'specification-inspection', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(93, 33, 'Issue Log', 'projectissueslist', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(94, 35, 'Add Risk Category', 'add-risk-category', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, NULL, '1', NULL),
(95, 36, 'Risk Mitigation', 'edit-risk-mitigation', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, NULL, '1', NULL),
(96, 36, 'Risk Mitigation ', 'view-risk-mitigation', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(97, 36, 'Risk Mitigation Add', 'add-risk-mitigation', '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(98, 19, 'Add Strategic Plan', 'add-strategic-plan', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, 1, '1', '09-04-2023'),
(99, 19, 'Key Result Areas', 'view-kra', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, NULL, '1', NULL),
(100, 19, 'Strategic Objectives', 'view-strategic-plan-objectives', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, NULL, '1', NULL),
(101, 19, 'Strategic Plan Programs', 'view-program', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(102, 19, 'Strategic Plan Projects', 'strategic-plan-projects', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 0, 1, 1, 1, '1', '13-04-2023'),
(103, 19, 'Implementation Matrix', 'strategic-plan-implementation-matrix', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, NULL, '1', NULL),
(104, 19, 'Add Program', 'add-program', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(105, 19, 'Edit Strategic Plan', 'edit-strategic-plan', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(106, 19, 'Add Strategic Objective', 'add-objective', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, NULL, '1', NULL),
(107, 19, 'Strategic Objective Programs', 'view-strategicplan-programs', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(108, 19, 'Program Projects', 'view-project', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(109, 22, 'Edit Program ', 'edit-program', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(110, 22, 'Add Project', 'add-project', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(111, 22, 'Add Outputs', 'add-project-outputs', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(112, 23, 'Add Activities', 'add-project-output-designs', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(113, 23, 'Add Activities', 'add-activities', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(114, 23, 'Add Specifications', 'add-specifications', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(115, 24, 'Add Financial Plan', 'add-financial-plan', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 2, 0, 1, 1, 1, '1', '05-08-2023'),
(116, 25, 'Add Procurement Details', 'add-procurement-details', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 0, 0, 1, 1, 1, 1, '1', '05-05-2023'),
(117, 25, 'Add Procurement ', 'add-procurement-plan', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 0, 1, 1, 1, 1, '1', '05-05-2023'),
(118, 15, 'Project Dashboard', 'project-dashboard', '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', 1, 0, 0, 0, 1, 118, '1', '20-09-2023'),
(119, 15, 'Project Indicators', 'project-indicators', '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', 1, 0, 0, 0, 1, 118, '1', '20-09-2023'),
(120, 15, 'Project GIS Map', 'project-map', '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', 1, 0, 0, 0, 1, 118, '1', '20-09-2023'),
(121, 15, 'Project Gallery', 'project-gallery', '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', 1, 0, 0, 0, 1, 118, '1', '20-09-2023'),
(122, 31, 'Project Gallery', 'view-project-gallery', '<i class=\"fa fa-image\" style=\"color:white\"></i>', 0, 0, 0, 1, 1, 1, '1', '12-04-2023'),
(123, 26, 'Add M&E Plan', 'add-project-mne-plan', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 0, 4, 1, 0, 1, NULL, '1', NULL),
(124, 27, 'Monitoring Checklist', 'add-project-design-checklist', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 0, 0, 1, 0, 1, NULL, '1', NULL),
(125, 29, 'Add Team', 'add-team', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 0, 7, 0, 1, 1, 1, '1', '22-09-2023'),
(126, 28, 'Add Project Map', 'add-project-mapping', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 0, 5, 1, 0, 1, NULL, '1', NULL),
(127, 28, 'Add Map', 'add-map-data-automatically', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 0, 5, 0, 1, 1, 1, '1', '26-04-2023'),
(128, 28, 'Add map', 'add-map-data-manual', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 0, 5, 1, 0, 1, NULL, '1', NULL),
(129, 46, 'Project Financiers', 'view-financier-projects', '<i class=\"fa fa-slideshare\" style=\"color:white\"></i>', 0, 5, 1, 1, 1, NULL, '1', NULL),
(130, 46, 'Financier Info', 'view-financier-info', ' <i class=\"fa fa-puzzle-piece\" style=\"color:white\"></i>', 0, 0, 1, 1, 1, NULL, '1', NULL),
(131, 46, 'Financier Funds', 'view-financier-funds', '<i class=\"fa fa-slideshare\" style=\"color:white\"></i>', 0, 0, 1, 1, 1, NULL, '1', NULL),
(132, 46, 'Funds ', 'add-development-funds', '<i class=\"fa fa-slideshare\" style=\"color:white\"></i>', 0, 0, 1, 0, 1, NULL, '1', NULL),
(133, 46, 'Add Financier', 'add-financier', '<i class=\"fa fa-slideshare\" style=\"color:white\"></i>', 0, 0, 1, 0, 1, NULL, '1', NULL),
(134, 46, 'Edit Financier', 'edit-financier', '<i class=\"fa fa-slideshare\" style=\"color:white\"></i>', 0, 0, 1, 0, 1, NULL, '1', NULL),
(135, 42, 'M&E Disaggregation Report', 'project-mne-disaggregation-report', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 0, 0, 1, 0, 1, NULL, '1', NULL),
(136, 55, 'Outcome Indicator', 'add-outcome-indicator', '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', 0, 0, 1, 0, 1, NULL, '1', NULL),
(137, 27, 'Project Activities Checklist', 'add-project-monitoring-design-checklist', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 0, 4, 1, 0, 1, NULL, '1', NULL),
(138, 23, 'Project Milestones', 'add-project-milestone', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 0, 1, 1, 0, 1, NULL, '1', NULL),
(139, 3, 'Add Milestones', 'add-project-milestones', '<i class=\"fa fa-signal\" aria-hidden=\"true\" style=\"color:white\"></i>', 1, 2, 0, 1, 1, 118, '1', '09-08-2023'),
(140, 139, 'Milestone', 'add-milestone', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 2, 0, 1, 1, 1, '1', '02-08-2023'),
(141, 139, 'Milestone', 'add-milestone-output-activities', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 1, 0, 1, 1, 1, '1', '03-08-2023'),
(142, 142, 'Add Inspection Checklist', 'add-project-inspection-checklist', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 5, 5, 0, 0, 1, 1, '1', '28-08-2023'),
(143, 142, 'Add Inspection Checklist', 'add-project-output-inspection-checklist', '<i class=\"fa fa-columns\" style=\"color:white\"></i>', 1, 5, 0, 1, 1, 1, '1', '09-08-2023'),
(144, 30, 'Program of Works', 'add-work-program', '<i class=\"fa fa-database\" style=\"color:white\"></i>', 1, 0, 0, 1, 1, 1, '1', '08-09-2023'),
(145, 58, 'Main Menu', 'global-configuration', '<i class=\"fa fa-cog fa-spin\" style=\"font-size:16px; color:red;\"></i>', 1, 0, 0, 1, 1, 1, '1', '28-08-2023'),
(146, 40, 'Project Survey Form', 'create-project-survey-form', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 0, 5, 0, 0, 118, 118, '118', '04-10-2023'),
(147, 0, 'System Terminologies', 'system-terminologies', '<i class=\"fa fa-cog fa-spin\" style=\"font-size:16px; color:red;\"></i>', 2, 0, 1, 0, 118, NULL, '118', NULL),
(148, 0, 'Email Configuration', 'email_configuration', '<i class=\"fa fa-envelope-o\" style=\"color:white\"></i>', 4, 0, 1, 0, 118, NULL, '118', NULL),
(149, 0, 'Email Templates', 'email_templates', '<i class=\"fa fa-envelope\" style=\"color:white\"></i>', 5, 0, 1, 0, 118, NULL, '118', NULL),
(150, 0, 'View Survey Data', 'view-survey-data', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 1, 0, 1, 0, 118, NULL, '118', NULL),
(151, 0, 'Survey Conclusion', 'survey-conclusion', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 0, 10, 1, 0, 118, NULL, '118', NULL),
(152, 15, 'Project Issues', 'project-issues', '<i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>', 5, 0, 0, 0, 118, 118, '118', '20-09-2023'),
(153, 15, 'Project Team Members', 'project-team-members', '<i class=\"fa fa-users\" aria-hidden=\"true\"></i>', 0, 0, 0, 0, 118, 118, '118', '20-09-2023'),
(154, 15, 'Project Finance', 'project-finance', '<i class=\"fa fa-money\" aria-hidden=\"true\"></i>', 0, 0, 0, 0, 118, 118, '118', '20-09-2023'),
(155, 15, 'Project Timeline', 'project-timeline', '<i class=\"fa fa-calendar\" aria-hidden=\"true\"></i>', 0, 0, 0, 0, 118, 118, '118', '20-09-2023'),
(156, 15, 'Project Media', 'project-media', '<i class=\"fa fa-book\" aria-hidden=\"true\"></i>', 0, 0, 0, 0, 118, NULL, '118', NULL),
(157, 46, 'Financier Status', 'view-financier-status', '<i class=\"fa fa-slideshare\" style=\"color:white\"></i>', 0, 0, 1, 1, 118, NULL, '118', NULL),
(158, 4, 'Project Risk Plan', 'project-risk-plan', '<i class=\"fa fa-exclamation-triangle\" style=\"color:yellow\"></i>', 1, 0, 0, 1, 1, 118, '1', '10-10-2023'),
(159, 15, 'Project Contract Details', 'project-contract-details', '<i class=\"fa fa-file-text\" aria-hidden=\"true\"></i>', 0, 0, 1, 0, 118, NULL, '118', NULL),
(160, 53, 'Report', 'view-qpr-report', ' <i class=\"fa fa-file-text-o\" aria-hidden=\"true\" style=\"color:white\"></i>', 1, 0, 1, 0, 1, NULL, '1', NULL),
(161, 43, 'Payment Request', 'inhouse-payment-request', '<i class=\"fa fa-money\" style=\"color:white\"></i>', 1, 0, 0, 1, 1, 1, '1', '02-10-2023'),
(162, 40, 'Survey Form Preview', 'preview-project-survey-form', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 0, 0, 1, 0, 118, NULL, '118', NULL),
(163, 40, 'Survey Form Form', 'deploy-survey-form', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 0, 0, 0, 0, 118, NULL, '118', NULL),
(164, 40, 'Secondary Data Source Evaluation', 'secondary-data-evaluation', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 0, 9, 0, 0, 118, NULL, '118', NULL),
(165, 3, 'Add Risk Plan', 'add-project-risk-plan', '<i class=\"fa fa-exclamation-triangle\" style=\"color:yellow\"></i>', 6, 7, 0, 0, 118, 118, '118', '14-10-2023'),
(166, 165, 'Add Project Risk Details', 'add-project-risks', '<i class=\"fa fa-exclamation-triangle\" style=\"color:yellow\"></i>', 0, 7, 0, 0, 118, NULL, '118', NULL),
(167, 38, 'Monitoring Observations', 'project-monitoring-observations', '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', 2, 10, 1, 0, 1, NULL, '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_page_actions`
--

CREATE TABLE `tbl_page_actions` (
  `id` int NOT NULL,
  `sidebar_id` int NOT NULL,
  `action` varchar(255) NOT NULL,
  `role_group` varchar(255) NOT NULL,
  `designation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ministry` varchar(255) DEFAULT NULL,
  `sector` varchar(255) DEFAULT NULL,
  `directorate` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_page_actions`
--

INSERT INTO `tbl_page_actions` (`id`, `sidebar_id`, `action`, `role_group`, `designation`, `ministry`, `sector`, `directorate`, `status`) VALUES
(1, 23, 'add_project', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(2, 20, 'edit', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(3, 20, 'delete', '2,4', '1,7', NULL, NULL, NULL, 1),
(5, 91, 'add', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '41', 1),
(6, 91, 'add_objective', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '41', 1),
(7, 91, 'delete', '1,4', '1,7', '10', '18', '41', 1),
(8, 90, 'add', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '41', 1),
(9, 90, 'edit', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '41', 1),
(10, 90, 'add_program', '2', '7,8,9,10,11,12,13', NULL, '', '41', 1),
(11, 90, 'add_strategy', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '41', 1),
(12, 90, 'delete', '1,4', '1,7', '10', '18', '41', 1),
(13, 91, 'edit', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '41', 1),
(14, 49, 'delete', '2,4', '6,7,1', NULL, NULL, NULL, 1),
(15, 49, 'edit', '2,4', '6,7,1', NULL, NULL, NULL, 1),
(16, 65, 'leave', '2,4', '7,1', NULL, NULL, NULL, 1),
(17, 46, 'add_output', '1,4', '7,1', '10', '18', '42', 1),
(18, 46, 'edit_output', '1,4', '7,1', '10', '18', '42', 1),
(19, 46, 'delete_output', '1,4', '1,7', '10', '18', '42', 1),
(20, 46, 'add_outcome', '1,4', '7,1', '10', '18', '42', 1),
(21, 46, 'edit_outcome', '1,4', '7,1', '10', '18', '42', 1),
(22, 46, 'delete_outcome', '1,4', '1,7', '10', '18', '42', 1),
(23, 46, 'add_baseline', '1,4', '7,8,9,10,11,12,13,1', '10', '18', '42', 1),
(24, 46, 'edit_baseline', '1,4', '7,8,9,10,11,12,13,1', '10', '18', '42', 1),
(25, 47, 'add', '1,4', '7,1', '10', '18', '42', 1),
(26, 47, 'edit', '1,4', '7,1', '10', '18', '42', 1),
(27, 47, 'delete', '1,4', '7,1', '10', '18', '42', 1),
(28, 41, 'add_financier', '1,4', '7,1', NULL, NULL, NULL, 1),
(29, 41, 'edit_financier', '1,4', '7,1', NULL, NULL, NULL, 1),
(30, 41, 'delete_financier', '1,4', '1,7', NULL, NULL, NULL, 1),
(31, 41, 'add_funds', '1,4', '1,7', NULL, NULL, NULL, 1),
(37, 45, 'add', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '41', 1),
(38, 45, 'edit', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '41', 1),
(39, 45, 'delete', '1,4', '1,7', '10', '18', '41', 1),
(40, 23, 'add', '2', '7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(41, 23, 'edit', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(42, 23, 'delete', '2,4', '7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(43, 23, 'add_quarterly_targets', '2,4', '7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(44, 23, 'edit_quarterly_targets', '2,4', '7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(45, 112, 'unapprove_project', '2,4', '6,1', NULL, NULL, NULL, 1),
(46, 112, 'edit', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(47, 112, 'delete', '2,4', '1,7', NULL, NULL, NULL, 1),
(48, 112, 'add', '1,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(49, 23, 'edit_quarterly_targets', '2,4', '7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(50, 23, 'approve', '2,4', '7,6,1', NULL, NULL, NULL, 1),
(51, 25, 'edit', '2,4', '1,6', NULL, NULL, NULL, 1),
(52, 25, 'delete', '2,4', '1,6', NULL, NULL, NULL, 1),
(53, 25, 'add', '2,4', '1,6', NULL, NULL, NULL, 1),
(54, 26, 'add_map', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '42', 1),
(55, 26, 'assign', '1,4', '1,7', '10', '18', '42', 1),
(56, 26, 'reassign', '1,4', '1,7', '10', '18', '42', 1),
(57, 22, 'add_budget', '1,4', '1,7', NULL, NULL, NULL, 1),
(58, 22, 'edit_budget', '1,4', '1,7', NULL, NULL, NULL, 1),
(59, 22, 'add_quarterly_targets', '2,4', '1,6,7', NULL, NULL, NULL, 1),
(60, 22, 'edit_quarterly_targets', '2,4', '1,6,7', NULL, NULL, NULL, 1),
(61, 93, 'add_project', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(62, 93, 'add', '2', '7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(63, 93, 'edit', '2', '7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(64, 28, 'add', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(65, 29, 'edit_plan', '1,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(66, 29, 'add_procurement', '1,4', '1,7,8,9,10,11,12,13', '10', '17', '43', 1),
(67, 29, 'edit_procurement', '1,4', '1,7,8,9,10,11,12,13', '10', '17', '43', 1),
(68, 29, 'delete_procurement', '1,4', '1,7,8,9,10,11,12,13', '10', '17', '43', 1),
(69, 94, 'edit', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(70, 94, 'delete', '2,4', '2,4', NULL, NULL, NULL, 1),
(71, 94, 'add_to_adp', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(72, 94, 'remove_adp', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(73, 30, 'edit', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(74, 30, 'delete', '2,4', '1,7', NULL, NULL, NULL, 1),
(75, 30, 'add', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(76, 31, 'add', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '42', 1),
(77, 31, 'edit', '1,4', '1,7', '10', '18', '42', 1),
(78, 31, 'add_checklist', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '42', 1),
(79, 31, 'edit_checklist', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '42', 1),
(80, 31, 'delete', '1,4', '1,7', '10', '18', '42', 1),
(81, 36, 'deploy', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '42', 1),
(82, 36, 'form_details', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '42', 1),
(83, 21, 'unapprove', '2,4', '6,7,1', NULL, NULL, NULL, 1),
(84, 21, 'approve', '2,4', '6,7,1', NULL, NULL, NULL, 1),
(85, 21, 'remove_adp', '2,4', '6,7,1', NULL, NULL, NULL, 1),
(86, 21, 'add_budget', '2,4', '6,7,1', NULL, NULL, NULL, 1),
(87, 44, 'edit', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '41', 1),
(88, 44, 'delete', '1,4', '1,7', '10', '18', '41', 1),
(89, 44, 'add', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '41', 1),
(90, 48, 'edit', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '43', 1),
(91, 48, 'delete', '1,4', '1,7', '10', '18', '43', 1),
(92, 48, 'add', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '43', 1),
(93, 54, 'add_remarks', '1,4', '7,1', '10', '18', '42', 1),
(94, 53, 'drill_down', '1,4', '7,1', '10', '18', '42', 1),
(95, 28, 'edit', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(96, 28, 'delete', '2,4', '1,7', NULL, NULL, NULL, 1),
(97, 23, 'unapprove', '2,4', '7,6,1', NULL, NULL, NULL, 1),
(98, 226, 'edit', '2,4', '1,6,7', NULL, NULL, NULL, 1),
(99, 242, 'add', '2,4', '1,6', NULL, NULL, NULL, 1),
(100, 46, 'add_impact', '1,4', '7,1', '10', '18', '42', 1),
(101, 46, 'edit_impact', '1,4', '7,1', '10', '18', '42', 1),
(102, 46, 'delete_impact', '1,4', '1,7', '10', '18', '42', 1),
(103, 20, 'add', '2,4', '1,7,8,9,10,11,12,13', NULL, NULL, NULL, 1),
(104, 268, 'deploy', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '42', 1),
(105, 268, 'form_details', '1,4', '1,7,8,9,10,11,12,13', '10', '18', '42', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_page_designations`
--

CREATE TABLE `tbl_page_designations` (
  `id` int NOT NULL,
  `page_id` int NOT NULL,
  `designation_id` int NOT NULL,
  `status` int DEFAULT '1',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_page_designations`
--

INSERT INTO `tbl_page_designations` (`id`, `page_id`, `designation_id`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, NULL, '29-03-2023', NULL),
(2, 1, 2, 1, 1, NULL, '29-03-2023', NULL),
(3, 1, 3, 1, 1, NULL, '29-03-2023', NULL),
(4, 1, 4, 1, 1, NULL, '29-03-2023', NULL),
(5, 1, 5, 1, 1, NULL, '29-03-2023', NULL),
(6, 1, 6, 1, 1, NULL, '29-03-2023', NULL),
(7, 1, 7, 1, 1, NULL, '29-03-2023', NULL),
(8, 1, 8, 1, 1, NULL, '29-03-2023', NULL),
(9, 1, 9, 1, 1, NULL, '29-03-2023', NULL),
(10, 1, 10, 1, 1, NULL, '29-03-2023', NULL),
(11, 1, 11, 1, 1, NULL, '29-03-2023', NULL),
(12, 1, 12, 1, 1, NULL, '29-03-2023', NULL),
(13, 1, 13, 1, 1, NULL, '29-03-2023', NULL),
(14, 1, 14, 1, 1, NULL, '29-03-2023', NULL),
(15, 2, 1, 1, 1, NULL, '29-03-2023', NULL),
(16, 2, 2, 1, 1, NULL, '29-03-2023', NULL),
(17, 2, 3, 1, 1, NULL, '29-03-2023', NULL),
(18, 2, 4, 1, 1, NULL, '29-03-2023', NULL),
(19, 2, 5, 1, 1, NULL, '29-03-2023', NULL),
(20, 2, 6, 1, 1, NULL, '29-03-2023', NULL),
(21, 2, 7, 1, 1, NULL, '29-03-2023', NULL),
(22, 2, 8, 1, 1, NULL, '29-03-2023', NULL),
(23, 2, 9, 1, 1, NULL, '29-03-2023', NULL),
(24, 2, 10, 1, 1, NULL, '29-03-2023', NULL),
(25, 2, 11, 1, 1, NULL, '29-03-2023', NULL),
(26, 2, 12, 1, 1, NULL, '29-03-2023', NULL),
(27, 2, 13, 1, 1, NULL, '29-03-2023', NULL),
(28, 2, 14, 1, 1, NULL, '29-03-2023', NULL),
(40, 4, 1, 1, 1, NULL, '29-03-2023', NULL),
(41, 4, 5, 1, 1, NULL, '29-03-2023', NULL),
(42, 4, 6, 1, 1, NULL, '29-03-2023', NULL),
(43, 4, 7, 1, 1, NULL, '29-03-2023', NULL),
(44, 4, 8, 1, 1, NULL, '29-03-2023', NULL),
(45, 4, 9, 1, 1, NULL, '29-03-2023', NULL),
(46, 4, 10, 1, 1, NULL, '29-03-2023', NULL),
(47, 4, 11, 1, 1, NULL, '29-03-2023', NULL),
(48, 4, 12, 1, 1, NULL, '29-03-2023', NULL),
(49, 4, 13, 1, 1, NULL, '29-03-2023', NULL),
(50, 4, 14, 1, 1, NULL, '29-03-2023', NULL),
(51, 5, 1, 1, 1, NULL, '29-03-2023', NULL),
(52, 5, 5, 1, 1, NULL, '29-03-2023', NULL),
(53, 5, 6, 1, 1, NULL, '29-03-2023', NULL),
(54, 5, 7, 1, 1, NULL, '29-03-2023', NULL),
(55, 5, 8, 1, 1, NULL, '29-03-2023', NULL),
(56, 5, 9, 1, 1, NULL, '29-03-2023', NULL),
(57, 5, 10, 1, 1, NULL, '29-03-2023', NULL),
(58, 5, 11, 1, 1, NULL, '29-03-2023', NULL),
(59, 5, 12, 1, 1, NULL, '29-03-2023', NULL),
(60, 5, 13, 1, 1, NULL, '29-03-2023', NULL),
(61, 5, 14, 1, 1, NULL, '29-03-2023', NULL),
(62, 6, 1, 1, 1, NULL, '29-03-2023', NULL),
(63, 6, 5, 1, 1, NULL, '29-03-2023', NULL),
(64, 6, 6, 1, 1, NULL, '29-03-2023', NULL),
(65, 6, 7, 1, 1, NULL, '29-03-2023', NULL),
(66, 6, 8, 1, 1, NULL, '29-03-2023', NULL),
(67, 6, 9, 1, 1, NULL, '29-03-2023', NULL),
(68, 7, 1, 1, 1, NULL, '29-03-2023', NULL),
(69, 7, 5, 1, 1, NULL, '29-03-2023', NULL),
(70, 7, 6, 1, 1, NULL, '29-03-2023', NULL),
(71, 7, 7, 1, 1, NULL, '29-03-2023', NULL),
(72, 7, 8, 1, 1, NULL, '29-03-2023', NULL),
(73, 7, 9, 1, 1, NULL, '29-03-2023', NULL),
(74, 7, 10, 1, 1, NULL, '29-03-2023', NULL),
(75, 8, 1, 1, 1, NULL, '29-03-2023', NULL),
(76, 8, 5, 1, 1, NULL, '29-03-2023', NULL),
(77, 8, 6, 1, 1, NULL, '29-03-2023', NULL),
(78, 8, 7, 1, 1, NULL, '29-03-2023', NULL),
(79, 8, 8, 1, 1, NULL, '29-03-2023', NULL),
(80, 8, 9, 1, 1, NULL, '29-03-2023', NULL),
(81, 9, 1, 1, 1, NULL, '29-03-2023', NULL),
(82, 9, 5, 1, 1, NULL, '29-03-2023', NULL),
(83, 9, 6, 1, 1, NULL, '29-03-2023', NULL),
(84, 9, 7, 1, 1, NULL, '29-03-2023', NULL),
(85, 9, 8, 1, 1, NULL, '29-03-2023', NULL),
(86, 9, 9, 1, 1, NULL, '29-03-2023', NULL),
(87, 10, 1, 1, 1, NULL, '29-03-2023', NULL),
(88, 10, 2, 1, 1, NULL, '29-03-2023', NULL),
(89, 10, 3, 1, 1, NULL, '29-03-2023', NULL),
(90, 10, 4, 1, 1, NULL, '29-03-2023', NULL),
(91, 10, 5, 1, 1, NULL, '29-03-2023', NULL),
(92, 10, 6, 1, 1, NULL, '29-03-2023', NULL),
(93, 10, 7, 1, 1, NULL, '29-03-2023', NULL),
(94, 10, 8, 1, 1, NULL, '29-03-2023', NULL),
(95, 10, 9, 1, 1, NULL, '29-03-2023', NULL),
(96, 10, 10, 1, 1, NULL, '29-03-2023', NULL),
(97, 10, 11, 1, 1, NULL, '29-03-2023', NULL),
(98, 10, 12, 1, 1, NULL, '29-03-2023', NULL),
(99, 10, 13, 1, 1, NULL, '29-03-2023', NULL),
(100, 10, 14, 1, 1, NULL, '29-03-2023', NULL),
(101, 11, 1, 1, 1, NULL, '29-03-2023', NULL),
(102, 11, 2, 1, 1, NULL, '29-03-2023', NULL),
(103, 11, 3, 1, 1, NULL, '29-03-2023', NULL),
(104, 11, 4, 1, 1, NULL, '29-03-2023', NULL),
(105, 11, 5, 1, 1, NULL, '29-03-2023', NULL),
(106, 11, 6, 1, 1, NULL, '29-03-2023', NULL),
(107, 11, 7, 1, 1, NULL, '29-03-2023', NULL),
(108, 11, 8, 1, 1, NULL, '29-03-2023', NULL),
(109, 11, 9, 1, 1, NULL, '29-03-2023', NULL),
(110, 11, 10, 1, 1, NULL, '29-03-2023', NULL),
(111, 11, 11, 1, 1, NULL, '29-03-2023', NULL),
(112, 11, 12, 1, 1, NULL, '29-03-2023', NULL),
(113, 11, 13, 1, 1, NULL, '29-03-2023', NULL),
(114, 11, 14, 1, 1, NULL, '29-03-2023', NULL),
(115, 12, 1, 1, 1, NULL, '29-03-2023', NULL),
(116, 12, 5, 1, 1, NULL, '29-03-2023', NULL),
(117, 12, 6, 1, 1, NULL, '29-03-2023', NULL),
(118, 12, 7, 1, 1, NULL, '29-03-2023', NULL),
(119, 12, 8, 1, 1, NULL, '29-03-2023', NULL),
(120, 12, 9, 1, 1, NULL, '29-03-2023', NULL),
(121, 12, 10, 1, 1, NULL, '29-03-2023', NULL),
(122, 12, 11, 1, 1, NULL, '29-03-2023', NULL),
(123, 12, 12, 1, 1, NULL, '29-03-2023', NULL),
(124, 12, 13, 1, 1, NULL, '29-03-2023', NULL),
(125, 12, 14, 1, 1, NULL, '29-03-2023', NULL),
(140, 14, 1, 1, 1, NULL, '29-03-2023', NULL),
(225, 21, 1, 1, 1, NULL, '29-03-2023', NULL),
(226, 21, 2, 1, 1, NULL, '29-03-2023', NULL),
(227, 21, 3, 1, 1, NULL, '29-03-2023', NULL),
(228, 21, 4, 1, 1, NULL, '29-03-2023', NULL),
(229, 21, 5, 1, 1, NULL, '29-03-2023', NULL),
(230, 21, 6, 1, 1, NULL, '29-03-2023', NULL),
(231, 21, 7, 1, 1, NULL, '29-03-2023', NULL),
(232, 21, 8, 1, 1, NULL, '29-03-2023', NULL),
(233, 21, 9, 1, 1, NULL, '29-03-2023', NULL),
(234, 21, 10, 1, 1, NULL, '29-03-2023', NULL),
(235, 21, 11, 1, 1, NULL, '29-03-2023', NULL),
(236, 21, 12, 1, 1, NULL, '29-03-2023', NULL),
(237, 21, 13, 1, 1, NULL, '29-03-2023', NULL),
(238, 21, 14, 1, 1, NULL, '29-03-2023', NULL),
(363, 31, 1, 1, 1, NULL, '29-03-2023', NULL),
(364, 31, 5, 1, 1, NULL, '29-03-2023', NULL),
(365, 31, 6, 1, 1, NULL, '29-03-2023', NULL),
(366, 31, 7, 1, 1, NULL, '29-03-2023', NULL),
(367, 31, 8, 1, 1, NULL, '29-03-2023', NULL),
(368, 31, 9, 1, 1, NULL, '29-03-2023', NULL),
(369, 31, 10, 1, 1, NULL, '29-03-2023', NULL),
(370, 31, 11, 1, 1, NULL, '29-03-2023', NULL),
(371, 31, 12, 1, 1, NULL, '29-03-2023', NULL),
(372, 31, 13, 1, 1, NULL, '29-03-2023', NULL),
(373, 31, 14, 1, 1, NULL, '29-03-2023', NULL),
(385, 33, 1, 1, 1, NULL, '29-03-2023', NULL),
(386, 33, 5, 1, 1, NULL, '29-03-2023', NULL),
(387, 33, 6, 1, 1, NULL, '29-03-2023', NULL),
(388, 33, 7, 1, 1, NULL, '29-03-2023', NULL),
(389, 33, 8, 1, 1, NULL, '29-03-2023', NULL),
(390, 33, 9, 1, 1, NULL, '29-03-2023', NULL),
(391, 33, 10, 1, 1, NULL, '29-03-2023', NULL),
(392, 33, 11, 1, 1, NULL, '29-03-2023', NULL),
(393, 33, 12, 1, 1, NULL, '29-03-2023', NULL),
(394, 33, 13, 1, 1, NULL, '29-03-2023', NULL),
(395, 33, 14, 1, 1, NULL, '29-03-2023', NULL),
(396, 34, 1, 1, 1, NULL, '29-03-2023', NULL),
(397, 34, 5, 1, 1, NULL, '29-03-2023', NULL),
(398, 34, 6, 1, 1, NULL, '29-03-2023', NULL),
(399, 34, 7, 1, 1, NULL, '29-03-2023', NULL),
(400, 34, 8, 1, 1, NULL, '29-03-2023', NULL),
(401, 34, 9, 1, 1, NULL, '29-03-2023', NULL),
(402, 34, 10, 1, 1, NULL, '29-03-2023', NULL),
(403, 34, 11, 1, 1, NULL, '29-03-2023', NULL),
(404, 34, 12, 1, 1, NULL, '29-03-2023', NULL),
(405, 34, 13, 1, 1, NULL, '29-03-2023', NULL),
(406, 34, 14, 1, 1, NULL, '29-03-2023', NULL),
(407, 35, 1, 1, 1, NULL, '29-03-2023', NULL),
(408, 35, 5, 1, 1, NULL, '29-03-2023', NULL),
(409, 35, 6, 1, 1, NULL, '29-03-2023', NULL),
(410, 35, 7, 1, 1, NULL, '29-03-2023', NULL),
(411, 35, 8, 1, 1, NULL, '29-03-2023', NULL),
(412, 35, 9, 1, 1, NULL, '29-03-2023', NULL),
(413, 35, 10, 1, 1, NULL, '29-03-2023', NULL),
(414, 35, 11, 1, 1, NULL, '29-03-2023', NULL),
(415, 35, 12, 1, 1, NULL, '29-03-2023', NULL),
(416, 35, 13, 1, 1, NULL, '29-03-2023', NULL),
(417, 35, 14, 1, 1, NULL, '29-03-2023', NULL),
(418, 36, 1, 1, 1, NULL, '29-03-2023', NULL),
(419, 36, 5, 1, 1, NULL, '29-03-2023', NULL),
(420, 36, 6, 1, 1, NULL, '29-03-2023', NULL),
(421, 36, 7, 1, 1, NULL, '29-03-2023', NULL),
(422, 36, 8, 1, 1, NULL, '29-03-2023', NULL),
(423, 36, 9, 1, 1, NULL, '29-03-2023', NULL),
(424, 36, 10, 1, 1, NULL, '29-03-2023', NULL),
(425, 36, 11, 1, 1, NULL, '29-03-2023', NULL),
(426, 36, 12, 1, 1, NULL, '29-03-2023', NULL),
(427, 36, 13, 1, 1, NULL, '29-03-2023', NULL),
(428, 36, 14, 1, 1, NULL, '29-03-2023', NULL),
(429, 37, 1, 1, 1, NULL, '29-03-2023', NULL),
(430, 37, 5, 1, 1, NULL, '29-03-2023', NULL),
(431, 37, 6, 1, 1, NULL, '29-03-2023', NULL),
(432, 37, 7, 1, 1, NULL, '29-03-2023', NULL),
(433, 37, 8, 1, 1, NULL, '29-03-2023', NULL),
(434, 37, 9, 1, 1, NULL, '29-03-2023', NULL),
(435, 37, 10, 1, 1, NULL, '29-03-2023', NULL),
(436, 37, 11, 1, 1, NULL, '29-03-2023', NULL),
(437, 37, 12, 1, 1, NULL, '29-03-2023', NULL),
(438, 37, 13, 1, 1, NULL, '29-03-2023', NULL),
(439, 37, 14, 1, 1, NULL, '29-03-2023', NULL),
(471, 40, 1, 1, 1, NULL, '29-03-2023', NULL),
(472, 40, 5, 1, 1, NULL, '29-03-2023', NULL),
(473, 40, 6, 1, 1, NULL, '29-03-2023', NULL),
(474, 40, 7, 1, 1, NULL, '29-03-2023', NULL),
(475, 40, 8, 1, 1, NULL, '29-03-2023', NULL),
(476, 40, 9, 1, 1, NULL, '29-03-2023', NULL),
(477, 40, 10, 1, 1, NULL, '29-03-2023', NULL),
(478, 40, 11, 1, 1, NULL, '29-03-2023', NULL),
(479, 40, 12, 1, 1, NULL, '29-03-2023', NULL),
(480, 40, 13, 1, 1, NULL, '29-03-2023', NULL),
(481, 40, 14, 1, 1, NULL, '29-03-2023', NULL),
(482, 41, 1, 1, 1, NULL, '29-03-2023', NULL),
(483, 41, 5, 1, 1, NULL, '29-03-2023', NULL),
(484, 41, 6, 1, 1, NULL, '29-03-2023', NULL),
(485, 41, 7, 1, 1, NULL, '29-03-2023', NULL),
(486, 41, 8, 1, 1, NULL, '29-03-2023', NULL),
(487, 41, 9, 1, 1, NULL, '29-03-2023', NULL),
(488, 41, 10, 1, 1, NULL, '29-03-2023', NULL),
(489, 41, 11, 1, 1, NULL, '29-03-2023', NULL),
(490, 41, 12, 1, 1, NULL, '29-03-2023', NULL),
(491, 41, 13, 1, 1, NULL, '29-03-2023', NULL),
(492, 41, 14, 1, 1, NULL, '29-03-2023', NULL),
(504, 43, 1, 1, 1, NULL, '29-03-2023', NULL),
(505, 43, 5, 1, 1, NULL, '29-03-2023', NULL),
(506, 43, 6, 1, 1, NULL, '29-03-2023', NULL),
(507, 43, 7, 1, 1, NULL, '29-03-2023', NULL),
(508, 43, 8, 1, 1, NULL, '29-03-2023', NULL),
(509, 43, 9, 1, 1, NULL, '29-03-2023', NULL),
(510, 43, 12, 1, 1, NULL, '29-03-2023', NULL),
(511, 44, 1, 1, 1, NULL, '29-03-2023', NULL),
(512, 44, 5, 1, 1, NULL, '29-03-2023', NULL),
(513, 44, 6, 1, 1, NULL, '29-03-2023', NULL),
(514, 44, 7, 1, 1, NULL, '29-03-2023', NULL),
(515, 44, 8, 1, 1, NULL, '29-03-2023', NULL),
(520, 45, 1, 1, 1, NULL, '29-03-2023', NULL),
(521, 45, 5, 1, 1, NULL, '29-03-2023', NULL),
(522, 45, 6, 1, 1, NULL, '29-03-2023', NULL),
(523, 45, 7, 1, 1, NULL, '29-03-2023', NULL),
(524, 46, 1, 1, 1, NULL, '29-03-2023', NULL),
(525, 46, 5, 1, 1, NULL, '29-03-2023', NULL),
(526, 46, 6, 1, 1, NULL, '29-03-2023', NULL),
(527, 46, 7, 1, 1, NULL, '29-03-2023', NULL),
(528, 46, 8, 1, 1, NULL, '29-03-2023', NULL),
(529, 46, 9, 1, 1, NULL, '29-03-2023', NULL),
(541, 48, 1, 1, 1, NULL, '29-03-2023', NULL),
(542, 48, 5, 1, 1, NULL, '29-03-2023', NULL),
(543, 48, 6, 1, 1, NULL, '29-03-2023', NULL),
(544, 48, 7, 1, 1, NULL, '29-03-2023', NULL),
(545, 48, 8, 1, 1, NULL, '29-03-2023', NULL),
(546, 48, 9, 1, 1, NULL, '29-03-2023', NULL),
(547, 48, 10, 1, 1, NULL, '29-03-2023', NULL),
(548, 48, 11, 1, 1, NULL, '29-03-2023', NULL),
(549, 48, 12, 1, 1, NULL, '29-03-2023', NULL),
(550, 48, 13, 1, 1, NULL, '29-03-2023', NULL),
(551, 48, 14, 1, 1, NULL, '29-03-2023', NULL),
(552, 49, 1, 1, 1, NULL, '29-03-2023', NULL),
(553, 49, 5, 1, 1, NULL, '29-03-2023', NULL),
(554, 49, 6, 1, 1, NULL, '29-03-2023', NULL),
(555, 49, 7, 1, 1, NULL, '29-03-2023', NULL),
(556, 49, 8, 1, 1, NULL, '29-03-2023', NULL),
(557, 49, 9, 1, 1, NULL, '29-03-2023', NULL),
(558, 49, 10, 1, 1, NULL, '29-03-2023', NULL),
(559, 49, 11, 1, 1, NULL, '29-03-2023', NULL),
(560, 49, 12, 1, 1, NULL, '29-03-2023', NULL),
(561, 49, 13, 1, 1, NULL, '29-03-2023', NULL),
(562, 49, 14, 1, 1, NULL, '29-03-2023', NULL),
(569, 50, 1, 1, 1, NULL, '29-03-2023', NULL),
(570, 50, 5, 1, 1, NULL, '29-03-2023', NULL),
(571, 50, 6, 1, 1, NULL, '29-03-2023', NULL),
(572, 50, 7, 1, 1, NULL, '29-03-2023', NULL),
(573, 50, 8, 1, 1, NULL, '29-03-2023', NULL),
(574, 50, 9, 1, 1, NULL, '29-03-2023', NULL),
(575, 51, 1, 1, 1, NULL, '29-03-2023', NULL),
(576, 51, 2, 1, 1, NULL, '29-03-2023', NULL),
(577, 51, 3, 1, 1, NULL, '29-03-2023', NULL),
(578, 51, 4, 1, 1, NULL, '29-03-2023', NULL),
(579, 51, 5, 1, 1, NULL, '29-03-2023', NULL),
(580, 51, 6, 1, 1, NULL, '29-03-2023', NULL),
(581, 51, 7, 1, 1, NULL, '29-03-2023', NULL),
(582, 51, 8, 1, 1, NULL, '29-03-2023', NULL),
(583, 51, 9, 1, 1, NULL, '29-03-2023', NULL),
(584, 51, 10, 1, 1, NULL, '29-03-2023', NULL),
(585, 51, 11, 1, 1, NULL, '29-03-2023', NULL),
(586, 51, 12, 1, 1, NULL, '29-03-2023', NULL),
(587, 51, 13, 1, 1, NULL, '29-03-2023', NULL),
(588, 51, 14, 1, 1, NULL, '29-03-2023', NULL),
(652, 56, 1, 1, 1, NULL, '29-03-2023', NULL),
(653, 56, 5, 1, 1, NULL, '29-03-2023', NULL),
(654, 56, 6, 1, 1, NULL, '29-03-2023', NULL),
(655, 56, 7, 1, 1, NULL, '29-03-2023', NULL),
(656, 56, 8, 1, 1, NULL, '29-03-2023', NULL),
(657, 56, 10, 1, 1, NULL, '29-03-2023', NULL),
(658, 56, 11, 1, 1, NULL, '29-03-2023', NULL),
(659, 56, 12, 1, 1, NULL, '29-03-2023', NULL),
(660, 56, 13, 1, 1, NULL, '29-03-2023', NULL),
(661, 56, 14, 1, 1, NULL, '29-03-2023', NULL),
(675, 59, 1, 1, 1, NULL, '29-03-2023', NULL),
(676, 60, 1, 1, 1, NULL, '29-03-2023', NULL),
(757, 55, 1, 1, 1, NULL, '03-04-2023', NULL),
(758, 55, 5, 1, 1, NULL, '03-04-2023', NULL),
(759, 55, 6, 1, 1, NULL, '03-04-2023', NULL),
(760, 55, 7, 1, 1, NULL, '03-04-2023', NULL),
(761, 55, 8, 1, 1, NULL, '03-04-2023', NULL),
(762, 55, 9, 1, 1, NULL, '03-04-2023', NULL),
(763, 55, 10, 1, 1, NULL, '03-04-2023', NULL),
(764, 55, 11, 1, 1, NULL, '03-04-2023', NULL),
(765, 55, 12, 1, 1, NULL, '03-04-2023', NULL),
(766, 55, 13, 1, 1, NULL, '03-04-2023', NULL),
(767, 55, 14, 1, 1, NULL, '03-04-2023', NULL),
(781, 58, 1, 1, 1, NULL, '08-04-2023', NULL),
(782, 64, 1, 1, 1, NULL, '08-04-2023', NULL),
(783, 65, 1, 1, 1, NULL, '08-04-2023', NULL),
(784, 65, 5, 1, 1, NULL, '08-04-2023', NULL),
(785, 65, 6, 1, 1, NULL, '08-04-2023', NULL),
(786, 65, 7, 1, 1, NULL, '08-04-2023', NULL),
(787, 65, 8, 1, 1, NULL, '08-04-2023', NULL),
(788, 65, 9, 1, 1, NULL, '08-04-2023', NULL),
(789, 65, 10, 1, 1, NULL, '08-04-2023', NULL),
(790, 65, 11, 1, 1, NULL, '08-04-2023', NULL),
(791, 65, 12, 1, 1, NULL, '08-04-2023', NULL),
(792, 65, 13, 1, 1, NULL, '08-04-2023', NULL),
(793, 65, 14, 1, 1, NULL, '08-04-2023', NULL),
(805, 67, 1, 1, 1, NULL, '08-04-2023', NULL),
(806, 67, 5, 1, 1, NULL, '08-04-2023', NULL),
(807, 67, 6, 1, 1, NULL, '08-04-2023', NULL),
(808, 67, 7, 1, 1, NULL, '08-04-2023', NULL),
(809, 67, 8, 1, 1, NULL, '08-04-2023', NULL),
(810, 67, 9, 1, 1, NULL, '08-04-2023', NULL),
(811, 67, 10, 1, 1, NULL, '08-04-2023', NULL),
(812, 67, 11, 1, 1, NULL, '08-04-2023', NULL),
(813, 67, 12, 1, 1, NULL, '08-04-2023', NULL),
(814, 67, 13, 1, 1, NULL, '08-04-2023', NULL),
(815, 67, 14, 1, 1, NULL, '08-04-2023', NULL),
(816, 68, 1, 1, 1, NULL, '08-04-2023', NULL),
(817, 68, 5, 1, 1, NULL, '08-04-2023', NULL),
(818, 68, 6, 1, 1, NULL, '08-04-2023', NULL),
(819, 68, 7, 1, 1, NULL, '08-04-2023', NULL),
(820, 68, 8, 1, 1, NULL, '08-04-2023', NULL),
(821, 68, 9, 1, 1, NULL, '08-04-2023', NULL),
(822, 68, 10, 1, 1, NULL, '08-04-2023', NULL),
(823, 68, 11, 1, 1, NULL, '08-04-2023', NULL),
(824, 68, 12, 1, 1, NULL, '08-04-2023', NULL),
(825, 68, 13, 1, 1, NULL, '08-04-2023', NULL),
(826, 68, 14, 1, 1, NULL, '08-04-2023', NULL),
(827, 69, 1, 1, 1, NULL, '08-04-2023', NULL),
(828, 69, 5, 1, 1, NULL, '08-04-2023', NULL),
(829, 69, 6, 1, 1, NULL, '08-04-2023', NULL),
(830, 69, 7, 1, 1, NULL, '08-04-2023', NULL),
(831, 69, 8, 1, 1, NULL, '08-04-2023', NULL),
(832, 69, 9, 1, 1, NULL, '08-04-2023', NULL),
(833, 69, 10, 1, 1, NULL, '08-04-2023', NULL),
(834, 69, 11, 1, 1, NULL, '08-04-2023', NULL),
(835, 69, 12, 1, 1, NULL, '08-04-2023', NULL),
(836, 69, 13, 1, 1, NULL, '08-04-2023', NULL),
(837, 69, 14, 1, 1, NULL, '08-04-2023', NULL),
(838, 70, 1, 1, 1, NULL, '08-04-2023', NULL),
(839, 70, 5, 1, 1, NULL, '08-04-2023', NULL),
(840, 70, 6, 1, 1, NULL, '08-04-2023', NULL),
(841, 70, 7, 1, 1, NULL, '08-04-2023', NULL),
(842, 70, 8, 1, 1, NULL, '08-04-2023', NULL),
(843, 70, 9, 1, 1, NULL, '08-04-2023', NULL),
(844, 70, 10, 1, 1, NULL, '08-04-2023', NULL),
(845, 70, 11, 1, 1, NULL, '08-04-2023', NULL),
(846, 70, 12, 1, 1, NULL, '08-04-2023', NULL),
(847, 70, 13, 1, 1, NULL, '08-04-2023', NULL),
(848, 70, 14, 1, 1, NULL, '08-04-2023', NULL),
(849, 71, 1, 1, 1, NULL, '08-04-2023', NULL),
(850, 71, 2, 1, 1, NULL, '08-04-2023', NULL),
(851, 71, 3, 1, 1, NULL, '08-04-2023', NULL),
(852, 71, 4, 1, 1, NULL, '08-04-2023', NULL),
(853, 71, 5, 1, 1, NULL, '08-04-2023', NULL),
(854, 71, 6, 1, 1, NULL, '08-04-2023', NULL),
(855, 71, 7, 1, 1, NULL, '08-04-2023', NULL),
(856, 71, 8, 1, 1, NULL, '08-04-2023', NULL),
(857, 71, 9, 1, 1, NULL, '08-04-2023', NULL),
(858, 71, 10, 1, 1, NULL, '08-04-2023', NULL),
(859, 71, 11, 1, 1, NULL, '08-04-2023', NULL),
(860, 71, 12, 1, 1, NULL, '08-04-2023', NULL),
(861, 71, 13, 1, 1, NULL, '08-04-2023', NULL),
(862, 71, 14, 1, 1, NULL, '08-04-2023', NULL),
(863, 72, 1, 1, 1, NULL, '08-04-2023', NULL),
(864, 72, 6, 1, 1, NULL, '08-04-2023', NULL),
(865, 72, 7, 1, 1, NULL, '08-04-2023', NULL),
(866, 72, 8, 1, 1, NULL, '08-04-2023', NULL),
(867, 72, 9, 1, 1, NULL, '08-04-2023', NULL),
(868, 73, 1, 1, 1, NULL, '08-04-2023', NULL),
(869, 73, 5, 1, 1, NULL, '08-04-2023', NULL),
(870, 73, 6, 1, 1, NULL, '08-04-2023', NULL),
(871, 73, 7, 1, 1, NULL, '08-04-2023', NULL),
(872, 73, 8, 1, 1, NULL, '08-04-2023', NULL),
(873, 73, 9, 1, 1, NULL, '08-04-2023', NULL),
(874, 47, 1, 1, 1, NULL, '08-04-2023', NULL),
(875, 47, 5, 1, 1, NULL, '08-04-2023', NULL),
(876, 47, 6, 1, 1, NULL, '08-04-2023', NULL),
(877, 47, 7, 1, 1, NULL, '08-04-2023', NULL),
(878, 47, 8, 1, 1, NULL, '08-04-2023', NULL),
(879, 47, 9, 1, 1, NULL, '08-04-2023', NULL),
(880, 47, 10, 1, 1, NULL, '08-04-2023', NULL),
(881, 47, 11, 1, 1, NULL, '08-04-2023', NULL),
(882, 47, 12, 1, 1, NULL, '08-04-2023', NULL),
(883, 47, 13, 1, 1, NULL, '08-04-2023', NULL),
(884, 47, 14, 1, 1, NULL, '08-04-2023', NULL),
(885, 74, 1, 1, 1, NULL, '08-04-2023', NULL),
(886, 74, 5, 1, 1, NULL, '08-04-2023', NULL),
(887, 74, 6, 1, 1, NULL, '08-04-2023', NULL),
(888, 74, 7, 1, 1, NULL, '08-04-2023', NULL),
(889, 74, 8, 1, 1, NULL, '08-04-2023', NULL),
(890, 74, 9, 1, 1, NULL, '08-04-2023', NULL),
(891, 75, 1, 1, 1, NULL, '08-04-2023', NULL),
(892, 75, 5, 1, 1, NULL, '08-04-2023', NULL),
(893, 75, 6, 1, 1, NULL, '08-04-2023', NULL),
(894, 75, 7, 1, 1, NULL, '08-04-2023', NULL),
(895, 75, 8, 1, 1, NULL, '08-04-2023', NULL),
(896, 75, 9, 1, 1, NULL, '08-04-2023', NULL),
(897, 76, 1, 1, 1, NULL, '08-04-2023', NULL),
(898, 76, 5, 1, 1, NULL, '08-04-2023', NULL),
(899, 76, 6, 1, 1, NULL, '08-04-2023', NULL),
(900, 76, 7, 1, 1, NULL, '08-04-2023', NULL),
(901, 76, 8, 1, 1, NULL, '08-04-2023', NULL),
(902, 77, 1, 1, 1, NULL, '08-04-2023', NULL),
(903, 77, 5, 1, 1, NULL, '08-04-2023', NULL),
(904, 77, 6, 1, 1, NULL, '08-04-2023', NULL),
(905, 77, 7, 1, 1, NULL, '08-04-2023', NULL),
(906, 77, 8, 1, 1, NULL, '08-04-2023', NULL),
(907, 78, 1, 1, 1, NULL, '08-04-2023', NULL),
(908, 78, 5, 1, 1, NULL, '08-04-2023', NULL),
(909, 78, 6, 1, 1, NULL, '08-04-2023', NULL),
(910, 79, 1, 1, 1, NULL, '08-04-2023', NULL),
(911, 79, 7, 1, 1, NULL, '08-04-2023', NULL),
(912, 80, 1, 1, 1, NULL, '08-04-2023', NULL),
(913, 80, 5, 1, 1, NULL, '08-04-2023', NULL),
(914, 80, 6, 1, 1, NULL, '08-04-2023', NULL),
(915, 80, 7, 1, 1, NULL, '08-04-2023', NULL),
(916, 80, 8, 1, 1, NULL, '08-04-2023', NULL),
(917, 80, 9, 1, 1, NULL, '08-04-2023', NULL),
(918, 80, 10, 1, 1, NULL, '08-04-2023', NULL),
(919, 80, 11, 1, 1, NULL, '08-04-2023', NULL),
(920, 80, 12, 1, 1, NULL, '08-04-2023', NULL),
(921, 80, 13, 1, 1, NULL, '08-04-2023', NULL),
(922, 80, 14, 1, 1, NULL, '08-04-2023', NULL),
(923, 81, 1, 1, 1, NULL, '08-04-2023', NULL),
(924, 81, 5, 1, 1, NULL, '08-04-2023', NULL),
(925, 81, 6, 1, 1, NULL, '08-04-2023', NULL),
(926, 81, 7, 1, 1, NULL, '08-04-2023', NULL),
(927, 81, 8, 1, 1, NULL, '08-04-2023', NULL),
(928, 81, 9, 1, 1, NULL, '08-04-2023', NULL),
(929, 81, 10, 1, 1, NULL, '08-04-2023', NULL),
(930, 81, 11, 1, 1, NULL, '08-04-2023', NULL),
(931, 81, 12, 1, 1, NULL, '08-04-2023', NULL),
(932, 81, 13, 1, 1, NULL, '08-04-2023', NULL),
(933, 81, 14, 1, 1, NULL, '08-04-2023', NULL),
(934, 82, 1, 1, 1, NULL, '08-04-2023', NULL),
(935, 82, 5, 1, 1, NULL, '08-04-2023', NULL),
(936, 82, 6, 1, 1, NULL, '08-04-2023', NULL),
(937, 82, 7, 1, 1, NULL, '08-04-2023', NULL),
(938, 82, 8, 1, 1, NULL, '08-04-2023', NULL),
(939, 82, 9, 1, 1, NULL, '08-04-2023', NULL),
(940, 82, 10, 1, 1, NULL, '08-04-2023', NULL),
(941, 82, 11, 1, 1, NULL, '08-04-2023', NULL),
(942, 82, 12, 1, 1, NULL, '08-04-2023', NULL),
(943, 82, 13, 1, 1, NULL, '08-04-2023', NULL),
(944, 82, 14, 1, 1, NULL, '08-04-2023', NULL),
(966, 85, 1, 1, 1, NULL, '08-04-2023', NULL),
(967, 85, 5, 1, 1, NULL, '08-04-2023', NULL),
(968, 85, 6, 1, 1, NULL, '08-04-2023', NULL),
(969, 85, 7, 1, 1, NULL, '08-04-2023', NULL),
(970, 85, 8, 1, 1, NULL, '08-04-2023', NULL),
(971, 85, 9, 1, 1, NULL, '08-04-2023', NULL),
(972, 85, 10, 1, 1, NULL, '08-04-2023', NULL),
(973, 85, 11, 1, 1, NULL, '08-04-2023', NULL),
(974, 85, 12, 1, 1, NULL, '08-04-2023', NULL),
(975, 85, 13, 1, 1, NULL, '08-04-2023', NULL),
(976, 85, 14, 1, 1, NULL, '08-04-2023', NULL),
(988, 87, 1, 1, 1, NULL, '08-04-2023', NULL),
(989, 87, 5, 1, 1, NULL, '08-04-2023', NULL),
(990, 87, 6, 1, 1, NULL, '08-04-2023', NULL),
(991, 87, 7, 1, 1, NULL, '08-04-2023', NULL),
(992, 87, 8, 1, 1, NULL, '08-04-2023', NULL),
(993, 87, 9, 1, 1, NULL, '08-04-2023', NULL),
(994, 87, 10, 1, 1, NULL, '08-04-2023', NULL),
(995, 87, 11, 1, 1, NULL, '08-04-2023', NULL),
(996, 87, 12, 1, 1, NULL, '08-04-2023', NULL),
(997, 87, 13, 1, 1, NULL, '08-04-2023', NULL),
(998, 87, 14, 1, 1, NULL, '08-04-2023', NULL),
(1032, 91, 1, 1, 1, NULL, '08-04-2023', NULL),
(1033, 91, 5, 1, 1, NULL, '08-04-2023', NULL),
(1034, 91, 6, 1, 1, NULL, '08-04-2023', NULL),
(1035, 91, 7, 1, 1, NULL, '08-04-2023', NULL),
(1036, 91, 8, 1, 1, NULL, '08-04-2023', NULL),
(1037, 91, 9, 1, 1, NULL, '08-04-2023', NULL),
(1038, 91, 10, 1, 1, NULL, '08-04-2023', NULL),
(1039, 91, 11, 1, 1, NULL, '08-04-2023', NULL),
(1040, 91, 12, 1, 1, NULL, '08-04-2023', NULL),
(1041, 91, 13, 1, 1, NULL, '08-04-2023', NULL),
(1042, 91, 14, 1, 1, NULL, '08-04-2023', NULL),
(1043, 92, 1, 1, 1, NULL, '08-04-2023', NULL),
(1044, 92, 5, 1, 1, NULL, '08-04-2023', NULL),
(1045, 92, 6, 1, 1, NULL, '08-04-2023', NULL),
(1046, 92, 7, 1, 1, NULL, '08-04-2023', NULL),
(1047, 92, 8, 1, 1, NULL, '08-04-2023', NULL),
(1048, 92, 9, 1, 1, NULL, '08-04-2023', NULL),
(1049, 92, 10, 1, 1, NULL, '08-04-2023', NULL),
(1050, 92, 11, 1, 1, NULL, '08-04-2023', NULL),
(1051, 92, 12, 1, 1, NULL, '08-04-2023', NULL),
(1052, 92, 13, 1, 1, NULL, '08-04-2023', NULL),
(1053, 92, 14, 1, 1, NULL, '08-04-2023', NULL),
(1054, 93, 1, 1, 1, NULL, '08-04-2023', NULL),
(1055, 93, 5, 1, 1, NULL, '08-04-2023', NULL),
(1056, 93, 6, 1, 1, NULL, '08-04-2023', NULL),
(1057, 93, 7, 1, 1, NULL, '08-04-2023', NULL),
(1058, 93, 8, 1, 1, NULL, '08-04-2023', NULL),
(1059, 93, 9, 1, 1, NULL, '08-04-2023', NULL),
(1060, 93, 10, 1, 1, NULL, '08-04-2023', NULL),
(1061, 93, 11, 1, 1, NULL, '08-04-2023', NULL),
(1062, 93, 12, 1, 1, NULL, '08-04-2023', NULL),
(1063, 93, 13, 1, 1, NULL, '08-04-2023', NULL),
(1064, 93, 14, 1, 1, NULL, '08-04-2023', NULL),
(1065, 94, 1, 1, 1, NULL, '08-04-2023', NULL),
(1066, 94, 5, 1, 1, NULL, '08-04-2023', NULL),
(1067, 94, 6, 1, 1, NULL, '08-04-2023', NULL),
(1068, 94, 7, 1, 1, NULL, '08-04-2023', NULL),
(1069, 94, 8, 1, 1, NULL, '08-04-2023', NULL),
(1070, 94, 9, 1, 1, NULL, '08-04-2023', NULL),
(1071, 94, 10, 1, 1, NULL, '08-04-2023', NULL),
(1072, 94, 11, 1, 1, NULL, '08-04-2023', NULL),
(1073, 94, 12, 1, 1, NULL, '08-04-2023', NULL),
(1074, 94, 13, 1, 1, NULL, '08-04-2023', NULL),
(1075, 94, 14, 1, 1, NULL, '08-04-2023', NULL),
(1076, 95, 1, 1, 1, NULL, '08-04-2023', NULL),
(1077, 95, 5, 1, 1, NULL, '08-04-2023', NULL),
(1078, 95, 6, 1, 1, NULL, '08-04-2023', NULL),
(1079, 95, 7, 1, 1, NULL, '08-04-2023', NULL),
(1080, 95, 8, 1, 1, NULL, '08-04-2023', NULL),
(1081, 95, 9, 1, 1, NULL, '08-04-2023', NULL),
(1082, 95, 10, 1, 1, NULL, '08-04-2023', NULL),
(1083, 95, 11, 1, 1, NULL, '08-04-2023', NULL),
(1084, 95, 12, 1, 1, NULL, '08-04-2023', NULL),
(1085, 95, 13, 1, 1, NULL, '08-04-2023', NULL),
(1086, 95, 14, 1, 1, NULL, '08-04-2023', NULL),
(1087, 96, 1, 1, 1, NULL, '08-04-2023', NULL),
(1088, 96, 5, 1, 1, NULL, '08-04-2023', NULL),
(1089, 96, 6, 1, 1, NULL, '08-04-2023', NULL),
(1090, 96, 7, 1, 1, NULL, '08-04-2023', NULL),
(1091, 96, 8, 1, 1, NULL, '08-04-2023', NULL),
(1092, 96, 9, 1, 1, NULL, '08-04-2023', NULL),
(1093, 96, 10, 1, 1, NULL, '08-04-2023', NULL),
(1094, 96, 11, 1, 1, NULL, '08-04-2023', NULL),
(1095, 96, 12, 1, 1, NULL, '08-04-2023', NULL),
(1096, 96, 13, 1, 1, NULL, '08-04-2023', NULL),
(1097, 96, 14, 1, 1, NULL, '08-04-2023', NULL),
(1098, 97, 1, 1, 1, NULL, '08-04-2023', NULL),
(1099, 97, 5, 1, 1, NULL, '08-04-2023', NULL),
(1100, 97, 6, 1, 1, NULL, '08-04-2023', NULL),
(1101, 97, 7, 1, 1, NULL, '08-04-2023', NULL),
(1102, 97, 8, 1, 1, NULL, '08-04-2023', NULL),
(1103, 97, 9, 1, 1, NULL, '08-04-2023', NULL),
(1104, 97, 10, 1, 1, NULL, '08-04-2023', NULL),
(1105, 97, 11, 1, 1, NULL, '08-04-2023', NULL),
(1106, 97, 12, 1, 1, NULL, '08-04-2023', NULL),
(1107, 97, 13, 1, 1, NULL, '08-04-2023', NULL),
(1108, 97, 14, 1, 1, NULL, '08-04-2023', NULL),
(1110, 98, 1, 1, 1, NULL, '09-04-2023', NULL),
(1111, 98, 5, 1, 1, NULL, '09-04-2023', NULL),
(1112, 98, 6, 1, 1, NULL, '09-04-2023', NULL),
(1113, 98, 8, 1, 1, NULL, '09-04-2023', NULL),
(1114, 98, 9, 1, 1, NULL, '09-04-2023', NULL),
(1115, 99, 1, 1, 1, NULL, '09-04-2023', NULL),
(1116, 99, 2, 1, 1, NULL, '09-04-2023', NULL),
(1117, 99, 3, 1, 1, NULL, '09-04-2023', NULL),
(1118, 99, 4, 1, 1, NULL, '09-04-2023', NULL),
(1119, 99, 5, 1, 1, NULL, '09-04-2023', NULL),
(1120, 99, 6, 1, 1, NULL, '09-04-2023', NULL),
(1121, 99, 7, 1, 1, NULL, '09-04-2023', NULL),
(1122, 99, 8, 1, 1, NULL, '09-04-2023', NULL),
(1123, 99, 9, 1, 1, NULL, '09-04-2023', NULL),
(1124, 99, 10, 1, 1, NULL, '09-04-2023', NULL),
(1125, 99, 11, 1, 1, NULL, '09-04-2023', NULL),
(1126, 99, 12, 1, 1, NULL, '09-04-2023', NULL),
(1127, 99, 13, 1, 1, NULL, '09-04-2023', NULL),
(1128, 99, 14, 1, 1, NULL, '09-04-2023', NULL),
(1129, 100, 1, 1, 1, NULL, '09-04-2023', NULL),
(1130, 100, 2, 1, 1, NULL, '09-04-2023', NULL),
(1131, 100, 3, 1, 1, NULL, '09-04-2023', NULL),
(1132, 100, 4, 1, 1, NULL, '09-04-2023', NULL),
(1133, 100, 5, 1, 1, NULL, '09-04-2023', NULL),
(1134, 100, 6, 1, 1, NULL, '09-04-2023', NULL),
(1135, 100, 7, 1, 1, NULL, '09-04-2023', NULL),
(1136, 100, 8, 1, 1, NULL, '09-04-2023', NULL),
(1137, 100, 9, 1, 1, NULL, '09-04-2023', NULL),
(1138, 100, 10, 1, 1, NULL, '09-04-2023', NULL),
(1139, 100, 11, 1, 1, NULL, '09-04-2023', NULL),
(1140, 100, 12, 1, 1, NULL, '09-04-2023', NULL),
(1141, 100, 13, 1, 1, NULL, '09-04-2023', NULL),
(1142, 100, 14, 1, 1, NULL, '09-04-2023', NULL),
(1143, 101, 1, 1, 1, NULL, '09-04-2023', NULL),
(1144, 101, 2, 1, 1, NULL, '09-04-2023', NULL),
(1145, 101, 3, 1, 1, NULL, '09-04-2023', NULL),
(1146, 101, 4, 1, 1, NULL, '09-04-2023', NULL),
(1147, 101, 5, 1, 1, NULL, '09-04-2023', NULL),
(1148, 101, 6, 1, 1, NULL, '09-04-2023', NULL),
(1149, 101, 7, 1, 1, NULL, '09-04-2023', NULL),
(1150, 101, 8, 1, 1, NULL, '09-04-2023', NULL),
(1151, 101, 9, 1, 1, NULL, '09-04-2023', NULL),
(1152, 101, 10, 1, 1, NULL, '09-04-2023', NULL),
(1153, 101, 11, 1, 1, NULL, '09-04-2023', NULL),
(1154, 101, 12, 1, 1, NULL, '09-04-2023', NULL),
(1155, 101, 13, 1, 1, NULL, '09-04-2023', NULL),
(1156, 101, 14, 1, 1, NULL, '09-04-2023', NULL),
(1171, 103, 1, 1, 1, NULL, '09-04-2023', NULL),
(1172, 103, 2, 1, 1, NULL, '09-04-2023', NULL),
(1173, 103, 3, 1, 1, NULL, '09-04-2023', NULL),
(1174, 103, 4, 1, 1, NULL, '09-04-2023', NULL),
(1175, 103, 5, 1, 1, NULL, '09-04-2023', NULL),
(1176, 103, 6, 1, 1, NULL, '09-04-2023', NULL),
(1177, 103, 7, 1, 1, NULL, '09-04-2023', NULL),
(1178, 103, 8, 1, 1, NULL, '09-04-2023', NULL),
(1179, 103, 9, 1, 1, NULL, '09-04-2023', NULL),
(1180, 103, 10, 1, 1, NULL, '09-04-2023', NULL),
(1181, 103, 11, 1, 1, NULL, '09-04-2023', NULL),
(1182, 103, 12, 1, 1, NULL, '09-04-2023', NULL),
(1183, 103, 13, 1, 1, NULL, '09-04-2023', NULL),
(1184, 103, 14, 1, 1, NULL, '09-04-2023', NULL),
(1185, 61, 1, 1, 1, NULL, '09-04-2023', NULL),
(1186, 61, 2, 1, 1, NULL, '09-04-2023', NULL),
(1187, 61, 3, 1, 1, NULL, '09-04-2023', NULL),
(1188, 61, 4, 1, 1, NULL, '09-04-2023', NULL),
(1189, 61, 5, 1, 1, NULL, '09-04-2023', NULL),
(1190, 61, 6, 1, 1, NULL, '09-04-2023', NULL),
(1191, 61, 7, 1, 1, NULL, '09-04-2023', NULL),
(1192, 61, 8, 1, 1, NULL, '09-04-2023', NULL),
(1193, 61, 9, 1, 1, NULL, '09-04-2023', NULL),
(1194, 61, 10, 1, 1, NULL, '09-04-2023', NULL),
(1195, 61, 11, 1, 1, NULL, '09-04-2023', NULL),
(1196, 61, 12, 1, 1, NULL, '09-04-2023', NULL),
(1197, 61, 13, 1, 1, NULL, '09-04-2023', NULL),
(1198, 61, 14, 1, 1, NULL, '09-04-2023', NULL),
(1199, 62, 1, 1, 1, NULL, '09-04-2023', NULL),
(1200, 62, 2, 1, 1, NULL, '09-04-2023', NULL),
(1201, 62, 3, 1, 1, NULL, '09-04-2023', NULL),
(1202, 62, 4, 1, 1, NULL, '09-04-2023', NULL),
(1203, 62, 5, 1, 1, NULL, '09-04-2023', NULL),
(1204, 62, 6, 1, 1, NULL, '09-04-2023', NULL),
(1205, 62, 7, 1, 1, NULL, '09-04-2023', NULL),
(1206, 62, 8, 1, 1, NULL, '09-04-2023', NULL),
(1207, 62, 9, 1, 1, NULL, '09-04-2023', NULL),
(1208, 62, 10, 1, 1, NULL, '09-04-2023', NULL),
(1209, 62, 11, 1, 1, NULL, '09-04-2023', NULL),
(1210, 62, 12, 1, 1, NULL, '09-04-2023', NULL),
(1211, 62, 13, 1, 1, NULL, '09-04-2023', NULL),
(1212, 62, 14, 1, 1, NULL, '09-04-2023', NULL),
(1213, 104, 1, 1, 1, NULL, '10-04-2023', NULL),
(1214, 104, 5, 1, 1, NULL, '10-04-2023', NULL),
(1215, 104, 6, 1, 1, NULL, '10-04-2023', NULL),
(1216, 104, 7, 1, 1, NULL, '10-04-2023', NULL),
(1217, 104, 8, 1, 1, NULL, '10-04-2023', NULL),
(1218, 104, 9, 1, 1, NULL, '10-04-2023', NULL),
(1219, 104, 10, 1, 1, NULL, '10-04-2023', NULL),
(1220, 104, 11, 1, 1, NULL, '10-04-2023', NULL),
(1221, 104, 12, 1, 1, NULL, '10-04-2023', NULL),
(1222, 104, 13, 1, 1, NULL, '10-04-2023', NULL),
(1223, 104, 14, 1, 1, NULL, '10-04-2023', NULL),
(1224, 105, 1, 1, 1, NULL, '10-04-2023', NULL),
(1225, 105, 5, 1, 1, NULL, '10-04-2023', NULL),
(1226, 105, 6, 1, 1, NULL, '10-04-2023', NULL),
(1227, 105, 7, 1, 1, NULL, '10-04-2023', NULL),
(1228, 105, 8, 1, 1, NULL, '10-04-2023', NULL),
(1229, 105, 9, 1, 1, NULL, '10-04-2023', NULL),
(1230, 105, 10, 1, 1, NULL, '10-04-2023', NULL),
(1231, 105, 11, 1, 1, NULL, '10-04-2023', NULL),
(1232, 105, 12, 1, 1, NULL, '10-04-2023', NULL),
(1233, 105, 13, 1, 1, NULL, '10-04-2023', NULL),
(1234, 105, 14, 1, 1, NULL, '10-04-2023', NULL),
(1235, 106, 1, 1, 1, NULL, '10-04-2023', NULL),
(1236, 106, 5, 1, 1, NULL, '10-04-2023', NULL),
(1237, 106, 6, 1, 1, NULL, '10-04-2023', NULL),
(1238, 106, 7, 1, 1, NULL, '10-04-2023', NULL),
(1239, 106, 8, 1, 1, NULL, '10-04-2023', NULL),
(1240, 106, 9, 1, 1, NULL, '10-04-2023', NULL),
(1241, 106, 10, 1, 1, NULL, '10-04-2023', NULL),
(1242, 106, 11, 1, 1, NULL, '10-04-2023', NULL),
(1243, 106, 12, 1, 1, NULL, '10-04-2023', NULL),
(1244, 106, 13, 1, 1, NULL, '10-04-2023', NULL),
(1245, 106, 14, 1, 1, NULL, '10-04-2023', NULL),
(1246, 107, 1, 1, 1, NULL, '10-04-2023', NULL),
(1247, 107, 2, 1, 1, NULL, '10-04-2023', NULL),
(1248, 107, 3, 1, 1, NULL, '10-04-2023', NULL),
(1249, 107, 4, 1, 1, NULL, '10-04-2023', NULL),
(1250, 107, 5, 1, 1, NULL, '10-04-2023', NULL),
(1251, 107, 6, 1, 1, NULL, '10-04-2023', NULL),
(1252, 107, 7, 1, 1, NULL, '10-04-2023', NULL),
(1253, 107, 8, 1, 1, NULL, '10-04-2023', NULL),
(1254, 107, 9, 1, 1, NULL, '10-04-2023', NULL),
(1255, 107, 10, 1, 1, NULL, '10-04-2023', NULL),
(1256, 107, 11, 1, 1, NULL, '10-04-2023', NULL),
(1257, 107, 12, 1, 1, NULL, '10-04-2023', NULL),
(1258, 107, 13, 1, 1, NULL, '10-04-2023', NULL),
(1259, 107, 14, 1, 1, NULL, '10-04-2023', NULL),
(1260, 108, 1, 1, 1, NULL, '10-04-2023', NULL),
(1261, 108, 2, 1, 1, NULL, '10-04-2023', NULL),
(1262, 108, 3, 1, 1, NULL, '10-04-2023', NULL),
(1263, 108, 4, 1, 1, NULL, '10-04-2023', NULL),
(1264, 108, 5, 1, 1, NULL, '10-04-2023', NULL),
(1265, 108, 6, 1, 1, NULL, '10-04-2023', NULL),
(1266, 108, 7, 1, 1, NULL, '10-04-2023', NULL),
(1267, 108, 8, 1, 1, NULL, '10-04-2023', NULL),
(1268, 108, 9, 1, 1, NULL, '10-04-2023', NULL),
(1269, 108, 10, 1, 1, NULL, '10-04-2023', NULL),
(1270, 108, 11, 1, 1, NULL, '10-04-2023', NULL),
(1271, 108, 12, 1, 1, NULL, '10-04-2023', NULL),
(1272, 108, 13, 1, 1, NULL, '10-04-2023', NULL),
(1273, 108, 14, 1, 1, NULL, '10-04-2023', NULL),
(1274, 109, 1, 1, 1, NULL, '10-04-2023', NULL),
(1275, 109, 5, 1, 1, NULL, '10-04-2023', NULL),
(1276, 109, 6, 1, 1, NULL, '10-04-2023', NULL),
(1277, 109, 7, 1, 1, NULL, '10-04-2023', NULL),
(1278, 109, 8, 1, 1, NULL, '10-04-2023', NULL),
(1279, 109, 9, 1, 1, NULL, '10-04-2023', NULL),
(1280, 109, 10, 1, 1, NULL, '10-04-2023', NULL),
(1281, 109, 11, 1, 1, NULL, '10-04-2023', NULL),
(1282, 109, 12, 1, 1, NULL, '10-04-2023', NULL),
(1283, 109, 13, 1, 1, NULL, '10-04-2023', NULL),
(1284, 109, 14, 1, 1, NULL, '10-04-2023', NULL),
(1285, 110, 1, 1, 1, NULL, '10-04-2023', NULL),
(1286, 110, 5, 1, 1, NULL, '10-04-2023', NULL),
(1287, 110, 6, 1, 1, NULL, '10-04-2023', NULL),
(1288, 110, 7, 1, 1, NULL, '10-04-2023', NULL),
(1289, 110, 8, 1, 1, NULL, '10-04-2023', NULL),
(1290, 110, 9, 1, 1, NULL, '10-04-2023', NULL),
(1291, 110, 10, 1, 1, NULL, '10-04-2023', NULL),
(1292, 110, 11, 1, 1, NULL, '10-04-2023', NULL),
(1293, 110, 12, 1, 1, NULL, '10-04-2023', NULL),
(1294, 110, 13, 1, 1, NULL, '10-04-2023', NULL),
(1295, 110, 14, 1, 1, NULL, '10-04-2023', NULL),
(1296, 111, 1, 1, 1, NULL, '10-04-2023', NULL),
(1297, 111, 5, 1, 1, NULL, '10-04-2023', NULL),
(1298, 111, 6, 1, 1, NULL, '10-04-2023', NULL),
(1299, 111, 7, 1, 1, NULL, '10-04-2023', NULL),
(1300, 111, 8, 1, 1, NULL, '10-04-2023', NULL),
(1301, 111, 9, 1, 1, NULL, '10-04-2023', NULL),
(1302, 111, 10, 1, 1, NULL, '10-04-2023', NULL),
(1303, 111, 11, 1, 1, NULL, '10-04-2023', NULL),
(1304, 111, 12, 1, 1, NULL, '10-04-2023', NULL),
(1305, 111, 13, 1, 1, NULL, '10-04-2023', NULL),
(1306, 111, 14, 1, 1, NULL, '10-04-2023', NULL),
(1307, 112, 1, 1, 1, NULL, '11-04-2023', NULL),
(1308, 112, 5, 1, 1, NULL, '11-04-2023', NULL),
(1309, 112, 6, 1, 1, NULL, '11-04-2023', NULL),
(1310, 112, 7, 1, 1, NULL, '11-04-2023', NULL),
(1311, 112, 8, 1, 1, NULL, '11-04-2023', NULL),
(1312, 112, 9, 1, 1, NULL, '11-04-2023', NULL),
(1313, 112, 10, 1, 1, NULL, '11-04-2023', NULL),
(1314, 112, 11, 1, 1, NULL, '11-04-2023', NULL),
(1315, 112, 12, 1, 1, NULL, '11-04-2023', NULL),
(1316, 112, 13, 1, 1, NULL, '11-04-2023', NULL),
(1317, 112, 14, 1, 1, NULL, '11-04-2023', NULL),
(1318, 113, 1, 1, 1, NULL, '11-04-2023', NULL),
(1319, 113, 5, 1, 1, NULL, '11-04-2023', NULL),
(1320, 113, 6, 1, 1, NULL, '11-04-2023', NULL),
(1321, 113, 7, 1, 1, NULL, '11-04-2023', NULL),
(1322, 113, 8, 1, 1, NULL, '11-04-2023', NULL),
(1323, 113, 9, 1, 1, NULL, '11-04-2023', NULL),
(1324, 113, 10, 1, 1, NULL, '11-04-2023', NULL),
(1325, 113, 11, 1, 1, NULL, '11-04-2023', NULL),
(1326, 113, 12, 1, 1, NULL, '11-04-2023', NULL),
(1327, 113, 13, 1, 1, NULL, '11-04-2023', NULL),
(1328, 113, 14, 1, 1, NULL, '11-04-2023', NULL),
(1329, 114, 1, 1, 1, NULL, '11-04-2023', NULL),
(1330, 114, 5, 1, 1, NULL, '11-04-2023', NULL),
(1331, 114, 6, 1, 1, NULL, '11-04-2023', NULL),
(1332, 114, 7, 1, 1, NULL, '11-04-2023', NULL),
(1333, 114, 8, 1, 1, NULL, '11-04-2023', NULL),
(1334, 114, 9, 1, 1, NULL, '11-04-2023', NULL),
(1335, 114, 10, 1, 1, NULL, '11-04-2023', NULL),
(1336, 114, 11, 1, 1, NULL, '11-04-2023', NULL),
(1337, 114, 12, 1, 1, NULL, '11-04-2023', NULL),
(1338, 114, 13, 1, 1, NULL, '11-04-2023', NULL),
(1339, 114, 14, 1, 1, NULL, '11-04-2023', NULL),
(1440, 122, 1, 1, 1, NULL, '12-04-2023', NULL),
(1441, 122, 5, 1, 1, NULL, '12-04-2023', NULL),
(1442, 122, 6, 1, 1, NULL, '12-04-2023', NULL),
(1443, 122, 7, 1, 1, NULL, '12-04-2023', NULL),
(1444, 122, 8, 1, 1, NULL, '12-04-2023', NULL),
(1445, 122, 9, 1, 1, NULL, '12-04-2023', NULL),
(1446, 122, 10, 1, 1, NULL, '12-04-2023', NULL),
(1447, 122, 11, 1, 1, NULL, '12-04-2023', NULL),
(1448, 122, 12, 1, 1, NULL, '12-04-2023', NULL),
(1449, 122, 13, 1, 1, NULL, '12-04-2023', NULL),
(1450, 122, 14, 1, 1, NULL, '12-04-2023', NULL),
(1473, 123, 1, 1, 1, NULL, '13-04-2023', NULL),
(1474, 123, 6, 1, 1, NULL, '13-04-2023', NULL),
(1475, 123, 7, 1, 1, NULL, '13-04-2023', NULL),
(1476, 123, 8, 1, 1, NULL, '13-04-2023', NULL),
(1477, 123, 9, 1, 1, NULL, '13-04-2023', NULL),
(1478, 123, 10, 1, 1, NULL, '13-04-2023', NULL),
(1479, 123, 11, 1, 1, NULL, '13-04-2023', NULL),
(1480, 123, 12, 1, 1, NULL, '13-04-2023', NULL),
(1481, 123, 13, 1, 1, NULL, '13-04-2023', NULL),
(1482, 123, 14, 1, 1, NULL, '13-04-2023', NULL),
(1483, 124, 1, 1, 1, NULL, '13-04-2023', NULL),
(1484, 124, 5, 1, 1, NULL, '13-04-2023', NULL),
(1485, 124, 6, 1, 1, NULL, '13-04-2023', NULL),
(1486, 124, 7, 1, 1, NULL, '13-04-2023', NULL),
(1487, 124, 8, 1, 1, NULL, '13-04-2023', NULL),
(1488, 124, 9, 1, 1, NULL, '13-04-2023', NULL),
(1489, 124, 10, 1, 1, NULL, '13-04-2023', NULL),
(1490, 124, 11, 1, 1, NULL, '13-04-2023', NULL),
(1491, 124, 12, 1, 1, NULL, '13-04-2023', NULL),
(1492, 124, 13, 1, 1, NULL, '13-04-2023', NULL),
(1493, 124, 14, 1, 1, NULL, '13-04-2023', NULL),
(1494, 102, 1, 1, 1, NULL, '13-04-2023', NULL),
(1495, 102, 2, 1, 1, NULL, '13-04-2023', NULL),
(1496, 102, 3, 1, 1, NULL, '13-04-2023', NULL),
(1497, 102, 4, 1, 1, NULL, '13-04-2023', NULL),
(1498, 102, 5, 1, 1, NULL, '13-04-2023', NULL),
(1499, 102, 6, 1, 1, NULL, '13-04-2023', NULL),
(1500, 102, 7, 1, 1, NULL, '13-04-2023', NULL),
(1501, 102, 8, 1, 1, NULL, '13-04-2023', NULL),
(1502, 102, 9, 1, 1, NULL, '13-04-2023', NULL),
(1503, 102, 10, 1, 1, NULL, '13-04-2023', NULL),
(1504, 102, 11, 1, 1, NULL, '13-04-2023', NULL),
(1505, 102, 12, 1, 1, NULL, '13-04-2023', NULL),
(1506, 102, 13, 1, 1, NULL, '13-04-2023', NULL),
(1507, 102, 14, 1, 1, NULL, '13-04-2023', NULL),
(1522, 22, 1, 1, 1, NULL, '14-04-2023', NULL),
(1523, 22, 2, 1, 1, NULL, '14-04-2023', NULL),
(1524, 22, 3, 1, 1, NULL, '14-04-2023', NULL),
(1525, 22, 4, 1, 1, NULL, '14-04-2023', NULL),
(1526, 22, 5, 1, 1, NULL, '14-04-2023', NULL),
(1527, 22, 6, 1, 1, NULL, '14-04-2023', NULL),
(1528, 22, 7, 1, 1, NULL, '14-04-2023', NULL),
(1529, 22, 8, 1, 1, NULL, '14-04-2023', NULL),
(1530, 22, 9, 1, 1, NULL, '14-04-2023', NULL),
(1531, 22, 10, 1, 1, NULL, '14-04-2023', NULL),
(1532, 22, 11, 1, 1, NULL, '14-04-2023', NULL),
(1533, 22, 12, 1, 1, NULL, '14-04-2023', NULL),
(1534, 22, 13, 1, 1, NULL, '14-04-2023', NULL),
(1535, 22, 14, 1, 1, NULL, '14-04-2023', NULL),
(1550, 20, 1, 1, 1, NULL, '14-04-2023', NULL),
(1551, 20, 2, 1, 1, NULL, '14-04-2023', NULL),
(1552, 20, 3, 1, 1, NULL, '14-04-2023', NULL),
(1553, 20, 4, 1, 1, NULL, '14-04-2023', NULL),
(1554, 20, 5, 1, 1, NULL, '14-04-2023', NULL),
(1555, 20, 6, 1, 1, NULL, '14-04-2023', NULL),
(1556, 20, 7, 1, 1, NULL, '14-04-2023', NULL),
(1557, 20, 8, 1, 1, NULL, '14-04-2023', NULL),
(1558, 20, 9, 1, 1, NULL, '14-04-2023', NULL),
(1559, 20, 10, 1, 1, NULL, '14-04-2023', NULL),
(1560, 20, 11, 1, 1, NULL, '14-04-2023', NULL),
(1561, 20, 12, 1, 1, NULL, '14-04-2023', NULL),
(1562, 20, 13, 1, 1, NULL, '14-04-2023', NULL),
(1563, 20, 14, 1, 1, NULL, '14-04-2023', NULL),
(1663, 126, 1, 1, 1, NULL, '25-04-2023', NULL),
(1664, 126, 5, 1, 1, NULL, '25-04-2023', NULL),
(1665, 126, 6, 1, 1, NULL, '25-04-2023', NULL),
(1666, 126, 7, 1, 1, NULL, '25-04-2023', NULL),
(1667, 126, 8, 1, 1, NULL, '25-04-2023', NULL),
(1668, 126, 9, 1, 1, NULL, '25-04-2023', NULL),
(1669, 126, 10, 1, 1, NULL, '25-04-2023', NULL),
(1670, 126, 11, 1, 1, NULL, '25-04-2023', NULL),
(1671, 126, 12, 1, 1, NULL, '25-04-2023', NULL),
(1672, 126, 13, 1, 1, NULL, '25-04-2023', NULL),
(1673, 126, 14, 1, 1, NULL, '25-04-2023', NULL),
(1729, 128, 1, 1, 1, NULL, '26-04-2023', NULL),
(1730, 128, 5, 1, 1, NULL, '26-04-2023', NULL),
(1731, 128, 6, 1, 1, NULL, '26-04-2023', NULL),
(1732, 128, 7, 1, 1, NULL, '26-04-2023', NULL),
(1733, 128, 8, 1, 1, NULL, '26-04-2023', NULL),
(1734, 128, 9, 1, 1, NULL, '26-04-2023', NULL),
(1735, 128, 10, 1, 1, NULL, '26-04-2023', NULL),
(1736, 128, 11, 1, 1, NULL, '26-04-2023', NULL),
(1737, 128, 12, 1, 1, NULL, '26-04-2023', NULL),
(1738, 128, 13, 1, 1, NULL, '26-04-2023', NULL),
(1739, 128, 14, 1, 1, NULL, '26-04-2023', NULL),
(1740, 127, 1, 1, 1, NULL, '26-04-2023', NULL),
(1741, 127, 5, 1, 1, NULL, '26-04-2023', NULL),
(1742, 127, 6, 1, 1, NULL, '26-04-2023', NULL),
(1743, 127, 7, 1, 1, NULL, '26-04-2023', NULL),
(1744, 127, 8, 1, 1, NULL, '26-04-2023', NULL),
(1745, 127, 9, 1, 1, NULL, '26-04-2023', NULL),
(1746, 127, 10, 1, 1, NULL, '26-04-2023', NULL),
(1747, 127, 11, 1, 1, NULL, '26-04-2023', NULL),
(1748, 127, 12, 1, 1, NULL, '26-04-2023', NULL),
(1749, 127, 13, 1, 1, NULL, '26-04-2023', NULL),
(1750, 127, 14, 1, 1, NULL, '26-04-2023', NULL),
(1773, 129, 1, 1, 1, NULL, '27-04-2023', NULL),
(1774, 129, 5, 1, 1, NULL, '27-04-2023', NULL),
(1775, 129, 6, 1, 1, NULL, '27-04-2023', NULL),
(1776, 129, 7, 1, 1, NULL, '27-04-2023', NULL),
(1777, 129, 8, 1, 1, NULL, '27-04-2023', NULL),
(1778, 129, 9, 1, 1, NULL, '27-04-2023', NULL),
(1779, 130, 1, 1, 1, NULL, '27-04-2023', NULL),
(1780, 130, 5, 1, 1, NULL, '27-04-2023', NULL),
(1781, 130, 6, 1, 1, NULL, '27-04-2023', NULL),
(1782, 130, 7, 1, 1, NULL, '27-04-2023', NULL),
(1783, 130, 8, 1, 1, NULL, '27-04-2023', NULL),
(1784, 130, 9, 1, 1, NULL, '27-04-2023', NULL),
(1785, 131, 1, 1, 1, NULL, '27-04-2023', NULL),
(1786, 131, 5, 1, 1, NULL, '27-04-2023', NULL),
(1787, 131, 6, 1, 1, NULL, '27-04-2023', NULL),
(1788, 131, 7, 1, 1, NULL, '27-04-2023', NULL),
(1789, 131, 8, 1, 1, NULL, '27-04-2023', NULL),
(1790, 131, 9, 1, 1, NULL, '27-04-2023', NULL),
(1791, 132, 1, 1, 1, NULL, '27-04-2023', NULL),
(1792, 132, 5, 1, 1, NULL, '27-04-2023', NULL),
(1793, 132, 6, 1, 1, NULL, '27-04-2023', NULL),
(1794, 132, 7, 1, 1, NULL, '27-04-2023', NULL),
(1795, 132, 8, 1, 1, NULL, '27-04-2023', NULL),
(1796, 132, 9, 1, 1, NULL, '27-04-2023', NULL),
(1797, 133, 1, 1, 1, NULL, '27-04-2023', NULL),
(1798, 133, 5, 1, 1, NULL, '27-04-2023', NULL),
(1799, 133, 6, 1, 1, NULL, '27-04-2023', NULL),
(1800, 133, 7, 1, 1, NULL, '27-04-2023', NULL),
(1801, 133, 8, 1, 1, NULL, '27-04-2023', NULL),
(1802, 134, 1, 1, 1, NULL, '27-04-2023', NULL),
(1803, 134, 5, 1, 1, NULL, '27-04-2023', NULL),
(1804, 134, 6, 1, 1, NULL, '27-04-2023', NULL),
(1805, 134, 7, 1, 1, NULL, '27-04-2023', NULL),
(1806, 134, 8, 1, 1, NULL, '27-04-2023', NULL),
(1807, 135, 1, 1, 1, NULL, '27-04-2023', NULL),
(1808, 135, 5, 1, 1, NULL, '27-04-2023', NULL),
(1809, 135, 6, 1, 1, NULL, '27-04-2023', NULL),
(1810, 135, 7, 1, 1, NULL, '27-04-2023', NULL),
(1811, 135, 8, 1, 1, NULL, '27-04-2023', NULL),
(1812, 135, 9, 1, 1, NULL, '27-04-2023', NULL),
(1813, 135, 10, 1, 1, NULL, '27-04-2023', NULL),
(1814, 135, 11, 1, 1, NULL, '27-04-2023', NULL),
(1815, 135, 12, 1, 1, NULL, '27-04-2023', NULL),
(1816, 135, 13, 1, 1, NULL, '27-04-2023', NULL),
(1817, 135, 14, 1, 1, NULL, '27-04-2023', NULL),
(1818, 136, 1, 1, 1, NULL, '01-05-2023', NULL),
(1819, 136, 5, 1, 1, NULL, '01-05-2023', NULL),
(1820, 136, 6, 1, 1, NULL, '01-05-2023', NULL),
(1821, 136, 7, 1, 1, NULL, '01-05-2023', NULL),
(1822, 136, 8, 1, 1, NULL, '01-05-2023', NULL),
(1823, 136, 9, 1, 1, NULL, '01-05-2023', NULL),
(1824, 136, 10, 1, 1, NULL, '01-05-2023', NULL),
(1825, 136, 11, 1, 1, NULL, '01-05-2023', NULL),
(1826, 136, 12, 1, 1, NULL, '01-05-2023', NULL),
(1827, 136, 13, 1, 1, NULL, '01-05-2023', NULL),
(1828, 136, 14, 1, 1, NULL, '01-05-2023', NULL),
(1829, 66, 1, 1, 1, NULL, '01-05-2023', NULL),
(1830, 66, 5, 1, 1, NULL, '01-05-2023', NULL),
(1831, 66, 6, 1, 1, NULL, '01-05-2023', NULL),
(1832, 66, 7, 1, 1, NULL, '01-05-2023', NULL),
(1833, 66, 8, 1, 1, NULL, '01-05-2023', NULL),
(1834, 66, 9, 1, 1, NULL, '01-05-2023', NULL),
(1835, 66, 10, 1, 1, NULL, '01-05-2023', NULL),
(1836, 66, 11, 1, 1, NULL, '01-05-2023', NULL),
(1837, 66, 12, 1, 1, NULL, '01-05-2023', NULL),
(1838, 66, 13, 1, 1, NULL, '01-05-2023', NULL),
(1839, 66, 14, 1, 1, NULL, '01-05-2023', NULL),
(1851, 116, 1, 1, 1, NULL, '05-05-2023', NULL),
(1852, 116, 5, 1, 1, NULL, '05-05-2023', NULL),
(1853, 116, 6, 1, 1, NULL, '05-05-2023', NULL),
(1854, 116, 7, 1, 1, NULL, '05-05-2023', NULL),
(1855, 116, 8, 1, 1, NULL, '05-05-2023', NULL),
(1856, 116, 9, 1, 1, NULL, '05-05-2023', NULL),
(1857, 116, 10, 1, 1, NULL, '05-05-2023', NULL),
(1858, 116, 11, 1, 1, NULL, '05-05-2023', NULL),
(1859, 116, 12, 1, 1, NULL, '05-05-2023', NULL),
(1860, 116, 13, 1, 1, NULL, '05-05-2023', NULL),
(1861, 116, 14, 1, 1, NULL, '05-05-2023', NULL),
(1873, 117, 1, 1, 1, NULL, '05-05-2023', NULL),
(1874, 117, 5, 1, 1, NULL, '05-05-2023', NULL),
(1875, 117, 6, 1, 1, NULL, '05-05-2023', NULL),
(1876, 117, 7, 1, 1, NULL, '05-05-2023', NULL),
(1877, 117, 8, 1, 1, NULL, '05-05-2023', NULL),
(1878, 117, 9, 1, 1, NULL, '05-05-2023', NULL),
(1879, 117, 10, 1, 1, NULL, '05-05-2023', NULL),
(1880, 117, 11, 1, 1, NULL, '05-05-2023', NULL),
(1881, 117, 12, 1, 1, NULL, '05-05-2023', NULL),
(1882, 117, 13, 1, 1, NULL, '05-05-2023', NULL),
(1883, 117, 14, 1, 1, NULL, '05-05-2023', NULL),
(1934, 53, 1, 1, 1, NULL, '09-05-2023', NULL),
(1935, 53, 2, 1, 1, NULL, '09-05-2023', NULL),
(1936, 53, 3, 1, 1, NULL, '09-05-2023', NULL),
(1937, 53, 4, 1, 1, NULL, '09-05-2023', NULL),
(1938, 53, 5, 1, 1, NULL, '09-05-2023', NULL),
(1939, 53, 6, 1, 1, NULL, '09-05-2023', NULL),
(1940, 53, 7, 1, 1, NULL, '09-05-2023', NULL),
(1941, 53, 8, 1, 1, NULL, '09-05-2023', NULL),
(1942, 53, 9, 1, 1, NULL, '09-05-2023', NULL),
(1943, 53, 10, 1, 1, NULL, '09-05-2023', NULL),
(1944, 53, 11, 1, 1, NULL, '09-05-2023', NULL),
(1945, 53, 12, 1, 1, NULL, '09-05-2023', NULL),
(1946, 53, 13, 1, 1, NULL, '09-05-2023', NULL),
(1947, 53, 14, 1, 1, NULL, '09-05-2023', NULL),
(1976, 54, 1, 1, 1, NULL, '09-05-2023', NULL),
(1977, 54, 2, 1, 1, NULL, '09-05-2023', NULL),
(1978, 54, 3, 1, 1, NULL, '09-05-2023', NULL),
(1979, 54, 4, 1, 1, NULL, '09-05-2023', NULL),
(1980, 54, 5, 1, 1, NULL, '09-05-2023', NULL),
(1981, 54, 6, 1, 1, NULL, '09-05-2023', NULL),
(1982, 54, 7, 1, 1, NULL, '09-05-2023', NULL),
(1983, 54, 8, 1, 1, NULL, '09-05-2023', NULL),
(1984, 54, 9, 1, 1, NULL, '09-05-2023', NULL),
(1985, 54, 10, 1, 1, NULL, '09-05-2023', NULL),
(1986, 54, 11, 1, 1, NULL, '09-05-2023', NULL),
(1987, 54, 12, 1, 1, NULL, '09-05-2023', NULL),
(1988, 54, 13, 1, 1, NULL, '09-05-2023', NULL),
(1989, 54, 14, 1, 1, NULL, '09-05-2023', NULL),
(2001, 137, 1, 1, 1, NULL, '10-05-2023', NULL),
(2002, 137, 5, 1, 1, NULL, '10-05-2023', NULL),
(2003, 137, 6, 1, 1, NULL, '10-05-2023', NULL),
(2004, 137, 7, 1, 1, NULL, '10-05-2023', NULL),
(2005, 137, 8, 1, 1, NULL, '10-05-2023', NULL),
(2006, 137, 9, 1, 1, NULL, '10-05-2023', NULL),
(2007, 137, 10, 1, 1, NULL, '10-05-2023', NULL),
(2008, 137, 11, 1, 1, NULL, '10-05-2023', NULL),
(2009, 137, 12, 1, 1, NULL, '10-05-2023', NULL),
(2010, 137, 13, 1, 1, NULL, '10-05-2023', NULL),
(2011, 137, 14, 1, 1, NULL, '10-05-2023', NULL),
(2012, 138, 1, 1, 1, NULL, '11-05-2023', NULL),
(2013, 138, 5, 1, 1, NULL, '11-05-2023', NULL),
(2014, 138, 6, 1, 1, NULL, '11-05-2023', NULL),
(2015, 138, 7, 1, 1, NULL, '11-05-2023', NULL),
(2016, 138, 8, 1, 1, NULL, '11-05-2023', NULL),
(2017, 138, 9, 1, 1, NULL, '11-05-2023', NULL),
(2018, 138, 10, 1, 1, NULL, '11-05-2023', NULL),
(2019, 138, 11, 1, 1, NULL, '11-05-2023', NULL),
(2020, 138, 12, 1, 1, NULL, '11-05-2023', NULL),
(2021, 138, 13, 1, 1, NULL, '11-05-2023', NULL),
(2022, 138, 14, 1, 1, NULL, '11-05-2023', NULL),
(2051, 13, 1, 1, 1, NULL, '23-05-2023', NULL),
(2052, 13, 2, 1, 1, NULL, '23-05-2023', NULL),
(2053, 13, 3, 1, 1, NULL, '23-05-2023', NULL),
(2054, 13, 4, 1, 1, NULL, '23-05-2023', NULL),
(2055, 13, 5, 1, 1, NULL, '23-05-2023', NULL),
(2056, 13, 6, 1, 1, NULL, '23-05-2023', NULL),
(2057, 13, 7, 1, 1, NULL, '23-05-2023', NULL),
(2058, 13, 8, 1, 1, NULL, '23-05-2023', NULL),
(2059, 13, 9, 1, 1, NULL, '23-05-2023', NULL),
(2060, 13, 10, 1, 1, NULL, '23-05-2023', NULL),
(2061, 13, 11, 1, 1, NULL, '23-05-2023', NULL),
(2062, 13, 12, 1, 1, NULL, '23-05-2023', NULL),
(2063, 13, 13, 1, 1, NULL, '23-05-2023', NULL),
(2064, 13, 14, 1, 1, NULL, '23-05-2023', NULL),
(2065, 57, 1, 1, 1, NULL, '23-05-2023', NULL),
(2066, 57, 2, 1, 1, NULL, '23-05-2023', NULL),
(2067, 57, 3, 1, 1, NULL, '23-05-2023', NULL),
(2068, 57, 4, 1, 1, NULL, '23-05-2023', NULL),
(2069, 57, 5, 1, 1, NULL, '23-05-2023', NULL),
(2070, 57, 6, 1, 1, NULL, '23-05-2023', NULL),
(2071, 57, 7, 1, 1, NULL, '23-05-2023', NULL),
(2072, 57, 8, 1, 1, NULL, '23-05-2023', NULL),
(2073, 57, 9, 1, 1, NULL, '23-05-2023', NULL),
(2074, 57, 10, 1, 1, NULL, '23-05-2023', NULL),
(2075, 57, 11, 1, 1, NULL, '23-05-2023', NULL),
(2076, 57, 12, 1, 1, NULL, '23-05-2023', NULL),
(2077, 57, 13, 1, 1, NULL, '23-05-2023', NULL),
(2078, 57, 14, 1, 1, NULL, '23-05-2023', NULL),
(2188, 140, 1, 1, 1, NULL, '02-08-2023', NULL),
(2189, 140, 2, 1, 1, NULL, '02-08-2023', NULL),
(2190, 140, 3, 1, 1, NULL, '02-08-2023', NULL),
(2191, 140, 4, 1, 1, NULL, '02-08-2023', NULL),
(2192, 140, 5, 1, 1, NULL, '02-08-2023', NULL),
(2193, 140, 6, 1, 1, NULL, '02-08-2023', NULL),
(2194, 140, 7, 1, 1, NULL, '02-08-2023', NULL),
(2195, 140, 8, 1, 1, NULL, '02-08-2023', NULL),
(2196, 140, 9, 1, 1, NULL, '02-08-2023', NULL),
(2197, 140, 10, 1, 1, NULL, '02-08-2023', NULL),
(2198, 140, 11, 1, 1, NULL, '02-08-2023', NULL),
(2199, 140, 12, 1, 1, NULL, '02-08-2023', NULL),
(2200, 140, 13, 1, 1, NULL, '02-08-2023', NULL),
(2201, 140, 14, 1, 1, NULL, '02-08-2023', NULL),
(2235, 141, 1, 1, 1, NULL, '03-08-2023', NULL),
(2236, 141, 5, 1, 1, NULL, '03-08-2023', NULL),
(2237, 141, 6, 1, 1, NULL, '03-08-2023', NULL),
(2238, 141, 7, 1, 1, NULL, '03-08-2023', NULL),
(2239, 141, 8, 1, 1, NULL, '03-08-2023', NULL),
(2240, 141, 9, 1, 1, NULL, '03-08-2023', NULL),
(2241, 141, 10, 1, 1, NULL, '03-08-2023', NULL),
(2242, 141, 11, 1, 1, NULL, '03-08-2023', NULL),
(2243, 141, 12, 1, 1, NULL, '03-08-2023', NULL),
(2244, 141, 13, 1, 1, NULL, '03-08-2023', NULL),
(2245, 141, 14, 1, 1, NULL, '03-08-2023', NULL),
(2274, 24, 1, 1, 1, NULL, '04-08-2023', NULL),
(2275, 24, 5, 1, 1, NULL, '04-08-2023', NULL),
(2276, 24, 6, 1, 1, NULL, '04-08-2023', NULL),
(2277, 24, 7, 1, 1, NULL, '04-08-2023', NULL),
(2278, 24, 8, 1, 1, NULL, '04-08-2023', NULL),
(2279, 24, 9, 1, 1, NULL, '04-08-2023', NULL),
(2280, 24, 10, 1, 1, NULL, '04-08-2023', NULL),
(2281, 24, 11, 1, 1, NULL, '04-08-2023', NULL),
(2282, 24, 12, 1, 1, NULL, '04-08-2023', NULL),
(2283, 24, 13, 1, 1, NULL, '04-08-2023', NULL),
(2284, 24, 14, 1, 1, NULL, '04-08-2023', NULL),
(2296, 115, 1, 1, 1, NULL, '05-08-2023', NULL),
(2297, 115, 5, 1, 1, NULL, '05-08-2023', NULL),
(2298, 115, 6, 1, 1, NULL, '05-08-2023', NULL),
(2299, 115, 7, 1, 1, NULL, '05-08-2023', NULL),
(2300, 115, 8, 1, 1, NULL, '05-08-2023', NULL),
(2301, 115, 9, 1, 1, NULL, '05-08-2023', NULL),
(2302, 115, 10, 1, 1, NULL, '05-08-2023', NULL),
(2303, 115, 11, 1, 1, NULL, '05-08-2023', NULL),
(2304, 115, 12, 1, 1, NULL, '05-08-2023', NULL),
(2305, 115, 13, 1, 1, NULL, '05-08-2023', NULL),
(2306, 115, 14, 1, 1, NULL, '05-08-2023', NULL),
(2307, 25, 1, 1, 1, NULL, '08-08-2023', NULL),
(2308, 25, 5, 1, 1, NULL, '08-08-2023', NULL),
(2309, 25, 6, 1, 1, NULL, '08-08-2023', NULL),
(2310, 25, 7, 1, 1, NULL, '08-08-2023', NULL),
(2311, 25, 8, 1, 1, NULL, '08-08-2023', NULL),
(2312, 25, 9, 1, 1, NULL, '08-08-2023', NULL),
(2313, 25, 10, 1, 1, NULL, '08-08-2023', NULL),
(2314, 25, 11, 1, 1, NULL, '08-08-2023', NULL),
(2315, 25, 12, 1, 1, NULL, '08-08-2023', NULL),
(2316, 25, 13, 1, 1, NULL, '08-08-2023', NULL),
(2317, 25, 14, 1, 1, NULL, '08-08-2023', NULL);
INSERT INTO `tbl_page_designations` (`id`, `page_id`, `designation_id`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2381, 143, 1, 1, 1, NULL, '09-08-2023', NULL),
(2382, 143, 6, 1, 1, NULL, '09-08-2023', NULL),
(2383, 143, 7, 1, 1, NULL, '09-08-2023', NULL),
(2384, 143, 8, 1, 1, NULL, '09-08-2023', NULL),
(2385, 143, 9, 1, 1, NULL, '09-08-2023', NULL),
(2386, 143, 10, 1, 1, NULL, '09-08-2023', NULL),
(2387, 143, 11, 1, 1, NULL, '09-08-2023', NULL),
(2388, 143, 12, 1, 1, NULL, '09-08-2023', NULL),
(2389, 143, 13, 1, 1, NULL, '09-08-2023', NULL),
(2390, 143, 14, 1, 1, NULL, '09-08-2023', NULL),
(2419, 139, 1, 1, 118, NULL, '09-08-2023', NULL),
(2420, 139, 2, 1, 118, NULL, '09-08-2023', NULL),
(2421, 139, 3, 1, 118, NULL, '09-08-2023', NULL),
(2422, 139, 4, 1, 118, NULL, '09-08-2023', NULL),
(2423, 139, 5, 1, 118, NULL, '09-08-2023', NULL),
(2424, 139, 6, 1, 118, NULL, '09-08-2023', NULL),
(2425, 139, 7, 1, 118, NULL, '09-08-2023', NULL),
(2426, 139, 8, 1, 118, NULL, '09-08-2023', NULL),
(2427, 139, 9, 1, 118, NULL, '09-08-2023', NULL),
(2428, 139, 10, 1, 118, NULL, '09-08-2023', NULL),
(2429, 139, 11, 1, 118, NULL, '09-08-2023', NULL),
(2430, 139, 12, 1, 118, NULL, '09-08-2023', NULL),
(2431, 139, 13, 1, 118, NULL, '09-08-2023', NULL),
(2432, 139, 14, 1, 118, NULL, '09-08-2023', NULL),
(2444, 23, 1, 1, 118, NULL, '09-08-2023', NULL),
(2445, 23, 5, 1, 118, NULL, '09-08-2023', NULL),
(2446, 23, 6, 1, 118, NULL, '09-08-2023', NULL),
(2447, 23, 7, 1, 118, NULL, '09-08-2023', NULL),
(2448, 23, 8, 1, 118, NULL, '09-08-2023', NULL),
(2449, 23, 9, 1, 118, NULL, '09-08-2023', NULL),
(2450, 23, 10, 1, 118, NULL, '09-08-2023', NULL),
(2451, 23, 11, 1, 118, NULL, '09-08-2023', NULL),
(2452, 23, 12, 1, 118, NULL, '09-08-2023', NULL),
(2453, 23, 13, 1, 118, NULL, '09-08-2023', NULL),
(2454, 23, 14, 1, 118, NULL, '09-08-2023', NULL),
(2477, 3, 1, 1, 118, NULL, '09-08-2023', NULL),
(2478, 3, 5, 1, 118, NULL, '09-08-2023', NULL),
(2479, 3, 6, 1, 118, NULL, '09-08-2023', NULL),
(2480, 3, 7, 1, 118, NULL, '09-08-2023', NULL),
(2481, 3, 8, 1, 118, NULL, '09-08-2023', NULL),
(2482, 3, 9, 1, 118, NULL, '09-08-2023', NULL),
(2483, 3, 10, 1, 118, NULL, '09-08-2023', NULL),
(2484, 3, 11, 1, 118, NULL, '09-08-2023', NULL),
(2485, 3, 12, 1, 118, NULL, '09-08-2023', NULL),
(2486, 3, 13, 1, 118, NULL, '09-08-2023', NULL),
(2487, 3, 14, 1, 118, NULL, '09-08-2023', NULL),
(2499, 63, 1, 1, 118, NULL, '19-08-2023', NULL),
(2500, 142, 1, 1, 1, NULL, '28-08-2023', NULL),
(2501, 142, 6, 1, 1, NULL, '28-08-2023', NULL),
(2502, 142, 7, 1, 1, NULL, '28-08-2023', NULL),
(2503, 142, 8, 1, 1, NULL, '28-08-2023', NULL),
(2504, 142, 9, 1, 1, NULL, '28-08-2023', NULL),
(2505, 142, 10, 1, 1, NULL, '28-08-2023', NULL),
(2506, 142, 11, 1, 1, NULL, '28-08-2023', NULL),
(2507, 142, 12, 1, 1, NULL, '28-08-2023', NULL),
(2508, 142, 13, 1, 1, NULL, '28-08-2023', NULL),
(2509, 142, 14, 1, 1, NULL, '28-08-2023', NULL),
(2510, 27, 1, 1, 1, NULL, '28-08-2023', NULL),
(2511, 27, 5, 1, 1, NULL, '28-08-2023', NULL),
(2512, 27, 6, 1, 1, NULL, '28-08-2023', NULL),
(2513, 27, 7, 1, 1, NULL, '28-08-2023', NULL),
(2514, 27, 8, 1, 1, NULL, '28-08-2023', NULL),
(2515, 27, 9, 1, 1, NULL, '28-08-2023', NULL),
(2516, 27, 10, 1, 1, NULL, '28-08-2023', NULL),
(2517, 27, 11, 1, 1, NULL, '28-08-2023', NULL),
(2518, 27, 12, 1, 1, NULL, '28-08-2023', NULL),
(2519, 27, 13, 1, 1, NULL, '28-08-2023', NULL),
(2520, 27, 14, 1, 1, NULL, '28-08-2023', NULL),
(2557, 30, 1, 1, 1, NULL, '28-08-2023', NULL),
(2558, 30, 5, 1, 1, NULL, '28-08-2023', NULL),
(2559, 30, 6, 1, 1, NULL, '28-08-2023', NULL),
(2560, 30, 7, 1, 1, NULL, '28-08-2023', NULL),
(2561, 30, 8, 1, 1, NULL, '28-08-2023', NULL),
(2562, 30, 9, 1, 1, NULL, '28-08-2023', NULL),
(2563, 30, 10, 1, 1, NULL, '28-08-2023', NULL),
(2564, 30, 11, 1, 1, NULL, '28-08-2023', NULL),
(2565, 30, 12, 1, 1, NULL, '28-08-2023', NULL),
(2566, 30, 13, 1, 1, NULL, '28-08-2023', NULL),
(2567, 30, 14, 1, 1, NULL, '28-08-2023', NULL),
(2601, 145, 1, 1, 1, NULL, '28-08-2023', NULL),
(2602, 38, 1, 1, 1, NULL, '31-08-2023', NULL),
(2603, 38, 5, 1, 1, NULL, '31-08-2023', NULL),
(2604, 38, 6, 1, 1, NULL, '31-08-2023', NULL),
(2605, 38, 7, 1, 1, NULL, '31-08-2023', NULL),
(2606, 38, 8, 1, 1, NULL, '31-08-2023', NULL),
(2607, 38, 9, 1, 1, NULL, '31-08-2023', NULL),
(2608, 38, 10, 1, 1, NULL, '31-08-2023', NULL),
(2609, 38, 11, 1, 1, NULL, '31-08-2023', NULL),
(2610, 38, 12, 1, 1, NULL, '31-08-2023', NULL),
(2611, 38, 13, 1, 1, NULL, '31-08-2023', NULL),
(2612, 38, 14, 1, 1, NULL, '31-08-2023', NULL),
(2613, 83, 1, 1, 1, NULL, '31-08-2023', NULL),
(2614, 83, 5, 1, 1, NULL, '31-08-2023', NULL),
(2615, 83, 6, 1, 1, NULL, '31-08-2023', NULL),
(2616, 83, 7, 1, 1, NULL, '31-08-2023', NULL),
(2617, 83, 8, 1, 1, NULL, '31-08-2023', NULL),
(2618, 83, 9, 1, 1, NULL, '31-08-2023', NULL),
(2619, 83, 11, 1, 1, NULL, '31-08-2023', NULL),
(2620, 83, 12, 1, 1, NULL, '31-08-2023', NULL),
(2621, 83, 13, 1, 1, NULL, '31-08-2023', NULL),
(2622, 83, 14, 1, 1, NULL, '31-08-2023', NULL),
(2645, 147, 1, 1, 118, NULL, '07-09-2023', NULL),
(2657, 148, 1, 1, 118, NULL, '07-09-2023', NULL),
(2658, 149, 1, 1, 118, NULL, '07-09-2023', NULL),
(2670, 144, 1, 1, 1, NULL, '08-09-2023', NULL),
(2671, 144, 5, 1, 1, NULL, '08-09-2023', NULL),
(2672, 144, 6, 1, 1, NULL, '08-09-2023', NULL),
(2673, 144, 7, 1, 1, NULL, '08-09-2023', NULL),
(2674, 144, 8, 1, 1, NULL, '08-09-2023', NULL),
(2675, 144, 9, 1, 1, NULL, '08-09-2023', NULL),
(2676, 144, 10, 1, 1, NULL, '08-09-2023', NULL),
(2677, 144, 11, 1, 1, NULL, '08-09-2023', NULL),
(2678, 144, 12, 1, 1, NULL, '08-09-2023', NULL),
(2679, 144, 13, 1, 1, NULL, '08-09-2023', NULL),
(2680, 144, 14, 1, 1, NULL, '08-09-2023', NULL),
(2692, 150, 1, 1, 118, NULL, '14-09-2023', NULL),
(2693, 150, 5, 1, 118, NULL, '14-09-2023', NULL),
(2694, 150, 6, 1, 118, NULL, '14-09-2023', NULL),
(2695, 150, 7, 1, 118, NULL, '14-09-2023', NULL),
(2696, 150, 8, 1, 118, NULL, '14-09-2023', NULL),
(2697, 150, 9, 1, 118, NULL, '14-09-2023', NULL),
(2698, 150, 10, 1, 118, NULL, '14-09-2023', NULL),
(2699, 150, 11, 1, 118, NULL, '14-09-2023', NULL),
(2700, 150, 12, 1, 118, NULL, '14-09-2023', NULL),
(2701, 150, 13, 1, 118, NULL, '14-09-2023', NULL),
(2702, 150, 14, 1, 118, NULL, '14-09-2023', NULL),
(2703, 151, 1, 1, 118, NULL, '15-09-2023', NULL),
(2704, 151, 5, 1, 118, NULL, '15-09-2023', NULL),
(2705, 151, 6, 1, 118, NULL, '15-09-2023', NULL),
(2706, 151, 7, 1, 118, NULL, '15-09-2023', NULL),
(2707, 151, 8, 1, 118, NULL, '15-09-2023', NULL),
(2708, 151, 9, 1, 118, NULL, '15-09-2023', NULL),
(2709, 151, 10, 1, 118, NULL, '15-09-2023', NULL),
(2710, 151, 11, 1, 118, NULL, '15-09-2023', NULL),
(2711, 151, 12, 1, 118, NULL, '15-09-2023', NULL),
(2712, 151, 13, 1, 118, NULL, '15-09-2023', NULL),
(2713, 151, 14, 1, 118, NULL, '15-09-2023', NULL),
(2714, 42, 1, 1, 118, NULL, '18-09-2023', NULL),
(2715, 42, 2, 1, 118, NULL, '18-09-2023', NULL),
(2716, 42, 3, 1, 118, NULL, '18-09-2023', NULL),
(2717, 42, 4, 1, 118, NULL, '18-09-2023', NULL),
(2718, 42, 5, 1, 118, NULL, '18-09-2023', NULL),
(2719, 42, 6, 1, 118, NULL, '18-09-2023', NULL),
(2720, 42, 7, 1, 118, NULL, '18-09-2023', NULL),
(2721, 42, 8, 1, 118, NULL, '18-09-2023', NULL),
(2722, 42, 9, 1, 118, NULL, '18-09-2023', NULL),
(2723, 42, 10, 1, 118, NULL, '18-09-2023', NULL),
(2724, 42, 11, 1, 118, NULL, '18-09-2023', NULL),
(2725, 42, 12, 1, 118, NULL, '18-09-2023', NULL),
(2726, 42, 13, 1, 118, NULL, '18-09-2023', NULL),
(2727, 42, 14, 1, 118, NULL, '18-09-2023', NULL),
(2728, 52, 1, 1, 118, NULL, '18-09-2023', NULL),
(2729, 52, 2, 1, 118, NULL, '18-09-2023', NULL),
(2730, 52, 3, 1, 118, NULL, '18-09-2023', NULL),
(2731, 52, 4, 1, 118, NULL, '18-09-2023', NULL),
(2732, 52, 5, 1, 118, NULL, '18-09-2023', NULL),
(2733, 52, 6, 1, 118, NULL, '18-09-2023', NULL),
(2734, 52, 7, 1, 118, NULL, '18-09-2023', NULL),
(2735, 52, 8, 1, 118, NULL, '18-09-2023', NULL),
(2736, 52, 9, 1, 118, NULL, '18-09-2023', NULL),
(2737, 52, 10, 1, 118, NULL, '18-09-2023', NULL),
(2738, 52, 11, 1, 118, NULL, '18-09-2023', NULL),
(2739, 52, 12, 1, 118, NULL, '18-09-2023', NULL),
(2740, 52, 13, 1, 118, NULL, '18-09-2023', NULL),
(2741, 52, 14, 1, 118, NULL, '18-09-2023', NULL),
(2756, 15, 1, 1, 118, NULL, '18-09-2023', NULL),
(2757, 15, 2, 1, 118, NULL, '18-09-2023', NULL),
(2758, 15, 3, 1, 118, NULL, '18-09-2023', NULL),
(2759, 15, 4, 1, 118, NULL, '18-09-2023', NULL),
(2760, 15, 5, 1, 118, NULL, '18-09-2023', NULL),
(2761, 15, 6, 1, 118, NULL, '18-09-2023', NULL),
(2762, 15, 7, 1, 118, NULL, '18-09-2023', NULL),
(2763, 15, 8, 1, 118, NULL, '18-09-2023', NULL),
(2764, 15, 9, 1, 118, NULL, '18-09-2023', NULL),
(2765, 15, 10, 1, 118, NULL, '18-09-2023', NULL),
(2766, 15, 11, 1, 118, NULL, '18-09-2023', NULL),
(2767, 15, 12, 1, 118, NULL, '18-09-2023', NULL),
(2768, 15, 13, 1, 118, NULL, '18-09-2023', NULL),
(2769, 15, 14, 1, 118, NULL, '18-09-2023', NULL),
(2770, 18, 1, 1, 118, NULL, '18-09-2023', NULL),
(2771, 18, 2, 1, 118, NULL, '18-09-2023', NULL),
(2772, 18, 3, 1, 118, NULL, '18-09-2023', NULL),
(2773, 18, 4, 1, 118, NULL, '18-09-2023', NULL),
(2774, 18, 5, 1, 118, NULL, '18-09-2023', NULL),
(2775, 18, 6, 1, 118, NULL, '18-09-2023', NULL),
(2776, 18, 7, 1, 118, NULL, '18-09-2023', NULL),
(2777, 18, 8, 1, 118, NULL, '18-09-2023', NULL),
(2778, 18, 9, 1, 118, NULL, '18-09-2023', NULL),
(2779, 18, 10, 1, 118, NULL, '18-09-2023', NULL),
(2780, 18, 11, 1, 118, NULL, '18-09-2023', NULL),
(2781, 18, 12, 1, 118, NULL, '18-09-2023', NULL),
(2782, 18, 13, 1, 118, NULL, '18-09-2023', NULL),
(2783, 18, 14, 1, 118, NULL, '18-09-2023', NULL),
(2784, 16, 1, 1, 118, NULL, '18-09-2023', NULL),
(2785, 16, 2, 1, 118, NULL, '18-09-2023', NULL),
(2786, 16, 3, 1, 118, NULL, '18-09-2023', NULL),
(2787, 16, 4, 1, 118, NULL, '18-09-2023', NULL),
(2788, 16, 5, 1, 118, NULL, '18-09-2023', NULL),
(2789, 16, 6, 1, 118, NULL, '18-09-2023', NULL),
(2790, 16, 7, 1, 118, NULL, '18-09-2023', NULL),
(2791, 16, 8, 1, 118, NULL, '18-09-2023', NULL),
(2792, 16, 9, 1, 118, NULL, '18-09-2023', NULL),
(2793, 16, 10, 1, 118, NULL, '18-09-2023', NULL),
(2794, 16, 11, 1, 118, NULL, '18-09-2023', NULL),
(2795, 16, 12, 1, 118, NULL, '18-09-2023', NULL),
(2796, 16, 13, 1, 118, NULL, '18-09-2023', NULL),
(2797, 16, 14, 1, 118, NULL, '18-09-2023', NULL),
(2798, 17, 1, 1, 118, NULL, '18-09-2023', NULL),
(2799, 17, 2, 1, 118, NULL, '18-09-2023', NULL),
(2800, 17, 3, 1, 118, NULL, '18-09-2023', NULL),
(2801, 17, 4, 1, 118, NULL, '18-09-2023', NULL),
(2802, 17, 5, 1, 118, NULL, '18-09-2023', NULL),
(2803, 17, 6, 1, 118, NULL, '18-09-2023', NULL),
(2804, 17, 7, 1, 118, NULL, '18-09-2023', NULL),
(2805, 17, 8, 1, 118, NULL, '18-09-2023', NULL),
(2806, 17, 9, 1, 118, NULL, '18-09-2023', NULL),
(2807, 17, 10, 1, 118, NULL, '18-09-2023', NULL),
(2808, 17, 11, 1, 118, NULL, '18-09-2023', NULL),
(2809, 17, 12, 1, 118, NULL, '18-09-2023', NULL),
(2810, 17, 13, 1, 118, NULL, '18-09-2023', NULL),
(2811, 17, 14, 1, 118, NULL, '18-09-2023', NULL),
(2865, 84, 1, 1, 118, NULL, '20-09-2023', NULL),
(2866, 84, 5, 1, 118, NULL, '20-09-2023', NULL),
(2867, 84, 6, 1, 118, NULL, '20-09-2023', NULL),
(2868, 84, 7, 1, 118, NULL, '20-09-2023', NULL),
(2869, 84, 8, 1, 118, NULL, '20-09-2023', NULL),
(2870, 84, 9, 1, 118, NULL, '20-09-2023', NULL),
(2871, 84, 10, 1, 118, NULL, '20-09-2023', NULL),
(2872, 84, 11, 1, 118, NULL, '20-09-2023', NULL),
(2873, 84, 12, 1, 118, NULL, '20-09-2023', NULL),
(2874, 84, 13, 1, 118, NULL, '20-09-2023', NULL),
(2875, 84, 14, 1, 118, NULL, '20-09-2023', NULL),
(2876, 118, 1, 1, 118, NULL, '20-09-2023', NULL),
(2877, 118, 2, 1, 118, NULL, '20-09-2023', NULL),
(2878, 118, 3, 1, 118, NULL, '20-09-2023', NULL),
(2879, 118, 4, 1, 118, NULL, '20-09-2023', NULL),
(2880, 118, 5, 1, 118, NULL, '20-09-2023', NULL),
(2881, 118, 6, 1, 118, NULL, '20-09-2023', NULL),
(2882, 118, 7, 1, 118, NULL, '20-09-2023', NULL),
(2883, 118, 8, 1, 118, NULL, '20-09-2023', NULL),
(2884, 118, 9, 1, 118, NULL, '20-09-2023', NULL),
(2885, 118, 10, 1, 118, NULL, '20-09-2023', NULL),
(2886, 118, 11, 1, 118, NULL, '20-09-2023', NULL),
(2887, 118, 12, 1, 118, NULL, '20-09-2023', NULL),
(2888, 118, 13, 1, 118, NULL, '20-09-2023', NULL),
(2889, 118, 14, 1, 118, NULL, '20-09-2023', NULL),
(2890, 119, 1, 1, 118, NULL, '20-09-2023', NULL),
(2891, 119, 2, 1, 118, NULL, '20-09-2023', NULL),
(2892, 119, 3, 1, 118, NULL, '20-09-2023', NULL),
(2893, 119, 4, 1, 118, NULL, '20-09-2023', NULL),
(2894, 119, 5, 1, 118, NULL, '20-09-2023', NULL),
(2895, 119, 6, 1, 118, NULL, '20-09-2023', NULL),
(2896, 119, 7, 1, 118, NULL, '20-09-2023', NULL),
(2897, 119, 8, 1, 118, NULL, '20-09-2023', NULL),
(2898, 119, 9, 1, 118, NULL, '20-09-2023', NULL),
(2899, 119, 10, 1, 118, NULL, '20-09-2023', NULL),
(2900, 119, 11, 1, 118, NULL, '20-09-2023', NULL),
(2901, 119, 12, 1, 118, NULL, '20-09-2023', NULL),
(2902, 119, 13, 1, 118, NULL, '20-09-2023', NULL),
(2903, 119, 14, 1, 118, NULL, '20-09-2023', NULL),
(2904, 154, 1, 1, 118, NULL, '20-09-2023', NULL),
(2905, 154, 2, 1, 118, NULL, '20-09-2023', NULL),
(2906, 154, 3, 1, 118, NULL, '20-09-2023', NULL),
(2907, 154, 4, 1, 118, NULL, '20-09-2023', NULL),
(2908, 154, 5, 1, 118, NULL, '20-09-2023', NULL),
(2909, 154, 6, 1, 118, NULL, '20-09-2023', NULL),
(2910, 154, 7, 1, 118, NULL, '20-09-2023', NULL),
(2911, 154, 8, 1, 118, NULL, '20-09-2023', NULL),
(2912, 154, 9, 1, 118, NULL, '20-09-2023', NULL),
(2913, 154, 10, 1, 118, NULL, '20-09-2023', NULL),
(2914, 154, 11, 1, 118, NULL, '20-09-2023', NULL),
(2915, 154, 12, 1, 118, NULL, '20-09-2023', NULL),
(2916, 154, 13, 1, 118, NULL, '20-09-2023', NULL),
(2917, 154, 14, 1, 118, NULL, '20-09-2023', NULL),
(2918, 155, 1, 1, 118, NULL, '20-09-2023', NULL),
(2919, 155, 2, 1, 118, NULL, '20-09-2023', NULL),
(2920, 155, 3, 1, 118, NULL, '20-09-2023', NULL),
(2921, 155, 4, 1, 118, NULL, '20-09-2023', NULL),
(2922, 155, 5, 1, 118, NULL, '20-09-2023', NULL),
(2923, 155, 6, 1, 118, NULL, '20-09-2023', NULL),
(2924, 155, 7, 1, 118, NULL, '20-09-2023', NULL),
(2925, 155, 8, 1, 118, NULL, '20-09-2023', NULL),
(2926, 155, 9, 1, 118, NULL, '20-09-2023', NULL),
(2927, 155, 10, 1, 118, NULL, '20-09-2023', NULL),
(2928, 155, 11, 1, 118, NULL, '20-09-2023', NULL),
(2929, 155, 12, 1, 118, NULL, '20-09-2023', NULL),
(2930, 155, 13, 1, 118, NULL, '20-09-2023', NULL),
(2931, 155, 14, 1, 118, NULL, '20-09-2023', NULL),
(2932, 153, 1, 1, 118, NULL, '20-09-2023', NULL),
(2933, 153, 2, 1, 118, NULL, '20-09-2023', NULL),
(2934, 153, 3, 1, 118, NULL, '20-09-2023', NULL),
(2935, 153, 4, 1, 118, NULL, '20-09-2023', NULL),
(2936, 153, 5, 1, 118, NULL, '20-09-2023', NULL),
(2937, 153, 6, 1, 118, NULL, '20-09-2023', NULL),
(2938, 153, 7, 1, 118, NULL, '20-09-2023', NULL),
(2939, 153, 8, 1, 118, NULL, '20-09-2023', NULL),
(2940, 153, 9, 1, 118, NULL, '20-09-2023', NULL),
(2941, 153, 10, 1, 118, NULL, '20-09-2023', NULL),
(2942, 153, 11, 1, 118, NULL, '20-09-2023', NULL),
(2943, 153, 12, 1, 118, NULL, '20-09-2023', NULL),
(2944, 153, 13, 1, 118, NULL, '20-09-2023', NULL),
(2945, 153, 14, 1, 118, NULL, '20-09-2023', NULL),
(2946, 152, 1, 1, 118, NULL, '20-09-2023', NULL),
(2947, 152, 5, 1, 118, NULL, '20-09-2023', NULL),
(2948, 152, 6, 1, 118, NULL, '20-09-2023', NULL),
(2949, 152, 7, 1, 118, NULL, '20-09-2023', NULL),
(2950, 152, 8, 1, 118, NULL, '20-09-2023', NULL),
(2951, 152, 9, 1, 118, NULL, '20-09-2023', NULL),
(2952, 152, 10, 1, 118, NULL, '20-09-2023', NULL),
(2953, 152, 11, 1, 118, NULL, '20-09-2023', NULL),
(2954, 152, 12, 1, 118, NULL, '20-09-2023', NULL),
(2955, 152, 13, 1, 118, NULL, '20-09-2023', NULL),
(2956, 152, 14, 1, 118, NULL, '20-09-2023', NULL),
(2957, 120, 1, 1, 118, NULL, '20-09-2023', NULL),
(2958, 120, 2, 1, 118, NULL, '20-09-2023', NULL),
(2959, 120, 3, 1, 118, NULL, '20-09-2023', NULL),
(2960, 120, 4, 1, 118, NULL, '20-09-2023', NULL),
(2961, 120, 5, 1, 118, NULL, '20-09-2023', NULL),
(2962, 120, 6, 1, 118, NULL, '20-09-2023', NULL),
(2963, 120, 7, 1, 118, NULL, '20-09-2023', NULL),
(2964, 120, 8, 1, 118, NULL, '20-09-2023', NULL),
(2965, 120, 9, 1, 118, NULL, '20-09-2023', NULL),
(2966, 120, 10, 1, 118, NULL, '20-09-2023', NULL),
(2967, 120, 11, 1, 118, NULL, '20-09-2023', NULL),
(2968, 120, 12, 1, 118, NULL, '20-09-2023', NULL),
(2969, 120, 13, 1, 118, NULL, '20-09-2023', NULL),
(2970, 120, 14, 1, 118, NULL, '20-09-2023', NULL),
(2971, 156, 1, 1, 118, NULL, '20-09-2023', NULL),
(2972, 156, 2, 1, 118, NULL, '20-09-2023', NULL),
(2973, 156, 3, 1, 118, NULL, '20-09-2023', NULL),
(2974, 156, 4, 1, 118, NULL, '20-09-2023', NULL),
(2975, 156, 5, 1, 118, NULL, '20-09-2023', NULL),
(2976, 156, 6, 1, 118, NULL, '20-09-2023', NULL),
(2977, 156, 7, 1, 118, NULL, '20-09-2023', NULL),
(2978, 156, 8, 1, 118, NULL, '20-09-2023', NULL),
(2979, 156, 9, 1, 118, NULL, '20-09-2023', NULL),
(2980, 156, 10, 1, 118, NULL, '20-09-2023', NULL),
(2981, 156, 11, 1, 118, NULL, '20-09-2023', NULL),
(2982, 156, 12, 1, 118, NULL, '20-09-2023', NULL),
(2983, 156, 13, 1, 118, NULL, '20-09-2023', NULL),
(2984, 156, 14, 1, 118, NULL, '20-09-2023', NULL),
(2985, 121, 1, 1, 118, NULL, '20-09-2023', NULL),
(2986, 121, 2, 1, 118, NULL, '20-09-2023', NULL),
(2987, 121, 3, 1, 118, NULL, '20-09-2023', NULL),
(2988, 121, 4, 1, 118, NULL, '20-09-2023', NULL),
(2989, 121, 5, 1, 118, NULL, '20-09-2023', NULL),
(2990, 121, 6, 1, 118, NULL, '20-09-2023', NULL),
(2991, 121, 7, 1, 118, NULL, '20-09-2023', NULL),
(2992, 121, 8, 1, 118, NULL, '20-09-2023', NULL),
(2993, 121, 9, 1, 118, NULL, '20-09-2023', NULL),
(2994, 121, 10, 1, 118, NULL, '20-09-2023', NULL),
(2995, 121, 11, 1, 118, NULL, '20-09-2023', NULL),
(2996, 121, 12, 1, 118, NULL, '20-09-2023', NULL),
(2997, 121, 13, 1, 118, NULL, '20-09-2023', NULL),
(2998, 121, 14, 1, 118, NULL, '20-09-2023', NULL),
(2999, 86, 1, 1, 118, NULL, '21-09-2023', NULL),
(3000, 86, 5, 1, 118, NULL, '21-09-2023', NULL),
(3001, 86, 6, 1, 118, NULL, '21-09-2023', NULL),
(3002, 86, 7, 1, 118, NULL, '21-09-2023', NULL),
(3003, 86, 8, 1, 118, NULL, '21-09-2023', NULL),
(3004, 86, 9, 1, 118, NULL, '21-09-2023', NULL),
(3005, 86, 10, 1, 118, NULL, '21-09-2023', NULL),
(3006, 86, 11, 1, 118, NULL, '21-09-2023', NULL),
(3007, 86, 12, 1, 118, NULL, '21-09-2023', NULL),
(3008, 86, 13, 1, 118, NULL, '21-09-2023', NULL),
(3009, 86, 14, 1, 118, NULL, '21-09-2023', NULL),
(3010, 88, 1, 1, 118, NULL, '21-09-2023', NULL),
(3011, 88, 5, 1, 118, NULL, '21-09-2023', NULL),
(3012, 88, 6, 1, 118, NULL, '21-09-2023', NULL),
(3013, 88, 7, 1, 118, NULL, '21-09-2023', NULL),
(3014, 88, 8, 1, 118, NULL, '21-09-2023', NULL),
(3015, 88, 9, 1, 118, NULL, '21-09-2023', NULL),
(3016, 88, 10, 1, 118, NULL, '21-09-2023', NULL),
(3017, 88, 11, 1, 118, NULL, '21-09-2023', NULL),
(3018, 88, 12, 1, 118, NULL, '21-09-2023', NULL),
(3019, 88, 13, 1, 118, NULL, '21-09-2023', NULL),
(3020, 88, 14, 1, 118, NULL, '21-09-2023', NULL),
(3021, 89, 1, 1, 118, NULL, '21-09-2023', NULL),
(3022, 89, 5, 1, 118, NULL, '21-09-2023', NULL),
(3023, 89, 6, 1, 118, NULL, '21-09-2023', NULL),
(3024, 89, 7, 1, 118, NULL, '21-09-2023', NULL),
(3025, 89, 8, 1, 118, NULL, '21-09-2023', NULL),
(3026, 89, 9, 1, 118, NULL, '21-09-2023', NULL),
(3027, 89, 10, 1, 118, NULL, '21-09-2023', NULL),
(3028, 89, 11, 1, 118, NULL, '21-09-2023', NULL),
(3029, 89, 12, 1, 118, NULL, '21-09-2023', NULL),
(3030, 89, 13, 1, 118, NULL, '21-09-2023', NULL),
(3031, 89, 14, 1, 118, NULL, '21-09-2023', NULL),
(3032, 90, 1, 1, 118, NULL, '21-09-2023', NULL),
(3033, 90, 5, 1, 118, NULL, '21-09-2023', NULL),
(3034, 90, 6, 1, 118, NULL, '21-09-2023', NULL),
(3035, 90, 7, 1, 118, NULL, '21-09-2023', NULL),
(3036, 90, 8, 1, 118, NULL, '21-09-2023', NULL),
(3037, 90, 9, 1, 118, NULL, '21-09-2023', NULL),
(3038, 90, 10, 1, 118, NULL, '21-09-2023', NULL),
(3039, 90, 11, 1, 118, NULL, '21-09-2023', NULL),
(3040, 90, 12, 1, 118, NULL, '21-09-2023', NULL),
(3041, 90, 13, 1, 118, NULL, '21-09-2023', NULL),
(3042, 90, 14, 1, 118, NULL, '21-09-2023', NULL),
(3043, 157, 1, 1, 118, NULL, '21-09-2023', NULL),
(3044, 157, 5, 1, 118, NULL, '21-09-2023', NULL),
(3045, 157, 6, 1, 118, NULL, '21-09-2023', NULL),
(3046, 157, 7, 1, 118, NULL, '21-09-2023', NULL),
(3047, 157, 8, 1, 118, NULL, '21-09-2023', NULL),
(3048, 157, 9, 1, 118, NULL, '21-09-2023', NULL),
(3049, 125, 1, 1, 1, NULL, '22-09-2023', NULL),
(3050, 125, 5, 1, 1, NULL, '22-09-2023', NULL),
(3051, 125, 6, 1, 1, NULL, '22-09-2023', NULL),
(3052, 125, 7, 1, 1, NULL, '22-09-2023', NULL),
(3053, 125, 8, 1, 1, NULL, '22-09-2023', NULL),
(3054, 125, 9, 1, 1, NULL, '22-09-2023', NULL),
(3055, 125, 10, 1, 1, NULL, '22-09-2023', NULL),
(3056, 125, 11, 1, 1, NULL, '22-09-2023', NULL),
(3057, 125, 12, 1, 1, NULL, '22-09-2023', NULL),
(3058, 125, 13, 1, 1, NULL, '22-09-2023', NULL),
(3059, 125, 14, 1, 1, NULL, '22-09-2023', NULL),
(3096, 39, 1, 1, 1, NULL, '22-09-2023', NULL),
(3097, 39, 5, 1, 1, NULL, '22-09-2023', NULL),
(3098, 39, 6, 1, 1, NULL, '22-09-2023', NULL),
(3099, 39, 7, 1, 1, NULL, '22-09-2023', NULL),
(3100, 39, 8, 1, 1, NULL, '22-09-2023', NULL),
(3101, 39, 9, 1, 1, NULL, '22-09-2023', NULL),
(3102, 39, 10, 1, 1, NULL, '22-09-2023', NULL),
(3103, 39, 11, 1, 1, NULL, '22-09-2023', NULL),
(3104, 39, 12, 1, 1, NULL, '22-09-2023', NULL),
(3105, 39, 13, 1, 1, NULL, '22-09-2023', NULL),
(3106, 39, 14, 1, 1, NULL, '22-09-2023', NULL),
(3107, 159, 1, 1, 118, NULL, '22-09-2023', NULL),
(3108, 159, 2, 1, 118, NULL, '22-09-2023', NULL),
(3109, 159, 3, 1, 118, NULL, '22-09-2023', NULL),
(3110, 159, 4, 1, 118, NULL, '22-09-2023', NULL),
(3111, 159, 5, 1, 118, NULL, '22-09-2023', NULL),
(3112, 159, 6, 1, 118, NULL, '22-09-2023', NULL),
(3113, 159, 7, 1, 118, NULL, '22-09-2023', NULL),
(3114, 159, 8, 1, 118, NULL, '22-09-2023', NULL),
(3115, 159, 9, 1, 118, NULL, '22-09-2023', NULL),
(3116, 159, 10, 1, 118, NULL, '22-09-2023', NULL),
(3117, 159, 11, 1, 118, NULL, '22-09-2023', NULL),
(3118, 159, 12, 1, 118, NULL, '22-09-2023', NULL),
(3119, 159, 13, 1, 118, NULL, '22-09-2023', NULL),
(3120, 159, 14, 1, 118, NULL, '22-09-2023', NULL),
(3121, 29, 1, 1, 1, NULL, '29-09-2023', NULL),
(3122, 29, 5, 1, 1, NULL, '29-09-2023', NULL),
(3123, 29, 6, 1, 1, NULL, '29-09-2023', NULL),
(3124, 29, 7, 1, 1, NULL, '29-09-2023', NULL),
(3125, 29, 8, 1, 1, NULL, '29-09-2023', NULL),
(3126, 29, 9, 1, 1, NULL, '29-09-2023', NULL),
(3127, 29, 10, 1, 1, NULL, '29-09-2023', NULL),
(3128, 29, 11, 1, 1, NULL, '29-09-2023', NULL),
(3129, 29, 12, 1, 1, NULL, '29-09-2023', NULL),
(3130, 29, 13, 1, 1, NULL, '29-09-2023', NULL),
(3131, 29, 14, 1, 1, NULL, '29-09-2023', NULL),
(3132, 26, 1, 1, 1, NULL, '29-09-2023', NULL),
(3133, 26, 2, 1, 1, NULL, '29-09-2023', NULL),
(3134, 26, 3, 1, 1, NULL, '29-09-2023', NULL),
(3135, 26, 4, 1, 1, NULL, '29-09-2023', NULL),
(3136, 26, 5, 1, 1, NULL, '29-09-2023', NULL),
(3137, 26, 6, 1, 1, NULL, '29-09-2023', NULL),
(3138, 26, 7, 1, 1, NULL, '29-09-2023', NULL),
(3139, 26, 8, 1, 1, NULL, '29-09-2023', NULL),
(3140, 26, 9, 1, 1, NULL, '29-09-2023', NULL),
(3141, 26, 10, 1, 1, NULL, '29-09-2023', NULL),
(3142, 26, 11, 1, 1, NULL, '29-09-2023', NULL),
(3143, 26, 12, 1, 1, NULL, '29-09-2023', NULL),
(3144, 26, 13, 1, 1, NULL, '29-09-2023', NULL),
(3145, 26, 14, 1, 1, NULL, '29-09-2023', NULL),
(3146, 28, 1, 1, 1, NULL, '30-09-2023', NULL),
(3147, 28, 5, 1, 1, NULL, '30-09-2023', NULL),
(3148, 28, 6, 1, 1, NULL, '30-09-2023', NULL),
(3149, 28, 7, 1, 1, NULL, '30-09-2023', NULL),
(3150, 28, 8, 1, 1, NULL, '30-09-2023', NULL),
(3151, 28, 9, 1, 1, NULL, '30-09-2023', NULL),
(3152, 28, 10, 1, 1, NULL, '30-09-2023', NULL),
(3153, 28, 11, 1, 1, NULL, '30-09-2023', NULL),
(3154, 28, 12, 1, 1, NULL, '30-09-2023', NULL),
(3155, 28, 13, 1, 1, NULL, '30-09-2023', NULL),
(3156, 28, 14, 1, 1, NULL, '30-09-2023', NULL),
(3157, 160, 1, 1, 1, NULL, '02-10-2023', NULL),
(3158, 160, 5, 1, 1, NULL, '02-10-2023', NULL),
(3159, 160, 6, 1, 1, NULL, '02-10-2023', NULL),
(3160, 160, 7, 1, 1, NULL, '02-10-2023', NULL),
(3161, 160, 8, 1, 1, NULL, '02-10-2023', NULL),
(3162, 160, 9, 1, 1, NULL, '02-10-2023', NULL),
(3163, 160, 10, 1, 1, NULL, '02-10-2023', NULL),
(3164, 160, 11, 1, 1, NULL, '02-10-2023', NULL),
(3165, 160, 12, 1, 1, NULL, '02-10-2023', NULL),
(3166, 160, 13, 1, 1, NULL, '02-10-2023', NULL),
(3167, 160, 14, 1, 1, NULL, '02-10-2023', NULL),
(3179, 161, 1, 1, 1, NULL, '02-10-2023', NULL),
(3180, 161, 5, 1, 1, NULL, '02-10-2023', NULL),
(3181, 161, 6, 1, 1, NULL, '02-10-2023', NULL),
(3182, 161, 7, 1, 1, NULL, '02-10-2023', NULL),
(3183, 161, 8, 1, 1, NULL, '02-10-2023', NULL),
(3184, 161, 9, 1, 1, NULL, '02-10-2023', NULL),
(3185, 161, 10, 1, 1, NULL, '02-10-2023', NULL),
(3186, 161, 11, 1, 1, NULL, '02-10-2023', NULL),
(3187, 161, 12, 1, 1, NULL, '02-10-2023', NULL),
(3188, 161, 13, 1, 1, NULL, '02-10-2023', NULL),
(3189, 161, 14, 1, 1, NULL, '02-10-2023', NULL),
(3204, 19, 1, 1, 1, NULL, '02-10-2023', NULL),
(3205, 19, 2, 1, 1, NULL, '02-10-2023', NULL),
(3206, 19, 3, 1, 1, NULL, '02-10-2023', NULL),
(3207, 19, 4, 1, 1, NULL, '02-10-2023', NULL),
(3208, 19, 5, 1, 1, NULL, '02-10-2023', NULL),
(3209, 19, 6, 1, 1, NULL, '02-10-2023', NULL),
(3210, 19, 7, 1, 1, NULL, '02-10-2023', NULL),
(3211, 19, 8, 1, 1, NULL, '02-10-2023', NULL),
(3212, 19, 9, 1, 1, NULL, '02-10-2023', NULL),
(3213, 19, 10, 1, 1, NULL, '02-10-2023', NULL),
(3214, 19, 11, 1, 1, NULL, '02-10-2023', NULL),
(3215, 19, 12, 1, 1, NULL, '02-10-2023', NULL),
(3216, 19, 13, 1, 1, NULL, '02-10-2023', NULL),
(3217, 19, 14, 1, 1, NULL, '02-10-2023', NULL),
(3218, 162, 1, 1, 118, NULL, '03-10-2023', NULL),
(3219, 162, 5, 1, 118, NULL, '03-10-2023', NULL),
(3220, 162, 6, 1, 118, NULL, '03-10-2023', NULL),
(3221, 162, 7, 1, 118, NULL, '03-10-2023', NULL),
(3222, 162, 8, 1, 118, NULL, '03-10-2023', NULL),
(3223, 162, 9, 1, 118, NULL, '03-10-2023', NULL),
(3224, 162, 10, 1, 118, NULL, '03-10-2023', NULL),
(3225, 162, 11, 1, 118, NULL, '03-10-2023', NULL),
(3226, 162, 12, 1, 118, NULL, '03-10-2023', NULL),
(3227, 162, 13, 1, 118, NULL, '03-10-2023', NULL),
(3228, 162, 14, 1, 118, NULL, '03-10-2023', NULL),
(3229, 163, 1, 1, 118, NULL, '03-10-2023', NULL),
(3230, 163, 5, 1, 118, NULL, '03-10-2023', NULL),
(3231, 163, 6, 1, 118, NULL, '03-10-2023', NULL),
(3232, 163, 7, 1, 118, NULL, '03-10-2023', NULL),
(3233, 163, 8, 1, 118, NULL, '03-10-2023', NULL),
(3234, 163, 9, 1, 118, NULL, '03-10-2023', NULL),
(3235, 163, 10, 1, 118, NULL, '03-10-2023', NULL),
(3236, 163, 11, 1, 118, NULL, '03-10-2023', NULL),
(3237, 163, 12, 1, 118, NULL, '03-10-2023', NULL),
(3238, 163, 13, 1, 118, NULL, '03-10-2023', NULL),
(3239, 163, 14, 1, 118, NULL, '03-10-2023', NULL),
(3251, 146, 1, 1, 118, NULL, '04-10-2023', NULL),
(3252, 146, 5, 1, 118, NULL, '04-10-2023', NULL),
(3253, 146, 6, 1, 118, NULL, '04-10-2023', NULL),
(3254, 146, 7, 1, 118, NULL, '04-10-2023', NULL),
(3255, 146, 8, 1, 118, NULL, '04-10-2023', NULL),
(3256, 146, 9, 1, 118, NULL, '04-10-2023', NULL),
(3257, 146, 10, 1, 118, NULL, '04-10-2023', NULL),
(3258, 146, 11, 1, 118, NULL, '04-10-2023', NULL),
(3259, 146, 12, 1, 118, NULL, '04-10-2023', NULL),
(3260, 146, 13, 1, 118, NULL, '04-10-2023', NULL),
(3261, 146, 14, 1, 118, NULL, '04-10-2023', NULL),
(3262, 164, 1, 1, 118, NULL, '04-10-2023', NULL),
(3263, 164, 5, 1, 118, NULL, '04-10-2023', NULL),
(3264, 164, 6, 1, 118, NULL, '04-10-2023', NULL),
(3265, 164, 7, 1, 118, NULL, '04-10-2023', NULL),
(3266, 164, 8, 1, 118, NULL, '04-10-2023', NULL),
(3267, 164, 9, 1, 118, NULL, '04-10-2023', NULL),
(3268, 164, 10, 1, 118, NULL, '04-10-2023', NULL),
(3269, 164, 11, 1, 118, NULL, '04-10-2023', NULL),
(3270, 164, 12, 1, 118, NULL, '04-10-2023', NULL),
(3271, 164, 13, 1, 118, NULL, '04-10-2023', NULL),
(3272, 164, 14, 1, 118, NULL, '04-10-2023', NULL),
(3379, 158, 1, 1, 118, NULL, '10-10-2023', NULL),
(3380, 158, 2, 1, 118, NULL, '10-10-2023', NULL),
(3381, 158, 3, 1, 118, NULL, '10-10-2023', NULL),
(3382, 158, 4, 1, 118, NULL, '10-10-2023', NULL),
(3383, 158, 5, 1, 118, NULL, '10-10-2023', NULL),
(3384, 158, 6, 1, 118, NULL, '10-10-2023', NULL),
(3385, 158, 7, 1, 118, NULL, '10-10-2023', NULL),
(3386, 158, 8, 1, 118, NULL, '10-10-2023', NULL),
(3387, 158, 9, 1, 118, NULL, '10-10-2023', NULL),
(3388, 158, 10, 1, 118, NULL, '10-10-2023', NULL),
(3389, 158, 11, 1, 118, NULL, '10-10-2023', NULL),
(3390, 158, 12, 1, 118, NULL, '10-10-2023', NULL),
(3391, 158, 13, 1, 118, NULL, '10-10-2023', NULL),
(3392, 158, 14, 1, 118, NULL, '10-10-2023', NULL),
(3404, 166, 1, 1, 118, NULL, '10-10-2023', NULL),
(3405, 166, 5, 1, 118, NULL, '10-10-2023', NULL),
(3406, 166, 6, 1, 118, NULL, '10-10-2023', NULL),
(3407, 166, 7, 1, 118, NULL, '10-10-2023', NULL),
(3408, 166, 8, 1, 118, NULL, '10-10-2023', NULL),
(3409, 166, 9, 1, 118, NULL, '10-10-2023', NULL),
(3410, 166, 10, 1, 118, NULL, '10-10-2023', NULL),
(3411, 166, 11, 1, 118, NULL, '10-10-2023', NULL),
(3412, 166, 12, 1, 118, NULL, '10-10-2023', NULL),
(3413, 166, 13, 1, 118, NULL, '10-10-2023', NULL),
(3414, 166, 14, 1, 118, NULL, '10-10-2023', NULL),
(3448, 32, 1, 1, 1, NULL, '11-10-2023', NULL),
(3449, 32, 5, 1, 1, NULL, '11-10-2023', NULL),
(3450, 32, 6, 1, 1, NULL, '11-10-2023', NULL),
(3451, 32, 7, 1, 1, NULL, '11-10-2023', NULL),
(3452, 32, 8, 1, 1, NULL, '11-10-2023', NULL),
(3453, 32, 9, 1, 1, NULL, '11-10-2023', NULL),
(3454, 32, 10, 1, 1, NULL, '11-10-2023', NULL),
(3455, 32, 11, 1, 1, NULL, '11-10-2023', NULL),
(3456, 32, 12, 1, 1, NULL, '11-10-2023', NULL),
(3457, 32, 13, 1, 1, NULL, '11-10-2023', NULL),
(3458, 32, 14, 1, 1, NULL, '11-10-2023', NULL),
(3459, 167, 1, 1, 1, NULL, '12-10-2023', NULL),
(3460, 167, 6, 1, 1, NULL, '12-10-2023', NULL),
(3461, 167, 7, 1, 1, NULL, '12-10-2023', NULL),
(3462, 167, 8, 1, 1, NULL, '12-10-2023', NULL),
(3463, 167, 9, 1, 1, NULL, '12-10-2023', NULL),
(3464, 167, 10, 1, 1, NULL, '12-10-2023', NULL),
(3465, 167, 11, 1, 1, NULL, '12-10-2023', NULL),
(3466, 167, 12, 1, 1, NULL, '12-10-2023', NULL),
(3467, 167, 13, 1, 1, NULL, '12-10-2023', NULL),
(3468, 167, 14, 1, 1, NULL, '12-10-2023', NULL),
(3469, 165, 1, 1, 118, NULL, '14-10-2023', NULL),
(3470, 165, 5, 1, 118, NULL, '14-10-2023', NULL),
(3471, 165, 6, 1, 118, NULL, '14-10-2023', NULL),
(3472, 165, 7, 1, 118, NULL, '14-10-2023', NULL),
(3473, 165, 8, 1, 118, NULL, '14-10-2023', NULL),
(3474, 165, 9, 1, 118, NULL, '14-10-2023', NULL),
(3475, 165, 10, 1, 118, NULL, '14-10-2023', NULL),
(3476, 165, 11, 1, 118, NULL, '14-10-2023', NULL),
(3477, 165, 12, 1, 118, NULL, '14-10-2023', NULL),
(3478, 165, 13, 1, 118, NULL, '14-10-2023', NULL),
(3479, 165, 14, 1, 118, NULL, '14-10-2023', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_page_permissions`
--

CREATE TABLE `tbl_page_permissions` (
  `id` int NOT NULL,
  `page_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `status` int DEFAULT '1',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_page_permissions`
--

INSERT INTO `tbl_page_permissions` (`id`, `page_id`, `permission_id`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 1, NULL, '29-03-2023', NULL),
(2, 2, 2, 1, 1, NULL, '29-03-2023', NULL),
(4, 4, 2, 1, 1, NULL, '29-03-2023', NULL),
(5, 5, 2, 1, 1, NULL, '29-03-2023', NULL),
(6, 6, 2, 1, 1, NULL, '29-03-2023', NULL),
(7, 7, 2, 1, 1, NULL, '29-03-2023', NULL),
(8, 8, 2, 1, 1, NULL, '29-03-2023', NULL),
(9, 9, 2, 1, 1, NULL, '29-03-2023', NULL),
(10, 10, 2, 1, 1, NULL, '29-03-2023', NULL),
(11, 11, 2, 1, 1, NULL, '29-03-2023', NULL),
(12, 12, 2, 1, 1, NULL, '29-03-2023', NULL),
(14, 14, 2, 1, 1, NULL, '29-03-2023', NULL),
(26, 21, 1, 1, 1, NULL, '29-03-2023', NULL),
(27, 21, 2, 1, 1, NULL, '29-03-2023', NULL),
(28, 21, 3, 1, 1, NULL, '29-03-2023', NULL),
(29, 21, 4, 1, 1, NULL, '29-03-2023', NULL),
(30, 21, 5, 1, 1, NULL, '29-03-2023', NULL),
(31, 21, 8, 1, 1, NULL, '29-03-2023', NULL),
(82, 31, 1, 1, 1, NULL, '29-03-2023', NULL),
(83, 31, 2, 1, 1, NULL, '29-03-2023', NULL),
(84, 31, 3, 1, 1, NULL, '29-03-2023', NULL),
(85, 31, 4, 1, 1, NULL, '29-03-2023', NULL),
(89, 33, 1, 1, 1, NULL, '29-03-2023', NULL),
(90, 33, 2, 1, 1, NULL, '29-03-2023', NULL),
(91, 33, 3, 1, 1, NULL, '29-03-2023', NULL),
(92, 34, 1, 1, 1, NULL, '29-03-2023', NULL),
(93, 34, 2, 1, 1, NULL, '29-03-2023', NULL),
(94, 34, 3, 1, 1, NULL, '29-03-2023', NULL),
(95, 35, 1, 1, 1, NULL, '29-03-2023', NULL),
(96, 35, 2, 1, 1, NULL, '29-03-2023', NULL),
(97, 35, 3, 1, 1, NULL, '29-03-2023', NULL),
(98, 35, 4, 1, 1, NULL, '29-03-2023', NULL),
(99, 36, 1, 1, 1, NULL, '29-03-2023', NULL),
(100, 36, 2, 1, 1, NULL, '29-03-2023', NULL),
(101, 36, 3, 1, 1, NULL, '29-03-2023', NULL),
(102, 36, 4, 1, 1, NULL, '29-03-2023', NULL),
(103, 37, 2, 1, 1, NULL, '29-03-2023', NULL),
(113, 40, 1, 1, 1, NULL, '29-03-2023', NULL),
(114, 40, 2, 1, 1, NULL, '29-03-2023', NULL),
(115, 40, 3, 1, 1, NULL, '29-03-2023', NULL),
(116, 40, 4, 1, 1, NULL, '29-03-2023', NULL),
(117, 41, 1, 1, 1, NULL, '29-03-2023', NULL),
(118, 41, 2, 1, 1, NULL, '29-03-2023', NULL),
(119, 41, 3, 1, 1, NULL, '29-03-2023', NULL),
(123, 43, 1, 1, 1, NULL, '29-03-2023', NULL),
(124, 43, 2, 1, 1, NULL, '29-03-2023', NULL),
(125, 43, 3, 1, 1, NULL, '29-03-2023', NULL),
(126, 43, 4, 1, 1, NULL, '29-03-2023', NULL),
(127, 44, 1, 1, 1, NULL, '29-03-2023', NULL),
(128, 44, 2, 1, 1, NULL, '29-03-2023', NULL),
(129, 44, 3, 1, 1, NULL, '29-03-2023', NULL),
(130, 44, 4, 1, 1, NULL, '29-03-2023', NULL),
(133, 45, 2, 1, 1, NULL, '29-03-2023', NULL),
(134, 45, 3, 1, 1, NULL, '29-03-2023', NULL),
(135, 46, 1, 1, 1, NULL, '29-03-2023', NULL),
(136, 46, 2, 1, 1, NULL, '29-03-2023', NULL),
(137, 46, 3, 1, 1, NULL, '29-03-2023', NULL),
(138, 46, 4, 1, 1, NULL, '29-03-2023', NULL),
(143, 48, 1, 1, 1, NULL, '29-03-2023', NULL),
(144, 48, 2, 1, 1, NULL, '29-03-2023', NULL),
(145, 48, 3, 1, 1, NULL, '29-03-2023', NULL),
(146, 48, 4, 1, 1, NULL, '29-03-2023', NULL),
(147, 49, 1, 1, 1, NULL, '29-03-2023', NULL),
(148, 49, 2, 1, 1, NULL, '29-03-2023', NULL),
(149, 49, 3, 1, 1, NULL, '29-03-2023', NULL),
(153, 50, 1, 1, 1, NULL, '29-03-2023', NULL),
(154, 50, 2, 1, 1, NULL, '29-03-2023', NULL),
(155, 50, 3, 1, 1, NULL, '29-03-2023', NULL),
(156, 51, 2, 1, 1, NULL, '29-03-2023', NULL),
(174, 56, 1, 1, 1, NULL, '29-03-2023', NULL),
(175, 56, 2, 1, 1, NULL, '29-03-2023', NULL),
(176, 56, 3, 1, 1, NULL, '29-03-2023', NULL),
(177, 56, 4, 1, 1, NULL, '29-03-2023', NULL),
(190, 59, 1, 1, 1, NULL, '29-03-2023', NULL),
(191, 59, 2, 1, 1, NULL, '29-03-2023', NULL),
(192, 59, 3, 1, 1, NULL, '29-03-2023', NULL),
(193, 59, 4, 1, 1, NULL, '29-03-2023', NULL),
(194, 60, 1, 1, 1, NULL, '29-03-2023', NULL),
(195, 60, 2, 1, 1, NULL, '29-03-2023', NULL),
(196, 60, 3, 1, 1, NULL, '29-03-2023', NULL),
(197, 60, 4, 1, 1, NULL, '29-03-2023', NULL),
(218, 55, 1, 1, 1, NULL, '03-04-2023', NULL),
(219, 55, 2, 1, 1, NULL, '03-04-2023', NULL),
(220, 55, 3, 1, 1, NULL, '03-04-2023', NULL),
(233, 58, 1, 1, 1, NULL, '08-04-2023', NULL),
(234, 58, 2, 1, 1, NULL, '08-04-2023', NULL),
(235, 58, 3, 1, 1, NULL, '08-04-2023', NULL),
(236, 58, 4, 1, 1, NULL, '08-04-2023', NULL),
(237, 64, 1, 1, 1, NULL, '08-04-2023', NULL),
(238, 64, 2, 1, 1, NULL, '08-04-2023', NULL),
(239, 65, 1, 1, 1, NULL, '08-04-2023', NULL),
(240, 65, 2, 1, 1, NULL, '08-04-2023', NULL),
(242, 67, 2, 1, 1, NULL, '08-04-2023', NULL),
(243, 68, 2, 1, 1, NULL, '08-04-2023', NULL),
(244, 69, 2, 1, 1, NULL, '08-04-2023', NULL),
(245, 70, 2, 1, 1, NULL, '08-04-2023', NULL),
(246, 71, 2, 1, 1, NULL, '08-04-2023', NULL),
(247, 72, 1, 1, 1, NULL, '08-04-2023', NULL),
(248, 72, 2, 1, 1, NULL, '08-04-2023', NULL),
(249, 72, 3, 1, 1, NULL, '08-04-2023', NULL),
(250, 72, 4, 1, 1, NULL, '08-04-2023', NULL),
(251, 73, 1, 1, 1, NULL, '08-04-2023', NULL),
(252, 73, 2, 1, 1, NULL, '08-04-2023', NULL),
(253, 73, 3, 1, 1, NULL, '08-04-2023', NULL),
(254, 73, 4, 1, 1, NULL, '08-04-2023', NULL),
(255, 47, 1, 1, 1, NULL, '08-04-2023', NULL),
(256, 47, 2, 1, 1, NULL, '08-04-2023', NULL),
(257, 47, 3, 1, 1, NULL, '08-04-2023', NULL),
(258, 47, 4, 1, 1, NULL, '08-04-2023', NULL),
(259, 74, 2, 1, 1, NULL, '08-04-2023', NULL),
(260, 75, 2, 1, 1, NULL, '08-04-2023', NULL),
(261, 76, 1, 1, 1, NULL, '08-04-2023', NULL),
(262, 76, 2, 1, 1, NULL, '08-04-2023', NULL),
(263, 76, 3, 1, 1, NULL, '08-04-2023', NULL),
(264, 76, 4, 1, 1, NULL, '08-04-2023', NULL),
(265, 77, 2, 1, 1, NULL, '08-04-2023', NULL),
(266, 77, 3, 1, 1, NULL, '08-04-2023', NULL),
(267, 78, 2, 1, 1, NULL, '08-04-2023', NULL),
(268, 78, 3, 1, 1, NULL, '08-04-2023', NULL),
(269, 79, 1, 1, 1, NULL, '08-04-2023', NULL),
(270, 79, 2, 1, 1, NULL, '08-04-2023', NULL),
(271, 79, 3, 1, 1, NULL, '08-04-2023', NULL),
(272, 80, 2, 1, 1, NULL, '08-04-2023', NULL),
(273, 81, 2, 1, 1, NULL, '08-04-2023', NULL),
(274, 82, 1, 1, 1, NULL, '08-04-2023', NULL),
(275, 82, 2, 1, 1, NULL, '08-04-2023', NULL),
(276, 82, 3, 1, 1, NULL, '08-04-2023', NULL),
(277, 82, 4, 1, 1, NULL, '08-04-2023', NULL),
(283, 85, 2, 1, 1, NULL, '08-04-2023', NULL),
(285, 87, 2, 1, 1, NULL, '08-04-2023', NULL),
(289, 91, 2, 1, 1, NULL, '08-04-2023', NULL),
(290, 92, 2, 1, 1, NULL, '08-04-2023', NULL),
(291, 93, 2, 1, 1, NULL, '08-04-2023', NULL),
(292, 94, 1, 1, 1, NULL, '08-04-2023', NULL),
(293, 94, 2, 1, 1, NULL, '08-04-2023', NULL),
(294, 94, 3, 1, 1, NULL, '08-04-2023', NULL),
(295, 95, 2, 1, 1, NULL, '08-04-2023', NULL),
(296, 96, 2, 1, 1, NULL, '08-04-2023', NULL),
(297, 97, 2, 1, 1, NULL, '08-04-2023', NULL),
(299, 98, 2, 1, 1, NULL, '09-04-2023', NULL),
(300, 99, 1, 1, 1, NULL, '09-04-2023', NULL),
(301, 99, 2, 1, 1, NULL, '09-04-2023', NULL),
(302, 99, 3, 1, 1, NULL, '09-04-2023', NULL),
(303, 99, 4, 1, 1, NULL, '09-04-2023', NULL),
(304, 100, 1, 1, 1, NULL, '09-04-2023', NULL),
(305, 100, 2, 1, 1, NULL, '09-04-2023', NULL),
(306, 100, 3, 1, 1, NULL, '09-04-2023', NULL),
(307, 100, 4, 1, 1, NULL, '09-04-2023', NULL),
(308, 101, 1, 1, 1, NULL, '09-04-2023', NULL),
(309, 101, 2, 1, 1, NULL, '09-04-2023', NULL),
(310, 101, 3, 1, 1, NULL, '09-04-2023', NULL),
(311, 101, 4, 1, 1, NULL, '09-04-2023', NULL),
(316, 103, 1, 1, 1, NULL, '09-04-2023', NULL),
(317, 103, 2, 1, 1, NULL, '09-04-2023', NULL),
(318, 103, 3, 1, 1, NULL, '09-04-2023', NULL),
(319, 103, 4, 1, 1, NULL, '09-04-2023', NULL),
(320, 61, 2, 1, 1, NULL, '09-04-2023', NULL),
(321, 62, 2, 1, 1, NULL, '09-04-2023', NULL),
(322, 104, 2, 1, 1, NULL, '10-04-2023', NULL),
(323, 105, 1, 1, 1, NULL, '10-04-2023', NULL),
(324, 105, 2, 1, 1, NULL, '10-04-2023', NULL),
(325, 105, 3, 1, 1, NULL, '10-04-2023', NULL),
(326, 105, 4, 1, 1, NULL, '10-04-2023', NULL),
(327, 106, 1, 1, 1, NULL, '10-04-2023', NULL),
(328, 106, 2, 1, 1, NULL, '10-04-2023', NULL),
(329, 107, 2, 1, 1, NULL, '10-04-2023', NULL),
(330, 108, 2, 1, 1, NULL, '10-04-2023', NULL),
(331, 109, 2, 1, 1, NULL, '10-04-2023', NULL),
(332, 110, 2, 1, 1, NULL, '10-04-2023', NULL),
(333, 111, 2, 1, 1, NULL, '10-04-2023', NULL),
(334, 112, 2, 1, 1, NULL, '11-04-2023', NULL),
(335, 113, 2, 1, 1, NULL, '11-04-2023', NULL),
(336, 114, 2, 1, 1, NULL, '11-04-2023', NULL),
(345, 122, 2, 1, 1, NULL, '12-04-2023', NULL),
(360, 123, 2, 1, 1, NULL, '13-04-2023', NULL),
(361, 124, 2, 1, 1, NULL, '13-04-2023', NULL),
(362, 102, 1, 1, 1, NULL, '13-04-2023', NULL),
(363, 102, 2, 1, 1, NULL, '13-04-2023', NULL),
(364, 102, 3, 1, 1, NULL, '13-04-2023', NULL),
(365, 102, 4, 1, 1, NULL, '13-04-2023', NULL),
(366, 102, 5, 1, 1, NULL, '13-04-2023', NULL),
(367, 102, 6, 1, 1, NULL, '13-04-2023', NULL),
(368, 102, 7, 1, 1, NULL, '13-04-2023', NULL),
(376, 22, 1, 1, 1, NULL, '14-04-2023', NULL),
(377, 22, 2, 1, 1, NULL, '14-04-2023', NULL),
(378, 22, 3, 1, 1, NULL, '14-04-2023', NULL),
(379, 22, 4, 1, 1, NULL, '14-04-2023', NULL),
(380, 22, 7, 1, 1, NULL, '14-04-2023', NULL),
(381, 22, 11, 1, 1, NULL, '14-04-2023', NULL),
(382, 22, 12, 1, 1, NULL, '14-04-2023', NULL),
(388, 20, 2, 1, 1, NULL, '14-04-2023', NULL),
(389, 20, 6, 1, 1, NULL, '14-04-2023', NULL),
(390, 20, 7, 1, 1, NULL, '14-04-2023', NULL),
(391, 20, 11, 1, 1, NULL, '14-04-2023', NULL),
(392, 20, 12, 1, 1, NULL, '14-04-2023', NULL),
(393, 20, 13, 1, 1, NULL, '14-04-2023', NULL),
(394, 20, 14, 1, 1, NULL, '14-04-2023', NULL),
(441, 126, 2, 1, 1, NULL, '25-04-2023', NULL),
(468, 128, 2, 1, 1, NULL, '26-04-2023', NULL),
(469, 127, 2, 1, 1, NULL, '26-04-2023', NULL),
(484, 129, 1, 1, 1, NULL, '27-04-2023', NULL),
(485, 129, 2, 1, 1, NULL, '27-04-2023', NULL),
(486, 129, 3, 1, 1, NULL, '27-04-2023', NULL),
(487, 130, 1, 1, 1, NULL, '27-04-2023', NULL),
(488, 130, 2, 1, 1, NULL, '27-04-2023', NULL),
(489, 130, 3, 1, 1, NULL, '27-04-2023', NULL),
(490, 130, 4, 1, 1, NULL, '27-04-2023', NULL),
(491, 131, 1, 1, 1, NULL, '27-04-2023', NULL),
(492, 131, 2, 1, 1, NULL, '27-04-2023', NULL),
(493, 131, 3, 1, 1, NULL, '27-04-2023', NULL),
(494, 131, 4, 1, 1, NULL, '27-04-2023', NULL),
(495, 132, 1, 1, 1, NULL, '27-04-2023', NULL),
(496, 132, 2, 1, 1, NULL, '27-04-2023', NULL),
(497, 132, 3, 1, 1, NULL, '27-04-2023', NULL),
(498, 132, 4, 1, 1, NULL, '27-04-2023', NULL),
(499, 133, 1, 1, 1, NULL, '27-04-2023', NULL),
(500, 133, 2, 1, 1, NULL, '27-04-2023', NULL),
(501, 133, 3, 1, 1, NULL, '27-04-2023', NULL),
(502, 133, 4, 1, 1, NULL, '27-04-2023', NULL),
(503, 134, 1, 1, 1, NULL, '27-04-2023', NULL),
(504, 134, 2, 1, 1, NULL, '27-04-2023', NULL),
(505, 134, 3, 1, 1, NULL, '27-04-2023', NULL),
(506, 135, 1, 1, 1, NULL, '27-04-2023', NULL),
(507, 135, 2, 1, 1, NULL, '27-04-2023', NULL),
(508, 135, 3, 1, 1, NULL, '27-04-2023', NULL),
(509, 135, 4, 1, 1, NULL, '27-04-2023', NULL),
(510, 136, 2, 1, 1, NULL, '01-05-2023', NULL),
(511, 66, 2, 1, 1, NULL, '01-05-2023', NULL),
(515, 116, 2, 1, 1, NULL, '05-05-2023', NULL),
(521, 117, 1, 1, 1, NULL, '05-05-2023', NULL),
(522, 117, 2, 1, 1, NULL, '05-05-2023', NULL),
(523, 117, 3, 1, 1, NULL, '05-05-2023', NULL),
(524, 117, 4, 1, 1, NULL, '05-05-2023', NULL),
(525, 117, 9, 1, 1, NULL, '05-05-2023', NULL),
(546, 53, 1, 1, 1, NULL, '09-05-2023', NULL),
(547, 53, 2, 1, 1, NULL, '09-05-2023', NULL),
(548, 53, 3, 1, 1, NULL, '09-05-2023', NULL),
(549, 53, 4, 1, 1, NULL, '09-05-2023', NULL),
(556, 54, 1, 1, 1, NULL, '09-05-2023', NULL),
(557, 54, 2, 1, 1, NULL, '09-05-2023', NULL),
(558, 54, 3, 1, 1, NULL, '09-05-2023', NULL),
(566, 137, 1, 1, 1, NULL, '10-05-2023', NULL),
(567, 137, 2, 1, 1, NULL, '10-05-2023', NULL),
(568, 137, 3, 1, 1, NULL, '10-05-2023', NULL),
(569, 138, 1, 1, 1, NULL, '11-05-2023', NULL),
(570, 138, 2, 1, 1, NULL, '11-05-2023', NULL),
(571, 138, 3, 1, 1, NULL, '11-05-2023', NULL),
(577, 13, 2, 1, 1, NULL, '23-05-2023', NULL),
(578, 57, 1, 1, 1, NULL, '23-05-2023', NULL),
(579, 57, 2, 1, 1, NULL, '23-05-2023', NULL),
(580, 57, 3, 1, 1, NULL, '23-05-2023', NULL),
(581, 57, 4, 1, 1, NULL, '23-05-2023', NULL),
(688, 140, 1, 1, 1, NULL, '02-08-2023', NULL),
(689, 140, 2, 1, 1, NULL, '02-08-2023', NULL),
(690, 140, 3, 1, 1, NULL, '02-08-2023', NULL),
(691, 140, 4, 1, 1, NULL, '02-08-2023', NULL),
(692, 140, 5, 1, 1, NULL, '02-08-2023', NULL),
(693, 140, 6, 1, 1, NULL, '02-08-2023', NULL),
(694, 140, 7, 1, 1, NULL, '02-08-2023', NULL),
(695, 140, 8, 1, 1, NULL, '02-08-2023', NULL),
(696, 140, 9, 1, 1, NULL, '02-08-2023', NULL),
(697, 140, 10, 1, 1, NULL, '02-08-2023', NULL),
(698, 140, 11, 1, 1, NULL, '02-08-2023', NULL),
(699, 140, 12, 1, 1, NULL, '02-08-2023', NULL),
(700, 140, 13, 1, 1, NULL, '02-08-2023', NULL),
(701, 140, 14, 1, 1, NULL, '02-08-2023', NULL),
(702, 140, 15, 1, 1, NULL, '02-08-2023', NULL),
(703, 140, 16, 1, 1, NULL, '02-08-2023', NULL),
(724, 141, 1, 1, 1, NULL, '03-08-2023', NULL),
(725, 141, 2, 1, 1, NULL, '03-08-2023', NULL),
(726, 141, 3, 1, 1, NULL, '03-08-2023', NULL),
(727, 141, 4, 1, 1, NULL, '03-08-2023', NULL),
(728, 141, 8, 1, 1, NULL, '03-08-2023', NULL),
(729, 141, 9, 1, 1, NULL, '03-08-2023', NULL),
(730, 141, 15, 1, 1, NULL, '03-08-2023', NULL),
(731, 141, 16, 1, 1, NULL, '03-08-2023', NULL),
(758, 24, 1, 1, 1, NULL, '04-08-2023', NULL),
(759, 24, 2, 1, 1, NULL, '04-08-2023', NULL),
(760, 24, 3, 1, 1, NULL, '04-08-2023', NULL),
(761, 24, 9, 1, 1, NULL, '04-08-2023', NULL),
(762, 24, 15, 1, 1, NULL, '04-08-2023', NULL),
(763, 24, 16, 1, 1, NULL, '04-08-2023', NULL),
(767, 115, 1, 1, 1, NULL, '05-08-2023', NULL),
(768, 115, 2, 1, 1, NULL, '05-08-2023', NULL),
(769, 115, 3, 1, 1, NULL, '05-08-2023', NULL),
(770, 115, 4, 1, 1, NULL, '05-08-2023', NULL),
(771, 115, 5, 1, 1, NULL, '05-08-2023', NULL),
(772, 115, 6, 1, 1, NULL, '05-08-2023', NULL),
(773, 115, 7, 1, 1, NULL, '05-08-2023', NULL),
(774, 115, 8, 1, 1, NULL, '05-08-2023', NULL),
(775, 115, 9, 1, 1, NULL, '05-08-2023', NULL),
(776, 115, 10, 1, 1, NULL, '05-08-2023', NULL),
(777, 115, 11, 1, 1, NULL, '05-08-2023', NULL),
(778, 115, 12, 1, 1, NULL, '05-08-2023', NULL),
(779, 115, 13, 1, 1, NULL, '05-08-2023', NULL),
(780, 115, 14, 1, 1, NULL, '05-08-2023', NULL),
(781, 115, 15, 1, 1, NULL, '05-08-2023', NULL),
(782, 115, 16, 1, 1, NULL, '05-08-2023', NULL),
(783, 25, 1, 1, 1, NULL, '08-08-2023', NULL),
(784, 25, 2, 1, 1, NULL, '08-08-2023', NULL),
(785, 25, 3, 1, 1, NULL, '08-08-2023', NULL),
(786, 25, 4, 1, 1, NULL, '08-08-2023', NULL),
(787, 25, 9, 1, 1, NULL, '08-08-2023', NULL),
(788, 25, 15, 1, 1, NULL, '08-08-2023', NULL),
(789, 25, 16, 1, 1, NULL, '08-08-2023', NULL),
(847, 143, 1, 1, 1, NULL, '09-08-2023', NULL),
(848, 143, 2, 1, 1, NULL, '09-08-2023', NULL),
(849, 143, 3, 1, 1, NULL, '09-08-2023', NULL),
(850, 143, 4, 1, 1, NULL, '09-08-2023', NULL),
(851, 143, 5, 1, 1, NULL, '09-08-2023', NULL),
(852, 143, 6, 1, 1, NULL, '09-08-2023', NULL),
(853, 143, 7, 1, 1, NULL, '09-08-2023', NULL),
(854, 143, 8, 1, 1, NULL, '09-08-2023', NULL),
(855, 143, 9, 1, 1, NULL, '09-08-2023', NULL),
(856, 143, 10, 1, 1, NULL, '09-08-2023', NULL),
(857, 143, 11, 1, 1, NULL, '09-08-2023', NULL),
(858, 143, 12, 1, 1, NULL, '09-08-2023', NULL),
(859, 143, 13, 1, 1, NULL, '09-08-2023', NULL),
(860, 143, 14, 1, 1, NULL, '09-08-2023', NULL),
(861, 143, 15, 1, 1, NULL, '09-08-2023', NULL),
(862, 143, 16, 1, 1, NULL, '09-08-2023', NULL),
(889, 139, 1, 1, 118, NULL, '09-08-2023', NULL),
(890, 139, 2, 1, 118, NULL, '09-08-2023', NULL),
(891, 139, 3, 1, 118, NULL, '09-08-2023', NULL),
(892, 139, 4, 1, 118, NULL, '09-08-2023', NULL),
(893, 139, 5, 1, 118, NULL, '09-08-2023', NULL),
(894, 139, 7, 1, 118, NULL, '09-08-2023', NULL),
(895, 139, 8, 1, 118, NULL, '09-08-2023', NULL),
(896, 139, 9, 1, 118, NULL, '09-08-2023', NULL),
(897, 139, 10, 1, 118, NULL, '09-08-2023', NULL),
(898, 139, 13, 1, 118, NULL, '09-08-2023', NULL),
(899, 139, 14, 1, 118, NULL, '09-08-2023', NULL),
(900, 139, 15, 1, 118, NULL, '09-08-2023', NULL),
(901, 139, 16, 1, 118, NULL, '09-08-2023', NULL),
(911, 23, 1, 1, 118, NULL, '09-08-2023', NULL),
(912, 23, 2, 1, 118, NULL, '09-08-2023', NULL),
(913, 23, 3, 1, 118, NULL, '09-08-2023', NULL),
(914, 23, 4, 1, 118, NULL, '09-08-2023', NULL),
(915, 23, 7, 1, 118, NULL, '09-08-2023', NULL),
(916, 23, 8, 1, 118, NULL, '09-08-2023', NULL),
(917, 23, 9, 1, 118, NULL, '09-08-2023', NULL),
(918, 23, 15, 1, 118, NULL, '09-08-2023', NULL),
(919, 23, 16, 1, 118, NULL, '09-08-2023', NULL),
(933, 3, 2, 1, 118, NULL, '09-08-2023', NULL),
(950, 63, 1, 1, 118, NULL, '19-08-2023', NULL),
(951, 63, 2, 1, 118, NULL, '19-08-2023', NULL),
(952, 63, 3, 1, 118, NULL, '19-08-2023', NULL),
(953, 63, 4, 1, 118, NULL, '19-08-2023', NULL),
(958, 142, 1, 1, 1, NULL, '28-08-2023', NULL),
(959, 142, 2, 1, 1, NULL, '28-08-2023', NULL),
(960, 142, 3, 1, 1, NULL, '28-08-2023', NULL),
(961, 142, 4, 1, 1, NULL, '28-08-2023', NULL),
(962, 142, 8, 1, 1, NULL, '28-08-2023', NULL),
(963, 142, 9, 1, 1, NULL, '28-08-2023', NULL),
(964, 142, 10, 1, 1, NULL, '28-08-2023', NULL),
(965, 142, 12, 1, 1, NULL, '28-08-2023', NULL),
(966, 142, 14, 1, 1, NULL, '28-08-2023', NULL),
(967, 142, 15, 1, 1, NULL, '28-08-2023', NULL),
(968, 142, 16, 1, 1, NULL, '28-08-2023', NULL),
(969, 27, 1, 1, 1, NULL, '28-08-2023', NULL),
(970, 27, 2, 1, 1, NULL, '28-08-2023', NULL),
(971, 27, 3, 1, 1, NULL, '28-08-2023', NULL),
(972, 27, 4, 1, 1, NULL, '28-08-2023', NULL),
(973, 27, 9, 1, 1, NULL, '28-08-2023', NULL),
(974, 27, 15, 1, 1, NULL, '28-08-2023', NULL),
(975, 27, 16, 1, 1, NULL, '28-08-2023', NULL),
(1003, 30, 1, 1, 1, NULL, '28-08-2023', NULL),
(1004, 30, 2, 1, 1, NULL, '28-08-2023', NULL),
(1005, 30, 3, 1, 1, NULL, '28-08-2023', NULL),
(1006, 30, 4, 1, 1, NULL, '28-08-2023', NULL),
(1007, 30, 9, 1, 1, NULL, '28-08-2023', NULL),
(1056, 145, 1, 1, 1, NULL, '28-08-2023', NULL),
(1057, 145, 2, 1, 1, NULL, '28-08-2023', NULL),
(1058, 145, 3, 1, 1, NULL, '28-08-2023', NULL),
(1059, 145, 4, 1, 1, NULL, '28-08-2023', NULL),
(1060, 38, 1, 1, 1, NULL, '31-08-2023', NULL),
(1061, 38, 2, 1, 1, NULL, '31-08-2023', NULL),
(1062, 38, 3, 1, 1, NULL, '31-08-2023', NULL),
(1063, 83, 1, 1, 1, NULL, '31-08-2023', NULL),
(1064, 83, 2, 1, 1, NULL, '31-08-2023', NULL),
(1065, 83, 3, 1, 1, NULL, '31-08-2023', NULL),
(1066, 83, 4, 1, 1, NULL, '31-08-2023', NULL),
(1074, 147, 1, 1, 118, NULL, '07-09-2023', NULL),
(1075, 147, 2, 1, 118, NULL, '07-09-2023', NULL),
(1076, 147, 3, 1, 118, NULL, '07-09-2023', NULL),
(1077, 147, 4, 1, 118, NULL, '07-09-2023', NULL),
(1085, 148, 1, 1, 118, NULL, '07-09-2023', NULL),
(1086, 148, 2, 1, 118, NULL, '07-09-2023', NULL),
(1087, 148, 3, 1, 118, NULL, '07-09-2023', NULL),
(1088, 148, 4, 1, 118, NULL, '07-09-2023', NULL),
(1089, 149, 1, 1, 118, NULL, '07-09-2023', NULL),
(1090, 149, 2, 1, 118, NULL, '07-09-2023', NULL),
(1091, 149, 3, 1, 118, NULL, '07-09-2023', NULL),
(1092, 149, 4, 1, 118, NULL, '07-09-2023', NULL),
(1100, 144, 1, 1, 1, NULL, '08-09-2023', NULL),
(1101, 144, 2, 1, 1, NULL, '08-09-2023', NULL),
(1102, 144, 3, 1, 1, NULL, '08-09-2023', NULL),
(1103, 144, 4, 1, 1, NULL, '08-09-2023', NULL),
(1104, 144, 5, 1, 1, NULL, '08-09-2023', NULL),
(1105, 144, 6, 1, 1, NULL, '08-09-2023', NULL),
(1106, 144, 7, 1, 1, NULL, '08-09-2023', NULL),
(1107, 144, 8, 1, 1, NULL, '08-09-2023', NULL),
(1108, 144, 9, 1, 1, NULL, '08-09-2023', NULL),
(1109, 144, 10, 1, 1, NULL, '08-09-2023', NULL),
(1110, 144, 11, 1, 1, NULL, '08-09-2023', NULL),
(1111, 144, 12, 1, 1, NULL, '08-09-2023', NULL),
(1112, 144, 13, 1, 1, NULL, '08-09-2023', NULL),
(1113, 144, 14, 1, 1, NULL, '08-09-2023', NULL),
(1114, 144, 15, 1, 1, NULL, '08-09-2023', NULL),
(1115, 144, 16, 1, 1, NULL, '08-09-2023', NULL),
(1119, 150, 1, 1, 118, NULL, '14-09-2023', NULL),
(1120, 150, 2, 1, 118, NULL, '14-09-2023', NULL),
(1121, 150, 3, 1, 118, NULL, '14-09-2023', NULL),
(1122, 150, 4, 1, 118, NULL, '14-09-2023', NULL),
(1123, 151, 2, 1, 118, NULL, '15-09-2023', NULL),
(1124, 42, 1, 1, 118, NULL, '18-09-2023', NULL),
(1125, 42, 2, 1, 118, NULL, '18-09-2023', NULL),
(1126, 42, 3, 1, 118, NULL, '18-09-2023', NULL),
(1127, 52, 1, 1, 118, NULL, '18-09-2023', NULL),
(1128, 52, 2, 1, 118, NULL, '18-09-2023', NULL),
(1129, 52, 3, 1, 118, NULL, '18-09-2023', NULL),
(1131, 15, 2, 1, 118, NULL, '18-09-2023', NULL),
(1132, 18, 2, 1, 118, NULL, '18-09-2023', NULL),
(1133, 16, 2, 1, 118, NULL, '18-09-2023', NULL),
(1134, 17, 2, 1, 118, NULL, '18-09-2023', NULL),
(1139, 84, 2, 1, 118, NULL, '20-09-2023', NULL),
(1140, 118, 2, 1, 118, NULL, '20-09-2023', NULL),
(1141, 119, 2, 1, 118, NULL, '20-09-2023', NULL),
(1142, 154, 2, 1, 118, NULL, '20-09-2023', NULL),
(1143, 155, 2, 1, 118, NULL, '20-09-2023', NULL),
(1144, 153, 2, 1, 118, NULL, '20-09-2023', NULL),
(1145, 152, 2, 1, 118, NULL, '20-09-2023', NULL),
(1146, 120, 2, 1, 118, NULL, '20-09-2023', NULL),
(1147, 156, 2, 1, 118, NULL, '20-09-2023', NULL),
(1148, 121, 2, 1, 118, NULL, '20-09-2023', NULL),
(1149, 86, 2, 1, 118, NULL, '21-09-2023', NULL),
(1150, 88, 2, 1, 118, NULL, '21-09-2023', NULL),
(1151, 89, 2, 1, 118, NULL, '21-09-2023', NULL),
(1152, 90, 2, 1, 118, NULL, '21-09-2023', NULL),
(1153, 157, 1, 1, 118, NULL, '21-09-2023', NULL),
(1154, 157, 2, 1, 118, NULL, '21-09-2023', NULL),
(1155, 157, 3, 1, 118, NULL, '21-09-2023', NULL),
(1156, 157, 4, 1, 118, NULL, '21-09-2023', NULL),
(1157, 125, 1, 1, 1, NULL, '22-09-2023', NULL),
(1158, 125, 2, 1, 1, NULL, '22-09-2023', NULL),
(1159, 125, 3, 1, 1, NULL, '22-09-2023', NULL),
(1160, 125, 4, 1, 1, NULL, '22-09-2023', NULL),
(1161, 125, 9, 1, 1, NULL, '22-09-2023', NULL),
(1162, 125, 15, 1, 1, NULL, '22-09-2023', NULL),
(1163, 125, 16, 1, 1, NULL, '22-09-2023', NULL),
(1188, 39, 1, 1, 1, NULL, '22-09-2023', NULL),
(1189, 39, 2, 1, 1, NULL, '22-09-2023', NULL),
(1190, 39, 3, 1, 1, NULL, '22-09-2023', NULL),
(1191, 159, 2, 1, 118, NULL, '22-09-2023', NULL),
(1192, 29, 1, 1, 1, NULL, '29-09-2023', NULL),
(1193, 29, 2, 1, 1, NULL, '29-09-2023', NULL),
(1194, 29, 3, 1, 1, NULL, '29-09-2023', NULL),
(1195, 29, 4, 1, 1, NULL, '29-09-2023', NULL),
(1196, 29, 9, 1, 1, NULL, '29-09-2023', NULL),
(1197, 29, 15, 1, 1, NULL, '29-09-2023', NULL),
(1198, 29, 16, 1, 1, NULL, '29-09-2023', NULL),
(1199, 26, 1, 1, 1, NULL, '29-09-2023', NULL),
(1200, 26, 2, 1, 1, NULL, '29-09-2023', NULL),
(1201, 26, 3, 1, 1, NULL, '29-09-2023', NULL),
(1202, 26, 4, 1, 1, NULL, '29-09-2023', NULL),
(1203, 26, 5, 1, 1, NULL, '29-09-2023', NULL),
(1204, 26, 7, 1, 1, NULL, '29-09-2023', NULL),
(1205, 26, 8, 1, 1, NULL, '29-09-2023', NULL),
(1206, 26, 9, 1, 1, NULL, '29-09-2023', NULL),
(1207, 26, 10, 1, 1, NULL, '29-09-2023', NULL),
(1208, 26, 13, 1, 1, NULL, '29-09-2023', NULL),
(1209, 26, 14, 1, 1, NULL, '29-09-2023', NULL),
(1210, 26, 15, 1, 1, NULL, '29-09-2023', NULL),
(1211, 26, 16, 1, 1, NULL, '29-09-2023', NULL),
(1212, 28, 1, 1, 1, NULL, '30-09-2023', NULL),
(1213, 28, 2, 1, 1, NULL, '30-09-2023', NULL),
(1214, 28, 3, 1, 1, NULL, '30-09-2023', NULL),
(1215, 28, 4, 1, 1, NULL, '30-09-2023', NULL),
(1216, 28, 9, 1, 1, NULL, '30-09-2023', NULL),
(1217, 28, 15, 1, 1, NULL, '30-09-2023', NULL),
(1218, 28, 16, 1, 1, NULL, '30-09-2023', NULL),
(1219, 160, 1, 1, 1, NULL, '02-10-2023', NULL),
(1220, 160, 2, 1, 1, NULL, '02-10-2023', NULL),
(1221, 160, 3, 1, 1, NULL, '02-10-2023', NULL),
(1222, 160, 4, 1, 1, NULL, '02-10-2023', NULL),
(1227, 161, 1, 1, 1, NULL, '02-10-2023', NULL),
(1228, 161, 2, 1, 1, NULL, '02-10-2023', NULL),
(1229, 161, 3, 1, 1, NULL, '02-10-2023', NULL),
(1230, 161, 4, 1, 1, NULL, '02-10-2023', NULL),
(1235, 19, 1, 1, 1, NULL, '02-10-2023', NULL),
(1236, 19, 2, 1, 1, NULL, '02-10-2023', NULL),
(1237, 19, 3, 1, 1, NULL, '02-10-2023', NULL),
(1238, 19, 4, 1, 1, NULL, '02-10-2023', NULL),
(1239, 162, 1, 1, 118, NULL, '03-10-2023', NULL),
(1240, 162, 2, 1, 118, NULL, '03-10-2023', NULL),
(1241, 162, 3, 1, 118, NULL, '03-10-2023', NULL),
(1242, 162, 4, 1, 118, NULL, '03-10-2023', NULL),
(1243, 163, 1, 1, 118, NULL, '03-10-2023', NULL),
(1244, 163, 2, 1, 118, NULL, '03-10-2023', NULL),
(1245, 163, 3, 1, 118, NULL, '03-10-2023', NULL),
(1246, 163, 4, 1, 118, NULL, '03-10-2023', NULL),
(1251, 146, 1, 1, 118, NULL, '04-10-2023', NULL),
(1252, 146, 2, 1, 118, NULL, '04-10-2023', NULL),
(1253, 146, 3, 1, 118, NULL, '04-10-2023', NULL),
(1254, 146, 4, 1, 118, NULL, '04-10-2023', NULL),
(1255, 164, 1, 1, 118, NULL, '04-10-2023', NULL),
(1256, 164, 2, 1, 118, NULL, '04-10-2023', NULL),
(1257, 164, 3, 1, 118, NULL, '04-10-2023', NULL),
(1258, 164, 4, 1, 118, NULL, '04-10-2023', NULL),
(1291, 158, 1, 1, 118, NULL, '10-10-2023', NULL),
(1292, 158, 2, 1, 118, NULL, '10-10-2023', NULL),
(1293, 158, 3, 1, 118, NULL, '10-10-2023', NULL),
(1294, 158, 4, 1, 118, NULL, '10-10-2023', NULL),
(1299, 166, 1, 1, 118, NULL, '10-10-2023', NULL),
(1300, 166, 2, 1, 118, NULL, '10-10-2023', NULL),
(1301, 166, 3, 1, 118, NULL, '10-10-2023', NULL),
(1302, 166, 4, 1, 118, NULL, '10-10-2023', NULL),
(1313, 32, 1, 1, 1, NULL, '11-10-2023', NULL),
(1314, 32, 2, 1, 1, NULL, '11-10-2023', NULL),
(1315, 32, 3, 1, 1, NULL, '11-10-2023', NULL),
(1316, 167, 1, 1, 1, NULL, '12-10-2023', NULL),
(1317, 167, 2, 1, 1, NULL, '12-10-2023', NULL),
(1318, 167, 3, 1, 1, NULL, '12-10-2023', NULL),
(1319, 167, 4, 1, 1, NULL, '12-10-2023', NULL),
(1320, 165, 1, 1, 118, NULL, '14-10-2023', NULL),
(1321, 165, 2, 1, 118, NULL, '14-10-2023', NULL),
(1322, 165, 3, 1, 118, NULL, '14-10-2023', NULL),
(1323, 165, 4, 1, 118, NULL, '14-10-2023', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_page_sectors`
--

CREATE TABLE `tbl_page_sectors` (
  `id` int NOT NULL,
  `page_id` int NOT NULL,
  `department_id` int NOT NULL,
  `sector_id` int NOT NULL,
  `directorate_id` int NOT NULL,
  `status` int DEFAULT '1',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_page_sectors`
--

INSERT INTO `tbl_page_sectors` (`id`, `page_id`, `department_id`, `sector_id`, `directorate_id`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 7, 10, 18, 41, 1, 1, NULL, '29-03-2023', NULL),
(2, 8, 10, 18, 41, 1, 1, NULL, '29-03-2023', NULL),
(6, 45, 10, 17, 44, 1, 1, NULL, '29-03-2023', NULL),
(9, 55, 10, 18, 42, 1, 1, NULL, '03-04-2023', NULL),
(10, 64, 10, 18, 42, 1, 1, NULL, '08-04-2023', NULL),
(11, 65, 10, 18, 42, 1, 1, NULL, '08-04-2023', NULL),
(13, 67, 10, 18, 42, 1, 1, NULL, '08-04-2023', NULL),
(14, 68, 10, 18, 42, 1, 1, NULL, '08-04-2023', NULL),
(15, 69, 10, 18, 42, 1, 1, NULL, '08-04-2023', NULL),
(16, 70, 10, 18, 42, 1, 1, NULL, '08-04-2023', NULL),
(17, 47, 10, 17, 43, 1, 1, NULL, '08-04-2023', NULL),
(18, 74, 10, 17, 43, 1, 1, NULL, '08-04-2023', NULL),
(19, 75, 10, 17, 43, 1, 1, NULL, '08-04-2023', NULL),
(20, 76, 10, 17, 44, 1, 1, NULL, '08-04-2023', NULL),
(21, 77, 10, 17, 44, 1, 1, NULL, '08-04-2023', NULL),
(22, 82, 10, 18, 42, 1, 1, NULL, '08-04-2023', NULL),
(23, 94, 10, 18, 41, 1, 1, NULL, '08-04-2023', NULL),
(24, 95, 10, 18, 42, 1, 1, NULL, '08-04-2023', NULL),
(25, 96, 10, 18, 42, 1, 1, NULL, '08-04-2023', NULL),
(26, 97, 10, 18, 42, 1, 1, NULL, '08-04-2023', NULL),
(28, 98, 10, 18, 41, 1, 1, NULL, '09-04-2023', NULL),
(29, 99, 10, 18, 41, 1, 1, NULL, '09-04-2023', NULL),
(30, 100, 10, 18, 41, 1, 1, NULL, '09-04-2023', NULL),
(31, 103, 10, 18, 41, 1, 1, NULL, '09-04-2023', NULL),
(32, 105, 10, 18, 41, 1, 1, NULL, '10-04-2023', NULL),
(33, 106, 10, 18, 41, 1, 1, NULL, '10-04-2023', NULL),
(36, 123, 10, 18, 42, 1, 1, NULL, '13-04-2023', NULL),
(37, 124, 10, 18, 42, 1, 1, NULL, '13-04-2023', NULL),
(40, 128, 10, 18, 42, 1, 1, NULL, '26-04-2023', NULL),
(41, 127, 10, 18, 42, 1, 1, NULL, '26-04-2023', NULL),
(42, 129, 10, 17, 44, 1, 1, NULL, '27-04-2023', NULL),
(43, 130, 10, 17, 44, 1, 1, NULL, '27-04-2023', NULL),
(44, 131, 10, 17, 44, 1, 1, NULL, '27-04-2023', NULL),
(45, 132, 10, 17, 44, 1, 1, NULL, '27-04-2023', NULL),
(46, 133, 10, 17, 44, 1, 1, NULL, '27-04-2023', NULL),
(47, 134, 10, 17, 44, 1, 1, NULL, '27-04-2023', NULL),
(48, 135, 10, 18, 42, 1, 1, NULL, '27-04-2023', NULL),
(49, 136, 10, 18, 42, 1, 1, NULL, '01-05-2023', NULL),
(50, 66, 10, 18, 0, 1, 1, NULL, '01-05-2023', NULL),
(51, 116, 10, 17, 43, 1, 1, NULL, '05-05-2023', NULL),
(53, 117, 10, 17, 43, 1, 1, NULL, '05-05-2023', NULL),
(56, 137, 10, 18, 42, 1, 1, NULL, '10-05-2023', NULL),
(57, 25, 10, 17, 43, 1, 1, NULL, '08-08-2023', NULL),
(61, 157, 10, 17, 44, 1, 118, NULL, '21-09-2023', NULL),
(63, 26, 10, 18, 42, 1, 1, NULL, '29-09-2023', NULL),
(64, 28, 10, 18, 42, 1, 1, NULL, '30-09-2023', NULL);

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
-- Table structure for table `tbl_partner_roles`
--

CREATE TABLE `tbl_partner_roles` (
  `id` int NOT NULL,
  `role` varchar(255) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_partner_roles`
--

INSERT INTO `tbl_partner_roles` (`id`, `role`, `description`) VALUES
(1, 'Lead Partner', NULL),
(2, 'Collaborative Partner', NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payments_disbursed`
--

CREATE TABLE `tbl_payments_disbursed` (
  `id` int NOT NULL,
  `receipt_no` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `request_id` varchar(255) NOT NULL,
  `payment_mode` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `comments` text NOT NULL,
  `receipt` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `paid_to` int NOT NULL,
  `date_paid` date NOT NULL,
  `request_type` int NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_payments_disbursed`
--

INSERT INTO `tbl_payments_disbursed` (`id`, `receipt_no`, `request_id`, `payment_mode`, `comments`, `receipt`, `paid_to`, `date_paid`, `request_type`, `created_by`, `created_at`) VALUES
(1, '0', '1', '1', 'test the test', 'uploads/payments/1697000201_6_361078041_6077519649024240_5762839329424038319_n.jpg', 0, '2023-10-12', 2, 1, '2023-10-11'),
(5, '0', '2', '1', 'test the test', 'uploads/payments/1697000201_6_361078041_6077519649024240_5762839329424038319_n.jpg', 0, '2023-10-12', 2, 1, '2023-10-11'),
(6, '0', '147', '2', 'Testing', '../../uploads/payments/1697195831_4_378948292_6634977183251109_8639244104715301120_n.jpg', 0, '2023-10-19', 1, 1, '2023-10-13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payments_request`
--

CREATE TABLE `tbl_payments_request` (
  `id` int NOT NULL,
  `request_id` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL DEFAULT '0',
  `state_id` int NOT NULL DEFAULT '0',
  `site_id` int NOT NULL DEFAULT '0',
  `requested_for` int NOT NULL,
  `due_date` date NOT NULL,
  `amount_requested` double NOT NULL,
  `requested_by` int NOT NULL,
  `date_requested` date NOT NULL,
  `cod` int DEFAULT NULL,
  `cod_action_date` date DEFAULT NULL,
  `cof` int DEFAULT NULL,
  `cof_action_date` date DEFAULT NULL,
  `status` int NOT NULL COMMENT '(0: Draft,1:pending, 2:rejected, 3:paid)',
  `stage` int NOT NULL COMMENT '(1:DD, 2:COD, 3:COF, 4:DF)',
  `project_stage` int NOT NULL,
  `purpose` int NOT NULL DEFAULT '0' COMMENT '1 is Direct cost; 2 is Monitoring; 3 is Inspection'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_payments_request`
--

INSERT INTO `tbl_payments_request` (`id`, `request_id`, `projid`, `output_id`, `state_id`, `site_id`, `requested_for`, `due_date`, `amount_requested`, `requested_by`, `date_requested`, `cod`, `cod_action_date`, `cof`, `cof_action_date`, `status`, `stage`, `project_stage`, `purpose`) VALUES
(1, '147', 109, 0, 0, 0, 1, '2023-10-13', 0, 1, '2023-10-13', 1, '2023-10-13', 1, '2023-10-13', 3, 3, 10, 0),
(2, '2253', 109, 0, 0, 0, 1, '2023-10-13', 0, 1, '2023-10-13', NULL, NULL, NULL, NULL, 1, 1, 10, 0),
(3, '1500', 101, 0, 0, 0, 1, '2023-10-13', 0, 1, '2023-10-13', NULL, NULL, NULL, NULL, 1, 1, 10, 0),
(4, '407', 101, 0, 0, 0, 1, '2023-10-13', 0, 1, '2023-10-13', NULL, NULL, NULL, NULL, 1, 1, 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payments_request_details`
--

CREATE TABLE `tbl_payments_request_details` (
  `id` int NOT NULL,
  `request_id` varchar(255) NOT NULL,
  `task_id` int NOT NULL DEFAULT '0',
  `direct_cost_id` int NOT NULL,
  `no_of_units` double NOT NULL,
  `unit_cost` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_payments_request_details`
--

INSERT INTO `tbl_payments_request_details` (`id`, `request_id`, `task_id`, `direct_cost_id`, `no_of_units`, `unit_cost`) VALUES
(1, '147', 0, 737, 1, 50),
(2, '2253', 0, 737, 1, 50),
(3, '1500', 0, 652, 10, 50),
(4, '407', 0, 653, 5, 40000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_request_comments`
--

CREATE TABLE `tbl_payment_request_comments` (
  `id` int NOT NULL,
  `request_id` varchar(255) NOT NULL,
  `stage` int NOT NULL,
  `status` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `comments` text NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_payment_request_comments`
--

INSERT INTO `tbl_payment_request_comments` (`id`, `request_id`, `stage`, `status`, `comments`, `created_by`, `created_at`) VALUES
(1, '147', 1, '1', 'test one two three', 1, '2023-10-13'),
(2, '2253', 1, '1', 'test one two three', 1, '2023-10-13'),
(3, '1500', 1, '1', 'Testing if we are working to do the best', 1, '2023-10-13'),
(4, '407', 1, '1', 'testing the test', 1, '2023-10-13'),
(5, '147', 2, '1', 'Comments', 1, '2023-10-13'),
(6, '147', 3, '1', 'Comments', 1, '2023-10-13'),
(7, '147', 3, '1', 'Comments', 1, '2023-10-13'),
(8, '147', 4, '3', 'Testing', 1, '2023-10-13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_request_financiers`
--

CREATE TABLE `tbl_payment_request_financiers` (
  `id` int NOT NULL,
  `request_id` varchar(255) NOT NULL,
  `financier_id` int NOT NULL,
  `amount` double NOT NULL,
  `cost_type` int NOT NULL DEFAULT '0',
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Table structure for table `tbl_permissions`
--

CREATE TABLE `tbl_permissions` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phrase` varchar(255) NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_permissions`
--

INSERT INTO `tbl_permissions` (`id`, `name`, `phrase`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Create', 'create', 1, 1, NULL, '2023-03-29', NULL),
(2, 'Read', 'read', 1, 1, NULL, '2023-03-29', NULL),
(3, 'Update', 'update', 1, 1, NULL, '2023-03-29', NULL),
(4, 'Delete', 'delete', 1, 1, NULL, '2023-03-29', NULL),
(5, 'Add to ADP', 'add_to_adp', 1, 1, 1, '2023-03-29', '2023-04-01'),
(6, 'Remove from ADP', 'remove_from_adp', 1, 1, NULL, '2023-03-29', NULL),
(7, 'Approve Project', 'approve_project', 1, 1, NULL, '2023-03-29', NULL),
(8, 'Add Approved Project', 'add_approved_project', 1, 1, NULL, '2023-03-29', NULL),
(9, 'Approve Stage', 'approve_stage', 1, 1, 1, '2023-03-29', '2023-04-23'),
(10, 'Add Baseline ', 'add_basiline', 1, 1, NULL, '2023-04-01', NULL),
(11, 'Create Quarterly Targets ', 'create_quarterly_targets', 1, 1, NULL, '2023-04-14', NULL),
(12, 'Update Quarterly Targets', 'update_quarterly_targets', 1, 1, NULL, '2023-04-14', NULL),
(13, 'Add Budget', 'add_budget', 1, 1, NULL, '2023-04-14', NULL),
(14, 'Edit Budget', 'edit_budget', 1, 1, NULL, '2023-04-14', NULL),
(15, 'Assign Data Entry', 'assign_data_entry_responsible', 1, 1, 1, '2023-04-20', '2023-04-20'),
(16, 'Assign Approval Responsible', 'assign_approval_responsible', 1, 1, NULL, '2023-04-20', NULL);

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
  `rates` int NOT NULL DEFAULT '0',
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pmdesignation`
--

INSERT INTO `tbl_pmdesignation` (`moid`, `designation`, `Reporting`, `level`, `position`, `rates`, `active`) VALUES
(1, 'Super User', 0, 0, 1, 0, 1),
(2, 'Governor', 0, 0, 2, 0, 1),
(3, 'Deputy Governor', 0, 0, 3, 0, 1),
(4, 'County Secretary', 2, 0, 4, 0, 1),
(5, 'CEC', 2, 1, 5, 0, 1),
(6, 'CO', 2, 2, 6, 0, 1),
(7, 'Director', 6, 3, 7, 0, 1),
(8, 'Deputy Director', 6, 3, 8, 0, 1),
(9, 'Assistant Director', 7, 3, 9, 0, 1),
(10, 'Manager', 7, 3, 10, 0, 1),
(11, 'Assistant Manager', 7, 3, 11, 0, 1),
(12, 'Principal Officer', 7, 3, 12, 0, 1),
(13, 'Officer', 7, 3, 13, 0, 1),
(14, 'Assistant Officer', 7, 3, 14, 0, 1);

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
(3, 'Low', 'Low Priority', 1, 1),
(4, '1', 'Architecto reiciendi', 8, 0);

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
(1, 1, 2022, 'Road Tarmacked ', 1, 200, 2000000000),
(2, 1, 2023, 'Road Tarmacked ', 1, 300, 1500000000),
(3, 1, 2022, 'Road graded', 2, 50, 500000000),
(4, 1, 2023, 'Road graded', 2, 50, 300000000),
(5, 1, 2022, 'Bridges Constructed', 8, 20, 200000000),
(6, 1, 2023, 'Bridges Constructed', 8, 20, 140000000),
(7, 2, 2022, 'Boreholes drilled', 4, 50, 110000000),
(8, 2, 2023, 'Boreholes drilled', 4, 50, 110000000),
(9, 2, 2022, 'Dams constructed', 7, 10, 300000000),
(10, 2, 2023, 'Dams constructed', 7, 10, 300000000),
(11, 2, 2022, 'Treatment Plant Constructed', 9, 5, 500000000),
(12, 2, 2023, 'Treatment Plant Constructed', 9, 5, 500000000),
(13, 2, 2022, 'Water Storage Tanks Constructed', 10, 50, 250000000),
(14, 2, 2023, 'Water Storage Tanks Constructed', 10, 50, 250000000),
(15, 2, 2022, 'Water Pipeline Laid', 12, 20, 1000000000),
(16, 2, 2023, 'Water Pipeline Laid', 12, 20, 1000000000),
(17, 2, 2022, 'Sewerline laid', 18, 20, 1000000000),
(18, 2, 2023, 'Sewerline laid', 18, 20, 1000000000),
(19, 3, 2022, 'Water Kiosks Constructed', 21, 100, 1000000000),
(20, 3, 2023, 'Water Kiosks Constructed', 21, 100, 1000000000),
(21, 3, 2022, 'Water Pipeline Laid', 12, 200, 1000000000),
(22, 3, 2023, 'Water Pipeline Laid', 12, 200, 1000000000),
(23, 4, 2022, 'Road Tarmacked ', 1, 50, 300000000),
(24, 4, 2023, 'Road Tarmacked ', 1, 70, 700000000),
(25, 4, 2022, 'Road graded', 2, 43, 500000000),
(26, 4, 2023, 'Road graded', 2, 15, 600000000),
(27, 4, 2022, 'Bridges Constructed', 8, 5, 600000000),
(28, 4, 2023, 'Bridges Constructed', 8, 7, 800000000),
(68, 11, 2024, 'farmers trained on new farming methods', 25, 250, 30000000),
(67, 11, 2023, 'farmers trained on new farming methods', 25, 250, 25000000),
(62, 9, 2023, 'Road Tarmacked ', 1, 50, 500000000),
(63, 9, 2023, 'Bridges Constructed', 8, 10, 100000000),
(64, 10, 2022, 'Boreholes drilled', 4, 50, 100000000),
(69, 12, 2023, 'Solid Waste Incinerator', 26, 2, 20000000),
(70, 12, 2024, 'Solid Waste Incinerator', 26, 2, 20000000),
(71, 12, 2023, 'Anaerobic Reactor', 27, 2, 10000000),
(72, 12, 2024, 'Anaerobic Reactor', 27, 2, 10000000),
(73, 12, 2023, 'Vertical Flow Wetlands Constructed					', 28, 2, 20000000),
(74, 12, 2024, 'Vertical Flow Wetlands Constructed					', 28, 2, 20000000),
(75, 13, 2021, 'Bridges Constructed', 8, 20, 20000000),
(76, 13, 2022, 'Bridges Constructed', 8, 30, 30000000),
(77, 14, 2023, 'Bridges Constructed', 8, 20, 20000000),
(78, 14, 2024, 'Bridges Constructed', 8, 30, 30000000),
(82, 15, 2024, 'Bridges Constructed', 8, 10, 10000000),
(81, 15, 2023, 'Bridges Constructed', 8, 10, 10000000),
(83, 16, 2023, 'Bins Distributed', 30, 200, 2000000),
(84, 16, 2024, 'Bins Distributed', 30, 300, 3000000),
(85, 17, 2023, 'Bridges Constructed', 8, 10, 100000000),
(86, 17, 2024, 'Bridges Constructed', 8, 5, 80000000),
(87, 18, 2023, 'Classrooms Constructed', 34, 100, 100000000),
(88, 18, 2024, 'Classrooms Constructed', 34, 100, 100000000);

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
(1, 5, 2022, 'Boreholes drilled', 4, 111, 111111),
(2, 5, 2022, 'Dams constructed', 7, 1111, 11110.99),
(3, 5, 2022, 'Boreholes drilled', 4, 111, 111111),
(4, 5, 2022, 'Dams constructed', 7, 1111, 11110.99),
(5, 5, 2022, 'Boreholes drilled', 4, 111, 111111),
(6, 5, 2022, 'Dams constructed', 7, 1111, 11110.99),
(7, 6, 2022, 'Boreholes drilled', 4, 10000, 100000),
(8, 6, 2022, 'Boreholes drilled', 4, 10000, 100000),
(9, 6, 2022, 'Dams constructed', 7, 100, 2000000),
(10, 7, 2021, 'Boreholes drilled', 4, 11, 2000000),
(11, 7, 2021, 'Boreholes drilled', 4, 40, 3000000),
(12, 7, 2021, 'Dams constructed', 7, 300, 4000),
(13, 7, 2021, 'Water Storage Tanks Constructed', 10, 400, 2000),
(14, 7, 2021, 'Boreholes drilled', 4, 111, 11111),
(15, 7, 2021, 'Boreholes drilled', 4, 111, 11111),
(16, 7, 2021, 'Boreholes drilled', 4, 111, 1110.98),
(17, 7, 2021, 'Dams constructed', 7, 1000, 1000000),
(18, 7, 2021, 'Dams constructed', 7, 1000, 1000000),
(19, 7, 2021, 'Water Storage Tanks Constructed', 10, 2000, 2000000),
(20, 7, 2021, 'Boreholes drilled', 4, 1100, 999999.96),
(21, 7, 2021, 'Boreholes drilled', 4, 1100, 999999.96),
(22, 7, 2021, 'Boreholes drilled', 4, 1000, 1000000),
(23, 7, 2021, 'Boreholes drilled', 4, 1000, 1000000),
(24, 8, 2023, 'Boreholes drilled', 4, 100, 10000000),
(25, 8, 2023, 'Boreholes drilled', 4, 100, 10000000),
(26, 8, 2023, 'Sewerline laid', 18, 1000, 10000000000),
(27, 11, 2023, 'farmers trained on new farming methods', 25, 250, 25000000),
(28, 11, 2024, 'farmers trained on new farming methods', 25, 250, 30000000),
(29, 15, 2023, 'Bridges Constructed', 8, 10, 10000000),
(30, 15, 2024, 'Bridges Constructed', 8, 10, 9999999.95);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programs`
--

CREATE TABLE `tbl_programs` (
  `progid` int NOT NULL,
  `progname` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `problem_statement` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `description` text NOT NULL,
  `strategic_plan` int DEFAULT NULL,
  `strategic_obj` int DEFAULT NULL,
  `progstrategy` int DEFAULT NULL,
  `projsector` int NOT NULL,
  `projdept` int NOT NULL,
  `directorate` int DEFAULT NULL,
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

INSERT INTO `tbl_programs` (`progid`, `progname`, `problem_statement`, `description`, `strategic_plan`, `strategic_obj`, `progstrategy`, `projsector`, `projdept`, `directorate`, `budget`, `syear`, `years`, `program_type`, `createdby`, `datecreated`, `modifiedby`, `datemodified`, `deleted`) VALUES
(1, 'Roads Programme', 'poor state of road infrastructure', '<p>test</p>', 1, 6, 6, 15, 19, 40, NULL, 2022, 2, 0, '3', '2023-03-15', NULL, NULL, 0),
(2, 'Water and Sanitation Programme', 'Poor Water and Sanitation ', '<p>Water and Sanitation Programme</p>', 1, 10, 10, 9, 23, 38, NULL, 2022, 2, 0, '3', '2023-03-15', NULL, NULL, 0),
(3, 'Urban Investment Programme', 'The Urban Investments through its Urban Projects Concept (UPC) was established in 2007 to respond to the specific water and sanitation challenges of urban low-income settlements in Kenya. There are approximately 2,000 low-income areas in the country with an estimated total population of close to eight million. These low-income areas, which are a mixture of unplanned informal settlements and planned low-income residential areas, have inadequate water supply and sanitation. The Urban Investments’ objective is to provide technical and financial support for water and sanitation projects in low-income urban areas. In addition, it focuses on:  Improving public health, Contributing to the improvement of urban livelihoods, Reducing unaccounted-for water, Building capacity at water utility level, Ensuring that low-income areas are perceived as a business opportunity. The Urban Investments’ objective is to provide technical and financial support for water and sanitation projects in low-income urban areas. In addition, it focuses on:  Improving public health, Contributing to the improvement of urban livelihoods, Reducing unaccounted-for water, Building capacity at water utility level, Ensuring that low-income areas are perceived as a business opportunity.', '<p>The Urban Investments through its Urban Projects Concept (UPC) was established in 2007 to respond to the specific water and sanitation challenges of urban low-income settlements in Kenya. There are approximately 2,000 low-income areas in the country with an estimated total population of close to eight million. These low-income areas, which are a mixture of unplanned informal settlements and planned low-income residential areas, have inadequate water supply and sanitation. The Urban Investments&rsquo; objective is to provide technical and financial support for water and sanitation projects in low-income urban areas. In addition, it focuses on:&nbsp; Improving public health, Contributing to the improvement of urban livelihoods, Reducing unaccounted-for water, Building capacity at water utility level, Ensuring that low-income areas are perceived as a business opportunity. The Urban Investments&rsquo; objective is to provide technical and financial support for water and sanitation projects in low-income urban areas. In addition, it focuses on:&nbsp; Improving public health, Contributing to the improvement of urban livelihoods, Reducing unaccounted-for water, Building capacity at water utility level, Ensuring that low-income areas are perceived as a business opportunity.</p>', 1, 10, 10, 9, 23, 38, NULL, 2022, 2, 0, '3', '2023-03-18', NULL, NULL, 0),
(4, 'Roads Infrastructure Services ', 'Lack of proper road infrastructure', '<p>test</p>', 1, 6, 11, 15, 19, 40, NULL, 2022, 2, 1, '3', '2023-03-21', NULL, NULL, 0),
(9, 'Roads Programme 2', 'poor Road networks', '<p>test</p>', 1, 6, 13, 15, 19, 40, NULL, 2023, 1, 0, '3', '2023-05-30', NULL, NULL, 0),
(10, 'Water programme', 'Testing', '', 0, 0, 0, 9, 23, 38, NULL, 2022, 1, 0, '3', '2023-05-31', NULL, NULL, 0),
(11, 'Training farmers on new farming methods', 'Low yields due to lack of latest farming knowledge', '<p>Testing</p>', 1, 1, 1, 1, 2, 49, NULL, 2023, 2, 0, '3', '2023-08-03', '3', '2023-08-03', 0),
(12, 'Up-Scaling Basic Sanitation for the Urban Poor (UBSUP) Programme', 'Up-Scaling Basic Sanitation for the Urban Poor (UBSUP) Programme', '<p>Testing</p>', 0, 0, 0, 9, 23, 38, NULL, 2023, 2, 0, '3', '2023-08-09', NULL, NULL, 0),
(13, 'Construction of immunization centers', 'lack of immunization centers', '<p>Construction of these centers will be done in all public hospitals around Uasingishu</p>', 1, 3, 22, 15, 19, 20, NULL, 2021, 2, 1, '3', '2023-08-12', NULL, NULL, 0),
(14, 'Construction of immunization centers', 'Lack of proper or well defined immunization centers', '<p>Construction of these centers will enable decongestion in hospitals</p>', 1, 3, 22, 15, 19, 20, NULL, 2023, 2, 1, '3', '2023-08-12', NULL, NULL, 0),
(15, 'Construction of Bridges', 'Lack of proper bridges', '<p>There is no modern birdges</p>', 0, 0, 0, 15, 19, 20, NULL, 2023, 2, 0, '3', '2023-08-12', '3', '2023-08-12', 0),
(16, 'Bin Distibution', 'lack of proper waste management', '<p>This program is initiated to help in waste management and improve on the&nbsp;</p>', 0, 0, 0, 9, 23, 38, NULL, 2023, 2, 0, '3', '2023-08-14', NULL, NULL, 0),
(17, 'Bridgework Programmes ', 'test', '<p>test</p>', 1, 6, 11, 15, 19, 40, NULL, 2023, 2, 1, '3', '2023-10-03', NULL, NULL, 0),
(18, 'Early Childhood Education Programme', 'Early Childhood Education Programme', '<p>Early Childhood Education Programme</p>', 0, 0, 0, 5, 6, 48, NULL, 2023, 2, 0, '3', '2023-10-10', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programs_based_budget`
--

CREATE TABLE `tbl_programs_based_budget` (
  `id` int NOT NULL,
  `progid` int NOT NULL,
  `opid` int DEFAULT NULL,
  `indid` int DEFAULT NULL,
  `finyear` int NOT NULL,
  `budget` double NOT NULL,
  `target` float DEFAULT NULL,
  `created_by` int NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_programs_based_budget`
--

INSERT INTO `tbl_programs_based_budget` (`id`, `progid`, `opid`, `indid`, `finyear`, `budget`, `target`, `created_by`, `date_created`) VALUES
(1, 4, 27, 8, 2023, 120000000, 9, 1, '2023-04-03'),
(2, 4, 23, 1, 2023, 317000000, 55, 1, '2023-04-03'),
(3, 4, 25, 2, 2023, 5000000, 10, 1, '2023-04-03'),
(5, 14, NULL, NULL, 2023, 21500000, NULL, 118, '2023-09-29');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programs_quarterly_targets`
--

CREATE TABLE `tbl_programs_quarterly_targets` (
  `id` int NOT NULL,
  `pbbid` int NOT NULL DEFAULT '0',
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
(25, 1, 4, 27, 8, 2023, 2, 2, 3, 2, 118, '2023-04-24'),
(26, 2, 4, 23, 1, 2023, 15, 15, 15, 10, 118, '2023-04-24'),
(27, 3, 4, 25, 2, 2023, 30, 29, 39, 20, 118, '2023-04-24'),
(28, 0, 14, 46, 8, 2023, 5, 5, 5, 5, 118, '2023-09-29');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_program_of_works`
--

CREATE TABLE `tbl_program_of_works` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL,
  `site_id` int NOT NULL DEFAULT '0',
  `task_id` int NOT NULL,
  `subtask_id` int NOT NULL,
  `start_date` date NOT NULL,
  `duration` int NOT NULL,
  `end_date` date NOT NULL,
  `status` int NOT NULL DEFAULT '3',
  `progress` float NOT NULL DEFAULT '0',
  `complete` int NOT NULL DEFAULT '0',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_program_of_works`
--

INSERT INTO `tbl_program_of_works` (`id`, `projid`, `output_id`, `site_id`, `task_id`, `subtask_id`, `start_date`, `duration`, `end_date`, `status`, `progress`, `complete`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 1, 377, '2023-03-17', 200, '2023-10-03', 4, 55.5556, 0, 118, 118, '2023-03-17', '2023-03-17'),
(2, 1, 1, 0, 2, 377, '2023-04-21', 500, '2024-09-02', 4, 14.6667, 0, 118, 118, '2023-03-17', '2023-03-17'),
(3, 1, 1, 0, 9, 377, '2023-04-20', 200, '2023-11-06', 4, 4.44444, 0, 118, 118, '2023-03-17', '2023-03-17'),
(4, 1, 2, 0, 3, 377, '2023-03-22', 100, '2023-06-30', 11, 0, 0, 118, 118, '2023-03-17', '2023-03-17'),
(5, 1, 2, 0, 4, 377, '2023-03-30', 400, '2024-05-03', 11, 0, 0, 118, 118, '2023-03-17', '2023-03-17'),
(6, 1, 3, 1, 5, 377, '2023-03-18', 100, '2023-06-26', 11, 0, 0, 118, NULL, '2023-03-17', NULL),
(7, 1, 3, 1, 6, 377, '2023-03-18', 120, '2023-07-16', 11, 0, 0, 118, NULL, '2023-03-17', NULL),
(8, 1, 3, 2, 5, 377, '2023-03-18', 100, '2023-06-26', 11, 0, 0, 118, NULL, '2023-03-17', NULL),
(9, 1, 3, 2, 6, 377, '2023-03-18', 120, '2023-07-16', 11, 0, 0, 118, NULL, '2023-03-17', NULL),
(10, 3, 5, 6, 7, 346, '2023-03-18', 20, '2023-04-07', 11, 10, 0, 118, NULL, '2023-03-18', NULL),
(11, 3, 5, 6, 8, 346, '2023-03-18', 40, '2023-04-27', 11, 6, 0, 118, NULL, '2023-03-18', NULL),
(12, 3, 5, 7, 7, 384, '2023-03-18', 20, '2023-04-07', 11, 0, 0, 118, NULL, '2023-03-18', NULL),
(13, 3, 5, 7, 8, 384, '2023-03-18', 40, '2023-04-27', 11, 0, 0, 118, NULL, '2023-03-18', NULL),
(14, 97, 56, 138, 108, 337, '2023-09-08', 84, '2023-12-01', 3, 0, 0, 1, NULL, '2023-09-08', NULL),
(15, 97, 56, 138, 108, 340, '2023-09-13', 444, '2024-11-30', 3, 0, 0, 1, NULL, '2023-09-08', NULL),
(16, 97, 56, 138, 108, 341, '2023-09-20', 444, '2024-12-07', 3, 0, 0, 1, NULL, '2023-09-08', NULL),
(17, 97, 56, 139, 108, 337, '2023-09-20', 55, '2023-11-14', 3, 0, 0, 1, NULL, '2023-09-08', NULL),
(18, 97, 56, 139, 108, 340, '2023-09-27', 5555, '2038-12-12', 3, 0, 0, 1, NULL, '2023-09-08', NULL),
(19, 97, 56, 139, 108, 341, '2023-10-07', 5555, '2038-12-22', 3, 0, 0, 1, NULL, '2023-09-08', NULL),
(20, 97, 56, 140, 108, 337, '2023-09-21', 4444, '2035-11-21', 3, 0, 0, 1, NULL, '2023-09-08', NULL),
(21, 97, 56, 140, 108, 340, '2023-09-20', 4444, '2035-11-20', 3, 0, 0, 1, NULL, '2023-09-08', NULL),
(22, 97, 56, 140, 108, 341, '2023-10-04', 44444, '2145-06-10', 3, 0, 0, 1, NULL, '2023-09-08', NULL),
(23, 96, 53, 0, 101, 323, '2023-09-13', 178, '2024-03-09', 3, 0, 0, 118, 118, '2023-09-12', '2023-09-12'),
(24, 96, 53, 0, 101, 323, '2023-09-13', 178, '2024-03-09', 3, 0, 0, 118, 118, '2023-09-12', '2023-09-12'),
(25, 96, 53, 0, 101, 323, '2023-09-13', 178, '2024-03-09', 3, 0, 0, 118, 118, '2023-09-12', '2023-09-12'),
(26, 96, 53, 0, 101, 323, '2023-09-13', 178, '2024-03-09', 3, 0, 0, 118, 118, '2023-09-12', '2023-09-12'),
(27, 96, 54, 136, 103, 317, '2023-09-13', 300, '2024-07-09', 3, 0, 0, 1, NULL, '2023-09-12', NULL),
(28, 96, 54, 136, 104, 318, '2023-09-27', 300, '2024-07-23', 3, 0, 0, 1, NULL, '2023-09-12', NULL),
(29, 96, 54, 136, 104, 319, '2023-09-28', 100, '2024-01-06', 3, 0, 0, 1, NULL, '2023-09-12', NULL),
(30, 96, 54, 136, 104, 320, '2023-09-27', 100, '2024-01-05', 3, 0, 0, 1, NULL, '2023-09-12', NULL),
(31, 96, 54, 137, 103, 317, '2023-09-21', 44, '2023-11-04', 3, 0, 0, 118, NULL, '2023-09-12', NULL),
(32, 96, 54, 137, 104, 318, '2023-09-28', 99, '2024-01-05', 3, 0, 0, 118, 1, '2023-09-12', '2023-09-12'),
(33, 96, 54, 137, 104, 319, '2023-09-29', 877, '2026-02-22', 3, 0, 0, 118, 1, '2023-09-12', '2023-09-12'),
(34, 96, 54, 137, 104, 320, '2023-09-22', 766, '2025-10-27', 3, 0, 0, 118, 1, '2023-09-12', '2023-09-12'),
(35, 96, 53, 0, 100, 321, '2023-10-06', 55, '2023-11-30', 3, 0, 0, 118, 1, '2023-09-12', '2023-09-12'),
(36, 96, 53, 0, 100, 322, '2023-09-23', 77, '2023-12-09', 3, 0, 0, 118, 1, '2023-09-12', '2023-09-12'),
(37, 96, 53, 0, 100, 321, '2023-10-06', 55, '2023-11-30', 3, 0, 0, 118, 1, '2023-09-12', '2023-09-12'),
(38, 96, 53, 0, 100, 322, '2023-09-23', 77, '2023-12-09', 3, 0, 0, 118, 1, '2023-09-12', '2023-09-12'),
(39, 101, 59, 144, 112, 348, '2023-09-14', 20, '2023-10-04', 3, 0, 0, 118, NULL, '2023-09-14', NULL),
(40, 101, 59, 144, 113, 349, '2023-09-14', 30, '2023-10-14', 3, 0, 0, 118, NULL, '2023-09-14', NULL),
(41, 84, 55, 115, 350, 350, '2023-09-14', 20, '2023-10-04', 3, 0, 0, 118, NULL, '2023-09-14', NULL),
(42, 84, 55, 115, 351, 351, '2023-09-14', 40, '2023-10-24', 3, 0, 0, 118, NULL, '2023-09-14', NULL),
(43, 101, 59, 144, 112, 348, '2023-09-14', 20, '2023-10-04', 3, 0, 0, 1, NULL, '2023-10-04', NULL),
(44, 101, 59, 144, 113, 349, '2023-09-14', 30, '2023-10-14', 3, 0, 0, 1, NULL, '2023-10-04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_program_of_work_comments`
--

CREATE TABLE `tbl_program_of_work_comments` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `comments` text NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_program_of_work_comments`
--

INSERT INTO `tbl_program_of_work_comments` (`id`, `projid`, `comments`, `created_by`, `created_at`) VALUES
(1, 101, 'Comments', 1, '2023-10-10');

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
  `catid` int NOT NULL,
  `type` int NOT NULL,
  `risk_description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projectrisks`
--

INSERT INTO `tbl_projectrisks` (`id`, `projid`, `catid`, `type`, `risk_description`) VALUES
(2, 3, 4, 2, 'Favourable'),
(3, 3, 2, 3, 'Timely funding'),
(4, 1, 2, 3, 'Finances will be sufficient'),
(5, 1, 5, 3, 'environmental conditions will be favourable'),
(6, 1, 5, 3, 'environmental conditions will be favourable'),
(8, 7, 4, 2, 'Favourable climatic conditions'),
(10, 2, 4, 1, 'Favourable climate'),
(11, 2, 4, 1, 'Favourable climate'),
(13, 6, 4, 1, 'Favourable climate'),
(14, 6, 4, 2, 'Favourable climate'),
(15, 6, 7, 1, 'Political stability'),
(16, 6, 7, 2, 'Political stability'),
(17, 6, 2, 3, 'Timely disbursement of funds'),
(18, 6, 2, 3, 'Timely disbursement of funds'),
(19, 2, 1, 2, 'Assumption 1'),
(20, 2, 4, 2, 'Modi eum error non m'),
(23, 69, 1, 2, 'Vero veniam esse co'),
(29, 69, 1, 3, 'Assumptions '),
(40, 69, 1, 1, 'Assumptions'),
(44, 69, 4, 3, 'Assumption 1'),
(45, 69, 2, 3, 'Assumption 3'),
(46, 69, 4, 1, 'Assumption '),
(50, 78, 3, 3, 'Fuga Perferendis di'),
(51, 78, 7, 3, 'Consectetur quia per'),
(65, 96, 2, 3, 'sufficient funds will be available '),
(66, 96, 5, 3, 'environmental conditions will be favourable'),
(69, 97, 3, 3, 'Low Turnover'),
(70, 97, 4, 3, 'Conducive Climatic Conditions'),
(71, 97, 4, 2, 'Conducive Climatic Conditions'),
(72, 97, 4, 2, 'Conducive Climatic Conditions'),
(74, 7, 2, 3, 'Timely funding'),
(75, 101, 2, 3, 'Timely funding'),
(76, 84, 2, 3, 'Timely funding');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projects`
--

CREATE TABLE `tbl_projects` (
  `projid` int NOT NULL,
  `progid` int DEFAULT NULL,
  `key_unique` varchar(255) NOT NULL,
  `projcode` varchar(255) NOT NULL,
  `projname` varchar(300) DEFAULT NULL,
  `projdesc` text,
  `projstatement` text,
  `projsolution` text,
  `projcase` text,
  `projfocusarea` varchar(255) DEFAULT NULL,
  `projmapping` int NOT NULL DEFAULT '1',
  `projinspection` int NOT NULL DEFAULT '1',
  `projevaluation` int NOT NULL DEFAULT '0',
  `projimpact` int NOT NULL,
  `projcategory` enum('1','2') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1' COMMENT '1 is In House while 2 is Contractor',
  `projbigfouragenda` int DEFAULT NULL,
  `projfscyear` int DEFAULT NULL,
  `projfinancier` int DEFAULT NULL,
  `projduration` int NOT NULL,
  `beneficiaries` text,
  `projstatus` int NOT NULL DEFAULT '0',
  `progress` float NOT NULL DEFAULT '0',
  `projplanstatus` int NOT NULL DEFAULT '0',
  `projchangedstatus` varchar(100) DEFAULT NULL,
  `projsectorname` varchar(255) DEFAULT NULL,
  `projappdate` date DEFAULT NULL,
  `projtender` int DEFAULT NULL,
  `projcontractor` int DEFAULT NULL,
  `projcommunity` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `projlga` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `projlocation` varchar(250) DEFAULT NULL,
  `projcounty` int DEFAULT NULL,
  `projbudget` double DEFAULT NULL,
  `projcost` decimal(19,2) DEFAULT '0.00',
  `mne_budget` double DEFAULT '0',
  `direct_cost` double NOT NULL DEFAULT '0',
  `administrative_cost` double NOT NULL DEFAULT '0',
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
  `monitoring_frequency` int DEFAULT NULL,
  `projstatuschangereason` text,
  `projstatusrestorereason` text,
  `projstage` int NOT NULL DEFAULT '0',
  `proj_substage` int NOT NULL DEFAULT '0',
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
  `approved_date` date DEFAULT NULL,
  `payment_plan` int NOT NULL DEFAULT '0' COMMENT '1: Monitoring, 2: Tasks, 3: Projects Percentage'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projects`
--

INSERT INTO `tbl_projects` (`projid`, `progid`, `key_unique`, `projcode`, `projname`, `projdesc`, `projstatement`, `projsolution`, `projcase`, `projfocusarea`, `projmapping`, `projinspection`, `projevaluation`, `projimpact`, `projcategory`, `projbigfouragenda`, `projfscyear`, `projfinancier`, `projduration`, `beneficiaries`, `projstatus`, `progress`, `projplanstatus`, `projchangedstatus`, `projsectorname`, `projappdate`, `projtender`, `projcontractor`, `projcommunity`, `projlga`, `projlocation`, `projcounty`, `projbudget`, `projcost`, `mne_budget`, `direct_cost`, `administrative_cost`, `projtype`, `projwaypoints`, `mapped`, `projstartdate`, `projenddate`, `projmonitoringdate`, `outcome`, `outcome_indicator`, `mne_responsible`, `mne_report_users`, `monitoring_frequency`, `projstatuschangereason`, `projstatusrestorereason`, `projstage`, `proj_substage`, `projmestage`, `projevaluate`, `projdatecompleted`, `user_name`, `date_created`, `updated_by`, `date_updated`, `deleted`, `date_deleted`, `deleted_by`, `approved_by`, `approved_date`, `payment_plan`) VALUES
(1, 1, 'FSi8I0kqDQ', '2022/003/RWC049', 'Tebeson- Mailli Nne Road', 'test', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 5, NULL, 650, NULL, 3, 8.33333, 1, NULL, NULL, NULL, 8, 5, '305', '331', NULL, NULL, NULL, '1020000000.00', 10000000, 0, 0, 'New', NULL, 0, '2022-07-01', '2024-02-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, 0, NULL, '124', '2023-03-15', NULL, NULL, '0', NULL, NULL, '1', '2023-03-15', 2),
(2, 2, 'hwtZpr18Ub', 'WT/122346', 'Drilling of boreholes', '', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 5, NULL, 200, NULL, 3, 0, 1, NULL, NULL, NULL, 25, 3, '303', '321,322', NULL, NULL, NULL, '20000000.00', 500000, 0, 0, 'New', NULL, 0, '2022-07-01', '2023-02-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 1, 1, 0, NULL, '121', '2023-03-15', NULL, NULL, '0', NULL, NULL, '1', '2023-03-15', 1),
(3, 2, 'xjUbtFQxUg', 'WTR/321567', 'Construction of water storage tanks', '', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 5, NULL, 150, NULL, 0, 4, 1, NULL, NULL, NULL, 1, 4, '304,307', '335,313', NULL, NULL, NULL, '41000000.00', 1000000, 0, 0, 'New', NULL, 0, '2022-07-01', '2022-12-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 0, 1, 0, NULL, '121', '2023-03-15', NULL, NULL, '0', NULL, NULL, '1', '2023-03-15', 1),
(4, 2, 'ZlXZYJzPMM', 'WR/0039', 'Construction of water kiosks', 'Construction of boreholes', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 5, NULL, 300, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '1', '2', NULL, NULL, NULL, '0.00', NULL, 0, 0, 'New', NULL, 0, '2022-07-01', '2023-04-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-03-17', '121', '2023-04-11', '0', NULL, NULL, NULL, NULL, 0),
(7, 2, 'GAJK7tK7aS', 'WP9000023', 'Laying of water pipes', '', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 5, NULL, 360, NULL, 3, 0, 1, NULL, NULL, NULL, 9, 4, '306', '310', NULL, NULL, NULL, '500000000.00', 1000000, 0, 0, 'New', NULL, 0, '2022-07-01', '2023-06-30', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 5, 1, 1, 0, NULL, '118', '2023-03-17', NULL, NULL, '0', NULL, NULL, '1', '2023-03-18', 1),
(15, 4, 'VWlkgNWOAN', '2022/02/RWC0098', 'Kapkenduiywo Project ', 'Tarmacked road, Bridge and Culverts', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 6, NULL, 540, NULL, 3, 0, 1, NULL, NULL, NULL, NULL, NULL, '306', '309,310', NULL, NULL, NULL, '240000000.00', 4000000, 0, 0, 'New', NULL, 0, '2023-07-01', '2025-01-01', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, 5, 1, 1, 0, NULL, '124', '2023-03-21', '124', '2023-03-21', '0', NULL, NULL, '1', '2023-04-03', 0),
(51, 4, '621WEzaUTf', '2022/12/BWC009', 'Kaptebee Bridge ', 'Bridge - 20 meter with guard rails and tarmarc 500m on both sides', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 6, NULL, 120, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '305', '333', NULL, NULL, NULL, '50000000.00', NULL, 0, 0, 'New', NULL, 0, '2022-07-01', '2022-11-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '124', '2023-03-21', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(52, 4, 'rrNWy45hU3', '2022/10/RWCOO12', 'Kamungei- Kipkenyo Road ', '5 km Tarmacked road ', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 6, NULL, 178, NULL, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '307', '312,313', NULL, NULL, NULL, '15000000.00', 1000000, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, NULL, '124', '2023-03-23', NULL, NULL, '0', NULL, NULL, '1', '2023-09-02', 0),
(53, 4, 'AHW9vtpuiK', '2022/08/CCOO8', 'Chuiyat Center Culverts', 'Construction of  the four identified Ring and Box Culverts within the center ', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 6, NULL, 200, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '308', '319', NULL, NULL, NULL, '40000000.00', NULL, 0, 0, 'New', NULL, 0, '2022-07-01', '2023-01-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '124', '2023-03-23', '124', '2023-03-23', '0', NULL, NULL, NULL, NULL, 0),
(54, 4, 'B30xbZg1Yh', '2022/09/BWC009', 'Kapkures Bridge ', 'Standard bridge construction - 20 M...TARMARC OF ROAD 10M ON BOTH ENDS OF THE BRIDGE ...CONSTRUCTION OF GUARD RAILS ', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '2', NULL, 6, NULL, 230, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '303', '322', NULL, NULL, NULL, '10000000.00', NULL, 0, 0, 'New', NULL, 0, '2022-07-01', '2023-02-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '124', '2023-03-23', '124', '2023-03-23', '0', NULL, NULL, NULL, NULL, 0),
(55, 4, 'xNAXavKqT3', '2022/T/WC052', 'Kimuri Roadworks', 'gravelling ', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '1', NULL, 6, NULL, 165, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '308', '318', NULL, NULL, NULL, '5000000.00', NULL, 0, 0, 'New', NULL, 0, '2022-07-01', '2022-12-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '124', '2023-03-23', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(56, 4, 'Eoi140KNVE', '2022/06/BWC089', 'Toloita Road', 'Gravel Road Works ', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 6, NULL, 200, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '304', '336', NULL, NULL, NULL, '60000000.00', NULL, 0, 0, 'New', NULL, 0, '2022-07-01', '2023-01-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '124', '2023-03-23', '118', '2023-03-24', '0', NULL, NULL, NULL, NULL, 0),
(58, 2, 'KgcMXZwbVe', '555555', 'SEWERAGE RELOCATION WORKS ALONG THE NAIROBI EXPRESSWAY', '', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '2', NULL, 5, NULL, 455, NULL, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '1', '2', NULL, NULL, NULL, '500000000.00', 215000934, 0, 0, 'New', NULL, 0, '2022-07-01', '2023-09-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 2, 1, 0, NULL, '118', '2023-04-01', '118', '2023-04-01', '0', NULL, NULL, '1', '2023-04-18', 0),
(59, 2, 'rwYx0yXrPk', 'WSS/973333', ': SEWERAGE RELOCATION WORKS ALONG THE NAIROBI EXPRESSWAY', ': SEWERAGE RELOCATION WORKS ALONG THE NAIROBI EXPRESSWAY', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 5, NULL, 300, NULL, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '1', '2', NULL, NULL, NULL, '200.00', 118, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-04-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 1, 1, 0, NULL, '118', '2023-04-01', NULL, NULL, '0', NULL, NULL, '1', '2023-04-18', 0),
(61, 3, 'onrlaJDk0V', 'code', 'name', 'test', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 5, NULL, 600, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '305,308', '329', NULL, NULL, NULL, '0.00', NULL, 0, 0, 'New', NULL, 0, '2022-07-01', '2024-02-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '1', '2023-04-10', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(62, 3, 'ULIRQxPl1Z', 'Quas quo sequi nihil', 'Britanney Rogers', 'Praesentium neque no', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 5, NULL, 600, NULL, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '303,305,307,308', '323,324,326', NULL, NULL, NULL, '1000.00', 879, 0, 0, 'New', NULL, 0, '2022-07-01', '2024-02-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 1, 0, NULL, '1', '2023-04-10', NULL, NULL, '0', NULL, NULL, '1', '2023-04-18', 0),
(66, 1, 'cVZqhbcC3u', 'Sed nostrud illum u', 'Lucas Morton', 'Esse velit quo iure', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 5, NULL, 13, NULL, 3, 0, 1, NULL, NULL, NULL, 13, 8, '303,304,306,308', '338', NULL, NULL, NULL, '120000.00', 10000, 0, 0, 'New', NULL, 0, '2022-07-01', '2022-07-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 2, 1, 0, NULL, '1', '2023-04-14', '1', '2023-04-14', '0', NULL, NULL, '1', '2023-05-05', 2),
(68, 3, '1vcspQT0GO', 'Possimus et et cons', 'Dalton Pugh', 'Ullamco neque impedi', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '1', NULL, 5, NULL, 690, 'Id ,saepe ,aliquid, pro', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '305,306,307', '333', NULL, NULL, NULL, '0.00', NULL, 0, 0, 'New', NULL, 0, '2022-07-01', '2024-05-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '1', '2023-05-03', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(69, 2, '5vAmYsQc3r', ' 2018/12/AB23', 'Testing project name', 'Description', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 5, NULL, 700, 'ben 1, ben 2, ben 3, ben 4', 3, 0, 1, NULL, NULL, NULL, 14, 1, '307', '312', NULL, NULL, NULL, '42000000.00', 4200000, 0, 0, 'New', NULL, 0, '2022-07-01', '2024-06-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, 0, NULL, '1', '2023-05-11', NULL, NULL, '0', NULL, NULL, '1', '2023-05-11', 2),
(70, 1, 'LOEb1fECU7', '22990/WQ009', 'Kapkeben- Bondeni Road', '20 KM Road and a bridge ', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '2', NULL, 5, NULL, 300, 'Neigbouring community and road users', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '305', '331', NULL, NULL, NULL, '70000000.00', 3000000, 0, 0, 'New', NULL, 0, '2022-07-01', '2023-04-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, NULL, '124', '2023-05-30', NULL, NULL, '0', NULL, NULL, '124', '2023-05-30', 0),
(71, 3, 'oxH7TzGQFE', '12337', 'Testing', 'Testing', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 5, NULL, 730, 'Tester', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '307', '315', NULL, NULL, NULL, '70000000.00', 7000000, 0, 0, 'New', NULL, 0, '2022-07-01', '2024-06-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 1, 0, NULL, '118', '2023-05-30', '118', '2023-05-30', '0', NULL, NULL, '118', '2023-05-30', 0),
(72, 2, 'S6jORghkYv', '73456', 'Water Project', '', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 5, NULL, 200, '', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '305', '329', NULL, NULL, NULL, '9999998.00', 1000000, 7999998, 1000000, 'New', NULL, 0, '2022-07-01', '2023-01-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, NULL, '118', '2023-05-31', NULL, NULL, '0', NULL, NULL, '1', '2023-08-24', 0),
(73, 4, 'yZpIdEZ10J', 'Sit placeat incidu', 'Aquila Madden', 'Et voluptatum tempor', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 6, NULL, 300, 'Et ea dolorum eos e', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '305,307,308', '330', NULL, NULL, NULL, '0.00', NULL, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-04-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-06-10', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(74, 10, 'grs4zZ4fbX', 'BR/306661636', 'Drilling of boreholes Project', 'The project will address the water shortage in the area', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 5, NULL, 300, 'Community A', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '307', '313', NULL, NULL, NULL, '50000000.00', 1000000, 0, 0, 'New', NULL, 0, '2022-07-01', '2023-04-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 1, 0, NULL, '118', '2023-06-10', NULL, NULL, '0', NULL, NULL, '118', '2023-06-12', 0),
(75, 9, 's55pZXfG9W', 'Minus qui recusandae', 'Daniel Wagner', 'Ut harum omnis corpo', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 6, NULL, 350, 'Labore unde quis lib', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '304,308', '336,338,319', NULL, NULL, NULL, '1000000.00', 100000, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-06-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 1, 0, NULL, '1', '2023-08-02', NULL, NULL, '0', NULL, NULL, '1', '2023-08-02', 0),
(76, 9, '9u90JNqRjm', 'Voluptas facilis dig', 'Jessamine Whitehead', 'Voluptas minima dolo', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 6, NULL, 200, 'Magna ipsum eos acc', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, '306,308', '317,319', NULL, NULL, NULL, '2000000.00', 100, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-01-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, NULL, '118', '2023-08-02', NULL, NULL, '0', NULL, NULL, '118', '2023-08-14', 0),
(77, 1, 'NgolZqjA8v', '6790GT', 'Kapsuben Road Construction project', 'test', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '1', NULL, 6, NULL, 156, 'Farmers and other road users', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, '305', '333', NULL, NULL, NULL, '50000100.00', 3000000, 0, 0, 'New', NULL, 0, '2023-07-01', '2023-12-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, NULL, '118', '2023-08-02', NULL, NULL, '0', NULL, NULL, '118', '2023-08-02', 0),
(78, 1, 'tIWZztgaTX', 'TR/09/TH5Y7', 'Kimugul Road Construction Project', 'TEST', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 5, NULL, 120, '', 11, 0, 1, NULL, NULL, NULL, 15, 1, '303', '321,322', NULL, NULL, NULL, '300000000.00', 10000000, 0, 0, 'New', NULL, 0, '2022-07-01', '2022-10-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 0, 1, 0, NULL, '118', '2023-08-03', NULL, NULL, '0', NULL, NULL, '118', '2023-08-03', 1),
(79, 1, 'oImnDqM0QV', '890/RWC/09', 'Kamungei Road Construction Project ', 'test', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 5, NULL, 120, 'Road users', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '307', '312', NULL, NULL, NULL, '66000000.00', 2500000, 60000000, 3500000, 'New', NULL, 0, '2022-07-01', '2022-10-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 1, 0, NULL, '118', '2023-08-07', '118', '2023-08-07', '0', NULL, NULL, '118', '2023-08-14', 0),
(80, 1, 'L2qcQMGofg', '', 'Chepkigen Road Construction Project', 'test', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '1', NULL, 6, NULL, 200, 'test', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '308', '319', NULL, NULL, NULL, '0.00', NULL, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-01-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-08-07', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(81, 11, 'TLVvNyyn5J', 'AG-2308003', 'Training Farmer on ABC Farming Method', 'Training Farmer on ABC Farming Method', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 6, NULL, 300, '500 Farmers within the county', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '1,303,304,305,306,307,308', '2,321,322,323,324,325,326,327,334,335,336,337,338,328,329,330,331,332,333,309,310,311,312,313,314,315,316,317,318,319,320', NULL, NULL, NULL, '30000000.00', 1500000, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-04-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 1, 0, NULL, '118', '2023-08-07', NULL, NULL, '0', NULL, NULL, '118', '2023-08-07', 0),
(82, 2, 'kbPDExHFLh', 'WT009356', 'Construction of water storage tanks at Sosiani', 'Construction of water Kiosks at Sosiani', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '2', NULL, 5, NULL, 360, 'Residents of Dandora', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '305', '329', NULL, NULL, NULL, '50000000.00', 1000000, 0, 0, 'New', NULL, 0, '2022-07-01', '2023-06-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 1, 0, NULL, '118', '2023-08-08', '118', '2023-08-08', '0', NULL, NULL, '118', '2023-08-08', 0),
(83, 12, '7q0NgFM0MC', 'UBA001', 'Construction of Vertical Flow Wetlands ', 'Construction of Vertical Flow Wetlands ', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 6, NULL, 200, '', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, '303', '327', NULL, NULL, NULL, '2000000.00', 200000, 1600000, 200000, 'New', NULL, 0, '2023-07-01', '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, NULL, '118', '2023-08-09', '118', '2023-08-23', '0', NULL, NULL, '118', '2023-09-14', 0),
(84, 12, 'WWpkgR1rTc', 'RCT001', 'Construction of a Solid Waste Incinerator', 'Construction of a Solid Waste Incinerator', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '1', NULL, 6, NULL, 200, '', 4, 0, 1, NULL, NULL, NULL, NULL, NULL, '305', '333', NULL, NULL, NULL, '1000000.00', 50000, 900000, 50000, 'New', NULL, 0, '2023-07-01', '1970-01-01', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 9, 0, 1, 0, NULL, '118', '2023-08-09', NULL, NULL, '0', NULL, NULL, '118', '2023-09-14', 0),
(85, 12, 'bJtLMRcyXQ', 'AR003', 'Construction of Anaerobic Reactor', 'Construction of Anaerobic Reactor', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 6, NULL, 200, '', 4, 0, 1, NULL, NULL, NULL, NULL, NULL, '306', '309', NULL, NULL, NULL, '10000000.00', 800000, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-01-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 2, 1, 0, NULL, '118', '2023-08-09', NULL, NULL, '0', NULL, NULL, '118', '2023-08-09', 0),
(86, 13, 'TAEOEOUmz5', '2023/08/WsuT', 'Kapkin Hospital immunization center', 'Construction of Kapkin Hospital immunization center', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 4, NULL, 20, 'Residents of uasingishu county', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '307', '313', NULL, NULL, NULL, '1000000.00', NULL, 0, 0, 'New', NULL, 0, '2021-07-01', '2021-07-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-08-12', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(87, 13, 'zTG4DUvCqB', '2023/08/wTYU', 'Kamukunji Hospital Immunization Center', 'Kamukunji Hospital Immunization Center Construction', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 5, NULL, 10, 'Residents of Oletepes', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '307', '316', NULL, NULL, NULL, '10000000.00', NULL, 0, 0, 'New', NULL, 0, '2022-07-01', '2022-07-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-08-12', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(88, 14, '3WjADBEccC', '2023/18/W9AYE', 'Toror Hospital Immunization Center', 'Construction Toror Hospital Immunization Center', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 6, NULL, 250, 'Kerotet residents', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '303', '327', NULL, NULL, NULL, '20000000.00', NULL, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-03-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-08-12', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(89, 15, 'y1lfFIxza0', '2023/08/HEj7', 'Kaplelach River Bridge', 'Construction of bridge along Kaplelach river', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 6, NULL, 550, '', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '307', '316', NULL, NULL, NULL, '9999995.00', 1000000, 0, 0, 'New', NULL, 0, '2023-07-01', '2025-01-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 1, 0, NULL, '118', '2023-08-12', NULL, NULL, '0', NULL, NULL, '118', '2023-08-12', 0),
(90, 16, 'ZgHR9B8fuH', '2023/17/YISA', 'Bin Distribution in Kimpsomba', 'Bin Distribution in Kimpsomba Location', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 6, NULL, 200, 'Residents of Kimpsomba', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '303', '321', NULL, NULL, NULL, '0.00', NULL, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-01-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-08-14', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(91, 16, 'cuepEVj5V9', '2023/12/IYWI', 'Distribution of Bins in Kapkures', 'Distribution of Dust Bins in Kapkures', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 6, NULL, 200, 'Residents of Kapkures', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, '303', '322', NULL, NULL, NULL, '2000000.00', 500000, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-01-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 1, 0, NULL, '118', '2023-08-14', NULL, NULL, '0', NULL, NULL, '118', '2023-08-14', 0),
(93, 11, 'AXcBjiOMW4', 'TR/0092345', 'Training of farmers in the county', '', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 6, NULL, 360, '', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, '303,304,305,306,307,308', '321,322,323,324,325,326,327,334,335,336,337,338,328,329,330,331,332,333,309,310,311,312,313,314,315,316,317,318,319,320', NULL, NULL, NULL, '1000000.00', 800000, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-06-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, NULL, '118', '2023-08-18', NULL, NULL, '0', NULL, NULL, '118', '2023-08-18', 0),
(94, 1, 'Sve871xHqm', '2018/12/AB23', 'Project Name', 'Description', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 5, NULL, 700, '', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, '303', '321,322', NULL, NULL, NULL, '3000000.00', 500000, 0, 0, 'New', NULL, 0, '2022-07-01', '2024-05-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 1, 0, NULL, '1', '2023-08-19', '1', '2023-08-19', '0', NULL, NULL, '1', '2023-08-19', 0),
(95, 1, 'hOSj3SufS9', 'T/RW/C089', 'Bondeni Road Project', 'test', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '1', NULL, 5, NULL, 200, 'test', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, '304', '335,338', NULL, NULL, NULL, '56000000.00', 4500000, 0, 0, 'New', NULL, 0, '2022-07-01', '2023-01-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 2, 1, 0, NULL, '118', '2023-08-21', NULL, NULL, '0', NULL, NULL, '118', '2023-08-21', 0),
(96, 1, 'HO03OC5cCZ', 'RW/2022/C09', 'Kolelach Road works ', 'test', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 5, NULL, 250, 'test', 1, 0, 1, NULL, NULL, NULL, 18, 5, '303', '321', NULL, NULL, NULL, '603000000.00', 2000000, 598000000, 3000000, 'New', NULL, 0, '2022-07-01', '2023-03-10', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, 5, 0, 1, 0, NULL, '118', '2023-08-24', NULL, NULL, '0', NULL, NULL, '118', '2023-08-24', 1),
(97, 2, 'u6lowGwJ0U', 'DR/7644', 'Boreholes Drilling ', 'Boreholes Drilling ', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '2', NULL, 6, NULL, 200, 'Testing ', 1, 0, 1, NULL, NULL, NULL, 16, 2, '308', '317', NULL, NULL, NULL, '60000000.00', 1000000, 54000000, 5000000, 'New', NULL, 0, '2023-07-01', '2024-01-20', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 7, 0, 1, 0, NULL, '118', '2023-09-03', NULL, NULL, '0', NULL, NULL, '118', '2023-09-03', 2),
(98, 11, 'F0MG3frYuQ', 'AG12345', 'Training farmers on XYZ farming method', 'Training farmers on XYZ farming method', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 6, NULL, 700, 'Farmers', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '303,304', '326,327,336,337', NULL, NULL, NULL, '5000000.00', 500000, 3500000, 1000000, 'New', NULL, 0, '2023-07-01', '2025-05-31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 0, 1, 0, NULL, '118', '2023-09-04', NULL, NULL, '0', NULL, NULL, '118', '2023-09-04', 0),
(99, 11, '85762XUyQu', 'ToT123', 'FARMING AS BUSINESS TRAINING OF TRAINERS', 'FARMING AS BUSINESS TRAINING OF TRAINERS', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '1', NULL, 7, NULL, 10, 'Farmers', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '306', '309', NULL, NULL, NULL, '0.00', 0, 0, 0, 'New', NULL, 0, '2024-07-01', '2024-07-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-09-04', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(100, 2, 'K1ISaJJSer', 'TW/00700', ' CONSTRUCTION OF 3 NO. 24M3 ELEVATED PRESSED STEEL TANKS ON 18M STEEL TOWER', ' CONSTRUCTION OF 3NO. 24M3 ELEVATED PRESSED STEEL TANKS ON 18M STEEL TOWER', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '2', NULL, 5, NULL, 60, '', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '307', '312', NULL, NULL, NULL, '32000000.00', 1000000, 30000000, 1000000, 'New', NULL, 0, '2022-07-01', '2022-09-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 1, 0, NULL, '118', '2023-09-06', NULL, NULL, '0', NULL, NULL, '118', '2023-09-06', 0),
(101, 12, 'vkPmOPwFqX', 'AT/9000', ' Construction of Anaerobic Reactor', 'Construction of Anaerobic Reactor', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 6, NULL, 200, '', 4, 0, 1, NULL, NULL, NULL, 19, 1, '303', '324', NULL, NULL, NULL, '5000000.00', 500000, 4000000, 500000, 'New', NULL, 0, '2023-07-01', '1970-01-01', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 10, 3, 1, 0, NULL, '118', '2023-09-14', '118', '2023-09-14', '0', NULL, NULL, '118', '2023-09-14', 2),
(102, 12, 'jd83uNtwsR', '777777/KK', 'Construction of Vertical Flow Wetlands', 'Construction of Vertical Flow Wetlands', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 6, NULL, 90, '', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '308', '318', NULL, NULL, NULL, '0.00', 0, 0, 0, 'New', NULL, 0, '2023-07-01', '2023-09-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-09-14', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(104, 9, 'lvKRPmglA6', 'RD/4567', 'Upgrading of Sobea-Njoro Road', '', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '2', NULL, 6, NULL, 300, 'Community', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '308', '320', NULL, NULL, NULL, '0.00', 0, 0, 0, 'New', NULL, 0, '2023-07-01', '2024-04-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-09-23', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(105, 1, 'qh2HEcaEts', '2023/09/RWC009', 'Kapteren Road', 'Test', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 6, NULL, 165, 'Road users', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '305', '331', NULL, NULL, NULL, '0.00', 0, 0, 0, 'New', NULL, 0, '2023-07-01', '2023-12-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-09-28', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(106, 13, 'Kgzn6hlzuu', 'Quis temporibus vel ', 'Xanthus Stanley', 'Repellendus Adipisc', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 4, NULL, 600, 'Test 1, Test 2', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '303', '322,323', NULL, NULL, NULL, NULL, 0, 0, 0, 'New', NULL, 0, '2021-07-01', '2023-02-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '1', '2023-09-28', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(107, 13, 'uEdj6wyGub', 'Voluptas ab soluta i', 'Donna Mcmillan', 'Suscipit fuga Volup', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '2', NULL, 4, NULL, 700, 'Test 1', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '303,306', '322,325', NULL, NULL, NULL, '36.00', 0, 0, 0, 'New', NULL, 0, '2021-07-01', '2023-06-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '1', '2023-09-28', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(108, 13, 'T7iYCYGgIQ', 'Dolore dolore sunt ', 'Edward Horn', 'Ad illo in magni lab', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 4, NULL, 200, 'Voluptatibus et fugi', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '303,305,307,308', '321,322,323', NULL, NULL, NULL, '900009.00', 0, 0, 0, 'New', NULL, 0, '2021-07-01', '2022-01-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '1', '2023-09-28', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(109, 1, 'P9w0dm4qqR', '908/99', 'Matumaini Road', 'Test', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 6, NULL, 150, 'test', 3, 0, 1, NULL, NULL, NULL, 26, 3, '303,305', '321,331', NULL, NULL, NULL, '50000000.00', 4000000, 40000000, 6000000, 'New', NULL, 0, '2023-07-01', '1970-01-01', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, 10, 0, 1, 0, NULL, '118', '2023-09-28', '118', '2023-09-28', '0', NULL, NULL, '118', '2023-09-28', 1),
(110, 13, '66FBYYWcTn', 'kua92', 'Projo', 'Project Description', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 4, NULL, 200, 'Beneficiaries ,Beneficiaries ', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '1,303,304,305,306,307,308', '2,321,322,323,324,325,326,327,334,335,336,337,338,328,329,330,331,332,333,309,310,311,312,313,314,315,316,317,318,319,320', NULL, NULL, NULL, '100000.00', 0, 0, 0, 'New', NULL, 0, '2021-07-01', '2022-01-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-10-02', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(111, 17, '7YQV9lTeUE', 'RWC/0018', 'Kilele Bridge ', 'test', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 6, NULL, 150, 'Road Users', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '304', '338', NULL, NULL, NULL, '5000000.00', 0, 0, 0, 'New', NULL, 0, '2023-07-01', '2023-11-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-10-03', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(112, 17, 'hvXkdHxvX4', '26/RW/09/WG', 'Kormaet Bridge', 'Test', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '1', NULL, 6, NULL, 150, '', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '305', '332', NULL, NULL, NULL, '6000000.00', 0, 0, 0, 'New', NULL, 0, '2023-07-01', '2023-11-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-10-06', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(113, 17, 'XqK5MrpuLU', '98/BWX/00098', 'Sirende Kapkoi Bridge', 'Test', NULL, NULL, NULL, NULL, 1, 1, 0, 0, '2', NULL, 6, NULL, 160, 'Road users', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '303', '324', NULL, NULL, NULL, '6000000.00', 0, 0, 0, 'New', NULL, 0, '2023-07-01', '2023-12-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-10-10', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0),
(114, 17, 'ikiQMFzvwZ', 'NG/BWC/009/2021', 'Samoo Bridge ', 'test', NULL, NULL, NULL, NULL, 1, 1, 1, 0, '1', NULL, 6, NULL, 180, 'Road users', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '305', '332', NULL, NULL, NULL, '7000000.00', 0, 0, 0, 'New', NULL, 0, '2023-07-01', '2023-12-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-10-10', '118', '2023-10-10', '0', NULL, NULL, NULL, NULL, 0),
(116, 18, 'Uzs8kN8HrX', 'ECDE1234567', 'Construction of ECDE Classrooms', '', NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2', NULL, 6, NULL, 700, 'ECDE Pupils', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '306', '310', NULL, NULL, NULL, '12000000.00', 0, 0, 0, 'New', NULL, 0, '2023-07-01', '2025-05-31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, NULL, '118', '2023-10-10', NULL, NULL, '0', NULL, NULL, NULL, NULL, 0);

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
(1, 1, 2022, 1020000000, 1, '2023-03-15'),
(2, 2, 2022, 20000000, 1, '2023-03-15'),
(3, 3, 2022, 41000000, 1, '2023-03-15'),
(4, 5, 2022, 1000000000, 1, '2023-03-18'),
(5, 6, 2022, 1100000000, 1, '2023-03-18'),
(6, 7, 2022, 500000000, 1, '2023-03-18'),
(7, 15, 2022, 240000000, 1, '2023-04-03'),
(8, 58, 2022, 500000000, 1, '2023-04-18'),
(9, 59, 2022, 200, 1, '2023-04-18'),
(10, 62, 2022, 1000, 1, '2023-04-18'),
(11, 66, 2022, 120000, 1, '2023-05-05'),
(12, 69, 2022, 42000000, 1, '2023-05-11'),
(13, 70, 2022, 70000000, 124, '2023-05-30'),
(14, 71, 2022, 70000000, 118, '2023-05-30'),
(15, 74, 2022, 50000000, 118, '2023-06-12'),
(16, 75, 2023, 1000000, 1, '2023-08-02'),
(17, 77, 2023, 50000100, 118, '2023-08-02'),
(18, 78, 2023, 300000000, 118, '2023-08-03'),
(19, 79, 2023, 66000000, 118, '2023-08-07'),
(20, 81, 2023, 30000000, 118, '2023-08-07'),
(21, 82, 2023, 50000000, 118, '2023-08-08'),
(22, 85, 2023, 10000000, 118, '2023-08-09'),
(23, 89, 2023, 9999995, 118, '2023-08-12'),
(24, 76, 2023, 2000000, 118, '2023-08-14'),
(25, 76, 2023, 2000000, 118, '2023-08-14'),
(26, 76, 2023, 2000000, 118, '2023-08-14'),
(27, 76, 2023, 2000000, 118, '2023-08-14'),
(28, 76, 2023, 2000000, 118, '2023-08-14'),
(29, 76, 2023, 2000000, 118, '2023-08-14'),
(30, 76, 2023, 2000000, 118, '2023-08-14'),
(31, 76, 2023, 2000000, 118, '2023-08-14'),
(32, 76, 2023, 2000000, 118, '2023-08-14'),
(33, 76, 2023, 2000000, 118, '2023-08-14'),
(34, 76, 2023, 2000000, 118, '2023-08-14'),
(35, 76, 2023, 2000000, 118, '2023-08-14'),
(36, 76, 2023, 2000000, 118, '2023-08-14'),
(37, 76, 2023, 2000000, 118, '2023-08-14'),
(38, 76, 2023, 2000000, 118, '2023-08-14'),
(39, 76, 2023, 2000000, 118, '2023-08-14'),
(40, 76, 2023, 2000000, 118, '2023-08-14'),
(41, 76, 2023, 2000000, 118, '2023-08-14'),
(42, 79, 2023, 66000000, 118, '2023-08-14'),
(43, 91, 2023, 2000000, 118, '2023-08-14'),
(44, 93, 2023, 1000000, 118, '2023-08-18'),
(45, 94, 2023, 3000000, 1, '2023-08-19'),
(46, 95, 2023, 56000000, 118, '2023-08-21'),
(47, 72, 2023, 9999998, 1, '2023-08-24'),
(48, 72, 2023, 9999998, 1, '2023-08-24'),
(49, 72, 2023, 9999998, 1, '2023-08-24'),
(50, 72, 2023, 9999998, 1, '2023-08-24'),
(51, 96, 2023, 603000000, 118, '2023-08-24'),
(52, 52, 2023, 15000000, 1, '2023-09-02'),
(53, 97, 2023, 60000000, 118, '2023-09-03'),
(54, 98, 2023, 5000000, 118, '2023-09-04'),
(55, 100, 2023, 32000000, 118, '2023-09-06'),
(56, 101, 2023, 5000000, 118, '2023-09-14'),
(57, 84, 2023, 1000000, 118, '2023-09-14'),
(58, 83, 2023, 2000000, 118, '2023-09-14'),
(59, 109, 2023, 0, 118, '2023-09-28');

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_cost_funders_share`
--

CREATE TABLE `tbl_project_cost_funders_share` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `design_id` int DEFAULT NULL,
  `site_id` int DEFAULT NULL,
  `task_id` int DEFAULT NULL,
  `type` int NOT NULL,
  `plan_id` varchar(255) NOT NULL,
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

INSERT INTO `tbl_project_cost_funders_share` (`id`, `projid`, `outputid`, `design_id`, `site_id`, `task_id`, `type`, `plan_id`, `funder`, `amount`, `created_by`, `date_created`, `update_by`, `date_updated`) VALUES
(5, 3, 0, 0, 0, 0, 1, '16788874983', 2, 1000000, '118', '2023-03-15', NULL, NULL),
(6, 3, 5, 4, 7, 7, 1, '16788875813', 2, 5000000, '118', '2023-03-15', NULL, NULL),
(7, 3, 5, 4, 7, 8, 1, '16788876293', 2, 14500000, '118', '2023-03-15', NULL, NULL),
(8, 3, 5, 4, 6, 8, 1, '16788876973', 2, 14500000, '118', '2023-03-15', NULL, NULL),
(9, 3, 5, 4, 6, 7, 1, '16788877293', 2, 5000000, '118', '2023-03-15', NULL, NULL),
(10, 1, 1, 1, 0, 1, 1, '16788936671', 2, 90000000, '118', '2023-03-15', NULL, NULL),
(11, 1, 1, 1, 0, 2, 1, '16788937081', 2, 26000000, '118', '2023-03-15', NULL, NULL),
(12, 1, 1, 1, 0, 9, 1, '16788938881', 2, 47600000, '118', '2023-03-15', NULL, NULL),
(13, 1, 2, 2, 0, 3, 1, '16788941751', 2, 113000000, '118', '2023-03-15', NULL, NULL),
(14, 1, 2, 2, 0, 4, 1, '16788942591', 2, 22000000, '118', '2023-03-15', NULL, NULL),
(15, 2, 0, 0, 0, 0, 1, '16788944162', 2, 1000000, '118', '2023-03-15', NULL, NULL),
(16, 1, 3, 3, 1, 5, 1, '16788944301', 2, 15000000, '118', '2023-03-15', NULL, NULL),
(18, 2, 4, 9, 3, 10, 1, '16788945172', 2, 1000000, '118', '2023-03-15', NULL, NULL),
(19, 1, 3, 3, 1, 6, 1, '16788945511', 2, 58500000, '118', '2023-03-15', NULL, NULL),
(21, 2, 4, 9, 3, 11, 1, '16788945892', 2, 5500000, '118', '2023-03-15', NULL, NULL),
(22, 2, 4, 9, 4, 10, 1, '16788946422', 2, 1000000, '118', '2023-03-15', NULL, NULL),
(23, 2, 4, 9, 4, 11, 1, '16788946962', 2, 5500000, '118', '2023-03-15', NULL, NULL),
(24, 1, 3, 3, 2, 5, 1, '16788947211', 2, 39000000, '118', '2023-03-15', NULL, NULL),
(25, 2, 4, 9, 5, 10, 1, '16788947362', 2, 1000000, '118', '2023-03-15', NULL, NULL),
(26, 2, 4, 9, 5, 11, 1, '16788948242', 2, 4500000, '118', '2023-03-15', NULL, NULL),
(27, 1, 3, 3, 2, 6, 1, '16788948311', 2, 105440000, '118', '2023-03-15', NULL, NULL),
(34, 3, 0, NULL, NULL, NULL, 1, 'B', 2, 300000, '118', '2023-03-15', NULL, NULL),
(35, 3, 0, NULL, NULL, NULL, 1, 'A', 2, 400000, '118', '2023-03-15', NULL, NULL),
(36, 3, 0, NULL, NULL, NULL, 1, 'C', 2, 300000, '118', '2023-03-15', NULL, NULL),
(38, 1, 0, 0, 0, 0, 1, '16789566271', 2, 493460000, '124', '2023-03-16', NULL, NULL),
(39, 1, 0, NULL, NULL, NULL, 1, 'B', 2, 3800000, '118', '2023-03-16', NULL, NULL),
(41, 1, 0, NULL, NULL, NULL, 1, 'A', 2, 6200000, '118', '2023-03-16', NULL, NULL),
(42, 7, 0, 0, 0, 0, 1, '16791466137', 2, 5000000, '118', '2023-03-18', NULL, NULL),
(43, 7, 10, 10, 0, 12, 1, '16791467457', 2, 150000000, '118', '2023-03-18', NULL, NULL),
(44, 7, 10, 10, 0, 13, 1, '16791467827', 2, 160000000, '118', '2023-03-18', NULL, NULL),
(45, 7, 10, 10, 0, 14, 1, '16791468877', 2, 184000000, '118', '2023-03-18', NULL, NULL),
(46, 7, 0, NULL, NULL, NULL, 1, 'B', 2, 300000, '118', '2023-03-18', NULL, NULL),
(47, 7, 0, NULL, NULL, NULL, 1, 'A', 2, 350000, '118', '2023-03-18', NULL, NULL),
(48, 7, 0, NULL, NULL, NULL, 1, 'C', 2, 350000, '118', '2023-03-18', NULL, NULL),
(49, 6, 0, 0, 0, 0, 1, '16793882976', 2, 99000000, '118', '2023-03-21', NULL, NULL),
(50, 6, 8, 11, 11, 15, 1, '16793884136', 2, 150000000, '118', '2023-03-21', NULL, NULL),
(51, 6, 9, 12, 0, 17, 1, '16793884456', 2, 150000000, '118', '2023-03-21', NULL, NULL),
(52, 6, 8, 11, 11, 16, 1, '16793885436', 2, 500000000, '118', '2023-03-21', NULL, NULL),
(53, 6, 9, 12, 0, 18, 1, '16793886346', 2, 200000000, '118', '2023-03-21', NULL, NULL),
(54, 5, 6, 13, 10, 19, 1, '16803462515', 2, 200000000, '118', '2023-04-01', NULL, NULL),
(55, 5, 6, 13, 10, 21, 1, '16803462985', 2, 350000000, '118', '2023-04-01', NULL, NULL),
(56, 5, 7, 14, 10, 22, 1, '16803463525', 2, 200000000, '118', '2023-04-01', NULL, NULL),
(57, 5, 7, 14, 10, 23, 1, '16803464355', 2, 248500000, '118', '2023-04-01', NULL, NULL),
(58, 5, 0, 0, 0, 0, 1, '16803464965', 2, 500000, '118', '2023-04-01', NULL, NULL),
(59, 66, 24, 19, 0, 49, 1, '168327487866', 1, 40000, '1', '2023-05-05', NULL, NULL),
(60, 66, 24, 19, 0, 50, 1, '168327492566', 1, 20000, '1', '2023-05-05', NULL, NULL),
(64, 66, 0, 0, 0, 0, 1, '168327683266', 1, 50000, '1', '2023-05-05', NULL, NULL),
(65, 69, 0, 0, 0, 0, 1, '168424010769', 2, 4800000, '1', '2023-05-16', NULL, NULL),
(66, 69, 26, 21, 65, 53, 1, '168424061069', 2, 20000000, '1', '2023-05-16', NULL, NULL),
(67, 69, 26, 21, 65, 54, 1, '168424065869', 1, 1000000, '1', '2023-05-16', NULL, NULL),
(68, 69, 26, 21, 67, 53, 1, '168424074069', 1, 1000000, '1', '2023-05-16', NULL, NULL),
(69, 69, 26, 21, 67, 54, 1, '168424092369', 2, 1000000, '1', '2023-05-16', NULL, NULL),
(70, 69, 25, 20, 65, 51, 1, '168424129969', 2, 5000000, '1', '2023-05-16', NULL, NULL),
(71, 69, 25, 20, 65, 52, 1, '168424134369', 2, 2000000, '1', '2023-05-16', NULL, NULL),
(72, 69, 25, 20, 67, 52, 1, '168424138569', 2, 1000000, '1', '2023-05-16', NULL, NULL),
(73, 69, 25, 20, 67, 51, 1, '168424143569', 2, 100000, '1', '2023-05-16', NULL, NULL),
(74, 69, 25, 20, 66, 52, 1, '168424148269', 2, 20000, '1', '2023-05-16', NULL, NULL),
(75, 69, 25, 20, 66, 51, 1, '168424174069', 2, 1880000, '1', '2023-05-16', NULL, NULL),
(79, 69, 0, NULL, NULL, NULL, 1, 'B', 2, 1000000, '1', '2023-05-17', NULL, NULL),
(80, 69, 0, NULL, NULL, NULL, 1, 'A', 2, 1200000, '1', '2023-05-17', NULL, NULL),
(81, 69, 0, NULL, NULL, NULL, 1, 'C', 2, 2000000, '1', '2023-05-17', NULL, NULL),
(84, 78, 38, NULL, 92, 161, 1, '169141857478', 1, 4242, '1', '2023-08-07', NULL, NULL),
(87, 78, 0, NULL, 0, 0, 1, '169141892778', 1, 2823, '1', '2023-08-07', NULL, NULL),
(88, 78, 38, NULL, 92, 159, 1, '169142083878', 1, 9763, '1', '2023-08-07', NULL, NULL),
(90, 78, 38, NULL, 92, 154, 1, '169142094178', 1, 8543, '1', '2023-08-07', NULL, NULL),
(91, 78, 38, NULL, 92, 160, 1, '169142139678', 1, 39005491, '1', '2023-08-07', NULL, NULL),
(93, 78, 37, NULL, 98, 153, 1, '169142217078', 1, 1619, '1', '2023-08-07', NULL, NULL),
(94, 78, 0, NULL, NULL, NULL, 1, 'A', 1, 5000000, '1', '2023-08-08', NULL, NULL),
(95, 78, 0, NULL, NULL, NULL, 1, 'B', 1, 5000000, '1', '2023-08-08', NULL, NULL),
(140, 82, 0, NULL, 0, 0, 1, '169202931782', 1, 8419, '1', '2023-08-14', NULL, NULL),
(145, 71, 29, NULL, 70, 81, 1, '169219720171', 4, 7115000, '118', '2023-08-16', NULL, NULL),
(146, 71, 29, NULL, 70, 82, 1, '169219751771', 4, 12885000, '118', '2023-08-16', NULL, NULL),
(147, 82, 42, NULL, 111, 261, 1, '169219987382', 1, 6000000, '118', '2023-08-16', NULL, NULL),
(150, 71, 0, NULL, 0, 0, 1, '169220086171', 2, 11000000, '118', '2023-08-16', NULL, NULL),
(151, 82, 42, NULL, 111, 259, 1, '169226074282', 1, 34628, '118', '2023-08-17', NULL, NULL),
(153, 79, 40, NULL, 0, 69, 1, '169288915379', 1, 16341, '1', '2023-08-24', NULL, NULL),
(154, 79, 40, NULL, 102, 68, 1, '169289022679', 1, 17926, '1', '2023-08-24', NULL, NULL),
(155, 79, 40, NULL, 102, 69, 1, '169289033479', 1, 5585, '1', '2023-08-24', NULL, NULL);

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
  `output_start_year` int NOT NULL,
  `duration` int NOT NULL,
  `budget` int NOT NULL,
  `total_target` bigint NOT NULL,
  `workplan_interval` int DEFAULT NULL COMMENT 'Rename to monitoring frequency',
  `unit_type` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '3',
  `progress` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_details`
--

INSERT INTO `tbl_project_details` (`id`, `unique_key`, `progid`, `projid`, `outputid`, `indicator`, `output_start_year`, `duration`, `budget`, `total_target`, `workplan_interval`, `unit_type`, `status`, `progress`) VALUES
(1, 'FSi8I0kqDQ', 1, 1, 1, 1, 5, 600, 680000000, 45, NULL, 0, 4, 25),
(2, 'FSi8I0kqDQ', 1, 1, 3, 2, 5, 650, 140000000, 6, NULL, 0, 11, 0),
(3, 'FSi8I0kqDQ', 1, 1, 5, 8, 5, 300, 200000000, 2, NULL, 2, 11, 0),
(4, 'hwtZpr18Ub', 2, 2, 7, 4, 5, 200, 20000000, 9, NULL, 1, 3, 0),
(5, 'xjUbtFQxUg', 2, 3, 13, 10, 5, 150, 41000000, 4, NULL, 1, 11, 4),
(6, '41yEFQxnFt', 2, 5, 9, 7, 5, 300, 500000000, 1, NULL, 1, 3, 0),
(7, '41yEFQxnFt', 2, 5, 11, 9, 5, 300, 500000000, 1, NULL, 1, 3, 0),
(8, 'YBftO5sE3P', 2, 6, 9, 7, 5, 360, 100000000, 1, NULL, 1, 3, 0),
(9, 'YBftO5sE3P', 2, 6, 15, 12, 5, 360, 1000000000, 20, NULL, 0, 3, 0),
(10, 'GAJK7tK7aS', 2, 7, 15, 12, 5, 300, 500000000, 20, NULL, 0, 3, 0),
(11, 'VWlkgNWOAN', 4, 15, 27, 8, 6, 300, 60000000, 5, NULL, 2, 3, 0),
(12, 'VWlkgNWOAN', 4, 15, 23, 1, 6, 120, 180000000, 7, NULL, 0, 3, 0),
(13, 'AHW9vtpuiK', 4, 53, 27, 8, 6, 200, 40000000, 4, NULL, 2, 3, 0),
(14, 'rrNWy45hU3', 4, 52, 23, 1, 6, 120, 15000000, 5, NULL, 0, 3, 0),
(15, 'B30xbZg1Yh', 4, 54, 23, 1, 6, 200, 10000000, 30, NULL, 0, 3, 0),
(16, 'xNAXavKqT3', 4, 55, 25, 2, 6, 120, 5000000, 10, NULL, 0, 3, 0),
(23, 'rwYx0yXrPk', 2, 59, 17, 18, 5, 300, 200, 2, NULL, 0, 3, 0),
(22, 'ULIRQxPl1Z', 3, 62, 21, 12, 5, 300, 1000, 1, NULL, 0, 3, 0),
(19, '621WEzaUTf', 4, 51, 23, 1, 6, 110, 50000000, 3, NULL, 0, 3, 0),
(20, 'Eoi140KNVE', 4, 56, 23, 1, 6, 130, 60000000, 10, NULL, 0, 3, 0),
(21, 'KgcMXZwbVe', 2, 58, 17, 18, 5, 300, 500000000, 10, NULL, 0, 3, 0),
(24, 'cVZqhbcC3u', 1, 66, 1, 1, 5, 13, 120000, 12, NULL, 0, 3, 0),
(25, '5vAmYsQc3r', 2, 69, 7, 4, 5, 700, 40000000, 24, NULL, 1, 3, 0),
(26, '5vAmYsQc3r', 2, 69, 11, 9, 5, 700, 2000000, 4, NULL, 1, 3, 0),
(27, 'LOEb1fECU7', 1, 70, 1, 1, 5, 300, 50000000, 29, NULL, 0, 3, 0),
(28, 'LOEb1fECU7', 1, 70, 5, 8, 5, 120, 20000000, 1, NULL, 1, 3, 0),
(29, 'oxH7TzGQFE', 3, 71, 19, 21, 5, 400, 70000000, 30, NULL, 1, 3, 0),
(30, 'S6jORghkYv', 2, 72, 7, 4, 5, 100, 9999998, 2, NULL, 1, 3, 0),
(31, 'grs4zZ4fbX', 10, 74, 64, 4, 5, 300, 50000000, 4, NULL, 2, 3, 0),
(32, 's55pZXfG9W', 9, 75, 62, 1, 6, 300, 1000000, 10, NULL, 0, 3, 0),
(33, '9u90JNqRjm', 9, 76, 62, 1, 6, 200, 1000000, 10, NULL, 0, 3, 0),
(34, '9u90JNqRjm', 9, 76, 63, 8, 6, 200, 1000000, 4, NULL, 1, 3, 0),
(35, 'NgolZqjA8v', 1, 77, 1, 1, 6, 156, 50000000, 10, NULL, 0, 3, 0),
(36, 'NgolZqjA8v', 1, 77, 5, 8, 6, 60, 100, 3, NULL, 2, 3, 0),
(37, 'tIWZztgaTX', 1, 78, 1, 1, 5, 120, 200000000, 20, NULL, 0, 3, 0),
(38, 'tIWZztgaTX', 1, 78, 5, 8, 5, 120, 100000000, 5, NULL, 2, 3, 0),
(39, 'oImnDqM0QV', 1, 79, 1, 1, 5, 100, 60000000, 30, NULL, 0, 3, 0),
(40, 'oImnDqM0QV', 1, 79, 5, 8, 5, 120, 6000000, 3, NULL, 2, 3, 0),
(41, 'TLVvNyyn5J', 11, 81, 68, 25, 6, 300, 30000000, 300, NULL, 0, 3, 0),
(42, 'kbPDExHFLh', 2, 82, 13, 10, 5, 300, 50000000, 3, NULL, 2, 3, 0),
(43, 'bJtLMRcyXQ', 12, 85, 69, 26, 6, 200, 10000000, 1, NULL, 1, 3, 0),
(44, 'TAEOEOUmz5', 13, 86, 75, 8, 4, 10, 1000000, 1, NULL, 1, 3, 0),
(45, 'zTG4DUvCqB', 13, 87, 75, 8, 5, 10, 10000000, 1, NULL, 1, 3, 0),
(46, '3WjADBEccC', 14, 88, 77, 8, 6, 250, 20000000, 1, NULL, 1, 3, 0),
(47, 'y1lfFIxza0', 15, 89, 82, 8, 6, 220, 9999995, 1, NULL, 1, 3, 0),
(48, 'cuepEVj5V9', 16, 91, 83, 30, 6, 200, 2000000, 20, NULL, 1, 3, 0),
(49, 'AXcBjiOMW4', 11, 93, 68, 25, 6, 300, 1000000, 100, NULL, 0, 3, 0),
(50, 'Sve871xHqm', 1, 94, 1, 1, 5, 20, 3000000, 2, NULL, 0, 3, 0),
(51, 'hOSj3SufS9', 1, 95, 1, 1, 5, 200, 50000000, 25, NULL, 0, 3, 0),
(52, 'hOSj3SufS9', 1, 95, 5, 8, 5, 150, 6000000, 4, NULL, 2, 3, 0),
(53, 'HO03OC5cCZ', 1, 96, 1, 1, 5, 200, 600000000, 10, NULL, 0, 3, 0),
(54, 'HO03OC5cCZ', 1, 96, 5, 8, 5, 180, 3000000, 2, NULL, 2, 3, 0),
(55, 'WWpkgR1rTc', 12, 84, 69, 26, 6, 100, 1000000, 1, NULL, 1, 3, 0),
(56, 'u6lowGwJ0U', 2, 97, 7, 4, 6, 200, 60000000, 6, NULL, 1, 3, 0),
(57, 'F0MG3frYuQ', 11, 98, 68, 25, 6, 700, 5000000, 100, NULL, 0, 3, 0),
(58, 'K1ISaJJSer', 2, 100, 13, 10, 5, 60, 32000000, 3, NULL, 2, 3, 0),
(59, 'vkPmOPwFqX', 12, 101, 71, 27, 6, 200, 5000000, 1, NULL, 1, 3, 0),
(60, '7q0NgFM0MC', 12, 83, 73, 28, 6, 90, 2000000, 2, NULL, 1, 3, 0),
(61, 'L2qcQMGofg', 1, 80, 1, 1, 0, 0, 0, 10, NULL, 0, 3, 0),
(62, 'L2qcQMGofg', 1, 80, 5, 8, 0, 0, 0, 3, NULL, 2, 3, 0),
(63, 'qh2HEcaEts', 1, 105, 1, 1, 0, 0, 0, 10, NULL, 0, 3, 0),
(64, 'qh2HEcaEts', 1, 105, 5, 8, 0, 0, 0, 3, NULL, 2, 3, 0),
(66, 'P9w0dm4qqR', 1, 109, 1, 1, 0, 0, 0, 10, NULL, 0, 3, 0),
(67, 'P9w0dm4qqR', 1, 109, 5, 8, 0, 0, 0, 4, NULL, 2, 3, 0);

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_direct_cost_plan`
--

CREATE TABLE `tbl_project_direct_cost_plan` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `design_id` int DEFAULT NULL,
  `site_id` int DEFAULT NULL,
  `designation_id` int DEFAULT NULL,
  `plan_id` varchar(255) DEFAULT NULL,
  `tasks` int NOT NULL DEFAULT '0',
  `subtask_id` int NOT NULL DEFAULT '0',
  `personnel` int DEFAULT NULL,
  `other_plan_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL COMMENT 'A is Monitoring; B is Mapping; C is Baseline Evaluation',
  `description` text,
  `unit` varchar(100) DEFAULT NULL,
  `unit_cost` double DEFAULT NULL,
  `units_no` int DEFAULT NULL,
  `comments` text,
  `cost_type` int NOT NULL,
  `task_type` int NOT NULL DEFAULT '0',
  `item_order` varchar(255) NOT NULL DEFAULT '0',
  `tbl_project_direct_cost_plan` int NOT NULL DEFAULT '0',
  `financial_year` int NOT NULL DEFAULT '0',
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `update_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL,
  `inspection_status` int NOT NULL DEFAULT '0' COMMENT '0:non complaint, 1 complaint'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_direct_cost_plan`
--

INSERT INTO `tbl_project_direct_cost_plan` (`id`, `projid`, `outputid`, `design_id`, `site_id`, `designation_id`, `plan_id`, `tasks`, `subtask_id`, `personnel`, `other_plan_id`, `description`, `unit`, `unit_cost`, `units_no`, `comments`, `cost_type`, `task_type`, `item_order`, `tbl_project_direct_cost_plan`, `financial_year`, `created_by`, `date_created`, `update_by`, `date_updated`, `inspection_status`) VALUES
(5, 3, 0, 0, 0, 7, '16788874983', 0, 0, NULL, '2', 'Inspection', 'Days', 20000, 50, '', 2, 0, '0', 0, 2022, '118', '2023-03-15', NULL, NULL, 0),
(6, 3, 5, 4, 7, 0, '16788875813', 7, 0, NULL, '0', '12', '12', 50000, 100, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(7, 3, 5, 4, 7, 0, '16788876293', 8, 0, NULL, '0', '13', '13', 145000, 100, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(8, 3, 5, 4, 6, 0, '16788876973', 8, 0, NULL, '0', '13', '13', 145000, 100, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(9, 3, 5, 4, 6, 0, '16788877293', 7, 0, NULL, '0', '12', '12', 10000, 500, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(10, 1, 1, 1, 0, 0, '16788936671', 1, 0, NULL, '0', '14', '14', 2000, 30000, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(11, 1, 1, 1, 0, 0, '16788936671', 1, 0, NULL, '0', '15', '15', 1500, 20000, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(12, 1, 1, 1, 0, 0, '16788937081', 2, 0, NULL, '0', '16', '16', 20000, 1300, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(13, 1, 1, 1, 0, 0, '16788938881', 9, 0, NULL, '0', '17', '17', 1400000, 20, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(14, 1, 1, 1, 0, 0, '16788938881', 9, 0, NULL, '0', '18', '18', 980000, 20, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(15, 1, 2, 2, 0, 0, '16788941751', 3, 0, NULL, '0', '1', '1', 130000, 500, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(16, 1, 2, 2, 0, 0, '16788941751', 3, 0, NULL, '0', '2', '2', 160000, 300, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(17, 1, 2, 2, 0, 0, '16788942591', 4, 0, NULL, '0', '3', '3', 300000, 20, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(18, 1, 2, 2, 0, 0, '16788942591', 4, 0, NULL, '0', '4', '4', 400000, 40, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(19, 2, 0, 0, 0, 7, '16788944162', 0, 0, NULL, '2', 'Inspection', 'Days', 20000, 50, '', 2, 0, '0', 0, 2022, '118', '2023-03-15', NULL, NULL, 0),
(20, 1, 3, 3, 1, 0, '16788944301', 5, 0, NULL, '0', '5', '5', 300000, 50, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(22, 2, 4, 9, 3, 0, '16788945172', 10, 0, NULL, '0', '27', '27', 10000, 100, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(23, 1, 3, 3, 1, 0, '16788945511', 6, 0, NULL, '0', '6', '6', 20000, 60, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(24, 1, 3, 3, 1, 0, '16788945511', 6, 0, NULL, '0', '7', '7', 30000, 120, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(25, 1, 3, 3, 1, 0, '16788945511', 6, 0, NULL, '0', '8', '8', 30000, 140, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(26, 1, 3, 3, 1, 0, '16788945511', 6, 0, NULL, '0', '9', '9', 45000, 300, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(27, 1, 3, 3, 1, 0, '16788945511', 6, 0, NULL, '0', '10', '10', 76000, 400, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(28, 1, 3, 3, 1, 0, '16788945511', 6, 0, NULL, '0', '11', '11', 28000, 200, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(30, 2, 4, 9, 3, 0, '16788945892', 11, 0, NULL, '0', '28', '28', 55000, 100, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(31, 2, 4, 9, 4, 0, '16788946422', 10, 0, NULL, '0', '27', '27', 10000, 100, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(32, 2, 4, 9, 4, 0, '16788946962', 11, 0, NULL, '0', '28', '28', 55000, 100, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(33, 1, 3, 3, 2, 0, '16788947211', 5, 0, NULL, '0', '5', '5', 300000, 130, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(34, 2, 4, 9, 5, 0, '16788947362', 10, 0, NULL, '0', '27', '27', 10000, 100, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(35, 2, 4, 9, 5, 0, '16788948242', 11, 0, NULL, '0', '28', '28', 45000, 100, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(36, 1, 3, 3, 2, 0, '16788948311', 6, 0, NULL, '0', '6', '6', 120000, 120, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(37, 1, 3, 3, 2, 0, '16788948311', 6, 0, NULL, '0', '7', '7', 108000, 130, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(38, 1, 3, 3, 2, 0, '16788948311', 6, 0, NULL, '0', '8', '8', 140000, 130, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(39, 1, 3, 3, 2, 0, '16788948311', 6, 0, NULL, '0', '9', '9', 160000, 120, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(40, 1, 3, 3, 2, 0, '16788948311', 6, 0, NULL, '0', '10', '10', 120000, 130, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(41, 1, 3, 3, 2, 0, '16788948311', 6, 0, NULL, '0', '11', '11', 150000, 160, '', 1, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(68, 3, 0, NULL, NULL, 13, '2', 0, 0, NULL, 'B', 'perdiem', 'Days', 10000, 30, '', 4, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(69, 3, 0, NULL, NULL, 12, '2', 0, 0, NULL, 'A', 'perdiem', 'Days', 40000, 10, '', 4, 0, '0', 0, 2022, '118', '2023-03-15', NULL, NULL, 0),
(70, 3, 0, NULL, NULL, 13, '2', 0, 0, NULL, 'C', 'Enumerators', 'Days', 30000, 10, '', 4, 0, '0', 0, 0, '118', '2023-03-15', NULL, NULL, 0),
(87, 1, 0, 0, 0, 4, '16789566271', 0, 0, NULL, '2', 'Transport Costs', 'Day', 20000, 30, '', 2, 0, '0', 0, 2022, '124', '2023-03-16', NULL, NULL, 0),
(88, 1, 0, 0, 0, 7, '16789566271', 0, 0, NULL, '2', 'Transport Costs', 'Day', 20000, 30, '', 2, 0, '0', 0, 2022, '124', '2023-03-16', NULL, NULL, 0),
(89, 1, 0, 0, 0, 13, '16789566271', 0, 0, NULL, '2', 'Transport Costs', 'Day', 15000, 130, '', 2, 0, '0', 0, 2022, '124', '2023-03-16', NULL, NULL, 0),
(90, 1, 0, 0, 0, 2, '16789566271', 0, 0, NULL, '2', 'DSA', 'DAY', 100000, 120, '', 2, 0, '0', 0, 2022, '124', '2023-03-16', NULL, NULL, 0),
(91, 1, 0, 0, 0, 6, '16789566271', 0, 0, NULL, '2', 'DSA', 'Day', 99000, 300, '', 2, 0, '0', 0, 2022, '124', '2023-03-16', NULL, NULL, 0),
(92, 1, 0, 0, 0, 10, '16789566271', 0, 0, NULL, '2', 'DSA', 'Day', 90000, 600, '', 2, 0, '0', 0, 2022, '124', '2023-03-16', NULL, NULL, 0),
(93, 1, 0, 0, 0, 12, '16789566271', 0, 0, NULL, '2', 'DSA', 'Day', 88000, 700, '', 2, 0, '0', 0, 2022, '124', '2023-03-16', NULL, NULL, 0),
(94, 1, 0, 0, 0, 7, '16789566271', 0, 0, NULL, '2', 'Public P', 'Day', 30210, 1000, '', 2, 0, '0', 0, 2022, '124', '2023-03-16', NULL, NULL, 0),
(95, 1, 0, 0, 0, 2, '16789566271', 0, 0, NULL, '2', 'Transport', 'DAY', 50000, 40, '', 2, 0, '0', 0, 2023, '124', '2023-03-16', NULL, NULL, 0),
(96, 1, 0, 0, 0, 3, '16789566271', 0, 0, NULL, '2', 'Transport', 'DAY', 45000, 45, '', 2, 0, '0', 0, 2023, '124', '2023-03-16', NULL, NULL, 0),
(97, 1, 0, 0, 0, 5, '16789566271', 0, 0, NULL, '2', 'Transport', 'DAY', 40000, 50, '', 2, 0, '0', 0, 2023, '124', '2023-03-16', NULL, NULL, 0),
(98, 1, 0, 0, 0, 6, '16789566271', 0, 0, NULL, '2', 'Transport', 'DAY', 35000, 55, '', 2, 0, '0', 0, 2023, '124', '2023-03-16', NULL, NULL, 0),
(99, 1, 0, 0, 0, 2, '16789566271', 0, 0, NULL, '2', 'DSA', 'DAY', 200000, 100, '', 2, 0, '0', 0, 2023, '124', '2023-03-16', NULL, NULL, 0),
(100, 1, 0, 0, 0, 3, '16789566271', 0, 0, NULL, '2', 'DSA', 'DAY', 190000, 120, '', 2, 0, '0', 0, 2023, '124', '2023-03-16', NULL, NULL, 0),
(101, 1, 0, 0, 0, 11, '16789566271', 0, 0, NULL, '2', 'DSA', 'DAY', 180000, 140, '', 2, 0, '0', 0, 2023, '124', '2023-03-16', NULL, NULL, 0),
(102, 1, 0, 0, 0, 4, '16789566271', 0, 0, NULL, '2', 'DSA', 'DAY', 150000, 179, '', 2, 0, '0', 0, 2023, '124', '2023-03-16', NULL, NULL, 0),
(103, 1, 0, 0, 0, 7, '16789566271', 0, 0, NULL, '2', 'Public P.', 'DAY', 10000000, 20, '', 2, 0, '0', 0, 2023, '124', '2023-03-16', NULL, NULL, 0),
(104, 1, 0, NULL, NULL, 7, '2', 0, 0, NULL, 'B', 'DSA', 'Day', 30000, 20, '', 4, 0, '0', 0, 0, '118', '2023-03-16', NULL, NULL, 0),
(105, 1, 0, NULL, NULL, 5, '2', 0, 0, NULL, 'B', 'DSA', 'Day', 60000, 30, '', 4, 0, '0', 0, 0, '118', '2023-03-16', NULL, NULL, 0),
(106, 1, 0, NULL, NULL, 8, '2', 0, 0, NULL, 'B', 'DSA', 'Day', 70000, 20, '', 4, 0, '0', 0, 0, '118', '2023-03-16', NULL, NULL, 0),
(109, 1, 0, NULL, NULL, 6, '2', 0, 0, NULL, 'A', 'DSA', 'Day', 40000, 100, '', 4, 0, '0', 0, 2022, '118', '2023-03-16', NULL, NULL, 0),
(110, 1, 0, NULL, NULL, 9, '2', 0, 0, NULL, 'A', 'Transport', 'DAY', 30000, 20, '', 4, 0, '0', 0, 2023, '118', '2023-03-16', NULL, NULL, 0),
(111, 1, 0, NULL, NULL, 1, '2', 0, 0, NULL, 'A', 'PP', 'DAY', 160000, 10, '', 4, 0, '0', 0, 2023, '118', '2023-03-16', NULL, NULL, 0),
(112, 7, 0, 0, 0, 2, '16791466137', 0, 0, NULL, '2', 'Inspection', 'Days', 5000, 1000, '', 2, 0, '0', 0, 2022, '118', '2023-03-18', NULL, NULL, 0),
(113, 7, 10, 10, 0, 0, '16791467457', 12, 0, NULL, '0', '30', '30', 150000, 1000, '', 1, 0, '0', 0, 0, '118', '2023-03-18', NULL, NULL, 0),
(114, 7, 10, 10, 0, 0, '16791467827', 13, 0, NULL, '0', '29', '29', 160000, 1000, '', 1, 0, '0', 0, 0, '118', '2023-03-18', NULL, NULL, 0),
(115, 7, 10, 10, 0, 0, '16791468877', 14, 0, NULL, '0', '31', '31', 184000, 1000, '', 1, 0, '0', 0, 0, '118', '2023-03-18', NULL, NULL, 0),
(116, 7, 0, NULL, NULL, 7, '2', 0, 0, NULL, 'B', 'perdiem', 'Days', 30000, 10, '', 4, 0, '0', 0, 0, '118', '2023-03-18', NULL, NULL, 0),
(117, 7, 0, NULL, NULL, 12, '2', 0, 0, NULL, 'A', 'perdiem', 'Days', 35000, 10, '', 4, 0, '0', 0, 2022, '118', '2023-03-18', NULL, NULL, 0),
(118, 7, 0, NULL, NULL, 7, '2', 0, 0, NULL, 'C', 'Enumerators', 'Days', 35000, 10, '', 4, 0, '0', 0, 0, '118', '2023-03-18', NULL, NULL, 0),
(119, 6, 0, 0, 0, 2, '16793882976', 0, 0, NULL, '2', 'Inspection', 'Days', 500000, 100, '', 2, 0, '0', 0, 2022, '118', '2023-03-21', NULL, NULL, 0),
(120, 6, 0, 0, 0, 5, '16793882976', 0, 0, NULL, '2', 'Inspection', 'Days', 490000, 100, '', 2, 0, '0', 0, 2023, '118', '2023-03-21', NULL, NULL, 0),
(121, 6, 8, 11, 11, 0, '16793884136', 15, 0, NULL, '0', '32', '32', 1500000, 100, '', 1, 0, '0', 0, 0, '118', '2023-03-21', NULL, NULL, 0),
(122, 6, 9, 12, 0, 0, '16793884456', 17, 0, NULL, '0', '35', '35', 1500000, 100, '', 1, 0, '0', 0, 0, '118', '2023-03-21', NULL, NULL, 0),
(123, 6, 8, 11, 11, 0, '16793885436', 16, 0, NULL, '0', '33', '33', 150000, 1000, '', 1, 0, '0', 0, 0, '118', '2023-03-21', NULL, NULL, 0),
(124, 6, 8, 11, 11, 0, '16793885436', 16, 0, NULL, '0', '34', '34', 350000, 1000, '', 1, 0, '0', 0, 0, '118', '2023-03-21', NULL, NULL, 0),
(125, 6, 9, 12, 0, 0, '16793886346', 18, 0, NULL, '0', '36', '36', 200000, 1000, '', 1, 0, '0', 0, 0, '118', '2023-03-21', NULL, NULL, 0),
(126, 5, 6, 13, 10, 0, '16803462515', 19, 0, NULL, '0', '37', '37', 200000, 1000, '', 1, 0, '0', 0, 0, '118', '2023-04-01', NULL, NULL, 0),
(127, 5, 6, 13, 10, 0, '16803462985', 21, 0, NULL, '0', '38', '38', 350000, 1000, '', 1, 0, '0', 0, 0, '118', '2023-04-01', NULL, NULL, 0),
(128, 5, 7, 14, 10, 0, '16803463525', 22, 0, NULL, '0', '39', '39', 200000, 1000, '', 1, 0, '0', 0, 0, '118', '2023-04-01', NULL, NULL, 0),
(129, 5, 7, 14, 10, 0, '16803464355', 23, 0, NULL, '0', '40', '40', 248500, 1000, '', 1, 0, '0', 0, 0, '118', '2023-04-01', NULL, NULL, 0),
(130, 5, 0, 0, 0, 7, '16803464965', 0, 0, NULL, '2', 'Inspection', 'Days', 50000, 10, '', 2, 0, '0', 0, 2022, '118', '2023-04-01', NULL, NULL, 0),
(131, 66, 24, 19, 0, 0, '168327487866', 49, 0, NULL, '0', '55', '55', 1000, 20, 'Express my remarks ', 1, 0, '0', 0, 0, '1', '2023-05-05', NULL, NULL, 0),
(132, 66, 24, 19, 0, 0, '168327487866', 49, 0, NULL, '0', '56', '56', 1000, 20, 'Express my remarks ', 1, 0, '0', 0, 0, '1', '2023-05-05', NULL, NULL, 0),
(133, 66, 24, 19, 0, 0, '168327492566', 50, 0, NULL, '0', '54', '54', 2000, 10, 'Remarks', 1, 0, '0', 0, 0, '1', '2023-05-05', NULL, NULL, 0),
(140, 66, 0, 0, 0, NULL, '168327683266', 0, 0, NULL, '2', 'Description 1', 'unit 1', 1000, 30, 'Test', 2, 0, '0', 0, 2022, '1', '2023-05-05', NULL, NULL, 0),
(141, 66, 0, 0, 0, NULL, '168327683266', 0, 0, NULL, '2', 'Description 2', 'unit 2', 1000, 20, 'Test', 2, 0, '0', 0, 2022, '1', '2023-05-05', NULL, NULL, 0),
(142, 69, 0, 0, 0, NULL, '168424010769', 0, 0, NULL, '2', 'Description 1', 'unit ', 2000, 1000, 'Remarks ', 2, 0, '0', 0, 2022, '1', '2023-05-16', NULL, NULL, 0),
(143, 69, 0, 0, 0, NULL, '168424010769', 0, 0, NULL, '2', 'Description 2', 'unit', 1000, 2000, 'Remarks ', 2, 0, '0', 0, 2022, '1', '2023-05-16', NULL, NULL, 0),
(144, 69, 0, 0, 0, NULL, '168424010769', 0, 0, NULL, '2', 'Description', 'Unit 1', 800000, 1, 'Remarks ', 2, 0, '0', 0, 2023, '1', '2023-05-16', NULL, NULL, 0),
(145, 69, 26, 21, 65, NULL, '168424061069', 53, 0, NULL, '0', '61', '61', 2000000, 10, 'Remarks', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(146, 69, 26, 21, 65, NULL, '168424065869', 54, 0, NULL, '0', '62', '62', 1000000, 1, 'remarks', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(147, 69, 26, 21, 67, NULL, '168424074069', 53, 0, NULL, '0', '61', '61', 1000000, 1, 'remarks', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(148, 69, 26, 21, 67, NULL, '168424092369', 54, 0, NULL, '0', '62', '62', 1000000, 1, 'test', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(149, 69, 25, 20, 65, NULL, '168424129969', 51, 0, NULL, '0', '57', '57', 1000000, 2, 'Remarks ', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(150, 69, 25, 20, 65, NULL, '168424129969', 51, 0, NULL, '0', '58', '58', 1000000, 3, 'Remarks ', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(151, 69, 25, 20, 65, NULL, '168424134369', 52, 0, NULL, '0', '59', '59', 1000, 1000, 'Testing ', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(152, 69, 25, 20, 65, NULL, '168424134369', 52, 0, NULL, '0', '60', '60', 1000, 1000, 'Testing ', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(153, 69, 25, 20, 67, NULL, '168424138569', 52, 0, NULL, '0', '59', '59', 500, 1000, 'Remarks ', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(154, 69, 25, 20, 67, NULL, '168424138569', 52, 0, NULL, '0', '60', '60', 500, 1000, 'Remarks ', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(155, 69, 25, 20, 67, NULL, '168424143569', 51, 0, NULL, '0', '57', '57', 5000, 10, 'Remarks ', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(156, 69, 25, 20, 67, NULL, '168424143569', 51, 0, NULL, '0', '58', '58', 5000, 10, 'Remarks ', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(157, 69, 25, 20, 66, NULL, '168424148269', 52, 0, NULL, '0', '59', '59', 1000, 10, 'Remarks', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(158, 69, 25, 20, 66, NULL, '168424148269', 52, 0, NULL, '0', '60', '60', 1000, 10, 'Remarks', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(159, 69, 25, 20, 66, NULL, '168424174069', 51, 0, NULL, '0', '57', '57', 880, 1000, 'Remarks ', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(160, 69, 25, 20, 66, NULL, '168424174069', 51, 0, NULL, '0', '58', '58', 1000, 1000, 'Remarks ', 1, 0, '0', 0, 0, '1', '2023-05-16', NULL, NULL, 0),
(164, 69, 0, NULL, NULL, NULL, '2', 0, 0, NULL, 'B', 'Description', 'Unit', 1000, 1000, 'Remarks', 4, 0, '0', 0, 0, '1', '2023-05-17', NULL, NULL, 0),
(165, 69, 0, NULL, NULL, NULL, '2', 0, 0, NULL, 'A', 'Description', 'unit ', 200000, 1, 'Description', 4, 0, '0', 0, 2022, '1', '2023-05-17', NULL, NULL, 0),
(166, 69, 0, NULL, NULL, NULL, '2', 0, 0, NULL, 'A', 'Description 2', 'unit ', 1000000, 1, 'Description', 4, 0, '0', 0, 2023, '1', '2023-05-17', NULL, NULL, 0),
(167, 69, 0, NULL, NULL, NULL, '2', 0, 0, NULL, 'C', 'Description', 'unit', 1000000, 2, 'RTemarks', 4, 0, '0', 0, 0, '1', '2023-05-17', NULL, NULL, 0),
(169, 62, 0, 0, 0, NULL, '169105792762', 0, 0, NULL, '2', 'test', 'days', 100, 1, 'Testing', 2, 0, '0', 0, 2022, '118', '2023-08-03', NULL, NULL, 0),
(170, 62, 0, 0, 0, NULL, '169105792762', 0, 0, NULL, '2', 'test', 'days', 21, 1, 'Testing', 2, 0, '0', 0, 2023, '118', '2023-08-03', NULL, NULL, 0),
(180, 78, 38, NULL, 92, NULL, '169141857478', 161, 0, NULL, '0', 'Ducimus omnis ratio', 'Id quis autem offici', 54, 39, 'Fuga Culpa consequ', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(181, 78, 38, NULL, 92, NULL, '169141857478', 161, 0, NULL, '0', 'Voluptatibus aut min', 'Voluptatem sed amet', 19, 64, 'Fuga Culpa consequ', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(182, 78, 38, NULL, 92, NULL, '169141857478', 161, 0, NULL, '0', 'Ut perferendis quibu', 'Minim eaque est perf', 20, 46, 'Fuga Culpa consequ', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(190, 78, 0, NULL, 0, NULL, '169141892778', 0, 0, NULL, '2', 'Laboriosam voluptat', 'Nostrud sed quaerat ', 36, 61, 'Vel officia autem qu', 2, 0, '0', 0, 2022, '1', '2023-08-07', NULL, NULL, 0),
(191, 78, 0, NULL, 0, NULL, '169141892778', 0, 0, NULL, '2', 'Doloremque et totam ', 'Corporis non id atqu', 19, 33, 'Vel officia autem qu', 2, 0, '0', 0, 2022, '1', '2023-08-07', NULL, NULL, 0),
(192, 78, 38, NULL, 92, NULL, '169142083878', 159, 0, NULL, '0', 'Nesciunt quo sed fu', 'Earum ad quis hic an', 63, 20, '', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(193, 78, 38, NULL, 92, NULL, '169142083878', 159, 0, NULL, '0', 'Aliqua Consequatur', 'Maiores quasi nobis ', 53, 83, '', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(194, 78, 38, NULL, 92, NULL, '169142083878', 159, 0, NULL, '0', 'Non quo sapiente est', 'Dolorem amet vel fu', 6, 54, '', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(195, 78, 38, NULL, 92, NULL, '169142083878', 159, 0, NULL, '0', 'Impedit officiis au', 'Velit omnis aliquam ', 70, 54, '', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(200, 78, 38, NULL, 92, NULL, '169142094178', 154, 0, NULL, '0', 'Aspernatur hic quia ', 'days', 72, 47, '8,535', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(201, 78, 38, NULL, 92, NULL, '169142094178', 154, 0, NULL, '0', 'Dolores ab sunt aut ', 'days', 44, 95, '8,535', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(202, 78, 38, NULL, 92, NULL, '169142094178', 154, 0, NULL, '0', 'Natus nihil ut sit ', 'cm3', 89, 11, '8,535', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(203, 78, 38, NULL, 92, NULL, '169142139678', 160, 0, NULL, '0', 'Magnam dolorem sunt ', 'A proident doloribu', 34, 70, '', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(204, 78, 38, NULL, 92, NULL, '169142139678', 160, 0, NULL, '0', 'Vitae ad magni est ', 'Distinctio Esse fug', 62, 3, '', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(205, 78, 38, NULL, 92, NULL, '169142139678', 160, 0, NULL, '0', 'A culpa quibusdam hi', 'Unde aliqua Aut vit', 39, 75, '', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(206, 78, 38, NULL, 92, NULL, '169142139678', 160, 0, NULL, '0', '', '', 39, 1000000, '', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(211, 78, 37, NULL, 98, NULL, '169142217078', 153, 0, NULL, '0', 'Sit dolorem molesti', 'Est et inventore Na', 37, 22, '1,618', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(212, 78, 37, NULL, 98, NULL, '169142217078', 153, 0, NULL, '0', 'Autem exercitationem', 'Ipsa vero voluptate', 80, 9, '1,618', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(213, 78, 37, NULL, 98, NULL, '169142217078', 153, 0, NULL, '0', 'Dolores dolorem labo', 'Itaque eos eligendi ', 85, 1, '1,618', 1, 0, '0', 0, 0, '1', '2023-08-07', NULL, NULL, 0),
(214, 78, 0, NULL, NULL, NULL, '2', 0, 0, NULL, 'A', 'Facere sit molestia', 'Dolore accusamus et ', 5000000, 1, 'Est eos soluta esse', 4, 0, '0', 0, 2022, '1', '2023-08-08', NULL, NULL, 0),
(215, 78, 0, NULL, NULL, NULL, '2', 0, 0, NULL, 'B', 'Sapiente consequatur', 'Amet est nesciunt ', 5000000, 1, 'Qui placeat recusan', 4, 0, '0', 0, 0, '1', '2023-08-08', NULL, NULL, 0),
(263, 82, 0, NULL, 0, NULL, '169202931782', 0, 0, NULL, '2', 'Eaque autem quia mol', 'Quia atque sed corpo', 62, 10, 'Odio porro cupiditat', 2, 0, '0', 0, 2022, '1', '2023-08-14', NULL, NULL, 0),
(264, 82, 0, NULL, 0, NULL, '169202931782', 0, 0, NULL, '2', 'Rerum sequi eligendi', 'Nulla numquam molest', 52, 97, 'Odio porro cupiditat', 2, 0, '0', 0, 2022, '1', '2023-08-14', NULL, NULL, 0),
(265, 82, 0, NULL, 0, NULL, '169202931782', 0, 0, NULL, '2', 'Duis quo labore illo', 'Nisi non aut earum i', 95, 29, 'Odio porro cupiditat', 2, 0, '0', 0, 2022, '1', '2023-08-14', NULL, NULL, 0),
(276, 71, 29, NULL, 70, NULL, '169219720171', 81, 0, NULL, '0', 'Inventore mollitia l', 'Aut ut ut quaerat ut', 8400, 200, 'Test', 1, 0, '0', 0, 0, '118', '2023-08-16', NULL, NULL, 0),
(277, 71, 29, NULL, 70, NULL, '169219720171', 81, 0, NULL, '0', 'Ducimus consequuntu', 'Dolor sunt provident', 2400, 500, 'Test', 1, 0, '0', 0, 0, '118', '2023-08-16', NULL, NULL, 0),
(278, 71, 29, NULL, 70, NULL, '169219720171', 81, 0, NULL, '0', 'Dolorem error harum ', 'Corporis dolore aper', 7100, 250, 'Test', 1, 0, '0', 0, 0, '118', '2023-08-16', NULL, NULL, 0),
(279, 71, 29, NULL, 70, NULL, '169219720171', 81, 0, NULL, '0', 'Sit quia id ea fugi', 'Earum quas ut volupt', 8200, 300, 'Test', 1, 0, '0', 0, 0, '118', '2023-08-16', NULL, NULL, 0),
(280, 71, 29, NULL, 70, NULL, '169219751771', 82, 0, NULL, '0', 'Est consectetur qui', 'In odio qui qui dolo', 80000, 20, 'Testing', 1, 0, '0', 0, 0, '118', '2023-08-16', NULL, NULL, 0),
(281, 71, 29, NULL, 70, NULL, '169219751771', 82, 0, NULL, '0', 'Harum perferendis no', 'Omnis eveniet error', 25000, 350, 'Testing', 1, 0, '0', 0, 0, '118', '2023-08-16', NULL, NULL, 0),
(282, 71, 29, NULL, 70, NULL, '169219751771', 82, 0, NULL, '0', 'Est unde sit magna ', 'Animi dolore ut ape', 5000, 303, 'Testing', 1, 0, '0', 0, 0, '118', '2023-08-16', NULL, NULL, 0),
(283, 71, 29, NULL, 70, NULL, '169219751771', 82, 0, NULL, '0', 'Test 1', 'Days', 20000, 51, 'Testing', 1, 0, '0', 0, 0, '118', '2023-08-16', NULL, NULL, 0),
(284, 82, 42, NULL, 111, NULL, '169219987382', 261, 0, NULL, '0', 'Labour for foundation', 'Days', 50000, 50, 'hhhhhhhhhhhhhhhhh \r\nkkkkkkkkkkkkkkkkk\r\nlllllllllllllllllll\r\n;lkkjkhgffddgh\r\nrrrrrrrrrrrrrrrrrrrrrrrrrrrr\r\nttttttttttttttttttttt\r\nwwwwwwwwwwwwwwwwwww\r\nrrrrrrrrrrrrrrrrrrrrrrrrrr\r\ntttttttttttttttttttttttttt', 1, 0, '0', 0, 0, '118', '2023-08-16', NULL, NULL, 0),
(285, 82, 42, NULL, 111, NULL, '169219987382', 261, 0, NULL, '0', 'Inventore mollitia l', 'In odio qui qui dolo', 35000, 100, 'hhhhhhhhhhhhhhhhh \r\nkkkkkkkkkkkkkkkkk\r\nlllllllllllllllllll\r\n;lkkjkhgffddgh\r\nrrrrrrrrrrrrrrrrrrrrrrrrrrrr\r\nttttttttttttttttttttt\r\nwwwwwwwwwwwwwwwwwww\r\nrrrrrrrrrrrrrrrrrrrrrrrrrr\r\ntttttttttttttttttttttttttt', 1, 0, '0', 0, 0, '118', '2023-08-16', NULL, NULL, 0),
(289, 71, 0, NULL, 0, NULL, '169220086171', 0, 0, NULL, '2', '3,000,000', '3,000,000', 3000000, 2, 'Tester abcsfdgg', 2, 0, '0', 0, 2022, '118', '2023-08-16', NULL, NULL, 0),
(290, 71, 0, NULL, 0, NULL, '169220086171', 0, 0, NULL, '2', 'Tester', 'ABC', 500000, 10, 'Tester abcsfdgg', 2, 0, '0', 0, 2022, '118', '2023-08-16', NULL, NULL, 0),
(291, 82, 42, NULL, 111, NULL, '169226074282', 259, 0, NULL, '0', 'Autem optio deserun', 'Fugiat atque reicien', 67, 74, 'Testing', 1, 0, '0', 0, 0, '118', '2023-08-17', NULL, NULL, 0),
(292, 82, 42, NULL, 111, NULL, '169226074282', 259, 0, NULL, '0', 'Ea nihil voluptatem', 'Enim nulla et volupt', 64, 150, 'Testing', 1, 0, '0', 0, 0, '118', '2023-08-17', NULL, NULL, 0),
(293, 82, 42, NULL, 111, NULL, '169226074282', 259, 0, NULL, '0', 'Occaecat non maiores', 'Dolore ut fugit ea ', 31, 90, 'Testing', 1, 0, '0', 0, 0, '118', '2023-08-17', NULL, NULL, 0),
(294, 82, 42, NULL, 111, NULL, '169226074282', 259, 0, NULL, '0', 'Reprehenderit aut au', 'In velit error totam', 54, 320, 'Testing', 1, 0, '0', 0, 0, '118', '2023-08-17', NULL, NULL, 0),
(430, 79, 40, NULL, 102, NULL, '0', 69, 252, NULL, '0', 'Planking, strutting and shoring to sides of all excavations : keep excavations free from all fallen materials', '', 28, 1700, NULL, 1, 0, '81', 0, 0, '1', '2023-08-31', NULL, NULL, 0),
(431, 79, 40, NULL, 102, NULL, '0', 69, 253, NULL, '0', 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '', 1000, 9000, NULL, 1, 0, '79', 0, 0, '1', '2023-08-31', NULL, NULL, 0),
(432, 79, 40, NULL, 102, NULL, '0', 69, 254, NULL, '0', 'Erosion Protection and River Training Works ', '', 17, 88, NULL, 1, 0, '87', 0, 0, '1', '2023-08-31', NULL, NULL, 0),
(435, 79, 40, NULL, 102, NULL, '0', 70, 255, NULL, '0', 'Excavation for Gabions in Soft Material', '', 200, 9900, NULL, 1, 0, '100', 0, 0, '1', '2023-08-31', NULL, NULL, 0),
(436, 79, 40, NULL, 102, NULL, '0', 70, 256, NULL, '0', 'Gabion Mesh', '', 400, 3700, NULL, 1, 0, '87', 0, 0, '1', '2023-08-31', NULL, NULL, 0),
(437, 79, 40, NULL, 102, NULL, '0', 71, 257, NULL, '0', 'Footpaths ', '', 220, 2900, NULL, 1, 0, '10', 0, 0, '1', '2023-08-31', NULL, NULL, 0),
(438, 79, 40, NULL, 102, NULL, '0', 71, 258, NULL, '0', 'Hand Rails ', '', 450, 1800, NULL, 1, 0, '1', 0, 0, '1', '2023-08-31', NULL, NULL, 0),
(439, 79, 40, NULL, 103, NULL, '0', 68, 250, NULL, '0', 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', '', 720, 610, NULL, 1, 0, '85', 0, 0, '1', '2023-08-31', NULL, NULL, 0),
(440, 79, 40, NULL, 103, NULL, '0', 68, 251, NULL, '0', 'River deviation', '', 1000, 1000, NULL, 1, 0, '80', 0, 0, '1', '2023-08-31', NULL, NULL, 0),
(441, 79, 40, NULL, 102, NULL, '0', 68, 250, NULL, '0', 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', '', 2400, 200, NULL, 1, 0, '52', 0, 0, '1', '2023-08-31', NULL, NULL, 0),
(442, 79, 40, NULL, 102, NULL, '0', 68, 251, NULL, '0', 'River deviation', '', 7800, 810, NULL, 1, 0, '86', 0, 0, '1', '2023-08-31', NULL, NULL, 0),
(484, 79, 0, NULL, 0, NULL, '0', 0, 0, NULL, '6', 'Laboris quis in dese', '75', 6, 89, NULL, 6, 1, '45', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(485, 79, 0, NULL, 0, NULL, '0', 0, 0, NULL, '6', 'Ab in aut quasi rati', '54', 67, 3, NULL, 6, 1, '42', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(486, 79, 0, NULL, 0, NULL, '0', 0, 0, NULL, '6', 'Ut veniam illum la', '7', 54, 1, NULL, 6, 1, '50', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(487, 79, 0, NULL, 0, NULL, '0', 0, 0, NULL, '6', 'Vel fugiat quis sit', '16', 67, 34, NULL, 6, 1, '86', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(488, 79, 40, NULL, 103, NULL, '0', 71, 257, NULL, '0', 'Footpaths ', '0', 8, 47, NULL, 1, 0, '73', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(489, 79, 40, NULL, 103, NULL, '0', 71, 258, NULL, '0', 'Hand Rails ', '0', 55, 59, NULL, 1, 0, '58', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(490, 79, 40, NULL, 103, NULL, '0', 69, 252, NULL, '0', 'Planking, strutting and shoring to sides of all excavations : keep excavations free from all fallen materials', '0', 8, 60, NULL, 1, 0, '73', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(491, 79, 40, NULL, 103, NULL, '0', 69, 253, NULL, '0', 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '0', 80, 66, NULL, 1, 0, '30', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(492, 79, 40, NULL, 103, NULL, '0', 69, 254, NULL, '0', 'Erosion Protection and River Training Works ', '0', 66, 60, NULL, 1, 0, '43', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(493, 79, 40, NULL, 103, NULL, '0', 70, 255, NULL, '0', 'Excavation for Gabions in Soft Material', '0', 26, 54, NULL, 1, 0, '47', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(494, 79, 40, NULL, 103, NULL, '0', 70, 256, NULL, '0', 'Gabion Mesh', '0', 80, 42, NULL, 1, 0, '0', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(497, 79, 40, NULL, 104, NULL, '0', 69, 252, NULL, '0', 'Planking, strutting and shoring to sides of all excavations : keep excavations free from all fallen materials', '0', 20, 16, NULL, 1, 0, '8', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(498, 79, 40, NULL, 104, NULL, '0', 69, 253, NULL, '0', 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '0', 83, 58, NULL, 1, 0, '68', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(499, 79, 40, NULL, 104, NULL, '0', 69, 254, NULL, '0', 'Erosion Protection and River Training Works ', '0', 18, 99, NULL, 1, 0, '11', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(500, 79, 40, NULL, 104, NULL, '0', 70, 255, NULL, '0', 'Excavation for Gabions in Soft Material', '0', 990, 6900, NULL, 1, 0, '83', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(501, 79, 40, NULL, 104, NULL, '0', 70, 256, NULL, '0', 'Gabion Mesh', '0', 720, 3900, NULL, 1, 0, '95', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(502, 79, 40, NULL, 104, NULL, '0', 68, 250, NULL, '0', 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', '0', 3000, 1800, NULL, 1, 0, '41', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(503, 79, 40, NULL, 104, NULL, '0', 68, 251, NULL, '0', 'River deviation', '0', 22000, 61, NULL, 1, 0, '38', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(504, 79, 40, NULL, 104, NULL, '0', 71, 257, NULL, '0', 'Footpaths ', '0', 78, 99, NULL, 1, 0, '47', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(505, 79, 40, NULL, 104, NULL, '0', 71, 258, NULL, '0', 'Hand Rails ', '0', 9900, 1400, NULL, 1, 0, '68', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(511, 79, 39, NULL, 0, NULL, '0', 67, 247, NULL, '0', 'Construction of Improved Subgrade', '0', 24, 8, NULL, 1, 0, '36', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(512, 79, 39, NULL, 0, NULL, '0', 67, 248, NULL, '0', 'Surfacing ', '0', 69, 12, NULL, 1, 0, '45', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(513, 79, 39, NULL, 0, NULL, '0', 67, 249, NULL, '0', 'Road Furniture', '0', 1000, 1000, NULL, 1, 0, '31', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(514, 79, 39, NULL, 0, NULL, '0', 66, 243, NULL, '0', 'Survey works and setting out', '0', 68, 66, NULL, 1, 0, '58', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(515, 79, 39, NULL, 0, NULL, '0', 66, 244, NULL, '0', 'Earthworks', '0', 72, 88, NULL, 1, 0, '85', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(516, 79, 39, NULL, 0, NULL, '0', 66, 245, NULL, '0', 'Drainage ', '0', 14, 13, NULL, 1, 0, '31', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(517, 79, 39, NULL, 0, NULL, '0', 66, 246, NULL, '0', 'Slope Protection', '0', 1, 1000000, NULL, 1, 0, '48', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(527, 96, 54, NULL, 136, NULL, '0', 104, 318, NULL, '0', 'Excavation ', '13', 2400, 7700, NULL, 1, 0, '1', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(528, 96, 54, NULL, 136, NULL, '0', 104, 319, NULL, '0', 'Backfilling ', '13', 7600, 8800, NULL, 1, 0, '3', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(529, 96, 54, NULL, 136, NULL, '0', 104, 320, NULL, '0', 'Planking and Strutting', '14', 9000, 5600, NULL, 1, 0, '4', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(558, 96, 0, NULL, 0, NULL, '0', 0, 0, NULL, '2', 'Transportation Costs', '75', 100000, 20, NULL, 2, 0, '1', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(559, 96, 0, NULL, 0, NULL, '0', 0, 0, NULL, '2', 'Lunches', '75', 50000, 20, NULL, 2, 1, '2', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(560, 97, 0, NULL, 0, NULL, '0', 0, 0, NULL, '2', 'Allowances', '75', 50, 50000, NULL, 2, 1, '1', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(561, 97, 0, NULL, 0, NULL, '0', 0, 0, NULL, '2', 'Fuel', '75', 50000, 50, NULL, 2, 1, '2', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(562, 96, 54, NULL, 136, NULL, '0', 103, 317, NULL, '0', 'Top Soil Removal ', '13', 10898, 1000, NULL, 1, 0, '1', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(571, 96, 53, NULL, 0, NULL, '0', 101, 323, NULL, '0', 'Earthworks', '13', 4000, 6000, NULL, 1, 0, '9', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(572, 96, 53, NULL, 0, NULL, '0', 101, 0, NULL, '0', 'Profits', '13', 9000, 9000, NULL, 1, 1, '10', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(573, 96, 53, NULL, 0, NULL, '0', 101, 0, NULL, '0', 'see what i got', '75', 7777, 3000, NULL, 1, 1, '3', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(579, 96, 53, NULL, 0, NULL, '0', 100, 321, NULL, '0', 'Resident Engineers Office', '19', 2000000, 1, NULL, 1, 0, '99', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(580, 96, 53, NULL, 0, NULL, '0', 100, 322, NULL, '0', 'Contractors Site', '19', 30000000, 1, NULL, 1, 0, '98', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(581, 96, 53, NULL, 0, NULL, '0', 100, 0, NULL, '0', 'Profits', '54', 2090000, 3, NULL, 1, 1, '89', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(582, 96, 53, NULL, 0, NULL, '0', 100, 0, NULL, '0', 'Extra over 01-80-030A for contractors’ profits and overheads', '16', 1200000, 2, NULL, 1, 1, '78', 0, 0, '118', '2023-09-05', NULL, NULL, 0),
(593, 96, 54, NULL, 137, NULL, '0', 103, 317, NULL, '0', 'Top Soil Removal ', '13', 1, 2341000, NULL, 1, 0, '90', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(594, 96, 54, NULL, 137, NULL, '0', 103, 0, NULL, '0', 'test', '2', 1, 80000000, NULL, 1, 1, '49', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(595, 96, 54, NULL, 137, NULL, '0', 104, 318, NULL, '0', 'Excavation ', '13', 1, 100000000, NULL, 1, 0, '34', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(596, 96, 54, NULL, 137, NULL, '0', 104, 319, NULL, '0', 'Backfilling ', '13', 1, 50000000, NULL, 1, 0, '445', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(597, 96, 54, NULL, 137, NULL, '0', 104, 320, NULL, '0', 'Planking and Strutting', '14', 1, 50000000, NULL, 1, 0, '5456', 0, 0, '1', '2023-09-05', NULL, NULL, 0),
(630, 97, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Allowance for enumerators', '75', 5000, 30, NULL, 5, 1, '001', 0, 0, '118', '2023-09-08', NULL, NULL, 0),
(631, 96, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Transportation Costs', '76', 40000, 10, NULL, 3, 0, '1', 0, 0, '1', '2023-09-08', NULL, NULL, 0),
(632, 96, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Lunches ', '76', 50000, 30, NULL, 3, 0, '2', 0, 0, '1', '2023-09-08', NULL, NULL, 0),
(643, 97, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Perdiem ', '75', 8000, 100, NULL, 4, 0, '001', 0, 0, '118', '2023-09-08', NULL, NULL, 0),
(644, 97, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Allowance', '75', 15000, 2, NULL, 3, 1, '001', 0, 0, '118', '2023-09-08', NULL, NULL, 0),
(645, 97, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Fuel', '75', 20000, 1, NULL, 3, 1, '002', 0, 0, '118', '2023-09-08', NULL, NULL, 0),
(646, 96, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Transportation Costs', '7', 22, 1000, NULL, 4, 0, '1', 0, 0, '118', '2023-09-08', NULL, NULL, 0),
(647, 96, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'DSA', '76', 1000, 70, NULL, 4, 0, '2', 0, 0, '118', '2023-09-08', NULL, NULL, 0),
(648, 96, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Stakeholder allowances', '76', 10, 100, NULL, 4, 0, '3', 0, 0, '118', '2023-09-08', NULL, NULL, 0),
(649, 96, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'test', '76', 10, 700, NULL, 4, 1, '4', 0, 0, '118', '2023-09-08', NULL, NULL, 0),
(650, 101, 59, NULL, 144, NULL, '0', 112, 348, NULL, '0', 'Removal of trees and stumps', '14', 2000, 1000, NULL, 1, 0, '001', 0, 0, '118', '2023-09-14', NULL, NULL, 0),
(651, 101, 59, NULL, 144, NULL, '0', 113, 349, NULL, '0', 'Top soil removal ', '13', 2000, 1000, NULL, 1, 0, '001', 0, 0, '118', '2023-09-14', NULL, NULL, 0),
(652, 101, 0, NULL, 0, NULL, '0', 0, 0, NULL, '2', 'Allowances', '19', 50, 10000, NULL, 2, 1, '001', 0, 0, '118', '2023-09-14', NULL, NULL, 0),
(653, 101, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Perdiem', '75', 40000, 10, NULL, 4, 1, '001', 0, 0, '118', '2023-09-14', NULL, NULL, 0),
(654, 101, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Allowances', '75', 10000, 10, NULL, 3, 1, '001', 0, 0, '118', '2023-09-14', NULL, NULL, 0),
(655, 84, 55, NULL, 115, NULL, '0', 114, 350, NULL, '0', 'Excavation for foundation ', '13', 450, 1000, NULL, 1, 0, '001', 0, 0, '118', '2023-09-14', NULL, NULL, 0),
(656, 84, 55, NULL, 115, NULL, '0', 115, 351, NULL, '0', 'Pipe laying', '14', 450, 1000, NULL, 1, 0, '001', 0, 0, '118', '2023-09-14', NULL, NULL, 0),
(657, 84, 0, NULL, 0, NULL, '0', 0, 0, NULL, '2', 'Allowances', '19', 5, 10000, NULL, 2, 1, '001', 0, 0, '118', '2023-09-14', NULL, NULL, 0),
(658, 84, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Allowances', '75', 10000, 4, NULL, 4, 1, '001', 0, 0, '118', '2023-09-14', NULL, NULL, 0),
(659, 84, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Allowances', '75', 10000, 1, NULL, 3, 1, '001', 0, 0, '118', '2023-09-14', NULL, NULL, 0),
(660, 109, 67, NULL, 153, NULL, '0', 120, 352, NULL, '0', 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', '13', 100, 365, NULL, 1, 0, '1', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(661, 109, 67, NULL, 153, NULL, '0', 120, 353, NULL, '0', 'River deviation', '13', 700, 3900, NULL, 1, 0, '2', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(662, 109, 67, NULL, 153, NULL, '0', 120, 0, NULL, '0', 'Rockfill below structures', '13', 1200, 8000, NULL, 1, 1, '3', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(663, 109, 67, NULL, 153, NULL, '0', 121, 354, NULL, '0', 'Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 870, 1200, NULL, 1, 0, '1', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(664, 109, 67, NULL, 153, NULL, '0', 121, 355, NULL, '0', 'Extra over excavation in hardrock', '13', 2300, 1700, NULL, 1, 0, '2', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(665, 109, 67, NULL, 153, NULL, '0', 121, 356, NULL, '0', 'Keep excavations free from all water by baling, pumping or otherwise', '19', 500, 1780, NULL, 1, 0, '2.1', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(666, 109, 67, NULL, 153, NULL, '0', 122, 357, NULL, '0', 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 2800, 2097, NULL, 1, 0, '1', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(667, 109, 67, NULL, 153, NULL, '0', 122, 358, NULL, '0', 'Excavate for Approaches, Erosion Check, Scour Checks and the like. Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions (Provisional)', '13', 230, 3400, NULL, 1, 0, '2', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(668, 109, 67, NULL, 153, NULL, '0', 122, 359, NULL, '0', '200mm thick dry stone pitching to embankments and in front of abutments', '72', 70, 1071, NULL, 1, 0, '3', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(669, 109, 67, NULL, 153, NULL, '0', 123, 360, NULL, '0', 'Excavation in soft material to any depth, compaction of the surfaces to receive the gabions, backfilling with the excavated materials or hauling to spoil excavated material', '13', 300, 120, NULL, 1, 0, '1', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(670, 109, 67, NULL, 153, NULL, '0', 123, 361, NULL, '0', 'Providing and fixing the mesh including diaphragms', '72', 789, 400, NULL, 1, 0, '2', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(671, 109, 67, NULL, 153, NULL, '0', 123, 362, NULL, '0', 'Providing, hauling and placing the rock', '13', 908, 129, NULL, 1, 0, '3', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(672, 109, 67, NULL, 153, NULL, '0', 123, 363, NULL, '0', 'Providing and hauling all materials, preparation, handling, placing of 75mm thick concrete to floor bed as blinding.', '72', 1209, 879, NULL, 1, 0, '4', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(673, 109, 67, NULL, 153, NULL, '0', 123, 364, NULL, '0', 'Providing and hauling all materials, preparation, handling, placing, finishing and curing premix concrete to slab bed , column base, side walls,beams and deck slab.', '13', 156, 901, NULL, 1, 0, '5', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(674, 109, 67, NULL, 153, NULL, '0', 123, 365, NULL, '0', 'ditto to column but mixing on site.', '13', 567, 120, NULL, 1, 0, '6', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(675, 109, 67, NULL, 154, NULL, '0', 120, 352, NULL, '0', 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', '13', 908, 139, NULL, 1, 0, '2', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(676, 109, 67, NULL, 154, NULL, '0', 120, 353, NULL, '0', 'River deviation', '13', 709, 289, NULL, 1, 0, '8', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(677, 109, 67, NULL, 154, NULL, '0', 121, 354, NULL, '0', 'Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 908, 200, NULL, 1, 0, '3', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(678, 109, 67, NULL, 154, NULL, '0', 121, 355, NULL, '0', 'Extra over excavation in hardrock', '13', 678, 187, NULL, 1, 0, '6', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(679, 109, 67, NULL, 154, NULL, '0', 121, 356, NULL, '0', 'Keep excavations free from all water by baling, pumping or otherwise', '19', 290, 150, NULL, 1, 0, '8.0', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(680, 109, 67, NULL, 154, NULL, '0', 121, 0, NULL, '0', 'contractor profits', '13', 1509, 400, NULL, 1, 1, '9', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(681, 109, 67, NULL, 154, NULL, '0', 122, 357, NULL, '0', 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 300, 678, NULL, 1, 0, '1', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(682, 109, 67, NULL, 154, NULL, '0', 122, 358, NULL, '0', 'Excavate for Approaches, Erosion Check, Scour Checks and the like. Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions (Provisional)', '13', 680, 980, NULL, 1, 0, '66', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(683, 109, 67, NULL, 154, NULL, '0', 122, 359, NULL, '0', '200mm thick dry stone pitching to embankments and in front of abutments', '72', 870, 670, NULL, 1, 0, '89a', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(684, 109, 67, NULL, 154, NULL, '0', 123, 360, NULL, '0', 'Excavation in soft material to any depth, compaction of the surfaces to receive the gabions, backfilling with the excavated materials or hauling to spoil excavated material', '13', 303, 573, NULL, 1, 0, 'Exercitation quis en', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(685, 109, 67, NULL, 154, NULL, '0', 123, 361, NULL, '0', 'Providing and fixing the mesh including diaphragms', '72', 3091, 391, NULL, 1, 0, 'Adipisci adipisicing', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(686, 109, 67, NULL, 154, NULL, '0', 123, 362, NULL, '0', 'Providing, hauling and placing the rock', '13', 2092, 878, NULL, 1, 0, 'Rerum ipsa non dolo', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(687, 109, 67, NULL, 154, NULL, '0', 123, 363, NULL, '0', 'Providing and hauling all materials, preparation, handling, placing of 75mm thick concrete to floor bed as blinding.', '72', 706, 999, NULL, 1, 0, 'Sit facilis et aut a', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(688, 109, 67, NULL, 154, NULL, '0', 123, 364, NULL, '0', 'Providing and hauling all materials, preparation, handling, placing, finishing and curing premix concrete to slab bed , column base, side walls,beams and deck slab.', '13', 903, 664, NULL, 1, 0, 'Obcaecati quo molest', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(689, 109, 67, NULL, 154, NULL, '0', 123, 365, NULL, '0', 'ditto to column but mixing on site.', '13', 296, 361, NULL, 1, 0, 'Atque eum amet sed ', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(690, 109, 66, NULL, 0, NULL, '0', 118, 375, NULL, '0', 'Fill in soft material', '13', 11, 92, NULL, 1, 0, 'Eaque eiusmod alias ', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(691, 109, 66, NULL, 0, NULL, '0', 118, 376, NULL, '0', 'Improved Subgrade fill class s3', '13', 81, 54, NULL, 1, 0, 'Qui ut incididunt au', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(692, 109, 66, NULL, 0, NULL, '0', 118, 377, NULL, '0', 'Compaction of 150mm depth of existing ground under embankments to 100% MDD (AASHTO-T99', '13', 18, 69, NULL, 1, 0, 'Quia voluptatem cul', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(693, 109, 66, NULL, 0, NULL, '0', 118, 378, NULL, '0', 'Compaction of the 300 mm below formation level in cuttings to 100% MDD (AASHTO-T99', '13', 81, 44, NULL, 1, 0, 'Dolorem unde unde di', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(695, 109, 66, NULL, 0, NULL, '0', 117, 370, NULL, '0', 'Excavate for structures in soft material', '13', 12, 82, NULL, 1, 0, 'Nam eu magnam incidu', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(696, 109, 66, NULL, 0, NULL, '0', 117, 371, NULL, '0', 'Excavation of unsuitable material below structure', '13', 40, 75, NULL, 1, 0, 'Excepturi ea et veli', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(697, 109, 66, NULL, 0, NULL, '0', 117, 372, NULL, '0', 'Extra over item 7.01 for excavation in hard material', '13', 62, 88, NULL, 1, 0, 'Beatae rerum magni e', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(698, 109, 66, NULL, 0, NULL, '0', 117, 373, NULL, '0', 'Selected granular fill material', '13', 51, 31, NULL, 1, 0, 'Ut rerum tempor poss', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(699, 109, 66, NULL, 0, NULL, '0', 117, 374, NULL, '0', 'Gabion mattress mesh', '13', 74, 49, NULL, 1, 0, 'Et rerum dolorem opt', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(700, 109, 67, NULL, 155, NULL, '0', 123, 360, NULL, '0', 'Excavation in soft material to any depth, compaction of the surfaces to receive the gabions, backfilling with the excavated materials or hauling to spoil excavated material', '13', 13, 35, NULL, 1, 0, 'Duis at eius vel omn', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(701, 109, 67, NULL, 155, NULL, '0', 123, 361, NULL, '0', 'Providing and fixing the mesh including diaphragms', '72', 27, 58, NULL, 1, 0, 'Et non architecto om', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(702, 109, 67, NULL, 155, NULL, '0', 123, 362, NULL, '0', 'Providing, hauling and placing the rock', '13', 82, 58, NULL, 1, 0, 'Nostrum et nostrud i', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(703, 109, 67, NULL, 155, NULL, '0', 123, 363, NULL, '0', 'Providing and hauling all materials, preparation, handling, placing of 75mm thick concrete to floor bed as blinding.', '72', 82, 98, NULL, 1, 0, 'Aut anim qui accusan', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(704, 109, 67, NULL, 155, NULL, '0', 123, 364, NULL, '0', 'Providing and hauling all materials, preparation, handling, placing, finishing and curing premix concrete to slab bed , column base, side walls,beams and deck slab.', '13', 19, 41, NULL, 1, 0, 'Pariatur Minima rep', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(705, 109, 67, NULL, 155, NULL, '0', 123, 365, NULL, '0', 'ditto to column but mixing on site.', '13', 88, 2, NULL, 1, 0, 'Quia libero libero r', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(706, 109, 67, NULL, 155, NULL, '0', 122, 357, NULL, '0', 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 51, 66, NULL, 1, 0, 'Optio voluptates do', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(707, 109, 67, NULL, 155, NULL, '0', 122, 358, NULL, '0', 'Excavate for Approaches, Erosion Check, Scour Checks and the like. Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions (Provisional)', '13', 99, 92, NULL, 1, 0, 'Minima ex ullam quis', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(708, 109, 67, NULL, 155, NULL, '0', 122, 359, NULL, '0', '200mm thick dry stone pitching to embankments and in front of abutments', '72', 93, 53, NULL, 1, 0, 'Vitae ea sed molesti', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(709, 109, 67, NULL, 155, NULL, '0', 121, 354, NULL, '0', 'Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 69, 76, NULL, 1, 0, 'Veniam tempora alia', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(710, 109, 67, NULL, 155, NULL, '0', 121, 355, NULL, '0', 'Extra over excavation in hardrock', '13', 44, 60, NULL, 1, 0, 'Autem elit aspernat', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(711, 109, 67, NULL, 155, NULL, '0', 121, 356, NULL, '0', 'Keep excavations free from all water by baling, pumping or otherwise', '19', 77, 66, NULL, 1, 0, 'Laborum ea qui volup', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(712, 109, 67, NULL, 155, NULL, '0', 120, 352, NULL, '0', 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', '13', 25, 42, NULL, 1, 0, 'Magnam non consequat', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(713, 109, 67, NULL, 155, NULL, '0', 120, 353, NULL, '0', 'River deviation', '13', 90, 28, NULL, 1, 0, 'Sit velit quia tem', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(714, 109, 67, NULL, 156, NULL, '0', 122, 357, NULL, '0', 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 16, 69, NULL, 1, 0, 'Anim non et enim eos', 0, 0, '118', '2023-09-29', NULL, NULL, 0);
INSERT INTO `tbl_project_direct_cost_plan` (`id`, `projid`, `outputid`, `design_id`, `site_id`, `designation_id`, `plan_id`, `tasks`, `subtask_id`, `personnel`, `other_plan_id`, `description`, `unit`, `unit_cost`, `units_no`, `comments`, `cost_type`, `task_type`, `item_order`, `tbl_project_direct_cost_plan`, `financial_year`, `created_by`, `date_created`, `update_by`, `date_updated`, `inspection_status`) VALUES
(715, 109, 67, NULL, 156, NULL, '0', 122, 358, NULL, '0', 'Excavate for Approaches, Erosion Check, Scour Checks and the like. Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions (Provisional)', '13', 46, 98, NULL, 1, 0, 'Sunt ex eu et totam', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(716, 109, 67, NULL, 156, NULL, '0', 122, 359, NULL, '0', '200mm thick dry stone pitching to embankments and in front of abutments', '72', 76, 24, NULL, 1, 0, 'Id velit maiores inc', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(717, 109, 67, NULL, 156, NULL, '0', 120, 352, NULL, '0', 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', '13', 74, 24, NULL, 1, 0, 'Quo commodi sit cup', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(718, 109, 67, NULL, 156, NULL, '0', 120, 353, NULL, '0', 'River deviation', '13', 36, 100, NULL, 1, 0, 'Labore voluptatem te', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(719, 109, 67, NULL, 156, NULL, '0', 121, 354, NULL, '0', 'Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 60, 61, NULL, 1, 0, 'Modi ut do ad vel be', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(720, 109, 67, NULL, 156, NULL, '0', 121, 355, NULL, '0', 'Extra over excavation in hardrock', '13', 4, 51, NULL, 1, 0, 'Eos et dolores quia ', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(721, 109, 67, NULL, 156, NULL, '0', 121, 356, NULL, '0', 'Keep excavations free from all water by baling, pumping or otherwise', '19', 52, 44, NULL, 1, 0, 'Inventore reprehende', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(722, 109, 67, NULL, 156, NULL, '0', 123, 360, NULL, '0', 'Excavation in soft material to any depth, compaction of the surfaces to receive the gabions, backfilling with the excavated materials or hauling to spoil excavated material', '13', 82, 92, NULL, 1, 0, 'Sit commodi autem a', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(723, 109, 67, NULL, 156, NULL, '0', 123, 361, NULL, '0', 'Providing and fixing the mesh including diaphragms', '72', 46, 47, NULL, 1, 0, 'Ipsam odio qui quasi', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(724, 109, 67, NULL, 156, NULL, '0', 123, 362, NULL, '0', 'Providing, hauling and placing the rock', '13', 54, 86, NULL, 1, 0, 'Commodo debitis aut ', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(725, 109, 67, NULL, 156, NULL, '0', 123, 363, NULL, '0', 'Providing and hauling all materials, preparation, handling, placing of 75mm thick concrete to floor bed as blinding.', '72', 61, 68, NULL, 1, 0, 'Ratione eos totam es', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(726, 109, 67, NULL, 156, NULL, '0', 123, 364, NULL, '0', 'Providing and hauling all materials, preparation, handling, placing, finishing and curing premix concrete to slab bed , column base, side walls,beams and deck slab.', '13', 18, 71, NULL, 1, 0, 'Nam enim est dolor q', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(727, 109, 67, NULL, 156, NULL, '0', 123, 365, NULL, '0', 'ditto to column but mixing on site.', '13', 13, 51, NULL, 1, 0, 'Sit doloremque offic', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(728, 109, 66, NULL, 0, NULL, '0', 116, 366, NULL, '0', 'Preparation of Contractors Site', '19', 67, 35, NULL, 1, 0, 'Labore quaerat ex al', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(729, 109, 66, NULL, 0, NULL, '0', 116, 367, NULL, '0', 'Purchase of Equipments', '19', 51, 50, NULL, 1, 0, 'Natus et voluptates ', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(730, 109, 66, NULL, 0, NULL, '0', 116, 368, NULL, '0', 'Construction of resident engineer\'s office ', '19', 3, 54, NULL, 1, 0, 'Nostrud voluptate eo', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(731, 109, 66, NULL, 0, NULL, '0', 116, 369, NULL, '0', 'Mobilization of Plant facilities', '19', 38, 66, NULL, 1, 0, 'Enim omnis velit ea ', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(734, 109, 66, NULL, 0, NULL, '0', 119, 379, NULL, '0', 'Final Inspection and Handover', '75', 500000, 10, NULL, 1, 0, '1', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(735, 109, 66, NULL, 0, NULL, '0', 119, 0, NULL, '0', 'Refreshments ', '75', 800000, 1, NULL, 1, 1, '99', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(736, 109, 66, NULL, 0, NULL, '0', 119, 0, NULL, '0', 'Hiring of Tents ', '75', 29360, 1, NULL, 1, 1, '999', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(737, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '2', 'DSA', '76', 50, 40000, NULL, 2, 1, '1', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(738, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '2', 'Fuel', '75', 120000, 30, NULL, 2, 1, '2', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(739, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '2', 'Contingency', '75', 40000, 10, NULL, 2, 1, '3', 0, 0, '118', '2023-09-29', NULL, NULL, 0),
(740, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'DSA', '75', 50000, 10, NULL, 4, 1, '1', 0, 0, '118', '2023-09-30', NULL, NULL, 0),
(741, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Fuel', '75', 20000, 10, NULL, 4, 1, '2', 0, 0, '118', '2023-09-30', NULL, NULL, 0),
(742, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Stakeholder Transport Reimbursement', '76', 10000, 60, NULL, 4, 1, '3', 0, 0, '118', '2023-09-30', NULL, NULL, 0),
(747, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Transportation Costs', '75', 5000, 20, NULL, 3, 0, '1', 0, 0, '118', '2023-09-30', NULL, NULL, 0),
(748, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Allowances ', '76', 20000, 3, NULL, 3, 0, '2', 0, 0, '118', '2023-09-30', NULL, NULL, 0),
(749, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Mapping devices ', '19', 60000, 10, NULL, 3, 0, '3', 0, 0, '118', '2023-09-30', NULL, NULL, 0),
(750, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Refreshments ', '19', 5000, 80, NULL, 3, 1, '4', 0, 0, '118', '2023-09-30', NULL, NULL, 0),
(751, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'Fuel', '15', 300, 5000, NULL, 5, 0, '1', 0, 0, '118', '2023-09-30', NULL, NULL, 0),
(752, 109, 0, NULL, 0, NULL, '0', 0, 0, NULL, '0', 'DSA', '76', 5000, 8, NULL, 5, 1, '2', 0, 0, '118', '2023-09-30', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_direct_cost_plan_onhold_originals`
--

CREATE TABLE `tbl_project_direct_cost_plan_onhold_originals` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `issueid` int NOT NULL,
  `plan_id` int DEFAULT NULL,
  `unit_cost` double DEFAULT NULL,
  `units_no` int DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_evaluation_answers`
--

CREATE TABLE `tbl_project_evaluation_answers` (
  `id` int NOT NULL,
  `submissionid` int NOT NULL,
  `questionid` int NOT NULL,
  `answer` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `disaggregation` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_evaluation_answers`
--

INSERT INTO `tbl_project_evaluation_answers` (`id`, `submissionid`, `questionid`, `answer`, `disaggregation`) VALUES
(1, 1, 1, '1000', NULL),
(2, 1, 2, 'Yes', NULL),
(3, 2, 1, '800', NULL),
(4, 2, 2, 'Yes', NULL),
(5, 3, 1, '500', NULL),
(6, 3, 2, 'Yes', NULL),
(7, 4, 1, '550', NULL),
(8, 4, 2, 'Yes', NULL),
(9, 5, 3, 'Yes', NULL),
(10, 5, 4, 'Yes', NULL),
(11, 6, 3, ' No', NULL),
(12, 6, 4, 'No', NULL),
(13, 7, 3, 'Yes', NULL),
(14, 7, 4, 'Yes', NULL),
(15, 8, 46, 'No', NULL),
(16, 9, 46, 'No', NULL),
(17, 10, 46, 'No', NULL),
(18, 11, 46, 'Yes', NULL),
(19, 12, 46, 'No', NULL),
(20, 13, 46, 'Yes', NULL),
(21, 14, 46, 'No', NULL),
(22, 15, 46, 'No', NULL),
(23, 16, 46, 'No', NULL),
(24, 17, 46, 'No', NULL),
(25, 18, 50, 'Yes', NULL),
(26, 19, 50, 'Yes', NULL),
(27, 20, 50, 'NO', NULL),
(28, 21, 50, 'Yes', NULL),
(29, 22, 50, 'NO', NULL),
(30, 23, 50, 'NO', NULL),
(31, 24, 50, 'NO', NULL),
(32, 25, 50, 'NO', NULL),
(33, 26, 50, 'Yes', NULL);

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
-- Table structure for table `tbl_project_evaluation_questions`
--

CREATE TABLE `tbl_project_evaluation_questions` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `question` varchar(255) NOT NULL,
  `resultstype` int NOT NULL COMMENT '1 is impact results while 2 is outcome results',
  `resultstypeid` int NOT NULL,
  `parent` int DEFAULT NULL,
  `questiontype` int NOT NULL COMMENT '1 is main question while 2 is other questions',
  `answertype` int DEFAULT NULL COMMENT '1 is Number; 2 is Multiple Choice; 3 is Checkboxes; 4 is Dropdown; 5 is Text Area; 6 is File Upload',
  `answerlabels` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_evaluation_questions`
--

INSERT INTO `tbl_project_evaluation_questions` (`id`, `projid`, `question`, `resultstype`, `resultstypeid`, `parent`, `questiontype`, `answertype`, `answerlabels`) VALUES
(1, 110, 'Volume of water', 2, 1, NULL, 1, 1, 'Cubic Meter'),
(2, 110, 'Has the volume of water increased?', 2, 1, NULL, 2, 2, 'Yes,No'),
(5, 2, 'Are you accessing clean water?', 1, 5, NULL, 1, 2, 'Y,N'),
(6, 2, 'How has the project benefited you?', 1, 5, NULL, 2, 5, ''),
(7, 6, 'Are you accessing clean water?', 1, 6, NULL, 1, 2, 'Yes,No'),
(8, 6, 'Has the project benefited you?', 1, 6, NULL, 2, 5, ''),
(9, 6, 'Average income per month', 1, 7, NULL, 1, 1, ''),
(10, 6, 'How has the project benefitted you?', 1, 7, NULL, 2, 5, ''),
(11, 2, 'Question 1', 2, 5, NULL, 1, 1, 'Question label'),
(12, 2, 'Q1', 2, 5, NULL, 2, 2, 'Q2'),
(13, 2, '22', 2, 6, NULL, 1, 1, '222'),
(14, 2, 'Q1', 2, 6, NULL, 2, 3, ''),
(17, 97, '22', 2, 10, NULL, 1, 2, '2222'),
(18, 97, '', 2, 10, NULL, 2, 5, '3333'),
(19, 97, 'Are you accessing clean water?', 2, 11, NULL, 1, 2, 'Yes,No'),
(27, 7, 'Main Question Tester', 1, 2, NULL, 1, 2, 'Yes,NO'),
(28, 7, 'Other Questions Tester 1', 1, 2, NULL, 2, 1, ''),
(29, 7, 'Other Questions Tester 2', 1, 2, NULL, 2, 5, 'Test'),
(30, 7, 'Other Questions Tester 3', 1, 2, NULL, 2, 4, 'One,Two,Three'),
(46, 109, 'Do you have access to tarmacked road ?', 2, 26, NULL, 1, 2, 'Yes,No'),
(50, 109, 'Main Question Tester', 1, 11, NULL, 1, 2, 'Yes,NO');

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
(5, 7, 2, '2376', 'kkipe15@gmail.com', 355, NULL, '2023-03-18'),
(6, 7, 2, '2481', 'kkipe15@gmail.com', 355, NULL, '2023-03-18'),
(7, 7, 2, '2314', 'kkipe15@gmail.com', 355, NULL, '2023-03-18'),
(8, 109, 5, '5016', 'denkytheka@gmail.com', 321, NULL, '2023-10-03'),
(9, 109, 5, '5957', 'denkytheka@gmail.com', 321, NULL, '2023-10-03'),
(10, 109, 5, '5078', 'denkytheka@gmail.com', 321, NULL, '2023-10-03'),
(11, 109, 5, '5658', 'denkytheka@gmail.com', 321, NULL, '2023-10-03'),
(12, 109, 5, '5076', 'denkytheka@gmail.com', 321, NULL, '2023-10-03'),
(13, 109, 5, '5269', 'projtracsystemsltd@gmail.com', 331, NULL, '2023-10-03'),
(14, 109, 5, '5254', 'projtracsystemsltd@gmail.com', 331, NULL, '2023-10-03'),
(15, 109, 5, '5215', 'projtracsystemsltd@gmail.com', 331, NULL, '2023-10-03'),
(16, 109, 5, '5154', 'projtracsystemsltd@gmail.com', 331, NULL, '2023-10-03'),
(17, 109, 5, '5437', 'projtracsystemsltd@gmail.com', 331, NULL, '2023-10-03'),
(18, 109, 8, '8927', 'projtracsystemsltd@gmail.com', 331, NULL, '2023-10-04'),
(21, 109, 8, '8972', 'projtracsystemsltd@gmail.com', 331, NULL, '2023-10-04'),
(23, 109, 8, '8326', 'projtracsystemsltd@gmail.com', 331, NULL, '2023-10-04'),
(24, 109, 8, '8395', 'denkytheka@gmail.com', 321, NULL, '2023-10-04'),
(25, 109, 8, '8731', 'denkytheka@gmail.com', 321, NULL, '2023-10-04'),
(26, 109, 8, '8394', 'denkytheka@gmail.com', 321, NULL, '2023-10-04');

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
  `frequency_units` int DEFAULT '1' COMMENT '1 is Years; 2 is Months; 3 is Weeks; 4 is Days',
  `number_of_evaluations` int NOT NULL DEFAULT '0',
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
-- Dumping data for table `tbl_project_expected_impact_details`
--

INSERT INTO `tbl_project_expected_impact_details` (`id`, `projid`, `impact`, `indid`, `target`, `data_source`, `data_collection_method`, `evaluation_frequency`, `frequency_units`, `number_of_evaluations`, `next_evaluation_date`, `responsible`, `report_user`, `reporting_timeline`, `added_by`, `date_added`, `changed_by`, `date_changed`) VALUES
(1, 3, 'Improved water and sanitation', 19, NULL, '2', NULL, 3, 1, 1, NULL, '133', '7', '3', '118', '2023-03-15', NULL, NULL),
(2, 7, 'Increased accessibility to clean water by targeted population', 22, NULL, '1', NULL, 6, 1, 1, NULL, '128', '6', '6', '118', '2023-03-18', '118', '2023-09-26'),
(3, 2, 'Increased accessibility to clean water', 22, NULL, '1', NULL, 6, 1, 1, NULL, '133', '7', '6', '118', '2023-03-20', NULL, NULL),
(6, 6, 'Increased accessibility of water by targeted  households', 22, NULL, '1', NULL, 6, 1, 1, NULL, '133', '7', '6', '118', '2023-03-21', NULL, NULL),
(7, 6, 'Enhanced livelihood for the targeted population', 23, NULL, '1', NULL, 6, 1, 1, NULL, '133', '7', '6', '118', '2023-03-21', NULL, NULL),
(8, 69, 'IMPACT 22', 22, NULL, '2', NULL, 3, 1, 10, NULL, '128', '12', '6', '1', '2023-05-16', '1', '2023-05-22'),
(9, 69, 'Testing Pump one', 22, NULL, '2', NULL, 3, 1, 10, NULL, '128', '12', '6', '1', '2023-05-16', '1', '2023-05-19'),
(10, 7, 'Laying of water pipes Impact 1', 22, NULL, '2', NULL, 6, 1, 5, NULL, NULL, NULL, NULL, '118', '2023-09-26', '118', '2023-09-26'),
(11, 109, 'Economically Sound Society', 32, NULL, '1', NULL, 6, 1, 2, NULL, NULL, NULL, NULL, '118', '2023-09-30', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_expected_outcome_details`
--

CREATE TABLE `tbl_project_expected_outcome_details` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `impactid` int DEFAULT NULL,
  `outcome` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `indid` int NOT NULL,
  `target` decimal(10,0) DEFAULT NULL,
  `data_source` varchar(100) NOT NULL,
  `data_collection_method` int DEFAULT NULL,
  `evaluation_frequency` int NOT NULL,
  `number_of_evaluations` int NOT NULL DEFAULT '0',
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

INSERT INTO `tbl_project_expected_outcome_details` (`id`, `projid`, `impactid`, `outcome`, `indid`, `target`, `data_source`, `data_collection_method`, `evaluation_frequency`, `number_of_evaluations`, `next_evaluation_date`, `responsible`, `report_user`, `reporting_timeline`, `added_by`, `date_added`, `changed_by`, `date_changed`) VALUES
(1, 3, 1, 'Increased accessibility of water', 20, NULL, '1', NULL, 4, 1, NULL, '128', '7', '4', '118', '2023-03-15', NULL, NULL),
(2, 7, 2, 'Increased volume of water', 20, NULL, '2', NULL, 6, 1, NULL, '128', '6', '6', '118', '2023-03-18', '118', '2023-09-26'),
(3, 6, 6, 'Increased volume of water', 20, NULL, '2', NULL, 6, 1, NULL, '133', '7', '6', '118', '2023-03-21', NULL, NULL),
(4, 6, 7, 'Increased employment opportunities', 24, NULL, '2', NULL, 3, 1, NULL, '133', '7', '6', '118', '2023-03-21', NULL, NULL),
(5, 2, 3, 'Outcome one ', 20, NULL, '1', NULL, 3, 2, NULL, '128', '3,5', '4,5', '1', '2023-05-09', NULL, NULL),
(6, 2, 3, 'Cumque eveniet sunt', 24, NULL, '1', NULL, 5, 744, NULL, '133', '12,13', '4,6', '1', '2023-05-09', NULL, NULL),
(7, 69, 8, 'Velit animi sed rep', 20, NULL, '2', NULL, 3, 574, NULL, '128', '3,6,7,11', '2,4,5', '1', '2023-05-17', NULL, NULL),
(8, 69, 8, 'Testing the problem and the name is one ', 24, NULL, '1', NULL, 3, 33, NULL, '128', '3', '3', '1', '2023-05-19', NULL, NULL),
(12, 97, 0, 'Increased accessibility to clean water', 20, NULL, '1', NULL, 6, 1, NULL, '133', '7,8,9,10', '6', '118', '2023-09-08', NULL, NULL),
(11, 97, 0, 'Increased accessibility to clean water', 20, NULL, '1', NULL, 6, 1, NULL, '133', '7,8,9,10', '6', '118', '2023-09-08', NULL, NULL),
(26, 109, 11, 'Improved level of access to quality road infrastructure', 31, NULL, '1', NULL, 6, 2, NULL, NULL, NULL, NULL, '118', '2023-10-02', NULL, NULL);

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
-- Table structure for table `tbl_project_inspection_specification_compliance`
--

CREATE TABLE `tbl_project_inspection_specification_compliance` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL,
  `design_id` int NOT NULL,
  `site_id` int NOT NULL,
  `state_id` int NOT NULL,
  `task_id` int NOT NULL,
  `parameter_id` int NOT NULL,
  `specification_id` int NOT NULL,
  `formid` varchar(100) NOT NULL,
  `compliance` float NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_inspection_specification_compliance`
--

INSERT INTO `tbl_project_inspection_specification_compliance` (`id`, `projid`, `output_id`, `design_id`, `site_id`, `state_id`, `task_id`, `parameter_id`, `specification_id`, `formid`, `compliance`, `created_by`, `created_at`) VALUES
(1, 3, 5, 4, 6, 0, 7, 12, 8, '2023-03-21', 1, 118, '2023-03-21'),
(2, 3, 5, 4, 6, 0, 7, 12, 9, '2023-03-21', 2, 118, '2023-03-21'),
(3, 3, 5, 4, 6, 0, 7, 12, 10, '2023-03-21', 3, 118, '2023-03-21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_mapping`
--

CREATE TABLE `tbl_project_mapping` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `ptid` varchar(255) NOT NULL,
  `responsible` int NOT NULL,
  `mapping_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_mapping`
--

INSERT INTO `tbl_project_mapping` (`id`, `projid`, `ptid`, `responsible`, `mapping_date`) VALUES
(1, 7, '128', 128, '2023-03-18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_milestone`
--

CREATE TABLE `tbl_project_milestone` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `milestone` text NOT NULL,
  `milestone_type` int NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL,
  `updated_by` int DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_milestone`
--

INSERT INTO `tbl_project_milestone` (`id`, `projid`, `milestone`, `milestone_type`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 74, 'Borehole Completed', 2, 1, '2023-08-04', 118, '2023-09-14'),
(2, 77, 'MILESTONE 1', 0, 118, '2023-08-04', NULL, NULL),
(3, 77, 'Milestone2', 0, 118, '2023-08-04', NULL, NULL),
(6, 71, 'Milestone Test', 0, 1, '2023-08-04', 1, '2023-08-05'),
(7, 78, 'Kimugul- Kapserbai Kiosk', 2, 118, '2023-08-04', 1, '2023-08-07'),
(8, 78, 'Kapserbai- Kapkios', 2, 118, '2023-08-04', 1, '2023-08-07'),
(10, 78, 'Testing', 1, 1, '2023-08-07', 1, '2023-08-07'),
(13, 82, 'Water storage Tank completed', 1, 118, '2023-08-08', 118, '2023-08-09'),
(14, 85, 'Excavation', 1, 118, '2023-08-09', NULL, NULL),
(15, 85, ' SUBSTRUCTURE: CONCRETE / REINFORCEMENT/ FORMWORKS ', 1, 118, '2023-08-09', NULL, NULL),
(18, 89, 'milestone 1', 1, 118, '2023-08-12', NULL, NULL),
(20, 79, 'Kapmilimu Phase', 2, 118, '2023-08-14', NULL, NULL),
(21, 79, 'Aipca Section', 2, 118, '2023-08-14', NULL, NULL),
(22, 79, 'Final Section', 2, 118, '2023-08-14', NULL, NULL),
(23, 79, 'Demobilization', 1, 118, '2023-08-14', NULL, NULL),
(24, 91, 'milestone 1 manufacturing', 1, 118, '2023-08-14', 118, '2023-08-14'),
(25, 91, 'milestone 2', 1, 118, '2023-08-14', NULL, NULL),
(26, 62, 'Milestone', 2, 1, '2023-08-16', NULL, NULL),
(27, 94, 'Milestone 1', 1, 1, '2023-08-19', NULL, NULL),
(28, 94, 'Milestone based', 2, 1, '2023-08-19', NULL, NULL),
(30, 95, 'Phase 1', 2, 118, '2023-08-21', NULL, NULL),
(31, 59, 'm1', 2, 1, '2023-08-22', NULL, NULL),
(32, 59, 'm2', 1, 1, '2023-08-22', NULL, NULL),
(33, 95, 'Phase 2', 2, 118, '2023-08-22', NULL, NULL),
(34, 96, 'phase 1', 2, 118, '2023-08-24', NULL, NULL),
(35, 96, 'Phase 2', 2, 118, '2023-08-24', NULL, NULL),
(36, 96, 'milestone33', 1, 118, '2023-08-24', NULL, NULL),
(37, 98, 'Training of the 1st 50 Farmers', 2, 118, '2023-09-04', NULL, NULL),
(38, 98, 'Training of the last 50 Farmers', 2, 118, '2023-09-04', NULL, NULL),
(39, 97, 'Drilling Completed', 1, 118, '2023-09-04', NULL, NULL),
(40, 100, 'Water storage Tank completed', 1, 118, '2023-09-06', NULL, NULL),
(41, 101, 'Anaerobic reactor completed', 1, 118, '2023-09-14', NULL, NULL),
(42, 84, 'Solid Waste Incinerator', 1, 118, '2023-09-14', NULL, NULL),
(43, 109, 'Milestone1', 2, 118, '2023-09-29', NULL, NULL),
(44, 109, 'Milestone 2', 2, 118, '2023-09-29', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_milestones`
--

CREATE TABLE `tbl_project_milestones` (
  `milestone_id` int NOT NULL,
  `projid` int NOT NULL,
  `milestone` text NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_milestone_outputs`
--

CREATE TABLE `tbl_project_milestone_outputs` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `milestone_id` int NOT NULL,
  `output_id` int NOT NULL,
  `target` double DEFAULT '0',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_milestone_outputs`
--

INSERT INTO `tbl_project_milestone_outputs` (`id`, `projid`, `milestone_id`, `output_id`, `target`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, 77, 2, 35, 4, 118, NULL, '2023-08-04', NULL),
(3, 77, 2, 36, 2, 118, NULL, '2023-08-04', NULL),
(4, 77, 3, 35, 6, 118, NULL, '2023-08-04', NULL),
(5, 77, 3, 36, 1, 118, NULL, '2023-08-04', NULL),
(17, 71, 6, 29, 30, 1, NULL, '2023-08-05', NULL),
(22, 78, 10, 37, 0, 1, NULL, '2023-08-07', NULL),
(23, 78, 10, 38, 0, 1, NULL, '2023-08-07', NULL),
(28, 78, 8, 37, 8, 1, NULL, '2023-08-07', NULL),
(29, 78, 8, 38, 2, 1, NULL, '2023-08-07', NULL),
(30, 78, 7, 38, 3, 1, NULL, '2023-08-07', NULL),
(31, 78, 7, 37, 12, 1, NULL, '2023-08-07', NULL),
(40, 82, 13, 42, 0, 118, NULL, '2023-08-09', NULL),
(41, 85, 14, 43, 0, 118, NULL, '2023-08-09', NULL),
(42, 85, 15, 43, 0, 118, NULL, '2023-08-09', NULL),
(60, 89, 18, 47, 0, 118, NULL, '2023-08-12', NULL),
(63, 79, 20, 39, 16, 118, NULL, '2023-08-14', NULL),
(64, 79, 20, 40, 2, 118, NULL, '2023-08-14', NULL),
(65, 79, 21, 39, 9, 118, NULL, '2023-08-14', NULL),
(66, 79, 21, 40, 1, 118, NULL, '2023-08-14', NULL),
(67, 79, 22, 39, 5, 118, NULL, '2023-08-14', NULL),
(68, 79, 23, 39, 0, 118, NULL, '2023-08-14', NULL),
(70, 91, 25, 48, 0, 118, NULL, '2023-08-14', NULL),
(71, 91, 24, 48, 0, 118, NULL, '2023-08-14', NULL),
(72, 62, 26, 22, 1, 1, NULL, '2023-08-16', NULL),
(73, 94, 27, 50, 0, 1, NULL, '2023-08-19', NULL),
(74, 94, 28, 50, 2, 1, NULL, '2023-08-19', NULL),
(76, 95, 30, 51, 10, 118, NULL, '2023-08-21', NULL),
(77, 59, 31, 23, 2, 1, NULL, '2023-08-22', NULL),
(78, 59, 32, 23, 0, 1, NULL, '2023-08-22', NULL),
(79, 95, 30, 52, 3, 118, NULL, '2023-08-22', NULL),
(80, 95, 33, 52, 1, 118, NULL, '2023-08-22', NULL),
(81, 95, 33, 51, 15, 118, NULL, '2023-08-22', NULL),
(82, 96, 34, 53, 6, 118, NULL, '2023-08-24', NULL),
(83, 96, 34, 54, 2, 118, NULL, '2023-08-24', NULL),
(84, 96, 35, 53, 4, 118, NULL, '2023-08-24', NULL),
(85, 96, 36, 53, 0, 118, NULL, '2023-08-24', NULL),
(86, 98, 37, 57, 50, 118, NULL, '2023-09-04', NULL),
(87, 98, 38, 57, 50, 118, NULL, '2023-09-04', NULL),
(88, 97, 39, 56, 0, 118, NULL, '2023-09-04', NULL),
(89, 100, 40, 58, 0, 118, NULL, '2023-09-06', NULL),
(90, 74, 1, 31, 1, 118, NULL, '2023-09-14', NULL),
(91, 101, 41, 59, 0, 118, NULL, '2023-09-14', NULL),
(92, 84, 42, 55, 0, 118, NULL, '2023-09-14', NULL),
(93, 109, 43, 66, 6, 118, NULL, '2023-09-29', NULL),
(94, 109, 43, 67, 2, 118, NULL, '2023-09-29', NULL),
(95, 109, 44, 66, 4, 118, NULL, '2023-09-29', NULL),
(96, 109, 44, 67, 2, 118, NULL, '2023-09-29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_milestone_outputs_sites`
--

CREATE TABLE `tbl_project_milestone_outputs_sites` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL,
  `milestone_id` int NOT NULL,
  `milestone_ouput_id` int NOT NULL,
  `output_dissaggregation_id` int NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_milestone_outputs_sites`
--

INSERT INTO `tbl_project_milestone_outputs_sites` (`id`, `projid`, `output_id`, `milestone_id`, `milestone_ouput_id`, `output_dissaggregation_id`, `created_by`, `created_at`) VALUES
(1, 77, 35, 1, 1, 84, 1, '2023-08-03'),
(2, 77, 36, 1, 2, 85, 1, '2023-08-03'),
(3, 77, 36, 1, 2, 86, 1, '2023-08-03'),
(4, 77, 36, 1, 2, 87, 1, '2023-08-03'),
(5, 78, 37, 3, 5, 88, 118, '2023-08-03'),
(6, 78, 37, 3, 5, 89, 118, '2023-08-03'),
(7, 78, 38, 3, 6, 93, 118, '2023-08-03'),
(8, 78, 38, 3, 6, 94, 118, '2023-08-03'),
(9, 78, 37, 5, 9, 88, 118, '2023-08-03'),
(10, 78, 37, 5, 9, 89, 118, '2023-08-03'),
(11, 78, 38, 5, 10, 93, 118, '2023-08-03'),
(12, 78, 38, 5, 10, 94, 118, '2023-08-03'),
(13, 78, 37, 7, 13, 88, 118, '2023-08-03'),
(14, 78, 37, 7, 13, 89, 118, '2023-08-03'),
(15, 78, 38, 7, 14, 93, 118, '2023-08-03'),
(16, 78, 38, 7, 14, 94, 118, '2023-08-03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_monitoring_checklist`
--

CREATE TABLE `tbl_project_monitoring_checklist` (
  `checklist_id` int NOT NULL,
  `projid` int DEFAULT NULL,
  `output_id` int NOT NULL,
  `site_id` int NOT NULL,
  `task_id` int NOT NULL,
  `checklist` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `unit_of_measure` int NOT NULL,
  `target` int NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_monitoring_checklist`
--

INSERT INTO `tbl_project_monitoring_checklist` (`checklist_id`, `projid`, `output_id`, `site_id`, `task_id`, `checklist`, `unit_of_measure`, `target`, `created_by`, `created_at`) VALUES
(1, 3, 5, 4, 7, 'Depth not exceeding 0.25m', 14, 2, '118', '2023-03-15'),
(4, 2, 4, 9, 10, 'Report', 19, 1, '118', '2023-03-15'),
(5, 2, 4, 9, 11, 'Depth', 14, 350, '118', '2023-03-15'),
(6, 3, 5, 4, 8, 'Masonary joint reinforcement with 20X3mm thick hoop iron in every alternate course in every course. ', 14, 10, '118', '2023-03-15'),
(7, 1, 1, 1, 1, 'excavation', 13, 1800, '118', '2023-03-17'),
(8, 1, 1, 1, 1, 'Backfilling ', 13, 1800, '118', '2023-03-17'),
(9, 1, 1, 1, 2, 'Bush clearing and stripping', 16, 30, '118', '2023-03-17'),
(10, 1, 1, 1, 2, 'Construction of temporary access road', 16, 30, '118', '2023-03-17'),
(11, 1, 1, 1, 2, 'Top Soil Removal, collection and dumping ', 16, 30, '118', '2023-03-17'),
(12, 1, 1, 1, 2, 'Embankment fill ', 16, 30, '118', '2023-03-17'),
(13, 1, 1, 1, 2, 'Drainage ', 16, 30, '118', '2023-03-17'),
(14, 1, 1, 1, 9, 'Construction of Improved Subgrade', 16, 30, '118', '2023-03-17'),
(15, 1, 1, 1, 9, 'Sub base Construction', 16, 30, '118', '2023-03-17'),
(16, 1, 1, 1, 9, 'Base course construction', 16, 30, '118', '2023-03-17'),
(17, 1, 1, 1, 9, 'Surfacing ', 16, 30, '118', '2023-03-17'),
(18, 1, 1, 1, 9, 'Construction of guard rails', 16, 30, '118', '2023-03-17'),
(19, 1, 1, 1, 9, 'Installation of road signs', 19, 100, '118', '2023-03-17'),
(20, 1, 1, 1, 9, 'Road Marking ', 16, 30, '118', '2023-03-17'),
(21, 1, 1, 1, 9, 'Demobilization from site ', 19, 1, '118', '2023-03-17'),
(22, 1, 1, 1, 9, 'Inspection and handover', 19, 1, '118', '2023-03-17'),
(23, 1, 2, 2, 3, 'excavation', 16, 30, '118', '2023-03-17'),
(24, 1, 2, 2, 4, 'surfacing ', 16, 50, '118', '2023-03-17'),
(26, 1, 3, 3, 5, 'excavation', 13, 5000, '118', '2023-03-17'),
(27, 1, 3, 3, 6, 'planking and strutting', 16, 40, '118', '2023-03-17'),
(28, 7, 10, 10, 12, 'Top soil not exceeding 1.2m deep and 0.8m wide excavated', 13, 2880, '118', '2023-03-18'),
(29, 7, 10, 10, 13, 'Backfilling ', 13, 2880, '118', '2023-03-18'),
(30, 7, 10, 10, 14, 'Length', 14, 3000, '118', '2023-03-18'),
(31, 15, 11, 15, 24, 'Param 1', 2, 100, '1', '2023-05-10'),
(32, 66, 24, 19, 49, 'param 1', 7, 100, '1', '2023-05-10'),
(33, 66, 24, 19, 50, 'Param 3', 2, 200, '1', '2023-05-10'),
(34, 78, 37, 0, 137, 'Sed rerum dolor debi', 14, 31, '1', '2023-08-09'),
(35, 78, 37, 0, 137, 'Consectetur dolore ', 16, 27, '1', '2023-08-09'),
(36, 78, 37, 0, 137, 'Cum rem incidunt qu', 2, 35, '1', '2023-08-09'),
(37, 78, 37, 0, 138, 'Alias laborum eos q', 19, 68, '1', '2023-08-09'),
(38, 78, 37, 0, 138, 'Placeat necessitati', 16, 98, '1', '2023-08-09'),
(39, 78, 37, 0, 138, 'Aut blanditiis iste ', 2, 26, '1', '2023-08-09'),
(40, 78, 37, 0, 138, 'Reprehenderit offic', 54, 95, '1', '2023-08-09'),
(41, 78, 37, 0, 138, 'Blanditiis consequat', 15, 42, '1', '2023-08-09'),
(42, 78, 37, 0, 139, 'Harum veniam minima', 14, 56, '1', '2023-08-09'),
(43, 78, 37, 0, 139, 'Dolor earum neque Na', 13, 93, '1', '2023-08-09'),
(44, 78, 37, 0, 139, 'Quia culpa voluptat', 60, 93, '1', '2023-08-09'),
(45, 78, 37, 0, 139, 'Mollit excepteur inc', 2, 35, '1', '2023-08-09'),
(46, 78, 38, 93, 161, 'Voluptatem consectet', 15, 64, '1', '2023-08-09'),
(47, 78, 38, 93, 161, 'Accusantium perspici', 14, 44, '1', '2023-08-09'),
(48, 78, 38, 93, 161, 'Illo ducimus recusa', 16, 26, '1', '2023-08-09'),
(53, 78, 38, 93, 155, 'Odit sed ut nulla vo', 15, 86, '1', '2023-08-09'),
(54, 78, 38, 93, 155, 'Veniam voluptate la', 16, 25, '1', '2023-08-09'),
(55, 78, 38, 93, 155, 'Reprehenderit labor', 7, 57, '1', '2023-08-09'),
(56, 78, 38, 93, 155, 'Sit iste dolor eum ', 60, 8, '1', '2023-08-09'),
(57, 3, 5, 6, 7, 'Quidem quo rerum min', 14, 83, '1', '2023-08-12'),
(58, 3, 5, 6, 7, 'Magna in nisi except', 14, 73, '1', '2023-08-12'),
(59, 3, 5, 6, 7, 'Assumenda delectus ', 54, 91, '1', '2023-08-12'),
(60, 3, 5, 6, 7, 'Quis quia quas in Na', 7, 92, '1', '2023-08-12'),
(61, 3, 5, 6, 8, 'Iusto deserunt quis ', 16, 76, '1', '2023-08-12'),
(62, 3, 5, 6, 8, 'Blanditiis dolorum d', 7, 57, '1', '2023-08-12'),
(63, 3, 5, 6, 8, 'Omnis in voluptates ', 60, 56, '1', '2023-08-12'),
(64, 3, 5, 6, 8, 'Dolorem non fugiat ', 15, 97, '1', '2023-08-12');

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
  `projid` int NOT NULL,
  `output_id` int NOT NULL,
  `milestone_id` int NOT NULL,
  `site_id` int NOT NULL,
  `task_id` int NOT NULL,
  `subtask_id` int NOT NULL,
  `formid` varchar(100) NOT NULL,
  `achieved` float NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_monitoring_checklist_score`
--

INSERT INTO `tbl_project_monitoring_checklist_score` (`id`, `projid`, `output_id`, `milestone_id`, `site_id`, `task_id`, `subtask_id`, `formid`, `achieved`, `created_by`, `created_at`) VALUES
(1, 96, 53, 34, 0, 101, 323, '2023-09-08', 444, 1, '2023-09-08'),
(2, 96, 53, 34, 0, 101, 323, '2023-09-08', 444, 1, '2023-09-08'),
(3, 96, 53, 34, 0, 101, 323, '2023-09-08', 444, 1, '2023-09-08'),
(4, 96, 54, 34, 136, 103, 317, '2023-09-08', 44, 1, '2023-09-08'),
(5, 96, 54, 34, 136, 103, 317, '2023-09-08', 62, 1, '2023-09-08'),
(6, 96, 54, 34, 136, 103, 317, '2023-09-08', 62, 1, '2023-09-08'),
(7, 96, 54, 34, 136, 103, 317, '2023-09-08', 62, 1, '2023-09-08'),
(8, 96, 54, 34, 136, 103, 317, '2023-09-08', 62, 1, '2023-09-08'),
(9, 96, 53, 34, 0, 101, 323, '2023-09-08', 25, 1, '2023-09-08'),
(10, 96, 53, 34, 0, 101, 323, '2023-09-10', 400, 1, '2023-09-10'),
(11, 96, 53, 34, 0, 101, 323, '2023-09-10', 400, 1, '2023-09-10'),
(12, 96, 53, 34, 0, 101, 323, '2023-09-10', 400, 1, '2023-09-10'),
(13, 96, 53, 34, 0, 101, 323, '2023-09-10', 10, 1, '2023-09-10'),
(14, 96, 53, 34, 0, 101, 323, '2023-09-10', 10, 1, '2023-09-10'),
(15, 84, 55, 42, 115, 115, 351, '2023-09-14', 10, 118, '2023-09-14'),
(16, 84, 55, 42, 115, 115, 351, '2023-09-14', 10, 118, '2023-09-14'),
(17, 84, 55, 42, 115, 115, 351, '2023-09-14', 10, 118, '2023-09-14'),
(18, 97, 56, 39, 138, 108, 337, '2023-09-15', 30, 1, '2023-09-15'),
(19, 84, 55, 42, 115, 114, 350, '2023-09-15', 10, 118, '2023-09-15'),
(20, 84, 55, 42, 115, 114, 350, '2023-09-15', 10, 118, '2023-09-15'),
(21, 84, 55, 42, 115, 114, 350, '2023-09-18', 200, 1, '2023-09-18'),
(22, 84, 55, 42, 115, 114, 350, '2023-09-18', 10, 1, '2023-09-18'),
(23, 84, 55, 42, 115, 114, 350, '2023-09-18', 222, 1, '2023-09-18'),
(24, 84, 55, 42, 115, 114, 350, '2023-09-18', 33, 1, '2023-09-18'),
(25, 84, 55, 42, 115, 114, 350, '2023-09-18', 111, 1, '2023-09-18');

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
  `question` varchar(255) NOT NULL,
  `parent` int DEFAULT NULL,
  `questiontype` int NOT NULL COMMENT '1 is main question while 2 is other questions',
  `answertype` int DEFAULT NULL COMMENT '1 is Yes/No; 2 is Number; 3 is text description'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `outcomeid` int DEFAULT NULL,
  `outputid` varchar(255) NOT NULL,
  `indicator` int NOT NULL,
  `data_source` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `monitoring_frequency` int NOT NULL,
  `data_collection_method` int DEFAULT NULL,
  `responsible` varchar(100) DEFAULT NULL,
  `report_user` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `reporting_timeline` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `mne_code` varchar(255) NOT NULL,
  `date_created` date NOT NULL,
  `created_by` int NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_outputs_mne_details`
--

INSERT INTO `tbl_project_outputs_mne_details` (`opid`, `projid`, `outcomeid`, `outputid`, `indicator`, `data_source`, `monitoring_frequency`, `data_collection_method`, `responsible`, `report_user`, `reporting_timeline`, `mne_code`, `date_created`, `created_by`, `changed_by`, `date_changed`) VALUES
(1, 3, 1, '5', 10, NULL, 0, NULL, '128', NULL, NULL, 'AB12335', '2023-03-15', 118, NULL, NULL),
(2, 1, 0, '1', 1, NULL, 0, NULL, '128', NULL, NULL, 'AB12311', '2023-03-16', 118, NULL, NULL),
(3, 1, 0, '2', 2, NULL, 0, NULL, '128', NULL, NULL, 'AB12312', '2023-03-16', 118, NULL, NULL),
(4, 1, 0, '3', 8, NULL, 0, NULL, '128', NULL, NULL, 'AB12313', '2023-03-16', 118, NULL, NULL),
(5, 7, 2, '10', 12, NULL, 0, NULL, '128', NULL, NULL, 'AB123710', '2023-03-18', 118, NULL, NULL),
(6, 6, 3, '8', 7, NULL, 0, NULL, '133', NULL, NULL, 'AB12368', '2023-03-21', 118, NULL, NULL),
(7, 6, 4, '9', 12, NULL, 0, NULL, '133', NULL, NULL, 'AB12369', '2023-03-21', 118, NULL, NULL),
(8, 69, 7, '25', 4, NULL, 0, NULL, NULL, NULL, NULL, 'AB1236925', '2023-05-17', 1, '1', '2023-05-19 00:00:00'),
(9, 69, 7, '26', 9, NULL, 0, NULL, NULL, NULL, NULL, 'AB1236926', '2023-05-17', 1, NULL, NULL),
(10, 78, 0, '37', 1, NULL, 0, NULL, NULL, NULL, NULL, 'AB1237837', '2023-08-08', 1, '1', '2023-08-08 00:00:00'),
(11, 78, 0, '38', 8, NULL, 0, NULL, NULL, NULL, NULL, 'AB1237838', '2023-08-08', 1, NULL, NULL),
(12, 96, 0, '53', 1, NULL, 0, NULL, NULL, NULL, NULL, 'AB1239653', '2023-09-06', 118, NULL, NULL),
(13, 97, 0, '0', 0, NULL, 4, NULL, NULL, NULL, NULL, 'AB12397', '2023-09-07', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_output_designs`
--

CREATE TABLE `tbl_project_output_designs` (
  `id` int NOT NULL,
  `output_id` int NOT NULL,
  `design` varchar(255) NOT NULL,
  `sites` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_output_designs`
--

INSERT INTO `tbl_project_output_designs` (`id`, `output_id`, `design`, `sites`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Chocolate', '0', 118, NULL, '2023-03-15', NULL),
(2, 2, 'Vanilla', '0', 118, NULL, '2023-03-15', NULL),
(3, 3, 'Strawberry', '1,2', 118, NULL, '2023-03-15', NULL),
(4, 5, 'Water Tank Design', '6,7', 118, NULL, '2023-03-15', NULL),
(9, 4, 'Borehole Design', '3,4,5', 118, NULL, '2023-03-15', NULL),
(10, 10, 'Water Pipeline Design', '0', 118, NULL, '2023-03-18', NULL),
(11, 8, 'Dam Design', '11', 118, NULL, '2023-03-18', NULL),
(12, 9, 'Water Pipeline Design', '0', 118, NULL, '2023-03-18', NULL),
(13, 6, 'Dam Design', '10', 118, NULL, '2023-03-18', NULL),
(14, 7, 'Treatment Plant', '10', 118, NULL, '2023-03-18', NULL),
(15, 11, 'Design 1', '29', 1, NULL, '2023-04-11', NULL),
(16, 22, 'Design', '0', 1, NULL, '2023-04-23', NULL),
(17, 23, 'Design 2', '0', 1, NULL, '2023-04-25', NULL),
(18, 21, 'Sewer Line Installation', '0', 1, 1, '2023-05-03', '2023-05-03'),
(19, 24, 'Design 1', '0', 1, NULL, '2023-05-05', NULL),
(20, 25, 'Design Name 1', '65,66,67', 1, NULL, '2023-05-15', NULL),
(21, 26, 'Design 12', '65,67', 1, NULL, '2023-05-15', NULL),
(22, 27, 'Tarmacked Road', '0', 124, 124, '2023-05-30', '2023-05-30'),
(23, 28, 'Standard Bridge ', '69', 124, NULL, '2023-05-31', NULL),
(24, 29, 'Design 1', '70', 1, NULL, '2023-06-10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_output_details`
--

CREATE TABLE `tbl_project_output_details` (
  `id` int NOT NULL,
  `unique_key` varchar(255) NOT NULL,
  `progid` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int DEFAULT NULL,
  `indicator` int NOT NULL,
  `year` int NOT NULL,
  `target` int NOT NULL,
  `budget` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_output_details`
--

INSERT INTO `tbl_project_output_details` (`id`, `unique_key`, `progid`, `projid`, `outputid`, `indicator`, `year`, `target`, `budget`) VALUES
(1, 'FSi8I0kqDQ', 1, 1, 1, 1, 2022, 20, 300000000),
(2, 'FSi8I0kqDQ', 1, 1, 1, 1, 2023, 25, 380000000),
(3, 'FSi8I0kqDQ', 1, 1, 2, 2, 2022, 2, 40000000),
(4, 'FSi8I0kqDQ', 1, 1, 2, 2, 2023, 4, 100000000),
(5, 'FSi8I0kqDQ', 1, 1, 3, 8, 2022, 2, 200000000),
(6, 'hwtZpr18Ub', 2, 2, 4, 4, 2022, 9, 20000000),
(7, 'xjUbtFQxUg', 2, 3, 5, 10, 2022, 4, 41000000),
(8, '41yEFQxnFt', 2, 5, 6, 7, 2022, 1, 500000000),
(9, '41yEFQxnFt', 2, 5, 7, 9, 2022, 1, 500000000),
(10, 'YBftO5sE3P', 2, 6, 8, 7, 2022, 1, 100000000),
(11, 'YBftO5sE3P', 2, 6, 9, 12, 2022, 20, 1000000000),
(12, 'GAJK7tK7aS', 2, 7, 10, 12, 2022, 20, 500000000),
(13, 'VWlkgNWOAN', 4, 15, 11, 8, 2023, 5, 60000000),
(14, 'VWlkgNWOAN', 4, 15, 12, 1, 2023, 7, 180000000),
(15, 'AHW9vtpuiK', 4, 53, 13, 8, 2023, 4, 40000000),
(16, 'rrNWy45hU3', 4, 52, 14, 1, 2023, 5, 15000000),
(17, 'B30xbZg1Yh', 4, 54, 15, 1, 2023, 30, 10000000),
(18, 'xNAXavKqT3', 4, 55, 16, 2, 2023, 10, 5000000),
(25, 'rwYx0yXrPk', 2, 59, 23, 18, 2022, 2, 200),
(24, 'ULIRQxPl1Z', 3, 62, 22, 12, 2022, 1, 1000),
(21, '621WEzaUTf', 4, 51, 19, 1, 2023, 3, 50000000),
(22, 'Eoi140KNVE', 4, 56, 20, 1, 2023, 10, 60000000),
(23, 'KgcMXZwbVe', 2, 58, 21, 18, 2022, 10, 500000000),
(26, 'cVZqhbcC3u', 1, 66, 24, 1, 2022, 12, 120000),
(27, '5vAmYsQc3r', 2, 69, 25, 4, 2022, 20, 20000000),
(28, '5vAmYsQc3r', 2, 69, 25, 4, 2023, 4, 20000000),
(29, '5vAmYsQc3r', 2, 69, 26, 9, 2022, 2, 1000000),
(30, '5vAmYsQc3r', 2, 69, 26, 9, 2023, 2, 1000000),
(31, 'LOEb1fECU7', 1, 70, 27, 1, 2022, 29, 50000000),
(32, 'LOEb1fECU7', 1, 70, 28, 8, 2022, 1, 20000000),
(33, 'oxH7TzGQFE', 3, 71, 29, 21, 2022, 10, 20000000),
(34, 'oxH7TzGQFE', 3, 71, 29, 21, 2023, 20, 50000000),
(35, 'S6jORghkYv', 2, 72, 30, 4, 2022, 2, 9999998),
(36, 'grs4zZ4fbX', 10, 74, 31, 4, 2022, 4, 50000000),
(47, 's55pZXfG9W', 9, 75, 32, 1, 2023, 10, 1000000),
(48, '9u90JNqRjm', 9, 76, 33, 1, 2023, 10, 1000000),
(49, '9u90JNqRjm', 9, 76, 34, 8, 2023, 4, 1000000),
(50, 'NgolZqjA8v', 1, 77, 35, 1, 2023, 10, 50000000),
(51, 'NgolZqjA8v', 1, 77, 36, 8, 2023, 3, 100),
(52, 'tIWZztgaTX', 1, 78, 37, 1, 2022, 20, 200000000),
(53, 'tIWZztgaTX', 1, 78, 38, 8, 2022, 5, 100000000),
(54, 'oImnDqM0QV', 1, 79, 39, 1, 2022, 30, 60000000),
(55, 'oImnDqM0QV', 1, 79, 40, 8, 2022, 3, 6000000),
(56, 'TLVvNyyn5J', 11, 81, 41, 25, 2023, 300, 30000000),
(57, 'kbPDExHFLh', 2, 82, 42, 10, 2022, 3, 50000000),
(58, 'bJtLMRcyXQ', 12, 85, 43, 26, 2023, 1, 10000000),
(59, 'TAEOEOUmz5', 13, 86, 44, 8, 2021, 1, 1000000),
(60, 'zTG4DUvCqB', 13, 87, 45, 8, 2022, 1, 10000000),
(61, '3WjADBEccC', 14, 88, 46, 8, 2023, 1, 20000000),
(62, 'y1lfFIxza0', 15, 89, 47, 8, 2023, 1, 9999995),
(63, 'cuepEVj5V9', 16, 91, 48, 30, 2023, 20, 2000000),
(64, 'AXcBjiOMW4', 11, 93, 49, 25, 2023, 100, 1000000),
(65, 'Sve871xHqm', 1, 94, 50, 1, 2022, 2, 3000000),
(66, 'hOSj3SufS9', 1, 95, 51, 1, 2022, 25, 50000000),
(67, 'hOSj3SufS9', 1, 95, 52, 8, 2022, 4, 6000000),
(68, 'HO03OC5cCZ', 1, 96, 53, 1, 2022, 10, 600000000),
(71, 'HO03OC5cCZ', 1, 96, 54, 8, 2022, 2, 3000000),
(79, 'WWpkgR1rTc', 12, 84, 55, 26, 2023, 1, 1000000),
(73, 'u6lowGwJ0U', 2, 97, 56, 4, 2023, 6, 60000000),
(74, 'F0MG3frYuQ', 11, 98, 57, 25, 2023, 50, 2500000),
(75, 'F0MG3frYuQ', 11, 98, 57, 25, 2024, 50, 2500000),
(76, 'K1ISaJJSer', 2, 100, 58, 10, 2022, 3, 32000000),
(78, 'vkPmOPwFqX', 12, 101, 59, 27, 2023, 1, 5000000),
(81, '7q0NgFM0MC', 12, 83, 60, 28, 2023, 2, 2000000);

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
-- Table structure for table `tbl_project_payment_plan`
--

CREATE TABLE `tbl_project_payment_plan` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `payment_plan` text NOT NULL,
  `percentage` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_payment_plan`
--

INSERT INTO `tbl_project_payment_plan` (`id`, `projid`, `payment_plan`, `percentage`) VALUES
(56, 78, 'Phase 1', 30),
(57, 78, 'Phase 2', 40),
(58, 78, 'Phase 3', 30),
(59, 96, '1st PAYMENT', 40),
(60, 96, '2ND Payment', 30),
(61, 96, '3rd Payment', 30),
(62, 109, 'phase1', 60),
(63, 109, 'phase 2', 40);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_payment_plan_details`
--

CREATE TABLE `tbl_project_payment_plan_details` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `payment_plan_id` int NOT NULL,
  `milestone_id` int NOT NULL,
  `payment_status` int NOT NULL DEFAULT '0',
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_payment_plan_details`
--

INSERT INTO `tbl_project_payment_plan_details` (`id`, `projid`, `payment_plan_id`, `milestone_id`, `payment_status`, `created_by`, `created_at`) VALUES
(37, 78, 56, 8, 0, 1, '2023-08-12'),
(38, 78, 57, 7, 0, 1, '2023-08-12'),
(39, 78, 58, 10, 0, 1, '2023-08-12'),
(40, 96, 59, 34, 0, 118, '2023-09-06'),
(41, 96, 60, 35, 0, 118, '2023-09-06'),
(42, 96, 61, 36, 0, 118, '2023-09-06'),
(43, 109, 62, 43, 0, 118, '2023-09-29'),
(44, 109, 63, 44, 0, 118, '2023-09-29');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_photos`
--

CREATE TABLE `tbl_project_photos` (
  `fid` int NOT NULL,
  `projid` int DEFAULT NULL,
  `monitoringid` int NOT NULL DEFAULT '0',
  `task_id` int NOT NULL DEFAULT '0',
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
(1, 3, 3, 5, 4, '', '2023-04-01', '118', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_sites`
--

CREATE TABLE `tbl_project_sites` (
  `site_id` int NOT NULL,
  `unique_key` varchar(255) NOT NULL,
  `projid` int NOT NULL,
  `state_id` int NOT NULL,
  `site` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_sites`
--

INSERT INTO `tbl_project_sites` (`site_id`, `unique_key`, `projid`, `state_id`, `site`) VALUES
(1, 'FSi8I0kqDQ', 1, 377, 'Kwa Chief'),
(2, 'FSi8I0kqDQ', 1, 377, 'PAG CHURCH'),
(3, 'hwtZpr18Ub', 2, 357, 'Chemoiben'),
(4, 'hwtZpr18Ub', 2, 357, 'Litein'),
(5, 'hwtZpr18Ub', 2, 358, 'Chemosot'),
(6, 'xjUbtFQxUg', 3, 346, 'Litein'),
(7, 'xjUbtFQxUg', 3, 384, 'Chemosot'),
(10, '41yEFQxnFt', 5, 387, 'Kimwarer'),
(11, 'YBftO5sE3P', 6, 374, 'Itare'),
(12, 'Fsq7LdLcrg', 8, 3, 'Dandora'),
(13, 'Fsq7LdLcrg', 8, 3, 'Utawala'),
(14, 'Fsq7LdLcrg', 8, 3, 'Ruai'),
(19, 'z3O1kH2OMX', 16, 357, 'Equity'),
(20, 'z3O1kH2OMX', 16, 409, 'Kipsabuge AIC'),
(21, 'z3O1kH2OMX', 16, 409, 'Kipsomba bridge'),
(22, 'z3O1kH2OMX', 16, 357, 'Kapkures Cattle Dip'),
(25, 'VWlkgNWOAN', 15, 350, 'Ainaptich Bridge'),
(26, 'VWlkgNWOAN', 15, 351, 'Olare Bridge'),
(27, 'VWlkgNWOAN', 15, 353, 'Murgusi SDA'),
(28, 'VWlkgNWOAN', 15, 350, 'Tumaini Shop Culvert'),
(29, 'VWlkgNWOAN', 15, 350, 'Chamchi Shop Culvert'),
(52, 'AHW9vtpuiK', 53, 397, 'Kapchemosin Kinyozi'),
(53, 'AHW9vtpuiK', 53, 397, 'Cheringa Salon'),
(54, 'AHW9vtpuiK', 53, 397, 'Chepnyongaa Pool Bar'),
(55, 'AHW9vtpuiK', 53, 397, 'Nila baby shop'),
(56, 'ULIRQxPl1Z', 62, 407, 'Site 1'),
(57, 'ULIRQxPl1Z', 62, 407, 'Site2'),
(58, 'ULIRQxPl1Z', 62, 407, ' Site3'),
(59, 'ZlXZYJzPMM', 4, 3, 'Utawala'),
(60, 'ZlXZYJzPMM', 4, 3, 'Ruai'),
(61, 'xOlSdTu0nh', 63, 389, 'Site 1'),
(62, 'xOlSdTu0nh', 63, 389, 'Site 2'),
(63, 'QNfOYsa0LH', 64, 394, 'Site 1'),
(64, 'QNfOYsa0LH', 64, 394, ' Site 3'),
(65, '5vAmYsQc3r', 69, 348, 'Site 1'),
(66, '5vAmYsQc3r', 69, 348, ' Site 2'),
(67, '5vAmYsQc3r', 69, 348, ' Site 3'),
(68, '5vAmYsQc3r', 69, 348, ' Site 4'),
(69, 'LOEb1fECU7', 70, 377, 'kapkeben river bridge'),
(70, 'oxH7TzGQFE', 71, 387, 'Site One'),
(71, 'S6jORghkYv', 72, 375, 'Site X'),
(72, 'S6jORghkYv', 72, 376, 'Site Y'),
(73, 'grs4zZ4fbX', 74, 384, 'Site X'),
(74, 'grs4zZ4fbX', 74, 384, 'Site Y'),
(75, 'grs4zZ4fbX', 74, 385, 'Site AA'),
(76, 'grs4zZ4fbX', 74, 385, 'Site BB'),
(77, 's55pZXfG9W', 75, 339, 'Site 1'),
(78, 's55pZXfG9W', 75, 339, ' Site 2'),
(79, 's55pZXfG9W', 75, 339, ' Site 3'),
(80, '9u90JNqRjm', 76, 396, '1'),
(81, '9u90JNqRjm', 76, 396, '2'),
(82, '9u90JNqRjm', 76, 396, '3'),
(83, '9u90JNqRjm', 76, 397, '1'),
(84, '9u90JNqRjm', 76, 397, '2'),
(85, '9u90JNqRjm', 76, 397, '3'),
(86, '9u90JNqRjm', 76, 399, '1'),
(87, '9u90JNqRjm', 76, 399, '2'),
(88, '9u90JNqRjm', 76, 399, '3'),
(89, 'NgolZqjA8v', 77, 373, 'safaricom shop'),
(90, 'NgolZqjA8v', 77, 373, ' Tumaini Kinyozi'),
(91, 'NgolZqjA8v', 77, 373, ' Kapkrisopa Hotel'),
(92, 'tIWZztgaTX', 78, 409, 'Chelabal Shop'),
(93, 'tIWZztgaTX', 78, 409, ' Equity bank'),
(94, 'tIWZztgaTX', 78, 409, ' Chrisco Church'),
(95, 'tIWZztgaTX', 78, 357, 'Simba Energies'),
(96, 'tIWZztgaTX', 78, 357, ' Kapkata Butchery'),
(97, 'tIWZztgaTX', 78, 409, 'Tumaini Hotel'),
(98, 'tIWZztgaTX', 78, 409, ' Fish Point'),
(102, 'oImnDqM0QV', 79, 348, 'Kwa Milimu'),
(103, 'oImnDqM0QV', 79, 348, ' Kokwa Tai'),
(104, 'oImnDqM0QV', 79, 348, ' Kapsudi'),
(105, 'L2qcQMGofg', 80, 397, ' Kings Barber Shop'),
(106, 'L2qcQMGofg', 80, 397, ' Tulwet AIC'),
(107, 'L2qcQMGofg', 80, 399, 'Lingwai Cattle Dip'),
(111, 'kbPDExHFLh', 82, 375, 'Site A'),
(112, 'kbPDExHFLh', 82, 375, ' Site B'),
(113, 'kbPDExHFLh', 82, 375, ' Site C'),
(115, 'WWpkgR1rTc', 84, 372, 'Tapsagoi'),
(116, 'bJtLMRcyXQ', 85, 351, 'Olare'),
(117, 'TAEOEOUmz5', 86, 384, 'Daramura Hospital'),
(118, 'zTG4DUvCqB', 87, 413, 'Kamukunji Hospital'),
(119, '3WjADBEccC', 88, 362, 'Toror Hospital Immunization Center'),
(120, 'y1lfFIxza0', 89, 412, 'River Kaplelach'),
(121, 'ZgHR9B8fuH', 90, 409, 'whole area of Kipsomba'),
(122, 'cuepEVj5V9', 91, 357, 'Whole area of Kapkures'),
(125, 'Sve871xHqm', 94, 321, 'Site 1'),
(126, 'Sve871xHqm', 94, 321, ' Test 4'),
(127, 'Sve871xHqm', 94, 321, ' Site 3'),
(128, 'Sve871xHqm', 94, 322, 'Site 2'),
(129, 'Sve871xHqm', 94, 322, ' Site 5'),
(130, 'hOSj3SufS9', 95, 335, 'Kapkulumben'),
(131, 'hOSj3SufS9', 95, 335, ' Chepkanga'),
(132, 'hOSj3SufS9', 95, 338, 'Tembelio Center'),
(133, 'hOSj3SufS9', 95, 338, ' Kap Nashon'),
(134, '7q0NgFM0MC', 83, 327, 'Site 1'),
(135, '7q0NgFM0MC', 83, 327, ' Site 2'),
(136, 'HO03OC5cCZ', 96, 321, 'Choimim Cattle dip'),
(137, 'HO03OC5cCZ', 96, 321, ' Imani Church'),
(138, 'u6lowGwJ0U', 97, 317, 'Site1'),
(139, 'u6lowGwJ0U', 97, 317, 'Site2'),
(140, 'u6lowGwJ0U', 97, 317, 'Site3'),
(141, 'K1ISaJJSer', 100, 312, 'Gatina'),
(142, 'K1ISaJJSer', 100, 312, ' Kawangware'),
(143, 'K1ISaJJSer', 100, 312, ' Huruma'),
(144, 'vkPmOPwFqX', 101, 324, 'Moi Bridge'),
(145, 'jd83uNtwsR', 102, 318, 'XXX'),
(146, 'qh2HEcaEts', 105, 331, 'Tumaini'),
(147, 'qh2HEcaEts', 105, 331, ' Kaplelach'),
(148, 'qh2HEcaEts', 105, 331, ' Joska'),
(153, 'P9w0dm4qqR', 109, 321, 'Kapchumba'),
(154, 'P9w0dm4qqR', 109, 321, ' Kapkili'),
(155, 'P9w0dm4qqR', 109, 331, 'Ombi Letu'),
(156, 'P9w0dm4qqR', 109, 331, ' Angaza Kiosk'),
(157, '66FBYYWcTn', 110, 320, 'iiqoie'),
(158, '66FBYYWcTn', 110, 329, 'kjaheuj'),
(159, 'Uzs8kN8HrX', 116, 310, 'Kapsenetwet'),
(160, 'Uzs8kN8HrX', 116, 310, ' Kusumek');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_specifications`
--

CREATE TABLE `tbl_project_specifications` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `output_id` int NOT NULL,
  `task_id` int NOT NULL,
  `site_id` int NOT NULL,
  `specification` text NOT NULL,
  `standard_id` varchar(30) DEFAULT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_specifications`
--

INSERT INTO `tbl_project_specifications` (`id`, `projid`, `output_id`, `task_id`, `site_id`, `specification`, `standard_id`, `created_by`, `created_at`) VALUES
(1, 1, 3, 5, 5, 'testspec', '1', 118, '2023-03-15'),
(2, 1, 3, 6, 7, 'SPEC111', '1', 118, '2023-03-15'),
(3, 1, 3, 6, 6, 'test', '1', 118, '2023-03-15'),
(4, 1, 3, 6, 8, '3 yrs experience ', '1', 118, '2023-03-15'),
(5, 1, 3, 6, 11, 'testspec', '1', 118, '2023-03-15'),
(6, 1, 3, 6, 10, 'testspec', '1', 118, '2023-03-15'),
(7, 1, 3, 6, 9, 'testspec', '1', 118, '2023-03-15'),
(8, 3, 5, 7, 12, 'Specification1', '', 118, '2023-03-15'),
(9, 3, 5, 7, 12, 'Specification2', '', 118, '2023-03-15'),
(10, 3, 5, 7, 12, 'Specification3', '', 118, '2023-03-15'),
(11, 3, 5, 8, 13, 'Specification1', '', 118, '2023-03-15'),
(12, 3, 5, 8, 13, 'Specification2', '', 118, '2023-03-15'),
(13, 3, 5, 8, 13, 'Specification3', '', 118, '2023-03-15'),
(14, 1, 1, 1, 14, 'testspec', '1', 118, '2023-03-15'),
(15, 1, 1, 1, 15, 'testspec', '1', 118, '2023-03-15'),
(16, 1, 1, 2, 16, 'testspec', '1', 118, '2023-03-15'),
(17, 1, 1, 9, 17, 'testspec', '1', 118, '2023-03-15'),
(18, 1, 1, 9, 18, 'testspec', '1', 118, '2023-03-15'),
(19, 1, 2, 3, 1, 'testspec', '1', 118, '2023-03-15'),
(20, 1, 2, 3, 2, 'testspec', '1', 118, '2023-03-15'),
(21, 1, 2, 4, 3, 'spec1', '1', 118, '2023-03-15'),
(22, 1, 2, 4, 4, 'testspec', '1', 118, '2023-03-15'),
(23, 2, 4, 10, 27, ' report clearly indicating if it is viable to drill a borehole.', '', 118, '2023-03-15'),
(24, 2, 4, 11, 28, 'Total depth – 350m of 200mm diameter from surface ', '', 118, '2023-03-15'),
(25, 7, 10, 12, 30, 'Top soil not exceeding 1.2m deep and 0.8m wide to receive 160mm PN 10 HDPE Pipes to KS ISO-06- 1452 Part 2:2009. Rate shall include cart away surplus materials from site as directed by engineer', '', 118, '2023-03-18'),
(26, 7, 10, 13, 29, ' Soft red soil surround or equivalent material', '', 118, '2023-03-18'),
(27, 7, 10, 14, 31, 'Lay and joint 160mm PN 10 HDPE Pipes to KS ISO-06-1452 Part 2:2009. Rates to include for all jointing, cutting wastage.', '', 118, '2023-03-18'),
(28, 6, 8, 15, 32, 'Depth of 0.30m below the stripped level of 300mm below existing ground level or as directed by the Engineer', '', 118, '2023-03-18'),
(29, 6, 8, 16, 33, 'Provision and placement of 0.06m 1:3:6 concrete blinding layers to sill bases 5 no.', '', 118, '2023-03-18'),
(30, 6, 8, 16, 34, 'Compact mass concrete of mix ratio of 1:2:4 and construct sills size 26m x 1m x 0.7m as indicated in the drawings', '', 118, '2023-03-18'),
(31, 6, 9, 18, 36, '160mm PN 10 HDPE Pipes to KS ISO-06-1452 Part 2:2009. Rates to include for all jointing, cutting wastage. ', '', 118, '2023-03-18'),
(32, 6, 9, 17, 35, 'soft red soil surround or equivalent material then using excavated materials and compact in 200mm layerfills as approved by the Engineer.', '', 118, '2023-03-18'),
(33, 5, 6, 19, 37, 'Depth of 0.30m below the stripped level of 300mm below existing ground level or as directed by the Engineer', '', 118, '2023-03-18'),
(34, 5, 6, 21, 38, '0.06m 1:3:6 concrete blinding layers to sill bases 5 no.', '', 118, '2023-03-18'),
(35, 5, 7, 22, 39, 'Depth of 0.30m below the stripped level of 300mm below existing ground level or as directed by the Engineer', '', 118, '2023-03-18'),
(36, 5, 7, 23, 40, '0.06m 1:3:6 concrete blinding layers to sill bases 5 no.', '', 118, '2023-03-18'),
(37, 62, 22, 26, 43, 'Test', '1', 1, '2023-04-23'),
(38, 62, 22, 25, 42, 'Test name', '1', 1, '2023-04-23'),
(40, 59, 23, 29, 44, 'Specification 3', '', 1, '2023-04-25'),
(41, 59, 23, 28, 45, 'Exercitation quis as', '', 1, '2023-04-26'),
(42, 59, 23, 28, 45, 'Ut voluptas et minus', '', 1, '2023-04-26'),
(43, 59, 23, 28, 45, 'Architecto suscipit ', '', 1, '2023-04-26'),
(44, 58, 21, 31, 46, 'Laborum Hic id perf', '1', 1, '2023-05-03'),
(45, 58, 21, 31, 46, 'Obcaecati optio ips', '1', 1, '2023-05-03'),
(46, 58, 21, 31, 46, 'Vel dolor dolores it', '1', 1, '2023-05-03'),
(47, 58, 21, 31, 46, 'Dolores enim unde al', '1', 1, '2023-05-03'),
(48, 58, 21, 31, 47, 'Quod optio impedit', '1', 1, '2023-05-03'),
(49, 58, 21, 31, 47, 'Commodo vel non quam', '1', 1, '2023-05-03'),
(50, 58, 21, 31, 47, 'Autem qui sunt adipi', '1', 1, '2023-05-03'),
(51, 58, 21, 31, 47, 'Ex molestiae cupidat', '1', 1, '2023-05-03'),
(52, 58, 21, 31, 48, 'Alias voluptas ipsam', '1', 1, '2023-05-03'),
(53, 58, 21, 31, 48, 'Et ullam est simili', '1', 1, '2023-05-03'),
(54, 58, 21, 31, 48, 'Illum qui enim vita', '', 1, '2023-05-03'),
(55, 58, 21, 31, 48, 'Aspernatur quia perf', '', 1, '2023-05-03'),
(56, 58, 21, 32, 49, 'Earum magna voluptat', '1', 1, '2023-05-03'),
(57, 58, 21, 32, 49, 'Quas eveniet sit od', '1', 1, '2023-05-03'),
(58, 58, 21, 30, 50, 'Velit velit adipisi', '1', 1, '2023-05-03'),
(59, 58, 21, 30, 50, 'Duis sit odio est o', '1', 1, '2023-05-03'),
(60, 58, 21, 34, 53, 'Quia in quia aut sun', '1', 1, '2023-05-03'),
(61, 58, 21, 34, 53, 'Sunt qui cupiditate', '1', 1, '2023-05-03'),
(62, 58, 21, 34, 53, 'Sunt ullam quisquam', '', 1, '2023-05-03'),
(63, 58, 21, 34, 53, 'Culpa exercitatione', '', 1, '2023-05-03'),
(64, 58, 21, 33, 52, 'Proident id harum i', '1', 1, '2023-05-03'),
(65, 58, 21, 33, 52, 'Rem quos cumque veli', '1', 1, '2023-05-03'),
(66, 58, 21, 33, 52, 'Molestias sed laboru', '1', 1, '2023-05-03'),
(67, 58, 21, 33, 52, 'Expedita iure numqua', '1', 1, '2023-05-03'),
(68, 58, 21, 35, 51, 'Minima in enim neque', '1', 1, '2023-05-03'),
(69, 58, 21, 35, 51, 'Magni quia nulla vol', '1', 1, '2023-05-03'),
(70, 66, 24, 49, 55, 'Specification 1', '1', 1, '2023-05-05'),
(71, 66, 24, 49, 55, 'Specification 2', '1', 1, '2023-05-05'),
(72, 66, 24, 49, 56, 'Specification 4', '1', 1, '2023-05-05'),
(73, 66, 24, 49, 56, 'Specification 5', '1', 1, '2023-05-05'),
(74, 66, 24, 50, 54, 'Specification 9', '1', 1, '2023-05-05'),
(75, 66, 24, 50, 54, 'Specification 7', '', 1, '2023-05-05'),
(76, 69, 25, 51, 57, 'Spec 1', '', 1, '2023-05-15'),
(77, 69, 25, 51, 57, 'Spec 2', '', 1, '2023-05-15'),
(78, 69, 25, 51, 58, 'Spec 34', '', 1, '2023-05-15'),
(79, 69, 25, 52, 59, 'Spec 45', '', 1, '2023-05-15'),
(80, 69, 25, 52, 60, 'Spec 22', '', 1, '2023-05-15'),
(81, 69, 26, 53, 61, 'Spec 4', '1', 1, '2023-05-15'),
(82, 69, 26, 54, 62, 'Spec 45', '1', 1, '2023-05-15'),
(83, 71, 29, 82, 63, 'Specification 1', '1', 1, '2023-06-10'),
(84, 71, 29, 82, 64, 'Specification 2', '1', 1, '2023-06-10'),
(85, 71, 29, 81, 65, 'Eaque libero nulla e', '1', 1, '2023-06-10'),
(86, 71, 29, 81, 65, 'Praesentium natus si', '1', 1, '2023-06-10'),
(87, 71, 29, 81, 66, 'Omnis quasi esse adi', '1', 1, '2023-06-10'),
(88, 71, 29, 81, 66, 'Do commodo mollit mi', '1', 1, '2023-06-10'),
(89, 75, 32, 83, 67, 'Et labore dolor solu', '1', 1, '2023-08-02'),
(90, 75, 32, 83, 67, 'Nostrum ut dignissim', '1', 1, '2023-08-02'),
(91, 75, 32, 83, 68, 'Qui ex atque culpa ', '1', 1, '2023-08-02'),
(92, 75, 32, 83, 68, 'Minus rerum iusto ve', '1', 1, '2023-08-02'),
(93, 75, 32, 83, 68, 'Corporis nulla asper', '', 1, '2023-08-02'),
(94, 75, 32, 83, 69, 'Dolor repellendus A', '1', 1, '2023-08-02'),
(95, 75, 32, 83, 69, 'Et commodi sequi id ', '1', 1, '2023-08-02'),
(96, 75, 32, 86, 70, 'Est odit soluta dol', '1', 1, '2023-08-02'),
(97, 75, 32, 86, 70, 'Enim vel enim distin', '1', 1, '2023-08-02'),
(98, 75, 32, 86, 71, 'Esse qui architecto', '1', 1, '2023-08-02'),
(99, 75, 32, 86, 71, 'Unde nobis optio du', '1', 1, '2023-08-02'),
(100, 75, 32, 86, 71, 'Officia rerum at cor', '1', 1, '2023-08-02'),
(101, 75, 32, 88, 76, 'Hic a eveniet magna', '1', 1, '2023-08-02'),
(102, 75, 32, 88, 76, 'Laborum Officiis po', '1', 1, '2023-08-02'),
(103, 75, 32, 88, 76, 'Aut incidunt non el', '1', 1, '2023-08-02'),
(104, 75, 32, 88, 75, 'Dolor magni neque li', '1', 1, '2023-08-02'),
(105, 75, 32, 88, 75, 'Qui ea fugiat in il', '1', 1, '2023-08-02'),
(106, 75, 32, 86, 72, 'Id sunt et et velit ', '1', 1, '2023-08-02'),
(107, 75, 32, 86, 72, 'Doloremque vero aliq', '1', 1, '2023-08-02'),
(108, 75, 32, 86, 72, 'Magnam id exercitati', '1', 1, '2023-08-02'),
(109, 75, 32, 87, 78, 'Nostrud amet ex off', '1', 1, '2023-08-02'),
(110, 75, 32, 87, 78, 'Et facere eveniet i', '1', 1, '2023-08-02'),
(111, 75, 32, 87, 78, 'Et soluta aliquam id', '1', 1, '2023-08-02'),
(112, 75, 32, 87, 77, 'Atque omnis in corru', '1', 1, '2023-08-02'),
(113, 75, 32, 87, 77, 'Eveniet perferendis', '1', 1, '2023-08-02'),
(114, 75, 32, 87, 77, 'Placeat ut perferen', '1', 1, '2023-08-02'),
(115, 75, 32, 87, 79, 'Qui impedit ad cons', '1', 1, '2023-08-02'),
(116, 75, 32, 87, 79, 'Molestias non impedi', '1', 1, '2023-08-02'),
(117, 75, 32, 87, 79, 'Sapiente repellendus', '1', 1, '2023-08-02'),
(118, 75, 32, 88, 74, 'Ab velit enim volupt', '1', 1, '2023-08-02'),
(119, 75, 32, 88, 74, 'Quia ullamco non qua', '1', 1, '2023-08-02'),
(120, 75, 32, 88, 74, 'Minim ad quisquam qu', '1', 1, '2023-08-02'),
(121, 75, 32, 88, 74, 'Illo commodo velit ', '1', 1, '2023-08-02'),
(122, 75, 32, 86, 73, 'Incididunt architect', '1', 1, '2023-08-02'),
(123, 75, 32, 86, 73, 'Quod dolor ratione o', '1', 1, '2023-08-02'),
(124, 75, 32, 86, 73, 'Minim enim rem bland', '1', 1, '2023-08-02'),
(125, 74, 31, 89, 80, 'Et nesciunt aliqua', '1', 1, '2023-08-02'),
(126, 74, 31, 89, 80, 'Ea quam dolorem odio', '1', 1, '2023-08-02'),
(127, 74, 31, 89, 80, 'In aliquam ut vitae ', '1', 1, '2023-08-02'),
(128, 78, 37, 137, 0, 'Tenetur et sit et d', '1', 1, '2023-08-09'),
(129, 78, 37, 137, 0, 'Repudiandae sit magn', '1', 1, '2023-08-09'),
(130, 78, 37, 137, 0, 'Hic pariatur Est m', '1', 1, '2023-08-09'),
(131, 78, 37, 138, 0, 'Incidunt qui qui pe', '1', 1, '2023-08-09'),
(132, 78, 37, 138, 0, 'Odio assumenda ipsum', '1', 1, '2023-08-09'),
(133, 78, 37, 138, 0, 'Sapiente magna in ne', '1', 1, '2023-08-09'),
(134, 78, 37, 139, 0, 'Dolor et nulla nisi ', '1', 1, '2023-08-09'),
(135, 78, 37, 139, 0, 'Vel consequatur et ', '1', 1, '2023-08-09'),
(136, 78, 37, 139, 0, 'Est enim ad voluptat', '1', 1, '2023-08-09'),
(137, 78, 37, 139, 0, 'Quibusdam voluptates', '1', 1, '2023-08-09'),
(138, 78, 37, 140, 0, 'Laborum sed et dolor', '1', 1, '2023-08-09'),
(139, 78, 37, 140, 0, 'Ut qui adipisicing c', '1', 1, '2023-08-09'),
(140, 78, 37, 140, 0, 'Est qui explicabo P', '1', 1, '2023-08-09'),
(141, 78, 37, 141, 0, 'Eum ullam non autem ', '1', 1, '2023-08-09'),
(142, 78, 37, 141, 0, 'Natus suscipit sit i', '1', 1, '2023-08-09'),
(143, 78, 37, 141, 0, 'Rerum iusto sunt dol', '1', 1, '2023-08-09'),
(144, 78, 37, 141, 0, 'Sunt aspernatur aut ', '1', 1, '2023-08-09'),
(145, 78, 37, 142, 0, 'Proident amet labo', '1', 1, '2023-08-09'),
(146, 78, 37, 142, 0, 'Et numquam et tempor', '1', 1, '2023-08-09'),
(147, 78, 37, 142, 0, 'Pariatur Impedit l', '1', 1, '2023-08-09'),
(148, 78, 37, 142, 0, 'Et maiores aliqua A', '1', 1, '2023-08-09'),
(149, 78, 37, 143, 0, 'Dolore duis ut proid', '1', 1, '2023-08-09'),
(150, 78, 37, 143, 0, 'Officia velit velit', '1', 1, '2023-08-09'),
(151, 78, 37, 143, 0, 'Veritatis optio qui', '1', 1, '2023-08-09'),
(152, 78, 37, 144, 0, 'Eum eligendi cum sed', '1', 1, '2023-08-09'),
(153, 78, 37, 144, 0, 'Accusamus qui ex eve', '1', 1, '2023-08-09'),
(154, 78, 37, 144, 0, 'Ab natus est minus ', '1', 1, '2023-08-09'),
(170, 78, 37, 145, 0, 'Mollitia omnis aliqu', '1', 1, '2023-08-09'),
(171, 78, 37, 145, 0, 'Nulla consequuntur c', '1', 1, '2023-08-09'),
(172, 78, 37, 145, 0, 'Et rerum libero dolo', '1', 1, '2023-08-09'),
(173, 78, 37, 145, 0, 'Atque quis tempor la', '1', 1, '2023-08-09'),
(174, 78, 37, 146, 0, 'Similique consectetu', '1', 1, '2023-08-09'),
(175, 78, 37, 146, 0, 'Ut nulla libero blan', '1', 1, '2023-08-09'),
(176, 78, 37, 146, 0, 'Sed iste eos repelle', '1', 1, '2023-08-09'),
(177, 78, 37, 146, 0, 'Expedita qui tempori', '1', 1, '2023-08-09'),
(220, 78, 38, 155, 98, 'Eaque obcaecati hic ', '1', 1, '2023-08-09'),
(221, 78, 38, 155, 98, 'Reiciendis eligendi ', '1', 1, '2023-08-09'),
(222, 78, 38, 155, 98, 'Quibusdam quis cum i', '1', 1, '2023-08-09'),
(223, 78, 38, 156, 98, 'Perspiciatis error ', '1', 1, '2023-08-09'),
(224, 78, 38, 156, 98, 'Ut commodo autem arc', '1', 1, '2023-08-09'),
(225, 78, 38, 156, 98, 'Neque sed sunt molli', '1', 1, '2023-08-09'),
(226, 78, 38, 156, 98, 'Iure quisquam natus ', '', 1, '2023-08-09'),
(227, 78, 38, 157, 98, 'A nesciunt dolore o', '1', 1, '2023-08-09'),
(228, 78, 38, 157, 98, 'Quia at vel mollitia', '1', 1, '2023-08-09'),
(229, 78, 38, 157, 98, 'Quia incididunt haru', '1', 1, '2023-08-09'),
(230, 78, 38, 157, 98, 'Quidem non saepe ist', '1', 1, '2023-08-09'),
(231, 78, 38, 158, 98, 'Officia rerum fugiat', '1', 1, '2023-08-09'),
(232, 78, 38, 158, 98, 'Velit enim debitis a', '1', 1, '2023-08-09'),
(233, 78, 38, 158, 98, 'Architecto ducimus ', '1', 1, '2023-08-09'),
(234, 78, 38, 158, 98, 'Sunt quae tempor eo', '1', 1, '2023-08-09'),
(235, 78, 38, 159, 98, 'Ipsum ut maxime opti', '1', 1, '2023-08-09'),
(236, 78, 38, 159, 98, 'Veritatis in quisqua', '1', 1, '2023-08-09'),
(237, 78, 38, 159, 98, 'Saepe ipsum ullam d', '1', 1, '2023-08-09'),
(238, 78, 38, 159, 98, 'Ea possimus consequ', '1', 1, '2023-08-09'),
(245, 78, 38, 161, 98, 'In beatae debitis so', '1', 1, '2023-08-09'),
(246, 78, 38, 161, 98, 'Delectus tempora ma', '1', 1, '2023-08-09'),
(247, 78, 38, 161, 98, 'Ut commodi modi even', '1', 1, '2023-08-09'),
(248, 78, 38, 160, 98, 'Fugit sit debitis ', '1', 1, '2023-08-09'),
(249, 78, 38, 160, 98, 'Excepturi in id face', '1', 1, '2023-08-09'),
(250, 78, 38, 160, 98, 'Qui quod cumque quia', '1', 1, '2023-08-09'),
(251, 78, 38, 160, 98, 'Consequatur ipsa no', '1', 1, '2023-08-09'),
(252, 78, 38, 160, 98, 'Nulla obcaecati blan', '1', 1, '2023-08-09'),
(263, 78, 38, 163, 98, 'Quia voluptatum aut ', '1', 1, '2023-08-09'),
(264, 78, 38, 163, 98, 'Quaerat dolores inve', '1', 1, '2023-08-09'),
(265, 78, 38, 163, 98, 'Consequatur qui eos', '1', 1, '2023-08-09'),
(266, 78, 38, 162, 98, 'Explicabo Nam rem q', '1', 1, '2023-08-09'),
(267, 78, 38, 162, 98, 'Non et cum perspicia', '1', 1, '2023-08-09'),
(268, 78, 38, 162, 98, 'Velit enim perferen', '1', 1, '2023-08-09'),
(269, 78, 38, 162, 98, 'Quidem a sint eiusm', '1', 1, '2023-08-09'),
(270, 78, 38, 162, 98, 'Nam sed duis necessi', '1', 1, '2023-08-09'),
(271, 78, 38, 154, 92, 'At ullamco ea repreh', '1', 1, '2023-08-09'),
(272, 78, 38, 154, 92, 'Qui itaque quidem qu', '1', 1, '2023-08-09'),
(273, 78, 38, 154, 92, 'Cum et pariatur Sae', '1', 1, '2023-08-09'),
(274, 78, 38, 154, 98, 'Sunt ea sit commod', '1', 1, '2023-08-09'),
(275, 78, 38, 154, 98, 'Magnam perferendis i', '1', 1, '2023-08-09'),
(276, 78, 38, 155, 92, 'Ullamco dolore offic', '1', 1, '2023-08-09'),
(277, 78, 38, 155, 92, 'Qui iusto vel mollit', '1', 1, '2023-08-09'),
(278, 78, 38, 155, 92, 'Sunt id laudantium ', '1', 1, '2023-08-09'),
(283, 78, 38, 158, 93, 'Voluptate voluptas p', '1', 1, '2023-08-09'),
(284, 78, 38, 158, 93, 'Est enim accusamus ', '1', 1, '2023-08-09'),
(285, 78, 38, 158, 93, 'Deleniti veniam et ', '1', 1, '2023-08-09'),
(286, 78, 38, 158, 93, 'Sunt dolor qui nemo ', '1', 1, '2023-08-09'),
(287, 78, 38, 156, 93, 'Ea non exercitation ', '1', 1, '2023-08-09'),
(288, 78, 38, 156, 93, 'Velit in fugit est', '1', 1, '2023-08-09'),
(289, 78, 38, 156, 93, 'Dolor odit eaque con', '1', 1, '2023-08-09'),
(290, 78, 38, 157, 93, 'Et nisi quaerat cons', '1', 1, '2023-08-09'),
(291, 78, 38, 157, 93, 'Odio ullamco laborum', '1', 1, '2023-08-09'),
(292, 78, 38, 157, 93, 'Reprehenderit volupt', '1', 1, '2023-08-09'),
(293, 78, 38, 161, 92, 'Tenetur nostrud quia', '1', 1, '2023-08-09'),
(294, 78, 38, 161, 92, 'Cum consequat Aliqu', '1', 1, '2023-08-09'),
(295, 78, 38, 161, 92, 'Totam nisi quis accu', '1', 1, '2023-08-09'),
(296, 78, 38, 161, 92, 'Aliquam facere est q', '1', 1, '2023-08-09'),
(297, 78, 38, 155, 93, 'Est nisi eos ad id ', '1', 1, '2023-08-09'),
(298, 78, 38, 155, 93, 'Rerum consequuntur a', '1', 1, '2023-08-09'),
(299, 78, 38, 155, 93, 'Architecto totam qua', '1', 1, '2023-08-09'),
(300, 78, 38, 155, 93, 'Accusantium non maxi', '1', 1, '2023-08-09'),
(301, 78, 38, 162, 92, 'Ad commodo neque ear', '1', 1, '2023-08-09'),
(302, 78, 38, 162, 92, 'Sunt voluptatem min', '1', 1, '2023-08-09'),
(303, 78, 38, 162, 92, 'Dolore laboriosam a', '1', 1, '2023-08-09'),
(304, 78, 38, 162, 92, 'Velit dolorem repre', '1', 1, '2023-08-09'),
(305, 78, 38, 159, 92, 'Quae ea dolorem pers', '1', 1, '2023-08-09'),
(306, 78, 38, 159, 92, 'In non dolores accus', '1', 1, '2023-08-09'),
(307, 78, 38, 159, 92, 'Et et quis aut quasi', '1', 1, '2023-08-09'),
(308, 78, 38, 156, 92, 'Est voluptas proiden', '1', 1, '2023-08-09'),
(309, 78, 38, 156, 92, 'Odit tenetur quas po', '1', 1, '2023-08-09'),
(310, 78, 38, 156, 92, 'Impedit aperiam nih', '1', 1, '2023-08-09'),
(311, 78, 38, 156, 92, 'Aut eum dolor volupt', '1', 1, '2023-08-09'),
(312, 78, 38, 158, 92, 'Officia perspiciatis', '1', 1, '2023-08-09'),
(313, 78, 38, 158, 92, 'Deleniti pariatur A', '1', 1, '2023-08-09'),
(314, 78, 38, 158, 92, 'Laborum consectetur', '1', 1, '2023-08-09'),
(315, 78, 38, 158, 92, 'Est qui sed harum q', '1', 1, '2023-08-09'),
(316, 78, 38, 154, 93, 'Assumenda numquam co', '1', 1, '2023-08-09'),
(317, 78, 38, 154, 93, 'Dicta fugit sit se', '1', 1, '2023-08-09'),
(318, 78, 38, 154, 93, 'Cupiditate eos ratio', '1', 1, '2023-08-09'),
(319, 78, 38, 157, 92, 'Recusandae Qui amet', '1', 1, '2023-08-09'),
(320, 78, 38, 157, 92, 'Asperiores quae volu', '1', 1, '2023-08-09'),
(321, 78, 38, 157, 92, 'Voluptatum reprehend', '1', 1, '2023-08-09'),
(322, 78, 38, 157, 92, 'Veritatis distinctio', '1', 1, '2023-08-09'),
(323, 78, 38, 160, 92, 'At accusantium ipsa', '1', 1, '2023-08-09'),
(324, 78, 38, 160, 92, 'Tempore obcaecati t', '1', 1, '2023-08-09'),
(325, 78, 38, 160, 92, 'Sint itaque lorem c', '', 1, '2023-08-09'),
(326, 78, 38, 160, 92, 'Quod sint animi pra', '', 1, '2023-08-09'),
(327, 78, 38, 163, 92, 'Omnis saepe nihil re', '1', 1, '2023-08-09'),
(328, 78, 38, 163, 92, 'Quam totam ex rerum ', '1', 1, '2023-08-09'),
(329, 78, 38, 163, 92, 'Impedit in dolores ', '1', 1, '2023-08-09'),
(330, 78, 38, 163, 92, 'Repudiandae quis inc', '', 1, '2023-08-09'),
(331, 78, 38, 159, 93, 'Facere deserunt quia', '1', 1, '2023-08-09'),
(332, 78, 38, 159, 93, 'At rem rem eveniet ', '1', 1, '2023-08-09'),
(333, 78, 38, 159, 93, 'Minus corporis nostr', '1', 1, '2023-08-09'),
(334, 78, 38, 159, 93, 'Debitis dolore error', '', 1, '2023-08-09'),
(335, 78, 38, 160, 93, 'Est iure aut cum asp', '1', 1, '2023-08-09'),
(336, 78, 38, 160, 93, 'Laudantium excepteu', '1', 1, '2023-08-09'),
(337, 78, 38, 160, 93, 'Voluptatem facilis ', '1', 1, '2023-08-09'),
(338, 78, 38, 161, 93, 'Iure proident facer', '1', 1, '2023-08-09'),
(339, 78, 38, 161, 93, 'Aut quisquam magna d', '1', 1, '2023-08-09'),
(340, 78, 38, 161, 93, 'Veniam est est et ', '1', 1, '2023-08-09'),
(341, 78, 38, 161, 93, 'Qui voluptatem Sed ', '1', 1, '2023-08-09'),
(342, 78, 38, 162, 93, 'Repellendus Cillum ', '1', 1, '2023-08-09'),
(343, 78, 38, 162, 93, 'Officia totam deseru', '1', 1, '2023-08-09'),
(344, 78, 38, 162, 93, 'Voluptas aperiam fug', '1', 1, '2023-08-09'),
(345, 78, 38, 162, 93, 'Exercitation sit nul', '1', 1, '2023-08-09'),
(346, 78, 38, 163, 93, 'Possimus ut velit e', '1', 1, '2023-08-09'),
(347, 78, 38, 163, 93, 'Voluptas ab at odit ', '1', 1, '2023-08-09'),
(348, 78, 38, 163, 93, 'Tempore ea incididu', '1', 1, '2023-08-09'),
(349, 78, 38, 155, 96, 'Voluptas vero accusa', '1', 1, '2023-08-09'),
(350, 78, 38, 155, 96, 'Ad aliqua Nulla rep', '1', 1, '2023-08-09'),
(351, 78, 38, 155, 96, 'Commodi maxime quis ', '1', 1, '2023-08-09'),
(352, 78, 38, 155, 96, 'Placeat et sunt con', '', 1, '2023-08-09'),
(353, 78, 38, 154, 96, 'Dolore dolor et duis', '1', 1, '2023-08-09'),
(354, 78, 38, 154, 96, 'Aut atque aut dolor ', '1', 1, '2023-08-09'),
(355, 78, 38, 154, 96, 'Pariatur Saepe dolo', '1', 1, '2023-08-09'),
(356, 78, 38, 154, 96, 'Rerum tenetur veniam', '', 1, '2023-08-09'),
(357, 78, 38, 156, 96, 'Corrupti sed do vel', '1', 1, '2023-08-09'),
(358, 78, 38, 156, 96, 'Nihil in dolor lorem', '1', 1, '2023-08-09'),
(359, 78, 38, 156, 96, 'Ut ea autem necessit', '1', 1, '2023-08-09'),
(360, 78, 38, 157, 96, 'Dolore atque aut qui', '1', 1, '2023-08-09'),
(361, 78, 38, 157, 96, 'Ea facere eum anim e', '1', 1, '2023-08-09'),
(362, 78, 38, 157, 96, 'Est velit id et au', '1', 1, '2023-08-09'),
(363, 78, 38, 158, 96, 'Repudiandae in non a', '1', 1, '2023-08-09'),
(364, 78, 38, 158, 96, 'Voluptate quas commo', '1', 1, '2023-08-09'),
(365, 78, 38, 158, 96, 'Quisquam earum sit ', '1', 1, '2023-08-09'),
(366, 78, 38, 154, 95, 'Aut id consequatur l', '1', 1, '2023-08-09'),
(367, 78, 38, 154, 95, 'Dolores do ea volupt', '1', 1, '2023-08-09'),
(368, 78, 38, 154, 95, 'Ipsa incidunt ipsa', '1', 1, '2023-08-09'),
(369, 78, 38, 154, 95, 'Ex quis quia ut cons', '1', 1, '2023-08-09'),
(370, 78, 38, 155, 95, 'Laboriosam soluta o', '1', 1, '2023-08-09'),
(371, 78, 38, 155, 95, 'Praesentium sit erro', '1', 1, '2023-08-09'),
(372, 78, 38, 155, 95, 'In neque sint volupt', '1', 1, '2023-08-09'),
(373, 78, 38, 155, 95, 'Odio Nam impedit am', '1', 1, '2023-08-09'),
(374, 78, 38, 156, 95, 'Doloribus voluptatum', '1', 1, '2023-08-09'),
(375, 78, 38, 156, 95, 'Molestias vel dolore', '1', 1, '2023-08-09'),
(376, 78, 38, 156, 95, 'Vel in iure quia ut ', '1', 1, '2023-08-09'),
(377, 78, 38, 156, 95, 'Nobis eos corporis v', '', 1, '2023-08-09'),
(378, 78, 38, 157, 95, 'Iure et eveniet dol', '1', 1, '2023-08-09'),
(379, 78, 38, 157, 95, 'Cupiditate non excep', '1', 1, '2023-08-09'),
(380, 78, 38, 157, 95, 'Quibusdam voluptatem', '1', 1, '2023-08-09'),
(381, 78, 38, 158, 95, 'Temporibus delectus', '1', 1, '2023-08-09'),
(382, 78, 38, 158, 95, 'Facere dolor quos ea', '1', 1, '2023-08-09'),
(383, 78, 38, 158, 95, 'Aut impedit accusam', '1', 1, '2023-08-09'),
(384, 78, 38, 158, 95, 'Laudantium nihil no', '1', 1, '2023-08-09'),
(385, 78, 38, 159, 95, 'Ex architecto sapien', '1', 1, '2023-08-09'),
(386, 78, 38, 159, 95, 'Enim aut vel dolor i', '1', 1, '2023-08-09'),
(387, 78, 38, 159, 95, 'Impedit et ut offic', '1', 1, '2023-08-09'),
(388, 78, 38, 163, 95, 'Consectetur earum et', '1', 1, '2023-08-09'),
(389, 78, 38, 163, 95, 'Sunt incididunt aut', '1', 1, '2023-08-09'),
(390, 78, 38, 163, 95, 'Aut non commodo eos ', '1', 1, '2023-08-09'),
(391, 78, 38, 163, 95, 'Incididunt qui volup', '1', 1, '2023-08-09'),
(392, 78, 38, 160, 95, 'Amet aute in tempor', '1', 1, '2023-08-09'),
(393, 78, 38, 160, 95, 'Enim et suscipit lab', '1', 1, '2023-08-09'),
(394, 78, 38, 160, 95, 'Vel laboris dolor qu', '1', 1, '2023-08-09'),
(395, 78, 38, 160, 95, 'Amet velit tenetur ', '1', 1, '2023-08-09'),
(396, 78, 38, 161, 95, 'Cillum sed doloribus', '1', 1, '2023-08-09'),
(397, 78, 38, 161, 95, 'Aperiam ut tempora p', '1', 1, '2023-08-09'),
(398, 78, 38, 161, 95, 'Repudiandae numquam ', '1', 1, '2023-08-09'),
(399, 78, 38, 162, 95, 'Numquam proident in', '1', 1, '2023-08-09'),
(400, 78, 38, 162, 95, 'Nostrum ut deserunt ', '1', 1, '2023-08-09'),
(401, 78, 38, 162, 95, 'Nostrud ut et cumque', '1', 1, '2023-08-09'),
(402, 78, 38, 159, 96, 'Consequatur Rerum s', '1', 1, '2023-08-09'),
(403, 78, 38, 159, 96, 'Itaque qui nemo illu', '1', 1, '2023-08-09'),
(404, 78, 38, 159, 96, 'Assumenda rerum cons', '1', 1, '2023-08-09'),
(405, 78, 38, 159, 96, 'Aut quia labore accu', '1', 1, '2023-08-09'),
(406, 78, 38, 163, 96, 'Incididunt excepturi', '1', 1, '2023-08-09'),
(407, 78, 38, 163, 96, 'Deserunt minim digni', '1', 1, '2023-08-09'),
(408, 78, 38, 163, 96, 'Veniam sit non ut v', '1', 1, '2023-08-09'),
(409, 78, 38, 164, 93, 'Quis quia ratione al', '1', 1, '2023-08-09'),
(410, 78, 38, 164, 93, 'Repudiandae voluptat', '1', 1, '2023-08-09'),
(411, 78, 38, 164, 93, 'Libero sit voluptat', '1', 1, '2023-08-09'),
(412, 78, 38, 164, 93, 'Sit facere esse de', '1', 1, '2023-08-09'),
(413, 78, 38, 164, 93, 'Fugiat accusantium o', '1', 1, '2023-08-09'),
(414, 78, 38, 160, 96, 'Eligendi iusto repre', '1', 1, '2023-08-09'),
(415, 78, 38, 160, 96, 'Rerum dolores at nul', '1', 1, '2023-08-09'),
(416, 78, 38, 160, 96, 'Sunt quia officia al', '1', 1, '2023-08-09'),
(417, 78, 38, 160, 96, 'Sunt assumenda cumqu', '1', 1, '2023-08-09'),
(418, 78, 38, 160, 96, 'Et enim similique in', '1', 1, '2023-08-09'),
(419, 78, 38, 162, 96, 'Ullam nostrud eligen', '1', 1, '2023-08-09'),
(420, 78, 38, 162, 96, 'Ad eiusmod et aliqui', '1', 1, '2023-08-09'),
(421, 78, 38, 162, 96, 'Cillum veritatis omn', '1', 1, '2023-08-09'),
(422, 78, 38, 162, 96, 'Nisi non aliqua Qui', '1', 1, '2023-08-09'),
(423, 78, 38, 167, 98, 'Quia pariatur Volup', '1', 1, '2023-08-09'),
(424, 78, 38, 167, 98, 'Explicabo Dolor et ', '1', 1, '2023-08-09'),
(425, 78, 38, 167, 98, 'Nulla qui dolor labo', '1', 1, '2023-08-09'),
(426, 78, 38, 167, 98, 'Ut debitis et corpor', '1', 1, '2023-08-09'),
(427, 78, 38, 166, 98, 'Fugit sapiente in a', '1', 1, '2023-08-09'),
(428, 78, 38, 166, 98, 'Elit veniam assume', '1', 1, '2023-08-09'),
(429, 78, 38, 166, 98, 'Eius magni nulla ita', '1', 1, '2023-08-09'),
(430, 78, 38, 166, 98, 'Assumenda anim elit', '1', 1, '2023-08-09'),
(431, 78, 38, 166, 98, 'Est sapiente pariatu', '1', 1, '2023-08-09'),
(432, 78, 38, 165, 98, 'Tempor autem quae en', '1', 1, '2023-08-09'),
(433, 78, 38, 165, 98, 'Reprehenderit sunt ', '1', 1, '2023-08-09'),
(434, 78, 38, 165, 98, 'Reprehenderit est mo', '1', 1, '2023-08-09'),
(435, 78, 38, 165, 98, 'Eum rerum explicabo', '1', 1, '2023-08-09'),
(436, 78, 38, 165, 98, 'Dolores et cumque si', '1', 1, '2023-08-09'),
(437, 78, 38, 165, 93, 'Veniam quidem fugia', '1', 1, '2023-08-09'),
(438, 78, 38, 165, 93, 'Totam sequi minim de', '1', 1, '2023-08-09'),
(439, 78, 38, 165, 93, 'Et et dolor nisi in ', '1', 1, '2023-08-09'),
(440, 78, 38, 164, 92, 'Eu numquam ea volupt', '1', 1, '2023-08-09'),
(441, 78, 38, 164, 92, 'Sed assumenda deseru', '1', 1, '2023-08-09'),
(442, 78, 38, 164, 92, 'Nostrud eaque debiti', '1', 1, '2023-08-09'),
(443, 78, 38, 164, 92, 'Et eum delectus qui', '1', 1, '2023-08-09'),
(444, 78, 38, 164, 92, 'Beatae voluptas odio', '1', 1, '2023-08-09'),
(445, 78, 38, 165, 92, 'Voluptate exercitati', '1', 1, '2023-08-09'),
(446, 78, 38, 165, 92, 'Do amet enim ut rep', '1', 1, '2023-08-09'),
(447, 78, 38, 165, 92, 'Nulla aut dolor et o', '1', 1, '2023-08-09'),
(448, 78, 38, 165, 92, 'Culpa et quae beatae', '1', 1, '2023-08-09'),
(449, 78, 38, 166, 92, 'Adipisci ea incididu', '1', 1, '2023-08-09'),
(450, 78, 38, 166, 92, 'In similique ipsam n', '1', 1, '2023-08-09'),
(451, 78, 38, 166, 92, 'Voluptas aperiam cup', '1', 1, '2023-08-09'),
(452, 78, 38, 167, 92, 'Quae repudiandae mai', '1', 1, '2023-08-09'),
(453, 78, 38, 167, 92, 'Et impedit aliquid ', '1', 1, '2023-08-09'),
(454, 78, 38, 167, 92, 'Numquam fugit sint ', '1', 1, '2023-08-09'),
(455, 78, 38, 167, 92, 'Veniam perferendis ', '1', 1, '2023-08-09'),
(456, 78, 38, 166, 93, 'Odit in ad dolorem v', '1', 1, '2023-08-09'),
(457, 78, 38, 166, 93, 'Occaecat rerum ut re', '1', 1, '2023-08-09'),
(458, 78, 38, 166, 93, 'Dolorem tempora sunt', '1', 1, '2023-08-09'),
(459, 78, 38, 167, 93, 'Qui aut Nam atque iu', '1', 1, '2023-08-09'),
(460, 78, 38, 167, 93, 'Anim excepturi non a', '1', 1, '2023-08-09'),
(461, 78, 38, 167, 93, 'Velit assumenda lib', '1', 1, '2023-08-09'),
(462, 78, 38, 167, 93, 'Commodi et est simil', '', 1, '2023-08-09'),
(463, 78, 38, 167, 93, 'Saepe est rerum duci', '', 1, '2023-08-09'),
(464, 78, 38, 164, 95, 'Repellendus Cillum ', '1', 1, '2023-08-09'),
(465, 78, 38, 164, 95, 'Voluptatem maxime c', '1', 1, '2023-08-09'),
(466, 78, 38, 164, 95, 'Dolor magni laborios', '1', 1, '2023-08-09'),
(467, 78, 38, 164, 95, 'Reprehenderit solut', '', 1, '2023-08-09'),
(468, 78, 38, 165, 95, 'Est distinctio Cumq', '1', 1, '2023-08-09'),
(469, 78, 38, 165, 95, 'Ex dolorem suscipit ', '1', 1, '2023-08-09'),
(470, 78, 38, 165, 95, 'Eum molestias aute c', '1', 1, '2023-08-09'),
(471, 78, 38, 165, 95, 'Dolore enim consecte', '1', 1, '2023-08-09'),
(472, 78, 38, 166, 95, 'Cupidatat adipisicin', '1', 1, '2023-08-09'),
(473, 78, 38, 166, 95, 'Deleniti saepe disti', '1', 1, '2023-08-09'),
(474, 78, 38, 166, 95, 'Neque pariatur Est ', '1', 1, '2023-08-09'),
(475, 78, 38, 166, 95, 'Sequi quasi vitae om', '1', 1, '2023-08-09'),
(476, 78, 38, 167, 95, 'Mollitia et culpa e', '1', 1, '2023-08-09'),
(477, 78, 38, 167, 95, 'Quis dolores quibusd', '1', 1, '2023-08-09'),
(478, 78, 38, 167, 95, 'Consequatur fuga Cu', '1', 1, '2023-08-09'),
(479, 78, 38, 167, 95, 'Nihil vero do commod', '1', 1, '2023-08-09'),
(480, 78, 38, 167, 95, 'Eligendi nihil enim ', '1', 1, '2023-08-09'),
(481, 78, 38, 161, 96, 'Similique obcaecati ', '1', 1, '2023-08-09'),
(482, 78, 38, 161, 96, 'Nostrud nihil et ver', '1', 1, '2023-08-09'),
(483, 78, 38, 161, 96, 'Ut perspiciatis rep', '1', 1, '2023-08-09'),
(484, 78, 38, 161, 96, 'Ut vitae aliquam sed', '1', 1, '2023-08-09'),
(485, 78, 38, 164, 96, 'Sed facere qui moles', '1', 1, '2023-08-09'),
(486, 78, 38, 164, 96, 'Aute et duis illum ', '1', 1, '2023-08-09'),
(487, 78, 38, 164, 96, 'Sed dolores sit offi', '1', 1, '2023-08-09'),
(488, 78, 38, 164, 96, 'Qui et assumenda acc', '1', 1, '2023-08-09'),
(489, 78, 38, 164, 96, 'Lorem ut ullam error', '', 1, '2023-08-09'),
(490, 78, 38, 164, 98, 'Exercitation eligend', '1', 1, '2023-08-09'),
(491, 78, 38, 164, 98, 'Eveniet minim nostr', '1', 1, '2023-08-09'),
(492, 78, 38, 164, 98, 'Dolores fugiat lorem', '1', 1, '2023-08-09'),
(493, 78, 38, 164, 98, 'Ipsum aut non hic a', '1', 1, '2023-08-09'),
(494, 78, 38, 164, 98, 'Quae debitis repudia', '1', 1, '2023-08-09'),
(495, 78, 38, 165, 96, 'Dolores quia cumque ', '1', 1, '2023-08-09'),
(496, 78, 38, 165, 96, 'Nulla autem odio qui', '1', 1, '2023-08-09'),
(497, 78, 38, 165, 96, 'Eos quia a totam eni', '1', 1, '2023-08-09'),
(498, 78, 38, 165, 96, 'Eligendi molestiae c', '1', 1, '2023-08-09'),
(499, 78, 38, 165, 96, 'Pariatur Rem eum hi', '1', 1, '2023-08-09'),
(500, 78, 38, 166, 96, 'Fugiat eos neque au', '1', 1, '2023-08-09'),
(501, 78, 38, 166, 96, 'Reprehenderit dolor', '1', 1, '2023-08-09'),
(502, 78, 38, 166, 96, 'Facilis sint eligend', '1', 1, '2023-08-09'),
(503, 78, 38, 166, 96, 'Sit esse sed rem a', '1', 1, '2023-08-09'),
(504, 78, 38, 166, 96, 'Non dignissimos sit ', '1', 1, '2023-08-09'),
(505, 78, 38, 167, 96, 'Quasi sunt rerum Nam', '1', 1, '2023-08-09'),
(506, 78, 38, 167, 96, 'Quaerat minim except', '1', 1, '2023-08-09'),
(507, 78, 38, 167, 96, 'Debitis ipsa eu sed', '1', 1, '2023-08-09'),
(508, 78, 38, 167, 96, 'Rerum sint quod rer', '1', 1, '2023-08-09'),
(509, 78, 37, 147, 0, 'Ad temporibus volupt', '1', 1, '2023-08-09'),
(510, 78, 37, 147, 0, 'Repellendus Nisi ea', '1', 1, '2023-08-09'),
(511, 78, 37, 147, 0, 'Enim amet iure veli', '1', 1, '2023-08-09'),
(512, 78, 37, 153, 0, 'Eum ea labore sunt ', '1', 1, '2023-08-09'),
(513, 78, 37, 153, 0, 'Et nesciunt odio il', '1', 1, '2023-08-09'),
(514, 78, 37, 153, 0, 'Dolor praesentium es', '1', 1, '2023-08-09'),
(515, 78, 37, 148, 0, 'Ut saepe sed placeat', '1', 1, '2023-08-09'),
(516, 78, 37, 148, 0, 'Sit culpa architecto', '1', 1, '2023-08-09'),
(517, 78, 37, 148, 0, 'Commodi tenetur in n', '1', 1, '2023-08-09'),
(518, 78, 37, 148, 0, 'Delectus consequat', '1', 1, '2023-08-09'),
(519, 78, 37, 148, 0, 'Fugiat eu ipsum la', '1', 1, '2023-08-09'),
(520, 78, 37, 149, 0, 'Voluptatem neque vel', '1', 1, '2023-08-09'),
(521, 78, 37, 149, 0, 'Exercitationem volup', '1', 1, '2023-08-09'),
(522, 78, 37, 149, 0, 'Inventore consequunt', '1', 1, '2023-08-09'),
(523, 78, 37, 149, 0, 'Dolor sit deserunt v', '1', 1, '2023-08-09'),
(524, 78, 37, 149, 0, 'Incididunt temporibu', '1', 1, '2023-08-09'),
(525, 78, 37, 149, 0, 'In ut laboriosam be', '1', 1, '2023-08-09'),
(526, 78, 37, 150, 0, 'Fugiat quisquam et ', '1', 1, '2023-08-09'),
(527, 78, 37, 150, 0, 'Consequatur consequa', '1', 1, '2023-08-09'),
(528, 78, 37, 150, 0, 'Itaque est voluptatu', '1', 1, '2023-08-09'),
(529, 78, 37, 150, 0, 'Incididunt dolores a', '1', 1, '2023-08-09'),
(530, 78, 37, 150, 0, 'Et facere quia aut a', '1', 1, '2023-08-09'),
(531, 78, 37, 150, 0, 'Aut nobis est commod', '', 1, '2023-08-09'),
(532, 78, 37, 151, 0, 'Dolorem est repudian', '1', 1, '2023-08-09'),
(533, 78, 37, 151, 0, 'Ut ad veniam dolore', '1', 1, '2023-08-09'),
(534, 78, 37, 151, 0, 'Quibusdam ea qui sed', '1', 1, '2023-08-09'),
(535, 78, 37, 151, 0, 'Aute quis sit quasi', '1', 1, '2023-08-09'),
(536, 78, 37, 152, 0, 'Quia aut laborum Pr', '1', 1, '2023-08-09'),
(537, 78, 37, 152, 0, 'Consectetur voluptat', '1', 1, '2023-08-09'),
(538, 78, 37, 152, 0, 'Aut quam do voluptas', '1', 1, '2023-08-09'),
(539, 78, 37, 152, 0, 'Esse aut sit ea ven', '1', 1, '2023-08-09'),
(540, 78, 37, 152, 0, 'Deleniti sapiente la', '1', 1, '2023-08-09'),
(541, 78, 37, 152, 0, 'Consequatur ullam es', '', 1, '2023-08-09'),
(542, 15, 11, 24, 29, 'Test 1', '1', 118, '2023-08-09'),
(543, 15, 11, 24, 29, 'Test 2', '1', 118, '2023-08-09'),
(544, 15, 11, 24, 25, 'Qui hic molestiae id', '1', 1, '2023-08-12'),
(545, 15, 11, 24, 25, 'Dolores laboris in d', '1', 1, '2023-08-12'),
(546, 15, 11, 24, 25, 'Aut aliquid at modi ', '1', 1, '2023-08-12'),
(547, 15, 11, 24, 25, 'Nihil optio archite', '1', 1, '2023-08-12'),
(548, 15, 11, 24, 26, 'Earum ex consequat ', '1', 1, '2023-08-12'),
(549, 15, 11, 24, 26, 'Soluta excepturi rem', '1', 1, '2023-08-12'),
(550, 15, 11, 24, 26, 'Culpa rerum adipisi', '1', 1, '2023-08-12'),
(551, 15, 11, 24, 26, 'Autem ut voluptas pa', '1', 1, '2023-08-12'),
(552, 15, 11, 24, 26, 'Et sunt vel quis in', '1', 1, '2023-08-12');

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_stage_actions`
--

CREATE TABLE `tbl_project_stage_actions` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `stage` int NOT NULL,
  `sub_stage` int NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_stage_actions`
--

INSERT INTO `tbl_project_stage_actions` (`id`, `projid`, `stage`, `sub_stage`, `created_by`, `created_at`) VALUES
(1, 59, 1, 3, 1, '2023-04-25'),
(2, 62, 2, 1, 1, '2023-04-25'),
(3, 2, 4, 1, 1, '2023-04-25'),
(4, 15, 5, 1, 1, '2023-04-26'),
(5, 58, 1, 1, 1, '2023-04-26'),
(6, 1, 7, 2, 1, '2023-04-27'),
(7, 7, 5, 1, 1, '2023-04-27'),
(8, 1, 5, 3, 1, '2023-04-27'),
(9, 1, 6, 0, 1, '2023-04-27'),
(10, 1, 6, 0, 1, '2023-04-27'),
(11, 1, 6, 2, 1, '2023-04-27'),
(12, 1, 7, 0, 1, '2023-04-27'),
(13, 1, 7, 2, 1, '2023-04-27'),
(14, 1, 8, 0, 1, '2023-04-27'),
(15, 59, 1, 0, 1, '2023-04-28'),
(16, 59, 1, 1, 1, '2023-04-28'),
(17, 59, 1, 0, 1, '2023-04-28'),
(18, 59, 1, 0, 1, '2023-04-28'),
(19, 59, 2, 0, 1, '2023-04-28'),
(20, 59, 2, 1, 1, '2023-05-01'),
(21, 58, 1, 3, 1, '2023-05-03'),
(22, 58, 2, 0, 1, '2023-05-03'),
(23, 58, 2, 1, 1, '2023-05-03'),
(24, 66, 0, 3, 1, '2023-05-05'),
(25, 66, 2, 0, 1, '2023-05-05'),
(26, 66, 2, 2, 1, '2023-05-05'),
(27, 66, 2, 2, 1, '2023-05-05'),
(28, 66, 2, 3, 1, '2023-05-05'),
(29, 66, 3, 0, 1, '2023-05-05'),
(30, 66, 3, 1, 1, '2023-05-05'),
(31, 66, 3, 2, 1, '2023-05-08'),
(32, 66, 3, 3, 1, '2023-05-08'),
(33, 66, 4, 0, 1, '2023-05-08'),
(34, 66, 4, 1, 1, '2023-05-08'),
(35, 1, 4, 1, 1, '2023-05-10'),
(36, 69, 0, 3, 1, '2023-05-11'),
(37, 69, 1, 1, 1, '2023-05-11'),
(38, 69, 1, 2, 1, '2023-05-16'),
(39, 69, 2, 0, 1, '2023-05-16'),
(40, 69, 2, 1, 1, '2023-05-16'),
(41, 69, 2, 2, 1, '2023-05-16'),
(42, 69, 2, 3, 1, '2023-05-16'),
(43, 69, 3, 0, 1, '2023-05-16'),
(44, 69, 3, 1, 1, '2023-05-16'),
(45, 69, 3, 2, 1, '2023-05-16'),
(46, 69, 4, 0, 1, '2023-05-16'),
(47, 69, 4, 1, 1, '2023-05-16'),
(48, 70, 0, 3, 124, '2023-05-30'),
(49, 71, 0, 3, 118, '2023-05-30'),
(50, 71, 1, 1, 118, '2023-05-30'),
(51, 71, 1, 2, 1, '2023-06-10'),
(52, 71, 1, 3, 1, '2023-06-10'),
(53, 71, 2, 0, 1, '2023-06-10'),
(54, 74, 0, 3, 118, '2023-06-12'),
(55, 75, 0, 3, 1, '2023-08-02'),
(56, 75, 1, 2, 1, '2023-08-02'),
(57, 75, 3, 0, 1, '2023-08-02'),
(58, 77, 0, 3, 118, '2023-08-02'),
(59, 71, 2, 1, 118, '2023-08-03'),
(60, 78, 0, 3, 118, '2023-08-03'),
(61, 78, 1, 1, 118, '2023-08-04'),
(62, 78, 1, 2, 118, '2023-08-04'),
(63, 78, 3, 0, 118, '2023-08-04'),
(64, 78, 3, 0, 1, '2023-08-04'),
(65, 78, 3, 0, 1, '2023-08-04'),
(66, 78, 3, 0, 1, '2023-08-04'),
(67, 78, 2, 0, 1, '2023-08-04'),
(68, 71, 2, 2, 1, '2023-08-05'),
(69, 71, 4, 0, 1, '2023-08-05'),
(70, 71, 3, 0, 1, '2023-08-05'),
(71, 78, 2, 2, 1, '2023-08-07'),
(72, 78, 3, 0, 1, '2023-08-07'),
(73, 79, 0, 3, 118, '2023-08-07'),
(74, 79, 1, 2, 118, '2023-08-07'),
(75, 79, 2, 0, 118, '2023-08-07'),
(76, 81, 0, 3, 118, '2023-08-07'),
(77, 82, 0, 3, 118, '2023-08-08'),
(78, 78, 3, 2, 1, '2023-08-08'),
(79, 78, 5, 0, 1, '2023-08-08'),
(80, 82, 1, 2, 118, '2023-08-08'),
(81, 82, 2, 0, 118, '2023-08-08'),
(82, 82, 2, 2, 118, '2023-08-09'),
(83, 82, 3, 0, 118, '2023-08-09'),
(84, 78, 5, 2, 1, '2023-08-09'),
(85, 78, 6, 0, 1, '2023-08-09'),
(86, 78, 5, 2, 1, '2023-08-09'),
(87, 78, 6, 0, 1, '2023-08-09'),
(88, 85, 0, 3, 118, '2023-08-09'),
(89, 85, 1, 2, 118, '2023-08-09'),
(90, 85, 2, 0, 118, '2023-08-09'),
(91, 85, 2, 2, 118, '2023-08-09'),
(92, 85, 3, 0, 118, '2023-08-09'),
(93, 85, 2, 2, 1, '2023-08-09'),
(94, 85, 3, 0, 1, '2023-08-09'),
(95, 85, 2, 2, 1, '2023-08-09'),
(96, 85, 2, 2, 1, '2023-08-09'),
(97, 85, 2, 2, 1, '2023-08-09'),
(98, 85, 2, 2, 1, '2023-08-09'),
(99, 85, 3, 0, 1, '2023-08-09'),
(100, 85, 2, 2, 1, '2023-08-09'),
(101, 85, 2, 2, 1, '2023-08-09'),
(102, 89, 0, 3, 118, '2023-08-12'),
(103, 89, 1, 2, 118, '2023-08-12'),
(104, 89, 2, 0, 118, '2023-08-12'),
(105, 89, 2, 2, 118, '2023-08-12'),
(106, 89, 3, 0, 118, '2023-08-12'),
(107, 79, 2, 2, 118, '2023-08-14'),
(108, 79, 3, 0, 118, '2023-08-14'),
(109, 76, 0, 3, 118, '2023-08-14'),
(110, 79, 0, 3, 118, '2023-08-14'),
(111, 79, 1, 2, 118, '2023-08-14'),
(112, 79, 2, 0, 118, '2023-08-14'),
(113, 79, 2, 2, 118, '2023-08-14'),
(114, 79, 3, 0, 118, '2023-08-14'),
(115, 58, 2, 2, 118, '2023-08-14'),
(116, 91, 0, 3, 118, '2023-08-14'),
(117, 91, 1, 1, 118, '2023-08-14'),
(118, 91, 1, 2, 118, '2023-08-14'),
(119, 91, 2, 0, 118, '2023-08-14'),
(120, 91, 2, 1, 118, '2023-08-14'),
(121, 81, 1, 2, 118, '2023-08-15'),
(122, 91, 2, 2, 118, '2023-08-15'),
(123, 91, 3, 0, 118, '2023-08-15'),
(124, 91, 3, 1, 118, '2023-08-15'),
(125, 62, 2, 2, 1, '2023-08-16'),
(126, 62, 3, 0, 1, '2023-08-16'),
(127, 93, 0, 3, 118, '2023-08-18'),
(128, 94, 0, 3, 1, '2023-08-19'),
(129, 94, 1, 2, 1, '2023-08-19'),
(130, 94, 2, 0, 1, '2023-08-19'),
(131, 94, 2, 2, 1, '2023-08-19'),
(132, 94, 3, 0, 1, '2023-08-19'),
(133, 95, 0, 3, 118, '2023-08-21'),
(134, 95, 1, 2, 118, '2023-08-21'),
(135, 95, 2, 0, 118, '2023-08-21'),
(136, 95, 2, 2, 118, '2023-08-22'),
(137, 72, 0, 3, 1, '2023-08-24'),
(138, 96, 0, 3, 118, '2023-08-24'),
(139, 96, 1, 2, 118, '2023-08-24'),
(140, 96, 2, 0, 118, '2023-08-24'),
(141, 96, 2, 2, 118, '2023-08-24'),
(142, 96, 3, 0, 118, '2023-09-01'),
(143, 97, 0, 3, 118, '2023-09-03'),
(144, 98, 0, 3, 118, '2023-09-04'),
(145, 98, 1, 2, 118, '2023-09-04'),
(146, 98, 2, 0, 118, '2023-09-04'),
(147, 98, 2, 2, 118, '2023-09-04'),
(148, 98, 3, 0, 118, '2023-09-04'),
(149, 98, 1, 2, 118, '2023-09-04'),
(150, 98, 1, 2, 118, '2023-09-04'),
(151, 98, 2, 0, 118, '2023-09-04'),
(152, 97, 1, 2, 118, '2023-09-04'),
(153, 97, 2, 0, 118, '2023-09-04'),
(154, 97, 2, 2, 118, '2023-09-04'),
(155, 97, 3, 0, 118, '2023-09-04'),
(156, 97, 3, 2, 118, '2023-09-05'),
(157, 97, 5, 0, 118, '2023-09-05'),
(158, 97, 3, 2, 1, '2023-09-05'),
(159, 97, 4, 0, 1, '2023-09-05'),
(160, 97, 3, 2, 118, '2023-09-05'),
(161, 97, 4, 0, 118, '2023-09-05'),
(162, 96, 3, 2, 118, '2023-09-05'),
(163, 97, 4, 2, 118, '2023-09-05'),
(164, 97, 5, 0, 118, '2023-09-05'),
(165, 97, 5, 2, 118, '2023-09-05'),
(166, 97, 6, 0, 118, '2023-09-05'),
(167, 97, 4, 2, 118, '2023-09-05'),
(168, 97, 5, 0, 118, '2023-09-05'),
(169, 96, 4, 0, 118, '2023-09-06'),
(170, 96, 4, 2, 118, '2023-09-06'),
(171, 96, 5, 0, 118, '2023-09-06'),
(172, 100, 0, 3, 118, '2023-09-06'),
(173, 100, 1, 2, 118, '2023-09-06'),
(174, 100, 2, 0, 118, '2023-09-06'),
(175, 100, 2, 2, 118, '2023-09-06'),
(176, 100, 3, 0, 118, '2023-09-06'),
(177, 97, 5, 2, 1, '2023-09-07'),
(178, 97, 6, 0, 1, '2023-09-07'),
(179, 97, 5, 2, 118, '2023-09-08'),
(180, 97, 6, 0, 118, '2023-09-08'),
(181, 96, 5, 2, 118, '2023-09-08'),
(182, 96, 6, 0, 118, '2023-09-08'),
(183, 97, 6, 2, 1, '2023-09-08'),
(184, 97, 7, 0, 1, '2023-09-08'),
(185, 96, 6, 2, 118, '2023-09-12'),
(186, 74, 1, 2, 118, '2023-09-14'),
(187, 74, 2, 0, 118, '2023-09-14'),
(188, 101, 0, 3, 118, '2023-09-14'),
(189, 101, 1, 2, 118, '2023-09-14'),
(190, 101, 2, 0, 118, '2023-09-14'),
(191, 101, 2, 2, 118, '2023-09-14'),
(192, 101, 3, 0, 118, '2023-09-14'),
(193, 101, 3, 2, 118, '2023-09-14'),
(194, 101, 4, 0, 118, '2023-09-14'),
(195, 101, 4, 2, 118, '2023-09-14'),
(196, 101, 5, 0, 118, '2023-09-14'),
(197, 101, 5, 2, 118, '2023-09-14'),
(198, 101, 6, 0, 118, '2023-09-14'),
(199, 101, 6, 2, 118, '2023-09-14'),
(200, 101, 7, 0, 118, '2023-09-14'),
(201, 84, 0, 3, 118, '2023-09-14'),
(202, 83, 0, 3, 118, '2023-09-14'),
(203, 84, 1, 2, 118, '2023-09-14'),
(204, 84, 2, 0, 118, '2023-09-14'),
(205, 84, 2, 2, 118, '2023-09-14'),
(206, 84, 3, 0, 118, '2023-09-14'),
(207, 84, 3, 2, 118, '2023-09-14'),
(208, 84, 5, 0, 118, '2023-09-14'),
(209, 84, 5, 2, 118, '2023-09-14'),
(210, 84, 6, 0, 118, '2023-09-14'),
(211, 84, 6, 2, 118, '2023-09-14'),
(212, 84, 8, 0, 118, '2023-09-14'),
(213, 101, 7, 2, 1, '2023-09-18'),
(214, 101, 8, 0, 1, '2023-09-18'),
(215, 101, 8, 2, 1, '2023-09-18'),
(216, 101, 9, 0, 1, '2023-09-18'),
(217, 101, 9, 2, 1, '2023-09-18'),
(218, 101, 10, 0, 1, '2023-09-18'),
(219, 3, 6, 2, 1, '2023-09-25'),
(220, 3, 6, 2, 1, '2023-09-25'),
(221, 3, 7, 0, 1, '2023-09-25'),
(222, 96, 4, 2, 1, '2023-09-25'),
(223, 96, 5, 0, 1, '2023-09-25'),
(224, 109, 0, 3, 118, '2023-09-28'),
(225, 109, 1, 2, 118, '2023-09-29'),
(226, 109, 2, 0, 118, '2023-09-29'),
(227, 109, 2, 2, 118, '2023-09-29'),
(228, 109, 3, 0, 118, '2023-09-29'),
(229, 109, 3, 2, 118, '2023-09-29'),
(230, 109, 3, 2, 118, '2023-09-29'),
(231, 109, 4, 0, 118, '2023-09-29'),
(232, 109, 4, 2, 118, '2023-09-29'),
(233, 109, 5, 0, 118, '2023-09-29'),
(234, 109, 5, 2, 118, '2023-09-30'),
(235, 109, 6, 0, 118, '2023-09-30'),
(236, 109, 6, 2, 118, '2023-09-30'),
(237, 109, 7, 0, 118, '2023-09-30'),
(238, 109, 6, 2, 118, '2023-10-02'),
(239, 109, 7, 0, 118, '2023-10-02'),
(240, 109, 8, 2, 118, '2023-10-03'),
(241, 109, 9, 0, 118, '2023-10-03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_stage_responsible`
--

CREATE TABLE `tbl_project_stage_responsible` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `stage` int NOT NULL,
  `sub_stage` int NOT NULL,
  `responsible` int NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_project_stage_responsible`
--

INSERT INTO `tbl_project_stage_responsible` (`id`, `projid`, `stage`, `sub_stage`, `responsible`, `created_by`, `created_at`) VALUES
(1, 62, 1, 0, 121, 1, '2023-04-23'),
(2, 62, 1, 2, 143, 1, '2023-04-23'),
(7, 59, 1, 1, 143, 1, '2023-04-25');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_team_leave`
--

CREATE TABLE `tbl_project_team_leave` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `activity` int NOT NULL,
  `owner` int NOT NULL,
  `assignee` int NOT NULL,
  `team_type` int NOT NULL DEFAULT '1',
  `days_requested` int NOT NULL,
  `resumed_at` date DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_team_member_unavailability`
--

CREATE TABLE `tbl_project_team_member_unavailability` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `role` int NOT NULL,
  `role_owner` int NOT NULL,
  `reason` varchar(255) NOT NULL,
  `duration` int NOT NULL,
  `role_trasferred_to` int NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1 is someone is standing in, 0 is has transferred back the role',
  `date_recorded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='project team leader role stand in';

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
(1, 'Project Manager', 'Project Team Leader', 1, 0),
(2, 'Team Leader', 'Project Deputy Team Leader', 2, 1),
(3, 'Assistant Team Leader', 'Project Alias Officer', 3, 1),
(4, 'Team Member', 'Project Monitoring and Evaluation Officer', 3, 1),
(5, 'MnE Champion', 'Project Monitoring and Evaluation Officer', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_tender_details`
--

CREATE TABLE `tbl_project_tender_details` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `outputid` int NOT NULL,
  `site_id` int NOT NULL,
  `subtask_id` int NOT NULL DEFAULT '0',
  `costlineid` int NOT NULL,
  `tasks` int NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `unit` varchar(255) NOT NULL,
  `unit_cost` double NOT NULL,
  `units_no` int NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `update_by` varchar(100) DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_tender_details`
--

INSERT INTO `tbl_project_tender_details` (`id`, `projid`, `outputid`, `site_id`, `subtask_id`, `costlineid`, `tasks`, `description`, `unit`, `unit_cost`, `units_no`, `created_by`, `date_created`, `update_by`, `date_updated`) VALUES
(1, 3, 5, 6, 0, 9, 7, '12', '12', 10000, 500, '118', '2023-03-15', NULL, NULL),
(2, 3, 5, 6, 0, 8, 8, '13', '13', 145000, 100, '118', '2023-03-15', NULL, NULL),
(3, 3, 5, 7, 0, 6, 7, '12', '12', 10000, 500, '118', '2023-03-15', NULL, NULL),
(4, 3, 5, 7, 0, 7, 8, '13', '13', 145000, 100, '118', '2023-03-15', NULL, NULL),
(9, 2, 4, 5, 0, 34, 10, '27', '27', 10000, 100, '118', '2023-03-15', NULL, NULL),
(10, 2, 4, 5, 0, 35, 11, '28', '28', 45000, 100, '118', '2023-03-15', NULL, NULL),
(11, 2, 4, 3, 0, 22, 10, '27', '27', 10000, 100, '118', '2023-03-15', NULL, NULL),
(12, 2, 4, 3, 0, 30, 11, '28', '28', 55000, 100, '118', '2023-03-15', NULL, NULL),
(13, 2, 4, 4, 0, 31, 10, '27', '27', 10000, 100, '118', '2023-03-15', NULL, NULL),
(14, 2, 4, 4, 0, 32, 11, '28', '28', 55000, 100, '118', '2023-03-15', NULL, NULL),
(15, 1, 1, 0, 0, 10, 1, '14', '14', 2000, 30000, '118', '2023-03-16', NULL, NULL),
(16, 1, 1, 0, 0, 11, 1, '15', '15', 1500, 20000, '118', '2023-03-16', NULL, NULL),
(17, 1, 1, 0, 0, 12, 2, '16', '16', 20000, 1300, '118', '2023-03-16', NULL, NULL),
(18, 1, 1, 0, 0, 13, 9, '17', '17', 1400000, 20, '118', '2023-03-16', NULL, NULL),
(19, 1, 1, 0, 0, 14, 9, '18', '18', 980000, 20, '118', '2023-03-16', NULL, NULL),
(20, 1, 2, 0, 0, 15, 3, '1', '1', 130000, 500, '118', '2023-03-16', NULL, NULL),
(21, 1, 2, 0, 0, 16, 3, '2', '2', 160000, 300, '118', '2023-03-16', NULL, NULL),
(22, 1, 2, 0, 0, 17, 4, '3', '3', 300000, 20, '118', '2023-03-16', NULL, NULL),
(23, 1, 2, 0, 0, 18, 4, '4', '4', 400000, 40, '118', '2023-03-16', NULL, NULL),
(24, 1, 3, 1, 0, 20, 5, '5', '5', 300000, 50, '118', '2023-03-16', NULL, NULL),
(25, 1, 3, 1, 0, 23, 6, '6', '6', 20000, 60, '118', '2023-03-16', NULL, NULL),
(26, 1, 3, 1, 0, 24, 6, '7', '7', 30000, 120, '118', '2023-03-16', NULL, NULL),
(27, 1, 3, 1, 0, 25, 6, '8', '8', 30000, 140, '118', '2023-03-16', NULL, NULL),
(28, 1, 3, 1, 0, 26, 6, '9', '9', 45000, 300, '118', '2023-03-16', NULL, NULL),
(29, 1, 3, 1, 0, 27, 6, '10', '10', 75000, 400, '118', '2023-03-16', NULL, NULL),
(30, 1, 3, 1, 0, 28, 6, '11', '11', 27000, 200, '118', '2023-03-16', NULL, NULL),
(31, 1, 3, 2, 0, 33, 5, '5', '5', 300000, 50, '118', '2023-03-16', NULL, NULL),
(32, 1, 3, 2, 0, 36, 6, '6', '6', 20000, 60, '118', '2023-03-16', NULL, NULL),
(33, 1, 3, 2, 0, 37, 6, '7', '7', 30000, 120, '118', '2023-03-16', NULL, NULL),
(34, 1, 3, 2, 0, 38, 6, '8', '8', 30000, 140, '118', '2023-03-16', NULL, NULL),
(35, 1, 3, 2, 0, 39, 6, '9', '9', 45000, 300, '118', '2023-03-16', NULL, NULL),
(36, 1, 3, 2, 0, 40, 6, '10', '10', 75000, 400, '118', '2023-03-16', NULL, NULL),
(37, 1, 3, 2, 0, 41, 6, '11', '11', 27000, 200, '118', '2023-03-16', NULL, NULL),
(38, 7, 10, 0, 0, 113, 12, '30', '30', 150000, 1000, '118', '2023-03-18', NULL, NULL),
(39, 7, 10, 0, 0, 114, 13, '29', '29', 160000, 1000, '118', '2023-03-18', NULL, NULL),
(40, 7, 10, 0, 0, 115, 14, '31', '31', 184000, 1000, '118', '2023-03-18', NULL, NULL),
(41, 6, 8, 11, 0, 121, 15, '32', '32', 150000, 1000, '118', '2023-03-21', NULL, NULL),
(42, 6, 8, 11, 0, 123, 16, '33', '33', 150000, 1000, '118', '2023-03-21', NULL, NULL),
(43, 6, 8, 11, 0, 124, 16, '34', '34', 350000, 1000, '118', '2023-03-21', NULL, NULL),
(44, 6, 9, 0, 0, 122, 17, '35', '35', 150000, 100, '118', '2023-03-21', NULL, NULL),
(45, 6, 9, 0, 0, 125, 18, '36', '36', 200000, 1000, '118', '2023-03-21', NULL, NULL),
(46, 5, 6, 10, 0, 126, 19, '37', '37', 200000, 1000, '118', '2023-04-01', NULL, NULL),
(47, 5, 6, 10, 0, 127, 21, '38', '38', 350000, 1000, '118', '2023-04-01', NULL, NULL),
(48, 5, 7, 10, 0, 128, 22, '39', '39', 200000, 1000, '118', '2023-04-01', NULL, NULL),
(49, 5, 7, 10, 0, 129, 23, '40', '40', 248500, 1000, '118', '2023-04-01', NULL, NULL),
(50, 66, 24, 0, 0, 131, 49, '55', '55', 1000, 20, '1', '2023-05-05', NULL, NULL),
(51, 66, 24, 0, 0, 132, 49, '56', '56', 1000, 20, '1', '2023-05-05', NULL, NULL),
(52, 66, 24, 0, 0, 133, 50, '54', '54', 2000, 10, '1', '2023-05-05', NULL, NULL),
(53, 69, 26, 65, 0, 145, 53, '61', '61', 2000000, 10, '1', '2023-05-16', NULL, NULL),
(54, 69, 26, 65, 0, 146, 54, '62', '62', 1000000, 1, '1', '2023-05-16', NULL, NULL),
(55, 69, 25, 65, 0, 149, 51, '57', '57', 1000000, 2, '1', '2023-05-16', NULL, NULL),
(56, 69, 25, 65, 0, 150, 51, '58', '58', 1000000, 3, '1', '2023-05-16', NULL, NULL),
(57, 69, 25, 65, 0, 151, 52, '59', '59', 1000, 1000, '1', '2023-05-16', NULL, NULL),
(58, 69, 25, 65, 0, 152, 52, '60', '60', 1000, 1000, '1', '2023-05-16', NULL, NULL),
(65, 69, 25, 67, 0, 155, 51, '57', '57', 5000, 10, '1', '2023-05-16', NULL, NULL),
(66, 69, 25, 67, 0, 156, 51, '58', '58', 5000, 10, '1', '2023-05-16', NULL, NULL),
(67, 69, 25, 67, 0, 153, 52, '59', '59', 500, 1000, '1', '2023-05-16', NULL, NULL),
(68, 69, 25, 67, 0, 154, 52, '60', '60', 500, 1000, '1', '2023-05-16', NULL, NULL),
(75, 78, 38, 92, 0, 200, 154, 'Aspernatur hic quia ', 'days', 72, 25, '1', '2023-08-10', NULL, NULL),
(76, 78, 38, 92, 0, 201, 154, 'Dolores ab sunt aut ', 'days', 44, 19, '1', '2023-08-10', NULL, NULL),
(77, 78, 38, 92, 0, 202, 154, 'Natus nihil ut sit ', 'cm3', 13, 11, '1', '2023-08-10', NULL, NULL),
(78, 78, 38, 92, 0, 192, 159, 'Nesciunt quo sed fu', 'Earum ad quis hic an', 44, 20, '1', '2023-08-10', NULL, NULL),
(79, 78, 38, 92, 0, 193, 159, 'Aliqua Consequatur', 'Maiores quasi nobis ', 41, 82, '1', '2023-08-10', NULL, NULL),
(80, 78, 38, 92, 0, 194, 159, 'Non quo sapiente est', 'Dolorem amet vel fu', 6, 30, '1', '2023-08-10', NULL, NULL),
(81, 78, 38, 92, 0, 195, 159, 'Impedit officiis au', 'Velit omnis aliquam ', 44, 54, '1', '2023-08-10', NULL, NULL),
(82, 78, 38, 92, 0, 203, 160, 'Magnam dolorem sunt ', 'A proident doloribu', 33, 44, '1', '2023-08-10', NULL, NULL),
(83, 78, 38, 92, 0, 204, 160, 'Vitae ad magni est ', 'Distinctio Esse fug', 33, 0, '1', '2023-08-10', NULL, NULL),
(84, 78, 38, 92, 0, 205, 160, 'A culpa quibusdam hi', 'Unde aliqua Aut vit', 1, 71, '1', '2023-08-10', NULL, NULL),
(85, 78, 38, 92, 0, 206, 160, '', '', 4, 98, '1', '2023-08-10', NULL, NULL),
(86, 78, 38, 92, 0, 180, 161, 'Ducimus omnis ratio', 'Id quis autem offici', 51, 20, '1', '2023-08-10', NULL, NULL),
(87, 78, 38, 92, 0, 181, 161, 'Voluptatibus aut min', 'Voluptatem sed amet', 11, 1, '1', '2023-08-10', NULL, NULL),
(88, 78, 38, 92, 0, 182, 161, 'Ut perferendis quibu', 'Minim eaque est perf', 6, 34, '1', '2023-08-10', NULL, NULL),
(89, 69, 25, 66, 0, 159, 51, '57', '57', 800, 1000, '1', '2023-08-28', NULL, NULL),
(90, 69, 25, 66, 0, 160, 51, '58', '58', 800, 1000, '1', '2023-08-28', NULL, NULL),
(91, 69, 25, 66, 0, 157, 52, '59', '59', 1000, 10, '1', '2023-08-28', NULL, NULL),
(92, 69, 25, 66, 0, 158, 52, '60', '60', 1000, 10, '1', '2023-08-28', NULL, NULL),
(114, 97, 56, 140, 337, 544, 108, 'Drilling 8', '14', 50000, 100, '118', '2023-09-05', NULL, NULL),
(115, 97, 56, 140, 340, 545, 108, 'Drilling 8', '14', 40000, 200, '118', '2023-09-05', NULL, NULL),
(116, 97, 56, 140, 341, 546, 108, 'Drilling 8', '14', 15000, 200, '118', '2023-09-05', NULL, NULL),
(123, 97, 56, 138, 337, 555, 108, 'Drilling 8', '14', 40000, 200, '118', '2023-09-05', NULL, NULL),
(124, 97, 56, 138, 340, 556, 108, 'Drilling 8', '14', 40000, 100, '118', '2023-09-05', NULL, NULL),
(125, 97, 56, 138, 341, 557, 108, 'Drilling 8', '14', 20000, 100, '118', '2023-09-05', NULL, NULL),
(126, 97, 56, 139, 337, 552, 108, 'Drilling 8', '14', 40000, 200, '118', '2023-09-05', NULL, NULL),
(127, 97, 56, 139, 340, 553, 108, 'Drilling 8', '14', 40000, 100, '118', '2023-09-05', NULL, NULL),
(128, 97, 56, 139, 341, 554, 108, 'Drilling 8', '14', 40000, 100, '118', '2023-09-05', NULL, NULL),
(133, 96, 54, 137, 317, 593, 103, 'Top Soil Removal ', '13', 1, 2341000, '118', '2023-09-06', NULL, NULL),
(134, 96, 54, 137, 0, 594, 103, 'test', '2', 1, 80000000, '118', '2023-09-06', NULL, NULL),
(135, 96, 54, 137, 318, 595, 104, 'Excavation ', '13', 1, 100000000, '118', '2023-09-06', NULL, NULL),
(136, 96, 54, 137, 319, 596, 104, 'Backfilling ', '13', 1, 50000000, '118', '2023-09-06', NULL, NULL),
(137, 96, 54, 137, 320, 597, 104, 'Planking and Strutting', '14', 1, 50000000, '118', '2023-09-06', NULL, NULL),
(138, 96, 53, 0, 323, 571, 101, 'Earthworks', '13', 4000, 6000, '118', '2023-09-06', NULL, NULL),
(139, 96, 53, 0, 0, 572, 101, 'Profits', '13', 9000, 9000, '118', '2023-09-06', NULL, NULL),
(140, 96, 53, 0, 0, 573, 101, 'see what i got', '75', 7777, 3000, '118', '2023-09-06', NULL, NULL),
(141, 96, 53, 0, 321, 579, 100, 'Resident Engineers Office', '19', 2000000, 1, '118', '2023-09-06', NULL, NULL),
(142, 96, 53, 0, 322, 580, 100, 'Contractors Site', '19', 30000000, 1, '118', '2023-09-06', NULL, NULL),
(143, 96, 53, 0, 0, 581, 100, 'Profits', '54', 2090000, 3, '118', '2023-09-06', NULL, NULL),
(144, 96, 53, 0, 0, 582, 100, 'Extra over 01-80-030A for contractors’ profits and overheads', '16', 1200000, 2, '118', '2023-09-06', NULL, NULL),
(145, 96, 54, 136, 318, 527, 104, 'Excavation ', '13', 2502, 7700, '118', '2023-09-06', NULL, NULL),
(146, 96, 54, 136, 319, 528, 104, 'Backfilling ', '13', 8000, 8800, '118', '2023-09-06', NULL, NULL),
(147, 96, 54, 136, 320, 529, 104, 'Planking and Strutting', '14', 9000, 5600, '118', '2023-09-06', NULL, NULL),
(148, 96, 54, 136, 317, 562, 103, 'Top Soil Removal ', '13', 11000, 1000, '118', '2023-09-06', NULL, NULL),
(149, 101, 59, 144, 348, 650, 112, 'Removal of trees and stumps', '14', 2000, 1000, '118', '2023-09-14', NULL, NULL),
(150, 101, 59, 144, 349, 651, 113, 'Top soil removal ', '13', 2000, 1000, '118', '2023-09-14', NULL, NULL),
(385, 109, 67, 153, 352, 660, 120, 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', '13', 10, 365, '118', '2023-09-29', NULL, NULL),
(386, 109, 67, 153, 353, 661, 120, 'River deviation', '13', 1, 3900, '118', '2023-09-29', NULL, NULL),
(387, 109, 67, 153, 0, 662, 120, 'Rockfill below structures', '13', 3, 8000, '118', '2023-09-29', NULL, NULL),
(388, 109, 67, 153, 354, 663, 121, 'Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 96, 1200, '118', '2023-09-29', NULL, NULL),
(389, 109, 67, 153, 355, 664, 121, 'Extra over excavation in hardrock', '13', 22, 1700, '118', '2023-09-29', NULL, NULL),
(390, 109, 67, 153, 356, 665, 121, 'Keep excavations free from all water by baling, pumping or otherwise', '19', 86, 1780, '118', '2023-09-29', NULL, NULL),
(391, 109, 67, 153, 357, 666, 122, 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 41, 2097, '118', '2023-09-29', NULL, NULL),
(392, 109, 67, 153, 358, 667, 122, 'Excavate for Approaches, Erosion Check, Scour Checks and the like. Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions (Provisional)', '13', 73, 3400, '118', '2023-09-29', NULL, NULL),
(393, 109, 67, 153, 359, 668, 122, '200mm thick dry stone pitching to embankments and in front of abutments', '72', 21, 1071, '118', '2023-09-29', NULL, NULL),
(394, 109, 67, 153, 360, 669, 123, 'Excavation in soft material to any depth, compaction of the surfaces to receive the gabions, backfilling with the excavated materials or hauling to spoil excavated material', '13', 27, 120, '118', '2023-09-29', NULL, NULL),
(395, 109, 67, 153, 361, 670, 123, 'Providing and fixing the mesh including diaphragms', '72', 44, 400, '118', '2023-09-29', NULL, NULL),
(396, 109, 67, 153, 362, 671, 123, 'Providing, hauling and placing the rock', '13', 45, 129, '118', '2023-09-29', NULL, NULL),
(397, 109, 67, 153, 363, 672, 123, 'Providing and hauling all materials, preparation, handling, placing of 75mm thick concrete to floor bed as blinding.', '72', 1400, 879, '118', '2023-09-29', NULL, NULL),
(398, 109, 67, 153, 364, 673, 123, 'Providing and hauling all materials, preparation, handling, placing, finishing and curing premix concrete to slab bed , column base, side walls,beams and deck slab.', '13', 157, 901, '118', '2023-09-29', NULL, NULL),
(399, 109, 67, 153, 365, 674, 123, 'ditto to column but mixing on site.', '13', 600, 120, '118', '2023-09-29', NULL, NULL),
(400, 109, 67, 154, 352, 675, 120, 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', '13', 1000, 139, '118', '2023-09-29', NULL, NULL),
(401, 109, 67, 154, 353, 676, 120, 'River deviation', '13', 800, 289, '118', '2023-09-29', NULL, NULL),
(402, 109, 67, 154, 354, 677, 121, 'Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 1008, 200, '118', '2023-09-29', NULL, NULL),
(403, 109, 67, 154, 355, 678, 121, 'Extra over excavation in hardrock', '13', 709, 187, '118', '2023-09-29', NULL, NULL),
(404, 109, 67, 154, 356, 679, 121, 'Keep excavations free from all water by baling, pumping or otherwise', '19', 888, 150, '118', '2023-09-29', NULL, NULL),
(405, 109, 67, 154, 0, 680, 121, 'contractor profits', '13', 1800, 400, '118', '2023-09-29', NULL, NULL),
(406, 109, 67, 154, 357, 681, 122, 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 680, 678, '118', '2023-09-29', NULL, NULL),
(407, 109, 67, 154, 358, 682, 122, 'Excavate for Approaches, Erosion Check, Scour Checks and the like. Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions (Provisional)', '13', 700, 980, '118', '2023-09-29', NULL, NULL),
(408, 109, 67, 154, 359, 683, 122, '200mm thick dry stone pitching to embankments and in front of abutments', '72', 878, 670, '118', '2023-09-29', NULL, NULL),
(409, 109, 67, 154, 360, 684, 123, 'Excavation in soft material to any depth, compaction of the surfaces to receive the gabions, backfilling with the excavated materials or hauling to spoil excavated material', '13', 403, 573, '118', '2023-09-29', NULL, NULL),
(410, 109, 67, 154, 361, 685, 123, 'Providing and fixing the mesh including diaphragms', '72', 3093, 391, '118', '2023-09-29', NULL, NULL),
(411, 109, 67, 154, 362, 686, 123, 'Providing, hauling and placing the rock', '13', 2100, 878, '118', '2023-09-29', NULL, NULL),
(412, 109, 67, 154, 363, 687, 123, 'Providing and hauling all materials, preparation, handling, placing of 75mm thick concrete to floor bed as blinding.', '72', 801, 999, '118', '2023-09-29', NULL, NULL),
(413, 109, 67, 154, 364, 688, 123, 'Providing and hauling all materials, preparation, handling, placing, finishing and curing premix concrete to slab bed , column base, side walls,beams and deck slab.', '13', 1000, 664, '118', '2023-09-29', NULL, NULL),
(414, 109, 67, 154, 365, 689, 123, 'ditto to column but mixing on site.', '13', 306, 361, '118', '2023-09-29', NULL, NULL),
(415, 109, 67, 155, 360, 700, 123, 'Excavation in soft material to any depth, compaction of the surfaces to receive the gabions, backfilling with the excavated materials or hauling to spoil excavated material', '13', 13, 35, '118', '2023-09-29', NULL, NULL),
(416, 109, 67, 155, 361, 701, 123, 'Providing and fixing the mesh including diaphragms', '72', 27, 58, '118', '2023-09-29', NULL, NULL),
(417, 109, 67, 155, 362, 702, 123, 'Providing, hauling and placing the rock', '13', 82, 58, '118', '2023-09-29', NULL, NULL),
(418, 109, 67, 155, 363, 703, 123, 'Providing and hauling all materials, preparation, handling, placing of 75mm thick concrete to floor bed as blinding.', '72', 82, 98, '118', '2023-09-29', NULL, NULL),
(419, 109, 67, 155, 364, 704, 123, 'Providing and hauling all materials, preparation, handling, placing, finishing and curing premix concrete to slab bed , column base, side walls,beams and deck slab.', '13', 20, 41, '118', '2023-09-29', NULL, NULL),
(420, 109, 67, 155, 365, 705, 123, 'ditto to column but mixing on site.', '13', 91, 2, '118', '2023-09-29', NULL, NULL),
(421, 109, 67, 155, 357, 706, 122, 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 51, 66, '118', '2023-09-29', NULL, NULL),
(422, 109, 67, 155, 358, 707, 122, 'Excavate for Approaches, Erosion Check, Scour Checks and the like. Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions (Provisional)', '13', 99, 92, '118', '2023-09-29', NULL, NULL),
(423, 109, 67, 155, 359, 708, 122, '200mm thick dry stone pitching to embankments and in front of abutments', '72', 93, 53, '118', '2023-09-29', NULL, NULL),
(424, 109, 67, 155, 354, 709, 121, 'Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 69, 76, '118', '2023-09-29', NULL, NULL),
(425, 109, 67, 155, 355, 710, 121, 'Extra over excavation in hardrock', '13', 44, 60, '118', '2023-09-29', NULL, NULL),
(426, 109, 67, 155, 356, 711, 121, 'Keep excavations free from all water by baling, pumping or otherwise', '19', 77, 66, '118', '2023-09-29', NULL, NULL),
(427, 109, 67, 155, 352, 712, 120, 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', '13', 25, 42, '118', '2023-09-29', NULL, NULL),
(428, 109, 67, 155, 353, 713, 120, 'River deviation', '13', 90, 28, '118', '2023-09-29', NULL, NULL),
(429, 109, 67, 156, 357, 714, 122, 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 16, 69, '118', '2023-09-29', NULL, NULL),
(430, 109, 67, 156, 358, 715, 122, 'Excavate for Approaches, Erosion Check, Scour Checks and the like. Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions (Provisional)', '13', 47, 98, '118', '2023-09-29', NULL, NULL),
(431, 109, 67, 156, 359, 716, 122, '200mm thick dry stone pitching to embankments and in front of abutments', '72', 76, 24, '118', '2023-09-29', NULL, NULL),
(432, 109, 67, 156, 352, 717, 120, 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', '13', 74, 24, '118', '2023-09-29', NULL, NULL),
(433, 109, 67, 156, 353, 718, 120, 'River deviation', '13', 36, 100, '118', '2023-09-29', NULL, NULL),
(434, 109, 67, 156, 354, 719, 121, 'Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', '13', 60, 61, '118', '2023-09-29', NULL, NULL),
(435, 109, 67, 156, 355, 720, 121, 'Extra over excavation in hardrock', '13', 6, 51, '118', '2023-09-29', NULL, NULL),
(436, 109, 67, 156, 356, 721, 121, 'Keep excavations free from all water by baling, pumping or otherwise', '19', 52, 44, '118', '2023-09-29', NULL, NULL),
(437, 109, 67, 156, 360, 722, 123, 'Excavation in soft material to any depth, compaction of the surfaces to receive the gabions, backfilling with the excavated materials or hauling to spoil excavated material', '13', 82, 92, '118', '2023-09-29', NULL, NULL),
(438, 109, 67, 156, 361, 723, 123, 'Providing and fixing the mesh including diaphragms', '72', 46, 47, '118', '2023-09-29', NULL, NULL),
(439, 109, 67, 156, 362, 724, 123, 'Providing, hauling and placing the rock', '13', 66, 86, '118', '2023-09-29', NULL, NULL),
(440, 109, 67, 156, 363, 725, 123, 'Providing and hauling all materials, preparation, handling, placing of 75mm thick concrete to floor bed as blinding.', '72', 62, 68, '118', '2023-09-29', NULL, NULL),
(441, 109, 67, 156, 364, 726, 123, 'Providing and hauling all materials, preparation, handling, placing, finishing and curing premix concrete to slab bed , column base, side walls,beams and deck slab.', '13', 19, 71, '118', '2023-09-29', NULL, NULL),
(442, 109, 67, 156, 365, 727, 123, 'ditto to column but mixing on site.', '13', 15, 51, '118', '2023-09-29', NULL, NULL),
(443, 109, 66, 0, 375, 690, 118, 'Fill in soft material', '13', 11, 92, '118', '2023-09-29', NULL, NULL),
(444, 109, 66, 0, 376, 691, 118, 'Improved Subgrade fill class s3', '13', 81, 54, '118', '2023-09-29', NULL, NULL),
(445, 109, 66, 0, 377, 692, 118, 'Compaction of 150mm depth of existing ground under embankments to 100% MDD (AASHTO-T99', '13', 18, 69, '118', '2023-09-29', NULL, NULL),
(446, 109, 66, 0, 378, 693, 118, 'Compaction of the 300 mm below formation level in cuttings to 100% MDD (AASHTO-T99', '13', 81, 44, '118', '2023-09-29', NULL, NULL),
(447, 109, 66, 0, 370, 695, 117, 'Excavate for structures in soft material', '13', 12, 82, '118', '2023-09-29', NULL, NULL),
(448, 109, 66, 0, 371, 696, 117, 'Excavation of unsuitable material below structure', '13', 40, 75, '118', '2023-09-29', NULL, NULL),
(449, 109, 66, 0, 372, 697, 117, 'Extra over item 7.01 for excavation in hard material', '13', 62, 88, '118', '2023-09-29', NULL, NULL),
(450, 109, 66, 0, 373, 698, 117, 'Selected granular fill material', '13', 51, 31, '118', '2023-09-29', NULL, NULL),
(451, 109, 66, 0, 374, 699, 117, 'Gabion mattress mesh', '13', 74, 49, '118', '2023-09-29', NULL, NULL),
(452, 109, 66, 0, 366, 728, 116, 'Preparation of Contractors Site', '19', 67, 35, '118', '2023-09-29', NULL, NULL),
(453, 109, 66, 0, 367, 729, 116, 'Purchase of Equipments', '19', 51, 50, '118', '2023-09-29', NULL, NULL),
(454, 109, 66, 0, 368, 730, 116, 'Construction of resident engineer\'s office ', '19', 3, 54, '118', '2023-09-29', NULL, NULL),
(455, 109, 66, 0, 369, 731, 116, 'Mobilization of Plant facilities', '19', 38, 66, '118', '2023-09-29', NULL, NULL),
(456, 109, 66, 0, 379, 734, 119, 'Final Inspection and Handover', '75', 500000, 10, '118', '2023-09-29', NULL, NULL),
(457, 109, 66, 0, 0, 735, 119, 'Refreshments ', '75', 800000, 1, '118', '2023-09-29', NULL, NULL),
(458, 109, 66, 0, 0, 736, 119, 'Hiring of Tents ', '75', 29360, 1, '118', '2023-09-29', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_tender_details_onhold_originals`
--

CREATE TABLE `tbl_project_tender_details_onhold_originals` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `issueid` int NOT NULL,
  `costlineid` int NOT NULL,
  `unit_cost` double NOT NULL,
  `units_no` int NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_timeline_substage_records`
--

CREATE TABLE `tbl_project_timeline_substage_records` (
  `id` int NOT NULL,
  `projid` int NOT NULL,
  `projstage` int NOT NULL,
  `proj_substage` int NOT NULL,
  `created_by` int NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_workflow_stage`
--

CREATE TABLE `tbl_project_workflow_stage` (
  `id` int NOT NULL,
  `stage` varchar(255) NOT NULL,
  `parent` int DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `priority` int NOT NULL,
  `department_id` int NOT NULL DEFAULT '0',
  `section_id` int NOT NULL DEFAULT '0',
  `directorate_id` int NOT NULL DEFAULT '0',
  `active` int NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_workflow_stage`
--

INSERT INTO `tbl_project_workflow_stage` (`id`, `stage`, `parent`, `description`, `priority`, `department_id`, `section_id`, `directorate_id`, `active`) VALUES
(1, 'Planned ', 0, NULL, 0, 0, 0, 0, 1),
(2, 'Activities', 1, NULL, 1, 0, 0, 0, 1),
(3, 'Milestones', 2, NULL, 2, 0, 0, 0, 1),
(4, 'Financial Plan', 3, NULL, 3, 0, 0, 0, 1),
(5, 'Procurement', 4, NULL, 4, 0, 0, 0, 1),
(6, 'M&E Plan', 5, NULL, 6, 0, 0, 0, 1),
(7, 'Mapping', 6, NULL, 8, 0, 0, 0, 1),
(8, 'Team', 7, NULL, 5, 0, 0, 0, 1),
(9, 'Baseline Survey', 8, NULL, 9, 0, 0, 0, 1),
(10, 'Implimentation', 9, NULL, 10, 0, 0, 0, 1),
(11, 'Evaluation', 10, NULL, 11, 0, 0, 0, 1),
(26, 'Risk Plan', 6, NULL, 7, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_workflow_stage_new`
--

CREATE TABLE `tbl_project_workflow_stage_new` (
  `id` int NOT NULL,
  `stage` varchar(255) NOT NULL,
  `parent` int DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_project_workflow_stage_new`
--

INSERT INTO `tbl_project_workflow_stage_new` (`id`, `stage`, `parent`, `description`, `active`) VALUES
(1, 'Planned ', 0, NULL, 1),
(2, 'Activities', 1, NULL, 1),
(3, 'Financial Plan', 2, NULL, 1),
(4, 'Procurement', 3, NULL, 1),
(5, 'Team', 4, NULL, 1),
(6, 'M&E Plan', 4, NULL, 1),
(9, 'Mapping', 5, NULL, 1),
(7, 'Baseline Survey', 6, NULL, 1),
(8, 'Implementation', 7, NULL, 1),
(10, 'Evaluation', 9, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_workflow_stage_old`
--

CREATE TABLE `tbl_project_workflow_stage_old` (
  `id` int NOT NULL,
  `stage` varchar(255) NOT NULL,
  `parent` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_workflow_stage_timelines`
--

CREATE TABLE `tbl_project_workflow_stage_timelines` (
  `id` int NOT NULL,
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

INSERT INTO `tbl_project_workflow_stage_timelines` (`id`, `category`, `stage`, `status`, `description`, `time`, `escalate_after`, `units`, `escalate_to`, `active`) VALUES
(1, 'issue', 1, 'Open', 'project team leader assign issue owner', 5, 2, 'days', 0, 1),
(2, 'issue', 2, 'Analysis', 'issue owner start working on the assigned issue', 5, 2, 'days', 0, 1),
(3, 'issue', 3, 'Analyzed', 'Issue analyzed and report ready', 2, 1, 'days', 0, 1),
(4, 'issue', 4, 'Escalated', 'Issue escalated to project committee for further action', 30, 5, 'days', 0, 1),
(5, 'issue', 5, 'On Hold', 'project committee discussed escalated issue and decide to put the project on hold', 30, 2, 'days', 0, 1),
(6, 'issue', 6, 'Continue', 'Project committee decides to let the project continue', 5, 2, 'days', 0, 1),
(7, 'issue', 7, 'Closed', 'Issue closed by the project team leader', 5, 2, 'days', 0, 1),
(8, 'Baseline', 1, 'Pending', 'Pending Form Details', 30, 5, 'days', 0, 0),
(9, 'Baseline', 2, 'Pending Deployment', 'Pending Deployment', 10, 5, 'days', 0, 0),
(11, 'Endline', 1, 'Pending', 'Pending Form Details', 30, 5, 'days', 0, 0),
(12, 'mne', 1, 'Pending', 'Pending', 30, 2, 'days', 4, 1),
(13, 'In-house-Payment', 1, 'Pending', 'Maximum time Chief Office, project department, should take to approve payment request from director project directorate', 2, 1, 'days', 4, 1),
(14, 'Contractor-Payment', 1, 'Pending', 'Maximum time Chief Office, project department, should take to approve payment request from director project department', 10, 5, 'days', 2, 1),
(15, 'Contractor-Payment', 2, 'Pending', 'Maximum time Chief Office finance should take to approve payment request from CO project department', 10, 5, 'days', 2, 1),
(17, 'Contractor-Payment', 3, 'Pending', 'Maximum time director finance directorate should take to pay contractor after CO finance section approves a payment request', 30, 10, 'day', 2, 1),
(18, 'In-house-Payment', 2, 'Pending', 'Maximum time Chief Office, finance section, should take to approve payment request from Chief Office project section', 5, 5, 'days', 2, 1),
(19, 'In-house-Payment', 3, 'Pending', 'Maximum time director finance section should take to pay contractor after Chief Officer finance section approves a payment request', 2, 1, 'days', 6, 1);

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
(1, 1, 1, 1, 100, '124', '2023-03-15 00:00:00'),
(2, 2, 2, 1, 100, '121', '2023-03-15 00:00:00'),
(3, 2, 3, 1, 100, '121', '2023-03-15 00:00:00'),
(4, 2, 4, 1, 100, '118', '2023-03-17 00:00:00'),
(5, 2, 5, 1, 100, '118', '2023-03-17 00:00:00'),
(6, 2, 6, 1, 100, '118', '2023-03-17 00:00:00'),
(7, 2, 7, 1, 100, '118', '2023-03-17 00:00:00'),
(8, 3, 8, 1, 100, '121', '2023-03-18 00:00:00'),
(10, 4, 15, 1, 100, '124', '2023-03-21 00:00:00'),
(11, 4, 51, 1, 100, '124', '2023-03-21 00:00:00'),
(12, 4, 52, 1, 100, '124', '2023-03-23 00:00:00'),
(13, 4, 53, 1, 100, '124', '2023-03-23 00:00:00'),
(14, 4, 54, 1, 100, '124', '2023-03-23 00:00:00'),
(15, 4, 55, 1, 100, '124', '2023-03-23 00:00:00'),
(16, 4, 56, 1, 100, '124', '2023-03-23 00:00:00'),
(17, 2, 57, 1, 100, '118', '2023-04-01 00:00:00'),
(18, 2, 58, 1, 100, '118', '2023-04-01 00:00:00'),
(19, 2, 59, 1, 100, '118', '2023-04-01 00:00:00');

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
  `projid` int NOT NULL,
  `output_id` int DEFAULT NULL,
  `milestone_id` int DEFAULT NULL,
  `site_id` int DEFAULT NULL,
  `task_id` int DEFAULT NULL,
  `subtask_id` int DEFAULT NULL,
  `formid` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `monitoring_id` int NOT NULL DEFAULT '0',
  `origin` varchar(100) NOT NULL,
  `issue_area` int DEFAULT '1' COMMENT '1 is Quality; 2 is Scope; 3 is  Schedule; 4 is Cost',
  `risk_category` int NOT NULL,
  `observation` text NOT NULL,
  `recommendation` varchar(255) DEFAULT NULL,
  `owner` int DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1 is pending; 2 is analysis; 3 is  analyzed; 4 is escalated; 5 is continue; 6 is on hold; 7 is closed',
  `priority` int DEFAULT NULL COMMENT '1 is High; 2 is Medium; 3 is Low',
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

INSERT INTO `tbl_projissues` (`id`, `projid`, `output_id`, `milestone_id`, `site_id`, `task_id`, `subtask_id`, `formid`, `monitoring_id`, `origin`, `issue_area`, `risk_category`, `observation`, `recommendation`, `owner`, `status`, `priority`, `assessment`, `created_by`, `date_created`, `assigned_by`, `date_assigned`, `escalated_by`, `date_escalated`, `closed_by`, `date_closed`) VALUES
(1, 1, 1, 1, 0, NULL, 377, '2023-03-17', 0, '4', NULL, 2, 'TEST', NULL, NULL, 1, NULL, 0, '128', '2023-03-17', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 1, 1, 0, 1, 377, '2023-03-17', 0, '3', NULL, 5, 'Heavy rains ..dangerous falling rocks', NULL, NULL, 1, NULL, 0, '118', '2023-03-17', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, 5, 4, 6, 7, 0, '2023-03-21', 0, '2', NULL, 5, 'Testing', NULL, 143, 7, 1, 0, '118', '2023-03-21', '118', '2023-04-01', NULL, NULL, '1', '2023-04-08 18:04:17'),
(4, 96, 54, 34, 136, 103, 317, '2023-09-08', 0, '3', NULL, 4, 'Animi fugiat omnis', NULL, NULL, 1, NULL, 0, '1', '2023-09-08', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 96, 54, 34, 136, 103, 317, '2023-09-08', 0, '3', NULL, 5, 'Nihil exercitationem', NULL, NULL, 1, NULL, 0, '1', '2023-09-08', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 96, 54, 34, 136, 103, 317, '2023-09-08', 0, '3', NULL, 2, 'Ut aut in suscipit v', NULL, NULL, 1, NULL, 0, '1', '2023-09-08', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 96, 54, 34, 136, 103, 317, '2023-09-08', 0, '3', NULL, 1, 'Odio voluptates eos ', NULL, NULL, 1, NULL, 0, '1', '2023-09-08', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 96, 53, 34, 0, 101, 323, '2023-09-08', 0, '3', NULL, 5, 'Elit iusto nihil ha', NULL, NULL, 1, NULL, 0, '1', '2023-09-08', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 96, 53, 34, 0, 101, 323, '2023-09-08', 0, '3', NULL, 1, 'Illo sit proident ', NULL, NULL, 1, NULL, 0, '1', '2023-09-08', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 96, 53, 34, 0, 101, 323, '2023-09-08', 0, '3', NULL, 1, 'Aperiam non natus si', NULL, NULL, 1, NULL, 0, '1', '2023-09-08', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 78, NULL, NULL, NULL, NULL, NULL, '2023-09-11', 0, '3', NULL, 2, 'In do ut in sint ex', NULL, NULL, 1, NULL, 0, '1', '2023-09-11', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 78, NULL, NULL, NULL, NULL, NULL, '2023-09-11', 0, '3', NULL, 4, 'Dolorem reprehenderi', NULL, NULL, 1, NULL, 0, '1', '2023-09-11', NULL, NULL, NULL, NULL, NULL, NULL),
(13, 78, NULL, NULL, NULL, NULL, NULL, '2023-09-11', 0, '3', NULL, 3, 'Nobis labore lorem n', NULL, NULL, 1, NULL, 0, '1', '2023-09-11', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 78, NULL, NULL, NULL, NULL, NULL, '2023-09-11', 0, '3', NULL, 1, 'Repellendus Sequi n', NULL, NULL, 1, NULL, 0, '1', '2023-09-11', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 78, NULL, NULL, NULL, NULL, NULL, '2023-09-25', 0, '3', 1, 4, 'Testing new issue set up!', NULL, NULL, 1, 1, 0, '118', '2023-09-25', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 78, NULL, NULL, NULL, NULL, NULL, '2023-09-25', 0, '3', 4, 4, 'Testing again', NULL, NULL, 1, 1, 0, '118', '2023-09-25', NULL, NULL, NULL, NULL, NULL, NULL);

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
(1, 0, 3, 3, '118', 'Environmental: Testing', NULL, 2, '2023-04-01 13:17:17'),
(2, 1, 3, 3, '118', 'testing', NULL, 1, '2023-04-01 13:25:03');

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
(1, 3, 3, 2, '', '118', '2023-04-01'),
(2, 3, 3, 7, '', '1', '2023-04-08'),
(3, 3, 3, 7, '', '1', '2023-04-08');

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
  `projid` int NOT NULL,
  `output_id` int NOT NULL DEFAULT '0',
  `task_id` int DEFAULT '0',
  `stage` int DEFAULT NULL,
  `members` varchar(255) NOT NULL DEFAULT '0',
  `responsible` int NOT NULL,
  `role` int NOT NULL DEFAULT '0',
  `team_type` int NOT NULL DEFAULT '1',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projmembers`
--

INSERT INTO `tbl_projmembers` (`pmid`, `projid`, `output_id`, `task_id`, `stage`, `members`, `responsible`, `role`, `team_type`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 59, 0, 0, 1, '0', 121, 0, 1, 1, NULL, '2023-04-25', NULL),
(2, 62, 0, 0, 2, '0', 144, 0, 1, 1, NULL, '2023-04-25', NULL),
(3, 2, 0, 0, 4, '0', 121, 0, 1, 1, NULL, '2023-04-25', NULL),
(5, 58, 0, 0, 1, '121,143', 143, 0, 1, 1, NULL, '2023-04-26', NULL),
(63, 1, 0, 1, 7, '0', 121, 2, 1, 1, NULL, '2023-04-27', NULL),
(64, 1, 0, 2, 7, '0', 121, 2, 1, 1, NULL, '2023-04-27', NULL),
(65, 1, 0, 3, 7, '0', 121, 2, 1, 1, NULL, '2023-04-27', NULL),
(66, 1, 0, 4, 7, '0', 121, 2, 1, 1, NULL, '2023-04-27', NULL),
(67, 1, 0, 5, 7, '0', 121, 2, 1, 1, NULL, '2023-04-27', NULL),
(68, 1, 0, 6, 7, '0', 144, 4, 1, 1, NULL, '2023-04-27', NULL),
(69, 1, 0, 9, 7, '0', 144, 4, 1, 1, NULL, '2023-04-27', NULL),
(73, 7, 0, 0, 5, '0', 143, 0, 1, 1, NULL, '2023-04-27', NULL),
(74, 1, 0, 0, 5, '0', 124, 0, 1, 1, NULL, '2023-04-27', NULL),
(75, 1, 0, 0, 7, '0', 121, 2, 1, 1, NULL, '2023-04-27', NULL),
(76, 1, 0, 0, 7, '0', 143, 2, 1, 1, NULL, '2023-04-27', NULL),
(77, 1, 0, 0, 7, '0', 144, 3, 1, 1, NULL, '2023-04-27', NULL),
(78, 59, 0, 0, 1, '0', 121, 0, 1, 1, NULL, '2023-04-28', NULL),
(79, 59, 0, 0, 2, '0', 143, 0, 1, 1, NULL, '2023-05-01', NULL),
(80, 58, 0, 0, 1, '0', 143, 0, 1, 1, NULL, '2023-05-03', NULL),
(81, 58, 0, 0, 2, '0', 144, 0, 1, 1, NULL, '2023-05-03', NULL),
(82, 66, 0, 0, 2, '0', 124, 0, 1, 1, NULL, '2023-05-05', NULL),
(83, 66, 0, 0, 3, '0', 124, 0, 1, 1, NULL, '2023-05-05', NULL),
(84, 66, 0, 0, 3, '0', 124, 0, 1, 1, NULL, '2023-05-08', NULL),
(85, 66, 0, 0, 4, '0', 124, 0, 1, 1, NULL, '2023-05-08', NULL),
(86, 1, 0, 0, 4, '0', 134, 0, 1, 1, NULL, '2023-05-10', NULL),
(87, 69, 0, 0, 1, '0', 143, 0, 1, 1, NULL, '2023-05-11', NULL),
(88, 69, 0, 0, 2, '0', 121, 0, 1, 1, NULL, '2023-05-16', NULL),
(89, 69, 0, 0, 2, '0', 143, 0, 1, 1, NULL, '2023-05-16', NULL),
(90, 69, 0, 0, 3, '0', 143, 0, 1, 1, NULL, '2023-05-16', NULL),
(91, 69, 0, 0, 4, '0', 143, 0, 1, 1, NULL, '2023-05-16', NULL),
(93, 71, 0, 0, 1, '0', 121, 0, 1, 118, NULL, '2023-05-30', NULL),
(94, 71, 0, 0, 1, '0', 121, 0, 1, 1, NULL, '2023-06-10', NULL),
(95, 71, 0, 0, 2, '0', 121, 0, 1, 118, NULL, '2023-08-03', NULL),
(96, 78, 0, 0, 1, '0', 124, 0, 1, 118, NULL, '2023-08-04', NULL),
(98, 78, 0, 0, 7, '0', 133, 3, 1, 1, NULL, '2023-08-08', NULL),
(99, 78, 0, 154, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(100, 78, 0, 158, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(101, 78, 0, 155, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(102, 78, 0, 155, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(103, 78, 0, 160, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(104, 78, 0, 140, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(105, 78, 0, 154, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(106, 78, 0, 156, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(107, 78, 0, 163, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(108, 78, 0, 158, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(109, 78, 0, 155, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(110, 78, 0, 164, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(111, 78, 0, 155, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(112, 78, 0, 167, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(113, 78, 0, 160, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(114, 78, 0, 140, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(115, 78, 0, 150, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(116, 78, 0, 151, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(117, 78, 0, 154, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(118, 78, 0, 156, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(119, 78, 0, 163, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(120, 78, 0, 158, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(121, 78, 0, 155, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(122, 78, 0, 164, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(123, 78, 0, 155, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(124, 78, 0, 167, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(125, 78, 0, 160, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(126, 78, 0, 140, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(127, 78, 0, 150, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(128, 78, 0, 151, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(129, 78, 0, 157, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(130, 78, 0, 162, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(131, 78, 0, 161, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(132, 78, 0, 165, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(133, 78, 0, 166, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(134, 78, 0, 162, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(135, 78, 0, 165, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(136, 78, 0, 161, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(137, 78, 0, 162, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(138, 78, 0, 141, 10, '0', 121, 2, 1, 1, NULL, '2023-08-14', NULL),
(139, 78, 0, 159, 10, '0', 143, 2, 1, 1, NULL, '2023-08-14', NULL),
(140, 78, 0, 139, 10, '0', 143, 2, 1, 1, NULL, '2023-08-14', NULL),
(141, 78, 0, 143, 10, '0', 143, 2, 1, 1, NULL, '2023-08-14', NULL),
(142, 78, 0, 148, 10, '0', 143, 2, 1, 1, NULL, '2023-08-14', NULL),
(143, 78, 0, 149, 10, '0', 143, 2, 1, 1, NULL, '2023-08-14', NULL),
(144, 78, 0, 153, 10, '0', 143, 2, 1, 1, NULL, '2023-08-14', NULL),
(145, 78, 0, 0, 10, '0', 143, 2, 1, 1, NULL, '2023-08-14', NULL),
(146, 91, 0, 0, 1, '0', 121, 0, 1, 118, NULL, '2023-08-14', NULL),
(147, 91, 0, 0, 2, '0', 121, 0, 1, 118, NULL, '2023-08-14', NULL),
(148, 91, 0, 0, 3, '0', 121, 0, 1, 118, NULL, '2023-08-15', NULL),
(156, 96, 0, 0, 7, '0', 128, 2, 1, 118, NULL, '2023-09-08', NULL),
(159, 97, 0, 0, 7, '0', 128, 2, 1, 118, NULL, '2023-09-08', NULL),
(160, 97, 0, 0, 7, '0', 133, 5, 1, 118, NULL, '2023-09-08', NULL),
(163, 7, 0, 0, 7, '0', 128, 2, 1, 118, NULL, '2023-09-14', NULL),
(164, 7, 0, 0, 7, '0', 133, 4, 1, 118, NULL, '2023-09-14', NULL),
(165, 101, 0, 0, 7, '0', 128, 2, 1, 118, NULL, '2023-09-14', NULL),
(166, 101, 0, 0, 7, '0', 133, 4, 1, 118, NULL, '2023-09-14', NULL),
(167, 84, 0, 0, 7, '0', 128, 5, 1, 118, NULL, '2023-09-14', NULL),
(198, 15, 0, 0, 9, '0', 128, 1, 1, 1, NULL, '2023-09-15', NULL),
(199, 15, 0, 0, 9, '0', 133, 3, 3, 1, NULL, '2023-09-15', NULL),
(205, 101, 0, 0, 9, '0', 143, 2, 4, 1, NULL, '2023-09-15', NULL),
(207, 101, 0, 0, 9, '0', 121, 3, 4, 1, NULL, '2023-09-16', NULL),
(218, 101, 0, 0, 9, '0', 124, 3, 4, 1, NULL, '2023-09-18', NULL),
(219, 101, 0, 0, 9, '0', 119, 2, 4, 1, NULL, '2023-09-18', NULL),
(220, 101, 0, 0, 9, '0', 130, 3, 4, 1, NULL, '2023-09-18', NULL),
(221, 101, 0, 0, 9, '0', 144, 4, 4, 1, NULL, '2023-09-18', NULL),
(222, 7, 0, 0, 9, '0', 128, 1, 1, 118, NULL, '2023-09-26', NULL),
(223, 7, 0, 0, 9, '0', 133, 2, 2, 118, NULL, '2023-09-26', NULL),
(224, 7, 0, 0, 9, '0', 133, 3, 3, 118, NULL, '2023-09-26', NULL),
(225, 109, 0, 0, 10, '0', 123, 2, 4, 118, NULL, '2023-09-29', NULL),
(226, 109, 0, 0, 10, '0', 121, 4, 4, 118, NULL, '2023-09-29', NULL),
(228, 109, 0, 0, 10, '0', 128, 5, 4, 118, NULL, '2023-09-29', NULL),
(229, 109, 0, 0, 10, '0', 132, 3, 4, 118, NULL, '2023-09-30', NULL),
(230, 109, 0, 0, 9, '0', 128, 1, 1, 118, NULL, '2023-09-30', NULL),
(231, 109, 0, 0, 9, '0', 133, 2, 2, 118, NULL, '2023-09-30', NULL),
(232, 109, 0, 0, 9, '0', 128, 3, 3, 118, NULL, '2023-09-30', NULL),
(244, 101, 0, 0, 10, '0', 130, 0, 5, 118, NULL, '2023-10-11', NULL),
(245, 101, 0, 0, 10, '0', 137, 0, 5, 118, NULL, '2023-10-11', NULL),
(246, 101, 0, 0, 10, '0', 141, 0, 5, 118, NULL, '2023-10-11', NULL),
(247, 101, 0, 0, 10, '0', 120, 0, 5, 118, NULL, '2023-10-11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projmemoffices`
--

CREATE TABLE `tbl_projmemoffices` (
  `moid` int NOT NULL,
  `office` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projrisk_categories`
--

CREATE TABLE `tbl_projrisk_categories` (
  `catid` int NOT NULL,
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

INSERT INTO `tbl_projrisk_categories` (`catid`, `opid`, `department`, `category`, `description`, `type`, `created_by`, `date_created`, `changed_by`, `date_changed`) VALUES
(1, NULL, NULL, 'Security', NULL, '1,2,3', '120', '2022-07-20', NULL, NULL),
(2, NULL, NULL, 'Financial', NULL, '2,3', '120', '2022-07-20', NULL, NULL),
(3, NULL, NULL, 'Human Resources', NULL, '2,3', '120', '2022-07-20', NULL, NULL),
(4, NULL, NULL, 'Climatic', NULL, '1,2,3', '120', '2022-07-20', NULL, NULL),
(5, NULL, NULL, 'Environmental', NULL, '2,3', '120', '2022-07-20', NULL, NULL),
(6, NULL, NULL, 'Legal', NULL, '1,2,3', '120', '2022-07-20', NULL, NULL),
(7, NULL, NULL, 'Political', NULL, '1,2,3', '120', '2022-07-20', NULL, NULL),
(8, NULL, NULL, 'Test 123', NULL, '1,2', '118', '2022-12-15', '118', '2022-12-15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projrisk_categories_old`
--

CREATE TABLE `tbl_projrisk_categories_old` (
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
-- Dumping data for table `tbl_projrisk_categories_old`
--

INSERT INTO `tbl_projrisk_categories_old` (`rskid`, `opid`, `department`, `category`, `description`, `type`, `created_by`, `date_created`, `changed_by`, `date_changed`) VALUES
(1, NULL, NULL, 'Security', NULL, '2,3', '120', '2022-07-20', NULL, NULL),
(2, NULL, NULL, 'Financial', NULL, '2,3', '120', '2022-07-20', NULL, NULL),
(3, NULL, NULL, 'Human Resources', NULL, '2,3', '120', '2022-07-20', NULL, NULL),
(4, NULL, NULL, 'Climatic', NULL, '2,3', '120', '2022-07-20', NULL, NULL),
(5, NULL, NULL, 'Environmental', NULL, '2,3', '120', '2022-07-20', NULL, NULL),
(6, NULL, NULL, 'Legal', NULL, '2,3', '120', '2022-07-20', NULL, NULL),
(7, NULL, NULL, 'Political', NULL, '2,3', '120', '2022-07-20', NULL, NULL);

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
(1, 2, 'Look for alternative ways of financing', NULL),
(2, 7, 'Testing ABC', NULL),
(3, 6, 'Testing ABCD', NULL),
(4, 5, 'Testing Environmental', NULL),
(5, 4, 'Testing climate mitigations', NULL),
(6, 3, 'Testing HR mitigations', NULL),
(7, 1, 'Testing security risk mitigations', NULL);

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
  `fullname` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `firstname` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `middlename` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `lastname` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `title` int NOT NULL,
  `designation` int NOT NULL,
  `ministry` int DEFAULT NULL,
  `department` int DEFAULT NULL,
  `directorate` int DEFAULT NULL,
  `role_group` int NOT NULL,
  `levelA` int NOT NULL,
  `levelB` int NOT NULL,
  `levelC` int NOT NULL,
  `floc` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'uploads/passport.jpg',
  `filename` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `ftype` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `level` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `email` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `phone` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `availability` int NOT NULL DEFAULT '1',
  `disabled` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL,
  `datecreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_projteam2`
--

INSERT INTO `tbl_projteam2` (`ptid`, `fullname`, `firstname`, `middlename`, `lastname`, `title`, `designation`, `ministry`, `department`, `directorate`, `role_group`, `levelA`, `levelB`, `levelC`, `floc`, `filename`, `ftype`, `level`, `email`, `phone`, `availability`, `disabled`, `createdby`, `datecreated`) VALUES
(1, 'Evans Koech', 'Evans', 'Koech', 'Koech', 4, 1, 0, 0, 0, 4, 0, 0, 0, 'uploads/staff/5850_File_FQldlziXsAIzlbe.jpeg', 'FQldlziXsAIzlbe.jpeg', 'jpeg', '6', 'biwottech@gmail.com', '0727044818', 1, 0, 1, '2022-01-16'),
(2, 'Super  Admin', 'Super ', 'Admin', 'Admin', 2, 1, 0, 0, 0, 4, 0, 0, 0, 'uploads/staff/7726_File_download 8.png', 'download 8.png', 'png', NULL, 'denkytheka@gmail.com', '+254 727 044 818', 1, 0, 4, '2022-04-21'),
(3, 'Group1 Peter', 'Group1', '', 'Peter', 5, 7, 10, 17, 44, 1, 0, 0, 0, 'uploads/staff/2905_File_download 3.jpg', 'download 3.jpg', 'jpg', NULL, 'kkipe15@gmail.com', '+1 (974) 627-3956', 1, 0, 4, '2022-04-21'),
(4, 'Peter Korir', 'Peter', 'Director', 'Korir', 3, 7, 10, 18, 41, 1, 0, 0, 0, 'uploads/staff/1196_File_download 10.jpg', 'download 10.jpg', 'jpg', '0', 'pkorir59@gmail.com', '+1 (434) 901-5837', 1, 0, 4, '2022-04-21'),
(5, 'John Doe', 'John', '', 'Doe', 5, 8, 9, 23, 38, 2, 0, 0, 0, 'uploads/staff/1452_File_download.jpg', 'download.jpg', 'jpg', NULL, 'korirkipngetich@yahoo.com', '+1 (353) 294-5142', 1, 0, 4, '2022-04-21'),
(6, 'Millicent Okonjo', 'Millicent', 'Cherono', 'Okonjo', 3, 6, 10, 18, 0, 1, 0, 0, 0, 'uploads/staff/7829_File_milicent okonjo.jpg', 'milicent okonjo.jpg', 'jpg', NULL, 'kiplishi@gmail.com', '+1 (822) 298-7736', 1, 0, 4, '2022-04-21'),
(7, 'Nick Arap Nick', 'Nick', '', 'Arap Nick', 5, 7, 15, 19, 22, 2, 0, 0, 0, 'uploads/staff/7419_File_download 4.png', 'download 4.png', 'png', NULL, 'nicholaskoskey@gmail.com', '+1 (641) 903-5245', 1, 0, 4, '2022-04-21'),
(8, 'Group2 Nick 2', 'Group2', '', 'Nick 2', 5, 7, 15, 19, 40, 2, 0, 0, 0, 'uploads/staff/7882_File_download 5.png', 'download 5.png', 'png', NULL, 'neranickenterprises@gmail.com', '+1 (598) 789-1108', 1, 0, 4, '2022-04-21'),
(61, 'Orson Farmer', 'Orson', 'Callie Glenn', 'Farmer', 5, 2, 0, 0, 0, 1, 0, 0, 0, 'uploads/staff/7050_File_cover.jpg', '7050_File_cover.jpg', 'jpg', NULL, 'wovudy@mailinator.com', '+1 (616) 254-2865', 1, 0, 4, '2022-07-20'),
(62, 'Natalie Owen', 'Natalie', 'Meredith Gallagher', 'Owen', 7, 11, 9, 23, 38, 1, 0, 0, 0, 'uploads/staff/8509_File_cover.jpg', '8509_File_cover.jpg', 'jpg', NULL, 'bizanitib@mailinator.com', '+1 (217) 335-1717', 1, 0, 4, '2022-07-20'),
(63, 'Callum Rivera', 'Callum', 'Allistair Copeland', 'Rivera', 3, 4, 0, 0, 0, 3, 0, 0, 0, 'uploads/staff/7275_File_download 13.jpg', 'download 13.jpg', 'jpg', NULL, 'gasywip@mailinator.com', '+1 (869) 341-6354', 1, 0, 4, '2022-07-20'),
(64, 'Julius Rotich', 'Julius', '', 'Rotich', 5, 6, 15, 0, 0, 2, 0, 0, 0, 'uploads/staff/4891_File_Julius-Rotich-co-roads.jpeg', 'Julius-Rotich-co-roads.jpeg', 'jpeg', '0', 'mynahmc1@gmail.com', '0722114423', 0, 0, 4, '2022-07-20'),
(65, 'Peter Korir MnE', 'Peter', '', 'Korir MnE', 2, 7, 10, 18, 42, 1, 0, 0, 0, 'uploads/staff/2846_File_avator1.png', '2846_File_avator1.png', 'png', '0', 'kiplish@gmail.com', '0722114423', 1, 0, 4, '2022-07-20'),
(67, 'Procurement Officer', 'Procurement', '', 'Officer', 5, 7, 10, 17, 43, 1, 0, 0, 0, 'uploads/staff/2626_File_download 2.jpg', 'download 2.jpg', 'jpg', '6', 'p.korir@ombudsman.go.ke', '0727044818', 1, 0, 1, '2022-01-16'),
(68, 'Simon Kemei', 'Simon', '', 'Kemei', 5, 6, 9, 23, 0, 2, 0, 0, 0, 'uploads/staff/3690_File_Mr.-Simon-Kemei-Chief-Officer-Environment-Water-Energy-and-Natural-Resources.jpg', 'Mr.-Simon-Kemei-Chief-Officer-Environment-Water-Energy-and-Natural-Resources.jpg', 'jpg', NULL, 'projtrac1@gmail.com', '0722114471', 1, 0, 4, '2022-07-23'),
(69, 'Myles Ecorn', 'Myles', '', 'Ecorn', 4, 13, 5, 6, 48, 2, 0, 0, 0, 'uploads/staff/2742_File_linked icon.jpg', 'linked icon.jpg', 'jpg', NULL, 'ecornf@gmail.com', '0722114471', 1, 0, 4, '2022-07-23'),
(70, 'Kipngetich Kippe', 'Kipngetich', '', 'Kippe', 5, 13, 10, 18, 42, 1, 0, 0, 0, 'uploads/staff/5970_File_PETER.jpg', '5970_File_PETER.jpg', 'jpg', NULL, 'PEKIPP254@GMAIL.COM', '0720941928', 1, 0, 4, '2022-08-24'),
(71, 'Officer Group3', 'Officer', 'Officer', 'Group3', 5, 13, 15, 19, 40, 2, 0, 0, 0, 'uploads/staff/6624_File_user.png', '6624_File_user.png', 'png', NULL, 'neranickenterprise@gmail.com', '+1 (598) 789-1108', 1, 0, 4, '2022-04-21'),
(72, 'Stabex  Macharia', 'Stabex ', 'M', 'Macharia', 5, 7, 5, 6, 48, 2, 0, 0, 0, 'uploads/staff/5258_File_Screenshot (1).png', '5258_File_Screenshot (1).png', 'png', NULL, 'mynahmc@gmail.com', '829920', 1, 0, 4, '2022-11-03'),
(73, 'Jonathan Bii', 'Jonathan', 'Kimeli', 'Bii', 1, 2, 0, 0, 0, 3, 0, 0, 0, 'uploads/staff/4135_File_jonathan Bii.jpg', 'jonathan Bii.jpg', 'jpg', NULL, 'isaacharris749@gmail.com', '0727044818', 1, 0, 118, '2022-11-16'),
(74, 'John  Barorot', 'John ', 'Kibet', 'Barorot', 1, 3, 0, 0, 0, 3, 0, 0, 0, 'uploads/staff/4114_File_download 17.jpeg', 'download 17.jpeg', 'jpeg', NULL, 'mle88709@gmail.com', '0727044818', 1, 0, 118, '2022-11-16'),
(75, 'Peter Chesoss', 'Peter', 'Kipruto', 'Chesoss', 5, 6, 10, 17, 0, 2, 0, 0, 0, 'uploads/staff/5450_File_Peter-Kipruto-Chesoss-Chief-Officer-Finance.jpg', '5450_File_Peter-Kipruto-Chesoss-Chief-Officer-Finance.jpg', 'jpg', NULL, 'charlesfiverrwriter@gmail.com', '0727044818', 1, 0, 118, '2022-11-17'),
(76, 'Anthony Sitienei', 'Anthony', 'Cheruiyot', 'Sitienei', 5, 5, 13, 0, 0, 2, 0, 0, 0, 'uploads/staff/1469_File_Anthony Cheruiyot Sitienei.jpg', '1469_File_Anthony Cheruiyot Sitienei.jpg', 'jpg', NULL, 'denkytheka2@gmail.com', '0727044818', 1, 0, 118, '2022-11-17'),
(77, 'Micah Rogony', 'Micah', 'Kipkosgei', 'Rogony', 7, 6, 5, 6, 0, 2, 0, 0, 0, 'uploads/staff/1447_File_Micah Kipkosgei Rogony.jpg', '1447_File_Micah Kipkosgei Rogony.jpg', 'jpg', NULL, 'denkytheka3@gmail.com', '0727044818', 1, 0, 118, '2022-11-17'),
(78, 'Abraham Serem', 'Abraham', 'Kipkemboi', 'Serem', 3, 5, 9, 0, 0, 2, 0, 0, 0, 'uploads/staff/4119_File_Mr.-Simon-Kemei-Chief-Officer-Environment-Water-Energy-and-Natural-Resources.jpg', '4119_File_Mr.-Simon-Kemei-Chief-Officer-Environment-Water-Energy-and-Natural-Resources.jpg', 'jpg', NULL, 'denkytheka4@gmail.com', '0727044818', 1, 0, 118, '2022-11-17'),
(79, 'Joseph Lagat', 'Joseph', '', 'Lagat', 4, 5, 15, 0, 0, 2, 0, 0, 0, 'uploads/staff/1982_File_eng Joseph Lagat.jpg', '1982_File_eng Joseph Lagat.jpg', 'jpg', NULL, 'denkytheka@gmail.com', '0727044818', 1, 0, 118, '2022-11-17'),
(80, 'Isa  Galgalo', 'Isa ', '', 'Galgalo', 4, 13, 9, 23, 38, 2, 0, 0, 0, 'uploads/staff/6122_File_SamplePhoto_6.jpg', '6122_File_SamplePhoto_6.jpg', 'jpg', NULL, 'info@projtrac.co.ke', '0733467811', 1, 0, 118, '2023-03-01'),
(81, 'Kule Kulenyo', 'Kule', '', 'Kulenyo', 4, 7, 9, 23, 38, 2, 0, 0, 0, 'uploads/staff/6195_File_SamplePhoto_6.jpg', '6195_File_SamplePhoto_6.jpg', 'jpg', NULL, 'peter.korir@projtrac.co.ke', '+254726136397', 1, 0, 118, '2023-03-01');

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
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_qapr_report_conclusion`
--

INSERT INTO `tbl_qapr_report_conclusion` (`id`, `stid`, `year`, `quarter`, `section_comments`, `challenges`, `conclusion`, `appendices`, `created_by`, `created_at`, `updated_by`, `date_updated`) VALUES
(1, 15, 5, '2', '<p>testing&nbsp;</p>', '<p>testing&nbsp;</p>', '<p>tets</p>', '<p>test</p>', 118, '2023-10-02 10:42:09', NULL, NULL),
(2, 15, 5, '2', '<p>testing&nbsp;</p>', '<p>testing&nbsp;</p>', '<p>tets</p>', '<p>test</p>', 118, '2023-10-02 10:42:51', NULL, NULL),
(3, 15, 5, '2', '<p>testing&nbsp;</p>', '<p>testing&nbsp;</p>', '<p>tets</p>', '<p>test</p>', 118, '2023-10-02 10:43:37', NULL, NULL),
(4, 15, 5, '2', '<p>7777777</p>', '<p>testing&nbsp;</p>', '<p>tets</p>', '<p>test</p>', 118, '2023-10-02 10:43:49', NULL, NULL),
(5, 15, 4, '2', '<p>hhhh</p>', '<p>testtt</p>', '<p>yyyyuiu</p>', '<p>yyy</p>', 118, '2023-10-02 13:18:24', NULL, NULL),
(6, 0, 6, '1', '<p>Testing</p>', '<p>Tester</p>', '<p>ABC Tester</p>', '<p>XYZ Tester</p>', 118, '2023-10-02 14:09:36', NULL, NULL),
(7, 0, 5, '3', '<p>etetete</p>', '<p>tt</p>', '<p>ju</p>', '<p>hh</p>', 118, '2023-10-03 10:22:03', NULL, NULL);

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
(1, 1, 5, '2', 'test', 118, '2023-10-02 00:00:00'),
(2, 2, 5, '2', 'test', 118, '2023-10-02 00:00:00'),
(3, 8, 5, '2', 'final test', 118, '2023-10-02 00:00:00'),
(4, 1, 4, '2', 'ttt', 118, '2023-10-02 00:00:00'),
(5, 2, 4, '2', 'yy', 118, '2023-10-02 00:00:00'),
(6, 8, 4, '2', 'zz', 118, '2023-10-02 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_risk_impact`
--

CREATE TABLE `tbl_risk_impact` (
  `id` int NOT NULL,
  `digit` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_risk_impact`
--

INSERT INTO `tbl_risk_impact` (`id`, `digit`, `description`, `active`) VALUES
(1, 1, 'Negligible', 1),
(2, 2, 'Minor', 1),
(3, 3, 'Moderate', 1),
(4, 4, 'Major', 1),
(5, 5, 'Catastrophic', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_risk_probability`
--

CREATE TABLE `tbl_risk_probability` (
  `id` int NOT NULL,
  `digit` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_risk_probability`
--

INSERT INTO `tbl_risk_probability` (`id`, `digit`, `description`, `active`) VALUES
(1, 1, 'Rare', 1),
(2, 2, 'Unlikely', 1),
(3, 3, 'Possible', 1),
(4, 4, 'Likely', 1),
(5, 5, 'Very Likely', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_risk_severity`
--

CREATE TABLE `tbl_risk_severity` (
  `id` int NOT NULL,
  `digit` int NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `color` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_risk_severity`
--

INSERT INTO `tbl_risk_severity` (`id`, `digit`, `description`, `color`) VALUES
(1, 1, 'Low', 'bg-green'),
(2, 2, 'Low', 'bg-green'),
(3, 3, 'Low', 'bg-green'),
(4, 4, 'Moderate', 'bg-yellow'),
(5, 5, 'Moderate', 'bg-yellow'),
(6, 6, 'Moderate', 'bg-yellow'),
(7, 8, 'High', 'bg-orange'),
(8, 9, 'High', 'bg-orange'),
(9, 10, 'High', 'bg-orange'),
(10, 12, 'High', 'bg-orange'),
(11, 15, 'Extreme', 'bg-red'),
(12, 16, 'Extreme', 'bg-red'),
(13, 20, 'Extreme', 'bg-red'),
(14, 25, 'Extreme', 'bg-red');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_risk_strategy`
--

CREATE TABLE `tbl_risk_strategy` (
  `id` int NOT NULL,
  `strategy` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_risk_strategy`
--

INSERT INTO `tbl_risk_strategy` (`id`, `strategy`, `description`, `active`) VALUES
(1, 'Avoidance', 'It involves avoiding the risk by\r\nchanging the project plan (Investment plan) to\r\neliminate the risks or changing the project or\r\ninvestment to protect a project (Investment)\r\nobjective from the impact. For example one can\r\nreduce the scope of the project or investment to\r\nremove high risk tasks or by adding resources or\r\ntime or adopting a proven approach better than the\r\nnew one and hence removing problematic results. In\r\nour example of a medium size Fast Food\r\nRestaurant, avoidance can be used on competition\r\nrisk if the proprietor located the restaurant in part of\r\nNCBD where there are no chances for other new\r\ncompetitors to set their own.', 1),
(2, 'Acceptance', 'Accepting the consequences of the\r\nrisks by not changing the project or the\r\ninvestment plan or where better responses\r\ncannot be identified. Acceptance can take the\r\nfollowing forms:\r\na) Active\r\nb) Passive\r\nc) No action\r\nd) Contingency allowances\r\ne) Notify management that there could be a major\r\ncost increase if the risks occurs.\r\nIn our example of a medium size Fast Food Restaurant we\r\ncan accept that there is high chances of Fire accident or\r\nbreak out and then put in place contingencies to handle fire\r\nsuch as fire exit, fire extinguishers and fire detectors or\r\nalarms', 1),
(3, 'Mitigation(Reducing Impact)', 'The process of taking action to reduce the likelihood\r\nthat the risk will occur or taking action to reduce the\r\nprobability of risk which is always more effective than\r\nminimizing the consequences. This can be done through\r\nproper administrative measures such as training staff to\r\nknow how to handle fire when it breaks out or ensuring\r\nyou have police protection to avoid robberies.', 1),
(4, 'Transfer', 'Transferring the ownership of the risk\r\nfactor that will shift consequences of the risk\r\nand ownership of the responses to a third party.\r\nThis can be achieved through the following\r\nways.\r\na) Taking insurance policy\r\nb) Outsourcing difficult work to a more\r\nexperienced company.\r\nc) Arranging for fixed price contracts\r\nd) Using relevant warranties & guarantees\r\ne) Using financial risks exposures', 1),
(5, 'Monitor and Prepare', 'It is the process of\r\naccepting the risk for new but closely\r\nmonitoring the risk and proactively develop\r\naction plans if the event occurs. This can be\r\nachieved under the following measures:\r\na) Develop\r\nb) Have a fall back plan\r\nc) Establish criteria that will trigger the\r\nimplementation of the response plan\r\nIn our example, we can monitor new competition\r\nand plan on what to do next.', 1);

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
(1, 'issues', 1, 6);

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
(5, 0, 'Education, Culture, Youth Affairs, Sports and Social Services ', NULL, 2, '0', NULL, NULL),
(6, 5, 'Education, Culture, and Social Services ', NULL, 2, '0', NULL, NULL),
(7, 5, 'Youth Affairs, Gender and Sports Development  ', NULL, 2, '0', NULL, NULL),
(8, 5, 'Youth Affairs', NULL, 2, '1', NULL, NULL),
(9, 0, 'Water, Environment, Natural Resources, Tourism and Wildlife Management ', NULL, 2, '0', NULL, NULL),
(10, 0, 'Finance and Economic Planning', NULL, 1, '0', NULL, NULL),
(11, 0, 'Information and Communication Technology, Trade and Industrialization ', NULL, 2, '0', NULL, NULL),
(12, 0, 'Health Services ', NULL, 2, '0', NULL, NULL),
(13, 0, 'Land, Housing, Physical Planning & Urban Development', NULL, 2, '0', NULL, NULL),
(14, 0, 'Devolution, Administration, and Public Service Management', NULL, 2, '0', NULL, NULL),
(15, 0, 'Roads, Transport, Energy and Public Works', NULL, 2, '0', NULL, NULL),
(16, 0, 'Cooperatives and Enterprise Development ', NULL, 2, '0', NULL, NULL),
(17, 10, 'Finance ', NULL, 2, '0', NULL, NULL),
(18, 10, 'Economic Planning', NULL, 1, '0', NULL, NULL),
(19, 15, 'Roads, Transport, Energy and Public Works ', NULL, 2, '0', NULL, NULL),
(20, 19, 'Transport', NULL, 2, '1', NULL, NULL),
(21, 19, 'Energy ', NULL, 2, '1', NULL, NULL),
(22, 19, 'Public Works', NULL, 2, '0', NULL, NULL),
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
(35, 13, 'Municipality of Eldoret', NULL, 2, '0', NULL, NULL),
(36, 0, 'Procurement', NULL, 3, '0', NULL, NULL),
(37, 37, 'Agriculture 499', NULL, 2, '0', NULL, NULL),
(38, 23, 'Water  ', NULL, 2, '0', NULL, NULL),
(39, 25, '1', NULL, 1, '0', NULL, NULL),
(40, 19, 'Roads', NULL, 2, '0', NULL, NULL),
(41, 18, 'Planning ', NULL, 1, '0', NULL, NULL),
(42, 18, 'Monitoring and Evaluation', NULL, 1, '0', NULL, NULL),
(43, 17, 'Procurement', NULL, 1, '0', NULL, NULL),
(44, 17, 'Finance', NULL, 1, '0', NULL, NULL),
(45, 17, 'Accounts', NULL, 1, '0', NULL, NULL),
(46, 23, 'Environment and Natural Resources', NULL, 1, '0', NULL, NULL),
(47, 23, 'Tourism and Wildlife Management', NULL, 1, '0', NULL, NULL),
(48, 6, 'Education', NULL, 2, '0', NULL, NULL),
(49, 2, 'Agriculture', NULL, 2, '0', NULL, NULL);

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
  `sidebar_order` int NOT NULL DEFAULT '0',
  `Name` varchar(255) NOT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `page_icon` varchar(255) DEFAULT NULL,
  `icons` text NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `role_group` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `designation` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `ministry` varchar(255) NOT NULL,
  `sector` varchar(255) NOT NULL DEFAULT '0',
  `directorate` varchar(255) NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sidebar_menu`
--

INSERT INTO `tbl_sidebar_menu` (`id`, `parent`, `sidebar_order`, `Name`, `page_name`, `page_icon`, `icons`, `url`, `role_group`, `designation`, `ministry`, `sector`, `directorate`, `status`) VALUES
(1, 0, 1, 'Dashboards', NULL, NULL, '<i class=\"fa fa-dashboard\" style=\"color:white\"></i>', '', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(2, 0, 2, 'Plans', NULL, NULL, '<i class=\"fa fa-columns\" style=\"color:white\"></i>', NULL, '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(3, 0, 3, 'Programs', NULL, NULL, '<i class=\"fa fa-object-group\" style=\"color:white\"></i>', NULL, '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 0),
(4, 0, 4, 'Master Data', NULL, NULL, '<i class=\"fa fa-briefcase\" style=\"color:white\"></i>', NULL, '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(5, 0, 5, 'Project Management', NULL, NULL, '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', NULL, '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(6, 0, 6, 'M&E', NULL, NULL, '<i class=\"fa fa-newspaper-o\" style=\"color:white\"></i>', NULL, '1,2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(7, 0, 7, 'Payment', NULL, NULL, '<i class=\"fa fa-money\" style=\"color:white\"></i>', NULL, '1,2,4', '1,6,7', '', '0', '0', 1),
(8, 0, 8, 'Partners', NULL, NULL, '<i class=\"fa fa-slideshare\" style=\"color:white\"></i>', NULL, '1,4', '1,6,7', '10', '', '', 1),
(9, 0, 9, 'Issues & Risks', NULL, NULL, '<i class=\"fa fa-exclamation-circle\" style=\"color:white\"></i>', NULL, '1,2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 0),
(10, 0, 15, 'Indicators', NULL, NULL, '<i class=\"fa fa-microchip\" style=\"color:white\"></i>', NULL, '1,2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(11, 0, 11, 'Contractors', NULL, NULL, '<i class=\"fa fa-puzzle-piece\" style=\"color:white\"></i>', NULL, '1,4', '1,6,7', '10', '18', '43', 1),
(12, 0, 12, 'Personnel', NULL, NULL, '<i class=\"fa fa-users\" style=\"color:white\"></i>', NULL, '1,2,4', '1,6,7', '', '0', '0', 1),
(13, 0, 13, 'Files', NULL, NULL, '<i class=\"fa fa-folder-open-o\" style=\"color:white\"></i>', NULL, '2,4', '1,6,7', '', '0', '0', 1),
(14, 0, 14, 'Reports', NULL, NULL, '<i class=\"fa fa-file-text-o\" aria-hidden=\"true\" style=\"color:white\"></i>', NULL, '1,2,3,4\r\n', '1,2,3,4,5,6,7', '0', '0', '0', 1),
(15, 0, 16, 'Settings', NULL, NULL, '<i class=\"fa fa-cog fa-spin\" style=\"font-size:16px; color:red;\"></i>', NULL, '4', '1', '', '0', '0', 1),
(16, 1, 1, 'General Dashboard', NULL, '<i class=\"fa fa-dashboard\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '<i class=\"fa fa-dashboard\" aria-hidden=\"true\"></i>&nbsp;', 'dashboard', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(17, 1, 2, 'Financial Dashboard', NULL, NULL, '<i class=\"fa fa-dashboard\" aria-hidden=\"true\"></i>&nbsp;', 'view-financial-dashboard', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(18, 1, 4, 'Indicators GIS Dashboard', NULL, NULL, '<i class=\"fa fa-dashboard\" aria-hidden=\"true\"></i>&nbsp;', 'view-indicator-map-dashboard', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', 1),
(19, 1, 4, 'Projects GIS Dashboard', NULL, NULL, '<i class=\"fa fa-dashboard\" aria-hidden=\"true\"></i>&nbsp;', 'view-project-map-dashboard', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 0),
(20, 2, 1, 'Strategic Plans', NULL, NULL, '<i class=\"fa fa-bar-chart\" style=\"color:white\"></i>&nbsp;', 'view-strategic-plans', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(21, 2, 2, 'Annual Plans (ADPs)', NULL, NULL, '<i class=\"fa fa-arrows\" style=\"color:white\"></i>', 'view-adps', '2,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(22, 2, 3, 'Program Based Budgets', NULL, NULL, '<i class=\"fa fa-money\" style=\"color:white\"></i>', 'programs-adp', '1,2,4', '1,5,6,7,8,9,10,11,12,13,14', '0', '0', '0', 1),
(23, 2, 4, 'Independent Programs', NULL, NULL, '<i class=\"fa fa-list\" style=\"color:white\"></i>', 'all-programs', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(24, 0, 0, 'Add Independent Program', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-program', '2', '7,8,9,10,11,12,13', '', '0', '0', 1),
(25, 4, 6, 'Add Project Team', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-project-team', '2,4', '1,6', '0', '0', '0', 1),
(26, 4, 5, 'Add Mapping Details', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'project-mapping', '1,4', '1,6,7,8,9,10,11,12,13', '10', '18', '42', 1),
(27, 4, 1, 'Add Project Activities', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-project-activities', '2,4', '7,8,9,10,11,12,13,1', '', '0', '0', 1),
(28, 4, 2, 'Add Financial Plan', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-project-financial-plan', '2,4', '7,8,9,10,11,12,13,1', '', '', '', 1),
(29, 4, 3, 'Add Procurement Details', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-project-procurement-details', '1,4', '6,7,8,9,10,11,12,13,1', '10', '17', '43', 1),
(31, 4, 4, 'Add Project M&E Plan', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'view-mne-plan', '1,4', '6,7,8,9,10,11,12,13,1', '10', '18', '42', 1),
(32, 1, 3, 'Projects Dashboard', NULL, NULL, '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 'projects', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(33, 5, 1, 'My Projects', NULL, NULL, '<i class=\"fa fa-tasks\" style=\"color:white\"></i>', 'myprojects', '2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(34, 5, 2, 'Quality Assurance', NULL, NULL, '<i class=\"fa fa-file-text-o\" style=\"color:white\"></i>', 'general-project-progress', '2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(35, 6, 2, 'Output Monitoring', NULL, NULL, '<i class=\"fa fa-file-text-o\" style=\"color:white\"></i>', 'projects-monitoring', '1,4', '7,8,9,10,11,12,13,1', '10', '18', '42', 1),
(36, 6, 3, 'Outcome Evaluation', NULL, NULL, '<i class=\"fa fa-balance-scale\" style=\"color:white\"></i>', 'view-project-survey', '1,4', '7,8,9,10,11,12,13,1', '10', '18', '42', 1),
(37, 6, 4, 'Evaluation Reports', NULL, NULL, '<i class=\"fa fa-balance-scale\" style=\"color:white\"></i>', 'project-concluded-evaluations', '1,2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 0),
(38, 7, 1, 'Payments Request', NULL, NULL, '<i class=\"fa fa-upload\" style=\"color:white\"></i>', 'contractor-payment-requests', '2,4', '7,1', '', '0', '0', 1),
(39, 7, 2, 'Payments Approval', NULL, NULL, '<i class=\"fa fa-check-square-o\" style=\"color:white\"></i>', 'contractor-payment-approvals', '1,2,4', '6,1', '0', '0', '0', 1),
(40, 7, 3, 'Payment Disbursement', NULL, NULL, '<i class=\"fa fa-paper-plane-o\" style=\"color:white\"></i>', 'contractor-payment-disbursements', '1,4', '1,7', '10', '18', '44', 1),
(41, 8, 1, 'Financial Partners', NULL, NULL, '<i class=\"fa fa-handshake-o\" style=\"color:white\"></i>', 'view-financiers', '1,2,4', '6,7,1', '', '0', '0', 1),
(42, 9, 1, 'Projects Issues/Risks', NULL, NULL, '<i class=\"fa fa-exclamation-triangle\" style=\"color:white\"></i>', 'view-projects-risk-response', '1,2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 0),
(43, 5, 4, 'Escalated Issues', NULL, NULL, '<i class=\"fa fa-exclamation-triangle\" style=\"color:white\"></i>', 'projects-escalated-issues', '2,4', '6,7,1', '', '0', '0', 1),
(44, 5, 5, 'Risk Categories', NULL, NULL, '<i class=\"fa fa-exclamation-circle\" style=\"color:white\"></i>', 'view-risk-categories', '1,4', '7,8,9,10,11,12,13,1', '', '0', '0', 1),
(45, 5, 6, 'Risk Mitigations', NULL, NULL, '<i class=\"fa fa-exclamation-circle\" style=\"color:white\"></i>', 'view-risk-mitigation', '1,4', '7,8,9,10,11,12,13,1', '', '0', '0', 1),
(46, 10, 1, 'Indicators', NULL, NULL, '<i class=\"fa fa-microchip\" aria-hidden=\"true\"></i>', 'view-indicators', '1,2,4', '6,7,8,9,10,11,12,13,1', '0', '0', '0', 1),
(47, 10, 2, 'Measurement Units', NULL, NULL, '<i class=\"fa fa-barcode\" style=\"color:white\"></i>', 'view-measurement-units', '1,2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(48, 11, 1, 'Manage Contractors', NULL, NULL, '<i class=\"fa fa-university\" style=\"color:white\"></i>', 'view-contractors', '1,4', '6,7,1', '10', '18', '43', 1),
(49, 12, 1, 'View Personnel', NULL, NULL, '<i class=\"fa fa-users\" style=\"color:white\"></i>', 'view-members', '1,2,4', '6,7,1', '', '0', '0', 1),
(50, 12, 2, 'Add Personnel', NULL, NULL, '<i class=\"fa fa-users\" style=\"color:white\"></i>', 'add-member', '2,4', '6,7,1', '', '0', '0', 1),
(51, 13, 1, 'View All Files', NULL, NULL, '<i class=\"fa fa-files-o\" style=\"color:white\"></i>', 'view-all-files', '1,2,4', '6,7,1', '', '0', '0', 1),
(52, 14, 1, 'C-APR', NULL, NULL, '<i class=\"fa fa-columns\" style=\"color:white\"></i>&nbsp;', 'view-objective-performance', '1,2,3,4\r\n', '1,2,3,4,5,6,7', '', '0', '0', 1),
(53, 14, 2, 'Quarterly Progress Report', NULL, NULL, '<i class=\"fa fa-upload\" style=\"color:white\"></i>', 'output-indicators-quarterly-progress-report', '1,2,3,4\r\n', '1,2,3,4,5,6,7', '', '0', '0', 1),
(54, 14, 3, 'Projects Performance Report', NULL, NULL, '<i class=\"fa fa-upload\" style=\"color:white\"></i>', 'project-indicators-tracking-table', '1,2,3,4\r\n', '1,2,3,4,5,6,7', '', '0', '0', 1),
(55, 14, 4, 'Projects Implementation Report', NULL, NULL, '<i class=\"fa fa-upload\" style=\"color:white\"></i>', 'projects-implementation-report', '1,2,4', '1,2,3,4,5,6,7', '', '0', '0', 0),
(56, 14, 5, 'Indicators Performance', NULL, NULL, '<i class=\"fa fa-upload\" style=\"color:white\"></i>', 'output-indicators-tracking', '1,2,4', '1,2,3,4,5,6,7', '', '0', '0', 0),
(57, 14, 6, 'Financial Report', NULL, NULL, '<i class=\"fa fa-bar-chart \" style=\"color:white\"></i>', 'projfundingreport', '1,2,4', '1,2,3,4,5,6,7', '', '0', '0', 0),
(58, 14, 7, 'Funders Report', NULL, NULL, '<i class=\"fa fa-upload\" style=\"color:white\"></i>', 'projects-financiers-report', '1,2,4', '1,2,3,4,5,6,7', '', '0', '0', 0),
(59, 15, 1, 'Global Configuration', NULL, NULL, '<i class=\"fa fa-cogs\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'organization-details', '4', '1', '', '0', '0', 1),
(60, 15, 2, 'Add/View Sections', NULL, NULL, '<i class=\"fa fa-puzzle-piece\" aria-hidden=\"true\" style=\"color:white\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'view-sectors', '4', '1', '', '0', '0', 1),
(61, 15, 3, 'Add/View Locations', NULL, NULL, '<i class=\"fa fa-map-marker\" aria-hidden=\"true\" style=\"color:white\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'locations', '4', '1', '', '0', '0', 1),
(64, 16, 0, 'Dashboard Projects', NULL, NULL, '•&nbsp;', 'view-dashboard-projects', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(65, 49, 0, 'View Personnel Info', NULL, NULL, '<i class=\"fa fa-user\" aria-hidden=\"true\"></i>', 'view-member-info', '1,2,4', '6,7,1', '', '0', '0', 1),
(66, 48, 0, 'Manage Contractors Info', NULL, NULL, '•&nbsp;', 'view-contractor-info', '1,2,4', '6,7,1', '10', '18', '43', 1),
(67, 48, 0, 'Add Contractors ', NULL, NULL, '<i class=\"fa fa-puzzle-piece\" style=\"color:white\"></i>', 'add-contractor', '1,4', '7,1', '10', '18', '43', 1),
(68, 46, 0, 'Add Indicators', NULL, NULL, '<i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i>', 'add-output-indicator', '1,4', '1,7', '', '0', '0', 1),
(69, 46, 0, 'Edit Indicators', NULL, NULL, '<i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i>', 'edit-output-indicator', '1,4', '1,7', '', '0', '0', 1),
(70, 46, 0, 'Add Outcome Indicator', NULL, NULL, '<i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i>', 'add-outcome-indicator', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(71, 46, 0, 'Edit Indicators', NULL, NULL, '<i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i>', 'edit-outcome-indicator', '1,4', '1,7', '', '0', '0', 1),
(72, 46, 0, 'Indicator Baseline', NULL, NULL, '•&nbsp;', 'indicator-existing-baseline-data', '1,2,4', '7,8,9,10,11,12,13,1', '', '0', '0', 1),
(73, 44, 0, 'Risk Categories', NULL, NULL, '<i class=\"fa fa-exclamation-circle\" style=\"color:white\"></i>', 'add-risk-category', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(74, 45, 0, 'Risk Mitigations', NULL, NULL, '<i class=\"fa fa-exclamation-circle\" style=\"color:white\"></i>', 'add-risk-mitigation', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(75, 45, 0, 'Risk Mitigations', NULL, NULL, '•&nbsp;', 'edit-risk-mitigation', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(76, 36, 0, 'Projects Outcome Evaluation', NULL, NULL, '<i class=\"fa fa-balance-scale\" style=\"color:white\"></i>', 'evaluation-secondary-data-source', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(77, 41, 0, 'Financial Partners', NULL, NULL, '<i class=\"fa fa-money\" style=\"color:white\"></i>', 'view-funding', '1,2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(78, 41, 0, 'Financial Partners', NULL, NULL, '•&nbsp;', 'view-financier-info', '1,2,4', '6,7,1', '', '0', '0', 1),
(79, 41, 0, 'Financial Partners', NULL, NULL, '•&nbsp;', 'view-financier-funds', '1,2,4', '6,7,1', '', '0', '0', 1),
(80, 41, 0, 'Financial Partners', NULL, NULL, '•&nbsp;', 'view-financier-status', '1,4', '7,1', '', '0', '0', 1),
(81, 41, 0, 'Financier Projects', NULL, NULL, '•&nbsp;', 'view-financier-projects', '1,2,4', '6,7,1', '', '0', '0', 1),
(82, 41, 0, 'Financier Projects', NULL, NULL, '<i class=\"fa fa-handshake-o\" style=\"color:white\"></i>', 'add-financier', '1,4', '7,1', '10', '18', '41', 1),
(83, 41, 0, 'Financier Projects', NULL, NULL, '•&nbsp;', 'edit-financier', '1,2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(84, 41, 0, 'Add Funds', NULL, NULL, '<i class=\"fa fa-money\" aria-hidden=\"true\"></i>', 'add-development-funds', '1,2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(85, 32, 0, 'All Projects', NULL, NULL, '•&nbsp;', 'view-project-maps', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(86, 35, 0, 'Monitoring', NULL, NULL, '<i class=\"fa fa-list-ol\" aria-hidden=\"true\"></i>', 'add-monitoring-data', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '10', '18', '42', 1),
(87, 20, 0, 'Add Strategic Plan', NULL, NULL, '•&nbsp;', 'add-strategic-plan', '1,4', '1,7,8,9,10,11,12,13', '', '0', '0', 1),
(88, 20, 0, 'Edit Strategic Plan', NULL, NULL, '•&nbsp;', 'edit-strategic-plan', '1,4', '1,7,8,9,10,11,12,13', '', '0', '0', 1),
(89, 20, 0, 'Framework', NULL, NULL, '<i class=\"fa fa-columns\" style=\"color:white\"></i>&nbsp;', 'view-strategic-plan-framework', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(90, 20, 0, 'Strategic Objectives', NULL, NULL, '<i class=\"fa fa-columns\" style=\"color:white\"></i>&nbsp;', 'view-strategic-plan-objectives', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(91, 20, 0, 'Key Result Areas', NULL, NULL, '<i class=\"fa fa-columns\" style=\"color:white\"></i>&nbsp;', 'view-kra', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(92, 20, 0, 'Plan', NULL, NULL, '•&nbsp;', 'view-strategic-workplan-budget', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(93, 20, 0, 'SP Programs', NULL, NULL, '<i class=\"fa fa-columns\" style=\"color:white\"></i>&nbsp;', 'view-program', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(94, 20, 0, 'SP Projects', NULL, NULL, '<i class=\"fa fa-columns\" style=\"color:white\"></i>&nbsp;', 'strategic-plan-projects', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(95, 20, 0, 'Strategic Plan Implementation Matrix', NULL, NULL, '•&nbsp;', 'strategic-plan-implementation-matrix', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(96, 38, 0, 'Inhouse Payments Request', NULL, NULL, '<i class=\"fa fa-upload\" style=\"color:white\"></i>', 'inhouse-payment-requests', '2,4', '1,7', '', '0', '0', 1),
(97, 38, 0, 'Payment Certificates', NULL, NULL, '<i class=\"fa fa-certificate\" style=\"color:white\"></i>', 'certificateofcompletion', '2,4', '6,1', '', '0', '0', 1),
(98, 34, 0, 'Inspection', NULL, NULL, '•&nbsp;', 'project-inspection-report', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(99, 32, 0, 'My Projects', NULL, NULL, '•&nbsp;', 'view-project-gallery', '1,2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(100, 33, 0, 'My Projects', NULL, NULL, '•&nbsp;', 'myprojectdash', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(101, 33, 0, 'My Projects', NULL, NULL, '•&nbsp;', 'myprojectmilestones', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(102, 33, 0, 'My Projects', NULL, NULL, '•&nbsp;', 'myprojectworkplan', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(103, 33, 0, 'My Projects', NULL, NULL, '•&nbsp;', 'myprojectfinancialplan', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(104, 33, 0, 'My Projects', NULL, NULL, '•&nbsp;', 'myproject-key-stakeholders', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(105, 33, 0, 'Project Issues Log', NULL, NULL, '<i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>', 'projectissueslist', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(106, 33, 0, 'Project Files', NULL, NULL, '•&nbsp;', 'myprojectfiles', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(107, 33, 0, 'My Projects', NULL, NULL, '•&nbsp;', 'projreports', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(108, 33, 0, 'My Projects', NULL, NULL, '•&nbsp;', 'projectissuesanalysis', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(109, 33, 0, 'Project Issues Analysis', NULL, NULL, '<i class=\"fa fa-bar-chart\" aria-hidden=\"true\"></i>&nbsp;', 'project-issue-discussion', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(110, 20, 0, 'Strategic Objective Programs', NULL, NULL, '<i class=\"fa fa-columns\" style=\"color:white\"></i>&nbsp;', 'view-strategicplan-programs', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(111, 20, 0, 'Plan', NULL, NULL, '•&nbsp;', 'view-projects', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(112, 20, 0, 'Projects', NULL, NULL, '•&nbsp;', 'view-project', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(113, 61, 0, 'Add/View Locations', NULL, NULL, '<i class=\"fa fa-map-marker\" aria-hidden=\"true\" style=\"color:white\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'edit-locations', '4', '1', '', '0', '0', 1),
(114, 59, 0, 'Global Configuration', NULL, NULL, '<i class=\"fa fa-cogs\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'global-configuration', '4', '1', '', '0', '0', 1),
(208, 53, 0, 'Quarterly Progress Report', NULL, NULL, '•&nbsp;', 'view-qpr-report', '1,4', '1,2,3,4,5,6,7', '', '0', '0', 1),
(115, 36, 0, 'Add Project', NULL, NULL, '•&nbsp;', 'add-project', '2', '7,8,9,10,11,12,13', '', '0', '0', 1),
(116, 49, 0, 'View Personnel Info', NULL, NULL, '•&nbsp;', 'view-member-projects', '2,4', '6,7,1', '', '0', '0', 1),
(117, 36, 0, 'Projects Evaluation', NULL, NULL, '•&nbsp;', 'view-survey-data', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(118, 36, 0, 'Projects Evaluation', NULL, NULL, '<i class=\"fa fa-list-alt\" style=\"color:white\"></i>', 'survey-conclusion', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(119, 32, 0, 'All Projects', NULL, NULL, '•&nbsp;', 'project-stats', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(209, 52, 0, 'Yearly Plan Report', NULL, NULL, '•&nbsp;', 'view-yearly-objective-performance', '1,2,3,4', '1,2,3,4,5,6,7', '', '0', '0', 1),
(201, 20, 0, 'Plan', NULL, NULL, '•&nbsp;', 'add-objective', '1,4', '1,7,8,9,10,11,12,13', '', '0', '0', 1),
(202, 20, 0, 'Plan', NULL, NULL, '•&nbsp;', 'edit-objective', '1,4', '1,7,8,9,10,11,12,13', '', '0', '0', 1),
(203, 31, 0, 'Add Project M&E Plan', NULL, NULL, '•&nbsp;', 'edit-monitoring-evaluation-plan', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '10', '18', '42', 1),
(204, 31, 0, 'Add Project M&E Plan', NULL, NULL, '•&nbsp;', 'add-monitoring-checklist', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '10', '18', '42', 1),
(205, 37, 0, 'Projects Evaluation', NULL, NULL, '•&nbsp;', 'primary-data-evaluation-report', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(206, 36, 0, 'Edit Project', NULL, NULL, '•&nbsp;', 'edit-project', '2,4', '1,7,8,9,10,11,12,13', '', '0', '0', 1),
(207, 23, 0, 'Edit Program', NULL, NULL, '•&nbsp;', 'edit-program', '2', '7,8,9,10,11,12,13', '', '0', '0', 1),
(210, NULL, 0, 'Profile', NULL, NULL, '<i class=\"fa fa-user\" style=\"color:white\"></i>', 'profile', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(211, NULL, 0, 'Mapping Project Output', 'Mapping Project Output', NULL, '<i class=\"fa fa-user\" style=\"color:white\"></i>', 'add-map-data-automatically', '1,4', '1,7,8,9,10,11,12,13,14', '10', '18', '42', 1),
(212, NULL, 0, 'Mapping Project Output', 'Mapping Project Output', NULL, '<i class=\"fa fa-user\" style=\"color:white\"></i>', 'add-map-data-manual', '1,4', '1,7,8,9,10,11,12,13,14', '10', '18', '42', 1),
(213, 28, 2, 'Add Financial Plan', NULL, NULL, '•&nbsp;', 'add-financial-plan', '2,4', '6,7,8,9,10,11,12,13,1', '', '', '', 1),
(214, 29, 0, 'Add Procurement Details', NULL, NULL, '•&nbsp;', 'add-procurement-details', '1,4', '1,7,8,9,10,11,12,13', '10', '17', '43', 1),
(237, 236, 3, 'Leave Request Approval', NULL, NULL, '<i class=\"fa fa-check\" style=\"color:white\"></i>', 'leave-request-approval', '1,2,4', '6,1', '', '0', '0', 1),
(216, 31, 0, 'Add Project M&E Plan', NULL, NULL, '•&nbsp;', 'add-project-mne-plan', '1,4', '6,7,8,9,10,11,12,13,1', '10', '18', '42', 1),
(217, 36, 0, 'Projects Evaluation', NULL, NULL, '•&nbsp;', 'create-project-survey-form', '1,4', '7,8,9,10,11,12,13,1', '10', '18', '42', 1),
(218, 36, 0, 'Projects Evaluation', NULL, NULL, '•&nbsp;', 'preview-project-survey-form', '1,4', '7,8,9,10,11,12,13,1', '10', '18', '42', 1),
(219, 36, 0, 'Projects Evaluation', NULL, NULL, '•&nbsp;', 'deploy-survey-form', '1,4', '7,8,9,10,11,12,13,1', '10', '18', '42', 1),
(225, 42, 0, 'Escalated Issues', NULL, NULL, '<i class=\"fa fa-exclamation-triangle\" style=\"color:white\"></i>', 'project-escalated-issue-assessment', '2,4', '6,7,1', '', '0', '0', 1),
(220, 59, 0, 'System Terminologies', 'System Terminologies', NULL, '<i class=\"fa fa-cogs\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'system-terminologies', '4', '1', '', '0', '0', 1),
(221, 59, 0, 'Email Configuration', 'Email Configuration', NULL, '<i class=\"fa fa-cogs\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'email_configuration', '4', '1', '', '0', '0', 1),
(222, 59, 0, 'Email Templates', 'Email Templates', NULL, '<i class=\"fa fa-cogs\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'email_templates', '4', '1', '', '0', '0', 1),
(223, 59, 0, 'Email Template', 'Email Template', NULL, '<i class=\"fa fa-cogs\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'email_template', '4', '1', '', '0', '0', 1),
(224, 33, 0, 'Project Contractor Info', NULL, NULL, '•&nbsp;', 'view-project-contractor-info', '1,2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(236, 12, 3, 'Leave Requests', NULL, NULL, '<i class=\"fa fa-sign-out\" style=\"color:white\"></i>', 'leave-requests', '1,2,4', '6,1', '', '0', '0', 1),
(226, 5, 8, 'Projects Team', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'view-project-team', '2,4', '1,6', '0', '0', '0', 1),
(227, 34, 3, 'Inspection', NULL, NULL, '<i class=\"fa fa-file-text-o\" style=\"color:white\"></i>', 'project-inspection', '2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(233, 51, 1, 'View Project Files', NULL, NULL, '<i class=\"fa fa-files-o\" style=\"color:white\"></i>', 'view-project-files', '1,2,4', '6,7,1', '', '0', '0', 1),
(228, 33, 0, 'Project Escalated Issues', NULL, NULL, '•&nbsp;', 'project-escalated-issues', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(229, 34, 34, 'Inspection', NULL, NULL, '•&nbsp;', 'project-implementation-general-comments', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(230, 117, 0, 'Projects Evaluation Narration Question Data', NULL, NULL, '•&nbsp;', 'survey-narration-question-data', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(231, 78, 0, 'Financial Partner Status', NULL, NULL, '•&nbsp;', 'financier-status', '1,2,4', '6,7,1', '', '0', '0', 1),
(232, 34, 34, 'Inspection', NULL, NULL, '•&nbsp;', 'view-task-inspection-report', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(234, 38, 2, '            Payment Disbursement\r\n', NULL, NULL, '            <i class=\"fa fa-paper-plane-o\" style=\"color:white\"></i>\r\n', 'inhouse-payment-disbursements', '1,2,4', '6,1', '0', '0', '0', 1),
(235, 38, 3, 'Payments Approval', NULL, NULL, '            <i class=\"fa fa-check-square-o\" style=\"color:white\"></i>\r\n', 'inhouse-payment-approvals', '1,4', '1,7', '10', '18', '44', 1),
(238, 27, 1, 'Add Project Activities', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-activities', '2,4', '7,8,9,10,11,12,13,1', '', '0', '0', 1),
(239, 27, 1, 'Add Project Output Design', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-project-output-designs', '2,4', '7,8,9,10,11,12,13,1', '', '0', '0', 1),
(240, 29, 0, 'Add Procurement Plan', NULL, NULL, '•&nbsp;', 'add-procurement-plan', '1,4', '1,7,8,9,10,11,12,13', '10', '17', '43', 1),
(241, 25, 5, 'Add Project Team', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-team', '2,4', '1,6', '0', '0', '0', 1),
(242, 4, 10, 'Add Program of Works', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-program-of-works', '2,4', '1,6', '0', '0', '0', 1),
(243, 25, 5, 'Add Project Team', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-design-team', '2,4', '1,6', '0', '0', '0', 1),
(244, 242, 6, 'Add Program of Works', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-work-program', '2,4', '1,6', '0', '0', '0', 1),
(245, 32, 0, 'Project Dashboard', NULL, NULL, '<i class=\"fa fa-tachometer\" aria-hidden=\"true\"></i>', 'project-dashboard', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(246, 32, 0, 'Project Activities', NULL, NULL, '<i class=\"fa fa-list\" aria-hidden=\"true\"></i>', 'project-activities', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(247, 32, 0, 'Project Indicators', NULL, NULL, '<i class=\"fa fa-microchip\" aria-hidden=\"true\"></i>', 'project-indicators', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(248, 32, 0, 'Project GIS Map Dashboard', NULL, NULL, '<i class=\"fa fa-map-o\" aria-hidden=\"true\"></i>', 'project-map', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(249, 32, 0, 'Project Gallery', NULL, NULL, '<i class=\"fa fa-picture-o\" aria-hidden=\"true\"></i>', 'project-gallery', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(250, 238, 1, 'Add Output Design Specifications', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-specifications', '2,4', '7,8,9,10,11,12,13,1', '', '0', '0', 1),
(251, 227, 3, 'Inspection', NULL, NULL, '<i class=\"fa fa-file-text-o\" style=\"color:white\"></i>', 'inspect-task', '2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(252, 46, 0, 'Add Impact Indicator', NULL, NULL, '<i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i>', 'add-impact-indicator', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(253, 37, 0, 'Projects Evaluation Report', NULL, NULL, '•&nbsp;', 'secondary-data-evaluation-report', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(254, 37, 0, 'Projects Evaluation Report', NULL, NULL, '•&nbsp;', 'primary-data-evaluation-report', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(255, 46, 0, 'Edit Impact Indicator', NULL, NULL, '<i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i>', 'edit-impact-indicator', '1,4', '1,7', '', '0', '0', 1),
(256, 36, 9, 'Add Project Output', NULL, NULL, '•&nbsp;', 'add-project-outputs', '2', '7,8,9,10,11,12,13', '', '0', '0', 1),
(266, 5, 3, 'Issues Log', NULL, NULL, '<i class=\"fa fa-exclamation-triangle\" style=\"color:white\"></i>', 'projects-issues', '2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(258, 4, 4, 'Add Monitoring Checklists', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-project-monitoring-checklist', '2,4', '7,8,9,10,11,12,13,1', '0', '0', '0', 1),
(259, 258, 7, 'Add Project Ourput Design Monitoring Checklist', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-project-monitoring-design-checklist', '2,4', '7,8,9,10,11,12,13,1', '0', '0', '0', 1),
(260, 258, 7, 'Add Project Ourput Design Monitoring Checklist', NULL, NULL, '<i class=\"fa fa-database\" style=\"color:white\"></i>', 'add-project-design-checklist', '2,4', '7,8,9,10,11,12,13,1', '0', '0', '0', 1),
(261, 34, 3, 'Monitoring', NULL, NULL, '<i class=\"fa fa-file-text-o\" style=\"color:white\"></i>', 'project-monitoring', '2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(264, 227, 3, 'Inspection', NULL, NULL, '<i class=\"fa fa-file-text-o\" style=\"color:white\"></i>', 'specification-inspection', '2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(263, 6, 1, 'Activities Monitoring', NULL, NULL, '<i class=\"fa fa-file-text-o\" style=\"color:white\"></i>', 'project-output-monitoring-checklist', '2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(265, 0, 15, 'Quality Standards', 'Project Standards Categories', NULL, '<i class=\"fa fa-certificate\" style=\"color:white\"></i>', NULL, '2,4', '6,7,8,9,10,11,12,13,1', '0', '0', '0', 1),
(267, 33, 0, 'Project Issues', NULL, NULL, '<i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>', 'my-project-issues', '2,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(268, 6, 3, 'Impact Evaluation', NULL, NULL, '<i class=\"fa fa-balance-scale\" style=\"color:white\"></i>', 'view-project-impact-evaluation', '1,4', '7,8,9,10,11,12,13,1', '10', '18', '42', 1),
(269, 265, 1, 'View Standards', 'Project Standards Categories', NULL, '<i class=\"fa fa-certificate\"></i>&nbsp;', 'view-standards-categories', '2,4', '6,7,8,9,10,11,12,13,1', '0', '0', '0', 1),
(270, 36, 0, 'Projects Impact Evaluation', NULL, NULL, '<i class=\"fa fa-balance-scale\" style=\"color:white\"></i>', 'impact-evaluation-secondary-data-source', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(271, 37, 0, 'Project Outcome Evaluation', NULL, NULL, '<i class=\"fa fa-list-alt\" style=\"color:white\"></i>', 'secondary-data-evaluation', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(272, 6, 4, 'M&E Reports', NULL, NULL, '<i class=\"fa fa-balance-scale\" style=\"color:white\"></i>', 'mne-reports', '1,2,4', '6,7,8,9,10,11,12,13,1', '', '0', '0', 1),
(273, 36, 0, 'Testing', NULL, NULL, '•&nbsp;', 'testing', '1,4', '7,8,9,10,11,12,13,1', '10', '18', '42', 1),
(274, 272, 0, 'Project M&E Report', NULL, NULL, '<i class=\"fa fa-book\" aria-hidden=\"true\"></i>', 'project-mne-report', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1),
(275, 59, 0, 'Permissions', 'Permission', NULL, '<i class=\"fa fa-cogs\"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'view-permission', '4', '1', '', '0', '0', 1),
(276, 272, 0, 'Project M&E Disaggregation Report', NULL, NULL, '<i class=\"fa fa-book\" aria-hidden=\"true\"></i>', 'project-mne-disaggregation-report', '1,4', '1,5,6,7,8,9,10,11,12,13,14', '', '0', '0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stage_timelines_categories`
--

CREATE TABLE `tbl_stage_timelines_categories` (
  `id` int NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_by` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_standards`
--

CREATE TABLE `tbl_standards` (
  `standard_id` int NOT NULL,
  `category_id` int NOT NULL,
  `code` varchar(255) NOT NULL,
  `standard` text NOT NULL,
  `description` text NOT NULL,
  `department_id` int NOT NULL,
  `section_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_standards`
--

INSERT INTO `tbl_standards` (`standard_id`, `category_id`, `code`, `standard`, `description`, `department_id`, `section_id`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 4, 'RW001', 'Std Excavation ', 'Description of this is here', 0, 0, 1, 118, NULL, '2023-03-15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_standard_categories`
--

CREATE TABLE `tbl_standard_categories` (
  `category_id` int NOT NULL,
  `category` varchar(255) NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_standard_categories`
--

INSERT INTO `tbl_standard_categories` (`category_id`, `category`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Mechanical Egineering', 1, 1, 118, '2023-02-14', '2023-03-03'),
(2, 'Civil Engineering', 1, 1, 118, '2023-02-25', '2023-03-03'),
(3, 'Software Engineering', 0, 1, 118, '2023-02-25', '2023-03-03'),
(4, 'Road Construction Works ', 1, 118, NULL, '2023-03-15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_state`
--

CREATE TABLE `tbl_state` (
  `id` int NOT NULL,
  `parent` int DEFAULT NULL,
  `state` varchar(100) NOT NULL,
  `level` int NOT NULL DEFAULT '0',
  `active` int NOT NULL DEFAULT '1',
  `changed_by` varchar(100) DEFAULT NULL,
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_state`
--

INSERT INTO `tbl_state` (`id`, `parent`, `state`, `level`, `active`, `changed_by`, `date_changed`) VALUES
(1, NULL, 'Headquarters', 1, 1, NULL, NULL),
(2, 1, 'Headquarters', 1, 1, NULL, NULL),
(3, 2, 'Headquarters', 1, 1, NULL, NULL),
(303, NULL, 'Soy', 0, 1, NULL, NULL),
(304, NULL, 'Moiben', 0, 1, NULL, NULL),
(305, NULL, 'Turbo', 0, 1, NULL, NULL),
(306, NULL, 'Ainabkoi', 0, 1, '118', '2023-01-27 12:56:31'),
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
  `class_name` varchar(255) NOT NULL,
  `level` int NOT NULL DEFAULT '1',
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_status`
--

INSERT INTO `tbl_status` (`statusid`, `statusname`, `class_name`, `level`, `active`) VALUES
(1, 'Awaiting-Procurement', 'btn bg-grey waves-effect', 1, 1),
(2, 'Cancelled', 'btn bg-brown waves-effect', 1, 1),
(3, 'Pending', 'btn bg-yellow waves-effect', 1, 1),
(4, 'On Track', 'btn btn-primary waves-effect', 1, 1),
(5, 'Completed', 'btn btn-success waves-effect', 1, 1),
(6, 'On Hold', 'btn bg-pink waves-effect', 1, 1),
(7, 'Unapproved', 'btn bg-yellow waves-effect', 2, 0),
(8, 'Awaiting Approval', 'btn bg-yellow waves-effect', 2, 1),
(9, 'Overdue', 'btn bg-yellow waves-effect', 1, 0),
(10, 'Restored', 'btn bg-yellow waves-effect', 2, 1),
(11, 'Behind Schedule', 'btn bg-red waves-effect', 1, 1),
(13, 'Planned', 'btn bg-grey waves-effect', 1, 1);

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
(1, 'UASINGISHU COUNTY INTEGRATED DEVELOPMENT PLAN (CIDP) 2020-2024', 'A prosperous and attractive County in Kenya and beyond', 'To provide high quality of life to the residents through good governance, innovation, inclusive growth and sustainable development ', 5, 2020, 1, '118', '2023-03-14', NULL, NULL),
(2, 'Aliqua Est ab eos ', 'Sit et error impedit', 'Aut suscipit omnis c', 5, 2026, 0, '1', '2023-04-10', NULL, NULL),
(3, 'KILIFI COUNTY INTEGRATED DEVELOPMENT PLAN (CIDP) 2020-2024', 'vision', 'mission', 5, 2032, 0, '118', '2023-08-12', NULL, NULL);

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
(1, 7, 'To attain food security and improve nutritional status of residents of UasinGishu County', '', NULL, 1, NULL, NULL, '118', '2023-03-14'),
(2, 8, ' To reduce poverty and increase incomes amongst residents of Uasin Gishu County', '', NULL, 1, NULL, NULL, '118', '2023-03-14'),
(3, 10, 'To improve health and well-being of residents of Uasin Gishu County', '', NULL, 1, NULL, NULL, '118', '2023-03-14'),
(6, 11, 'To establish a sustainable, secure, compliant and reliable infrastructure in Uasin Gishu County', '<p>Investment in reliable road infrastructure</p>', NULL, 1, NULL, NULL, '118', '2023-03-18'),
(9, 9, 'To provide quality education that is accessible, affordable and responsive to societal needs. ', '', NULL, 1, NULL, NULL, '118', '2023-03-14'),
(10, 12, ' To improve access to clean and portable water, and attain sustainable environment through protection, restoration, conservation and management of the environment', '', NULL, 1, NULL, NULL, '118', '2023-03-14'),
(11, 14, 'improve immunization coverage', '<p>improve immunization coverage of kilifi county</p>', NULL, 1, NULL, NULL, '118', '2023-08-12');

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_survey_conclusion`
--

CREATE TABLE `tbl_survey_conclusion` (
  `id` int NOT NULL,
  `formkey` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `projid` int NOT NULL,
  `indid` int NOT NULL,
  `resultstype` int NOT NULL,
  `resultstypeid` int NOT NULL,
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

INSERT INTO `tbl_survey_conclusion` (`id`, `formkey`, `projid`, `indid`, `resultstype`, `resultstypeid`, `survey_type`, `level3`, `disaggregation`, `variable_category`, `numerator`, `denominator`, `comments`, `created_by`, `date_created`) VALUES
(11, '372049', 7, 20, 1, 2, 'Baseline', 355, NULL, 1, 100000, NULL, '', 118, '2023-03-18'),
(12, '465902', 109, 31, 2, 26, 'Baseline', 321, NULL, 2, 1, 5, '<p>Test</p>', 118, '2023-10-03'),
(13, '465902', 109, 31, 2, 26, 'Baseline', 331, NULL, 2, 1, 5, '<p>Test</p>', 118, '2023-10-03'),
(14, '279534', 109, 32, 1, 11, 'Baseline', 321, NULL, 2, 1, 3, '<p>Testing</p>', 118, '2023-10-04'),
(15, '279534', 109, 32, 1, 11, 'Baseline', 331, NULL, 2, 2, 3, '<p>Testing</p>', 118, '2023-10-04');

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
  `unit_of_measure` int NOT NULL,
  `parenttask` int DEFAULT NULL,
  `task` varchar(300) NOT NULL,
  `sdate` date DEFAULT NULL,
  `edate` date DEFAULT NULL,
  `taskbudget` double NOT NULL DEFAULT '0',
  `progress` decimal(10,2) NOT NULL DEFAULT '0.00',
  `inspectionscore` float NOT NULL DEFAULT '0',
  `inspectionstatus` int NOT NULL DEFAULT '0',
  `description` text,
  `status` int NOT NULL DEFAULT '0',
  `paymentstatus` int NOT NULL DEFAULT '0',
  `changedstatus` varchar(100) DEFAULT NULL,
  `monitored` enum('0','1') NOT NULL DEFAULT '0',
  `responsible` int DEFAULT NULL,
  `datecompleted` date DEFAULT NULL,
  `user_name` varchar(200) NOT NULL,
  `date_entered` date NOT NULL,
  `changedby` varchar(100) DEFAULT NULL,
  `datechanged` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_task`
--

INSERT INTO `tbl_task` (`tkid`, `msid`, `projid`, `outputid`, `unit_of_measure`, `parenttask`, `task`, `sdate`, `edate`, `taskbudget`, `progress`, `inspectionscore`, `inspectionstatus`, `description`, `status`, `paymentstatus`, `changedstatus`, `monitored`, `responsible`, `datecompleted`, `user_name`, `date_entered`, `changedby`, `datechanged`) VALUES
(1, 1, 1, 1, 0, 0, 'Excavation and backfilling ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-15', NULL, NULL),
(2, 2, 1, 1, 0, 0, 'Construction Works', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-15', NULL, NULL),
(3, 3, 1, 2, 0, 0, 'Excavation ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-15', NULL, NULL),
(4, 4, 1, 2, 0, 0, 'Construction Works', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-15', NULL, NULL),
(5, 5, 1, 3, 0, 0, 'Excavation ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-15', NULL, NULL),
(6, 6, 1, 3, 0, 0, 'Planking and Strutting', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-15', NULL, NULL),
(7, 7, 3, 5, 0, 0, 'Excavation in topsoil for foundations, depth not exceeding 0.25m', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-15', NULL, NULL),
(8, 8, 3, 5, 0, 0, 'Masonary joint reinforcement with 20X3mm thick hoop iron in every alternate course in every course.', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-15', NULL, NULL),
(9, 2, 1, 1, 0, 0, 'Roadworks', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-15', NULL, NULL),
(10, 15, 2, 4, 0, 0, 'Conduct a hydrological site survey using an established hydrologist to establish the best point to drill a borehole within the plot', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-15', NULL, NULL),
(11, 16, 2, 4, 0, 0, 'The drilling of borehole of sufficient diameter to provide for a finished cased and screened borehole of 200mm diameter to the provisional depth of about 350metres.', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-15', NULL, NULL),
(12, 17, 7, 10, 0, 0, 'Excavate in top soil not exceeding 1.2m deep and 0.8m wide to receive 160mm PN 10 HDPE Pipes to KS ISO-06- 1452 Part 2:2009. Rate shall include cart away surplus materials from site as directed by engineer', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-18', NULL, NULL),
(13, 17, 7, 10, 0, 0, 'Backfill with first soft red soil surround or equivalent material then using excavated materials and compact in 200mm layerfills as approved by the Engineer.', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-18', NULL, NULL),
(14, 18, 7, 10, 0, 0, 'Supply, transport to site, lay and joint 160mm PN 10 HDPE Pipes to KS ISO-06-1452 Part 2:2009. Rates to include for all jointing, cutting wastage. ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-18', NULL, NULL),
(15, 19, 6, 8, 0, 0, 'Embankment & Core Trench Excavations', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-18', NULL, NULL),
(16, 20, 6, 8, 0, 0, 'Spillway', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-18', NULL, NULL),
(17, 21, 6, 9, 0, 0, 'Backfill with first soft red soil surround or equivalent material then using excavated materials and compact in 200mm layerfills as approved by the Engineer.', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-18', NULL, NULL),
(18, 22, 6, 9, 0, 0, 'Supply, transport to site, lay and joint 160mm PN 10 HDPE Pipes to KS ISO-06-1452 Part 2:2009. Rates to include for all jointing, cutting wastage. ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-18', NULL, NULL),
(19, 23, 5, 6, 0, 0, ' Embankment & Core Trench Excavations', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-18', NULL, NULL),
(21, 24, 5, 6, 0, 0, 'Spillway', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-18', NULL, NULL),
(22, 25, 5, 7, 0, 0, 'Earthworks', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-18', NULL, NULL),
(23, 26, 5, 7, 0, 0, 'Concrete Works', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-03-18', NULL, NULL),
(24, 27, 15, 11, 0, 0, 'Task', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-04-11', NULL, NULL),
(25, 28, 62, 22, 0, 0, 'Dolore inventore con', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-04-23', NULL, NULL),
(26, 28, 62, 22, 0, NULL, 'Est quae sint verit', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-04-23', NULL, NULL),
(28, 29, 59, 23, 0, 0, 'T1', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-04-25', NULL, NULL),
(29, 29, 59, 23, 0, 0, 'T2', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-04-25', NULL, NULL),
(30, 31, 58, 21, 0, 0, 'Task 1', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-03', NULL, NULL),
(31, 31, 58, 21, 0, NULL, 'Task 2', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-03', NULL, NULL),
(32, 31, 58, 21, 0, NULL, 'Task 3', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-03', NULL, NULL),
(33, 32, 58, 21, 0, 0, 'Task 3', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-03', NULL, NULL),
(34, 32, 58, 21, 0, NULL, 'Task 4', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-03', NULL, NULL),
(35, 32, 58, 21, 0, 0, 'Task 5', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-03', NULL, NULL),
(49, 33, 66, 24, 0, 0, 'Task 1', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-05', NULL, NULL),
(50, 33, 66, 24, 0, 0, 'Task 2', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-05', NULL, NULL),
(51, 34, 69, 25, 0, 0, 'Sub-Task', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-15', NULL, NULL),
(52, 36, 69, 25, 0, 0, 'Sub-Task 34', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-15', NULL, NULL),
(53, 35, 69, 26, 0, 0, 'Sub 1', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-15', NULL, NULL),
(54, 35, 69, 26, 0, 53, 'Sub 2', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-05-15', NULL, NULL),
(55, 37, 70, 27, 0, 0, 'Construction of different sizes of pillars', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-05-31', NULL, NULL),
(56, 37, 70, 27, 0, NULL, 'Erection of pillars', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-05-31', NULL, NULL),
(58, 38, 70, 27, 0, 0, 'Construction of substructure', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(59, 38, 70, 27, 0, 0, 'Laying of Murram', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(60, 38, 70, 27, 0, 0, 'Compaction ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(61, 38, 70, 27, 0, NULL, 'Laying of Tarmac', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(62, 38, 70, 27, 0, NULL, 'Tarmack Compaction', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(63, 39, 70, 27, 0, 0, 'Laborum est neque od', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(64, 39, 70, 27, 0, NULL, 'Aperiam laborum dele', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(65, 39, 70, 27, 0, 0, 'Beatae inventore fac', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(67, 39, 70, 27, 0, 0, 'Proident repudianda', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(68, 39, 70, 27, 0, 0, 'Quibusdam ullam id q', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(69, 39, 70, 27, 0, 68, 'Unde est dolor quaer', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(72, 39, 70, 27, 0, 0, 'Sit magni voluptate', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(75, 39, 70, 27, 0, 0, 'Laborum odio volupta', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(76, 39, 70, 27, 0, NULL, 'Odit velit ut volup', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(77, 39, 70, 27, 0, 0, 'Non aliquip debitis ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(79, 39, 70, 27, 0, 0, 'Rerum proident est ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(80, 39, 70, 27, 0, NULL, 'Illo quia sed culpa', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '124', '2023-06-03', NULL, NULL),
(81, 45, 71, 29, 0, 0, 'Non libero voluptate', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-06-10', NULL, NULL),
(82, 45, 71, 29, 0, NULL, 'Quod voluptas amet ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-06-10', NULL, NULL),
(83, 47, 75, 32, 0, 0, 'Et sit corrupti in', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-02', NULL, NULL),
(86, 47, 75, 32, 0, 0, 'Et sit corrupti in', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-02', NULL, NULL),
(87, 47, 75, 32, 0, 0, 'Dolor voluptate eos ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-02', NULL, NULL),
(88, 47, 75, 32, 0, 87, 'Adipisicing magnam e', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-02', NULL, NULL),
(89, 48, 74, 31, 14, 0, 'Drilling for a 150mm finished din borehole in any material excluding recording initial ground level , and providing samples and records of all changes in strata water levels', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-02', NULL, NULL),
(96, 51, 77, 35, 0, 0, 'STask 1', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(105, 51, 77, 35, 0, NULL, 'STask 2', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(106, 52, 77, 35, 0, 0, 'STask A', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(107, 52, 77, 35, 0, 106, 'STask B', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(108, 52, 77, 35, 0, 107, 'STask C', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(137, 53, 78, 37, 0, 0, 'Removal of Top Soil', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(138, 53, 78, 37, 0, 137, 'Embankment fill', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(139, 54, 78, 37, 0, 0, 'Survey works and setting out', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(140, 54, 78, 37, 0, 139, 'Bush Clearing and Stripping ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(141, 54, 78, 37, 0, 140, 'Construction of temporary access road', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(142, 55, 78, 37, 0, 0, 'Construction of Box Culverts', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(143, 55, 78, 37, 0, 142, 'Installation of Ring Culverts', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(144, 55, 78, 37, 0, 143, 'Fill Slope', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(145, 55, 78, 37, 0, 144, 'Cut Slope', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(146, 55, 78, 37, 0, 144, 'Slope Protection', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(147, 57, 78, 37, 0, 0, 'Construction of Improved Subgrade', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(148, 57, 78, 37, 0, 147, 'sub-base', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(149, 57, 78, 37, 0, 148, 'Base- course', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(150, 57, 78, 37, 0, 149, 'Surfacing ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(151, 57, 78, 37, 0, 150, 'Road Furniture', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(152, 56, 78, 37, 0, 0, 'Demobilization of Tools and Equipment', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(153, 56, 78, 37, 0, 152, 'Final Inspection and Hand over', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-03', NULL, NULL),
(154, 59, 78, 38, 0, 0, 'Removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(155, 59, 78, 38, 0, 154, 'River Deviation', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(156, 60, 78, 38, 0, 0, 'Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(157, 60, 78, 38, 0, 156, 'Disposal of Water', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(158, 60, 78, 38, 0, 157, 'Planking and Strutting', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(159, 60, 78, 38, 0, 158, 'Granular filling ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(160, 60, 78, 38, 0, 159, 'Erosion Protection and River training works ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(161, 60, 78, 38, 0, 160, 'Stone Pitching ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(162, 61, 78, 38, 0, 0, 'Excavation for Gabions', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(163, 61, 78, 38, 0, 162, 'Gabion Mesh', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(164, 61, 78, 38, 0, 163, 'Rockfill for Gabions', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(165, 62, 78, 38, 0, 0, 'Reinforcements bars including bends, tying wires, hooks and distance blocks', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(166, 63, 78, 38, 0, 0, 'Footpath Construction', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(167, 63, 78, 38, 0, 166, 'Construction of Hand Rails ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-04', NULL, NULL),
(239, 65, 79, 39, 0, 0, 'Plant facilities ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(240, 65, 79, 39, 0, 239, 'Contractors Site', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(241, 65, 79, 39, 0, 240, 'Resident Engineers Office ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(242, 65, 79, 39, 0, 241, 'Purchase of Equipment', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(243, 66, 79, 39, 0, 0, 'Survey works and setting out', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(244, 66, 79, 39, 0, 243, 'Earthworks', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(245, 66, 79, 39, 0, 244, 'Drainage ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(246, 66, 79, 39, 0, 245, 'Slope Protection', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(247, 67, 79, 39, 0, 0, 'Construction of Improved Subgrade', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(248, 67, 79, 39, 0, 247, 'Surfacing ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(249, 67, 79, 39, 0, 248, 'Road Furniture', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(250, 68, 79, 40, 0, 0, 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(251, 68, 79, 40, 0, 250, 'River deviation', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(252, 69, 79, 40, 0, 0, 'Planking, strutting and shoring to sides of all excavations : keep excavations free from all fallen materials', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(253, 69, 79, 40, 0, 252, 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(254, 69, 79, 40, 0, 253, 'Erosion Protection and River Training Works ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(255, 70, 79, 40, 0, 0, 'Excavation for Gabions in Soft Material', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(256, 70, 79, 40, 0, 255, 'Gabion Mesh', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(257, 71, 79, 40, 0, 0, 'Footpaths ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(258, 71, 79, 40, 0, 257, 'Hand Rails ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-07', NULL, NULL),
(259, 76, 82, 42, 0, 0, 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-08', NULL, NULL),
(260, 77, 82, 42, 0, 0, 'Excavation in topsoil for foundations, depth not exceeding 0.25m', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-08', NULL, NULL),
(261, 78, 82, 42, 0, 0, 'Reinforced Concrete', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-08', NULL, NULL),
(262, 79, 85, 43, 0, 0, ' Top soil removal ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-09', NULL, NULL),
(263, 80, 85, 43, 0, 0, 'Damp-proof membrane', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-09', NULL, NULL),
(264, 81, 89, 47, 0, 0, 'Removal of soil', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-12', NULL, NULL),
(265, 82, 89, 47, 0, 0, 'labour', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-12', NULL, NULL),
(266, 83, 89, 47, 0, 0, 'labour', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-12', NULL, NULL),
(267, 84, 91, 48, 0, 0, 'Manufacturing', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-14', NULL, NULL),
(268, 84, 91, 48, 0, 0, 'Delivery to Kapkures', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-14', NULL, NULL),
(269, 85, 91, 48, 0, 0, 'Allocate 20 vehicles ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-14', NULL, NULL),
(270, 85, 91, 48, 0, 0, 'Allocate 100 staff ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-14', NULL, NULL),
(271, 73, 81, 41, 0, 0, 'Subtask1', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-15', NULL, NULL),
(272, 74, 81, 41, 0, 0, 'Subtask1', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-15', NULL, NULL),
(273, 75, 81, 41, 0, 0, 'Subtask1', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-15', NULL, NULL),
(274, 72, 81, 41, 0, 0, 'Subtask1', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-15', NULL, NULL),
(275, 87, 93, 49, 0, 0, 'STask 1', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-18', NULL, NULL),
(276, 87, 93, 49, 0, 275, 'STask A', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-18', NULL, NULL),
(280, 89, 94, 50, 2, 286, 'Ut amet exercitatio', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(281, 89, 94, 50, 16, 284, 'Voluptatem maxime n', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(282, 89, 94, 50, 2, 0, 'Sed ducimus distinc', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(283, 89, 94, 50, 13, 0, 'Quo sint nihil quod ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(284, 89, 94, 50, 2, 0, 'Vel voluptatem volup', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(285, 89, 94, 50, 2, 0, 'Laboris quisquam ten', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(286, 89, 94, 50, 2, 0, 'Accusamus quia quo v', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(287, 88, 94, 50, 14, 0, 'Soluta quam reprehen', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(288, 88, 94, 50, 16, 0, 'Aliqua Placeat ut ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(289, 88, 94, 50, 14, 0, 'Rerum repudiandae pr', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(290, 88, 94, 50, 14, 0, 'Soluta quam reprehen', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(291, 88, 94, 50, 16, 0, 'Aliqua Placeat ut ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(292, 88, 94, 50, 14, 0, 'Rerum repudiandae pr', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(293, 88, 94, 50, 14, 0, 'Soluta quam reprehen', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(294, 88, 94, 50, 16, 0, 'Aliqua Placeat ut ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(295, 88, 94, 50, 14, 0, 'Rerum repudiandae pr', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(296, 88, 94, 50, 14, 0, 'Soluta quam reprehen', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(297, 88, 94, 50, 16, 288, 'Aliqua Placeat ut ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(298, 88, 94, 50, 14, 290, 'Rerum repudiandae pr', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(299, 88, 94, 50, 14, 0, 'Soluta quam reprehen', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(300, 88, 94, 50, 16, 0, 'Aliqua Placeat ut ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(301, 88, 94, 50, 14, 0, 'Rerum repudiandae pr', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-08-19', NULL, NULL),
(302, 93, 95, 51, 7, 0, 'Ea exercitation fuga', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(303, 93, 95, 51, 59, 0, 'Sunt et repellendus', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(304, 93, 95, 51, 16, 0, 'Ut voluptate itaque ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(305, 94, 95, 51, 7, 0, 'Praesentium dignissi', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(306, 94, 95, 51, 2, 0, 'Est placeat molesti', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(307, 95, 95, 51, 19, 0, 'In et qui voluptate ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(308, 95, 95, 51, 2, 0, 'Et placeat pariatur', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(309, 96, 95, 51, 7, 0, 'Qui id ipsum disti', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(310, 96, 95, 51, 59, 0, 'Ipsam obcaecati quis', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(311, 97, 95, 52, 14, 0, 'Nisi aut voluptate d', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(312, 97, 95, 52, 60, 0, 'Sapiente duis minus ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(313, 98, 95, 52, 7, 0, 'Cillum similique tot', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(314, 98, 95, 52, 15, 0, 'Elit nihil incididu', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(315, 99, 95, 52, 13, 0, 'Sit in magnam aperia', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(316, 99, 95, 52, 14, 0, 'Non vel et vel vero ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-21', NULL, NULL),
(317, 103, 96, 54, 13, 0, 'Top Soil Removal ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-24', NULL, NULL),
(318, 104, 96, 54, 13, 0, 'Excavation ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-24', NULL, NULL),
(319, 104, 96, 54, 13, 0, 'Backfilling ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-24', NULL, NULL),
(320, 104, 96, 54, 14, 0, 'Planking and Strutting', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-24', NULL, NULL),
(321, 100, 96, 53, 19, 0, 'Resident Engineers Office', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-24', NULL, NULL),
(322, 100, 96, 53, 19, 0, 'Contractors Site', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-24', NULL, NULL),
(323, 101, 96, 53, 13, 0, 'Earthworks', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-08-24', NULL, NULL),
(331, 105, 98, 57, 19, 0, 'STask 1', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-04', NULL, NULL),
(332, 105, 98, 57, 19, 0, 'STask 2', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-04', NULL, NULL),
(333, 106, 98, 57, 19, 0, 'STask A', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-04', NULL, NULL),
(334, 106, 98, 57, 19, 0, 'STask B', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-04', NULL, NULL),
(335, 107, 98, 57, 19, 0, 'STask C', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-04', NULL, NULL),
(336, 107, 98, 57, 19, 0, 'STask D', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-04', NULL, NULL),
(337, 108, 97, 56, 14, NULL, 'Drilling 8\" diameter borehole from 0-100m depth', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '1', '2023-09-04', NULL, NULL),
(340, 108, 97, 56, 14, NULL, 'Drilling 8\" diameter borehole from 100-110m depth', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-04', NULL, NULL),
(341, 108, 97, 56, 14, NULL, 'Drilling 8\" diameter borehole above 110m depth if necessary', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-04', NULL, NULL),
(342, 109, 100, 58, 13, NULL, 'Excavate to reduced levels in top soil for depth not exceeding 0.25', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-06', NULL, NULL),
(343, 109, 100, 58, 13, NULL, 'Excavate for tank foundation 0.25-0.5m', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-06', NULL, NULL),
(344, 110, 100, 58, 13, NULL, 'Fill and compact selected excavated material other than top soil,rock or artificially hard material ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-06', NULL, NULL),
(345, 111, 100, 58, 14, NULL, 'Supply and fix 38mm diameter GI Class ”B” Tank inlet pipe ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-06', NULL, NULL),
(346, 111, 100, 58, 14, NULL, 'Supply and fix 63mm diameter GI Class B Tank', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-06', NULL, NULL),
(347, 48, 74, 31, 14, NULL, 'Provide and install 200mm dia. Plain carbon steel casing. ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-14', NULL, NULL),
(348, 112, 101, 59, 14, NULL, 'Removal of trees and stumps', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-14', NULL, NULL),
(349, 113, 101, 59, 13, NULL, 'Top soil removal ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-14', NULL, NULL),
(350, 114, 84, 55, 13, NULL, 'Excavation for foundation ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-14', NULL, NULL),
(351, 115, 84, 55, 14, NULL, 'Pipe laying', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-14', NULL, NULL),
(352, 120, 109, 67, 13, NULL, 'Clear site for new bridge construction including removal of hedges, bushes, trees shrubs and other undesirable vegetation, grub up roots, and dispose as directed the Engineer.', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(353, 120, 109, 67, 13, NULL, 'River deviation', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(354, 121, 109, 67, 13, NULL, 'Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(355, 121, 109, 67, 13, NULL, 'Extra over excavation in hardrock', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(356, 121, 109, 67, 19, NULL, 'Keep excavations free from all water by baling, pumping or otherwise', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(357, 122, 109, 67, 13, NULL, 'Provide, backfill to any depth granular fill material as hardcore or rockfill as necessary below bridge floor formation; all in accordance with the Specification and in conformity with the Supervisor\'s instructions', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(358, 122, 109, 67, 13, NULL, 'Excavate for Approaches, Erosion Check, Scour Checks and the like. Excavation in soft material to any depth, backfilling and compacting or hauling to spoil excavated material; all in accordance with the Specification and in conformity with the Supervisor\'s instructions (Provisional)', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(359, 122, 109, 67, 72, NULL, '200mm thick dry stone pitching to embankments and in front of abutments', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(360, 123, 109, 67, 13, NULL, 'Excavation in soft material to any depth, compaction of the surfaces to receive the gabions, backfilling with the excavated materials or hauling to spoil excavated material', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(361, 123, 109, 67, 72, NULL, 'Providing and fixing the mesh including diaphragms', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(362, 123, 109, 67, 13, NULL, 'Providing, hauling and placing the rock', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(363, 123, 109, 67, 72, NULL, 'Providing and hauling all materials, preparation, handling, placing of 75mm thick concrete to floor bed as blinding.', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(364, 123, 109, 67, 13, NULL, 'Providing and hauling all materials, preparation, handling, placing, finishing and curing premix concrete to slab bed , column base, side walls,beams and deck slab.', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(365, 123, 109, 67, 13, NULL, 'ditto to column but mixing on site.', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(366, 116, 109, 66, 19, NULL, 'Preparation of Contractors Site', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(367, 116, 109, 66, 19, NULL, 'Purchase of Equipments', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(368, 116, 109, 66, 19, NULL, 'Construction of resident engineer\'s office ', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(369, 116, 109, 66, 19, NULL, 'Mobilization of Plant facilities', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(370, 117, 109, 66, 13, NULL, 'Excavate for structures in soft material', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(371, 117, 109, 66, 13, NULL, 'Excavation of unsuitable material below structure', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(372, 117, 109, 66, 13, NULL, 'Extra over item 7.01 for excavation in hard material', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(373, 117, 109, 66, 13, NULL, 'Selected granular fill material', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(374, 117, 109, 66, 13, NULL, 'Gabion mattress mesh', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(375, 118, 109, 66, 13, NULL, 'Fill in soft material', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(376, 118, 109, 66, 13, NULL, 'Improved Subgrade fill class s3', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(377, 118, 109, 66, 13, NULL, 'Compaction of 150mm depth of existing ground under embankments to 100% MDD (AASHTO-T99', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(378, 118, 109, 66, 13, NULL, 'Compaction of the 300 mm below formation level in cuttings to 100% MDD (AASHTO-T99', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL),
(379, 119, 109, 66, 75, NULL, 'Final Inspection and Handover', NULL, NULL, 0, '0.00', 0, 0, NULL, 0, 0, NULL, '0', NULL, NULL, '118', '2023-09-29', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_taskstatus`
--

CREATE TABLE `tbl_taskstatus` (
  `tsid` int NOT NULL,
  `taskstatus` varchar(300) NOT NULL,
  `taskstatusname` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Table structure for table `tbl_task_parameters`
--

CREATE TABLE `tbl_task_parameters` (
  `id` int NOT NULL,
  `task_id` int NOT NULL,
  `parameter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `unit_of_measure` varchar(255) NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_task_parameters`
--

INSERT INTO `tbl_task_parameters` (`id`, `task_id`, `parameter`, `unit_of_measure`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 3, 'Excavation ', 'Cubic Metres', 118, NULL, '2023-03-15', NULL),
(2, 3, 'backfilling ', 'Cubic Metres', 118, NULL, '2023-03-15', NULL),
(3, 4, 'Sub-base', 'Kilometers', 118, NULL, '2023-03-15', NULL),
(4, 4, 'Surfacing ', 'kms', 118, NULL, '2023-03-15', NULL),
(5, 5, 'Stone Pitching ', 'kms', 118, NULL, '2023-03-15', NULL),
(6, 6, 'Planking ', 'kms', 118, NULL, '2023-03-15', NULL),
(7, 6, 'Strutting', 'kms', 118, NULL, '2023-03-15', NULL),
(8, 6, 'Erosion Protection', 'kms', 118, NULL, '2023-03-15', NULL),
(9, 6, 'River Training Works ', 'KMS', 118, NULL, '2023-03-15', NULL),
(10, 6, 'Concrete Blinding ', 'Cubic Metres', 118, NULL, '2023-03-15', NULL),
(11, 6, 'Reinforcement', 'Cubic Metres', 118, NULL, '2023-03-15', NULL),
(12, 7, 'Excavation in topsoil for foundations, depth not exceeding 0.25m', 'M3', 118, NULL, '2023-03-15', NULL),
(13, 8, 'Masonary joint reinforcement with 20X3mm thick hoop iron in every alternate course in every course.', 'M', 118, NULL, '2023-03-15', NULL),
(14, 1, 'Excavation ', 'Cubic Metres', 118, NULL, '2023-03-15', NULL),
(15, 1, 'Backfilling', 'Cubic Metres', 118, NULL, '2023-03-15', NULL),
(16, 2, 'Material Haulage', 'Cubic Metres', 118, NULL, '2023-03-15', NULL),
(17, 9, 'Road Furniture', 'kms', 118, NULL, '2023-03-15', NULL),
(18, 9, 'Road Surfacing ', 'kms', 118, NULL, '2023-03-15', NULL),
(27, 10, 'Allow for conducting an Hydrological site survey to establish the best point to drill a bore hole within the plot by a registered Hydrologist and produce report a detailed report to the Services Engineer. The result of this will either justify or negate the next event.', 'Item', 118, NULL, '2023-03-15', NULL),
(28, 11, 'Drilling from 100 – 200m', 'Lm', 118, NULL, '2023-03-15', NULL),
(29, 13, 'Backfill with first soft red soil surround or equivalent material then using excavated materials and compact in 200mm layerfills as approved by the Engineer.', 'M3', 118, NULL, '2023-03-18', NULL),
(30, 12, 'Excavate in top soil not exceeding 1.2m deep and 0.8m wide to receive 160mm PN 10 HDPE Pipes to KS ISO-06- 1452 Part 2:2009. Rate shall include cart away surplus materials from site as directed by engineer', 'M3', 118, NULL, '2023-03-18', NULL),
(31, 14, 'Supply, transport to site, lay and joint 160mm PN 10 HDPE Pipes to KS ISO-06-1452 Part 2:2009. Rates to include for all jointing, cutting wastage. ', 'M', 118, NULL, '2023-03-18', NULL),
(32, 15, 'Excavate for embankment foundation to a depth of 0.30m below the stripped level of 300mm below existing ground level or as directed by the Engineer', 'M3', 118, NULL, '2023-03-18', NULL),
(33, 16, 'Provide and place 0.06m 1:3:6 concrete blinding layers to sill bases 5 no.', 'M2', 118, NULL, '2023-03-18', NULL),
(34, 16, 'Provide,place and compact mass concrete of mix ratio of 1:2:4 and construct sills size 26m x 1m x 0.7m as indicated in the drawings', 'M3', 118, NULL, '2023-03-18', NULL),
(35, 17, 'Backfill with first soft red soil surround or equivalent material then using excavated materials and compact in 200mm layerfills as approved by the Engineer.', 'M3', 118, NULL, '2023-03-18', NULL),
(36, 18, 'Supply, transport to site, lay and joint 160mm PN 10 HDPE Pipes to KS ISO-06-1452 Part 2:2009. Rates to include for all jointing, cutting wastage. ', 'M', 118, NULL, '2023-03-18', NULL),
(37, 19, 'Excavate for embankment foundation to a depth of 0.30m below the stripped level of 300mm below existing ground level or as directed by the Engineer', 'M3', 118, NULL, '2023-03-18', NULL),
(38, 21, 'Provide and place 0.06m 1:3:6 concrete blinding layers to sill bases 5 no.', 'M2', 118, NULL, '2023-03-18', NULL),
(39, 22, 'Excavation in topsoil for foundations, depth not exceeding 0.25m', 'M3', 118, NULL, '2023-03-18', NULL),
(40, 23, 'Provide,place and compact mass concrete of mix ratio of 1:2:4 and construct sills size 26m x 1m x 0.7m as indicated in the drawings', 'M3', 118, NULL, '2023-03-18', NULL),
(41, 24, 'Item', '1', 1, NULL, '2023-04-11', NULL),
(42, 25, 'Est sed est reprehe', 'Ut omnis asperiores ', 1, NULL, '2023-04-23', NULL),
(43, 26, 'Tempor laboris deser', 'Alias molestiae veni', 1, NULL, '2023-04-23', NULL),
(44, 29, 'U1', 'Meters', 1, NULL, '2023-04-25', NULL),
(45, 28, 'T1 U1', 'Un', 1, NULL, '2023-04-25', NULL),
(46, 31, 'Item 1', 'Unit 1', 1, NULL, '2023-05-03', NULL),
(47, 31, 'Item 3', 'Unit 2', 1, NULL, '2023-05-03', NULL),
(48, 31, 'Item 4', 'Unit 3', 1, NULL, '2023-05-03', NULL),
(49, 32, 'Item 2', 'Unit 1', 1, NULL, '2023-05-03', NULL),
(50, 30, 'Item 34', 'Unit 4', 1, NULL, '2023-05-03', NULL),
(51, 35, 'Item 3', 'Unit 4', 1, NULL, '2023-05-03', NULL),
(52, 33, 'Item 54', 'Unit 43', 1, NULL, '2023-05-03', NULL),
(53, 34, 'Item Task 4', 'Unit 45', 1, NULL, '2023-05-03', NULL),
(54, 50, 'Item 1', 'Unit', 1, NULL, '2023-05-05', NULL),
(55, 49, 'Item 2', 'Unit', 1, NULL, '2023-05-05', NULL),
(56, 49, 'Item 3', 'Sem 1', 1, NULL, '2023-05-05', NULL),
(57, 51, 'Item 2', 'Unit 1', 1, NULL, '2023-05-15', NULL),
(58, 51, 'Item 3', 'Unit 3', 1, NULL, '2023-05-15', NULL),
(59, 52, 'Item 123', 'Unit 1', 1, NULL, '2023-05-15', NULL),
(60, 52, 'Item 345', 'Unit 1', 1, NULL, '2023-05-15', NULL),
(61, 53, 'Item 1', 'Unit 1', 1, NULL, '2023-05-15', NULL),
(62, 54, 'Item 2', 'Unit 4', 1, NULL, '2023-05-15', NULL),
(63, 82, 'Qui qui perspiciatis', 'Nesciunt et et do m', 1, NULL, '2023-06-10', NULL),
(64, 82, 'Eaque aut facilis mi', 'Ullam temporibus rer', 1, NULL, '2023-06-10', NULL),
(65, 81, 'Saepe dolore eu veni', 'Incididunt earum ut ', 1, NULL, '2023-06-10', NULL),
(66, 81, 'Aute Nam commodo ame', 'Voluptatem non sint', 1, NULL, '2023-06-10', NULL),
(67, 83, 'Molestiae elit ut u', 'Illo ex est dolor c', 1, NULL, '2023-08-02', NULL),
(68, 83, 'Cumque atque perspic', 'Doloribus assumenda ', 1, NULL, '2023-08-02', NULL),
(69, 83, 'Eum enim nesciunt m', 'Odio sed ut sed labo', 1, NULL, '2023-08-02', NULL),
(70, 86, 'Corrupti aspernatur', 'Rerum autem dolorem ', 1, NULL, '2023-08-02', NULL),
(71, 86, 'Atque Nam distinctio', 'Occaecat eum ratione', 1, NULL, '2023-08-02', NULL),
(72, 86, 'Sequi in temporibus ', 'Ut aut beatae sed sa', 1, NULL, '2023-08-02', NULL),
(73, 86, 'Nostrud perferendis ', 'Sed doloribus sint f', 1, NULL, '2023-08-02', NULL),
(74, 88, 'Aut amet autem in c', 'Aut consectetur corr', 1, NULL, '2023-08-02', NULL),
(75, 88, 'Voluptas non nostrud', 'Dolor optio dolores', 1, NULL, '2023-08-02', NULL),
(76, 88, 'Porro accusantium ea', 'Vel assumenda illum', 1, NULL, '2023-08-02', NULL),
(77, 87, 'Aut Nam qui irure no', 'Nobis enim magna adi', 1, NULL, '2023-08-02', NULL),
(78, 87, 'Delectus nisi ex et', 'Rerum alias et rem e', 1, NULL, '2023-08-02', NULL),
(79, 87, 'Veniam dolores in l', 'Tempore ullamco mol', 1, NULL, '2023-08-02', NULL),
(80, 89, 'Saepe recusandae Ea', 'Nulla proident sunt', 1, NULL, '2023-08-02', NULL),
(81, 89, 'Aut quos rerum porro', 'Quia aute iusto faci', 1, NULL, '2023-08-02', NULL),
(82, 90, 'At vero eius ipsum ', 'Reprehenderit volup', 1, NULL, '2023-08-02', NULL),
(83, 91, 'Omnis consectetur c', 'Odio fugit mollit t', 1, NULL, '2023-08-02', NULL),
(84, 91, 'Laborum Dolorem odi', 'Porro et labore dolo', 1, NULL, '2023-08-02', NULL),
(85, 91, 'Consequatur Obcaeca', 'Do qui vitae quis mi', 1, NULL, '2023-08-02', NULL),
(86, 92, 'Et vitae mollitia es', 'Assumenda ipsum aut', 1, NULL, '2023-08-02', NULL),
(87, 92, 'Qui quaerat deserunt', 'Ut cupidatat fugit ', 1, NULL, '2023-08-02', NULL),
(88, 92, 'Lorem autem nisi nem', 'Veritatis quo et mag', 1, NULL, '2023-08-02', NULL),
(89, 92, 'A cupidatat aspernat', 'Necessitatibus liber', 1, NULL, '2023-08-02', NULL),
(90, 93, 'Rem esse quidem nih', 'Enim ipsum dolor re', 1, NULL, '2023-08-02', NULL);

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
(1, 3, '567777', '21344456', 'Tender for  Construction of water storage tanks', 1, 1, 0, 1, '2022-12-01', '2022-12-08', '2022-12-22', '2022-12-24', '2023-01-02', '2023-07-07', '15', '75', 4, 'Above Board', '118', '2023-03-15', NULL, NULL),
(2, 2, '1275554567', '9820000', 'Tender for  Drilling of boreholes and Equiping', 1, 1, 0, 1, '2022-12-02', '2022-12-09', '2022-12-16', '2022-12-24', '2023-01-01', '2023-07-08', '20', '80', 3, 'Proper Evaluation', '118', '2023-03-15', NULL, NULL),
(3, 1, 'CGU/W/T/034/2022-2023', '090', 'Construction of Tebeson Maili NNE Road ', 1, 1, 0, 1, '2022-10-10', '2022-10-20', '2022-10-25', '2022-11-01', '2022-11-05', '2023-08-25', '28', '70', 5, 'The tender was done competitively and above reproach', '118', '2023-03-16', NULL, NULL),
(4, 1, 'CGU/W/T/034/2022-2023', '090', 'Construction of Tebeson Maili NNE Road ', 1, 1, 0, 1, '2022-07-22', '2022-07-30', '2022-08-01', '2022-08-05', '2022-08-10', '2023-11-15', '28', '70', 4, 'The tender was done competitively and above reproach', '118', '2023-03-16', NULL, NULL),
(5, 1, 'CGU/W/T/034/2022-2023', '090', 'Construction of Tebeson Maili NNE Road ', 1, 1, 0, 1, '2022-10-10', '2022-10-20', '2022-10-25', '2022-11-01', '2022-11-05', '2023-08-25', '28', '70', 5, 'The tender was done competitively and above reproach', '118', '2023-03-16', NULL, NULL),
(6, 1, 'CGU/W/T/034/2022-2023', '090', 'Construction of Tebeson Maili NNE Road ', 1, 1, 0, 1, '2022-10-10', '2022-10-20', '2022-10-25', '2022-11-01', '2022-11-05', '2023-08-25', '28', '70', 5, 'The tender was done competitively and above reproach', '118', '2023-03-16', NULL, NULL),
(7, 1, 'CGU/W/T/034/2022-2023', '090', 'Construction of Tebeson Maili NNE Road ', 1, 1, 0, 1, '2022-10-10', '2022-10-20', '2022-10-25', '2022-11-01', '2022-11-05', '2023-08-25', '28', '70', 5, 'The tender was done competitively and above reproach', '1', '2023-03-16', NULL, NULL),
(8, 1, 'CGU/W/T/034/2022-2023', '090', 'Construction of Tebeson Maili NNE Road ', 1, 1, 0, 1, '2022-10-10', '2022-10-20', '2022-10-25', '2022-11-01', '2022-11-05', '2023-08-25', '28', '70', 5, 'The tender was done competitively and above reproach', '1', '2023-03-16', NULL, NULL),
(9, 7, '57766333', '123456', 'tendernfor  Laying of water pipes', 1, 2, 0, 1, '2022-11-01', '2022-11-09', '2022-11-23', '2022-11-30', '2023-01-01', '2024-01-31', '20', '76', 4, 'well done', '118', '2023-03-18', NULL, NULL),
(10, 6, '456789', '12345', 'Tender for  Itare Dam Construction Project', 1, 2, 0, 1, '2022-10-21', '2022-10-24', '2022-11-01', '2022-11-15', '2023-01-01', '2025-01-01', '17', '75', 4, 'Above board', '118', '2023-03-21', NULL, NULL),
(11, 5, '45321', '566777', 'Tender for  Kimwarer Dam Construction Project', 1, 1, 0, 1, '2022-11-02', '2022-11-15', '2022-12-01', '2022-11-30', '2023-01-01', '2024-01-02', '20', '75', 4, 'Well done', '118', '2023-04-01', NULL, NULL),
(12, 66, 'Mollitia numquam off', 'Non fugiat unde ali', 'Ipsum facilis conse', 3, 2, 0, 6, '1986-08-08', '2022-01-03', '1973-03-24', '2014-03-18', '1981-11-11', '1988-04-12', '40', '90', 8, 'Doloribus in culpa a', '1', '2023-05-05', NULL, NULL),
(13, 66, 'Mollitia numquam off', 'Non fugiat unde ali', 'Ipsum facilis conse', 3, 2, 0, 6, '1986-08-08', '2022-01-03', '1973-03-24', '2014-03-18', '1981-11-11', '1988-04-12', '40', '90', 8, 'Doloribus in culpa a', '1', '2023-05-08', NULL, NULL),
(14, 69, 'IN13/20345/56', '6726', 'Project name one. ', 1, 1, 0, 1, '2023-05-18', '2023-05-24', '2023-06-08', '2023-06-03', '2023-05-19', '2023-05-23', '111', '12', 1, 'Contract tender comments here ', '1', '2023-05-16', NULL, NULL),
(15, 78, 'Laudantium exercita', 'Duis ut itaque sunt', 'Aperiam magna modi e', 1, 2, 0, 9, '2023-01-12', '2023-01-25', '2023-01-26', '2023-02-17', '2023-03-31', '2023-07-23', '26', '40', 1, 'Exercitationem qui r', '1', '2023-08-12', NULL, NULL),
(16, 97, '12344', '87345', 'Tender for drilling of boreholes', 1, 1, 0, 1, '2023-05-25', '2023-06-01', '2023-06-15', '2023-06-30', '2023-07-01', '2023-11-30', '15', '75', 2, 'Fairly done', '118', '2023-09-05', NULL, NULL),
(17, 96, '67MM', '2019/009U', 'Kolelach Road Project', 1, 2, 0, 1, '2022-12-23', '2023-01-19', '2023-01-15', '2023-01-30', '2023-02-01', '2024-02-01', '20', '75', 5, 'TEST', '118', '2023-09-06', NULL, NULL),
(18, 96, '67MM', '2019/009U', 'Kolelach Road Project', 1, 2, 0, 1, '2022-12-23', '2023-01-19', '2023-01-15', '2023-01-30', '2023-02-01', '2024-02-01', '20', '75', 5, 'TEST', '118', '2023-09-06', NULL, NULL),
(19, 101, '90002', '769000', 'Tender for the construction of anaerobic reactor', 1, 1, 0, 1, '2023-05-15', '2023-06-14', '2023-06-20', '2023-06-30', '2023-07-03', '2024-03-01', '15', '75', 1, 'Fairly done', '118', '2023-09-14', NULL, NULL),
(20, 2, '1275554567', '9820000', 'Tender for  Drilling of boreholes and Equiping', 1, 1, 0, 1, '2022-12-02', '2022-12-09', '2022-12-16', '2022-12-24', '2023-01-01', '2023-07-08', '20', '80', 3, 'Testing contract details', '118', '2023-09-21', NULL, NULL),
(21, 2, '1275554567', '9820000', 'Tender for  Drilling of boreholes and Equiping', 1, 1, 0, 1, '2022-12-02', '2022-12-09', '2022-12-16', '2022-12-24', '2023-01-01', '2023-07-08', '20', '80', 3, 'Testing contract details', '118', '2023-09-21', NULL, NULL),
(22, 2, '1275554567', '9820000', 'Tender for  Drilling of boreholes and Equiping', 1, 1, 0, 1, '2022-12-02', '2022-12-09', '2022-12-16', '2022-12-24', '2023-01-01', '2023-07-08', '20', '80', 3, 'Proper Evaluation', '118', '2023-09-21', NULL, NULL),
(23, 2, '1275554567', '9820000', 'Tender for  Drilling of boreholes and Equiping', 1, 1, 0, 1, '2022-12-02', '2022-12-09', '2022-12-16', '2022-12-24', '2023-01-01', '2023-07-08', '20', '80', 3, 'Proper Evaluation', '118', '2023-09-21', NULL, NULL),
(24, 2, '1275554567', '9820000', 'Tender for  Drilling of boreholes and Equiping', 1, 1, 0, 1, '2022-12-02', '2022-12-09', '2022-12-16', '2022-12-24', '2023-01-01', '2023-07-08', '20', '80', 3, 'Proper Evaluation', '118', '2023-09-21', NULL, NULL),
(25, 2, '1275554567', '9820000', 'Tender for  Drilling of boreholes and Equiping', 1, 1, 0, 1, '2022-12-02', '2022-12-09', '2022-12-16', '2022-12-24', '2023-01-01', '2023-07-08', '20', '80', 3, 'Proper Evaluation', '118', '2023-09-23', NULL, NULL),
(26, 109, '2022/RWCD/09/003', '98/22', 'Matumaini Roadworks Construction ', 1, 2, 0, 1, '2023-01-28', '2023-03-30', '2023-04-15', '2023-05-15', '2023-07-01', '2024-06-30', '25', '65', 3, 'Evaluation was above board', '118', '2023-09-29', NULL, NULL);

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
(6, 3, 'Plan', 'Plan', 'Plans'),
(7, 1, 'directorate', 'Directorate', 'Directorates');

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
(1, 'Hon', 1),
(2, 'Prof', 1),
(3, 'Dr', 1),
(4, 'Eng', 1),
(5, 'Mr', 1),
(6, 'Mrs', 1),
(7, 'Ms', 1),
(8, 'Eld', 1);

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
  `Q4` double NOT NULL,
  `created_by` int DEFAULT NULL,
  `date_created` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int NOT NULL,
  `pt_id` int NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `first_login` int NOT NULL DEFAULT '1',
  `password` varchar(200) NOT NULL,
  `type` int NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `pt_id`, `email`, `first_login`, `password`, `type`) VALUES
(1, 1, 'biwottech@gmail.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 1),
(118, 2, 'denkytheka@gmail.com', 0, '$2y$10$COQJSlMu48qd1S8rA94G4O8R2TgtgYQgOB9WBlhFtiSA3X4y0vHGS', 1),
(119, 3, 'kkipe15@gmail.com', 0, '$2y$10$COQJSlMu48qd1S8rA94G4O8R2TgtgYQgOB9WBlhFtiSA3X4y0vHGS', 1),
(120, 4, 'pkorir59@gmail.com', 0, '$2y$10$COQJSlMu48qd1S8rA94G4O8R2TgtgYQgOB9WBlhFtiSA3X4y0vHGS', 1),
(121, 5, 'korirkipngetich@yahoo.com', 0, '$2y$10$COQJSlMu48qd1S8rA94G4O8R2TgtgYQgOB9WBlhFtiSA3X4y0vHGS', 1),
(122, 6, 'kiplishi@gmail.com', 0, '$2y$10$COQJSlMu48qd1S8rA94G4O8R2TgtgYQgOB9WBlhFtiSA3X4y0vHGS', 1),
(123, 7, 'nicholaskoskey@gmail.com', 0, '$2y$10$aKqdJ1LCKAwF7jL4FYeQ/O6V3SNV5.q7GNb04GkS3HM/gi28b.LwW', 1),
(124, 8, 'neranickenterprises@gmail.com', 0, '$2y$10$COQJSlMu48qd1S8rA94G4O8R2TgtgYQgOB9WBlhFtiSA3X4y0vHGS', 1),
(126, 63, 'gasywip@mailinator.com', 1, '$2y$10$fDxRTp1R78147GHZNbyUzu0exo4ALMATpvpJyEd9B6QVVDGUKbcmK', 1),
(127, 64, 'mynahmc1@gmail.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 1),
(128, 65, 'kiplish@gmail.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 1),
(130, 67, 'p.korir@ombudsman.go.ke', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 1),
(131, 68, 'projtrac1@gmail.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 1),
(132, 69, 'ecornf@gmail.com', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 1),
(133, 70, 'PEKIPP254@GMAIL.COM', 0, '$2y$10$EQT/rVabl4Gg6VMSZ8Rwk.eSv4it2EsmpKZD.irhkeuMv/53.RJhG', 1),
(134, 71, 'neranickenterprise@gmail.com', 0, '$2y$10$COQJSlMu48qd1S8rA94G4O8R2TgtgYQgOB9WBlhFtiSA3X4y0vHGS', 1),
(135, 72, 'mynahmc@gmail.com', 1, '$2y$10$ypfbdZYGbRo4lXu1xCYlr.tlzvgxSWaC1aOtyHpaIhMZPViS5cTPC', 1),
(136, 73, 'isaacharris749@gmail.com', 0, '$2y$10$wLTSIQ3zQhptstHzqeOvR.Qg4RDpdQmj4n38jR.Ts5WT3.nC8C9vC', 1),
(137, 74, 'mle88709@gmail.com', 1, '$2y$10$mW5Lgb2SSivBh9OAUW9Jc.pMgNegqfE4ogDmAF2DFK6/SQjo2ZXmi', 1),
(138, 75, 'charlesfiverrwriter@gmail.com', 1, '$2y$10$ykfLSKaPSBeUwIL2PWkMYeRhbDxmKL997jU7CnK6kdLaQH1p3OnrO', 1),
(139, 76, 'denkytheka2@gmail.com', 1, '$2y$10$YBIyjLraRaGbRyEn3bhaAOObDsJ9kxIsA8WnpwvQwzAaEFlJQcHKS', 1),
(140, 77, 'denkytheka3@gmail.com', 1, '$2y$10$pvmNwKFKBsbGY3baA.jiy.66HLR3Uq5nmIXIZOh9o5symvENk1i5.', 1),
(141, 78, 'denkytheka4@gmail.com', 0, '$2y$10$weGy7Nx2UAa5QFVXXy7F9Ojgis9gd5B1lFChmcQZSjjqGdgAolf..', 1),
(142, 79, 'denkytheka@gmail.com', 1, '$2y$10$b4G77cUsmMrgOhUdSkCYtejNLo8RA9hjqbDBQ0XhF1vH9M4b2WDsa', 1),
(143, 80, 'info@projtrac.co.ke', 0, '$2y$10$c9eRIWQWTKkAArzR6ORfOOHwmDIZosMAaWlgbJSSFd.5ADJDyJY6a', 1),
(144, 81, 'peter.korir@projtrac.co.ke', 1, '$2y$10$kZsk4piorAQwPLU0PKyOmOBWkFSDO.nw4G6Lzw.TCtSO6fP.g/.Q6', 1);

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
-- Indexes for table `tbl_adp_projects_budget`
--
ALTER TABLE `tbl_adp_projects_budget`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `tbl_contractor_password_resets`
--
ALTER TABLE `tbl_contractor_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contractor_payment_requests`
--
ALTER TABLE `tbl_contractor_payment_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contractor_payment_request_comments`
--
ALTER TABLE `tbl_contractor_payment_request_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contractor_payment_request_details`
--
ALTER TABLE `tbl_contractor_payment_request_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contract_guarantees`
--
ALTER TABLE `tbl_contract_guarantees`
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
-- Indexes for table `tbl_designation_permissions`
--
ALTER TABLE `tbl_designation_permissions`
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
-- Indexes for table `tbl_inspection_monitoring_data_origin`
--
ALTER TABLE `tbl_inspection_monitoring_data_origin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_inspection_observations`
--
ALTER TABLE `tbl_inspection_observations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_issue_status`
--
ALTER TABLE `tbl_issue_status`
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
-- Indexes for table `tbl_member_subtasks`
--
ALTER TABLE `tbl_member_subtasks`
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
-- Indexes for table `tbl_milestone_outputs`
--
ALTER TABLE `tbl_milestone_outputs`
  ADD PRIMARY KEY (`milestone_output_id`);

--
-- Indexes for table `tbl_milestone_output_subtasks`
--
ALTER TABLE `tbl_milestone_output_subtasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_milestone_output_tasks`
--
ALTER TABLE `tbl_milestone_output_tasks`
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
-- Indexes for table `tbl_other_budget_lines_timelines`
--
ALTER TABLE `tbl_other_budget_lines_timelines`
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
-- Indexes for table `tbl_output_disaggregation_values`
--
ALTER TABLE `tbl_output_disaggregation_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_output_risks`
--
ALTER TABLE `tbl_output_risks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pages`
--
ALTER TABLE `tbl_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_page_actions`
--
ALTER TABLE `tbl_page_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_page_designations`
--
ALTER TABLE `tbl_page_designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_page_permissions`
--
ALTER TABLE `tbl_page_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_page_sectors`
--
ALTER TABLE `tbl_page_sectors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_partners`
--
ALTER TABLE `tbl_partners`
  ADD PRIMARY KEY (`ptnid`);

--
-- Indexes for table `tbl_partner_roles`
--
ALTER TABLE `tbl_partner_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_password_resets`
--
ALTER TABLE `tbl_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_payments_disbursed`
--
ALTER TABLE `tbl_payments_disbursed`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_payments_request`
--
ALTER TABLE `tbl_payments_request`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `requestid` (`request_id`);

--
-- Indexes for table `tbl_payments_request_details`
--
ALTER TABLE `tbl_payments_request_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_payment_request_comments`
--
ALTER TABLE `tbl_payment_request_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_payment_request_financiers`
--
ALTER TABLE `tbl_payment_request_financiers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_payment_status`
--
ALTER TABLE `tbl_payment_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_permissions`
--
ALTER TABLE `tbl_permissions`
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
-- Indexes for table `tbl_program_of_works`
--
ALTER TABLE `tbl_program_of_works`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_program_of_work_comments`
--
ALTER TABLE `tbl_program_of_work_comments`
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
  ADD PRIMARY KEY (`projid`),
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
-- Indexes for table `tbl_project_direct_cost_plan_onhold_originals`
--
ALTER TABLE `tbl_project_direct_cost_plan_onhold_originals`
  ADD UNIQUE KEY `id` (`id`);

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
-- Indexes for table `tbl_project_evaluation_questions`
--
ALTER TABLE `tbl_project_evaluation_questions`
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
-- Indexes for table `tbl_project_inspection_specification_compliance`
--
ALTER TABLE `tbl_project_inspection_specification_compliance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_mapping`
--
ALTER TABLE `tbl_project_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_milestone`
--
ALTER TABLE `tbl_project_milestone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_milestones`
--
ALTER TABLE `tbl_project_milestones`
  ADD PRIMARY KEY (`milestone_id`);

--
-- Indexes for table `tbl_project_milestone_outputs`
--
ALTER TABLE `tbl_project_milestone_outputs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_milestone_outputs_sites`
--
ALTER TABLE `tbl_project_milestone_outputs_sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_monitoring_checklist`
--
ALTER TABLE `tbl_project_monitoring_checklist`
  ADD PRIMARY KEY (`checklist_id`);

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
-- Indexes for table `tbl_project_output_designs`
--
ALTER TABLE `tbl_project_output_designs`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `tbl_project_payment_plan`
--
ALTER TABLE `tbl_project_payment_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_payment_plan_details`
--
ALTER TABLE `tbl_project_payment_plan_details`
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
-- Indexes for table `tbl_project_sites`
--
ALTER TABLE `tbl_project_sites`
  ADD PRIMARY KEY (`site_id`);

--
-- Indexes for table `tbl_project_specifications`
--
ALTER TABLE `tbl_project_specifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_stages`
--
ALTER TABLE `tbl_project_stages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_stage_actions`
--
ALTER TABLE `tbl_project_stage_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_stage_responsible`
--
ALTER TABLE `tbl_project_stage_responsible`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_team_leave`
--
ALTER TABLE `tbl_project_team_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_team_member_unavailability`
--
ALTER TABLE `tbl_project_team_member_unavailability`
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
-- Indexes for table `tbl_project_tender_details_onhold_originals`
--
ALTER TABLE `tbl_project_tender_details_onhold_originals`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tbl_project_timeline_substage_records`
--
ALTER TABLE `tbl_project_timeline_substage_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_workflow_stage`
--
ALTER TABLE `tbl_project_workflow_stage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_workflow_stage_new`
--
ALTER TABLE `tbl_project_workflow_stage_new`
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
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `tbl_projrisk_categories_old`
--
ALTER TABLE `tbl_projrisk_categories_old`
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
-- Indexes for table `tbl_risk_impact`
--
ALTER TABLE `tbl_risk_impact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_risk_probability`
--
ALTER TABLE `tbl_risk_probability`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_risk_severity`
--
ALTER TABLE `tbl_risk_severity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_risk_strategy`
--
ALTER TABLE `tbl_risk_strategy`
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
-- Indexes for table `tbl_stage_timelines_categories`
--
ALTER TABLE `tbl_stage_timelines_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_standards`
--
ALTER TABLE `tbl_standards`
  ADD PRIMARY KEY (`standard_id`);

--
-- Indexes for table `tbl_standard_categories`
--
ALTER TABLE `tbl_standard_categories`
  ADD PRIMARY KEY (`category_id`);

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
-- Indexes for table `tbl_task_parameters`
--
ALTER TABLE `tbl_task_parameters`
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
-- AUTO_INCREMENT for table `tbl_adp_projects_budget`
--
ALTER TABLE `tbl_adp_projects_budget`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_annual_dev_plan`
--
ALTER TABLE `tbl_annual_dev_plan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_capr_report_conclusion`
--
ALTER TABLE `tbl_capr_report_conclusion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_capr_report_remarks`
--
ALTER TABLE `tbl_capr_report_remarks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `tbl_contractor_password_resets`
--
ALTER TABLE `tbl_contractor_password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_contractor_payment_requests`
--
ALTER TABLE `tbl_contractor_payment_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_contractor_payment_request_comments`
--
ALTER TABLE `tbl_contractor_payment_request_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_contractor_payment_request_details`
--
ALTER TABLE `tbl_contractor_payment_request_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_contract_guarantees`
--
ALTER TABLE `tbl_contract_guarantees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT for table `tbl_designation_permissions`
--
ALTER TABLE `tbl_designation_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_employee_leave_bal`
--
ALTER TABLE `tbl_employee_leave_bal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_escalations`
--
ALTER TABLE `tbl_escalations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `fid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_filetypes`
--
ALTER TABLE `tbl_filetypes`
  MODIFY `ftid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_financiers`
--
ALTER TABLE `tbl_financiers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_financier_status_comments`
--
ALTER TABLE `tbl_financier_status_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_funds_request`
--
ALTER TABLE `tbl_funds_request`
  MODIFY `fid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_general_inspection`
--
ALTER TABLE `tbl_general_inspection`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `tbl_indicator`
--
ALTER TABLE `tbl_indicator`
  MODIFY `indid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_indicator_baseline_survey_forms`
--
ALTER TABLE `tbl_indicator_baseline_survey_forms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `catid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_disaggregations`
--
ALTER TABLE `tbl_indicator_disaggregations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_indicator_disaggregation_types`
--
ALTER TABLE `tbl_indicator_disaggregation_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_indicator_level3_disaggregations`
--
ALTER TABLE `tbl_indicator_level3_disaggregations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_indicator_measurement_variables`
--
ALTER TABLE `tbl_indicator_measurement_variables`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_indicator_measurement_variables_disaggregation_type`
--
ALTER TABLE `tbl_indicator_measurement_variables_disaggregation_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_indicator_output_baseline_values`
--
ALTER TABLE `tbl_indicator_output_baseline_values`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=742;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_inspection_checklist_questions`
--
ALTER TABLE `tbl_inspection_checklist_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_inspection_checklist_topics`
--
ALTER TABLE `tbl_inspection_checklist_topics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_inspection_monitoring_data_origin`
--
ALTER TABLE `tbl_inspection_monitoring_data_origin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_inspection_observations`
--
ALTER TABLE `tbl_inspection_observations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_issue_status`
--
ALTER TABLE `tbl_issue_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_key_results_area`
--
ALTER TABLE `tbl_key_results_area`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_mbrtitle`
--
ALTER TABLE `tbl_mbrtitle`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_measurement_units`
--
ALTER TABLE `tbl_measurement_units`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `tbl_meetings`
--
ALTER TABLE `tbl_meetings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_member_subtasks`
--
ALTER TABLE `tbl_member_subtasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  MODIFY `mgid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_milestone`
--
ALTER TABLE `tbl_milestone`
  MODIFY `msid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `tbl_milestone_certificate`
--
ALTER TABLE `tbl_milestone_certificate`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_milestone_outputs`
--
ALTER TABLE `tbl_milestone_outputs`
  MODIFY `milestone_output_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_milestone_output_subtasks`
--
ALTER TABLE `tbl_milestone_output_subtasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=298;

--
-- AUTO_INCREMENT for table `tbl_milestone_output_tasks`
--
ALTER TABLE `tbl_milestone_output_tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `mid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_monitoringoutput`
--
ALTER TABLE `tbl_monitoringoutput`
  MODIFY `moid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_monitoring_links`
--
ALTER TABLE `tbl_monitoring_links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_monitoring_observations`
--
ALTER TABLE `tbl_monitoring_observations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_msgcomments`
--
ALTER TABLE `tbl_msgcomments`
  MODIFY `mcid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_myprogfunding`
--
ALTER TABLE `tbl_myprogfunding`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_myprogfunding_history`
--
ALTER TABLE `tbl_myprogfunding_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_myprojfunding`
--
ALTER TABLE `tbl_myprojfunding`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tbl_myprojfunding_history`
--
ALTER TABLE `tbl_myprojfunding_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_myprojpartner`
--
ALTER TABLE `tbl_myprojpartner`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_objective_strategy`
--
ALTER TABLE `tbl_objective_strategy`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_other_budget_lines_timelines`
--
ALTER TABLE `tbl_other_budget_lines_timelines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `tbl_output_disaggregation_values`
--
ALTER TABLE `tbl_output_disaggregation_values`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_output_risks`
--
ALTER TABLE `tbl_output_risks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_pages`
--
ALTER TABLE `tbl_pages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `tbl_page_actions`
--
ALTER TABLE `tbl_page_actions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `tbl_page_designations`
--
ALTER TABLE `tbl_page_designations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3480;

--
-- AUTO_INCREMENT for table `tbl_page_permissions`
--
ALTER TABLE `tbl_page_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1324;

--
-- AUTO_INCREMENT for table `tbl_page_sectors`
--
ALTER TABLE `tbl_page_sectors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tbl_partners`
--
ALTER TABLE `tbl_partners`
  MODIFY `ptnid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_partner_roles`
--
ALTER TABLE `tbl_partner_roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_password_resets`
--
ALTER TABLE `tbl_password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_payments_disbursed`
--
ALTER TABLE `tbl_payments_disbursed`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_payments_request`
--
ALTER TABLE `tbl_payments_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_payments_request_details`
--
ALTER TABLE `tbl_payments_request_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_payment_request_comments`
--
ALTER TABLE `tbl_payment_request_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_payment_request_financiers`
--
ALTER TABLE `tbl_payment_request_financiers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_payment_status`
--
ALTER TABLE `tbl_payment_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_permissions`
--
ALTER TABLE `tbl_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_pmdesignation`
--
ALTER TABLE `tbl_pmdesignation`
  MODIFY `moid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=445;

--
-- AUTO_INCREMENT for table `tbl_priorities`
--
ALTER TABLE `tbl_priorities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_procurementmethod`
--
ALTER TABLE `tbl_procurementmethod`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_progdetails`
--
ALTER TABLE `tbl_progdetails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `tbl_progdetails_history`
--
ALTER TABLE `tbl_progdetails_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_programs`
--
ALTER TABLE `tbl_programs`
  MODIFY `progid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_programs_based_budget`
--
ALTER TABLE `tbl_programs_based_budget`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_programs_quarterly_targets`
--
ALTER TABLE `tbl_programs_quarterly_targets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_program_of_works`
--
ALTER TABLE `tbl_program_of_works`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbl_program_of_work_comments`
--
ALTER TABLE `tbl_program_of_work_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `tbl_projects`
--
ALTER TABLE `tbl_projects`
  MODIFY `projid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `tbl_projectstages`
--
ALTER TABLE `tbl_projectstages`
  MODIFY `psid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projects_evaluation`
--
ALTER TABLE `tbl_projects_evaluation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projects_location_targets`
--
ALTER TABLE `tbl_projects_location_targets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projects_performance_report_remarks`
--
ALTER TABLE `tbl_projects_performance_report_remarks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_approved_yearly_budget`
--
ALTER TABLE `tbl_project_approved_yearly_budget`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_cost_funders_share`
--
ALTER TABLE `tbl_project_cost_funders_share`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `tbl_project_details`
--
ALTER TABLE `tbl_project_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `tbl_project_details_history`
--
ALTER TABLE `tbl_project_details_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_direct_cost_plan`
--
ALTER TABLE `tbl_project_direct_cost_plan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=753;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_answers`
--
ALTER TABLE `tbl_project_evaluation_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
-- AUTO_INCREMENT for table `tbl_project_evaluation_questions`
--
ALTER TABLE `tbl_project_evaluation_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_submission`
--
ALTER TABLE `tbl_project_evaluation_submission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_project_evaluation_types`
--
ALTER TABLE `tbl_project_evaluation_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_project_expected_impact_details`
--
ALTER TABLE `tbl_project_expected_impact_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_project_expected_outcome_details`
--
ALTER TABLE `tbl_project_expected_outcome_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_project_expenditure_timeline`
--
ALTER TABLE `tbl_project_expenditure_timeline`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_form_markers`
--
ALTER TABLE `tbl_project_form_markers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_history_results_level_disaggregation`
--
ALTER TABLE `tbl_project_history_results_level_disaggregation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_implementation_method`
--
ALTER TABLE `tbl_project_implementation_method`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_project_inspection_checklist`
--
ALTER TABLE `tbl_project_inspection_checklist`
  MODIFY `ckid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_inspection_checklist_comments`
--
ALTER TABLE `tbl_project_inspection_checklist_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `tbl_project_inspection_specification_compliance`
--
ALTER TABLE `tbl_project_inspection_specification_compliance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_project_mapping`
--
ALTER TABLE `tbl_project_mapping`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_project_milestone`
--
ALTER TABLE `tbl_project_milestone`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbl_project_milestones`
--
ALTER TABLE `tbl_project_milestones`
  MODIFY `milestone_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_milestone_outputs`
--
ALTER TABLE `tbl_project_milestone_outputs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `tbl_project_milestone_outputs_sites`
--
ALTER TABLE `tbl_project_milestone_outputs_sites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_project_monitoring_checklist`
--
ALTER TABLE `tbl_project_monitoring_checklist`
  MODIFY `checklist_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tbl_project_monitoring_checklist_noncompliance_comments`
--
ALTER TABLE `tbl_project_monitoring_checklist_noncompliance_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_monitoring_checklist_score`
--
ALTER TABLE `tbl_project_monitoring_checklist_score`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_project_other_cost_plan`
--
ALTER TABLE `tbl_project_other_cost_plan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_outcome_evaluation_questions`
--
ALTER TABLE `tbl_project_outcome_evaluation_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_outputs`
--
ALTER TABLE `tbl_project_outputs`
  MODIFY `opid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_outputs_mne_details`
--
ALTER TABLE `tbl_project_outputs_mne_details`
  MODIFY `opid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_project_output_designs`
--
ALTER TABLE `tbl_project_output_designs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_project_output_details`
--
ALTER TABLE `tbl_project_output_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `tbl_project_output_details_history`
--
ALTER TABLE `tbl_project_output_details_history`
  MODIFY `odid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_output_diss_resp`
--
ALTER TABLE `tbl_project_output_diss_resp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_payment_plan`
--
ALTER TABLE `tbl_project_payment_plan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tbl_project_payment_plan_details`
--
ALTER TABLE `tbl_project_payment_plan_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbl_project_photos`
--
ALTER TABLE `tbl_project_photos`
  MODIFY `fid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_results_level_disaggregation`
--
ALTER TABLE `tbl_project_results_level_disaggregation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_riskscore`
--
ALTER TABLE `tbl_project_riskscore`
  MODIFY `scid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_project_sites`
--
ALTER TABLE `tbl_project_sites`
  MODIFY `site_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `tbl_project_specifications`
--
ALTER TABLE `tbl_project_specifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=553;

--
-- AUTO_INCREMENT for table `tbl_project_stages`
--
ALTER TABLE `tbl_project_stages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_stage_actions`
--
ALTER TABLE `tbl_project_stage_actions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `tbl_project_stage_responsible`
--
ALTER TABLE `tbl_project_stage_responsible`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_project_team_leave`
--
ALTER TABLE `tbl_project_team_leave`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_team_member_unavailability`
--
ALTER TABLE `tbl_project_team_member_unavailability`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_team_roles`
--
ALTER TABLE `tbl_project_team_roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_project_tender_details`
--
ALTER TABLE `tbl_project_tender_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=459;

--
-- AUTO_INCREMENT for table `tbl_project_timeline_substage_records`
--
ALTER TABLE `tbl_project_timeline_substage_records`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_workflow_stage`
--
ALTER TABLE `tbl_project_workflow_stage`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_project_workflow_stage_new`
--
ALTER TABLE `tbl_project_workflow_stage_new`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_project_workflow_stage_old`
--
ALTER TABLE `tbl_project_workflow_stage_old`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_workflow_stage_timelines`
--
ALTER TABLE `tbl_project_workflow_stage_timelines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_projfunding`
--
ALTER TABLE `tbl_projfunding`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_projfunding_history`
--
ALTER TABLE `tbl_projfunding_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projissues`
--
ALTER TABLE `tbl_projissues`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_projissues_discussions`
--
ALTER TABLE `tbl_projissues_discussions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_projissue_comments`
--
ALTER TABLE `tbl_projissue_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_projissue_severity`
--
ALTER TABLE `tbl_projissue_severity`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_projmembers`
--
ALTER TABLE `tbl_projmembers`
  MODIFY `pmid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;

--
-- AUTO_INCREMENT for table `tbl_projmemoffices`
--
ALTER TABLE `tbl_projmemoffices`
  MODIFY `moid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projrisk_categories`
--
ALTER TABLE `tbl_projrisk_categories`
  MODIFY `catid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_projrisk_categories_old`
--
ALTER TABLE `tbl_projrisk_categories_old`
  MODIFY `rskid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_projrisk_response`
--
ALTER TABLE `tbl_projrisk_response`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_projstage_responsible`
--
ALTER TABLE `tbl_projstage_responsible`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_projstatuschangereason`
--
ALTER TABLE `tbl_projstatuschangereason`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `ptid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `tbl_projtypelist`
--
ALTER TABLE `tbl_projtypelist`
  MODIFY `projtypeid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_public_feedback`
--
ALTER TABLE `tbl_public_feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_qapr_report_conclusion`
--
ALTER TABLE `tbl_qapr_report_conclusion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_qapr_report_remarks`
--
ALTER TABLE `tbl_qapr_report_remarks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_risk_impact`
--
ALTER TABLE `tbl_risk_impact`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_risk_probability`
--
ALTER TABLE `tbl_risk_probability`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_risk_severity`
--
ALTER TABLE `tbl_risk_severity`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_risk_strategy`
--
ALTER TABLE `tbl_risk_strategy`
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
  MODIFY `stid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tbl_settings_menu`
--
ALTER TABLE `tbl_settings_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sidebar_menu`
--
ALTER TABLE `tbl_sidebar_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;

--
-- AUTO_INCREMENT for table `tbl_stage_timelines_categories`
--
ALTER TABLE `tbl_stage_timelines_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_standards`
--
ALTER TABLE `tbl_standards`
  MODIFY `standard_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_standard_categories`
--
ALTER TABLE `tbl_standard_categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_strategic_objective_targets_threshold`
--
ALTER TABLE `tbl_strategic_objective_targets_threshold`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_strategic_plan_objectives`
--
ALTER TABLE `tbl_strategic_plan_objectives`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_strategic_plan_objective_targets`
--
ALTER TABLE `tbl_strategic_plan_objective_targets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_strategic_plan_op_indicator_budget`
--
ALTER TABLE `tbl_strategic_plan_op_indicator_budget`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_strategic_plan_op_indicator_targets`
--
ALTER TABLE `tbl_strategic_plan_op_indicator_targets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_survey_conclusion`
--
ALTER TABLE `tbl_survey_conclusion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_system_modules`
--
ALTER TABLE `tbl_system_modules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_task`
--
ALTER TABLE `tbl_task`
  MODIFY `tkid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=380;

--
-- AUTO_INCREMENT for table `tbl_taskstatus`
--
ALTER TABLE `tbl_taskstatus`
  MODIFY `tsid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_task_inspection_status`
--
ALTER TABLE `tbl_task_inspection_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_task_parameters`
--
ALTER TABLE `tbl_task_parameters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `tbl_task_progress`
--
ALTER TABLE `tbl_task_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_task_status`
--
ALTER TABLE `tbl_task_status`
  MODIFY `statusid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_tenderdetails`
--
ALTER TABLE `tbl_tenderdetails`
  MODIFY `td_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_titles`
--
ALTER TABLE `tbl_titles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
