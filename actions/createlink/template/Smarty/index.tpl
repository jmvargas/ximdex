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

<form method="post" name="cln_form" id="cln_form" action="{$action_url}">
    <input type="hidden" name="id_node" value="{$id_node}" />
    <div class="action_header">
        <h2>{t}Create link{/t}: {$name}</h2>
    </div>

    <div class="action_content">
        <div class="row">
            <div class="small-8 columns">
                <div class="input">
                    <label for="name" class="label_title">{t}Name{/t}:</label>
                    <input type="text" name="name" id="name" class="cajaxg validable not_empty js_val_unique_name js_val_alphanumeric" data-idnode="{$id_node}" placeholder="{t}Link name{/t}" />
                </div>
            </div>

            <div class="small-4 columns">
                <div class="input-select icon">
                    <label for="link_type" class="label_title">{t}Type{/t}:</label>
                    <select name="link_type" id="link_type" class="cajaxg document-type">
                        <option value="url" selected>URL (http://)</option>
                        <option value="email">E-mail (mailto:)</option>
                    </select>
                </div>
            </div>

            <div class="small-12 columns">
                <div class="input">
                    <label for="url" class="label_title">{t}URL{/t}:</label>
                    <input type="text" name="url" id="url" class="cajaxg validable not_empty js_val_unique_url" data-idnode="{$id_node}" value="http://">
                </div>
            </div>

            <div class="small-12 columns">
                <div class="input">
                    <label for="description" class="label_title"><span>{t}Description{/t}:</span></label>
                    <input type="text" name="description" id="description" class="cajaxg">
                </div>
            </div>
        </div>

        <!-- <div class="input-select icon icon-positioned link">
            <select name="link_type" id="link_type" class="cajaxg document-type">
                <option value="url" selected>URL (http://)</option>
                <option value="email">E-mail (mailto:)</option>
            </select>
        </div> -->
    </div>
    <fieldset class="buttons-form positioned_btn">
        {button label="Create" class='validate btn main_action'} {*message="Would you like to create a new link?"*}
    </fieldset>
</form>
