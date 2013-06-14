<?php
return array(
    array(
        'name' => 'child1',
        'options' => array(
            'uri' => 'http://www.child1.com',
        ),
        'attributes' => array(
            'class' => 'child1-class'
        ),
    ),
    array(
        'name' => 'child2',
        'options' => array(
            'label' => 'child2Label',
            'route' => 'test',
            'params' => array(
                'controller' => 'test',
                'action' => 'index'
            ),
        ),
        'pages' => array(
            array(
                'name' => 'child2-list',
                'options' => array(
                    'label' => 'List All',
                    'route' => 'test',
                    'params' => array(
                        'controller' => 'test',
                        'action' => 'list'
                    )
                )
            ),
            array(
                'name' => 'child2-add',
                'options' => array(
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
        'name' => 'child3',
        'options' => array(
            'label' => 'child3Label',
            'route' => 'foo',
            'params' => array(
                'controller' => 'foo',
                'action' => 'index'
            ),
        ),
        'pages' => array(
            array(
                'name' => 'child3-list',
                'options' => array(
                    'label' => 'child3: List All',
                    'route' => 'foo',
                    'params' => array(
                        'controller' => 'foo',
                        'action' => 'list'
                    )
                )
            ),
            array(
                'name' => 'child3-add',
                'options' => array(
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