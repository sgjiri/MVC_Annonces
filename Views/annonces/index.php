<h1>List des annonces</h1>

<?php foreach($annonces as $annonce):?>
<article>
    <h2><a href="/PHP/MVC_Annonces/annonces/lire/<?= $annonce->id?>"><?= $annonce->titre?></a></h2>
    <p><?= $annonce->description?></p>
</article>



<?php endforeach?>   