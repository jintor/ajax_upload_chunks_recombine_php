<?php

if (isset($_FILES)) {

  include_once('clean.php');

	$pcache = clean( $_REQUEST['pcache'] ?? '' ) ;
	$thismedia_id = clean( $_REQUEST['thismedia_id'] ?? '' ) ;
	$thismedia_name = clean( $_REQUEST['thismedia_name'] ?? '' );
	$thismedia_ext = pathinfo($exif_file)['extension'] ?? '';
	$numberofchunks = clean( $_REQUEST['numberofchunks'] ?? '' ) ;
	$thischunk_number = clean( $_REQUEST['thischunk_number'] ?? '' ) ;
	$thisfilex = clean( $_REQUEST['thisfilex'] ?? '' ) ;
	$howmanyfiles = clean( $_REQUEST['howmanyfiles'] ?? '' ) ;
	$thissession = clean( $_REQUEST['thissession'] ?? '' ) ;

	if ( $pcache != '' ) {
	
		// DEV
		// var_dump(['thismedia_id'=>$thismedia_id,'thismedia_name'=>$thismedia_name,'numberofchunks'=>$numberofchunks,'thischunk_number'=>$thischunk_number,'thisfilex'=>$thisfilex]);

		// WHERE TO SAVE CHUNKS
		$received_chunk_dir = '/temporary_path_here_you_receiver_chunks/'.$thissession.'/'.$thismedia_id.'/';
		$received_chunk_path = $received_chunk_dir.$thismedia_id.'_chunk_'.$thischunk_number;

		// IF DIRECTORY NOT THERE CREATE IT
		if ( !file_exists($received_chunk_dir) ) { shell_exec('mkdir -p "'.$received_chunk_dir.'"'); }

		// MOVE_UPLOADED_FILE
		foreach ($_FILES as $thisfilekey => $thisfilechun) { 
			if ( isset($thisfilechun['name']) && isset($thisfilechun['tmp_name']) ) { 
				if ( filesize($thisfilechun['tmp_name']) > 1 ) { move_uploaded_file($thisfilechun['tmp_name'],$received_chunk_path); }
			}
		}
	
		// ECHO PERCENT PROGRESSION FOR THAT FILE
		echo '<script>$("#phpz_'.$thismedia_id.'_message").html("FILE '.$thisfilex.' : received chunk number '.$thischunk_number.' of '.$numberofchunks.' chunks");</script>';

	}

}

?>
