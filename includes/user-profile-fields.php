<?php


	/**
	 * Add fields to the user profile
	 * @param  object $user The user
	 */
	function photoboard_notifications_add_fields( $user ) {

		$notifications = get_the_author_meta( 'photoboard_get_notifications', $user->ID );

		?>

		<h3><?php _e( 'Notifications', 'photoboard_user_groups' ); ?></h3>

		<table class="form-table">

			<tr>
				<th><?php _e( 'Album Notifications', 'photoboard_notifications' ); ?></th>

				<td>
					<label>
						<input type="checkbox" name="photoboard_notifications" id="photoboard_notifications" value="one" <?php checked( $notifications, 'on' ); ?> <?php checked( $notifications, '' ); ?>>
						<?php _e( 'Receive email notifications when new photos or videos are posted.', 'photoboard_notifications' ); ?>
					</label>
				</td>
			</tr>

		</table>


		<?php
	}
	add_action( 'show_user_profile', 'photoboard_notifications_add_fields' );
	add_action( 'edit_user_profile', 'photoboard_notifications_add_fields' );



	/**
	 * Save custom fields on update
	 * @param  integer $user_id The user ID
	 */
	function photoboard_notifications_save_fields( $user_id ) {

		// Security check
		if ( !current_user_can( 'edit_user', $user_id ) ) return false;

		// Update user group
		if ( isset( $_POST['photoboard_notifications'] ) ) {
			update_usermeta( $user_id, 'photoboard_get_notifications', 'on' );
		} else {
			update_usermeta( $user_id, 'photoboard_get_notifications', 'off' );
		}

	}
	add_action( 'personal_options_update', 'photoboard_notifications_save_fields' );
	add_action( 'edit_user_profile_update', 'photoboard_notifications_save_fields' );