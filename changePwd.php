<?php include("includes/iheader.php");?>

<?php


include("forms4/fchangePwd.php");

  

if(isset($_POST['submitPwd'])){
  //***Tarkistetaan email myös palvelimella
  //if(!filter_var($_POST['givenEmail'], FILTER_VALIDATE_EMAIL)){
  //  $_SESSION['swarningInput']="Sähköposti ei validi.";
  if(strlen($_POST['givenOldPassword'])<8){
    $_SESSION['swarningInput']="Salasana puuttuu.";
  }elseif(strlen($_POST['givenPassword'])<8){
    $_SESSION['swarningInput']="Salasana puuttuu.";
  }else if($_POST['givenPassword'] != $_POST['givenPasswordVerify']){
    $_SESSION['swarningInput']="Kirjoita antamasi salasana uudestaan.";
  }else{
    unset($_SESSION['swarningInput']);
    
  
  
    try {
        //Tiedot kannasta, hakuehto
        $data['email'] = $_SESSION['suserEmail'];
        $STH = $DBH->prepare("SELECT * FROM users WHERE userEmail = :email;");
        $STH->execute($data);
        $STH->setFetchMode(PDO::FETCH_OBJ);
        $tulosOlio=$STH->fetch();
    
        $_SESSION['suseroldpwd'] = $_POST['givenOldPassword'];
        $_SESSION['susernewpwd'] = $_POST['givenPassword'];
        $_SESSION['suserrepeatnewpwd'] = $_POST['givenPasswordVerify'];

        //lomakkeelle annettu  vanha salasana + suola
        $added='#â‚¬%&&/'; //suolataan annettu salasana
        $oldpwdadded = $_SESSION['suseroldpwd'].$added; //$added löytyy cconfig.php
          
        //tarkistetaan löytyykö vanha salasana kannasta
        //jos löytyy niin tallennetaan uusi salasana
          if(password_verify($oldpwdadded,$tulosOlio->userPwd)){
              $_SESSION['suserEmail']=$tulosOlio->userEmail;
              //$_SESSION['suserPwd']= $_POST['givenPassword'];
              $added='#â‚¬%&&/'; //suolataan annettu uusi salasana
              $newpwdadded = password_hash($_SESSION['susernewpwd'].$added, PASSWORD_BCRYPT);

              $data2['email'] = $_SESSION['suserEmail'];
              $STH = $DBH->prepare("UPDATE users SET userPwd='$newpwdadded' WHERE userEmail=:email;");
              $STH->execute($data2);

              //salasana vaihdettu ja kirjataan käyttäjä ulos
              include("logOutUser.php");
            
            }else{
              $_SESSION['swarningInput']="Väärä vanha salasana";
            }
          
            
        
    }catch(PDOException $e) {
      file_put_contents('log/DBErrors.txt', 'signInUser.php: '.$e->getMessage()."\n", FILE_APPEND);
      $_SESSION['swarningInput'] = 'Database problem';
    }
  }
}
?>


<?php
  //***Näytetäänkö lomakesyötteen aiheuttama varoitus?
  if(isset($_SESSION['swarningInput'])){
  echo("<p class=\"warning\">ILLEGAL INPUT: ". $_SESSION['swarningInput']."</p>");
  }
?>
    
<?php include("includes/ifooter.php");?>