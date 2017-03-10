SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `himevico` ;
CREATE SCHEMA IF NOT EXISTS `himevico` DEFAULT CHARACTER SET utf8 ;
USE `himevico` ;

-- -----------------------------------------------------
-- Table `himevico`.`ausencia`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`ausencia` ;

CREATE TABLE IF NOT EXISTS `himevico`.`ausencia` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(45) NOT NULL,
  `color` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

INSERT INTO `himevico`.`ausencia` (`tipo`,`color`) VALUES ("Permiso", "Azul");
INSERT INTO `himevico`.`ausencia` (`tipo`,`color`) VALUES ("Enfermedad profesional", "Amarillo");
INSERT INTO `himevico`.`ausencia` (`tipo`,`color`) VALUES ("Enfermedad comun", "Rosa");
INSERT INTO `himevico`.`ausencia` (`tipo`,`color`) VALUES ("Otros", "Gris");


-- -----------------------------------------------------
-- Table `himevico`.`empresas` comentario de prueba
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`empresas` ;

CREATE TABLE IF NOT EXISTS `himevico`.`empresas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `nif` VARCHAR(9) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (1,'Cove','123456');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------
-- Table `himevico`.`centros`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`centros` ;

CREATE TABLE IF NOT EXISTS `himevico`.`centros` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `idEmpresa` INT(11) NULL DEFAULT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `localizacion` VARCHAR(250) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `empresafk_idx` (`idEmpresa` ASC),
  CONSTRAINT `centro_empresa_FK`
    FOREIGN KEY (`idEmpresa`)
    REFERENCES `himevico`.`empresas` (`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `centros`
--

LOCK TABLES `centros` WRITE;
/*!40000 ALTER TABLE `centros` DISABLE KEYS */;
INSERT INTO `centros` VALUES (1,1,'Vitoria','C/Falsa');
/*!40000 ALTER TABLE `centros` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `himevico`.`estados`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`estados` ;

CREATE TABLE IF NOT EXISTS `himevico`.`estados` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `estados`
--

LOCK TABLES `estados` WRITE;
/*!40000 ALTER TABLE `estados` DISABLE KEYS */;
INSERT INTO `estados` VALUES (1,'Abierto'),(2,'Cerrado'),(3,'Validado'),(4,'Finalizado');
/*!40000 ALTER TABLE `estados` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------
-- Table `himevico`.`calendario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`calendario` ;

CREATE TABLE IF NOT EXISTS `himevico`.`calendario` (
  `id` INT(4) NOT NULL,
  `desc` TEXT NOT NULL,
  `estado` VARCHAR(1) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 21944
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `himevico`.`festivos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`festivos` ;

CREATE TABLE IF NOT EXISTS `himevico`.`festivos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `motivo` VARCHAR(45) NOT NULL,
  
   /* Para cuando se programe la parte de calendario */
   
   
  `centros_id` INT(11) NOT NULL,
  `calendario_id` INT(4) NOT NULL,
  
  PRIMARY KEY (`id`)
  ,
  INDEX `fk_festivos_centros1_idx` (`centros_id` ASC),
  INDEX `fk_festivos_calendario1_idx` (`calendario_id` ASC),
  CONSTRAINT `fk_festivos_centros1`
    FOREIGN KEY (`centros_id`)
    REFERENCES `himevico`.`centros` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `himevico`.`tipos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`tipos` ;

CREATE TABLE IF NOT EXISTS `himevico`.`tipos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `precio` DOUBLE NOT NULL,
  `tipo` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `tipos`
--

