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
 *  @author Ximdex DevTeam <dev@ximdex.com>
 *  @version $Revision$
 */

require_once(XIMDEX_ROOT_PATH . '/inc/install/steps/generic/GenericInstallStep.class.php');
require_once(XIMDEX_ROOT_PATH . '/inc/install/managers/FastTraverseManager.class.php');
require_once(XIMDEX_ROOT_PATH . '/inc/install/managers/InstallModulesManager.class.php');

class XimdexModulesInstallStep extends GenericInstallStep {

	public function index(){		
		
		$imManager = new InstallModulesManager(InstallModulesManager::WEB_MODE);
		$imManager->buildModulesFile();
		$ftManager = new FastTraverseManager (FastTraverseManager::WEB_MODE);		
		$ftManager->buildFastTraverse();
		$modules = $this->installManager->getModulesByDefault();
		$this->addJs("InstallModulesController.js");
		$this->render();
	}

	public function getModulesLikeJson(){
		$modules = $this->installManager->getModulesByDefault(false);		
		$this->sendJSON($modules);
	}

	public function installModule(){
		
		$moduleName = $this->request->getParam("module");
		$imManager = new InstallModulesManager(InstallModulesManager::WEB_MODE);
		$installState = $imManager->installModule($moduleName);
		$values=array("result"=>$installState);
		$this->sendJSON($values);
				
	}
}

?>