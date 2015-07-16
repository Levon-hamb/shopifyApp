<?php

class Shopify {

    public  $api_key = "7caa6cd1e630e63c40196a91e88e7b0a";
    public  $password = "bd721a4c46c0dd0c735b374e48110a74";
    public  $domain = "inspiratee.myshopify.com";

    public function getProductTypes(){
        $url = "https://".$this->api_key.":".$this->password."@".$this->domain."/admin/products.json?product_type";
        $method = "GET";
        $response = $this->curl($url, $method);
        $types = json_decode($response, true);
        return $types;
    }

    public function updateProduct($images, $product_id){
        $products_array = array(
            "product" => array(
                "variants" => $images
            )
        );

        $url = "https://".$this->api_key.":".$this->password."@".$this->domain."/admin/products/".$product_id.".json";
        $method = "PUT";
        $res = $this->curl($url, $method, $products_array);
        $res = json_decode($res);
        return $res;
    }

    public function createProduct($id, $title, $body, $type, $tags, $images, $variants, $options ){

        $products_array = array(
            "product"=>array(
                "title"=> $title,
                "body_html"=> $body,
                "vendor"=> "Inspiratee",
                'tags' => $tags,
                'id' => $id,
                "product_type"=> $type,
                "published"=> true,
                'options' => $options,
                "variants" => $variants,
                "images"=> $images,
                "handle" => $id
            ),

        );
        $url = "https://".$this->api_key.":".$this->password."@".$this->domain."/admin/products.json";
        $method = "POST";
        $res = $this->curl($url, $method, $products_array);
        $res = json_decode($res);

        return $res;
    }

    public function curl($url, $method, $products_array = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if($products_array != null){
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($products_array));
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec ($curl);
        curl_close ($curl);

        return $response;
    }



}