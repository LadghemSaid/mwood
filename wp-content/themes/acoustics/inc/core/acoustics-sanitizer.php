<?php
/**
 * Acoustics Theme Sanitizer
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */
function acoustics_sanitize_checkbox( $input ) {
  return ( ( isset( $input ) && true == $input ) ? true: false );
}

function acoustics_sanitize_radioimage( $input ) {
  $keys = array( 'left-sidebar', 'no-sidebar', 'right-sidebar' );
    if ( in_array( $input, $keys ) ) {
        return $input;
	}else {
  		return 'no-sidebar';
	}
}
