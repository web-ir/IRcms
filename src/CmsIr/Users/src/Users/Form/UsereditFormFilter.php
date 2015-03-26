<?php
namespace CmsIr\Users\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\AbstractValidator;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;

class UsereditFormFilter extends InputFilter
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
            'name'     => 'surname',
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
            'name'       => 'email',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Uzupełnij pole!'
                        )
                    ),
                ),
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'message' =>  'Błedny format maila!'
                    )
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'upload',
            'required'   => false,
        ));

        $this->add(array(
            'name'       => 'dictionary_position_id',
            'required'   => false,
        ));

        $this->add(array(
            'name'       => 'dictionary_group_id',
            'required'   => false,
        ));

        $this->add(array(
            'name'       => 'position_description',
            'required'   => false,
        ));

	}
}