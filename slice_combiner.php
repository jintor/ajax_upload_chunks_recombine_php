<?php

if ( isset($_REQUEST) ) {

	$pcache = clean( $_REQUEST['pcache'] ?? '' ) ;
	$thismedia_id = clean( $_REQUEST['thismedia_id'] ?? '' ) ;
	$thismedia_name = accentx(clean( $_REQUEST['thismedia_name'] ?? '' ),'unlock');
	$thismedia_ext = exiftensionx(['exif_file'=>$thismedia_name]);
	$numberofchunks = clean( $_REQUEST['numberofchunks'] ?? '' ) ;
	$thischunk_number = clean( $_REQUEST['thischunk_number'] ?? '' ) ;
	$thisfilex = clean( $_REQUEST['thisfilex'] ?? '' ) ;
	$howmanyfiles = clean( $_REQUEST['howmanyfiles'] ?? '' ) ;
	$thissession = clean( $_REQUEST['thissession'] ?? '' ) ;

	if ( $thissession != '' ) {

		// PATH
		$received_chunk_dir = '/temporary_path_here_you_receiver_chunks/'.$thissession;
		$received_final_path = $received_chunk_dir.'/'.$thismedia_id.'/'.$thismedia_id.'.'.$thismedia_ext;

		// GET OF SORTED FILES -V because chunk_1, chunk_2, ...., chunk_9, chunk_10, chunk_11 ==> dont want chunk_1,chunk_10,chunk_2 but chunk_1,chunk_2,...,chunk_10
		$all_chunks_raw = shell_exec('ls '.$received_chunk_dir.'/'.$thismedia_id.'/* | sort -V');
		
		if ( $all_chunks_raw != '' ) {

			// GET LS OF ALL CHUNKS
			$all_chunks_explo = array_filter(explode(PHP_EOL,$all_chunks_raw));
	
			// IF ONLY 1 CHUNK JUST RENAME
			if ( count($all_chunks_explo) == 1 ) { rename($all_chunks_explo[0],$received_final_path); @unlink($all_chunks_explo[0]); }
			else {
				// RECOMBINE ALL CHUNKS WITH FOPEN FREAD chunksize = 1024 * 1024 * 9 = 9437184 from javascript HAS TO BE THE SAME VALUE
				foreach ( $all_chunks_explo as $chunkey => $chunx ){    
					$file = fopen($chunx, 'rb'); $buff = fread($file, 9437184); fclose($file);
					$final = fopen($received_final_path, 'ab'); $write = fwrite($final, $buff); fclose($final);
				}
				// DELETE CHUNKS AFTER COMBINE
				shell_exec('ls '.$received_chunk_dir.'/'.$thismedia_id.' -name "*_chunk_*" -delete');
			}

		}

		// HERE YOU CAN FFMPEG, IMAGEMAGICK, ETC TO CONVERT TO WEB COMPATIBLE FORMAT

		// HERE YOU CAN SEND FILES TO S3 BUCKET (services like filebase.com)

		// DELETE FILE AFTER SENDING TO S3 BUCKET IF YOU NEED TO CLEAR SPACE

		echo '<script>$("#phpz_'.$thismedia_id.'_message").append(" ---COMBINATION DONE---");</script>';

	}

}
