<?php

/**
 * Template Name: Contact
 */

if (!defined('ABSPATH')) {
	exit;
}

get_header();

the_post();

$contact_form_shortcode = get_post_meta($post->ID, '_contact_form_shortcode', true);

?>

<div class="contact-wrap heading-bg">
	<div class="container max-w_955">
		<div class="contact">
			<div class="row">
				<div class="<?php echo $contact_form_shortcode ? 'col-lg-5' : 'col-lg-12'; ?>">
					<div class="contact-info">
						<h2 class="contact-info__title"><?php the_title(); ?></h2>
						<?php the_content(); ?>
						<div class="contact-image">
							<div class="contact-image__inner">
								<img src="<?php echo WB_THEME_URL; ?>/images/contact-bg.png" srcset="<?php echo WB_THEME_URL; ?>/images/contact-bg@2x.png 2x" alt="<?php _e('BG', 'wb'); ?>" class="contact-image__bg">
								<img src="<?php echo WB_THEME_URL; ?>/images/contact-girl.png" srcset="<?php echo WB_THEME_URL; ?>/images/contact-girl@2x.png 2x" alt="<?php _e('Girl', 'wb'); ?>" class="contact-image__girl flot-y">
							</div>
						</div>
					</div>
				</div>
				<?php if ($contact_form_shortcode) : ?>
					<div class="col-lg-7">
						<?php echo do_shortcode(htmlspecialchars_decode($contact_form_shortcode)); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
