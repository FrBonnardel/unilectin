<?php

function page_bar($activepage, $numberpage)
{
	echo "<div style='line-height:30px;height:40px;border:1px solid lightgrey;padding:5px;background-image: linear-gradient(to bottom,#fff 0,#e0e0e0 100%);'>";
	echo "<label style='margin-right:5px;'>page</label>";
    echo "<div class='btn-group' style='float:right;'> ";
    echo "<label class='btn btn-default' onclick=\"reloadphp_div(" . max($activepage-1,1) . ");\">&laquo;</label>";
    for ($i =  max($activepage-2,1); $i <= min($activepage+2,$numberpage); ++$i) {
        if ($i !== 0) {
            if ($activepage == $i) {
            	echo "<label class='btn btn-default active'>$i</label>";
            } else {
                echo "<label class='btn btn-default' onclick=\"reloadphp_div(" . $i . ");\">$i</label>";
            }
        }
    }
    echo "<label class='btn btn-default' onclick=\"reloadphp_div(" . min($activepage+1,$numberpage) . ");\">&raquo;</label>";
    echo "<label class='btn btn-default' onclick=\"reloadphp_div(" . 1 . ");\">first</label>";
    echo "<label class='btn btn-default' onclick=\"reloadphp_div(" . $numberpage . ");\">last</label>";
    echo "</div>";
    echo "</div>";
}

?>

<script>
function reloadphp_div(valuepage){
    document.getElementById('activepage').value = valuepage;
    load_page();
}
</script>