<?php

return [
    [
        'url'      => '#',
        'title'    => 'Dashboard1',
        'subItems' => [
            [
                'title' => 'User',
                'route' => 'dashboard'
            ],
            [
                'title'  => 'Dashboard1-1',
                'action' => 'Admin\DashboardController@index'
            ],
            [
                'title' => 'Post',
                'route' => 'posts'
            ],
            [
                'url'      => '#',
                'title'    => 'Dashboard1-1',
                'subItems' => [
                    [
                        'url'      => '#',
                        'title'    => 'Dashboard1-1-1',
                        'subItems' => [
                            [
                                'url'   => '#',
                                'title' => 'Dashboard1-1-1-1'
                            ],
                            [
                                'url'   => '#',
                                'title' => 'Dashboard1-1-1-2'
                            ],
                        ]
                    ],
                    [
                        'url'   => '#',
                        'title' => 'Dashboard1-1-2',
                    ]
                ]
            ]

        ]
    ]
];