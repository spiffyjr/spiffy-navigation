<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'SpiffyNavigation\ModuleOptions'      => 'SpiffyNavigation\ModuleOptionsFactory',
            'SpiffyNavigation\Service\Navigation' => 'SpiffyNavigation\Service\NavigationFactory'
        )
    ),

    'spiffy_navigation' => array(
        'containers' => array(),

        'listeners' => array(),

        'providers' => array(),
    ),
);