<?php

class Taxonomy_Fields extends WB_Plugin {

	/**
	 * @Action category_add_form_fields
	 * @Action tool-category_add_form_fields
	 * @Action tool-tag_add_form_fields
	 * @Action course-category_add_form_fields
	 * @Action course-tag_add_form_fields
	 * @Action service-category_add_form_fields
	 * @Action service-tag_add_form_fields
	 */
	public function __add_form_fields() {
		?>
		<div class="form-field">
			<label for="thumbnail">
				<?php _e('Thumbnail', 'wb'); ?>
				(<a href="#" wb-action="upload" wb-target="#thumbnail"><?php _e('Upload', 'wb'); ?></a>)
			</label>
			<input type="text" name="thumbnail" id="thumbnail">
		</div>
		<?php
	}

	/**
	 * @Action category_edit_form_fields
	 * @Action tool-category_edit_form_fields
	 * @Action tool-tag_edit_form_fields
	 * @Action course-category_edit_form_fields
	 * @Action course-tag_edit_form_fields
	 * @Action service-category_edit_form_fields
	 * @Action service-tag_edit_form_fields
	 */
	public function __edit_form_fields($term) {
		$thumbnail = get_term_meta($term->term_id, '_thumbnail', true);

		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="thumbnail">
					<?php _e('Thumbnail', 'wb'); ?>
					(<a href="#" wb-action="upload" wb-target="#thumbnail"><?php _e('Upload', 'wb'); ?></a>)
				</label>
			</th>
			<td>
				<input type="text" name="thumbnail" value="<?php echo $thumbnail; ?>" id="thumbnail">
			</td>
		</tr>
		<?php
	}

	/**
	 * @Action create_category
	 * @Action edited_category
	 * @Action create_tool-category
	 * @Action edited_tool-category
	 * @Action create_tool-tag
	 * @Action edited_tool-tag
	 * @Action create_course-category
	 * @Action edited_course-category
	 * @Action create_course-tag
	 * @Action edited_course-tag
	 * @Action create_service-category
	 * @Action edited_service-category
	 * @Action create_service-tag
	 * @Action edited_service-tag
	 */
	public function __save_form_fields($term_id) {
		if (isset($_POST['thumbnail'])) {
			update_term_meta($term_id, '_thumbnail', esc_url($_POST['thumbnail']));
		}

		if (get_term_meta($term_id, '_views', true) === '') {
			update_term_meta($term_id, '_views', 0);
		}
	}


}
