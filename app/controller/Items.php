<?php

namespace Controller;

use Model\Items as ModelItems;
use Helper\Data;

class Items extends Controller
{

    public function inserir() : string
    {

        try {
            $params = $this->getParameters();

            $descricao = $params['descricao'];
            $preco = Data::formatPriceDatabase($params['preco']);

            $items = new ModelItems();
            $items->descricao = $descricao;
            $items->preco = $preco;
            $items->save();

            $id = $items->id;

            if ($id) {
                $response = array(
                    "id" => $id,
                    "status" => 1,
                    "message" => "Item cadastrado com sucesso."
                );
                echo json_encode($response);
            }
        } catch (\Exception $e) {
            $response = array(
                "status" => 2,
                "message" => $e->getMessage()
            );
            echo json_encode($response);
        } catch (\Error $e) {
            $response = array(
                "status" => 2,
                "message" => "Ocorreu um erro " . $e->getMessage()
            );
            echo json_encode($response);
        }
        exit;
    }

    public function alterar() : string
    {
        try {

            $params = $this->getParameters();

            $id = $params['id'];
            $descricao = $params['descricao'];
            $preco = Data::formatPriceDatabase($params['preco']);

            $objeto = new ModelItems();
            $item = $objeto->find($id);

            $item->descricao = $descricao;
            $item->preco = $preco;
            $item->save();

            $response = array(
                "status" => 1,
                "message" => "Item alterado com sucesso."
            );
            echo json_encode($response);

        } catch (\Exception $e) {
            $response = array(
                "status" => 2,
                "message" => $e->getMessage()
            );
            echo json_encode($response);
        } catch (\Error $e) {
            $response = array(
                "status" => 2,
                "message" => "Ocorreu um erro " . $e->getMessage()
            );
            echo json_encode($response);
        }
        exit;
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
            $message = array ('status' => 2, 'message' => 'Ocorreu um Erro ' . $e->getMessage());
            echo json_encode($message);
        }
        exit;
    }
}