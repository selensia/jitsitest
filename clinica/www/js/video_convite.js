/**
 * https://jitsi.github.io/handbook/docs/dev-guide/dev-guide-iframe
 * Faz requisição com o servidor para buscar dados de uma sala criada
 */
function entrarSala(){
    $(document).ready(function(){
        var token = document.getElementById('token').value;

        $.ajax({             
            type: 'POST',
            data: { 
                token: token, 
            },             
            url: 'https://transitabile.com.br/clinica/main/php/controle/controle.php?cmd=entrar',
            success : function(data){
                var response = $.parseJSON(data);
                if (response.status == 'failed') {
                    // exibir mensagem do backend 
                    alert(response.mensagem);
                } else {
                    entrar(response.mensagem);
                }
            },
            error : function(jqXhr, status , errorMessage){
                alert('Erro: ' + errorMessage);
            }         
        });
    });
}

/**
 * entra uma sala, para entrar em uma sala pelo nosso sistema iremos criar
 * @param {object} response
 */ 
function entrar(response){
    var nomeSala = response.nomeSala;
    var senha = response.senha;
    var nomePaciente = response.nomePaciente;

    var domain = 'meet.jit.si';
    var options = {
        roomName: nomeSala,
        width: 700,
        height: 700,
        userInfo: {
            email: 'email@jitsiexamplemail.com',
            displayName: nomePaciente
        },
        parentNode: document.querySelector('#meet')
    };
    var api = new JitsiMeetExternalAPI(domain, options);
    entrarComSenha(api, senha, nomePaciente);
}

/**
 * entra em uma sala com senha e ja insere o apelido para o usuário
 * @param {object} api
 * @param {string} senha
 */ 
function entrarComSenha(api, senha, nomePaciente){
    api.on('passwordRequired', function ()
    {
        api.executeCommand('password', senha);
    });
    api.executeCommand('displayName', nomePaciente);
}