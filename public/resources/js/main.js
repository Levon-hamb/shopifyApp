$(document).ready(function(){

    CKEDITOR.replace( 'desc' );

    $('.add_variant').click(function(){
        var clone = ' <div class="option"><div class="col-lg-2"></div><div class="col-lg-9 var"><table class="opt"> <tr><th>Name</th><th>Value</th><th>Price Adjustor</th><th>Weight Adjustor</th><th>SKU Mapping</th></tr><tr class="tr"><td><input type="text" name="var_name" class="var_name"></td><td><input type="text" name="value" class="value"></td><td><input type="text" name="price" class="price"></td><td><input type="text" name="weight" class="weight"></td><td><input type="text" name="sku_mapping" class="sku_mapping"></td><td><input type="button" class="delete_var_opt btn btn-default" value="-"></td></tr></table><input type="button" class="btn btn-default add_opt_var" value="+"></div><div class="col-lg-1 minus"><input type="button" class="btn btn-default delete_var" value="-" style="left: -30px;top: 18px;position: relative;"></div></div>';
        $('#variant').append(clone);
    });

    $( document ).delegate( ".add_opt_var", "click", function() {
        var clone = '<tr class="tr"><td> </td><td><input type="text" name="value" class="value"></td><td><input type="text" name="price" class="price"></td><td><input type="text" name="weight" class="weight"></td><td><input type="text" name="sku_mapping" class="sku_mapping"></td><td><input type="button" class="delete_var_opt btn btn-default" value="-"></td></tr>';
        $(this).prev().append(clone);
    });

    $( document ).delegate( ".delete_var_opt", "click", function() {
        if($(this).parents(".tr").is($(this).parents('.tr:first'))){
            var opt = $(this).parents(".tr").children('td:first').clone();
            if($(this).parents(".tr").next().children('td:first').length > 0 || $(this).parents('.tr').prev('.tr').length > 0){
                $(this).parents(".tr").next().children('td:first').remove();
                $(this).parents('.tr').next().prepend(opt);
                $(this).parents(".tr").remove();
            }
        }
    });

    $('#submit').click(function(e){
        e.preventDefault();
        var name = $('#name').val();
        var type = $( "#type option:selected" ).text();
        var tags = $('#tags').val();
        var desc = CKEDITOR.instances.desc.getData();
        var sku = $( '#sku_format' ).val();
        var id = $( '#id' ).val();
        var opt = [];
        $('.opt').each(function(){
            var opt_var = [];
            var name = $(this).find('.var_name').val();
            opt_var.push(name);
            $(this).find("tr.tr").each(function(){
                var arr = {};
                var value = $(this).find('.value').val();
                var price = $(this).find('.price').val();
                var weight = $(this).find('.weight').val();
                var sku_mapping = $(this).find('.sku_mapping').val();
                arr.value = value;
                arr.price = price;
                arr.weight = weight;
                arr.sku_mapping = sku_mapping;
                opt_var.push(arr);
            });
            opt.push(opt_var);
        });
        var url = window.location.href;
        console.log(url.match('/(editTemplate){1}/'));
        var template = null;
        if(url.match('/(editTemplate){1}/')){
            $.ajax({
                type: "POST",
                url: "/saveEditTemplate",
                data: ({name: name, type: type, tags: tags, desc: desc, sku: sku, opt: opt, id: id}),
                success : function(data){
                    template = data;
                    //console.log(data);
                },
                async: false,
                dataType: "JSON"
            });
            if(template == 1){
                window.location.href = '/';
            }
        }else{
            $.ajax({
                type: "POST",
                url: "/createTemplate",
                data: ({name: name, type: type, tags: tags, desc: desc, sku: sku, opt: opt}),
                success : function(data){
                    template = data;
                    //console.log(data);
                },
                async: false,
                dataType: "JSON"
            });
            if(template == 1){
                window.location.href = '/';
            }
        }
    });

    $('#template_name').on('change', function(){
        var template_id = $('#template_name option:selected').val();
        var template = null;
        $.ajax({
            type: "POST",
            url: "/getTemplate",
            data: ({id: template_id}),
            success : function(data){
                template = data;
            },
            async: false,
            dataType: "JSON"
        });

        $('.variant').text('');
        CKEDITOR.instances.desc.setData(template.desc);
        $('#tags').val(template.tags);
        $.each(template.var, function(index, value){
            var name = null;
            $.each(value, function(index,val){

                //console.log(val);
                if(index == 0){
                    $('.variant').append('<div class="col-lg-2 var_name">'+val+' Variant</div>');
                    name = val;
                }else{
                    $('.variant').append('<div class="col-lg-2 var_name"></div><div class="col-lg-10 var" ><table class="opt"><tr><td><div  class="name checked" >'+val.value+'<input type="checkbox" class="hidden" value="'+val.value+'" name="var[]" checked="checked"></div></td></tr></table></div>');
                }
            });
        });
    });

    $( document ).delegate( ".name", "click", function() {
        if($(this).find('.hidden').prop("checked")){
            $(this).find('.hidden').prop("checked", false);
            $(this).removeClass('checked');
        }else{
            $(this).find('.hidden').prop("checked", true);
            $(this).addClass('checked');
        }
    });

    $( document ).delegate( ".delete_var", "click", function() {
        $(this).parent('.minus').parent('.option').remove();
        var_name();
    });

    $( document ).delegate( ".var_name", "change", function(){
        var_name();
    });

    function var_name(){
        var name = [];
        $('.tr').find('.var_name').each(function(){
            if($(this).val().length > 0){
                name.push($(this).val());
            }
        });
        $('.remove').remove();
        var text = '<div class="remove">';
        for( var i = 0; i < name.length;  i++){
            text += '<input value="%'+name[i]+'%" class="add_sku btn btn-default" type="button">  - the SKU mapping for the '+name[i]+' variant<br>';
        }
        text += '</div>';
        $('.sku_name').append(text);
    }

    $( document ).delegate( ".add_sku", "click", function(){
        var sku_format = $('#sku_format').val();
        //if(sku_format.length > 0){
        //    sku_format += "-";
        //    sku_format += $(this).val();
        //}else{
            sku_format += $(this).val();
        //}
        $('#sku_format').val(sku_format );
    });



});