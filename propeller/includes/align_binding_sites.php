<?php
function align_binding_sites($query_sequences, $domain, $domain_file)
{
	$query_sequences = explode(",", $query_sequences);
	$lengths = array_map('strlen', $query_sequences);
	$length = max($lengths);
	$file_path = join(DIRECTORY_SEPARATOR, array(
		$_SERVER['DOCUMENT_ROOT'],
		'domain',
		$domain_file
	));
	if (! file_exists($file_path)) {
		echo ("file not found : $file_path");
		return ($file_path);
	}
	$file = fopen($file_path, "r");
	$binding_sites = array();
	while (! feof($file)) {
		$line = rtrim(fgets($file));
		if ($line != "") {
			if(explode(" ",$line)[0]==$domain){
				$tmp = explode(" ",$line);
				$binding_sites[$tmp[3]] = $tmp[4];
			}
		}
	}
	fclose($file);
	$count=0;
  $binding_sites_aligned=[];
  foreach ($query_sequences as $aligned_blade){
		$blade = str_replace([".","_","-"], "", $aligned_blade);
    $binding_sites_aligned[$blade] = "";
  }
	foreach ($query_sequences as $aligned_blade){
		$blade = str_replace([".","_","-"], "", $aligned_blade);
		if( array_key_exists($blade, $binding_sites)){
      $count++;
      $index=0;
      for ($i=0; $i<$length; $i++) {
        $char = "-";
        if($i<strlen($aligned_blade)){
          $char = $aligned_blade[$i];
        }
        if($char != "." && $char != "-"){
          $binding_sites_aligned[$blade] .= $binding_sites[$blade][$index];
          $index++;
        }else{
          $binding_sites_aligned[$blade] .= '-';
        }
      }
		}
	}
	return($binding_sites_aligned);
}
?>