<?php

load_plugin_textdomain( 'baweic', 'false', dirname( plugin_basename( ___FILE___ ) ) . '/lang/' );

function baweic_register_form_add_field($errors)
{ 
	global $baweic_fields, $allowedposttags;
?>
	<p>
		<label><span>Invitation Code</span><br />
			<?php if ( $errmsg = $errors->get_error_message('authentication_failed') ) { ?>
			<p class="error"><?php echo $errmsg ?></p>
			<?php } ?>
			<input name="invitation_code" tabindex="30" type="text" class="input" id="invitation_code" style="text-transform: uppercase" />
		</label>
		<?php if( !empty( $baweic_fields['link'] ) && $baweic_fields['link']=='on' ): ?>
		<span style="font-style: italic; position: relative; top: -15px;"><?php echo !empty( $baweic_fields['text_link'] ) ? wp_kses_post( $baweic_fields['text_link'], $allowedposttags ) : ''; ?></span>
		<?php endif; ?>
	</p>
 <?php
}
add_action('signup_extra_fields', 'baweic_register_form_add_field');

function baweic_registration_errors( $data )
{
	global $baweic_options;
	$invitation_code = isset( $_POST['invitation_code'] ) ? strtoupper( $_POST['invitation_code'] ) : '';
	if( !array_key_exists( $invitation_code, $baweic_options['codes'] ) ) {
		add_action( 'login_head', 'wp_shake_js', 12 );
		$data['errors']->add('authentication_failed', __( '<strong>ERROR</strong>: Wrong Invitation Code.', 'baweic' ));
	}elseif( isset( $baweic_options['codes'][$invitation_code] ) && $baweic_options['codes'][$invitation_code]['leftcount']==0 ){
		add_action( 'login_head', 'wp_shake_js', 12 );
		$data['errors']->add('authentication_failed', __( '<strong>ERROR</strong>: This Invitation Code is over.', 'baweic' ));
	}else{
		$baweic_options['codes'][$invitation_code]['leftcount']--;
		$baweic_options['codes'][$invitation_code]['users'][] = $data['user_name'];
		update_site_option( 'baweic_options', $baweic_options );
	}
	return $data;
}
add_filter('wpmu_validate_user_signup', 'baweic_registration_errors');

function baweic_login_footer()
{
	global $baweic_options;
	$invitation_code = isset( $_POST['invitation_code'] ) ? strtoupper( $_POST['invitation_code'] ) : '';
	if( !array_key_exists( $invitation_code, $baweic_options['codes'] ) ):
		?>
		<script type="text/javascript">
			try{document.getElementById('invitation_code').focus();}catch(e){}
		</script>
		<?php 
	endif;
}
add_action( 'login_footer', 'baweic_login_footer' );