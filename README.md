This bundle provides some features that ease mailing for Symfony projects.

*This bundle is under development, more features will be added soon, and existing ones may change.*

# Installation

## Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
$ composer require softspring/mailer-bundle
```

## Applications that don't use Symfony Flex

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require softspring/mailer-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Softspring\MailerBundle\SfsMailerBundle(),
        );

        // ...
    }

    // ...
}
```

# Configuration

## Configure mailer

By default, SfsMailerBundle uses xxxxxxxxx mailer service, created by SwiftmailerBundle.

If you want to setup another mailer service:

```yaml
# config/package/sfs_mailer.yaml
sfs_mailer:
    mailer: "your.mailer.service.name"
```
## Configure default from

Each template could have its custom sender name and address, but if you want to set default values:

```yaml
# config/package/sfs_mailer.yaml
sfs_mailer:
   from_email:
       sender_name: "My Application"
       address: "no-reply@my-application.com"
```

# Load templates 

## By configuration

```yaml
# config/package/sfs_mailer.yaml
sfs_mailer:
    templates:
        user_resetting:
            template: "@FOSUser/Resetting/email.txt.twig"
            html_block: body_html  # html by default
            text_block: body_text  # text by default
            subject_block: subject # default value
            from_email:
                sender_name: "My Application custom from"
                address: "custom-from@myapplication.com"
            example_context:
                user:
                    username: myusername
                confirmationUrl: "https://myapplication.com/resetting?code=123456879"
```                   
                 
## Creating a loader service

Create a loader class:

```php
<?php

namespace App\Mailer\Loader;

use Softspring\MailerBundle\Loader\TemplateLoaderInterface;
use Softspring\MailerBundle\Model\Template;
use Softspring\MailerBundle\Model\TemplateCollection;

class MyCustomTemplateLoader implements TemplateLoaderInterface
{
    public function load(): TemplateCollection
    {
        $collection = new TemplateCollection();

        $template = new Template();
        $template->setId('app.template1');
        $template->setTwigTemplate('mail/template1.txt.twig');
        // $template->setSubjectBlockName('subject');
        // $template->setHtmlBlockName('html');
        // $template->setTextBlockName('txt');
        $collection->addTemplate($template);

        return $collection;
    }
}
```

Configure your service and tag it as *sfs_mailer.template.loader*:   
   
```yaml     
services:
    App\Mailer\Loader\MyCustomTemplateLoader:
       tags: ['sfs_mailer.template_loader']                  
```          