<?php include($_SERVER['DOCUMENT_ROOT']."/propeller/header.php"); ?>

<?php
if($_GET['protein_id']==""){
    exit("protein ID unset");
}
$uniparc = $_GET['uniparc'];
include("./includes/create_request.php");
//$POST_ARRAY=$_POST;
$request = create_request(null,$_GET['protein_id']);
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
?>

<title><?php echo $row['name']; ?> protein in PropLec - UniLectin</title>

<script src="/js/architecture.js" xmlns:right></script>
<script src="/js/d3.v3.min.js"></script>
<script src='/js/msaViewer.js' charset='utf-8'></script>
<script src='/js/binding_site_viewer.js' charset='utf-8'></script>
<link rel="stylesheet" href="/css/nouislider.min.css">
<script src="/js/nouislider.min.js"></script>


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
include("./includes/viewer.php");
$id = $row['protein_id'];
echo "<script>$('#button_load_details_$id').click();</script>";
?>
<?php include($_SERVER['DOCUMENT_ROOT']."/propeller/footer.php"); ?>