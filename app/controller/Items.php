<?php

namespace Controller;

use Model\Items as ModelItems;

class Items extends Controller
{

    public function inserir() : string
    {

        try {
            $params = $this->getParameters();

            $descricao = $params['descricao'];
            $preco = str_replace(".", "", $params['preco']);
            $preco = str_replace(",", ".", $preco);

            $items = ModelItems::query()->insert([
                "descricao" => $descricao,
                "preco" => $preco
            ]);

            if ($items)
                echo 'Item cadastrado com sucesso';

            echo 'Item não cadastrado';
        } catch (\Exception $e) {
            echo "Ocorreu uma exceção " . $e->getMessage();
        } catch (\Error $e) {
            echo "Ocorreu um Erro " . $e->getMessage();
        }
    }

    public function alterar() : string
    {
        try {

            $params = $this->getParameters();

            $id = $params['id'];
            $descricao = $params['descricao'];
            $preco = str_replace(".", "", $params['preco']);
            $preco = str_replace(",", ".", $preco);

            $objeto = new ModelItems();
            $item = $objeto->find($id);

            $item->descricao = $descricao;
            $item->preco = $preco;
            $item->save();

            echo "Item alterado com sucesso.";

        } catch (\Error $e) {
            echo "Ocorreu uma exceção " . $e->getMessage();
        } catch (\Error $e) {
            echo "Ocorreu um Erro " . $e->getMessage();
        }
    }

    public function find() : string
    {
        try {

            $dados = array();
            $elements = ModelItems::all(['descricao', 'preco'])->all();

            if (!empty($elements)) {
                foreach ($elements as $k => $elemento) {
                    $dados[$k]['descricao'] = $elemento['descricao'];
                    $dados[$k]['preco'] = "R$ ".number_format($elemento['preco'], 2, ",", ".");
                }
            }

            echo json_encode($dados);

        } catch (\Error $e) {
            $message = array ('message' => 'Ocorreu um Erro ' . $e->getMessage());
            echo json_encode($message);
        }
    }
}