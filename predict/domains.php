
<script src="../js/architecture_simplified.js" xmlns:right></script>
<script src="../js/d3.v3.min.js"></script>

<?php include($_SERVER['DOCUMENT_ROOT']."/predict/header.php"); ?>
<title>LectomeXplore architectures</title>
<center>
    <label class="title_main" href='./'>LectomeXplore architectures</label>
</center>
<p>This interface allows to search for combination of lectin domain(s) and Pfam domain(s)</p>
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
        <div style="width: 49%;float: left;">
		<div style="width: 100%;display: inline-block;">
			<label style="width: 100%;">domain
				<input list='domain_list' type='text' id='domain' name="domain" style="width: 100%; height: 30px;padding-top:5px" value="<?php if(isset($_GET['domain'])){echo $_GET['domain'];} ?>">
			</label>
			<?php
			$request = "SELECT distinct(domain) from lectinpred_aligned_domains ";
			$request .= "ORDER BY domain ";
			$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
			echo "<datalist name='domain_list' id='domain_list'  onchange=\"domain_refresh(this.value);\">";
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo "<option value='{$row['domain']}'>";
			}
			echo "</datalist>";
			?>
		</div>
		<div style="width: 100%;display: inline-block;">
			<label style="width:100%;">pfam
				<input list='pfam_list' type='text' id='domain_pfam' name="domain_pfam" style="width: 100%; height: 30px;padding-top:5px">
			</label>
			<?php
			$request = "SELECT distinct(name) from lectinpred_pfam ORDER BY name ";
			$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
			echo "<datalist name='pfam_list' id='pfam_list'  onchange=\"pfam_refresh(this.value);\">";
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo "<option value='{$row['name']}'>";
			}
			echo "</datalist>";
			?>
		</div>
		<div style="width: 100%;display: inline-block;">
			<label style="width: 100%;">Min similarity score <input
																															class="form-control" type="number" id="similarity_score"
																															name="similarity_score" min="0" max="1" value="0.25" step=".01"
																															style="width:100%; height: 30px; text-align: right; ">
			</label>
        </div>
        </div>
        <div style="width: 49%;float:right;">
        <div style="width: 100%;">
            <label style="width: 100%;">Superkingdom
                <input list='superkingdom_list' type='text' id='superkingdom' name="superkingdom" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_POST['superkingdom'])) {
                    echo $_POST['superkingdom'];
                } ?>">
            </label>
            <?php
            $request = "SELECT distinct(superkingdom) from lectinpred_species ";
            $request .= "ORDER BY superkingdom ";
            $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
            echo "<datalist name='superkingdom_list' id='superkingdom_list'  onchange=\"superkingdom_refresh(this.value);\">";
            while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                echo "<option value='{$row['superkingdom']}'>";
            }
            echo "</datalist>";
            ?>
        </div>
        <div style="width: 100%;">
            <label style="width: 100%;">kingdom
                <input list='kingdom_list' type='text' id='kingdom' name="kingdom" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_POST['kingdom'])) {
                    echo $_POST['kingdom'];
                } ?>">
            </label>
            <?php
            $request = "SELECT distinct(kingdom) from lectinpred_species ";
            $request .= "ORDER BY kingdom ";
            $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
            echo "<datalist name='kingdom_list' id='kingdom_list'  onchange=\"kingdom_refresh(this.value);\">";
            while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                echo "<option value='{$row['kingdom']}'>";
            }
            echo "</datalist>";
            ?>
        </div>
        <div style="width: 100%;">
            <label style="width: 100%;">Phylum
                <input list='phylum_list' type='text' id='phylum' name="phylum" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_POST['phylum'])) {
                    echo $_POST['phylum'];
                } ?>">
            </label>
            <?php
            $request = "SELECT distinct(phylum) from lectinpred_species ";
            $request .= "ORDER BY phylum ";
            $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
            echo "<datalist name='phylum_list' id='phylum_list'  onchange=\"phylum_refresh(this.value);\">";
            while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                echo "<option value='{$row['phylum']}'>";
            }
            echo "</datalist>";
            ?>
        </div>
        </div>
	<button id="load_tree_viewer" class="btn-primary"
					style="width: 100%;height:40px;font-size:20px;" onclick="new_search();">Load matching architectures</button>

	</form>
<input id="protein_id" name="protein_id" type="hidden" value="" >
</div>

<div id="results_div" name="results_div" style='width: 100%; margin-top: 10px;'></div>

<?php include($_SERVER['DOCUMENT_ROOT']."/predict/footer.php"); ?>