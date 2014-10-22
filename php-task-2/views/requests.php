<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Полученные заявки</title>
</head>
<body>
<table cellpadding="0" cellspacing="3" border="0">
	<tr>
		<th>id</th>
		<th>Имя</th>
		<th>Фамилия</th>
		<th>Отчество</th>
		<th>Дата рождения</th>
		<th>Телефон</th>
		<th>Емэил</th>
		<th>Серия/номер паспорта</th>
		<th>Кем выдан</th>
		<th>Дата выдачи</th>
		<th>Код подразделения</th>
		<th>Место рождения</th>
		<th>Согласен</th>
		<th>Время заполнения</th>
	</tr>
<?php
if ($r->num_rows):
	while ($row = $r->fetch_assoc()):
?>
	<tr>
		<td><?=$row['id'] ?: '—';?></td>
		<td><?=$row['first_name'] ?: '—';?></td>
		<td><?=$row['last_name'] ?: '—';?></td>
		<td><?=$row['middle_name'] ?: '—';?></td>
		<td><?=$row['birth_d'] ?: '—';?></td>
		<td><?=$row['phone'] ?: '—';?></td>
		<td><?=$row['email'] ?: '—';?></td>
		<td><?=$row['passport_id'] ?: '—';?></td>
		<td><?=$row['issued_by'] ?: '—';?></td>
		<td><?=$row['issued_d'] ?: '—';?></td>
		<td><?=$row['issued_code'] ?: '—';?></td>
		<td><?=$row['birth_place'] ?: '—';?></td>
		<td><?=$row['confirmed'] ? 'Да' : 'Нет';?></td>
		<td><?=$row['fill_t'] ?: '0';?></td>
	</tr>
<?php
	endwhile;
else:
?>
<tr>
	<td align="center" colspan="14">Нет записей</td>
</tr>
<?php endif; ?>
</table>
</body>
</html>