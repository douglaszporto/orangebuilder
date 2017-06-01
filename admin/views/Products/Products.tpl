{{extends '../Index.tpl'}}
{{block name='content'}}
<div id="title-header">
    <h1>Produtos</h1>
    <input type="text" name="filter" id="input-filter" placeholder="Encontrar produto"/>
    <button id="btn-filter" class="main"><i class="fa fa-search"></i></button>

    <div id="actions-wrapper">
        <button id="btn-new" class="main" data-href="{{$domain}}/admin/form/produtos">Adicionar</div>
    </div>
</div>
<br />
{{if strlen($success) > 0}}
    <div id="success-container">
        <div id="success-icon"><i class="fa fa-check-circle"></i></div>
        <div id="success-description">{{$success}}</div>
    </div>
{{/if}}
{{if count($errors) > 0}}
    <div id="error-container">
        <div id="error-icon"><i class="fa fa-times-circle"></i></div>
        <div id="error-description">
            {{foreach $errors as $error}}
                {{$error}}<br/>
            {{/foreach}}
        </div>
    </div>
{{/if}}
<div id="datagrid-view">
    <table>
        <thead>
            <tr>
                <th class="al" style="width:35%">Nome <i class="fa fa-sort-up"></i></th>
                <th class="al" style="width:35%">Categoria</th>
                <th class="ar" style="width:15%">Preço (R$)</th>
                <th class="ar" style="width:15%">Em estoque</th>
            </tr>
        </thead>
        <tbody>
            {{foreach $products as $product}}
            <tr>
                <td class="al"><a href="#" class="datagrid-item-open">{{$product["name"]}}</a></td>
                <td class="al">{{$product["category"]}}</td>
                <td class="ar">{{$product["price"]}}</td>
                <td class="ar">{{$product["stock"]}}</td>
            </tr>
            {{/foreach}}
        </tbody>
    </table>
</div>
{{/block}}