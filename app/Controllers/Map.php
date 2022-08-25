<?php

namespace App\Controllers;
use CodeIgniter\Controller;

class Map extends BaseController
{
    public function index()
    {
        return view('map_view');
    }
}