LOCK TABLES `tipos` WRITE;
/*!40000 ALTER TABLE `tipos` DISABLE KEYS */;
INSERT INTO `tipos` VALUES (1,15,'mañana'),(2,20,'tarde'),(3,30,'noche'),(4,20,'festivos');
/*!40000 ALTER TABLE `tipos` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `himevico`.`franjas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`franjas` ;

CREATE TABLE IF NOT EXISTS `himevico`.`franjas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `horaInicio` TIME NOT NULL,
  `horaFin` TIME NOT NULL,
  `idTipo` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_franjas_tipos1_idx` (`idTipo` ASC),
  CONSTRAINT `fk_franjas_tipos1`
    FOREIGN KEY (`idTipo`)
    REFERENCES `himevico`.`tipos` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 25
DEFAULT CHARACTER SET = utf8;


--
-- Dumping data for table `franjas`
--

LOCK TABLES `franjas` WRITE;
/*!40000 ALTER TABLE `franjas` DISABLE KEYS */;
INSERT INTO `franjas` VALUES (0,'00:00:00','01:00:00',3),(1,'01:00:00','02:00:00',3),(2,'02:00:00','03:00:00',3),(3,'03:00:00','04:00:00',3),(4,'04:00:00','05:00:00',3),(5,'05:00:00','06:00:00',3),(6,'06:00:00','07:00:00',1),(7,'07:00:00','08:00:00',1),(8,'08:00:00','09:00:00',1),(9,'09:00:00','10:00:00',1),(10,'10:00:00','11:00:00',1),(11,'11:00:00','12:00:00',1),(12,'12:00:00','13:00:00',1),(13,'13:00:00','14:00:00',1),(14,'14:00:00','15:00:00',2),(15,'15:00:00','16:00:00',2),(16,'16:00:00','17:00:00',2),(17,'17:00:00','18:00:00',2),(18,'18:00:00','19:00:00',2),(19,'19:00:00','20:00:00',2),(20,'20:00:00','21:00:00',2),(21,'21:00:00','22:00:00',2),(22,'22:00:00','23:00:00',3),(23,'23:00:00','00:00:00',3);
/*!40000 ALTER TABLE `franjas` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------
-- Table `himevico`.`horasconvenios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`horasconvenios` ;

CREATE TABLE IF NOT EXISTS `himevico`.`horasconvenios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `horasAnual` INT(11) NOT NULL,
  `denominacion` VARCHAR(45) NOT NULL,
  `idCentro` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fkcentro_idx` (`idCentro` ASC),
  CONSTRAINT `hc_centro_FK`
    FOREIGN KEY (`idCentro`)
    REFERENCES `himevico`.`centros` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


--
-- Dumping data for table `horasconvenios`
--

LOCK TABLES `horasconvenios` WRITE;
/*!40000 ALTER TABLE `horasconvenios` DISABLE KEYS */;
INSERT INTO `horasconvenios` VALUES (1,1750,'Convenio xxx',1);
/*!40000 ALTER TABLE `horasconvenios` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------
-- Table `himevico`.`perfiles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`perfiles` ;

CREATE TABLE IF NOT EXISTS `himevico`.`perfiles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(45) NOT NULL,
  `idHorasConvenio` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `perfiles__idx` (`idHorasConvenio` ASC),
  CONSTRAINT `perfiles_horasconvenio_FK`
    FOREIGN KEY (`idHorasConvenio`)
    REFERENCES `himevico`.`horasconvenios` (`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `perfiles`
--

LOCK TABLES `perfiles` WRITE;
/*!40000 ALTER TABLE `perfiles` DISABLE KEYS */;
INSERT INTO `perfiles` VALUES (1,'Gerencia',1),(2,'Administracion',1),(3,'Produccion',1),(4,'Logistica',1);
/*!40000 ALTER TABLE `perfiles` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `himevico`.`trabajadores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`trabajadores` ;

CREATE TABLE IF NOT EXISTS `himevico`.`trabajadores` (
  `dni` VARCHAR(9) NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `apellido1` VARCHAR(45) NOT NULL,
  `apellido2` VARCHAR(45) NOT NULL,
  `telefono` VARCHAR(45) NOT NULL,
  `idCentro` INT(11) NOT NULL,
  `idPerfil` INT(11) NOT NULL,
  `foto` VARCHAR(255) NULL DEFAULT 'Vista/Fotos/Default/foto.jpg',
  PRIMARY KEY (`dni`),
  INDEX `centrofk_idx` (`idCentro` ASC),
  INDEX `perfilfk_idx` (`idPerfil` ASC),
  CONSTRAINT `trabajador_centro_FK`
    FOREIGN KEY (`idCentro`)
    REFERENCES `himevico`.`centros` (`id`),
  CONSTRAINT `trabajador_perfil_FK`
    FOREIGN KEY (`idPerfil`)
    REFERENCES `himevico`.`perfiles` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `trabajadores`
--

LOCK TABLES `trabajadores` WRITE;
/*!40000 ALTER TABLE `trabajadores` DISABLE KEYS */;
INSERT INTO `trabajadores` VALUES ('12345678G','Ion','Jaureguialzo','Sarasola','333333333',1,4,'Vista/Fotos/Default/foto.jpg'),('14260521S','Oihane','Garcia','Bolumburu','321456754',1,2,'Vista/Fotos/Default/foto.jpg'),('22222222A','Pepe','ape1','ape2','123412341',1,2,'Vista/Fotos/Default/foto.jpg'),('33333333A','Mikel','ddvv','sdss','929281812',1,3,'Vista/Fotos/Default/foto.jpg'),('44444444A','Jon','Sanchez','Chica','6086491630',1,1,'Vista/Fotos/72827654V/IMAGEN-DE-PERFIL-FACEBOOK-619x346.jpg'),('99999999a','Josu','sdad','adsad','222111444',1,4,'Vista/Fotos/Default/foto.jpg');
/*!40000 ALTER TABLE `trabajadores` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `himevico`.`partesproduccion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`partesproduccion` ;

CREATE TABLE IF NOT EXISTS `himevico`.`partesproduccion` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `incidencia` VARCHAR(255) NULL DEFAULT NULL,
  `autopista` DOUBLE NULL DEFAULT NULL,
  `dieta` DOUBLE NULL DEFAULT NULL,
  `otroGasto` DOUBLE NULL DEFAULT NULL,
  `idEstado` INT(11) NULL DEFAULT NULL,
  `dniTrabajador` VARCHAR(9) NULL DEFAULT NULL,
  `horasExtra` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `Partesproduccion_trabajadores_FK_idx` (`dniTrabajador` ASC),
  INDEX `pp_estado_FK_idx` (`idEstado` ASC),
  CONSTRAINT `pp_estado_FK`
    FOREIGN KEY (`idEstado`)
    REFERENCES `himevico`.`estados` (`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `pp_trabajadores_FK`
    FOREIGN KEY (`dniTrabajador`)
    REFERENCES `himevico`.`trabajadores` (`dni`)
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `himevico`.`horariopartes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`horariopartes` ;

CREATE TABLE IF NOT EXISTS `himevico`.`horariopartes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `entrada` TIME NOT NULL,
  `salida` TIME NOT NULL,
  `idPartesProduccion` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_horarioPartes_partesproduccion1_idx` (`idPartesProduccion` ASC),
  CONSTRAINT `horariopartes_parteproduccion_fk`
    FOREIGN KEY (`idPartesProduccion`)
    REFERENCES `himevico`.`partesproduccion` (`id`)
    ON DELETE SET NULL)
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `horariopartes`
--

LOCK TABLES `horariopartes` WRITE;
/*!40000 ALTER TABLE `horariopartes` DISABLE KEYS */;
INSERT INTO `horariopartes` VALUES (1,'07:00:00','07:00:00',1),(7,'06:00:00','15:00:00',2);
/*!40000 ALTER TABLE `horariopartes` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `himevico`.`horarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`horarios` ;

CREATE TABLE IF NOT EXISTS `himevico`.`horarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `horarios`
--

LOCK TABLES `horarios` WRITE;
/*!40000 ALTER TABLE `horarios` DISABLE KEYS */;
INSERT INTO `horarios` VALUES (1,'Logistica-partida'),(2,'Producción-partida'),(3,'Logistica-mañana'),(4,'Producción-mañana'),(5,'Logistica-tarde'),(6,'Producción-tarde'),(7,'Logistica-noche'),(8,'Producción-noche');
/*!40000 ALTER TABLE `horarios` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `himevico`.`horariosfranja`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`horariosfranja` ;

CREATE TABLE IF NOT EXISTS `himevico`.`horariosfranja` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `idHorario` INT(11) NOT NULL,
  `idFranja` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `hf_franja_fk_idx` (`idFranja` ASC),
  INDEX `hf_horario_FK_idx` (`idHorario` ASC),
  CONSTRAINT `hf_franja_FK`
    FOREIGN KEY (`idFranja`)
    REFERENCES `himevico`.`franjas` (`id`),
  CONSTRAINT `hf_horario_FK`
    FOREIGN KEY (`idHorario`)
    REFERENCES `himevico`.`horarios` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 21
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `horariosfranja`
--

LOCK TABLES `horariosfranja` WRITE;
/*!40000 ALTER TABLE `horariosfranja` DISABLE KEYS */;
INSERT INTO `horariosfranja` VALUES (1,1,9),(2,1,10),(3,1,11),(4,1,12),(5,1,13),(6,1,14),(7,2,9),(8,2,10),(9,2,11),(10,2,12),(11,2,13),(12,2,14),(13,7,7),(14,7,8),(15,7,9),(16,7,10),(17,7,11),(18,7,12),(19,7,13),(20,7,14);
/*!40000 ALTER TABLE `horariosfranja` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------
-- Table `himevico`.`horariotrabajadores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`horariotrabajadores` ;

CREATE TABLE IF NOT EXISTS `himevico`.`horariotrabajadores` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dniTrabajador` VARCHAR(9) NOT NULL,
  `idHorario` INT(11) NOT NULL,
  `numeroSemana` INT(11) NOT NULL,
  `calendario_id` INT(4), /*para cuando se programe calendarios*/
  PRIMARY KEY (`id`),
  INDEX `ht_trabjadores_FK_idx` (`dniTrabajador` ASC),
  INDEX `ht_horario_FK_idx` (`idHorario` ASC),
  INDEX `fk_horariotrabajadores_calendario1_idx` (`calendario_id` ASC),
  CONSTRAINT `ht_horario_FK`
    FOREIGN KEY (`idHorario`)
    REFERENCES `himevico`.`horarios` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `ht_trabjadores_FK`
    FOREIGN KEY (`dniTrabajador`)
    REFERENCES `himevico`.`trabajadores` (`dni`)
    
   )
