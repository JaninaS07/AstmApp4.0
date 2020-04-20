<?php include("includes/iheader.php");?>
<h2>Sivustoa vielä päivitetään...</h2>

<?php
//kirjautuneen käyttäjän userID?
$dataYhteenvetoOire['email'] = $_SESSION['suserEmail'];
$sqlYhteenvetoOire = "SELECT userID FROM users where userEmail =  :email";
$kyselyYhteenvetoOire=$DBH->prepare($sqlYhteenvetoOire);
$kyselyYhteenvetoOire->execute($dataYhteenvetoOire);
$tulosYhteenvetoOire=$kyselyYhteenvetoOire->fetch();
$currentUserID=$tulosYhteenvetoOire[0];
?>

<?php
include("forms4/selectTimeOire.php");
//jos ei ole painettu "näytä tiedot", näytetään 5 viimeisintä oiretta
 if (!isset($_POST['submitTimeOire'])) {
$dataOireet['userID']=$currentUserID;
$sqlOireet="SELECT * FROM `oireet`
WHERE userID = :userID ORDER BY oireDate DESC LIMIT 5";
    $kyselyOireet=$DBH->prepare($sqlOireet);				
    $kyselyOireet->execute($dataOireet);
    echo ("<p><h3>Oireet ja laukaisijat (Näytetään 5 viimeisintä):</h3></p>");

    echo("<table>
            <tr>
                <th>ajankohta</th>
                <th>oireet</th>
                <th>laukaisijat</th>
                <th>lisätiedot</th>

            

            </tr>");
            //tulostetaan valitut oireet, jotka eivät ole null
        while	($row=$kyselyOireet->fetch()){	
                $formatted_datetime = date("d.m.y, H:i", strtotime($row['oireDate']));
                echo("<tr><td><ul><li>".$formatted_datetime."</li></ul></td>");
                echo("<td>");
                echo("<ul>");
                if(strlen($row['cough']) > 1) {
                    echo("<li>".$row['cough']."</li>");
                }
                if(strlen($row['sniffle']) > 1) {
                    echo"<li>".($row['sniffle']."</li>");
                }
                if(strlen($row['shortBreath']) > 1) {
                    echo"<li>".($row['shortBreath']."</li>");
                }
                if(strlen($row['slime']) > 1) {
                    echo("<li>".$row['slime']."</li>");
                }
                if(strlen($row['wheeze']) > 1) {
                    echo("<li>".$row['wheeze']."</li>");
                }
                echo("</ul>");
                echo("</td>");
                echo("<td>");
                echo("<ul>");
                // tulostetaan valitut triggerit, jotka eivät ole null
                if(strlen($row['animal']) > 1) {
                    echo("<li>".$row['animal']."</li>");
                }
                if(strlen($row['dust']) > 1) {
                    echo("<li>".$row['dust']."</li>");
                }
                if(strlen($row['pollen']) > 1) {
                    echo("<li>".$row['pollen']."</li>");
                }
                if(strlen($row['smoking']) > 1) {
                    echo("<li>".$row['smoking']."</li>");
                }
                if(strlen($row['medication']) > 1) {
                    echo("<li>".$row['medication']."</li>");
                }
                if(strlen($row['fever']) > 1) {
                    echo("<li>".$row['fever']."</li>");
                }
                if(strlen($row['coldAir']) > 1) {
                    echo("<li>".$row['coldAir']."</li>");
                }
                if(strlen($row['sports']) > 1) {
                    echo("<li>".$row['sports']."</li>");
                }
                if(strlen($row['stress']) > 1) {
                    echo("<li>".$row['stress']."</li>");
                }
                if(strlen($row['flavor']) > 1) {
                    echo("<li>".$row['flavor']."</li>");
                }
                if(strlen($row['alcohol']) > 1) {
                    echo("<li>".$row['alcohol']."</li>");
                }
                if(strlen($row['noTriggers']) > 1) {
                    echo("<li>".$row['noTriggers']."</li>");
                }
                echo("</ul>");
                echo("</td>");
                echo("<td>");
                echo("<ul>");
                // tulostetaan vapaana tekstinä kirjoitettu lisätieto, jos se ei ole null
                if(strlen($row['oireText']) > 1) {
                    echo("<li>".$row['oireText']."</li>");
                }
                if(strlen($row['triggerText']) > 1) {
                    echo("<li>".$row['triggerText']."</li>");
                }
                echo("</ul>");
                echo("</td>");
                //tähän triggerit samalla tavalla
                echo("</tr>");
            }
        echo("</table><br/>");
//jos on valittu aikaväli, mutta ei ole valittu oireet tai laukaisijat
 }elseif ($_POST['aikaOire']=="aika" && isset($_POST['submitTimeOire']) && !isset($_POST['valintaOire'][0]) && !isset($_POST['valintaOire'][1])
 || $_POST['aikaOire']=="aika" && isset($_POST['submitTimeOire']) && isset($_POST['valintaOire'][0]) && isset($_POST['valintaOire'][1])) {
        $fromDate = $_POST['from'];
        $toDate= $_POST['to'];
        $dataOireet['userID'] = $currentUserID;
        $sqlOireet = "SELECT * FROM `oireet`
        WHERE oireDate >= date('" . $fromDate . "') AND oireDate < (date('" . $toDate . "') + INTERVAL 1 DAY)
        AND userID = :userID
        ORDER BY oireDate DESC";
        $kyselyOireet=$DBH->prepare($sqlOireet);
        $kyselyOireet->execute($dataOireet);
        echo ("<p><h3>Oireet ja laukaisijat ajalta:</h3></p>");
        $fromDate1 = date("d.m.yy", strtotime($fromDate));
        $toDate1 = date("d.m.yy", strtotime($toDate));
        echo ("<h3>".$fromDate1. " - " .$toDate1. "</h3>");

        echo("<table>
            <tr>
                <th>ajankohta</th>
                <th>oireet</th>
                <th>laukaisijat</th>
                <th>lisätiedot</th>

            

            </tr>");
            //tulostetaan valitut oireet, jotka eivät ole null
        while	($row=$kyselyOireet->fetch()){	
                $formatted_datetime = date("d.m.y, H:i", strtotime($row['oireDate']));
                echo("<tr><td><ul><li>".$formatted_datetime."</li></ul></td>");
                echo("<td>");
                echo("<ul>");
                if(strlen($row['cough']) > 1) {
                    echo("<li>".$row['cough']."</li>");
                }
                if(strlen($row['sniffle']) > 1) {
                    echo"<li>".($row['sniffle']."</li>");
                }
                if(strlen($row['shortBreath']) > 1) {
                    echo"<li>".($row['shortBreath']."</li>");
                }
                if(strlen($row['slime']) > 1) {
                    echo("<li>".$row['slime']."</li>");
                }
                if(strlen($row['wheeze']) > 1) {
                    echo("<li>".$row['wheeze']."</li>");
                }
                echo("</ul>");
                echo("</td>");
                echo("<td>");
                echo("<ul>");
                // tulostetaan valitut triggerit, jotka eivät ole null
                if(strlen($row['animal']) > 1) {
                    echo("<li>".$row['animal']."</li>");
                }
                if(strlen($row['dust']) > 1) {
                    echo("<li>".$row['dust']."</li>");
                }
                if(strlen($row['pollen']) > 1) {
                    echo("<li>".$row['pollen']."</li>");
                }
                if(strlen($row['smoking']) > 1) {
                    echo("<li>".$row['smoking']."</li>");
                }
                if(strlen($row['medication']) > 1) {
                    echo("<li>".$row['medication']."</li>");
                }
                if(strlen($row['fever']) > 1) {
                    echo("<li>".$row['fever']."</li>");
                }
                if(strlen($row['coldAir']) > 1) {
                    echo("<li>".$row['coldAir']."</li>");
                }
                if(strlen($row['sports']) > 1) {
                    echo("<li>".$row['sports']."</li>");
                }
                if(strlen($row['stress']) > 1) {
                    echo("<li>".$row['stress']."</li>");
                }
                if(strlen($row['flavor']) > 1) {
                    echo("<li>".$row['flavor']."</li>");
                }
                if(strlen($row['alcohol']) > 1) {
                    echo("<li>".$row['alcohol']."</li>");
                }
                if(strlen($row['noTriggers']) > 1) {
                    echo("<li>".$row['noTriggers']."</li>");
                }
                echo("</ul>");
                echo("</td>");
                echo("<td>");
                echo("<ul>");
                // tulostetaan vapaana tekstinä kirjoitettu lisätieto, jos se ei ole null
                if(strlen($row['oireText']) > 1) {
                    echo("<li>".$row['oireText']."</li>");
                }
                if(strlen($row['triggerText']) > 1) {
                    echo("<li>".$row['triggerText']."</li>");
                }
                echo("</ul>");
                echo("</td>");
                //tähän triggerit samalla tavalla
                echo("</tr>");
            }
        echo("</table><br/>");

 //jos on valittu aikaväli ja valittu "oireet"   
 }elseif ($_POST['aikaOire']=="aika" && isset($_POST['submitTimeOire']) && isset($_POST['valintaOire'][0]) && !isset($_POST['valintaOire'][1])) {
    $fromDate = $_POST['from'];
    $toDate= $_POST['to'];
    $dataOireet['userID'] = $currentUserID;
    $sqlOireet = "SELECT cough, sniffle, shortBreath, slime, wheeze, oireText, userID, oireDate FROM `oireet`
    WHERE oireDate >= date('" . $fromDate . "') AND oireDate < (date('" . $toDate . "') + INTERVAL 1 DAY)
    AND userID = :userID
    ORDER BY oireDate DESC";
    $kyselyOireet=$DBH->prepare($sqlOireet);
    $kyselyOireet->execute($dataOireet);
    echo ("<p><h3>Oireet ajalta:</h3></p>");
    $fromDate1 = date("d.m.yy", strtotime($fromDate));
    $toDate1 = date("d.m.yy", strtotime($toDate));
    echo ("<h3>".$fromDate1. " - " .$toDate1. "</h3>");

    echo("<table>
    <tr>
        <th>ajankohta</th>
        <th>oireet</th>
        <th>lisätiedot</th>

    </tr>");
    //tulostetaan valitut oireet, jotka eivät ole null
while	($row=$kyselyOireet->fetch()){	
        $formatted_datetime = date("d.m.y, H:i", strtotime($row['oireDate']));
        echo("<tr><td><ul><li>".$formatted_datetime."</li></ul></td>");
        echo("<td>");
        echo("<ul>");
        if(strlen($row['cough']) > 1) {
            echo("<li>".$row['cough']."</li>");
        }
        if(strlen($row['sniffle']) > 1) {
            echo"<li>".($row['sniffle']."</li>");
        }
        if(strlen($row['shortBreath']) > 1) {
            echo"<li>".($row['shortBreath']."</li>");
        }
        if(strlen($row['slime']) > 1) {
            echo("<li>".$row['slime']."</li>");
        }
        if(strlen($row['wheeze']) > 1) {
            echo("<li>".$row['wheeze']."</li>");
        }
        echo("</ul>");
        echo("</td>");

        echo("<td>");
        echo("<ul>");
        // tulostetaan vapaana tekstinä kirjoitettu lisätieto, jos se ei ole null
        if(strlen($row['oireText']) > 1) {
            echo("<li>".$row['oireText']."</li>");
        }
        echo("</ul>");
        echo("</td>");
        //tähän triggerit samalla tavalla
        echo("</tr>");
    }
echo("</table><br/>");

 }elseif ($_POST['aikaOire']=="aika" && isset($_POST['submitTimeOire']) && isset($_POST['valintaOire'][1]) && !isset($_POST['valintaOire'][0])) {
    $fromDate = $_POST['from'];
    $toDate= $_POST['to'];
    $dataOireet['userID'] = $currentUserID;
    $sqlOireet = "SELECT animal, dust, pollen, perfume, smoking, medication, fever, coldAir, sports, stress, flavor, alcohol, noTriggers, triggerText, userID, oireDate FROM `oireet`
    WHERE oireDate >= date('" . $fromDate . "') AND oireDate < (date('" . $toDate . "') + INTERVAL 1 DAY)
    AND userID = :userID
    ORDER BY oireDate DESC";
    $kyselyOireet=$DBH->prepare($sqlOireet);
    $kyselyOireet->execute($dataOireet);
    echo ("<p><h3>Laukaisijat ajalta:</h3></p>");
    $fromDate1 = date("d.m.yy", strtotime($fromDate));
    $toDate1 = date("d.m.yy", strtotime($toDate));
    echo ("<h3>".$fromDate1. " - " .$toDate1. "</h3>");

    echo("<table>
    <tr>
        <th>ajankohta</th>
        <th>laukaisijat</th>
        <th>lisätiedot</th>


    </tr>");

while	($row=$kyselyOireet->fetch()){	
        $formatted_datetime = date("d.m.y, H:i", strtotime($row['oireDate']));
        echo("<tr><td><ul><li>".$formatted_datetime."</li></ul></td>");
        echo("<td>");
        echo("<ul>");
        // tulostetaan valitut triggerit, jotka eivät ole null
        if(strlen($row['animal']) > 1) {
            echo("<li>".$row['animal']."</li>");
        }
        if(strlen($row['dust']) > 1) {
            echo("<li>".$row['dust']."</li>");
        }
        if(strlen($row['pollen']) > 1) {
            echo("<li>".$row['pollen']."</li>");
        }
        if(strlen($row['smoking']) > 1) {
            echo("<li>".$row['smoking']."</li>");
        }
        if(strlen($row['medication']) > 1) {
            echo("<li>".$row['medication']."</li>");
        }
        if(strlen($row['fever']) > 1) {
            echo("<li>".$row['fever']."</li>");
        }
        if(strlen($row['coldAir']) > 1) {
            echo("<li>".$row['coldAir']."</li>");
        }
        if(strlen($row['sports']) > 1) {
            echo("<li>".$row['sports']."</li>");
        }
        if(strlen($row['stress']) > 1) {
            echo("<li>".$row['stress']."</li>");
        }
        if(strlen($row['flavor']) > 1) {
            echo("<li>".$row['flavor']."</li>");
        }
        if(strlen($row['alcohol']) > 1) {
            echo("<li>".$row['alcohol']."</li>");
        }
        if(strlen($row['noTriggers']) > 1) {
            echo("<li>".$row['noTriggers']."</li>");
        }
        echo("</ul>");
        echo("</td>");
        echo("<td>");
        echo("<ul>");
        // tulostetaan vapaana tekstinä kirjoitettu lisätieto, jos se ei ole null
        if(strlen($row['triggerText']) > 1) {
            echo("<li>".$row['triggerText']."</li>");
        }
        echo("</ul>");
        echo("</td>");
        //tähän triggerit samalla tavalla
        echo("</tr>");
    }
echo("</table><br/>");


 }elseif(empty($_POST['from'])) {
     echo("valitse aikaväli!");
 }

?>
