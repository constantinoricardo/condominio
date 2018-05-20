<?php

namespace Controller;

use Illuminate\Database\Capsule\Manager as DB;
use Model\Apartamento as ModelApartamento;

class Apartamento extends Controller
{

    private $id;

    private $numero;

    private $bloco_id;

    private $morador_id;

    public function parametros() : void {
        $params = $this->getParameters();

        if (!empty($params)) {
            $this->numero = $params['numero'];
            $this->bloco_id = $params['bloco_id'];
            $this->id = $params['id'];
        }

        if (isset($params['morador_id']))
            $this->morador_id = $params['morador_id'];
    }

    public function delete() : void {
        try {

            $this->parametros();

            ModelApartamento::destroy($this->id);
            echo "Apartamento excluído.";

        } catch (\Error $e) {
            echo "Ocorreu um erro " . $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function inserir() : void {
        try {

            $this->parametros();
            $this->validarApartamento();

            $apartamento = ModelApartamento::query()->insert([
                'numero' => $this->numero,
                'bloco_id' => $this->bloco_id
            ]);

            if ($apartamento)
                echo "Apartamento cadastrado com sucesso.";

        } catch (\Error $e) {
            echo "Ocorreu um erro " . $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function find() : string {
        try {

            $this->parametros();

            $apartamentos = DB::table("apartamento")
                ->select( "id", "numero", "bloco_id")
                ->where("bloco_id", "=", $this->bloco_id)
                ->whereNotNull("morador_id")
                ->get();

            echo json_encode($apartamentos);

        } catch (\Error $e) {
            $array = array('message' => "Ocorreu um erro " . $e->getMessage());
            echo json_encode($array);
        }
    }

    public function alterarMorador(): void {
        try {

            $this->parametros();

        } catch (\Error $e) {
            echo "Ocorreu um erro " . $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function alterar() : void {
        try {

            $this->parametros();
            $this->validarApartamento();

            $objeto = new ModelApartamento();
            $apartamento = $objeto->find($this->id);

            $apartamento->numero = $this->numero;
            $apartamento->bloco_id = $this->bloco_id;
            $apartamento->save();

            echo "Apartamento atualizado com sucesso.";

        } catch (\Error $e) {
            echo "Ocorreu um erro " . $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function validarApartamento() {

        $elemento = DB::table("apartamento")
                    ->select("numero")
                    ->where("numero", "=", $this->numero)
                    ->where("bloco_id", "=", $this->bloco_id)
                    ->get();

        if (!is_null($elemento[0]))
            throw new \Exception("Apartamento já se encontra cadastrado");
    }
}