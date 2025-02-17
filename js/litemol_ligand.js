
function load_ligand(PDB, ligand) {
  $('#liteMolViewerligand').empty();
  var ligandDetailsArr=ligand.split(":");
  var ligandName = ligandDetailsArr[0];
  var ligandchain = ligandDetailsArr[1];
  var ligandid = ligandDetailsArr[2];
  
  var plugin = LiteMol.Plugin.create({target : '#liteMolViewerligand', layoutState: {hideControls : true}, viewportBackground: "#fff"});
  var Transformer = LiteMol.Bootstrap.Entity.Transformer;
  var Visualization = LiteMol.Bootstrap.Visualization;
  var Query = LiteMol.Core.Structure.Query;
  var Transform = LiteMol.Bootstrap.Tree.Transform;
  var Command = LiteMol.Bootstrap.Command;
  // in the ligand instance, you will want to NOT include Bootstrap.Behaviour.ShowInteractionOnSelect(5) 
  var ligandStyle = {
    type: 'BallsAndSticks',
    params: {
      useVDW: true,
      vdwScaling: 0.25,
      bondRadius: 0.13,
      detail: 'Automatic'
    },
    theme: {
      template: Visualization.Molecule.Default.ElementSymbolThemeTemplate,
      colors: Visualization.Molecule.Default.ElementSymbolThemeTemplate.colors,
      transparency: {
        alpha: 1.0
      }
    }
  };
  var ambStyle = {
    type: 'BallsAndSticks',
    params: {
      useVDW: false,
      atomRadius: 0.15,
      bondRadius: 0.07,
      detail: 'Automatic'
    },
    theme: {
      template: Visualization.Molecule.Default.UniformThemeTemplate,
      colors: Visualization.Molecule.Default.UniformThemeTemplate.colors.set('Uniform', {
        r: 0.4,
        g: 0.4,
        b: 0.4
      }),
      transparency: {
        alpha: 0.75
      }
    }
  };
  var ligandQ = Query.residues({
    name: ligandName
  }); // here you will fill in the whole info 
  var ambQ = Query.residues({
    name: ligandName
  }).ambientResidues(5); // adjust the radius
  var id = PDB+':'+ligandName;
  var url = "https://webchem.ncbr.muni.cz/CoordinateServer/"+PDB+"/ligandInteraction?name="+ligandName+"&authAsymId="+ligandchain+"&authSeqNumber="+ligandid; // here you will fill in the full server etc ...
  console.log(url);
  var action = Transform.build()
  .add(plugin.context.tree.root, Transformer.Data.Download, {
    url: url,
    type: 'String',
    id: id
  })
  .then(Transformer.Data.ParseCif, {
    id: id
  }, {
    isBinding: true
  })
  .then(Transformer.Molecule.CreateFromMmCif, {
    blockIndex: 0
  }, {
    isBinding: true
  })
  .then(Transformer.Molecule.CreateModel, {
    modelIndex: 0
  }, {
    isBinding: false,
    ref: 'ligand-model'
  });
  action.then(Transformer.Molecule.CreateSelectionFromQuery, {
    query: ambQ,
    name: 'Ambience'
  }, {
    isBinding: true
  })
    .then(Transformer.Molecule.CreateVisual, {
    style: ambStyle
  });
  action.then(Transformer.Molecule.CreateSelectionFromQuery, {
    query: ligandQ,
    name: 'Ligand'
  }, {
    isBinding: true
  })
    .then(Transformer.Molecule.CreateVisual, {
    style: ligandStyle
  }, {
    ref: 'ligand-visual'
  });
  plugin.applyTransform(action)
    .then(function() {
    // we select the ligand to display the density around it if it's loaded
    Command.Molecule.CreateSelectInteraction.dispatch(plugin.context, {
      entity: plugin.context.select('ligand-visual')[0],
      query: Query.everything()
    });
  });
  //.catch(e => reportError(e));
}