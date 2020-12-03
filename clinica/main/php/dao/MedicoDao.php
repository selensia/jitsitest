<?php

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/Conectar.php';

class MedicoDao extends Conectar
{

    /**
     * Função responsável por inserir dados no banco de dados
     * tabela medico
     * @param object $medico objeto com dados do medico
     * @return array $retorno um array para tratamento de erro 
     */
    public function insert($medico)
    {
        $conn = $this->conectarBD();
        $data = date("Y-m-d H:i:s");

        try {
            $stmt = $conn->prepare("INSERT INTO medico (nome, created, modified) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $medico->nomeMedico, $data, $data);
            $stmt->execute();

            $retorno = array(
                "erro" => false,
                "mensagem" => $medico
            );

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
     * tabela medico
     * @param int $id id de identificação do medico
     * @return array $retorno um array para tratamento de erro ou com valores pesquisados
     */
    public function selectById($id)
    {
        $conn = $this->conectarBD();

        try {
            $stmt = $conn->prepare("SELECT * FROM medico WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        
            $resultado = $stmt->get_result();
            $row = $resultado->fetch_assoc();

            if ($row)
            {
                $retorno = array(
                    "id" => $row["id"],
                    "nomeMedico" => $row["nome"],
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
     * Função responsável por pesquisar dados no banco de dados por nome
     * tabela medico
     * @param object $name objeto com nome do médico
     * @return array $retorno um array para tratamento de erro ou com valores pesquisados
     */
    public function selectByName($name)
    {
        $conn = $this->conectarBD();

        try {
            $stmt = $conn->prepare("SELECT * FROM medico WHERE nome LIKE ?");
            $stmt->bind_param("s", $name);
            $stmt->execute();
        
            $resultado = $stmt->get_result();
            $row = $resultado->fetch_assoc();

            if ($row)
            {
                $retorno = array(
                    "id" => $row["id"],
                    "nome" => $row["nome"]
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