<?php

$menus[] = [
    'class'=>'',
    'icon'=>'fa fa-home',
    'text'=>__('Dashboard'),
    'url'=>route('admin.dashboard'),
];


$menus['Admins'] = [
    'permission'=> ['admin.admins.index'],
    'class'=>'',
    'icon'=>'fa fa-cogs',
    'text'=>__('Admins'),
    'sub'=>[
        'Admins'=> [
            'icon'=>'fa fa-users',
            'permission'=> 'admin.admins.index',
            'url'=> route('admin.admins.index'),
            'text'=> __('Admins'),
        ],
        'AdminGroups'=> [
            'icon'=>'fa fa-user-secret',
            'permission'=> 'admin.admin-groups.index',
            'url'=> route('admin.admin-groups.index'),
            'text'=> __('Admin groups'),
        ],
        'NewAdminGroups'=> [
            'icon'=>'fa fa-user-circle',
            'permission'=> 'admin.admin-groups.create',
            'url'=> route('admin.admin-groups.create'),
            'text'=> __('New group'),
        ],
        'NewAdmin'=> [
            'icon'=>'fa fa-user-plus',
            'permission'=> 'admin.admins.create',
            'url'=> route('admin.admins.create'),
            'text'=> __('New Admin'),
        ],
        'Logs'=> [
            'icon'=>'fa fa-flag',
            'permission'=> 'admin.logs.index',
            'url'=> route('admin.logs.index'),
            'text'=> __('Logs'),
        ],
    ]
];

$menus['settings'] = [
    'permission'=> ['admin.settings.index'],
    'class'=>'',
    'icon'=>'fa fa-cogs',
    'text'=>__('Settings'),
    'url'=>route('admin.settings.index'),
];

$menus['event'] = [
    'permission'=> ['admin.event.index'],
    'class'=>'',
    'icon'=>'fa fa-cogs',
    'text'=>__('event'),
    'sub'=>[
        'event'=> [
            'icon'=>'fa fa-users',
            'permission'=> 'admin.event.index',
            'url'=> route('admin.event.index'),
            'text'=> __('event'),
        ],
        'Newevent'=> [
            'icon'=>'fa fa-user-plus',
            'permission'=> 'admin.event.create',
            'url'=> route('admin.event.create'),
            'text'=> __('New event'),
        ],
    ]
];



$menus['emailmodel'] = [
    'permission'=> ['admin.emailmodel.index'],
    'class'=>'',
    'icon'=>'fa fa-cogs',
    'text'=>__('emailmodel'),
    'sub'=>[
        'emailmodel'=> [
            'icon'=>'fa fa-users',
            'permission'=> 'admin.emailmodel.index',
            'url'=> route('admin.emailmodel.index'),
            'text'=> __('emailmodel'),
        ],
        'Newemailmodel'=> [
            'icon'=>'fa fa-user-plus',
            'permission'=> 'admin.emailmodel.create',
            'url'=> route('admin.emailmodel.create'),
            'text'=> __('New emailmodel'),
        ],
    ]
];

$menus['contact'] = [
    'permission'=> ['admin.contact.index'],
    'class'=>'',
    'icon'=>'fa fa-cogs',
    'text'=>__('contact'),
    'sub'=>[
        'contact'=> [
            'icon'=>'fa fa-users',
            'permission'=> 'admin.contact.index',
            'url'=> route('admin.contact.index'),
            'text'=> __('contact'),
        ],
        'Newcontact'=> [
            'icon'=>'fa fa-user-plus',
            'permission'=> 'admin.contact.create',
            'url'=> route('admin.contact.create'),
            'text'=> __('New contact'),
        ],
    ]
];

$menus['contact'] = [
    'permission'=> ['admin.contact.index'],
    'class'=>'',
    'icon'=>'fa fa-cogs',
    'text'=>__('contact'),
    'sub'=>[
        'contact'=> [
            'icon'=>'fa fa-users',
            'permission'=> 'admin.contact.index',
            'url'=> route('admin.contact.index'),
            'text'=> __('contact'),
        ],
        'Newcontact'=> [
            'icon'=>'fa fa-user-plus',
            'permission'=> 'admin.contact.create',
            'url'=> route('admin.contact.create'),
            'text'=> __('New contact'),
        ],
    ]
];

$menus['contactemail'] = [
    'permission'=> ['admin.contactemail.index'],
    'class'=>'',
    'icon'=>'fa fa-cogs',
    'text'=>__('contactemail'),
    'sub'=>[
        'contactemail'=> [
            'icon'=>'fa fa-users',
            'permission'=> 'admin.contactemail.index',
            'url'=> route('admin.contactemail.index'),
            'text'=> __('contactemail'),
        ],
        'Newcontactemail'=> [
            'icon'=>'fa fa-user-plus',
            'permission'=> 'admin.contactemail.create',
            'url'=> route('admin.contactemail.create'),
            'text'=> __('New contactemail'),
        ],
    ]
];




?>


@foreach($menus as $onemenu)
    {!! GenerateMenu($onemenu) !!}
@endforeach
