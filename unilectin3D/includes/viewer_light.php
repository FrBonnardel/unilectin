<?php
$pdb_link="";
echo "<div class='div-border-title'>{$row['family']} {$row['species']} {$row['uniprot']}";
echo "<a href='https://unilectin.eu/unilectin3D/display_lectin?uniprot={$row['uniprot']}' target='_blank' style='float:right;font-size:14px;width:200px;height:30px;padding:5px;text-align:left;' class='btn btn-md btn-success'><span class='glyphicon glyphicon-resize-full' style='margin-left:20px;margin-right:20px;'></span>View information</a>";
echo "</div>";
echo "<div class='div-border' style='margin-bottom:20px;display: inline-block;'>";
?>
<div style="width: 50%;display:inline-block;">
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
  $pdb_list_link="";
  $related_pdb_request="SELECT pdb, ligand FROM lectin_view WHERE uniprot = '{$row['uniprot']}' ORDER BY pdb";
  $related_pdb_results = mysqli_query ( $connexion, $related_pdb_request ) or die ( "SQL Error:<br>$related_pdb_request<br>" . mysqli_error ( $connexion ) );
  while($related_pdb_row = mysqli_fetch_array ( $related_pdb_results, MYSQLI_ASSOC )){
    $pdb = $related_pdb_row['pdb'];
    $ligand = $related_pdb_row['ligand'];
    if($ligand==''){$ligand="no ligand";}
    $pdb_list_link .= "<a href='https://unilectin.eu/unilectin3D/display_structure?pdb=$pdb' target='_blank'>$pdb : $ligand</a><br>";
  }
  ?>
</div>
  <div style="width: 50%;float:right;border:1px solid lightgrey;height:20px;"  class="input-group">
    <span  class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">related structures</span><div style="border:none;height:108px;overflow-x:auto; text-align: left;"><?php echo $pdb_list_link;?></div>
  </div>
</div>


