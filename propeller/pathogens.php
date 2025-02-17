<?php include($_SERVER['DOCUMENT_ROOT']."/propeller/header.php"); ?>
<div class="div-border-title">
	Lectins of possible pathogen 
	<div style="float:right;margin-right:10px" class="input-group">
		<span class="input-group-addon" style="width: 100px; height: 30px;padding-top:6px;font-size:16px;float:left;color:black" >Order by</span>
		<select class="input-group-input" name="family" id="family" style="color:black;width: 200px; height: 30px;line-height: inherit;font-size:16px;float:right;" onchange="window.open('./pathogens?family='+this.value,'_self');">
			<option selected value="">All families</option>
			<option <?php if($_GET['family'] == "PropLec5A_tachy"){echo "selected";} ?>>PropLec5A_tachy</option>
			<option <?php if($_GET['family'] == "PropLec6A_RSL_AAL"){echo "selected";} ?>>PropLec6A_RSL_AAL</option>
			<option <?php if($_GET['family'] == "PropLec6B_tectonin"){echo "selected";} ?>>PropLec6B_tectonin</option>
			<option <?php if($_GET['family'] == "PropLec7A_PLL"){echo "selected";} ?>>PropLec7A_PLL</option>
			<option <?php if($_GET['family'] == "PropLec7B_PVL"){echo "selected";} ?>>PropLec7B_PVL</option>
		</select>
	</div>
</div>

<?php 
$rwhere = "";
if($_GET['family'] != ""){
	$rwhere = " AND domain ='".$_GET['family']."' ";
}
include($_SERVER['DOCUMENT_ROOT']."/predict/includes/pathogen_species_array.php");
$rwhere .= " AND (";
foreach ($pathogen_species as $species) {
    $rwhere .= " species LIKE '$species%' OR ";
}
$rwhere = rtrim($rwhere, "OR ");
$rwhere .= " ) ";


echo "<table style='width: 100%;margin-top:30px;' class='manage_tables'>";
echo "<thead><tr>";
echo ("<td>species</td>");
echo ("<td>nbprot</td>");
echo ("<td>protein</td>");
echo ("<td>name</td>");
echo ("<td>domain</td>");
echo ("<td>gene</td>");
echo ("<td>score</td>");
echo ("<td>nbdomain</td>");
echo ("<td>action</td>");
echo "</tr></thead>";
echo "<tbody>";

$request = "SELECT protein.protein_id, COUNT(protein.protein_id) AS nbprot, cluster, name, nbdomain, type, length, species, gene, gene_begin, gene_end, domain, score 
FROM propeller_view WHERE cluster != '' $rwhere GROUP BY cluster, species ORDER BY species, score DESC ";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
	echo "<tr>";
	echo "<td>{$row['species']}</td>";
	echo "<td>{$row['nbprot']}</td>";
	echo "<td>{$row['protein']}</td>";
	echo "<td>{$row['name']}</td>";
	echo "<td>{$row['domain']}</td>";
	echo "<td>{$row['gene']} [{$row['gene_begin']}-{$row['gene_end']}]</td>";
	echo "<td>{$row['score']}</td>";
	echo "<td>{$row['nbdomain']}</td>";
	echo "<td><button style='width:60px;height:30px;padding:5px;float:right;' class='btn btn-md btn-success' onclick=\"window.open('/propeller/display?protein_id={$row['protein_id']}')\"><span class='glyphicon glyphicon-resize-full'></span></button></td>";
	echo "</tr>";
}
echo "</tbody>";
echo "</table>";

?>
<?php include($_SERVER['DOCUMENT_ROOT']."/propeller/footer.php"); ?>