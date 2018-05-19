<?php

namespace Controller;

use Illuminate\Database\Capsule\Manager as DB;
use Model\Morador as ModelMorador;

class Morador extends Controller
{

    private $id;

    private $nome;

    private $cpf;

    public function parametros() : void {
        $params = $this->getParameters();

        if (!empty($params)) {
            $this->id = $params['id'];
            $this->nome = $params['nome'];
            $this->cpf = $params['cpf'];
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

            $morador = ModelMorador::query()->insert([
                'nome' => $this->nome,
                'cpf' => $this->cpf
            ]);

            if ($morador)
                echo "Morador cadastrado com sucesso.";

        } catch (\Error $e) {
            echo "Houve um erro " . $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }


}