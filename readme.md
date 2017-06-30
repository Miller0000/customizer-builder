# WordPress Customizer Builder
The WordPress customizer requires a lot of lines of code to register a simple control. This wrapper aims to make registering controls as easy and readable as possible.

## Docs

The CustomizerBuilder (CB) is made to be used in themes. Include it in your theme's ```functions.php```:
```php
require(get_template_directory() . '/customizer/customizer.php');
```

Always use the CB inside the **customize_register** hook. This is the same place you normally register controls to the customizer. This hook is only used when opening the customizer in the WordPress admin dashboard, and will therefor not affect the performance of your website.

```php
add_action('customize_register', function($wp_customize)
{
    require 'customizer-builder.php';
    require 'controls/control-html-note.php';
    require 'controls/control-number.php';
    require 'controls/control-textarea.php';
    
    $CB = new CustomizerBuilder($wp_customize);
        
    //todo: add panels, sections and controls...    
}
```

### Panels and sections

Sections can be (but don't have to be) inside a panel. When creating a new panel using ```$CB->newPanel```, it is set as the **current panel** inside the CB. Adding new sections to this panel is done using ```$CB->addSection```. This function will always **add** a new section to the current panel. Creating a panel, and adding sections to it works like this:

```php
$CB->newPanel("first_panel", "First Panel");
    
    $CB->addSection("section_one", "Panel 1, Section 1");            
        // todo: add controls to this section...
            
    $CB->addSection("section_two", "Panel 1, Section 2");
        // todo: add controls to this section...
```

Panels and sections have an optional third argument for a closure, the closure is executed immediately after the panel/section is created. Using the closure allows for slightly more readable code. The code below uses closures, and is functionally the same as the code above.

```php
$CB->newPanel("first_panel", "First Panel", function() use ($CB) {

    $CB->addSection("section_one", "Panel 1, Section 1", function() use ($CB) {          
        // todo: add controls to this section...
    });  
            
    $CB->addSection("section_two", "Panel 1, Section 2", function() use ($CB) {
        // todo: add controls to this section...
    });

}); 
```

Creating a section that isn't inside a panel is done using ```$CB->newSection```, like this:

```php
$CB->newSection("section_3", "No panel, Section 3", function() use ($CB) {
    // todo: add controls to this section...
});
```

### Adding controls to sections

Customizer controls are always added to sections. The section that is created last (either using ```$CB->newSection``` or ```$CB->addSection```) is set as the **current section** inside the CB. Controls are always added to the current section.

```php
$CB->newSection("section_3", "No panel, Section 3", function() use ($CB) {
   
    $CB->addTextBox("homepage_title", "This is the label for the homepage title control");
    $CB->addImageUrl("test_image1_url", "Banner image");
    $CB->addImageId("test_image2_id", "Different image");
    $CB->addTextArea("textarea1", "This is a textarea");
    
});
```


### Retrieving the values
The CB only changes the way controls are added to the customizer, it doesn't change the way you use the values. Just like always, you can retrieve the stored values like this:
```php
$title = get_theme_mod("homepage_title");
$imgUrl = get_theme_mod("test_image1_url");
$imgID = get_theme_mod("test_image2_id");
$textareaText = get_theme_mod("textarea1");
```

### Adding arguments to controls
In an attempt to keep registering controls as simple and readable as possible, arguments to controls are added in the following way:
```php
$CB->addTextBox("name", "label textbox")->setDefault("this is the default value");
$CB->addImageId("name2", "label image")->setDescription("This returns an ID, useful for responsive images");
```
These argument functions are always added to the last added control. You can also use these functions like this:
```php
$CB->addTextBox("name", "label textbox");
    $CB->setDefault("this is the default value");
```

---

## Available controls and arguments
The following controls are available:
```php
$CB->addNumber($name, $label);
$CB->addTextArea($name, $label, $allowHtml = false);
$CB->addImageId($name, $label);
$CB->addImageUrl($name, $label);
$CB->addCheckbox($name, $label);
$CB->addUrl($name, $label);
$CB->addTextBox($name, $label, $allowHtml = false);
```
The following functions for adding arguments to controls are available:
```php
$CB->setDescription($string);
$CB->setDefault($string);
$CB->setTransport($string);
$CB->setCapability($string);
$CB->setSanitizeCallback($string);
```
The following functions are available to display things inside the customizer admin dashboard:
```php
$CB->displayNote($label, $content = ""); // for writing instructions or reminders inside the customizer
$CB->displayHr();
```
