<?php
namespace Sitece16\Entidades;

/*
 * Slim Auth Implementation Example
 *
 * @link      http://github.com/jeremykendall/slim-auth-impl Canonical source repo
 * @copyright Copyright (c) 2013-2015 Jeremy Kendall (http://about.me/jeremykendall)
 * @license   http://github.com/jeremykendall/slim-auth-impl/blob/master/LICENSE MIT
 */
use Zend\Permissions\Acl\Acl as ZendAcl;

/**
 * ACL for Slim Auth Implementation Example.
 */
class Acl extends ZendAcl {
    public function __construct() {
        /**
         * ROLES
         */
        $this->addRole('guest');
        $this->addRole('ALUMNO', 'guest');
        $this->addRole('SECRETARIA', 'ALUMNO');
        $this->addRole('COORDINADOR', 'SECRETARIA');
        $this->addRole('COOFINAN', 'COORDINADOR');
        $this->addRole('FINANCIERO', 'COOFINAN');
        $this->addRole('DIRECTOR', 'FINANCIERO');
        $this->addRole('SUPERVISOR', 'DIRECTOR');
        $this->addRole('ADMINISTRA', 'SUPERVISOR');
        $this->addRole('ROOT');

        /**
          * RECURSOS - rutas de la app.
          */
        // $this->addResource('/wh'); 
        // $this->addResource('/static'); 
        // Login/Logout
        $this->addResource('/');
        $this->addResource('/login');
        $this->addResource('/logout');
        $this->addResource('/api/v1[/{params:.*}]');
        $this->addResource('/docs[/{params:.*}]');
        $this->addResource('/webhook[/{params:.*}]');


        /**
          *  PERMISOS - el tercer argumento es método HTTP
          */
        //$this->allow('guest', '/wh', 'GET');
        //$this->allow('guest', '/static', 'GET');
        $this->allow('guest', '/', 'GET');
        $this->allow('guest', '/login', ['GET', 'POST']);
        $this->allow('guest', '/logout', 'GET');
        $this->allow('ROOT', '/api/v1[/{params:.*}]', 'GET');
        $this->allow('ROOT', '/docs[/{params:.*}]', 'GET');
        $this->allow('guest', '/webhook[/{params:.*}]', ['GET', 'POST']);
        

        // root puede hacer lo que sea
        $this->allow('ROOT');
    }
}

/**
 *     Array
 * INTENDENCIA
 * ALUMNO
 * PROFESOR
 * SECRETARIA
 * CAMISAS
 * CARRERA
 * PUBLICIDAD
 * COMWEB
 * WEB
 * SOPORTE
 * MODULISTA
 * COOVENTAS
 * PROMOTOR
 * SEP
 * RH
 * CONTADOR
 * COORDINADOR
 * JURIDICO
 * ACADEMICA
 * COOFINAN
 * FINANCIERO
 * DIRECTOR
 * GERENTE
 * SUPERVISOR
 * ADMINISTRA
 * ROOT
 */
