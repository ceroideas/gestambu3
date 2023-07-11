<?php
function deleteElements($keys){
  $result = [];
  for ($i=0; $i < count($keys); $i++) { 
    //$result[] = ["data" => $keys[$i]];
    
    if(!is_numeric($keys[$i])){
      $result[] = ["data" => $keys[$i]];
    }
    
  }
  return $result;
}
?>
