<?php
namespace kakalika\modules\updates;

class Updates extends \ntentan\models\Model
{
    public $belongsTo = array(
        'user',
        array('user', 'as' => 'assignee')
    );
    
    public $behaviours = array(
        'timestampable'
    );
    
    public $updateIssue = true;
    
    public function preSaveCallback() {
        $this->user_id = $_SESSION['user']['id'];
    }
    
    public function postSaveCallback($id) 
    {
        if($this->updateIssue)
        {
            $issue = \kakalika\modules\issues\Issues::getJustFirstWithId($this->issue_id);
            $issue->updated = date('Y-m-d h:i:s');
            $issue->updater = $_SESSION['user']['id'];
            $issue->update();
        }
    }
}