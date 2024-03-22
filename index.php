<?php
/**
 * @package Asari
 * @version 1.0
 */
/*
Plugin Name: Asari
Plugin URI: http://mczerwinski.com/
Description: Wtyczka pobierająca dane z asari.pl poprzez api.
Author: Mateusz Czerwiński
Version: 1.0
Author URI: http://mczerwinski.com/
License: GPL
*/

if ( !defined('ABSPATH') ){die('-1');}

//require "Asari.class.php";

//$asari = new Asari(36881, '80h6uzv50IEW2AZCZm9uqrST1881Lkk83oOHlj6d');
//print_r( $asari->advert_list() );

//print_r(advertList());

function asari_options_page()
{
    add_menu_page(
        'Asari',
        'Asari',
        'manage_options',
        'asari',
        'asari_admin_index',
        'dashicons-download',
        20
    );
}

add_action('admin_menu', 'asari_options_page');



function asari_admin_index(){
	require __DIR__ .'/AsariPluginTab.php';
	//echo'<div class="wrap"><h1>Wtyczka Asari</h1></div>';
} 
?>