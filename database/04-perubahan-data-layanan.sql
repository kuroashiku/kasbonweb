UPDATE rms_layanan SET yan_nama = 'Penyakit Dalam' WHERE (yan_id = '105');
UPDATE rms_layanan SET yan_nama = 'Kesehatan Anak' WHERE (yan_id = '109');
UPDATE rms_layanan SET yan_nama = 'Obstetri' WHERE (yan_id = '106');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('115', 'GIN', 'Ginekologi');
UPDATE rms_layanan SET yan_nama = 'Bedah' WHERE (yan_id = '103');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('116', 'BOP', 'Bedah Orthopedi');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('117', 'BSR', 'Bedah Saraf');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('118', 'LBK', 'Luka Bakar');
UPDATE rms_layanan SET yan_nama = 'Saraf' WHERE (yan_id = '108');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('119', 'JWA', 'Jiwa');
UPDATE rms_layanan SET yan_kode = 'PSI', yan_nama = 'Psikologi' WHERE (yan_id = '111');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('120', 'NAP', 'Penatalaksana Pnyguna. NAPZA');
UPDATE rms_layanan SET yan_nama = 'THT' WHERE (yan_id = '104');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('121', 'MTA', 'Mata');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('122', 'KEL', 'Kulit dan Kelamin');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('123', 'KRD', 'Kardiologi');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('124', 'PRU', 'Paru-paru');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('125', 'GER', 'Geriatri');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('126', 'RTR', 'Radioterapi');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('127', 'DNU', 'Kedokteran Nuklir');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('128', 'KUS', 'Kusta');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('130', 'RMD', 'Rehabilitasi Medik');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('131', 'ISO', 'Isolasi');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('132', 'ICU', 'ICU');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('133', 'ICC', 'ICCU');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('134', 'NIC', 'NICU/PICU');
UPDATE rms_layanan SET yan_nama = 'Umum' WHERE (yan_id = '102');
UPDATE rms_layanan SET yan_nama = 'Gigi dan Mulut' WHERE (yan_id = '107');
UPDATE rms_layanan SET yan_nama = 'TB DOTS' WHERE (yan_id = '114');
INSERT INTO rms_layanan (yan_id, yan_kode, yan_nama) VALUES ('135', 'BYI', 'Perinatologi');

-- *******************************************************
-- Memasukkan group klinik ke daftar layanan di rms_lokasi
-- *******************************************************

ALTER TABLE rms_lokasi CHANGE COLUMN lok_yan_ids lok_yan_ids VARCHAR(500) NULL DEFAULT NULL;
UPDATE rms_lokasi SET lok_yan_ids = 'Umum:102,113;Bedah:103;THT:104;Internis:105;Kandungan:106;Gigi:107;Anak:109;IGD:110;Paru:114' WHERE (lok_id = '12');
