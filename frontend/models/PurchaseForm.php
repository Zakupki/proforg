<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class PurchaseForm extends CFormModel
{
    public $id;
    public $pid;
    public $product_id;
    public $winner;
    public $reduction;
    public $external;
    public $title;
    public $purchasestate_id;
    public $delete;
    public $company_id;
    public $user_id;
    public $price;
    public $amount;
    public $delivery;
    public $delay;
    public $comment;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {

        $rules[] = array('product_id', 'required');
        //$rules[]= array('products', 'checkproducts');
        $rules[] = array('pid,id,product_id,winner,delete,title,price,reduction,external,amount,delivery,delay,comment', 'safe');
        return $rules;
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */

    /*public function checkproducts(){
        $this->addError('products','123');
    }*/

    public function save($purchasestate_id = null)
    {
        if (!$this->getErrors()) {
            if ($this->id > 0) {
                $offer = Offer::model()->findByPk($this->id);

                if($this->external==1 || $this->delete==1){
                    $parent_ext_offer=Offer::model()->with('product')->findByPk($this->pid);
                    if(($parent_ext_offer->price!=$this->price ||
                        $parent_ext_offer->delivery!=$this->delivery  ||
                        $parent_ext_offer->amount!=$this->amount ||
                        $parent_ext_offer->delay!=$this->delay)
                    && !$this->delete){
                        $offer = new Offer();
                        $offer->tag_id = $parent_ext_offer->product->tag_id;
                        $offer->product_id = $this->product_id;
                        $offer->pid = $this->pid;
                        $offer->company_id = $parent_ext_offer->company_id;
                        $offer->user_id = 3;
                        $offer->price = $this->price;
                        $offer->amount = $this->amount;
                        $offer->delivery = $this->delivery;
                        $offer->delay = $this->delay;
                        $offer->comment = $this->comment;
                    }
                }
                if ($purchasestate_id == 3) {
                    if ($this->reduction > 0)
                        $offer->reduction_level = 1;
                    if ($this->pid == $this->id)
                        $offer->winner = $offer->reduction = $this->reduction;
                    else {
                        $parentoffer = Offer::model()->findByPk($this->pid);
                        $parentoffer->reduction = $this->reduction;
                        $parentoffer->save();
                        $offer->winner = $this->reduction;
                    }
                } else
                    $offer->winner = $this->winner;
                    if($this->delete)
                        $offer->delete();
                    else
                        $offer->save();

                //if (count($offer->getErrors()) > 0)
                //    print_r($offer->getErrors());
            } elseif ($this->external == 1) {
                if (strlen($this->title) > 0) {
                    $this->company_id = Company::model()->getExternalCompany($this->title);
                    if ($this->company_id > 0) {
                        $product = Product::model()->findByPk($this->product_id);
                        $offer = new Offer();
                        $offer->tag_id = $product->tag_id;
                        $offer->product_id = $this->product_id;
                        $offer->company_id = $this->company_id;
                        $offer->user_id = 3;
                        $offer->price = $this->price;
                        $offer->amount = $this->amount;
                        $offer->delivery = $this->delivery;
                        $offer->delay = $this->delay;
                        $offer->comment = $this->comment;
                        $offer->winner = $this->winner;
                        $offer->save();
                        if ($offer->getErrors()) {
                           // print_r($offer->getErrors());
                        } else
                            Offer::model()->resetOfferPlaces(array('product_id' => $this->product_id));

                    }
                }
            }
            //echo $offer->id;
            /*}*/
            //uprint_r($offer->getErrors());
            return true;
        } else
            print_r($this->getErrors());
    }

}
