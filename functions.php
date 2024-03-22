<?php

date_default_timezone_set('Europe/Warsaw');
function make_directory($path){
	if (!file_exists($path)) {
		mkdir($path, 0777, true);
	}
}

function make_curl($url){
  // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
			return $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);
}

function show_date(){
	return date('Y-m-d H:i:s', strtotime(' -1 day') );
}		
function numer_format($number){
	return number_format($number,2 ,'.',' ');
}
function prt($val){
	echo '<pre>';
		print_r($val);
	echo '</pre>';
}


function upload_user_file( $url, $filename ) {

		$uploaddir = wp_upload_dir();
		//$uploadfile = $uploaddir['path'] . '/' . $filename;
		$uploadfile = $uploaddir['basedir'] . '/AsariPlugin/' . $filename;
		
		if( file_exists($uploadfile) ) return false;

		$contents= file_get_contents($url);
		$savefile = fopen($uploadfile, 'w');
		fwrite($savefile, $contents);
		fclose($savefile);

		include_once( ABSPATH . 'wp-admin/includes/image.php' );

		$wp_filetype = wp_check_filetype(basename($filename), null );

		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => $filename,
			'post_content' => '',
			'post_status' => 'inherit'
		);

		$attach_id = wp_insert_attachment( $attachment, $uploadfile );

		$imagenew = get_post( $attach_id );
		$fullsizepath = get_attached_file( $imagenew->ID );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
}
?>