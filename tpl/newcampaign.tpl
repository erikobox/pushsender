<h1>Новая кампания</h1>

<form method="post" action="/index.php">
  <label>Название</label>
  <input type="text" name="name">
  <label>Реферальная ссылка</label>
  <input type="text" name="afflink">
  <label>Тематика</label>
  <input type="text" name="interest">
  <input type="hidden" name="action" value="newcampaign">
  <input type="submit" value="Создать кампанию">
</form>