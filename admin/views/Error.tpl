{{extends 'Index.tpl'}}
{{block name='content'}}
    <div id="error-container">
        <div id="error-icon"><i class="fa fa-times-circle"></i></div>
        <div id="error-description">
            {{$error}}
        </div>
    </div>
{{/block}}