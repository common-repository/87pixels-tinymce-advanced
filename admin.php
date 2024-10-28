<div class="wrap container">
	<?php echo '<h2 class="pt-3 pb-3">Full TinyMCE WordPress Editor Settings</h2>'; ?>
	
	<?php $options = get_option( 'full_tmce_options' ); ?>
	
	<form method="post" action="options.php" id="plugin-settings-form">
		
	
		<div class="row pt-3">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<?php do_settings_sections( 'full-tmce-settings' ); ?>
				<?php settings_fields( 'full-tmce-settings' ); ?>
				<h4 class="pt-3 pb-3">Buttons</h4>
			</div>

			<?php
			foreach( $options['toolbar'] as $toolbar_btn_key => $toolbar_btn_value ) {
				$name = $options['naming'][$toolbar_btn_key];
			?>
				<div class="col-xs-12 col-sm-4 col-md-3 pt-1 pb-1">
					<div class="input-group">
						<span class="input-group-addon checkbox">
							<label>
							<input type="checkbox" class="<?php echo substr( $toolbar_btn_key, 0, -3 ); ?>" id="full_tmce_options[toolbar][<?php echo $toolbar_btn_key; ?>]" 
								name="full_tmce_options[toolbar][<?php echo $toolbar_btn_key; ?>]" value="1" <?php checked(1, $toolbar_btn_value) ?>/>
							<span class="cr"><i class="cr-icon fa fa-check"></i></span>
							</label>
						</span>
						<?php if ( $toolbar_btn_key != 'formatselect' && $toolbar_btn_key != 'fontselect' && $toolbar_btn_key != 'fontsizeselect' ) { ?>
							<span class="input-group-addon"><i class="mce-ico mce-i-<?php echo $toolbar_btn_key; ?>"></i></span>
						<?php } ?>
						<input type="text" disabled class="form-control" name="full_tmce_options[naming][<?php echo $toolbar_btn_key; ?>]" 
							value="<?php echo $name; ?>">
					</div>
				</div>
			<?php
			}
			?>
		</div>
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<?php submit_button('Save all changes', 'primary','submit', TRUE); ?>
			</div>
		</div>
	</form>
</div>