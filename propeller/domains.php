<?php include($_SERVER['DOCUMENT_ROOT']."/propeller/header.php");

?>
<script src="/js/architecture_simplified.js" xmlns:right></script>
<script src="/js/d3.v3.min.js"></script>
<script src='/js/msaViewer.js' charset='utf-8'></script>
<script src='/js/binding_site_viewer.js' charset='utf-8'></script>
<link rel="stylesheet" href="/css/nouislider.min.css">
<script src="/js/nouislider.min.js"></script>
<title>Domain architectures search</title>

    <center>
        <label class="title_main" href='./'>PropLec architectures</label>
    </center>
<script>
	function scroll_to_div(id){
		var divPosition = $('#'+id).offset().top - $('#header').height() ;
		divPosition = $('#content_scroll').scrollTop() + divPosition;
		$('#content_scroll').animate({scrollTop: divPosition}, "slow");
	}
	function new_search(){
		$("#activepage").val(1);
		load_page();
	}
	function load_page(){
		$('#results_div').html("");
		$('#results_div').append("loading");
		$.post("./includes/domain.php", $('#search').serialize(),
					 function(data,status){
			$("#results_div").html("");
			$('#results_div').append(data);
		}
					);
	}
</script>

<div class="div-border-title">Filters</div>
<div class="div-border" style="width: 100%;padding:5px;">
	<form action="javascript:void(0);" method="post" id="search" name="search"  autocomplete="off">
		<input class="form-control" type="hidden" id="activepage"
					 name="activepage" min="0" max="100" value="1"
					 style="width: 20em; height: 30px; text-align: right; float: right">
		<div style="width: 33%;display: inline-block;">
			<label style="width: 20em;">domain
				<input list='domain_list' type='text' id='domain' name="domain" style="width: 100%; height: 30px;padding-top:5px" value="<?php if(isset($_GET['domain'])){echo $_GET['domain'];} ?>">
			</label>
			<?php
			$request = "SELECT distinct(domain) FROM propeller_view ";
			$request .= "ORDER BY domain ";
			$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
			echo "<datalist name='domain_list' id='domain_list'  onchange=\"domain_refresh(this.value);\">";
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo "<option value='{$row['domain']}'>";
			}
			echo "</datalist>";
			?>
		</div>
		<div style="width: 33%;display: inline-block;">
			<label style="width: 20em;">pfam
				<input list='pfam_list' type='text' id='domain_pfam' name="domain_pfam" style="width: 100%; height: 30px;padding-top:5px">
			</label>
			<?php
			$request = "SELECT distinct(pfam_name) FROM propeller_view ";
			$request .= "ORDER BY pfam_name ";
			$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
			echo "<datalist name='pfam_list' id='pfam_list'  onchange=\"pfam_refresh(this.value);\">";
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                $pfam_list = explode(', ', $row['pfam_name']);
                foreach($pfam_list AS $k => $pfam) {
                    echo "<option value='$pfam'>";
                }
			}
			echo "</datalist>";
			?>
		</div>
		<div style="width: 33%;display: inline-block;">
			<label style="width: 20em;">Min similarity score <input
																															class="form-control" type="number" id="similarity_score"
																															name="similarity_score" min="0" max="1" value="0.25" step=".01"
																															style="width: 20em; height: 30px; text-align: right; float: right">
			</label>
		</div>
	<button id="load_tree_viewer" class="btn-primary"
					style="width: 100%;height:40px;font-size:20px;" onclick="new_search();">Load matching architectures</button>

	</form>
<input id="protein_id" name="protein_id" type="hidden" value="" >
</div>

<div id="results_div" name="results_div"
		 style='width: 100%; margin-top: 10px;'></div>
<script>
//load_page();
</script>
<?php include($_SERVER['DOCUMENT_ROOT']."/propeller/footer.php"); ?>