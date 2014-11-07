go-contact
==========

Description
---
Creates a shortcode can be use to insert a contact form into a post or page.


Requirements
---
Software: none.

While not required, this has a soft dependency with [go-recaptcha](https://github.com/GigaOM/go-recaptcha) and [go-gravatar](https://github.com/GigaOM/go-gravatar). Some of the design decisions were made for working with shortcodes and mulitple instances on a single page.

Hacking
---
Allows you to creat your own templates to use for the contact form. There are two example forms in the 'components/templates' folder. Also allows you to add additional required fields in the required fields array:
```php
public $required_fields = array(
		'email',
		'name',
		'subject',
		'body',
	);
```

Usage
---
You insert a shortcode:
```html
[go_contact email='example@example.com' form='about' submit='clickme' ]
```
The shortcode takes three parameters:
* email: email address that the form will be sent to.
* submit: when the submit button is clicked.
* form: the form that is being sent out. Inserts -form at the end.