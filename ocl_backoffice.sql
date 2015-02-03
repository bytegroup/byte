-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 02, 2015 at 08:03 পূর্বাহ্ণ
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ocl_backoffice`
--

-- --------------------------------------------------------

--
-- Table structure for table `ocl_admins`
--

CREATE TABLE IF NOT EXISTS `ocl_admins` (
  `adminId` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permissions` text NOT NULL,
  `creatorId` int(11) NOT NULL,
  `createDate` varchar(255) NOT NULL,
  `editorId` int(11) NOT NULL,
  `editDate` varchar(255) NOT NULL,
  PRIMARY KEY (`adminId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `ocl_admins`
--

INSERT INTO `ocl_admins` (`adminId`, `userName`, `password`, `permissions`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(2, 'dsi', 'dsi', 'HEADER_Staff;canStaffView;canStaffAdd;canStaffEdit;canStaffDelete;HEADER_Admin;canSetAdminPermissions;canAdminView;canAdminAdd;canAdminEdit;canAdminDelete;HEADER_IT-Inventory;canIT-InventoryView;canIT-InventoryAdd;canIT-InventoryEdit;canIT-InventoryDelete;HEADER_Report;canReportView;canReportExport', 23, '02-01-15 :: 11:20 am', 23, '02-01-15 :: 11:20 am'),
(3, 'mamun1', 'mamun', 'test', 26, '05-12-14 :: 09:47 am', 26, '05-12-14 :: 09:47 am'),
(23, 'mamun5', 'mamun', 'testdegdfgd', 8, '05-12-14 :: 02:52 pm', 8, '05-12-14 :: 02:52 pm'),
(29, 'test', 'test', 'canManageStuffs;canSetAdminPermissions;canDelete', 6, '08-12-14 :: 03:51 pm', 6, '08-12-14 :: 03:51 pm'),
(30, 'test1', 'test', 'canAdd;canView;canEdit', 7, '09-12-14 :: 08:21 am', 7, '09-12-14 :: 08:21 am'),
(31, 'test2', 'test', 'canManageStuffs;canAdd;canEdit', 8, '09-12-14 :: 12:30 pm', 8, '09-12-14 :: 12:30 pm'),
(32, 'test3', 'test', 'canView;canEdit;canDelete', 11, '10-12-14 :: 10:20 am', 11, '10-12-14 :: 10:20 am'),
(33, 'tesst', 'testt', 'HEADER_Staff;canStaffAdd;canStaffEdit;HEADER_Admin;canAdminEdit;canAdminDelete;canIT-InventoryAdd', 26, '18-12-14 :: 01:15 pm', 26, '18-12-14 :: 01:15 pm'),
(34, 'tttttt', 'tttttt', 'HEADER_Staff;canStaffView;canStaffDelete;HEADER_IT-Inventory;canIT-InventoryView;canIT-InventoryEdit;canIT-InventoryDelete', 10, '18-12-14 :: 02:40 pm', 10, '18-12-14 :: 02:40 pm'),
(35, 'abc', 'pass1234', 'HEADER_Staff;canStaffAdd;HEADER_Admin;canAdminView', 27, '19-12-14 :: 03:07 pm', 27, '19-12-14 :: 03:07 pm');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_approve_quotation`
--

CREATE TABLE IF NOT EXISTS `ocl_approve_quotation` (
  `approveQuotationId` int(8) NOT NULL AUTO_INCREMENT,
  `quotationId` int(8) NOT NULL,
  `userId` int(8) NOT NULL COMMENT 'approveBy',
  `approveDate` date NOT NULL,
  `approvalAttachment` varchar(255) NOT NULL,
  `approveDetails` text NOT NULL,
  `creatorId` int(8) NOT NULL,
  `createDate` varchar(100) NOT NULL,
  `editorId` int(8) NOT NULL,
  `editDate` varchar(100) NOT NULL,
  PRIMARY KEY (`approveQuotationId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `ocl_approve_quotation`
--

INSERT INTO `ocl_approve_quotation` (`approveQuotationId`, `quotationId`, `userId`, `approveDate`, `approvalAttachment`, `approveDetails`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(3, 75, 23, '2014-12-30', 'b1132-8716105.pdf', '', 0, '', 23, '12-01-15 :: 12:58 pm'),
(4, 78, 26, '2014-12-30', '', '', 0, '', 0, ''),
(5, 81, 23, '2015-01-28', '', 'sadsada', 23, '26-01-15 :: 12:35 pm', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_bill`
--

CREATE TABLE IF NOT EXISTS `ocl_bill` (
  `billId` int(5) NOT NULL AUTO_INCREMENT,
  `billNumber` varchar(20) NOT NULL,
  `receiveId` int(8) NOT NULL,
  `budgetId` int(5) DEFAULT NULL,
  `billReceiveDate` date DEFAULT NULL,
  `billingDate` date DEFAULT NULL,
  `billType` varchar(50) NOT NULL,
  `billAmount` int(100) NOT NULL,
  `billPaymentType` varchar(50) NOT NULL,
  `billCheckedById` int(8) DEFAULT NULL,
  `billSubmittedById` int(8) DEFAULT NULL,
  `billPaymentById` int(8) NOT NULL,
  `billDescription` text,
  `creatorId` int(8) DEFAULT NULL,
  `createDate` varchar(50) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`billId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Dumping data for table `ocl_bill`
--

INSERT INTO `ocl_bill` (`billId`, `billNumber`, `receiveId`, `budgetId`, `billReceiveDate`, `billingDate`, `billType`, `billAmount`, `billPaymentType`, `billCheckedById`, `billSubmittedById`, `billPaymentById`, `billDescription`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(52, '1001/bill/15/52', 145, 5, '2015-01-30', '2015-01-30', 'Product Purchase Bill', 12000, 'Cheque', 17, 26, 23, 'test', 23, '30-01-15 :: 07:08 pm', 23, '02-02-15 :: 12:21 pm'),
(53, '1001/bill/15/53', 144, 5, '2015-02-02', '2015-02-02', 'Product Purchase Bill', 1100, 'Cash', 17, 17, 0, 'test', 23, '02-02-15 :: 12:21 pm', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_budget`
--

CREATE TABLE IF NOT EXISTS `ocl_budget` (
  `budgetId` int(5) NOT NULL AUTO_INCREMENT,
  `companyId` int(8) DEFAULT NULL,
  `budgetHead` varchar(100) NOT NULL,
  `budgetYear` year(4) DEFAULT NULL,
  `budgetType` varchar(100) NOT NULL,
  `budgetQuantity` int(8) DEFAULT NULL,
  `budgetAmount` double(20,2) NOT NULL DEFAULT '0.00',
  `budgetUtilization` double(20,2) DEFAULT '0.00',
  `budgetDescription` text NOT NULL,
  `creatorId` int(8) DEFAULT NULL,
  `createDate` varchar(50) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`budgetId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `ocl_budget`
--

INSERT INTO `ocl_budget` (`budgetId`, `companyId`, `budgetHead`, `budgetYear`, `budgetType`, `budgetQuantity`, `budgetAmount`, `budgetUtilization`, `budgetDescription`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(1, 1, '', 2014, '', 100000, 0.00, 313266.00, 'undefined', 8, '2014-10-15', NULL, NULL),
(2, 1, '', 2014, '', 10000, 0.00, 3619.00, 'undefined', 8, '2014-10-15', NULL, NULL),
(3, 1, '', 2014, '', 20, 0.00, 0.00, 'This is  a test budget', 8, '2014-11-25', NULL, NULL),
(4, 17, 'tstB', 2015, 'Capital', 10, 1200000.00, 0.00, 'test purpose', 23, '0000-00-00', 23, '28-01-15 :: 07:34 pm'),
(5, 17, 'tstB2', 2015, 'Capital', 12, 120000.00, 13100.00, 'test purpost', 23, '0000-00-00', 23, '29-01-15 :: 06:07 pm'),
(6, 17, 'tstB1', 2015, 'Capital', 12, 1200000.00, 0.00, 'test purpost', 23, '0000-00-00', 23, '27-01-15 :: 06:35 pm'),
(7, 17, 'tstB3', 2015, 'Capital', 33, 21000.00, 0.00, 'test purpose', 23, '29-01-15 :: 06:08 pm', 23, '30-01-15 :: 02:23 pm'),
(8, 17, 'Antivirus', 2015, 'Capital', 165, 234000.00, 0.00, 'adfadsaf', 23, '02-02-15 :: 12:38 pm', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_budget_detail`
--

CREATE TABLE IF NOT EXISTS `ocl_budget_detail` (
  `budgetId` int(5) DEFAULT NULL,
  `budgetQuantity` int(100) DEFAULT NULL,
  `budgetConsumedQuantity` int(100) DEFAULT NULL,
  `categoryId` int(5) DEFAULT NULL,
  `budgetDetailId` int(5) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`budgetDetailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `ocl_budget_detail`
--

INSERT INTO `ocl_budget_detail` (`budgetId`, `budgetQuantity`, `budgetConsumedQuantity`, `categoryId`, `budgetDetailId`) VALUES
(79, 7, NULL, 1, 1),
(79, 8, NULL, 6, 2),
(80, 5, NULL, 1, 3),
(80, 4, NULL, 7, 4),
(81, 1, NULL, 1, 5),
(82, 1, NULL, 4, 6),
(83, 1, NULL, 1, 7),
(84, 1, NULL, 1, 8),
(85, 1, NULL, 1, 9),
(86, 1, NULL, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_categories`
--

CREATE TABLE IF NOT EXISTS `ocl_categories` (
  `categoryId` int(5) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(255) NOT NULL,
  `categoryDescription` text NOT NULL,
  `active` varchar(10) DEFAULT NULL,
  `categoryCode` varchar(50) DEFAULT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` datetime DEFAULT NULL,
  PRIMARY KEY (`categoryId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `ocl_categories`
--

INSERT INTO `ocl_categories` (`categoryId`, `categoryName`, `categoryDescription`, `active`, `categoryCode`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(1, 'Monitor', 'Lcd led', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'LCD Monitor', 'Monitor', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'CRT Monitor', 'Monitor', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'UPS', '600VA offline UPS', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'UPS', '1000VA Online UPS', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'K-Board', 'USB', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Mouse', 'USB', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Mouse', 'Ps2', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Mouse', 'Wirless', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Laptop', 'Dell laptop', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Tablet', 'Samsung tablet', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'Computer', '', 'Active', NULL, NULL, NULL, NULL, NULL),
(14, 'test', 'test category ', 'Active', 'tst', 23, '0000-00-00 00:00:00', 23, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_companies`
--

CREATE TABLE IF NOT EXISTS `ocl_companies` (
  `companyId` int(5) NOT NULL AUTO_INCREMENT,
  `companyName` varchar(255) NOT NULL,
  `organizationId` int(5) NOT NULL,
  `companyDescription` text,
  `companyAddress` longtext,
  `companyCode` varchar(255) DEFAULT NULL,
  `companyEmail` varchar(255) DEFAULT NULL,
  `companyPhone` varchar(255) DEFAULT NULL,
  `companyFax` varchar(255) DEFAULT NULL,
  `companyWebSite` varchar(255) DEFAULT NULL,
  `companyRoundSeal` varchar(255) DEFAULT NULL,
  `companyLogo` varchar(255) DEFAULT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` varchar(255) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(255) DEFAULT NULL,
  `active` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`companyId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `ocl_companies`
--

INSERT INTO `ocl_companies` (`companyId`, `companyName`, `organizationId`, `companyDescription`, `companyAddress`, `companyCode`, `companyEmail`, `companyPhone`, `companyFax`, `companyWebSite`, `companyRoundSeal`, `companyLogo`, `creatorId`, `createDate`, `editorId`, `editDate`, `active`) VALUES
(17, 'SAPL', 2, 'test', 'Ctg', '1001', 'test@gmail.com', '012345', '023456', 'www.test.com', NULL, NULL, NULL, NULL, 23, '19-12-14 :: 02:04 pm', 'Active'),
(27, 'test', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 23, '19-12-14 :: 02:46 pm', NULL, NULL, 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_damage`
--

CREATE TABLE IF NOT EXISTS `ocl_damage` (
  `damageId` int(5) NOT NULL AUTO_INCREMENT,
  `damageDetails` text,
  `damageRemarks` text,
  `damageType` varchar(20) NOT NULL,
  `departmentId` int(5) DEFAULT NULL,
  `organizationId` int(5) DEFAULT NULL,
  `vendorsId` int(5) DEFAULT NULL,
  `damageQuantity` int(100) NOT NULL,
  `receiveDate` datetime DEFAULT NULL,
  `damageDate` datetime DEFAULT NULL,
  `checkedById` int(5) DEFAULT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` datetime DEFAULT NULL,
  `warrantyEndDate` datetime DEFAULT NULL,
  `stockId` int(5) DEFAULT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  PRIMARY KEY (`damageId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `ocl_damage`
--

INSERT INTO `ocl_damage` (`damageId`, `damageDetails`, `damageRemarks`, `damageType`, `departmentId`, `organizationId`, `vendorsId`, `damageQuantity`, `receiveDate`, `damageDate`, `checkedById`, `creatorId`, `createDate`, `editorId`, `editDate`, `warrantyEndDate`, `stockId`, `itemMasterId`) VALUES
(6, '<p>\r\n	Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;</p>\r\n<p>\r\n	Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;Test purpose.&nbsp;</p>\r\n', '<p>\r\n	Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;</p>\r\n<p>\r\n	Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;</p>\r\n<p>\r\n	Also test purpose.&nbsp;Also test purpose.&nbsp;Also test purpose.&nbsp;</p>\r\n', 'Non-Repairable', 6, 1, 3, 0, '2014-11-14 17:24:09', '2014-11-14 00:00:00', 0, 8, NULL, NULL, NULL, '2014-11-29 11:19:00', NULL, 9),
(7, NULL, NULL, 'Non-Repairable', 6, 1, 3, 1, NULL, '0000-00-00 00:00:00', 2, 8, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL),
(8, NULL, NULL, 'Non-Repairable', 6, 1, 3, 1, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL),
(9, NULL, NULL, 'Non-Repairable', 6, 1, 3, 1, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL),
(10, NULL, NULL, 'Non-Repairable', 6, 1, 3, 1, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL),
(11, NULL, NULL, 'Non-Repairable', 6, 1, 3, 1, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(12, NULL, NULL, 'Non-Repairable', 6, 1, 3, 0, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(13, NULL, NULL, 'Non-Repairable', 6, 1, 3, 0, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(14, NULL, NULL, 'Non-Repairable', 6, 1, 3, 0, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(15, NULL, NULL, 'Non-Repairable', 6, 1, 3, 1, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '2014-10-15 06:00:00', NULL, NULL),
(16, NULL, NULL, 'Non-Repairable', 6, 1, 3, 2, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(17, NULL, NULL, 'Non-Repairable', 6, 1, 3, 2, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(18, NULL, NULL, 'Repairable', 2, 1, 3, 0, NULL, '2014-10-28 00:00:00', 1, 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(19, NULL, NULL, 'Repairable', 2, 1, 3, 2, NULL, '0000-00-00 00:00:00', 6, 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(20, NULL, NULL, 'Repairable', 2, 1, 3, 2, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '2014-10-22 00:00:00', NULL, NULL),
(21, NULL, NULL, 'Repairable', 2, 1, 3, 5, NULL, '2014-10-27 00:00:00', 0, 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(22, NULL, NULL, 'Repairable', 2, 1, 3, 2, NULL, '0000-00-00 00:00:00', 6, 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(23, NULL, NULL, 'Repairable', 2, 1, 3, 1, NULL, '2014-10-27 00:00:00', 8, 8, NULL, NULL, NULL, '2014-10-22 00:00:00', NULL, NULL),
(24, NULL, NULL, 'Repairable', 2, 1, 3, 1, NULL, '0000-00-00 00:00:00', 6, 8, NULL, NULL, NULL, '2014-10-24 08:52:04', NULL, NULL),
(25, NULL, NULL, 'Repairable', 2, 1, 3, 1, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '2014-10-23 11:01:51', NULL, NULL),
(26, NULL, NULL, 'Repairable', 2, 1, 3, 1, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '2014-10-15 14:38:31', NULL, NULL),
(27, NULL, NULL, 'Repairable', 2, 1, 3, 1, NULL, '2014-10-28 00:00:00', 7, 8, NULL, NULL, NULL, '2014-10-27 00:00:00', NULL, NULL),
(28, NULL, NULL, 'Repairable', 2, 1, 3, 2, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '2014-10-27 00:00:00', 250, NULL),
(29, NULL, NULL, 'Repairable', 2, 1, 3, 3, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '2014-10-27 00:00:00', 250, 5),
(30, NULL, NULL, 'Repairable', 2, 1, 3, 2, NULL, '0000-00-00 00:00:00', 0, 8, NULL, NULL, NULL, '2014-10-27 00:00:00', 250, 5),
(31, 'test', 'test', 'Repairable', NULL, NULL, NULL, 1, NULL, '2015-01-01 12:04:21', 23, NULL, NULL, 23, '0000-00-00 00:00:00', NULL, 212, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_departments`
--

CREATE TABLE IF NOT EXISTS `ocl_departments` (
  `departmentId` int(5) NOT NULL AUTO_INCREMENT,
  `departmentName` varchar(255) NOT NULL,
  `departmentCode` varchar(255) NOT NULL,
  `organizationId` int(5) NOT NULL,
  `companyId` int(11) NOT NULL,
  `departmentDescription` longtext NOT NULL,
  `departmentEmail` varchar(255) NOT NULL,
  `departmentPhone` varchar(255) NOT NULL,
  `active` varchar(10) DEFAULT NULL,
  `sectionName` varchar(50) DEFAULT NULL,
  `HODUserId` int(5) DEFAULT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` varchar(255) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`departmentId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `ocl_departments`
--

INSERT INTO `ocl_departments` (`departmentId`, `departmentName`, `departmentCode`, `organizationId`, `companyId`, `departmentDescription`, `departmentEmail`, `departmentPhone`, `active`, `sectionName`, `HODUserId`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(2, 'IT ', 'rrr', 2, 17, 'it related', 'it@ocl.com', '222', 'Active', NULL, 23, NULL, NULL, 23, '12-12-14 :: 12:54 pm'),
(3, 'IT-SAPL', '', 2, 0, 'Sapl it related', 'it@sapl.com', '4545', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Accounts', '', 1, 0, 'Accounts Related', 'Accounts@saplbd.com', '195', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Documents', 'test', 2, 17, 'cfs report related', 'doc@saplbd.com', '112', 'Active', NULL, 23, NULL, NULL, 23, '12-12-14 :: 12:17 pm'),
(7, 'CFS Shed', '', 1, 0, 'cfs related', 'cfs@saplbd.com', '111', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Customs', 'sapl-company', 2, 17, 'customs related', 'customs@saplbd.com', '01723111333', 'Active', NULL, 26, NULL, NULL, 23, '12-12-14 :: 06:36 am'),
(9, 'ECD', '', 1, 0, 'Equipment Container Depo related', 'ecd@saplbd.com', '136', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'ICD', '', 1, 0, 'Inland Container Depo', 'icd@saplbd.com', '118', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'HED', '', 1, 0, 'Heavy Equipment related', 'hed@saplbd.com', '132', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'MDD', '', 1, 0, 'Maintenance and Development related', 'mdd@saplbd.com', '123', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'Electrical', '', 1, 0, 'Electrical related', 'electrical@oclbd.com', '140', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'Dep 1', 'dept.sapl', 17, 0, 'test', 'test@gmail.com', '012345', 'Active', 'test', 122, NULL, NULL, 23, '0000-00-00 00:00:00'),
(15, 'Dep 2', '', 3, 0, 'test', 'test@gmail.com', '0123456', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, ' testde', ' testde', 1, 25, '', '', '', 'Active', NULL, NULL, 23, '19-12-14 :: 02:18 pm', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_designations`
--

CREATE TABLE IF NOT EXISTS `ocl_designations` (
  `designationId` int(5) NOT NULL AUTO_INCREMENT,
  `designationName` varchar(255) DEFAULT NULL,
  `designationGrade` varchar(255) DEFAULT NULL,
  `designationRank` int(5) NOT NULL,
  `designationDescription` text NOT NULL,
  `active` varchar(10) DEFAULT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` varchar(100) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`designationId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ocl_designations`
--

INSERT INTO `ocl_designations` (`designationId`, `designationName`, `designationGrade`, `designationRank`, `designationDescription`, `active`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(1, 'Developer', '1001', 0, 'Test Developer', 'Active', NULL, NULL, 23, '10-12-14 :: 03:15 pm'),
(3, 'IT Manager', '1002', 0, '', 'Active', NULL, NULL, NULL, NULL),
(4, 'test', 'test', 0, 'test', 'Active', 23, '0000-00-00 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_issues`
--

CREATE TABLE IF NOT EXISTS `ocl_issues` (
  `issueDescription` text,
  `stockId` int(5) DEFAULT NULL,
  `departmentId` int(5) DEFAULT NULL,
  `companyId` int(8) NOT NULL,
  `organizationId` int(5) DEFAULT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `issueDate` date DEFAULT NULL,
  `issueQuantity` int(8) DEFAULT NULL,
  `issueTo` varchar(50) NOT NULL,
  `issueUserId` int(5) DEFAULT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `issueId` int(5) NOT NULL AUTO_INCREMENT,
  `productCode` varchar(50) DEFAULT NULL,
  `createDate` varchar(100) NOT NULL,
  `editorId` int(8) NOT NULL,
  `editDate` varchar(100) NOT NULL,
  PRIMARY KEY (`issueId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

--
-- Dumping data for table `ocl_issues`
--

INSERT INTO `ocl_issues` (`issueDescription`, `stockId`, `departmentId`, `companyId`, `organizationId`, `itemMasterId`, `issueDate`, `issueQuantity`, `issueTo`, `issueUserId`, `creatorId`, `issueId`, `productCode`, `createDate`, `editorId`, `editDate`) VALUES
('test', NULL, 6, 0, 1, NULL, '2014-09-25', 1, '', 8, 8, 1, NULL, '', 0, ''),
('undefined', NULL, 6, 0, 1, NULL, '2014-10-09', 1, '', NULL, 8, 2, NULL, '', 0, ''),
('undefined', 211, 6, 0, 1, NULL, '2014-10-09', 1, '', NULL, 8, 3, NULL, '', 0, ''),
('undefined', 211, 6, 0, 1, NULL, '2014-10-09', 1, '', NULL, 8, 4, NULL, '', 0, ''),
('undefined', 211, 6, 0, 1, NULL, '2014-10-09', 1, '', NULL, 8, 5, NULL, '', 0, ''),
('test', 211, 6, 0, 1, NULL, '2014-10-09', 1, '', 2, 8, 6, NULL, '', 0, ''),
('undefined', 211, 6, 0, 1, NULL, '2014-10-09', 1, '', NULL, 8, 7, NULL, '', 0, ''),
('undefined', 211, 6, 0, 1, NULL, '2014-10-09', 2, '', NULL, 8, 8, NULL, '', 0, ''),
('undefined', 211, 6, 0, 1, NULL, '2014-10-09', 2, '', NULL, 8, 9, NULL, '', 0, ''),
('undefined', 211, 6, 0, 1, NULL, '2014-10-09', 2, '', NULL, 8, 10, NULL, '', 0, ''),
('undefined', 211, 6, 0, 1, NULL, '2014-10-09', 2, '', NULL, 8, 11, NULL, '', 0, ''),
('undefined', 211, 6, 0, 1, NULL, '2014-10-09', 2, '', NULL, 8, 12, NULL, '', 0, ''),
('undefined', 210, 6, 0, 1, NULL, '2014-10-09', 8, '', NULL, 8, 13, NULL, '', 0, ''),
('undefined', 209, 6, 0, 1, NULL, '2014-10-09', 3, '', NULL, 8, 14, NULL, '', 0, ''),
('undefined', 208, 6, 0, 1, NULL, '2014-10-09', 9, '', NULL, 8, 15, NULL, '', 0, ''),
('undefined', 207, 6, 0, 1, NULL, '2014-10-09', 3, '', NULL, 8, 16, NULL, '', 0, ''),
('undefined', 206, 6, 0, 1, NULL, '2014-10-09', 9, '', NULL, 8, 17, NULL, '', 0, ''),
('test', 204, 6, 0, 1, NULL, '2014-10-09', 9, '', 2, 8, 18, NULL, '', 0, ''),
('undefined', 203, 6, 0, 1, NULL, '2014-10-09', 2, '', NULL, 8, 19, NULL, '', 0, ''),
('test', 202, 6, 0, 1, NULL, '2014-10-09', 7, '', 3, 8, 20, NULL, '', 0, ''),
('test', 190, 6, 0, 1, 4, '2014-10-09', 1, '', 5, 8, 21, NULL, '', 0, ''),
('undefined', 205, 6, 0, 1, 4, '2014-10-10', 1, '', 3, 8, 22, NULL, '', 0, ''),
('undefined', 191, 6, 0, 1, 3, '2014-10-10', 1, '', NULL, 8, 23, NULL, '', 0, ''),
('undefined', 276, 2, 0, 1, 7, '2014-11-05', 2, '', 1, 8, 24, 'CPU/1', '', 0, ''),
('undefined', 276, 2, 0, 1, 7, '2014-11-05', 2, '', 1, 8, 25, 'CPU/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 26, 'HDD/2', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 1, '', NULL, 8, 27, 'HDD/4', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 2, '', NULL, 8, 28, 'HDD/6', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 29, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 1, '', NULL, 8, 30, 'HDD/3', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 2, '', NULL, 8, 31, 'HDD/5', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 32, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 1, '', NULL, 8, 33, 'HDD/3', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 34, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 35, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 36, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 37, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 38, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 39, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 40, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 41, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 42, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 43, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 44, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-11', 0, '', NULL, 8, 45, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-12', 0, '', NULL, 8, 46, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-12', 0, '', NULL, 8, 47, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-12', 0, '', NULL, 8, 48, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-12', 0, '', NULL, 8, 49, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-12', 0, '', NULL, 8, 50, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-12', 0, '', NULL, 8, 51, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-12', 0, '', NULL, 8, 52, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-12', 0, '', NULL, 8, 53, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-12', 0, '', NULL, 8, 54, 'HDD/1', '', 0, ''),
('undefined', 279, 2, 0, 1, 9, '2014-11-12', 0, '', NULL, 8, 55, 'HDD/1', '', 0, ''),
('undefined', 280, 2, 0, 1, 1, '2014-11-12', 1, '', 1, 8, 56, 'MNT/CRT/10', '', 0, ''),
(NULL, 212, 3, 17, NULL, NULL, NULL, 5, '', 17, 23, 57, NULL, '31-12-14 :: 12:04 pm', 0, ''),
(NULL, 17, 2, 0, NULL, NULL, '2015-01-21', 2, 'Employee', 23, 23, 59, NULL, '21-01-15 :: 04:20 pm', 0, ''),
(NULL, 17, 2, 0, NULL, NULL, '2015-01-21', 1, 'Department', NULL, 23, 60, NULL, '21-01-15 :: 04:48 pm', 23, '26-01-15 :: 04:16 pm'),
(NULL, 16, NULL, 0, NULL, NULL, '2015-01-22', 1, 'Company', NULL, 23, 61, NULL, '22-01-15 :: 05:42 pm', 0, ''),
(NULL, 16, 6, 0, NULL, NULL, '2015-01-23', 2, 'Department', NULL, 23, 63, NULL, '23-01-15 :: 02:39 pm', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_issues_sub`
--

CREATE TABLE IF NOT EXISTS `ocl_issues_sub` (
  `issueSubId` int(5) NOT NULL AUTO_INCREMENT,
  `issueId` int(5) DEFAULT NULL,
  `stockId` int(5) DEFAULT NULL,
  `departmentId` int(5) DEFAULT NULL,
  `organizationId` int(5) DEFAULT NULL,
  `quotationId` int(5) DEFAULT NULL,
  `requisitionId` int(5) DEFAULT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `vendorsId` int(5) DEFAULT NULL,
  `receiveId` int(5) DEFAULT NULL,
  `issueDate` datetime DEFAULT NULL,
  `issueQuantity` int(100) DEFAULT NULL,
  `issueDepartmentId` int(5) DEFAULT NULL,
  `issueUserId` int(5) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `userId` int(5) DEFAULT NULL,
  `stockDetailId` int(5) DEFAULT NULL,
  `productCode` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`issueSubId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ocl_items_master`
--

CREATE TABLE IF NOT EXISTS `ocl_items_master` (
  `itemMasterId` int(8) NOT NULL AUTO_INCREMENT,
  `categoryId` int(8) NOT NULL,
  `unitId` int(5) NOT NULL,
  `itemType` varchar(20) NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `active` varchar(10) DEFAULT NULL,
  `itemCode` varchar(50) DEFAULT NULL,
  `warranty` varchar(50) DEFAULT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` datetime DEFAULT NULL,
  `itemDescription` text,
  `serialNumber` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`itemMasterId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `ocl_items_master`
--

INSERT INTO `ocl_items_master` (`itemMasterId`, `categoryId`, `unitId`, `itemType`, `itemName`, `active`, `itemCode`, `warranty`, `creatorId`, `createDate`, `editorId`, `editDate`, `itemDescription`, `serialNumber`) VALUES
(1, 1, 2, '1', 'CRT', NULL, 'MNT/CRT', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 2, '1', 'LED', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 1, 1, '1', 'Wires', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 1, 4, '1', 'LCD Monitor', NULL, 'MNT/LCD', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 2, 2, '1', 'Monitor', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 10, 2, '1', 'DELL', 'Active', 'Lap/Dell', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 12, 2, '1', 'CPU', 'Active', 'CPU', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 12, 2, '1', 'Keyboard', 'Active', 'Key', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 12, 2, '1', 'HDD', 'Active', 'HDD', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 12, 2, '1', 'Mother board', NULL, 'MBoard', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 12, 2, '1', 'CPU', 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 3, 2, 'Countable', 'test monitor', 'Active', 'tst-mnitr', NULL, NULL, NULL, 23, '0000-00-00 00:00:00', NULL, 'mn-32');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_items_master_sub`
--

CREATE TABLE IF NOT EXISTS `ocl_items_master_sub` (
  `itemMasterSubId` int(5) NOT NULL AUTO_INCREMENT,
  `itemMasterId` int(8) NOT NULL,
  `productId` varchar(30) DEFAULT NULL,
  `active` varchar(10) DEFAULT NULL,
  `productCode` varchar(50) DEFAULT NULL,
  `userId` int(5) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` datetime DEFAULT NULL,
  `itemDescription` varchar(255) DEFAULT NULL,
  `serialNumber` varchar(50) DEFAULT NULL,
  `itemNameSub` varchar(255) NOT NULL,
  PRIMARY KEY (`itemMasterSubId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `ocl_items_master_sub`
--

INSERT INTO `ocl_items_master_sub` (`itemMasterSubId`, `itemMasterId`, `productId`, `active`, `productCode`, `userId`, `createDate`, `editorId`, `editDate`, `itemDescription`, `serialNumber`, `itemNameSub`) VALUES
(6, 5, NULL, 'Active', 'SUB/MNT', NULL, NULL, NULL, NULL, 'Test 2', NULL, 'Power Cable'),
(7, 6, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'HDD'),
(8, 6, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Keyboard'),
(9, 6, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Battery'),
(10, 7, NULL, 'Active', 'HDD', NULL, NULL, NULL, NULL, NULL, NULL, 'HDD'),
(11, 7, NULL, NULL, 'Key', NULL, NULL, NULL, NULL, NULL, NULL, 'Keyboard'),
(12, 7, NULL, NULL, 'MBoard', NULL, NULL, NULL, NULL, NULL, NULL, 'Mother board'),
(13, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'RAM'),
(14, 7, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Monitor');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_item_types`
--

CREATE TABLE IF NOT EXISTS `ocl_item_types` (
  `itemTypeId` int(5) NOT NULL AUTO_INCREMENT,
  `itemTypeName` varchar(200) NOT NULL,
  PRIMARY KEY (`itemTypeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ocl_item_types`
--

INSERT INTO `ocl_item_types` (`itemTypeId`, `itemTypeName`) VALUES
(1, 'Countable'),
(2, 'NonCountable');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_messages`
--

CREATE TABLE IF NOT EXISTS `ocl_messages` (
  `messageId` int(20) NOT NULL AUTO_INCREMENT,
  `messageSenderId` int(20) NOT NULL,
  `messageReceiverId` int(20) NOT NULL,
  `messageSubject` text NOT NULL,
  `messageBody` text NOT NULL,
  `organizationId` int(20) NOT NULL,
  `messageCreated` datetime NOT NULL,
  `messageOpened` enum('Unread','Read') DEFAULT 'Unread',
  PRIMARY KEY (`messageId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ocl_messages`
--

INSERT INTO `ocl_messages` (`messageId`, `messageSenderId`, `messageReceiverId`, `messageSubject`, `messageBody`, `organizationId`, `messageCreated`, `messageOpened`) VALUES
(1, 2, 1, 'asasdas', 'asdawqe', 1, '2012-11-07 02:03:06', 'Unread'),
(2, 4, 1, 'rtyrt', 'rtyrt', 1, '2012-11-08 04:22:32', 'Unread'),
(3, 1, 2, 'qweqwe', 'dfgfgdf', 1, '2012-11-08 02:12:27', 'Unread'),
(4, 2, 4, 'qweqwe', 'sdfsdf', 1, '2012-11-08 15:21:19', 'Unread');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_organizations`
--

CREATE TABLE IF NOT EXISTS `ocl_organizations` (
  `organizationId` int(5) NOT NULL AUTO_INCREMENT,
  `organizationName` varchar(255) NOT NULL,
  `organizationAddress` longtext NOT NULL,
  `organizationPhone` int(100) NOT NULL,
  `organizationEmail` varchar(255) NOT NULL,
  `active` varchar(10) DEFAULT NULL,
  `organizationCode` varchar(50) DEFAULT NULL,
  `organizationFax` varchar(50) DEFAULT NULL,
  `organizationWebSite` varchar(255) DEFAULT NULL,
  `organizationRoundSeal` varchar(255) DEFAULT NULL,
  `organizationLogo` varchar(255) DEFAULT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` varchar(255) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`organizationId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `ocl_organizations`
--

INSERT INTO `ocl_organizations` (`organizationId`, `organizationName`, `organizationAddress`, `organizationPhone`, `organizationEmail`, `active`, `organizationCode`, `organizationFax`, `organizationWebSite`, `organizationRoundSeal`, `organizationLogo`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(1, 'OCL', 'South Patenga, Chittagong', 123456, 'ocl@gmail.com', 'Active', 'ocl', NULL, NULL, NULL, NULL, NULL, NULL, 23, '19-12-14 :: 01:39 pm'),
(2, 'SAPL', 'South Patenga, Chittagong', 321789, 'admin@sapl.org', 'Active', 'sapl', NULL, '', NULL, NULL, 23, '0000-00-00 00:00:00', 23, '0000-00-00 00:00:00'),
(5, 'SAPL EAST', 'South Patenga, Chittagong', 21121, 'test@sapl-east.org', 'Active', 'sapl-east', NULL, '', NULL, NULL, 23, '0000-00-00 00:00:00', NULL, NULL),
(9, 'test', 'test', 0, '', 'Inactive', 'test', NULL, NULL, NULL, NULL, 23, '19-12-14 :: 02:45 pm', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_phonebook`
--

CREATE TABLE IF NOT EXISTS `ocl_phonebook` (
  `contactId` int(5) NOT NULL AUTO_INCREMENT,
  `contactName` varchar(255) NOT NULL,
  `contactAddress` longtext NOT NULL,
  `contactPhoneNo` varchar(255) NOT NULL,
  `contactMobileNo` varchar(255) NOT NULL,
  `contactEmail` varchar(155) NOT NULL,
  `createdUserId` int(5) NOT NULL,
  `contactCreated` datetime NOT NULL,
  PRIMARY KEY (`contactId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ocl_quotations`
--

CREATE TABLE IF NOT EXISTS `ocl_quotations` (
  `quotationId` int(5) NOT NULL AUTO_INCREMENT,
  `quotationTitle` varchar(255) NOT NULL,
  `quotationNumber` varchar(50) NOT NULL,
  `quotationDescription` text NOT NULL,
  `departmentId` int(5) NOT NULL,
  `organizationId` int(5) NOT NULL,
  `requisitionId` int(5) NOT NULL,
  `quotationApproved` tinyint(1) NOT NULL,
  `quotationFile` varchar(100) NOT NULL,
  `vendorsId` int(5) NOT NULL,
  `quotationDate` date NOT NULL,
  `creatorId` int(5) NOT NULL,
  `requisitionNumber` varchar(50) DEFAULT NULL,
  `totalPrice` double DEFAULT NULL,
  `finalTotalPrice` double DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `approvedById` int(5) DEFAULT NULL,
  `approvedDate` datetime DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` datetime DEFAULT NULL,
  PRIMARY KEY (`quotationId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

--
-- Dumping data for table `ocl_quotations`
--

INSERT INTO `ocl_quotations` (`quotationId`, `quotationTitle`, `quotationNumber`, `quotationDescription`, `departmentId`, `organizationId`, `requisitionId`, `quotationApproved`, `quotationFile`, `vendorsId`, `quotationDate`, `creatorId`, `requisitionNumber`, `totalPrice`, `finalTotalPrice`, `createDate`, `approvedById`, `approvedDate`, `editorId`, `editDate`) VALUES
(3, '', '', 'tyutyyg', 3, 1, 50, 1, '', 2, '2012-11-19', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '', '', 'undefined', 2, 1, 51, 1, '', 1, '2013-01-03', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '', '', 'supporting accessories.', 6, 1, 54, 0, '', 3, '2013-07-14', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, '', '', 'undefined', 2, 1, 51, 1, '', 3, '2014-09-18', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, '', '', 'undefined', 2, 1, 51, 1, '', 3, '2014-09-22', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, '', '', 'undefined', 2, 1, 51, 1, '', 4, '2014-09-22', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, '', '', 'undefined', 2, 1, 51, 0, '', 3, '2014-09-22', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, '', '', 'undefined', 2, 1, 51, 0, '', 3, '2014-09-22', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, '', '', 'undefined', 2, 1, 51, 0, '', 3, '2014-09-22', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, '', '', 'undefined', 6, 1, 54, 0, '', 3, '2014-09-22', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, '', '', 'undefined', 2, 1, 55, 0, '', 3, '2014-09-22', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, '', '', 'Test', 2, 1, 55, 0, '', 3, '2014-09-22', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, '', '', 'undefined', 6, 1, 54, 0, '', 3, '2014-10-01', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, '', '', 'undefined', 2, 1, 74, 1, '', 3, '2014-10-10', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, '', '', 'undefined', 2, 1, 67, 1, '', 3, '2014-10-13', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, '', '', 'undefined', 2, 1, 66, 1, '', 3, '2014-10-13', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, '', '', 'undefined', 2, 1, 65, 1, '', 3, '2014-10-13', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, '', '', 'undefined', 2, 1, 64, 1, '', 3, '2014-10-14', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, '', '', 'undefined', 2, 1, 63, 1, '', 3, '2014-10-14', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, '', '', 'undefined', 2, 1, 77, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, '', '', 'undefined', 2, 1, 78, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, '', '', 'undefined', 2, 1, 79, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, '', '', 'undefined', 2, 1, 80, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, '', '', 'undefined', 2, 1, 81, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, '', '', 'undefined', 2, 1, 82, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, '', '', 'undefined', 2, 1, 83, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, '', '', 'undefined', 2, 1, 84, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, '', '', 'undefined', 2, 1, 85, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, '', '', 'undefined', 2, 1, 86, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, '', '', 'undefined', 2, 1, 87, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, '', '', 'undefined', 2, 1, 88, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, '', '', 'undefined', 2, 1, 89, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, '', '', 'undefined', 2, 1, 90, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, '', '', 'undefined', 2, 1, 91, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, '', '', 'undefined', 2, 1, 92, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, '', '', 'undefined', 2, 1, 93, 1, '', 3, '2014-10-29', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, '', '', 'undefined', 2, 1, 94, 1, '', 3, '2014-11-03', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, '', '', 'undefined', 2, 1, 95, 1, '', 3, '2014-11-03', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, '', '', 'undefined', 2, 1, 96, 1, '', 3, '2014-11-03', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, '', '', 'undefined', 2, 1, 97, 1, '', 3, '2014-11-07', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 'test quotation33', '', '<p>\n	undefined</p>\n', 2, 1, 98, 1, '', 3, '2014-11-12', 8, NULL, NULL, NULL, NULL, NULL, NULL, 23, '0000-00-00 00:00:00'),
(75, 'test Quotation298', '', 'this quotation is only for test purpose', 0, 0, 98, 0, '', 7, '2014-12-22', 23, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, 23, '0000-00-00 00:00:00'),
(76, 'test23', '', 'tee', 0, 0, 98, 0, '', 4, '2014-12-23', 23, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, 23, '0000-00-00 00:00:00'),
(77, 'test qq', '', 'test', 0, 0, 98, 0, '', 9, '2014-12-24', 23, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(78, 'Quotation for CRT monitor', '', 'test monitor quotation ', 0, 0, 3, 0, '', 7, '2014-12-31', 23, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(79, 'Quotation2 for CRT monitor', '', '', 0, 0, 3, 0, '', 3, '2014-12-30', 23, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, 23, '0000-00-00 00:00:00'),
(80, 'Quotation3 for CRT monitor', '', '', 0, 0, 3, 0, '', 6, '2014-12-25', 23, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(81, 'test quo1', '1001/QUO/15/81', 'sfdsaf', 0, 0, 99, 0, '', 7, '2015-01-26', 23, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(82, 'test Quotation2', '1001/QUO/15/82', 'sfdsfdsfsdfsd', 0, 0, 99, 0, '', 8, '2015-01-26', 23, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_quotations_detail`
--

CREATE TABLE IF NOT EXISTS `ocl_quotations_detail` (
  `quotaionDetailId` int(10) NOT NULL AUTO_INCREMENT,
  `quotationId` int(5) DEFAULT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `orderedQuantity` int(100) NOT NULL,
  `unitPrice` double NOT NULL,
  `quotationPrice` double NOT NULL,
  PRIMARY KEY (`quotaionDetailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=132 ;

--
-- Dumping data for table `ocl_quotations_detail`
--

INSERT INTO `ocl_quotations_detail` (`quotaionDetailId`, `quotationId`, `itemMasterId`, `orderedQuantity`, `unitPrice`, `quotationPrice`) VALUES
(7, 3, 2, 9, 0, 45000),
(8, 3, 3, 5, 0, 3000),
(9, 4, 1, 34, 0, 300),
(10, 4, 3, 23, 0, 200),
(11, 5, 3, 8, 0, 1600),
(12, 5, 4, 2, 0, 10000),
(13, 6, 1, 34, 0, 0),
(14, 6, 3, 23, 0, 0),
(15, 7, 1, 34, 0, 3),
(16, 7, 3, 23, 0, 4),
(17, 8, 1, 34, 0, 2),
(18, 8, 3, 23, 0, 5),
(19, 9, 1, 34, 0, 5),
(20, 9, 3, 23, 0, 6),
(21, 10, 1, 34, 0, 3),
(22, 10, 3, 23, 0, 3),
(23, 11, 1, 34, 0, 4),
(24, 11, 3, 23, 0, 4),
(25, 12, 3, 8, 0, 5),
(26, 12, 4, 2, 0, 5),
(27, 13, 4, 2, 0, 2),
(28, 14, 4, 2, 0, 3),
(29, 15, 3, 8, 0, 0),
(30, 15, 4, 2, 0, 0),
(31, 16, 1, 10, 0, 1000),
(32, 16, 2, 5, 0, 2000),
(37, 19, 4, 43, 0, 0),
(38, 20, 4, 43, 0, 0),
(39, 21, 4, 43, 0, 0),
(40, 22, 4, 43, 0, 0),
(41, 23, 4, 43, 0, 0),
(89, 53, 1, 2, 0, 200),
(90, 54, 1, 2, 0, 33),
(91, 55, 1, 2, 0, 44),
(92, 56, 1, 2, 0, 222),
(93, 57, 1, 2, 0, 55),
(94, 58, 1, 2, 0, 33),
(95, 59, 1, 1, 0, 222),
(96, 60, 1, 2, 0, 33),
(97, 61, 1, 3, 0, 200),
(98, 61, 4, 2, 0, 600),
(99, 62, 1, 2, 0, 0),
(100, 62, 4, 4, 0, 0),
(101, 63, 1, 1, 0, 4),
(102, 64, 1, 1, 0, 0),
(103, 65, 1, 1, 0, 0),
(104, 66, 1, 1, 0, 0),
(105, 67, 1, 1, 0, 0),
(106, 68, 1, 1, 0, 0),
(107, 69, 1, 1, 0, 0),
(108, 70, 7, 10, 0, 100000),
(109, 71, 6, 10, 0, 100000),
(110, 72, 1, 1, 0, 10000),
(111, 73, 9, 10, 0, 100000),
(113, 76, 12, 5, 341, 1705),
(117, 76, 6, 3, 44, 132),
(118, 75, 6, 3, 3400, 10200),
(119, 77, 6, 3, 44, 132),
(122, 75, 12, 5, 4343, 21715),
(123, 78, 1, 7, 5500, 38500),
(124, 79, 1, 7, 6000, 42000),
(125, 80, 1, 7, 5000, 35000),
(126, 81, 2, 3, 6000, 18000),
(127, 81, 7, 4, 21350, 85400),
(128, 81, 5, 2, 5600, 11200),
(129, 82, 2, 3, 7000, 21000),
(130, 82, 7, 4, 22500, 90000),
(131, 82, 5, 2, 6500, 13000);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_receives`
--

CREATE TABLE IF NOT EXISTS `ocl_receives` (
  `receiveId` int(5) NOT NULL AUTO_INCREMENT,
  `receiveNumber` varchar(50) NOT NULL,
  `receiveDescription` text,
  `userId` int(8) NOT NULL COMMENT 'ReceivedBy',
  `quotationId` int(5) DEFAULT NULL,
  `requisitionId` int(5) DEFAULT NULL,
  `receiveDate` date DEFAULT NULL,
  `receiveQuantity` int(8) NOT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` varchar(100) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`receiveId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=146 ;

--
-- Dumping data for table `ocl_receives`
--

INSERT INTO `ocl_receives` (`receiveId`, `receiveNumber`, `receiveDescription`, `userId`, `quotationId`, `requisitionId`, `receiveDate`, `receiveQuantity`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(108, '', 'undefined', 0, 56, 80, '2014-10-29', 0, 8, NULL, NULL, NULL),
(109, '', 'undefined', 0, 57, 81, '2014-10-29', 0, 8, NULL, NULL, NULL),
(110, '', 'undefined', 0, 58, 82, '2014-10-29', 0, 8, NULL, NULL, NULL),
(111, '', 'undefined', 0, 59, 83, '2014-10-29', 0, 8, NULL, NULL, NULL),
(112, '', 'undefined', 0, 60, 84, '2014-10-29', 0, 8, NULL, NULL, NULL),
(113, '', 'undefined', 0, 61, 85, '2014-10-29', 0, 8, NULL, NULL, NULL),
(114, '', 'undefined', 0, 62, 86, '2014-10-29', 0, 8, NULL, NULL, NULL),
(115, '', 'undefined', 0, 63, 87, '2014-10-29', 0, 8, NULL, NULL, NULL),
(116, '', 'undefined', 0, 64, 88, '2014-10-29', 0, 8, NULL, NULL, NULL),
(117, '', 'undefined', 0, 65, 89, '2014-10-29', 0, 8, NULL, NULL, NULL),
(118, '', 'undefined', 0, 66, 90, '2014-10-29', 0, 8, NULL, NULL, NULL),
(119, '', 'undefined', 0, 67, 91, '2014-10-29', 0, 8, NULL, NULL, NULL),
(120, '', 'undefined', 0, 68, 92, '2014-10-29', 0, 8, NULL, NULL, NULL),
(121, '', 'undefined', 0, 69, 93, '2014-10-29', 0, 8, NULL, NULL, NULL),
(122, '', 'undefined', 0, 70, 94, '2014-11-03', 0, 8, NULL, NULL, NULL),
(123, '', 'undefined', 0, 71, 95, '2014-11-03', 0, 8, NULL, NULL, NULL),
(124, '', 'undefined', 0, 72, 96, '2014-11-03', 0, 8, NULL, NULL, NULL),
(125, '', 'undefined', 0, 73, 97, '2014-11-07', 0, 8, NULL, NULL, NULL),
(127, '', NULL, 23, 75, NULL, '2015-01-12', 0, 23, '12-01-15 :: 06:39 pm', NULL, NULL),
(141, '1001/RECV/15/141', NULL, 23, 75, 98, '2015-01-19', 0, 23, '19-01-15 :: 03:15 pm', 23, '20-01-15 :: 12:28 pm'),
(142, '1001/RECV/15/142', NULL, 17, 75, 98, '2015-01-19', 0, 23, '19-01-15 :: 03:17 pm', NULL, NULL),
(143, '1001/RECV/15/143', NULL, 24, 75, 98, '2015-01-19', 0, 23, '19-01-15 :: 04:07 pm', 23, '20-01-15 :: 12:30 pm'),
(144, '1001/RECV/15/144', NULL, 26, 81, 99, '2015-01-29', 0, 23, '26-01-15 :: 12:35 pm', NULL, NULL),
(145, '1001/RECV/15/145', NULL, 7, 81, 99, '2015-01-29', 0, 23, '26-01-15 :: 12:36 pm', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_receives_detail`
--

CREATE TABLE IF NOT EXISTS `ocl_receives_detail` (
  `receiveDetailId` int(10) NOT NULL AUTO_INCREMENT,
  `receiveId` int(5) DEFAULT NULL,
  `requisitionId` int(8) NOT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `receiveQuantity` int(11) NOT NULL,
  `productCode` varchar(50) NOT NULL,
  `issueId` int(8) NOT NULL,
  `budgetId` int(5) DEFAULT NULL,
  `warrantyEndDate` date DEFAULT NULL,
  PRIMARY KEY (`receiveDetailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=349 ;

--
-- Dumping data for table `ocl_receives_detail`
--

INSERT INTO `ocl_receives_detail` (`receiveDetailId`, `receiveId`, `requisitionId`, `itemMasterId`, `receiveQuantity`, `productCode`, `issueId`, `budgetId`, `warrantyEndDate`) VALUES
(331, 141, 0, 6, 0, '1001/Lap/Dell/331', 61, NULL, '2015-01-26'),
(333, 141, 0, 12, 0, '1001/tst-mnitr/333', 0, NULL, '2015-01-26'),
(334, 142, 0, 6, 0, '1001/Lap/Dell/334', 63, NULL, '2015-01-28'),
(335, 142, 0, 12, 0, '1001/tst-mnitr/335', 0, NULL, '2015-01-28'),
(336, 143, 0, 6, 0, '1001/Lap/Dell/336', 63, NULL, '2015-01-31'),
(337, 143, 0, 12, 0, '1001/tst-mnitr/337', 59, NULL, '2015-01-30'),
(338, 143, 0, 12, 0, '1001/tst-mnitr/338', 59, NULL, '2015-01-30'),
(339, 143, 0, 12, 0, '1001/tst-mnitr/339', 60, NULL, '2015-01-30'),
(340, 144, 0, 2, 0, '1001//340', 0, NULL, '0000-00-00'),
(341, 144, 0, 2, 0, '1001//341', 0, NULL, '0000-00-00'),
(342, 144, 0, 2, 0, '1001//342', 0, NULL, '0000-00-00'),
(343, 144, 0, 7, 0, '1001/CPU/343', 0, NULL, '0000-00-00'),
(344, 144, 0, 7, 0, '1001/CPU/344', 0, NULL, '0000-00-00'),
(345, 144, 0, 7, 0, '1001/CPU/345', 0, NULL, '0000-00-00'),
(346, 145, 0, 7, 0, '1001/CPU/346', 0, NULL, '0000-00-00'),
(347, 145, 0, 5, 0, '1001//347', 0, NULL, '0000-00-00'),
(348, 145, 0, 5, 0, '1001//348', 0, NULL, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_repair`
--

CREATE TABLE IF NOT EXISTS `ocl_repair` (
  `repairId` int(5) NOT NULL AUTO_INCREMENT,
  `repairName` varchar(200) NOT NULL,
  `repairRemarks` text,
  `repairTypeId` int(5) DEFAULT NULL,
  `damageId` int(5) DEFAULT NULL,
  `departmentId` int(5) DEFAULT NULL,
  `organizationId` int(5) DEFAULT NULL,
  `vendorsId` int(5) DEFAULT NULL,
  `repairVendorsId` int(5) DEFAULT NULL,
  `repairQuantity` int(100) NOT NULL,
  `receiveDate` datetime DEFAULT NULL,
  `repairDate` date DEFAULT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` varchar(100) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(100) DEFAULT NULL,
  `warrantyEndDate` datetime DEFAULT NULL,
  `repairDetails` text,
  `status` varchar(255) DEFAULT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `categoryId` int(5) DEFAULT NULL,
  `stockId` int(5) DEFAULT NULL,
  PRIMARY KEY (`repairId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `ocl_repair`
--

INSERT INTO `ocl_repair` (`repairId`, `repairName`, `repairRemarks`, `repairTypeId`, `damageId`, `departmentId`, `organizationId`, `vendorsId`, `repairVendorsId`, `repairQuantity`, `receiveDate`, `repairDate`, `creatorId`, `createDate`, `editorId`, `editDate`, `warrantyEndDate`, `repairDetails`, `status`, `itemMasterId`, `categoryId`, `stockId`) VALUES
(4, '', NULL, NULL, NULL, 6, 1, 3, 5, 1, NULL, '2014-10-15', 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, 'Completed', NULL, NULL, NULL),
(5, '', NULL, 5, NULL, 6, 1, 3, 4, 1, NULL, '2014-10-14', 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL),
(6, '', NULL, 0, 0, 6, 1, 3, 0, 0, NULL, '0000-00-00', 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL),
(7, '', NULL, 0, 6, 6, 1, 3, 0, 0, NULL, '0000-00-00', 8, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL),
(8, '', NULL, 4, 19, 2, 1, 3, 4, 2, NULL, '2014-10-28', 8, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL, NULL, NULL, NULL),
(9, '', NULL, 5, 21, 2, 1, 3, 7, 4, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL, NULL, NULL, NULL),
(10, '', NULL, 0, 22, 2, 1, 3, 0, 2, NULL, '2014-10-27', 8, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL, NULL, NULL, NULL),
(11, '', NULL, 5, 29, 2, 1, 3, 4, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL, NULL, NULL, NULL),
(12, '', NULL, 0, 29, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, 'Completed', 3, NULL, 210),
(13, '', NULL, 0, 30, 2, 1, 3, 0, 1, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, 'Completed', 3, NULL, 210),
(14, '', NULL, 5, 30, 2, 1, 3, 4, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-27 00:00:00', NULL, 'Completed', 5, NULL, 250),
(15, '', NULL, 5, 30, 2, 1, 3, 4, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-27 00:00:00', NULL, 'Completed', 5, NULL, 250),
(16, '', NULL, 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-27 00:00:00', NULL, 'Completed', 5, NULL, 250),
(17, '', NULL, 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-27 00:00:00', NULL, 'Completed', 5, NULL, 250),
(18, '', NULL, 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-27 00:00:00', NULL, 'Completed', 5, NULL, 250),
(19, '', NULL, 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-27 00:00:00', NULL, 'Completed', 5, NULL, 250),
(20, '', NULL, 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-27 00:00:00', NULL, 'Completed', 5, NULL, 250),
(21, '', NULL, 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, '2014-10-27 00:00:00', NULL, 'Completed', 5, NULL, 250);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_repair_types`
--

CREATE TABLE IF NOT EXISTS `ocl_repair_types` (
  `repairTypeId` int(5) NOT NULL AUTO_INCREMENT,
  `repairTypeName` varchar(200) NOT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` datetime DEFAULT NULL,
  `categoryId` int(5) DEFAULT NULL,
  `repairTypeDescription` text,
  PRIMARY KEY (`repairTypeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ocl_repair_types`
--

INSERT INTO `ocl_repair_types` (`repairTypeId`, `repairTypeName`, `creatorId`, `createDate`, `editorId`, `editDate`, `categoryId`, `repairTypeDescription`) VALUES
(4, 'Test', NULL, NULL, NULL, NULL, 1, NULL),
(5, 'Replacement', NULL, NULL, NULL, NULL, 10, NULL),
(6, 'Modification', NULL, NULL, NULL, NULL, 6, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_requisitions`
--

CREATE TABLE IF NOT EXISTS `ocl_requisitions` (
  `requisitionTitle` varchar(255) NOT NULL,
  `requisitionNumber` varchar(50) NOT NULL,
  `requisitionDescription` text NOT NULL,
  `requisitionFor` varchar(50) NOT NULL,
  `departmentId` int(5) DEFAULT NULL,
  `organizationId` int(5) NOT NULL,
  `companyId` int(8) NOT NULL,
  `userId` int(5) DEFAULT NULL,
  `requisitionDetailId` int(8) NOT NULL,
  `requisitionApproved` tinyint(1) NOT NULL,
  `requisitionCreateDate` date NOT NULL,
  `creatorId` int(8) NOT NULL,
  `createDate` text,
  `editorId` int(5) DEFAULT NULL,
  `editDate` text,
  `requisitionId` int(5) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`requisitionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100 ;

--
-- Dumping data for table `ocl_requisitions`
--

INSERT INTO `ocl_requisitions` (`requisitionTitle`, `requisitionNumber`, `requisitionDescription`, `requisitionFor`, `departmentId`, `organizationId`, `companyId`, `userId`, `requisitionDetailId`, `requisitionApproved`, `requisitionCreateDate`, `creatorId`, `createDate`, `editorId`, `editDate`, `requisitionId`) VALUES
('r38', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 1),
('Test Req', '', 'undefined', '', 2, 1, 0, 1, 0, 1, '0000-00-00', 0, NULL, NULL, NULL, 2),
('test requisition', '', 'supporting accessories', 'Department', 6, 1, 17, NULL, 0, 1, '2014-12-30', 0, NULL, 23, '30-12-14 :: 01:36 pm', 3),
('test123', '', 'LCD Monitor', '', 2, 1, 0, 5, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 4),
('test', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 5),
('test', '', 'test', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 6),
('test 2', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 7),
('test 3', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 8),
('test 4', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 9),
('test 5', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 10),
('test 6', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 11),
('7', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 12),
('8', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 13),
('9', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 14),
('10', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 15),
('test', '', 'test', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 16),
('test', '', 'test', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 17),
('test', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 18),
('test', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 19),
('test', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 20),
('test', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 21),
('requistion', '', 'test requisition', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 22),
('Requisition 2', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 23),
('Requisition 3', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 24),
('requisition 4', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 25),
('req 5', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 26),
('req 6', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 27),
('req 7', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 28),
('req 8', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 29),
('req 9', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 30),
('req 10', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 31),
('req 11', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 32),
('req 12', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 33),
('req 14', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 34),
('req 15', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 35),
('req 16', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 36),
('req 17', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 37),
('req 17', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 38),
('r 1', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 39),
('r2', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 40),
('r 3', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 41),
('r4', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 42),
('r5', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 43),
('r6', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 44),
('r7', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 45),
('r2', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 46),
('r8', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 47),
('r9', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 48),
('r9', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 49),
('r10', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 50),
('r12', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 51),
('r14', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 52),
('r15', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 53),
('r16', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 54),
('r17', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 55),
('r18', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 56),
('r19', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 57),
('120', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 58),
('r21', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 59),
('r22', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 60),
('r23', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 61),
('r24', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 62),
('r25', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 63),
('r25', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 64),
('r27', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 65),
('r28', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 66),
('r29', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 67),
('r30', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 68),
('r20', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 69),
('req 21', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 70),
('req 22', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 71),
('r31', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 72),
('r32', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 73),
('r33', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 74),
('r40', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 75),
('r42', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 76),
('r43', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 77),
('r44', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 78),
('r45', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 79),
('r45', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 80),
('r46', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 81),
('r47', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 82),
('r47', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 83),
('r48', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 84),
('r48', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 85),
('r49', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 86),
('r49', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 87),
('r50', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 88),
('r51', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 89),
('r51', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 90),
('r52', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 91),
('r53', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 92),
('r54', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 93),
('CPU', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 94),
('Dell', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 95),
('CPU 2', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 96),
('hdd', '', 'undefined', '', 2, 1, 0, 8, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 97),
('Test Requisition', '', 'undefined', 'Staff', 6, 1, 17, 6, 0, 0, '2014-12-17', 0, NULL, 23, '29-12-14 :: 02:47 pm', 98),
('Test Req1', '1001/REQ/15/99', 'test ', 'Staff', 6, 0, 17, 6, 0, 0, '2015-01-24', 23, '26-01-15 :: 12:29 pm', NULL, NULL, 99);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_requisitions_detail`
--

CREATE TABLE IF NOT EXISTS `ocl_requisitions_detail` (
  `requisitionId` int(5) DEFAULT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `categoryId` int(5) NOT NULL,
  `orderedQuantity` int(11) NOT NULL,
  `receivedQuantity` int(11) NOT NULL,
  `requisitionDetailId` int(5) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`requisitionDetailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=118 ;

--
-- Dumping data for table `ocl_requisitions_detail`
--

INSERT INTO `ocl_requisitions_detail` (`requisitionId`, `itemMasterId`, `categoryId`, `orderedQuantity`, `receivedQuantity`, `requisitionDetailId`) VALUES
(51, 1, 0, 34, 0, 1),
(51, 3, 0, 23, 0, 2),
(54, 4, 0, 2, 0, 3),
(54, 3, 0, 8, 0, 4),
(55, 4, 0, 2, 0, 5),
(57, 4, 0, 43, 0, 6),
(58, 4, 0, 43, 0, 7),
(58, 2, 0, 3, 0, 8),
(59, 4, 0, 43, 0, 9),
(60, 4, 0, 43, 0, 10),
(61, 4, 0, 43, 0, 11),
(62, 4, 0, 43, 0, 12),
(63, 4, 0, 43, 0, 13),
(64, 4, 0, 43, 0, 14),
(65, 4, 0, 43, 0, 15),
(66, 4, 0, 43, 0, 16),
(67, 4, 0, 43, 0, 17),
(68, 2, 0, 20, 0, 18),
(68, 4, 0, 10, 0, 19),
(69, 4, 0, 43, 0, 20),
(69, 1, 0, 20, 0, 21),
(70, 4, 0, 43, 0, 22),
(70, 1, 0, 10, 0, 23),
(71, 4, 0, 43, 0, 24),
(71, 1, 0, 1, 0, 25),
(72, 4, 0, 43, 0, 26),
(72, 1, 0, 1, 0, 27),
(73, 4, 0, 43, 0, 28),
(73, 1, 0, 1, 0, 29),
(74, 1, 0, 10, 0, 30),
(74, 2, 0, 5, 0, 31),
(75, 4, 0, 43, 0, 32),
(75, 3, 0, 5, 0, 33),
(75, 1, 0, 2, 0, 84),
(77, 1, 0, 2, 0, 86),
(78, 1, 0, 2, 0, 87),
(79, 1, 0, 2, 0, 88),
(80, 1, 0, 2, 0, 89),
(81, 1, 0, 2, 0, 90),
(82, 1, 0, 2, 0, 91),
(83, 1, 0, 1, 0, 92),
(84, 1, 0, 2, 0, 93),
(85, 1, 0, 3, 0, 94),
(85, 4, 0, 2, 0, 95),
(86, 1, 0, 2, 0, 96),
(86, 4, 0, 4, 0, 97),
(87, 1, 0, 1, 0, 98),
(88, 1, 0, 1, 0, 99),
(89, 1, 0, 1, 0, 100),
(90, 1, 0, 1, 0, 101),
(91, 1, 0, 1, 0, 102),
(92, 1, 0, 1, 0, 103),
(93, 1, 0, 1, 0, 104),
(94, 7, 0, 10, 0, 105),
(95, 6, 0, 10, 0, 106),
(96, 1, 0, 1, 0, 107),
(97, 9, 0, 10, 0, 108),
(98, 12, 12, 5, 0, 109),
(0, 3, 3, 7, 0, 110),
(15, 3, 3, 5, 0, 111),
(98, 6, 6, 3, 0, 112),
(98, 2, 2, 4, 0, 113),
(3, 1, 1, 7, 0, 114),
(99, 2, 2, 3, 0, 115),
(99, 7, 7, 4, 0, 116),
(99, 5, 5, 2, 0, 117);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_site_users`
--

CREATE TABLE IF NOT EXISTS `ocl_site_users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `accountStatus` enum('1','0') NOT NULL DEFAULT '1',
  `profilePicture` varchar(32) NOT NULL DEFAULT 'profile.jpg',
  `prefix` varchar(20) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `middleName` varchar(255) NOT NULL,
  `displayName` varchar(255) NOT NULL,
  `suffix` varchar(20) NOT NULL,
  `organization` varchar(255) NOT NULL,
  `title` varchar(20) NOT NULL,
  `telephoneHome` varchar(255) NOT NULL,
  `telephoneOffice` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `gender` enum('Male','Female','') NOT NULL DEFAULT '',
  `dob` date NOT NULL,
  `updateDtTm` int(11) NOT NULL,
  `creationDtTm` int(11) NOT NULL,
  `modifyId` int(11) NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ocl_stock`
--

CREATE TABLE IF NOT EXISTS `ocl_stock` (
  `stockId` int(5) NOT NULL AUTO_INCREMENT,
  `stockNumber` varchar(50) NOT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `companyId` int(8) NOT NULL,
  `stockQuantity` int(8) DEFAULT NULL,
  `issueQuantity` int(8) DEFAULT '0',
  `damageQuantity` int(8) NOT NULL DEFAULT '0',
  `stockDescription` text,
  PRIMARY KEY (`stockId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `ocl_stock`
--

INSERT INTO `ocl_stock` (`stockId`, `stockNumber`, `itemMasterId`, `companyId`, `stockQuantity`, `issueQuantity`, `damageQuantity`, `stockDescription`) VALUES
(16, '1001/STK/15/16', 6, 17, 0, 3, 0, NULL),
(17, '1001/STK/15/17', 12, 17, 2, 3, 0, NULL),
(18, '1001/STK/15/18', 2, 17, 3, 0, 0, NULL),
(19, '1001/STK/15/19', 7, 17, 4, 0, 0, NULL),
(20, '1001/STK/15/20', 5, 17, 2, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_stock_detail`
--

CREATE TABLE IF NOT EXISTS `ocl_stock_detail` (
  `stockDetailId` int(5) NOT NULL AUTO_INCREMENT,
  `stockId` int(5) DEFAULT NULL,
  `productCode` varchar(50) DEFAULT NULL,
  `receiveId` int(8) NOT NULL,
  `issueId` int(8) NOT NULL,
  PRIMARY KEY (`stockDetailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `ocl_stock_detail`
--

INSERT INTO `ocl_stock_detail` (`stockDetailId`, `stockId`, `productCode`, `receiveId`, `issueId`) VALUES
(39, 12, '1001/Lap/Dell/39', 138, 0),
(40, 13, '1001/tst-mnitr/40', 138, 0),
(41, 13, '1001/tst-mnitr/41', 138, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_stock_detail_sub`
--

CREATE TABLE IF NOT EXISTS `ocl_stock_detail_sub` (
  `stockDetailSubId` int(5) NOT NULL AUTO_INCREMENT,
  `stockDetailId` int(5) DEFAULT NULL,
  `stockId` int(5) DEFAULT NULL,
  `departmentId` int(5) DEFAULT NULL,
  `organizationId` int(5) DEFAULT NULL,
  `quotationId` int(5) DEFAULT NULL,
  `requisitionId` int(5) DEFAULT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `vendorsId` int(5) DEFAULT NULL,
  `receiveId` int(5) DEFAULT NULL,
  `receiveDate` datetime DEFAULT NULL,
  `warrantyEndDate` datetime DEFAULT NULL,
  `userId` int(5) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `productCode` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`stockDetailSubId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ocl_units`
--

CREATE TABLE IF NOT EXISTS `ocl_units` (
  `untiId` int(5) NOT NULL AUTO_INCREMENT,
  `unitName` varchar(200) NOT NULL,
  `active` varchar(10) DEFAULT NULL,
  `unitCode` varchar(50) DEFAULT NULL,
  `unitDescription` text NOT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` datetime DEFAULT NULL,
  PRIMARY KEY (`untiId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `ocl_units`
--

INSERT INTO `ocl_units` (`untiId`, `unitName`, `active`, `unitCode`, `unitDescription`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(1, 'Meter', NULL, NULL, '', NULL, NULL, NULL, NULL),
(2, 'PCS', NULL, NULL, '', NULL, NULL, NULL, NULL),
(4, 'Inch', NULL, NULL, '', NULL, NULL, NULL, NULL),
(5, 'Dozen', NULL, NULL, '', NULL, NULL, NULL, NULL),
(6, 'Kgs', NULL, NULL, '', NULL, NULL, NULL, NULL),
(8, 'Inch', 'Active', 'ttt', '', 12, '2014-11-17 13:32:48', 34, '2014-11-17 13:33:12'),
(16, 'test', 'Active', 'www', '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_users`
--

CREATE TABLE IF NOT EXISTS `ocl_users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `adminId` int(11) NOT NULL,
  `profilePicture` varchar(100) NOT NULL DEFAULT 'profile.jpg',
  `groupId` int(11) DEFAULT '1',
  `firstName` varchar(255) DEFAULT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `middleName` varchar(255) DEFAULT NULL,
  `employeeId` varchar(150) NOT NULL,
  `employeeType` varchar(100) NOT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `dob` date NOT NULL,
  `cdob` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `presentAddress` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL DEFAULT 'unknown',
  `extNo` int(5) NOT NULL,
  `organizationId` int(11) NOT NULL DEFAULT '1',
  `companyId` int(8) NOT NULL,
  `departmentId` int(11) NOT NULL DEFAULT '2',
  `userRole` varchar(20) NOT NULL,
  `active` varchar(10) DEFAULT NULL,
  `education` varchar(235) DEFAULT NULL,
  `designationId` int(5) DEFAULT NULL,
  `basicSalary` int(15) NOT NULL,
  `grossSalary` int(15) NOT NULL,
  `lastYearRating` varchar(150) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `personalMobile` int(20) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `permanentAddress` varchar(255) DEFAULT NULL,
  `joiningDate` date DEFAULT NULL,
  `confirmationDate` date NOT NULL,
  `lastPromotionDate` date NOT NULL,
  `resignationDate` date NOT NULL,
  `bloodGroup` varchar(20) DEFAULT NULL,
  `createDate` varchar(255) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(255) DEFAULT NULL,
  `createUserId` int(5) DEFAULT NULL,
  PRIMARY KEY (`userId`),
  KEY `groupId` (`groupId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `ocl_users`
--

INSERT INTO `ocl_users` (`userId`, `adminId`, `profilePicture`, `groupId`, `firstName`, `lastName`, `middleName`, `employeeId`, `employeeType`, `gender`, `dob`, `cdob`, `email`, `presentAddress`, `phone`, `extNo`, `organizationId`, `companyId`, `departmentId`, `userRole`, `active`, `education`, `designationId`, `basicSalary`, `grossSalary`, `lastYearRating`, `mobile`, `personalMobile`, `ip`, `permanentAddress`, `joiningDate`, `confirmationDate`, `lastPromotionDate`, `resignationDate`, `bloodGroup`, `createDate`, `editorId`, `editDate`, `createUserId`) VALUES
(2, 0, '', 1, NULL, NULL, NULL, '', '', NULL, '0000-00-00', '0000-00-00', '', '', 'unknown', 0, 1, 0, 3, '', NULL, NULL, NULL, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(3, 0, '', 1, 'Test1', 'Test', NULL, '', '', 'Male', '1997-11-06', '0000-00-00', 'test1@test.com', '', '', 0, 2, 0, 3, '', NULL, NULL, 1, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(5, 0, '', 1, 'Waliul', 'Alam', NULL, '', '', 'Male', '2013-06-04', '0000-00-00', 'waliul.alam@saplbd.com', 'Uttara', '', 0, 1, 0, 2, '', NULL, NULL, 1, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(6, 29, '', 2, 'Pravakar', 'Das', NULL, '', '', 'Male', '2013-06-04', '0000-00-00', 'pravakar@saplbd.com', 'Uttara', '012345', 0, 1, 0, 2, '', NULL, NULL, 1, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(7, 30, '', 1, 'Md.Waliul', 'Alam', NULL, '', '', 'Male', '1988-01-01', '0000-00-00', 'waliul.alam@saplbd.com', 'Uttara', '01963611552', 0, 1, 0, 2, '', NULL, NULL, 1, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(8, 31, '', 1, 'Sayed', 'Islam', 'Zahidul', '', '', 'Male', '1976-10-08', '0000-00-00', 'zahidul.islam.dsi@gmail.com', 'Uttara', '012345', 0, 1, 0, 2, '', 'Active', NULL, 1, 0, 0, '', NULL, 0, '127.0.0.1', NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, '0000-00-00 00:00:00', NULL, NULL, 8),
(9, 0, '', 1, 'Sayed', 'Islam', NULL, '', '', 'Male', '2014-09-02', '0000-00-00', 'zahidul.islam.dsi@gmail.com', 'Uttara', '012345', 0, 1, 0, 2, '', NULL, NULL, 1, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(10, 34, '', 1, 'sayed', 'islam', NULL, '', '', 'Male', '2014-09-01', '0000-00-00', 'zahidul.islam.dsi@gmail.com', 'Uttara', '012345', 0, 1, 0, 2, '', NULL, NULL, 0, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(11, 32, '', 1, 'Sayed', 'Islam', 'Zahidul', '', '', 'Male', '2014-10-07', '0000-00-00', 'zahidul.islam.dsi@gmail.com', 'Dhaka', '012345', 0, 1, 0, 2, '', NULL, NULL, 1, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(12, 0, '', 1, NULL, NULL, NULL, '', '', NULL, '2014-10-07', '0000-00-00', 'test@gmail.com', 'unknown', 'unknown', 0, 1, 0, 2, '', NULL, NULL, NULL, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(13, 0, '', 1, NULL, NULL, NULL, '', '', NULL, '2014-10-07', '0000-00-00', 'test@gmail.com', 'unknown', 'unknown', 0, 1, 0, 2, '', NULL, NULL, NULL, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(15, 0, '', 1, NULL, NULL, NULL, '', '', NULL, '2014-10-07', '0000-00-00', 'test@gmail.com', 'unknown', 'unknown', 0, 1, 0, 2, '', NULL, NULL, NULL, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(16, 0, '', 1, 'Sayed', 'Islam', 'Zahidul', '', '', 'Male', '2014-10-07', '0000-00-00', 'test@gmail.com', 'Uttara', 'unknown', 0, 1, 0, 2, '', 'Active', NULL, 1, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(17, 0, '', 3, 'Mizanur', 'Rahman', NULL, '', '', 'Male', '1988-01-20', '0000-00-00', 'm_mamun_it@yahoo.com', 'Barisal', '00000', 0, 1, 0, 2, '', 'Active', NULL, 3, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, NULL, NULL, NULL, NULL),
(23, 2, '121c4-dd4db-c607c-mamun_ict.jpg', 1, 'Md', 'Mamun', NULL, '', '', 'Male', '1990-11-28', '0000-00-00', 'm.mamun.it@gmail.com', 'Dhaka', '123000', 0, 1, 17, 2, 'User', 'Active', NULL, 1, 0, 0, '', '0003333', 0, '10.0.0.95', '0', '2014-10-02', '0000-00-00', '0000-00-00', '0000-00-00', NULL, '0000-00-00 00:00:00', 23, '12-12-14 :: 02:37 pm', 23),
(24, 0, '', 2, 'test', 'test', NULL, '', '', 'Male', '2014-11-19', '0000-00-00', 'm@gmail.com', 'tests', '444', 0, 3, 0, 15, 'Admin,User', 'Active', 'test', 3, 0, 0, '', '333', 0, '127.0.0.1', 'test', '2014-11-01', '0000-00-00', '0000-00-00', '0000-00-00', 'a+', '0000-00-00 00:00:00', NULL, NULL, 8),
(25, 0, '', 2, NULL, NULL, NULL, '', '', NULL, '0000-00-00', '0000-00-00', 'test@sfa.com', '', 'unknown', 0, 1, 0, 2, 'Admin,User', NULL, NULL, NULL, 0, 0, '', NULL, 0, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, '0000-00-00 00:00:00', NULL, NULL, NULL),
(26, 33, '', 1, 'Md', 'Mamun', '---', '', '', 'Male', '2014-12-01', '0000-00-00', 'm2323.dsi@gmail.com', 'test', '1322', 0, 2, 17, 2, '', 'Active', 'BSc.', 1, 0, 0, '', '1232', 0, '10.0.0.95', 'test23', '2014-11-19', '0000-00-00', '0000-00-00', '0000-00-00', 'a-', '0000-00-00 00:00:00', 23, '24-12-14 :: 07:29 am', 8),
(27, 35, '', 1, 'abc', 'xyz', NULL, '', '', 'Male', '0000-00-00', '0000-00-00', '', '', '', 0, 2, 17, 4, '', 'Active', NULL, 1, 0, 0, '', NULL, 0, '10.0.0.132', NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, '19-12-14 :: 02:40 pm', NULL, NULL, 23),
(28, 0, '', 1, ' abc', 'xyz', NULL, '', '', 'Male', '0000-00-00', '0000-00-00', '', '', '', 0, 9, 27, 18, '', 'Active', NULL, 4, 0, 0, '', NULL, 0, '10.0.0.132', NULL, NULL, '0000-00-00', '0000-00-00', '0000-00-00', NULL, '19-12-14 :: 02:53 pm', NULL, NULL, 23);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_user_group`
--

CREATE TABLE IF NOT EXISTS `ocl_user_group` (
  `groupId` int(11) NOT NULL AUTO_INCREMENT,
  `groupName` varchar(255) NOT NULL,
  `userLevel` int(11) NOT NULL COMMENT 'These are predefined to differentiate between different user type',
  `description` text,
  `permissions` text NOT NULL,
  `updateDtTm` int(11) NOT NULL,
  `creationDtTm` int(11) NOT NULL,
  `modifyId` int(11) NOT NULL,
  PRIMARY KEY (`groupId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ocl_user_group`
--

INSERT INTO `ocl_user_group` (`groupId`, `groupName`, `userLevel`, `description`, `permissions`, `updateDtTm`, `creationDtTm`, `modifyId`) VALUES
(1, 'Super Admin', 9, 'Super Admin Groups', 'canViewIT-InventoryMenu;canViewAdmin-SetupMenu;canViewUser;canAddUser;canEditUser;canDeleteUser;canViewUserGroup;canAddUserGroup;canEditUserGroup;canDeleteUserGroup;canSetUserGroupPermission;canViewVendors;canAddVendors;canEditVendors;canDeleteVendors;canViewUnits;canAddUnits;canEditUnits;canDeleteUnits;canViewCategories;canAddCategories;canEditCategories;canDeleteCategories;canViewItem_Master;canAddItem_Master;canEditItem_Master;canDeleteItem_Master;canViewOrganizations;canAddOrganizations;canEditOrganizations;canDeleteOrganizations;canViewDepartments;canAddDepartments;canEditDepartments;canDeleteDepartments;canViewRequisitions;canAddRequisitions;canEditRequisitions;canApproveRequisitions;canDeleteRequisitions;canViewQuotations;canAddQuotations;canEditQuotations;canDeleteQuotations;canApproveQuotations;canViewReceives;canAddReceives;canEditReceives;canDeleteReceives;canEditStock;canDeleteStock;canAddIssue;canEditIssue;canDeleteIssue;canViewRepair_Type;canAddRepair_Type;canEditRepair_Type;canDeleteRepair_Type;canViewDamage;canAddDamage;canEditDamage;canDeleteDamage;canViewRepair;canAddRepair;canEditRepair;canDeleteRepair;canCompleteRepair;canViewBudget;canAddBudget;canEditBudget;canDeleteBudget;canViewBill;canAddBill;canEditBill;canDeleteBill;canViewCompanies;canAddCompanies;canEditCompanies;canDeleteCompanies;canViewDesignation;canAddDesignation;canEditDesignation;canDeleteDesignation;', 1373430062, 1346605670, 0),
(2, 'Level 10', 8, NULL, 'canViewIT-InventoryMenu;canViewVendors;canAddVendors;canEditVendors;canDeleteVendors;canViewUnits;canAddUnits;canEditUnits;canDeleteUnits;canViewCategories;canAddCategories;canEditCategories;canDeleteCategories;canViewItem_Master;canAddItem_Master;canEditItem_Master;canDeleteItem_Master;', 1412171153, 1352364174, 0),
(3, 'Level 3', 8, NULL, 'canViewIT-InventoryMenu;canViewUser;canAddUser;canEditUser;canDeleteUser;canViewUserGroup;canViewVendors;canViewUnits;canViewCategories;canViewItem_Master;canViewOrganizations;canViewDepartments;canViewRequisitions;canViewQuotations;', 1373429981, 1373429981, 0),
(4, 'Level 3', 8, NULL, '', 1411364907, 1411364907, 0),
(5, 'Level 4', 8, NULL, '', 1411364937, 1411364937, 0),
(6, 'Level 2', 9, NULL, '', 1412171186, 1412171186, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_vendors`
--

CREATE TABLE IF NOT EXISTS `ocl_vendors` (
  `vendorsId` int(8) NOT NULL AUTO_INCREMENT,
  `vendorsName` varchar(255) NOT NULL,
  `vendorsAddress` longtext NOT NULL,
  `vendorsEmail` varchar(255) NOT NULL,
  `vendorsPhone` int(100) NOT NULL,
  `active` varchar(10) DEFAULT NULL,
  `vendorsBusinessType` varchar(50) DEFAULT NULL,
  `vendorsFax` varchar(50) DEFAULT NULL,
  `vendorsWebSite` varchar(255) DEFAULT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` varchar(255) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`vendorsId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `ocl_vendors`
--

INSERT INTO `ocl_vendors` (`vendorsId`, `vendorsName`, `vendorsAddress`, `vendorsEmail`, `vendorsPhone`, `active`, `vendorsBusinessType`, `vendorsFax`, `vendorsWebSite`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(3, 'Janani Computer', 'CDA Market, CTG', '750415@gmail.com', 750415, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Omega Compuer', 'SK Mujib Road, CTG', 'omega_rupok@yahoo.com', 721671, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Shah Computer', 'CDA Market CTG', 'shahcomputer@gmail.com', 1815504251, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'RESHIT COMPUTER', 'Choumohoni, Chittagong', 'sales_ctg@rishit.com', 1191000121, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'COMPUTER SOURCE', 'SK Mujib Road, Chittagong', 'debasish@computersourcebd.com', 1730303395, 'Active', 'test', NULL, NULL, NULL, NULL, 23, '19-12-14 :: 02:31 pm'),
(8, 'Computer Village', 'S.K. Mujib Road, Chittagong', 'n_ripon1327@yahoo.com', 1711436745, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'SAPL West', '<p>\n	Ctg</p>\n', 'test@gmail.com', 123456, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'test', 'test test', 'test@tt.org', 2222, 'Active', 'test', '54545', 'test', 23, '10-12-14 :: 03:52 pm', 23, '10-12-14 :: 03:53 pm'),
(11, ' test', '', '', 0, 'Active', ' test', NULL, NULL, 23, '19-12-14 :: 02:31 pm', 23, '19-12-14 :: 02:35 pm');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_vendors_contacts`
--

CREATE TABLE IF NOT EXISTS `ocl_vendors_contacts` (
  `vendorContactsPersonId` int(11) NOT NULL AUTO_INCREMENT,
  `vendorContactsPersonName` varchar(255) NOT NULL,
  `vendorContactsPersonDesignation` varchar(150) NOT NULL,
  `vendorContactsOfficialEmail` varchar(150) NOT NULL,
  `vendorContactsPersonalEmail` varchar(255) NOT NULL,
  `vendorContactsOfficialNumber` varchar(20) NOT NULL,
  `vendorContactsPersonalNumber` varchar(20) NOT NULL,
  `vendorContactsSkypeId` varchar(100) NOT NULL,
  `vendorsId` int(8) NOT NULL,
  PRIMARY KEY (`vendorContactsPersonId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ocl_vendors_contacts`
--

INSERT INTO `ocl_vendors_contacts` (`vendorContactsPersonId`, `vendorContactsPersonName`, `vendorContactsPersonDesignation`, `vendorContactsOfficialEmail`, `vendorContactsPersonalEmail`, `vendorContactsOfficialNumber`, `vendorContactsPersonalNumber`, `vendorContactsSkypeId`, `vendorsId`) VALUES
(1, 'Test Name', '', '', 'test@test.com', '', '', '', 3),
(2, 'testing', '', '', 'guest@test.com', '', '', '', 8),
(3, 'test&test', '', '', 'te@test.tt', '', '', '', 8);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
