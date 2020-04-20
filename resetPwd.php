<?php

    if(isset($_POST["resetRequestSubmit"])){

        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32);

        $url = "https://users.metropolia.fi/~niinajhe/WSKnjh/AstmApp4.0/changePwd.php?selector=" . $selector . "&validator=" . bin2hex($token);

        $expires = date("U") + 1800;

        require 'db'

        $userEmail = $_POST["email"];

        $sql ="DELETE FROM pwdReset WHERE pwdResetEmail=?";
        $stmt = 

        if(!){

        } else {

        }

        $sql ="INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);";

        
        $to = $userEmail;
        $subject = 'Reset your password for AstmApp'

        $message = '<p>Hei, tästä linkistä <a href=""</a> pääset uusimaan salasanasi AstmApp:iin. Jos et pyytänyt salasanan palautusta,
         voit jättää tämän viestin huomioimatta.</p>';

        mail($to, $subject, $message, $headers);

        header("Location:receiveEmail.php");

    } else {
        header("Location:index.php");
    }