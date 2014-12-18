<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: admincp.controller.php
 * Date: 17/11/14
 * Time: 19:26
 */

class admincpController extends BaseController {
    public $userModel=null;
    public function __construct(){
        parent::__construct();
        $this->userModel=$this->loadModel('user');
        $this->userModel->login('aashwin','nontoxic');
        $this->userModel->loginSession();
        if(!$this->userModel->isLoggedIn()) {
            $this->loadView('ErrorPages', 'NoPermission');
            return;
        }
        if(!$this->userModel->isLoggedInAdmin()){
            $this->loadView('ErrorPages', 'NoPermission');
            return;
        }
        $this->title('Admin Panel');
        $this->setTemplateLayout('admin_default');

    }

    /**
        -- Main Pages
     **/
    public function index(){
        $this->setTemplateLayout('admin_default');
        $this->loadView('Admin', 'index');
        $this->addViewArray('page', 'index');

    }
    public function sports($page=1,$perPage=10,$order='navOrder', $by='ASC',$ajax='no'){

        if($page<0){
            $page=1;
        }
        if($ajax=='ajax'){
            $this->setTemplateLayout('');
            $this->addViewArray('ajax', true);

        }else{
            $this->addViewArray('page', 'sports');
            $this->addViewArray('ajax', false);
            $this->addViewArray('currentPage', $page);
            $this->addViewArray('order', $order);
            $this->addViewArray('by', $by);
        }
        $this->addViewArray('perPage', $perPage);
        $start=($page-1)*$perPage;
        $this->addViewArray('SportsModel', $this->loadModel('sports'));

        $this->addViewArray('GetSports', $this->getViewArray('SportsModel')->getSports($start, $perPage, $order, $by));

        $this->loadView('Admin', 'sports_main');

    }
    public function teams($page=1,$perPage=10,$order='teamId', $by='ASC',$ajax='no'){

        if($page<0){
            $page=1;
        }
        if($ajax=='ajax'){
            $this->setTemplateLayout('');
            $this->addViewArray('ajax', true);

        }else{
            $this->addViewArray('page', 'teams');
            $this->addViewArray('ajax', false);
            $this->addViewArray('currentPage', $page);
            $this->addViewArray('order', $order);
            $this->addViewArray('by', $by);
        }
        $this->addViewArray('perPage', $perPage);
        $start=($page-1)*$perPage;
        $this->addViewArray('TeamsModel', $this->loadModel('teams'));
        $this->addViewArray('SportsModel', $this->loadModel('sports'));

        $this->addViewArray('GetTeams', $this->getViewArray('TeamsModel')->getTeams($start, $perPage, $order, $by));

        $this->loadView('Admin', 'teams_main');

    }
    public function tournaments($page=1,$perPage=10,$order='tournamentName', $by='ASC', $ajax='no'){
        if($page<0){
            $page=1;
        }
        if($ajax=='ajax'){
            $this->setTemplateLayout('');
            $this->addViewArray('ajax', true);

        }else{
            $this->addViewArray('page', 'tournaments');
            $this->addViewArray('ajax', false);
            $this->addViewArray('currentPage', $page);
            $this->addViewArray('order', $order);
            $this->addViewArray('by', $by);
        }
        $this->addViewArray('perPage', $perPage);
        $start=($page-1)*$perPage;
        $this->addViewArray('TournamentsModel', $this->loadModel('tournaments'));

        $this->addViewArray('GetTournaments', $this->getViewArray('TournamentsModel')->getTournaments($start, $perPage, $order, $by));

        $this->loadView('Admin', 'tournaments_main');

    }
    public function events($page=1,$perPage=10,$order='eventName', $by='ASC', $ajax='no'){
        if($page<0){
            $page=1;
        }
        if($ajax=='ajax'){
            $this->setTemplateLayout('');
            $this->addViewArray('ajax', true);

        }else{
            $this->addViewArray('page', 'events');
            $this->addViewArray('ajax', false);
            $this->addViewArray('currentPage', $page);
            $this->addViewArray('order', $order);
            $this->addViewArray('by', $by);
        }
        $this->addViewArray('perPage', $perPage);
        $start=($page-1)*$perPage;
        $this->addViewArray('EventsModel', $this->loadModel('events'));
        $this->addViewArray('GetEvents', $this->getViewArray('EventsModel')->getEvents($start, $perPage, $order, $by));

        $this->loadView('Admin', 'events_main');

    }

