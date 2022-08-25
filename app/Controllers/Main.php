<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\KunjunganModel;
use App\Models\UserModel;

class Main extends BaseController
{
    public function index()
    {
        return view('main_view');
    }

    public function dashboard()
    {
        // chart baru yang dikerjakan Naufal
        // menunggu konfirmasi dari pihak marketing untuk mengaktifkan
        ////////////////////////////////////
        return view('dashboard_db_view');
        ////////////////////////////////////
        // return view('dashboard_view'); // chart lama yang dikerjakan pak Mendin
    }

    public function report()
    {
        return view('report_view');
    }

    public function manusia()
    {
        return view('manusia_view');
    }

    public function hewan()
    {
        return view('hewan_view');
    }

    public function loket()
    {
        return view('loket_view');
    }

    public function layanan()
    {
        return view('layanan_view');
    }

    public function kasir()
    {
        return view('kasir_view');
    }

    public function inventori()
    {
        return view('inventori_view');
    }

    public function akuntansi()
    {
        return view('acc_view');
    }

    public function master()
    {
        return view('master_view');
    }

    public function laporan()
    {
        return view('laporan_view');
    }

    public function lapdinkes()
    {
        return view('lapdinkes_view');
    }

    public function rekapkarcis()
    {
        return view('rekapkarcis_view');
    }

    public function login()
    {
        return view('login_view');
    }

    public function pos()
    {
        return view('pos_view');
    }

    public function cust()
    {
        return view('customer_view');
    }
    
    public function itm()
    {
        return view('item_view');
    }

    public function conv()
    {
        return view('konversi_view');
    }

    public function sales()
    {
        return view('sales_view');
    }

    public function trans()
    {
        return view('transaksi_view');
    }

    public function po()
    {
        return view('po_view');
    }

    public function rcv()
    {
        return view('receive_view');
    }
}
