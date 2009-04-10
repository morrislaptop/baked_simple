<?php
App::import('Helper', 'Form');

class UniformHelper extends FormHelper {
	
	var $helpers = array('Html', 'Javascript');
	
	function create($model = null, $options = array()) {
		
		// include required files.
		$this->Javascript->link('/bakedsimple/js/uni-form.jquery', false);
		$this->Html->css('/bakedsimple/css/uni-form.css', 'stylesheet', null, false);
		
		// put in the uniForm class
		if ( !isset($options['class']) ) {
			$options['class'] = 'uniForm';
		}
		return parent::create($model, $options);
	}

	function input($fieldName, $options = array()) 
	{
		$view =& ClassRegistry::getObject('view');
		$this->setEntity($fieldName);
		$model =& ClassRegistry::getObject($this->model());
		$type = $model->getColumnType($this->field());
		
		if ( 'boolean' == $type ) {
			if ( !isset($options['div']) ) {
				$options['div'] = 'inlineLabel';
			}
			else {
				$options['div'] = 'inlineLabel ' . $options['div'];
			}
		}
		else if ( 'datetime' == $type || 'date' == $type ) {
			if ( !isset($options['between']) ) {
				$options['between'] = '<div class="multiField">';
			}
			else {
				$options['between'] .= '<div class="multiField">';
			}
			if ( !isset($options['after']) ) {
				$options['after'] = '</div>';
			}
			else {
				$options['after'] = '</div>' . $options['after'];
			}
		}
		
		if ( !isset($options['div']) ) {
			$options['div'] = 'ctrlHolder';
		}
		else {
			$options['div'] = 'ctrlHolder ' . $options['div'];
		}
		
		return parent::input($fieldName, $options);
	}
	
	function dateTime($fieldName, $dateFormat = 'DMY', $timeFormat = '12', $selected = null, $attributes = array(), $showEmpty = true) {
		if ( !isset($attributes['separator']) ) {
			$attributes['separator'] = null;
		}
		return parent::dateTime($fieldName, $dateFormat, $timeFormat, $selected, $attributes, $showEmpty);
	}
	function month($fieldName, $selected = null, $attributes = array(), $showEmpty = true) {
		if ( isset($attributes['name']) ) {
			$attributes['name'] .= '[month]';
		}
		$out = '<label class="blockLabel">Month';
		$out .= parent::month($fieldName, $selected, $attributes, $showEmpty);
		$out .= '</label>';
		return $out;
	}
	function day($fieldName, $selected = null, $attributes = array(), $showEmpty = true) {
		if ( isset($attributes['name']) ) {
			$attributes['name'] .= '[day]';
		}
		$out = '<label class="blockLabel">Day';
		$out .= parent::day($fieldName, $selected, $attributes, $showEmpty);
		$out .= '</label>';
		return $out;
	}
	function year($fieldName, $minYear = null, $maxYear = null, $selected = null, $attributes = array(), $showEmpty = true) {
		if ( isset($attributes['name']) ) {
			$attributes['name'] .= '[year]';
		}
		$out = '<label class="blockLabel">Year';
		$out .= parent::year($fieldName, $minYear, $maxYear, $selected, $attributes, $showEmpty);
		$out .= '</label>';
		return $out;
	}
	function hour($fieldName, $format24Hours = false, $selected = null, $attributes = array(), $showEmpty = true) {
		if ( isset($attributes['name']) ) {
			$attributes['name'] .= '[hour]';
		}
		$out = '<label class="blockLabel">Hour';
		$out .= parent::hour($fieldName, $format24Hours, $selected, $attributes, $showEmpty);
		$out .= '</label>';
		return $out;
	}
	function minute($fieldName, $selected = null, $attributes = array(), $showEmpty = true) {
		if ( isset($attributes['name']) ) {
			$attributes['name'] .= '[minute]';
		}
		$out = '<label class="blockLabel">Minute';
		$out .= parent::minute($fieldName, $selected, $attributes, $showEmpty);
		$out .= '</label>';
		return $out;
	}
	function meridian($fieldName, $selected = null, $attributes = array(), $showEmpty = true) {
		if ( isset($attributes['name']) ) {
			$attributes['name'] .= '[meridian]';
		}
		$out = '<label class="blockLabel">AM/PM';
		$out .= parent::meridian($fieldName, $selected, $attributes, $showEmpty);
		$out .= '</label>';
		return $out;
	}
	
	function text($fieldName, $options = array()) {
		if ( !isset($options['class']) ) {
			$options['class'] = 'textInput';
		}
		return parent::text($fieldName, $options);
	}
	
	function submit($caption = null, $options = array()) {
		if ( !isset($options['div']) ) {
			$options['div'] = 'buttonHolder';
		}
		return parent::submit($caption, $options);
	}
	
	function select($fieldName, $options = array(), $selected = null, $attributes = array(), $showEmpty = '') {
		if ( !isset($attributes['escape']) ) {
			$attributes['escape'] = false;
		}
		return parent::select($fieldName, $options, $selected, $attributes, $showEmpty);
	}
	
	function checkbox($fieldName, $options = array()) {
		if ( !isset($options['labelClass']) ) {
			
		}
		return parent::checkbox($fieldName, $options);
	}
}
?>