    /**
    -- Delete Pages
     **/
    public function deleteSport($id, $ajax='no'){
        $sportsModel=$this->loadModel('sports');
        if($sportsModel->delete($id)!==false){
            $message='#ID=' . $id . ' sport has been deleted from the sports database.';
            $error=false;
        }else{
            $message='Cannot delete sport.';
            $error=true;
        }
        if($ajax=='no'){
            $_SESSION[($error?'Error':'Success').'Messages'][]=$message;
            header('Location: ' . Functions::pageLink($this->getController(), 'sports'));
        }else{
            echo json_encode(array('return'=>($error?'error':'success'),'msg'=>$message));
        }


        exit;
    }
    public function deleteTournament($id, $ajax='no'){
        $tournamentsModel=$this->loadModel('tournaments');
        if($tournamentsModel->delete($id)!==false){
            $message='#ID=' . $id . ' tournament has been deleted from the tournaments database.';
            $error=false;
        }else{
            $message='Cannot delete tournament.';
            $error=true;
        }
        if($ajax=='no'){
            $_SESSION[($error?'Error':'Success').'Messages'][]=$message;
            header('Location: ' . Functions::pageLink($this->getController(), 'tournaments'));
        }else{
            echo json_encode(array('return'=>($error?'error':'success'),'msg'=>$message));
        }


        exit;
    }
    public function deleteEvent($id, $ajax='no'){
        $eventsModel=$this->loadModel('events');
        if($eventsModel->delete($id)!==false){
            $message='#ID=' . $id . ' event has been deleted from the events database.';
            $error=false;
        }else{
            $message='Cannot delete event.';
            $error=true;
        }
        if($ajax=='no'){
            $_SESSION[($error?'Error':'Success').'Messages'][]=$message;
            header('Location: ' . Functions::pageLink($this->getController(), 'events'));
        }else{
            echo json_encode(array('return'=>($error?'error':'success'),'msg'=>$message));
        }


        exit;
    }
    public function deleteTeam($id, $ajax='no'){
        $model=$this->loadModel('teams');
        if($model->delete($id)!==false){
            $message='#ID=' . $id . ' team has been deleted from the teams database.';
            $error=false;
        }else{
            $message='Cannot delete team.';
            $error=true;
        }
        if($ajax=='no'){
            $_SESSION[($error?'Error':'Success').'Messages'][]=$message;
            header('Location: ' . Functions::pageLink($this->getController(), 'teams'));
        }else{
            echo json_encode(array('return'=>($error?'error':'success'),'msg'=>$message));
        }


        exit;
    }

