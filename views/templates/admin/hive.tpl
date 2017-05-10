<style>
    .panel-heading{
        cursor:pointer;
    }
    .panel-heading:hover{
        background-color: #fff;
    }
    .up,.down{
        cursor: pointer;
        text-transform: uppercase;
        color: #000000;
    }
    .up:hover,.down:hover{
        color : #E6644E;
    }
</style>

<div class="container">
    <hr />
    {var_dump($test)}
    {var_dump($quantitySupplier)}
    <h2>Produit : {$productname}</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="material-icons">help</i>
                <p>Choissiez parmi la liste des déclinaisons blablabla</p>
            </div>

            {foreach from=$attribute item=attributeDeclination}
            <div class="panel panel-default">
                <div class="panel-heading phead">
                    <strong>{$attributeDeclination["nameDeclination"]}</strong>
                </div>
                <div class="panel-body pbody">
                    <div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Fournisseur</th>
                                <th>Quantité (max: {$attributeDeclination["defaultQuantityDeclination"]})</th>
                                <th>Activer / Desactiver</th>
                                <th>Position</th>
                            </tr>
                            </thead>
                            <tbody>
                                <form type="post">
                                    {foreach from=$supplier item=supp}
                                    <tr {if $supp['id_supplier'] == $defsupplier} class="success"{/if}>
                                        <td>{counter}
                                        </td>
                                        <td>{$supp['name_supplier']}</td>
                                        <td>
                                            <input type="hidden"
                                                   name="idDeclination{$attributeDeclination['idDeclination']}"
                                                   value="{$attributeDeclination["idDeclination"]}">
                                            <input type="hidden" name="idSupplier{$attributeDeclination['idDeclination']}"
                                            value="{$supp['id_supplier']}">
                                            <input type="hidden" name="idProduct{$attributeDeclination['idDeclination']}"
                                                   value="{$attributeDeclination["idProduct"]}">
                                            <input type="hidden" name="nameDeclination"
                                                   value="{$attributeDeclination["nameDeclination"]}">
                                            <input name="numberSupplierQuantity{$attributeDeclination['idDeclination']}{$supp['id_supplier']}"
                                                   type="number">
                                        </td>
                                        <td>
                                            <label class="switch">
                                            <input type="checkbox"{if $supp['status_supplier']} checked{/if}>
                                            </label>
                                        </td>
                                        <td><span></span></td>
                                    </tr>
                                    {/foreach}
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {/foreach}
            {var_dump($infoDeclination)}

        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('.pbody').hide();
            $('.phead').click(function(){
                $(this).next('.pbody').toggle();
            });
            $(".up,.down").click(function(){
                var row = $(this).parents("tr:first");
                if ($(this).is(".up")) {
                    row.insertBefore(row.prev());
                } else {
                    row.insertAfter(row.next());
                }
            });
        });
    </script>
</div>
