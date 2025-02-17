<?php 
if($_GET['source'] != ""){
  $source = $_GET['source'];
  $pdb = $_GET['pdb'];
  $molecule_id = $_GET['molecule_id'];
  $height = $_GET['height'];
  $width = $_GET['width'];
  include ("connect.php");
  $connexion = connectdatabase ();
}

//echo "VALUES $source $pdb $molecule_id";

if($source == "PDBe" && $pdb != ""){
  echo "<div style='position:relative;float:left;height:{$height}px;width:{$width}px;'  id='liteMolViewer$molecule_id'></div>";
?>
<script>
  var initParams = {
    moleculeId: '<?php echo strtolower($pdb);?>',
    pdbeUrl: 'https://www.ebi.ac.uk/pdbe/',
    loadMaps: true,
    validationAnnotation: false,
    domainAnnotation: false,
    lowPrecisionCoords: false,
    isExpanded: false,
    treeMenu: false,
    showControls: false,
    hideControls : true
  }

  if('1' != '') initParams['assembly'] = '1';

  LiteMolPluginInstance.customSpecification.behaviours.push(LiteMol.Bootstrap.Behaviour.ApplyInteractivitySelection);
  LiteMolPluginInstance.customSpecification.behaviours.push(LiteMol.Bootstrap.Behaviour.Molecule.ShowInteractionOnSelect(5));

  LiteMolPluginInstance.customSpecification.settings.initParams = initParams;

  let ele = document.getElementById('liteMolViewer<?php echo $molecule_id;?>');
  LiteMolPluginInstance.render(ele, initParams);
  LiteMolPluginInstance.setBackground();
</script>

<?php 
}
if($source == "Unilectin" && $molecule_id != ""){
  echo "<div style='position:relative;float:left;height:{$height}px;width:{$width}px;'  id='liteMolViewer$molecule_id'></div>";
  $request_litemol = "SELECT filepath FROM _file WHERE molecule_id = $molecule_id AND file_type_id = 2";
  $results_litemol = mysqli_query($connexion, $request_litemol) or die ( "SQL Error:<br>$request_litemol<br>" . mysqli_error ( $connexion ) );
  $row_litemol = mysqli_fetch_array($results_litemol, MYSQLI_ASSOC);
  $file = "./glyco3d/".$row_litemol['filepath'];
  if(!file_exists($file)){
    $file = "../glyco3d/".$row_litemol['filepath'];
  }
  $target = "#liteMolViewer$molecule_id";
?>
<script>
  var file = "<?php echo $file?>";
  var target = "<?php echo $target?>";
  var litemol = LiteMol.Plugin.create({target : target, layoutState: {hideControls : true}, viewportBackground: "#fff"});
  //var Transformer = LiteMol.Bootstrap.Entity.Transformer;
  //var Core = LiteMol.Core;
  //var Bootstrap = LiteMol.Bootstrap;
  //var Command = Bootstrap.Command;
  litemol.loadMolecule({ format:'pdb', url:file})

  let query = LiteMol.Core.Structure.Query.residues({ authAsymId: 'A', authSeqNumber: 132 }, { authAsymId: 'A', authSeqNumber: 144 }, ...);
  let t = plugin.createTransform();
  t.add('polymer-visual', Transformer.Molecule.CreateSelectionFromQuery, { query, name: 'Residues' }, { }))
    .then(Transformer.Molecule.CreateVisual, { style: Bootstrap.Visualization.Molecule.Default.ForType.get('BallsAndSticks') });
  plugin.applyTransform(t);  
</script>
<?php
}
?>





