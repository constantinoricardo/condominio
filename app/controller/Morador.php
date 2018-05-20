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

    public function parametros() : void {
        $params = $this->getParameters();

        if (!empty($params)) {
            $this->id = $params['id'];
            $this->nome = $params['nome'];
            $this->cpf = $params['cpf'];
            $this->apartamento_id = $params['apartamento_id'];
        }
    }

    private function validarMorador() {

        $elemento = DB::table("morador")
            ->select("nome")
            ->where("nome", "=", $this->nome)
            ->orWhere("cpf", "=", $this->cpf)
            ->get();

        if (!is_null($elemento[0]))
            throw new \Exception("Morador já se encontra cadastrado");
    }

    public function delete() : void {
        try {

            $this->parametros();

            ModelMorador::destroy($this->id);
            echo "Morador excluído.";

        } catch (\Error $e) {
            echo "Ocorreu um erro " . $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function inserir() : string {
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

                echo "Morador cadastrado com sucesso.";
            }

            DB::connection()->commit();

        } catch (\Error $e) {
            echo "Houve um erro " . $e->getMessage();
            DB::connection()->rollBack();
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::connection()->rollBack();
        }
    }


}