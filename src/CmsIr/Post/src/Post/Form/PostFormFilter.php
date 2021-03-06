<?php
namespace CmsIr\Post\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class PostFormFilter extends InputFilter
{
	public function __construct($sm)
	{
        $this->add(array(
            'name'       => 'name',
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
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'status_id',
            'required' => false,
        ));

        $this->add(array(
            'name'       => 'author_id',
            'required' => false,
        ));

        $this->add(array(
            'name'       => 'tag_id',
            'required' => false,
        ));

//        $this->add(array(
//            'name'       => 'url',
//            'required' => true,
//            'filters'  => array(
//                array('name' => 'StripTags'),
//                array('name' => 'StringTrim'),
//            ),
//            'validators' => array(
//                array(
//                    'name' => 'NotEmpty',
//                    'options' => array(
//                        'messages' => array(
//                            NotEmpty::IS_EMPTY => 'Uzupełnij pole!'
//                        )
//                    )
//                ),
//            ),
//        ));
	}
}