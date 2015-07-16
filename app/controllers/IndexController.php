<?php


class IndexController extends BaseController {


    public function index()
    {

        $data = Input::all();
        if(isset($data["code"]) && !empty($data["code"])){

            $user = User::validHmac($data['code']);
            if(!$user && empty($user) && !isset($user)){
                $u = User::insert(array('hmac' => $data['code']));
                return Redirect::to('https://'.$data['shop'].'/admin/oauth/authorize?client_id=58b8f1fad9c80acbd717c14d8a698478&scope=write_orders,read_customers');

//                return Redirect::to('/');
            }
            else{
                return View::make('index');
                dd('else<br>'.$user);

            }
            echo "aaaa";
            exit;
        }


    }

    public function createProduct()
    {
        $templates = Template::all();
        setcookie('temp_var', '');
        setcookie('checked_var', '');
        return View::make('create_product')->with('templates', $templates);
    }

    public function productTemplates()
    {
        $templates = Template::all();
        return View::make('product_templates')->with('templates', $templates);
    }

    public function editTemplate($id){
        $temlate = Template::find($id);
        $types = $this->getTypes();
        return View::make('create_template')->with(array('template'=> $temlate, 'types' => $types));

    }

    public function createTemplate(){
        $types = $this->getTypes();
        return View::make('create_template')->with('types', $types);
    }

    public function create_template(){
        $data = Input::all();
        $template = Template::createTemplate($data);

        if(Request::ajax()){
            echo 1;
        }
    }

    public function getTypes(){
        $shop = new Shopify();
        $test = $shop->getProductTypes();
        $types = array();
        foreach($test['products'] as $type){
            if(!in_array($type['product_type'], $types)){
                array_push($types, $type['product_type']);
            }
        }
        return $types;
    }

    public function saveEditTemplate(){
        $data = Input::all();

        $template = Template::updateTemplate($data);


        if(Request::ajax()){
            echo 1;
        }else{
            return Redirect::to('/productTemplates');
        }
    }

    public function deleteTemplate($id){

        $template = Template::deleteTemplate($id);
        if($template) {
            if (Request::ajax()) {
                echo 1;
            } else {
                return Redirect::to('/productTemplates');
            }
        }
    }

    public function getTemplate($id = null){
        $data = Input::all();
        if(isset($data['id'])){
            $id = $data['id'];
        }
        $template = Template::find($id);
        $template->var = unserialize($template->var);
        if(Request::ajax()){
            echo json_encode($template);
        }else{
            return $template;
        }
    }

    public function duplicateTemplate($id){
        Template::find($id)->replicate()->save();
        return Redirect::to('/productTemplates');
    }

    public function createProductStep1(){
        $data = Input::all();


        $rules = array(
            'template_name' => 'required',
            'title' => 'required',
            'product_id' => 'required',
            'var' => 'required'
        );
        $message = array('var.required' => '"Error: no variants selected"');
        $validator = Validator::make($data, $rules, $message);
        if($validator->fails())
        {
            $messages = $validator->messages()->all();
            return Redirect::to('/createProduct')->with('errors', $messages);
        }
        else{

            $template_id = $data['template_name'];
            $template = $this->getTemplate($template_id);
            $id = $data['product_id'];
            $title = $data['title'];
            $desc = null;
            if(isset($data['desc'])){
                $desc = $data['desc'];
            }
            $tags = null;
            if(isset($data['tags'])){
                $tags = $data['tags'];
            }
            $type = $template['type'];
            $sku = $template['sku'];

            $variants = $template['var'];
            $var = $data['var'];

            $variants_new = [];
            foreach($variants as $variant){
                $variants_new[$variant[0]] = [];
                for($i=1; $i < count($variant); $i++ ){
                    foreach($var as $v){
                        if($variant[$i]['value'] == $v){
                            array_push($variants_new[$variant[0]], $variant[$i]);
                        }
                    }
                }
            }
            setcookie('temp_var', '');
            setcookie('checked_var', '');
            setcookie('temp_var', serialize($variants));
            setcookie('checked_var', serialize($variants_new));

            $vari = [];
            $variant_name = [];
            foreach($variants_new as $key=>$var){
                array_push($variant_name, $key);
                array_push($vari, $var);
            }

            $result = array();
            foreach ($variants_new as $key=>$v1) {
                if(empty($result)){
                    foreach ($v1 as $e) {
                        $result[][$key]=$e;
                    }
                }
                else{
                    $b = array();
                    foreach ($result as $r) {
                        foreach ($v1 as $e) {
                            $c = $r;
                            $c[$key] = $e;
                            $b[] = $c;
                        }
                    }
                    $result = $b;
                }

            }
            $images = null;
            $imageNames = [];
            if(isset($data['image']) && !empty($data['image'][0]) && $data['image'][0] != null){
                $images = $data['image'];
                $today = date("d.m.y");
                $imageNames = [];
                foreach($images as $image){
                    if($image->isValid()) {
                        $destinationPath = public_path().'/resources/images/'.$today; // upload path
                        $fileName =  $image->getClientOriginalName();// renaming image
                        if(!File::exists($destinationPath.'/'.$fileName)){
                            $image->move($destinationPath, $fileName); // uploading file to given path
                        }

                        $photoPhat = Request::root().'/resources/images/'.$today.'/'.$fileName;
                        array_push($imageNames, $photoPhat);
                    }
                }
            }

            $options = array();
            foreach($variant_name as $key=>$name){
                $arr['name'] = $name;
                $options[$key] = $arr;
            }

            return View::make("customize_variants")->with(
                array(
                    'id'=>$id,
                    'title'=>$title,
                    'desc'=>$desc,
                    'tags'=>$tags,
                    'type'=>$type,
                    'sku_format'=>$sku,
                    'variants_name' => $variant_name,
                    'variants'=>$result,
                    'images' =>$imageNames,
                    'options' => $options
                ));
        }


    }


