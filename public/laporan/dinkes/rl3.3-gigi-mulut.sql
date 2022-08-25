SELECT '35Prop' AS 'KODE PROPINSI', 'Kabupaten Situbondo' AS 'KAB-KOTA', '3512037' AS "KODE RS",
  'RS UMUM DAERAH ASEMBAGUS' AS 'NAMA RS',YEAR(kun_tgcheckin) AS 'TAHUN', tin_nama AS "JENIS KEGIATAN",
YEAR(kun_tgcheckin) AS 'TAHUN'
FROM rms_tindakan
LEFT JOIN rms_kunbiaya ON tin_id=kbi_tin_id AND kbi_jns_id="T"
LEFT JOIN rms_kunjungan ON kun_id=kbi_kun_id AND kun_tgcheckin BETWEEN '2021-01-01' AND '2022-01-01'
WHERE tin_yan_id=107 
GROUP BY tin_nama
ORDER BY COUNT(tin_nama) DESC