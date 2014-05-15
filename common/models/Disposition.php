<?php
/**
 * This is the model class for table "{{offer}}".
 *
 * The followings are the available columns in table '{{offer}}':
 * @property integer $id
 * @property string $title
 * @property integer $product_id
 * @property integer $user_id
 * @property double $price
 * @property integer $delivery
 * @property integer $delay
 * @property string $date_create
 *
 * @method Offer active
 * @method Offer cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Offer indexed($column = 'id')
 * @method Offer language($lang = null)
 * @method Offer select($columns = '*')
 * @method Offer limit($limit, $offset = 0)
 * @method Offer sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property User $user
 */
class Disposition extends BaseActiveRecord
{
    public $minprice;
    public $maxprice;
    public $company;
    public $company_id;
    public $companygroup;
    public $delivery;
    public $delivery_id;
    public $delay;
    public $amount;
    public $unit;
    public $avg_price;
    public $price_diff;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Offer the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{taggroup}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title', 'required'),
            array('sort,disposition,status,minprice,maxprice,companygroup,delivery,delay,amount,unit', 'safe'),
            array('company', 'safe', 'on' => 'search'),
        ));
    }


    /**
     * @return array relational rules.
     */
    /*public function relations()
    {
        return array(
            'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
        );
    }*/

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => Yii::t('frontend', 'Title'),
            'minprice' => Yii::t('frontend', 'Minimum Price'),
            'maxprice' => Yii::t('frontend', 'Maximum Price'),
            'avg_price' => Yii::t('frontend', 'Average Price'),
            'price_diff' => Yii::t('frontend', 'Price Diff'),
            'company' => Yii::t('frontend', 'Company'),
            'disposition' => Yii::t('frontend', 'Disposition'),
            'companygroup' => Yii::t('frontend', 'Companygroup'),
            'delay' => Yii::t('frontend', 'Average Delay'),
            'amount' => Yii::t('frontend', 'Total Amount'),
            'unit' => Yii::t('frontend', 'Unit'),
            'delivery' => Yii::t('frontend', 'Delivery'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        //$criteria->join('INNER JOIN z_tag ON z_tag.taggroup_id = z_taggroup.id');
        $criteria->select='
        t.`id`,
        t.`title`,
        t.`disposition`,
        offer.delivery,
        offer.delay,
        tag.`title` as tag,
        MAX(offer.price) AS maxprice,
        MIN(offer.price) AS minprice,
        MIN(offer.price)/MAX(offer.price) AS perc,
        COUNT(offer.id) AS cnt,
        company.title AS company,
        companygroup.title AS companygroup';
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.title', $this->title,true);
        $criteria->compare('company.title', $this->company,true);
        $criteria->join='
            INNER JOIN {{tag}} tag
             ON tag.taggroup_id = t.id
            INNER JOIN z_offer offer
             ON offer.`tag_id`=tag.id AND offer.`winner`=1 AND offer.`exclude_lose`=0
            INNER JOIN z_product product
             ON product.id=offer.`product_id`
            INNER JOIN z_purchase purchase
             ON purchase.id=product.`purchase_id`
            INNER JOIN z_company company
             ON company.id=purchase.`company_id`
            INNER JOIN z_companygroup companygroup
             ON companygroup.id=company.`companygroup_id`
        ';
        $criteria->group='company.id,t.id,offer.delivery';
        //$criteria->with = array('product', 'user', 'tag');

        return parent::searchInit($criteria);
    }
}