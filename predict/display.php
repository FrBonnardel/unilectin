<script src="/js/architecture.js" xmlns:right></script>
<script src="/js/d3.v3.min.js"></script>
<script src='/js/msaViewer.js' charset='utf-8'></script>
<script src='/js/binding_site_viewer.js' charset='utf-8'></script>
<link rel="stylesheet" href="/css/nouislider.min.css">
<script src="/js/nouislider.min.js"></script>

<?php include($_SERVER['DOCUMENT_ROOT']."/predict/header.php");

?>

<style>

	.bar rect {
		shape-rendering: crispEdges;
	}
	.bar text {
		fill: #999999;
	}
	.axis path, .axis line {
		fill: none;
		stroke: #000;
		shape-rendering: crispEdges;
	}
</style>
<script>
	function load_lectin_viewer_details(protein_id){
		var button =$( "#button_load_details_"+protein_id );
		button.find('span').toggleClass('glyphicon-plus').toggleClass('glyphicon-minus');
		var div = $('#lectin_viewer_container_'+protein_id);
		if(button.hasClass( "btn-danger" )){
			button.removeClass("btn-danger");
			button.addClass("btn-success");
			div.html("");
		}
		else{
			var xhr = new XMLHttpRequest();
			xhr.open('GET', './includes/viewer_details.php?protein_id='+protein_id);
			xhr.addEventListener('readystatechange', function() {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					div.html("");
					div.append(xhr.responseText);
					button.removeClass("btn-success");
					button.addClass("btn-danger");
				}
			});
			xhr.send(null);
		}
	}
</script>


<?php
if($_GET['protein_id']==""){
  exit("protein ID unset");
}

include("./includes/create_request.php");
//$POST_ARRAY=$_POST;
$link = "./display?protein_id={$_GET['protein_id']}&domains_id=";
?>
<div style="float:right;margin-right:10px" class="input-group">
    <span class="input-group-addon" style="width: 280px; height: 30px;padding-top:6px;font-size:16px;float:left;color:black" >Change the lectin class displayed</span>
    <select class="input-group-input" name="family" id="family" style="color:black;width: 200px; height: 30px;line-height: inherit;font-size:16px;float:right;" onchange="window.open('<?php echo $link; ?>'+this.value,'_self');">
        <?php
        $request = create_request(null,$_GET['protein_id'],null);
        $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
        while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
            echo "<option value='{$row['domains_id']}' ";
            if($_GET['domains_id'] == $row['domains_id']){echo " selected ";}
            echo ">{$row['domain']}</option>";
        }
        ?>
    </select>
</div>
<?php
//
$request = create_request(null,$_GET['protein_id'],$_GET['domains_id']);
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$id = $row['protein_id'];

echo "<title>{$row['uniprot']} identified as {$row['domain']} in LectomeXplore</title>";

$load_pfam = 1;
include("./includes/viewer.php");
include("./includes/viewer_details.php");
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/predict/footer.php"); ?>