<?php
// Exit if accessed directly
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit;
}

// Delete plugin options from the database
delete_option( 'mon_plugin_gutenberg_email_address' );
