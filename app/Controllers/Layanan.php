<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\LayananModel;

class Layanan extends Controller
{
    public function foto()
    {
        return view('kunfoto_view');
    }

    public function suketmti()
    {
        return view('suratkematian_view');
    }

    public function suketskt()
    {
        return view('suratsakit_view');
    }

    public function ppi()
    {
        return view('kunppi_view');
    }

    public function obtchange()
    {
        return view('obtchange_view');
    }
}
