<?php include("includes/iheader.php");

?>
<h2>Sivustoa vielä päivitetään...</h2>

<?php
//kirjautuneen käyttäjän userID?
$dataYhteenveto['email'] = $_SESSION['suserEmail'];
$sqlYhteenveto = "SELECT userID FROM users where userEmail =  :email";
$kyselyYhteenveto=$DBH->prepare($sqlYhteenveto);
$kyselyYhteenveto->execute($dataYhteenveto);
$tulosYhteenveto=$kyselyYhteenveto->fetch();
$currentUserID=$tulosYhteenveto[0];
?>

<?php
include("forms4/selectTime.php");
//kysely ja laskukaava viitearvoille
$dataViite['userID'] = $currentUserID;
$sqlViite = "SELECT userSex, userHeight, userDOB FROM `users` WHERE userID = :userID";
$kyselyViite=$DBH->prepare($sqlViite);
$kyselyViite->execute($dataViite);

echo ("<h4>PEF-mittauksen tavoitearvosi on: ");

//echo("<table>
   // <tr>
      //  <th>Viitearvo</th>
   // </tr>");
while	($row=$kyselyViite->fetch()){
        $dateOfBirth=$row['userDOB'];
        $today=date("Y-m-d");
        $diff = date_diff(date_create($dateOfBirth), date_create($today));
        $age=$diff->format('%y');
        $pituus=$row['userHeight'];
        //viitearvo lähde: http://www.gfr.fi/pef.html
        // kaava poimittu inspectistä
        $pef_wmM = exp(0.544*log($age)-0.0151*$age-74.7/$pituus+5.48);
        $pef_wmN = exp(0.376*log($age)-0.012*$age-58.8/$pituus+5.63);
        $pef_euN = -0.0000001116*pow($pef_wmN, 3.0)+0.0008814*pow($pef_wmN, 2.0)+0.4*$pef_wmN+50.356;
        $pef_euM = -0.0000001116*pow($pef_wmM, 3.0)+0.0008814*pow($pef_wmM, 2.0)+0.4*$pef_wmM+50.356;
        if($row['userSex'] == "m") {
            echo('<span style="color:green; font-weight: bold; font-size: 20px;">'.ROUND($pef_euM, 0). " l/min</h4>");
        }
        if($row['userSex'] == "n") {
            echo('<span style="color:green; font-weight: bold; font-size: 20px;">'.ROUND($pef_euN, 0).'</span>'. " l/min</h4>");
        }
        //nämä kysymysmerkkiin pop-upiin?
        //echo("Viitearvot määritellään iän, sukupuolen ja pituuden perusteella.<br/>");
        //echo("Viitearvot perustuvat EU standardiin: EU EN-13826 [l/min]");
        // vuorokausivaihtelu: 100 x (suurin PEF- pienin PEF) / 1/2x (suurin PEF+ pienin PEF)
    }

?>
<?php

/*if(isset($_POST['submitTime'])) {
    $_SESSION['aikaPef']=$_POST['aikaPef'];
    if(!empty($_POST['valinta'])){
    foreach($_POST['valinta'] as $key => $value){
        $_SESSION['valinta'] = $_POST['valinta'];
       }
} }*/

