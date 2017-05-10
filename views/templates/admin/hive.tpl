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
    {var_dump($position)}
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
                            <table class="table table-striped table-hive">
                                <thead>
                                <tr>
                                    <th>Ordre</th>
                                    <th>Fournisseur</th>
                                    <th>Quantité (max: {$attributeDeclination["defaultQuantityDeclination"]})</th>
                                    <th>Activer / Desactiver</th>
                                    <th>Fournisseur par défaut</th>
                                </tr>
                                </thead>
                                <tbody>
                                {foreach from=$position item=position_supplier}
                                    <form type="post">
                                        <tr>
                                            <td><span name="compteur">
                                               {$position_supplier['position']}
                                                    <input name="position_update" type="hidden" value=""/>
                                            </span>
                                            </td>
                                            <td> {$position_supplier['name']}</td>
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
                                                <input name=
                                                       "numberSupplierQuantity{$attributeDeclination['idDeclination']}{$supp['id_supplier']}"
                                                       type="number">
                                            </td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox"{if $supp['status_supplier']} checked{/if}>
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
            {var_dump($infoDeclination)}

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
                        $(this).find('td').first().html(index + 1);

                    });
                }
            });
        });
    </script>
</div>
