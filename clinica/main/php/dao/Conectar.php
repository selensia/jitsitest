<?php

class Conectar
{

    private $conn = null;

    /**
     * Função responsável por criar conexão com o banco de dados
     * @return conexao $conn retorna a conexão
     */
    function conectarBD()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $servername = "localhost";
        $username = "trans593_clinica_medica";
        $password = "Cl!n!ca2020";
        $databasename = "trans593_clinica_medica";

        $this->conn = new mysqli($servername, $username, $password, $databasename);
        $this->conn->set_charset("utf8");
        return $this->conn;
    }
}