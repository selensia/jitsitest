/**
 * https://jitsi.github.io/handbook/docs/dev-guide/dev-guide-iframe
 * cria uma sala, para criação é necessário definir o dominio e nome da sala o restante das informções
 * não são obrigatórias, mas servem para definir tamanho, apelido para utilização etc.
 */ 
function criaSala(){
    $(document).ready(function(){
        $('#criar').click(function (){
            var nomeSala = document.getElementById('nomeSala').value;
            var senha = document.getElementById('senha').value;
            var nomeMedico = document.getElementById('nomeMedico').value;

            var domain = 'meet.jit.si';
            var options = {
                roomName: nomeSala,
                width: 700,
                height: 700,
                userInfo: {
                    email: 'email@jitsiexamplemail.com',
                    displayName: nomeMedico
                },
                parentNode: document.querySelector('#meet')
            };
            var api = new JitsiMeetExternalAPI(domain, options);
            criarSenha(api, senha);

            salvarSala(nomeSala, senha, nomeMedico);
        });
    });
}

/**
 * iniciar requisição com o servidor para salvar dados da consulta
 * @param {string} nomeSala 
 * @param {string} senha 
 * @param {string} nomeMedico 
 */
function salvarSala(nomeSala, senha, nomeMedico){
    var nomePaciente = document.getElementById('nomePaciente').value;

    $.ajax({             
        type: 'POST',
        data: { 
            nomeSala: nomeSala, 
            senha: senha,
            nomeMedico: nomeMedico,
            nomePaciente: nomePaciente
        },             
        url: 'https://transitabile.com.br/clinica/main/php/controle/controle.php?cmd=criar',
        success : function(data){
            var response = $.parseJSON(data);
            if (response.status == 'failed') {
                // exibir mensagem do backend 
                alert(response.mensagem);
            } else {
                criarLink(response.mensagem);
            }
        },
        error : function(jqXhr, status , errorMessage){
            alert('Erro: ' + errorMessage);
        }         
     });
}

/**
 * função de teste para adquirir o link do paciente
 * @param {string} token 
 */
function criarLink(token){
    $.ajax({             
        type: 'POST',
        data: { 
            token: token
        },             
        url: 'https://transitabile.com.br/clinica/main/php/controle/controle.php?cmd=link',
        success : function(data){
            var response = $.parseJSON(data);
            if (response.status == 'failed') {
                // exibir mensagem do backend 
                alert(response.mensagem);
            } else {
                // pegar link retorno e jogar pra algum lugar  
                var link = '<a class="link" href="https://transitabile.com.br/clinica/www/index.php?token=' + response.mensagem + '" target="blank" >Link para entrar na sala</a>';
                // -classe- inserir
                $(".link").append(link);
            }
        },
        error : function(jqXhr, status, errorMessage){
            alert('Erro: ' + jqXhr);
        }         
     });
}

/**
 * cria a senha para a sala
 * @param {object} api
 * @param {string} senha
 */ 
function criarSenha(api, senha){
    api.addEventListener('participantRoleChanged', function(event) {
        if (event.role === "moderator") {
            api.executeCommand('password', senha);
        }
    });
}

/**
 * serve para desativar o jitsi
 */
function fecharSala()
{
    api.isVideoAvailable().then(available => {
        api.dispose();
    });
}