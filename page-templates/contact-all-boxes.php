<?php

/**
 * Template Name: Contact All Three Boxes
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
				<div class="col-lg-12">
					<div class="contact-info space-btn">
						<h2 class="contact-info__title"><?php the_title(); ?></h2>
						<?php the_content(); ?>
						
						<a href="/sellers-contact-us/" title="Sellers" class="box-contact seller">
							<strong>Sellers</strong>
							<span>Get your software or course in front of millions of monthly buyers – If you would like to have your software featured on the site, please get in touch.</span>
							<span class="ml__btn btn btn-outline-white">Email Us</span>
						</a>
						
						<a href="/buyers-contact-us/" title="Sellers" class="box-contact buyer">
							<strong>Buyers</strong>
							<span>If you would like any help with auditing your current marketing stack or would like guidance on what software could help, please drop us a line.</span>
							<span class="ml__btn btn btn-outline-white">Email Us</span>
						</a>
						
						<a href="/general-queries/" title="Sellers" class="box-contact general">
							<strong>General Queries</strong>
							<span>Would you like some expert digital marketing consulting and advice? Or have any general questions that we can help with? If so, let's connect. </span>
							
							<span class="ml__btn btn btn-outline-white">Email Us</span>
						</a>
						<div class="clear"></div>
						
						
					</div>
				</div>
			
		</div>
	</div>
</div>
	</div>

<?php get_footer(); ?>
