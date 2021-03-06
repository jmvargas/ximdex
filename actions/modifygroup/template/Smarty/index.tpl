{**
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
 *}

<form method="post" name="mg_form" id="mg_form" action="{$action_url}" class='validate_ajax'>
    <div class="action_header">
        <h2>{t}Modify group{/t}: {$name}</h2>

        <fieldset class="buttons-form">
            {button label="Modify" class='validate btn main_action' } {* message="Woul you like to modify this group?"*}
        </fieldset>
    </div>

    <div class="action_content">
        <input type=hidden name='id_node' value="{$id_node_parent}">

        <div class="input icon group">
            <input type="text" name="name" id="name" value="{$name}" class="cajag validable not_empty">
        </div>
    </div>
</form>
