
<?php 
include 'vendor/autoload.php'; 
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile('file2.pdf');
$pages  = $pdf->getPages();

/**
 * Funcao para quebrar a string em array apartir de uma string
 * 
 * @return String $str
 */
function  filter($seq, $text){
    $str = str_replace($seq, "@", $text);
    $str = explode("@", $str);
    return $str;
}

//Main
$arrayFinal = [];
foreach ($pages as $page) {
    $text =  $page->getText();

    if(preg_match("/FULL USE/", $text)){
        $text = explode("#", $text);
        foreach($text as $text1){
            if(preg_match("/Qty/", $text1)){
                $text1 = filter("Price", $text1);
                $text1 = $text1[1];
                $text1 = filter("Oracle", $text1);
                
                //Blocos filtrados 
                foreach($text1 as $text2) {
                    $text2 = filter("Program", $text2);
                    if(strlen($text2[0]) > 2){
                        $string = "Oracle ".$text2[0];
                        //Formando array do bloco para tabela
                        $array = filter("\x09", $string);
                        
                        array_push($arrayFinal, $array);
                        foreach ($array as $valueTable) {
                            echo $valueTable;
                            echo "<br>";
                        }
                        echo "<hr>";
                    }
                }
            }
        }
    }
}