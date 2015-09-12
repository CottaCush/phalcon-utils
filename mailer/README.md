Mailer 
======
Library to handle Mailing


Contributors
------------
Adeyemi Olaoye <yemi@cottacush.com>


Requirements
------------
* [SwiftMailer](https://github.com/swiftmailer/swiftmailer)
* [Active Mandrill Account](http://mandrill.com)



Usage
-----

* Setup Configuration

```php
return new \Phalcon\Config(array(
    ....
'params' => array(
        'mailer' => array(
            'mandrill_username' => 'user@example.com',
            'mandrill_password' => '****',
            'default_from' => ['test@example.com' => 'Test'],
            'smtp_host' => 'smtp.mandrillapp.com',
            'smtp_port' => 587
        ),
    ),
    ....    
);
```

* Setup Mailer in Dependency Injection

```php
 /**
  * Register mailer service
  */
$di->set('mailer', function () use ($config) {
    return new PhalconUtils/Mailer(
    $config->params->mailer->mandrill_username,
    $config->params->mailer->mandrill_password,
    $config->params->mailer->smtp_host,
    $config->params->mailer->smtp_port,
    $config->params->mailer->default_from);
});
```


* Send Email

    **Parameters**
    
    * `text` - Text version of email body
     
    
    * `html` - HTML version of email body
    
     
    * `subject` - email subject
    
    
    * `to` - recipient email and name as an associative array
    
    
    * `from` - sender email and name as an associative array - defaults to `default_from_email` in config
    
    
    * `cc` - array of cced recipients email and name as an associative array
    
    
    * `bcc` - array of bcced recipients email and name as an associative array
    
    
    * `params` - parameters to be replaced in email body
    
    
```php
$sendStatus = $this->mailer->send('Hello World', '<b>Hello World {{title}} {{name}}</b>', 'Test Email', 
                                  ['test@example.com' => 'Test Test'],
                                  ['sender@example.com' => 'Test Test'], 
                                  ['testcc@example.com' => 'Test Test'],
                                  ['testbcc@example.com' => 'Test Test'], ['title'=> 'Mr', 'name'=> 'Test']);
```