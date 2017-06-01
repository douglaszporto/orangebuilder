{{extends '../Index.tpl'}}
{{block name='content'}}
<div id="form">
    <h2 class="form-new-item">Editar Produto</h2>

    <form id="form-content" method="POST" action="{{$domain}}/admin/salvar/produtos/{{$data['id']}}">
        <input type="hidden" value="{{$data['id']}}" />
        <label for="input-name">Nome</label>
        <input type="text" name="data-name" id="data-name" style="width:460px;" value="{{$data['name']}}"/>

        <label for="input-category">Categoria</label>
        <select name="data-category" id="data-category" style="width:460px;" data-input-select>
            <option value="">&nbsp;</option>
            {{foreach $categories as $key => $category}}
            <option value="{{$key}}" {{if $key==$data['category_id']}}selected="selected"{{/if}}>{{$category}}</option>
            {{/foreach}}
        </select>

        <label for="input-price">Preço</label>
        <input type="text" name="data-price" id="data-price" style="width:460px;" value="{{$data['price']}}"/>

        <label for="input-stock">Em estoque</label>
        <input type="text" name="data-stock" id="data-stock" style="width:460px;" value="{{$data['stock']}}"/>
    </form>

    <div id="form-buttons">
        <button id="form-button-add" class="main form-new-item">Editar</button>
        <a href="{{$domain}}/admin/produtos" id="form-close">Cancelar</a>
    </div>
</div>
{{/block}}