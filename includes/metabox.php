<?php

	/**
	 * Create the metabox
	 */
	function photoboard_notifications_create_metabox() {
		add_meta_box( 'photoboard_notifications_metabox', 'Album Notifications', 'photoboard_notifications_render_metabox', 'post', 'side', 'default');
	}
	add_action( 'add_meta_boxes', 'photoboard_notifications_create_metabox' );



	/**
	 * Render the metabox
	 */
	function photoboard_notifications_render_metabox() {

		// Variables
		global $post;
		$disabled = get_post_meta( $post->ID, 'photoboard_notifications_disable', true );

		?>

			<fieldset>

				<label>
					<input type="checkbox" name="photoboard_notifications_disable" id="photoboard_notifications_disable" value="on" <?php checked( $disabled, 'on' ); ?>>
					<?php _e( 'Disable notifications for this album', 'photoboard_notifications' ); ?>
				</label>

			</fieldset>

		<?php

		// Security field
		wp_nonce_field( 'photoboard_notifications_form_metabox_nonce', 'photoboard_notifications_form_metabox_process' );

	}



	/**
	 * Save the metabox
	 * @param  Number $post_id The post ID
	 * @param  Array  $post    The post data
	 */
	function photoboard_notifications_save_metabox( $post_id, $post ) {

		if ( !isset( $_POST['photoboard_notifications_form_metabox_process'] ) ) return;

		// Verify data came from edit screen
		if ( !wp_verify_nonce( $_POST['photoboard_notifications_form_metabox_process'], 'photoboard_notifications_form_metabox_nonce' ) ) {
			return $post->ID;
		}

		// Verify user has permission to edit post
		if ( !current_user_can( 'edit_post', $post->ID )) {
			return $post->ID;
		}

		// Update notification setting
		if ( isset( $_POST['photoboard_notifications_disable'] ) ) {
			update_post_meta( $post->ID, 'photoboard_notifications_disable', 'on' );
		} else {
			update_post_meta( $post->ID, 'photoboard_notifications_disable', 'off' );
		}

	}
	add_action('save_post', 'photoboard_notifications_save_metabox', 1, 2);