<?php 
require_once('config/Config.php');
require_once('classes/Db/ExtendPDO.php');
include_once __DIR__ . "/autoload.php";

$time_start = microtime(true);
$pdo = new ExtendPDO(DB_HOST,DB_DBNAME,DB_ENCODE,DB_USER,DB_PW);
$json = file_get_contents("Gossiping-38".$_GET['page']."01-38".($_GET['page']+1)."00.json");
$obj = json_decode($json);
$start = $_GET['index'];
$i = 1;
foreach($obj as $a){ 
    foreach($a as $b){
        if($i >= $start)
        {
            $tid = $pdo->insert('ptt_titles',[
                'title'=>$b->article_title,
                'board_id'=>1
                ]);
            foreach(($b->messages) as $c){
                foreach($c as $d){                
                    $test = $pdo->insert('ptt_words',[
                    'title_id'=>$tid,
                    'word'=>$d
                    ]);
                }
            }
        }
        $i += 1;
        if( $i - $start  >= 100){
            $url = "load.php?index=".$i."&page=".($_GET['page']);
            echo "<script type='text/javascript'>";
            echo "window.location.href='$url'";
            echo "</script>"; 
            exit;
        }

    }
    
}
$url = "load.php?index=1&page=".($_GET['page']+1);
echo "<script type='text/javascript'>";
echo "window.location.href='$url'";
echo "</script>"; 
exit;

