<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Edit Record->Detail<?=$EventName?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="../Icons/favicon.ico" />
		<link rel="stylesheet" href="./RSVPs/RSVPNew.css">
		<link rel="stylesheet" href="../JavaScript/bootstrap-4.0.0/css/bootstrap.min.css">
		<script src="../JavaScript/jquery-3.2.1.min.js" defer></script>
		<script src="../JavaScript/bootstrap-4.0.0/js/bootstrap.bundle.min.js" defer></script>
		<script src="./RSVPs/RSVP_js.js" defer></script>
		<style>
			html {
				background-image: url('https://president-events.louisville.edu/oop/images/Backgrounds/GM-Night2.jpg');
			}
		</style>
	</head>

	<body>
	
		<!-- Main Div Start -->
		<div id="mainDiv" class="container">
			<div class="row justify-content-center">
				<div class="col-sm-10 col-md-8 col-lg-6">
					<!-- Header Div Start -->
					<div id="headerDiv" class="headerBodyFooter">
						<div id="headerImage">
							<img id="bannerImg" src="https://president-events.louisville.edu/oop/images/UL_logos/UL_Logowhiteoutline.png" alt="UL">
						</div>
						<div id="headerText" class="mt-4">
							<h5 id="instructions">Please RSVP by completing the form below</h5>
						</div>
						<!-- Header Div End -->
					</div>
					<!-- Body Div Start -->
					<div id="bodyDiv" class="headerBodyFooter">
						<form class="ghost-form" action="<?=$formAction?>" method="post">
							<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
							<div id="formDiv" class="infoDivs">
								<h5>
									<br>
									<?=$EventName?>
								    
									<br><br>
									<?=$EventLoc?><br><br>
									<?=$EventDate?>
									<br>
									<?=$EventTime?>
								</h5>
								<input class="ghost-input" name="name" value="<?=$FirstName?> <?=$LastName?>" type="text" readonly />
								<input class="ghost-input" name="email" value="<?=$Email?>" type="text" readonly />
								<br>
								<br>
								<div class="selectContainer">
									<div class="form-group row align-items-center justify-content-center">
										<label class="col-form-label">RSVP:</label>
										<section class="col-auto">
											<select name="rsvp" id="rsvp" class="custom-select" required>
												<option value="">Select</option>
												<option value="Decline">Unable to attend</option>
												<option value="1">1</option>
												<?php 
													if($Max_RSVP > "1")
													{
														//echo('<option value="2">2</option>');
														for($i = 2; $i <= $Max_RSVP; $i++)
														{
															echo('<option value="' . $i . '">' . $i . '</option>');
														}
													} 
												?>
											</select>
										</section>
									</div>
								</div>
								<br>
									<?php 
										if($Max_RSVP > "1")
										{
											$str = "<input class=\"ghost-input ghost-input-underlined\" name=\"Guest\" placeholder=\"Your guest's name\" value=\"\" type=\"text\" />";
											echo $str;
											echo "<br><br style=\"clear:both\">";
											
										} 
									?>
							</div>
							<br style="clear:both">
							<input id="submit" name="submit" type="submit" value="Submit" class="ghost-button">
						</form>
						<!-- Body Div End -->
					</div>
					<!-- Footer Div Start -->
					<div id="divFooter" class="headerBodyFooter">
						If you have any questions, please contact:
						<br> Joe Elliott
						<br> joe.elliott@louisville.edu
						<br> 502.852.5856
						<!-- Footer Div End -->
					</div>
				</div>
			</div>
			<!-- Main Div End -->
		</div>
	</body>
</html>