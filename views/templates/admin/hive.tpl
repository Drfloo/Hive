<hr />
<div class="container">
    <h3>{var_dump($supp['productname'])}</h3>
    <div class="row">
        <div class="col-md-12">
            <select class="form-control" data-live-search="true">
                <option data-tokens="ketchup mustard">Hot Dog, Fries and a Soda</option>
                <option data-tokens="mustard">Burger, Shake and a Smile</option>
                <option data-tokens="frosting">Sugar, Spice and all things nice</option>
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
            <th>Quantité</th>
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
</div>


