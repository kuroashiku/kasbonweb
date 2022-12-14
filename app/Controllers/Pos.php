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
    public function po()
    {
        return view('po_view');
    }
    public function receive()
    {
        return view('receive_view');
    }
    public function po_add()
    {
        return view('po_add_view');
    }
    public function receive_add()
    {
        return view('receive_add_view');
    }
}

