{* <div class="panel">
    <div class="panel-heading">
        <i class="icon-list-alt"></i> {l s='Price History'}
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>{l s='Product ID'}</th>
                <th>{l s='Price'}</th>
                <th>{l s='Date'}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$price_history item=price}
                <tr>
                    <td>{$price.id_product}</td>
                    <td>{$price.price}</td>
                    <td>{$price.date_add}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
<p>test|$priceHistory|test</p>
</div> *}

<div id="price-change-form">
  {* <form action="{$link->getModuleLink('pricehistory', 'updatePrice')}" method="post">
    <input type="hidden" name="id_product" value="{$product.id_product}">
    <label for="new-price">New Price:</label>
    <input type="text" name="new_price" id="new-price" value="{$product.price}">
    <button type="submit">Update Price</button>
  </form> *}
</div>