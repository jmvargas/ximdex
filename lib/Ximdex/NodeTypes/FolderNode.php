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

use baseIO;
use DB;
use depth;
use files;
use name;
use nodeTypeID;
use params;
use parentID;
use recurrence;
use target;
use Ximdex\Logger;
use Ximdex\Models\Node;
use Ximdex\Utils\FsUtils;


/**
 * @brief Handles directories.
 *
 *  Directories are located in data/nodes directory.
 */
class FolderNode extends Root
{

    /**
     *  Creates the folder in data/nodes directory.
     */

    function RenderizeNode()
    {

        $parentID = $this->parent->GetParent();
        $parent = new Node($parentID);

        if (!$parent->IsRenderized()) {
            $parent->RenderizeNode();
        }

        $folder = $this->GetChildrenPath();

        $folder = \App::getValue("AppRoot") . \App::getValue("NodeRoot") . $folder;

        if (file_exists($folder))
            FsUtils::deltree($folder);


        if (!mkdir($folder, 0777))
            $this->parent->SetError(7);

        chmod($folder, 0777);
    }

    /**
     *    Get the documents that must be publicated together with the folder
     * @param array params
     * @return array
     */

    public function getPublishabledDeps($params)
    {

        $idNode = $this->parent->get('IdNode');
        $node = new Node($idNode);

        $condition = (empty($params['childtype'])) ? NULL : " AND n.IdNodeType = '{$params['childtype']}'";
        return $node->TraverseTree(6, true, $condition);
    }

    /**
     *  Gets the XML documents that belong to the Section.
     * @return array|null
     */

    function getXmlDocuments()
    {

        $folderType = $this->nodeType->get('Name') == 'XimNewsSection' ? 'news' : 'documents';

        $xmlFolderId = $this->parent->GetChildByName($folderType);

        if (!($xmlFolderId > 0)) {
            Logger::error('xml folder not found');
            return NULL;
        }

        $xmlFolder = new Node($xmlFolderId);

        $descendants = $xmlFolder->TraverseTree();

        if (sizeof($descendants) > 0) {
            $xmlDocs = array();
            foreach ($descendants as $id) {
                $doc = new Node($id);

                if ($doc->nodeType->get('IsStructuredDocument') == 1) {
                    $xmlDocs[] = $id;
                }
            }

            return $xmlDocs;
        }

        return NULL;
    }


    function CreateNode($name = null, $parentID = null, $nodeTypeID = null)
    {
        //By default, when a schemes folder is created, we insert the default RNGs for metadata
        if ($nodeTypeID == 5053) {
            $this->_createDefaultRNGs();
        }
        $this->updatePath();
    }

    /**
     *  Does nothing.
     */

    function DeleteNode()
    {
    }


    function CloneNode($target)
    {
    }

    function UpdatePath()
    {
        $node = new Node($this->nodeID);
        $path = pathinfo($node->GetPath());
        if(isset($path['dirname'])) {
            $db = new DB();
            $db->execute(sprintf("update Nodes set Path = '%s' where IdNode = %s", $path['dirname'], $this->nodeID));
        }

        $children = $node->getChildren();
        $children = is_array($children) ? $children : array();
        foreach ($children as $childId) {
            $child = new Node($childId);
            $child->class->UpdatePath();
        }
    }

    function RenameNode($name = null)
    {
        $this->updatePath();
    }


    function GetDependencies()
    {
        $query = sprintf("SELECT DISTINCT IdNode FROM Nodes WHERE IdParent = %d", $this->nodeID);

        $this->dbObj->Query($query);
        $deps = array();

        while (!$this->dbObj->EOF) {
            $deps[] = $this->dbObj->GetValue("IdNode");
            $this->dbObj->Next();
        }

        return $deps;
    }

    /**
     *  Does nothing.
     * @param string target
     */

    function MoveNode($target = null)
    {

    }


