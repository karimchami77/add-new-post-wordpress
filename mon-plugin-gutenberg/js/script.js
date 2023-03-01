( function( $ ) {
    $( document ).ready( function() {
        
        // Handle form submission
        $( '#mon-plugin-gutenberg-form' ).submit( function( event ) {
            event.preventDefault();
            
            // Disable submit button and show spinner
            $( '#mon-plugin-gutenberg-submit' ).attr( 'disabled', true );
            $( '#mon-plugin-gutenberg-spinner' ).show();
            
            // Prepare form data
            var formData = new FormData( $( this )[0] );
            
            // Submit form data via AJAX
            $.ajax({
                type: 'POST',
                url: mon_plugin_gutenberg.ajax_url,
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    // Hide spinner and enable submit button
                    $( '#mon-plugin-gutenberg-spinner' ).hide();
                    $( '#mon-plugin-gutenberg-submit' ).attr( 'disabled', false );
                    
                    // Show success message
                    $( '#mon-plugin-gutenberg-form-message' ).html( '<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>' );
                },
                error: function( xhr, status, error ) {
                    // Hide spinner and enable submit button
                    $( '#mon-plugin-gutenberg-spinner' ).hide();
                    $( '#mon-plugin-gutenberg-submit' ).attr( 'disabled', false );
                    
                    // Show error message
                    $( '#mon-plugin-gutenberg-form-message' ).html( '<div class="notice notice-error is-dismissible"><p>' + xhr.responseJSON.data.message + '</p></div>' );
                }
            });
        });
        
    } );
} )( jQuery );
