<?php

	/**
	 * Fields
	 */

	function photoboard_notifications_settings_alert_class() {
		$options = photoboard_notifications_get_theme_options();
		?>
		<input type="text" name="photoboard_notifications_theme_options[alert_class]" class="large-text" id="alert_class" value="<?php echo stripslashes( esc_attr( $options['alert_class'] ) ); ?>" /><br />
		<label class="description" for="alert_class"><?php _e( 'Class for successful settings update alert', 'photoboard_notifications' ); ?></label>
		<?php
	}

	function photoboard_notifications_settings_alert_text() {
		$options = photoboard_notifications_get_theme_options();
		?>
		<input type="text" name="photoboard_notifications_theme_options[alert_text]" class="large-text" id="alert_text" value="<?php echo stripslashes( esc_attr( $options['alert_text'] ) ); ?>" /><br />
		<label class="description" for="alert_text"><?php _e( 'Text for alert message', 'photoboard_notifications' ); ?></label>
		<?php
	}

	function photoboard_notifications_settings_checkbox_text() {
		$options = photoboard_notifications_get_theme_options();
		?>
		<input type="text" name="photoboard_notifications_theme_options[checkbox_text]" class="large-text" id="checkbox_text" value="<?php echo stripslashes( esc_attr( $options['checkbox_text'] ) ); ?>" /><br />
		<label class="description" for="checkbox_text"><?php _e( 'Text for checkbox', 'photoboard_notifications' ); ?></label>
		<?php
	}

	function photoboard_notifications_settings_btn_class() {
		$options = photoboard_notifications_get_theme_options();
		?>
		<input type="text" name="photoboard_notifications_theme_options[btn_class]" class="large-text" id="btn_class" value="<?php echo stripslashes( esc_attr( $options['btn_class'] ) ); ?>" /><br />
		<label class="description" for="btn_class"><?php _e( 'Class for submit button' ); ?></label>
		<?php
	}

	function photoboard_notifications_settings_btn_text() {
		$options = photoboard_notifications_get_theme_options();
		?>
		<input type="text" name="photoboard_notifications_theme_options[btn_text]" class="large-text" id="btn_text" value="<?php echo stripslashes( esc_attr( $options['btn_text'] ) ); ?>" /><br />
		<label class="description" for="btn_text"><?php _e( 'Submit button text', 'photoboard_notifications' ); ?></label>
		<?php
	}


	/**
	 * Menu
	 */

	// Register the theme options page and its fields
	function photoboard_notifications_theme_options_init() {
		register_setting(
			'photoboard_notifications_options', // Options group, see settings_fields() call in photoboard_notifications_theme_options_render_page()
			'photoboard_notifications_theme_options', // Database option, see photoboard_notifications_get_theme_options()
			'photoboard_notifications_theme_options_validate' // The sanitization callback, see photoboard_notifications_theme_options_validate()
		);

		// Register our settings field group
		add_settings_section(
			'general', // Unique identifier for the settings section
			'', // Section title (we don't want one)
			'__return_false', // Section callback (we don't want anything)
			'photoboard_notifications_theme_options' // Menu slug, used to uniquely identify the page; see photoboard_notifications_theme_options_add_page()
		);

		// Register our individual settings fields
		// add_settings_field( $id, $title, $callback, $page, $section );
		// $id - Unique identifier for the field.
		// $title - Setting field title.
		// $callback - Function that creates the field (from the Theme Option Fields section).
		// $page - The menu page on which to display this field.
		// $section - The section of the settings page in which to show the field.

		add_settings_field( 'photoboard_notifications_alert_class', __( 'Alert Class', 'photoboard_notifications' ), 'photoboard_notifications_settings_alert_class', 'photoboard_notifications_theme_options', 'general' );
		add_settings_field( 'photoboard_notifications_alert_text', __( 'Alert Text', 'photoboard_notifications' ), 'photoboard_notifications_settings_alert_text', 'photoboard_notifications_theme_options', 'general' );

		add_settings_field( 'photoboard_notifications_checkbox_text', __( 'Checkbox Text', 'photoboard_notifications' ), 'photoboard_notifications_settings_checkbox_text', 'photoboard_notifications_theme_options', 'general' );

		add_settings_field( 'photoboard_notifications_btn_class', __( 'Button Class', 'photoboard_notifications' ), 'photoboard_notifications_settings_btn_class', 'photoboard_notifications_theme_options', 'general' );
		add_settings_field( 'photoboard_notifications_btn_text', __( 'Button Text', 'photoboard_notifications' ), 'photoboard_notifications_settings_btn_text', 'photoboard_notifications_theme_options', 'general' );
	}
	add_action( 'admin_init', 'photoboard_notifications_theme_options_init' );



	// Create theme options menu
	// The content that's rendered on the menu page.
	function photoboard_notifications_theme_options_render_page() {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e( 'Photoboard Notifications Settings', 'photoboard_notifications' ); ?></h2>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'photoboard_notifications_options' );
					do_settings_sections( 'photoboard_notifications_theme_options' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}



	// Add the theme options page to the admin menu
	function photoboard_notifications_theme_options_add_page() {
		$theme_page = add_submenu_page(
			'options-general.php', // parent slug
			'Notifications', // Label in menu
			'Notifications', // Label in menu
			'edit_theme_options', // Capability required
			'photoboard_notifications_theme_options', // Menu slug, used to uniquely identify the page
			'photoboard_notifications_theme_options_render_page' // Function that renders the options page
		);
	}
	add_action( 'admin_menu', 'photoboard_notifications_theme_options_add_page' );



	// Restrict access to the theme options page to admins
	function photoboard_notifications_option_page_capability( $capability ) {
		return 'edit_theme_options';
	}
	add_filter( 'option_page_capability_photoboard_notifications_options', 'photoboard_notifications_option_page_capability' );



	/**
	 * Process Options
	 */

	// Get the current options from the database.
	// If none are specified, use these defaults.
	function photoboard_notifications_get_theme_options() {
		$saved = (array) get_option( 'photoboard_notifications_theme_options' );
		$defaults = array(
			'alert_class' => '',
			'alert_text' => 'Your settings have been updated.',
			'checkbox_text' => 'Receive email notifications when new photos or videos are posted.',
			'btn_class' => '',
			'btn_text' => 'Update Settings',
		);

		$defaults = apply_filters( 'photoboard_notifications_default_theme_options', $defaults );

		$options = wp_parse_args( $saved, $defaults );
		$options = array_intersect_key( $options, $defaults );

		return $options;
	}



	// Sanitize and validate updated theme options
	function photoboard_notifications_theme_options_validate( $input ) {
		$output = array();

		if ( isset( $input['alert_class'] ) && ! empty( $input['alert_class'] ) )
			$output['alert_class'] = wp_filter_nohtml_kses( $input['alert_class'] );

		if ( isset( $input['alert_text'] ) && ! empty( $input['alert_text'] ) )
			$output['alert_text'] = wp_filter_nohtml_kses( $input['alert_text'] );

		if ( isset( $input['checkbox_class'] ) && ! empty( $input['checkbox_class'] ) )
			$output['checkbox_class'] = wp_filter_nohtml_kses( $input['checkbox_class'] );

		if ( isset( $input['btn_class'] ) && ! empty( $input['btn_class'] ) )
			$output['btn_class'] = wp_filter_nohtml_kses( $input['btn_class'] );

		if ( isset( $input['btn_text'] ) && ! empty( $input['btn_text'] ) )
			$output['btn_text'] = wp_filter_post_kses( $input['btn_text'] );

		return apply_filters( 'photoboard_notifications_theme_options_validate', $output, $input );
	}