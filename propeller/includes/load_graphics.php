<?php
include ("page_bar.php");
include ("connect.php");
$connexionBIG = connectdatabaseBIG();

$activepage = 1;
if (isset($_POST['activepage'])) {
	$activepage = $_POST['activepage'];
}
include("create_request.php");
$POST_ARRAY=$_POST;
$request = create_request($POST_ARRAY,0);
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));

$protein_id=0;
$content_blades="protein_name\tblades\tscore\n";
while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
	$protein_id++;
	$results_array[$protein_id]['protein_id']=$row['protein_id'];
	$results_array[$protein_id]['name']=$row['name'];
	$results_array[$protein_id]['species']=$row['species'];
	$results_array[$protein_id]['superkingdom']=$row['superkingdom'];
	$results_array[$protein_id]['kingdom']=$row['kingdom'];
	$results_array[$protein_id]['phylum']=$row['phylum'];
	$results_array[$protein_id]['nbdomain']=$row['nbdomain'];
	$results_array[$protein_id]['domain']=$row['domain'];
}
if($protein_id == 0){
	exit("No results with current filters");
}

//DISPLAY graphic_domain
echo "<div style='width:100%;display:inline-block;'>";
echo "<div style='width:49%;height:360px;display:inline-block;margin-bottom:50px;margin-right:5px;float:left;'>";
include("graphic_domain.php");
echo "</div>";
//DISPLAY graphic_sunburst
echo "<div style='width:49%;height:360px;display:inline-block;margin-bottom:50px;float:left;margin-right:5px;'>";
include("graphic_sunburst.php");
echo "</div>";
echo "</div>";
//DISPLAY graphic_nbblade
echo "<div style='width:100%;height:auto;display:inline-block;margin-bottom:20px;'>";
include("graphic_nbblade_large.php");
echo "</div>";
//DISPLAY TREEGRAPH
echo "<div id='tree-container_container' style='width:100%;height:260px;display:inline-block;margin-bottom:50px;'>";
?>
<div class="div-border-title">
	Taxonomic tree
</div>
<div class="div-border" id="tree-container" style="width:100%; height:auto;display:inline-block;">
</div>
<?php
include("graphic_treeview_data.php");
include("graphic_treeview_large.php");
echo "</div>";
?>
<script>
	function resize_tree(){
		var divc = $('#tree-container_container');
		var div = $('#tree-container');
		div.html("");
		if(divc.width() > 500){
			divc.width(480);
			var xhr = new XMLHttpRequest();
			xhr.open('GET', './includes/graphic_treeview.php');
							 xhr.addEventListener('readystatechange', function() {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					div.html(xhr.responseText);
				}
			});
			xhr.send(null);
		}else{
			divc.width(980);
			var xhr = new XMLHttpRequest();
			xhr.open('GET', './includes/graphic_treeview_large.php');
							 xhr.addEventListener('readystatechange', function() {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					div.html(xhr.responseText);
				}
			});
			xhr.send(null);
		}
	}
</script>
