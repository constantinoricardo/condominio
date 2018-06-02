<?php

namespace Controller;

use Repositories\Morador as MoradorRepositories;
use Repositories\Items as ItemsRepositories;
use Model\Agendamento as ModelAgendamento;
use Model\ItemAgendamento as ModelItemAgendamento;
use Helper\Data;
use Illuminate\Database\Capsule\Manager as DB;
use Sirius\Validation\Validator;

class Agendamento extends Controller
{

    private $id;

    private $morador_id;

    private $data_reserva;

    private $descricao;

    private $item_id;

    public function getValidator()
    {
        $validator = new Validator();
        $validator->add('morador_id', 'required', '', 'Morador é obrigatório.');
        $validator->add('data_reserva', 'required', '', 'Data da Reserva é obrigatória.');
        $validator->add('descricao', 'required', '','Descrição é obrigatória.');
        $validator->add('item_id', 'required', '','Item é obrigatório.');

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

        $this->id = $params['id'];
        $this->morador_id = $params['morador_id'];
        $this->data_reserva = Data::formatDateDatabase($params['data_reserva']);
        $this->descricao = $params['descricao'];
        $this->item_id = $params['item_id'];
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

    public function agendar() : string
    {

        try {

            $this->parametros();
            $this->verificarExisteAgendamentoData();

            DB::connection()->beginTransaction();

            $modalAgendamento = new ModelAgendamento();
            $modalAgendamento->morador_id = $this->morador_id;
            $modalAgendamento->data_reserva = $this->data_reserva;
            $modalAgendamento->date_included_at = date("Y-m-d H:i:s");
            $modalAgendamento->pagamento = ItemsRepositories::findPrice($this->item_id);
            $modalAgendamento->descricao = $this->descricao;
            $modalAgendamento->save();

            if ($modalAgendamento->id) {

                foreach ($this->item_id as $k => $item) {
                    $itemAgendamento = ModelItemAgendamento::query()->insert([
                        "item_id" => $item,
                        "agendamento_id" => $modalAgendamento->id
                    ]);
                }

                if ($itemAgendamento) {
                    $response = array(
                        "status" => 1,
                        "message" => "Agendamento realizado com sucesso!"
                    );
                    echo json_encode($response);
                    DB::connection()->commit();
                }
            }
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