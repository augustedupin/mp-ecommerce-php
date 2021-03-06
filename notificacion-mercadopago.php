<?php
    require __DIR__  . '/vendor/autoload.php';
    require_once "phpmailer/PHPMailerAutoload.php";
    require_once "phpmailer/class.phpmailer.php";
    require_once "phpmailer/class.smtp.php";

    MercadoPago\SDK::setAccessToken('APP_USR-6588866596068053-041607-428a530760073a99a1f2d19b0812a5b6-491494389');

    $merchant_order = null; 
    switch($_GET["topic"]) {
        case "payment":
            $payment = MercadoPago\Payment::find_by_id($_GET["id"]);
            var_dump($payment);
            // Get the payment and the corresponding merchant_order reported by the IPN.
            $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
            break;
        case "merchant_order":
            $merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
            break;
    }

    $paid_amount = 0;
    foreach ($merchant_order->payments as $payment) {
        if ($payment['status'] == 'approved'){
            $paid_amount += $payment['transaction_amount'];
        }
    }

    // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
    if($paid_amount >= $merchant_order->total_amount){
        if (count($merchant_order->shipments)>0) { // The merchant_order has shipments
            if($merchant_order->shipments[0]->status == "ready_to_ship") {
                print_r("Totally paid. Print the label and release your item.");
            }
        } else { // The merchant_order don't has any shipments
            print_r("Totally paid. Release your item.");
        }
    } else {
        print_r("Not paid yet. Do not release your item.");
    }

            //Procesamos el email a enviar
    $body = "<p> El topico es".$_GET["topic"]." y la id es ".$_GET["id"]."</p>";
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = "mail.sunmex.com"; // SMTP a utilizar. Por ej. smtp.elserver.com
    $mail->Username = "admin@sunmex.com"; // Correo completo a utilizar
    $mail->Password = "bu7PKNnDIBqD"; // Contraseña
    $mail->Port = 587; // Puerto a utilizar
    $mail->From = "admin@sunmex.com"; // Desde donde enviamos (Para mostrar)
    $mail->FromName = "Postmaster Sunmex";
    $mail->AddAddress("cdabraxas377@gmail.com"); // Esta es la dirección a donde enviamos
        //$mail->AddAddress("javierbzn.vidafull@gmail.com"); // Esta es la dirección de prueba.
        //$mail->AddAddress("cdelgado.vidafull@gmail.com"); // Esta es la dirección de prueba.
        //$mail->AddCC("cuenta@dominio.com"); // Copia
        //$mail->AddBCC("cuenta@dominio.com"); // Copia oculta

    $mail->IsHTML(true); // El correo se envía como HTML
    $mail->Subject = "Respuesta de mercadopago"; // Este es el titulo del email.
    $mail->Body = $body; // Mensaje a enviar

    $exito = $mail->Send(); // Envía el correo.

    if($exito) {
            echo "exito";
    } else {
            echo 'Hubo un inconveniente. Contacta a un administrador.';
    }
?>