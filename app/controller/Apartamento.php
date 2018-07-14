<?php

namespace Controller;

use Illuminate\Database\Capsule\Manager as DB;
use Model\Apartamento as ModelApartamento;
use Repositories\Apartamento as ApartamentoRepository;

class Apartamento extends Controller
{

    private $id;

    private $numero;

    private $bloco_id;

    private $morador_id;

    public function parametros() : void
    {
        $params = $this->getParameters();

        if (!empty($params)) {
            $this->numero = $params['numero'];
            $this->bloco_id = $params['bloco_id'];
            $this->id = $params['id'];
        }

//        if (($params['morador_id']))
//            $this->morador_id = $params['morador_id'];

        $this->morador_id = (empty($params['morador_id'])) ? null : $params['morador_id'];
    }

    public function delete() : void
    {
        try {

            $this->parametros();

            ModelApartamento::destroy($this->id);
            $response = array(
                "status" => 1,
                "message" => "Apartamento excluído"
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

    public function inserir() : void
    {
        try {

            $this->parametros();
            $this->validarApartamento();

            $apartamento = new ModelApartamento();
            $apartamento->numero = $this->numero;
            $apartamento->bloco_id = $this->bloco_id;
            $apartamento->morador_id = $this->morador_id;
            $apartamento->save();

            $id = $apartamento->id;

            if ($id) {
                $response = array(
                    "id" => $id,
                    "status" => 1,
                    "message" => "Apartamento cadastrado com sucesso."
                );
                echo json_encode($response);
            }
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
     * metodo usado para buscar tabela com as informações do apartamento,
     * tipo numero do bloco e morador
     *
     * @return string
     */
    public function find() : string
    {
        try {

            $this->parametros();

            $apartamentos = DB::table("apartamento")
                ->select( "apartamento.id", "apartamento.numero", "bloco.id as bloco_id", "bloco.descricao as bloco", "morador.nome as morador")
                ->join("bloco", "bloco.id", "=", "apartamento.bloco_id")
                ->leftJoin("morador", "morador.id", "=", "apartamento.morador_id")
                ->get();

            echo json_encode($apartamentos);

        } catch (\Error $e) {
            $array = array("status" => 2,'message' => "Ocorreu um erro " . $e->getMessage());
            echo json_encode($array);
        }
        exit;
    }

    /**
     * @author Ricardo Constantino
     *
     * na tela de morador, busca-se a descricao do bloco
     * onde ele mora
     * @return string
     */
    public function buscarDescricaoBloco() : string
    {
        try {

            $this->parametros();

            $apartamentos = DB::table("apartamento")
                ->select( "bloco.descricao")
                ->join("bloco", "bloco.id", "=", "apartamento.bloco_id")
                ->where("apartamento.id", "=", $this->id)
                ->get();

            echo json_encode($apartamentos);

        } catch (\Error $e) {
            $array = array('status' => 2, 'message' => "Ocorreu um erro " . $e->getMessage());
            echo json_encode($array);
        }
        exit;
    }

    public function alterarMorador(): void
    {
        try {

            $this->parametros();
            $this->validarMoradorExisteApartamento();

            $apartamento = new ApartamentoRepository();
            $apartamento->alterarMoradorApartamento($this->id, $this->morador_id);

            $response = array(
                "status" => 1,
                "message" => "Morador alterado com sucesso"
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
     * metodo para buscar o apartamento, ou seja,
     * edicao do apartamento, apenas para mostrar na tela no formulario
     *
     * @return string
     */
    public function edit() : string
    {
        try {

            $this->parametros();

            $apartamento = DB::table("apartamento")
                ->select( "apartamento.id", "apartamento.numero", "bloco.descricao")
                ->join("bloco", "bloco.id", "=", "apartamento.bloco_id")
                ->where("apartamento.id", "=", $this->id)
                ->get();

            echo json_encode($apartamento);

        } catch (\Error $e) {
            $array = array('status' => 2, 'message' => "Ocorreu um erro " . $e->getMessage());
            echo json_encode($array);
        }
        exit;
    }

    public function alterar() : void
    {
        try {

            $this->parametros();
            $this->validarApartamento();

            $objeto = new ModelApartamento();
            $apartamento = $objeto->find($this->id);

            if (is_null($apartamento))
                throw new \Exception("Por favor, escolha um apartamento que exista!");

            $apartamento->numero = $this->numero;
            $apartamento->bloco_id = $this->bloco_id;
            $apartamento->save();

            $response = array(
                "status" => 1,
                "message" => "Apartamento atualizado com sucesso."
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

    private function validarMoradorExisteApartamento()
    {
        $elemento = DB::table("apartamento")
            ->select("id")
            ->where("morador_id", "=", $this->morador_id)
            ->get();

        if (!is_null($elemento[0]))
            throw new \Exception("Este morador já possui esse apartamento");
    }

    private function validarApartamento()
    {
        $elemento = DB::table("apartamento")
                    ->select("numero")
                    ->where("numero", "=", $this->numero)
                    ->where("bloco_id", "=", $this->bloco_id)
                    ->get();

        if (!is_null($elemento[0]))
            throw new \Exception("Apartamento já se encontra cadastrado");
    }
}