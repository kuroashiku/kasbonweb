<?php 
namespace App\Controllers;
use CodeIgniter\Controller;

class Video extends BaseController
{
    public function index()
    {
        return view('video_view');
    }

    public function penilaian()
    {
        return view('videopenilaian_view');
    }
}
