<?php

namespace app\controllers;

use app\components\Mail;
use app\models\Category;
use app\models\Vote;
use Faker\Provider\DateTime;
use Yii;
use app\models\Voter;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * PropertyController implements the CRUD actions for Property model.
 */
class DashboardController extends BaseController
{
    public $layout = 'main-admin'; // Main layout for super admin panel

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * Dashboard.
     * @return mixed
     */
    public function actionAdminDashboard()
    {
        Yii::$app->appLog->writeLog('Admin dashboard view');

        $voteStatsVotedNonVotedCounts = $this->getVoteStatsVotedNonVotedCounts();
        $voteStatsVotingCountPerDay = $this->getVoteStatsVotingCountPerDay();
        $voteStatsDesktopAndMobile = $this->getVoteStatsDesktopAndMobile();

        return $this->render('admin-dashboard', [
            'doughnutChartParams1' => [
                'voters' => $voteStatsVotedNonVotedCounts['voters'],
                'votedVoters' => $voteStatsVotedNonVotedCounts['votedVoters'],
                'notVotedVoters' => $voteStatsVotedNonVotedCounts['notVotedVoters'],
                'votedPercentage' => $voteStatsVotedNonVotedCounts['votedPercentage'],
                'notVotedPercentage' => $voteStatsVotedNonVotedCounts['notVotedPercentage'],
            ],
            'doughnutChartParams2' => [
                'devideDesktopCount' => $voteStatsDesktopAndMobile['devideDesktopCount'],
                'devideMobileCount' => $voteStatsDesktopAndMobile['devideMobileCount'],
            ],
            'barChartParams' => [
                'days' => implode('","', $voteStatsVotingCountPerDay['days']),
                'votes' => implode(',', $voteStatsVotingCountPerDay['votes'])
            ],
        ]);
    }

    /**
     * @return string
     */
    public function actionBailiffDashboard()
    {
        Yii::$app->appLog->writeLog('Bailiff dashboard view');

        $this->layout = 'main-bailiff';

        $voteStatsVotedNonVotedCounts = $this->getVoteStatsVotedNonVotedCounts();
        $voteStatsRemainingDays = $this->getVoteStatsRemainingDays();
        $voteStatsVotingCountPerDay = $this->getVoteStatsVotingCountPerDay();

        $category = new Category();
        $categories = $category->find()->all();

        return $this->render('bailiff-dashboard', [
            'doughnutChartParams1' => [
                'voters' => $voteStatsVotedNonVotedCounts['voters'],
                'votedVoters' => $voteStatsVotedNonVotedCounts['votedVoters'],
                'notVotedVoters' => $voteStatsVotedNonVotedCounts['notVotedVoters'],
                'votedPercentage' => $voteStatsVotedNonVotedCounts['votedPercentage'],
                'notVotedPercentage' => $voteStatsVotedNonVotedCounts['notVotedPercentage'],
            ],
            'doughnutChartParams2' => [
                'votingStartAt' => $voteStatsRemainingDays['votingStartAt'],
                'votingEndAt' => $voteStatsRemainingDays['votingEndAt'],
                'daysRemaining' => $voteStatsRemainingDays['daysRemaining'],
                'daysCompleted' => $voteStatsRemainingDays['daysCompleted'],
            ],
            'barChartParams' => [
                'days' => implode('","', $voteStatsVotingCountPerDay['days']),
                'votes' => implode(',', $voteStatsVotingCountPerDay['votes'])
            ],
            'categories' => $categories
        ]);
    }

    /**
     * Get the total voters, voted and non voted count
     * @return array
     */
    public function getVoteStatsVotedNonVotedCounts(){
        $voter = new Voter();

        $totalVoters = $voter->find()->where(['status' => Voter::STATUS_ACTIVE])->count();
        $totalVotedVoters = $voter->find()->where(['status' => Voter::STATUS_ACTIVE, 'voted' => Voter::VOTED_YES])->count();
        $notVotedVoters = $totalVoters - $totalVotedVoters;
        $totalVotedPercentage = ($totalVoters > 0) ? round(($totalVotedVoters/$totalVoters)*100): 0;
        $totalNotVotedPercentage = 100 - $totalVotedPercentage;

        return [
            'voters' => $totalVoters,
            'votedVoters' => $totalVotedVoters,
            'notVotedVoters' => $notVotedVoters,
            'votedPercentage' => $totalVotedPercentage,
            'notVotedPercentage' => $totalNotVotedPercentage,
        ];
    }

