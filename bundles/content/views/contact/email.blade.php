<h1>Message From Boojers.com</h1>
<table border="0" cellpadding="10">
	<tbody>
		<? if (!empty($form_data)): ?>
			<? foreach ($form_data as $key => $value): ?>
				<tr>
					<td><?=ucwords(str_replace('_', ' ', $key));?></td>
					<td><?=$value;?></td>
				</tr>
			<? endforeach; ?>
		<? endif; ?>
		<tr>
			<td>Received</td>
			<td><?=date('Y-m-d', time());?></td>
		</tr>
	</tbody>
</table>