<?php
/**
 * @package Make
 */

$thumb_key    = 'layout-' . ttfmake_get_view() . '-featured-images';
$thumb_option = ttfmake_sanitize_choice( get_theme_mod( $thumb_key, ttfmake_get_default( $thumb_key ) ), $thumb_key );

// Header
ob_start();
//get_template_part( 'partials/entry', 'meta-top' );
get_template_part( 'partials/entry', 'sticky' );
if ( 'post-header' === $thumb_option ) :
	get_template_part( 'partials/entry', 'thumbnail' );
endif;
get_template_part( 'partials/entry', 'title' );
get_template_part( 'partials/entry', 'meta-before-content' );
$entry_header = trim( ob_get_clean() );

// Footer
ob_start();
get_template_part( 'partials/entry', 'meta-post-footer' );
get_template_part( 'partials/entry', 'taxonomy' );
get_template_part( 'partials/entry', 'sharing' );
$entry_footer = trim( ob_get_clean() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( $entry_header ) : ?>

	<header class="entry-header">
		<?php echo $entry_header; ?>
	</header>
	<?php endif; ?>

	<div class="entry-content">
		<div class="document_icon">
			<?php
				$attached_doc = get_post_meta( get_the_ID(), 'ecpt_uploadfile', true );
			?>
			<a href="<?php echo $attached_doc; ?>" title="Download <?php echo esc_attr( get_the_title() ); ?>"><img src="/wp-includes/images/media/document.png"></a>
		</div>
		<?php if ( 'thumbnail' === $thumb_option ) : ?>
			<?php // get_template_part( 'partials/entry', 'thumbnail' ); ?>
		<?php endif; ?>
		<?php get_template_part( 'partials/entry', 'content' ); ?>
	</div>

	<?php if ( $entry_footer ) : ?>
	<footer class="entry-footer">
		<?php // echo $entry_footer; ?>
	</footer>
	<?php endif; ?>
</article>
