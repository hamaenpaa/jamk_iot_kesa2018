<?php include 'header.php'; ?>
	</head>
	<title>IoT Project</title>
	<body>
		<div class="page-wrap">
			<header>
				<div class="banner-wrap">
					<h1>Tervetuloa käyttäjä!</h1>
					<?php include 'C:/xampp/htdocs/jamk_iot_kesa2018/html/inc/navigation.php'; ?>
				</div>
			</header>
			<div class="content-wrap">
				<div>
					<div class="hidden"><?php include 'C:/xampp/htdocs/jamk_iot_kesa2018/html/inc/list_courses.php'; ?></div>
					<h2>Kurssien haku</h2>
					<div class="form-wrap">
						<form method="post" action="inc/sub/course/save_course.php">
						<input type="hidden" name="id" value="<?php echo $id; ?>" />
						<span class="form-row"><label>Kurssin tunnus:</label>
						<input type="text" name="course_id" value="<?php echo $course_id; ?>" /></span>
						<span class="form-row"><label>Kurssin nimi:</label>
						<input type="text" name="course_name" value="<?php echo $course_name; ?>" /></span>
						<button type="submit" class="button">Hae</button>
					</div>
					<h2>Lisää kurssi</h2>
					<div class="form-wrap">
						<form method="post" action="inc/sub/course/save_course.php">
						<input type="hidden" name="id" value="<?php echo $id; ?>" />
						<span class="form-row"><label>Kurssin tunnus:</label>
						<input type="text" name="course_id" value="<?php echo $course_id; ?>" /></span>
						<span class="form-row"><label>Kurssin nimi:</label>
						<input type="text" name="course_name" value="<?php echo $course_name; ?>" /></span>
						<span class="form-row"><label>Kurssin kuvaus:</label></span>
						<span class="form-row"><textarea name="course_description" rows="7" cols="50"><?php echo $course_description; ?></textarea></span>
						<button type="submit" class="button">Tallenna</button>
					</div>
				</div>
			</div>
<?php include 'footer.php'; ?>