<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
require_once(APPPATH.'Libraries/tcpdf/tcpdf.php');
require_once(APPPATH.'Libraries/PHPJasperXML.inc.php');
use App\Models\LapdinkesModel;
use App\Models\RekapkarcisModel;

class Laporan extends Controller
{
    public function bigten()
    {
        $PHPJasperXML = new PHPJasperXML();
    }
}
