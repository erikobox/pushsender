<h1>Регистрация</h1>

<form method="post" action="/index.php">
  <span>Логин:</span>
  <input type="text" name="login">
  <span>Email:</span>
  <input type="email" name="email">
  <span>Пароль:</span>
  <input type="password" name="password">
  <input type="hidden" name="action" value="signup">
  <input type="submit" value="Зарегистрироваться">
</form>

<a href="/index.php">Войти</a>