<?php
// Sécurité : si ce fichier est appelé directement, on arrête l'exécution du code.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// On charge les fichiers nécessaires pour la page d'administration.
add_action( 'admin_enqueue_scripts', 'mon_plugin_gutenberg_admin_enqueue_scripts' );
function mon_plugin_gutenberg_admin_enqueue_scripts() {
	wp_enqueue_script(
		'mon-plugin-gutenberg-admin-script',
		plugins_url( 'js/admin.js', __FILE__ ),
		array( 'jquery' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'js/admin.js' )
	);
	wp_localize_script(
		'mon-plugin-gutenberg-admin-script',
		'mon_plugin_gutenberg_admin_data',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'mon-plugin-gutenberg-nonce' ),
			'error_message' => __( 'Un message avec ce titre existe déjà.', 'mon-plugin-gutenberg' )
		)
	);
}

// On crée la page d'administration.
add_action( 'admin_menu', 'mon_plugin_gutenberg_admin_menu' );
function mon_plugin_gutenberg_admin_menu() {
	add_menu_page(
		'Mon Plugin Gutenberg',
		'Mon Plugin Gutenberg',
		'manage_options',
		'mon-plugin-gutenberg',
		'mon_plugin_gutenberg_admin_page',
		'dashicons-email-alt2',
		90
	);
}

// Fonction pour afficher la page d'administration.
function mon_plugin_gutenberg_admin_page() {
	include( plugin_dir_path( __FILE__ ) . 'templates/admin-template.php' );
}

// Fonction pour créer le message.
add_action( 'wp_ajax_mon_plugin_gutenberg_create_message', 'mon_plugin_gutenberg_create_message' );
function mon_plugin_gutenberg_create_message() {
	check_ajax_referer( 'mon-plugin-gutenberg-nonce', 'security' );

	$title = sanitize_text_field( $_POST['title'] );
	$content = wp_kses_post( $_POST['content'] );

	// On vérifie que le titre n'existe pas déjà.
	$post = get_page_by_title( $title, OBJECT, 'post' );
	if ( $post ) {
		wp_send_json_error( array( 'message' => __( 'Un message avec ce titre existe déjà.', 'mon-plugin-gutenberg' ) ) );
	}

	// On crée le message.
	$post_id = wp_insert_post( array(
		'post_title' => $title,
		'post_content' => $content,
		'post_status' => 'draft',
		'post_type' => 'post',
	) );

	// On envoie un e-mail à l'administrateur.
	$admin_email = get_option( 'admin_email' );
	$subject = __( 'Nouveau message créé', 'mon-plugin-gutenberg' );
	$message = sprintf( __( 'Un nouveau message a été créé : %s', 'mon-plugin-gutenberg' ), $title );
	wp_mail( $admin_email, $subject, $message );

	wp_send_json_success( array( 'message' => __( 'Le message a été créé avec succès.', 'mon-plugin-gutenberg' ) ) );
}
