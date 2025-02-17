<script>
  function load_glycoct(div,div_pic,iupac){
    $.ajax({
      url:"https://glyconnect.expasy.org/api/structures/translate/iupac/glycoct",
      type:"POST",
      async: false,
      data:JSON.stringify({"iupac":iupac,"glycanType":""}),
      dataType: 'json',
      contentType:"application/json",
      complete: function(data) {
        if(data.responseText.indexOf("problem") >= 0) {
          return;
        }
        $('#'+div).html(data.responseText);
        load_glycoct_svg(div_pic,div);
        $('#container_'+div).css('display','inline-block');
      }
    });
  }
  function load_glycoct_svg(div,div_glycoct){
    var element = document.getElementById(div_glycoct);
    var glycoct = element.innerHTML;
    $.ajax({
      url:"https://glyconnect.expasy.org/api/structures/cartoon",
      type:"POST",
      data:JSON.stringify({"glycoct":glycoct,"notation":"cfg","format":"svg"}),
      dataType: 'json',
      contentType:"application/json",
      complete: function(data) {
        if(data.responseText.indexOf("error") >= 0) {
          $('#'+div).html("No image");
          return;
        }
        $('#'+div).html(data.responseText);
        var width = $('#'+div).find("rect")[0].attributes.width.value;
        var height = $('#'+div).find("rect")[0].attributes.height.value;
        $('#'+div).width(width*0.8);
        $('#'+div).height(height*0.8);
        $('#'+div).find("svg")[0].setAttribute('width', width);
        $('#'+div).find("svg")[0].setAttribute('height', height);
        $('#'+div).find("svg")[0].setAttribute('style', "fill-opacity:1; color-rendering:auto; color-interpolation:auto; text-rendering:auto; stroke:black; stroke-linecap:square; stroke-miterlimit:10; shape-rendering:auto; stroke-opacity:1; fill:black; stroke-dasharray:none; font-weight:normal; stroke-width:1; font-family:'Dialog'; font-style:normal; stroke-linejoin:miter; font-size:12; stroke-dashoffset:0; image-rendering:auto;transform:scale(0.8);-webkit-transform-origin: 0 0;");
      }
    });
  }
</script>

