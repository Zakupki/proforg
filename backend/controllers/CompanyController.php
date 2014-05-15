<?php
class CompanyController extends BackController
{
     protected function afterActionDone($model)
        {
    
            if(isset($_POST['CompanyUser']))
            {
                $hasErrors = false;
                $psModel = new CompanyUser();
                $CompanyUserData = $_POST['CompanyUser'];
                foreach($CompanyUserData as $idx => $item)
                {
                    $psModel->user_id = $item['user_id'];
                    $psModel->companyrole_id = $item['companyrole_id'];
                    $psModel->status = $item['status'];

                    if(!$psModel->validate(array('user_id','companyrole_id','status')))
                    {
                        $hasErrors = true;
                        user()->addFlash(
                            'error',
                            $this->renderPartial('//inc/_model_errors', array('data' => $psModel->stringifyAttributeErrors()), true)
                        );
                        continue;
                    }
                                    }
                        if(!$hasErrors)
                    CompanyUser::model()->updateForUser($model->id, $CompanyUserData); 
            }else{
                 CompanyUser::model()->updateForUser($model->id, array());
            }

            if(isset($_POST['MarketCompany']))
            {
                $hasErrors = false;
                $psModel = new MarketCompany();
                $MarketCompanyData = $_POST['MarketCompany'];
                foreach($MarketCompanyData as $idx => $item)
                {
                    $psModel->market_id = $item['market_id'];
                    if(!$psModel->validate(array('market_id')))
                    {
                        $hasErrors = true;
                        user()->addFlash(
                            'error',
                            $this->renderPartial('//inc/_model_errors', array('data' => $psModel->stringifyAttributeErrors()), true)
                        );
                        continue;
                    }
                }
                if(!$hasErrors)
                    MarketCompany::model()->updateForMarket($model->id, $MarketCompanyData);
            }else{
                MarketCompany::model()->updateForMarket($model->id, array());
            }


                 return parent::afterActionDone($model);
        }
}