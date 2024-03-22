<?php
require 'config.php';

bootstrap();

?>


<div class="wrap"><h1>Wtyczka Asari</h1></div><br><br>

<button class="btn btn-primary" onClick="send_ajax('update_advert_ids');">Pobierz liste ogloszeń</button>
<small>Pobiera liste ogłoszeń, bez ich zawartości(np. tytuł, zdjęcia)</small><br><hr><br>

<button class="btn btn-primary" onClick="send_ajax_inteval($(this),'restart');"">Aktualizuj wszystkie ogłoszenia od nowa</button><br><br>
<button class="btn btn-primary" onClick="send_ajax_inteval($(this));">Aktualizuj ogłoszenia</button><br><br>

<br><br><div class="ajax_loader"></div><div class="result"></div><br><br>

<?php

//$asari = new Asari(36881, '80h6uzv50IEW2AZCZm9uqrST1881Lkk83oOHlj6d');
//echo $asari->show_counter();
/*
$term_id = $asari->add_term('miasto', 'property-city');
wp_set_post_terms( $post_id, $terms)

echo $term_id;
/*
if( Asari::isUserSet() ){
				if( $asari->check_ids_list() ){
					$a = $asari->get_postID_by_adv_id(123);
					prt($a);
				}else{
					$asari->advert_ids();
				}
}else{
	form_login();
	Asari::save_user();
}  
  */
echo '<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>';
echo '<script src="'.$path.'/js/functions.js?v='.rand().'"></script>';  

?>
