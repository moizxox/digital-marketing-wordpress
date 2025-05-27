<h3><?php _e('Social Links', 'wb'); ?></h3>

<div class="wb-section">
	<div class="label-area">
		<?php _e('Facebook Link', 'wb'); ?>
	</div>
	<div class="form-area">
		<input type="text" name="data[facebook_link]" value="<?php echo $facebook_link; ?>">
	</div>
	<div class="clear"></div>
</div>

<div class="wb-section">
	<div class="label-area">
		<?php _e('LinkedIn Link', 'wb'); ?>
	</div>
	<div class="form-area">
		<input type="text" name="data[linkedin_link]" value="<?php echo $linkedin_link; ?>">
	</div>
	<div class="clear"></div>
</div>

<div class="wb-section">
	<div class="label-area">
		<?php _e('Instagram Link', 'wb'); ?>
	</div>
	<div class="form-area">
		<input type="text" name="data[instagram_link]" value="<?php echo $instagram_link; ?>">
	</div>
	<div class="clear"></div>
</div>

<div class="wb-section">
	<div class="label-area">
		<?php _e('Footer Text', 'wb'); ?>
	</div>
	<div class="form-area">
		<?php wp_editor($footer_text, 'footer_text', 'textarea_name=data[footer_text]&textarea_rows=5'); ?>
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
	var wb_saveable = true,
		wb_resettable = true;
</script>
