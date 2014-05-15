<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class OfferForm extends CFormModel
{
    public $id;
	public $pid;	
    public $title;
	public $tag_id;
    public $price;
    public $price_reduce;
    public $amount;
    public $product_id;
	public $delivery;
	public $delay;
	public $comment;
	public $delete;
    public $reduction_pass;
    public $reduction_passed;
    public $reduction_level;

    /**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{

        $rules[]=array('title,price,amount,product_id', 'required');
        //$rules[]= array('products', 'checkproducts');
        $rules[] = array('reduction_pass, reduction_passed,reduction_level,title,price,price_reduce,amount,product_id,id,pid,tag_id,delivery,delay,comment,delete', 'safe');
        return $rules;
	}
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */

    /*public function checkproducts(){
        $this->addError('products','123');
    }*/

    public function save(){
        if(!$this->getErrors()){

			if($this->delete){
				if($this->pid>0)
				$id=$this->pid;
				else
				$id=$this->id;
				$data=Offer::model()->findByPk($id)->delete();
			}else{
				$purchase=new Offer;
                $this->tag_id = Tag::model()->getTag($this->title);
                $purchase->attributes=$this->attributes;



                if (isset($this->id) && isset($this->tag_id)) {
                    $prevoffer = Offer::model()->findByPk($this->id);
                    if (isset($prevoffer->id))
                        if ($prevoffer->price == $this->price &&
                            $prevoffer->amount == $this->amount &&
                            $prevoffer->delay == $this->delay &&
                            $prevoffer->comment == $this->comment) {
                            return true;
                        }else{
                            $prevoffer->winner=0;
                            $prevoffer->save();
                        }
                }

                $purchase->user_id=yii::app()->user->getId();

                if (isset($prevoffer->id))
                    $purchase->company_id=$prevoffer->company_id;
                else
                    $purchase->company_id=Yii::app()->session['major_company']['id'];
				if($purchase->pid<1)
					unset($purchase->pid);
                else
                {
                    $firstoffer=Offer::model()->findByPk($purchase->pid);
                    if(isset($firstoffer->price))
                    if($firstoffer->price>$purchase->price)
                    $purchase->price_reduce=((($firstoffer->price-$purchase->price)*$purchase->amount)/($purchase->price*$purchase->amount))*100;
                }

                //print_r($purchase->attributes);
                $product=Product::model()->with('purchase')->findByPk($this->product_id);
                if($product->purchase->delay>$this->delay){
                    $finance=Finance::model()->findByAttributes(array('company_id'=>$product->purchase->company_id));
                    if($finance){
                        $purchase->fincompany_id=$finance->fincompany_id;
                        $purchase->credit_percent=$finance->percent;
                    }
                }
                $purchase->save();
                $this->id=$purchase->id;
                //print_r($purchase->getErrors());

            }
			//print_r($purchase->getErrors());
            //echo 111;
            return true;
        }else
			print_r($this->getErrors());

	}

    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
}
