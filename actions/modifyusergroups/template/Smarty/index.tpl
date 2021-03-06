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

<form ng-controller="XModifyUserGroupsCtrl"
      ng-init='nodeid={$id_node}; user_name="{$user_name}"; general_role={$general_role}; all_roles={$all_roles};
      filtered_groups={$filtered_groups}; user_groups_with_role={$user_groups_with_role}; init();'
      method="post" action="{$action_url}" class="form_group_user">

    <div class="action_header">
        <h2>{t}Manage groups{/t}</h2>
        <fieldset class="buttons-form">

        </fieldset>
    </div>

    <div class="action_content">
        <h3>{t}Available groups{/t}</h3>
        <div class="associate-group">
                <div ng-show="filtered_groups.length>0" class="row-item col2-3">
			 		<span class="col1-2 icon icon-group label-select">
			 			<select  class='select-clean block' ng-model="newGroup"
                                ng-options="group_info as group_info.Name for group_info in filtered_groups">

                        </select>
			 		</span>

					<span class="col1-2 icon icon-rol label-select">
						<select  class='select-clean block' ng-model="newRole"
                                ng-options="rol_info.IdRole as rol_info.Name for rol_info in all_roles">

                        </select>
					</span>
                    <div class="buttons-form row-item-actions actions-outside col1-3">
                        <button type="button" class="add-btn icon btn-unlabel-rounded"
                                ng-click="addGroup()"
                                >
                            <span>{t}Add group{/t}</span>
                        </button>
                    </div>
                </div>

                <p ng-hide="filtered_groups.length>0">{t}There are not{/t} <span ng-if="user_groups_with_role.length>0">{t}more{/t} </span>{t}available groups to be associated with the user{/t}</p>
        </div>
        <h3>#/user_name/# {t}belongs to the next groups{/t}:</h3>
        <div ng-if="user_groups_with_role.length>0" class="change-group">

                <div ng-repeat="user_group_info in user_groups_with_role" class="row-item icon">

                    <span class="col1-3">
						#/user_group_info.Name/#
					</span>
                    <span class="col1-3">
                        <select name='idRole' class='select-clean block'
                                ng-model="user_groups_with_role[$index].IdRole"
                                ng-change="user_groups_with_role[$index].dirty=true"
                                ng-options="rol_info.IdRole as rol_info.Name for rol_info in all_roles">

                        </select>
                    </span>

                    <div class="buttons-form row-item-actions col1-3">
                        <span ng-show="user_groups_with_role[$index].dirty">

                            <button type="button" class="recover-btn icon btn-unlabel-rounded"
                                    ng-click="update($index)"
                                    >
                                <span>{t}Update{/t}</span>
                            </button>
                        </span>
                        <span ng-if="user_group_info.IdGroup != '101'">
                            <button type="button" class="delete-btn icon btn-unlabel-rounded"
                                    ng-click="openDeleteModal($index)"
                                    >
                                <span>{t}Delete{/t}</span>
                            </button>
                        </span>
                    </div>
                </div>
        </div>
    </div>

    <p ng-if="!user_groups_with_role">{t}There are no groups associated with user yet{/t}</p>
    </div>
</form>
