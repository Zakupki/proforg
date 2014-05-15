<?php
class AnalyticsController extends BackController
{
    public function actionManagers()
    {
        /** @var $model BaseActiveRecord */
        $model = $this->getNewModel('search');
        $model->unsetAttributes(); // clear any default values

        if(isset($_GET[$this->getModelName()]))
            $model->attributes = $_GET[$this->getModelName()];

        $model->restoreGridState();

        $this->render($this->view['managers'], array(
            'model' => $model,
        ));
    }

    /*public function actionManagers()
    {
        $model = $this->getNewModel('managers');

        if(isset($_POST['Purchaseanalytics']))
        $model->attributes=$_POST['Purchaseanalytics'];

        $data=$model->getstats();

        $this->render($this->view['managers'], array(
            'model' => $model,
            'data' => $data
        ));
    }*/
}