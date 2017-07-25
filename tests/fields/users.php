<?php
return array(
    'columns' =>
        array(
            'id'    =>
                array(
                    'type'   => 'int',
                    'length' => '10',
                    'name'   => 'ID',
                ),
            'name'  =>
                array(
                    'type'   => 'varchar',
                    'length' => '255',
                    'name'   => '姓名',
                ),
            'email' =>
                array(
                    'type'   => 'varchar',
                    'length' => '255',
                    'name'   => 'Email',
                ),
        ),
    'list'    =>
        array(
            'default' =>
                array(
                    0 =>
                        array(
                            'name'   => 'Email',
                            'column' => 'email',
                        ),
                    1 =>
                        array(
                            'presenter' => true,
                            'column'    => 'gender',
                        ),
                ),
            'group'   =>
                array(
                    0 =>
                        array(
                            'column' => 'gender',
                        ),
                ),
        ),
);