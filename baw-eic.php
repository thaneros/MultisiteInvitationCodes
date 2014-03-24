<?php
/*
Plugin Name: Multisite Invitation Codes
Plugin URI: 
Description: Visitors have to enter an invitation code to register on your blog. The easy way!
Version: 1.0.4
Author: Benjamin Durin
Original Author: Juliobox
License: GPLv2
*/

$baweic_options = get_site_option( 'baweic_options' );
$baweic_fields = get_site_option( 'baweic_fields' );
DEFINE( '___FILE___', __FILE__ );

if( !is_admin() ) :
	include( 'inc/front-end.php' );
else:
	include( 'inc/back-end.php' );
endif;