angular.module('skApp.ngRemoteValidate', [])
.directive('ngRemoteValidate', ['$http', function ($http) {
    return {
        require: 'ngModel',
        link: function (scope, elem, attrs, ctrl) {
            elem.on('blur', function (evt) {
                scope.$apply(function () {
                    var params = {};
                    params[ctrl.$name] = elem.val();

                    $http({
                        method: 'GET',
                        url: attrs.ngRemoteValidate,
                        params: params
                    }).then(function(response) {
                        if (typeof response.data[0] !== 'undefined') {
                            ctrl.$setValidity('unique', false);
                        } else {
                            ctrl.$setValidity('unique', true);
                        }
                    });
                });
            });
        }
    };
}]);
