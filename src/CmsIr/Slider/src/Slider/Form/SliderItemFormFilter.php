<?php
namespace CmsIr\Slider\Form;

use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class SliderItemFormFilter extends InputFilter
{
	public function __construct($sm)
	{
        $this->add(array(
            'name'     => 'name',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Uzupełnij pole!'
                        )
                    )
                )
            ),
        ));

        $this->add(array(
            'name'       => 'upload',
            'required'   => false,
        ));
        $this->add(array(
            'name'       => 'title',
            'required'   => false,
        ));
        $this->add(array(
            'name'       => 'description',
            'required'   => false,
        ));
        $this->add(array(
            'name'       => 'url',
            'required'   => false,
        ));
	}
}