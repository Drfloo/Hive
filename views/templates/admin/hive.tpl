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
    .table-hive tbody tr{
      border : solid 2px #BBCDD2;
      }
    .table th , td{
      text-align: center;
      }

  .disableed {
      transition: 2s;
      background-color: #eee;
      opacity: 0.5;
      color: rgba(255,255,255,1);
      cursor: not-allowed;
      }

    .quantitybutton {
        background-color: #5CB85C;
        color : #fff;
    }
    input[type=checkbox]{
      border-radius: 50%;
    }
    .refresh{
        margin-bottom: 20px;
    }
</style>

<div class="container">
    <hr />
    {var_dump($vraitest)}
    {var_dump($id_lang)}
    {var_dump($biite)}
    {var_dump($test)}
    <h2>Produit : {$productname}</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="material-icons">help</i>
                <p>Choissiez parmi la liste des déclinaisons blablabla</p>
            </div>
            <button class="btn btn-default refresh">Actualiser les fournisseurs</button>
            {foreach from=$test item=showProduct}
            <div class="panel panel-default">
                <div class="panel-heading phead">
                    <strong>{$showProduct["nameDeclination"]}</strong>
                </div>
                <div class="panel-body pbody">
                    <div>
                        <table class="table table-bordered table-hive">
                            <thead>
                            <tr>
                                <th>Ordre</th>
                                <th>Fournisseur</th>
                                <th>Quantité</th>
                                <th></th>
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
                                            <input class="value_position" type="hidden"
                                                   value="{counter name="{$showProduct["nameDeclination"]}"}">
                                        </td>
                                        <td> {$showDetailProduct['name_supplier']}</td>
                                        <td>
                                            <input id="coucou" name="numberSupplierQuantity" type="number" class="form-control"
                                                   value="{$showDetailProduct['quantity_supplier']}">
                                            <input type="hidden" name="default"
                                                   value="{$showDetailProduct['supplier_default']}">
                                            <input type="hidden"
                                                   name="idProduct"
                                                   value="{$showProduct["idProduct"]}">
                                            <input type="hidden"
                                                   name="idLang"
                                                   value="{$id_lang}">
                                            <input type="hidden" name="idSupplier"
                                            value="{$showDetailProduct['id_supplier']}">
                                            <input type="hidden" name="idProductAttribute"
                                                   value="{$showProduct['idDeclination']}">
                                            <input type="hidden" name="nameDeclination"
                                                   value="{$showProduct["nameDeclination"]}">
                                        </td>
                                        <td><button id="soupe" class="btn quantitybutton" type="button">save</button>
                                        </td>
                                        <td>
                                            <label class="switch">
                                            <input id="checkage" class="ckeckbox"
                                                   type="checkbox"{if $showDetailProduct['supplier_enabled'] == 1} checked{/if}>
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
          $('input:checkbox').each(function(){
               if($(this).is(":checked")) {
                   $(this).parents('tr').removeClass('disableed');
                   $(this).parents('tr').find('#coucou').prop( "disabled", false );
                   $(this).parents('tr').find('#soupe').prop( "disabled", false ).css('background-color', '#5CB85C');
               }else{
                       //
                   $(this).parents('tr').addClass('disableed');
                   $(this).parents('tr').find('#coucou').prop( "disabled", true ).css('background-color', 'white');
                   $(this).parents('tr').find('#soupe').prop( "disabled", true ).css('background-color', '#CCCCCC');
               }
           });

            $('.pbody').hide();
            $('.input-position').val(1);
            $('.phead').click(function(){
                $(this).next('.pbody').toggle();
            });
            $('input[name=numberSupplierQuantity]').change(function(){
                $(this).parents().next('td').find('.btn').css("background-color", "#db841a");
            });
            $('input[name=numberSupplierQuantity]').keyup(function(){
                $(this).parents().next('td').find('.btn').css("background-color", "#db841a");
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

           $('input:checkbox').change(function(){
                if($(this).is(":checked")) {
                    $(this).parents('tr').removeClass('disableed');
                    $(this).parents('tr').find('#coucou').prop( "disabled", false );
                    $(this).parents('tr').find('#soupe').prop( "disabled", false ).css('background-color', '#5CB85C');
                }else{
                        //
                    $(this).parents('tr').addClass('disableed');
                    $(this).parents('tr').find('#coucou').prop( "disabled", true ).css('background-color', 'white');
                    $(this).parents('tr').find('#soupe').prop( "disabled", true ).css('background-color', '#CCCCCC');
                }
            });

            $(".switch").find('input').change(function (i) {
                console.log($(this).is(':checked'));
                if ($(this).is(':checked')) {
                    $.ajax(
                        {
                            type: "POST",
                            url: "{$base_dir}/prestashop/modules/Hive/activesupplier.php",
                            data: {
                                id: $(this).closest('tr').find("input[name='idProductAttribute']").attr('value'),
                                id_supplier: $(this).closest('tr').find("input[name='idSupplier']").attr('value'),
                                statut: 1,
                                position: $(this).closest('tr').find(".value_position").attr('value'),
                                id_product: $(this).closest('tr').find("input[name='idProduct']").attr('value'),
                            }
                        })
                }
                else{
                    $(this).closest('tr').find("input[name='numberSupplierQuantity']").val(0);
                    $.ajax(
                        {
                            type: "POST",
                            url: "{$base_dir}/prestashop/modules/Hive/activesupplier.php",
                            data: {
                                id: $(this).closest('tr').find("input[name='idProductAttribute']").attr('value'),
                                id_supplier: $(this).closest('tr').find("input[name='idSupplier']").attr('value'),
                                statut: 0,
                                position: $(this).closest('tr').find(".value_position").attr('value'),
                                id_product: $(this).closest('tr').find("input[name='idProduct']").attr('value'),
                            }
                        })
                }
            });
            $('.quantitybutton').on('click',(function () {
                $(this).css("background-color", "#5CB85C");
                $.ajax(
                    {
                        type: "POST",
                        url: "{$base_dir}/prestashop/modules/Hive/quantitychange.php",
                        data: {
                            id: $(this).closest('tr').find("input[name='idProductAttribute']").attr('value'),
                            id_product: $(this).closest('tr').find("input[name='idProduct']").attr('value'),
                            id_supplier: $(this).closest('tr').find("input[name='idSupplier']").attr('value'),
                            quantity: $(this).closest('tr').find("input[name='numberSupplierQuantity']").val(),
                            position: $(this).closest('tr').find(".value_position").attr('value'),

                        }
                    });
            }))
            $('.refresh').on('click',(function () {
                console.log($(this).closest('tr').find("input[name='idLang']").attr('value'),)
                $.ajax(
                        {
                            type: "POST",
                            url: "{$base_dir}/prestashop/modules/Hive/refreshModule.php",
                            data: {
                                id_lang: {$id_lang},
                            }
                        });
            }))
        });

    </script>
</div>
