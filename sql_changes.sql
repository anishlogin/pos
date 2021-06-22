CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `mrp` float NOT NULL,
  `retail_price` float NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `sub_category` varchar(50) DEFAULT NULL,
  `qty` int NOT NULL,
  `unit` varchar(20) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT into users (`name`,`password`) values ("admin","password");


CREATE TABLE `tblpos`(
  `posid` bigint unsigned NOT NULL AUTO_INCREMENT,
  counterno varchar(10) DEFAULT NULL, 
  InvDate timestamp NULL DEFAULT CURRENT_TIMESTAMP, 
  BillNo int NOT NULL,
  Partyname varchar(20) DEFAULT NULL,
  PartyAddress varchar(20) DEFAULT NULL,
  PartyEmail varchar(20) DEFAULT NULL,
  Total float NOT NULL,
  discountperc varchar(10) DEFAULT NULL,
  TotalDiscount varchar(10) DEFAULT NULL,
  SGST varchar(10) DEFAULT NULL,
  CGST varchar(10) DEFAULT NULL,
  CESS varchar(10) DEFAULT NULL,
  GrandTotal float NOT NULL,
  RoundOff float DEFAULT 0,
  BillAmount float NOT NULL,
  PayType varchar(20) DEFAULT NULL,
  paidamount float NULL,
  changeamount float NOT NULL,
  noofbags varchar(10) DEFAULT NULL,
  isPending varchar(10) DEFAULT NULL,
  isdiscountapplied varchar(10) DEFAULT NULL,
  CreatedBy varchar(10) DEFAULT NULL,
  PRIMARY KEY (`posid`)
);
CREATE TABLE `tblposunsaved`(
  `posid` bigint unsigned NOT NULL AUTO_INCREMENT,
  postblid int DEFAULT NULL, 
  counterno varchar(10) DEFAULT NULL, 
  InvDate timestamp NULL DEFAULT CURRENT_TIMESTAMP, 
  BillNo int NOT NULL,
  Partyname varchar(20) DEFAULT NULL,
  PartyAddress varchar(20) DEFAULT NULL,
  PartyEmail varchar(20) DEFAULT NULL,
  Total float NOT NULL,
  discountperc varchar(10) DEFAULT NULL,
  TotalDiscount varchar(10) DEFAULT NULL,
  SGST varchar(10) DEFAULT NULL,
  CGST varchar(10) DEFAULT NULL,
  CESS varchar(10) DEFAULT NULL,
  GrandTotal float NOT NULL,
  RoundOff float DEFAULT 0,
  BillAmount float NOT NULL,
  PayType varchar(20) DEFAULT NULL,
  paidamount float NULL,
  changeamount float NOT NULL,
  noofbags varchar(10) DEFAULT NULL,
  isPending varchar(10) DEFAULT NULL,
  isdiscountapplied varchar(10) DEFAULT NULL,
  CreatedBy varchar(10) DEFAULT NULL,
  PRIMARY KEY (`posid`)
  );
CREATE TABLE tblposdetails(
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  postblid int DEFAULT NULL, 
  ItemCode varchar(20) DEFAULT NULL, 
  ItemName varchar(20) DEFAULT NULL, 
  MRP float NOT NULL, 
  Rate float NOT NULL, 
  Qty int DEFAULT NULL,
  freeqty int DEFAULT NULL, 
  Unit varchar(20) DEFAULT NULL, 
  Amount float NOT NULL, 
  DiscAmt float DEFAULT NULL, 
  taxableamt float DEFAULT NULL, 
  taxrate float DEFAULT NULL, 
  sgsttax float DEFAULT NULL, 
  cgsttax float DEFAULT NULL,
  cesstax float DEFAULT NULL,
  cessrate float DEFAULT NULL,
  PRIMARY KEY (`id`)

);
CREATE TABLE tblposunsaveddetails(
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  postblid int DEFAULT NULL, 
  ItemCode varchar(20) DEFAULT NULL, 
  ItemName varchar(20) DEFAULT NULL, 
  MRP float NOT NULL, 
  Rate float NOT NULL, 
  Qty int DEFAULT NULL,
  freeqty int DEFAULT NULL, 
  Unit varchar(20) DEFAULT NULL, 
  Amount float NOT NULL, 
  DiscAmt float DEFAULT NULL, 
  taxableamt float DEFAULT NULL, 
  taxrate float DEFAULT NULL, 
  sgsttax float DEFAULT NULL, 
  cgsttax float DEFAULT NULL,
  cesstax float DEFAULT NULL,
  cessrate float DEFAULT NULL,
  PRIMARY KEY (`id`)
  
);

INSERT INTO `products` (`name`,`code`,`mrp`,`retail_price`,`qty`,`unit`) values ("a","1",10.00,11.00,10,"KGg."),("b","2",10.50,11.00,10,"KGg."),("c","3",100.00,11.00,10,"KGg."),("avjhdsbfjdshgfjs","4",12.50,11.00,10,"KGg.");