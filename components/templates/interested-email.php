<?php
echo  'Name: ' . esc_attr( $from_name );

echo  "\n\r" . 'Email: ' . sanitize_email( $from_email );

echo  "\n\r" . 'Job Title: ' . wp_kses( $_POST[ $this->slug ][ $instance ]['jobtitle'], array() );

echo  "\n\r" . 'Job Function: ' . wp_kses( $_POST[ $this->slug ][ $instance ]['jobfunction'], array() );

echo  "\n\r" . 'Job Role: ' . wp_kses( $_POST[ $this->slug ][ $instance ]['jobrole'], array() );

echo  "\n\r" . 'Industry: ' . wp_kses( $_POST[ $this->slug ][ $instance ]['industry'], array() );