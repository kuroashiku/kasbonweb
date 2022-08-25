-- -----------------------------------------------------
-- Table dks_layanan
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS dks_layanan (
  dyn_id INT NOT NULL,
  dyn_nama VARCHAR(45) NULL,
  dyn_prop VARCHAR(15) NULL,
  PRIMARY KEY (dyn_id))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table dks_grptindakan
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS dks_grptindakan (
  dgti_id INT NOT NULL,
  dgti_nama VARCHAR(100) NULL,
  dgti_lok_id INT NULL,
  PRIMARY KEY (dgti_id))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table dks_tindakan
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS dks_tindakan (
  dtin_id INT NOT NULL,
  dtin_nama VARCHAR(100) NULL,
  dtin_satuan VARCHAR(15) NULL,
  dtin_status VARCHAR(15) NULL,
  PRIMARY KEY (dtin_id))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table dks_grpharga
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS dks_grpharga (
  dghr_id INT NOT NULL,
  dghr_nama VARCHAR(30) NULL,
  PRIMARY KEY (dghr_id))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table dks_hargatindakan
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS dks_hargatindakan (
  dhti_id INT NOT NULL,
  dhti_dtin_id INT NOT NULL,
  dhti_dgti_id INT NOT NULL,
  dhti_dghr_id INT NOT NULL,
  dhti_harga INT NULL,
  PRIMARY KEY (dhti_id),
  INDEX fk_dks_hargatindakan_dks_tindakan1_idx (dhti_dtin_id ASC),
  INDEX fk_dks_hargatindakan_dks_grptindakan1_idx (dhti_dgti_id ASC),
  INDEX fk_dks_hargatindakan_dks_grpharga1_idx (dhti_dghr_id ASC),
  CONSTRAINT fk_dks_hargatindakan_dks_tindakan1
    FOREIGN KEY (dhti_dtin_id)
    REFERENCES dks_tindakan (dtin_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_dks_hargatindakan_dks_grptindakan1
    FOREIGN KEY (dhti_dgti_id)
    REFERENCES dks_grptindakan (dgti_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_dks_hargatindakan_dks_grpharga1
    FOREIGN KEY (dhti_dghr_id)
    REFERENCES dks_grpharga (dghr_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
