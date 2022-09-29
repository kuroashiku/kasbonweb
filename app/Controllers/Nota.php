<?php 
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\NotaModel;

class Nota extends ResourceController
{
    public function grafikbayarnota()
    {
        $notaModel = new NotaModel();
        $retobj = $notaModel->grafikBayarNota();
        echo json_encode($retobj);
    }
}