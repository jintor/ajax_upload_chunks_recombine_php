<?php

function clean ( $toClean ) {

	if ( is_string( $toClean ) )  { 
	
		$toClean = trim ( $toClean ) ;
	
		$toClean = str_replace("&apos;",'’',$toClean);
		$toClean = str_replace("&#39;",'’',$toClean);
		$toClean = str_replace("&quot;",'”',$toClean);
		$toClean = str_replace("&amp;",'&',$toClean);
		$toClean = str_replace("&nbsp;",' ',$toClean);
		$toClean = str_replace('%27',"’",$toClean);
		$toClean = str_replace('%2C',",",$toClean);
		
		$toClean = rawurldecode ( $toClean ) ;
		$toClean = html_entity_decode ( $toClean ) ;
		
		// FORCE ’ and ”
		$toClean = str_replace('`','’',$toClean);	
		$toClean = str_replace("'",'’',$toClean);
		$toClean = str_replace("'",'’',$toClean);
		$toClean = str_replace('"','”',$toClean);
		
		$toClean = addslashes ( $toClean ) ; 
		
	}
	
	return $toClean ;
	
}
