<?php
echo wp_kses_post( $message );

if ( $gravatar_profile = go_gravatar()->get_profile( $from_email ) )
{
	echo "\n\r" . '-----------------';

	echo "\n\r" . 'Gravatar Profile: ' . esc_url( $gravatar_profile->profileUrl );

	echo  "\n\r" . 'Name: ' . esc_attr( $gravatar_profile->displayName );

	if ( '' != $gravatar_profile->aboutMe )
	{
		echo "\n\r" . 'About: ' . wp_kses_post( $gravatar_profile->aboutMe );
	} // END if

	if ( '' != $gravatar_profile->currentLocation )
	{
		echo "\n\r" . 'Location: ' . esc_attr( $gravatar_profile->currentLocation );
	} // END if
} // END if

echo "\n\r" . '-----------------';

echo  "\n\r" . 'Name used on form: ' . esc_attr( $from_name );

echo  "\n\r" . 'Email: ' . sanitize_email( $from_email );