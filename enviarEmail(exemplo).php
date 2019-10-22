<?php

require 'PHPMailer/PHPMailerAutoload.php';

//Construtor, idioma e caracteres especiais
$mail = new PHPMailer;
$mail->setLanguage('br');                             // Habilita as saídas de erro em Português
$mail->CharSet='UTF-8';                               // Habilita o envio do email como 'UTF-8'

//$mail->SMTPDebug = 3;                               // Habilita a saída do tipo "verbose"

//Configurações
$mail->isSMTP();                                      // Configura o disparo como SMTP
$mail->Host = 'smtplw.com.br';                        // Especifica o enderço do servidor SMTP da Locaweb
$mail->SMTPAuth = true;                               // Habilita a autenticação SMTP
$mail->Username = 'user';                        // Usuário do SMTP
$mail->Password = 'senha';                          // Senha do SMTP
$mail->SMTPSecure = 'tls';                            // Habilita criptografia TLS | 'ssl' também é possível
$mail->Port = 587;                                    // Porta TCP para a conexão

$mail->From = 'pessoa1@exemplo.com';                          // Endereço previamente verificado no painel do SMTP
$mail->FromName = 'Fulano';                     // Nome no remetente
$mail->addAddress('pessoa2@exemplo.com');// Acrescente um destinatário
//$mail->addAddress('joao@exemplo.com');                // O nome é opcional
//$mail->addReplyTo('info@exemplo.com', 'Informação');
//$mail->addCC('cc@exemplo.com');
//$mail->addBCC('bcc@exemplo.com');

$mail->isHTML(true);                                  // Configura o formato do email como HTML

$mail->Subject = 'Aplicação Estágio';
$mail->Body    = '<strong>Corpo da mensagem, aqui pode ir o HTML!</strong>';							//Body em HTML
$mail->AltBody = '';		//Body em plain-text

if(!$mail->send()) {
    echo 'A mensagem não pode ser enviada';
    echo 'Mensagem de erro: ' . $mail->ErrorInfo;
} else {
    echo 'Mensagem enviada com sucesso';
}

?>