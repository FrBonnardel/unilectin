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
	var levels = {
		0: "superkingdom",
		1: "kingdom",
		2: "phylum",
		3: "species",
		4: "species"
	};
</script>
<script src='/predict/js/lectinpred_tree.js?152396639'></script>
<script>
  $('#tree-container').html("");
  create_tree(1000, 600);
</script>