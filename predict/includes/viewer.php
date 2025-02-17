<?php
//include ("connect.php");
//$connexionBIG = connectdatabase();
//$row = unserialize(base64_decode($_POST['row']));
$id = $row['protein_id'];
$uniprot = "<a target='_blank' href='http://www.uniprot.org/uniprot/{$row['uniprot']}'>{$row['uniprot']}</a>";
$ncbi = "<a target='_blank' href='https://www.ncbi.nlm.nih.gov/protein/{$row['alt_ac']}'>{$row['alt_ac']}</a>";
echo "<div class='div-border-title' id='lectin_viewer_main_container_$id' style='margin-top:10px;display: inline-block;'>{$row['protein_ac']}";
echo "<a style='width:60px;height:30px;padding:5px;float:right;' class='btn btn-md btn-success' target='_blank' href='./display?protein_id={$row['protein_id']}&domains_id={$row['domains_id']}'><span class='glyphicon glyphicon-resize-full'></span></a>";
echo "</div>";
echo "<div class='div-border' style='display: inline-block;width:100%;'>";
$chromozome = "<a target='_blank' href='https://www.ncbi.nlm.nih.gov/nuccore/{$row['gene']}'>{$row['gene']}</a>";
?>
<div style="width: 50%;display: inline-block;">
	<div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
		<span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; "><label>NCBI protein</label></span><div style="padding:2px;background-color:white;border:none;width: 100%; height: 20px; text-align: left;"><?php echo $ncbi; ?></div>
	</div>	
	<div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
		<span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; "><label>UniProt</label></span><div style="padding:2px;background-color:white;border:none;width: 100%; height: 20px; text-align: left;"><?php echo $uniprot; ?></div>
	</div>	
	<div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
		<span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; "><label>length</label></span><input readonly type="text" value="<?php echo $row['length'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
	</div>	
	<div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
		<span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; "><label>species</label></span><input readonly type="text" value="<?php echo $row['species'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
	</div>
    <div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
        <span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; "><label>strain : assembly</label></span><input readonly type="text" value="<?php echo $row['strain'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
		<span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; "><label>definition</label></span><input readonly type="text" value="<?php echo $row['name'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
	</div>	
</div>
<div style="width: 50%;display: inline-block;float:right">
	<div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
		<span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; "><label>lectin class</label></span><input readonly type="text" value="<?php echo $row['domain'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
	</div>
	<div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
		<span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; " title="sequence similarity to the reference [0->1]"><label>score</label></span><input readonly type="text" value="<?php echo $row['score'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
	</div>	
	<div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
		<span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; "><label>chz/contig</label></span><div style="padding:2px;background-color:white;border:none;width: 100%; height: 20px; text-align: left;"><?php echo $chromozome; ?></div>
	</div>
    <div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
        <span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; "><label>UniRef representative</label></span><input readonly type="text" value="<?php echo $row['cluster'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
        <span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; "><label>Pfam</label></span><input readonly type="text" value="<?php echo $row['pfam_name'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
        <span  class='input-group-addon' type="text" style="height: 20px;width:150px;padding:0; "><label>Cazy</label></span><input readonly type="text" value="<?php echo $row['cazy'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
</div>
	<?php
	// CREATE DOMAIN PLOT FILE
	$services_annot = array ();
	$request_domain = "SELECT domain, begin, end, hmm_begin, hmm_end FROM lectinpred_aligned_domains LEFT JOIN lectinpred_domain ON (lectinpred_domain.domains_id = lectinpred_aligned_domains.domains_id) ";
	$request_domain .= "WHERE protein_id = '{$row['protein_id']}' ORDER BY domain, begin ";
	$results_domain = mysqli_query($connexionBIG, $request_domain) or die("SQL Error:<br>$request_domain<br>" . mysqli_error($connexionBIG));
	while ($row_annot = mysqli_fetch_array($results_domain, MYSQLI_ASSOC)) {
		$annotation = array ();
		$annotation['service'] = $row_annot['domain'];
		$annotation['name'] = $row_annot['domain'] .' HMM['. $row_annot['hmm_begin'] .':'. $row_annot['hmm_end'] . ']';
		$annotation['description'] = "";
		$annotation['begin'] = $row_annot['begin'];
		$annotation['end'] = $row_annot['end'];
		array_push($services_annot,$annotation);
	}
	if ($load_pfam){
        $request_domain = "SELECT name, pfam, begin, end FROM lectinpred_pfam pfam LEFT JOIN lectinpred_pfam_domain pfamdo ON (pfam.pfam_entry_id = pfamdo.pfam_entry_id) ";
        $request_domain .= " WHERE protein_id = '{$row['protein_id']}' $pfam_exclude_rwhere ORDER BY name, begin ";
        $results_domain = mysqli_query($connexionBIG, $request_domain) or die("SQL Error:<br>$request_domain<br>" . mysqli_error($connexionBIG));
        while ($row_annot = mysqli_fetch_array($results_domain, MYSQLI_ASSOC)) {
            $annotation = array ();
            $annotation['service'] = "PFAM";
            $annotation['name'] = $row_annot['name'].' '.$row_annot['pfam'];
            $annotation['description'] = "";
            $annotation['begin'] = $row_annot['begin'];
            $annotation['end'] = $row_annot['end'];
            array_push($services_annot,$annotation);
        }
    }
if ($load_pfam){
    $request_domain = "SELECT name, begin, end FROM lectinpred_cazy cazy  WHERE protein_id = '{$row['protein_id']}' ORDER BY name, begin ";
    $results_domain = mysqli_query($connexionBIG, $request_domain) or die("SQL Error:<br>$request_domain<br>" . mysqli_error($connexionBIG));
    while ($row_annot = mysqli_fetch_array($results_domain, MYSQLI_ASSOC)) {
        $annotation = array ();
        $annotation['service'] = "CAZY";
        $annotation['name'] = $row_annot['name'];
        $annotation['description'] = "";
        $annotation['begin'] = $row_annot['begin'];
        $annotation['end'] = $row_annot['end'];
        array_push($services_annot,$annotation);
    }
}
	//COLOR
	$services_color = array ();
	$services_color['prop5_FLF']="#f4d800";
	$services_color['prop6_RVY']="#e53e00";
	$services_color['pro6_GVN']="#d60ddd";
	$services_color['prop7_EVF']="#42C0FB";
	$services_color['prop7_GFG']="#6ab975";
	$services_color['PFAM']="#ffaa00";

	//ENCODE
	$region_id=$row['domains_id'];
	$rowser = array ();
	$rowser['sequence'] = $row['sequence'];
	$rowser['length'] = $row['length'];
	$rowser['name'] = $row['name'];
	$rowser['protein_id'] = $row['protein_id'];
	foreach($rowser as $key => $value){
		$value = str_replace(' ', '_', $value);
		$value = str_replace('|', '_', $value);
		$rowser[$key] = preg_replace('/["\']/', '', $value);
	}
	$rowser = json_encode( $rowser);
	foreach($services_annot as $key => $value){
		$value = str_replace(' ', '_', $value);
		$value = str_replace('|', '_', $value);
		$services_annot[$key] = preg_replace('/["\']/', '', $value);
	}
	$services_annot_ser = json_encode( $services_annot);
	$services_color_ser = json_encode( $services_color );

	//STORE INFO
	echo "<div id='input_$region_id'>";
	echo "<input type='hidden' name='row' id='row_$region_id' value=$rowser>";
	echo "<input type='hidden' name='services_annot' id='services_annot_$region_id' value=$services_annot_ser>";
	echo "<input type='hidden' name='services_color' id='services_color_$region_id' value=$services_color_ser>";
	echo "</div>";

	// SVG SECTION
	$transcript_size=$row['length']*3;
	echo "<div id='slider_div_$region_id' style='width:100%;display:inline-block;'></div>";
	echo "<button name='button_$region_id' id='button_$region_id' class='button_$region_id' style='display:none;' onclick=\"create_svg('$region_id');\">Refresh SVG</button>";
	echo "<div id='svg_div_$region_id' style='margin:0px;padding:0px;overflow:hidden;overflow-y:auto;width:100%;max-height:30em;' onwheel=\"wheel_svg_zoom('$region_id','$transcript_size');\"></div>";
	echo "<script>";
	echo "		try {";
	echo "		create_slider('$region_id');";
	echo "		create_svg('$region_id');";
	echo "      }catch ( e ) {console.log(e);}";
	echo "</script>";
	//DETAILS DIV
	echo "<div id='lectin_viewer_container_$id' style='width:100%'></div>";
	echo "</div>";
	?>