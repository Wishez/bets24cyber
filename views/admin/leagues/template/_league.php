<?php 
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

?>

<style type="text/css">
tr{
    user-select: none;
}
#drag-hover{
    position: absolute;
    display: none;
}
#drag-hover{
    z-index: 5;
    box-shadow: 0px 0px 7px #96a3ab;
}
table{
    border-collapse: collapse; 
}
table tr{
    //transition: 0.3s border-top;
}
</style>

<!-- <div style="background-color: red; position: absolute; width: 200px; height: 200px; display: none; z-index: 100" id="x"></div> -->

<div id="drag-hover">
    <table class="league-index-table">
    </table>
</div>
<?php //$form = ActiveForm::begin(['method'=>'GET','options' => ['enctype' => 'multipart/form-data']]);?>
<form method="get">
    

<div class="col-md-3 pull-right" style="margin: 20px 0; padding: 0;">
    <input style="width: 205px;     float: left; margin-right: 10px;" type="text" value="<?=$search?>" name="search" class="form-control">
    <button class="btn btn-success" type="submit">Search</button>
</div>
</form>
<?php //ActiveForm::end();?>

<table class="league-index-table x-table">
        <tr>
            <th width="31"></th>
            <th width="31"></th>
            <th>Название</th>
            <th>Дата начала</th>
            <th>Игра</th>
            <th>Призовой фонд</th>
            <th>Действие</th>
        </tr>
    <?= ListView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider(
            [
                'query' => $model,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        ),
                'layout' => "{items}\n{pager}",
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model) {
            $game = $model['game'] == 0 ? 'dota2' : 'csgo';
            $html = '<tr class="drag-table" league_id="'.$model->id.'" level="1">
                <td colspan="3">'.$model->name.'</td>
                <td>'.$model->date_start.'</td>
                <td>'.($model->game == 0 ? 'dota2' : 'csgo').'</td>
                <td>'.$model['prizepool'].'$</td>
                <td>'.($model->close ? '<a class="glyphicon glyphicon-eye-open" href="enable?id='.$model->id.'"></a>' : '<a class="glyphicon glyphicon-eye-close" href="disable?id='.$model->id.'"></a>').'
                
                </td>
            </tr>';
            foreach ($model->quals as $qual) {
                $html .= '<tr class="quals-close-block drag-table qual" league_id="'.$qual->id.'" level="2">
                            <td class="empty-td"></td>
                            
                            <td colspan="2">'.$qual->name.'</td>
                            <td>'.$qual->date_start.'</td>
                            <td>'.($model->game == 0 ? 'dota2' : 'csgo').'</td>
                            <td>'.(empty($qual->prizepool) ? 0 : $qual->prizepool).'$</td>
                <td>'.($qual->close ? '<a class="glyphicon glyphicon-eye-open" href="enable?id='.$qual->id.'"></a>' : '<a class="glyphicon glyphicon-eye-close" href="disable?id='.$qual->id.'"></a>').'
                
                </td>
                        </tr>';
                foreach ($qual->quals as $qual1) {
                    $html .= '<tr class="qual-new drag-table qual" league_id="'.$qual1->id.'" level="3">
                            <td class="empty-td"></td>
                            <td class="empty-td"></td>
                           
                            <td>'.$qual1->name.'</td>
                            <td>'.$qual1->date_start.'</td>
                            <td>'.($model->game == 0 ? 'dota2' : 'csgo').'</td>
                            <td>'.(empty($qual1->prizepool) ? 0 : $qual1->prizepool).'$</td>
                <td>'.($qual1->close ? '<a class="glyphicon glyphicon-eye-open" href="enable?id='.$qual1->id.'"></a>' : '<a class="glyphicon glyphicon-eye-close" href="disable?id='.$qual1->id.'"></a>').'
                
                </td>
                        </tr>';
                }
            }
            return $html;
        },
    ]) ?>


</table>