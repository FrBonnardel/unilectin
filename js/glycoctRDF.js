function glycoctRDF(glycoCtCode) {
//var glycoCtCode = "RES\n1b:x-dglc-HEX-1:5\n2b:b-dgal-HEX-1:5\n3b:a-dgro-dgal-NON-2:6|1:a|2:keto|3:d\n4s:n-acetyl\n5b:b-dgal-HEX-1:5\n6s:n-acetyl\n7b:b-dgal-HEX-1:5\n8b:a-dgro-dgal-NON-2:6|1:a|2:keto|3:d\n9s:n-acetyl\nLIN\n1:1o(4+1)2d\n2:2o(3+2)3d\n3:3d(5+1)4n\n4:2o(4+1)5d\n5:5d(2+1)6n\n6:5o(3+1)7d\n7:7o(3+2)8d\n8:8d(5+1)9n";
    glycoCtCode = glycoCtCode.replace(/\n+$/,'');
    var queryRDF = [];
    queryRDF.push("prefix glycan: <http://mzjava.expasy.org/glycan/>");
    queryRDF.push("prefix predicate: <http://mzjava.expasy.org/predicate/>");
    queryRDF.push("prefix residue: <http://mzjava.expasy.org/residue/>");
    queryRDF.push("prefix monosaccharide: <http://mzjava.expasy.org/monosaccharide/>");
    queryRDF.push("prefix substituent: <http://mzjava.expasy.org/substituent/>");
    queryRDF.push("SELECT DISTINCT ?glycan");
    queryRDF.push("WHERE {");
    queryRDF.push("?glycan predicate:has_residue ?res0 .");
    //queryRDF.push("?glycan predicate:has_residue ?resExt . ?resExt predicate:is_GlycosidicLinkage ?res0 .");
    //console.log(glycoCtCode);

    var conversion = {};
    conversion["alt-HEX-1:5"] = "Alt";
    conversion["lara-PEN-1:4"] = "Ara";
    conversion["lglc-HEX-1:5|2:d|4:d|6:d"] = "Bac";
    conversion["dalt-HEX-1:5|6:d"] = "dAlt";
    conversion["lara-PEN-1:4|2:keto"] = "Fru";
    conversion["lgal-HEX-1:5|6:d"] = "Fuc";
    conversion["dgal-HEX-1:5"] = "Gal";
    conversion["dgal-HEX-0:0|1:aldi"] = "Gal";
    conversion["dgal-HEX-1:5|6:a"] = "GalA";
    conversion["dglc-HEX-1:5"] = "Glc";
    conversion["dglc-HEX-0:0|1:aldi"] = "Glc";
    conversion["dglc-HEX-1:5|6:a"] = "GlcA";
    conversion["HEX-1:5"] = "Hex";
    conversion["HEX-1:5|6:a"] = "HexA";
    conversion["lido-HEX-1:5"] = "Ido";
    conversion["lido-HEX-1:5|6:a"] = "IdoA";
    conversion["dgro-dgal-NON-2:6|1:a|2:keto|3:d"] = "Kdn";
    conversion["dman-HEX-1:5"] = "Man";
    conversion["dman-HEX-x:x"] = "Man";
    conversion["dman-HEX-1:5|6:a"] = "ManA";
    conversion["PEN-1:4"] = "Pent";
    conversion["dglc-HEX-1:5|6:d"] = "Qui";
    conversion["lman-HEX-1:5|6:d"] = "Rha";
    conversion["dxyl-PEN-1:4"] = "Xyl";
    conversion["dgal-HEX-0:0|1:aldi"] = "Gal";
    conversion["lgal-HEX-0:0|1:aldi|6:d"] = "Fuc";
    conversion["dglc-HEX-0:0|1:aldi"] = "Glc";
    conversion["HEX-1:5|0:d"] = "DeoxyHex";

    var residue_with_acetyl = ["dglc", "dman", "dgal", "alt", "lido"];

    var residues = glycoCtCode.split('\nLIN\n')[0].replace("RES\n", "");
    residues = residues.split('\n');
    //console.log(residues);
    var linkages = glycoCtCode.split('\nLIN\n')[1];
    if (typeof linkages !== 'undefined') {
        linkages = linkages.split('\n');
    }

    var residues_link = [];
    for (var i = 0; i < residues.length; i++) {
        var residue = residues[i];
        residues_link[i] = residue[3];
    }
    ;

    for (var i = 0; i < residues.length; i++) {
        var residue = residues[i];
        var residue_fullname = residue.substr(5);
        var residue_name = residue.split('-')[1];
        var next_residue = residues[i + 1];
        var next_residue_name = 'none';
        if (typeof next_residue !== 'undefined') {
            next_residue_name = next_residue.split('-')[1];
        }
        if (residue_fullname in conversion) {
            queryRDF.push('?res' + i + ' a monosaccharide:' + conversion[residue_fullname] + ' .');
        }
        else if (residue_name == 'acetyl') {
            queryRDF.push('?res' + i + ' a substituent:NAcetyl .?res' + (i - 1) + ' predicate:is_SubstituentLinkage ?res' + i + ' .');
        }
        if (next_residue_name != 'acetyl' && residue_with_acetyl.includes(residue_name)) {
            queryRDF.push('MINUS {?res' + i + ' predicate:links_To ?SUB .?SUB a substituent:NAcetyl .}.');
        }
    }

    var linked_monosac = [] ;
    if (typeof linkages !== 'undefined') {
        for (var i = 0; i < linkages.length; i++) {
            var linkage = linkages[i];
            var source = linkage[2] - 1;
            var target = linkage[9] - 1;
            var source_atom = linkage[5];
            var target_atom = linkage[7];
            linked_monosac.push(source);
            if (residues_link[source] != 'n' && residues_link[target] != 'n') {
                queryRDF.push('?res' + source + ' predicate:is_GlycosidicLinkage ?res' + target + ' .');
                if (residues_link[target] == 'a') {
                    queryRDF.push('?res' + source + ' predicate:has_anomerConnection_alpha ?res' + target + ' .');
                }
                else {
                    queryRDF.push('?res' + source + ' predicate:has_anomerConnection_beta ?res' + target + ' .');
                }
                queryRDF.push('?res' + source + ' predicate:has_linkedCarbon_' + source_atom + ' ?res' + target + ' .');
                queryRDF.push('?res' + source + ' predicate:has_anomerCarbon_' + target_atom + ' ?res' + target + ' .');
                //queryRDF.push('MINUS {?res'+target+' predicate:links_To ?SUB .?SUB a substituent:NAcetyl .}.');
            } else {
                queryRDF.push('?res' + source + ' predicate:has_linkedCarbon_' + source_atom + ' ?res' + target + ' .');
            }
        }
    }
    for (var i = 0; i < residues.length; i++) {
        if (i in linked_monosac){continue;}
        queryRDF.push('MINUS {?res' + i + ' predicate:is_GlycosidicLinkage ?resEpi' + i + ' .}.');
    }

    queryRDF.push('}');
    //console.log(queryRDF.join('\n'));
    return(queryRDF);
}