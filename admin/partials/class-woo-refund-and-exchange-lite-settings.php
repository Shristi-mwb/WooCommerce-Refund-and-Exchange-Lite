<?php 
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    woo-refund-and-exchange-lite
 * @subpackage woo-refund-and-exchange-lite/admin/partials
 */

/**This class is for generating the html for the settings.
 *
 * 
 * This file use to display the function for the html
 *
 * @package    woo-refund-and-exchange-lite
 * @subpackage woo-refund-and-exchange-lite/admin/partials
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class mwb_rma_admin_settings {

	/**
	*This function is for generating the html for tab settings
	*@name mwb_rma_generate_tab_settings_html
	*@param $value
	*@since 1.0.0 
	*/

	public function mwb_rma_generate_tab_settings_html($settings_array,$setting_values){
		$mwb_settings_array = isset($settings_array) ? $settings_array : array();
		$mwb_setting_values = isset($setting_values) ? $setting_values : array();
		
		if(isset($mwb_settings_array) && !empty($mwb_settings_array) && is_array($mwb_settings_array)) {
			foreach ($mwb_settings_array as $key => $value) {
				?>
				<tr>
					<td><?php $this->mwb_rma_generate_label($value); ?></td>
					<?php if(isset($value['desc_tip']) && !empty($value['desc_tip']) && $value['type'] != 'checkbox'){ ?>
						<td><?php $this->mwb_rma_generate_tool_tip($value);?></td>
						<?php
					}else{
						?>
						<td></td>
						<?php
					}
					?>
					<?php
					if($value['type'] == 'checkbox'){
						?>
						<td><?php $this->mwb_rma_generate_checkbox_html($value,$mwb_setting_values);?></td>
						<?php
					}elseif($value['type'] == 'number'){
						?>
						<td><?php $this->mwb_rma_generate_number_html($value,$mwb_setting_values);?></td>
						<?php
					}elseif($value['type'] == 'multiselect'){
						?>
						<td><?php $this->mwb_rma_generate_searchSelect_html($value,$mwb_setting_values);?></td><?php
					}elseif($value['type'] == 'wp_editor'){
							?>
						<td><?php $this->mwb_rma_generate_wp_editor($value,$mwb_setting_values);?></td><?php
					}elseif($value['type'] == 'text'){
							?>
						<td><?php $this->mwb_rma_generate_text_html($value,$mwb_setting_values);?></td><?php
					}elseif($value['type'] == 'add_more_button'){
							?>
						<td><?php $this->mwb_rma_add_more_button_html($value,$mwb_setting_values);?></td><?php
					}elseif($value['type'] == 'display_text'){
							?>
						<td><?php $this->mwb_rma_display_text_html($value);?></td><?php
					}elseif($value['type'] == 'add_more_text'){
							?>
						<td><?php $this->mwb_rma_add_more_text_html($value,$mwb_setting_values);?></td><?php
					}
					do_action('mwb_rma_add_html_type',$value, $mwb_setting_values);
					?>
				</tr>
				<?php
			}
		}
	}

	/**
	*This function is used to save tab settings values
	*@name mwb_rma_save_tab_settings
	*@param $value
	*@since 1.0.0 
	*/

	public function mwb_rma_save_tab_settings($post,$setting_array){
		$mwb_settings_array = isset($setting_array) ? $setting_array : array();
		$mwb_setting_post = isset($post) ? $post : array();
		$mwb_setting_update_arr = [];
		foreach( $mwb_settings_array as $arr_key => $ref_val ){
			foreach ($ref_val['data'] as $key1 => $ref_val) {
				foreach($mwb_setting_post as $pd_key => $pd_val){
					if( $ref_val['type'] != 'display_text'){
						$text_type = array_key_exists('val_type', $ref_val);
						if( $ref_val['type'] == 'text'  ){
							$mwb_setting_update_arr[$pd_key] = isset($pd_val) ? stripcslashes(sanitize_text_field($pd_val)):'';
						}else{
							$mwb_setting_update_arr[$pd_key] = isset($pd_val)? $pd_val:'';
						}
					}
				}
			}
		}
		return $mwb_setting_update_arr;
	}


	/**
	*This function is for generating for the checkbox for the Settings
	*@name mwb_rma_generate_checkbox_html
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_rma_generate_checkbox_html($value,$general_settings) {
		 $enable_mwb_rma = isset($general_settings[$value['id']]) ? $general_settings[$value['id']] : 0;
		?>
		<label for="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>">
			<input type="checkbox" name="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" <?php checked($enable_mwb_rma,'on');?> id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" class="<?php echo (array_key_exists('class', $value))?$value['class']:'';?>"> <?php echo (array_key_exists('desc', $value))?$value['desc']:'';?>
			<p class="mwb-rma-cdesc-tip"><?php echo (array_key_exists('desc_tip', $value))?$value['desc_tip']:'';?></p>
		</label>
		<?php
	}

	/**
	*This function is for generating for the number for the Settings
	*@name mwb_rma_generate_number_html
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_rma_generate_number_html($value,$general_settings) {
		$mwb_signup_value = isset($general_settings[$value['id']]) ? intval($general_settings[$value['id']]) : 1;
		?>
		<label for="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>">
			<input type="number" <?php if (array_key_exists('custom_attributes', $value)) {
					
					foreach ($value['custom_attributes'] as $attribute_name => $attribute_val) {
						echo  $attribute_name ;
						echo  "=$attribute_val"; 
						
					}
				}?> value="<?php echo $mwb_signup_value;?>" name="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>"
			class="<?php echo (array_key_exists('class', $value))?$value['class']:'';?>"><?php echo (array_key_exists('desc', $value))?$value['desc']:'';?>
		</label>
		<?php
	}

	
	/**
	 * Generate Drop down menu fields
	 * @since 1.0.0
	 * @name mwb_wgm_generate_searchSelect_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */

	public function mwb_rma_generate_searchSelect_html( $value,$general_settings )
	{
		$selectedvalue =  isset($general_settings[$value['id']]) ? ($general_settings[$value['id']]) : array();
		if($selectedvalue == ''){
			$selectedvalue = '';
		}
		?>
		<select name="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>[]" id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" <?php echo (array_key_exists('multiple', $value))? $value['multiple']:''; ?>
			<?php
			if (array_key_exists('custom_attribute', $value)) {
				foreach ($value['custom_attribute'] as $attribute_name => $attribute_val) {
					echo  $attribute_name.'='.$attribute_val ;					
				}
			}
			if(is_array($value['options']) && !empty($value['options'])){
				foreach($value['options'] as $option_key => $option_value){
					$select = 0;
					if(is_array($selectedvalue) && in_array($option_key, $selectedvalue)){
						$select = 1;
					}
					?>
					><option value="<?php echo $option_key;?>" <?php echo selected(1, $select);?> ><?php echo $option_value; ?></option>
					<?php
				}
			}	
			?>
			</select>
		</label>
	<?php	
	}
	
	/**
	*This function is for generating for the wp_editor for the Settings
	*@name mwb_rma_generate_wp_editor
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_rma_generate_wp_editor($value,$notification_settings) {

		if(isset($value['id']) && !empty($value['id'])) {
			$defaut_text = isset($value['default'])?$value['default']:'';
			$mwb_rma_content = isset($notification_settings[$value['id']]) ?$notification_settings[$value['id']] : $defaut_text;
			$value_id = (array_key_exists('id', $value))?$value['id']:'';
			?>
			<label for="<?php echo $value_id; ?>">
				<?php 
				$content = stripcslashes($mwb_rma_content);
				$editor_id= $value_id;
				$settings = array(
					'media_buttons'    => false,
					'drag_drop_upload' => true,
					'dfw'              => true,
					'teeny'            => true,
					'editor_height'    => 200,
					'editor_class'       => 'mwb_rma_new_woo_ver_style_textarea',
					'textarea_name'    => $value_id,
					);
					wp_editor($content,$editor_id,$settings); ?>
				</label>	
				<?php
		}
	}


	/**
	*This function is for generating for the Label for the Settings
	*@name mwb_rma_generate_label
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_rma_generate_label($value) {
		?>
		<div class="mwb_rma_general_label">
			<label for="<?php echo (array_key_exists('id', $value))?$value['id']:'';?>"><?php echo (array_key_exists('title', $value))?$value['title']:''; ?></label>
			<?php if(array_key_exists('pro',$value)) {?>
			<span class="mwb_rma_general_pro">Pro</span>
			<?php }?>
		</div>
		<?php
	}

	/**
	*This function is for generating for the Tool tip for the Settings
	*@name mwb_rma_generate_tool_tip
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_rma_generate_tool_tip($value) {
		if(array_key_exists('desc_tip',$value)) {
			echo wc_help_tip($value['desc_tip']);
		}
	}

	/**
	*This function is for generating for the text html
	*@name mwb_rma_generate_text_html
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_rma_generate_text_html($value,$general_settings) {
		$mwb_signup_value = isset($general_settings[$value['id']]) ? ($general_settings[$value['id']]) : '';
		if(empty($mwb_signup_value)) {
			$mwb_signup_value = array_key_exists('default',$value)?$value['default']:'';
		}
		?>
		<label for="
			<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>">
			<input type="text" <?php 
			if (array_key_exists('custom_attributes', $value)) {
					foreach ($value['custom_attributes'] as $attribute_name => $attribute_val) {
						echo  $attribute_name ;
						echo  "=$attribute_val"; 
					}
				}?> 
				style ="<?php echo (array_key_exists('style', $value))?$value['style']:''; ?>"
				value="<?php echo $mwb_signup_value;?>" name="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?><?php echo (array_key_exists('val_type', $value))?'[]':''; ?>" id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>"
				placeholder ="<?php echo (array_key_exists('placeholder', $value))?$value['placeholder']:''; ?>" class="<?php echo (array_key_exists('class', $value))?$value['class']:'';?>"><?php echo (array_key_exists('desc', $value))?$value['desc']:'';?>
		</label>
			<?php
	}

	/**
	 * Generate save button html for setting page
	 * @since 1.0.0
	 * @name mwb_wgm_save_button_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_rma_save_button_html($name){
		?>
		<p class="submit">
			<input type="submit" value="<?php _e('Save changes', 'woo-refund-and-exchange-lite'); ?>" class="button-primary woocommerce-save-button" name="<?php echo $name;?>" id="<?php echo $name;?>" >
		</p><?php
	}

	/**
	*This function is for generating the notice of the save settings
	*@name mwb_rma_settings_saved
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_rma_settings_saved() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php _e('Settings saved.','woo-refund-and-exchange-lite'); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php _e('Dismiss this notices.','woo-refund-and-exchange-lite'); ?></span>
			</button>
		</div>
		<?php 
	}

	/**
	*This function is to create add more button
	*@name mwb_rma_add_more_button_html
	*@param $value
	*@since 1.0.0 
	*/
	
	public function mwb_rma_add_more_button_html($value,$general_settings){
		$mwb_signup_value = isset($general_settings[$value['id']]) ? ($general_settings[$value['id']]) : '';
		?>
		<p>
			<input type="button" value="<?php echo (array_key_exists('label', $value))?$value['label']:'';  ?>" class="button <?php echo (array_key_exists('class', $value))?$value['class']:'';  ?>" id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>">
		</p>
		<?php

	}

	/**
	*This function is to create text html for simple text
	*@name mwb_rma_display_text_html
	*@param $value
	*@since 1.0.0 
	*/

	public function mwb_rma_display_text_html($value){
	?>
		<div>
			<?php 
			if(array_key_exists('str', $value)){ ?>
				<?php echo (array_key_exists('str', $value))?$value['str']:''; ?><br>
			 <?php } ?>
			<?php if(array_key_exists('ss_both', $value)){
				echo sprintf(((array_key_exists('ss_both', $value))?$value['ss_both']:''),'<b>','</b>','<b>','</b>','<b>','</b>','<b>','</b>');
		 	}
		 	if(array_key_exists('shortcode', $value)){ ?>
				<br><b><?php echo (array_key_exists('shortcode', $value))?$value['shortcode']:''; ?></b>
			<?php } ?>
		</div>
	<?php
	}

	/**
	*This function is to create text html for add more button
	*@name mwb_rma_add_more_text_html
	*@param $value
	*@since 1.0.0 
	*/

	public function mwb_rma_add_more_text_html($value,$general_settings){
		$mwb_signup_value = isset($general_settings[$value['id']]) ? ($general_settings[$value['id']]) : array();
	
		if(is_array($mwb_signup_value) && !empty($mwb_signup_value)){

			foreach ($mwb_signup_value as $key1 => $value1) {
				if(!empty($value1)){
						?>
					<div <?php if($key1 == 0){ ?> id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>_wrapper" <?php }else{ ?> class="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>_wrapper" <?php }?> >

						<label for="
							<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>">
							<input type="text" <?php 
							if (array_key_exists('custom_attributes', $value)) {
									foreach ($value['custom_attributes'] as $attribute_name => $attribute_val) {
										echo  $attribute_name ;
										echo  "=$attribute_val"; 
									}
								}?> 
								style ="<?php echo (array_key_exists('style', $value))?$value['style']:''; ?>"
								value="<?php echo $value1;?>" name="<?php echo (array_key_exists('id', $value))?$value['id'].'[]':''; ?>" <?php if($key1 == 0){ ?> id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" <?php }?>
								class="<?php echo (array_key_exists('class', $value))?$value['class']:'';?>"><?php echo (array_key_exists('desc', $value))?$value['desc']:'';?>
						</label>
						<?php if($key1 != 0){ ?>
						<a href="#" class="mwb_rma_remove_button"><?php _e( 'Remove' ,'woo-refund-and-exchange-lite')?></a>
					<?php }?>
					</div>
				<?php
				}else{
					if($key1 == 0){
					?>
						<div id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>_wrapper">

							<label for="
								<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>">
								<input type="text" <?php 
								if (array_key_exists('custom_attributes', $value)) {
										foreach ($value['custom_attributes'] as $attribute_name => $attribute_val) {
											echo  $attribute_name ;
											echo  "=$attribute_val"; 
										}
									}?> 
									style ="<?php echo (array_key_exists('style', $value))?$value['style']:''; ?>"
									value="" name="<?php echo (array_key_exists('id', $value))?$value['id'].'[]':''; ?>" id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>"
									class="<?php echo (array_key_exists('class', $value))?$value['class']:'';?>"><?php echo (array_key_exists('desc', $value))?$value['desc']:'';?>
							</label>
						</div>
						<?php
					}
				}
			}
		}else{
			?>
			<div id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>_wrapper">

				<label for="
				<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>">
				<input type="text" <?php 
				if (array_key_exists('custom_attributes', $value)) {
					foreach ($value['custom_attributes'] as $attribute_name => $attribute_val) {
						echo  $attribute_name ;
						echo  "=$attribute_val"; 
					}
				}?> 
				style ="<?php echo (array_key_exists('style', $value))?$value['style']:''; ?>"
				value="" name="<?php echo (array_key_exists('id', $value))?$value['id'].'[]':''; ?>" id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>"
				class="<?php echo (array_key_exists('class', $value))?$value['class']:'';?>"><?php echo (array_key_exists('desc', $value))?$value['desc']:'';?>
			</label>
		</div>
		<?php
		}
	}

}