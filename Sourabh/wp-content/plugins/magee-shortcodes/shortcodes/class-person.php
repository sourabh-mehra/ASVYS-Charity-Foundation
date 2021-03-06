<?php
class Magee_Person {

	public static $args;
    private  $id;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

        add_shortcode( 'ms_person', array( $this, 'render' ) );
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
				'id' 					=>'',
				'class' 				=>'',
				'name'					=>'',	
				'title' 				=>'',
				'picture' 				=>'',
				'piclink'				=>'#',	
				'picborder' 			=>'0',
				'picbordercolor' 		=>'',
				'picborderradius'		=>'0',
				'iconboxedradius'		=>'4px',
				'iconcolor'				=>'#595959',	
				'link1'					=>'#',
				'link2' 				=>'#',
				'link3'					=>'#',				
				'link4' 				=>'#',
				'link5' 				=>'#',
				'icon1'					=>'',
				'icon2' 				=>'',
				'icon3'					=>'',				
				'icon4' 				=>'',
				'icon5' 				=>'',
			), $args
		);
		
		extract( $defaults );
		self::$args = $defaults;
		$uniqid = uniqid('person-');
		$this->id = $id.$uniqid;
        $class .= ' '.$uniqid;
		
		
		$textstyle1 = sprintf('.'.$uniqid.' .person-vcard.person-social li a i{ border-radius: %s; background-color:%s;}',$iconboxedradius,$iconcolor);
		$textstyle2 = sprintf('.'.$uniqid.' .img-box img{ border-radius: %s; display: inline-block;}',$picborderradius);
		
		$imgstyle = '';
		if( $picborder !='' )
		$imgstyle .= sprintf('.'.$uniqid.' .img-box img{border-width: %s;border-style: solid;}',$picborder);
		
		if( $picbordercolor !='' )
		$imgstyle .= sprintf('.'.$uniqid.' .img-box img{border-color: %s;}',$picbordercolor);
		
		$styles = sprintf( '<style type="text/css" scoped="scoped">%s %s %s</style>', $textstyle1,$textstyle2,$imgstyle);
		$divimgtitle = '<div class="img-overlay primary"><div class="img-overlay-container"><div class="img-overlay-content"><i class="fa fa-link"></i></div></div></div>';
		$divimga = sprintf('<a href="%s" ><img src="%s">%s</a>',$piclink,$picture,$divimgtitle);
		$divimg = sprintf('<div class="person-img-box"><div class="img-box figcaption-middle text-center fade-in">%s</div></div>',$divimga);
		$divname = sprintf('<h3 class="person-name" style="text-transform: uppercase;">%s</h3>',$name);
		$divtitle = sprintf('<h4 class="person-title" style="text-transform: uppercase;">%s</h4>',$title);
		$divcont = sprintf('<p class="person-desc">%s</p>',do_shortcode( Magee_Core::fix_shortcodes($content)));
		$divli = '';
		if($icon1 != ''){
			$divli .= sprintf(' <li><a href="%s"><i class="fa %s"></i></a></li>',$link1,$icon1);
		}
		if($icon2 != ''){
			$divli .= sprintf(' <li><a href="%s"><i class="fa %s"></i></a></li>',$link2,$icon2);
		}
		if($icon3 != ''){
			$divli .= sprintf(' <li><a href="%s"><i class="fa %s"></i></a></li>',$link3,$icon3);
		}
		if($icon4 != ''){
			$divli .= sprintf(' <li><a href="%s"><i class="fa %s"></i></a></li>',$link4,$icon4);
		}
		if($icon5 != ''){
			$divli .= sprintf(' <li><a href="%s"><i class="fa %s"></i></a></li>',$link5,$icon5);
		}		
		$divul=sprintf('<div class="person-vcard text-center">%s %s %s<ul class="person-social" >%s</ul></div>',$divname,$divtitle,$divcont,$divli);
		$html=sprintf('%s<div class="magee-person-box %s" id = "%s">%s %s</div>',$styles,$class,$id,$divimg,$divul);
 
		
		return $html;
	}
	
}

new Magee_Person();