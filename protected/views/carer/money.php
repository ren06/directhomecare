<style type="text/css">
    #money{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_MONEY');
$this->pageSubTitle = 'Invoices and bank transfer history';
?>

<div class="row">
    <div class="large-6 medium-8 small-12 columns">
        <h3><?php echo Yii::t('texts', 'HEADER_BALANCE_AVAILABLE') . '&#58;&#160;' . CarerTransaction::getCreditBalance(Yii::app()->user->id)->text; ?></h3>
        <p class="rc-note">
            <?php echo 'You current balance will be paid to your bank account on 1st and 15th of the Month.' ?>
        </p>
        <h3>Bank account details</h3>
        <p class="rc-note">
            <?php echo 'Please maintain your UK bank account details below in order to receive your payment.' ?>
        </p>

        <?php if (Yii::app()->user->hasFlash('withdrawalRequest')): ?>
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('withdrawalRequest'); ?><br />
            </div>
            <?php
        endif;

        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'withdrawal-form',
            'enableAjaxValidation' => false,
        ));
        ?>
        <label>First name
            <?php echo $form->textField($model, 'first_name', array('maxlength' => 50, 'placeholder' => 'First name', 'class' => 'rc-field-disabled', 'disabled' => 'disabled')); ?>
        </label>
        <label>Last name
            <?php echo $form->textField($model, 'last_name', array('maxlength' => 50, 'placeholder' => 'Last name', 'class' => 'rc-field-disabled', 'disabled' => 'disabled')); ?>
        </label>
        <label>Sort code
            <?php echo $form->textField($model, 'sort_code', array('maxlength' => 6, 'placeholder' => 'Sort code e.g. 123456', 'class' => 'rc-field-medium')); ?>
            <?php echo $form->error($model, 'sort_code', array('class' => 'rc-error')); ?>
        </label>
        <label>Account number
            <?php echo $form->textField($model, 'account_number', array('maxlength' => 8, 'placeholder' => 'Account number e.g. 12345678', 'class' => 'rc-field-medium')); ?>
            <?php echo $form->error($model, 'account_number', array('class' => 'rc-error')); ?>
        </label>
        <?php // echo $form->textField($model, 'amount', array('maxlength' => 7, 'placeholder' => 'Amount in Pounds e.g. 27', 'class' => 'rc-field-small')); ?>
        <?php //echo $form->error($model, 'amount', array('class' => 'rc-error')); ?>
        <?php //echo $form->textField($model, 'decimals', array('maxlength' => 2, 'placeholder' => 'Pence e.g. 10', 'class' => 'rc-field-small')); ?>
        <?php echo CHtml::submitButton(Yii::t('texts', 'BUTTON_WITHDRAW_MONEY'), array('class' => 'button expand', 'submit' => array('carer/money'))); ?>
        <?php $this->renderPartial('/common/_ajaxLoader'); ?>
        <?php $this->endWidget(); ?>        
    </div>
</div>
<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php
        if (count($dataProvider->getData()) > 0) {
            ?>
            <h3><?php echo Yii::t('texts', 'HEADER_TRANSACTION_HISTORY'); ?></h3>
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'id' => 'transactions',
                'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
                'template' => '{items}{pager}',
                'columns' => array(
                    array(
                        'class' => 'CDataColumn', // can be omitted, default
                        'id' => 'reference',
                        'headerHtmlOptions' => array('style' => 'width:79px'),
                        //'name' => 'id', TO DISABLE SORTING
                        'header' => Yii::t('texts', 'TABLES_HEADER_TRANSACTION_REFERENCE'),
                        'value' => function($data, $row) {
                            return BusinessLogic::getReference($data);
                        },
                        'type' => 'raw',
                    ),
                    array(
                        'class' => 'CDataColumn',
                        'id' => 'created',
                        'headerHtmlOptions' => array('style' => 'width:79px'),
                        'header' => Yii::t('texts', 'TABLES_HEADER_TRANSACTION_DATE'),
                        'value' => function($data, $row) {
                            return Calendar::convert_DBDateTime_DisplayDateText($data->created);
                        },
                        'type' => 'raw',
                    ), array(
                        'header' => Yii::t('texts', 'TABLES_HEADER_TRANSACTION'),
                        'class' => 'CDataColumn',
                        'headerHtmlOptions' => array('style' => 'width:344px'),
                        'htmlOptions' => array('style' => 'text-align:left;padding-left:24px'),
                        'value' => function($data, $row) {
                            return $data->displayServiceTypeUsersConditionsHTML();
                        },
                        'type' => 'raw',
                    ), array(
                        'class' => 'CDataColumn',
                        'headerHtmlOptions' => array('style' => 'width:79px'),
                        //'name' => 'created', //by RC to stop sorting
                        'header' => Yii::t('texts', 'TABLES_HEADER_WAGE'),
                        'value' => function($data, $row) {
                            $price = new Price($data->paid_credit, $data->currency);
                            if ($price->amount == null) {
                                return '';
                            } else {
                                return $price->text;
                            }
                        },
                        'type' => 'raw',
                    ),
                    array(
                        'class' => 'CDataColumn',
                        'headerHtmlOptions' => array('style' => 'width:79px'),
                        'header' => Yii::t('texts', 'TABLES_HEADER_WITHDRAWAL'),
                        'value' => function($data, $row) {
                            $price = new Price($data->withdraw, $data->currency);
                            if ($price->amount == null) {
                                return '';
                            } else {
                                return $price->text;
                            }
                        },
                        'type' => 'raw',
                    ),
                    array(
                        'class' => 'CDataColumn',
                        'headerHtmlOptions' => array('style' => 'width:79px'),
                        'header' => Yii::t('texts', 'TABLES_HEADER_BALANCE'),
                        'value' => function($data, $row) {
                            $price = new Price($data->credit_balance, $data->currency);
                            return $price->text;
                        },
                        'type' => 'raw',
                    ),
                    array(
                        'class' => 'CButtonColumn', // can be omitted, default
                        'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
                        'template' => '{details} {invoice}',
                        'visible' => true,
                        'headerHtmlOptions' => array('style' => 'width:147px'),
                        'buttons' => array(
                            'details' => array(
                                'label' => Yii::t('texts', 'BUTTON_DETAILS'), //Text label of the button.
                                'url' => 'Yii::app()->createUrl("carer/missionDetails", array("id"=>$data->id_mission, "scenario"=>NavigationScenario::CARER_BACK_TO_MONEY))',
                                'options' => array('title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_DETAILS'), 'class' => 'rc-linkbutton-white-small'),
                                'visible' => '$data->isDetailslButtonVisible()',
                            ),
                            'invoice' => array(
                                'label' => Yii::t('texts', 'BUTTON_INVOICE'), //Text label of the button.
                                'url' => 'Yii::app()->createUrl("carer/missionInvoice", array("id"=>$data->id_mission, "scenario"=>NavigationScenario::CARER_BACK_TO_MONEY))',
                                'options' => array('title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_INVOICE'), 'class' => 'rc-linkbutton-white-small'),
                                'visible' => '($data->type == CarerTransaction::CREDIT_PAYMENT || $data->type == CarerTransaction::CREDIT_CANCEL_CLIENT)',
                            ),
                        ),
                    ),
                ),
            ));
        } else {
            //echo '<b>' . Yii::t('texts', 'TABLES_NO_TRANSACTIONS') . '</b>';
        }
        ?>  
    </div>
</div>