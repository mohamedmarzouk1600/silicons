<?php

return [
    /*
    * General
    */
    [
        'name' => 'General',
        'permissions' => [
            'admin-dashboard' => ['admin.dashboard'],
        ]
    ],
    /*
    * Admins
    */
    [
        'name' => 'Admins',
        'permissions' => [
            'view-admin' => ['admin.admins.index'],
            'add-admin' => ['admin.admins.create', 'admin.admins.store'],
            'view-one-admin' => ['admin.admins.show'],
            'edit-admin' => ['admin.admins.edit', 'admin.admins.update'],
            'delete-admin' => ['admin.admins.destroy'],
        ]
    ],


    /*
    * Admin groups
    */
    [
        'name' => 'admin-groups',
        'permissions' => [
            'view-group' => ['admin.admin-groups.index'],
            'add-group' => ['admin.admin-groups.create', 'admin.admin-groups.store'],
            'view-one-group' => ['admin.admin-groups.show'],
            'edit-group' => ['admin.admin-groups.edit', 'admin.admin-groups.update'],
            'delete-group' => ['admin.admin-groups.destroy'],
        ]
    ],

    /*
    * logs
    */
    [
        'name' => 'logs',
        'permissions' => [
            'view-logs' => ['admin.logs.index'],
            'view-one-log' => ['admin.logs.show', 'admin.model-deleted.restore'],
        ]
    ],

    /*
    * Settings
    */
    [
        'name' => 'settings',
        'permissions' => [
            'view-setting' => ['admin.settings.index'],
            'add-setting' => ['admin.settings.create', 'admin.settings.store'],
            'view-one-setting' => ['admin.settings.show'],
            'edit-setting' => ['admin.settings.edit', 'admin.settings.update'],
            'delete-setting' => ['admin.settings.destroy'],
        ]
    ],



    /*
    * emailmodel
    */
    [
        'name' => 'emailmodel',
        'permissions' => [
            'view-emailmodel'=>['admin.emailmodel.index'],
            'add-emailmodel'=>['admin.emailmodel.create','admin.emailmodel.store'],
            'view-one-emailmodel'=>['admin.emailmodel.show'],
            'edit-emailmodel'=>['admin.emailmodel.edit','admin.emailmodel.update'],
            'send-emails-emailmodel'=>['admin.emailmodel.send.mail'],
            'delete-emailmodel'=>['admin.emailmodel.destroy'],
        ]
    ],




    /*
    * event
    */
    [
        'name' => 'event',
        'permissions' => [
            'view-event'=>['admin.event.index'],
            'add-event'=>['admin.event.create','admin.event.store'],
            'view-one-event'=>['admin.event.show'],
            'edit-event'=>['admin.event.edit','admin.event.update'],
            'upload-event'=>['admin.event.upload'],
            'import-event'=>['admin.event.import'],
            'delete-event'=>['admin.event.destroy'],
        ]
    ],


    /*
    * contact
    */
    [
        'name' => 'contact',
        'permissions' => [
            'view-contact'=>['admin.contact.index'],
            'add-contact'=>['admin.contact.create','admin.contact.store'],
            'view-one-contact'=>['admin.contact.show'],
            'edit-contact'=>['admin.contact.edit','admin.contact.update'],
            'delete-contact'=>['admin.contact.destroy'],
        ]
    ],


    /*
    * contactemail
    */
    [
        'name' => 'contactemail',
        'permissions' => [
            'view-contactemail'=>['admin.contactemail.index'],
            // 'add-contactemail'=>['admin.contactemail.create','admin.contactemail.store'],
            'view-one-contactemail'=>['admin.contactemail.show'],
            'edit-contactemail'=>['admin.contactemail.edit','admin.contactemail.update'],
            'delete-contactemail'=>['admin.contactemail.destroy'],
        ]
    ],


    

];
