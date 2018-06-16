<!DOCTYPE html>
<html <?php if ($fullHeight) { echo 'class="full-height"'; } ?>>
    <head>
        <title><?=$pageTitle?></title>
        <meta charset="utf-8">
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="https://cdn.rawgit.com/afeld/bootstrap-toc/v0.4.1/dist/bootstrap-toc.min.css" rel="stylesheet">
        
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:100,200,300,400,500,600,700,800,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Droid+Sans+Mono" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Merriweather" rel="stylesheet">
        
        <?php if ($pageCode == 'styleguide') {
			foreach ($fontUrls as $fontUrl)
			{
				echo '<link href="' . $fontUrl . '" rel="stylesheet">';
			}
			
			foreach ($fontFiles as $fontFile)
			{
				echo '<link href="/uploads/style-guide/' . $fontFile . '" rel="stylesheet">';
			}
		} ?>
       	
        <!-- build:css ../../../../assets/styles/styles.css -->
        <link href="../../../../temp/styles/styles.css" rel="stylesheet" type="text/css"/>
        <!-- endbuild -->
        
        <!-- build:js ../../../../assets/scripts/Vendor.js -->
		<script src="../../../../temp/scripts/Vendor.js"></script>
		<!-- endbuild -->
		
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://cdn.rawgit.com/afeld/bootstrap-toc/v0.4.1/dist/bootstrap-toc.min.js"></script>
		
        <!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
    </head>