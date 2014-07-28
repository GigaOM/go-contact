<form action="<?php echo esc_url_raw( $target ); ?>" id="<?php echo esc_attr( $this->slug . '-' . $this->instance ); ?>" class="<?php echo esc_attr( $this->slug ); ?>">
	<?php $this->nonce_field( $this->instance ); ?>
	<input type="hidden" name="<?php echo $this->get_field_name( 'hash' ); ?>" value="<?php echo esc_attr( $email_hash ); ?>" />
	<div class="recipient_info">
		<?php
			echo get_avatar( $email, 75 );

			if ( $gravatar_profile = go_gravatar()->get_profile( $email ) )
			{
				?>
				<h4><?php echo esc_attr( $gravatar_profile->displayName ); ?></h4>
				<?php
			} // END if
		?>
	</div>
	<div class="info" style="display: none;"></div>
	<fieldset class="contact_info">
		<label class="required" for="<?php echo $this->get_field_id( 'name' ); ?>">Full Name</label>
		<input type="text" name="<?php echo $this->get_field_name( 'name' ); ?>" value="" class="name" id="<?php echo $this->get_field_id( 'name' ); ?>" placeholder="Full Name">
		<label class="required" for="<?php echo $this->get_field_id( 'email' ); ?>">Email Address</label>
		<input type="text" name="<?php echo $this->get_field_name( 'email' ); ?>" value="" class="email" id="<?php echo $this->get_field_id( 'email' ); ?>" placeholder="Email Address">
		<label class="required" for="<?php echo $this->get_field_id( 'subject' ); ?>">Email Subject</label>
		<input type="text" name="<?php echo $this->get_field_name( 'subject' ); ?>" value="" class="subject" id="<?php echo $this->get_field_id( 'subject' ); ?>" placeholder="Email Subject" />
	</fieldset>
	<fieldset class="message">
		<label class="required" for="<?php echo $this->get_field_id( 'body' ); ?>">Message</label>
		<textarea name="<?php echo $this->get_field_name( 'body' ); ?>" rows="8" cols="40" class="body" id="<?php echo $this->get_field_id( 'body' ); ?>" placeholder="Message"></textarea>
	</fieldset>
	<label class="required notice">Required</label>
	<fieldset class="recaptcha">
		<?php echo go_recaptcha()->get_inputs(); ?>
	</fieldset>
	<button class="submit">
		<?php echo esc_attr( $attributes['submit'] ); ?>
	</button>
</form>