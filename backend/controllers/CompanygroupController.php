<?php
class CompanygroupController extends BackController
{
    protected function afterActionDone($model)
        {
    
            if(isset($_POST['CompanygroupUser']))
            {
                $hasErrors = false;
                $psModel = new CompanygroupUser();
                $CompanygroupUserData = $_POST['CompanygroupUser'];
                foreach($CompanygroupUserData as $idx => $item)
                {
                    $psModel->user_id = $item['user_id'];
                                         if(!$psModel->validate(array('user_id', )))
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
                    CompanygroupUser::model()->updateForUser($model->id, $CompanygroupUserData); 
            }else{
                 CompanygroupUser::model()->updateForUser($model->id, array());
            }
                 return parent::afterActionDone($model);
        }
}