<?php 
/*
Plugin Name: WP Simple Related Posts Amazon CloudSearch
Plugin URI: http://kakunin-pl.us/
Description: It provide related posts using Amazon CloudSearch
Author: horike takahiro
Version: 1.0
Author URI: http://kakunin-pl.us/


Copyright 2014 horike takahiro (email : horike37@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ( ! defined( 'AWS_CLOUDSEARCH_RP_DOMAIN' ) )
	define( 'AWS_CLOUDSEARCH_RP_DOMAIN', '' );
	
if ( ! defined( 'AWS_CLOUDSEARCH_RP_PLUGIN_URL' ) )
	define( 'AWS_CLOUDSEARCH_RP_PLUGIN_URL', plugins_url() . '/' . dirname( plugin_basename( __FILE__ ) ));

if ( ! defined( 'AWS_CLOUDSEARCH_RP_PLUGIN_DIR' ) )
	define( 'AWS_CLOUDSEARCH_RP_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ));

require_once( AWS_CLOUDSEARCH_RP_PLUGIN_DIR . '/admin/admin.php' );
add_action( 'sirp_target_option', 'aws_cloudsearch_rp_original_method_options' );
function aws_cloudsearch_rp_original_method_options($target) {
	echo '<label for="aws_cloudsearch_rp_original_method"><input id="aws_cloudsearch_rp_original_method" name="sirp_options[target]" type="radio" '.checked( $target, 'Aws_Cloudsearch_Simple_Related_Posts', false ).' value="Aws_Cloudsearch_Simple_Related_Posts" />' . __( 'View Related Posts based on Amazon Cloudsearch' ) . '</label>';
}

add_action( 'sirp_addon_requirement', 'aws_cloudsearch_rp_requirement_original_method' );
function aws_cloudsearch_rp_requirement_original_method() {
	require_once 'class-simple-related-posts-using-aws-cloudsearch.php';
}