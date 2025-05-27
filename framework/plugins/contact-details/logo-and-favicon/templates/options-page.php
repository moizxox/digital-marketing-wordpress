<h3><?php _e('Logo', 'wb'); ?></h3>
<div class="wb-section">
	<div class="label-area">
		<div class="wb-image">
			<img src="<?php echo wb_image(esc_url($logo), 400, 300); ?>" alt="" id="img-logo">
		</div>
	</div>
	<div class="form-area">
		<input type="text" name="data[logo]" value="<?php echo esc_url($logo); ?>" id="val-logo" placeholder="<?php _e('No media selected', 'wb'); ?>" wb-action="upload" wb-target="#val-logo" wb-image="#img-logo">
		<span><?php _e('Recommended image size: 165 x 50 px', 'wb'); ?></span>
		<p>
			<input type="submit" value="<?php _e('Upload', 'wb'); ?>" class="wb-btn" wb-action="upload" wb-target="#val-logo" wb-image="#img-logo">
		</p>
	</div>
	<div class="clear"></div>
</div>

<h3><?php _e('Logo 2x', 'wb'); ?></h3>
<div class="wb-section">
	<div class="label-area">
		<div class="wb-image">
			<img src="<?php echo wb_image(esc_url($retina_logo), 400, 300); ?>" alt="" id="img-retina_logo">
		</div>
	</div>
	<div class="form-area">
		<input type="text" name="data[retina_logo]" value="<?php echo esc_url($retina_logo); ?>" id="val-retina_logo" placeholder="<?php _e('No media selected', 'wb'); ?>" wb-action="upload" wb-target="#val-retina_logo" wb-image="#img-retina_logo">
		<span><?php _e('Recommended image size: 330 x 100 px', 'wb'); ?></span>
		<p>
			<input type="submit" value="<?php _e('Upload', 'wb'); ?>" class="wb-btn" wb-action="upload" wb-target="#val-retina_logo" wb-image="#img-retina_logo">
		</p>
	</div>
	<div class="clear"></div>
</div>

<h3><?php _e('Admin Logo', 'wb'); ?></h3>
<div class="wb-section">
	<div class="label-area">
		<div class="wb-image">
			<img src="<?php echo wb_image(esc_url($admin_logo), 400, 300); ?>" alt="" id="img-admin-logo">
		</div>
	</div>
	<div class="form-area">
		<input type="text" name="data[admin_logo]" value="<?php echo esc_url($admin_logo); ?>" id="val-admin-logo" placeholder="<?php _e('No media selected', 'wb'); ?>" wb-action="upload" wb-target="#val-admin-logo" wb-image="#img-admin-logo">
		<span><?php _e('Recommended image size: 80 x 80 px', 'wb'); ?></span>
		<p>
			<input type="submit" value="<?php _e('Upload', 'wb'); ?>" class="wb-btn" wb-action="upload" wb-target="#val-admin-logo" wb-image="#img-admin-logo">
		</p>
	</div>
	<div class="clear"></div>
</div>

<h3><?php _e('Favicon', 'wb'); ?></h3>
<div class="wb-section">
	<div class="label-area">
		<div class="wb-image">
			<img src="<?php echo wb_image(esc_url($favicon), 16, 16); ?>" alt="" id="img-favicon">
		</div>
	</div>
	<div class="form-area">
		<input type="text" name="data[favicon]" value="<?php echo esc_url($favicon); ?>" id="val-favicon" placeholder="<?php _e('No media selected', 'wb'); ?>" wb-action="upload" wb-target="#val-favicon" wb-image="#img-favicon">
		<span><?php _e('Recommended image size: 16 x 16 px', 'wb'); ?></span>
		<p>
			<input type="submit" value="<?php _e('Upload', 'wb'); ?>" class="wb-btn" wb-action="upload" wb-target="#val-favicon" wb-image="#img-favicon">
		</p>
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
	var wb_saveable = true,
		wb_resettable = true;
</script>
