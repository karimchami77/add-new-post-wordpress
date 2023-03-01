<?php

add_action( 'wp_ajax_mon_plugin_gutenberg_create_message', 'mon_plugin_gutenberg_create_message_ajax_handler' );
add_action( 'wp_ajax_nopriv_mon_plugin_gutenberg_create_message', 'mon_plugin_gutenberg_create_message_ajax_handler' );

function mon_plugin_gutenberg_create_message_ajax_handler() {
    // Check the nonce
    check_ajax_referer( 'mon_plugin_gutenberg_create_message_nonce', 'security' );

    // Check if the user is logged in
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( __( 'You must be logged in to create a message.', 'mon-plugin-gutenberg' ) );
    }

    // Check the request method
    if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
        wp_send_json_error( __( 'Invalid request method.', 'mon-plugin-gutenberg' ) );
    }

    // Get the form data
    $title = sanitize_text_field( $_POST['title'] );
    $text = sanitize_text_field( $_POST['text'] );

    // Validate the title
    $existing_post = get_page_by_title( $title, OBJECT, 'post' );
    if ( $existing_post ) {
        wp_send_json_error( __( 'A post with the same title already exists.', 'mon-plugin-gutenberg' ) );
    }

    // Create a new post
    $post = array(
        'post_title' => $title,
        'post_content' => $text,
        'post_status' => 'draft',
        'post_author' => get_current_user_id(),
        'post_type' => 'post'
    );
    $post_id = wp_insert_post( $post );

    if ( $post_id ) {
        // Send an email to the admin
        $to = get_option( 'admin_email' );
        $subject = sprintf( __( 'New message: %s', 'mon-plugin-gutenberg' ), $title );
        $body = sprintf( __( 'A new message has been created with the following details: <br><br> Title: %s <br> Text: %s <br> Post ID: %d', 'mon-plugin-gutenberg' ), $title, $text, $post_id );
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );
        wp_mail( $to, $subject, $body, $headers );

        wp_send_json_success( __( 'Message created successfully.', 'mon-plugin-gutenberg' ) );
    } else {
        wp_send_json_error( __( 'Error creating message.', 'mon-plugin-gutenberg' ) );
    }
}
