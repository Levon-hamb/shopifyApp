@extends('layouts.template')

@section('content')

<form @if(isset($template)) action="/saveEditTemplate" @else action="/createTemplate" @endif method="post">
<div id="step-1">
    <h2 class="StepTitle">Step 1 Content</h2>
    <div class="col-lg-2">
        Template name
    </div>
    <div class="col-lg-10">
        <input type="text" name="name" id="name" value="{{isset($template['name'])?$template['name']:""}}">
    </div>
    <div class="col-lg-2">
        Product Type
    </div>
    <div class="col-lg-10">
    <select name="type" id="type">
    @if(isset($types))
        @foreach($types as $type)
            <option value="{{$type}}" @if(isset($template['type']) && $template['type'] == $type)selected @endif>{{$type}}</option>
        @endforeach
    @endif
    </select>
    </div>
    <div class="col-lg-2">
        Tags
    </div>
    <div class="col-lg-10">
        <input type="text" name="tags" id="tags" value="{{isset($template['tags'])?$template['tags']:""}}">
    </div>
    <div class="col-lg-2">
        Description
    </div>
    <div class="col-lg-10">
        <textarea class="editor" rows="10" cols="45" id="desc" name="desc">{{isset($template['desc'])?$template['desc']:""}}</textarea>
    </div>
    <div id="variant">
    @if(isset($template))
    <?php $num = 1; ?>
    <input type="hidden" value="{{$template['id']}}" name="id" id="id">
        @foreach(unserialize($template['var']) as $temp)
        <div class="option">
        <div class="col-lg-2">
                   @if($num == 1) Primary Variant @elseif($num > 1) Secondary Variant @endif
        </div>
        <div class="col-lg-9 var" >
            <table class="opt">
                <tr>
                    <th>Name</th>
                    <th>Value</th>
                    <th>@if($num == 1) Price @elseif($num > 1) Price Adjustor @endif </th>
                    <th>@if($num == 1) Weight @elseif($num > 1) Weight Adjustor @endif </th>
                    <th>SKU Mapping</th>
                </tr>
                @for($i = 1; $i < count($temp);$i++)
                <tr class="tr">
                    @if($i==1)<td><input type="text" name="var_name" class="var_name" value="{{isset($temp[0])?$temp[0]:''}}"></td>@else
                    <td> </td>@endif
                    <td><input type="text" name="value" class="value" value="{{isset($temp[$i]['value'])?$temp[$i]['value']:''}}"></td>
                    <td><input type="text" name="price" class="price" value="{{isset($temp[$i]['price'])?$temp[$i]['price']:''}}"></td>
                    <td><input type="text" name="weight" class="weight" value="{{isset($temp[$i]['weight'])?$temp[$i]['weight']:''}}"></td>
                    <td><input type="text" name="sku_mapping" class="sku_mapping" value="{{isset($temp[$i]['sku_mapping'])?$temp[$i]['sku_mapping']:''}}"></td>
                    <td><input type="button" class="delete_var_opt btn btn-default" value="-" ></td>
                </tr>
                @endfor
            </table>
            <input type="button" class="btn btn-default add_opt_var" value="+">
        </div>
        <div class="col-lg-1 minus">
            <input type="button" class="btn btn-default delete_var" value="-" style="left: -30px;top: 18px;position: relative;">
        </div>
        </div>
        <?php $num++; ?>
    @endforeach
    @else
    <div class="option">
        <div class="col-lg-2">
            Primary Variant
        </div>
        <div class="col-lg-9 var" >
            <table class="opt">
                <tr>
                    <th>Name</th>
                    <th>Value</th>
                    <th>Price</th>
                    <th>Weight</th>
                    <th>SKU Mapping</th>
                </tr>
                <tr class="tr">
                    <td><input type="text" name="var_name" class="var_name"></td>
                    <td><input type="text" name="value" class="value"></td>
                    <td><input type="text" name="price" class="price"></td>
                    <td><input type="text" name="weight" class="weight"></td>
                    <td><input type="text" name="sku_mapping" class="sku_mapping"></td>
                    <td><input type="button" class="delete_var_opt btn btn-default" value="-"></td>
                </tr>
            </table>
            <input type="button" class="btn btn-default add_opt_var" value="+">
        </div>
        <div class="col-lg-1 minus">
            <input type="button" class="btn btn-default delete_var" value="-" style="left: -30px;top: 18px;position: relative;">
        </div>
    </div>
    @endif
    </div>
    <div class="col-lg-12">Add new Variant <input type="button" class="btn btn-default add_variant" value="+"></div>

    <div>
        <div class="col-lg-2">
            SKU Format
        </div>
        <div class="col-lg-10">
            <input type="text" style="width: 400px" name="sku_format" id="sku_format" value="{{isset($template['sku'])?$template['sku']:""}}">
        </div>
        <div class="col-lg-2"></div>
        <div class="col-lg-6">
            Choose from variables: <input value="%product_id%" class="add_sku btn btn-default" type="button"> - unique id you will define for each product you create

        </div>
        <div class="col-lg-4"></div>

        <div class="col-lg-2"></div>
        <div class="sku_name col-lg-10 text-center">
        <div class="remove">
            @if(isset($template))
            @foreach(unserialize($template['var']) as $temp)
            <input value="%{{$temp[0]}}%" class="add_sku btn btn-default" type="button"> - the SKU mapping for the {{$temp[0]}} variant <br>
            @endforeach
            @endif
        </div>
        </div>
    </div>
    <div class="col-lg-12 text-center">
        <a href="/productTemplates" name="cancel" class="btn btn-default">Cancel</a>
        <input type="submit" name="submit" value="Save Template" id="submit" class="btn btn-default">
    </div>


</div>

</form>
@stop