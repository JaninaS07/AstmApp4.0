<form method="post">

<p>Vaihda salasana</p>

  <div class="container">
    <label for="register"><b><span class="red">* </span>Vanha salasana:</span></b></label>
    <input type="password" name="givenOldPassword" placeholder="Syötä vanha salasana" minlength="8" maxlength="40">

    <label for="register"><b><span class="red">* </span>Uusi salasana:</span></b></label>
    <input type="password" name="givenPassword" placeholder="<?= _PASSWORDCREATEINPUT ?>" minlength="8" maxlength="40">
  
    <label for="register"><b><span class="red">*</span> <?= _PASSWORDAGAIN ?></span></b></label>
    <input type="password" name="givenPasswordVerify" placeholder="<?= _PASSWORDAGAININPUT ?>" minlength="8" maxlength="40">

  <div class="btn-group">
      <button type="submit" id="button1" name="submitPwd">Vaihda salasana</button>
      <button type="reset" id="button1" name="reset">Tyhjennä</button>
  </div>
</form>