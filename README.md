# UniLectin

The UniLectin interactive database is dedicated to the classification and curation of lectins (with UniLectin3D module) and prediction of lectins (Not yet implemented). 

#UniLectin3D
Module of UniLectin containing the curated lectins structures. Use Glyco3D update database.
Source in unilectin3D directory. 

- index.php load home.php file as the homepage
- generate svg: used to generate the SNFG representations of new IUPAC codes
- load swissmodel: used to extract the PLIP representation of a PDB and store them as html file in the templates folder
- advanced search: formular with multiple features to search for lectins
- display lectin: display all informations for a uniprot entry of a lectin
- display structure: display all infromations for a lectin PDB structure
- display cluster: display the lectins structures for one cluster ID
- tutorial: how to use this module

In the include section are php script included by the main pages on load or when a search formular is submitted

- connect
- expasy_links_array:
- genetic_code: used for gene translation
- get_consensus: used to generate the consensus of a multiple sequence alignment
- litemol: display for a PDB the litemol viewer
- pathogen_species_array: list of pathogen species from the NIH

#PropLec
Module of UniLectin containing the predicted bpropeller lectins. Use 4 tables for protein, aligned domains, domains and pfams.
Source in propeller directory.

- index.php load home.php file as the homepage
- display: display all informations for a predicted lectin (call include/viewer)
- motifs: search formular for lectin and pfam architectures (call include/domains)
- pathogens: display in a table all lectins from pathogens species
- protein: list all protein AC predicted (for referencement)
- search: search fomular for the predicted lectins (call include/search parser)
- tutorial: how to use this module

In the include section are php script included by the main pages on load or when a search formular is submitted

- align binding sites: used to represent the carbohydrate binding sites
- connect: used to connect to the database
- create_request: called by search.php, use user inputs to generate a SQL request
- domain: called by motifs.php, display  the combinations of lectin domains and pfam domains
- get binding sites: load the binding sites text file to represent the carbohydrate binding sites
- graphic_nbdomain: piechart of the number of lectin by familly
- graphic_nbblade: barchart of the number of domain
- graphic_sunburst: barchart of the taxonomy of predicted lectins
- graphic_treeview: treechart of the taxonomy of predicted lectins
- keyword_search: called by the quick search bar in the header
- load_graphics: called by search.php, load the graphics
- search_parser: called by search.php, call create request, load graphics and viewer
- viewer: display a panel with the features of a predicted lectin
- viewer_details: display the NCBI panel and the motifs alignments

#Predict
Module of UniLectin containing the predicted lectins. Use 5 tables for species, protein, aligned domains, domains and pfams.
Source in predict directory. 

- index.php load home.php file as the homepage
- display: display all informations for a predicted lectin (call include/viewer)
- domains: search formular for lectin and pfam architectures (call include/domains)
- protein: list all protein AC predicted (for referencement)
- search: search fomular for the predicted lectins (call include/search parser)
- tutorial: how to use this module

In the include section are php script included by the main pages on load or when a search formular is submitted

- align binding sites: used to represent the carbohydrate binding sites
- connect: used to connect to the database
- create_request: called by search.php, use user inputs to generate a SQL request
- domain: called by motifs.php, display  the combinations of lectin domains and pfam domains
- get binding sites: load the binding sites text file to represent the carbohydrate binding sites
- graphic_nbdomain: piechart of the number of lectin by familly
- graphic_nbblade: barchart of the number of domain
- graphic_sunburst: barchart of the taxonomy of predicted lectins
- graphic_treeview: treechart of the taxonomy of predicted lectins
- keyword_search: called by the quick search bar in the header
- load_graphics: called by search.php, load the graphics
- search_parser: called by search.php, call create request, load graphics and viewer
- viewer: display a panel with the features of a predicted lectin
- viewer_details: display the NCBI panel and the motifs alignments
