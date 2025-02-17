<!DOCTYPE html>
<html>

<head>
    <!-- Required for IE11 -->
    <script src="https://cdn.jsdelivr.net/npm/babel-polyfill/dist/polyfill.min.js" defer></script>
    <!-- Web component polyfill (only loads what it needs) -->
    <script src="https://cdn.jsdelivr.net/npm/@webcomponents/webcomponentsjs/webcomponents-lite.js" charset="utf-8" defer></script>
    <!-- Required to polyfill modern browsers as code is ES5 for IE... -->
    <script src="https://cdn.jsdelivr.net/npm/@webcomponents/webcomponentsjs/custom-elements-es5-adapter.js" charset="utf-8" defer></script>
    <!-- d3.js dependency script -->
    <script  src="https://cdn.jsdelivr.net/npm/d3@5.9.2" defer></script>
    <!-- PDB Topology Viewer library script -->
    <script  type="text/javascript" src="https://www.ebi.ac.uk/pdbe/pdb-component-library/js/pdb-topology-viewer-component-2.0.0.js" defer></script>
</head>

<body>
<style>
    #viewerSection{
        width:450px;
        height: 450px;
        position:relative;
    }
</style>
<div id="viewerSection">

    <!-- PDB Topology Viewer Web-component -->
    <pdb-topology-viewer entry-id="<?php echo $_GET['pdb']; ?>" entity-id="1"></pdb-topology-viewer>

</div>


</body>

</html>