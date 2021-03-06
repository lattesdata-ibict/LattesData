<?php

namespace App\Models\LattesData;

use CodeIgniter\Model;

class Forms extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = '*';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    function Home()
    {        
        $sx = '';
        $LattesData = new \App\Models\LattesData\LattesData();
            if (isset($_POST['process']) and (strlen($_POST['process']) > 0))
                {
                    $proc = $_POST['process'];
                    $proc = $LattesData->padroniza_processo($proc);
                    jslog("Processo: ".$proc[1]);
                    if ($proc[1] != 0)
                        {
                            $sx .= $this->welcome();        
                        } else {
                            $sx .= $LattesData->show_metadate($proc);
                        }
                } else {
                    $sx .= $this->welcome();
                }        
        //$sx .= '20113023806';
        return $sx;
    }

function welcome()
    {
        $sx = '';
        $sx = h('Deposite seus dados');
        $sx .= '<p>Bem-vindo(a) ao Reposit??rio LattesData!</p>';
        $sx .= '<p>Este espa??o ?? destinado para que pesquisadores possam realizar o dep??sito dos seus conjuntos de dados que tiveram suas pesquisas financiadas totalmente ou parcialmente pelo CNPq.</p>';
        $sx .= '<p>Para iniciar a submiss??o, preencha o campo ?? direita da tela com n??mero do processo no CNPq, depois clique em ???Depositar???.</p>';
        $sx .= '<p>Ap??s essa etapa inicial, ser?? encaminhado um e-mail confirmando o cadastro do projeto no LattesData. Caso tenha alguma d??vida no acesso ou preenchimento dos metadados, entre em contato com o seguinte e-mail: lattesdata@cnpq.br.</p>';
        $sx .= '<div style="height: 100px"></div>';
        return $sx;
    }

 function form()
        {
            $erro = '';
            $LattesData = new \App\Models\LattesData\LattesData();
            $proc = '';
            if (isset($_POST['process']) and (strlen($_POST['process']) > 0))
                {
                    $proc = $_POST['process'];
                    $proc = $LattesData->padroniza_processo($proc); 

                    switch ($proc[1])
                        {
                            case '0':
                                $proc = $proc[0];
                                break;
                            case '2':
                                $erro = 'N??mero do processo inv??lido - '.$proc[0];
                                $proc = '';
                                break;
                            default:
                                $proc = '';
                                break;
                        }
                }
            
            $sx = '
            <div class="border border-1 border-primary" style="width: 100%;">
            <div class="card-body">
              <h1 class="card-title">Depositar</h1>
              <h5 class="card-subtitle mb-2 text-muted">Conjunto de dados (<i>Datasets</i>)</h5>
              <p class="card-text">
              ';
            $sx .= form_open(URL);
            //$sx .= '<form method="post" accept-charset="utf-8">';
            $sx .= 'Informe o n??mero do processo no CNPq para iniciar o dep??sito.';
            $sx .= form_input('process', '', 'class="form-control" placeholder="N??mero do processo"');
            $sx .= 'Ex: 123456/2022-2';
            $info = 'O n??mero do processo do CNPQ ?? composto por seis d??gitos, '.chr(13)
                    .' seguido de um ponto e dois d??gitos. Ex: 123456/2022-2'.chr(13)
                    .' O n??mero do processo ?? disponibilizado em seu termo de outorga.';

            $sx .= ' <span title="'.$info.'" style="cursor: pointer; font-size: 150%">&#x1F6C8;</span><br>';
            $sx .= form_submit('action', 'depositar', 'class="btn btn-primary" style="width: 100%;"');
            $sx .= form_close();
            $sx .= '
              </p>
            </div>
          </div>';

          if ($erro != '')
                {
                    $sx .= '<div class="alert alert-danger" role="alert">'.$erro.'</div>';
                }
          return $sx;            
        }  
}