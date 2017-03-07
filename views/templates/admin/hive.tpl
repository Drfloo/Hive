<div class="container">
    <h3>{var_dump($supplier)}</h3>
    {var_dump($defsupplier)}
    <p>Choississez le meillleur fournissseur pour ce produit</p>
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Fournisseur</th>
            <th>Frais</th>
            <th>Activer/Désactiver</th>
            <th>Position</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$supplier item=supp}
        <tr {if $supp['id_supplier'] == $defsupplier} class="success"{/if}>
            <td>{$supp['name_supplier']}</td>
            <td>{$supp['frais_supplier']}</td>
            <td>
                <form action="#">
                    <input type="checkbox"{if $supp['status_supplier']} checked{/if}>
                </form>
            </td>
            <td>#3</td>
        </tr>
        {/foreach}
        </tbody>
    </table>
</div>

