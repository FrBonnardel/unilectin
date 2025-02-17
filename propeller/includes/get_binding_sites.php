<?php
function get_binding_sites($query_sequences, $match_sequences, $domain, $domain_file)
{
	$query_sequences = explode(",", $query_sequences);
	$match_sequences = explode(",", $match_sequences);
	$lengths = array_map('strlen', $match_sequences);
	$length = max($lengths);
	$file_path = join(DIRECTORY_SEPARATOR, array(
		$_SERVER['DOCUMENT_ROOT'],
		'domain',
		$domain_file
	));
	if (! file_exists($file_path)) {
		echo ("file not found : $file_path");
		return (- 1);
	}
	$proteins = array();
	$file = fopen($file_path, "r");
	$content = "";
	$i = 0;
	$blade = "";
	$binding_sites = array();
	$ligands = array();
	$ligands['ALL']=1;
	while (! feof($file)) {
		$line = rtrim(fgets($file));
		if ($line != "") {
			if(explode(" ",$line)[0]==$domain){
				$tmp = explode(" ",$line);
				$binding_sites[$tmp[3]][$tmp[2]] = $tmp[4];
				$ligands[$tmp[2]]=1;
			}
		}
	}
	fclose($file);
	$binding_sites_aligned = array();
	foreach($ligands as $ligand => $v){
		$binding_sites_aligned[$ligand] = array();
		for ($i=0; $i<$length; $i++) {
			$binding_sites_aligned[$ligand][$i] = array();
		}
	}
	$count=0;
	foreach ($query_sequences as $aligned_blade){
		$blade = str_replace([
			".",
			"_",
			"-"
		], "", $aligned_blade);
		if( array_key_exists($blade, $binding_sites)){
			foreach($ligands as $ligand => $v){
				$count++;
				$index=0;
				for ($i=0; $i<$length; $i++) {
					$char = "-";
					if($i<strlen($aligned_blade)){
						$char = $aligned_blade[$i];
					}
					if($char != "." && $char != "-"){
						if($binding_sites[$blade][$ligand][$index] == "*"){
							$binding_sites_aligned[$ligand][$i][$char] += 1;
							$binding_sites_aligned['ALL'][$i][$char] += 1;
						}
						$index++;
					}
				}
				//echo "<pre>",print_r($binding_sites_aligned[$ligand]),"</pre>";
			}
		}
	}
	$binding_sites=[];
	foreach($ligands as $ligand => $v){
		$binding_sites[$ligand]="";
	}
	foreach($ligands as $ligand => $v){
		for ($i=0; $i<$length; $i++) {
			if(isset($binding_sites_aligned[$ligand])){
				if(isset($binding_sites_aligned[$ligand][$i])){
					$maxaa = array_keys($binding_sites_aligned[$ligand][$i], max($binding_sites_aligned[$ligand][$i]));
					$nbsites = $binding_sites_aligned[$ligand][$i][$maxaa];
					if($nbsites >= 1){
						$binding_sites[$ligand].=$maxaa;
					}
					else{
						$binding_sites[$ligand].="-";
					}
				}
			}
		}
	}
  ksort($binding_sites);
	return($binding_sites);
}
?>