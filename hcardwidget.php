<?php
/**
 * @package hCard ShortCode
 */
/*
Plugin Name: hCard ShortCode
Plugin URI: http://www.compartoweb.com
Description: hCard ShortCode automatically translates vCards into hCards and displays them. Invoke it with [hcard].
Version: 1.0.0
Author: Comparto Web
Author URI: http://www.compartoweb.com
License: GPLv2 or later
*/

require_once dirname( __FILE__ ) . '/lib/phpMicroformats.class.php';
require_once dirname( __FILE__ ) . '/lib/Contact_Vcard_Parse.class.php';

add_filter('upload_mimes', 'custom_upload_mimes');
add_shortcode( 'hcard', 'vcard_func' );

/* == enable vcf upload support == */
function custom_upload_mimes( $existing_mimes=array() ) {
 	$existing_mimes['vcf'] = 'text/x-vcard';
	return $existing_mimes; 
}

/* == an utility function to navigate in depth a nested array == */
function vcard_get($info, $array){
	$base = $info;
	foreach($array as $item){
		if ($base[$item]){
			$base = $base[$item];
		}else{
			return null;
		}
	}
	return $base;
}

/* == parses the vcard and extracts information, then displays them according to the 'only' options == */
function vcard_func( $atts ){
	
	/* == vcard, the vcard slug == only, an optional comma-separated list of keys to display == */
	extract( shortcode_atts( array(
	    'vcard'  => null,
	    'only'   => null
	    ), $atts ));
	
	$hcard = "";
	$onlyies = explode(",",$only);
		
	if($vcard != null){ 
		/* == retrieve the vcard == */
		$vposts = get_posts(array( 'post_name' => $vcard, 'post_type' => 'attachment', 'numberposts' => 1));
		
		if(!empty($vposts)){
			
			$vpost = $vposts[0];
			$vcard_txt = file_get_contents($vpost->guid);
			
			/* == parse the vcard == */
			$parse = new Contact_Vcard_Parse();
			$cardinfo = $parse->fromtext($vcard_txt);
			
			$pic_url =  vcard_get($cardinfo,array(0,'PHOTO',0,'value',0,0));
			$pic_url =  strpos($pic_url, 'http') === 0 ? $pic_url : site_url() . $pic_url;	
			
			/* == create the values array == */
			$values = array();
			
			if(empty($onlyies) || in_array('name',$onlyies))
				$values['name']  = vcard_get($cardinfo,array(0,'N',0,'value','given',0)) . " " . 								 								    vcard_get($cardinfo,array(0,'N',0,'value','family',0));

			if(empty($onlyies) || in_array('email',$onlyies))
				$values['email'] = array(
										'type'   => vcard_get($cardinfo,array(0,'EMAIL',0,'param','TYPE',0)),
										'value'  => vcard_get($cardinfo,array(0,'EMAIL',0,'value',0,0))
								   );
			
			$values['org'] = array();
								   
   			if(empty($onlyies) || in_array('org:title',$onlyies))
   				$values['org']['title'] = vcard_get($cardinfo,array(0,'TITLE',0,'value',0,0));
			
   			if(empty($onlyies) || in_array('org:name',$onlyies))
   				$values['org']['name']  = vcard_get($cardinfo,array(0,'ORG',0,'value',0,0));
			
			$values['url'] = array();

			if(empty($onlyies) || in_array('url',$onlyies))
				foreach(vcard_get($cardinfo,array(0,'URL')) as $url){
					$values['url'][] = array(
									'type' 	=> vcard_get($url, array('param','TYPE',0)),
									'value'	=> vcard_get($url, array('value',0,0)),
								);
				}

   			if(empty($onlyies) || in_array('photo',$onlyies))
   				$values['photo'] = $pic_url;

   			if(empty($onlyies) || in_array('location',$onlyies))
   				$values['location'] = array(
										'street'   => vcard_get($cardinfo,array(0,'ADR',0,'value','street',0)),
										'town' 	   => vcard_get($cardinfo,array(0,'ADR',0,'value','locality',0)),
										'state'    => vcard_get($cardinfo,array(0,'ADR',0,'value','region',0)),
										'country'  => vcard_get($cardinfo,array(0,'ADR',0,'value','country',0)),
										'zip' 	   => vcard_get($cardinfo,array(0,'ADR',0,'value','postcode',0))
									  );
	
			/* == create the hcard == */
			$hcard = phpMicroformats::createHCard($values);
			
		}
	}
	
	/* == print the hcard == */
	return $hcard;
}

?>