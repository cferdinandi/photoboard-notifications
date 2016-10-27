<?php

/**
 * Core functionality
 */


	/**
	 * Create form for users to update notification preferences
	 */
	function photoboard_notifications_form() {

		if ( is_user_logged_in() ) {

			// Variables
			$options = photoboard_notifications_get_theme_options();
			$current_user = wp_get_current_user();
			$notifications = get_user_meta( $current_user->ID, 'photoboard_get_notifications', 'true' );
			$checked = ( in_array( $notifications, array( 'on', '' ) ) ? 'checked' : '');
			$alert = photoboard_notifications_get_session( 'photoboard_alert_notifications', true );

			$form =
				( $alert === 'success' ? '<div class="' . $options['alert_class'] . '">' . $options['alert_text'] . '</div>' : '' ) .
				'<form class="form-photoboard" id="photoboard-form-get-notifications" name="photoboard-form-get-notifications" action="" method="post">' .

					'<label class="photoboard-notifications-checkbox">' .
						'<input type="checkbox" name="photoboard-get-notifications" id="photoboard-get-notifications" value="on" ' . $checked . '>' .
						' ' . $options['checkbox_text'] .
					'</label>' .

					'<button class="photoboard-notifications-button ' . $options['btn_class'] . '">'  . $options['btn_text'] . '</button>' .

					wp_nonce_field( 'photoboard_notifications_nonce', 'photoboard_notifications_process', true, false ) .

				'</form>';

		} else {
			$form = '<p>' . __( 'You must be logged in to update your preferences.', 'photoboard' ) . '</p>';
		}

		return $form;

	}
	add_shortcode( 'photoboard_notifications_form', 'photoboard_notifications_form' );




	/**
	 * Process user notification preference updates
	 */
	function photoboard_process_set_notifications_form() {
		if ( isset( $_POST['photoboard_notifications_process'] ) ) {
			if ( wp_verify_nonce( $_POST['photoboard_notifications_process'], 'photoboard_notifications_nonce' ) ) {

				// Variables
				$current_user = wp_get_current_user();
				$referer = esc_url_raw( photoboard_notifications_get_url() );

				// Update settings
				if ( isset( $_POST['photoboard-get-notifications'] ) ) {
					update_user_meta( $current_user->ID, 'photoboard_get_notifications', 'on' );
				} else {
					update_user_meta( $current_user->ID, 'photoboard_get_notifications', 'off' );
				}

				// Set alert message
				photoboard_notifications_set_session( 'photoboard_alert_notifications', 'success' );

				// Reload page
				wp_safe_redirect( $referer, 302 );
				exit;

			} else {
				die( 'Security check' );
			}
		}
	}
	add_action('init', 'photoboard_process_set_notifications_form');


	/**
	 * Notify members of new post by email
	 * @param array $post The post being updated
	 */
	function photoboard_notifications_new_post_email( $post ) {

		// If post is not published, bail
		if ( get_post_type( $post->ID ) !== 'post' || get_post_status( $post->ID ) !== 'publish' ) return;

		// Variables
		$users = get_users();
		$headers = Array();

		// Loop through each user
		foreach ($users as $user) {

			// Get user notification settings
			$notifications = get_user_meta($user->ID, 'photoboard_get_notifications', 'true');

			// Only send notification to users who want to get them
			if ( $notifications !== 'on' ) continue;

			// Add user to email list
			$headers[] = 'Bcc: ' . $user->user_email;

		}

		// Email variables
		$to = 'Photoboard <' . get_option( 'admin_email' ) . '>';
		$subject = 'New photos on Photoboard: ' . get_the_title( $post->ID );
		$message =
			'There are new photos or videos on Photoboard. Click here to view them: ' . get_permalink( $post->ID ) . "\r\n\r\n" .
			'To stop receiving these emails, visit ' . site_url() . '/notifications' . "\r\n";
		$headers[] = 'From: Photoboard <' . get_option( 'admin_email' ) . '>';

		// Send email
		wp_mail( $to, $subject, $message, $headers );

	}
	// add_action('save_post', 'photoboard_new_post_email');
	add_action('draft_to_publish', 'photoboard_notifications_new_post_email');
	add_action('new_to_publish', 'photoboard_notifications_new_post_email');
	add_action('pending_to_publish', 'photoboard_notifications_new_post_email');
	add_action('future_to_publish', 'photoboard_notifications_new_post_email');