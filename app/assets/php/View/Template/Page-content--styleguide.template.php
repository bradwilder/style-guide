<?php

include(__ASSETS_PATH . '/php/View/Template/Page-title.template.php');
echo MVCoutput(StyleguideSectionsModel, StyleguideSectionsController, StyleguideSectionsView, null, $currentUser, null);

?>