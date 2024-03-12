<table class="table table-striped">
    <thead>
        <th>ID</th>
        <th>Titre</th>
        <th>Description</th>
        <th>Actif</th>
        <th>Action</th>
    </thead>
    <tbody>
        <?php
        foreach ($ads as $ad) : ?>
            <tr>
                <td><?= $ad->id ?></td>
                <td><?= $ad->titre ?></td>
                <td><?= $ad->description ?></td>
                <td>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch<?= $ad->id ?>" <?= $ad->actif ? 'checked' : '' ?> data-id="<?= $ad->id ?>">
                        <label class="custom-control-label" for="customSwitch<?= $ad->id ?>"></label>
                    </div>
                </td>
                <td>
                    <a href="/PHP/MVC_Annonces/annonces/edit/<?= $ad->id ?>" class="btn btn-warning">Modifier</a>
                    <a href="/PHP/MVC_Annonces/admin/deleteAd/<?= $ad->id ?>" class="btn btn-danger">Suprimer</a>
                    <?= $ad->titre ?>
                </td>
            </tr>
        <?php
        endforeach ?>
    </tbody>
</table>

