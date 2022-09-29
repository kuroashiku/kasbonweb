<?php

namespace App\Controllers;

class Kasir extends BaseController
{
	public function updatelogin()
    {
        $kasirModel = new KasirModel();
        $retobj = $kasirModel->updatePassword();
        echo json_encode($retobj);
    }
}