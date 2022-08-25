SELECT
	'35Prop' AS 'KODE PROPINSI', 
    'Kabupaten Situbondo' AS 'KAB-KOTA',
	'3512037' AS "KODE RS",
  	'RS UMUM DAERAH ASEMBAGUS' AS 'NAMA RS',
  	DATE_FORMAT(kun_tgcheckin, '%M') AS 'BULAN',
  	YEAR(kun_tgcheckin) AS 'TAHUN',
    @n:=@n+1 "No. urut",
  	kit_nama AS 'KODE ICD 10',
    '' AS 'DESKRIPSI',
  	COUNT(CASE WHEN man_kelamin='L' THEN 1 END) AS 'Kasus Baru menurut Jenis Kelamin LK',
  	COUNT(CASE WHEN man_kelamin='P' THEN 1 END) AS 'Kasus Baru menurut Jenis Kelamin PR',
    '' AS 'Jumlah Kasus Baru(4+5)',
  	COUNT(*) AS 'Jumlah Kunjungan' FROM rms_kunjungan
LEFT JOIN rms_manusia ON man_id = kun_man_id
LEFT JOIN rms_kunperiksa ON kpr_kun_id = kun_id
LEFT JOIN rms_penyakit ON kit_id = kpr_kit_id
CROSS JOIN (SELECT @n:=0) r
WHERE kun_tgcheckin BETWEEN '2021-06-01' AND '2021-07-01'
GROUP BY kpr_kit_id
ORDER BY COUNT(*) DESC
LIMIT 10