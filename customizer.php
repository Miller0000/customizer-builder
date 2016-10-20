<?php

function CB_customize_register( $wp_customize )
{
    require 'customizer-builder.php';
    $CB = new CustomizerBuilder( $wp_customize );

    // $CB->newPanel("first_panel", "First Panel");
    
}
add_action( 'customize_register', 'CB_customize_register' );
