<?php

if( !defined("ABSPATH") )
    exit;

class CustomizerBuilder
{
    // Version: 0001

    /** @var  $wp_customize WP_Customize_Manager */
    private $wp_customize;
    private $PriorityCounter = 1000;
    private $NoteCounter     = 0;
    private $CurrentSection  = null;
    private $CurrentPanel    = null;

    private $_controlName           = null;
    private $_settingArgumentsArray = [];
    private $_controlArgumentsArray = [];
    private $_customControlClass    = null;

    public function __construct( $wp_customize )
    {
        $this->wp_customize = $wp_customize;
    }

    function __destruct()
    {
        $this->processCurrentControl();
    }

    private function p()
    {
        return $this->PriorityCounter++;
    }

    private function hasQueuedControl()
    {
        return !is_null( $this->_controlName );
    }

    private function processCurrentControl()
    {
        if( $this->hasQueuedControl() )
            $this->_addCurrentControl();

        $this->_controlName           = null;
        $this->_settingArgumentsArray = [];
        $this->_controlArgumentsArray = [];
        $this->_customControlClass    = null;
    }

    private function _addCurrentControl()
    {
        $this->wp_customize->add_setting( $this->_controlName, $this->_settingArgumentsArray );

        if( !is_null($this->_customControlClass) )
            $this->wp_customize->add_control( new $this->_customControlClass($this->wp_customize, $this->_controlName, $this->_controlArgumentsArray) );
        else
            $this->wp_customize->add_control( $this->_controlName, $this->_controlArgumentsArray );
    }

    private function initializeNewControl( $name, $label, $customControlClass = null )
    {
        if( is_null( $this->CurrentSection ) )
            throw new Exception("You need to add a section before adding a control");

        $this->processCurrentControl();

        $this->_controlName = $name;

        if( !is_null( $customControlClass ) )
            $this->_customControlClass = $customControlClass;

        $this->_setControlArgument( "label", $label );
        $this->_setControlArgument( "section", $this->CurrentSection );
        $this->_setControlArgument( "priority", $this->p() );
    }



    public function newPanel( $name, $title )
    {
        $this->processCurrentControl();
        $this->wp_customize->add_panel( $name, [ 'title' => $title, 'priority' => $this->p(), ] );
        $this->CurrentPanel = $name;
    }

    public function newSection( $name, $title )
    {
        $this->processCurrentControl();
        $this->wp_customize->add_section( $name, [ 'title' => $title, 'priority' => $this->p(), ] );
        $this->CurrentSection = $name;
    }


    public function addSection( $name, $title )
    {
        if( is_null( $this->CurrentPanel ) )
            throw new Exception("You need to create a panel before using addSection");

        $this->processCurrentControl();
        $this->wp_customize->add_section( $name, [ 'title' => $title, 'panel' => $this->CurrentPanel, 'priority' => $this->p(), ] );
        $this->CurrentSection = $name;
    }

    public function addTextbox( $name, $label, $allowHTML = false )
    {
        $this->initializeNewControl( $name, $label );

        if( $allowHTML )
            $this->SanitizeCallback("wp_kses_post");

        return $this;
    }

    function addUrl( $name, $label )
    {
        $this->initializeNewControl( $name, $label );

        $this->SanitizeCallback("esc_url_raw");

        return $this;
    }

    function addCheckbox( $name, $label )
    {
        $this->initializeNewControl( $name, $label );

        $this->_setControlArgument( "type", "checkbox" );
        $this->DefaultValue(false);

        return $this;
    }


    public function addImageUrl( $name, $label )
    {
        $this->initializeNewControl( $name, $label, "WP_Customize_Image_Control" );

        $this->SanitizeCallback("esc_url_raw");

        return $this;
    }

    function addImageID( $name, $label )
    {
        $this->initializeNewControl( $name, $label, "WP_Customize_Media_Control" );

        $this->_setControlArgument( "mime_type", "image" );

        return $this;
    }

    function addTextarea( $name, $label, $allowHTML = false )
    {
        $this->initializeNewControl( $name, $label, "CB_Textarea_Control" );

        if( $allowHTML )
            $this->SanitizeCallback("wp_kses_post");

        return $this;
    }


    /**
     * Displays a (HTML) note in the customizer.
     */
    public function addNote($label, $content = "" )
    {
        // Every control needs a unique setting, even if the setting wont be used
        $NoteName = "cb__" . $this->NoteCounter++;

        $this->initializeNewControl($NoteName, $label, "CB_HTML_Note");

        $this->_setControlArgument("content", $content);
    }

    function addNumber( $name, $label )
    {
        $this->initializeNewControl( $name, $label, "CB_Number_Control" );

        $this->_setControlArgument( "type", "number" );

        return $this;
    }



    private function _setSettingsArgument($key, $value)
    {
        if( !$this->hasQueuedControl() )
            throw new Exception("There is no control to add this setting to");

        $this->_settingArgumentsArray[$key] = $value;
    }

    private function _setControlArgument($key, $value)
    {
        if( !$this->hasQueuedControl() )
            throw new Exception("There is no control to add this setting to");

        $this->_controlArgumentsArray[$key] = $value;
    }

    public function Description( $string )
    {
        $this->_setControlArgument("description", $string);
        return $this;
    }

    public function DefaultValue( $string )
    {
        // http://stackoverflow.com/questions/10395930/cant-have-function-named-default-in-php
        $this->_setSettingsArgument("default", $string);
        return $this;
    }

    public function Transport( $string )
    {
        $this->_setSettingsArgument("transport", $string);
        return $this;
    }

    public function Capability( $string )
    {
        $this->_setSettingsArgument("capability", $string);
        return $this;
    }

    public function SanitizeCallback( $string )
    {
        $this->_setSettingsArgument("sanitize_callback", $string);
        return $this;
    }


}









if ( class_exists('WP_Customize_Control') )
{

    class CB_HTML_Note extends WP_Customize_Control
    {
        public $content = '';
        public function render_content()
        {
            if (isset( $this->label ))	     echo '<span class="customize-control-title">' . $this->label . '</span>';
            if (isset( $this->content ))     echo $this->content;
            if (isset( $this->description )) echo '<span class="description customize-control-description">' . $this->description . '</span>';
        }
    }


    class CB_Textarea_Control extends WP_Customize_Control
    {
        public $type = 'textarea';
        public function render_content()
        {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
            </label>
            <?php
        }
    }


    class CB_Number_Control extends WP_Customize_Control
    {
        public $type = 'number';
        public function render_content()
        {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <input type="number" <?php $this->link(); ?> value="<?php echo intval($this->value()); ?>"/>
            </label>
            <?php
        }
    }

}