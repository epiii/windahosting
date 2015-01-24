/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50141
Source Host           : 127.0.0.1:3306
Source Database       : printshopdb

Target Server Type    : MYSQL
Target Server Version : 50141
File Encoding         : 65001

Date: 2014-01-26 08:34:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bahanbaku
-- ----------------------------
DROP TABLE IF EXISTS `bahanbaku`;
CREATE TABLE `bahanbaku` (
  `kd_bahan` char(4) NOT NULL,
  `nm_bahan` varchar(50) NOT NULL,
  `harga_bahan` int(10) DEFAULT NULL,
  `keterangan` varchar(50) NOT NULL,
  `jml_bahan` int(100) NOT NULL,
  PRIMARY KEY (`kd_bahan`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bahanbaku
-- ----------------------------
INSERT INTO `bahanbaku` VALUES ('BB01', 'Toner 15A', '50000', 'Baru', '97');
INSERT INTO `bahanbaku` VALUES ('BB02', 'Toner 35A', '0', 'Baru', '102');
INSERT INTO `bahanbaku` VALUES ('BB03', 'Toner 36A', '0', 'Baru', '88');
INSERT INTO `bahanbaku` VALUES ('BB04', 'Toner 12A', '0', 'Baru', '102');
INSERT INTO `bahanbaku` VALUES ('BB05', 'Toner MLT 104', '70000', 'Baru', '41');
INSERT INTO `bahanbaku` VALUES ('BB06', 'Toner 38A', '150000', 'Baru', '43');
INSERT INTO `bahanbaku` VALUES ('BB07', 'Toner EP-25', '50000', 'Baru', '46');
INSERT INTO `bahanbaku` VALUES ('BB08', 'Toner ML 1610D2', '55000', 'Baru', '21');
INSERT INTO `bahanbaku` VALUES ('BB09', 'Toner EP-26', '75000', 'Baru', '42');
INSERT INTO `bahanbaku` VALUES ('BB10', 'Tabung', '0', 'Baru', '6');
INSERT INTO `bahanbaku` VALUES ('BB11', 'Fuser Film HP 1010/1020/1300/1102/1006', '0', 'Baru', '49');
INSERT INTO `bahanbaku` VALUES ('BB12', 'Lower Pressure HP', '0', 'Baru', '46');
INSERT INTO `bahanbaku` VALUES ('BB13', 'Doctor Balse HP', '15000', 'Baru', '70');
INSERT INTO `bahanbaku` VALUES ('BB14', 'OPC Drum HP Q2612A', '20000', 'Baru', '38');
INSERT INTO `bahanbaku` VALUES ('BB15', 'OPC Drum Samsung ML 1610', '28000', 'Baru', '70');
INSERT INTO `bahanbaku` VALUES ('BB16', 'Element Haeting HP 1010/1020', '0', 'Baru', '68');
INSERT INTO `bahanbaku` VALUES ('BB17', 'Chip Toner Cartridge HP Laser 49A ', '25000', 'Baru', '65');
INSERT INTO `bahanbaku` VALUES ('BB18', 'OPC Drum Samsung ML 1640', '28000', 'Baru', '60');

-- ----------------------------
-- Table structure for barang
-- ----------------------------
DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
  `kd_barang` char(4) NOT NULL,
  `nm_barang` varchar(50) NOT NULL,
  `harga_beli` int(10) NOT NULL,
  `harga_jual` int(10) NOT NULL,
  `qty` int(3) NOT NULL,
  `ppn` int(3) NOT NULL,
  `diskon` int(3) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `kd_kategori` char(3) NOT NULL,
  `link_gambar` varchar(100) NOT NULL,
  PRIMARY KEY (`kd_barang`),
  KEY `kd_kategori` (`kd_kategori`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of barang
-- ----------------------------
INSERT INTO `barang` VALUES ('B001', 'HP LaserJet M1132 MFP', '1700000', '2000000', '-116', '10', '5', 'Baru', 'K01', 'images/HP LaserJet Pro M1132 MFP Printer.jpg');
INSERT INTO `barang` VALUES ('B002', 'HP LaserJet Pro P1102', '910000', '1200000', '2', '10', '5', 'Baru', 'K01', 'images/p1102.jpeg');
INSERT INTO `barang` VALUES ('B003', 'Canon LBP 2900', '1250000', '1500000', '0', '10', '0', 'Bekas', 'K01', 'images/canon2900.jpg');
INSERT INTO `barang` VALUES ('B004', 'Samsung Laser ML-1640', '555000', '850000', '6', '10', '5', 'Bekas', 'K01', 'images/ml1640.jpg');
INSERT INTO `barang` VALUES ('B005', 'Samsung Laser ML-2580N', '1700000', '2000000', '3', '10', '5', 'Baru', 'K01', 'images/ml580.jpg');
INSERT INTO `barang` VALUES ('B006', 'Samsung Laser Printer ML-2850D', '1880000', '2200000', '4', '10', '5', 'Baru', 'K01', 'images/ml2850D.jpg');
INSERT INTO `barang` VALUES ('B007', 'Printer Canon LBP 3150', '1250000', '1700000', '-1', '10', '0', 'Bekas', 'K01', 'images/lbp3150.jpg');
INSERT INTO `barang` VALUES ('B008', 'Printer Canon LBP 6000', '1500000', '1800000', '5', '10', '5', 'Bekas', 'K01', 'images/lbp6000.jpg');
INSERT INTO `barang` VALUES ('B009', 'Canon LBP 5050', '3600000', '4000000', '8', '10', '0', 'Baru', 'K01', 'images/canon5050.jpg');
INSERT INTO `barang` VALUES ('B010', 'HP LaserJet P1566', '1900000', '2200000', '5', '10', '5', 'Baru', 'K01', 'images/p1566.png');
INSERT INTO `barang` VALUES ('B011', 'Panasonic KX MB 2010', '2200000', '2500000', '-14', '10', '0', 'Baru', 'K01', 'images/mb2010.jpg');
INSERT INTO `barang` VALUES ('B012', 'Panasonic KX MB 772 CX', '2000000', '2300000', '5', '10', '5', 'Baru', 'K01', 'images/kxmb772.jpg');
INSERT INTO `barang` VALUES ('B013', 'HP LaserJet P2035', '2550000', '3000000', '5', '10', '0', 'Baru', 'K01', 'images/P2035.jpg');
INSERT INTO `barang` VALUES ('B014', 'HP LaserJet P2055D', '4300000', '4700000', '5', '10', '5', 'Baru', 'K01', 'images/P2055D.jpg');
INSERT INTO `barang` VALUES ('B015', 'Epson T60', '1600000', '2000000', '4', '10', '0', 'Baru', 'K01', 'images/t60.jpg');
INSERT INTO `barang` VALUES ('B016', 'coba', '2000000', '2500000', '8', '10', '5', 'Baru', 'K06', '');

-- ----------------------------
-- Table structure for jasa
-- ----------------------------
DROP TABLE IF EXISTS `jasa`;
CREATE TABLE `jasa` (
  `kd_jasa` char(4) NOT NULL,
  `nama_jasa` varchar(50) DEFAULT NULL,
  `harga_jasa` int(10) NOT NULL,
  `kd_bahan` char(4) NOT NULL,
  `diskon` float(3,0) NOT NULL,
  `ppn` float(3,0) NOT NULL,
  `kd_kategori` char(3) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`kd_jasa`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of jasa
-- ----------------------------
INSERT INTO `jasa` VALUES ('J001', 'Refil HP C7115A', '100000', 'BB01', '5', '10', 'BB0', 'Baru');
INSERT INTO `jasa` VALUES ('J002', 'Refil HP CB435', '110000', 'BB02', '5', '10', 'K02', 'Baru');
INSERT INTO `jasa` VALUES ('J003', 'Refil HP CB436', '110000', 'BB03', '5', '10', 'K02', 'Baru');
INSERT INTO `jasa` VALUES ('J004', 'Refil HP Q1338A', '250000', 'BB06', '5', '10', 'K02', 'Baru');
INSERT INTO `jasa` VALUES ('J005', 'Refil HP Q2612A', '100000', 'BB04', '5', '10', 'K02', 'Baru');
INSERT INTO `jasa` VALUES ('J006', 'Refil Canon Ep-25', '100000', 'BB07', '5', '10', 'K02', 'Baru');
INSERT INTO `jasa` VALUES ('J007', 'Refil Canon EP-26', '125000', 'BB09', '5', '10', 'K02', 'Baru');
INSERT INTO `jasa` VALUES ('J008', 'Refil Samsung ML1610', '100000', 'BB08', '5', '10', 'K02', 'Baru');
INSERT INTO `jasa` VALUES ('J009', 'Refil Samsung ML1660', '100000', 'BB05', '5', '10', 'K02', 'Baru');
INSERT INTO `jasa` VALUES ('J010', 'Rekondisi HP C7115A', '250000', 'BB01', '5', '10', 'K03', 'Baru');
INSERT INTO `jasa` VALUES ('J011', 'Service Printer Epson T11', '185000', 'BB10', '5', '10', 'K04', 'Baru');
INSERT INTO `jasa` VALUES ('J012', 'Service Printer HP Fuser', '100000', 'BB11', '5', '10', 'K04', 'Baru');
INSERT INTO `jasa` VALUES ('J014', 'Service Lower Pressure 1010/1020/P1006', '150000', 'BB12', '5', '10', 'K04', 'Baru');
INSERT INTO `jasa` VALUES ('J013', 'Service OPC Drum HP Q2612A ', '100000', 'BB14', '5', '10', 'K04', 'Baru');
INSERT INTO `jasa` VALUES ('J015', 'Rekondisi HP CB435', '300000', 'BB02', '5', '10', 'K03', 'Baru');
INSERT INTO `jasa` VALUES ('J016', 'Rekondisi HP CB436', '300000', 'BB03', '5', '10', 'K03', 'Baru');
INSERT INTO `jasa` VALUES ('J017', 'Rekondisi HP Q2612A', '250000', 'BB04', '5', '10', 'K03', 'Baru');
INSERT INTO `jasa` VALUES ('J018', 'Rekondisi Canon EP-25', '250000', 'BB07', '5', '10', 'K03', 'Baru');
INSERT INTO `jasa` VALUES ('J019', 'Rekondisi Canon EP-26', '225000', 'BB09', '5', '10', 'K03', 'Baru');
INSERT INTO `jasa` VALUES ('J020', 'Rekondisi Samsung MLT 104', '350000', 'BB05', '5', '10', 'K03', 'Baru');
INSERT INTO `jasa` VALUES ('J021', 'ok', '600', 'BB17', '0', '0', 'K02', 'ket');
INSERT INTO `jasa` VALUES ('J022', '7', '5', 'BB17', '7', '7', 'K02', '7');

-- ----------------------------
-- Table structure for jasa_item
-- ----------------------------
DROP TABLE IF EXISTS `jasa_item`;
CREATE TABLE `jasa_item` (
  `no_transaksi` char(7) NOT NULL,
  `kd_jasa` varchar(4) NOT NULL,
  `kd_bahan` varchar(50) NOT NULL,
  `harga_jasa` int(10) NOT NULL,
  `jml_bahan` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of jasa_item
-- ----------------------------
INSERT INTO `jasa_item` VALUES ('TJ00001', 'J005', 'BB04', '0', '4');
INSERT INTO `jasa_item` VALUES ('TJ00001', 'J009', 'BB05', '0', '2');

-- ----------------------------
-- Table structure for kategori
-- ----------------------------
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `kd_kategori` char(3) NOT NULL,
  `nm_kategori` varchar(50) NOT NULL,
  `jns_kategori` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`kd_kategori`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kategori
-- ----------------------------
INSERT INTO `kategori` VALUES ('K06', 'PRINTER', '1');
INSERT INTO `kategori` VALUES ('K02', 'REFIL', '2');
INSERT INTO `kategori` VALUES ('K03', 'REKONDISI', '2');
INSERT INTO `kategori` VALUES ('K04', 'Service Sparepart', '2');

-- ----------------------------
-- Table structure for pelanggan
-- ----------------------------
DROP TABLE IF EXISTS `pelanggan`;
CREATE TABLE `pelanggan` (
  `kd_pelanggan` char(4) NOT NULL,
  `nm_pelanggan` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `kode_pos` varchar(20) NOT NULL,
  PRIMARY KEY (`kd_pelanggan`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of pelanggan
-- ----------------------------
INSERT INTO `pelanggan` VALUES ('P001', 'PT ACSI', 'Ruko Klampis Megah B-25, Klampis Surabaya', '031-5910897', '60117');
INSERT INTO `pelanggan` VALUES ('P002', 'PT ALAM DIAN RAYA', 'Jl. Berbek Industri III Kav. 7-11', '031-8432140', '60293');
INSERT INTO `pelanggan` VALUES ('P003', 'PT BPR SAHABAT SEJATI', 'Jl. Sedati Agung No. 20, Sedati-Sidoarjo', '031-8666242-243', '61253');
INSERT INTO `pelanggan` VALUES ('P004', 'CAR CLINIC', 'Jl. Muncul No.5-7, Gedangan-Sidoarjo', '031-8555500', '61254');
INSERT INTO `pelanggan` VALUES ('P005', 'PT CIPTA BUSANA JAYA', 'Jl. Raya Gedangan No. 214, Gedangan-Sidoarjo', '0318912601', '61254');
INSERT INTO `pelanggan` VALUES ('P006', 'PT DINAMIKA MITRA SEJATI', 'Pondok Maspion Blok R-11, Waru-Sidoarjo', '0318554687', '61253');
INSERT INTO `pelanggan` VALUES ('P007', 'ERA KUSUMA', 'Jl. Raya Gedangan, Sidoarjo', '031-8556281', '61254');
INSERT INTO `pelanggan` VALUES ('P008', 'PT PRESTASI IDE JAYA', 'Jl. Raya Industri No. 17, Betro- Sedati-Sidoarjo', '0318910135', '61253');
INSERT INTO `pelanggan` VALUES ('P009', 'Koperasi Sindhu Arta', 'Jl. Raya Gedangan No. 175, Gedangan-Sidoarjo', '031-8010306', '61253');
INSERT INTO `pelanggan` VALUES ('P010', 'YAMAHA YES', 'Jl. Raya Gedangan No. 176, Gedangan-Sidoarjo', '031-8011011', '61253');
INSERT INTO `pelanggan` VALUES ('P011', 'YAMAMORI', 'Jl. Sedati No.1 Dusun Ketajen, Gedangan-Sidoarjo', '031-89114119', '61253');
INSERT INTO `pelanggan` VALUES ('P012', 'PT Makmur Jaya', 'Gedangan', '031-8912367', '61254');

-- ----------------------------
-- Table structure for pembelian
-- ----------------------------
DROP TABLE IF EXISTS `pembelian`;
CREATE TABLE `pembelian` (
  `no_pembelian` char(7) NOT NULL,
  `no_supplier` char(7) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `catatan` varchar(50) NOT NULL,
  `kd_supplier` char(3) NOT NULL,
  `userid` varchar(20) NOT NULL,
  PRIMARY KEY (`no_pembelian`),
  KEY `kd_supplier` (`kd_supplier`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of pembelian
-- ----------------------------
INSERT INTO `pembelian` VALUES ('BL00001', '', '2014-01-25', '2014-01-26', 'hutang', 'S06', 'admin');

-- ----------------------------
-- Table structure for pembelian_item
-- ----------------------------
DROP TABLE IF EXISTS `pembelian_item`;
CREATE TABLE `pembelian_item` (
  `no_pembelian` char(7) NOT NULL,
  `kd_item` varchar(4) NOT NULL,
  `kd_barang` varchar(4) NOT NULL,
  `kd_bahan` char(4) NOT NULL,
  `harga_beli` int(10) NOT NULL,
  `jumlah` int(5) NOT NULL,
  KEY `no_pembelian` (`no_pembelian`,`kd_barang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of pembelian_item
-- ----------------------------
INSERT INTO `pembelian_item` VALUES ('BL00001', 'BB15', '', '', '0', '2');
INSERT INTO `pembelian_item` VALUES ('BL00001', 'B011', '', '', '0', '1');

-- ----------------------------
-- Table structure for penjualan
-- ----------------------------
DROP TABLE IF EXISTS `penjualan`;
CREATE TABLE `penjualan` (
  `no_penjualan` char(7) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `kd_pelanggan` char(60) NOT NULL,
  `catatan` varchar(50) NOT NULL,
  `userid` varchar(20) NOT NULL,
  PRIMARY KEY (`no_penjualan`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of penjualan
-- ----------------------------
INSERT INTO `penjualan` VALUES ('JL00001', '2014-01-25', 'P003', 'lunas', 'admin');

-- ----------------------------
-- Table structure for penjualan_item
-- ----------------------------
DROP TABLE IF EXISTS `penjualan_item`;
CREATE TABLE `penjualan_item` (
  `no_penjualan` char(7) NOT NULL,
  `kd_barang` varchar(4) NOT NULL,
  `jumlah` int(3) NOT NULL,
  KEY `no_penjualan` (`no_penjualan`,`kd_barang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of penjualan_item
-- ----------------------------
INSERT INTO `penjualan_item` VALUES ('JL00001', 'B001', '2');
INSERT INTO `penjualan_item` VALUES ('JL00001', 'B011', '1');

-- ----------------------------
-- Table structure for returbeli
-- ----------------------------
DROP TABLE IF EXISTS `returbeli`;
CREATE TABLE `returbeli` (
  `no_returbeli` char(7) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `kd_supplier` char(3) NOT NULL,
  `userid` varchar(20) NOT NULL,
  PRIMARY KEY (`no_returbeli`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of returbeli
-- ----------------------------
INSERT INTO `returbeli` VALUES ('RB00001', '2014-01-25', 'S04', 'admin');
INSERT INTO `returbeli` VALUES ('RB00002', '2014-01-25', 'S02', 'admin');
INSERT INTO `returbeli` VALUES ('RB00003', '2014-01-25', 'S02', 'admin');

-- ----------------------------
-- Table structure for returbeli_item
-- ----------------------------
DROP TABLE IF EXISTS `returbeli_item`;
CREATE TABLE `returbeli_item` (
  `no_returbeli` char(7) NOT NULL,
  `kd_barang` char(6) NOT NULL,
  `kd_bahan` char(4) NOT NULL,
  `jumlah` int(3) NOT NULL,
  `catatan` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of returbeli_item
-- ----------------------------
INSERT INTO `returbeli_item` VALUES ('RB00001', '', '', '1', 'BUSUK');
INSERT INTO `returbeli_item` VALUES ('RB00001', 'B002', '', '2', 'CACAT');
INSERT INTO `returbeli_item` VALUES ('RB00002', 'B001', '', '2', 'rusak');
INSERT INTO `returbeli_item` VALUES ('RB00002', '', '', '1', 'encereen');
INSERT INTO `returbeli_item` VALUES ('RB00003', 'B015', '', '2', 'konslet');
INSERT INTO `returbeli_item` VALUES ('RB00003', '', 'BB10', '1', 'bejat kabeh ngono');

-- ----------------------------
-- Table structure for supplier
-- ----------------------------
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `kd_supplier` char(3) NOT NULL,
  `nm_supplier` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  PRIMARY KEY (`kd_supplier`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of supplier
-- ----------------------------
INSERT INTO `supplier` VALUES ('S01', 'LASER PRINTER', 'Wonorejo', '031-60223269');
INSERT INTO `supplier` VALUES ('S02', 'GLOBAL', 'Dukuh Kupang', '031-78216075');
INSERT INTO `supplier` VALUES ('S03', 'Apricom', 'Nginden', '031-71284303');
INSERT INTO `supplier` VALUES ('S04', 'Alfacom', 'Jl. Ngagel Jaya Selatan 94, Surabaya', '031-5027531');
INSERT INTO `supplier` VALUES ('S05', 'Duta Print', 'Jl. Brigjen Katamso', '031-8683843');
INSERT INTO `supplier` VALUES ('S06', 'ALFAPRINTER', 'Jl. Nginden Intan Raya No. 25', '031-71323915-16');
INSERT INTO `supplier` VALUES ('S07', 'FixPrint', 'Jl. Karah no. 123, Surabaya ', '031 - 8293358 ');

-- ----------------------------
-- Table structure for tmp_jasa
-- ----------------------------
DROP TABLE IF EXISTS `tmp_jasa`;
CREATE TABLE `tmp_jasa` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `kd_jasa` char(4) NOT NULL,
  `kd_bahan` varchar(50) NOT NULL,
  `harga_jasa` int(10) NOT NULL,
  `harga_bahan` int(10) NOT NULL,
  `jml_bahan` int(100) NOT NULL,
  `userid` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tmp_jasa
-- ----------------------------

-- ----------------------------
-- Table structure for tmp_pembelian
-- ----------------------------
DROP TABLE IF EXISTS `tmp_pembelian`;
CREATE TABLE `tmp_pembelian` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `kd_item` varchar(4) NOT NULL,
  `kd_barang` varchar(4) NOT NULL,
  `kd_bahan` char(4) NOT NULL,
  `harga_beli` int(10) NOT NULL,
  `qty` int(3) NOT NULL,
  `userid` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tmp_pembelian
-- ----------------------------

-- ----------------------------
-- Table structure for tmp_penjualan
-- ----------------------------
DROP TABLE IF EXISTS `tmp_penjualan`;
CREATE TABLE `tmp_penjualan` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `kd_barang` char(4) NOT NULL,
  `qty` int(3) NOT NULL,
  `userid` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=257 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tmp_penjualan
-- ----------------------------
INSERT INTO `tmp_penjualan` VALUES ('256', 'B001', '2', 'admin');

-- ----------------------------
-- Table structure for tmp_returbeli
-- ----------------------------
DROP TABLE IF EXISTS `tmp_returbeli`;
CREATE TABLE `tmp_returbeli` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `kd_barang` char(6) NOT NULL,
  `kd_bahan` char(6) NOT NULL,
  `qty` int(3) NOT NULL,
  `catatan` varchar(50) NOT NULL,
  `userid` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tmp_returbeli
-- ----------------------------

-- ----------------------------
-- Table structure for transaksi
-- ----------------------------
DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE `transaksi` (
  `no_transaksi` char(7) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `kd_pelanggan` char(4) NOT NULL,
  `catatan` varchar(50) NOT NULL,
  `userid` varchar(20) NOT NULL,
  PRIMARY KEY (`no_transaksi`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of transaksi
-- ----------------------------
INSERT INTO `transaksi` VALUES ('TJ00001', '2014-01-25', 'P010', '', 'admin');

-- ----------------------------
-- Table structure for user_login
-- ----------------------------
DROP TABLE IF EXISTS `user_login`;
CREATE TABLE `user_login` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `userid` char(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `level` enum('Kasir','Admin') NOT NULL DEFAULT 'Kasir',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user_login
-- ----------------------------
INSERT INTO `user_login` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'winda', 'Admin');
INSERT INTO `user_login` VALUES ('2', 'kasir', 'c7911af3adbd12a035b289556d96470a', 'fitri', 'Kasir');
INSERT INTO `user_login` VALUES ('5', 'Kasir1', '1116d70f3af1555dd95b361a7619eb1b', 'vita', 'Kasir');
