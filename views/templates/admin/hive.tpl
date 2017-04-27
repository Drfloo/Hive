<hr />
<div class="container">
    <h2>Produit : {$productname}</h2>
    <div class="row">
        <div class="col-md-12">
            <label for="select">Choisir une déclinaison du produit</label>
            <select class="form-control" data-live-search="true" id="select">
                {foreach from=$attribute item=attributeDeclination}
                    <option value="#">
                        {$attributeDeclination["idDeclination"]}
                        {$attributeDeclination["nameDeclination"]}
                    </option>
                {/foreach}
            </select>
        </div>
    </div>
    <p>Choississez le meillleur fournissseur pour ce produit</p>
    <table class="table table-condensed table-striped product m-t-1">
        <thead>
        <tr>
            <th>Position</th>
            <th>Fournisseur</th>
            <th>Frais</th>
            <th>Activer/Désactiver</th>
            <th>Quantité (max: {$attributeDeclination["defaultQuantityDeclination"]})</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$supplier item=supp}
        <tr {if $supp['id_supplier'] == $defsupplier} class="success"{/if}>
            <td>{counter}</td>
            <td>{$supp['name_supplier']}</td>
            <td>{$supp['frais_supplier']}</td>
            <td>
                <form action="#">
                    <input type="checkbox"{if $supp['status_supplier']} checked{/if}>
                </form>
            </td>
            <td>
                <form action="">
                    <input type="text">
                </form>
            </td>
        </tr>
        {/foreach}
        </tbody>
    </table>
    {var_dump($infoDeclination)}
</div>
