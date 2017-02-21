<?php
/**
 *  \details &copy; 2011  Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 *  Ximdex a Semantic Content Management System (CMS)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  See the Affero GNU General Public License for more details.
 *  You should have received a copy of the Affero GNU General Public License
 *  version 3 along with Ximdex (see LICENSE file).
 *
 *  If not, visit http://gnu.org/licenses/agpl-3.0.html.
 *
 * @author Ximdex DevTeam <dev@ximdex.com>
 * @version $Revision$
 */

namespace Ximdex\MVC;

use ModulesManager;
use Status;
use Ximdex\Models\ActionsStats;
use Ximdex\MVC\ActionAbstract;
use Ximdex\MVC\ActionFactory;
use Ximdex\MVC\IController;
use Ximdex\Runtime\App;
use \Ximdex\Utils\Session;


ModulesManager::file('/inc/Status.class.php', 'ximADM');
// Implement \Ximdex\Utils\Session::check() as Filter.

/**
 *
 * @brief Controller to execute and route actions
 *
 * Controller to execute and route actions
 *
 */
class ApplicationController extends IController
{
    private $timer = null;

    /**
     *
     */
    function compose()
    {
        $stats = array();

        // Select and enroute the action
        $actionController = ActionFactory::getAction($this->request);

        //Si no existe la accion, mostramos error
        if ($actionController == NULL) {
            $actionController = $this->_error_no_action();
        } else {
            $this->setUserState();
            $stats = $this->actionStatsStart();
            $actionController->execute($this->request);
        }

        // Inserts action stats
        $this->actionStatsEnd($stats);

        $this->hasError = $actionController->hasError();
        $this->msgError = $actionController->getMsgError();

    }

    /**
     * @return ActionAbstract
     */
    function _error_no_action()
    {
        $action = $this->request->input("action");
        $nodeid = $this->request->input("nodeid");


        $actionController = new ActionAbstract();
        $actionController->messages->add(_("Required action not found."), MSG_TYPE_ERROR);
        //error_log("action: $action | node: $nodeid"):
        $this->request->setParam('messages', $actionController->messages->messages);
        $actionController->render($this->request->getRequests());

        return $actionController;
    }

    /**
     * Error cuando no hay una action asociada
     */

    /**
     *
     */
    function setUserState()
    {
        if (ModulesManager::isEnabled('ximADM')) {
            $userID = (int)Session::get('userID');
            $action = $this->request->input("action");
            $method = $this->request->input("method");

            if ($userID && !is_null($action) && "index" == $method) {
                $user_status = new Status();
                $status = $user_status->get($userID);
                $hash = NULL;
                if (!empty($status)) {
                    $hash = $status->hash;
                    $action = ("moduleslist" == $action) ? "browser" : $action;
                }
                $user_status->assign($userID, $hash, $action);
            }
        }
    }

    /**
     * @return array
     */
    function actionStatsStart()
    {
        $actionStats = App::getValue('ActionsStats');
        $action = $this->request->input("action");
        $method = $this->request->input("method");
        $nodeId = (int)$this->request->input("nodeid");
        $userId = Session::get("userID");
        // Starts timer for use in action stats
        $stats = array();

        if ($actionStats == 1 && !is_null($action) && "index" == $method) {
            $this->timer = new \Ximdex\Utils\Timer();
            $this->timer->start();

            if (ModulesManager::isEnabled('ximDEMOS')) {
                $actionStats = new ActionsStats();
                $idStat = $actionStats->create(NULL, $nodeId, $userId, $action, 1);
            }
            $stats = array("action" => $action, "nodeid" => $nodeId, "idStat" => 0);
        }
        return $stats;
    }


    // Inserts action stats
    /**
     * @param $stats
     */
    function actionStatsEnd($stats)
    {
        $actionStats = App::getValue('ActionsStats');
        $action = $this->request->input("action");
        $method = $this->request->input("method");
        $nodeId = (int)$this->request->input("nodeid");
        $userId = Session::get("userID");

        if ($actionStats == 1 && !is_null($action) && "index" == $method && $this->timer) {
            $stats_time = $this->timer->mark('End action');

            if (ModulesManager::isEnabled('ximDEMOS')) {
                if ($stats["idStat"]) {
                    $actionStats = new ActionsStats($stats["idStat"]);
                    $actionStats->set("Duration", $this->timer->display_parcials(null, true));
                    $actionStats->update();
                } else {
                    $actionStats = new ActionsStats();
                    $actionStats->create(NULL, $nodeId, $userId, $action, $this->timer->display_parcials(null, true));
                }
            }
            // else {
            $this->send_stats($stats, $method, $stats_time);
            //}
        }
    }

    /**
     * @param $stats
     * @param $method
     * @param $duration
     */
    private function send_stats($stats, $method, $duration)
    {

        $ctx = stream_context_create(array(
                'http' => array(
                    'timeout' => 1
                )
            )
        );

        if (strcmp($stats["action"], "browser3") == 0)
            $event = "login";
        else
            $event = "action";

        $remote = App::getValue('StatsServer') . "/stats/stats_ximdex.php";
        $ximid = App::getValue('ximid');
        $userId = Session::get("userID");
        if (strcmp($stats["nodeid"], '') != 0) {
            $nodeid = (int)$stats["nodeid"];
        } else
            $nodeid = 0;

        $code = $stats["action"] . "_" . $method;

        @file_get_contents("$remote?eventid=$event&nodeId=$nodeid&userid=$userId&duration=$duration&ximid=$ximid&code=$code", 0, $ctx);

    }
}