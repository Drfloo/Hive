<style>
    .panel-heading{
        cursor:pointer;
    }
    .panel-heading:hover{
        background-color: #fff;
    }
</style>

<div class="container">
    <hr />
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
                                <th>Position</th>
                                <th>Fournisseur</th>
                                <th>Quantité (max: {$attributeDeclination["defaultQuantityDeclination"]})</th>
                                <th>Activer / Desactiver</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form method="gets" action="../../../controller/admin/AdminTraitementController.php">
                            {foreach from=$supplier item=supp}
                                    <tr {if $supp['id_supplier'] == $defsupplier} class="success"{/if}>
                                        <td>{counter}</td>
                                        <td>{$supp['name_supplier']}</td>
                                        <td>
                                             <input type="hidden" name="idDeclination"
                                               value="{$attributeDeclination["idDeclination"]}">
                                            <input type="hidden" name="nameDeclination"
                                               value="{$attributeDeclination["nameDeclination"]}">
                                            <input name="numberSupplierQuantity" type="number">
                                        </td>
                                        <td>
                                            <label class="switch">
                                            <input type="checkbox"{if $supp['status_supplier']} checked{/if}>
                                            <div class="slider"></div>
                                            </label>
                                        </td>
                                    </tr>
                            {/foreach}
                            </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {/foreach}
        </div>
    </div>
    {var_dump($infoDeclination)}
    <script>
        $(document).ready(function(){
            $('.pbody').hide();
            $('.phead').click(function(){
                $(this).next('.pbody').toggle();
            });
        });
    </script>
</div>
