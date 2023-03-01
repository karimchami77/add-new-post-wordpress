<?php

// Ajout des scripts et styles
function mon_plugin_gutenberg_enqueue_scripts() {
    wp_enqueue_style(
        'mon-plugin-gutenberg-style',
        plugins_url( 'css/style.css', __FILE__ ),
        array(),
        filemtime( plugin_dir_path( __FILE__ ) . 'css/style.css' )
    );

    wp_enqueue_script(
        'mon-plugin-gutenberg-script',
        plugins_url( 'js/script.js', __FILE__ ),
        array( 'jquery' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'js/script.js' ),
        true
    );
}
add_action( 'enqueue_block_editor_assets', 'mon_plugin_gutenberg_enqueue_scripts' );

// Vérification du titre du message
function mon_plugin_gutenberg_validate_title() {
    if ( isset( $_POST['title'] ) ) {
        $post_title = sanitize_text_field( $_POST['title'] );
        $post = get_page_by_title( $post_title, 'OBJECT', 'post' );

        if ( $post ) {
            wp_send_json_error( 'Un message avec ce titre existe déjà.' );
        } else {
            wp_send_json_success();
        }
    }
}
add_action( 'wp_ajax_mon_plugin_gutenberg_validate_title', 'mon_plugin_gutenberg_validate_title' );
add_action( 'wp_ajax_nopriv_mon_plugin_gutenberg_validate_title', 'mon_plugin_gutenberg_validate_title' );

// Création du message
function mon_plugin_gutenberg_create_message() {
    check_ajax_referer( 'mon_plugin_gutenberg_nonce', 'security' );

    $post_title = sanitize_text_field( $_POST['title'] );
    $post_content = wp_kses_post( $_POST['content'] );

    $post_data = array(
        'post_title'   => $post_title,
        'post_content' => $post_content,
        'post_status'  => 'draft',
        'post_type'    => 'post'
    );
    $post_id = wp_insert_post( $post_data );

    if ( $post_id ) {
        wp_send_json_success();
        $admin_email = get_option( 'admin_email' );
        $message = 'Un nouveau message a été créé : ' . $post_title . ' - ' . $post_content;
        wp_mail( $admin_email, 'Nouveau message créé', $message );
    } else {
        wp_send_json_error( 'Erreur lors de la création du message.' );
    }
}
add_action( 'wp_ajax_mon_plugin_gutenberg_create_message', 'mon_plugin_gutenberg_create_message' );
add_action( 'wp_ajax_nopriv_mon_plugin_gutenberg_create_message', 'mon_plugin_gutenberg_create_message' );
