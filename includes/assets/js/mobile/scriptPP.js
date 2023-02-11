'use strict';

var parcoursApp = angular.module('parcoursApp', ['ngSanitize']);

parcoursApp.component('parcoursList',
{
    templateUrl: 'vue/mobile/template/parcourslisttemplate.html',
    controller : function ParcoursListController()
    {
        this.listeParcours = listeParcoursJson;
        // this.orderProp = 'nom';
    }
});