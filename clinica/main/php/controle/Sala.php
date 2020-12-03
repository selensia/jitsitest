<?php

require_once __DIR__ . '/../entidade/Sala.php';
require_once __DIR__ . '/../entidade/Medico.php';
require_once __DIR__ . '/../dao/MedicoDao.php';
require_once __DIR__ . '/../dao/ConsultaDao.php';

class CriarSala
{

    /**
     * Função responsável por realizar gerenciar os dados que serão enviados ao banco de dados e tratar seus erros
     * Dados para criação de sala
     * @param array @post array com dados para criação de sala
     * @return object $retorno retorna um objeto com status da ação e mensagem tratata 
     */
    public function iniciarCriacao($post)
    {
        $retorno = new stdClass();
        $sala = new Sala();
        $sala->medico = new Medico();
        $medicoDao = new MedicoDao();
        $consultaDao = new ConsultaDao();

        $retorno->status = "failed";

        $retornoMedicoDao;
        $retornoConsultaDao;

        $sala->nomeSala = $post['nomeSala'];
        $sala->senha = $post['senha'];
        $sala->nomePaciente = $post['nomePaciente'];
        $sala->medico->nomeMedico = $post['nomeMedico'];

        $retornoMedicoDao = $medicoDao->selectByName($sala->medico->nomeMedico);

        if ($retornoConsultaDao['erro'] == true && $retornoConsultaDao['mensagem' == "SemDados"]) 
        {
            $retorno->mensagem = "Não foi localizado nenhum médico com o nomo informado.";
            return $retorno;
        }

        if ($retornoMedicoDao['erro'] == true)
        {
            $retorno->mensagem = "Erro no banco de dados.";
            return $retorno;
        }

        $sala->medico->id = $retornoMedicoDao['id'];

        $sala->token = md5(time() . $sala->nomeSala . $sala->nomeCliente);

        $retornoConsultaDao = $consultaDao->insert($sala);

        if ($retornoConsultaDao['erro'] === true)
        {
            $retorno->mensagem = "Erro ao salvar consulta.";
            return $retorno;
        }

        $retorno->status = "success";
        $retorno->mensagem = $sala->token;
        return $retorno;
    }

    /**
     * Função responsável por realizar gerenciar os dados que serão enviados ao banco de dados e tratar seus erros
     * Dados para geração do link para a sala
     * @param array @post array com único dado, sendo este o token da sala
     * @return object $retorno retorna um objeto com status da ação e mensagem tratata 
     */
    public function criarLink($post)
    {
        $retorno = new stdClass();
        $retorno->status = "failed";

        $token = $post['token'];
        $tipoLink = "link";

        if ($tipoLink === "link")
        {
            $retorno->status = "success";
            $retorno->mensagem = $token; //"https://transitabile.com.br/clinica/www/index.php?token=" . $token;
            return $retorno;
        } 
        else if ($tipoLink === "email")
        {
            $to = "doneduardold@gmail.com";
            $subject = "Teste - Clinica médica";
            $txt = '<!DOCTYPE html>
                    <html lang="pt-br" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>
                    <meta charset="utf-8">
                    </head>
                    <body style="color:#000000 !important;"> <p> Link do app clínica médica.</p>
                    <p>Caso não tenha feito nenhum cadastro ignore este email.
                    <br><br>Clique <a href="https://transitabile.com.br/clinica/www/convidado/index.php?token=' . $token . '"><b>aqui</b></a> para acessar a sala.</p>
                    </body>
                    </html>';
            $headers = "From: Clinica <contatomariana@transitabile.com.br>" . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8;' . "\r\n";

            if (mail($to, $subject, $txt, $headers)) 
            {
                $resultado = "Foi enviado um email para confimar seu cadastro, por favor verifique a caixa de entrada e de spam.";
                
                $retorno->status = "success";
                $retorno->mensagem = $resultado;
                return $retorno;
            } 
            else 
            {
                $retorno->mensagem = "Erro: Não foi possível enviar o email com a confirmação do cadastro, por favor tente novamente.";
                return $retorno;
            }
        }
    }

    /**
     * Função responsável por realizar gerenciar os dados que serão enviados ao banco de dados e tratar seus erros
     * Dados para entrar em uma sala
     * @param array @post array com um único dado sendo este o token da sala
     * @return object $retorno retorna um objeto com status da ação e mensagem tratata 
     */
    public function entrarSala($post)
    {
        $retorno = new stdClass();
        $sala = new Sala();
        $consultaDao = new ConsultaDao();
        $retorno->status = "failed";

        $sala->token = $post['token'];

        $retornoConsultaDao = $consultaDao->selectByToken($sala->token);

        if ($retornoConsultaDao['erro'] == true && $retornoConsultaDao['mensagem' == "SemDados"]) 
        {
            $retorno->mensagem = "Token inexistente ou expirado.";
            return $retorno;
        }

        if ($retornoConsultaDao['erro'] == true)
        {
            $retorno->mensagem = "Erro no banco de dados.";
            return $retorno;
        }

        $retorno->status = "success";
        $retorno->mensagem = $retornoConsultaDao;
        return $retorno;
    }
}