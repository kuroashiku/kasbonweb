<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ManusiaModel;

class Manusia extends Controller
{
    public function index()
    {
        return view('manusia_view');
    }

    public function test()
    {
        print_r($_POST);
    }
}
