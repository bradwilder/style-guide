<tr class="<?=($row->user->id == $currentUser->id ? 'type__label--emphasis' : '')?>" data-group-id="<?=$row->user->groupID?>">
	<td class="users-table__id"><?=$row->user->id?></td>
	<td class="users-table__email"><?=$row->user->email?></td>
	<td class="users-table__phone"><?=$row->user->phone?></td>
	<td class="users-table__display-name"><?=$row->user->displayName?></td>
	<td class="users-table__group-name"><?=$row->group->name?></td>
	<td class="users-table__sessions">
		<?php if ($row->user->sessions > 0) { ?>
			<a data-id="<?=$row->user->id?>" class="type__link" data-modal="#sessionsModal"><?=$row->user->sessions?></a>
		<?php } else { echo 0; } ?>
	</td>
	<td class="users-table__requests">
		<?php if (count($row->user->requests) > 0) { ?>
			<a data-id="<?=$row->user->id?>" class="type__link" data-modal="#requestsModal"><?=count($row->user->requests)?></a>
		<?php } else { echo 0; } ?>
	</td>
	<td class="type__<?=($row->user->isDeleted ? 'danger' : (!$row->user->isActive ? 'warning' : 'alert'))?>"><?=($row->user->isDeleted ? 'Deleted' : (!$row->user->isActive ? 'Inactive' . ($row->user->resetNeeded ? ' (reset)' : '') : 'Active'))?></td>
	<td>
		<i data-id="<?=$row->user->id?>" class="tables__edit-button fa fa-pencil" data-modal="#editUserModal"></i>
		<?php
			$noActiveRequest = true;
			foreach ($row->user->requests as $request)
			{
				$requestExpiredate = strtotime($request);
				$currentDate = strtotime(date("Y-m-d H:i:s"));
				if ($currentDate <= $requestExpiredate)
				{
					$noActiveRequest = false;
				}
			}
			
			if (!$row->user->isActive && !$row->user->isDeleted && $noActiveRequest)
			{
				echo '<i data-id="' . $row->user->id . '" class="tables__edit-button activate-request fa fa-envelope"></i>';
			}
		?>
		<?php if ($row->user->id != 1) { ?>
			<?php if (!$row->user->isDeleted) { ?>
				<i data-id="<?=$row->user->id?>" class="tables__edit-button fa fa-trash" data-modal="#deleteUserModal"></i>
			<?php } else { ?>
				<i data-id="<?=$row->user->id?>" class="tables__edit-button undelete-user fa fa-undo"></i>
			<?php } ?>
		<?php } ?>
	</td>
</tr>