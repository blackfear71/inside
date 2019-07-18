'use strict';

angular.module('parcoursApp', []);

angular.
    module('parcoursApp').
    component('parcoursList', {
        templateUrl: 'vue/template/parcourslisttemplate.html',
        controller : function ParcoursListController() {
            this.listeParcours = listeParcoursJson;
            // this.orderProp = 'nom';
            console.log(listeParcoursJson);
        }
});