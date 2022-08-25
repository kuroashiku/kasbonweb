SELECT
  '3512037' AS "KODE RS",
  'RS UMUM DAERAH ASEMBAGUS' AS 'NAMA RS',
  DATE_FORMAT(kun_tgcheckin, '%M') AS 'BULAN',
  YEAR(kun_tgcheckin) AS 'TAHUN',
  'Kabupaten Situbondo' AS 'KAB-KOTA',
  '35Prop' AS 'KODE PROPINSI', 
  '1' AS 'NO',
  'Penyakit Dalam' AS 'JENIS KEGIATAN',
  COUNT(CASE WHEN man_kelamin='L' THEN 1 END) AS L,
  COUNT(CASE WHEN man_kelamin='P' THEN 1 END) AS P,
  COUNT(*) AS JUMLAH
FROM rms_kunjungan
LEFT JOIN rms_layanan ON kun_yan_id=yan_id
LEFT JOIN rms_manusia ON kun_man_id=man_id
WHERE yan_nama='Poli Dalam dan Jantung' AND kun_tgcheckin BETWEEN '2021-06-01' AND '2021-07-01'
UNION
SELECT
  '3512037' AS "KODE RS",
  'RS UMUM DAERAH ASEMBAGUS' AS 'NAMA RS',
  DATE_FORMAT(kun_tgcheckin, '%M') AS 'BULAN',
  YEAR(kun_tgcheckin) AS 'TAHUN',
  'Kabupaten Situbondo' AS 'KAB/KOTA',
  '35Prop' AS 'KODE PROPINSI', 
  '2' AS 'NO',
  'Bedah' AS 'JENIS KEGIATAN',
  COUNT(CASE WHEN man_kelamin='L' THEN 1 END) AS L,
  COUNT(CASE WHEN man_kelamin='P' THEN 1 END) AS P,
  COUNT(*) AS JUMLAH
FROM rms_kunjungan
LEFT JOIN rms_layanan ON kun_yan_id=yan_id
LEFT JOIN rms_manusia ON kun_man_id=man_id
WHERE yan_nama='Poli Bedah' AND kun_tgcheckin BETWEEN '2021-06-01' AND '2021-07-01'
UNION
SELECT
  '3512037' AS "KODE RS",
  'RS UMUM DAERAH ASEMBAGUS' AS 'NAMA RS',
  DATE_FORMAT(kun_tgcheckin, '%M') AS 'BULAN',
  YEAR(kun_tgcheckin) AS 'TAHUN',
  'Kabupaten Situbondo' AS 'KAB/KOTA',
  '35Prop' AS 'KODE PROPINSI', 
  '16' AS 'NO',
  'Gigi & Mulut' AS 'JENIS KEGIATAN',
  COUNT(CASE WHEN man_kelamin='L' THEN 1 END) AS L,
  COUNT(CASE WHEN man_kelamin='P' THEN 1 END) AS P,
  COUNT(*) AS JUMLAH
FROM rms_kunjungan
LEFT JOIN rms_layanan ON kun_yan_id=yan_id
LEFT JOIN rms_manusia ON kun_man_id=man_id
WHERE yan_nama='Poli Gigi' AND kun_tgcheckin BETWEEN '2021-06-01' AND '2021-07-01'
UNION
SELECT
  '3512037' AS "KODE RS",
  'RS UMUM DAERAH ASEMBAGUS' AS 'NAMA RS',
  DATE_FORMAT(kun_tgcheckin, '%M') AS 'BULAN',
  YEAR(kun_tgcheckin) AS 'TAHUN',
  'Kabupaten Situbondo' AS 'KAB/KOTA',
  '35Prop' AS 'KODE PROPINSI', 
  '23' AS 'NO',
  'Umum' AS 'JENIS KEGIATAN',
  COUNT(CASE WHEN man_kelamin='L' THEN 1 END) AS L,
  COUNT(CASE WHEN man_kelamin='P' THEN 1 END) AS P,
  COUNT(*) AS JUMLAH
FROM rms_kunjungan
LEFT JOIN rms_layanan ON kun_yan_id=yan_id
LEFT JOIN rms_manusia ON kun_man_id=man_id
WHERE yan_nama='Poli Umum' AND kun_tgcheckin BETWEEN '2021-06-01' AND '2021-07-01'
UNION
SELECT
  '3512037' AS "KODE RS",
  'RS UMUM DAERAH ASEMBAGUS' AS 'NAMA RS',
  DATE_FORMAT(kun_tgcheckin, '%M') AS 'BULAN',
  YEAR(kun_tgcheckin) AS 'TAHUN',
  'Kabupaten Situbondo' AS 'KAB/KOTA',
  '35Prop' AS 'KODE PROPINSI', 
  '24' AS 'NO',
  'Rawat Darurat' AS 'JENIS KEGIATAN',
  COUNT(CASE WHEN man_kelamin='L' THEN 1 END) AS L,
  COUNT(CASE WHEN man_kelamin='P' THEN 1 END) AS P,
  COUNT(*) AS JUMLAH
FROM rms_kunjungan
LEFT JOIN rms_layanan ON kun_yan_id=yan_id
LEFT JOIN rms_manusia ON kun_man_id=man_id
WHERE yan_nama='Instalasi Gawat Darurat' AND kun_tgcheckin BETWEEN '2021-06-01' AND '2021-07-01'