<?Php 
class graalLevel{
    var $tile = array();
    var $imgFolder = 'images/';
    var $defaultTileset = 'pics1.png';
    function prepare(){
        file_exists($this->imgFolder) or mkdir($this->imgFolder);
        file_exists($this->imgFolder.$this->defaultTileset) or die("Please, first save pics1.png file in folder '{$this->imgFolder}'.");
    }
    function graalLevel($lvl){
        $this->prepare();
        preg_match_all('/board \d \d+ \d\d \d (.*)/ei',$lvl,$r);
        function convertLine($a,$b,&$o){
            foreach(str_split($a, 2) as $c=>$d)$o->tile[$b][$c] = $o->tileSetCoordinates($d);
        }    
        array_walk($r[1],'convertLine',$this);
    }
    function tileSetCoordinates($d){
        $seq = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
        $_ = strpos($seq,$d[1])+strpos($seq,$d[0])*64;
        return array(floor((floor($_/512)*16+($_%16))*16)*-1,floor((floor($_/16)%32)*16)*-1);
    }
    function render(){
        $s='';
        for($i=0;$i<64;$i++)for($j=0;$j<64;$j++)
            $s .= '<div class=div style="background-position:'.(count($this->tile)>0?$this->tile[$i][$j][0]:'0').'px '.(count($this->tile)>0?$this->tile[$i][$j][1]:0).'px;"></div>';
        echo "<style type='text/css'>*{margin:0px;}.div{background-image:url({$this->imgFolder}{$this->defaultTileset});display:block; width:16px; height: 16px; float:left;}</style><div style='width:1024px;height:1024px;'>$s</div>";
    }
}
if(array_key_exists('arq',$_FILES) && $_FILES['arq']['size']>0 && is_array($t = pathinfo($_FILES['arq']['name'])) && $t['extension'] == 'nw'){
    $level = new graalLevel(    file_get_contents($_FILES['arq']['tmp_name'])    );
    $level->render();
}
?>

<form method="post" enctype="multipart/form-data">Select nw file:<input type="file" name="arq" /><input type="submit" name="submit" value="enviar"/></form>
