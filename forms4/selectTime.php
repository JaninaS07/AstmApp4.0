<form method="POST" autocomplete="off" autofill="off">
<h3>Valitse ajankohta:</h3>
<div id="check"></div>
<div class="centered">
<div class="leftaligned">
<input type="radio" name="aikaPef" value="aika" id="aika" checked/><label>Aikavälillä:</label><br/>
<div id="calendar">
<label for="from"></label>
<input type="text" id="from" name="from">
<label for="to"> - </label>
<input type="text" id="to" name="to">
</div>
<!--<input type="radio" name="aikaPef" value="vko1" id="vko1"/><label>1 vkon ajalta</label><br/>
<input type="radio" name="aikaPef" value="vko2" id="vko2"/><label>2 vkon ajalta</label><br/>-->

<input type="checkbox" name="valinta[0]" value="ennen"><label>Ennen lääkkeenottoa</label><br/>
<input type="checkbox" name="valinta[1]" value="jälkeen"><label>Lääkkeenoton jälkeen</label><br/>


</div>
</div>
</div>
<br/>
<button type="submit" name="submitTime" id="button1">Näytä tiedot</button>
<script src="javascript/calendar.js"></script>
</form>