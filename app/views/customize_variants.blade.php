@extends("layouts.template")

@section("content")
<form method="post" action="/create_productsShopify">
<div class="container">
    <div class="row">
        <div class="col-lg-2">

            <a href="" onclick="window.history.back();" class="btn btn-default"> Previous screen</a>
        </div>
        <div class="col-lg-10">

        </div>
    </div>
    <div class="row">
        <div class="col-lg-2">
            Product Images
        </div>
        <div class="col-lg-10">
            @if(isset($images) && !empty($images))
                @foreach($images as $key=>$image )
                    <img src="{{$image}}" style="width: 100px">
                    @if($key % 5 == 0)
                        <br>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1">
            Variants
        </div>
        <div class="col-lg-11">
            <div class="col-lg-10">
                <table>
                <tr>
                    @foreach($variants_name as $key)
                        <th>{{$key}}</th>
                    @endforeach
                    <th>Price</th>
                    <th>Weight</th>
                    <th>SKU</th>
                </tr>
                    @foreach($variants as $variant)
                    <tr class="tr">
                        <?php
                            $weight = null;
                            $price = null;
                            $sku = "";
                            $sku_new = [];
                            $test = explode("%",$sku_format);
                        ?>
                        @foreach($variant as $k=>$v)
                            <?php

                                $count =count($test);


                                $price += $v['price'];
                                $weight += $v['weight'];
                                $sku_new[] = $v['sku_mapping'];
                                if(empty($sku)){
                                    if( $test[1] == 'product_id'){
                                        $sku .= $id.$test[2].$v['sku_mapping'];
                                    }else{
                                        $sku .= $v['sku_mapping'];
                                    }

                                }else{
                                    $map = $test[2].$v['sku_mapping'];
                                    $sku .= $map;
                                }
                            ?>
                            <td><input type="text" name="{{$k}}[]" class="price" value="{{$v['value']}}" style="width: 150px;"></td>
                        @endforeach
                        @if(isset($test[$count-1]) && !empty($test[$count-1]))
                            <?php $sku .= $test[$count-1];?>
                        @endif


                        <td><input type="text" name="price[]" value="{{$price}}" style="width: 150px;"></td>
                        <td><input type="text" name="weight[]" value="{{$weight}}" style="width: 150px;"></td>
                        <td><input type="text" name="sku[]" value="{{$sku}}" style="width: 150px;"></td>
                        <?php
                            $pattern = '/'.$sku.'/';
                            $first = false;
                            if(isset($images) && !empty($images)){
                                if(!$first){
                                    foreach($images as $image){
                                        if(preg_match("/".$id."/", $image) && preg_match("/".$sku_new[0]."/", $image) && preg_match("/".$sku_new[1]."/", $image) && preg_match("/".$sku_new[2]."/", $image) ){
                                            echo '<td><img src="'.$image.'" alt="" class="photo"/></td>
                                            <input type="hidden" name="images[]" value="'.$image.'">';
                                           $first = true;
                                           break;
                                        }
                                    }
                                }
                                if(!$first){
                                    foreach($images as $image){
                                        if(preg_match("/".$id."/", $image) && preg_match("/".$sku_new[0]."/", $image) && preg_match("/".$sku_new[2]."/", $image)){
                                            echo '<td><img src="'.$image.'" alt="" class="photo"/></td>
                                            <input type="hidden" name="images[]" value="'.$image.'">';
                                            $first = true;
                                            break;
                                        }
                                    }
                                }
                                if(!$first){
                                    foreach($images as $image){
                                        if( preg_match("/".$id."/", $image) && preg_match("/".$sku_new[0]."/", $image)){
                                            echo '<td><img src="'.$image.'" alt="" class="photo"/></td>
                                            <input type="hidden" name="images[]" value="'.$image.'">';
                                           $first = true;
                                           break;
                                        }
                                    }
                                }

                                if(!$first){
                                    echo '<td><img src="'.$images[0].'" alt="" class="photo"/>44</td>
                                    <input type="hidden" name="images[]" value="'.$images[0].'">';
                                    $first = true;
                                }
                                if(!$first){
                                    foreach($images as $image){
                                        if(preg_match("/".$id."/", $image)){
                                            echo '<td><img src="'.$image.'" alt="" class="photo"/></td>
                                            <input type="hidden" name="images[]" value="'.$image.'">';
                                            $first = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        ?>
                    </tr>
                    @endforeach
             </table>
            </div>
            <input type="hidden" name="id" value="{{ $id}}">
            <input type="hidden" name="all_images" value='{{ serialize($images)}}'>
            <input type="hidden" name="title" value="{{$title}}">
            <input type="hidden" name="body" value='{{$desc}}'>
            <input type="hidden" name="tags" value="{{$tags}}">
            <input type="hidden" name="type" value="{{$type}}">
            <input type="hidden" name="sku_format" value="{{$sku_format}}">
            <input type="hidden" name="options" value='{{serialize($options)}}'>
        </div>
        <input value="Create Product" class="btn btn-default" type="submit">
    </div>
</div>

<div class="modal-footer"></div>
</form>
@stop