ENGINE = InnoDB
AUTO_INCREMENT = 107
DEFAULT CHARACTER SET = utf8;
--
-- Dumping data for table `horariotrabajadores`
--

LOCK TABLES `horariotrabajadores` WRITE;
/*!40000 ALTER TABLE `horariotrabajadores` DISABLE KEYS */;
INSERT INTO `horariotrabajadores` (`id`,`dniTrabajador`,`idHorario`,`numeroSemana`) VALUES (3,'12345678G',1,1),(4,'12345678G',1,2),(5,'12345678G',1,3),(6,'12345678G',1,4),(7,'12345678G',1,5),(8,'12345678G',1,6),(9,'12345678G',1,7),(10,'12345678G',1,8),(11,'12345678G',1,9),(12,'12345678G',1,10),(13,'12345678G',1,11),(14,'12345678G',1,12),(15,'12345678G',1,13),(16,'12345678G',1,14),(17,'12345678G',1,15),(18,'12345678G',1,16),(19,'12345678G',1,17),(20,'12345678G',1,18),(21,'12345678G',1,19),(22,'12345678G',1,20),(23,'12345678G',1,21),(24,'12345678G',1,22),(25,'12345678G',1,23),(26,'12345678G',1,24),(27,'12345678G',1,25),(28,'12345678G',1,26),(29,'12345678G',1,27),(30,'12345678G',1,28),(31,'12345678G',1,29),(32,'12345678G',1,30),(33,'12345678G',1,31),(34,'12345678G',1,32),(35,'12345678G',1,33),(36,'12345678G',1,34),(37,'12345678G',1,35),(38,'12345678G',1,36),(39,'12345678G',1,37),(40,'12345678G',1,38),(41,'12345678G',1,39),(42,'12345678G',1,40),(43,'12345678G',1,41),(44,'12345678G',1,42),(45,'12345678G',1,43),(46,'12345678G',1,44),(47,'12345678G',1,45),(48,'12345678G',1,46),(49,'12345678G',1,47),(50,'12345678G',1,48),(51,'12345678G',1,49),(52,'12345678G',1,50),(53,'12345678G',1,51),(54,'12345678G',1,52),(55,'33333333A',7,1),(56,'33333333A',7,2),(57,'33333333A',7,3),(58,'33333333A',7,4),(59,'33333333A',7,5),(60,'33333333A',7,6),(61,'33333333A',7,7),(62,'33333333A',7,8),(63,'33333333A',7,9),(64,'33333333A',7,10),(65,'33333333A',7,11),(66,'33333333A',7,12),(67,'33333333A',7,13),(68,'33333333A',7,14),(69,'33333333A',7,15),(70,'33333333A',7,16),(71,'33333333A',7,17),(72,'33333333A',7,18),(73,'33333333A',7,19),(74,'33333333A',7,20),(75,'33333333A',7,21),(76,'33333333A',7,22),(77,'33333333A',7,23),(78,'33333333A',7,24),(79,'33333333A',7,25),(80,'33333333A',7,26),(81,'33333333A',7,27),(82,'33333333A',7,28),(83,'33333333A',7,29),(84,'33333333A',7,30),(85,'33333333A',7,31),(86,'33333333A',7,32),(87,'33333333A',7,33),(88,'33333333A',7,34),(89,'33333333A',7,35),(90,'33333333A',7,36),(91,'33333333A',7,37),(92,'33333333A',7,38),(93,'33333333A',7,39),(94,'33333333A',7,40),(95,'33333333A',7,41),(96,'33333333A',7,42),(97,'33333333A',7,43),(98,'33333333A',7,44),(99,'33333333A',7,45),(100,'33333333A',7,46),(101,'33333333A',7,47),(102,'33333333A',7,48),(103,'33333333A',7,49),(104,'33333333A',7,50),(105,'33333333A',7,51),(106,'33333333A',7,52);
/*!40000 ALTER TABLE `horariotrabajadores` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `himevico`.`login`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`login` ;

CREATE TABLE IF NOT EXISTS `himevico`.`login` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dniTrabajador` VARCHAR(9) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `trabajadorFK_idx` (`dniTrabajador` ASC),
  CONSTRAINT `login_trabajador_FK`
    FOREIGN KEY (`dniTrabajador`)
    REFERENCES `himevico`.`trabajadores` (`dni`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = utf8;


--
-- Dumping data for table `login`
--

LOCK TABLES `login` WRITE;
/*!40000 ALTER TABLE `login` DISABLE KEYS */;
INSERT INTO `login` VALUES (1,'14260521S','827ccb0eea8a706c4c34a16891f84e7b'),(2,'22222222A','ab56b4d92b40713acc5af89985d4b786'),(9,'99999999a','202cb962ac59075b964b07152d234b70'),(10,'33333333A','827ccb0eea8a706c4c34a16891f84e7b'),(11,'44444444A','827ccb0eea8a706c4c34a16891f84e7b'),(12,'12345678G','827ccb0eea8a706c4c34a16891f84e7b');
/*!40000 ALTER TABLE `login` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------
-- Table `himevico`.`parteslogistica`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`parteslogistica` ;

CREATE TABLE IF NOT EXISTS `himevico`.`parteslogistica` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `nota` VARCHAR(255) NULL DEFAULT NULL,
  `autopista` DOUBLE NULL DEFAULT NULL,
  `dieta` DOUBLE NULL DEFAULT NULL,
  `otroGasto` VARCHAR(45) NULL DEFAULT NULL,
  `idEstado` INT(11) NULL DEFAULT NULL,
  `dniTrabajador` VARCHAR(9) NULL DEFAULT NULL,
  `horasExtra` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `trabajadorfk_idx` (`dniTrabajador` ASC),
  INDEX `pl_estado_fk_idx` (`idEstado` ASC),
  CONSTRAINT `pl_estado_fk`
    FOREIGN KEY (`idEstado`)
    REFERENCES `himevico`.`estados` (`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `pl_trabajador_FK`
    FOREIGN KEY (`dniTrabajador`)
    REFERENCES `himevico`.`trabajadores` (`dni`)
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS `himevico`.`parteslogistica` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dniTrabajador` VARCHAR(9) NULL DEFAULT NULL,
  `idEstado` INT(11) NULL DEFAULT NULL,
  `nota` VARCHAR(250) NULL DEFAULT NULL,
  `fecha` DATE NOT NULL,
  `horasExtra` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `trabajadorfk_idx` (`dniTrabajador` ASC),
  INDEX `pl_estado_fk_idx` (`idEstado` ASC),
  CONSTRAINT `pl_estado_fk`
    FOREIGN KEY (`idEstado`)
    REFERENCES `himevico`.`estados` (`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `pl_trabajador_FK`
    FOREIGN KEY (`dniTrabajador`)
    REFERENCES `himevico`.`trabajadores` (`dni`)
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `himevico`.`tipostarea`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`tipostarea` ;

CREATE TABLE IF NOT EXISTS `himevico`.`tipostarea` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(250) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `tipostarea`
--

LOCK TABLES `tipostarea` WRITE;
/*!40000 ALTER TABLE `tipostarea` DISABLE KEYS */;
INSERT INTO `tipostarea` VALUES (1,'Medios de Produccion'),(2,'Maquinaria'),(3,'Mantenimiento/Avería/Incidencia');
/*!40000 ALTER TABLE `tipostarea` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------
-- Table `himevico`.`tareas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`tareas` ;

CREATE TABLE IF NOT EXISTS `himevico`.`tareas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(250) NOT NULL,
  `idTipoTarea` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `tareas_tipostareas_FK_idx` (`idTipoTarea` ASC),
  CONSTRAINT `tareas_tipostareas_FK`
    FOREIGN KEY (`idTipoTarea`)
    REFERENCES `himevico`.`tipostarea` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `tareas`
--

LOCK TABLES `tareas` WRITE;
/*!40000 ALTER TABLE `tareas` DISABLE KEYS */;
INSERT INTO `tareas` VALUES (1,'Organizacion Planta',1),(2,'Cizalla',1),(3,'Prensa',1),(4,'Carga/Descarga de Camiones',1),(5,'Grua Portante/Iman',1),(6,'Soplete',1),(7,'Liebherr',2),(8,'Poclain-1188',2),(9,'Liebherr A 904 C IND',2),(10,'Cizalla',3),(11,'Prensa',3),(12,'Liebherr',3),(13,'Poclain-1188',3),(14,'Limpieza de planta',3),(15,'Limpieza de fosos',3);
/*!40000 ALTER TABLE `tareas` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `himevico`.`partesproducciontareas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`partesproducciontareas` ;

CREATE TABLE IF NOT EXISTS `himevico`.`partesproducciontareas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `idTareas` INT(11) NOT NULL,
  `idParteProduccion` INT(11) NOT NULL,
  `numeroHoras` DOUBLE NULL DEFAULT NULL,
  `paqueteEntrada` INT(11) NULL DEFAULT NULL,
  `paqueteSalida` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `ppt_pp_FK_idx` (`idParteProduccion` ASC),
  INDEX `ppt_tareas_FK` (`idTareas` ASC),
  CONSTRAINT `ppt_pp_FK`
    FOREIGN KEY (`idParteProduccion`)
    REFERENCES `himevico`.`partesproduccion` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `ppt_tareas_FK`
    FOREIGN KEY (`idTareas`)
    REFERENCES `himevico`.`tareas` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `himevico`.`ausenciastrabajadores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`ausenciastrabajadores` ;

CREATE TABLE IF NOT EXISTS `himevico`.`ausenciastrabajadores` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dniTrabajador` VARCHAR(9) NOT NULL,
  `idAusencia` INT(11) NOT NULL,
  `fecha` DATETIME NOT NULL,
  `horaInicio` DATETIME NOT NULL,
  `horaFin` DATETIME NOT NULL,
  `calendario_id` INT(4) NOT NULL,
  PRIMARY KEY (`id`, `calendario_id`),
  INDEX `ausencia_fk_idx` (`idAusencia` ASC),
  INDEX `trabajador_FK_idx` (`dniTrabajador` ASC),
  INDEX `fk_ausenciastrabajadores_calendario1_idx` (`calendario_id` ASC),
  CONSTRAINT `ta_ausencia_FK`
    FOREIGN KEY (`idAusencia`)
    REFERENCES `himevico`.`ausencia` (`id`),
  CONSTRAINT `ta_trabajador_FK`
    FOREIGN KEY (`dniTrabajador`)
    REFERENCES `himevico`.`trabajadores` (`dni`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `himevico`.`vehiculos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`vehiculos` ;

CREATE TABLE IF NOT EXISTS `himevico`.`vehiculos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `matricula` VARCHAR(45) NOT NULL,
  `marca` VARCHAR(45) NOT NULL,
  `idCentro` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `centrofk_idx` (`idCentro` ASC),
  CONSTRAINT `vehiculo_centro_FK`
    FOREIGN KEY (`idCentro`)
    REFERENCES `himevico`.`centros` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;

--
-- Dumping data for table `vehiculos`
--

LOCK TABLES `vehiculos` WRITE;
/*!40000 ALTER TABLE `vehiculos` DISABLE KEYS */;
INSERT INTO `vehiculos` VALUES (1,'111111','seat',1);
/*!40000 ALTER TABLE `vehiculos` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------
-- Table `himevico`.`viajes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`viajes` ;

CREATE TABLE IF NOT EXISTS `himevico`.`viajes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `horaInicio` TIME NOT NULL,
  `horaFin` TIME NOT NULL,
  `idVehiculo` INT(11) NULL DEFAULT NULL,
  `idParte` INT(11) NOT NULL,
  `albaran` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `parteLogistikafk_idx` (`idParte` ASC),
  INDEX `vehiculofk_idx` (`idVehiculo` ASC),
  CONSTRAINT `viajes_pl_FK`
    FOREIGN KEY (`idParte`)
    REFERENCES `himevico`.`parteslogistica` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `viajes_vehiculo_FK`
    FOREIGN KEY (`idVehiculo`)
    REFERENCES `himevico`.`vehiculos` (`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `himevico`.`festivosnacional`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`festivosnacional` ;

CREATE TABLE IF NOT EXISTS `himevico`.`festivosnacional` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `motivo` VARCHAR(45) NOT NULL,
  `calendario_id` INT(4) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_festivosnacional_calendario1_idx` (`calendario_id` ASC)
  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `himevico`.`vacacionestrabajadores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `himevico`.`vacacionestrabajadores` ;

CREATE TABLE IF NOT EXISTS `himevico`.`vacacionestrabajadores` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dniTrabajador` VARCHAR(9) NOT NULL,
  `fecha` DATETIME NOT NULL,
  `horaInicio` DATETIME NOT NULL,
  `horaFin` DATETIME NOT NULL,
  `calendario_id` INT(4) NOT NULL,
  `estado` VARCHAR(1) NOT NULL,
  PRIMARY KEY (`id`, `calendario_id`),
  INDEX `trabajador_FK_idx` (`dniTrabajador` ASC),
  INDEX `fk_ausenciastrabajadores_calendario1_idx` (`calendario_id` ASC),
  CONSTRAINT `ta_trabajador_FK0`
    FOREIGN KEY (`dniTrabajador`)
    REFERENCES `himevico`.`trabajadores` (`dni`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
