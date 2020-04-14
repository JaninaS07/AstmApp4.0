<?php 
// jos on painettu lääkitystiedot, mutta ei ole painettu submitPefDatoja
// niin näytetään mittaus form
if (isset($_SESSION['medsInfo']) && !isset($_POST['submitPefData1']) &&
 !isset($_POST['submitPefData4']) && !isset($_POST['submitPefData5'])){
    include("forms2/fpefMittaus3.php");
    include("pefcodes/pefsessionunset.php");
}

//jos on painettu "tallenna"
if (isset($_POST['submitPefData1'])){

    // tarkistetaan, että tulos on viitearvojen sisällä
    if($_POST['given1st'] > 50 && $_POST['given1st'] <= 1000 &&
    $_POST['given2nd'] > 50 && $_POST['given2nd'] <= 1000 &&
    $_POST['given3rd'] > 50 && $_POST['given3rd'] <= 1000) 
    {

    $_SESSION['1st']=$_POST['given1st'];
    $_SESSION['2nd']=$_POST['given2nd'];
    $_SESSION['3rd']=$_POST['given3rd'];
    /*$mittaus1=$_POST['given4th'];
    $mittaus2=$_POST['given5th'];*/

    $tulokset = array($_SESSION['1st'], $_SESSION['2nd'],  $_SESSION['3rd']);
    
    rsort($tulokset); // järjestetään mittaukset suurimmasta pienimpään
    $_SESSION['1st']=$tulokset[0];
    $_SESSION['2nd']=$tulokset[1];
    $_SESSION['3rd']=$tulokset[2];
    // lasketaan 1. ja 2. tuloksen erotus
    $tulos1 = abs($tulokset[0] - $tulokset[1]);
    // jos erotus <= kuin 20
        if ($tulos1 <= 20) {  
            //tallennetaan tulokset kantaan
               $data3['email'] = $_SESSION['suserEmail'];
                $sql3 = "SELECT userID FROM users where userEmail =  :email";
                $kysely3=$DBH->prepare($sql3);
                $kysely3->execute($data3);
                $tulos2=$kysely3->fetch();
                $currentUserID=$tulos2[0];
                try {
                    $data4['medsInfo']=$_SESSION['medsInfo'];
                    $data4['1st']=$_SESSION['1st'];
                    $data4['2nd']=$_SESSION['2nd'];
                    $data4['3rd']=$_SESSION['3rd'];
                    $data4['userID']=$currentUserID;
                    $sql5="INSERT INTO peakFlow (medsInfo, 1st, 2nd, 3rd, userID)
                    VALUES (:medsInfo, :1st, :2nd, :3rd, :userID);";
                    $kysely4 = $DBH->prepare($sql5); 
                    $kysely4->execute($data4);
                    unset($_SESSION['1st']); // tuhotaan kaikki sessio muuttujat
                    unset($_SESSION['2nd']);
                    unset($_SESSION['3rd']);
                    unset($_SESSION['4th']);
                    unset($_SESSION['5th']);
                    unset($_SESSION['medsInfo']);
                    //Palataan yhteenveto-sivulle
                    header("Location: yhteenveto.php");
                } catch(PDOException $e) {
                    file_put_contents('log/DBErrors.txt', 'index.php: '.$e->getMessage()."\n", FILE_APPEND);
                }
            //}

        //include("forms2/ftallennusLomake.php");
        //include("pefcodes/pefsessionunset.php");
        
    //jos erotus on yli 20
    //niin suoritetaan ylimääräinen mittaus
        }elseif ($tulos1 > 20) {  
            //näytetään taas mittaustulokset
            echo("<h3>Mittaustulokset: </br></h3>");
            echo("1. mittaus: " . $_SESSION['1st'] . " l/min<br/>");
            echo("2. mittaus: " . $_SESSION['2nd'] . " l/min<br/>");
            echo("3. mittaus: " . $_SESSION['3rd'] . " l/min<br/>");
            $_SESSION['1st']=$tulokset[0];
            $_SESSION['2nd']=$tulokset[1];
            $_SESSION['3rd']=$tulokset[2];

            echo("<br/><h5>Kahden parhaimman puhallusarvon tulokset poikkeavat toisistaan yli 20 l/min.</h5>");
            echo("<h4>Ole hyvä ja suorita uusi PEF-mittaus:</h4>");
            
            include("forms2/fpefMittausYlim.php");
            include("pefcodes/pefsessionunset.php");
        } 
    }else {
        echo("<h4>Anna puhallusarvot välillä 50-1000!</h4>");
        include("forms2/fpefMittaus3.php");
        include("pefcodes/pefsessionunset.php");
    }
}

    //tehdään muuttujat 1. ja 2. ylimääräisestä mittauksesta
    $mittaus1=$_POST['given4th'];
    $mittaus2=$_POST['given5th'];
    
    //laitetaan 1. 2. 3. mittaustulokset taulukkoon
   $tulokset = array($_SESSION['1st'], $_SESSION['2nd'],  $_SESSION['3rd']);
    rsort($tulokset);
    // järjestetään mittaukset suurimmasta pienimpään

    // lasketaan 1. ja 2. tuloksen erotus
    //$tulos1 = abs($tulokset[0] - $tulokset[1]);

