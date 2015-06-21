<?php
$required_fields = array(
	'email',
	'name',
	'jobtitle',
	'jobfunction',
	'jobrole',
	'industry',
);
?>
<form action="<?php echo esc_url_raw( $target ); ?>" id="<?php echo esc_attr( $this->slug . '-' . $this->instance ); ?>" class="<?php echo esc_attr( $this->slug ); ?> always-show go-form-interested" style="display: block;">
	<?php $this->nonce_field( $this->instance );?>
	<input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'hash' ) ); ?>" value="<?php echo esc_attr( $email_hash ); ?>" />
	<input type="hidden" name="<?php echo $this->get_field_name( 'required' ); ?>" value="<?php echo esc_attr( implode( ',', $required_fields ) ); ?>" id="<?php echo $this->get_field_id( 'required' ); ?>">
	<fieldset class="contact_info">
		<label class="required" for="<?php echo $this->get_field_id( 'name' ); ?>">Full Name</label>
		<input type="text" name="<?php echo $this->get_field_name( 'name' ); ?>" value="" class="name" id="<?php echo $this->get_field_id( 'name' ); ?>" placeholder="Full Name">
		<label class="required" for="<?php echo $this->get_field_id( 'email' ); ?>">Company Email</label>
		<input type="text" name="<?php echo $this->get_field_name( 'email' ); ?>" value="" class="email" id="<?php echo $this->get_field_id( 'email' ); ?>" placeholder="Company Email">
		<label class="required" for="<?php echo $this->get_field_id( 'jobtitle' ); ?>">Job Title</label>
		<input type="text" name="<?php echo $this->get_field_name( 'jobtitle' ); ?>" value="" class="jobtitle" id="<?php echo $this->get_field_id( 'jobtitle' ); ?>" placeholder="Job Title" />
		<label class="required" for="<?php echo $this->get_field_id( 'jobfunction' ); ?>">Job Function</label>
		<select name="<?php echo $this->get_field_name( 'jobfunction' ); ?>" id="<?php echo $this->get_field_id( 'jobfunction' ); ?>" class="jobfunction">
			<option selected disabled>Select ...</option>
			<option value="Analyst Relations">Analyst Relations</option>
			<option value="Executive">Executive</option>
			<option value="Finance">Finance</option>
			<option value="IT - All">IT - All</option>
			<option value="IT - Business Intelligence">IT - Business Intelligence</option>
			<option value="IT - Database">IT - Database</option>
			<option value="IT - Network">IT - Network</option>
			<option value="IT - Project Management">IT - Project Management</option>
			<option value="IT - Quality / Testing">IT - Quality / Testing</option>
			<option value="IT - Risk / Compliance / Security">IT - Risk / Compliance / Security</option>
			<option value="IT - Server / Storage">IT - Server / Storage</option>
			<option value="IT - Strategy">IT - Strategy</option>
			<option value="IT - Telecom">IT - Telecom</option>
			<option value="Marketing / Communications">Marketing / Communications</option>
			<option value="Research & Development">Research & Development</option>
			<option value="Sales">Sales</option>
			<option value="Other">Other</option>
		</select>
		<label class="required" for="<?php echo $this->get_field_id( 'jobrole' ); ?>">Job Role</label>
		<select name="<?php echo $this->get_field_name( 'jobrole' ); ?>" id="<?php echo $this->get_field_id( 'jobrole' ); ?>" class="jobrole">
			<option selected disabled>Select ...</option>
			<option value="CEO / President">CEO / President</option>
			<option value="CMO">CMO</option>
			<option value="CTO / CIO">CTO / CIO</option>
			<option value="Other C-level">Other C-level</option>
			<option value="VP">VP</option>
			<option value="Director">Director</option>
			<option value="Manager">Manager</option>
			<option value="Developer / Engineer / Architect">Developer / Engineer / Architect</option>
			<option value="Analyst">Analyst</option>
			<option value="Editor">Editor</option>
			<option value="Other">Other</option>
		</select>
		<label class="required" for="<?php echo $this->get_field_id( 'industry' ); ?>">Industry</label>
		<select name="<?php echo $this->get_field_name( 'industry' ); ?>" id="<?php echo $this->get_field_id( 'industry' ); ?>" class="industry">
			<option selected disabled>Select ...</option>
			<option value="volvo">Agriculture</option>
			<option value="saab">Apparel</option>
			<option value="opel">Banking / CIO</option>
			<option value="audi">Biotechnology</option>
			<option value="audi">Chemicals</option>
			<option value="audi">Communications</option>
			<option value="audi">Construction</option>
			<option value="audi">Consulting</option>
			<option value="audi">Education</option>
			<option value="audi">Electronics</option>
			<option value="audi">Energy</option>
			<option value="audi">Engineering</option>
			<option value="audi">Entertainment</option>
			<option value="audi">Environmental</option>
			<option value="audi">Finance</option>
			<option value="audi">Food & Beverage</option>
			<option value="audi">Government</option>
			<option value="audi">Healthcare</option>
			<option value="audi">Hospitality</option>
			<option value="audi">Insurance</option>
			<option value="audi">Machinery</option>
			<option value="audi">Manufacturing</option>
			<option value="audi">Media</option>
			<option value="audi">Not For Profit</option>
			<option value="audi">Other</option>
			<option value="audi">Recreation</option>
			<option value="audi">Retail</option>
			<option value="audi">Shipping</option>
			<option value="audi">Technology</option>
			<option value="audi">Telecommunications</option>
			<option value="audi">Transportation</option>
			<option value="audi">Utilities</option>
		</select>
	</fieldset>
	<button class="submit button primary" style="font-size: larger;">
		<?php echo esc_html( $attributes['submit'] ); ?>
	</button>
</form>