var app = angular.module('app', ['onsen']);

app.controller('LoginController', function ($scope, $timeout) {
  this.items = [
    {
      title: 'Water the plants',
      done: false,
    },
    {
      title: 'Walk the dog',
      done: true,
    },
    {
      title: 'Go to the dentist',
      done: false,
    },
    {
      title: 'Buy milk',
      done: false,
    },
    {
      title: 'Play tennis',
      done: true,
    }
  ]

  this.newTodo = function() {
    this.items.push({
      title: '',
      done: false
    });
  }.bind(this);

  this.focusInput = function(event) {
    $timeout(function() {
      var item = event.target.parentNode.querySelector('input[type="text"]');
      item.focus();
      item.select();
    });
  }

  this.clearCompleted = function() {
    this.items = this.items.filter(function(item) {
      return !item.done;
    });
  }.bind(this);

  this.selectedItem = -1;
});

app.controller('inhoudController', function ($scope, $http) {
  $http.get('data/dataHome.json').
    success(function(data, status, headers, config) {
      $scope.posts = data;
    }).
    error(function(data, status, headers, config) {
      // hier kunnen we eventueel de fouten opvangen.
    });
});
