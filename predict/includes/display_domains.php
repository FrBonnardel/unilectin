<?php
//IMPORTANT OPTIONS
if($protein_id==""){
  $protein_id = 200;
  $domain="ADP-ribosylating_toxin";
}

function get_transcript($protein_id) {
  $path = dirname ( __DIR__ ) . "/db_load/lectinpred_scores.txt";
  $text = "";
  if (! file_exists ( $path )) {
    die ( "File not found $path" );
  }
  $file_handle = fopen ( $path, "r" );
  $i = 1;
  while ( ! feof ( $file_handle ) ) {
    if ($i == $protein_id) {
      break;
    }
    fgets ( $file_handle );
    $i ++;
  }
  if (! feof ( $file_handle )) {
    $text = fgets ( $file_handle );
  }
  fclose ( $file_handle );
  return (rtrim($text));
}

$info = get_transcript($protein_id);
//echo $protein_id;
//echo '<pre>'; print_r(explode("\t",$info)); echo '</pre>';
$ref_seqs = explode("\t",$info)[2];
$match_seqs = explode("\t",$info)[3];
//echo $match_seq;

?>

