<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\SupplierModel;

class Supplier extends Controller
{
    public function index()
    {
        return view('supplier_view');
    }
}
