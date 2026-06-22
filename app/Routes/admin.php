<?php

/**
 * Mapa de rutas del panel administrativo
 * Todas las rutas incluyen el prefijo "admin/"
 */

$adminRoutes = [
    // Rutas del panel principal
    'admin/dashboard' => [
        'controller' => 'AdministradorController',
        'action' => 'dashboard',
    ],
    'admin/usuarios' => [
        'controller' => 'AdministradorController',
        'action' => 'usuarios',
    ],
    'admin/administradores' => [
        'controller' => 'AdminController',
        'action' => 'index',
    ],
    'admin/administradores/crear' => [
        'controller' => 'AdminController',
        'action' => 'crear',
    ],
    'admin/administradores/editar' => [
        'controller' => 'AdminController',
        'action' => 'editar',
    ],
    'admin/administradores/eliminar' => [
        'controller' => 'AdminController',
        'action' => 'delete',
    ],
    'admin/administradores/guardar' => [
        'controller' => 'AdminController',
        'action' => 'store',
    ],
    'admin/administradores/actualizar' => [
        'controller' => 'AdminController',
        'action' => 'update',
    ],




    // CRUD de Portadas
    'admin/portadas' => [
        'controller' => 'PortadaController',
        'action' => 'index',
    ],
    'admin/portadas/ver' => [
        'controller' => 'PortadaController',
        'action' => 'ver',
    ],
    'admin/portadas/crear' => [
        'controller' => 'PortadaController',
        'action' => 'crear',
    ],
    'admin/portadas/editar' => [
        'controller' => 'PortadaController',
        'action' => 'editar',
    ],
    'admin/portadas/eliminar' => [
        'controller' => 'PortadaController',
        'action' => 'delete',
    ],
    'admin/portadas/guardar' => [
        'controller' => 'PortadaController',
        'action' => 'store',
    ],
    'admin/portadas/actualizar' => [
        'controller' => 'PortadaController',
        'action' => 'update',
    ],
    'admin/portadas/verificar-orden' => [
        'controller' => 'PortadaController',
        'action' => 'verificarOrden',
    ],
    'admin/portadas/toggle' => [
        'controller' => 'PortadaController',
        'action' => 'toggle',
    ],


    // CRUD de Contactos
    'admin/contactos' => [
        'controller' => 'ContactoController',
        'action' => 'index',
    ],
    'admin/contactos/ver' => [
        'controller' => 'ContactoController',
        'action' => 'ver',
    ],
    'admin/contactos/crear' => [
        'controller' => 'ContactoController',
        'action' => 'crear',
    ],
    'admin/contactos/editar' => [
        'controller' => 'ContactoController',
        'action' => 'editar',
    ],
    'admin/contactos/eliminar' => [
        'controller' => 'ContactoController',
        'action' => 'delete',
    ],
    'admin/contactos/guardar' => [
        'controller' => 'ContactoController',
        'action' => 'store',
    ],
    'admin/contactos/actualizar' => [
        'controller' => 'ContactoController',
        'action' => 'update',
    ],
    'admin/contactos/toggle' => [
        'controller' => 'ContactoController',
        'action' => 'toggle',
    ],
    'admin/contactos/verificar-orden' => [
        'controller' => 'ContactoController',
        'action' => 'verificarOrden',
    ],
];

return $adminRoutes;