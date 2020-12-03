<?php

header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL);
date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/Sala.php';

switch($_GET['cmd']) {
    case "criar":
        criar();
        break;
    case "link":
        criarLink();
        break;
    case "entrar":
        entrar();
        break;
    default:
        $retorno = new stdClass();
        $retorno->status = "failed";
        $retorno->mensagem = "Comando inexistente";
        echo json_encode($retorno);
        break;
}

/**
 * Função responsável por receber os parametros $_POST e enviar para a função iniciarCriacao() e repassar seu retorno para o front
 * @param array $_POST array com dados recebidos via post do front
 * @return $retorno retorna um objeto com status da ação e com mensagem descritiva de erro ou com dados
 */
function criar()
{
    $retorno = new stdClass();
    $retorno->status = "failed";

    $sala = new CriarSala();
    $retornoSala = $sala->iniciarCriacao($_POST);

    if ($retornoSala->status == "success") {
        $retorno->status = "success";
    }

    $retorno->mensagem = $retornoSala->mensagem;

    echo json_encode($retorno);
    return;
}

/**
 * Função responsável por receber os parametros $_POST e enviar para a função criarLink() e repassar seu retorno para o front
 * @param array $_POST array com dados recebidos via post do front
 * @return $retorno retorna um objeto com status da ação e com mensagem descritiva de erro ou com dados
 */
function criarLink()
{
    $retorno = new stdClass();
    $retorno->status = "failed";

    $sala = new CriarSala();
    $retornoSala = $sala->criarLink($_POST);

    if ($retornoSala->status == "success") {
        $retorno->status = "success";
    }

    $retorno->mensagem = $retornoSala->mensagem;

    echo json_encode($retorno);
    return;
}

/**
 * Função responsável por receber os parametros $_POST e enviar para a função entrarSala() e repassar seu retorno para o front
 * @param array $_POST array com dados recebidos via post do front
 * @return $retorno retorna um objeto com status da ação e mensagem com descritiva do erro ou com dados
 */
function entrar()
{
    $retorno = new stdClass();
    $retorno->status = "failed";

    $sala = new CriarSala();
    $retornoSala = $sala->entrarSala($_POST);

    if ($retornoSala->status == "success") {
        $retorno->status = "success";
    }

    $retorno->mensagem = $retornoSala->mensagem;

    echo json_encode($retorno);
    return;
}