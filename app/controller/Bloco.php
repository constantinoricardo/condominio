<?php

namespace Controller;

use Model\Bloco as ModelBloco;

class Bloco extends Controller
{

    public function inserir() : string {

        try {
            $params = $this->getParameters();

            $numero = $params['numero'];
            $descricao = $params['descricao'];

            $bloco = ModelBloco::query()->insert([
                "numero" => $numero,
                "descricao" => $descricao
            ]);

            if ($bloco)
                echo 'Bloco cadastrado com sucesso';

            echo 'Bloco não cadastrado';
        } catch (\Exception $e) {
            echo "Ocorreu uma exceção " . $e->getMessage();
        } catch (\Error $e) {
            echo "Ocorreu um Erro " . $e->getMessage();
        }
    }


    public function find() : string {
        try {

            $elements = ModelBloco::all(['numero', 'descricao'])->all();
            echo json_encode($elements);

        } catch (\Error $e) {
            $message = array ('message' => 'Ocorreu um Erro ' . $e->getMessage());
            echo json_encode($message);
        }
    }
}