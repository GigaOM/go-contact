go-contact
==========

Description
---
Handles the form processing. Creates a shortcode that can be use to insert an email contact form into a post or page.


Requirements
---
While not required, this has a soft dependency with [go-recaptcha](https://github.com/GigaOM/go-recaptcha) and [go-gravatar](https://github.com/GigaOM/go-gravatar). Some of the design decisions were made for working with shortcodes and mulitple instances on a single page.

Hacking
---
Allows you to create your own templates to use for the contact form. There are two example forms in the 'components/templates' folder. Also allows you to add additional required fields in the required fields array:
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
* submit: The text for the submit button. Optional-default is 'Submit'.
* form: The form template to use. Looks in the /templates/folder. Expects the filename to end in "-form.php".
So, if you enter "acme" for the template name, it looks for /templates/acme-form.php
also, "optional - default template is /templates/default-form.php"