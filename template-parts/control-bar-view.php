<div class="control-bar control-bar_products">
	<div class="control-bar__left">
		<ul class="control-bar-view">
			<li class="control-bar-item">
				<label <?php echo !isset($_COOKIE['_lv']) ? 'class="active"' : ''; ?> id="grid">
					<input type="radio" name="view" <?php echo !isset($_COOKIE['_lv']) ? 'checked' : ''; ?>>
					<span class="control-bar__label"><?php _e('Grid View', 'wb'); ?></span>
				</label>
			</li>
			<li class="control-bar-item">
				<label <?php echo isset($_COOKIE['_lv']) ? 'class="active"' : ''; ?> id="list">
					<input type="radio" name="view" <?php echo isset($_COOKIE['_lv']) ? 'checked' : ''; ?>>
					<span class="control-bar__label"><?php _e('List View', 'wb'); ?></span>
				</label>
			</li>
		</ul>
	</div>
	<div class="control-bar__right">
		<form method="get">
			<ul class="control-bar-list">
				<li class="control-bar-item">
					<div class="control-bar__label"><?php _e('Show', 'wb'); ?></div>
					<select name="per_page" class="control-bar__select select-styler"> 
						<option value="12" <?php selected('12', $per_page); ?>>
							<?php _e('12 per page', 'wb'); ?>
						</option>
						<option value="24" <?php selected('24', $per_page); ?>>
							<?php _e('24 per page', 'wb'); ?>
						</option>
						<option value="48" <?php selected('48', $per_page); ?>>
							<?php _e('48 per page', 'wb'); ?>
						</option>
						<option value="96" <?php selected('96', $per_page); ?>>
							<?php _e('96 per page', 'wb'); ?>
						</option>
					</select>
				</li>
				<li class="control-bar-item">
					<div class="control-bar__label"><?php _e('Sort by', 'wb'); ?></div>
					<select name="sort" class="control-bar__select select-styler">
						<option value="alphabetically" <?php selected('alphabetically', $sort); ?>>
							<?php _e('Alphabetically', 'wb'); ?>
						</option>
						<option value="popularity" <?php selected('popularity', $sort); ?>>
							<?php _e('Popularity', 'wb'); ?>
						</option>
						<option value="price-hl" <?php selected('price-hl', $sort); ?>>
							<?php _e('Price (High to Low)', 'wb'); ?>
						</option>
						<option value="price-lh" <?php selected('price-lh', $sort); ?>>
							<?php _e('Price (Low to High)', 'wb'); ?>
						</option>
					</select>
				</li>
			</ul>
		</form>
	</div>
</div> 