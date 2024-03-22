<?php
 require "Asari.class.php";
require "components.php";
require "functions.php";
$path = '../wp-content/plugins/asari';
require_once('../../../wp-config.php');


$asari = new Asari(36881, '80h6uzv50IEW2AZCZm9uqrST1881Lkk83oOHlj6d');

 switch ($_POST['post']) {
    case 'show_counter':
        
		$id = $asari->get_not_updated_advID();
		$arr = $asari->listing($id)->data;
		
		$post_id = $asari->get_postID_by_adv_id($id);
		
		if( $post_id == 0 )
			$asari->insert_post_wpp($arr);
		else
			$asari->update_post_wpp($arr, $post_id);
		
		
		echo $asari->show_counter();
		
        break;
    case 'update_advert_ids':
        echo $asari->update_advert_ids();
        break;
    case 'is_all_updated':
        echo $asari->is_all_updated();
        break;    
	case 'reset_is_updated':
        echo $asari->reset_is_updated();
        break;
}
 


?>