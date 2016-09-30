<table class="table table-striped table-bordered table-hover">
    <thead>
	<tr>
	    <?php for($i=1;$i<count($labels);$i++): ?>
	    <th>
		<?php echo $labels[$i]; ?>
	    </th>
	    <?php endfor;?>
	</tr>
    </thead>
    <tbody>
	<?php foreach($values as $value): ?>
	<tr>
	    <?php $init=TRUE; ?>
	    <?php foreach($value as $v): ?>
		<?php if(!$init): ?>
		    <td>
			<?php echo $v; ?>
		    </td>
		 <?php else: ?>
		    <?php $id=$v; ?>
		<?php endif;?>
		<?php $init=FALSE; ?>
	    <?php endforeach; ?>
	
	    <td>
		<a href="<?php echo base_url();?>app/fiche_personne/<?php echo $id;?>">Voir</a>
	    </td>
	</tr>
	<?php endforeach; ?>
    </tbody>
</table>

