DROP VIEW IF EXISTS emp_statistik_view;
CREATE VIEW emp_statistik_view AS SELECT
  '35Prop' kode_prop,
	'3512037' kode_rs,
  'Kabupaten Situbondo' kab_kota,
  'RS Umum Daerah Asembagus Kabupaten Situbondo' nama_rs,
  sta_tahun tahun,
  sta_kua_kode no_kode,
  kua_nama kualifikasi_pendidikan,
  IF(MOD(sta_kua_kode,10000)=0,
    (SELECT SUM(subq.sta_keadaan_lakilaki) FROM emp_statistik subq
    WHERE MOD(subq.sta_kua_kode,100)<>0 AND
      FLOOR(subq.sta_kua_kode/10000)=FLOOR(main.sta_kua_kode/10000)
      AND subq.sta_tahun=main.sta_tahun),
    IF(MOD(sta_kua_kode,100)=0,
      (SELECT SUM(subq.sta_keadaan_lakilaki) FROM emp_statistik subq
      WHERE MOD(subq.sta_kua_kode,100)<>0 AND
        FLOOR(subq.sta_kua_kode/100)=FLOOR(main.sta_kua_kode/100)
        AND subq.sta_tahun=main.sta_tahun),
      sta_keadaan_lakilaki)) keadaan_lakilaki,
  IF(MOD(sta_kua_kode,10000)=0,
    (SELECT SUM(subq.sta_keadaan_perempuan) FROM emp_statistik subq
    WHERE MOD(subq.sta_kua_kode,100)<>0 AND
      FLOOR(subq.sta_kua_kode/10000)=FLOOR(main.sta_kua_kode/10000)
      AND subq.sta_tahun=main.sta_tahun),
    IF(MOD(sta_kua_kode,100)=0,
      (SELECT SUM(subq.sta_keadaan_perempuan) FROM emp_statistik subq
      WHERE MOD(subq.sta_kua_kode,100)<>0 AND
        FLOOR(subq.sta_kua_kode/100)=FLOOR(main.sta_kua_kode/100)
        AND subq.sta_tahun=main.sta_tahun),
      sta_keadaan_perempuan)) keadaan_perempuan,
  IF(MOD(sta_kua_kode,10000)=0,
    (SELECT SUM(subq.sta_kebutuhan_lakilaki) FROM emp_statistik subq
    WHERE MOD(subq.sta_kua_kode,100)<>0 AND
      FLOOR(subq.sta_kua_kode/10000)=FLOOR(main.sta_kua_kode/10000)
      AND subq.sta_tahun=main.sta_tahun),
    IF(MOD(sta_kua_kode,100)=0,
      (SELECT SUM(subq.sta_kebutuhan_lakilaki) FROM emp_statistik subq
      WHERE MOD(subq.sta_kua_kode,100)<>0 AND
        FLOOR(subq.sta_kua_kode/100)=FLOOR(main.sta_kua_kode/100)
        AND subq.sta_tahun=main.sta_tahun),
      sta_kebutuhan_lakilaki)) kebutuhan_lakilaki,
  IF(MOD(sta_kua_kode,10000)=0,
    (SELECT SUM(subq.sta_kebutuhan_perempuan) FROM emp_statistik subq
    WHERE MOD(subq.sta_kua_kode,100)<>0 AND
      FLOOR(subq.sta_kua_kode/10000)=FLOOR(main.sta_kua_kode/10000)
      AND subq.sta_tahun=main.sta_tahun),
    IF(MOD(sta_kua_kode,100)=0,
      (SELECT SUM(subq.sta_kebutuhan_perempuan) FROM emp_statistik subq
      WHERE MOD(subq.sta_kua_kode,100)<>0 AND
        FLOOR(subq.sta_kua_kode/100)=FLOOR(main.sta_kua_kode/100)
        AND subq.sta_tahun=main.sta_tahun),
      sta_kebutuhan_perempuan)) kebutuhan_perempuan,
  IF(sta_keadaan_lakilaki >= sta_kebutuhan_lakilaki, 0,
    sta_kebutuhan_lakilaki-sta_keadaan_lakilaki) kekurangan_lakilaki,
  IF(sta_keadaan_perempuan >= sta_kebutuhan_perempuan, 0,
    sta_kebutuhan_perempuan-sta_keadaan_perempuan) kekurangan_perempuan
FROM emp_statistik main
LEFT JOIN emp_kualifikasi ON kua_kode=sta_kua_kode
WHERE sta_tahun=2021;

SELECT kode_prop 'KODE PROP', kode_rs 'KODE RS', kab_kota 'KAB-KOTA',
  nama_rs 'NAMA RS', tahun 'TAHUN', no_kode, kualifikasi_pendidikan,
  keadaan_lakilaki, keadaan_perempuan, kebutuhan_lakilaki, kebutuhan_perempuan,
  IF(MOD(no_kode,10000)=0,
    (SELECT SUM(subq.kekurangan_lakilaki) FROM emp_statistik_view subq
    WHERE MOD(subq.no_kode,100)<>0 AND
      FLOOR(subq.no_kode/10000)=FLOOR(main.no_kode/10000)),
    IF(MOD(no_kode,100)=0,
      (SELECT SUM(subq.kekurangan_lakilaki) FROM emp_statistik_view subq
      WHERE MOD(subq.no_kode,100)<>0 AND
        FLOOR(subq.no_kode/100)=FLOOR(main.no_kode/100)),
      kekurangan_lakilaki)) kekurangan_lakilaki,
  IF(MOD(no_kode,10000)=0,
    (SELECT SUM(subq.kekurangan_perempuan) FROM emp_statistik_view subq
    WHERE MOD(subq.no_kode,100)<>0 AND
      FLOOR(subq.no_kode/10000)=FLOOR(main.no_kode/10000)),
    IF(MOD(no_kode,100)=0,
      (SELECT SUM(subq.kekurangan_perempuan) FROM emp_statistik_view subq
      WHERE MOD(subq.no_kode,100)<>0 AND
        FLOOR(subq.no_kode/100)=FLOOR(main.no_kode/100)),
      kekurangan_perempuan)) kekuangan_perempuan
FROM emp_statistik_view main;