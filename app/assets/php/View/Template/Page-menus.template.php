<header class="navbar navbar-inverse navbar-fixed-top menu">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<h1 class="header__brand" data-toc-skip>
				<img src="/assets/img/logo.png" class="header__brand-img" alt="Project logo">
				<span class="header__brand-name type__desc"><?=$brandName?></span>
			</h1>
		</div>
		
		<div class="collapse navbar-collapse type__title type__title--darker" id="navbar-collapse">
			<ul class="nav navbar-nav header__nav">
				<?php if (in_array('View', $currentUser->roles)) { ?>
					<li class="nav-item">
						<a class="nav-link" href="/">Style Guide</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/moodboard">Mood Board</a>
					</li>
				<?php } ?>

				<?php if (in_array('Admin', $currentUser->roles)) { ?>
					<li class="nav-item">
						<a class="nav-link" href="/admin">
							<i class="fa fa-gear"></i> Admin
						</a>
					</li>
				<?php } ?>
			</ul>
			
			<ul class="nav navbar-nav navbar-right header__nav">
				<?php if ($pageCode == 'moodboard' && in_array('Edit', $currentUser->roles)) { ?>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">
							<i class="fa fa-pencil"></i> Edit Page <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="#" data-modal="#uploadImageModal">Upload Image</a></li>
							<li><a href="#" data-modal="#manageImagesModal">Manage Images</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#" data-modal="#newSectionModal">New Section</a></li>
							<li><a href="#" data-modal="#arrangeSectionsModal">Arrange Sections</a></li>
						</ul>
					</li>
				<?php } ?>
				
				<?php if ($pageCode == 'styleguide' && in_array('Edit', $currentUser->roles)) { ?>
					<li>
						<a class="nav-link" href="/?action=config">
							<i class="fa fa-cog"></i> Configure Page
						</a>
					</li>
				<?php } ?>
				
				<?php if ($pageCode == 'styleguideConfig') { ?>
					<li>
						<a class="nav-link" href="/?">
							<i class="fa fa-eye"></i> View Page
						</a>
					</li>
				<?php } ?>
				
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#"><i class="fa fa-user"></i> <span data-user-id="<?=$currentUser->id?>" class="type__title--no-upper user-name"><?=($currentUser->displayName != '' ? $currentUser->displayName : $currentUser->email)?></span> <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a class="logout__submit" href="#">Logout</a></li>
						<li><a class="refresh-session" href="#">Refresh Session <span class="type__title--no-upper user-session-remaining"></span></a></li>
						<li><a class="" data-modal="#changePasswordModal" href="#">Change Password</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</header>