    function ToXml($depth, & $files, $recurrence)
    {

        $query = sprintf("SELECT IdGroup, IdRole FROM RelGroupsNodes WHERE IdNode = %d", $this->nodeID);
        $this->dbObj->Query($query);

        $indexTabs = str_repeat("\t", $depth + 1);
        $xml = '';
        while (!$this->dbObj->EOF) {
            $xml .= sprintf("%s<RelGroupsNodes idNode=\"%d\" idGroup=\"%d\" idRol=\"%d\" />\n",
                $indexTabs,
                $this->nodeID,
                $this->dbObj->GetValue('IdGroup'),
                $this->dbObj->GetValue('IdRole'));
            $this->dbObj->Next();
        }

        $query = sprintf("SELECT IdLanguage, Name FROM NodeNameTranslations WHERE IdNode = %d", $this->nodeID);

        $this->dbObj->Query($query);
        while (!$this->dbObj->EOF) {
            $idLanguage = $this->dbObj->GetValue('IdLanguage');
            $name = $this->dbObj->GetValue('Name');
            $xml .= sprintf("%s<NodeNameTranslation IdLang=\"%d\">\n", $indexTabs, $idLanguage);
            $xml .= sprintf("%s\t<![CDATA[%s]]>\n", $indexTabs, utf8_encode($name));
            $xml .= sprintf("%s</NodeNameTranslation>\n", $indexTabs);
            $this->dbObj->Next();
        }

        return $xml;
    }


    function updateChooseTemplates()
    {

        $section = $this->parent;

        $dinamicTemplateList = $section->getProperty('dinamic_template_list');
        $dinamicTemplateList = $dinamicTemplateList[0];

        $dinamicTemplatePatterns = explode(', ', $dinamicTemplateList);
        foreach ($dinamicTemplatePatterns as $key => $dinamicTemplatePattern) {
            $matches = array();
            preg_match_all('/%%%([^%]+)%%%/', $dinamicTemplatePattern, $matches);
            $templates[] = array(
                'ORIGINAL_TEMPLATE' => $dinamicTemplatePattern,
                'TEMPLATE' => preg_replace('/(%%%[\w\d\_\-]+%%%)/', '([\w\d\_\-]+)', $dinamicTemplatePattern),
                'VALUES' => $matches[1]);
        }
        $dinamicCallerStructure = array();

        // For controlling the file creation
        $createFile = true;
        $childrens = $section->GetChildren(5077);

        foreach ($childrens as $idChildren) {
            $children = new Node($idChildren);

            if ($children->get('Name') == 'templates_choose.xsl') {
                $createFile = false;
                $chooseNodeId = $idChildren;
            }

            foreach ($templates as $template) {
                $matches = array();
                if (preg_match("/^" . $template['TEMPLATE'] . "\.xsl$/", $children->get('Name'), $matches) > 0) {
                    if (count($matches) - 1 == count($template['VALUES'])) {

                        $dinamicCallerStructure[str_replace('%%%', '_', $template['ORIGINAL_TEMPLATE'])][] = array(
                            'KEYS' => $template['VALUES'],
                            'VALUES' => $matches,
                            'FILE' => $children->get('Name'));

                    }
                    break;
                }
            }
        }


        $xslChoose = '<?xml version="1.0" encoding="utf-8"?>' . "\r\n"
            . '<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">' . "\r\n"
            . '	<xsl:template name="dinamic_caller">' . "\r\n"
            . '		<xsl:param name="template_name" />' . "\r\n"
            . "		<xsl:choose>\r\n";
        foreach ($dinamicCallerStructure as $key => $dinamicCaller) {
            $xslChoose .= sprintf("			<xsl:when test=\"\$template_name = '%s'\">\r\n"
                . "				<xsl:choose>\r\n", $key);

            foreach ($dinamicCaller as $dinamicCallerInfo) {
                $condition = array();
                foreach ($dinamicCallerInfo['KEYS'] as $key => $xpathKey) {
                    $xpathValue = $dinamicCallerInfo['VALUES'][$key + 1];
                    if ($xpathKey == 'schema') {
                        $xpathValue = $xpathValue . '.xml';
                    }
                    $condition[] = sprintf("/docxap/@%s = '%s'", $xpathKey, $xpathValue);
                }
                $condition = implode(' and ', $condition);
                $xslChoose .= sprintf('					<xsl:when test="%s">', $condition) . "\r\n";
                $xslChoose .= sprintf('						<xsl:call-template name="%s" />', str_replace('.xsl', '', $dinamicCallerInfo['FILE'])) . "\r\n";
                $xslChoose .= "					</xsl:when>\r\n";

            }
            $xslChoose .= sprintf("				</xsl:choose>\r\n			</xsl:when>\r\n");
        }
        $xslChoose .= "		</xsl:choose>\r\n	</xsl:template>\r\n</xsl:stylesheet>";

        // Updates template_choose content (or creates the node)
        if (!$createFile) {
            $chooseNode = new Node($chooseNodeId);
            $chooseNode->SetContent($xslChoose);
        } else {
            $file = XIMDEX_ROOT_PATH . '/data/tmp/' . FsUtils::getUniqueFile(XIMDEX_ROOT_PATH . '/data/tmp/');
            FsUtils::file_put_contents($file, $xslChoose);

            $data = array(
                'NODETYPENAME' => 'XslTemplate',
                'NAME' => 'templates_choose.xsl',
                'PARENTID' => $this->parent->get('IdNode'),
                'CHILDRENS' => array(
                    array('NODETYPENAME' => 'PATH', 'SRC' => $file)
                )
            );

            $baseIO = new baseIO();
            $baseIO->build($data);
        }
    }


