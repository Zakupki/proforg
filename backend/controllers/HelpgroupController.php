<?php
class HelpgroupController extends BackController
{
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);

        if($this->doAction($model))
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($model);
            }
            $this->redirectAction();
        }

        $this->render($this->view['update'], array(
            'model' => $model,
            'helps' => Help::model()->sort()->findAllByAttributes(array('helpgroup_id' => $model->id)),
            'help' => new Help
        ));
    }
}