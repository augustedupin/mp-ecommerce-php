<?php
	// mercadopago API,  por cierto me dio el siguiente error
	// al seguir la documentacion Could not find package mercadopago/dx-php in a version matching dev-master
	require __DIR__  . '/vendor/autoload.php';

	// Agrega credenciales
	MercadoPago\SDK::setAccessToken('APP_USR-6718728269189792-112017-dc8b338195215145a4ec035fdde5cedf-491494389');

	// Crea un objeto de preferencia
	$preference = new MercadoPago\Preference();

	// Crea un ítem en la preferencia
	$item = new MercadoPago\Item();
	$item->title = 'Mi producto';
	$item->quantity = 1;
	$item->unit_price = 75.56;
	$preference->items = array($item);
	$preference->save();

	if ($preference->init_point) {
		header("location:".$preference->init_point);
	}
?>
