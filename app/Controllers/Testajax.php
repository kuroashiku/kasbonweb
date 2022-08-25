<?php 
namespace App\Controllers;
use CodeIgniter\Controller;

class Testajax extends Controller
{
    public function index()
    {
        return view('testajax_view');
    }

    public function read()
    {
        $nama = $_POST['nama'];
        $nama = $nama." Profeta Nusantara";
        return $nama;
    }
}
