<?php





$list = glob ( "../../../public/media/jpeg/*" );

foreach ($list as $l) {

    $t = str_replace('../','',$l);
    echo str_replace('public','',$t)."\n";

}


