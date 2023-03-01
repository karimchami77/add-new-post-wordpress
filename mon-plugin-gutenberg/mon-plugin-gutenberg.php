<?php
/*
Plugin Name: Mon Plugin Gutenberg
Plugin URI: https://example.com/
Description: Un plugin pour ajouter un bloc Gutenberg qui permet de créer des messages.
Version: 1.0.0
Author: Votre Nom
Author URI: https://example.com/
License: GPL2
*/

// Sécurité : si ce fichier est appelé directement, on arrête l'exécution du code.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// On enregistre le bloc Gutenberg.
add_action( 'init', 'mon_plugin_gutenberg_register_block' );
function mon_plugin_gutenberg_register_block() {

	// Si Gutenberg n'est pas activé, on arrête l'exécution de la fonction.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	// On enregistre les fichiers CSS et JS pour le bloc.
	wp_register_style(
		'mon-plugin-gutenberg-style',
		plugins_url( 'css/style.css', __FILE__ ),
		array(),
		filemtime( plugin_dir_path( __FILE__ ) . 'css/style.css' )
	);

	wp_register_script(
		'mon-plugin-gutenberg-script',
		plugins_url( 'js/script.min.js', __FILE__ ),
		array( 'wp-blocks', 'wp-element', 'wp-editor' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'js/script.js' ),
		true
	);

	// On enregistre le bloc Gutenberg.
	register_block_type( 'mon-plugin-gutenberg/message', array(
		'style' => 'mon-plugin-gutenberg-style',
		'editor_script' => 'mon-plugin-gutenberg-script',
		'render_callback' => 'mon_plugin_gutenberg_render_block',
	) );
}

// Fonction pour rendre le bloc.
function mon_plugin_gutenberg_render_block( $attributes ) {
	ob_start();
	include( plugin_dir_path( __FILE__ ) . 'templates/block-template.php' );
	return ob_get_clean();
}
