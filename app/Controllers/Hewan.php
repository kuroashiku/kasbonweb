<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\HewanModel;

class Hewan extends Controller
{
    public function index()
    {
        return view('hewan_view');
    }
}
