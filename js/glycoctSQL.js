function glycoctRDF(glycoCtCode) {
    glycoCtCode = glycoCtCode.replace(/\\n/,'\n');
    glycoCtCode = glycoCtCode.replace(/\n+$/,'');
    console.log("glycoCtCode", glycoCtCode);
    var queryRDF = [];
    //queryRDF.push("?glycan predicate:has_residue ?res0 .");
    //queryRDF.push("SELECT * FROM ");
    queryRDF.push("SELECT E0.glycan_id, E0.source_db, E0.source_db_id, COUNT(E0.monosac) FROM ");

    var residues = glycoCtCode.split('\\nLIN\\n')[0].replace("RES\\n", "");
    residues = residues.split('\\n');
    console.log("residues", residues);
    var linkages = glycoCtCode.split('\\nLIN\\n')[1];
    if (typeof linkages !== 'undefined') {
        linkages = linkages.split('\\n');
    }
    console.log("linkages", linkages);

    var residues_link = {};
    var residues_name = {}
    for (var i = 0; i < residues.length; i++) {
        var residue = residues[i];
        if (! residue.includes("-") || ! residue.includes(":")){continue;}
        residues_link[i] = residue[3];
        residues_name[i] = residue.substr(5);
    };

    for (var i = 0; i < residues.length; i++) {
        var residue = residues[i];
        if (! residue.includes("-") || ! residue.includes(":")){continue;}
        var residue_fullname = residue.split('-');
        residue_fullname.shift();
        residue_fullname = residue_fullname.join('-');
        var next_residue_name = 'none';
        if (residues.length > i+1){
            next_residue_name = residues[i + 1];
        }
        //console.log(residue_fullname, residues_name[i]);
        residues_name[i] = residue_fullname.split('-')[0];
        if (residues_name[i] == "" && residue_fullname.includes("-")){
            residues_name[i] = residue_fullname.split('-')[1];
        }
        if (residue_fullname.includes("acetyl")){
            continue;
        }
        if (next_residue_name.includes("acetyl")){
            residues_name[i] += "nac"
        }
        //queryRDF.push(residues_name[i] + ' ' + i + ' ' + 'A_Monosaccharide' + ' ' + ' ')
    }

    var linked_monosac = [] ;
    if (typeof linkages !== 'undefined') {
        for (var i = 0; i < linkages.length; i++) {
            var linkage = linkages[i];
            var numbers = [...linkage.matchAll("([0-9]+)")];
            var source = numbers[1][1] - 1;
            var target = numbers[4][1] - 1;
            var source_atom = numbers[2][1] * 1;
            var target_atom = numbers[3][1] * 1;
            linked_monosac.push(source);
            if (residues_link[source] !== 'undefined' && residues_link[target] !== 'undefined') {
                if (residues_link[source] != 'n' && residues_link[target] != 'n'){
                    var e_source = 'E'+source
                    var e_target = 'E'+target
                    if(queryRDF[1] == null){queryRDF.push(' test_epitopes '+e_source+' INNER JOIN test_epitopes '+e_target+' ON ( ');}
                    else{queryRDF.push(' LEFT JOIN test_epitopes '+e_target+' ON ( ');}
                    queryRDF.push(e_source+'.glycan_id = ' + e_target + '.glycan_id AND ');
                    queryRDF.push(e_source+'.monosac = "' + residues_name[source] + '" AND '+e_source+'.source = ' + source + ' ' + 'AND '+e_source+'.link="glyco"' + ' AND '+e_target+'.source = ' + target + ' AND '+e_target+'.monosac = "' + residues_name[target]+'"');
                    queryRDF.push(' ) ');
                    if (residues_link[target] == 'a'){
                        //queryRDF.push(residues_name[source] + ' ' + source + ' ' + 'has_anomerConnection_alpha' + ' ' + target + ' ' + residues_name[target]);
                    }else{
                        //queryRDF.push(residues_name[source] + ' ' + source + ' ' + 'has_anomerConnection_beta' + ' ' + target + ' ' + residues_name[target]);
                    }
                    //queryRDF.push(residues_name[source] + ' ' + source + ' ' + 'has_linkedCarbon_' + source_atom + ' ' + target + ' ' + residues_name[target]);
                    //queryRDF.push(residues_name[source] + ' ' + source + ' ' + 'has_anomerCarbon_' + target_atom + ' ' + target + ' ' + residues_name[target]);
                }else{
                    //queryRDF.push(residues_name[source] + ' ' + source + ' ' + 'has_linkedCarbon_' + source_atom + ' ' + target + ' ' + residues_name[target]);
                }
            }
        }
    }
    for (var i = 0; i < residues.length; i++) {
        if (i in linked_monosac){continue;}
        //queryRDF.push('end' + ' ' + i + ' ' + 'has_no_Linkage' + ' ' + '\t');
    }

    queryRDF.push(' GROUP BY E0.glycan_id ');
    return(queryRDF.join(' '));
}

//var glycoCtCode = "RES\n1b:x-dglc-HEX-1:5\n2b:b-dgal-HEX-1:5\n3b:a-dgro-dgal-NON-2:6|1:a|2:keto|3:d\n4s:n-acetyl\n5b:b-dgal-HEX-1:5\n6s:n-acetyl\n7b:b-dgal-HEX-1:5\n8b:a-dgro-dgal-NON-2:6|1:a|2:keto|3:d\n9s:n-acetyl\nLIN\n1:1o(4+1)2d\n2:2o(3+2)3d\n3:3d(5+1)4n\n4:2o(4+1)5d\n5:5d(2+1)6n\n6:5o(3+1)7d\n7:7o(3+2)8d\n8:8d(5+1)9n";
//var res = glycoctRDF(glycoCtCode);
//console.log(res);