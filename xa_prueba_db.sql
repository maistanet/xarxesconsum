/*CREATE DATABASE  IF NOT EXISTS `` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
/*USE ``;
-- MySQL dump 10.13  Distrib 5.5.29, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: xa_prueba_db
-- ------------------------------------------------------
-- Server version	5.5.29-0ubuntu0.12.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producte`
--

DROP TABLE IF EXISTS `producte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actiu` tinyint(1) NOT NULL,
  `id_productor` int(9) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nom_producte` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `descripcio` text COLLATE utf8_unicode_ci NOT NULL,
  `format` enum('Kg','gr','unitat') COLLATE utf8_unicode_ci NOT NULL,
  `preu` float NOT NULL,
  `comentari` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producte`
--

LOCK TABLES `producte` WRITE;
/*!40000 ALTER TABLE `producte` DISABLE KEYS */;
/*!40000 ALTER TABLE `producte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `correu`
--

DROP TABLE IF EXISTS `correu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `correu` (
  `correu` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `correu`
--

LOCK TABLES `correu` WRITE;
/*!40000 ALTER TABLE `correu` DISABLE KEYS */;
INSERT INTO `correu` (`correu`, `text`) VALUES ('EMAIL_GC', '<h3>\r\n	<strong>Grup de consum NOMBRE_GC.</strong></h3>\r\n');
/*!40000 ALTER TABLE `correu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productors`
--

DROP TABLE IF EXISTS `productors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productors` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `nom_productor` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `localitat` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `posicio` text COLLATE utf8_unicode_ci NOT NULL,
  `descripcio` text COLLATE utf8_unicode_ci NOT NULL,
  `contacte` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `correu` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `socis` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productors`
--

LOCK TABLES `productors` WRITE;
/*!40000 ALTER TABLE `productors` DISABLE KEYS */;
/*!40000 ALTER TABLE `productors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saldo`
--

DROP TABLE IF EXISTS `saldo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saldo` (
  `id_uc` int(8) NOT NULL,
  `id_moneda` int(8) NOT NULL,
  `saldo` float NOT NULL,
  PRIMARY KEY (`id_uc`,`id_moneda`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saldo`
--

LOCK TABLES `saldo` WRITE;
/*!40000 ALTER TABLE `saldo` DISABLE KEYS */;
/*!40000 ALTER TABLE `saldo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moneda`
--

DROP TABLE IF EXISTS `moneda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moneda` (
  `id` int(8) NOT NULL,
  `nombre` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moneda`
--

LOCK TABLES `moneda` WRITE;
/*!40000 ALTER TABLE `moneda` DISABLE KEYS */;
INSERT INTO `moneda` VALUES (1,'&euro;');
/*!40000 ALTER TABLE `moneda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unitat_c`
--

DROP TABLE IF EXISTS `unitat_c`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unitat_c` (
  `id` int(8) NOT NULL,
  `user` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `correu` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `n_caixa` int(8) NOT NULL,
  `fecha_alta` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unitat_c`
--

LOCK TABLES `unitat_c` WRITE;
/*!40000 ALTER TABLE `unitat_c` DISABLE KEYS */;
INSERT INTO `unitat_c` VALUES (0,'admin','admin','admin','email',0,CURDATE());
/*!40000 ALTER TABLE `unitat_c` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comanda`
--

DROP TABLE IF EXISTS `comanda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comanda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `id_unitat_c` int(8) NOT NULL,
  `t_producte` text COLLATE utf8_unicode_ci NOT NULL,
  `t_quantitat` text COLLATE utf8_unicode_ci NOT NULL,
  `t_pvp` text COLLATE utf8_unicode_ci NOT NULL,
  `total` float NOT NULL,
  `observacions` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comanda`
--

LOCK TABLES `comanda` WRITE;
/*!40000 ALTER TABLE `comanda` DISABLE KEYS */;
/*!40000 ALTER TABLE `comanda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `nom_xarxa` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `dia_limit` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `dia_venda` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `hora_limit` time NOT NULL,
  `descripcio` text COLLATE utf8_unicode_ci NOT NULL,
  `avisos` text COLLATE utf8_unicode_ci NOT NULL,
  `limit_comanda_saldo` enum('off','si','no') COLLATE utf8_unicode_ci NOT NULL,
  `panel_pedido_activo` tinyint(1) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES ('XÃ¡rxa de prueba','Sunday','Tuesday','19:00:00','<h3>\r\n	Xarxa de prueba.</h3>\r\n<p>\r\n	Aquesta es la descripci&oacute; de la p&aacute;gina de entrada de la xarxa de prueba.</p>\r\n','','off','1');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-01-28 21:25:46