    public function createProductsShopify(){
        echo "<pre>";

        $data = Input::all();

        setcookie('temp_var', '');
        setcookie('checked_var', '');
        $all_images = unserialize($data['all_images']);

        $id = $data['id'];
        $sku = $data['sku_format'];
        $images =[];
        if(isset($data['images'])){
            $images = $data['images'];
        }


        $tags = null;
        if(isset($data['tags'])){
            $tags = $data['tags'];
        }
        $body = null;
        if(isset($data['body'])){
            $body = $data['body'];
        }

        $title = $data['title'];
        $type = $data['type'];
        $options = unserialize($data['options']);
        unset($data['all_images'],$data['images'],$data['id'], $data['title'], $data['body'],$data['tags'],$data['sku_format'], $data['type'],$data['options']);
        $result = [];
        foreach($data as $key=>$d){
            $arr = [];
            if(empty($result)){
                foreach($d as $k=>$v) {
                    $arr[$key] = $v;
                    $result[] = $arr;
                }
            }else{
                foreach($d as $k=>$v) {
                    $result[$k][$key] = $v;
                }
            }
        }
        foreach ($result as $keyy=>$re) {
            $i = 1;
            foreach($re as $k=>$r){
                foreach ($options as $key=>$o) {
                    foreach ($o as $or) {
                        if($or == $k){
                            $re['option'.$i] = $re[$k];
                            unset($re[$k]);
                            $i++;
                        }
                    }
                }
                $result[$keyy] = $re;
            }
        }
        $photos = $images;
        $num = [];
        foreach ($photos as $key=>$img) {
            foreach ($photos as $k=>$i) {
                if($img == $i){
                    unset($photos[$k]);
                    $num[$key][] = $k;
                }
            }
        }
        $nu = [];
        foreach($num as $n){
            $nu[] = $n;
        }
        $photo =[];
        $im = array_unique($images);
        $output = array_merge(array_diff($im, $all_images), array_diff($all_images, $im));
        foreach($output as $o){
            array_push($im, $o);
        }
        foreach ($im as $p) {
            $photo[] = $p;
        }

        foreach($photo as $k=>$img){
            $photo[$k] = array("src" => $img);
        }
        $shopify = new Shopify();
        $product = $shopify->createProduct($id, $title, $body, $type, $tags, $photo, $result, $options );
        if(!empty($product->errors) && isset($product->errors)){
            echo "<pre>";
            dd($product->errors->base);
        }

        $imagesss = $product->product->images;
        $variants = $product->product->variants;
        $product_id = $product->product->id;

        $ima = [];
        foreach($variants as $key=>$var){
            foreach ($nu as $k=>$n) {
                foreach($n as $ke=>$nm){
                    if($nm == $key){
                        $ima[$key]['image_id'] = $imagesss[$k]->id ;
                        $ima[$key]['id'] = $var->id;
                    }
                }
            }
        }

        $images_id = $shopify->updateProduct($ima, $product_id );
        return Redirect::to("/");
    }

}
