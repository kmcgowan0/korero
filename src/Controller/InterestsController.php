<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Interests Controller
 *
 * @property \App\Model\Table\InterestsTable $Interests
 *
 * @method \App\Model\Entity\Interest[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InterestsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $interests = $this->paginate($this->Interests);

        $this->set(compact('interests'));
    }

    /**
     * View method
     *
     * @param string|null $id Interest id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $interest = $this->Interests->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('interest', $interest);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function randomColour()
    {
        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $colour = $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)];
        return $colour;
    }

    public function add()
    {
        $colour = $this->randomColour();
        $interest = $this->Interests->newEntity();
        if ($this->request->is('post')) {

            $interest_data = $this->request->getData();
            $interest_data['name'] = ucwords($interest_data['name']);
            $interest_data['users']['_ids'] = [$this->Auth->user('id')];
            $interest_data['colour'] = $colour;
            $interest = $this->Interests->patchEntity($interest, $interest_data);
            if ($this->Interests->save($interest)) {
                $this->Flash->success(__('The interest has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The interest could not be saved. Please, try again.'));
        }
        $users = $this->Interests->Users->find('list', ['limit' => 200]);
        $this->set(compact('interest', 'users'));
    }

    public function search()
    {
        $term = $this->request->getQuery('term');
        $interests = $this->Interests->find()->where(['name LIKE' => '%' . $term . '%']);
        $this->set(compact('interests'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Interest id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $interest = $this->Interests->get($id, [
            'contain' => ['Users']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $interest = $this->Interests->patchEntity($interest, $this->request->getData());
            if ($this->Interests->save($interest)) {
                $this->Flash->success(__('The interest has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The interest could not be saved. Please, try again.'));
        }
        $users = $this->Interests->Users->find('list', ['limit' => 200]);
        $this->set(compact('interest', 'users'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Interest id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $interest = $this->Interests->get($id);
        if ($this->Interests->delete($interest)) {
            $this->Flash->success(__('The interest has been deleted.'));
        } else {
            $this->Flash->error(__('The interest could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


}
