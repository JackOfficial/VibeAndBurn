<?php
$mycontent = "sample.txt";
if(file_exists($mycontent)){
  if(unlink($mycontent)){
    echo "FILE DELETED!";
  }
  else{
    echo "SOMETHING WENT WRONG!";
  }
  
    // $file = fopen($mycontent, 'a');
    // fwrite($file, "IBI NIBINDI NANDITSEMO!");
    // fwrite($file, "IBI NIBINDI NANDITSEMO2!");
    // fclose($file);
}
else{
    $file = fopen($mycontent, 'w');
    fwrite($file, "something!");
    fclose($file);
}

if(file_exists($mycontent)){
    $file = fopen($mycontent, 'r');
    echo fread($file, filesize($mycontent));
   fclose($file);
}


?>