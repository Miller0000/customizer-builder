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
            $CB->displayNote("Useful message", "Here you can type a useful message");

            $CB->addTextBox("website_name", "Website name")
               ->setDefault("My Cool Website");
        });


        $CB->addSection("second_section", "Second Section", function() use ($CB) {
            $CB->displayNote("Note label", "This panel has two sections");
        }); 

    });


});
