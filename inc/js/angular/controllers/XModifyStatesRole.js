// Generated by CoffeeScript 1.8.0
(function() {
  angular.module("ximdex.module.xmodifystatesrole", ['ngAnimate']).controller("XModifyStatesRoleCtrl", [
    "$scope", "$http", "xUrlHelper", "$timeout", function($scope, $http, xUrlHelper, $timeout) {
      var url;
      url = xUrlHelper.getAction({
        action: 'modifystatesrole',
        method: 'update_states'
      });
      return $scope.saveChanges = function() {
        var petition;
        $scope.loading = true;
        petition = $http.post(url, {
          states: $scope.all_states,
          idRole: $scope.idRole
        });
        petition.success(function(data, status, headers, config) {
          $scope.loading = false;
          if (data.message !== "") {
            $scope.messageClass = data.result === "ok" ? "message-success" : "message-error";
            $scope.thereAreMessages = true;
            $scope.message = data.message;
            return $timeout(function() {
              $scope.thereAreMessages = false;
              return $timeout(function() {
                return $scope.message = "";
              }, 500);
            }, 2000);
          }
        });
        return petition.error(function(data, status, headers, config) {
          return $scope.loading = false;
        });
      };
    }
  ]);

}).call(this);

//# sourceMappingURL=XModifyStatesRole.js.map
