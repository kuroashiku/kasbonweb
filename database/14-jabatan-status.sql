ALTER TABLE `emp_jabatan` ADD `jab_status` VARCHAR(20) NULL DEFAULT NULL AFTER `jab_com_id`;
update emp_jabatan set jab_status="Aktif";