    function getIndex()
    {

        $result = $this->parent->query(sprintf("SELECT ft.IdChild"
            . " FROM FastTraverse ft INNER JOIN Nodes n on n.IdNode = ft.IdChild"
            . " INNER JOIN NodeProperties np on np.IdNode = n.IdNode and Property = 'is_section_index'"
            . " Where ft.IdNode = %d order by ft.Depth ASC LIMIT 1", $this->parent->get('IdNode'), 'ft.IdChild'), MONO);

        if (!(count($result) > 0)) {
            return NULL;
        }

        return $result[0];
    }


    function CanDenyDeletion()
    {
    }

    private function _createDefaultRNGs()
    {
        $defaultRNGs_folder = "/inc/metadata/schemes/";
        $idparent = $this->parent->GetID();

        $this->buildDefaultRngFromPath($idparent, $defaultRNGs_folder);
    }

    protected function buildDefaultRngFromPath($idParent, $path)
    {
        if ($handle = opendir(XIMDEX_ROOT_PATH . $path)) {
            $entry = readdir($handle);
            while (false !== $entry) {
                $data = array();
                if (!($entry == '.' || $entry == '..')) {
                    $data = array(
                        'NODETYPENAME' => "RNGVISUALTEMPLATE",
                        'NAME' => $entry,
                        'PARENTID' => $idParent,
                        'CHILDRENS' => array(
                            array('NODETYPENAME' => 'PATH', 'SRC' => '')
                        )
                    );
                    $baseIO = new baseIO();
                    $rngId = $baseIO->build($data);

                    if (!$rngId > 0) {
                        error_log("Fail! Default metadata RNG " . $entry . " was not created!");
                    } else {
                        $newRngNode = new Node($rngId);
                        $rngContent = file_get_contents(XIMDEX_ROOT_PATH . $path . $entry, FILE_USE_INCLUDE_PATH);
                        $newRngNode->SetContent($rngContent);

                        //setting the type of schema
                        $sql = "INSERT INTO NodeProperties VALUES (NULL,$rngId,'SchemaType','metadata_schema')";
                        $dbObj = new DB();
                        if (!$dbObj->Execute($sql)) {
                            error_log("Fail! Default metadata RNG " . $entry . " couldn't be modified!");
                        }
                        //forbidding rng deletion
                        $sql = "INSERT INTO NoActionsInNode VALUES ($rngId,7318)";
                        $dbObj = new DB();
                        if (!$dbObj->Execute($sql)) {
                            error_log("Fail! Default metadata RNG " . $entry . " could be deleted!");
                        }
                    }
                }
                $entry = readdir($handle);
            }
            closedir($handle);
        }
    }
}
