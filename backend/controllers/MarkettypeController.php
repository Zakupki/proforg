<?php
class MarkettypeController extends BackController
{
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('*'),
                'roles'=>array('manager','admin'),
            ),
        );
    }
}