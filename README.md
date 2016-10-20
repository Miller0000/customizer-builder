# customizer-builder
A wrapper for $wp_customize that makes working with the WordPress customizer easier

##Code example

Always use the CustomizerBuilder inside the **customize_register** hook

```php

function CB_customize_register( $wp_customize )
{
    require 'customizer-builder.php';
    $CB = new CustomizerBuilder( $wp_customize );
    
    
    $CB->newPanel("first_panel", "First Panel");
    
        $CB->addSection("p1_section_one", "Panel 1, Section 1");
            
            $CB->addTextbox( "homepage_title", "Homepage title");
            $CB->addTextbox( "homepage_subtitle", "Homepage subtitle");
            $CB->addTextbox( "homepage_quote", "Homepage quote");
            
        $CB->addSection("p1_section_two", "Panel 1, Section 2");
        
            $CB->addImageUrl("test_image1_url", "Banner image")->Description("This returns an URL");
            $CB->addImageID("test_image2_id", "Different image")->Description("This returns an ID, useful for responsive images");
            $CB->addTextarea("textarea1", "This is a textarea");
            
            
    $CB->newSection("section_3", "No panel, Section 3");
    
        $CB->addNote("<hr>This is a note", "useful for reminding people about <b>stuff</b>");
        $CB->addCheckbox("charlie", "Did you remember the stuff?")->DefaultValue(true);
        
}
add_action( 'customize_register', 'CB_customize_register' );
```

The rest of the code stays the same, to get your values, just use:
```php
$title = get_theme_mod("homepage_title");
$subtitle = get_theme_mod("homepage_subtitle");
$quote = get_theme_mod("homepage_quote");
```


##Docs

###Panels and sections
There are 3 functions for working with panels and sections
```php
    $CB = new CustomizerBuilder( $wp_customize );    
    $CB->newPanel( "Name", "Title" );
    $CB->newSection( "Name", "Title" );    
    $CB->addSection( "Name", "Title" );
```

**newPanel** created a new panel, and sets it as the current panel

**newSection** created a new section, and sets it as the current section (it is NOT added to the current panel)

**addSection** created a new section, adds it to the current panel, and sets it as the current section

###Adding controls to sections
Controls are always added to the current section


###Priority
Controls will appear in the order they are added in the code. CB has a priority counter that starts at 1000, and is incremented whenever a panel, section or control is added