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

<form method="post" action="{$action_url}" id="link_form">
    <div class="action_header">
		<h2>{t}Modify external link{/t}</h2>
		<fieldset class="buttons-form">
		    {button label="Modify" class='validate  btn main_action' }{*message="Would you like to modify this link?"*}
	    </fieldset>
	</div>

	<div class="action_content">
        <p class="input-select icon icon-positioned link">
            <input type="text" value="{$name}" name="Name" id="name" class="cajaxg validable not_empty js_val_unique_name js_val_alphanumeric" data-idnode="{$id_node}" placeholder="{t}Link name{/t}"/>
            <select name="link_type" id="link_type" class="cajaxg document-type validable not_empty">
                <option value="url">URL (http://)</option>
                <option value="email">E-mail (mailto:)</option>
            </select>
        </p>
        <div class="input">
            <label for="url" class="label_title">{t}URL{/t}</label>
            <input type="text" name="Url" value="{$url}" id="url" class="cajaxg validable not_empty js_val_unique_url" data-idnode="{$id_node}" />
        </div>
        <div class="input">
            <label for="description" class="label_title"><span>{t}Description{/t}</span></label>
            <input type="text" name="Description" value="{$description}" id="description" class="cajaxg" />
        </div>
	</div>
</form>
