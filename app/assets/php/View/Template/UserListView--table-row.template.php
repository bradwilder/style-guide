<tr class="<?=($userListItem->user->id == $currentUser->id ? 'type__label--emphasis' : '')?>" data-group-id="<?=$userListItem->user->groupID?>">
	<td class="users-table__id"><?=$userListItem->user->id?></td>
	<td class="users-table__email"><?=$userListItem->user->email?></td>
	<td class="users-table__phone"><?=$userListItem->user->phone?></td>
	<td class="users-table__display-name"><?=$userListItem->user->displayName?></td>
	<td class="users-table__group-name"><?=$userListItem->group->name?></td>
	<td class="users-table__sessions">
		<?php if ($userListItem->user->sessions > 0) { ?>
			<a data-id="<?=$userListItem->user->id?>" class="type__link" data-modal="#sessionsModal"><?=$userListItem->user->sessions?></a>
		<?php } else { echo 0; } ?>
	</td>
	<td class="users-table__requests">
		<?php if (count($userListItem->user->requests) > 0) { ?>
			<a data-id="<?=$userListItem->user->id?>" class="type__link" data-modal="#requestsModal"><?=count($userListItem->user->requests)?></a>
		<?php } else { echo 0; } ?>
	</td>
	<td class="type__<?=($userListItem->user->isDeleted ? 'danger' : (!$userListItem->user->isActive ? 'warning' : 'alert'))?>"><?=($userListItem->user->isDeleted ? 'Deleted' : (!$userListItem->user->isActive ? 'Inactive' . ($userListItem->user->resetNeeded ? ' (reset)' : '') : 'Active'))?></td>
	<td>
		<i data-id="<?=$userListItem->user->id?>" class="tables__edit-button fa fa-pencil" data-modal="#editUserModal"></i>
		<?php
			$noActiveRequest = true;
			foreach ($userListItem->user->requests as $request)
			{
				$requestExpiredate = strtotime($request);
				$currentDate = strtotime(date("Y-m-d H:i:s"));
				if ($currentDate <= $requestExpiredate)
				{
					$noActiveRequest = false;
				}
			}
			
			if (!$userListItem->user->isActive && !$userListItem->user->isDeleted && $noActiveRequest)
			{
				echo '<i data-id="' . $userListItem->user->id . '" class="tables__edit-button activate-request fa fa-envelope"></i>';
			}
		?>
		<?php if ($userListItem->user->id != 1) { ?>
			<?php if (!$userListItem->user->isDeleted) { ?>
				<i data-id="<?=$userListItem->user->id?>" class="tables__edit-button fa fa-trash" data-modal="#deleteUserModal"></i>
			<?php } else { ?>
				<i data-id="<?=$userListItem->user->id?>" class="tables__edit-button undelete-user fa fa-undo"></i>
			<?php } ?>
		<?php } ?>
	</td>
</tr>