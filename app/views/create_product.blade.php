@extends('layouts.template')

@section('content')

<div class="error text-center">
    @if(isset($errors))
    @foreach($errors as  $error)
     {{$error.'<br>'}}
     @endforeach

    @endif
</div>
<form action="/createProduct" method="post" enctype="multipart/form-data">
    <div id="step-1">

        <div class="col-lg-2">
            Product Template
        </div>
        <div class="col-lg-10">
         <select name="template_name" id="template_name">
         <option value="Choose template" selected="selected" disabled>Choose template</option>
        @foreach($templates as $template)
            <option value="{{$template['id']}}" >{{$template['name']}}</option>
        @endforeach
         </select>
        </div>
        <div class="col-lg-2">
            Product ID
        </div>
        <div class="col-lg-10">
            <input type="text" name="product_id" id="product_id">
        </div>
        <div class="col-lg-2">
            Product Title
        </div>
        <div class="col-lg-10">
            <input type="text" name="title" id="title">
        </div>
        <div class="col-lg-2">
            Product Image Folder
        </div>
        <div class="col-lg-10">
            <input type="file" name="image[]" class="" multiple="multiple">
        </div>
        <div class="variant">
            @if(isset($_COOKIE['temp_var']) && isset($_COOKIE['checked_var']))

            <?php
                $template_vars = [];
                $checked_vars = [];
                $template_vars = unserialize($_COOKIE['temp_var']);
                $checked_vars = unserialize($_COOKIE['checked_var']);
                foreach($template_vars as $key=>$temp){
                    echo '<div class="col-lg-2 var_name">'.$temp[0].' Variant</div>';
                    foreach($checked_vars as $ky=>$check){
                        if($temp[0] == $ky){
                            foreach($check as $c){
                                foreach($temp as $k=>$t){
                                    if($k != 0){
                                        if($c['value'] == $t['value']){
                                            unset($temp[$k]);

                                        }
                                    }
                                }
                            }
                        }
                    }
                    foreach($checked_vars as $ky=>$check){
                        foreach ($check as $c) {
                            if($temp[0] == $ky){
                                echo '<div class="col-lg-2 var_name"></div><div class="col-lg-10 var" >
                                       <table class="opt"><tr><td><div  class="name checked" >
                                       '.$c['value'].'<input type="checkbox" class="hidden" value="'.$c['value'].'" name="var[]" checked="checked">
                                       </div></td></tr></table></div>';
                            }
                        }
                    }
                    $keys = array_keys($temp);
                    foreach($keys as $k){
                        if(is_array($temp[$k])){
                            echo '<div class="col-lg-2 var_name"></div><div class="col-lg-10 var" >
                                   <table class="opt"><tr><td><div  class="name " >
                                   '.$temp[$k]['value'].'<input type="checkbox" class="hidden" value="'.$temp[$k]['value'].'" name="var[]" >
                                   </div></td></tr></table></div>';
                        }
                    }
                }
            ?>

            @endif
        </div>
        <div class="col-lg-2">
            Description
        </div>
        <div class="col-lg-10">
            <textarea class="editor" rows="10" cols="45" id="desc" name="desc"></textarea>
        </div>

        <div>
            <div class="col-lg-2">
                Tags
            </div>
            <div class="col-lg-10">
                <input type="text"  name="tags" id="tags">
            </div>
        </div>
        <div class="row">
            <input class="btn btn-default col-lg-offset-10 " value="Continue" type="submit"  id="continue" >

        </div>
    </div>
</form>

@stop