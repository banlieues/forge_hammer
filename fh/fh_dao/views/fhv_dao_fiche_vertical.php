<table class="table table-striped table-bordered table-hover">
    <?php for($i=0;$i<count($labels);$i++): ?>
    <tr>
	<th style="text-align:right; width:150px"><?php echo $labels[$i];?></th>
	<td><?php echo $values[$i]; ?></td>
    </tr>
    <?php endfor; ?>
</table>

