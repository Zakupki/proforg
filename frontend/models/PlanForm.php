<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class PlanForm extends CFormModel
{
    public $id;
    public $market_id;
    public $company_id;
    public $unit_id;
    public $companies;
    public $products;
    public $date_deliver;
    public $date_close;
    public $date_create;
    public $comment;
    public $address;
    public $purchasestate_id;
    public $delay=0;
    public $purchaseFiles;
    public $title;
    public $markettype_id;
    public $invites;
    public $dirrect=0;
    public $emails;
    public $payed;
    public $payoffer;
    public $usecredit;
    public $creditpercent;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{

        $rules[]=array('market_id, company_id, date_deliver, date_close', 'required');
        $rules[]= array('products', 'checkproducts');
        $rules[]= array('purchasestate_id', 'checkbalance');
        $rules[]= array('title,comment,delay,dirrect,id,purchasestate_id,address,emails,payed,payoffer,usecredit,creditpercent', 'safe');
        return $rules;
	}
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */

    public function checkbalance(){
        if($this->purchasestate_id==2 && !$this->payed){
            if(Payments::model()->companyBalance($this->company_id)<Paysystem::model()->getPrice(3))
                $this->addError('payed','У Вас недостаточно средств');
        }
    }

    public function save(){
        if(!$this->getErrors()){
            $new=0;
            if(isset($this->id)){
                $purchase=Purchase::model()->findByPk($this->id);
                if(isset($this->purchasestate_id)){
                    if($this->purchasestate_id==2){
                        if(!$purchase->payed){
                            #balance
                            Payments::model()->makePayment(3,$purchase->company_id,$purchase->id);
                            $purchase->payed=1;
                        }

                        #История
                        $history=new History();
                        $history->purchase_id=$this->id;
                        $history->date_create=new CDbExpression('NOW()');
                        $history->company_id=$this->company_id;
                        $history->historytype_id=2;
                        $history->user_id=yii::app()->user->getId();
                        $history->save();
                    }
                }
            }
            else{
                $purchase=$oldpurchase=new Purchase;
                $new=1;
            }
            $purchase->market_id=$this->market_id;
            $purchase->title=$this->title;
            $purchase->company_id=$this->company_id;
            $purchase->user_id=yii::app()->user->getId();
            $purchase->date_deliver=$this->date_deliver;
            $purchase->date_close=$this->date_close;
            $purchase->payoffer=$this->payoffer;
            $purchase->delay=$this->delay;
            $purchase->dirrect=$this->dirrect;
            $purchase->usecredit=$this->usecredit;
            if(!$this->usecredit)
                $purchase->creditpercent=new CDbExpression('NULL');
            else
                $purchase->creditpercent=$this->creditpercent;

            $noemails=0;
            if(!$purchase->emails)
                $noemails=1;

            if($new || $purchase->purchasestate_id!=2 || !$purchase->emails){
                $purchase->emails=$this->emails;
            }

            $purchase->comment=$this->comment;
            $purchase->address=$this->address;
            $purchase->purchasestate_id=$this->purchasestate_id;
            //print_r($purchase->attributes);
            if($new)
            $purchase->payed=1;
            $purchase->save();

            if($new && isset($purchase->id)){
                #balance
                Payments::model()->makePayment(3,$purchase->company_id,$purchase->id);
                $purchase->payed=1;

                $history=new History();
                $history->purchase_id=$purchase->id;
                $history->date_create=new CDbExpression('NOW()');
                $history->company_id=$purchase->company_id;
                $history->historytype_id=1;
                $history->user_id=yii::app()->user->getId();
                $history->save();
            }

            if($purchase->id){
                $allproducts=array();
                $this->id=$purchase->id;
                foreach($this->products as $p){
                    if(strlen(trim($p['name']))<1 || intval($p['amount'])<1 ||  intval($p['unit'])<1)
                    continue;
                    if($p['id']>0)
                        $product=Product::model()->findByPk($p['id']);
                    else{
                        $product=new Product;
                    }
                    $tagid=Tag::model()->getTag($p['name']);

                        $product->tag_id=$tagid;

                        $product->purchase_id=$this->id;
                        $product->unit_id=$p['unit'];
                        $product->amount=$p['amount'];
                        $product->save();
                        $allproducts[$product->id]=$product->id;
                        //print_r($product->getErrors());

                }


                if($new || $purchase->purchasestate_id!=2 || $noemails){
                    if($this->purchasestate_id==2){
                        $emailArr=array();
                        $emailArr=explode(',',$this->emails);
                        if(count($emailArr)>0){
                            foreach($emailArr AS $email){
                                #ОТПРАВКА EMAIL
                                $contr=Yii::app()->controller;
                                $contr->layout="mail";
                                $body =$contr->render('/mail/invite_email', array('products' => Product::model()->with(array('tag','unit'))->findAllByAttributes(array('purchase_id'=>$purchase->id)), 'purchase'=>$purchase), true);
                                $queue = new EmailQueue();
                                $queue->to_email = trim($email,'\'');
                                $queue->subject = "Приглашение принять участие в торгах";
                                $queue->from_email = 'support@zakupki-online.com';
                                $queue->from_name = 'Zakupki-online';
                                $queue->date_published = new CDbExpression('NOW()');
                                $queue->message = $body;
                                $queue->save();

                            }
                    }
                    }
                }


                if(count($allproducts)>0)
                    Product::model()->deleteAll('id not in('.implode(',',$allproducts).') AND purchase_id='.$purchase->id);
                else
                    Product::model()->deleteAll('t.purchase_id='.$purchase->id);
            return $purchase->id;
            }
        }else
           print_r($this->getErrors());
    }



    public function checkproducts()
    {
       $cnt=0;
        foreach($this->products as $k => $product){
            if(strlen(trim($product['name']))>0 || intval($product['amount'])>0 ||  intval($product['unit'])>0){
                if(strlen(trim($product['name']))<1 || intval($product['amount'])<1 ||  intval($product['unit'])<1)
                    $this->addError('products_'.$k,1);
                $cnt++;
            }

        }
        if($cnt<1){
        $this->addError('products_'.$cnt,1);
        }
    }
	public function findByPk($id){
        $plan=new PlanForm;


        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_company_invite.company_id,
          z_company.title
        FROM z_company_invite
        INNER JOIN z_company
          ON z_company.id=z_company_invite.company_id
        WHERE z_company_invite.purchase_id=:purchase_id
        ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":purchase_id",$id, PDO::PARAM_INT);
        $invites=$command->queryAll();

        $purchase=Purchase::model()->findByAttributes(array('id'=>$id,'user_id'=>yii::app()->user->getId()));

        if(!$purchase)
            $this->redirect('/');
        $plan->id=$purchase->id;
        $plan->title=$purchase->title;
        $plan->date_create=$purchase->date_create;
        $plan->markettype_id=$purchase->market->markettype_id;
        $plan->market_id=$purchase->market_id;
        $plan->delay=$purchase->delay;
        $plan->emails=$purchase->emails;
        $plan->date_deliver=$purchase->date_deliver;
        $plan->date_close=$purchase->date_close;
        $plan->delay=$purchase->delay;
        $plan->purchasestate_id=$purchase->purchasestate_id;
        $plan->payed=$purchase->payed;
        $plan->payoffer=$purchase->payoffer;
        $plan->comment=$purchase->comment;
        $plan->address=$purchase->address;
        $plan->dirrect=$purchase->dirrect;
        $plan->invites=$invites;
        $plan->usecredit=$purchase->usecredit;
        $plan->creditpercent=$purchase->creditpercent;

        $plan->products=$purchase->products;
        $plan->purchaseFiles=$purchase->purchaseFiles;

        return $plan;
	}
    public function inviteToPurchase($companies){
        if(!isset($companies))
            return;
        $companyArr=array();
        $companyArr=explode(',',$companies);
        if(count($companyArr)>0){

            //$oldinvites=CompanyInvite::model()->findColumn('company_id', 'purchase_id = '.$this->id);


            $connection = Yii::app()->db;
            $sql = '
            SELECT
              z_company_invite.company_id
            FROM z_company_invite
            WHERE z_company_invite.purchase_id=:purchase_id
            ';
            $command = $connection->createCommand($sql);
            $command->bindParam(":purchase_id",$this->id, PDO::PARAM_INT);
            $oldinvites=$command->queryColumn();
            $newinvites=array();

            foreach($companyArr as $com){
                if(in_array($com,$oldinvites)){
                    unset($oldinvites[array_search($com, $oldinvites)]);
                }else{
                    $inv=new CompanyInvite;
                    $inv->company_id=$com;
                    $inv->purchase_id=$this->id;
                    $inv->date_create=new CDbExpression('NOW()');
                    $inv->save();
                    if($inv->id>0)
                        $newinvites[$inv->company_id]=$inv->company_id;
                }
                //print_r($inv->getErrors());
            }
            if(count($newinvites)>0){
                $sql2 = '
                SELECT
                  z_user.email,
                  concat(z_user.first_name," ",z_user.last_name) AS name
                FROM
                  z_company_user
                  INNER JOIN z_user
                    ON z_user.id = z_company_user.`user_id`
                  INNER JOIN z_company
                    ON z_company.id = z_company_user.`company_id`
                WHERE z_company_user.company_id in('.implode(',',$newinvites).')
                  AND z_company_user.`status` = 1
                  AND z_user.status = 1
                  AND z_user.`subscribe_regular` = 1
                  AND z_company.`status` = 1
                GROUP BY z_user.id
                ';
                $command2 = $connection->createCommand($sql2);
                //$command->bindParam(":purchase_id",$this->id, PDO::PARAM_INT);
                $inviteusers=$command2->queryAll();
                //print_r($inviteusers);
                foreach($inviteusers as $userdata){
                   $products=Product::model()->with(array('tag','unit'))->findAllByAttributes(array('purchase_id'=>$this->id));
                   $purchase=Purchase::model()->with('company')->findByPk($this->id);
                   $contr=Yii::app()->controller;
                   $contr->layout="mail";
                   $body =$contr->render('/mail/invite_email', array('products' => $products, 'purchase'=>$purchase, 'user'=>$userdata['name']), true);
                   $queue = new EmailQueue();
                   $queue->to_email = trim($userdata['email'],'\'');
                   $queue->subject = "Приглашение принять участие в торгах";
                   $queue->from_email = 'support@zakupki-online.com';
                   $queue->from_name = 'Zakupki-online';
                   $queue->date_published = new CDbExpression('NOW()');
                   $queue->message = $body;
                   $queue->save();
                }
            }
            if(count($oldinvites)>0)
                CompanyInvite::model()->deleteAll('company_id in('.implode(',',$oldinvites).') AND purchase_id='.$this->id);


        }
    }
}
