<?php
class MarketCompanyController extends BackController
{
    public function behaviors() {
        return array(
            'exportableGrid' => array(
                'class' => 'backend.components.ExportableGridBehavior',
                'filename' => 'Рынок-Компания.csv',
                'csvDelimiter' => ';', //i.e. Excel friendly csv delimiter
            ));
    }
    public function actionAdmin() {
        $model = new MarketCompany('search');
        $model->unsetAttributes();
        if (isset($_GET['MarketCompany']))
            $model->attributes = $_GET['MarketCompany'];
        if ($this->isExportRequest()) { //<==== [[ADD THIS BLOCK BEFORE RENDER]]
            //set_time_limit(0); //Uncomment to export lage datasets
            //Add to the csv a single line of text
            //$this->exportCSV(array('POSTS WITH FILTER:'), null, false);
            //Add to the csv a single model data with 3 empty rows after the data
            //$this->exportCSV($model, array_keys($model->attributeLabels()), false, 3);
            //Add to the csv a lot of models from a CDataProvider
            $this->exportCSV($model->search(), array('id','company.title','market.title'));
            //$model->restoreGridState();
            //CVarDumper::dump($model->search(),10,true);
        }else
        $this->render('admin', array(
            'model' => $model,
        ));
    }
}