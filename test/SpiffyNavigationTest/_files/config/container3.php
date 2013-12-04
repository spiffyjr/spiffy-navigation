<?php
return array(
    array(
        'options' => array(
            'uri' => 'http://www.child1.com',
            'name' => 'child1',
        ),
        'attributes' => array(
            'class' => 'child1-class',
        ),
    ),
    array(
        'options' => array(
            'name' => 'child2',
            'role' => 'foo',
            'permission' => 'child2',
            'label' => 'child2Label',
            'route' => 'test',
            'params' => array(
                'controller' => 'test',
                'action' => 'index'
            ),
        ),
        'pages' => array(
            array(
                'options' => array(
                    'name' => 'child2-list',
                    'label' => 'List All',
                    'route' => 'test',
                    'params' => array(
                        'controller' => 'test',
                        'action' => 'list'
                    )
                )
            ),
            array(
                'options' => array(
                    'name' => 'child2-add',
                    'label' => 'Create',
                    'route' => 'test',
                    'params' => array(
                        'controller' => 'test',
                        'action' => 'create'
                    )
                )
            )
        )
    ),
    array(
        'options' => array(
            'name' => 'child3',
            'label' => 'child3Label',
            'route' => 'foo',
            'params' => array(
                'controller' => 'foo',
                'action' => 'index'
            ),
        ),
        'pages' => array(
            array(
                'options' => array(
                    'name' => 'child3-list',
                    'label' => 'child3: List All',
                    'route' => 'foo',
                    'params' => array(
                        'controller' => 'foo',
                        'action' => 'list'
                    )
                )
            ),
            array(
                'options' => array(
                    'name' => 'child3-add',
                    'role' => 'foo',
                    'permission' => 'child3-add',

                    'label' => 'child3: Create',
                    'route' => 'foo',
                    'params' => array(
                        'controller' => 'foo',
                        'action' => 'create'
                    )
                )
            )
        )
    ),
);