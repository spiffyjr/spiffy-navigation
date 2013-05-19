<?php
return array(
    array(
        'name' => 'child1',
        'properties' => array(
            'uri' => 'http://www.child1.com',
        ),
        'attributes' => array(
            'class' => 'child1-class'
        ),
    ),
    array(
        'name' => 'child2',
        'properties' => array(
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
                'properties' => array(
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
                'properties' => array(
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
        'properties' => array(
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
                'properties' => array(
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
                'properties' => array(
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