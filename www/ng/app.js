'use strict';

if (window.location.protocol != "http:")
    window.location.href = "http:" + window.location.href.substring(window.location.protocol.length);

// Declare app level module which depends on filters, and services
angular.module('Scripta', ['Scripta.filters', 'Scripta.services', 'Scripta.directives', 'Scripta.controllers'])
.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/status',   {templateUrl: 'partials/status.html',   controller: 'CtrlStatus'});
  $routeProvider.when('/miner',    {templateUrl: 'partials/miner.html',    controller: 'CtrlMiner'});
  $routeProvider.when('/settings', {templateUrl: 'partials/settings.html', controller: 'CtrlSettings'});
  $routeProvider.when('/backup',   {templateUrl: 'partials/backup.html',   controller: 'CtrlBackup'});
  $routeProvider.when('/terminal', {templateUrl: 'partials/terminal.html', controller: 'CtrlTerminal'});
  $routeProvider.otherwise({redirectTo: '/status'});
}]);
