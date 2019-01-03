<?php

define('ROOT', dirname(__DIR__));

include ROOT.'/condominio/vendor/autoload.php';

/**
 * inicializando a conexão com o banco de dados
 */
use Model\Database;
new Database();

$dados = "";
$dados = json_decode(file_get_contents('php://input'), true);

if (is_null($dados)) {
    if (!empty($_POST))
        $dados = $_POST;

    if (!empty($_GET))
        $dados = $_GET;
}


$path = $_SERVER['PATH_INFO'];
$info = explode('/', $path);

$controller = "Controller\\".ucfirst($info[1]);
$method = $info[2];


$array = array();

if (isset($info[3]))
    $array[] = $info[3];

$conteudo = new $controller($dados);
call_user_func_array([$conteudo, $method], $array);

