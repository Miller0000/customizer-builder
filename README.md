# WordPress Customizer Builder
A wrapper for $wp_customize that makes working with the WordPress customizer easier

##Docs

Always use the CustomizerBuilder (CB) inside the **customize_register** hook. This is the same place you normally register controls to the customizer. This hook is only used when opening the customizer in the WordPress backoffice, and will therefor not affect the performance of your website.

```php
function CB_customize_register( $wp_customize )
{
    require 'customizer-builder.php';
    $CB = new CustomizerBuilder( $wp_customize );
        
    //todo: add panels, sections and controls...    
}
add_action( 'customize_register', 'CB_customize_register' );
```

###Panels and sections

Sections can be (but don't have to be) inside a panel. When creating a new panel using ```$CB->newPanel```, it is set as the **current panel** inside the CB. Adding new sections to this panel is done using ```$CB->addSection```. This function will always **add** a new section to the current panel. Creating a panel, and adding sections to it works like this:

```php
$CB->newPanel("first_panel", "First Panel");
    
    $CB->addSection("section_one", "Panel 1, Section 1");            
        // todo: add controls to this section...
            
    $CB->addSection("section_two", "Panel 1, Section 2");
        // todo: add controls to this section...
```

Creating a section that isn't inside a panel is done using ```$CB->newSection```, like this:

```php
$CB->newSection("section_3", "No panel, Section 3");
    // todo: add controls to this section...
```

###Adding controls to sections

Customizer controls are always added to sections. The section that is created last (either using ```$CB->newSection``` or ```$CB->addSection```) is set as the **current section** inside the CB. Controls are always added to the current section.

```php
$CB->newSection("section_3", "No panel, Section 3");
    $CB->addTextbox("homepage_title", "This is the label for the homepage title control");
    $CB->addImageUrl("test_image1_url", "Banner image");
    $CB->addImageID("test_image2_id", "Different image");
    $CB->addTextarea("textarea1", "This is a textarea");
```


###Using the values
The CB only changes the way controls are added to the customizer, it doesn't change the way you use the values. Just like always, you can retrieve the stored values like this:
```php
$title = get_theme_mod("homepage_title");
$imgUrl = get_theme_mod("test_image1_url");
$imgID = get_theme_mod("test_image2_id");
$textareaText = get_theme_mod("textarea1");
```

###Adding arguments to controls
In an attempt to keep registering controls are simple and readable as possible, arguments to controls are added in the following way:
```php
$CB->addTextbox("name", "label textbox")->DefaultValue("this is the default value");
$CB->addImageID("name2", "label image")->Description("This returns an ID, useful for responsive images");
```
These argument functions are always added to the last added control. You can also use these functions like this:
```php
$CB->addTextbox("name", "label textbox");
    $CB->DefaultValue("this is the default value");
```

---

##Available controls and arguments
The following controls are currently available:
```php
$CB->addNumber( $name, $label );
$CB->addNote($label, $content = "" ); // for writing instructions or reminders inside the customizer
$CB->addTextarea( $name, $label, $allowHTML = false );
$CB->addImageID( $name, $label );
$CB->addImageUrl( $name, $label );
$CB->addCheckbox( $name, $label );
$CB->addUrl( $name, $label );
$CB->addTextbox( $name, $label, $allowHTML = false );
```
The following functions for adding arguments to controls are available:
```php
$CB->Description( $string );
$CB->DefaultValue( $string );
$CB->Transport( $string );
$CB->Capability( $string );
$CB->SanitizeCallback( $string );
```

