<h1><?php echo $project->name ?></h1>
<?php if (!empty($dateFrom) && !empty($dateTo)) : ?>
<div class="dates">Raport za dni: <?php echo date('d.m.Y', strtotime($dateFrom)) ?> - <?php echo date('d.m.Y', strtotime($dateTo)) ?></div>
<?php endif; ?>
<table>
    <?php $tmpTaskId = null; ?>
    <?php foreach ($logs as $log) : ?>
    <?php if ($tmpTaskId != $log->task->id) : ?>
    <tr>
        <td colspan="2">
            <strong>[<?php echo $project->code ?>-<?php echo $log->task->number ?>] <?php echo $log->task->name ?></strong>
        </td>
    </tr>
    <?php $tmpTaskId = $log->task->id ?>
    <?php endif; ?>

    <tr>
        <td><?php echo $log->comment ?></td>
        <td><?php echo $log->ftime ?></td>
    </tr>
    <?php endforeach; ?>

    <tr>
        <td style="text-align: right;"><strong>Razem:</strong></td>
        <td><strong><?php echo $timeSum ?></strong></td>
    </tr>
</table>
