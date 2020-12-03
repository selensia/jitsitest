<?php 

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/Conectar.php';

class ConsultaDao extends Conectar
{

    /**
     * Função responsável por inserir dados no banco de dados
     * tabela consulta
     * @param object $consulta objeto com dados para da criação da sala
     * @return array $retorno um array para tratamento de erro 
     */
    public function insert($consulta)
    {
        $conn = $this->conectarBD();
        $data = date("Y-m-d H:i:s");

        try {
            $stmt = $conn->prepare("INSERT INTO consulta (medico_id, nome_paciente, nome_sala, senha, token, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $consulta->medico->id, $consulta->nomePaciente, $consulta->nomeSala, $consulta->senha, $consulta->token, $data, $data);
            if ($stmt->execute())
            {
                $retorno = array(
                    "erro" => false,
                    "mensagem" => $consulta
                );
            } 
            else  if ($stmt->errno)
            {
                $retorno = array (
                    "erro" => true,
                    "mensagem" => Api::error('DATABASE-ERROR', "statement-error (code:$stmt->errno) $stmt->error", 400)
                );
            }
        } catch (Exception $ex) {
            $retorno = array(
                "erro" => true,
                "mensagem" => $ex
            );
        } finally {
            $stmt->close();
            $conn->close();
            return $retorno;
        }
    }

    /**
     * Função responsável por pesquisar dados no banco de dados por id
     * tabela consulta
     * @param int $id id de identificação da consulta
     * @return array $retorno um array para tratamento de erro ou com valores pesquisados
     */
    public function selectById($id)
    {
        $conn = $this->conectarBD();

        try {
            $stmt = $conn->prepare("SELECT * FROM consulta WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        
            $resultado = $stmt->get_result();
            $row = $resultado->fetch_assoc();

            if ($row)
            {
                $retorno = array(
                    "id" => $row["id"],
                    "idMedico" => $row["medico_id"],
                    "nomePaciene" => $row["nome_paciente"],
                    "nomeSala" => $row["nome_sala"],
                    "senha" => $row["senha"],
                    "dataCriacao" => $row["created"],
                    "dataModificacao" => $row["modified"]
                );
            } 
            else
            {
                $retorno = array(
                    "erro" => true,
                    "mensagem" => "SemDados"
                );
            }
        } catch (Exception $ex) {
            $retorno = array(
                "erro" => true,
                "mensagem" => $ex
            );
        } finally {
            $stmt->close();
            $conn->close();
            return $retorno;
        }
    }

    /**
     * Função responsável por pesquisar dados no banco de dados por token
     * tabela consulta
     * @param object $token objeto com token
     * @return array $retorno um array para tratamento de erro ou com valores pesquisados
     */
    public function selectByToken($token)
    {
        $conn = $this->conectarBD();

        try {
            $stmt = $conn->prepare("SELECT * FROM consulta WHERE token LIKE ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
        
            $resultado = $stmt->get_result();
            $row = $resultado->fetch_assoc();

            if ($row)
            {
                $retorno = array(
                    "id" => $row["id"],
                    "nomePaciente" => $row["nome_paciente"],
                    "nomeSala" => $row["nome_sala"],
                    "senha" => $row["senha"]
                );
            } 
            else
            {
                $retorno = array(
                    "erro" => true,
                    "mensagem" => "SemDados"
                );
            }
        } catch (Exception $ex) {
            $retorno = array(
                "erro" => true,
                "mensagem" => $ex
            );
        } finally {
            $stmt->close();
            $conn->close();
            return $retorno;
        }
    }
}