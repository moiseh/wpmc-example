<?php
/*
* Plugin Name: WP Magic Crud Example
* Description: Provides a clean example of how to use Magic admin CRUD plugin for WordPress plugin
* Version:     1.0
* Author:      Moises Heberle
* License:     GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( 'Not allowed' );

add_action('wpmc_entities', function($entities){
    $entities['contact'] = [
        'table_name' => 'sys_contacts',
        'default_order' => 'name',
        'display_field' => 'name',
        'singular' => 'Contact',
        'plural' => 'Contacts',
        'restrict_logged' => 'user_id',
        'fields' => [
            'name' => [
                'label' => 'Nome',
                'type' => 'text',
                'required' => true,
                'flags' => ['list','sort','view','add','edit'],
            ],
            'student_id' => [
                'label' => 'Estudante',
                'type' => 'belongs_to',
                'ref_entity' => 'student',
                'required' => true,
                'flags' => ['list','sort','view','add','edit'],
            ],
            'lastname' => [
                'label' => 'Segundo nome',
                'type' => 'text',
                'flags' => ['list','sort','view','add','edit'],
            ],
            'email' => [
                'label' => 'E-mail',
                'type' => 'email',
                'flags' => ['list','sort','view','add','edit'],
            ],
            'phone' => [
                'label' => 'Telefone',
                'type' => 'integer',
                'flags' => ['list','sort','view','add','edit'],
            ],
            'cellphone' => [
                'label' => 'Celular',
                'type' => 'text',
                'flags' => ['list','sort','view','add','edit'],
            ],
        ]
    ];

    $entities['student'] = [
        'table_name' => 'sys_students',
        'default_order' => 'name',
        'display_field' => 'name',
        'singular' => 'Student',
        'plural' => 'Students',
        'restrict_logged' => 'user_id',
        'fields' => [
            'name' => [
                'label' => 'Nome',
                'type' => 'text',
                'required' => true,
                'flags' => ['list','sort','view','add','edit'],
            ],
            'lastname' => [
                'label' => 'Segundo nome',
                'type' => 'text',
                'flags' => ['list','sort','view','add','edit'],
            ],
            'email' => [
                'label' => 'E-mail',
                'type' => 'email',
                'flags' => ['list','sort','view','add','edit'],
            ],
            'contacts' => [
                'label' => 'Contatos',
                'type' => 'has_many',
                'ref_entity' => 'contact',
                'ref_column' => 'student_id',
                'flags' => ['list','sort','view','add','edit'],
            ],
        ]
    ];

    return $entities;
}, 10, 2);