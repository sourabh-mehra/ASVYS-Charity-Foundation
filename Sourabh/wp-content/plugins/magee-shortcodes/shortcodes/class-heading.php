<?php
class Magee_Title {

	public static $args;
    private  $id;


	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

        add_shortcode( 'ms_heading', array( $this, 'render' ) );
	}

	/**
	 * Render the shortcode
	 * @param  array $args     Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string          HTML output
	 */
	function render( $args, $content = '') {

		$defaults =	Magee_Core::set_shortcode_defaults(
			array(
				'id' 					=> '',
				'class' 				=> '',
				'style'					=> 'border',	
				'color'				    => '',
				'border_color'          => '',
				'text_align'            => '',
				'font_weight'            => '400',
				'font_size'            => '36px',
				'margin_top'            => '',
				'margin_bottom'         => '',
				'border_width'           => '5px',
			), $args
		);
		
		extract( $defaults );
		self::$args = $defaults;
		
		$uniqid = uniqid('heading-');
		$class .=' '.$uniqid;

		$html  = '<style type="text/css">
		                          
                                    .'.$uniqid.'.magee-heading{
                                        font-size:'.$font_size.';
                                        font-weight:'.$font_weight.';
                                        margin-top:'.$margin_top.';
                                        margin-bottom:'.$margin_bottom.';
                                        color: '.$color.';
                                        border-color: '.$border_color.';
                                        text-align: '.$text_align.';
                                    }
                                    .'.$uniqid.'.heading-border .heading-inner {
                                        border-width: '.$border_width.';
                                    }
								.'.$uniqid.'.heading-doubleline .heading-inner:before,
								.'.$uniqid.'.heading-doubleline .heading-inner:after {
									    border-color: '.$border_color.';
									    border-width: '.$border_width.';
									}
                                </style>';
		$html .= '<h1 class="magee-heading heading-'.$style.' '.$class.'" id="'.$id.'"><span class="heading-inner">'.do_shortcode( Magee_Core::fix_shortcodes($content)).'</span></h1>';
  
		
		return $html;
	}
	
}

new Magee_Title();