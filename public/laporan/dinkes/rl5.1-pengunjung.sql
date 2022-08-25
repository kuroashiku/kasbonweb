SELECT
    '3512037' AS "KODE RS",
    'RS UMUM DAERAH ASEMBAGUS' AS 'NAMA RS',
    DATE_FORMAT(a.kun_tgcheckin, '%M') AS 'BULAN',
    YEAR(a.kun_tgcheckin) AS 'TAHUN',
    'Kabupaten Situbondo' AS 'KAB-KOTA',
    '35Prop' AS 'KODE PROPINSI', 
    '1' AS 'NO',
    IF(x.kun_id IS NULL, 'Pengunjung Baru', 'Pengunjung Lama') 'JENIS KEGIATAN',
    COUNT(CASE WHEN man_kelamin='L' THEN 1 END) AS L,
    COUNT(CASE WHEN man_kelamin='P' THEN 1 END) AS P,
    COUNT(*) AS JUMLAH
FROM rms_kunjungan a
LEFT JOIN rms_manusia ON a.kun_man_id=man_id
LEFT JOIN rms_kunjungan x ON x.kun_man_id=a.kun_man_id AND x.kun_id<a.kun_id
WHERE a.kun_tgcheckin BETWEEN '2021-06-01' AND '2021-07-01'
GROUP BY DATE_FORMAT(a.kun_tgcheckin, '%Y%m'), IF(x.kun_id IS NULL, 'Pengunjung Baru', 'Pengunjung Lama')
ORDER BY DATE_FORMAT(a.kun_tgcheckin, '%Y%m')
