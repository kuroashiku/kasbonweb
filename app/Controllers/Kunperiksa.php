<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\KunperiksaModel;

class Kunperiksa extends Controller
{
    public function obgyn(){
        return view('kunobgyn_view');
    }
    
    public function dental(){
        return view('kundental_view');
    }
}