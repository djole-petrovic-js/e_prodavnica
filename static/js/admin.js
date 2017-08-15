( function() {

  'use strict';

  angular.module('admin',['ngRoute','ngSanitize'])
  .config(['$routeProvider',function($routeProvider){
    $routeProvider
      .when('/',{
        templateUrl:'static/templates/proizvodi.html',
        controller:'productsCtrl'
      })
      .when('/kategorije',{
        templateUrl:'static/templates/kategorije.html',
        controller:'categoriesCtrl'
      })
      .when('/linkovi',{
        templateUrl:'static/templates/links.html',
        controller:'linksCtrl'
      })
      .when('/korisnici',{
        templateUrl:'static/templates/korisnici.html',
        controller:'usersCtrl'
      })
      .when('/anketa',{
        templateUrl:'static/templates/anketa.html',
        controller:'pollCtrl'
      })
      .when('/slike_proizvodi',{
        templateUrl:'static/templates/slike_proizvodi.html',
        controller:'photosCtrl'
      })
      .otherwise({
        redirectTo:'/'
      });
  }])

  .controller('photosCtrl',['$rootScope','$http','$scope',function($rootScope,$http,$scope){
    $scope.productID = null;

    $scope.getPhotos = function() {
      $http.get('/api/get_photos.php?id=' + $scope.productID).then(function(response){
        $scope.photos = response.data;
      });
    }

    $scope.modifyPhoto = function(photo) {
      var data = {
        id_slike:photo.id_slike,
        naziv:photo.naziv_slike,
        id:photo.id_proizvodi
      };

      $http.post('/api/modify_photo.php',data).then(function(response){
        if ( response.data.success === true ) {
          $rootScope.info = 'Fotografija uspesno azurirana...';
          $('#informationsModal').modal();
        } else {
          $rootScope.info = 'Doslo je do greske , probajte ponovo';
          $('#informationsModal').modal();
        }
      });
    }

    $scope.deletePhoto = function(photoID) {
      $http.post('/api/delete_photo.php',{ id:photoID }).then(function(response){
        if ( response.data.success === true ) {
          var index = $scope.photos.findIndex(function(p){
            return p.id_slike === photoID
          });

          $scope.photos.splice(index,1);
          $rootScope.info = 'Fotografija uspesno obrisana...';
          $('#informationsModal').modal();
        } else {
          $rootScope.info = 'Doslo je do greske , probajte ponovo';
          $('#informationsModal').modal();
        }
      });
    }

    $http.get('/api/get_all_products.php').then(function(response){
      $scope.products = response.data;
    });
  }])

  .controller('productsCtrl',[
    '$rootScope',
    '$scope',
    '$http',
    '$sce',
    function($rootScope,$scope,$http,$sce){
      $scope.showProductForm = false;
      $scope.id_kategorije = null;

      $scope.product = {
        name:null,
        category:null,
        price:null,
        qty:null,
        image:null,
      };

      $scope.modifyProduct = function(product) {
        var data = {
          id_proizvodi:product.id_proizvodi,
          naziv:product.pNaziv,
          id_kategorije:product.id_kategorije,
          cena:product.cena,
          kolicina:product.kolicina,
          opis:product.opis
        };

        $http.post('/api/modify_product.php',data).then(function(response){
          var message;

          if ( response.data.success === true ) {
            message = 'Uspesno azurirano';
          } else {
            message = 'Doslo je do greske , proverite podatke';
          }

          $('#informationsModal').modal();
          $rootScope.info = message;
        });
      }

      $scope.showHideProductForm = function() {
        $scope.showProductForm = !$scope.showProductForm;
      }

      $scope.deleteProduct = function(id) {
        $http.post('/api/delete_product.php',{ id:id }).then(function(response){
          if ( response.data.success === true  ) {
            var index = $scope.products.findIndex(function(p){
              return p.id_proizvodi === id
            });

            $scope.products.splice(index,1);

            $rootScope.info = 'Uspesno obrisano...';
            $('#informationsModal').modal();
          } else {
            $rootScope.info = 'Doslo je do greske , probajte ponovo...';
            $('#informationsModal').modal();
          }
        });
      }

      $http.get('/api/get_all_categories.php').then(function(response){
        $http.get('/api/get_all_products.php').then(function(response){
         $scope.products = response.data;
      });

        var categories = response.data;

        var options = categories.map(function(o){
          var value = o.id_kategorije;
          var label = o.naziv;

          return '<option value="' + value + '">' + label + '</option>';
        });

        $scope.categories = response.data;
        $scope.options = $sce.trustAsHtml(options.join(''));
      });
  }])

  .controller('categoriesCtrl',['$rootScope','$scope','$http',function($rootScope,$scope,$http){
    $scope.categorieName = '';

    $scope.addCategory = function() {
      $http.post('/api/add_categorie.php',{ name:$scope.categorieName }).then(function(response){
        $http.get('/api/get_all_categories.php').then(function(response){
          $scope.categories = response.data;
        });
      });
    }

    $scope.modifyCategorie = function(categorie) {
      var data = {
        id:categorie.id_kategorije,
        naziv:categorie.naziv
      };

      $http.post('/api/modify_categorie.php',data).then(function(response){
        if ( response.data.success === true ) {
          $('#informationsModal').modal();
          $rootScope.info = 'Kategorija uspesno azurirana...';
        } else {
          $('#informationsModal').modal();
          $rootScope.info = 'Doslo je do greske...';
        }
      });
    }

    $scope.deleteCategorie = function(id) {
      $http.post('/api/delete_categorie.php',{ id:id }).then(function(response){
        var index = $scope.categories.findIndex(function(c){
          return c.id_kategorije === id
        });

        $scope.categories.splice(index,1);
      });
    }

    $http.get('/api/get_all_categories.php').then(function(response){
      $scope.categories = response.data;
    });
  }])

  .controller('linksCtrl',['$scope','$http','$rootScope',function($scope,$http,$rootScope){
    $scope.indexOfLinkToDelete = null;
    $scope.linkToModify = null;

    $scope.linkToAdd = {
      ime:null,
      link:null,
      dozvola:null
    };

    $scope.addLink = function() {
      $http.post('/api/add_link.php',$scope.linkToAdd).then(function(response){
        $scope.linkToAdd = {
          ime:null,
          link:null,
          dozvola:null
        };
        
        if ( response.data.success === true ) {
          $('#informationsModal').modal();
          $rootScope.info = 'Link uspesno dodat...';
        } else {
          $('#informationsModal').modal();
          $rootScope.info = 'Doslo je do greske...';
        }

        $http.get('/api/get_links.php').then(function(response){
          $scope.links = response.data;
        });
      });
    }

    $scope.showModificationForm = function(link) {
      $('#modificationModal').modal();
      $scope.linkToModify = link;
    }

    $scope.modifyLink = function() {
      $('#modificationModal').modal('hide');

      $http.post('/api/modify_link.php',$scope.linkToModify).then(function(response){
        if ( response.data.success === true ) {
          $('#informationsModal').modal();
          $rootScope.info = 'Link uspesno promenjen...';
        } else {
          $('#informationsModal').modal();
          $rootScope.info = 'Doslo je do greske , pokusajte ponovo...';
        }
      });
    }

    $scope.confirmDelete = function(index) {
      $('#confirmDeleteModal').modal();
      $scope.indexOfLinkToDelete = index;
    }

    $scope.deleteLink = function() {
      $('#confirmDeleteModal').modal('hide');

      var data = {
        index:$scope.indexOfLinkToDelete
      }

      $http.post('/api/delete_link.php',data).then(function(response){
        var index = $scope.links.findIndex(function(link){
          return link.id_linkovi === $scope.indexOfLinkToDelete
        });

        $scope.links.splice(index,1);
      });
    }

    $http.get('/api/get_links.php').then(function(response){
      $scope.links = response.data;
    });
  }])

  .controller('usersCtrl',['$rootScope','$scope','$http',function($rootScope,$scope,$http){
    $scope.deleteUser = function(userID) {
      $http.post('/api/delete_user.php',{ id:userID }).then(function(response){
        if ( response.data.success === true ) {
          var index = $scope.users.findIndex(function(u) {
            return u.id_korisnik === userID
          });

          $scope.users.splice(index,1)

          $('#informationsModal').modal();
          $rootScope.info = 'Korisnik uspesno obrisan...';
        } else {
          $('#informationsModal').modal();
          $rootScope.info = 'Doslo je do greske , pokusajte ponovo...';
        }
      });
    }

    $scope.updateUser = function(id) {
      var user = $scope.users.find(function(user){
        return user.id_korisnik === id;
      });

      var data = {
        userID:user.id_korisnik,
        dozvola:user.dozvola
      };

      $http.post('/api/update_user.php',data).then(function(response){
        if ( response.data.success === true ) {
          $('#informationsModal').modal();
          $rootScope.info = 'Korisnik uspesno promenjen...';
        } else {
          $('#informationsModal').modal();
          $rootScope.info = 'Doslo je do greske , pokusajte ponovo...';
        }
      });
    }

    $http.get('/api/get_all_users.php').then(function(response){
      $scope.users = response.data;
    });
  }])

  .controller('pollCtrl',['$scope','$http','$rootScope',function($scope,$http,$rootScope){
    $scope.poll = null;
    $scope.pollName = null;
    $scope.pollID = null;

    $scope.resetVotes = function() {
      $http.get('/api/reset_votes.php').then(function(response){
        if ( response.data.success === true ) {
          $scope.poll.forEach(function(opt){
            opt.broj_glasova = 0;
          });

          $('#informationsModal').modal();
          $rootScope.info = 'Anketa uspesno resetovana...';
        } else {
          $('#informationsModal').modal();
          $rootScope.info = 'Doslo je do greske , pokusajte ponovo...';
        }
      });
    }

    $scope.updatePoll = function() {
      var data = {
        pollName:$scope.pollName,
        pollID:$scope.pollID,
        pollShow:$scope.showPoll === true ? 1 : 0,
        options:$scope.poll
      };

      $http.post('/api/update_poll.php',data).then(function(response){
        if ( response.data.success ) {
          $('#informationsModal').modal();
          $rootScope.info = 'Anketa Uspesno Azurirana';
        } else {
          $('#informationsModal').modal();
          $rootScope.info = 'Doslo je do greske , pokusajte ponovo...';
        }
      });
    }

    $scope.hideShowPoll = function() {
      $scope.showPoll = !$scope.showPoll;
    }

    $http.get('/api/get_poll.php').then(function(response){
      $scope.poll = response.data;
      $scope.pollName = response.data[0].ime;
      $scope.pollID = response.data[0].id_anketa;
      $scope.showPoll = +response.data[0].vidljivost === 1;
    });

    $http.get('/api/get_poll_votes_count.php').then(function(response){
      $scope.votesSum = response.data[0].broj_glasova;
    });

  }]);

}());