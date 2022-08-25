ALTER TABLE rms_kamar 
DROP COLUMN kmr_harga,
DROP COLUMN kmr_jumlahbed,
DROP COLUMN kmr_nama,
DROP INDEX kode_UNIQUE,
ADD COLUMN kmr_ruang VARCHAR(25) NULL AFTER kmr_nomorbed,
ADD COLUMN kmr_kelas_ruang INT NULL AFTER kmr_ruang,
ADD COLUMN kmr_tarif_ruang INT NULL AFTER kmr_kelas_ruang,
ADD COLUMN kmr_bangsal VARCHAR(25) NULL AFTER kmr_tarif_ruang,
CHANGE COLUMN kmr_kode kmr_nomorbed VARCHAR(10) NULL DEFAULT NULL;