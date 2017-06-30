<?php

add_action('customize_register', function($wp_customize)
{
    require 'customizer-builder.php';
    require 'controls/control-html-note.php';
    require 'controls/control-number.php';
    require 'controls/control-textarea.php';
    
    $CB = new CustomizerBuilder($wp_customize);

    $CB->newPanel("first_panel", "First Panel", function() use ($CB) {

        $CB->addSection("first_section", "First Section", function() use ($CB) {
        
            $CB->displayNote("Note label", "This section shows all available controls");
            $CB->addTextBox("textBox1", "TextboxLabel");
            $CB->displayHr();
            $CB->addUrl("url1", "Url");
            $CB->addCheckbox("checkbox1", "Checkbox");
            $CB->addImageUrl("imageUrl1", "Image Url");
            $CB->addImageId("imageId1", "Image Id");
            $CB->addTextArea("textArea1", "Text Area");
            $CB->addNumber("number1", "Number");

        });


        $CB->addSection("second_section", "Second Section", function() use ($CB) {

            $CB->displayNote("Note label", "This panel has two sections");

        }); 

    });


    $CB->newSection("header_section", "Header", function() use ($CB) {

        $CB->displayNote("Useful message", "Here you can type a useful message");

        $CB->addTextBox("website_name", "Website name")
           ->setDefault("My Cool Website");

        $CB->addImageUrl("header_logo_src", "Header logo")
           ->setDescription("The header logo should be at least 200px wide");

    });


});
