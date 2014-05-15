<?php
class AnalyticsController extends BackController
{
    public function actionAdmin()
    {
        $model = $this->getNewModel();

        if(isset($_POST['Purchaseanalytics']))
        $model->attributes=$_POST['Purchaseanalytics'];

        $data=$model->getstats();

        $this->render($this->view['admin'], array(
            'model' => $model,
            'data' => $data
        ));
    }
}