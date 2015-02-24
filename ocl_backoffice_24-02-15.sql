-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 24, 2015 at 02:36 অপরাহ্ণ
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ocl_approve_quotation`
--

INSERT INTO `ocl_approve_quotation` (`approveQuotationId`, `quotationId`, `userId`, `approveDate`, `approvalAttachment`, `approveDetails`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(3, 75, 23, '2014-12-30', 'b1132-8716105.pdf', '', 0, '', 23, '12-01-15 :: 12:58 pm'),
(4, 78, 26, '2014-12-30', '', '', 0, '', 0, ''),
(5, 81, 23, '2015-01-28', '', 'sadsada', 23, '26-01-15 :: 12:35 pm', 0, ''),
(6, 83, 23, '2015-02-03', '', 'test', 23, '03-02-15 :: 02:48 pm', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_bill`
--

CREATE TABLE IF NOT EXISTS `ocl_bill` (
  `billId` int(5) NOT NULL AUTO_INCREMENT,
  `billNumber` varchar(20) NOT NULL,
  `receiveId` int(8) NOT NULL,
  `budgetType` varchar(50) NOT NULL,
  `budgetId` int(5) DEFAULT NULL,
  `billReceiveDate` date DEFAULT NULL,
  `billingDate` date DEFAULT NULL,
  `billType` varchar(50) NOT NULL,
  `billAmount` int(100) NOT NULL,
  `billPaymentType` varchar(50) NOT NULL,
  `billCheckedById` int(8) DEFAULT NULL,
  `billSubmittedById` int(8) DEFAULT NULL,
  `billPaymentById` int(8) NOT NULL,
  `billParticulars` text NOT NULL,
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

INSERT INTO `ocl_bill` (`billId`, `billNumber`, `receiveId`, `budgetType`, `budgetId`, `billReceiveDate`, `billingDate`, `billType`, `billAmount`, `billPaymentType`, `billCheckedById`, `billSubmittedById`, `billPaymentById`, `billParticulars`, `billDescription`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(52, '1001/bill/15/52', 145, '', 5, '2015-01-30', '2015-01-30', 'Product Purchase Bill', 12000, 'Cheque', 17, 26, 23, '', 'test', 23, '30-01-15 :: 07:08 pm', 23, '02-02-15 :: 12:21 pm'),
(53, '1001/bill/15/53', 144, 'Capital', 6, '2015-02-02', '2015-02-02', 'Product Purchase Bill', 1100, 'Cash', 17, 17, 0, '', 'test', 23, '02-02-15 :: 12:21 pm', 23, '04-02-15 :: 04:09 pm');

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
(4, 17, 'tstB', 2015, 'Revenue', 10, 1200000.00, 0.00, 'test purpose', 23, '0000-00-00', 23, '04-02-15 :: 03:53 pm'),
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

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
(14, 'test', 'test category ', 'Active', 'tst', 23, '0000-00-00 00:00:00', 23, '0000-00-00 00:00:00'),
(15, 'Cable', 'test cable', 'Active', 'cbl', 23, '0000-00-00 00:00:00', NULL, NULL);

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
  `stockId` int(5) DEFAULT '0',
  `issueId` int(8) NOT NULL DEFAULT '0',
  `damageType` varchar(20) NOT NULL,
  `damageDate` date DEFAULT NULL,
  `checkedById` int(5) DEFAULT NULL,
  `damageDetails` text,
  `damageRemarks` text,
  `damageQuantity` int(100) NOT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` varchar(50) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`damageId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `ocl_damage`
--

INSERT INTO `ocl_damage` (`damageId`, `stockId`, `issueId`, `damageType`, `damageDate`, `checkedById`, `damageDetails`, `damageRemarks`, `damageQuantity`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(35, 17, 0, 'Permanent-Damage', '2015-02-04', 26, 'test', 'test', 1, 23, '04-02-15 :: 07:15 pm', NULL, NULL),
(38, 23, 0, 'Repairable-Damage', '2015-02-19', 23, 'test', 'test', 3, 23, '19-02-15 :: 06:30 pm', 23, '24-02-15 :: 06:30 pm'),
(39, 23, 0, 'Permanent-Damage', '2015-02-19', 26, 'test', 'test', 1, 23, '19-02-15 :: 06:31 pm', NULL, NULL),
(42, 22, 0, 'Permanent-Damage', '2015-02-20', 23, 'test', 'test', 45, 23, '20-02-15 :: 03:05 pm', 23, '20-02-15 :: 04:27 pm'),
(43, 22, 0, 'Permanent-Damage', '2015-02-20', 26, 'test', 'test', 5, 23, '20-02-15 :: 03:26 pm', NULL, NULL),
(44, 23, 67, 'Permanent-Damage', '2015-02-23', 23, 'test', 'test', 2, 23, '23-02-15 :: 11:49 am', 23, '23-02-15 :: 04:12 pm'),
(45, 23, 67, 'Repairable-Damage', '2015-02-23', 26, 'test', 'test', 1, 23, '23-02-15 :: 11:54 am', 23, '23-02-15 :: 03:51 pm'),
(46, 24, 68, 'Repairable-Damage', '2015-02-23', 23, 'fgfgfg', 'bbbbvbvbv', 1, 23, '23-02-15 :: 02:41 pm', NULL, NULL),
(47, 22, 64, 'Permanent-Damage;Rep', '2015-02-23', 23, 'test', 'test', 18, 23, '23-02-15 :: 05:05 pm', 23, '24-02-15 :: 07:02 pm'),
(48, 22, 64, 'Repairable-Damage', '2015-02-23', 23, 'test', 'test', 7, 23, '23-02-15 :: 05:28 pm', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_damage_details`
--

CREATE TABLE IF NOT EXISTS `ocl_damage_details` (
  `damageDetailId` int(11) NOT NULL AUTO_INCREMENT,
  `damageId` int(8) NOT NULL,
  `stockDetailId` int(11) NOT NULL,
  `damageQuantity` int(11) NOT NULL,
  `damageType` varchar(100) NOT NULL,
  `issueId` int(8) NOT NULL,
  PRIMARY KEY (`damageDetailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

--
-- Dumping data for table `ocl_damage_details`
--

INSERT INTO `ocl_damage_details` (`damageDetailId`, `damageId`, `stockDetailId`, `damageQuantity`, `damageType`, `issueId`) VALUES
(3, 39, 59, 1, 'Permanent-Damage', 0),
(6, 43, 54, 5, 'Permanent-Damage', 0),
(9, 42, 43, 35, 'Permanent-Damage', 0),
(10, 42, 54, 10, 'Permanent-Damage', 0),
(18, 46, 49, 1, 'Repairable-Damage', 68),
(22, 45, 58, 1, 'Repairable-Damage', 0),
(52, 44, 45, 1, 'Permanent-Damage', 0),
(53, 44, 57, 1, 'Permanent-Damage', 0),
(56, 48, 43, 5, 'Repairable-Damage', 64),
(57, 48, 54, 2, 'Repairable-Damage', 64),
(60, 38, 44, 1, 'Repairable-Damage', 0),
(61, 38, 60, 1, 'Repairable-Damage', 0),
(62, 38, 61, 1, 'Permanent-Damage', 0),
(63, 47, 43, 15, 'Permanent-Damage', 64),
(64, 47, 54, 3, 'Repairable-Damage', 64);

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
  `issueId` int(5) NOT NULL AUTO_INCREMENT,
  `stockId` int(5) DEFAULT NULL,
  `issueTo` varchar(50) NOT NULL,
  `companyId` int(8) NOT NULL,
  `departmentId` int(5) DEFAULT NULL,
  `issueUserId` int(5) DEFAULT NULL,
  `issueDate` date DEFAULT NULL,
  `issueQuantity` int(8) DEFAULT NULL,
  `issueDescription` text,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` varchar(100) NOT NULL,
  `editorId` int(8) NOT NULL,
  `editDate` varchar(100) NOT NULL,
  PRIMARY KEY (`issueId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;

--
-- Dumping data for table `ocl_issues`
--

INSERT INTO `ocl_issues` (`issueId`, `stockId`, `issueTo`, `companyId`, `departmentId`, `issueUserId`, `issueDate`, `issueQuantity`, `issueDescription`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(42, 279, '', 0, 2, NULL, '2014-11-11', 0, 'undefined', 8, '', 0, ''),
(43, 279, '', 0, 2, NULL, '2014-11-11', 0, 'undefined', 8, '', 0, ''),
(44, 279, '', 0, 2, NULL, '2014-11-11', 0, 'undefined', 8, '', 0, ''),
(45, 279, '', 0, 2, NULL, '2014-11-11', 0, 'undefined', 8, '', 0, ''),
(46, 279, '', 0, 2, NULL, '2014-11-12', 0, 'undefined', 8, '', 0, ''),
(47, 279, '', 0, 2, NULL, '2014-11-12', 0, 'undefined', 8, '', 0, ''),
(48, 279, '', 0, 2, NULL, '2014-11-12', 0, 'undefined', 8, '', 0, ''),
(49, 279, '', 0, 2, NULL, '2014-11-12', 0, 'undefined', 8, '', 0, ''),
(50, 279, '', 0, 2, NULL, '2014-11-12', 0, 'undefined', 8, '', 0, ''),
(51, 279, '', 0, 2, NULL, '2014-11-12', 0, 'undefined', 8, '', 0, ''),
(52, 279, '', 0, 2, NULL, '2014-11-12', 0, 'undefined', 8, '', 0, ''),
(53, 279, '', 0, 2, NULL, '2014-11-12', 0, 'undefined', 8, '', 0, ''),
(54, 279, '', 0, 2, NULL, '2014-11-12', 0, 'undefined', 8, '', 0, ''),
(55, 279, '', 0, 2, NULL, '2014-11-12', 0, 'undefined', 8, '', 0, ''),
(56, 280, '', 0, 2, 1, '2014-11-12', 1, 'undefined', 8, '', 0, ''),
(57, 212, '', 17, 3, 17, NULL, 5, NULL, 23, '31-12-14 :: 12:04 pm', 0, ''),
(59, 17, 'Employee', 0, 2, 23, '2015-01-21', 2, NULL, 23, '21-01-15 :: 04:20 pm', 0, ''),
(60, 17, 'Department', 0, 2, NULL, '2015-01-21', 1, NULL, 23, '21-01-15 :: 04:48 pm', 23, '26-01-15 :: 04:16 pm'),
(61, 16, 'Company', 0, NULL, NULL, '2015-01-22', 1, NULL, 23, '22-01-15 :: 05:42 pm', 0, ''),
(63, 16, 'Department', 0, 6, NULL, '2015-01-23', 2, NULL, 23, '23-01-15 :: 02:39 pm', 0, ''),
(64, 22, 'Company', 0, NULL, NULL, '2015-02-19', 25, 'test', 23, '19-02-15 :: 06:26 pm', 0, ''),
(65, 22, 'Department', 0, 6, NULL, '2015-02-19', 75, 'test', 23, '19-02-15 :: 06:27 pm', 0, ''),
(66, 23, 'Department', 0, 8, NULL, '2015-02-19', 3, 'test', 23, '19-02-15 :: 06:28 pm', 0, ''),
(67, 23, 'Employee', 0, 2, 26, '2015-02-19', 1, 'test', 23, '19-02-15 :: 06:29 pm', 0, ''),
(68, 24, 'Employee', 0, 2, 23, '2015-02-23', 2, 'gfgfgfgfhhh', 23, '23-02-15 :: 02:40 pm', 0, '');

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
-- Table structure for table `ocl_issue_details`
--

CREATE TABLE IF NOT EXISTS `ocl_issue_details` (
  `issueDetailId` int(11) NOT NULL AUTO_INCREMENT,
  `issueId` int(11) NOT NULL,
  `stockDetailId` int(11) NOT NULL,
  PRIMARY KEY (`issueDetailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `ocl_issue_details`
--

INSERT INTO `ocl_issue_details` (`issueDetailId`, `issueId`, `stockDetailId`) VALUES
(1, 66, 46),
(2, 66, 47),
(3, 66, 55),
(4, 67, 45),
(5, 67, 48),
(6, 67, 57),
(7, 67, 58),
(8, 68, 49),
(9, 68, 50),
(10, 68, 51);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_issue_uncountable_details`
--

CREATE TABLE IF NOT EXISTS `ocl_issue_uncountable_details` (
  `issueUncountableDetailId` int(11) NOT NULL AUTO_INCREMENT,
  `issueId` int(8) NOT NULL,
  `stockDetailId` int(8) NOT NULL,
  `issueQuantity` int(11) NOT NULL,
  PRIMARY KEY (`issueUncountableDetailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ocl_issue_uncountable_details`
--

INSERT INTO `ocl_issue_uncountable_details` (`issueUncountableDetailId`, `issueId`, `stockDetailId`, `issueQuantity`) VALUES
(1, 64, 43, 40),
(2, 64, 54, 10),
(3, 65, 43, 70),
(4, 65, 54, 5);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `ocl_items_master`
--

INSERT INTO `ocl_items_master` (`itemMasterId`, `categoryId`, `unitId`, `itemType`, `itemName`, `active`, `itemCode`, `warranty`, `creatorId`, `createDate`, `editorId`, `editDate`, `itemDescription`, `serialNumber`) VALUES
(1, 1, 2, 'Countable', 'CRT', 'Active', 'MNT/CRT', NULL, NULL, NULL, 23, '0000-00-00 00:00:00', NULL, NULL),
(4, 1, 4, 'Countable', 'LCD Monitor', 'Active', 'MNT/LCD', NULL, NULL, NULL, 23, '0000-00-00 00:00:00', NULL, NULL),
(6, 10, 2, 'Countable', 'DELL', 'Active', 'Lap/Dell', NULL, NULL, NULL, 23, '0000-00-00 00:00:00', NULL, NULL),
(7, 12, 2, 'Countable', 'CPU', 'Active', 'CPU', NULL, NULL, NULL, 23, '0000-00-00 00:00:00', NULL, NULL),
(8, 12, 2, 'Countable', 'Keyboard', 'Active', 'Key', NULL, NULL, NULL, 23, '0000-00-00 00:00:00', NULL, NULL),
(9, 12, 2, 'Countable', 'HDD', 'Active', 'HDD', NULL, NULL, NULL, 23, '0000-00-00 00:00:00', NULL, NULL),
(10, 12, 2, 'Countable', 'Mother board', 'Active', 'MBoard', NULL, NULL, NULL, 23, '0000-00-00 00:00:00', NULL, NULL),
(12, 3, 2, 'Countable', 'test monitor', 'Active', 'tst-mnitr', NULL, NULL, NULL, 23, '0000-00-00 00:00:00', NULL, 'mn-32'),
(13, 15, 1, 'Uncountable', 'Optical Fiber', 'Active', 'OptF', NULL, 23, '0000-00-00 00:00:00', NULL, NULL, 'test item', NULL),
(14, 15, 1, 'Uncountable', 'Wires', 'Active', 'wires', NULL, 23, '0000-00-00 00:00:00', NULL, NULL, 'test Item', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

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
(82, 'test Quotation2', '1001/QUO/15/82', 'sfdsfdsfsdfsd', 0, 0, 99, 0, '', 8, '2015-01-26', 23, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(83, 'Quote for Cable Requisition', '1001/QUO/15/83', 'Test', 0, 0, 100, 0, '', 4, '2015-02-03', 23, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, 23, '0000-00-00 00:00:00'),
(84, 'Test Quote For Cable', '1001/QUO/15/84', 'Test', 0, 0, 100, 0, '', 8, '2015-02-03', 23, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=140 ;

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
(131, 82, 5, 2, 6500, 13000),
(132, 83, 13, 120, 15, 1800),
(133, 83, 14, 300, 10, 3000),
(134, 83, 8, 20, 550, 11000),
(135, 83, 9, 10, 4000, 40000),
(136, 84, 13, 120, 14, 1680),
(137, 84, 14, 300, 11, 3300),
(138, 84, 8, 20, 600, 12000),
(139, 84, 9, 10, 3900, 39000);

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
  `receiveConfirmed` varchar(20) NOT NULL DEFAULT 'No',
  `creatorId` int(5) DEFAULT NULL,
  `createDate` varchar(100) DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`receiveId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=149 ;

--
-- Dumping data for table `ocl_receives`
--

INSERT INTO `ocl_receives` (`receiveId`, `receiveNumber`, `receiveDescription`, `userId`, `quotationId`, `requisitionId`, `receiveDate`, `receiveConfirmed`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(108, '', 'undefined', 0, 56, 80, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(109, '', 'undefined', 0, 57, 81, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(110, '', 'undefined', 0, 58, 82, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(111, '', 'undefined', 0, 59, 83, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(112, '', 'undefined', 0, 60, 84, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(113, '', 'undefined', 0, 61, 85, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(114, '', 'undefined', 0, 62, 86, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(115, '', 'undefined', 0, 63, 87, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(116, '', 'undefined', 0, 64, 88, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(117, '', 'undefined', 0, 65, 89, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(118, '', 'undefined', 0, 66, 90, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(119, '', 'undefined', 0, 67, 91, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(120, '', 'undefined', 0, 68, 92, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(121, '', 'undefined', 0, 69, 93, '2014-10-29', 'No', 8, NULL, NULL, NULL),
(122, '', 'undefined', 0, 70, 94, '2014-11-03', 'No', 8, NULL, NULL, NULL),
(123, '', 'undefined', 0, 71, 95, '2014-11-03', 'No', 8, NULL, NULL, NULL),
(124, '', 'undefined', 0, 72, 96, '2014-11-03', 'No', 8, NULL, NULL, NULL),
(125, '', 'undefined', 0, 73, 97, '2014-11-07', 'No', 8, NULL, NULL, NULL),
(127, '', NULL, 23, 75, NULL, '2015-01-12', 'No', 23, '12-01-15 :: 06:39 pm', NULL, NULL),
(141, '1001/RECV/15/141', NULL, 23, 75, 98, '2015-01-19', 'No', 23, '19-01-15 :: 03:15 pm', 23, '20-01-15 :: 12:28 pm'),
(142, '1001/RECV/15/142', NULL, 17, 75, 98, '2015-01-19', 'No', 23, '19-01-15 :: 03:17 pm', NULL, NULL),
(143, '1001/RECV/15/143', NULL, 24, 75, 98, '2015-01-19', 'No', 23, '19-01-15 :: 04:07 pm', 23, '20-01-15 :: 12:30 pm'),
(144, '1001/RECV/15/144', NULL, 26, 81, 99, '2015-01-29', 'No', 23, '26-01-15 :: 12:35 pm', NULL, NULL),
(145, '1001/RECV/15/145', NULL, 7, 81, 99, '2015-01-29', 'No', 23, '26-01-15 :: 12:36 pm', NULL, NULL),
(147, '1001/RECV/15/147', 'test', 26, 83, 100, '2015-02-13', 'Yes', 23, '13-02-15 :: 02:45 pm', NULL, NULL),
(148, '1001/RECV/15/148', NULL, 23, 83, 100, '2015-02-13', 'Yes', 23, '13-02-15 :: 02:56 pm', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_receives_detail`
--

CREATE TABLE IF NOT EXISTS `ocl_receives_detail` (
  `receiveDetailId` int(10) NOT NULL AUTO_INCREMENT,
  `receiveId` int(5) DEFAULT NULL,
  `requisitionId` int(8) NOT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `productCode` varchar(100) NOT NULL,
  `issueId` int(8) NOT NULL,
  `damageId` int(8) NOT NULL,
  `receiveQuantity` int(11) NOT NULL,
  `warrantyEndDate` date DEFAULT NULL,
  PRIMARY KEY (`receiveDetailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=375 ;

--
-- Dumping data for table `ocl_receives_detail`
--

INSERT INTO `ocl_receives_detail` (`receiveDetailId`, `receiveId`, `requisitionId`, `itemMasterId`, `productCode`, `issueId`, `damageId`, `receiveQuantity`, `warrantyEndDate`) VALUES
(331, 141, 0, 6, '1001/Lap/Dell/331', 61, 0, 0, '2015-01-26'),
(333, 141, 0, 12, '1001/tst-mnitr/333', 0, 0, 0, '2015-01-26'),
(334, 142, 0, 6, '1001/Lap/Dell/334', 63, 0, 0, '2015-01-28'),
(335, 142, 0, 12, '1001/tst-mnitr/335', 0, 35, 0, '2015-01-28'),
(336, 143, 0, 6, '1001/Lap/Dell/336', 63, 0, 0, '2015-01-31'),
(337, 143, 0, 12, '1001/tst-mnitr/337', 59, 0, 0, '2015-01-30'),
(338, 143, 0, 12, '1001/tst-mnitr/338', 59, 0, 0, '2015-01-30'),
(339, 143, 0, 12, '1001/tst-mnitr/339', 60, 0, 0, '2015-01-30'),
(340, 144, 0, 2, '1001//340', 0, 0, 0, '0000-00-00'),
(341, 144, 0, 2, '1001//341', 0, 0, 0, '0000-00-00'),
(342, 144, 0, 2, '1001//342', 0, 0, 0, '0000-00-00'),
(343, 144, 0, 7, '1001/CPU/343', 0, 0, 0, '0000-00-00'),
(344, 144, 0, 7, '1001/CPU/344', 0, 0, 0, '0000-00-00'),
(345, 144, 0, 7, '1001/CPU/345', 0, 0, 0, '0000-00-00'),
(346, 145, 0, 7, '1001/CPU/346', 0, 0, 0, '0000-00-00'),
(347, 145, 0, 5, '1001//347', 0, 0, 0, '0000-00-00'),
(348, 145, 0, 5, '1001//348', 0, 0, 0, '0000-00-00'),
(353, 147, 0, 13, '1001/OptF/353', 0, 0, 50, '2015-02-13'),
(354, 147, 0, 14, '1001/wires/354', 0, 0, 150, '2015-02-13'),
(355, 147, 0, 8, '1001/Key/355', 0, 0, 5, '2015-02-28'),
(356, 147, 0, 8, '1001/Key/356', 0, 0, 5, '2015-02-28'),
(357, 147, 0, 8, '1001/Key/357', 0, 0, 5, '2015-02-28'),
(358, 147, 0, 8, '1001/Key/358', 0, 0, 5, '2015-02-28'),
(359, 147, 0, 8, '1001/Key/359', 0, 0, 5, '2015-02-28'),
(360, 147, 0, 9, '1001/HDD/360', 0, 0, 4, '2015-02-28'),
(361, 147, 0, 9, '1001/HDD/361', 0, 0, 4, '2015-02-28'),
(362, 147, 0, 9, '1001/HDD/362', 0, 0, 4, '2015-02-28'),
(363, 147, 0, 9, '1001/HDD/363', 0, 0, 4, '2015-02-28'),
(364, 148, 0, 13, '1001/OptF/364', 0, 0, 20, '2015-02-13'),
(365, 148, 0, 14, '1001/wires/365', 0, 0, 30, '2015-02-13'),
(366, 148, 0, 8, '1001/Key/366', 0, 0, 7, '2015-02-28'),
(367, 148, 0, 8, '1001/Key/367', 0, 0, 7, '2015-02-28'),
(368, 148, 0, 8, '1001/Key/368', 0, 0, 7, '2015-02-28'),
(369, 148, 0, 8, '1001/Key/369', 0, 0, 7, '2015-02-28'),
(370, 148, 0, 8, '1001/Key/370', 0, 0, 7, '2015-02-28'),
(371, 148, 0, 8, '1001/Key/371', 0, 0, 7, '2015-02-28'),
(372, 148, 0, 8, '1001/Key/372', 0, 0, 7, '2015-02-28'),
(373, 148, 0, 9, '1001/HDD/373', 0, 0, 2, '2015-02-28'),
(374, 148, 0, 9, '1001/HDD/374', 0, 0, 2, '2015-02-28');

-- --------------------------------------------------------

--
-- Table structure for table `ocl_receives_detail_old`
--

CREATE TABLE IF NOT EXISTS `ocl_receives_detail_old` (
  `receiveDetailId` int(10) NOT NULL AUTO_INCREMENT,
  `receiveId` int(5) DEFAULT NULL,
  `requisitionId` int(8) NOT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `receiveQuantity` int(11) NOT NULL,
  `productCode` varchar(50) NOT NULL,
  `issueId` int(8) NOT NULL DEFAULT '0',
  `damageId` int(8) NOT NULL DEFAULT '0',
  `warrantyEndDate` date DEFAULT NULL,
  PRIMARY KEY (`receiveDetailId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ocl_repair`
--

CREATE TABLE IF NOT EXISTS `ocl_repair` (
  `repairId` int(5) NOT NULL AUTO_INCREMENT,
  `repairName` varchar(200) NOT NULL,
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
  `repairRemarks` text,
  `repairDetails` text,
  `warrantyEndDate` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `categoryId` int(5) DEFAULT NULL,
  `stockId` int(5) DEFAULT NULL,
  PRIMARY KEY (`repairId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `ocl_repair`
--

INSERT INTO `ocl_repair` (`repairId`, `repairName`, `repairTypeId`, `damageId`, `departmentId`, `organizationId`, `vendorsId`, `repairVendorsId`, `repairQuantity`, `receiveDate`, `repairDate`, `creatorId`, `createDate`, `editorId`, `editDate`, `repairRemarks`, `repairDetails`, `warrantyEndDate`, `status`, `itemMasterId`, `categoryId`, `stockId`) VALUES
(4, '', NULL, NULL, 6, 1, 3, 5, 1, NULL, '2014-10-15', 8, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 'Completed', NULL, NULL, NULL),
(5, '', 5, NULL, 6, 1, 3, 4, 1, NULL, '2014-10-14', 8, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(6, '', 0, 0, 6, 1, 3, 0, 0, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(7, '', 0, 6, 6, 1, 3, 0, 0, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(8, '', 4, 19, 2, 1, 3, 4, 2, NULL, '2014-10-28', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL, NULL, NULL),
(9, '', 5, 21, 2, 1, 3, 7, 4, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL, NULL, NULL),
(10, '', 0, 22, 2, 1, 3, 0, 2, NULL, '2014-10-27', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL, NULL, NULL),
(11, '', 5, 29, 2, 1, 3, 4, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-14 06:00:00', NULL, NULL, NULL, NULL),
(12, '', 0, 29, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-14 06:00:00', 'Completed', 3, NULL, 210),
(13, '', 0, 30, 2, 1, 3, 0, 1, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-14 06:00:00', 'Completed', 3, NULL, 210),
(14, '', 5, 30, 2, 1, 3, 4, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-27 00:00:00', 'Completed', 5, NULL, 250),
(15, '', 5, 30, 2, 1, 3, 4, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-27 00:00:00', 'Completed', 5, NULL, 250),
(16, '', 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-27 00:00:00', 'Completed', 5, NULL, 250),
(17, '', 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-27 00:00:00', 'Completed', 5, NULL, 250),
(18, '', 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-27 00:00:00', 'Completed', 5, NULL, 250),
(19, '', 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-27 00:00:00', 'Completed', 5, NULL, 250),
(20, '', 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-27 00:00:00', 'Completed', 5, NULL, 250),
(21, '', 0, 30, 2, 1, 3, 0, 2, NULL, '0000-00-00', 8, NULL, NULL, NULL, NULL, NULL, '2014-10-27 00:00:00', 'Completed', 5, NULL, 250);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_repair_types`
--

CREATE TABLE IF NOT EXISTS `ocl_repair_types` (
  `repairTypeId` int(5) NOT NULL AUTO_INCREMENT,
  `categoryId` int(5) DEFAULT NULL,
  `serviceType` varchar(200) NOT NULL,
  `serviceTypeDescription` text,
  `serviceRate` float(15,2) NOT NULL DEFAULT '0.00',
  `serviceStartDate` date NOT NULL,
  `serviceEndDate` date NOT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` datetime DEFAULT NULL,
  PRIMARY KEY (`repairTypeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ocl_repair_types`
--

INSERT INTO `ocl_repair_types` (`repairTypeId`, `categoryId`, `serviceType`, `serviceTypeDescription`, `serviceRate`, `serviceStartDate`, `serviceEndDate`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(4, 1, 'Test', NULL, 1000.00, '2015-02-12', '2015-02-28', NULL, NULL, 23, '0000-00-00 00:00:00'),
(5, 10, 'Replacement', NULL, 200.00, '2015-02-12', '2015-02-28', NULL, NULL, 23, '0000-00-00 00:00:00'),
(6, 6, 'Modification', NULL, 500.00, '2015-02-12', '2015-02-28', NULL, NULL, 23, '0000-00-00 00:00:00');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

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
('Test Req1', '1001/REQ/15/99', 'test ', 'Staff', 6, 0, 17, 6, 0, 0, '2015-01-24', 23, '26-01-15 :: 12:29 pm', NULL, NULL, 99),
('Cable Requisition', '1001/REQ/15/100', 'test', 'Company', NULL, 0, 17, NULL, 0, 0, '2015-02-03', 23, '03-02-15 :: 12:16 pm', NULL, NULL, 100);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_requisitions_detail`
--

CREATE TABLE IF NOT EXISTS `ocl_requisitions_detail` (
  `requisitionDetailId` int(5) NOT NULL AUTO_INCREMENT,
  `requisitionId` int(5) DEFAULT NULL,
  `categoryId` int(5) NOT NULL,
  `itemMasterId` int(5) DEFAULT NULL,
  `orderedQuantity` int(11) NOT NULL,
  `receivedQuantity` int(11) NOT NULL,
  PRIMARY KEY (`requisitionDetailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=122 ;

--
-- Dumping data for table `ocl_requisitions_detail`
--

INSERT INTO `ocl_requisitions_detail` (`requisitionDetailId`, `requisitionId`, `categoryId`, `itemMasterId`, `orderedQuantity`, `receivedQuantity`) VALUES
(1, 51, 0, 1, 34, 0),
(2, 51, 0, 3, 23, 0),
(3, 54, 0, 4, 2, 0),
(4, 54, 0, 3, 8, 0),
(5, 55, 0, 4, 2, 0),
(6, 57, 0, 4, 43, 0),
(7, 58, 0, 4, 43, 0),
(8, 58, 0, 2, 3, 0),
(9, 59, 0, 4, 43, 0),
(10, 60, 0, 4, 43, 0),
(11, 61, 0, 4, 43, 0),
(12, 62, 0, 4, 43, 0),
(13, 63, 0, 4, 43, 0),
(14, 64, 0, 4, 43, 0),
(15, 65, 0, 4, 43, 0),
(16, 66, 0, 4, 43, 0),
(17, 67, 0, 4, 43, 0),
(18, 68, 0, 2, 20, 0),
(19, 68, 0, 4, 10, 0),
(20, 69, 0, 4, 43, 0),
(21, 69, 0, 1, 20, 0),
(22, 70, 0, 4, 43, 0),
(23, 70, 0, 1, 10, 0),
(24, 71, 0, 4, 43, 0),
(25, 71, 0, 1, 1, 0),
(26, 72, 0, 4, 43, 0),
(27, 72, 0, 1, 1, 0),
(28, 73, 0, 4, 43, 0),
(29, 73, 0, 1, 1, 0),
(30, 74, 0, 1, 10, 0),
(31, 74, 0, 2, 5, 0),
(32, 75, 0, 4, 43, 0),
(33, 75, 0, 3, 5, 0),
(84, 75, 0, 1, 2, 0),
(86, 77, 0, 1, 2, 0),
(87, 78, 0, 1, 2, 0),
(88, 79, 0, 1, 2, 0),
(89, 80, 0, 1, 2, 0),
(90, 81, 0, 1, 2, 0),
(91, 82, 0, 1, 2, 0),
(92, 83, 0, 1, 1, 0),
(93, 84, 0, 1, 2, 0),
(94, 85, 0, 1, 3, 0),
(95, 85, 0, 4, 2, 0),
(96, 86, 0, 1, 2, 0),
(97, 86, 0, 4, 4, 0),
(98, 87, 0, 1, 1, 0),
(99, 88, 0, 1, 1, 0),
(100, 89, 0, 1, 1, 0),
(101, 90, 0, 1, 1, 0),
(102, 91, 0, 1, 1, 0),
(103, 92, 0, 1, 1, 0),
(104, 93, 0, 1, 1, 0),
(105, 94, 0, 7, 10, 0),
(106, 95, 0, 6, 10, 0),
(107, 96, 0, 1, 1, 0),
(108, 97, 0, 9, 10, 0),
(109, 98, 12, 12, 5, 0),
(110, 0, 3, 3, 7, 0),
(111, 15, 3, 3, 5, 0),
(112, 98, 6, 6, 3, 0),
(113, 98, 2, 2, 4, 0),
(114, 3, 1, 1, 7, 0),
(115, 99, 2, 2, 3, 0),
(116, 99, 7, 7, 4, 0),
(117, 99, 5, 5, 2, 0),
(118, 100, 15, 13, 120, 0),
(119, 100, 15, 14, 300, 0),
(120, 100, 12, 8, 20, 0),
(121, 100, 12, 9, 10, 0);

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
  `damageQuantity` int(8) NOT NULL DEFAULT '0' COMMENT 'damageFromStock',
  `damageQuantityFromIssue` int(8) NOT NULL DEFAULT '0',
  `stockDescription` text,
  PRIMARY KEY (`stockId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `ocl_stock`
--

INSERT INTO `ocl_stock` (`stockId`, `stockNumber`, `itemMasterId`, `companyId`, `stockQuantity`, `issueQuantity`, `damageQuantity`, `damageQuantityFromIssue`, `stockDescription`) VALUES
(16, '1001/STK/15/16', 6, 17, 0, 3, 0, 0, NULL),
(17, '1001/STK/15/17', 12, 17, 1, 3, 1, 0, NULL),
(18, '1001/STK/15/18', 2, 17, 3, 0, 0, 0, NULL),
(19, '1001/STK/15/19', 7, 17, 4, 0, 0, 0, NULL),
(20, '1001/STK/15/20', 5, 17, 2, 0, 0, 0, NULL),
(21, '1001/STK/15/21', 13, 17, 70, 0, 0, 0, NULL),
(22, '1001/STK/15/22', 14, 17, 5, 100, 50, 25, NULL),
(23, '1001/STK/15/23', 8, 17, 1, 4, 4, 3, NULL),
(24, '1001/STK/15/24', 9, 17, 3, 2, 0, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocl_stock_detail`
--

CREATE TABLE IF NOT EXISTS `ocl_stock_detail` (
  `stockDetailId` int(5) NOT NULL AUTO_INCREMENT,
  `stockId` int(5) DEFAULT NULL,
  `productCode` varchar(50) DEFAULT NULL,
  `receiveDetailId` int(8) NOT NULL,
  PRIMARY KEY (`stockDetailId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

--
-- Dumping data for table `ocl_stock_detail`
--

INSERT INTO `ocl_stock_detail` (`stockDetailId`, `stockId`, `productCode`, `receiveDetailId`) VALUES
(39, 12, '1001/Lap/Dell/39', 0),
(40, 13, '1001/tst-mnitr/40', 0),
(41, 13, '1001/tst-mnitr/41', 0),
(42, 21, '1001/OptF/42', 353),
(43, 22, '1001/wires/43', 354),
(44, 23, '1001/Key/44', 359),
(45, 23, '1001/Key/45', 359),
(46, 23, '1001/Key/46', 359),
(47, 23, '1001/Key/47', 359),
(48, 23, '1001/Key/48', 359),
(49, 24, '1001/HDD/49', 363),
(50, 24, '1001/HDD/50', 363),
(51, 24, '1001/HDD/51', 363),
(52, 24, '1001/HDD/52', 363),
(53, 21, '1001/OptF/53', 364),
(54, 22, '1001/wires/54', 365),
(55, 23, '1001/Key/55', 372),
(56, 23, '1001/Key/56', 372),
(57, 23, '1001/Key/57', 372),
(58, 23, '1001/Key/58', 372),
(59, 23, '1001/Key/59', 372),
(60, 23, '1001/Key/60', 372),
(61, 23, '1001/Key/61', 372),
(62, 24, '1001/HDD/62', 374),
(63, 24, '1001/HDD/63', 374);

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
  `unitId` int(5) NOT NULL AUTO_INCREMENT,
  `unitName` varchar(200) NOT NULL,
  `active` varchar(10) DEFAULT NULL,
  `unitCode` varchar(50) DEFAULT NULL,
  `unitDescription` text NOT NULL,
  `creatorId` int(5) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `editorId` int(5) DEFAULT NULL,
  `editDate` datetime DEFAULT NULL,
  PRIMARY KEY (`unitId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `ocl_units`
--

INSERT INTO `ocl_units` (`unitId`, `unitName`, `active`, `unitCode`, `unitDescription`, `creatorId`, `createDate`, `editorId`, `editDate`) VALUES
(1, 'Meter', NULL, NULL, '', NULL, NULL, NULL, NULL),
(2, 'PCS', NULL, NULL, '', NULL, NULL, NULL, NULL),
(4, 'Inch', NULL, NULL, '', NULL, NULL, NULL, NULL),
(5, 'Dozen', NULL, NULL, '', NULL, NULL, NULL, NULL),
(6, 'Kgs', NULL, NULL, '', NULL, NULL, NULL, NULL),
(8, 'Inch', 'Active', 'ttt', '', 12, '2014-11-17 13:32:48', 34, '2014-11-17 13:33:12'),
(16, 'test', 'Active', 'www', '', NULL, NULL, 23, '0000-00-00 00:00:00');

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
