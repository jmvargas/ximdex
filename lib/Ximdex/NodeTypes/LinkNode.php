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

namespace Ximdex\NodeTypes;

use commit;
use depth;
use description;
use files;
use Ximdex\Logger;
use Ximdex\Models\Iterators\IteratorLinkDescriptions;
use Ximdex\Models\Link;
use name;
use Ximdex\Models\NodeDependencies;
use nodeTypeID;
use parentID;
use recurrence;
use Ximdex\Models\RelLinkDescriptions;
use stateID;
use unknown;
use url;
use Ximdex\NodeTypes\Root;



/**
 * @brief Handles links to external pages or web sites.
 */
class LinkNode extends Root
{

	var $link = NULL;

	/**
	 *  Constructor.
	 * @param object parent
	 */

	public function __construct($parent)
	{

		parent::__construct($parent);
		$this->link = new Link($this->nodeID);
	}

	/**
	 *  Adds a row to Versions table and creates the file.
	 * @param string name
	 * @param int parentID
	 * @param int nodeTypeID
	 * @param int stateID
	 * @param string url
	 * @param string description
	 * @return unknown
	 */

	function CreateNode($name = null, $parentID = null, $nodeTypeID = null, $stateID = null, $url = null, $description = null)
	{

		$link = new Link();
		$link->set('IdLink', $this->nodeID);
		$link->set('Url', $url);

		$result = $this->parent->SetDescription($description);

		$insertedId = $link->add();
		if (!$insertedId || !$result) {
			$this->messages->add(_('No se ha podido insertar el enlace'), MSG_TYPE_ERROR);
			$this->messages->mergeMessages($link->messages);
		}

		$this->link = new Link($link->get('IdLink'));

		$relDescription = !empty($description) ? $description : $this->link->get('Name');
		$rel = RelLinkDescriptions::create($this->nodeID, $relDescription);
		if ($rel->getIdRel() < 0) {
			Logger::warning(sprintf('No se ha podido crear la descripcion para el enlace %s en su tabla relacionada.', $link->get('IdLink')));
		}

		$ret = $this->link->get('IdLink');
		$this->UpdatePath();

		return $ret;
	}

	/**
	 *  Deletes the information of link in the database.
	 * @return unknown
	 */

	function DeleteNode()
	{

		if (!($this->link->get('IdLink') > 0)) {
			Logger::error("Se ha solicitado eliminar el nodo {$this->nodeID} que actualmente no existe");
		}

		$result = $this->link->delete();

		if (!$result) {
			$this->parent->messages->add(_('No se ha podido eliminar el enlace'), MSG_TYPE_ERROR);
			reset($this->link->messages->messages);

			while (list(, $message) = each($this->link->messages->messages)) {
				$this->parent->messages[] = $message;
			}
		} else {

			$it = new IteratorLinkDescriptions('IdLink = %s', array($this->link->get('IdLink')));
			while ($rel = $it->next()) {
				if (!$rel->delete()) {
					Logger::warning(sprintf('No se ha podido eliminar la descripcion con id %s para el enlace %s.', $rel->getIdRel(), $this->link->get('IdLink')));
				}
			}

		}

		return $result;
	}

	/**
	 *  Gets the url of the link.
	 */

	function GetUrl()
	{

		return $this->link->get('Url');
	}

	/**
	 *  Sets the url of the link.
	 * @param string url
	 * @param bool commit
	 * @return bool
	 */

	function SetUrl($url, $commit = true)
	{

		$this->link->set('Url', $url);

		if ($commit) {
			$result = $this->link->update();

			if (!$result) {
				$this->parent->messages->add(_('No se ha podido eliminar el enlace'), MSG_TYPE_ERROR);
				reset($this->link->messages->messages);
				while (list(, $message) = each($this->link->messages->messages)) {
					$this->parent->messages[] = $message;
				}
			}

			return $result;
		}

		return true;
	}

	/**
	 *  Gets the dependencies of the link.
	 * @return array
	 */

	function GetDependencies()
	{

		$nodeDependencies = new NodeDependencies();
		return $nodeDependencies->getByTarget($this->nodeID);
	}

	/**
	 *  Builds a XML wich contains the properties of the language.
	 * @param int depth
	 * @param array files
	 * @param bool recurrence
	 * @return string
	 */

	function ToXml($depth, & $files, $recurrence)
	{
		$indexTabs = str_repeat("\t", $depth + 1);
		return sprintf("%s<LinkInfo Url=\"%s\">\n"
			. "%s\t<![CDATA[%s]]>\n"
			. "%s</LinkInfo>\n",
			$indexTabs, urlencode($this->link->get('Url')),
			$indexTabs, $this->parent->GetDescription(), $indexTabs);
	}
}