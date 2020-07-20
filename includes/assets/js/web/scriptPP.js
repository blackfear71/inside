'use strict';

var parcoursApp = angular.module('parcoursApp', []);

parcoursApp.component('parcoursList', {
  templateUrl: 'vue/template/parcourslisttemplate.html',
  controller : function ParcoursListController() {
      this.listeParcours = listeParcoursJson;
      // this.orderProp = 'nom';
  }
});