<?php
$pdb_link="";
$unidb = "uniprot";
if(strpos($row['uniprot'],"UPI") !== false){
	$unidb = "uniprot";
}
echo "<div class='div-border-title' style='height:33px;'>{$row['uniprot']}<div style='width:130px;float:right;' class='sharethis-inline-share-buttons'></div>";
echo "<a class='btn btn-primary' style='width:150px;float:right;' role='button' href='http://www.uniprot.org/$unidb/{$row['uniprot']}' target='_blank'>Uniprot</a>";
echo "</div>";
?>
<script src="//platform-api.sharethis.com/js/sharethis.js#property=5b6057db78eb8b00113e3405&product=inline-share-buttons"></script>
<?php
echo "<div class='div-border' style='margin-bottom:20px;display: inline-block;'>";
?>
<div class='div-border' style='display: inline-block;'>
    <div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
        <span  class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">fold</span><input readonly type="text" value="<?php echo $row['fold'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
        <span  class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">class</span><input readonly type="text" value="<?php echo $row['class'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
        <span  class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">family</span><input readonly type="text" value="<?php echo $row['family'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
        <span  class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">origin</span><input readonly type="text" value="<?php echo $row['origin'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid lightgrey;height:20px;"  class="input-group">
        <span  class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">species</span><input readonly type="text" value="<?php echo $row['species'];?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <?php
    $pdb_list_link = "";
    $pdb_list = "";
    $related_pdb_request = "SELECT pdb, ligand FROM lectin_view WHERE uniprot = '{$row['uniprot']}' ORDER BY pdb";
    $related_pdb_results = mysqli_query($connexion, $related_pdb_request) or die ("SQL Error:<br>$related_pdb_request<br>" . mysqli_error($connexion));
    while ($related_pdb_row = mysqli_fetch_array($related_pdb_results, MYSQLI_ASSOC)) {
        $pdb = $related_pdb_row['pdb'];
        $ligand = $related_pdb_row['ligand'];
        if ($ligand == '') {
            $ligand = "no ligand";
        }
        $ligand = substr($ligand, 0, 30);
        $pdb_list_link .= "<a class='btn btn-primary' style='width:auto;' href='https://unilectin.eu/unilectin3D/display_structure?pdb=$pdb'>$pdb : $ligand</a>";
        $pdb_list .= $pdb . " ";
    }
    ?>
    <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
        <span class='input-group-addon' type="text" style="height: 20px;width:140px;padding:0;font-weight: bold;">available structures</span>
        <div style="border:none;height:60px;overflow-x:auto; text-align: left;background-color: white;"><?php echo $pdb_list_link; ?></div>
    </div>
    <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
        <span class='input-group-addon' type="text" style="height: 20px;width:140px;padding:0;font-weight: bold;">related proteins<br>> 50% identity</span>
        <div id="related_50" style="border:none;width: 100%; height:60px;overflow-x:auto;text-align: left;background-color: white;">
            <?php
            $content = file_get_contents("https://www.uniprot.org/uniref/?fil=identity:0.5&sort=score&format=tab&query=" . $row['uniprot']);
            $lines = explode("\n", $content);
            $info = explode("\t", $lines[1])[4];
            $info = explode("; ", $info);
            foreach ($info as $protein) {
                $unidb = "uniprot";
                if (strpos($protein, "UPI") !== false) {
                    $unidb = "uniprot";
                }
                if ($protein != '') {
                    echo "<a class='btn btn-success' style='width:150px;' role='button' href='http://www.uniprot.org/$unidb/$protein' target='_blank'>$protein</a>";
                }
            }
            ?>
        </div>
    </div>
</div>
<?php

//DISPLAY CARBOHYDRATES
$related_pdb_request="SELECT DISTINCT(iupac) FROM lectin_view WHERE uniprot = '{$row['uniprot']}' ORDER BY iupac";
$related_pdb_results = mysqli_query ( $connexion, $related_pdb_request ) or die ( "SQL Error:<br>$related_pdb_request<br>" . mysqli_error ( $connexion ) );
$sugarid=0;
$id = $row['uniprot'];
echo "<div style='width:100%;display: inline-block;margin-bottom:1px;'>";
while($related_pdb_row = mysqli_fetch_array ( $related_pdb_results, MYSQLI_ASSOC )){
  if($related_pdb_row['iupac'] != ""){
    $sugar_iupac=$related_pdb_row['iupac'];
      echo "<div style='float:right;margin-right:5px;padding:3px;border:1px solid black;border-radius:5px;'><img src='../../templates/svg/$sugar_iupac.svg'></div>";
  }
}
echo "</div>";

//DISPLAY UNIPROT PROTEIN VIEWER
$id = $row['uniprot'];
echo "<label>UniProt viewer</label>";
echo "<div id='display_$id' style='width:100%'>";
echo "<script>
							var yourDiv_$id = document.getElementById('display_$id');
							var instance_$id = new ProtVista({
									el: yourDiv_$id,
									uniprotacc: '$id',
									defaultSources: true,
				 });
				 </script>";
echo "</div>";

//DISPLAY UNIPROT PDB VIEWER
echo "<label>PDBe viewer</label>";
echo "<pdb-uniprot-viewer entry-id='{$row['uniprot']}' height='300' width='980'></pdb-uniprot-viewer>";
?>
<script>
  angular.element(document).ready(function () {
    angular.bootstrap(document, ['pdb.uniprot.viewer']);
  });
</script>
<?php

$biblio_request="SELECT * FROM lectin_view WHERE uniprot = '{$row['uniprot']}' GROUP BY title ORDER BY year DESC";
$biblio_results = mysqli_query ( $connexion, $biblio_request ) or die ( "SQL Error:<br>$biblio_request<br>" . mysqli_error ( $connexion ) );
while ( $biblio_row = mysqli_fetch_array ( $biblio_results, MYSQLI_ASSOC ) ) {
  if($biblio_row['year'] == '0'){
    $biblio_row['year']="";
  }
  echo "<div style='width: 100%;border:1px solid grey;height:20px;'  class='input-group'>";
  echo "<span  class='input-group-addon' type='text' style='height: 20px;width:130px;padding:0; '><label>Reference of<br>{$biblio_row['pdb']} structure<br>{$biblio_row['year']}</label>";
  echo "<br><a class='btn btn-success' style='width:50%' role='button' href='https://pubmed.ncbi.nlm.nih.gov/{$biblio_row['pubmed']}' target='_blank'>PubMed</a>";
  echo "</span>";
  
  echo "<div style='width: 100%;border:1px solid grey;height:90px;overflow-x:auto;'>";
  $reference = $biblio_row['authors'];
  $reference .= "<br>".$biblio_row['title'];
  $reference .= " ".$biblio_row['journal'];
  $reference .= " ".$biblio_row['volume'];
  $reference .= " ".$biblio_row['pages'];
  $reference .= " ".$biblio_row['year'];
  //$reference .= " ".$biblio_row['month'];
  //$reference .= " ".$biblio_row['day'];
  echo $reference;
  echo "</div></div>";
  echo "<meta name='reference' content=\"$reference\">";
}

echo "</div>";
?>