    /**
     * Get the days completed and remaining days for voting
     * @return array
     */
    public function getVoteStatsRemainingDays(){

        /*
        $curDate = new DateTime(date('Y-m-d'));
        $endDate = new DateTime(Yii::$app->params['votingEndAt']);
        $daysToEndVote = $endDate->diff($curDate)->format('%a');
        */

        $daysToEndVoteFromNow = $this->getDateDiff(date('Y-m-d'), Yii::$app->params['votingEndAt']);
        $daysToEndVoteFromStart = $this->getDateDiff(Yii::$app->params['votingStartAt'], Yii::$app->params['votingEndAt']);
        $daysCompleted = $daysToEndVoteFromStart - $daysToEndVoteFromNow;

        return [
            'votingStartAt' => Yii::$app->params['votingStartAt'],
            'votingEndAt' => Yii::$app->params['votingEndAt'],
            'daysRemaining' => $daysToEndVoteFromNow,
            'daysCompleted' => $daysCompleted,
        ];
    }

    /**
     * Get the voting counts for last 7 days
     * @return array
     */
    public function getVoteStatsVotingCountPerDay(){
        $voter = new Voter();
        Yii::$app->db->pdo->exec('SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY", ""));');
        $votedDates = $voter
            ->find()
            ->select(['*, COUNT(*) as totalVotes'])
            ->where(['voted' => Voter::VOTED_YES])
            ->groupBy(['CAST(votedAt AS DATE)'])
            ->orderBy(['UNIX_TIMESTAMP(votedAt)' => SORT_DESC ])
            ->limit(7)
			->all();

        $daysArr = [];
        $votesArr = [];
        foreach ($votedDates as $votedDate){
            $daysArr[] = date('d/m/Y', strtotime($votedDate->votedAt));
            $votesArr[] = $votedDate->totalVotes;
        }
        
        krsort($daysArr);//To get the days in ASC order again
        krsort($votesArr);//To get the days in ASC order again

        return [
            'days' => $daysArr,
            'votes' => $votesArr,
        ];
    }

    /**
     * @return array
     */
    public function getVoteStatsDesktopAndMobile(){
        $voter = new Voter();

        $devideDesktopCount = $voter->find()->where(['status' => Voter::STATUS_ACTIVE])->andWhere(['voted' => Voter::VOTED_YES])->andWhere(['device' => Voter::DEVICE_DESKTOP])->count();
        $devideMobileCount = $voter->find()->where(['status' => Voter::STATUS_ACTIVE])->andWhere(['voted' => Voter::VOTED_YES])->andWhere(['device' => Voter::DEVICE_MOBILE])->count();

        return [
            'devideDesktopCount' => $devideDesktopCount,
            'devideMobileCount' => $devideMobileCount,
        ];
    }

    /**
     * @param $date1
     * @param $date2
     * @return mixed
     */
    public function getDateDiff($date1, $date2){
        $dateFrom = date_create($date1);
        $dateTo = date_create($date2);
        $daysDiff = date_diff($dateFrom, $dateTo);
        return $daysDiff->days;
    }

    /**
     * @return mixed
     */
    public function actionExportReportAsPdf() {

        Yii::$app->appLog->writeLog('Bailiff dashboard PDF export');
		// TODO We need get this into separate place
        Yii::$app->db->pdo->exec('SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY", ""));');
        $category = new Category();
        $categories = $category->find()->all();

        $content = $this->renderPartial('_reportView',[
            'categories' => $categories
        ], false);

        try {

            $pdf = new Pdf();
            $pdf->content = $content;
            $pdf->render();
            return $pdf->render();
        } catch (Exception $e) {

            Yii::$app->appLog->writeLog('Error on PDF export', ['exception' => $e->getMessage() ]);
        }

    }

    /**
     * @return bool
     */
    public function actionSendVotingResultsEmail(){

        Yii::$app->appLog->writeLog('Bailiff dashboard email export');

        $this->layout = 'main-bailiff';
		// TODO We need get this into separate place
		Yii::$app->db->pdo->exec('SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY", ""));');

        $category = new Category();
        $categories = $category->find()->all();

        $mail = new Mail();

        $content = $this->renderPartial('_reportView',[
            'categories' => $categories
        ], false);

        $bailiffEmail = Yii::$app->user->identity->email;
        $bailiffName = Yii::$app->user->identity->firstName;

        $success = false;
        try{

            if($mail->sendVotingResults($bailiffEmail, $bailiffName, $content)){
                $success = true;
            } else {
                $success = false;
            }
        } catch (Exception $e){

            Yii::$app->appLog->writeLog('Send voting results exception', ['exception' => $e->getMessage(), 'emails' => $bailiffEmail, 'bailiff name' => $bailiffName]);
            $success = false;
        }

        $sucMsg = Yii::t('app', 'Send voting results success');
        $errMsg = Yii::t('app', 'Send voting results failed');

        if($success){

            Yii::$app->appLog->writeLog($sucMsg, ['emails' => $bailiffEmail, 'bailiff name' => $bailiffName]);
            Yii::$app->session->setFlash('success', $sucMsg);
        } else {

            Yii::$app->session->setFlash('error', $errMsg);
            Yii::$app->appLog->writeLog('Send voting results failed', ['emails' => $bailiffEmail, 'bailiff name' => $bailiffName]);
        }

        return $this->redirect(['bailiff-dashboard']);
    }

}