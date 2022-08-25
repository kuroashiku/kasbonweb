<?php 
namespace App\Controllers;
use CodeIgniter\Controller;

class Bpjs extends Controller
{
    public function potongan_bpjs(){
        return view('bpjs_claim_view');
    }
    
}