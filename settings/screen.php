<?php
	global $wpdb, $title, $wpsi;
	
	require('process.php');
?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( $title . ' Settings' ); ?></h2>
	<div id="poststuff">
		<form id="wpsi_settings" action="" method="post">
			<div id="col-container">
				<div id="col-right">
				
				</div><!--/#col-right-->
				
				<div id="col-left">
					<div id="wpsi_setup" class="postbox">
						<h3 class="hndle"><span><?php printf ( __( '%s Setup', 'wp-section-index' ), esc_html( $title ) ); ?></span></h3>
						<div class="inside">
							<p class="form-required">
								<label class="alignleft"><?php _e('Heading Tag', 'wp-section-index'); ?></label>
								<select id="wpsi_tag" name="wpsi_tag" class="alignright">
									<?php
										$heading_options = '';
										for ( $i = 1; $i <= 6; $i++ ) {
											$heading_options .= '<option value="h' . $i . '"';
											if ( ${'v_' . $matty_prefix . 'tag'} == 'h' . $i ) { $heading_options .= ' selected="selected"'; }
											$heading_options .= '>Heading ' . $i . '</option>' . "\n";
										} // End FOR Loop
										echo $heading_options;
									?>
								</select>
								<br class="clear" />
							</p><!--/.form-required-->
							<p class="form-required">
								<label class="alignleft" for="wpsi_use_pages"><?php _e('Use on Pages', 'wp-section-index'); ?></label>
								<span class="alignright"><input type="radio" id="wpsi_use_pages_n" name="wpsi_use_pages" value="0" <?php if ( ${'v_' . $matty_prefix . 'use_pages'} == '0' ) { echo ' checked="checked"'; } ?> /> <?php _e('No', 'wp-section-index'); ?></span>
								<span class="alignright"><input type="radio" id="wpsi_use_pages_y" name="wpsi_use_pages" value="1" <?php if ( ${'v_' . $matty_prefix . 'use_pages'} == '1' ) { echo ' checked="checked"'; } ?> /> <?php _e('Yes', 'wp-section-index'); ?></span>
								<br class="clear" />
								<p><em><?php printf ( __( '%sWhen selected, the section index widget will work on all appropriate Pages%s', 'wp-section-index' ), '(', ')' ); ?></em></p>
							</p><!--/.form-required-->
							<p class="form-required">
								<label class="alignleft" for="wpsi_use_posts"><?php _e('Use on Posts', 'wp-section-index'); ?></label>
								<span class="alignright"><input type="radio" id="wpsi_use_posts_n" name="wpsi_use_posts" value="0" <?php if ( ${'v_' . $matty_prefix . 'use_posts'} == '0' ) { echo ' checked="checked"'; } ?> /> <?php _e('No', 'wp-section-index'); ?></span>
								<span class="alignright"><input type="radio" id="wpsi_use_posts_y" name="wpsi_use_posts" value="1" <?php if ( ${'v_' . $matty_prefix . 'use_posts'} == '1' ) { echo ' checked="checked"'; } ?> /> <?php _e('Yes', 'wp-section-index'); ?></span>
								<br class="clear" />
								<p><em><?php printf ( __( '%sWhen selected, the section index widget will work on all appropriate Posts%s', 'wp-section-index' ), '(', ')' ); ?></em></p>
							</p><!--/.form-required-->
							<p class="form-required">
								<label class="alignleft" for="wpsi_use_backtotop"><?php _e('Insert "Back to top" anchors', 'wp-section-index'); ?></label>
								<span class="alignright"><input type="radio" id="wpsi_use_backtotop_n" name="wpsi_use_backtotop" value="0" <?php if ( ${'v_' . $matty_prefix . 'use_backtotop'} == '0' ) { echo ' checked="checked"'; } ?> /> <?php _e('No', 'wp-section-index'); ?></span>
								<span class="alignright"><input type="radio" id="wpsi_use_backtotop_y" name="wpsi_use_backtotop" value="1" <?php if ( ${'v_' . $matty_prefix . 'use_backtotop'} == '1' ) { echo ' checked="checked"'; } ?> /> <?php _e('Yes', 'wp-section-index'); ?></span>
								<br class="clear" />
								<p><em><?php printf ( __( '%sWhen selected, "back to top" anchors will be added below each section in the content%s', 'wp-section-index' ), '(', ')' ); ?></em></p>
							</p><!--/.form-required-->
							<p class="form-required">
								<label class="alignleft" for="wpsi_backtotop_id"><?php _e('"Back to top" element ID', 'wp-section-index'); ?></label>
								<?php $v_backtotop_id = ''; if ( ${'v_' . $matty_prefix . 'backtotop_id'} != '' ) { $v_backtotop_id = ${'v_' . $matty_prefix . 'backtotop_id'}; } // End IF Statement ?>
								<span class="alignright"><input type="text" id="wpsi_backtotop_id" name="wpsi_backtotop_id" value="<?php echo $v_backtotop_id; ?>" /></span>
								<br class="clear" />
								<p><em><?php printf ( __( '%sThis is the ID of the element to which the "Back to top" links will point.%s', 'wp-section-index' ), '(', ')' ); ?></em></p>
								<p><em><?php printf ( __( '%sFor more on this, please see the documentation.%s', 'wp-section-index' ), '(', ')' ); ?></em></p>
							</p><!--/.form-required-->
							<p class="form-required">
								<label class="alignleft" for="wpsi_display_all_anchors"><?php _e('Display section indexes for all pages of paginated posts', 'wp-section-index'); ?></label>
								<span class="alignright"><input type="radio" id="wpsi_display_all_anchors_n" name="wpsi_display_all_anchors" value="0" <?php if ( ${'v_' . $matty_prefix . 'display_all_anchors'} == '0' ) { echo ' checked="checked"'; } ?> /> <?php _e('No', 'wp-section-index'); ?></span>
								<span class="alignright"><input type="radio" id="wpsi_display_all_anchors_y" name="wpsi_display_all_anchors" value="1" <?php if ( ${'v_' . $matty_prefix . 'display_all_anchors'} == '1' ) { echo ' checked="checked"'; } ?> /> <?php _e('Yes', 'wp-section-index'); ?></span>
								<br class="clear" />
								<p><em><?php printf ( __( '%sWhen set to "Yes", links to all section indexes in the Post or Page will be displayed in the widget, not just those for the page currently being viewed.%s', 'wp-section-index' ), '(', ')' ); ?></em></p>
							</p><!--/.form-required-->
							<?php /*
							<p class="form-required">
								<label class="alignleft" for="wpsi_clean_data"><?php _e('Clean settings when Deactivated', 'wp-section-index'); ?></label>
								<span class="alignright"><input type="radio" id="wpsi_clean_data_n" name="wpsi_clean_data" value="0" <?php if ( ${'v_' . $matty_prefix . 'clean_data'} == '0' ) { echo ' checked="checked"'; } ?> /> <?php _e('No', 'wp-section-index'); ?></span>
								<span class="alignright"><input type="radio" id="wpsi_clean_data_y" name="wpsi_clean_data" value="1" <?php if ( ${'v_' . $matty_prefix . 'clean_data'} == '1' ) { echo ' checked="checked"'; } ?> /> <?php _e('Yes', 'wp-section-index'); ?></span>
								<br class="clear" />
								<p><em><?php printf ( __( '%sWhen selected, all settings will be cleared when the plugin is deactivated%s', 'wp-section-index' ), '(', ')' ); ?></em></p>
							</p><!--/.form-required-->
							*/ ?>
						</div><!--/.inside-->
					</div><!--/#wpsi_setup .postbox-->

