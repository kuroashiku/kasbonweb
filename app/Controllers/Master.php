<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\MasterModel;
use App\Models\SdmModel;
use App\Models\TindakanModel;
use App\Models\ObatModel;
use App\Models\LaboratModel;
use App\Models\KamarModel;
use App\Models\RadiologiModel;

class Master extends Controller
{
    public function view()
    {
        return view($_GET['namamaster'].'_view');
    }
}
