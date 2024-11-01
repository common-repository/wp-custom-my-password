<?php
/*
  Plugin Name: WP Custom My Password
  Plugin URI: http://suoling.net/wp-custom-my-password
  Description: This plugin allows your user to custom his password on the registration screen.
  Version: 1.0
  Author: suifengtec
  Author URI: http://suoling.net
 */
if(!defined('ABSPATH')){ exit; }
add_action('plugins_loaded','wpcmpsw_i18n');
function wpcmpsw_i18n(){
    load_plugin_textdomain('cmypsw', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
}
add_action( 'register_form', 'wpcmpsw_register_form' );
function wpcmpsw_register_form() {

		$pass1=stripslashes( trim( $_POST['pass1'] ) );
		$pass2=stripslashes( trim( $_POST['pass2'] ) );
		$pass1=$pass1?$pass1:'';
		$pass2=$pass2?$pass2:'';
        ?>
        <p>
            <label for="pass1"><?php _e('Password','cmypsw'); ?><br />
                <input type="password" name="pass1" id="pass1" class="input" value="<?php echo esc_attr( wp_unslash( $pass1 ) ); ?>" size="25" /></label>
        </p>
        <p>
            <label for="pass2"><?php _e('Retype your password','cmypsw'); ?><br />
                <input type="password" name="pass2" id="pass2" class="input" value="<?php echo esc_attr( wp_unslash( $pass2 ) ); ?>" size="25" /></label>
        </p>
		<style type="text/css">
			#reg_passmail {display: none;}
		</style>
        <?php
    }


add_filter( 'registration_errors', 'wpcmpsw_registration_errors', 10, 3 );
    function wpcmpsw_registration_errors( $errors, $sanitized_user_login, $user_email ) {

        if ( empty( $_POST['pass1'] ) || ! empty( $_POST['pass1'] ) && trim( $_POST['pass1'] ) == '' ) {
            $errors->add( 'pass1_error', __( '<strong>ERROR</strong>:Please enter your password.', 'cmypsw' ) );
        }
        if ( empty( $_POST['pass2'] ) || ! empty( $_POST['pass2'] ) && trim( $_POST['pass1'] ) == '' ) {
            $errors->add( 'pass2_error', __( '<strong>ERROR</strong>:Please re-type your password.', 'cmypsw' ) );
        }
        if (( !empty( $_POST['pass1'] ) &&  trim( $_POST['pass1'] ) != '' )&&( !empty( $_POST['pass2'] ) &&  trim( $_POST['pass2'] ) != '' )&&(trim( $_POST['pass1'])!=trim( $_POST['pass2'] ) )) {
            $errors->add( 'pass2_error', __( '<strong>ERROR</strong>: The two passwords do NOT match.', 'cmypsw' ) );
        }
        return $errors;
    }


add_action( 'user_register', 'wpcmpsw_user_register' );
function wpcmpsw_user_register( $user_id ) {
    if ( ! empty( $_POST['pass1'] ) &&!empty( $_POST['pass2'] ) &&(trim( $_POST['pass1'])==trim( $_POST['pass2'] ))) {
    	$pass=stripslashes( trim( $_POST['pass1'] ) );
    	$userdata=array();
    	$userdata['ID'] = $user_id;
		$userdata['user_pass'] = $pass;
		$user_id = wp_update_user( $userdata );
    }
}
