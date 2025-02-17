<?php
$id = $row['lectin_id'];
echo "<div class='div-border-title'>";
echo "{$row['pdb']} {$row['family']} {$row['species']}";
echo "<a href='https://unilectin.eu/unilectin3D/display_structure?pdb={$row['pdb']}' target='_blank' style='float:right;font-size:14px;width:330px;height:30px;padding:5px;text-align:left;' class='btn btn-md btn-success'><span class='glyphicon glyphicon-resize-full' style='margin-left:20px;margin-right:20px;'></span>View the 3D structure and information</a>";
echo "</div>";
echo "<div class='div-border' style='margin-bottom:20px;display: inline-block;'>";
?>
    <div style='width:50%;display: inline-block;'>
        <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
            <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">fold</span><input readonly type="text" value="<?php echo $row['fold']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
        </div>
        <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
            <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">class</span><input readonly type="text" value="<?php echo $row['class']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
        </div>
        <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
            <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">family</span><input readonly type="text" value="<?php echo $row['family']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
        </div>
        <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
            <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">resolution (&Aring;)</span><input readonly type="text" value="<?php echo $row['resolution']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
        </div>
    </div>
    <div style='width:50%;display: inline-block;float:right;'>
        <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
            <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">origin</span><input readonly type="text" value="<?php echo $row['origin']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
        </div>
        <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
            <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">species</span><input readonly type="text" value="<?php echo $row['species']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;font-style:italic; ">
        </div>
        <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
            <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">comments</span><input readonly type="text" value="<?php echo $row['comments']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
        </div>
        <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
            <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">IUPAC condensed</span><input readonly type="text" value="<?php echo $row['iupac']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
        </div>
    </div>
<?php
echo "<div style='width:100%;display: inline-block;margin-bottom:1px;'>";
if (isset($row['species_image'])) {
    $filepath = $row['species_image'];
    echo "<div id='pic_{$row['lectin_id']}' onclick=\"maximise_image(this.id)\" style='background:url(\"/glyco3d/$filepath\");background-size: contain;background-repeat:no-repeat;background-position:top center;width:150px;height:100px;float:left;'></div>";
}
$file_request = "SELECT * FROM file WHERE file.lectin_id = {$row['lectin_id']}";
$file_results = mysqli_query($connexion, $file_request) or die ("SQL Error:<br>$file_request<br>" . mysqli_error($connexion));
while ($file_row = mysqli_fetch_array($file_results, MYSQLI_ASSOC)) {
    $filepath = $file_row['filepath'];
    if (pathinfo($filepath, PATHINFO_EXTENSION) != "pdb") {
        echo "<div id='pic_{$file_row['file_id']}' onclick=\"maximise_image(this.id)\" style='background:url(\"/glyco3d/$filepath\");background-size: contain;background-repeat:no-repeat;background-position:top center;width:150px;height:100px;float:left;'></div>";
    }
}
if ($row['sugar_sequence'] != "") {
    $sugar_iupac = $row['iupac'];
    echo "<div id = 'glycoct_png_{$id}' style='float:right;margin-right:5px;'><img src='../../templates/png/$sugar_iupac.png'></div>";
}
echo "</div>";
echo "</div>";