<div class="product-filter side-panel products-row-small-filter__sidebar">
	<div class="side-panel__heading">
		<div class="side-panel__heading__inner">
			<h3><?php _e('Filter Results', 'wb'); ?></h3>
			<span class="badge-num found-posts"><?php echo $GLOBALS['total_results']; ?></span>
			<button class="side-panel-close product-filter__heading__close">
				<i class="icon icon-close"></i>
			</button>
		</div>
	</div>
	<form action="<?php echo get_term_link($term); ?>" method="get" autocomplete="off">
		<input type="hidden" name="type" value="<?php echo $type; ?>">
		<input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
		<input type="hidden" name="sort" value="<?php echo $sort; ?>">
		<div class="side-panel__body">
			<?php
			// Helper: Check if taxonomy exists and has terms
			function cpt_has_terms($taxonomy) {
				$terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
				return !is_wp_error($terms) && !empty($terms);
			}
			?>

			<?php if (cpt_has_terms($type . '-pricing-option')) : ?>
				<div class="product-filter__box">
					<h3 class="product-filter__box__title"><?php _e('Pricing Options', 'wb'); ?></h3>
					<ul class="product-filter__list">
						<?php foreach (get_terms(array('taxonomy' => $type . '-pricing-option', 'hide_empty' => false)) as $_pricing_option) : ?>
							<li>
								<label class="product-filter-control">
									<input type="checkbox" name="pricing_option[]" value="<?php echo $_pricing_option->term_id; ?>" <?php echo in_array($_pricing_option->term_id, $pricing_option) ? 'checked' : ''; ?>>
									<span class="product-filter-control__label"><?php echo $_pricing_option->name; ?></span>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<?php if ($prices) : ?>
				<div class="product-filter__box">
					<div class="product-filter__box__heading">
						<h3 class="product-filter__box__title"><?php _e('Price', 'wb'); ?></h3>
						<?php if ($currencies) : ?>
							<div class="product-filter__box__all">
								<div class="btn-group btn-group-sm currency-group">
									<?php foreach ($currencies as $_currency) : ?>
										<button type="button" class="btn btn-secondary <?php echo ($_currency == $currency) ? 'active' : ''; ?>" data-currency="<?php echo $_currency; ?>"><?php echo $_currency; ?></button>
									<?php endforeach; ?>
								</div>
								<input type="hidden" name="currency" value="<?php echo $currency; ?>">
							</div>
						<?php endif; ?>
					</div>
					<ul class="product-filter__list">
						<li>
							<label class="product-filter-control">
								<input type="checkbox" name="price[]" value="0" <?php echo in_array('0', $price) ? 'checked' : ''; ?>>
								<span class="product-filter-control__label"><?php _e('Free', 'wb'); ?></span>
							</label>
						</li>
						<?php foreach ($prices['list'] as $_price) : ?>
							<li>
								<label class="product-filter-control">
									<input type="checkbox" name="price[]" value="<?php echo $_price['ranges']['min']; ?>-<?php echo $_price['ranges']['max']; ?>" <?php echo in_array($_price['ranges']['min'] . '-' . $_price['ranges']['max'], $price) ? 'checked' : ''; ?>>
									<span class="product-filter-control__label"><span class="curr-symbol"><?php echo $currency; ?></span><?php echo $_price['ranges']['min']; ?> - <span class="curr-symbol"><?php echo $currency; ?></span><?php echo $_price['ranges']['max']; ?></span>
								</label>
							</li>
						<?php endforeach; ?>
						<?php $last_price_range = array_key_last($prices['list']); ?>
						<?php if ($prices['max'] > $prices['list'][$last_price_range]['ranges']['max']) : ?>
							<li>
								<label class="product-filter-control">
									<input type="checkbox" name="price[]" value="<?php echo $prices['list'][$last_price_range]['ranges']['max']; ?>" <?php echo in_array($prices['list'][$last_price_range]['ranges']['max'], $price) ? 'checked' : ''; ?>>
									<span class="product-filter-control__label"><?php _e('Over', 'wb'); ?> <span class="curr-symbol"><?php echo $currency; ?></span><?php echo $prices['list'][$last_price_range]['ranges']['max']; ?></span>
								</label>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>
			<?php // Only show location filters if taxonomy exists and is needed
			if (in_array($type, ['tool', 'service']) && cpt_has_terms($type . '-location')) : ?>
				<div class="product-filter__box">
					<div class="product-filter__box__heading">
						<h3 class="product-filter__box__title"><?php _e('Country', 'wb'); ?></h3>
					</div>
					<ul class="product-filter__list">
						<?php foreach (get_terms(array('taxonomy' => $type . '-location', 'parent' => 0, 'hide_empty' => false)) as $_country) : ?>
							<li>
								<label class="product-filter-control">
									<input type="checkbox" name="country[]" value="<?php echo $_country->term_id; ?>" <?php echo in_array($_country->term_id, $country) ? 'checked' : ''; ?>>
									<span class="product-filter-control__label"><?php echo $_country->name; ?></span>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="product-filter__box">
					<div class="product-filter__box__heading">
						<h3 class="product-filter__box__title"><?php _e('City', 'wb'); ?></h3>
					</div>
					<ul class="product-filter__list">
						<?php foreach (get_terms(array('taxonomy' => $type . '-location', 'exclude_parents' => 1, 'hide_empty' => false)) as $_city) : ?>
							<li>
								<label class="product-filter-control">
									<input type="checkbox" name="city[]" value="<?php echo $_city->term_id; ?>" <?php echo in_array($_city->term_id, $city) ? 'checked' : ''; ?>>
									<span class="product-filter-control__label"><?php echo $_city->name; ?></span>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<?php if (cpt_has_terms($type . '-tag')) : ?>
				<div class="product-filter__box">
					<div class="product-filter__box__heading">
						<h3 class="product-filter__box__title">
							<?php
							switch ($type) {
								case 'course':
									_e("What you'll learn", 'wb');
									break;
								case 'service':
									_e('Services', 'wb');
									break;
								default:
									_e('Features', 'wb');
									break;
							}
							?>
						</h3>
					</div>
					<ul class="product-filter__list">
						<?php foreach (get_terms(array('taxonomy' => $type . '-tag', 'hide_empty' => false)) as $_tag) : ?>
							<li>
								<label class="product-filter-control">
									<input type="checkbox" name="ftag[]" value="<?php echo $_tag->term_id; ?>" <?php echo in_array($_tag->term_id, $tag) ? 'checked' : ''; ?>>
									<span class="product-filter-control__label"><?php echo $_tag->name; ?></span>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
		<div class="product-filter__bottom">
			<button class="btn btn-green btn-block product-filter__apply" type="submit">
				<?php _e('Apply Filters', 'wb'); ?>
			</button>
			<button class="btn btn-link btn-block product-filter__clear" type="reset" style="display: <?php echo (!empty($pricing_option) || !empty($price) || !empty($country) || !empty($city) || !empty($tag)) ? 'block' : 'none'; ?>;">
				<i class="icon icon-clear"></i>
				<?php _e('Clear All Filters', 'wb'); ?>
			</button>
		</div>
	</form>
</div> 