<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ReceiveModel;

class Receive extends Controller
{
    public function receive_items(){
        return view('receivestock_view', $_POST);
    }
}