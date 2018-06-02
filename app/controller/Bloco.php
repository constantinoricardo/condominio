<?php

namespace Controller;

use Model\Bloco as ModelBloco;
use Sirius\Validation\Validator;
use Illuminate\Database\Capsule\Manager as DB;

class Bloco extends Controller
{

    private $numero;

    private $descricao;

    public function getValidator()
    {
        $validator = new Validator();
        $validator->add('numero', 'required', '', 'Número é obrigatório.');
        $validator->add('descricao', 'required', '','Descrição do bloco é obrigatório.');

        return $validator;
    }

    public function parametros() : void
    {
        $messages = array();
        $params = $this->getParameters();

        $validator = $this->getValidator();

        if (!$validator->validate($params)) {
            foreach ($validator->getMessages() as $k => $value) {
                $messages[] = $value[0]->getTemplate();
            }
            throw new \Exception($messages[0]);
        }

        $this->numero = $params['numero'];
        $this->descricao = $params['descricao'];
    }

    private function validarNumeroBlocoDescricaoExiste()
    {
        $elemento = DB::table("bloco")
            ->select("id")
            ->where("numero", "=", $this->numero)
            ->orWhere("descricao", "=", $this->descricao)
            ->get();

        if (!is_null($elemento[0]))
            throw new \Exception("Este bloco já existe.");
    }

    public function delete() : void
    {
        try {

            $this->parametros();

            ModelBloco::destroy($this->id);
            $response = array(
                "status" => 1,
                "message" => "Bloco excluído com sucesso."
            );
            echo json_encode($response);

        } catch (\Error $e) {
            $response = array(
                "status" => 2,
                "message" => "Ocorreu um erro " . $e->getMessage()
            );
            echo json_encode($response);

        } catch (\Exception $e) {
            $response = array(
                "status" => 2,
                "message" => $e->getMessage()
            );
            echo json_encode($response);
        }
        exit;
    }

    public function inserir() : string
    {
        try {
            $this->parametros();
            $this->validarNumeroBlocoDescricaoExiste();

            $bloco = ModelBloco::query()->insert([
                "numero" => $this->numero,
                "descricao" => $this->descricao
            ]);

            if ($bloco) {
                $response = array(
                    "status" => 1,
                    "message" => "Bloco cadastrado com sucesso."
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


    public function find() : string
    {
        try {

            $elements = ModelBloco::all(['numero', 'descricao'])->all();
            echo json_encode($elements);

        } catch (\Error $e) {
            $message = array ('message' => 'Ocorreu um Erro ' . $e->getMessage());
            echo json_encode($message);
        }
        exit;
    }
}