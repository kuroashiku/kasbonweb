<?php 
namespace App\Controllers;
use CodeIgniter\Controller;

class Pos extends Controller
{
    public function customer()
    {
        return view('customer_view');
    }
    public function posgambar()
    {
        return view('item_gambar_view');
    }
    public function item()
    {
        return view('item_view');
    }
    public function konversi()
    {
        return view('konversi_view');
    }
    public function transaksi()
    {
        return view('transaksi_view');
    }
    public function sales()
    {
        return view('sales_view');
    }
}
