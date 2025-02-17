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
$request = create_request($POST_ARRAY,0,0);
$request .= " ORDER BY score DESC ";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
// COUNT
//die($request);
$nb_regions = mysqli_num_rows($results);
$nbresultbypage = 40;
$windownumberpage = ceil(intval($nb_regions) / $nbresultbypage);
$first_display_region = (($activepage - 1) * $nbresultbypage) + 1;
$last_display_region = min(($activepage * $nbresultbypage), $nb_regions);

// RESULTS ARRAY
$request .= " LIMIT " . (($activepage - 1) * $nbresultbypage) . " , " . $nbresultbypage;
echo "<div style='line-height:30px;height:40px;border:1px solid lightgrey;padding:5px;background-image: linear-gradient(to bottom,#fff 0,#e0e0e0 100%);font-size: 24px;'>lectin list $first_display_region to $last_display_region out of $nb_regions </div>";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
	include("viewer.php");
}

page_bar($activepage, $windownumberpage);
?>

