update emp_jabatan set jab_com_id=13 where jab_com_id=10;
INSERT INTO emp_jabatan (jab_id,jab_nama,jab_tipe,jab_harga,jab_com_id) VALUES
    (31,'Dokter','D',40000,10),
    (32,'Perawat','P',30000,10),
    (33,'Staff','S',25000,10);
ALTER TABLE rms_kunbiaya 
    DROP FOREIGN KEY fk_rms_kunbiaya_rms_tindakan1;
ALTER TABLE rms_kunbiaya 
    DROP INDEX fk_rms_kunbiaya_rms_tindakan1_idx;
ALTER TABLE rms_kunbiaya 
    DROP COLUMN kbi_dtin_id;
ALTER TABLE rms_lokasi 
    ADD COLUMN lok_jenis VARCHAR(45) NULL AFTER lok_yan_ids;
UPDATE rms_lokasi SET lok_jenis = '2' WHERE (lok_id = '11');
UPDATE rms_lokasi SET lok_jenis = '1' WHERE (lok_id = '12');
UPDATE rms_lokasi SET lok_jenis = '1' WHERE (lok_id = '13');
UPDATE rms_lokasi SET lok_jenis = '2' WHERE (lok_id = '14');

