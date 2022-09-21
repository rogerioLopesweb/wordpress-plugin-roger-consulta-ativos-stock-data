<?php

/*
Plugin Name: Roger Consulta Ativo em Stock Data

Description: Consulta ativo via API da alphavantage.co

Version: 1.0

Author: TECH LEAD - Rogério Lopes

Author URI: https://www.linkedin.com/in/rogerio-tech-lead/

License: GPL2

*/
// inclui as rotinas do plugin
require_once plugin_dir_path(__FILE__) . '/includes/roger-admin.php';
require_once plugin_dir_path(__FILE__) . '/includes/roger-api-stock-data.php';
require_once plugin_dir_path(__FILE__) . '/includes/roger-card.php';
require_once plugin_dir_path(__FILE__) . '/includes/roger-shortcode.php';
//pega os cadastrado no admin em Configurações Suno Nasdaq
$options = get_option('roger_stock_plugin_options');
$roger_car_view = $options['roger_card_view']; //pega a forma escolhi para exibica

//se a forma escolhi foi depois da tag body
if($roger_car_view=="roger_tag_body_view"){
  //para versoes mais antigas do WP, o mais indicado e rodar no wp atual 
    //o tema/templete te que conter a funcao wp_body_open, para que o card seja exibido depois da tab body
    if ( ! function_exists( 'wp_body_open' ) ) {
        function wp_body_open() {
            do_action( 'wp_body_open' );
        }
    }
    //adiciona o card depois da tag body
    add_action('wp_body_open','roger_card_ativo_view');
}

?>