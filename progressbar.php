<!DOCTYPE html>
<head>
	<title>Animated Bar</title>
	<link href="css22/style.css" rel="stylesheet">
	<script src="http://use.edgefonts.net/source-sans-pro.js"></script>
	
</head>
<body>
<?php
$total = rand(1,500);
$current = rand(1,$total);
$percent = round(($current/$total) * 100,1);
echo "$current is $percent% of $total.
<p />";
?>

<style type="text/CSS">
.outter{
	height:25px;
	width:100%;
	border:solid 1px #000;
}
.inner{
	height:25px;
	width:<?php echo $percent ?>%;
	border-right:solid 1px #000;
	background-color: CornflowerBlue;
	box-shadow:inset 0px 0px 6px 2px rgba(255,255,255,.3);
}
.meter { 
	height: 30px;  /* Can be anything */
	position: relative;
	background: #555;
	border-radius: 5px;
	padding: 1px;
	box-shadow: inset 0 -1px 1px rgba(255,255,255,0.3);
}
.meter > span {
  display: block;
  height: 100%;
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
  background-color: rgb(43,194,83);
  background-image: linear-gradient(
    center bottom,
    rgb(43,194,83) 37%,
    rgb(84,240,84) 69%
  );
  box-shadow: 
    inset 0 2px 9px  rgba(255,255,255,0.3),
    inset 0 -2px 6px rgba(0,0,0,0.4);
  position: relative;
  overflow: hidden;
}

/* If you want the label inside the progress bar */
#label {
    text-align: center; /* If you want to center it */
    line-height: 25px; /* Set the line-height to the same as the height of the progress bar container, to center it vertically */
    color: black;
}

/* Defining the animation */

@-webkit-keyframes progress
{
	to {background-position: 30px 0;}
}

@-moz-keyframes progress
{
  to {background-position: 30px 0;}
}

@keyframes progress
{
  to {background-position: 30px 0;}
}

/* Set the base of our loader */

.barBg {
	background:#EEE;
	width:100%;
	height:25px;
	border:1px solid #EEE;
	border-radius: 1px;
	-moz-border-radius: 1px;
	-webkit-border-radius: 1px;
	margin-bottom:30px;
}


.bar {
	background: #7aff32;
	width: <?php echo $percent ?>%;
	height:30px;
	height: 25px;
	border-radius: 1px;
	-moz-border-radius: 1px;
	-webkit-border-radius: 1px;
}

/* Set the linear gradient tile for the animation and the playback */

.barFill {
	width: 100%;
	height: 25px;
	border-radius: 20px;
	-webkit-animation: progress 1s linear infinite;
	-moz-animation: progress 1s linear infinite;
	animation: progress 1s linear infinite;
	background-repeat: repeat-x;
	background-size: 30px 30px;
	background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
	background-image: linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}

/* Here's some predefined widths to control the fill. Add these classes to the "bar" div */

.ten {
	width: 10%; /* Sets the progress to 10% */
}	

.twenty {
	width: 20%; /* Sets the progress to 20% */
}	

.thirty {
	width: 30%; /* Sets the progress to 30% */
}	

.forty {
	width: 40%; /* Sets the progress to 40% */
}	

.fifty {
	width: 50%; /* Sets the progress to 50% */
}

.sixty {
	width: 60%; /* Sets the progress to 60% */
}	

.seventy {
	width: 70%; /* Sets the progress to 70% */
}	

.eighty {
	width: 80%; /* Sets the progress to 80% */
}	

.ninety {
	width: 90%; /* Sets the progress to 90% */
}	

.hundred {
	width: 100%; /* Sets the progress to 100% */
}	

/* Some colour classes to get you started. Add the colour class to the "bar" div */

.aquaGradient{
	background: -moz-linear-gradient(top,  #7aff32 0%, #54a6e5 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#7aff32), color-stop(100%,#54a6e5));
	background: -webkit-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
	background: -o-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
	background: -ms-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
	background: linear-gradient(to bottom,  #7aff32 0%,#54a6e5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7aff32', endColorstr='#54a6e5',GradientType=0 );
}

.roseGradient {
	background: #ff3232;
	background: -moz-linear-gradient(top,  #ff3232 0%, #ed89ff 47%, #ff8989 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ff3232), color-stop(47%,#ed89ff), color-stop(100%,#ff8989));
	background: -webkit-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
	background: -o-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
	background: -ms-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
	background: linear-gradient(to bottom,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff3232', endColorstr='#ff8989',GradientType=0 );
}

.madras { /* Credit to Divya Manian via http://lea.verou.me/css3patterns/#madras */
	background-color: hsl(34, 53%, 82%);
	background-image: -webkit-repeating-linear-gradient(45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px,                  
	  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
	  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
	  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
	  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
	  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 120px, hsla(197, 62%, 11%, 0.5) 120px, hsla(197, 62%, 11%, 0.5) 140px       
	  ),
	-webkit-repeating-linear-gradient(-45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px, 
	  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
	  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
	  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
	  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
	  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 140px, hsla(197, 62%, 11%, 0.5) 140px, hsla(197, 62%, 11%, 0.5) 160px       
	);; background-color: hsl(34, 53%, 82%);
	background-image: -webkit-repeating-linear-gradient(45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px,                  
	  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
	  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
	  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
	  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
	  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 120px, hsla(197, 62%, 11%, 0.5) 120px, hsla(197, 62%, 11%, 0.5) 140px       
	  ),
	-webkit-repeating-linear-gradient(-45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px, 
	  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
	  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
	  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
	  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
	  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 140px, hsla(197, 62%, 11%, 0.5) 140px, hsla(197, 62%, 11%, 0.5) 160px       
	);
}

.cornflowerblue {
	background-color: CornflowerBlue;
	box-shadow:inset 0px 0px 6px 2px rgba(255,255,255,.3);
	width: <?php echo $percent ?>%;
}

.carrot {
	background: #f2a130;
	background: -moz-linear-gradient(-45deg,  #f2a130 0%, #e5bd6e 100%);
	background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#f2a130), color-stop(100%,#e5bd6e));
	background: -webkit-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
	background: -o-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
	background: -ms-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
	background: linear-gradient(135deg,  #f2a130 0%,#e5bd6e 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2a130', endColorstr='#e5bd6e',GradientType=1 );

}
</style>

<div class="outter">
<div id="label" class="inner"><?php echo $percent ?>% Complete</div>
</div>
<br />

100% Fill, Aqua Gradient
<div class="barBg">
	<div class="bar hundred cornflowerblue">
		<div id="label" class="barFill"><?php echo $percent ?>% Complete</div>
	</div>
</div>

<br />
<div class="meter">
	<span style="width: <?php echo $percent ?>%"><?php echo $percent ?>%</span>
</div>

</body>
</html>