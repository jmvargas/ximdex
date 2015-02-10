// Generated by CoffeeScript 1.8.0
angular.module("ximdex.main.controller").controller("XTabsCtrl", [
  "$scope", "xTabs", "xUrlHelper", "$http", "$interval", "$window", "$timeout", function($scope, xTabs, xUrlHelper, $http, $interval, $window, $timeout) {
    var rightPosition;
    $scope.tabs = xTabs.getTabs();
    $scope.removeTab = xTabs.removeTab;
    $scope.setActiveTab = xTabs.setActive;
    $scope.closeAllTabs = xTabs.closeAll;
    $scope.offAllTabs = xTabs.offAll;
    $scope.activeIndex = xTabs.activeIndex;
    $scope.submitForm = xTabs.submitForm;
    $scope.closeTabById = xTabs.removeTabById;
    $scope.reloadTabById = xTabs.reloadTabById;
    $scope.openAction = function(action, nodes) {
      var n, newNode, nodesArray, _i, _len;
      nodesArray = [];
      if (Array.isArray(nodes)) {
        for (_i = 0, _len = nodes.length; _i < _len; _i++) {
          n = nodes[_i];
          newNode = {
            nodeid: n
          };
          nodesArray.push(newNode);
        }
      } else if (nodes) {
        nodesArray.push({
          nodeid: nodes
        });
      }
      xTabs.pushTab(action, nodesArray);
    };
    $scope.menuTabsEnabled = false;
    $scope.showingMenu = false;
    $scope.limitTabs = 9999999;
    $scope.reloadWelcomeTab = function() {
      var nodes, url;
      nodes = [
        {
          nodeid: 10000
        }
      ];
      url = xUrlHelper.getAction({
        action: "welcome",
        nodes: nodes
      });
      $http.get(url).success(function(data) {
        var newtab;
        if (data) {
          newtab = {
            id: "10000_welcome",
            name: "welcome",
            content: data,
            nodes: nodes,
            action: null,
            command: "welcome",
            blink: false,
            show: true,
            url: url,
            history: [url]
          };
          xTabs.loadCssAndJs(newtab);
        }
      });
    };
    $scope.reloadWelcomeTab();
    $scope.$on('nodemodified', function(event, nodeId) {
      $scope.reloadWelcomeTab();
    });
    $scope.closeMenu = function() {
      $scope.showingMenu = false;
    };
    rightPosition = function(elem) {
      return angular.element($window).width() - (angular.element(elem).offset().left + angular.element(elem).outerWidth());
    };
    return $scope.$on('updateTabsPosition', function(event, deletedTab) {
      var a, actualElement, actualLeft, acumWidth, cont, container, containerPosition, containerWidth, contents, contentsWidth, e, element, elementPosition, i, idContent, moveLeft, newleft, rtContainer, rtElement, temp, widthDeletedTab, _i, _j, _len, _len1, _ref;
      newleft = 0;
      temp = angular.element('#angular-content > .hbox-panel > .tabs-container');
      containerPosition = temp.offset();
      containerWidth = temp.width();
      container = angular.element('#angular-content > .hbox-panel > .tabs-container > ul');
      contents = angular.element('#angular-content > .hbox-panel > .tabs-container > ul.ui-tabs-nav > li');
      contentsWidth = 0;
      rtContainer = rightPosition(temp);
      actualLeft = parseInt(container.css("left"));
      if ($scope.activeIndex() < 0) {
        container.css("left", "0px");
        return;
      }
      idContent = "#" + $scope.tabs[$scope.activeIndex()].id + "_tab";
      widthDeletedTab = 0;
      if (deletedTab) {
        widthDeletedTab = angular.element("#" + deletedTab.id + "_tab").outerWidth();
      }
      element = angular.element(idContent);
      elementPosition = element.offset().left;
      rtElement = rightPosition(element);
      contents.each(function(index, element) {
        if (index === 0) {
          return;
        }
        return contentsWidth += angular.element(element).width() + 2;
      });
      if (containerWidth - 40 < contentsWidth) {
        $scope.menuTabsEnabled = true;
      } else {
        $scope.menuTabsEnabled = false;
        $scope.showingMenu = false;
      }
      if ($scope.activeIndex() === $scope.tabs.length - 1) {
        $scope.limitTabs = $scope.activeIndex() + 1;
      }
      if (!$scope.menuTabsEnabled) {
        return;
      }
      if (elementPosition < containerPosition.left) {
        console.log("tab", "izquierda");
        _ref = container.find('li');
        for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
          a = _ref[i];
          if (i === 0) {
            continue;
          }
          rtElement = (rightPosition(angular.element(a))) - (containerPosition.left - elementPosition);
          $scope.limitTabs = i - 1;
          if (rtContainer + 40 > rtElement) {
            break;
          }
        }
        newleft = containerPosition.left - elementPosition;
        actualElement = element;
        acumWidth = element.outerWidth() + 2 - widthDeletedTab;
        while (acumWidth + actualElement.next().outerWidth() + 2 < containerWidth - 40) {
          actualElement = actualElement.next();
          acumWidth += actualElement.outerWidth() + 2;
        }
        newleft += containerWidth - 40 - acumWidth;
      } else if (rtContainer + 40 > rtElement + 2) {
        console.log("tab", "derecha");
        $scope.limitTabs = $scope.activeIndex() + 1;
        newleft = 2 + rtElement + widthDeletedTab - rtContainer - 40;
      } else {
        console.log("tab", "enmedio");
        if (deletedTab && (contents[0] != null)) {
          actualElement = element;
          cont = 0;
          while (actualElement.next().length !== 0 && rightPosition(actualElement.next()) + 2 > rtContainer - 40) {
            actualElement = actualElement.next();
            cont++;
          }
          newleft = rightPosition(actualElement) - rtContainer - 40 - 2;
          if (newleft < 0) {
            newleft = 0;
          } else {
            $scope.limitTabs = $scope.activeIndex() + (cont + 1);
          }
        }
      }
      contentsWidth = deletedTab ? -widthDeletedTab - 2 : 0;
      for (i = _j = 0, _len1 = contents.length; _j < _len1; i = ++_j) {
        e = contents[i];
        if (i > $scope.limitTabs + 1) {
          break;
        }
        contentsWidth += angular.element(e).outerWidth() + 2;
      }
      moveLeft = actualLeft + Math.floor(newleft);
      if (moveLeft < (contentsWidth - (containerWidth - 40)) * -1) {
        console.log("tab", "demasiado a la izquierda");
        moveLeft = (contentsWidth - (containerWidth - 40)) * -1;
      }
      if (moveLeft > 0) {
        console.log("tab", "demasiado a la derecha");
        container.css("left", "0px");
      } else {
        container.css("left", moveLeft + "px");
      }
    });
  }
]);