    /**
    -- Add Pages
     **/
    public function addSport(){
        $sportsModel=$this->loadModel('sports');
        $this->addViewArray('page', 'sports');

        if(isset($_POST['sportName'])){
            $sportsModel->add($_POST['sportName'], $_POST['navOrder']);
            if($sportsModel->numErrors()>0){
                $_SESSION['ErrorMessages']=$sportsModel->getErrors();
                $_SESSION['FormData']=$_POST;
                header('Location: '.Functions::pageLink($this->getController(), 'addsport'));
                exit;
            }
            $_SESSION['FormData']=array();
            $_SESSION['SuccessMessages'][]=$_POST['sportName'].' has been added to the sports database.';
            header('Location: '.Functions::pageLink($this->getController(), 'sports'));
            exit;
        }
        $this->addViewArray('NextNavOrder', $sportsModel->nextNavOrder());
        $this->loadView('Admin', 'sports_add');
    }
    public function addTeam(){
        $teamsModel=$this->loadModel('teams');
        $this->addViewArray('page', 'teams');
        $this->addViewArray('SportsModel', $this->loadModel('sports'));

        if(isset($_POST['teamName'])){

            if(($_FILES['teamFlag']['error']==0)) {
                $File = new FileUpload(TEAM_FLAG_DIR);
                $File->setMaxSize(0.1); //Max 100kb
                $File->setBasicType('image'); //Images only.
                $File->setMaxDimension(240, 240);
                $File->setOptimize(true);
                $File->keepOriginal(true, TEAM_FLAG_DIR.'originals/');
                $File->setFile($_FILES['teamFlag']);

                $uploadFlag = $File->uploadFile();
            }else{
                $uploadFlag=array('error'=>false, 'filename'=>'');
            }
            if($uploadFlag['error']===false){
                $teamsModel->add($_POST['teamName'], $uploadFlag['filename']);
            }else{
                $teamsModel->addErrors('Upload Failed. Response: '.$uploadFlag['type']);
            }
            if($teamsModel->numErrors()>0){
                $_SESSION['ErrorMessages']=$teamsModel->getErrors();

                $_SESSION['FormData']=$_POST;
                header('Location: '.Functions::pageLink($this->getController(), $this->getAction()));
                exit;
            }
            $_SESSION['FormData']=array();
            $_SESSION['SuccessMessages'][]=$_POST['teamName'].' has been added to the teams database.';
            header('Location: '.Functions::pageLink($this->getController(), 'teams'));
            exit;
        }
        $this->loadView('Admin', 'teams_add');
    }
    public function addTournament(){
        $tournamentsModel=$this->loadModel('tournaments');
        $this->addViewArray('page', 'tournaments');

        if(isset($_POST['tournamentName'])){
            $tournamentsModel->add($_POST['tournamentName'],$_POST['tournamentSport'],$_POST['tournamentStart'],$_POST['tournamentEnd']);
            if($tournamentsModel->numErrors()>0){
                $_SESSION['ErrorMessages']=$tournamentsModel->getErrors();
                $_SESSION['FormData']=$_POST;
                header('Location: '.Functions::pageLink($this->getController(), 'addtournament'));
                exit;
            }
            $_SESSION['FormData']=array();
            $_SESSION['SuccessMessages'][]=$_POST['tournamentName'].' has been added to the tournaments database.';
            header('Location: '.Functions::pageLink($this->getController(), 'tournaments'));
            exit;
        }
        $this->addViewArray('SportsModel', $this->loadModel('sports'));
        $this->loadView('Admin', 'tournaments_add');
    }
    public function addEvent(){
        $eventsModel=$this->loadModel('events');
        $this->addViewArray('page', 'events');
        $this->addViewArray('AddressModel', $this->loadModel('address'));
        $this->addViewArray('TournamentsModel', $this->loadModel('tournaments'));
        $this->addViewArray('CountriesModel', $this->loadModel('countries'));
        $this->addViewArray('TeamsModel', $this->loadModel('teams'));

        if(isset($_POST['eventName'])){
            $addressId=intval($_POST['addressId']);
            if($addressId===0){
                if($this->getViewArray('AddressModel')->add($_POST['addressLine1'],$_POST['addressLine2'],$_POST['postCode'],$_POST['countryId'])){
                    $addressId=$this->getDb()->lastInsertId();
                }
            }
            $eventsModel->add($_POST['eventName'],$_POST['tournamentId'],$_POST['team1'],$_POST['team2'],$addressId,$_POST['startTime']);
            if($eventsModel->numErrors()>0){
                $_SESSION['ErrorMessages']=$eventsModel->getErrors();
                $_SESSION['FormData']=$_POST;
                header('Location: '.Functions::pageLink($this->getController(), $this->getAction()));
                exit;
            }
            $_SESSION['FormData']=array();
            $_SESSION['SuccessMessages'][]=$eventsModel->buildName($_POST['eventName'], intval($_POST['team1']),intval($_POST['team2'])).' has been added to the events database.';
            header('Location: '.Functions::pageLink($this->getController(), 'events'));
            exit;
        }

        $this->loadView('Admin', 'events_add');
    }
    /**
    -- Edit Pages
     **/
    public function editSport($id){
        $sportsModel=$this->loadModel('sports');
        $this->addViewArray('page', 'sports');

        if(isset($_POST['sportName'])){
            $sportsModel->update($id, $_POST['sportName'],$_POST['navOrder']);
            if($sportsModel->numErrors()>0){
                $_SESSION['ErrorMessages']=$sportsModel->getErrors();
                header('Location: '.Functions::pageLink($this->getController(), 'editSport', $id));
                exit;
            }
            $_SESSION['FormData']=array();
            $_SESSION['SuccessMessages'][]=$_POST['sportName'].'[#ID='.$id.'] has been updated to the sports database.';
            header('Location: '.Functions::pageLink($this->getController(), 'sports'));
            exit;
        }
        $this->addViewArray('SportData', $sportsModel->getSport($id));
        if($this->getViewArray('SportData')===false)
        {
            $_SESSION['ErrorMessages'][]='Sport does not exist!';
            header('Location: '.Functions::pageLink($this->getController(), 'sports'));
            exit;
        }
        $this->loadView('Admin', 'sports_edit');
    }
    public function editTournament($id){
        $tournamentsModel=$this->loadModel('tournaments');
        $this->addViewArray('page', 'tournaments');
        $this->addViewArray('SportsModel', $this->loadModel('sports'));

        if(isset($_POST['tournamentName'])){
            $tournamentsModel->update($id, $_POST['tournamentName'],$_POST['tournamentSport'],$_POST['tournamentStart'],$_POST['tournamentEnd']);
            if($tournamentsModel->numErrors()>0){
                $_SESSION['ErrorMessages']=$tournamentsModel->getErrors();
                header('Location: '.Functions::pageLink($this->getController(), 'editTournament', $id));
                exit;
            }
            $_SESSION['FormData']=array();
            $_SESSION['SuccessMessages'][]=$_POST['tournamentName'].'[#ID='.$id.'] has been updated to the tournaments database.';
            header('Location: '.Functions::pageLink($this->getController(), 'tournaments'));
            exit;
        }
        $this->addViewArray('TournamentData', $tournamentsModel->getTournament($id));
        if($this->getViewArray('TournamentData')===false)
        {
            $_SESSION['ErrorMessages'][]='Tournament does not exist!';
            header('Location: '.Functions::pageLink($this->getController(), 'tournaments'));
            exit;
        }
        $this->loadView('Admin', 'tournaments_edit');
    }
    public function editEvent($id){
        $this->addViewArray('page', 'events');
        $this->addViewArray('EventsModel', $this->loadModel('events'));
        $this->addViewArray('AddressModel', $this->loadModel('address'));
        $this->addViewArray('TournamentsModel', $this->loadModel('tournaments'));
        $this->addViewArray('CountriesModel', $this->loadModel('countries'));
        $this->addViewArray('TeamsModel', $this->loadModel('teams'));

        if(isset($_POST['eventName'])){
            $addressId=intval($_POST['addressId']);
            if($addressId===0){
                if($this->getViewArray('AddressModel')->add($_POST['addressLine1'],$_POST['addressLine2'],$_POST['postCode'],$_POST['countryId'])){
                    $addressId=$this->getDb()->lastInsertId();
                }
            }
            $this->getViewArray('EventsModel')->update($id, $_POST['eventName'],$_POST['tournamentId'],$_POST['team1'],$_POST['team2'],$addressId,$_POST['startTime']);
            if($this->getViewArray('EventsModel')->numErrors()>0){
                $_SESSION['ErrorMessages']= $this->getViewArray('EventsModel')->getErrors();
                header('Location: '.Functions::pageLink($this->getController(), 'editEvent', $id));
                exit;
            }
            $_SESSION['FormData']=array();
            $_SESSION['SuccessMessages'][]=$this->getViewArray('EventsModel')->buildName($_POST['eventName'], intval($_POST['team1']),intval($_POST['team2'])).'[#ID='.$id.'] has been updated to the events database.';
            header('Location: '.Functions::pageLink($this->getController(), 'events'));
            exit;
        }
        $this->addViewArray('EventData', $this->getViewArray('EventsModel')->getEvent($id));
        if($this->getViewArray('EventData')===false)
        {
            $_SESSION['ErrorMessages'][]='Event does not exist!';
            header('Location: '.Functions::pageLink($this->getController(), 'events'));
            exit;
        }
        $this->loadView('Admin', 'events_edit');
    }
    public function editTeam($id){
        $this->addViewArray('page', 'teams');
        $this->addViewArray('TeamsModel', $this->loadModel('teams'));

        if(isset($_POST['teamName'])){
            if($_FILES['teamFlag']['error']==0) {
                if($_POST['flagFile']!='') {
                    unlink(TEAM_FLAG_DIR . $_POST['flagFile']);
                    unlink(TEAM_FLAG_DIR . 'originals/' . $_POST['flagFile']);
                }
                $File = new FileUpload(TEAM_FLAG_DIR);
                $File->setMaxSize(0.1); //Max 100kb
                $File->setBasicType('image'); //Images only.
                $File->setMaxDimension(240, 240);
                $File->setOptimize(true);
                $File->keepOriginal(true, TEAM_FLAG_DIR.'originals/');
                $File->setFile($_FILES['teamFlag']);

                $uploadFlag = $File->uploadFile();
                if($uploadFlag['error']===false) {
                    $teamFlag=$uploadFlag['filename'];
                }else {
                    $teamFlag = '';
                }
            }elseif($_POST['removeFlag']==1){
                if($_POST['flagFile']!='') {
                    unlink(TEAM_FLAG_DIR . $_POST['flagFile']);
                    unlink(TEAM_FLAG_DIR . 'originals/' . $_POST['flagFile']);
                }
                    $teamFlag='';

            }else {
                $teamFlag=$_POST['flagFile'];
            }
            $this->getViewArray('TeamsModel')->update($id, $_POST['teamName'], $teamFlag);
            if($this->getViewArray('TeamsModel')->numErrors()>0){
                $_SESSION['ErrorMessages']= $this->getViewArray('TeamsModel')->getErrors();
                header('Location: '.Functions::pageLink($this->getController(), 'editTeam', $id));
                exit;
            }
            $_SESSION['FormData']=array();
            $_SESSION['SuccessMessages'][]=$_POST['teamName'].'[#ID='.$id.'] has been updated to the teams database.';
            header('Location: '.Functions::pageLink($this->getController(), 'teams'));
            exit;
        }
        $this->addViewArray('TeamData', $this->getViewArray('TeamsModel')->getTeam($id));
        if($this->getViewArray('TeamData')===false)
        {
            $_SESSION['ErrorMessages'][]='Team does not exist!';
            header('Location: '.Functions::pageLink($this->getController(), 'teams'));
            exit;
        }
        $this->loadView('Admin', 'teams_edit');
    }


} 