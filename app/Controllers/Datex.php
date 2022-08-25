<?php 
namespace App\Controllers;
use CodeIgniter\Controller;

class Datex extends BaseController
{
    public function index()
    {
        return view('datex_view');
    }
}
