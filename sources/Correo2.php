<?php

/*
 * Autor: Héctor Morales Palma
 * Fecha de Creación: 08 de Noviembre del 2013
 * Objetivo: Correo por SMTP vía GMAIL
 */
class Correo  {
//objeto mail
private $mail;

public function __construct($destinatario,$asunto,$body) {
    
    
    $this->mail = new PHPMailer();
    
    //$this->mail->SMTPDebug  = 1;
    $this->mail->isSMTP();
    $this->mail->Debugoutput = 'html';
    $this->mail->Host = "smtp.gmail.com";
    $this->mail->Port =  587;
    $this->mail->SMTPAuth = true;
    $this->mail->Username = "sapitotais@gmail.com";
    $this->mail->Password = "sapitotais2";
    $this->mail->isHTML(true);
    $this->mail->SMTPSecure = "tls";
    $this->mail->setFrom($this->mail->Username, 'Metaspace');
    //Set an alternative reply-to address
    //$mail->addReplyTo('replyto@example.com', 'First Last');
    //Set who the message is to be sent to
    //$mail->addAddress('enriquegprc@terra.com.mx', 'John Doe');
    $this->mail->addAddress($destinatario, '');
    //Set the subject line
    $this->mail->Subject = $asunto;
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative bod
    //Replace the plain text body with one created manuall
    $this->mail->Body =$body;       
    }
    /**
     * Función que envia correo
     */
    public function enviar() 
    {
            $this->mail->send();
    }
}
?>
