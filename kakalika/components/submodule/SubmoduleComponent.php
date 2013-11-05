<?php
namespace kakalika\components\submodule;

use ntentan\models\Model;
use ntentan\Ntentan;

class SubmoduleComponent extends \ntentan\controllers\components\Component
{
    private $modules = array();
    
    public function __construct($modules)
    {
        $this->modules = $modules;
    }
    
    public function submodule($module, $id, $command = null, $subId = null)
    {        
        $project = \kakalika\modules\projects\Projects::getJustFirstWithId($id);
        $model = Model::load($this->modules[$module]['model']);
        $this->view->template = "projects_submodule.tpl.php";
        
        $this->set('module', $module);
        $this->set('id', $id);
        $this->set('sub_section', $this->modules[$module]['title']);
        $this->set('sub_section_path', "admin/projects/$module/$id");
        $this->set('sub_section_menu', 
            array(
                array(
                    'label' => "Add a new {$this->modules[$module]['item']}",
                    'url' => Ntentan::getUrl("admin/projects/$module/$id/add"),
                    'id' => "menu-item-projects-$module-add"
                )
            )
        ); 
                    
        switch($command)
        {
        case 'edit':
            $subItem = $model->getFirstWithId($subId);
            
            if(count($_POST) > 0)
            {
                $subItem->setData($_POST);
                $subItem->project_id = $id;
                if($subItem->update())
                {
                    Ntentan::redirect(Ntentan::getUrl("admin/projects/$module/$id"));
                }
                else
                {
                    $this->set('errors', $subItem->invalidFields);
                }
            }
            
            $this->set('title', "Edit the {$subItem} {$this->modules[$module]['item']}");
            $this->set('data', $subItem->toArray());
            $this->view->template = "projects_submodule_edit.tpl.php";
            break;
        case 'delete':
            $item = $model->getFirstWithId($subId);
            $this->view->template = 'delete.tpl.php';

            if($_GET['confirm'] == 'yes')
            {
                $item->delete();
                Ntentan::redirect(Ntentan::getUrl("admin/projects/$module/$id"));
            }

            $this->set(
                array(
                    'item_type' => $this->modules[$module]['item'],
                    'item_name' => $item,
                )
            );    
            $this->set('title', "Delete a {$this->modules[$module]['item']} from the $project project");
            break;
        
        case 'add':
            $formVars = array();
            if(count($_POST) > 0)
            {
                $newItem = $model->getNew();
                $newItem->setData($_POST);
                $newItem->project_id = $id;
                if($newItem->save())
                {
                    Ntentan::redirect(Ntentan::getUrl("admin/projects/$module/$id"));
                }
                else
                {
                    $formVars['errors'] = $newItem->invalidFields;
                    $formVars['data'] = $_POST;
                }
            }
            
            $this->view->template = "projects_submodule_add.tpl.php";
            
            if(is_object($this->modules[$module]['get_form_vars']))
            {
                 $formVars = array_merge($formVars, $this->modules[$module]['get_form_vars']());
            }
            
            $this->set('form_vars', $formVars);
                
            $this->set('title', "Add a new {$this->modules[$module]['item']} to the $project project");
            
            break;
        
        default:

            $items = $model->getAllWithProjectId(
                $id,
                array(
                    'fields' => $this->modules[$module]['fields'],
                    'sort' => 'id desc'
                )
            );
            
            if(is_object($this->modules[$module]['filter']))
            {
                $items = $this->modules[$module]['filter']($items);
            }
            
            $this->set('disable_edit', $this->modules[$module]['disable_edit']);
            $this->set('project', $project->name);            
            $this->set('title', ucfirst($this->modules[$module]['items']) . " of the $project project");
            $this->set('items', $items);    
            $this->set('item_type', $this->modules[$module]['item']);
            $this->set('id', $id);
        }                    
    }
}

