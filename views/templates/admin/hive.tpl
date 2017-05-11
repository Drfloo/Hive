<style>
    .panel-heading{
        cursor:pointer;
    }
    .panel-heading:hover{
        background-color: #fff;
    }
    .table-hive tr{
        cursor: move;
    }
</style>

<div class="container">
    <hr />
    {var_dump($test)}
    <h2>Produit : {$productname}</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="material-icons">help</i>
                <p>Choissiez parmi la liste des déclinaisons blablabla</p>
            </div>
            {foreach from=$test item=showProduct}
            <div class="panel panel-default">
                <div class="panel-heading phead">
                    <strong>{$showProduct["nameDeclination"]} id = {$showProduct["idDeclination"]}</strong>
                </div>
                <div class="panel-body pbody">
                    <div>
                        <table class="table table-striped table-hive">
                            <thead>
                            <tr>
                                <th>Ordre</th>
                                <th>Fournisseur</th>
                                <th>Quantité</th>
                                <th>Activer / Desactiver</th>
                                <th>Fournisseur par défaut</th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$showProduct["hive"] item=showDetailProduct}
                                <form type="post">
                                    <tr>
                                        <td><span name="compteur">
                                               {$showDetailProduct['position']}
                                            </span>
                                            <input class="value_position" type="hidden" value="0">
                                        </td>
                                        <td> {$showDetailProduct['name_supplier']}</td>
                                        <td>
                                            <input type="hidden"
                                                   name="idDeclination{$showDetailProduct['idDeclination']}"
                                                   value="{$showDetailProduct["idDeclination"]}">
                                            <input type="hidden" name="idSupplier{$showDetailProduct['idDeclination']}"
                                            value="{$supp['id_supplier']}">
                                            <input type="hidden" name="idProduct{$showDetailProduct['idDeclination']}"
                                                   value="{$showDetailProduct["idProduct"]}">
                                            <input type="hidden" name="nameDeclination"
                                                   value="{$showDetailProduct["nameDeclination"]}">
                                            <input name=
                                                   "numberSupplierQuantity{$showDetailProduct['idDeclination']}
                                                    {$showDetailProduct['id_supplier']}"
                                                   type="number" value="{$showDetailProduct['quantity_supplier']}">
                                        </td>
                                        <td>
                                            <label class="switch">
                                            <input type="checkbox"{if $showDetailProduct['supplier_enabled']} checked{/if}>
                                            </label>
                                        </td>
                                        <td><input type="radio" name="checkbox"></td>
                                    </tr>
                                </form>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {/foreach}
            <!--{var_dump($infoDeclination)}-->
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('.pbody').hide();
            $('.input-position').val(1)
            $('.phead').click(function(){
                $(this).next('.pbody').toggle();
            });
            $( ".table-hive tbody" ).sortable( {
                update: function( event, ui ) {
                    $(this).children().each(function(index) {
                        $(this).find('td span').first().html(index + 1);
                    });
                }
            });
        });
    </script>
</div>
