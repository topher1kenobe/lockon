<?php
/**
 * @package Make
 */

if ( is_singular( 'documents' ) || is_archive( 'documents' ) ) {
	echo '<p class="document_types">Type: ';
	the_terms( get_the_ID(), 'documenttype' );
	echo '</p>';
} else {
	global $ttfmake_current_location;
	$ttfmake_current_location = 'before-content';
	get_template_part( 'partials/entry', 'meta' );
	unset( $ttfmake_current_location );
}
