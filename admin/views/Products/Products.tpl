{{extends '../Index.tpl'}}
{{block name='content'}}
<div id="title-header">
    <h1>Produtos</h1>
    <form method="POST" action="{{$domain}}/admin/produtos" id="filter-form">
        <input type="text" name="filter" id="input-filter" value="{{$filter}}"/>
        <input type="hidden" name="page" id="input-page" value="{{$page}}"/>
        <input type="hidden" name="bypage" id="input-bypage" value="{{$bypage}}"/>
        <input type="hidden" name="orderby" id="input-orderby" value="{{$orderBy}}"/>
        <input type="hidden" name="orderdir" id="input-orderdir" value="{{$orderDir}}"/>
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
                    <th class="al sortable {{if $orderBy == 'name'}}{{$orderDir}}{{/if}}" data-input-columnsort="name" style="width:30%">Nome</th>
                    <th class="al sortable {{if $orderBy == 'category'}}{{$orderDir}}{{/if}}" data-input-columnsort="category" style="width:30%">Categoria</th>
                    <th class="ar sortable {{if $orderBy == 'price'}}{{$orderDir}}{{/if}}" data-input-columnsort="price" style="width:15%">Preço (R$)</th>
                    <th class="ar sortable {{if $orderBy == 'stock'}}{{$orderDir}}{{/if}}" data-input-columnsort="stock" style="width:15%">Em estoque</th>
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
                    <td colspan="5" id="datagrid-footer">
                        <div id="datagrid-footer-count">{{$regCount}}</div>
                        {{if $pages > 1}}
                            <div id="datagrid-pagination">
                                <div data-input-pagination="1" class="datagrid-page {{if $isfirstPage}}disabled{{/if}}"><i class="fa fa-angle-double-left"></i></a></div>
                                <div data-input-pagination="{{max(1,$page - 1)}}" class="datagrid-page {{if $isfirstPage}}disabled{{/if}}"><i class="fa fa-angle-left"></i></div>
                                {{for $p=$initialPage to $finalPage}}
                                <div data-input-pagination="{{$p}}" class="datagrid-page {{if $p == $page}}active{{/if}}">{{$p}}</div>
                                {{/for}}
                                <div data-input-pagination="{{min($pages, $page + 1)}}" class="datagrid-page {{if $isLastPage}}disabled{{/if}}"><i class="fa fa-angle-right"></i></div>
                                <div data-input-pagination="{{$pages}}" class="datagrid-page {{if $isLastPage}}disabled{{/if}}"><i class="fa fa-angle-double-right"></i></div>
                            </div>
                        {{/if}}
                        <div id="datagrid-foorter-bypage-wrapper">
                            <select name="bypage" id="datagrid-foorter-bypage" data-input-select-nofilter data-input-pagination-bypage>
                                <option value="10" {{if $bypage==10}}selected{{/if}}>10</option>
                                <option value="20" {{if $bypage==20}}selected{{/if}}>20</option>
                                <option value="50" {{if $bypage==50}}selected{{/if}}>50</option>
                                <option value="100" {{if $bypage==100}}selected{{/if}}>100</option>
                            </select>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
{{/block}}