<?php
	// Only display the database options if all options are present in the database.
	if ( count ($wpsi->missing_options ) == 0 ) {
?>
					<div id="wpsi_cleanup" class="postbox">
						<h3 class="hndle"><span><?php printf ( __( '%s Database Clean-up <small>(For advanced users only)</small>', 'wp-section-index' ), esc_html( $title ) ); ?></span></h3>
						<div class="inside">
							<h4><?php _e('Database Options Clean-up', 'wp-section-index'); ?></h4>
							<p><?php printf ( __( 'The %s plugin makes use of several fields in the %s table in your database. To remove these fields (resetting the %s options), please make use of the form below.', 'wp-section-index' ), esc_html( $title ), '<code>' . $wpdb->prefix . 'options' . '</code>', esc_html( $title ) ); ?></p>
							<p class="error"><?php printf( __( 'Please note that this feature is for advanced users only. It is strongly recommended that you backup your database %s working with this feature.', 'wp-section-index' ), '<strong>' . __( 'before', 'wp-section-index' ) . '</strong>' ); ?></p><!--/.error-->
							<div class="inside">
								<form name="database_cleanup" action="" method="post">
									<fieldset>
										<strong><?php _e( 'Fields to be removed from the database', 'wp-section-index' ); ?></strong> <?php echo '<code>' . $wpdb->prefix . 'options' . '</code>'; ?> <strong><?php _e( 'table', 'wp-section-index' ); ?>:</strong>
										<p>
										<ul>
											<?php
												$html = '';
												
												foreach ( $wpsi->options as $key => $value ) {
													
													$html .= '<li><code>' . $wpsi->prefix . $key . '</code> <small class="alignright">(' . __( 'Currently set to', 'wp-section-index' ). ' "' . $value . '")</small></li>' . "\n";
													
												} // End FOREACH Loop
												
												echo $html;
											?>
										</ul>
										</p>
									</fieldset>
									<fieldset>
										<h4><?php _e( 'Final Confirmation', 'wp-section-index' ); ?></h4>
										<p><input type="checkbox" value="y" name="wpsi_databasecleanup_tnc"> <?php _e( 'I acknowledge and fully understand what I am about to do by clicking the "Clean Section Index Database &rarr;" button.' ); ?></p>

										<button type="submit" name="wpsi_databasecleanup" class="button alignright"><?php _e( 'Clean Section Index Database', 'wp-section-index' ); ?> &rarr;</button>
									</fieldset>
								</form>
							</div><!--/.inside-->
						</div><!--/.inside-->
					</div><!--/#wpsi_cleanup .postbox-->
<?php } // End IF Statement ?>


					<div class="button-set">
						<?php /*<button type="submit" name="wpsi_reset" class="button alignleft"><?php _e('Restore Default Settings', 'wp-section-index'); ?> &uarr;</button>*/ ?>
						<button type="submit" name="wpsi_update" class="button-primary alignright"><?php _e('Update Settings', 'wp-section-index'); ?> &rarr;</button>
						<br class="clear" />
					</div><!--/.button-set-->
				</div><!--/#col-left-->
				<div class="clear"></div><!--/.clear-->
			</div><!--/#col-container-->
		</form><!--/#wpsi_settings-->
	</div><!--/#poststuff-->
</div><!--/.wrap-->