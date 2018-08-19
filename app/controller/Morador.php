<?php

namespace Controller;

use Illuminate\Database\Capsule\Manager as DB;
use Model\Morador as ModelMorador;
use Repositories\Apartamento;

class Morador extends Controller
{

    private $id;

    private $nome;

    private $cpf;

    private $apartamento_id;

    public function parametros() : void
    {
        $params = $this->getParameters();

        if (!empty($params)) {
            $this->id = $params['id'];
            $this->nome = $params['nome'];
            $this->cpf = $params['cpf'];
            $this->apartamento_id = $params['apartamento_id'];
        }
    }

    private function validarMorador()
    {
        $elemento = DB::table("morador")
            ->select("nome")
            ->where("nome", "=", $this->nome)
            ->orWhere("cpf", "=", $this->cpf)
            ->get();

        if (!is_null($elemento[0]))
            throw new \Exception("Morador já se encontra cadastrado");
    }

    public function find() : string
    {
        try {

            $this->parametros();

            $apartamentos = DB::table("morador")
                ->select( "apartamento.id", "apartamento.numero", "bloco.id as bloco_id", "bloco.descricao as bloco", "morador.nome as morador")
                ->join("apartamento", "morador.id", "=", "apartamento.morador_id")
                ->join("bloco", "bloco.id", "=", "apartamento.bloco_id")
                ->get();

            echo json_encode($apartamentos);

        } catch (\Error $e) {
            $array = array("status" => 2,'message' => "Ocorreu um erro " . $e->getMessage());
            echo json_encode($array);
        }
        exit;
    }

    public function delete() : void
    {
        try {

            $this->parametros();

            ModelMorador::destroy($this->id);
            $response = array(
                "status" => 1,
                "message" => "Morador excluído com sucesso."
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

    /**
     *
     * @author Ricardo Constantino
     *
     * metodo que faz a edicao do morador,
     * apenas para mostrar na tela no formulario,
     *
     * @return string
     */
    public function edit() : string
    {
        try {

            $this->parametros();

            $morador = DB::table("morador")
                ->select("morador.id", "morador.nome", "morador.cpf",
                    "apartamento.numero")
                ->leftJoin("apartamento", "apartamento.morador_id", "=", "morador.id")
                ->leftJoin("bloco", "apartamento.bloco_id", "=", "bloco.id")
                ->where("morador.id", "=", $this->id)
                ->get();

            echo json_encode($morador);

        } catch (\Error $e) {
            $response = array(
                "status" => 2,
                "message" => "Ocorreu um erro " . $e->getMessage()
            );
            echo json_encode($response);
        }
        exit;
    }

    public function inserir() : string
    {
        try {

            $this->parametros();
            $this->validarMorador();

            DB::connection()->beginTransaction();

            $morador = new ModelMorador();
            $morador->nome = $this->nome;
            $morador->cpf = $this->cpf;
            $morador->save();

            $morador_id = $morador->id;

            if ($morador) {

                $apartamento = new Apartamento();
                $apartamento->alterarMoradorApartamento($this->apartamento_id, $morador_id);

                $response = array(
                    "id" =>$morador_id,
                    "status" => 1,
                    "message" => "Morador cadastrado com sucesso."
                );
                echo json_encode($response);
            }

            DB::connection()->commit();

        } catch (\Error $e) {
            DB::connection()->rollBack();
            $response = array(
                "status" => 2,
                "message" => "Ocorreu um erro " . $e->getMessage()
            );
            echo json_encode($response);
        } catch (\Exception $e) {
            DB::connection()->rollBack();
            $response = array(
                "status" => 2,
                "message" => $e->getMessage()
            );
            echo json_encode($response);
        }
        exit;
    }


}