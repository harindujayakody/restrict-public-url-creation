<?php
/*
Plugin Name: Restrict Public URL Creation
Plugin URI: https://github.com/harindujayakody/restrict-public-url-creation
Description: Restricts URL creation to logged-in users only.
Version: 1.0
Author: Harindu Jayakody
Author URI: https://ekathuwa.org/
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Restrict URL creation to logged-in users only.
//
// Verified against YOURLS core (includes/functions-shorturls.php):
// yourls_add_new_link() finishes with
//   return yourls_apply_filter( 'add_new_link', $return, $url, $keyword, $title );
// where $return is the success array. The previous version of this plugin
// hooked that filter with a callback that took no arguments and returned
// false unconditionally, which replaced the success result with `false` for
// EVERYONE — including logged-in admins and API callers. Creation was
// therefore blocked for the very users the plugin was meant to allow.
//
// The fix scopes the block to anonymous requests only: logged-in users get
// the original $return untouched, and only anonymous requests receive a
// failure result (in YOURLS' own fail-array shape, so admin/AJAX and API
// callers get a usable error instead of a bare `false`).
//
// Note: the previous version also hooked a 'pre_check_shorturl' action to
// redirect anonymous users to the login page. That action does not exist in
// YOURLS core (the corresponding core action is 'pre_add_new_link'), so the
// hook never fired and the redirect was dead code. It is removed; the
// filter below is the single enforcement point.
yourls_add_filter( 'add_new_link', 'disable_public_link_creation', 10, 4 );
function disable_public_link_creation( $return, $url, $keyword, $title ) {
    // Logged-in user (admin UI or authenticated API call): allow creation.
    if ( yourls_is_valid_user() ) {
        return $return;
    }

    // Anonymous request: block creation with a standard failure result.
    return array(
        'status'  => 'fail',
        'code'    => 'error:unauthorized',
        'message' => 'URL creation is restricted to logged-in users.',
        'errorCode' => '401',
    );
}
?>