// jos erotus alle 20 ja on lisätty hyväksytysti 3. mittaus, 
//niin näytetään tulokset ja liitetään tallenna tulokset button --> tietokantaan

   /* if (abs($tulos1 <= 20 ) && isset($_SESSION['3rd'])) {  
        echo("<h3>Mittaustulokset: </br></h3>");
        
        echo("1. mittaus: " . $_SESSION['1st'] . " l/min<br/>");
        echo("2. mittaus: " . $_SESSION['2nd'] . " l/min<br/>");
        echo("3. mittaus: " . $_SESSION['3rd'] . " l/min<br/>");
        $_SESSION['1st']=$tulokset[0];
        $_SESSION['2nd']=$tulokset[1];
        $_SESSION['3rd']=$tulokset[2];

        include("forms2/ftallennusLomake.php");
        include("pefcodes/pefsessionunset.php");
        
    //jos erotus on yli 20 eikä ole painettu submitPefData4 eikä submitPefData5,
    //niin suoritetaan ylimääräinen mittaus
    }elseif (abs($tulos1 > 20) && !isset($_POST['submitPefData4']) && !isset($_POST['submitPefData5'])) {  
        //näytetään taas mittaustulokset
        echo("<h3>Mittaustulokset: </br></h3>");
        echo("1. mittaus: " . $_SESSION['1st'] . " l/min<br/>");
        echo("2. mittaus: " . $_SESSION['2nd'] . " l/min<br/>");
        echo("3. mittaus: " . $_SESSION['3rd'] . " l/min<br/>");

        echo("<br/><h5>Kahden parhaimman puhallusarvon tulokset poikkeavat toisistaan yli 20 l/min.<br/>");
        echo("<br/>Ole hyvä ja suorita ylimääräinen PEF-mittaus:</h5>");
        
        include("forms2/fpefMittausYlim.php");
        include("pefcodes/pefsessionunset.php");
    } */
        // tarkistetaan, että annettu arvo on välilllä 0-1000
    if (isset($_POST['submitPefData4'])){
            // lasketaan ylim. mittauksen ja parhaimman mittauksen erotus
            $erotus=abs($tulokset[0]-$mittaus1);
            $erotus1=abs($mittaus1-$tulokset[0]);
            if($_POST['given4th'] > 50 && $_POST['given4th'] <= 1000) 
            {   
                //lisätään mittaus sessioon ja järjestetään tulokset arrayhin
                $_SESSION['4th']=$_POST['given4th'];
                $tulokset = array($_SESSION['1st'], $_SESSION['2nd'],  $_SESSION['3rd'], $_SESSION['4th']);
                rsort($tulokset);
                $_SESSION['1st']=$tulokset[0];
                $_SESSION['2nd']=$tulokset[1];
                $_SESSION['3rd']=$tulokset[2];

                // jos erotus <= 20, niin siirretään tiedot kantaan
                if($erotus <= 20 || $erotus1 <= 20) {

                    $data3['email'] = $_SESSION['suserEmail'];
                    $sql3 = "SELECT userID FROM users where userEmail =  :email";
                    $kysely3=$DBH->prepare($sql3);
                    $kysely3->execute($data3);
                    $tulos2=$kysely3->fetch();
                    $currentUserID=$tulos2[0];
                    try {
                        $data4['medsInfo']=$_SESSION['medsInfo'];
                        $data4['1st']=$_SESSION['1st'];
                        $data4['2nd']=$_SESSION['2nd'];
                        $data4['3rd']=$_SESSION['3rd'];
                        $data4['userID']=$currentUserID;
                        $sql5="INSERT INTO peakFlow (medsInfo, 1st, 2nd, 3rd, userID)
                        VALUES (:medsInfo, :1st, :2nd, :3rd, :userID);";
                        $kysely4 = $DBH->prepare($sql5); 
                        $kysely4->execute($data4);
                        unset($_SESSION['1st']); // tuhotaan kaikki sessio muuttujat
                        unset($_SESSION['2nd']);
                        unset($_SESSION['3rd']);
                        unset($_SESSION['4th']);
                        unset($_SESSION['5th']);
                        unset($_SESSION['medsInfo']);
                        //Palataan yhteenveto-sivulle
                        header("Location: yhteenveto.php");
                    } catch(PDOException $e) {
                        file_put_contents('log/DBErrors.txt', 'index.php: '.$e->getMessage()."\n", FILE_APPEND);
                    }
                    //jos erotus > 20, niin pyydetään suorittamaan uusi mittaus
                }elseif($erotus > 20 || $erotus1 > 20) {
                    echo("<h3>Mittaustulokset: </br></h3>");
                    echo("1. mittaus: " . $_SESSION['1st'] . " l/min<br/>");
                    echo("2. mittaus: " . $_SESSION['2nd'] . " l/min<br/>");
                    echo("3. mittaus: " . $_SESSION['3rd'] . " l/min<br/>");
                    echo("1. ylimääräinen mittaus: " . $_SESSION['4th'] ." l/min<br />");
                    echo("<h5>Annettu puhallusarvo poikkeaa parhaimmasta puhallusarvosta edelleen yli 20 l/min.</h5>");
                    echo("<h4>Ole hyvä ja suorita uusi PEF-mittaus:</h4>");
                    include("forms2/fpefMittaus2.php");
                    include("pefcodes/pefsessionunset.php");
                        
                    // jos erotus on pienempi 20, näytetään mittaustulokset
                    //tallennetaan tulokset taulukkooon
                    //järjestetään alkiot suurimmasta pienimpään
                    //tallennetaan 3 parasta tulosta
                }//elseif($erotus <= 20 || $erotus1 <= 20){
                        
                        /*echo("<h3>Mittaustulokset:<br/></h3>");
                        echo("1. mittaus: " . $_SESSION['1st'] . " l/min<br/>");
                        echo("2. mittaus: " . $_SESSION['2nd'] . " l/min<br/>");
                        echo("3. mittaus: " . $_SESSION['3rd'] . " l/min<br/>");
                        echo("1. ylimääräinen mittaus: " . $_SESSION['4th'] ." l/min<br /></br>");
                        // siirretään arraysta 3 parasta tulosta sessiomuuttujiin
                        echo("<h3>'Tallenna puhallusarvot'<br/>tallentaa 3 parasta puhallusarvoa"."<br/></h3>");*/
                        /*$_SESSION['1st']=$tulokset[0];
                        $_SESSION['2nd']=$tulokset[1];
                        $_SESSION['3rd']=$tulokset[2];*/
                        
                        //liitetään mukaan tallenna kantaan -buttoni
                        //include("forms2/ftallennusLomake.php");
                        //include("pefcodes/pefsessionunset.php");

                }else {
                echo("<h4>Anna puhallusarvo välillä 50-1000!</h4>");
                include("forms2/fpefMittausYlim.php");
                include("pefcodes/pefsessionunset.php");
                
                }   
        }
    
    /*$tulokset1 = array($_SESSION['1st'], $_SESSION['2nd'],  $_SESSION['3rd'], , $_SESSION['4th']);
    rsort($tulokset1);*/

    //2. ylimääräinen mittaus
    if(isset($_POST['submitPefData5'])) {
            // raja-arvoille ehdot
            if($_POST['given5th'] > 50 && $_POST['given5th'] <= 1000) 
            {
                $_SESSION['5th']=$_POST['given5th']; 
                    //sijoitetaan kaikki 5 tulosta taulukkoon
                    //järjestetään alkiot
                    $tulokset = array($_SESSION['1st'], $_SESSION['2nd'],  $_SESSION['3rd'], $_SESSION['4th'], $_SESSION['5th']);
                    rsort($tulokset);
                    /*echo("<h3>Mittaustulokset:<br/></h3>");
                    echo("1. mittaus: " . $_SESSION['1st'] . " l/min<br/>");
                    echo("2. mittaus: " . $_SESSION['2nd'] . " l/min<br/>");
                    echo("3. mittaus: " . $_SESSION['3rd'] ." l/min<br />");
                    echo("1. ylimääräinen mittaus: " . $_SESSION['4th'] ." l/min</br>");
                    echo("2. ylimääräinen mittaus: " . $_SESSION['5th'] ." l/min</br>");
                    // siirretään arraysta 3 parasta tulosta sessiomuuttujiin
                    echo("<h3>'Tallenna puhallusarvot'<br/>tallentaa 3 parasta puhallusarvoa"."<br/></h3>");*/
                    $_SESSION['1st']=$tulokset[0];
                    $_SESSION['2nd']=$tulokset[1];
                    $_SESSION['3rd']=$tulokset[2];
                    //siirretään tulokset kantaan
                    $data3['email'] = $_SESSION['suserEmail'];
                    $sql3 = "SELECT userID FROM users where userEmail =  :email";
                    $kysely3=$DBH->prepare($sql3);
                    $kysely3->execute($data3);
                    $tulos2=$kysely3->fetch();
                    $currentUserID=$tulos2[0];
                    try {
                        $data4['medsInfo']=$_SESSION['medsInfo'];
                        $data4['1st']=$_SESSION['1st'];
                        $data4['2nd']=$_SESSION['2nd'];
                        $data4['3rd']=$_SESSION['3rd'];
                        $data4['userID']=$currentUserID;
                        $sql5="INSERT INTO peakFlow (medsInfo, 1st, 2nd, 3rd, userID)
                        VALUES (:medsInfo, :1st, :2nd, :3rd, :userID);";
                        $kysely4 = $DBH->prepare($sql5); 
                        $kysely4->execute($data4);
                        unset($_SESSION['1st']); // tuhotaan kaikki sessio muuttujat
                        unset($_SESSION['2nd']);
                        unset($_SESSION['3rd']);
                        unset($_SESSION['4th']);
                        unset($_SESSION['5th']);
                        unset($_SESSION['medsInfo']);
                        //Palataan yhteenveto-sivulle
                        header("Location: yhteenveto.php");
                    } catch(PDOException $e) {
                        file_put_contents('log/DBErrors.txt', 'index.php: '.$e->getMessage()."\n", FILE_APPEND);
                    }

                    //include("forms2/ftallennusLomake.php");
                    include("pefcodes/pefsessionunset.php");
               
           }else {
           echo("<h4>Anna puhallusarvo välillä 50-1000!</h4>");
           include("forms2/fpefMittaus2.php");
          }
    } 
?>