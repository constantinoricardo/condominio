<?php

namespace Controller;

use Repositories\Morador as MoradorRepositories;
use Repositories\Items as ItemsRepositories;

class Agendamento extends Controller
{

    private $id;

    private $morador_id;

    private $data_descricao;

    private $pagamento;

    private $descricao;

    public function parametros() : void
    {
        $params = $this->getParameters();

        $this->id = $params['id'];
        $this->morador_id = $params['morador_id'];
        $this->data_descricao = $params['data_descricao'];
        $this->pagamento = $params['pagamento'];
        $this->descricao = $params['descricao'];
    }

    /**
     *
     * @author Ricardo Constantino
     *
     * metodo usado para pegar os itens do condominio para que na
     * tela de agendamento eles sejam exibidos
     *
     * @return string
     */
    public function items(): string
    {
        try {
            $itemsObject = new ItemsRepositories();
            $items = $itemsObject->findAll();

            echo json_encode($items);

        } catch (\Error $e) {
            echo "Ocorreu um erro " . $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     *
     * @author Ricardo Constantino
     *
     * metodo usado para pegar todos os moradores para que na
     * tela de agendamento eles sejam escolhidos
     *
     * @return string
     */
    public function morador(): string
    {
        try {

            $moradorObject = new MoradorRepositories();
            $morador = $moradorObject->findAll();

            echo json_encode($morador);

        } catch (\Error $e) {
            echo "Ocorreu um erro " . $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function agendar() : string
    {

        try {

            $this->parametros();


        } catch (\Error $e) {
            echo "Ocorreu um erro " . $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}