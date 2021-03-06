<div class='issue_view'>
    <div id='title_block' class='<?= $issue['status'] ?>_title'><h4>#<?= $issue['number'] ?></h4> <h4><?= $issue['title'] ?></h4></div>
    <div class='row'>
        <div class='column grid_10_7 issue_view_content'>
            <div style='padding:15px'><?= t('issue_slug.tpl.php', array('issue' => $issue)) ?></div>
            <p id='description'><?= nl2br($issue['description']) ?></p>  
            <?php if($issue->issue_attachments->count() > 0): ?>
            <ul class="attachment-display-box">
            <?php foreach($issue->issue_attachments as $attachment): ?>
                <?php if($attachment['update_id'] != '')  continue; ?>
                <li>
                    <a href='<?= u("issues/attachment/{$attachment['id']}/{$attachment['name']}") ?>'><?= $attachment['name'] ?></a>
                    <br/><span class="small-date"><?= $helpers->filesize($attachment['size']) ?></span>
                </li>
            <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            
            <?php if($updates->count() > 0): ?>
            <h5>Comments (<?= $updates->count() ?>)</h5>
            <?php endif; ?>
            
            <?php $stripe = true; foreach($updates as $update): ?>
            <div class="update <?= $stripe ? 'striped' : '' ?>">
                <div class="issue_number">#<?= $update['number'] ?></div>
                <img src="<?= $helpers->social->gravatar($update['user']['email'])->size(54) ?>" />
                <span class="name"><?= $update['user']['firstname'] . " " . $update['user']['lastname'] ?></span>
                <div class="small-date"><?= $helpers->date($update['created'])->sentence(array('elaborate_with' => 'ago')) ?> ⚫ <?= $helpers->date($update['created'])->format('jS F, Y @ g:i a') ?></div>
                <?php
                $changes = array();
                if(isset($update['assigned_to']['id'])) 
                    $changes[] = "Assigned <span class='name'>{$update['assigned_to']}</span> to this issue";
                    
                if($update['priority'] != '')
                    $changes[] = "Set priority to <b>{$update['priority']}</b>";
                    
                if($update['kind'] != '')
                    $changes[] = "Marked issue as <b>{$update['kind']}</b>";
                    
                if($update['status'] != '')
                    $changes[] = "<b>{$update['status']}</b> this issue";
                    
                if($update['milestone_id'] != '')
                    $changes[] = "Set milestone to <b>{$update['milestone']['name']}</b>";
                    
                if($update['component_id'] != '')
                    $changes[] = "Set component as <b>{$update['component']['name']}</b>";
                    
                ?>
                
                <?php if($update['comment'] != ''): ?>
                <p>                 
                    <?= nl2br($update['comment']) ?>
                </p>
                <?php endif; ?>
                
                <?php if(count($changes) > 0): ?>
                <ul class="changes-list">
                    <?php foreach($changes as $change): ?>
                    <li><?= $change ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?> 
                
                <?php if($update->issue_attachments->count() > 0): ?>
                <ul class="attachment-display-box sub-attachment-box">
                <?php foreach($update['issue_attachments'] as $attachment): ?>
                    <li>
                        <a href='<?= u("issues/attachment/{$attachment['id']}/{$attachment['name']}") ?>'><?= $attachment['name'] ?></a>
                        <br/><span class="small-date"><?= $helpers->filesize(unescape($attachment['size'])) ?></span>
                    </li>
                <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                
                
            </div>
            <?php $stripe = !$stripe; endforeach; ?>
            <?= $helpers->form->open()->setAttribute('enctype', 'multipart/form-data') . $helpers->form->get_text_area('Comment', 'comment') ?>  
            <div class="attachment-box">
                <div id="issue-attachments"></div>
                <span id="attachment-link" class="link" onclick="kakalika.addUploadField()">Add Attachment</span>
            </div>
            <?php
                switch ($issue['status'])
                {
                    case 'OPEN':
                    case 'REOPENED':
                        echo $helpers->form->close(
                            array('value' => 'Comment', 'name' => 'action'),
                            array('value' => 'Resolve', 'name' => 'action', 'id' => 'resolve'),
                            array('value' => 'Close', 'name' => 'action', 'id' => 'close')
                        );
                        break;
                        
                    case 'CLOSED':
                        echo $helpers->form->close(
                            array('value' => 'Comment', 'name' => 'action'),
                            array('value' => 'Reopen', 'name' => 'action', 'id' => 'reopen')
                        );    
                        break;
                    
                    case 'RESOLVED':
                        echo $helpers->form->close(
                            array('value' => 'Comment', 'name' => 'action'),
                            array('value' => 'Reopen', 'name' => 'action', 'id' => 'reopen'),
                            array('value' => 'Close', 'name' => 'action', 'id' => 'close')
                        );
                        break;
                }
            ?>
        </div>
        <div class='column grid_10_3 issue_view_side'>
            <div>
                <?= $helpers->menu(
                    array(
                        array(
                            'label' => 'Edit this issue',
                            'url' => u("{$project_code}/issues/edit/{$issue['number']}"),
                            'id' => 'menu-item-issues-edit'
                        ),
                        array(
                            'label' => $watching ? 'Stop watching this issue': 'Watch this issue',
                            'url' => u("{$project_code}/issues/watch/{$issue['number']}?redirect=" . urlencode(u("{$project_code}/issues/{$issue['number']}"))),
                            'id' => 'menu-item-issues-watch'
                        )
                    )
                )->setAlias('side')
                ?>
                <?php if($issue->watchers->count() > 0): ?>
                <h5>Watchers</h5>
                <?php foreach($issue->watchers as $watcher): if(!isset($watcher['user']['email'])) continue;?>
                <img src="<?= $helpers->social->gravatar($watcher['user']['email']) ?>" title="<?= $watcher['user']['firstname'] ?> <?= $watcher['user']['lastname'] ?>" />
                <?php endforeach; ?>
                <?php endif; ?>
                
                <h5>Details</h5>
                <dl>
                    <dt>Status</dt>
                    <dd class="status_<?= $issue['status'] ?>"><?= $issue['status'] ?></dd>
                    
                    <?php if($issue['kind'] != ''): ?>
                    <dt>Kind</dt>
                    <dd class="kind_<?= $issue['kind'] ?>"><?= $issue['kind'] ?></dd>
                    <?php endif; ?>
                    
                    <?php if($issue['priority'] != ''): ?>
                    <dt>Priority</dt>
                    <dd class="priority_<?= $issue['priority'] ?>"><?= $issue['priority'] ?></dd>
                    <?php endif; ?>
                    
                    <?php if($issue['milestone']['id'] != ''): ?>
                    <dt>Milestone</dt>
                    <dd class="milestone"><?= $issue['milestone']['name'] ?><span class='small-date'> ⚫ Due <?= $helpers->date($issue['milestone']['due_date'])->sentence(array('elaborate_with' => 'ago')) ?></span></dd>
                    <?php endif; ?>
                    
                    <?php if($issue['component']['id'] != ''): ?>
                    <dt>Component</dt>
                    <dd class="component"><?= $issue['component']['name'] ?></dd>
                    <?php endif; ?>
                    
                </dl>
                <h5>People</h5>
                
                <?php 
                echo t('people_info.tpl.php', 
                    array(
                        'action' => 'Opened by',
                        'email' => $issue->opened_by['email'],
                        'firstname' => $issue->opened_by['firstname'],
                        'lastname' => $issue->opened_by['lastname'],
                        'time' => $issue['created']
                    )
                );
                
                if($issue->assigned_to['id'] != '')
                    echo t('people_info.tpl.php', 
                        array(
                            'action' => 'Assigned to',
                            'email' => $issue->assigned_to['email'],
                            'firstname' => $issue->assigned_to['firstname'],
                            'lastname' => $issue->assigned_to['lastname'],
                            'time' => $issue['assigned']
                        )
                    );
                      
                if($issue->updated_by['id'] != '')
                    echo t('people_info.tpl.php', 
                        array(
                            'action' => 'Updated by',
                            'email' => $issue->updated_by['email'],
                            'firstname' => $issue->updated_by['firstname'],
                            'lastname' => $issue->updated_by['lastname'],
                            'time' => $issue['updated']
                        )
                    )                         
                        
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/html" id="upload-field">
<?= $helpers->form->get_upload_field('', 'attachment[]') ?>    
</script>