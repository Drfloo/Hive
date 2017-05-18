<style>
    .panel-heading{
        cursor:pointer;
    }
    .panel-heading:hover{
        background-color: #fff;
    }
    .table-hive tbody  {
        cursor : move;
    }
</style>

<div class="container">
    <hr />
    {var_dump($biite)}
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
                    <strong>{$showProduct["nameDeclination"]}</strong>
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
                                <th>Prix d'achat</th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$showProduct["hive"] item=showDetailProduct}
                                <form>
                                    <tr>
                                        <td><span name="compteur">
                                               {$showDetailProduct['position']}
                                            </span>
                                            <input class="value_position" type="hidden" value="{counter
                                            name="{$showProduct["nameDeclination"]}"}">
                                        </td>
                                        <td> {$showDetailProduct['name_supplier']}</td>
                                        <td>
                                            <input type="hidden"
                                                   name="idProduct"
                                                   value="{$showProduct["idProduct"]}">
                                            <input type="hidden" name="idSupplier"
                                            value="{$showDetailProduct['id_supplier']}">
                                            <input type="hidden" name="idProductAttribute"
                                                   value="{$showProduct['idDeclination']}">
                                            <input type="hidden" name="nameDeclination"
                                                   value="{$showProduct["nameDeclination"]}">
                                            <input name="numberSupplierQuantity" type="number" value="{$showDetailProduct['quantity_supplier']}">
                                            <button class="quantitybutton" type="button">save</button>
                                        </td>
                                        <td>
                                            <label class="switch">
                                            <input type="checkbox"{if $showDetailProduct['supplier_enabled']} checked{/if}>
                                            </label>

                                        </td>
                                        <td>{foreach from=$showDetailProduct["price_supplier"] item=showDetailProductPrice}
                                                {$showDetailProductPrice['product_supplier_price_te']}
                                        {/foreach}</td>
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
            $('.input-position').val(1);
            $('.phead').click(function(){
                $(this).next('.pbody').toggle();
            });
            $( ".table-hive tbody" ).sortable( {
               update: function( event, ui ) {
               $(this).children('form tr').each(function(index) {
                   $(this).find('td span').first().html(index + 1);
                     });
               $(this).children('form tr').each(function(i) {
                   $(this).find('.value_position').val(i + 1);
               });
               $(this).children('form tr').each(function (i) {
                   console.log($(this).find("input[class='value_position']").attr('value'));
                   console.log($(this).find("input[name='idProductAttribute']").attr('value'));
                   console.log($(this).find("input[name='idSupplier']").attr('value'));

                   $.ajax (
                       {
                       type: "POST",
                       url: "{$base_dir}/prestashop/modules/Hive/traitement.php",
                       data: {
                           id: $(this).find("input[name='idProductAttribute']").attr('value'),
                           position: $(this).find("input[class='value_position']").attr('value'),
                           id_supplier: $(this).find("input[name='idSupplier']").attr('value'),

                       }
                       }
                       )
               });
               }
             });
            $(".switch").find('input').change(function (i) {
                if ($(this).attr("checked")) {
                    $.ajax(
                        {
                            type: "POST",
                            url: "{$base_dir}/prestashop/modules/Hive/activesupplier.php",
                            data: {
                                id: $(this).closest('tr').find("input[name='idProductAttribute']").attr('value'),
                                id_supplier: $(this).closest('tr').find("input[name='idSupplier']").attr('value'),
                                statut: 0,
                            }
                        })
                }
                else{
                    $.ajax(
                        {
                            type: "POST",
                            url: "{$base_dir}/prestashop/modules/Hive/activesupplier.php",
                            data: {
                                id: $(this).closest('tr').find("input[name='idProductAttribute']").attr('value'),
                                id_supplier: $(this).closest('tr').find("input[name='idSupplier']").attr('value'),
                                statut: 1,
                            }
                        })
                }
            });
            $('.quantitybutton').on('click',(function () {
                $.ajax(
                    {
                        type: "POST",
                        url: "{$base_dir}/prestashop/modules/Hive/quantitychange.php",
                        data: {
                            id: $(this).closest('tr').find("input[name='idProductAttribute']").attr('value'),
                            id_product: $(this).closest('tr').find("input[name='idProduct']").attr('value'),
                            id_supplier: $(this).closest('tr').find("input[name='idSupplier']").attr('value'),
                            quantity: $(this).closest('tr').find("input[name='numberSupplierQuantity']").val(),
                        }
                    });
            }))
        });

    </script>
</div>
