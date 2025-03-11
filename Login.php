<?php header('X-Robots-Tag: noindex'); ?>
<!DOCTYPE html>
<html lang="zh-tw">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex">
		<meta name="googlebot" content="noindex">
		<title>登入系統</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
		<link href="Assets/css/Login.css" rel="stylesheet">
		<style>

			@media (min-width: 768px) {
				.bd-placeholder-img-lg {
				font-size: 3.5rem;
				}
			}
		</style>
	</head>
	<body>
		<div class="container">
			<main class="form-signin">
				<form action="./Api/CallBack?code=Pin" method="post">
					<h1 class="h3 mb-3 fw-normal">登入系統</h1>

					<div class="form-floating mb-3">
						<input type="password" class="form-control" id="floatingInput" name="Pin" placeholder="Pin碼" autofocus>
						<label for="floatingInput">Pin碼</label>
					</div>

					<button class="w-100 btn btn-lg btn-primary mb-2" type="submit">Login</button>
				</form>
			</main>
		</div> <!-- /container -->
	</body>
</html>