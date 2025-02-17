<?php
/**
 * Created by PhpStorm.
 * User: François Bonnardel
 * Date: 14/12/2018
 * Time: 15:50
 */
?>

    <title>UniLectin3D new tree</title>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/unilectin3D/header.php"); ?>
    <script src="/js/d3.v3.min.js"></script>

    <div style='width:100%;padding-left:3%;padding-right:6%;display: inline;'>
        <div style='width:94%;border-top:1px solid black;display: inline-block;'></div>
    </div>
    <div style="width:100%; height:auto;position:relative;">
        Browse by similariry
        <form method="get">
        <div class="slidecontainer" style="width:200px;padding:10px;">
            <?php
            $level=80;
            if(isset($_GET['level'])){
                $level=$_GET['level'];
            }
            ?>
            <input name="level" type="range" min="30" max="90" value="<? echo $level;?>" step="10" class="slider" id="myRange">
            <center><label id="demo"></label></center>
        </div>
            <button class="btn btn-primary" type="submit" style="width:200px;">Refresh</button>
        </form>
        <script>
            var slider = document.getElementById("myRange");
            var output = document.getElementById("demo");
            output.innerHTML = slider.value; // Display the default slider value

            // Update the current slider value (each time you drag the slider handle)
            slider.oninput = function() {
                output.innerHTML = this.value;
            }
        </script>
        <ul>
            <li>Algal lectins = 'brown'</li>
            <li>Animal lectins = 'red'</li>
            <li>Bacterial lectins = 'yellow'</li>
            <li>Fungal and yeast lectins = 'purple'</li>
            <li>Plant lectins = 'green'</li>
            <li>Protist, parasites, mold = 'blue'</li>
            <li>Virus lectins = 'black'</li>
        </ul>
        <button class="btn btn-primary" style="width:200px;float:right;" onclick="$('#tree-container').html('');create_tree(1000, 600);">Reset</button>
        <div class='div-border' id="tree-container"></div>
    </div>
    <div style='width:100%;padding-left:3%;padding-right:6%;display: inline;'>
        <div style='width:94%;border-top:1px solid black;display: inline-block;'></div>
    </div>
<?php
//DISPLAY THE FAMILY TREE
$root = array();
$root['name'] = 'Lectins';
$root['children'] = array();
$request = "SELECT * FROM test_cluster LEFT JOIN lectin_view ON (lectin_view.pdb = test_cluster.pdb) group by clust90";
$results = mysqli_query($connexion, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexion));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    if ($row['origine'] == 'Algal lectins') {
        $row['color'] = 'brown';
    }
    if ($row['origine'] == 'Animal lectins') {
        $row['color'] = 'red';
    }
    if ($row['origine'] == 'Bacterial lectins') {
        $row['color'] = 'yellow';
    }
    if ($row['origine'] == 'Fungal and yeast lectins') {
        $row['color'] = 'purple';
    }
    if ($row['origine'] == 'Plant lectins') {
        $row['color'] = 'green';
    }
    if ($row['origine'] == 'Protist, parasites, mold') {
        $row['color'] = 'blue';
    }
    if ($row['origine'] == 'Virus lectins') {
        $row['color'] = 'black';
    }
    //$row['superkingdom'] = substr($row['superkingdom'],0,30);
    //$row['kingdom'] = substr($row['kingdom'],0,30);
    //$row['phylum'] = substr($row['phylum'],0,30);
    //$row['species'] = substr($row['species'],0,30);
    //$s_group=explode(" ",$row['species'])[0];
    $parts = [$row['fold'], $row['cname'], $row['clust'.$level], $row['pdb']];
    $currentNode = &$root;
    for ($j = 0; $j < count($parts); $j++) {
        $children = &$currentNode["children"];
        $nodeName = $parts[0];
        for ($tmp = 1; $tmp < $j + 1; $tmp++) {
            $nodeName = $nodeName . "." . $parts[$tmp];
        }
        $cat_level = $j;
        $childNode;
        if ($j + 1 < count($parts)) {
            // Not yet at the end of the sequence; move down the tree.
            $foundChild = false;
            for ($k = 0; $k < count($children); $k++) {
                if ($children[$k]["name"] == $nodeName) {
                    $currentNode = &$children[$k];
                    $foundChild = true;
                    break;
                }
            }
            // If we don't already have a child node for this branch, create it.
            if (!$foundChild) {
                $childNode = array();
                $childNode['name'] = $nodeName;
                $childNode['cat'] = $cat_level;
                $childNode['color'] = $row['color'];
                $childNode['children'] = array();
                array_push($children, $childNode);
                // Get new child pointer
                for ($k = 0; $k < count($children); $k++) {
                    if ($children[$k]["name"] == $nodeName) {
                        $currentNode = &$children[$k];
                        break;
                    }
                }
            } else {
                if ($currentNode['color'] != $row['color']) {
                    $currentNode['color'] = 'grey';
                }
            }
        } else {
            // Reached the end of the sequence; create a leaf node.
            $childNode = array();
            $childNode['name'] = $nodeName;
            $childNode['cat'] = $cat_level;
            $childNode['children'] = "";
            array_push($children, $childNode);
        }
    }
}
$json_data = json_encode($root);
?>

    <script type="application/json" id="data"><?php echo $json_data; ?></script>
    <style>
        .node circle {
            fill: #fff;
            stroke: steelblue;
            stroke-width: 1.5px;
        }

        .node {
            font: 15px sans-serif;
        }

        .link {
            fill: none;
            stroke: #ccc;
            stroke-width: 1.5px;
        }
    </style>

    <script>
        var tree_link = "https://unilectin.eu/unilectin3D/advanced_search?";
        var levels = {
            0: "fold",
            1: "clust20",
            2: "clustN",
            3: "pdb"
        };
    </script>
    <script src='/js/test_tree_color.js?v=466846456'></script>
    <script>
        if (screen.width >= 600) {
            create_tree(1000, 600);
        }
    </script>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/unilectin3D/footer.php"); ?>