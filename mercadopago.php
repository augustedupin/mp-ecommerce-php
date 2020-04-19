<?php
	// mercadopago API,  por cierto me dio el siguiente error
	// al seguir la documentacion Could not find package mercadopago/dx-php in a version matching dev-master
	require __DIR__  . '/vendor/autoload.php';

	// Agrega credenciales
	MercadoPago\SDK::setAccessToken('APP_USR-6718728269189792-112017-dc8b338195215145a4ec035fdde5cedf-491494389');
	MercadoPago\SDK::setClientId('491494389');

	$created = date("Y-m-d H:i:s", time());
	$nombre = $_POST["name"];
	$precio = $_POST["price"];
	$imagen = str_replace( './', '/', $_POST["image"]);

	$imagen = "https://".$_SERVER['SERVER_NAME'].$imagen;

	// Crea un objeto de preferencia
	$preference = new MercadoPago\Preference();
	$preference->external_reference = "ABCD1234";
	$preference->payment_methods = array(
		  "excluded_payment_methods" => array(
		    array(
		    	"id" => "amex"
		    )
		  ),
		  "excluded_payment_types" => array(
		    array(
		    	"id" => "atm"
		    ),
		    /*array(
		    	"id" =>"atm"
		    ),*/
		  ),
		  "installments" => 6
		);

	$preference->back_urls = array(
			    "success" => $_SERVER['SERVER_NAME']."/exitosa-mercadopago.php",
			    "failure" => $_SERVER['SERVER_NAME']."/fallida-mercadopago.php",
			    "pending" => $_SERVER['SERVER_NAME']."/pendiente-mercadopago.php"
	);
	$preference->auto_return = "approved";

	// Crea un ítem en la preferencia
	$item = new MercadoPago\Item();
	$item->id = "1234";
	$item->title = $nombre;
	$item->description = "Dispositivo móvil de Tienda e-commerce";
	$item->quantity = 1;
	$item->currency_id = "MXN";
	$item->unit_price = $precio;
	//$item->picture_url = "https://augustedupin-mp-commerce-php.herokuapp.com/assets/003.jpg";
	$item->picture_url = $imagen;

	$payer = new MercadoPago\Payer();
	$payer->name = "Lalo";
	$payer->surname = "Landa";
	$payer->email = "test_user_58295862@testuser.com";
	$payer->phone = array(
	    "area_code" => "55",
	    "number" => "49737300"
  	);
	//$payer->date_created = $created;
	$payer->address = array(
	    "street_name" => "Insurgentes Sur",
	    "street_number" => 1602,
	    "zip_code" => "03940"
  	);

	/*$shipments = new MercadoPago\Shipments();
		$shipments->receiver_address = array(
		      "zip_code" => "15510",
		      "street_number" => "473 D Bis",
		      "street_name" => "norte 164"
	);*/
	
	$preference->items = array($item);
	$preference->payer = $payer;
	$preference->notification_url = $_SERVER['SERVER_NAME']."/notificacion-mercadopago.php";
	$preference->save();

	/*if ($preference->init_point) {
		header("location:".$preference->init_point);
	}*/
?>
<form action="/procesar-pago" method="POST">
  <script
   src="https://www.mercadopago.com.mx/integrations/v1/web-payment-checkout.js"
   data-preference-id="<?php echo $preference->id; ?>">
  </script>
</form>