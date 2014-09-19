<?php
return array(
    'fake' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users',
            'defaults' => array(
            ),
        ),
    ),
    'users-list' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Index',
                'action'     => 'usersList',
            ),
        ),
    ),
    'user-create' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users/create',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Index',
                'action'     => 'create',
            ),
        ),
    ),
    'user-edit' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users/edit/:id',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Index',
                'action'     => 'edit',
            ),
            'constraints' => array(
                'id' => '[0-9]+'
            ),
        ),
    ),
    'user-preview' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users/preview/:id',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Index',
                'action'     => 'preview',
            ),
            'constraints' => array(
                'id' => '[0-9]+'
            ),
        ),
    ),
    'user-delete' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users/delete/:id',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Index',
                'action'     => 'delete',
            ),
            'constraints' => array(
                'id' => '[0-9]+'
            ),
        ),
    ),
);