<p><strong><?= Yii::t('app', 'Winners of the vote') ?></strong></p>
<table cellpadding="0" cellspacing="0" width="100%" style="border-top: 1px solid #121212;">
    <?php
    foreach ($categories as $category):

        //Get relavent vote details for the category
        $vote = new \app\models\Vote();
        $votes = $vote->find()
            ->joinWith(['voter'])
            ->select(['*, COUNT(*) as totalVotes'])
            ->where(['categoryId' => $category->id])
            ->andWhere(['Voter.voted' => \app\models\Voter::VOTED_YES])
            ->groupBy(['nomineeId'])
            ->orderBy('totalVotes DESC')
            ->one();

        $imageWebPath = Yii::$app->view->theme->baseUrl.'/images/unknown-pic.png';
        $nomineeName = Yii::t('app', 'Not voted');
        $totalVotes = 0;

        if (!empty($votes)) {

            $imageWebPath = $votes->nominee->imageWebPath;
            $nomineeName = $votes->nominee->name;
            $totalVotes = $votes->totalVotes;
        }
        ?>
        <tr>
            <td width="170" style="border-bottom: 1px solid #999999;padding: 10px;">
                <img src="<?= $imageWebPath ?>" width="150" />
            </td>
            <td style="border-bottom: 1px solid #999999;padding: 10px;">
                <h4 style="color: #121212;">
                    <?= $category->name ?>
                </h4>
                <h3 style="color: #d4b469; font-family: Arial;">
                    <?= $nomineeName ?>
                </h3>
                <h4 style="color: #121212;">
                    <?php 
                    if($totalVotes == 0) {
                        echo Yii::t('app', 'Not Voted');
                    } else if($totalVotes == 1){
                        echo $totalVotes.' '.Yii::t('app', 'Vote'); 
                    } else {
                        echo $totalVotes.' '.Yii::t('app', 'Votes');
                    }
                    ?>
                </h4>
            </td>
        </tr>
        <?php
    endforeach;
    ?>
</table>