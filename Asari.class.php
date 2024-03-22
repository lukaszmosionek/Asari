<?php

class Asari{
	private $userId;
	private $loginToken;
	
	function __construct($userId, $loginToken){
		$this->userId = $userId;
		$this->loginToken = $loginToken;
		
		global $wpdb;
		$sel = $wpdb->query(" CREATE TABLE IF NOT EXISTS 
			".$wpdb->prefix."`_pluginAsari_ids_list` ( 
				`id` INT NOT NULL AUTO_INCREMENT , 
				`adv_id` INT NOT NULL UNIQUE, 
				`post_id` INT NOT NULL , 
				`updated_at` DATETIME NOT NULL ,
				`is_updated` BOOLEAN NOT NULL , 				
			PRIMARY KEY (`id`)) ENGINE = InnoDB; ");
		$sel = $wpdb->get_results("CREATE TABLE IF NOT EXISTS 
			`".$wpdb->prefix."_pluginAsari_credentials` ( 
				`USER` VARCHAR(5) NOT NULL UNIQUE, 
				`userID` INT NOT NULL ,
				`ids_list` INT  NOT NULL,  
				`loginToken` VARCHAR(255) NOT NULL) 
			ENGINE = InnoDB;");
	}	
	function exportedListingIdList(){
		$url = "https://api.asariweb.pl/apiSite/exportedListingIdList";

		$input = array(
			'userId'                                  =>      $this->userId, // authentication userId
			'loginToken'                           =>      $this->loginToken, // authentication loginToken
		);
		 
		$curl = curl_init();
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
		curl_setopt($curl, CURLOPT_URL, $url);
		$result = curl_exec($curl);
		curl_close($curl);
		
		return json_decode($result);
	}
	function listing($id){
		$url = "https://api.asariweb.pl/apiSite/listing";
	 
		$input = array(
			'userId'       =>      $this->userId, // authentication userId
			'loginToken'     =>      $this->loginToken, // authentication loginToken
			'id'          =>       $id // example: 51234524
		);
		 
		$curl = curl_init();
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
		curl_setopt($curl, CURLOPT_URL, $url);
		$result = curl_exec($curl);
		curl_close($curl);
		
		return json_decode($result);
	}
	function update_advert_ids(){
		$list = ($this->exportedListingIdList());
		
		if( $list->success ){	
			global $wpdb;
		//	$wpdb->show_errors();
			$i=0;
			foreach( $list->data as $one){
				$id = $one->id;
				$a = $wpdb->query("INSERT INTO `_pluginAsari_ids_list` SET `adv_id`='$id' ");
				if( $a ) $i++;
			}
			return 'Zaktualizowano liste ogłoszeń! Nowych ogłoszeń: '.$i;
			$sel = $wpdb->query(" UPDATE _pluginAsari_credentials SET ids_list=1 WHERE USER='USER' ");
			
		}else{
			return "Błąd uwierzytelniania. Sprawdź ID użytkownia i token.";	
		}			
	}
	function check_ids_list(){
		global $wpdb;
		return $sel = $wpdb->get_results(" SELECT  ids_list FROM _pluginAsari_credentials WHERE USER='USER' ")[0]->ids_list;
		
		
	}
	function get_not_updated_advID(){
		global $wpdb;		
		$sel = $wpdb->get_results(" SELECT * FROM `_pluginAsari_ids_list` WHERE is_updated=0 LIMIT 1 ");
		return $sel = $sel[0]->adv_id;					
	}
	function get_advID_by_id($id){
		global $wpdb;		
		$sel = $wpdb->get_results(" SELECT adv_id FROM `_pluginAsari_ids_list` WHERE post_id=$id LIMIT 1 ");
		return $sel = $sel[0]->adv_id;					
	}
	function is_all_updated(){
		global $wpdb;		
		$sel = $wpdb->get_results(" SELECT COUNT(id) as c FROM `_pluginAsari_ids_list` WHERE is_updated=0 ")[0]->c;				
		return $sel;					
	}
	

	function generate_post($adv_id){
				
				$r = $this->listing($adv_id)->data;
				
				//prt($r);
				
				
				//echo $img_id = $r->images[0]->id;
			
				
				//echo $img_url = 'https://img.asariweb.pl/normal/'.$img_id;
				//%ttile = Ulica/dzielnica/miasto
				//$arr[] = [ '', 'price', $r->street->fullName.'/'.$r->location->quarter.'.'.$r->location->locality ]
				
				$arr[] = [ 'ID', 'ID', $r->id, 'hide']; 
				$arr[] = [ 'Cena', 'price', $r->price->amount.' '.$r->price->currency ]; 
				$arr[] = [ 'Opis', 'desc', $r->description ]; 
				$arr[] = [ 'Lokacja', 'location', $r->location->name ];
				$arr[] = [ 'Kraj', 'country', $r->country->name ];
				$arr[] = [ 'Ulica', 'street', $r->street->fullName ];
				$arr[] = [ 'Data utworzenia', 'dateCreated', $r->dateCreated ];
				$arr[] = [ 'Data modyfikacji', 'lastUpdated', $r->lastUpdated ];
				$arr[] = [ 'Cena za metr kwadratowy', 'priceM2', $r->priceM2->amount.' '.$r->priceM2->currency ];
				$arr[] = [ 'Liczba pięter', 'floorNo', $r->floorNo ];
				$arr[] = [ 'Rok wybudowania', 'yearBuilt', $r->yearBuilt ];
				$arr[] = [ 'Czynsz administracyjny', 'administrativeRent', $r->administrativeRent->amount.' '.$r->administrativeRent->currency ];
				$arr[] = [ 'Windy', 'elevator', $r->elevator ];
				$arr[] = [ 'Typ budynku', 'buildingType', $r->buildingType ];
				$arr[] = [ 'Stan budynku', 'buildingCondition', $r->buildingCondition ];
				$arr[] = [ 'Materiał', 'material', $r->material ];
				$arr[] = [ 'Lokalizacja', 'title', $r->street->fullName.'/'.$r->location->quarter.'.'.$r->location->locality ];
			//	$arr[] = [ 'imię', 'firstName', $r->user->firstName ],[ 'Nazwisko:', 'lastName', $r->user->lastName ];
			
			
				make_directory('../wp-content/uploads/AsariPlugin');
				foreach( $r->images as $img){
					
					//echo copy( 'https://img.asariweb.pl/normal/'.$img->id, '../wp-content/uploads/AsariPlugin/normal'.$img->id.'.jpg');
					//echo copy( 'https://img.asariweb.pl/thumbnail/'.$img->id, '../wp-content/uploads/AsariPlugin/thumbnail'.$img->id.'.jpg');
					
					//$foto .= '<a href="'.get_site_url().'/wp-content/uploads/AsariPlugin/normal'.$img->id.'.jpg"><img style="display:inline-block;margin: 1px 0px 0px 1px;" class="img-responsive" src="'.get_site_url().'/wp-content/uploads/AsariPlugin/thumbnail'.$img->id.'.jpg" /></a>';	

					$foto .= '<a href="https://img.asariweb.pl/normal/'.$img->id.'"><img style="display:inline-block;margin: 1px 0px 0px 1px;" class="img-responsive" src="https://img.asariweb.pl/thumbnail/'.$img->id.'" /></a>';
				}
				$arr[] = [ 'Zdjęcia', 'foto', '<br>'.$foto ];
				
			

				return $r;
				return $arr;
	}
	function add_field(){
		$arrs = $this->save_post();
		foreach( $arrs as $a){
			if(!isset($a[3]))
				$string .= '<div class="field"><span style="font-weight:bold;" class="AsariValue">'.$a[0].': </span><span class="key">'.$a[2].'</span></div><br>';
		}
		return $string;
	}	
	function make_array(){
		$arrs = $this->save_post();
		foreach( $arrs as $a){
			$arr[$a[1]] =  $a[2] ;
		}
		return $arr;
	}
	function get_postID_by_adv_id($adv_id){
		global $wpdb;
		return $all = $wpdb->get_results("SELECT post_id FROM _pluginAsari_ids_list WHERE adv_id='$adv_id' ")[0]->post_id;
	}
	function count_all_adv(){
		global $wpdb;
		return $all = $wpdb->get_results("SELECT COUNT(id) as c FROM _pluginAsari_ids_list")[0]->c;
	}
	function count_updated_adv(){
		global $wpdb;
		return  $wpdb->get_results(" SELECT COUNT(id) as c FROM _pluginAsari_ids_list WHERE is_updated=1 ")[0]->c;
	}
	function show_counter(){
		$all = $this->count_all_adv();
		$upd = $this->count_updated_adv();
		
		$val= ($upd/$all)*100;
		
		return '
		<div class="progress" style="width: 300px;">
		  <div class="progress-bar" role="progressbar" style="width: '.numer_format($val).'%;" aria-valuenow="'.numer_format($val).'" aria-valuemin="0" aria-valuemax="100">'.numer_format($val).'%</div>
		</div><br>
		Zaktualizowano '.$upd.'/'.$all.' ogłoszeń.';
	}
	

	function reset_is_updated(){
		global $wpdb;
		$sel = $wpdb->query(" UPDATE `_pluginAsari_ids_list` SET  is_updated=0 ");						
	}

	
	function update_post_wpp($r, $post_id){
		global $wpdb;
		  $my_post = array(
			  'ID'           => $post_id,
			  'post_title'    => wp_strip_all_tags( $r->street->fullName.'/'.$r->location->quarter.'.'.$r->location->locality ),
			  'post_content'  => $r->description,
			  'post_status'   => 'publish',
			  'post_author'   => 1,
			  'post_type'   => 'property',
		  );

		// Update the post into the database
		  wp_update_post( $my_post );
		
		  
		update_post_meta($post_id, 'REAL_HOMES_property_address', $r->street->fullName);
		update_post_meta($post_id, 'REAL_HOMES_property_price', $r->price->amount);
		update_post_meta($post_id, 'REAL_HOMES_property_year_built', $r->yearBuilt);
		update_post_meta($post_id, 'REAL_HOMES_property_bathrooms', $r->noOfBathrooms);
		update_post_meta($post_id, 'REAL_HOMES_property_bathrooms', $r->buildingType);
		
			$term_city = $this->add_term($r->location->locality, 'property-city');
			$term_type = $this->add_term($r->buildingType, 'property-type');
			//$term_id2 = $this->add_term($r->sectionName, 'property-type');
			
			if( $r->sectionName == 'ApartmentRental' ) $sec = 21; 
			elseif( $r->sectionName == 'ApartmentSale' ) $sec = 22;

			
			wp_set_post_terms( $post_id, [$sec] , 'property-status');
			wp_set_post_terms( $post_id, [$term_city] , 'property-city');
			wp_set_post_terms( $post_id, [$term_type] , 'property-type');
		
		$this->post_images($post_id, $r);
		
		 $sel = $wpdb->query(" UPDATE `_pluginAsari_ids_list` SET  updated_at=NOW(),is_updated=1 WHERE adv_id='".$r->id."' ");	
						  
						  
	}
	function insert_post_wpp($r){
				global $wpdb;
			
				
						$my_post = array(
						  'post_title'    => wp_strip_all_tags( $r->street->fullName.'/'.$r->location->quarter.'.'.$r->location->locality ),
						  'post_content'  => $r->description,
						  'post_status'   => 'publish',
						  'post_author'   => 1,
						  'post_type'   => 'property',
						);
						 
						// Insert the post into the database
						$post_id = wp_insert_post( $my_post );

						$sel = $wpdb->query(" UPDATE `_pluginAsari_ids_list` SET  updated_at=NOW(),is_updated=1 , post_id = '".$post_id."' WHERE adv_id='".$r->id."' ");				
						add_post_meta($post_id, 'REAL_HOMES_property_address', $r->street->fullName);
						add_post_meta($post_id, 'REAL_HOMES_property_price', $r->price->amount);
						add_post_meta($post_id, 'REAL_HOMES_property_year_built', $r->yearBuilt);
						add_post_meta($post_id, 'REAL_HOMES_property_bathrooms', $r->noOfBathrooms);
						
						$term_city = $this->add_term($r->location->locality, 'property-city');
						$term_type = $this->add_term($r->buildingType, 'property-type');
						//$term_id2 = $this->add_term($r->sectionName, 'property-type');
						
						if( $r->sectionName == 'ApartmentRental' ) $sec = 21; 
						elseif( $r->sectionName == 'ApartmentSale' ) $sec = 22;
			
						
						wp_set_post_terms( $post_id, [$sec] , 'property-status');
						wp_set_post_terms( $post_id, [$term_city] , 'property-city');
						wp_set_post_terms( $post_id, [$term_type] , 'property-type');
						
						$this->post_images($post_id, $r);
					
			
	}
		
	function post_images($post_id, $arr){
		$i = 1;
		foreach( $arr->images as $img ){
			// echo $img->id;
			 
			 $attach_id = upload_user_file('https://img.asariweb.pl/normal/'.$img->id, 'AsariPluginPhoto-'.$post_id.'-'.$img->id.'.jpg');
			 if( $attach_id ) add_post_meta($post_id, 'REAL_HOMES_property_images', $attach_id);
			 
			 if( $i == 1 ) set_post_thumbnail( $post_id, $attach_id );
			 
			$i++; 
		 }
	}
	function add_term($name, $cat){
		$a = wp_insert_term($name, $cat) ;
		if( isset($a->error_data['term_exists']) )
			$term_id = $a->error_data['term_exists'];
		else
			$term_id = $a['term_id'];
		return $term_id;
	}
	
	
	static function isUserSet(){
		global $wpdb;
		$a = $wpdb->get_results("SELECT * FROM `_pluginAsari_credentials` WHERE `USER`='USER'");
		
		if( $a[0]->userID == '' AND $a[0]->loginToken == ''){
			return false;
		}else{
			return true;
		}
	}
	static function save_user(){
				global $wpdb;
				//$wpdb->show_errors();

			if( isset($_POST['userID'], $_POST['loginToken']) ){
				$userID = $_POST['userID'];
				$loginToken = $_POST['loginToken'];
				
				
				if( empty($userID) || empty($loginToken) ){
					echo '<div class="alert alert-danger">
					  <strong>Puste userID lub loginToken</strong>
					</div>';
				}else{			
					
					$wpdb->query("INSERT INTO `_pluginAsari_credentials` SET `USER`='USER'");
					$wpdb->query("UPDATE `_pluginAsari_credentials` SET `userID`='$userID', `loginToken`='$loginToken' WHERE `USER`='USER'");
				}
			}

		
	}
	
	
		
		
			
	
		
	
	
	
}
?>