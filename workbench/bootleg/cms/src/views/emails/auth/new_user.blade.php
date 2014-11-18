<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Password Set</h2>

		<div>
			To set your password, complete this form: {{ action('RemindersController@getReset', array($token)) }}.
		</div>
	</body>
</html>