<?php

namespace Controller;

use Repositories\Morador as MoradorRepositories;
use Repositories\Items as ItemsRepositories;
use Model\Agendamento as ModelAgendamento;
use Model\ItemAgendamento as ModelItemAgendamento;
use Illuminate\Database\Capsule\Manager as DB;
use Sirius\Validation\Validator;

class Agendamento extends Controller
{

    private $id;

    private $morador_id;

    private $data_reserva;

    private $pagamento;

    private $descricao;

    private $item_id;

    public function getValidator()
    {
        $validator = new Validator();
        $validator->add('morador_id', 'required', '', 'Morador é obrigatório.');
        $validator->add('data_reserva', 'required', '', 'Data da Reserva é obrigatória.');
        $validator->add('pagamento', 'required', '', 'Pagamento é obrigatório.');
        $validator->add('descricao', 'required', '','Descrição é obrigatória.');
        $validator->add('item_id', 'required', '','Item é obrigatório.');

        return $validator;
    }

    public function parametros() : void
    {
        $params = $this->getParameters();

        $validator = $this->getValidator();

        if (!$validator->validate($params))
        {
            $message = $validator->getMessages()['morador_id'][0]->getTemplate();
            throw new \Exception($message);
        }

        $this->id = $params['id'];
        $this->morador_id = $params['morador_id'];
        $this->data_reserva = $params['data_reserva'];
        $this->pagamento = $params['pagamento'];
        $this->descricao = $params['descricao'];
        $this->item_id = $params['item'];
    }

    private function verificarExisteAgendamentoData() : void
    {
        $agendamento = DB::table("agendamento")
            ->select("id")
            ->where("data_reserva", "=", $this->data_reserva)
            ->get();

        if (!is_null($agendamento[0]))
            throw new \Exception("Existe agendamento para essa data.");

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
            $this->verificarExisteAgendamentoData();

            $agendamento = ModelAgendamento::query()->insert([
                "morador_id" => $this->morador_id,
                "data_reserva" => $this->data_reserva,
                "data_included_at" => date("Y-m-d H:i:s"),
                "pagamento" => $this->pagamento,
                "descricao" => $this->descricao
            ]);

            if ($agendamento->id) {

                foreach ($this->item_id as $k => $item) {
                    $itemAgendamento = ModelItemAgendamento::query()->insert([
                        "item_id" => $this->item_id,
                        "agendamento_id" => $agendamento->id
                    ]);
                }

                if ($itemAgendamento)
                    echo "Agendamento realizado com sucesso!";

            }


        } catch (\Error $e) {
            echo "Ocorreu um erro " . $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}