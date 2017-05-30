{{extends 'Index.tpl'}}
{{block name='content'}}
<div id="title-header">
    <h1>Produtos</h1>
    <input type="text" name="filter" id="input-filter" placeholder="Encontrar produto"/>
    <button id="btn-filter" class="main"><i class="fa fa-search"></i></button>

    <div id="actions-wrapper">
        <button id="btn-new" class="main">Adicionar</div>
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
                <th class="al" style="width:35%">Nome <i class="fa fa-chevron-down"></i></th>
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

{{block name="form"}}
<div id="background">&nbsp;</div>
<div id="form">
    <h2 class="form-new-item">Novo Produto</h2>
    <h2 class="form-edit-item">Editar Produto</h2>

    <form id="form-content" method="POST" action="{{$domain}}/admin/novo/produtos">
        <label for="input-name">Nome</label>
        <input type="text" name="data-name" id="data-name" style="width:460px;" />

        <label for="input-category">Categoria</label>
        <select name="data-category" id="data-category" style="width:460px;" data-input-select>
            <option value="">&nbsp;</option>
            <option value="1">Categoria 1</option>
            <option value="2">Categoria 2</option>
            <option value="3">Categoria 3</option>
            <option value="3">Categoria 3</option>
            <option value="3">Categoria 3</option>
            <option value="3">Categoria 3</option>
            <option value="3">Categoria 3</option>
            <option value="3">Categoria 3</option>
            <option value="3">Categoria 3</option>
            <option value="3">Categoria 3</option>
        </select>

        <label for="input-price">Preço</label>
        <input type="text" name="data-price" id="data-price" style="width:460px;" />

        <label for="input-stock">Em estoque</label>
        <input type="text" name="data-stock" id="data-stock" style="width:460px;" />
    </form>

    <div id="form-buttons">
        <button id="form-button-add" class="main form-new-item">Adicionar</button>
        <button id="form-button-edit" class="main form-edit-item">Editar</button>
        <a href="#" id="form-close">Cancelar</a>
    </div>
</div>
{{/block}}