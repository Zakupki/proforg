<?php
class ProductController extends BackController
{
    public function actionTest()
    {
        $model=Product::model()->sort('t.id asc')->with('taggroup','tag')->limit(20)->findAll();


        foreach($model as $m){
            if(isset($m->taggroup))
                echo $m->tag->title.' | '.$m->taggroup->title;
            else
                echo $m->tag->title.' | -----';
            echo "<br>";
        }

    }
}