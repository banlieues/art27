<?php

namespace Calendar\Controllers;

use App\Controllers\BaseController;

use CodeIgniter\HTTP\RequestInterface;
use Calendar\Models\CalendarModel;

class Calendar extends BaseController
{
      protected $context;
      public function __construct() 
      // --> fonction php qui charge ces éléments de façon automatique
      // dans une fonction instancié
      {
          helper(['debug','icons']);
          $this->path = "Calendar\Views\calendar";
          $this->datas['context']= $this->context;
          $this->datas['title']= "papopititle";
          $this->datas['subtitle']= 'subpapopi';
          $this->datas['titleView']= 'papopitv';
          $this->datas['icon'] = icon('home') ;
          $this->CalendarModel=new CalendarModel();
          $this->CalendarModel->init();
          $this->db= \Config\Database::connect();
          $this->builder= $this->db->table('events');   
          $this->query= $this->builder->select('*')->get();
   
          $this->events = $this->query->getResult();
          
         foreach ($this->events as $this->key => $value) 
          {
              $this->datas['data'][$this->key]['id'] = $value->id;
              $this->datas['data'][$this->key]['title'] = $value->name;
              $this->datas['data'][$this->key]['start'] = $value->start_date;
              $this->datas['data'][$this->key]['end'] = $value->end_date;
              $this->datas['data'][$this->key]['backgroundColor'] = "#00a65a";
          }
      }
      public function index() {
 

     return view($this->path,$this->datas);
    }

    public function CreateEvent()
    {
        
       if($this->request->getMethod() == 'post')
       {
           $model = new CalendarModel();
           $model -> save($_POST);

           return redirect()->to('/calendar/new')->with("success",'Votre événement a été ajouté au calendrier');
       }
        return view ('Calendar\Views\createEvent',$this->datas);
    }

    public function DeleteEvent($id=null){

    $model = new CalendarModel();
    $post = $id;
   if($post){
    $model->delete($id);
    return redirect()->to('/calendar/delete');
    }

return view('Calendar\Views\deleteEvent',$this->datas);
}

public function EditEvent($id=null)
   {
           $model = new CalendarModel();
            $post = $model->find($id);
  
             $this->datas['id']= $post['id'];
              $this->datas['name']= $post['name'];
              $this->datas['start_date']= $post['start_date'];
             $this->datas['end_date'] = $post['end_date'];
        

            if($this->request->getMethod() == 'post'){
                $model = new CalendarModel();
                $_POST['id']=$id;
                $model -> save($_POST);
                $post = $model->find($id);
                return redirect()->to('/calendar/delete');
            }
            $this->datas['post'] =  $post ;
            return view('Calendar\Views\updateEvent',$this->datas,);
;
    }
    
}



