<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;


    require '../phpmailer/src/Exception.php';
    require '../phpmailer/src/PHPMailer.php';
    require '../phpmailer/src/SMTP.php';


    if (isset($_POST["asunto"]) && isset($_POST["mensaje"])) {
        
        $asunto = $_POST["asunto"];
        $mensaje = $_POST["mensaje"];
        $mail= new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username= 'videoclubrubio@gmail.com';
        $mail->Password='wmwejdhdatvkuyze';
        $mail->SMTPSecure='ssl';
        $mail->Port=465;

        $mail->setFrom('videoclubrubio@gmail.com');
        $mail->addAddress('videoclubrubio@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body= $mensaje;
        $mail->send();
        
        header("Location: ../pages/contactoUser.php?error=2");
        
    }else{
        header("Location: ../pages/contactoUser.php?error=1");
    }

?>
