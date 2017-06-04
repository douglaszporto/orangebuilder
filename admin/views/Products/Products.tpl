{{extends '../Index.tpl'}}
{{block name='content'}}
<div id="title-header">
    <h1>Produtos</h1>
    <form method="POST" action="{{$domain}}/admin/produtos" id="filter-form">
        <input type="text" name="filter" id="input-filter" placeholder="Encontrar produto" value="{{if strlen($filter) > 0}}{{$filter}}{{/if}}"/>
        <button id="btn-filter" class="main"><i class="fa fa-search"></i></button>
    </form>

    <div id="actions-wrapper">
        <div id="actions-extra-wrapper">
            <button id="btn-extra" title="Outras ações"><i class="fa fa-ellipsis-h"></i></button>
            <div id="actions-extra-container">
                <button id="btn-delete" disabled="disabled" data-operation-visibility="3" data-form="datagrid-form" data-href="{{$domain}}/admin/produtos/remover"><i class="fa fa-trash"></i> Remover</button>
            </div>
        </div>
        <button id="btn-new" class="main" data-href="{{$domain}}/admin/produtos/form"><i class="fa fa-plus"></i> Adicionar</div>
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
<form method="POST" action="" id="datagrid-form">
    <div id="datagrid-view">
        <table>
            <thead>
                <tr>
                    <th class="ac" style="width:10%"><input type="checkbox" id="datagrid-select-all" name="datagrid-select-all" data-input-checkbox/></th>
                    <th class="al sortable {{if $orderBy == 'name'}}{{$orderDir}}{{/if}}" style="width:30%"><a href="{{$domain}}/admin/produtos?orderBy=name&orderDir={{$reverseOrderDir}}">Nome</a></th>
                    <th class="al sortable {{if $orderBy == 'category'}}{{$orderDir}}{{/if}}" style="width:30%"><a href="{{$domain}}/admin/produtos?orderBy=category&orderDir={{$reverseOrderDir}}">Categoria</a></th>
                    <th class="ar sortable {{if $orderBy == 'price'}}{{$orderDir}}{{/if}}" style="width:15%"><a href="{{$domain}}/admin/produtos?orderBy=price&orderDir={{$reverseOrderDir}}">Preço (R$)</a></th>
                    <th class="ar sortable {{if $orderBy == 'stock'}}{{$orderDir}}{{/if}}" style="width:15%"><a href="{{$domain}}/admin/produtos?orderBy=stock&orderDir={{$reverseOrderDir}}">Em estoque</a></th>
                </tr>
            </thead>
            <tbody>
                {{foreach $products as $product}}
                <tr>
                    <td class="ac"><input type="checkbox" id="datagrid-selected-item-{{$product['id']}}" class="datagrid-selected-items" name="datagrid-selected-items[]" value="{{$product['id']}}" data-input-checkbox/></td>
                    <td class="al"><a href="{{$domain}}/admin/produtos/form/{{$product['id']}}" class="datagrid-item-open">{{$product["name"]}}</a></td>
                    <td class="al">{{$product["category"]}}</td>
                    <td class="ar">{{$product["price"]}}</td>
                    <td class="ar">{{$product["stock"]}}</td>
                </tr>
                {{/foreach}}
                <tr>
                    <td colspan="5">
                        <div id="datagrid-footer-count">{{$regCount}}</div>
                        <div id="datagrid-foorter-bypage-wrapper">
                            <select name="bypage" id="datagrid-foorter-bypage" data-input-select data-input-unfilterable>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
{{/block}}