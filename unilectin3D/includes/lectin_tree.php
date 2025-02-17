<?php 
//SPECIES NODES
$root=array();
$root['name']='Lectins';
$root['children']=array();
$request = "SELECT origine,classe,famille FROM lectin_view GROUP BY origine,classe,famille ORDER BY origine,classe,famille ";
$results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
  //$row['superkingdom'] = substr($row['superkingdom'],0,30);
  //$row['kingdom'] = substr($row['kingdom'],0,30);
  //$row['phylum'] = substr($row['phylum'],0,30);
  //$row['species'] = substr($row['species'],0,30);
  //$s_group=explode(" ",$row['species'])[0];
	$parts=[$row['origine'],$row['classe'],$row['famille']];
	$currentNode = &$root;
	for ($j = 0; $j < count($parts); $j++) {
		$children = &$currentNode["children"];
		$nodeName = $parts[0];
    for ($tmp = 1; $tmp < $j + 1; $tmp++) {
      $nodeName = $nodeName . "." . $parts[$tmp];
    }
		$cat_level=$j;
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
				$childNode=array();
				$childNode['name'] = $nodeName;
				$childNode['cat'] = $cat_level;
				$childNode['children']=array();
				array_push($children,$childNode);
				// Get new child pointer
				for ($k = 0; $k < count($children); $k++) {
					if ($children[$k]["name"] == $nodeName) {
						$currentNode = &$children[$k];
						break;
					}
				}
			}
		} else {
			// Reached the end of the sequence; create a leaf node.
			$childNode=array();
			$childNode['name'] = $nodeName;
			$childNode['cat'] = $cat_level;
			$childNode['children']="";
			array_push($children,$childNode);
		}
	}
}
$json_data=json_encode($root);
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
