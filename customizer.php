<?php

function CB_customize_register( $wp_customize )
{
    require 'customizer-builder.php';
    $CB = new CustomizerBuilder( $wp_customize );


    $CB->newPanel("first_panel", "First Panel");

        $CB->addSection("p1_section_one", "Panel 1, Section 1");

            $CB->addTextbox( "homepage_title", "Homepage title")->Description("example description")->DefaultValue("Customizer Builder");

        $CB->addSection("p1_section_two", "Panel 1, Section 2");

            $CB->addImageUrl("test_image1_url", "Banner image")->Description("This returns an URL");
            $CB->addImageID("test_image2_id", "Different image")->Description("This returns an ID, useful for responsive images");
            $CB->addTextarea("textarea1", "This is a textarea");


    $CB->newSection("section_3", "No panel, Section 3");

        $CB->addNote("<hr>This is a note", "useful for reminding people about <b>stuff</b>");

        $CB->addCheckbox("charlie", "Did you remember the stuff?")->DefaultValue(true);

}
add_action( 'customize_register', 'CB_customize_register' );