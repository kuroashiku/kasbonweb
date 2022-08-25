<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\CoaModel;

class Coa extends Controller
{
    public function index()
    {
        return view('coa_view');
    }
}
