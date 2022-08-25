<?php
    function dateId($dateOfId, $maxId){
        if (is_null($maxId)){
            return $dateOfId . "001";
        }
        else{
            return $dateOfId . sprintf("%03d",((int)substr($maxId,8,3))+1);
        }
    }

    function readObgyn($post){
        $db = db_connect();
        $query = $db->query("SELECT * FROM rms_kunobgyn
            WHERE kog_kpr_id=".$post['kog_kpr_id']." LIMIT 1");
        $rows = $query->getResult(); 
        $row = $rows[0] ? $rows[0] : null;
        $result['status'] = 'failed';
        if(!$row){
            $result['sql'] = (string)($db->getLastQuery());
            return $result;
        }
        if($row->kog_lasthaid) {
            $date = date_create($row->kog_lasthaid);
            $row->kog_lasthaid = date_format($date,"m/d/Y");
        }
        $result['status'] = 'success';
        $result['data'] = $row;
        return $result;
    }

    function saveObgyn($post, $kunperiksa){
        $result['status'] = 'success';
        $db = db_connect();
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];
        $builder = null;
        if ($post['kog_id'] == '0' || !$post['kog_id']) { // new record
            if($post['kog_kpr_id'] == null){
                //save kunperiksa first
                $builder = $db->table('rms_kunperiksa');
                $query = $db->query("SELECT MAX(kpr_id) max_id,
                    DATE_FORMAT(NOW(), '%Y%m%d') dateof_id
                    FROM rms_kunperiksa
                    WHERE LEFT(kpr_id,8)=DATE_FORMAT(NOW(), '%Y%m%d')");
                $row = $query->getRow();
                $post['kog_kpr_id'] = dateId($row->dateof_id, $row->max_id);
                $builder->set('kpr_id', $post['kog_kpr_id'] );
                $builder->set('kpr_kun_id', $post['kog_kun_id']);
                $builder->set('kpr_tgcreate', date('Y-m-d H:i:s'));
                $builder->set('kpr_ipaddrcreate', $ip);
                $builder->set('kpr_usercreate', $post['username']);
                if (!$builder->insert()) {
                    $result['errmsg'] = 'Proses generate ID data pemeriksaan gagal';
                    $result['status'] = 'failed';
                    $result['sql'] = (string)($db->getLastQuery());
                    return $result;
                }
            }
            $builder = $db->table('rms_kunobgyn');
            $query = $db->query("SELECT MAX(kog_id) max_id,
                DATE_FORMAT(NOW(), '%Y%m%d') dateof_id
                FROM rms_kunobgyn
                WHERE LEFT(kog_id,8)=DATE_FORMAT(NOW(), '%Y%m%d')");
                
            $row = $query->getRow();
            
            $new_id = dateId($row->dateof_id, $row->max_id);
            $post['kog_id'] = $new_id;
            $builder->set('kog_id', $post['kog_id']);
            $builder->set('kog_kun_id', $post['kog_kun_id']);
            $builder->set('kog_kpr_id', $post['kog_kpr_id']);
            $builder->set('kog_tgcreate', date('Y-m-d H:i:s'));
            $builder->set('kog_ipaddrcreate', $ip);
            $builder->set('kog_usercreate', $post['username']);

            if (!$builder->insert()) {
                $result['errmsg'] = 'Proses generate ID data pemeriksaan gagal';
                $result['status'] = 'failed';
                $result['sql'] = (string)($db->getLastQuery());
            }
        }
        
        if ($result['status'] == 'success') {
            if(is_null($builder)){
                $builder = $db->table('rms_kunobgyn');
            }
            $builder->set('kog_menarrche',  $post['kog_menarrche']);
            $lastHaid = $post['kog_lasthaid']?"STR_TO_DATE('".$post['kog_lasthaid']."','%m/%d/%Y')":'null';
            $builder->set('kog_lasthaid',  $lastHaid, false);
            $builder->set('kog_rikawin',  $post['kog_rikawin']);
            $builder->set('kog_haidproblem',  $post['kog_haidproblem']);
            $builder->set('kog_gravida',    $post['kog_gravida']);
            $builder->set('kog_paritas',    $post['kog_paritas']);
            $builder->set('kog_abortus',    $post['kog_abortus']);
            $builder->set('kog_masalah',    $post['kog_masalah']);
            $builder->set('kog_rikit_kel',    $post['kog_rikit_kel']);
            $builder->set('kog_rigyn',    $post['kog_rigyn']);
            $builder->set('kog_kb',    $post['kog_kb']);
            $builder->set('kog_kb_pli',    $post['kog_kb_pli']);
            $builder->set('kog_mata',    $post['kog_mata']);
            $builder->set('kog_dada',    $post['kog_dada']);
            $builder->set('kog_ektramilas',    $post['kog_ektramilas']);
            $builder->set('kog_kardio',    $post['kog_kardio']);
            $builder->set('kog_ob_abd',    $post['kog_ob_abd']);
            $builder->set('kog_ob_tfu',    $post['kog_ob_tfu']);
            $builder->set('kog_ob_letpung',    $post['kog_ob_letpung']);
            $builder->set('kog_ob_pres',    $post['kog_ob_pres']);
            $builder->set('kog_ob_kul',    $post['kog_ob_kul']);
            $builder->set('kog_ob_kulstat',    $post['kog_ob_kulstat']);
            $builder->set('kog_ob_kon',    $post['kog_ob_kon']);
            $builder->set('kog_ob_konstat',    $post['kog_ob_konstat']);
            $builder->set('kog_gy_vul',    $post['kog_gy_vul']);
            $builder->set('kog_gy_vag',    $post['kog_gy_vag']);
            $builder->set('kog_gy_touch',    $post['kog_gy_touch']);
            $builder->set('kog_gy_pang',    $post['kog_gy_pang']);
            $builder->set('kog_gy_feto',    $post['kog_gy_feto']);
            $builder->set('kog_nf_fut',    $post['kog_nf_fut']);
            $builder->set('kog_nf_ctut',    $post['kog_nf_ctut']);
            $builder->set('kog_nf_loc',    $post['kog_nf_loc']);
            $builder->set('kog_nf_luk',    $post['kog_nf_luk']);
            $builder->set('kog_pelaksana',    $post['kog_pelaksana']);
            $builder->set('kog_ketlain',    $post['kog_ketlain']);
            $builder->set('kog_kondisi',    $post['kog_kondisi']);
            $builder->set('kog_efeksmp',    $post['kog_efeksmp']);
            $builder->set('kog_kunulang',    $post['kog_kunulang'] == 'true' ? 1 : 0);
            $builder->set('kog_rirawat',    $post['kog_rirawat']);
            $builder->set('kog_rioperasi',    $post['kog_rioperasi']);
            $builder->set('kog_tgedit', date('Y-m-d H:i:s'));
            $builder->set('kog_ipaddredit', $ip);
            $builder->set('kog_useredit', $post['username']);

            $builder->where('kog_id', $post['kog_id']);
            if (!$builder->update()) {
                $result['errmsg'] = 'Terjadi kegagalan saat menyimpan data pemeriksaan';
                $result['status'] = 'failed';
            }
            $result['sql'] = (string)($db->getLastQuery());
        }
        if ($result['status'] == 'success') {
            $result['errmsg'] = 'Tidak ada error';
            $result['row'] = $kunperiksa->getKunperiksaRow($db, $post);
        }
        return $result;
    }

?>