<?php

class Template extends Eloquent {

    protected $table = 'templates';

    public $timestamps = false;

    public static function createTemplate($data){
        $template = Template::insert(array(
            'name' => $data['name'],
            'type' => $data['type'],
            'tags' => $data['tags'],
            'desc' => $data['desc'],
            'var' => serialize($data['opt']),
            'sku' => $data['sku']
        ));
        return $template;

    }

    public static function updateTemplate($data){
        $template = Template::where('id', $data['id'])->update(array(
            'name' => $data['name'],
            'type' => $data['type'],
            'tags' => $data['tags'],
            'desc' => $data['desc'],
            'var' => serialize($data['opt']),
            'sku' => $data['sku']
        ));
        return $template;
    }

    public static function deleteTemplate($id){
        $template = Template::where('id', $id)->delete();
        return $template;
    }


}
