<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function isMobile() {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|
		cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|
		up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
	public function index()
	{
		
		if($this->isMobile())
			echo "Maaf, aplikasi ini masih belum compatible dengan mobile";
		else
			return view('main_view');
 	}
}