if (!isset($_POST['submitTime'])) {
    $dataPEF['userID'] = $currentUserID;
    $sqlPEF = "SELECT medsInfo, 1st, 2nd, 3rd, timeOfMeasurement, userSex FROM `peakFlow`, `users`
    WHERE users.userID = peakFlow.userID AND users.userID = :userID
    ORDER BY timeOfMeasurement DESC LIMIT 3";
    $kyselyPEF=$DBH->prepare($sqlPEF);
    $kyselyPEF->execute($dataPEF);
    echo ("<p><h3>3 viimeisintä PEF-mittausta:</h3></p>");

/*}elseif ($_POST['aikaPef']=="vko1" && isset($_POST['submitTime']) && !isset($_POST['valinta'][0]) && !isset($_POST['valinta'][1])) {
    $dataPEF['userID'] = $currentUserID;
    $sqlPEF = "SELECT medsInfo, 1st, 2nd, 3rd, timeOfMeasurement, userSex, (DATE(NOW()) - INTERVAL 7 DAY) AS diff FROM `peakFlow`, `users` 
    WHERE users.userID = peakFlow.userID AND users.userID = :userID
    AND timeOfMeasurement >= (DATE(NOW()) - INTERVAL 7 DAY)
    ORDER BY timeOfMeasurement DESC";
    $kyselyPEF=$DBH->prepare($sqlPEF);
    $kyselyPEF->execute($dataPEF);
    
    echo ("<p><h4>PEF-mittaukset ajalta ".date("d.m.Y")." - ". "</h4></p>");

}elseif ($_POST['aikaPef']=="vko2" && isset($_POST['submitTime']) && !isset($_POST['valinta'][0]) && !isset($_POST['valinta'][1])) {
    $dataPEF['userID'] = $currentUserID;
    $sqlPEF = "SELECT medsInfo, 1st, 2nd, 3rd, timeOfMeasurement, userSex, (DATE(NOW()) - INTERVAL 14 DAY) AS diff FROM `peakFlow`, `users` 
    WHERE users.userID = peakFlow.userID AND users.userID = :userID
    AND timeOfMeasurement >= (DATE(NOW()) - INTERVAL 14 DAY)
    ORDER BY timeOfMeasurement DESC";
    $kyselyPEF=$DBH->prepare($sqlPEF);
    $kyselyPEF->execute($dataPEF);	
    
    echo ("<p><h4>PEF-mittaukset ajalta ".date("d.m.Y")." - ". "</h4></p>");
}elseif ($_POST['aikaPef']=="vko1" && isset($_POST['submitTime']) && isset($_POST['valinta'][0]) && !isset($_POST['valinta'][1])) {
    $dataPEF['userID'] = $currentUserID;
    $sqlPEF = "SELECT medsInfo, 1st, 2nd, 3rd, timeOfMeasurement, userSex, (DATE(NOW()) - INTERVAL 7 DAY) AS diff FROM `peakFlow`, `users` 
    WHERE users.userID = peakFlow.userID AND users.userID = :userID AND medsInfo='ennen'
    AND timeOfMeasurement >= (DATE(NOW()) - INTERVAL 7 DAY)
    ORDER BY timeOfMeasurement DESC";
    $kyselyPEF=$DBH->prepare($sqlPEF);
    $kyselyPEF->execute($dataPEF);
    
    echo ("<p><h4>PEF-mittaukset ajalta ".date("d.m.Y")." - ". "</h4></p>");

}elseif ($_POST['aikaPef']=="vko1" && isset($_POST['submitTime']) && isset($_POST['valinta'][1]) && !isset($_POST['valinta'][0])) {
    $dataPEF['userID'] = $currentUserID;
    $sqlPEF = "SELECT medsInfo, 1st, 2nd, 3rd, timeOfMeasurement, userSex, (DATE(NOW()) - INTERVAL 7 DAY) AS diff FROM `peakFlow`, `users` 
    WHERE users.userID = peakFlow.userID AND users.userID = :userID AND medsInfo='jälkeen'
    AND timeOfMeasurement >= (DATE(NOW()) - INTERVAL 7 DAY)
    ORDER BY timeOfMeasurement DESC";
    $kyselyPEF=$DBH->prepare($sqlPEF);
    $kyselyPEF->execute($dataPEF);
    
    echo ("<p><h4>PEF-mittaukset ajalta ".date("d.m.Y")." - ". "</h4></p>");

}elseif ($_POST['aikaPef']=="vko2" && isset($_POST['submitTime']) && isset($_POST['valinta'][1]) && !isset($_POST['valinta'][0])) {
    $dataPEF['userID'] = $currentUserID;
    $sqlPEF = "SELECT medsInfo, 1st, 2nd, 3rd, timeOfMeasurement, userSex, (DATE(NOW()) - INTERVAL 7 DAY) AS diff FROM `peakFlow`, `users` 
    WHERE users.userID = peakFlow.userID AND users.userID = :userID AND medsInfo='jälkeen'
    AND timeOfMeasurement >= (DATE(NOW()) - INTERVAL 14 DAY)
    ORDER BY timeOfMeasurement DESC";
    $kyselyPEF=$DBH->prepare($sqlPEF);
    $kyselyPEF->execute($dataPEF);	
    
    echo ("<p><h4>PEF-mittaukset ajalta ".date("d.m.Y")." - ". "</h4></p>");

}elseif ($_POST['aikaPef']=="vko2" && isset($_POST['submitTime']) && isset($_POST['valinta'][0]) && !isset($_POST['valinta'][1])) {
    $dataPEF['userID'] = $currentUserID;
    $sqlPEF = "SELECT medsInfo, 1st, 2nd, 3rd, timeOfMeasurement, userSex, (DATE(NOW()) - INTERVAL 7 DAY) AS diff FROM `peakFlow`, `users` 
    WHERE users.userID = peakFlow.userID AND users.userID = :userID AND medsInfo='ennen'
    AND timeOfMeasurement >= (DATE(NOW()) - INTERVAL 14 DAY)
    ORDER BY timeOfMeasurement DESC";
    $kyselyPEF=$DBH->prepare($sqlPEF);
    $kyselyPEF->execute($dataPEF);
                    
    echo ("<p><h4>PEF-mittaukset ajalta ".date("d.m.Y")." - ". "</h4></p>");
*/
// jos on valittu aikaväli, mutta ei ole valittu ennen eikä jälkeen
}elseif ($_POST['aikaPef']=="aika" && isset($_POST['submitTime']) && !isset($_POST['valinta'][0]) && !isset($_POST['valinta'][1])
|| $_POST['aikaPef']=="aika" && isset($_POST['submitTime']) && isset($_POST['valinta'][0]) && isset($_POST['valinta'][1])) {
    $fromDate = $_POST['from'];
    $toDate= $_POST['to'];
    $dataPEF['userID'] = $currentUserID;
    $sqlPEF = "SELECT medsInfo, 1st, 2nd, 3rd, timeOfMeasurement, userSex FROM `peakFlow`, `users` 
    WHERE timeOfMeasurement >= date('" . $fromDate . "') AND timeOfMeasurement < (date('" . $toDate . "') + INTERVAL 1 DAY)
    AND users.userID = peakFlow.userID AND users.userID = :userID
    ORDER BY timeOfMeasurement DESC";
    $kyselyPEF=$DBH->prepare($sqlPEF);
    $kyselyPEF->execute($dataPEF);
    echo ("<p><h3>PEF-mittaukset ajalta:</h3></p>");
        $fromDate2 = date("d.m.yy", strtotime($fromDate));
        $toDate2 = date("d.m.yy", strtotime($toDate));
        echo ("<h3>".$fromDate2. " - " .$toDate2. "</h3>");
// jos on valittu aikaväli ja on valittu "ennen"    
}elseif ($_POST['aikaPef']=="aika" && isset($_POST['submitTime']) && isset($_POST['valinta'][0]) && !isset($_POST['valinta'][1])) {
    $fromDate = $_POST['from'];
    $toDate= $_POST['to'];
    $dataPEF['userID'] = $currentUserID;
    $sqlPEF = "SELECT medsInfo, 1st, 2nd, 3rd, timeOfMeasurement, userSex FROM `peakFlow`, `users` 
    WHERE timeOfMeasurement >= date('" . $fromDate . "') AND timeOfMeasurement < (date('" . $toDate . "') + INTERVAL 1 DAY)
    AND users.userID = peakFlow.userID AND users.userID = :userID AND medsInfo='ennen'
    ORDER BY timeOfMeasurement DESC";
    $kyselyPEF=$DBH->prepare($sqlPEF);
    $kyselyPEF->execute($dataPEF);
    echo ("<p><h3>PEF-mittaukset ajalta:</h3></p>");
        $fromDate2 = date("d.m.yy", strtotime($fromDate));
        $toDate2 = date("d.m.yy", strtotime($toDate));
        echo ("<h3>".$fromDate2. " - " .$toDate2. "</h3>");
//jos on valittu aikaväli ja on valittu "jälkeen"
}elseif ($_POST['aikaPef']=="aika" && isset($_POST['submitTime']) && isset($_POST['valinta'][1]) && !isset($_POST['valinta'][0])) {
    $fromDate = $_POST['from'];
    $toDate= $_POST['to'];
    $dataPEF['userID'] = $currentUserID;
    $sqlPEF = "SELECT medsInfo, 1st, 2nd, 3rd, timeOfMeasurement, userSex FROM `peakFlow`, `users` 
    WHERE timeOfMeasurement >= date('" . $fromDate . "') AND timeOfMeasurement < (date('" . $toDate . "') + INTERVAL 1 DAY)
    AND users.userID = peakFlow.userID AND users.userID = :userID AND medsInfo='jälkeen'
    ORDER BY timeOfMeasurement DESC";
    $kyselyPEF=$DBH->prepare($sqlPEF);
    $kyselyPEF->execute($dataPEF);
    echo ("<p><h3>PEF-mittaukset ajalta:</h3></p>");
        $fromDate2 = date("d.m.yy", strtotime($fromDate));
        $toDate2 = date("d.m.yy", strtotime($toDate));
        echo ("<h3>".$fromDate2. " - " .$toDate2. "</h3>");
//jos on valittu aikaväli ja "ennen" ja "jälkeen"
}elseif ($_POST['aikaPef']=="aika" && isset($_POST['submitTime']) && isset($_POST['valinta'][1]) && isset($_POST['valinta'][0])) {
    $fromDate = $_POST['from'];
    $toDate= $_POST['to'];
    $dataPEF['userID'] = $currentUserID;
    $sqlPEF = "SELECT medsInfo, 1st, 2nd, 3rd, timeOfMeasurement, userSex FROM `peakFlow`, `users` 
    WHERE timeOfMeasurement >= date('" . $fromDate . "') AND timeOfMeasurement < (date('" . $toDate . "') + INTERVAL 1 DAY)
    AND users.userID = peakFlow.userID AND users.userID = :userID
    ORDER BY timeOfMeasurement DESC";
    $kyselyPEF=$DBH->prepare($sqlPEF);
    $kyselyPEF->execute($dataPEF);
    echo ("<p><h3>PEF-mittaukset ajalta:</h3></p>");
        $fromDate2 = date("d.m.yy", strtotime($fromDate));
        $toDate2 = date("d.m.yy", strtotime($toDate));
        echo ("<h3>".$fromDate2. " - " .$toDate2. "</h3>");
}
//echo ("<p><h4>PEF-mittaukset ajalta ".date("d.m.Y")." - ". "</h4></p>");
echo("<table>
    <tr>
        <th>ajankohta</th>
        <th>lääkitys</th>
        <th colspan=\"3\">mittaukset</th>
        <th>paras arvo</th>
        <th>keskiarvo</th>
    </tr>");
        // lasketaan %raja-arvot mittausten keskiarvolle --> keskiarvon väri tulosten mukaan
while	($row=$kyselyPEF->fetch()){	
        $formatted_datetime = date("d.m.y, H:i", strtotime($row['timeOfMeasurement']));
        $ka=($row['1st']+$row['2nd']+$row['3rd'])/3;
        $goodN=$pef_euN*0.85;
        $mediumN=$pef_euN*0.70;
        $badN=$pef_euN*0.50;
        $goodM=$pef_euM*0.85;
        $mediumM=$pef_euM*0.70;
        $badM=$pef_euM*0.50;
        echo("<tr>
        <td align=\"right\">".$formatted_datetime."</td>
        <td>".$row['medsInfo']."</td>
        <td align=\"right\">".$row['1st']."</td>
        <td align=\"right\">".$row['2nd']."</td>
        <td align=\"right\">".$row['3rd']."</td>
        <td>".$row['1st']."</td>");
        echo("<td>");
       /*if($row['1st'] > $row['2nd']) {
            //echo($row['2nd']. " l/min");
            echo(ROUND($pef_euN, 0));
        }*/
        if($row['userSex'] == "m") { 
            if($ka > $goodM) {
                //echo($ka);
                echo '<span style="color:green; font-weight: bold;">'.ROUND($ka, 0).'</span>';
            }elseif($ka >= $badM && $ka <= $goodM) {
                //echo($ka);
                echo '<span style="color:DarkOrange;  font-weight: bold;">'.ROUND($ka, 0).'</span>';
            }elseif($ka < $badM) {
                //echo($ka);
                echo '<span style="color:red;  font-weight: bold;">'.ROUND($ka, 0).'</span>';
            }else {
                echo(ROUND($ka, 0));
            }
        }

        if($row['userSex'] == "n") {
            if($ka > $goodN) {
                
                echo '<span style="color:green; font-weight: bold;">'.ROUND($ka, 0).'</span>';
            }elseif($ka >= $badN && $ka <= $goodN) {
                //echo($ka);
                echo '<span style="color:DarkOrange;  font-weight: bold;">'.ROUND($ka, 0).'</span>';
            }elseif($ka < $badN) {
                //echo($ka);
                echo '<span style="color:red;  font-weight: bold;">'.ROUND($ka, 0).'</span>';
            }else {
                echo(ROUND($ka, 0));
            }
        }

    
        echo("</td></tr>");

    }
echo("</table><br/>");

?>

<br/>
<?php include("includes/ifooter.php");?>
