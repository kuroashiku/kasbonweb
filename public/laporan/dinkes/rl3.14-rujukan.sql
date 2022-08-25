SELECT '35Prop' AS 'KODE PROPINSI', 'Kabupaten Situbondo' AS 'KAB-KOTA', '3512037' AS "KODE RS",
  'RS UMUM DAERAH ASEMBAGUS' AS 'NAMA RS',YEAR(kun_tgcheckin) AS 'TAHUN', yan_nama AS "JENIS SPESIALISASI",
COUNT(CASE WHEN kun_rujukan='Puskesmas' THEN 1 END) AS "RUJUKAN_DITERIMA DARI PUSKESMAS",
COUNT(CASE WHEN kun_rujukan!='Puskesmas' AND kun_rujukan!='Rumah Sakit' AND kun_rujukan!='Sendiri' THEN 1 END) AS "RUJUKAN_DITERIMA DARI FASILITAS KES. LAIN",
COUNT(CASE WHEN kun_rujukan='Rumah Sakit' THEN 1 END) AS "RUJUKAN_DITERIMA DARI RS LAIN",
COUNT(CASE WHEN kun_rujukan!='Sendiri' THEN 1 END) 
 FROM rms_layanan 
LEFT JOIN rms_kunjungan ON yan_id=kun_yan_id AND kun_tgcheckin BETWEEN '2021-01-01' AND '2022-01-01'
WHERE yan_id<200 
GROUP BY yan_nama
ORDER BY COUNT(kun_yan_id) DESC