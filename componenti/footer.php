<?php
if (!isset($nome_gioco))
	die("Questo componente non può essere richiamato direttamente.");
?>
<div id="footer">
    <div class="container"> 
        <p id="info"><span id="nomegioco"><?php echo $nome_gioco; ?></span> - Powered by PMS © </p>
    </div>
</div>
