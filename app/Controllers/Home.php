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
		
		if($this->isMobile()){
		echo "Maaf, akses aplikasi melalui mobile bisa dilakukan dg install APK kasbon -- sila kontak Admin ";
		echo nl2br(" \n\n ");
 
		echo nl2br ('<a href="http://aplikasi.biz/"&nbsp &nbsp &nbsp &nbsp \n\n> pls go thru mobile demo link, Click ! </a>');
		}
		else
			return view('main_view');
 	}
}
