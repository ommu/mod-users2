<?php
/**
 * User Newsletter (user-newsletter)
 * @var $this NewsletterController
 * @var $model UserNewsletter
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-users
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array( 
    'id'=>'support-newsletter-form', 
    'enableAjaxValidation'=>true, 
    //'htmlOptions' => array('enctype' => 'multipart/form-data') 
)); ?>
	<fieldset>
		<div class="clearfix">
			<?php if($launch == 2)
				$model->unsubscribe = 1;
			else {
				$model->unsubscribe = 0;
			}
			echo $form->hiddenField($model,'unsubscribe');
			?>
			<?php echo $form->textField($model,'email',array('maxlength'=>32, 'placeholder'=>$model->getAttributeLabel('email'), 'class'=>'span-9')); ?><?php echo CHtml::submitButton($launch == 0 ? Yii::t('phrase', 'Notify Me!') : ($launch == 1 ? Yii::t('phrase', 'Subscribe') : Yii::t('phrase', 'Unsubscribe')), array('onclick' => 'setEnableSave()')); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>

	</fieldset>
<?php $this->endWidget(); ?>
