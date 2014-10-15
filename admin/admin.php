<?php

class AWS_CloudSearch_Simple_Related_Posts_Admin_Options {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}
	
	
	public function admin_menu() {
		add_options_page( __( 'Simple Related Posts Amazon CloudSearch', AWS_CLOUDSEARCH_RP_DOMAIN ), __( 'Simple Related Posts Amazon CloudSearch', AWS_CLOUDSEARCH_RP_DOMAIN ), 'manage_options', 'aws_cloudsearch_using_simple_related_posts', array( $this, 'option_page'));
	}

	public function option_page() {
	?>
<div class="wrap">
	
<h2><?php _e( 'Simple Related Posts Amazon CloudSearch', AWS_CLOUDSEARCH_RP_DOMAIN ); ?></h2>
	
<form action="options.php" method="post">
<?php settings_fields( 'aws_cloudsearch_sirp_options' ); ?>
<?php do_settings_sections( 'aws_cloudsearch' ); ?>
<p class="submit"><input name="Submit" type="submit" value="<?php _e( 'save', AWS_CLOUDSEARCH_RP_DOMAIN ) ?>" class="button-primary" /></p>
</form>
	
</div>
<?php
	}
	
	public function admin_init() {
		register_setting( 'aws_cloudsearch_sirp_options', 'aws_cloudsearch_sirp_options', array( $this, 'options_validate' ) );	
		add_settings_section( 'aws_cloudsearch_main', __( 'Configuration', AWS_CLOUDSEARCH_RP_DOMAIN ), array( $this, 'section_text' ), 'aws_cloudsearch' );	
		add_settings_field( 'sirp_target', __( 'End Point', AWS_CLOUDSEARCH_RP_DOMAIN ), array( $this, 'setting_endpoint' ), 'aws_cloudsearch', 'aws_cloudsearch_main' );
	}
	
	public function section_text() {
	}
	
	public function setting_endpoint() {
		$options = get_option( 'aws_cloudsearch_sirp_options' );

		echo '<input id="aws_cloudsearch_sirp_endpoint" name="aws_cloudsearch_sirp_options[endpoint]" size="36" type="text" value="' . esc_attr( $options['endpoint'] ) . '" />';

	}
	
	
	public function options_validate( $input ) {
		$newinput['endpoint'] = esc_url( $input['endpoint'] );
	
		return $newinput;
	}
}
new AWS_CloudSearch_Simple_Related_Posts_Admin_Options();