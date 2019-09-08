<a href="index.php?campaign={campaignid}">Обратно в кампанию</a>

<h1>Создать лендинг</h1>

<form method="post" action="/index.php" enctype="multipart/form-data">
  <label>Заголовок</label>
  <input placeholder="до 50 символов" type="text" name="title">
  <label>Текст</label>
  <textarea placeholder="до 125 символов" name="text"></textarea>
  <label>Ссылка</label>
  <input type="text" name="link">
  <label>Маленькая картинка</label>
  <input type="file" name="thumbnail">
  <small>Рекомендуемый размер: 128×128px 
  <br />JPG, PNG, GIF до 200KB</small>
  <label>Большая картинка</label>
  <input type="file" name="bigimage">
  <small>Рекомендованный размер: 400*200px
  <br />JPG, PNG, GIF до 200KB</small>
  <label>Время рассылки</label>
  <input type="text" name="starttime" id="starttime" />
  <label>Какой это шаг?</label>
  <input type="number" name="step">
  <input type="hidden" name="campaignid" value="{campaignid}">
  <input type="hidden" name="action" value="newlanding">
  <input type="submit" value="Создать кампанию">
</form>



<script>
$('#starttime').timepicker({ 'step': 10, 'timeFormat': 'H:i' });
$('#endtime').timepicker({ 'step': 10,  'timeFormat': 'H:i' });
</script>